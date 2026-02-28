<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin_name = 'Super-Admin';
        $superAdminRole = Role::updateOrCreate(['name' => $super_admin_name],['name' => $super_admin_name]); //as super-admin

        $permissions = config('role_permissions');
        foreach($permissions as $key => $val){
            foreach($val as $action_name){
                $permission_name = $key.' '.$action_name;
                Permission::updateOrCreate(['name' => $permission_name],['name' => $permission_name]);
            }
        }

        $allPermissionNames = Permission::pluck('name')->toArray();
        $superAdminRole->givePermissionTo($allPermissionNames);

        $superAdminUser = User::where('email','admin@gmail.com')->first();

        if(!$superAdminUser){
            $superAdminUser = User::firstOrCreate([
                'email' => 'admin@gmail.com',
            ], [
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'role' => User::$admin,
                'password' => Hash::make ('admin@123'),
            ]);
        }
        $superAdminUser->assignRole($superAdminRole);

    }
}
