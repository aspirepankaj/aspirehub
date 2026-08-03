<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Role;
use App\Modules\Core\Authentication\Models\Permission;
use App\Modules\Core\Authentication\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create default administrator role
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'System wide access for administrators'
        ]);

        // 2. Create some sample permissions
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'description' => 'Permission to view admin dashboard'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'description' => 'Permission to edit general configuration'],
        ];

        foreach ($permissions as $perm) {
            $createdPerm = Permission::create($perm);
            
            // 3. Link permissions to the admin role
            $adminRole->permissions()->attach($createdPerm->id);
        }

        // 4. Create the administrator user
        $user = User::create([
            'name' => 'System Admin',
            'email' => 'admin@aspirehub.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // 5. Assign user to the adspv_admins table as active admin
        Admin::create([
            'user_id' => $user->id,
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);
    }
}
