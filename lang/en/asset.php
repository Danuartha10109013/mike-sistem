<?php
return [
    'title' => 'Asset',
    'column' => [
        'number' => 'Number',
        'name' => 'Name',
        'quantity' => 'Quantity',
        'price' => 'Price',
        'decrease' => 'Final Price & Decrease % Per Year',
        'condition' => 'Condition',
        'date' => 'Date',
        'user' => 'User',
        'created_at' => 'Created At',
        'held_by_user' => 'Held By User?'
    ],
    'placeholder' => [
        'number' => 'Enter Asset Number',
        'name' => 'Enter Asset Name',
        'quantity' => 'Enter Asset Quantity',
        'price' => 'Enter Asset Price',
        'decrease' => 'Enter Decrease % Per Year',
        'condition' => 'Enter Asset Condition',
        'date' => 'Enter Asset Date',
        'user' => 'Enter Asset User',
        'brand' => 'Enter Asset Brand',
        'room' => 'Enter Asset Room',
        'category' => 'Enter Asset Category',
    ],
    'infolist' => [
        'description' => 'View the details of the asset.',
    ],
    'navigation' => [
        'view_asset' => 'View Asset',
        'edit_asset' => 'Edit Asset',
        'maintenance_histories' => 'Maintenance Histories',
        'purchase_histories' => 'Purchase Histories',
    ],

    // Page
    'view_asset_maintenances' => [
        'title' => 'View Asset Maintenances',
        'breadcrumb' => 'Maintenances',
        'description' => 'A list of all approved purchases for this asset.',
    ],
    'view_asset_purchases' => [
        'title' => 'View Asset Purchases',
        'breadcrumb' => 'Purchases',
        'description' => 'A list of all approved purchases for this asset.',
    ],
];
