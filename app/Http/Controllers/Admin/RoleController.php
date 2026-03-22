<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission; // FIXED: Added import
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles with filters and stats.
     */
    public function index(Request $request)
    {
        $query = Role::withCount('users');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereNested(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get paginated results
        $perPage = $request->input('per_page', 15);
        $roles = $query->latest()->paginate($perPage);

        // Load permissions count for each role
        foreach ($roles as $role) {
            $role->permissions_count = $role->permissions()->count();
        }

        // Stats for dashboard cards
        $totalRoles = Role::count();
        $activeRoles = Role::where('status', 'active')->count();
        $totalPermissions = Permission::count();

        return view('admin.roles.index', compact(
            'roles',
            'totalRoles',
            'activeRoles',
            'totalPermissions'
        ));
    }

    /**
     * Show form for creating new role.
     */
    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('name')->get();
        
        // Group permissions by their group for better display
        $groupedPermissions = $permissions->groupBy('group');

        return view('admin.roles.create', compact('permissions', 'groupedPermissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create the role
        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'guard_name' => 'web',
            'status' => $request->status
        ]);

        // Sync permissions if any were selected
        if ($request->has('permissions') && is_array($request->permissions)) {
            $role->permissions()->sync($request->permissions);
        }

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_role',
            'description' => "Created role: {$role->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show form for editing role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('group')->orderBy('name')->get();
        
        // Group permissions by their group for better display
        $groupedPermissions = $permissions->groupBy('group');
        
        // Get current role permission IDs
        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update the role
        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status
        ]);

        // Sync permissions
        $role->permissions()->sync($request->permissions ?? []);

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_role',
            'description' => "Updated role: {$role->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Request $request, Role $role)
    {
        // Prevent deletion of default system roles
        if (in_array($role->slug, ['admin', 'supervisor', 'cashier'])) {
            return back()->with('error', 'Cannot delete default system roles.');
        }

        // Check if role has users assigned
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users.');
        }

        $roleName = $role->name;
        
        // Detach all permissions first
        $role->permissions()->detach();
        
        // Delete the role
        $role->delete();

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_role',
            'description' => "Deleted role: {$roleName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Toggle role status (active/inactive).
     */
    public function toggleStatus(Request $request, Role $role)
    {
        // Prevent toggling default admin role
        if ($role->slug === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change admin role status.'
            ], 400);
        }

        $role->status = $role->status === 'active' ? 'inactive' : 'active';
        $role->save();

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'toggle_role_status',
            'description' => "Changed role status to {$role->status} for: {$role->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role status updated successfully.',
            'new_status' => $role->status
        ]);
    }

    /**
     * Get permissions for a role (API endpoint).
     */
    public function getPermissions(Role $role)
    {
        $permissions = $role->permissions()->pluck('permissions.id');
        
        return response()->json([
            'success' => true,
            'permissions' => $permissions
        ]);
    }

    /**
     * Assign role to user.
     */
    public function assignToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        // Check if user already has this role
        if ($user->roles()->where('role_id', $role->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User already has this role.'
            ], 400);
        }

        $user->roles()->attach($role->id);

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'assign_role',
            'description' => "Assigned role {$role->name} to user {$user->full_name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully.'
        ]);
    }

    /**
     * Remove role from user.
     */
    public function removeFromUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);

        // Prevent removing admin role from last admin
        if ($role->slug === 'admin' && $user->roles()->where('slug', 'admin')->exists()) {
            $adminCount = User::whereHas('roles', function (\Illuminate\Database\Eloquent\Builder $q) {
                $q->where('slug', 'admin');
            })->count();
            
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove the last admin role.'
                ], 400);
            }
        }

        $user->roles()->detach($role->id);

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'remove_role',
            'description' => "Removed role {$role->name} from user {$user->full_name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully.'
        ]);
    }
}
