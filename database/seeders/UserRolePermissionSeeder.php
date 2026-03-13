<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserRolePermissionSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $super_admin_role_name = 'Super-Admin';

        // Create or update role
        $superAdminRole = Role::firstOrCreate([
            'name' => $super_admin_role_name,
            'guard_name' => 'web'
        ]);

        $permissionsByModule = config('role_permissions');

        foreach ($permissionsByModule as $module => $actions) {
            foreach ($actions as $action) {
                $permission_name = $module . ' ' . $action;
                // Use firstOrCreate to ensure it's in the DB
                Permission::firstOrCreate(['name' => $permission_name, 'guard_name' => 'web']);
            }
        }

        // Clear cache again before syncing
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Assign all permissions to Super-Admin
        $allPermissionNames = Permission::where('guard_name', 'web')->pluck('name')->toArray();
        $superAdminRole->syncPermissions($allPermissionNames);

        // Create super admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'role' => User::$admin,
                'password' => Hash::make('admin@123'),
            ]
        );

        // Assign role
        $superAdminUser->assignRole($superAdminRole);
    }
}
