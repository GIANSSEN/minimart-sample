<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = config('permissions.roles', []);
        $permissions = config('permissions.permissions', []);
        $rolePermissions = config('permissions.role_permissions', []);

        foreach ($roles as $slug => $roleData) {
            Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'] ?? null,
                    'guard_name' => 'web',
                    'status' => $roleData['status'] ?? 'active',
                ]
            );
        }

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['slug' => $permissionData['slug']],
                [
                    'name' => $permissionData['name'],
                    'group' => $permissionData['group'] ?? null,
                    'description' => $permissionData['description'] ?? null,
                    'guard_name' => 'web',
                ]
            );
        }

        $allPermissionIds = Permission::pluck('id', 'slug');

        foreach ($rolePermissions as $roleSlug => $permissionSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            if (!$role) {
                continue;
            }

            if ($permissionSlugs === '*') {
                $role->permissions()->sync($allPermissionIds->values()->all());
                continue;
            }

            $permissionIds = collect($permissionSlugs)
                ->map(fn (string $slug) => $allPermissionIds->get($slug))
                ->filter()
                ->values()
                ->all();

            $role->permissions()->sync($permissionIds);
        }

        // Keep legacy users aligned: sync role_user pivot from users.role.
        $users = User::query()->get();
        foreach ($users as $user) {
            if (!$user->role) {
                continue;
            }

            $role = Role::where('slug', $user->role)->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }
    }
}
