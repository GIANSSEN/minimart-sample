<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users with filters and stats.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereNested(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Apply approval_status filter
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Get paginated results with per_page (default 15)
        $perPage = $request->input('per_page', 15);
        $users = $query->latest()->paginate($perPage);

        // Calculate stats for dashboard cards
        $totalUsers = User::count();
        $activeCount = User::where('status', 'active')->count();
        $pendingCount = User::where('status', 'pending')->count();
        $inactiveCount = User::where('status', 'inactive')->count();

        // load available roles for dropdowns
        $roles = Role::active()->orderBy('name')->get();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeCount',
            'pendingCount',
            'inactiveCount',
            'roles'
        ));
    }

    /**
     * Show form for creating new user.
     */
    public function create()
    {
        $roles = Role::active()->orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        // load roles so we can validate against them
        $availableRoleSlugs = Role::active()->pluck('slug')->toArray();

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|unique:users',
            'username' => 'required|string|unique:users',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in($availableRoleSlugs)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,pending',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'employee_id' => $request->employee_id,
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
            // If status is 'pending', set approval_status accordingly
            'approval_status' => 'approved',
            'created_by' => Auth::id(),
        ]);

        $user->syncRoles([$request->role]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_user',
            'description' => "Created user: {$user->full_name}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show form for editing user.
     */
    public function edit(User $user)
    {
        $roles = Role::active()->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // load roles so we can validate against them
        $availableRoleSlugs = Role::active()->pluck('slug')->toArray();

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|unique:users,employee_id,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => ['required', Rule::in($availableRoleSlugs)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,pending',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'employee_id' => $request->employee_id,
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ];

        // Keep existing approval_status
        // $data['approval_status'] is not changed here

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_user',
            'description' => "Updated user: {$user->full_name}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->full_name;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_user',
            'description' => "Deleted user: {$userName}",
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $ids = $request->ids;
        $currentUserId = Auth::id();

        // Remove current user from the list to prevent self-deletion
        $filteredIds = array_filter($ids, fn($id) => $id != $currentUserId);

        if (empty($filteredIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid users to delete (cannot delete your own account).'
            ], 400);
        }

        $count = count($filteredIds);
        User::whereIn('id', $filteredIds)->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'bulk_delete_users',
            'description' => "Bulk deleted {$count} users",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$count} users deleted successfully."
        ]);
    }

    /**
     * Get stats partial (for AJAX refresh).
     */
    public function stats()
    {
        $totalUsers = User::count();
        $activeCount = User::where('status', 'active')->count();
        $pendingCount = User::where('status', 'pending')->count();
        $inactiveCount = User::where('status', 'inactive')->count();

        return view('admin.users.partials.stats', compact(
            'totalUsers',
            'activeCount',
            'pendingCount',
            'inactiveCount'
        ));
    }

    /**
     * Bulk approve users.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $count = count($request->ids);
        User::whereIn('id', $request->ids)->update([
            'approval_status' => 'approved',
            'status' => 'active'
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'bulk_approve_users',
            'description' => "Bulk approved {$count} users",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$count} users approved successfully."
        ]);
    }

    /**
     * Display pending approvals (users with approval_status = pending).
     */
    public function pending()
    {
        $pendingUsers = User::where('approval_status', 'pending')
            ->latest()
            ->paginate(15);
        
        return view('admin.users.pending', compact('pendingUsers'));
    }

    /**
     * Approve a pending user.
     */
    public function approve(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->approval_status = 'approved';
        $user->status = 'active'; // Also activate account
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'approve_user',
            'description' => "Approved user: {$user->full_name}",
            'ip_address' => $request->ip()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User approved successfully.'
            ]);
        }

        return redirect()->route('admin.users.pending')
            ->with('success', 'User approved successfully.');
    }

    /**
     * Reject a pending user.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $user = User::findOrFail($id);
        $user->approval_status = 'rejected';
        $user->status = 'inactive'; // Deactivate account
        $user->rejection_reason = $request->reason;
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'reject_user',
            'description' => "Rejected user: {$user->full_name}. Reason: {$request->reason}",
            'ip_address' => $request->ip()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User rejected successfully.'
            ]);
        }

        return redirect()->route('admin.users.pending')
            ->with('success', 'User rejected successfully.');
    }

    /**
     * Toggle user status (active/inactive) via AJAX.
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Cannot toggle your own status'], 400);
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'toggle_user_status',
            'description' => "Changed user status to {$user->status} for: {$user->full_name}",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'new_status' => $user->status
        ]);
    }

    /**
     * Export users to CSV format.
     */
    public function export(Request $request)
    {
        $ids = $request->query('ids');
        
        if ($ids) {
            // Export specific users
            $userIds = array_filter(explode(',', $ids));
            $users = User::whereIn('id', $userIds)
                ->select('id', 'employee_id', 'full_name', 'username', 'email', 'phone', 'role', 'status', 'created_at')
                ->get();
        } else {
            // Export all users with applied filters
            $query = User::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereNested(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }

            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $users = $query->select('id', 'employee_id', 'full_name', 'username', 'email', 'phone', 'role', 'status', 'created_at')
                ->latest()
                ->get();
        }

        $filename = 'users-export-' . now()->format('Y-m-d-His') . '.csv';

        return response()->stream(function () use ($users) {
            $handle = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($handle, ['ID', 'Employee ID', 'Full Name', 'Username', 'Email', 'Phone', 'Role', 'Status', 'Created At']);

            // CSV rows
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->employee_id,
                    $user->full_name,
                    $user->username,
                    $user->email,
                    $user->phone,
                    $user->role,
                    $user->status,
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
