<?php

return [

    'enquiries' => [
        'create',
        'view',
        'get-own',
        'get-all',
        'edit',
        'delete',
        'follow_up',
        'update_status',
        'convert_to_lead',
        'mark_to_close',
        'close'
    ],

    'leads' => [
        'create', 'get-own', 'get-all', 'view', 'edit', 'delete', 'move-stage' 
    ],

    'site_visits' => [
        'schedule',
        'view',
        'complete'
    ],

    'quotations' => [
        'create',
        'view',
        'edit',
        'approve',
        'reject'
    ],

    'document_management' => [
        'upload',
        'view',
        'verify'
    ],

    'backend_management' => [
        'approve',
        'reject'
    ],

    'materials' => [
        'reserve',
        'dispatch',
        'view'
    ],

    'technicians' => [
        'assign',
        'view',
        'complete_installation'
    ],

    'verification' => [
        'view', 'verify_project'
    ],

    'project_completion' => [
        'complete_project',
        'view'
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
