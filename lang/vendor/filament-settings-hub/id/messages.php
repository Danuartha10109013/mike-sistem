<?php

return [
    'title' => 'Pusat Pengaturan',
    'group' => 'Pengaturan',
    'back' => 'Back',
    'settings' => [
        'site' => [
            'title' => 'Pengaturan Situs',
            'description' => 'Kelola pengaturan situs Anda',
            'form' => [
                'site_name' => 'Nama Situs',
                'site_description' => 'Deskripsi Situs',
                'site_logo' => 'Logo Situs',
                'site_profile' => 'Profil Situs',
                'site_keywords' => 'Kata Kunci Situs',
                'site_email' => 'Email Situs',
                'site_phone' => 'Nomor Telepon Situs',
                'site_author' => 'Penulis Situs',
            ],
            'site-map' => 'Generate Site Map',
            'site-map-notification' => 'Site Map Generated Successfully',
        ],
        'social' => [
            'title' => 'Pengaturan Sosial',
            'description' => 'Kelola pengaturan sosial Anda',
            'form' => [
                'site_social' => 'Social Links',
                'vendor' => 'Vendor',
                'link' => 'Link',
            ],
        ],
        'location' => [
            'title' => 'Pengaturan Lokasi',
            'description' => 'Kelola pengaturan lokasi Anda',
            'form' => [
                'site_address' => 'Site Address',
                'site_phone_code' => 'Site Phone Code',
                'site_location' => 'Site Location',
                'site_currency' => 'Site Currency',
                'site_language' => 'Site Language',
            ],
        ],
    ],
];
