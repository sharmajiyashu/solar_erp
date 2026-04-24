<?php

/**
 * Solar service module: flat permission names (Spatie) + optional role presets.
 * Seeded via UserRolePermissionSeeder; use middleware: permission:service_assign, etc.
 */
return [

    'permissions' => [
        'service_assign' => 'View pending slots and assign them to admin users',
        'service_management' => 'Complete visits with verification codes and manage service flow',
        'ticket_management' => 'View and reply to customer tickets',
    ],

    /**
     * Default permissions attached when creating these roles (seeder only; Super-Admin still gets all).
     */
    'role_permission_defaults' => [
        'Service Coordinator' => [
            'service_assign',
            'service_management',
        ],
        'Support Agent' => [
            'ticket_management',
        ],
    ],
];
