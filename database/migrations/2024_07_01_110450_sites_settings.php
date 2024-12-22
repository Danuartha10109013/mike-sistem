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
        $this->migrator->add('sites.site_name', 'Sistem Manajemen aset');
        $this->migrator->add('sites.site_description', 'Sistem Manajemen aset adalah aplikasi yang digunakan untuk mengelola aset barang Gothru');
        $this->migrator->add('sites.site_keywords', 'aset, barang, manajemen, sistem, aplikasi');
        $this->migrator->add('sites.site_profile', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'Mika Ancela');
        $this->migrator->add('sites.site_address', 'Subang, Indonesia');
        $this->migrator->add('sites.site_email', '');
        $this->migrator->add('sites.site_phone', '');
        $this->migrator->add('sites.site_phone_code', '');
        $this->migrator->add('sites.site_location', 'Indonesia');
        $this->migrator->add('sites.site_currency', 'IDR');
        $this->migrator->add('sites.site_language', 'Indonesia');
        $this->migrator->add('sites.site_social', []);
    }
}
