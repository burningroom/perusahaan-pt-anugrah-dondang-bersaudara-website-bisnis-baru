<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Nama',
    'column.guard_name' => 'Guard',
    'column.roles' => 'Roles',
    'column.permissions' => 'Izin',
    'column.updated_at' => 'Dirubah',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Nama',
    'field.guard_name' => 'Guard',
    'field.permissions' => 'Izin',
    'field.select_all.name' => 'Pilih Semua',
    'field.select_all.message' => 'Aktifkan semua izin yang <span class="text-primary font-medium">Tersedia</span> untuk role ini.',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Master Data',
    'nav.role.label' => 'Data Roles',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Role',
    'resource.label.roles' => 'Data Roles',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entitas',
    'resources' => 'Resources',
    'widgets' => 'Widget',
    'pages' => 'Halaman',
    'custom' => 'Izin Kustom',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Kamu tidak punya izin akses',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Lihat',
        'view_any' => 'Lihat Apa Saja',
        'create' => 'Buat',
        'update' => 'Perbarui',
        'delete' => 'Hapus',
        'delete_any' => 'Hapus Apa Saja',
        'force_delete' => 'Paksa Hapus',
        'force_delete_any' => 'Paksa Hapus Apa Saja',
        'restore' => 'Pulihkan',
        'replicate' => 'Replikasi',
        'reorder' => 'Susun Ulang',
        'restore_any' => 'Pulihkan Apa Saja',
    ],
];
