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

        $super_admin_name = 'Super-Admin';

        // Create or update role
        $superAdminRole = Role::firstOrCreate([
            'name' => $super_admin_name,
            'guard_name' => 'web'
        ]);

        $permissions = config('role_permissions');

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {

                $permission_name = $module . ' ' . $action;

                Permission::findOrCreate($permission_name, 'web');
            }
        }

        // get all permissions
        $allPermissionNames = Permission::pluck('name')->toArray();

        // assign permissions to role
        $superAdminRole->syncPermissions($allPermissionNames);

        // create super admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'role' => User::$admin,
                'password' => Hash::make('admin@123'),
            ]
        );

        // assign role
        $superAdminUser->assignRole($superAdminRole);
    }
}
