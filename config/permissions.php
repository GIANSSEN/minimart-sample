<?php

return [
    /*
    |--------------------------------------------------------------------------
    | System Roles
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'admin' => [
            'name' => 'Admin',
            'description' => 'Full system access',
            'status' => 'active',
        ],
        'supervisor' => [
            'name' => 'Supervisor',
            'description' => 'Operational management access',
            'status' => 'active',
        ],
        'cashier' => [
            'name' => 'Cashier',
            'description' => 'POS and daily sales operations',
            'status' => 'active',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Definitions
    |--------------------------------------------------------------------------
    | Keep these granular for future flexibility.
    */
    'permissions' => [
        // Dashboard
        ['slug' => 'view-dashboard', 'name' => 'View Dashboard', 'group' => 'Dashboard', 'description' => 'View dashboard metrics and widgets'],

        // User Maintenance
        ['slug' => 'manage-users', 'name' => 'Manage Users', 'group' => 'User Maintenance', 'description' => 'Full user maintenance access'],
        ['slug' => 'view-users', 'name' => 'View Users', 'group' => 'User Maintenance', 'description' => 'View user list and details'],
        ['slug' => 'create-users', 'name' => 'Create Users', 'group' => 'User Maintenance', 'description' => 'Create new users'],
        ['slug' => 'edit-users', 'name' => 'Edit Users', 'group' => 'User Maintenance', 'description' => 'Edit existing users'],
        ['slug' => 'delete-users', 'name' => 'Delete Users', 'group' => 'User Maintenance', 'description' => 'Delete users'],
        ['slug' => 'approve-users', 'name' => 'Approve Users', 'group' => 'User Maintenance', 'description' => 'Approve or reject pending users'],
        ['slug' => 'manage-roles', 'name' => 'Manage Roles', 'group' => 'User Maintenance', 'description' => 'Manage roles and role assignments'],
        ['slug' => 'view-activity-logs', 'name' => 'View Activity Logs', 'group' => 'User Maintenance', 'description' => 'View activity logs'],

        // Product Maintenance
        ['slug' => 'manage-products', 'name' => 'Manage Products', 'group' => 'Product Maintenance', 'description' => 'Full product maintenance access'],
        ['slug' => 'view-products', 'name' => 'View Products', 'group' => 'Product Maintenance', 'description' => 'View product list and details'],
        ['slug' => 'create-products', 'name' => 'Create Products', 'group' => 'Product Maintenance', 'description' => 'Create new products'],
        ['slug' => 'edit-products', 'name' => 'Edit Products', 'group' => 'Product Maintenance', 'description' => 'Edit products'],
        ['slug' => 'delete-products', 'name' => 'Delete Products', 'group' => 'Product Maintenance', 'description' => 'Delete products'],
        ['slug' => 'manage-categories', 'name' => 'Manage Categories', 'group' => 'Product Maintenance', 'description' => 'Manage categories'],
        ['slug' => 'manage-brands', 'name' => 'Manage Brands', 'group' => 'Product Maintenance', 'description' => 'Manage brands'],
        ['slug' => 'manage-uom', 'name' => 'Manage Units of Measure', 'group' => 'Product Maintenance', 'description' => 'Manage units of measure'],
        ['slug' => 'manage-variations', 'name' => 'Manage Variations', 'group' => 'Product Maintenance', 'description' => 'Manage product variations'],

        // Supplier Maintenance
        ['slug' => 'manage-suppliers', 'name' => 'Manage Suppliers', 'group' => 'Supplier Maintenance', 'description' => 'Full supplier maintenance access'],
        ['slug' => 'view-suppliers', 'name' => 'View Suppliers', 'group' => 'Supplier Maintenance', 'description' => 'View suppliers'],
        ['slug' => 'create-suppliers', 'name' => 'Create Suppliers', 'group' => 'Supplier Maintenance', 'description' => 'Create suppliers'],
        ['slug' => 'edit-suppliers', 'name' => 'Edit Suppliers', 'group' => 'Supplier Maintenance', 'description' => 'Edit suppliers'],
        ['slug' => 'delete-suppliers', 'name' => 'Delete Suppliers', 'group' => 'Supplier Maintenance', 'description' => 'Delete suppliers'],
        ['slug' => 'view-purchase-history', 'name' => 'View Purchase History', 'group' => 'Supplier Maintenance', 'description' => 'View supplier purchase history'],
        ['slug' => 'manage-supplier-returns', 'name' => 'Manage Supplier Returns', 'group' => 'Supplier Maintenance', 'description' => 'Manage supplier return records'],
        ['slug' => 'manage-payment-terms', 'name' => 'Manage Payment Terms', 'group' => 'Supplier Maintenance', 'description' => 'Manage payment terms'],

        // Inventory
        ['slug' => 'manage-inventory', 'name' => 'Manage Inventory', 'group' => 'Inventory', 'description' => 'Full inventory management access'],
        ['slug' => 'view-inventory', 'name' => 'View Inventory', 'group' => 'Inventory', 'description' => 'View inventory pages and records'],
        ['slug' => 'stock-in', 'name' => 'Process Stock In', 'group' => 'Inventory', 'description' => 'Process stock in transactions'],
        ['slug' => 'stock-out', 'name' => 'Process Stock Out', 'group' => 'Inventory', 'description' => 'Process stock out transactions'],
        ['slug' => 'adjust-inventory', 'name' => 'Adjust Inventory', 'group' => 'Inventory', 'description' => 'Adjust inventory quantities'],
        ['slug' => 'view-inventory-alerts', 'name' => 'View Inventory Alerts', 'group' => 'Inventory', 'description' => 'View stock and expiry alerts'],
        ['slug' => 'view-stock-history', 'name' => 'View Stock History', 'group' => 'Inventory', 'description' => 'View inventory history'],
        ['slug' => 'void-inventory-transaction', 'name' => 'Void Inventory Transaction', 'group' => 'Inventory', 'description' => 'Void inventory transactions'],

        // Sales
        ['slug' => 'manage-sales', 'name' => 'Manage Sales', 'group' => 'Sales', 'description' => 'Full sales management access'],
        ['slug' => 'access-pos', 'name' => 'Access POS', 'group' => 'Sales', 'description' => 'Access point of sale module'],
        ['slug' => 'view-sales', 'name' => 'View Sales', 'group' => 'Sales', 'description' => 'View sales and transaction history'],
        ['slug' => 'process-sales', 'name' => 'Process Sales', 'group' => 'Sales', 'description' => 'Create and process sales'],
        ['slug' => 'void-sales', 'name' => 'Void Sales', 'group' => 'Sales', 'description' => 'Void sales transactions'],
        ['slug' => 'refund-sales', 'name' => 'Refund Sales', 'group' => 'Sales', 'description' => 'Process refunds'],
        ['slug' => 'manage-cash-drops', 'name' => 'Manage Cash Drops', 'group' => 'Sales', 'description' => 'Manage cash drops'],
        ['slug' => 'view-shift-reports', 'name' => 'View Shift Reports', 'group' => 'Sales', 'description' => 'View shift reports'],

        // Reports
        ['slug' => 'view-reports', 'name' => 'View Reports', 'group' => 'Reports', 'description' => 'Access reporting pages'],
        ['slug' => 'export-reports', 'name' => 'Export Reports', 'group' => 'Reports', 'description' => 'Export report data'],

        // System
        ['slug' => 'manage-system', 'name' => 'Manage System', 'group' => 'System', 'description' => 'Full system maintenance access'],
        ['slug' => 'manage-import', 'name' => 'Manage Data Import', 'group' => 'System', 'description' => 'Manage import tools'],
        ['slug' => 'manage-labels', 'name' => 'Manage Product Labels', 'group' => 'System', 'description' => 'Manage label printing'],
        ['slug' => 'manage-taxes', 'name' => 'Manage Tax Rates', 'group' => 'System', 'description' => 'Manage tax settings'],
        ['slug' => 'manage-system-settings', 'name' => 'Manage System Settings', 'group' => 'System', 'description' => 'Manage sensitive system settings'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Permission Mapping
    |--------------------------------------------------------------------------
    | Use "*" to grant all permissions.
    */
    'role_permissions' => [
        'admin' => '*',
        'supervisor' => [
            'view-dashboard',

            'manage-products',
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            'manage-categories',
            'manage-brands',

            'manage-suppliers',
            'view-suppliers',
            'create-suppliers',
            'edit-suppliers',
            'delete-suppliers',
            'view-purchase-history',
            'manage-supplier-returns',

            'manage-inventory',
            'view-inventory',
            'stock-in',
            'stock-out',
            'adjust-inventory',
            'view-inventory-alerts',
            'view-stock-history',
            'void-inventory-transaction',

            'manage-sales',
            'access-pos',
            'view-sales',
            'process-sales',
            'void-sales',
            'refund-sales',
            'manage-cash-drops',
            'view-shift-reports',

            'view-reports',
            'export-reports',
        ],
        'cashier' => [
            'access-pos',
            'view-sales',
            'process-sales',
        ],
    ],
];
