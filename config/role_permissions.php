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

    'bank_documents' => [
        'upload',
        'view',
        'verify'
    ],

    'bank_approval' => [
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
        'verify_project'
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
