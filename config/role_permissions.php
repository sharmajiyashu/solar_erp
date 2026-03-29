<?php

return [

    'dashboard' => [
        'view',
        'get-all',
    ],

    'enquiries' => [
        'create',
        'view',
        'get-all',
        'delete',
        'mark_to_close',
        'close'
    ],

    'leads' => [
        'view',
        'create',
        'get-all',
        'cancel',
        'delete',
    ],

    'site_visits' => [
        'schedule',
        'edit',
        'get-all'
    ],

    'quotations' => [
        'view',
        'create',
        'get-all'
    ],

    'document_management' => [
        'view',
        'create',
    ],

    'backend_management' => [
        'view',
        'create',
    ],

    'procurement_management' => [
        'view',
        'create',
        'delete',
        'get-all'   
    ],

    'items_management' => [
        'view',
        'create',
        'delete',
        'get-all'
    ],

    'installation_management' => [
        'view',
        'create',
    ],

    'verification_management' => [
        'view',
        'create',
    ],

    'project_completion' => [
        'view',
        'create',
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

    'reports' => [
        'view',
        'export',
    ],

    'category_management' => [
        'view',
        'create',
        'edit',
        'delete',
    ],

    'product_management' => [
        'view',
        'create',
        'edit',
        'delete',
    ],
    'website_enquiries' => [
        'view',
        'delete',
        'update_status',
    ],
    'service_packages' => [
        'view',
        'create',
        'edit',
        'delete',
    ],
    'expenses' => [
        'view',
        'create',
        'edit',
        'delete',
    ],
    'wallet' => [
        'view',
        'manage',
    ],
];
