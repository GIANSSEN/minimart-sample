<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon; // Add this import for proper type hinting

/**
 * @property int $id
 * @property string|null $employee_id
 * @property string $username
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property string $password
 * @property string $role
 * @property string $status
 * @property string $approval_status
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property string|null $profile_photo
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityLog> $activityLogs
 * @property-read int|null $activity_logs_count
 * @property-read User|null $approver
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $createdCategories
 * @property-read int|null $created_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $createdProducts
 * @property-read int|null $created_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Supplier> $createdSuppliers
 * @property-read int|null $created_suppliers_count
 * @property-read User|null $creator
 * @property-read mixed $approval_badge
 * @property-read \Illuminate\Support\Carbon|null $approved_at
 * @property-read mixed $avatar_url
 * @property-read mixed $initials
 * @property-read \Illuminate\Support\Carbon|null $last_login
 * @property-read mixed $role_label
 * @property-read mixed $roles_list
 * @property-read mixed $status_badge
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $stockTransactions
 * @property-read int|null $stock_transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byRole($role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User rejected()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @property string|null $rejection_reason
 * @property int|null $approved_by
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $directPermissions
 * @property-read int|null $direct_permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRejectionReason($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'employee_id', 
        'username', 
        'email', 
        'password', 
        'full_name',
        'phone', 
        'address', 
        'avatar', 
        'role', 
        'permissions',
        'status', 
        'last_login', 
        'login_attempts', 
        'created_by',
        'approval_status', 
        'approved_at', 
        'approved_by', 
        'rejection_reason'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
        'last_login' => 'datetime',
        'approved_at' => 'datetime',
        'login_attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this user.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the sales made by this user.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'cashier_id');
    }

    /**
     * Get the activity logs for this user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    /**
     * Get the stock transactions created by this user.
     */
    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class, 'created_by');
    }

    /**
     * Get the products created by this user.
     */
    public function createdProducts()
    {
        return $this->hasMany(Product::class, 'created_by');
    }

    /**
     * Get the categories created by this user.
     */
    public function createdCategories()
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    /**
     * Get the suppliers created by this user.
     */
    public function createdSuppliers()
    {
        return $this->hasMany(Supplier::class, 'created_by');
    }

    /**
     * Get the roles for this user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
                    ->withTimestamps();
    }

    /**
     * Get direct permissions attached to this user.
     */
    public function directPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withTimestamps();
    }

    // ==================== ROLE METHODS ====================

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role): bool
    {
        if (empty($role)) {
            return false;
        }

        // Check direct role field first (legacy)
        if ($this->role === $role) {
            return true;
        }

        // Check through roles relationship
        if (is_string($role)) {
            $this->loadMissing('roles');
            return $this->roles->contains('slug', $role);
        }

        $this->loadMissing('roles');
        return $this->roles->contains('id', $role->id);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole($roles): bool
    {
        if (!is_array($roles)) {
            $roles = func_get_args();
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles($roles): bool
    {
        if (!is_array($roles)) {
            $roles = func_get_args();
        }
        
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        if ($role->slug ?? null) {
            $this->role = $role->slug;
            $this->save();
        }

        return $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        
        return $this->roles()->detach($role->id);
    }

    /**
     * Sync roles for the user.
     */
    public function syncRoles($roles)
    {
        $roleIds = [];
        $primaryRoleSlug = null;

        foreach ($roles as $role) {
            if (is_string($role)) {
                /** @var \App\Models\Role|null $roleModel */
                $roleModel = Role::where('slug', $role)->first();
                if ($roleModel) {
                    $roleIds[] = $roleModel->id;
                    $primaryRoleSlug ??= $roleModel->slug;
                }
            } else {
                $roleIds[] = $role->id;
                $primaryRoleSlug ??= $role->slug ?? null;
            }
        }

        if ($primaryRoleSlug) {
            $this->role = $primaryRoleSlug;
            $this->save();
        }

        return $this->roles()->sync($roleIds);
    }

    // ==================== PERMISSION METHODS ====================

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission): bool
    {
        $permission = is_string($permission) ? trim($permission) : null;
        if (!$permission) {
            return false;
        }

        // Admin always has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Check direct permissions JSON field (legacy)
        $legacyPermissions = $this->getAttribute('permissions');
        if (is_array($legacyPermissions) && in_array($permission, $legacyPermissions, true)) {
            return true;
        }

        // Check direct permission pivot
        $this->loadMissing('directPermissions');
        if ($this->directPermissions->contains('slug', $permission)) {
            return true;
        }

        // Check through role relationships
        $this->loadMissing('roles.permissions');
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('slug', $permission)) {
                return true;
            }
        }

        // Fallback to legacy role column if no role_user records exist.
        if ($this->role) {
            static $legacyRolePermissionCache = [];

            if (!array_key_exists($this->role, $legacyRolePermissionCache)) {
                /** @var \App\Models\Role|null $legacyRole */
                $legacyRole = Role::where('slug', $this->role)
                    ->with('permissions:id,slug')
                    ->first();
                
                $legacyRolePermissionCache[$this->role] = $legacyRole
                    ?->permissions
                    ->pluck('slug')
                    ->all() ?? [];
            }

            if (in_array($permission, $legacyRolePermissionCache[$this->role], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission($permissions): bool
    {
        if (!is_array($permissions)) {
            $permissions = func_get_args();
        }
        
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions($permissions): bool
    {
        if (!is_array($permissions)) {
            $permissions = func_get_args();
        }
        
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        
        return true;
    }

    // ==================== ROLE CHECK METHODS ====================

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if user is supervisor.
     */
    public function isSupervisor()
    {
        return $this->role === 'supervisor' || $this->hasRole('supervisor');
    }

    /**
     * Check if user is cashier.
     */
    public function isCashier()
    {
        return $this->role === 'cashier' || $this->hasRole('cashier');
    }

    /**
     * Check if user is inventory clerk.
     */
    public function isInventoryClerk()
    {
        return $this->hasRole('inventory-manager') || $this->hasRole('inventory-clerk');
    }

    /**
     * Check if user can access admin panel.
     */
    public function canAccessAdmin()
    {
        return $this->isAdmin() || $this->isSupervisor() || $this->isInventoryClerk();
    }

    // ==================== APPROVAL METHODS ====================

    /**
     * Check if user is pending approval.
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if user is approved.
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if user is rejected.
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Approve the user.
     */
    public function approve($approvedBy = null)
    {
        $this->approval_status = 'approved';
        $this->status = 'active';
        $this->approved_by = $approvedBy ?: auth()->id();
        $this->save();
    }

    /**
     * Reject the user.
     */
    public function reject($reason = null, $rejectedBy = null)
    {
        $this->approval_status = 'rejected';
        $this->status = 'inactive';
        $this->rejection_reason = $reason;
        $this->approved_by = $rejectedBy ?: auth()->id();
        $this->save();
    }

    // ==================== STATUS METHODS ====================

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is inactive.
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    /**
     * Activate the user.
     */
    public function activate()
    {
        $this->status = 'active';
        $this->save();
    }

    /**
     * Deactivate the user.
     */
    public function deactivate()
    {
        $this->status = 'inactive';
        $this->save();
    }

    // ==================== SCOPES ====================

    /**
     * Scope active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope users by role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope pending approval users.
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * scope approved users.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope rejected users.
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Scope search users.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('full_name', 'LIKE', "%{$search}%")
              ->orWhere('username', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('employee_id', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }

    // ==================== ACCESSORS ====================

    /**
     * Get role label.
     */
    public function getRoleLabelAttribute()
    {
        $labels = [
            'admin' => 'Administrator',
            'supervisor' => 'Supervisor',
            'cashier' => 'Cashier',
            'inventory-manager' => 'Inventory Manager',
            'inventory-clerk' => 'Inventory Clerk',
            'viewer' => 'Viewer'
        ];
        return $labels[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="badge bg-success">Active</span>',
            'inactive' => '<span class="badge bg-secondary">Inactive</span>'
        ];
        return $badges[$this->status] ?? '<span class="badge bg-dark">Unknown</span>';
    }

    /**
     * Get approval badge HTML.
     */
    public function getApprovalBadgeAttribute()
    {
        $badges = [
            'approved' => '<span class="badge bg-success">Approved</span>',
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>'
        ];
        return $badges[$this->approval_status] ?? '<span class="badge bg-dark">Unknown</span>';
    }

    /**
     * Get avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=4e73df&color=fff&size=200';
    }

    /**
     * Get user initials.
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->full_name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    /**
     * Get roles list for display.
     */
    public function getRolesListAttribute()
    {
        return $this->roles->pluck('name')->implode(', ');
    }

    // ==================== BOOT METHOD ====================

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if (class_exists('App\Models\ActivityLog')) {
                ActivityLog::create([
                    'user_id' => auth()->id() ?? $user->id,
                    'action' => 'create_user',
                    'action_type' => 'CREATE',
                    'model_type' => 'User',
                    'model_id' => $user->id,
                    'description' => "User account created: {$user->full_name}",
                    'new_values' => json_encode($user->toArray()),
                    'ip_address' => request()->ip()
                ]);
            }
        });

        static::updated(function ($user) {
            if (class_exists('App\Models\ActivityLog')) {
                ActivityLog::create([
                    'user_id' => auth()->id() ?? $user->id,
                    'action' => 'update_user',
                    'action_type' => 'UPDATE',
                    'model_type' => 'User',
                    'model_id' => $user->id,
                    'description' => "User account updated: {$user->full_name}",
                    'old_values' => json_encode($user->getOriginal()),
                    'new_values' => json_encode($user->getChanges()),
                    'ip_address' => request()->ip()
                ]);
            }
        });

        static::deleted(function ($user) {
            if (class_exists('App\Models\ActivityLog')) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'delete_user',
                    'action_type' => 'DELETE',
                    'model_type' => 'User',
                    'model_id' => $user->id,
                    'description' => "User account deleted: {$user->full_name}",
                    'old_values' => json_encode($user->toArray()),
                    'ip_address' => request()->ip()
                ]);
            }
        });
    }

    // ==================== FIXED: Type-Hinted Methods ====================

    /**
     * Get the created at timestamp with proper type.
     */
    public function getCreatedAtAttribute($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    /**
     * Get the updated at timestamp with proper type.
     */
    public function getUpdatedAtAttribute($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    /**
     * Get the deleted at timestamp with proper type.
     */
    public function getDeletedAtAttribute($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    /**
     * Get the approved at timestamp with proper type.
     */
    public function getApprovedAtAttribute($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    /**
     * Get the last login timestamp with proper type.
     */
    public function getLastLoginAttribute($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }
}
