<?php

use Spatie\LaravelSettings\Exceptions\SettingAlreadyExists;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SitesSettings extends SettingsMigration
{
    /**
     * @throws SettingAlreadyExists
     */
    public function up(): void
    {
        $this->migrator->add('sites.site_name', 'Sistem Manajemen Inventori');
        $this->migrator->add('sites.site_description', 'Sistem Manajemen Inventori adalah aplikasi yang digunakan untuk mengelola inventori barang');
        $this->migrator->add('sites.site_keywords', 'inventori, barang, manajemen, sistem, aplikasi');
        $this->migrator->add('sites.site_profile', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'Muhammad Rizal Pahlevi');
        $this->migrator->add('sites.site_address', 'Semarang, Indonesia');
        $this->migrator->add('sites.site_email', 'diskominfo@semarangkota.go.id');
        $this->migrator->add('sites.site_phone', '(024)3549446');
        $this->migrator->add('sites.site_phone_code', '');
        $this->migrator->add('sites.site_location', 'Indonesia');
        $this->migrator->add('sites.site_currency', 'IDR');
        $this->migrator->add('sites.site_language', 'Indonesia');
        $this->migrator->add('sites.site_social', []);
    }
}
