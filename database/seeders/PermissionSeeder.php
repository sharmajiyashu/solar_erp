<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsByModule = config('role_permissions');

        foreach ($permissionsByModule as $module => $permissions) {
            foreach ($permissions as $permission) {
                $permissionName = $module . ' ' . $permission;
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // Create Super Admin role and assign all permissions
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->syncPermissions(Permission::all());

        // Assign basic permissions to all roles
        $allRoles = Role::all();
        foreach ($allRoles as $r) {
            $r->givePermissionTo('dashboard view');
            $r->givePermissionTo('enquiries create');
            $r->givePermissionTo('leads view');
            $r->givePermissionTo('leads create');
            $r->givePermissionTo('site_visits schedule');
            
            // Granular Stage Permissions (View + Create)
            $r->givePermissionTo('quotations view');
            $r->givePermissionTo('quotations create');
            
            $r->givePermissionTo('document_management view');
            $r->givePermissionTo('document_management create');
            
            $r->givePermissionTo('backend_management view');
            $r->givePermissionTo('backend_management create');
            
            $r->givePermissionTo('procurement_management view');
            $r->givePermissionTo('procurement_management create');
            
            $r->givePermissionTo('installation_management view');
            $r->givePermissionTo('installation_management create');
            
            $r->givePermissionTo('verification_management view');
            $r->givePermissionTo('verification_management create');
            
            $r->givePermissionTo('project_completion view');
            $r->givePermissionTo('project_completion create');
        }
    }
}
