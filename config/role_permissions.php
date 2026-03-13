<?php

return [

    'enquiries' => [
        'create',
        'view',
        'get-all',
        'delete',
        'mark_to_close',
        'close'
    ],

    'leads' => [
        'create', 'get-own', 'get-all', 'view', 'edit', 'delete', 'move-stage' 
    ],

    'site_visits' => [
        'schedule',
        'complete'
    ],

    'quotations' => [
        'view',
        'create',
        'edit',
        'approve',
        'reject',
        'delete'
    ],

    'document_management' => [
        'view',
        'upload',
        'verify',
        'delete'
    ],

    'backend_management' => [
        'view',
        'approve',
        'reject'
    ],

    'procurement_management' => [
        'view',
        'reserve',
        'dispatch'
    ],

    'installation_management' => [
        'view',
        'assign',
        'complete_installation'
    ],

    'verification_management' => [
        'view',
        'verify_project'
    ],

    'project_completion' => [
        'view',
        'complete_project'
    ],

    'users' => [
        'create',
        'view',
        'edit',
        'delete',
        'assign_role'
    ],

    'roles_permissions' => [
        'create',
        'view',
        'edit',
        'delete',
        'assign_permissions'
    ],
];
