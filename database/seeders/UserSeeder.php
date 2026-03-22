<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'employee_id' => 'ADM001',
                'full_name' => 'Admin User',
                'email' => 'admin@minimart.com',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '09123456789',
                'address' => 'Main Office',
                'status' => 'active',
            ],
            [
                'employee_id' => 'SUP001',
                'full_name' => 'Supervisor User',
                'email' => 'supervisor@minimart.com',
                'username' => 'supervisor',
                'password' => Hash::make('password123'),
                'role' => 'supervisor',
                'phone' => '09123456785',
                'address' => 'Operations',
                'status' => 'active',
            ],
            [
                'employee_id' => 'MER001',
                'full_name' => 'Merchandiser User',
                'email' => 'merchandiser@minimart.com',
                'username' => 'merchandiser',
                'password' => Hash::make('password123'),
                'role' => 'merchandiser',
                'phone' => '09123456788',
                'address' => 'Warehouse',
                'status' => 'active',
            ],
            [
                'employee_id' => 'CSH001',
                'full_name' => 'Cashier User',
                'email' => 'cashier@minimart.com',
                'username' => 'cashier',
                'password' => Hash::make('password123'),
                'role' => 'cashier',
                'phone' => '09123456787',
                'address' => 'Store Front',
                'status' => 'active',
            ],
            [
                'employee_id' => 'CSH002',
                'full_name' => 'Maria Santos',
                'email' => 'maria@minimart.com',
                'username' => 'maria',
                'password' => Hash::make('password123'),
                'role' => 'cashier',
                'phone' => '09123456786',
                'address' => 'Quezon City',
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            $createdUser = User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );

            $role = Role::where('slug', $user['role'])->first();
            if ($role) {
                $createdUser->roles()->sync([$role->id]);
            }
        }
    }
}
