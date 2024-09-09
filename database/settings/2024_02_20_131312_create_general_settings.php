<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.brand_name', 'SIDeskel');
        $this->migrator->add('general.brand_logo', 'sites/logo.png');
        $this->migrator->add('general.brand_logo_dark', 'sites/logo-dark.png');
        $this->migrator->add('general.brand_logoHeight', '6rem');
        $this->migrator->add('general.site_favicon', 'sites/logo.png');
        $this->migrator->add('general.site_init', [0 => false, 1 => false, 2 => false, 3 => false, 4 => false, 5 => false]);
        $this->migrator->add('general.site_active', false);
        $this->migrator->add('general.site_theme', [
            "primary" => "rgb(19, 83, 196)",
            "secondary" => "rgb(95, 178, 217)",
            "gray" => "rgb(82, 93, 99)",
            "success" => "rgb(12, 195, 178)",
            "danger" => "rgb(242, 15, 83)",
            "info" => "rgb(116, 84, 227)",
            "warning" => "rgb(255, 186, 93)",
        ]);
        $this->migrator->add('general.site_type', 'kelurahan');
        $this->migrator->add('general.sebutan_kepala', 'Lurah');
        $this->migrator->add('general.sebutan_deskel', 'Kelurahan');
        $this->migrator->add('general.sebutan_prov', 'Provinsi');
        $this->migrator->add('general.sebutan_kabkota', 'Kota');
        $this->migrator->add('general.sebutan_kec', 'Kecamatan');
        $this->migrator->add('general.singkatan_prov', 'Prov.');
        $this->migrator->add('general.singkatan_kabkota', 'Kota.');
        $this->migrator->add('general.singkatan_kec', 'Kec.');
        $this->migrator->add('general.singkatan_nipd', 'NIPD');
        $this->migrator->add('general.sebutan_wilayah', [
            "Khusus" => ["Nagari"],
            "Dasar" => ["RW", "RT"],
            "Lengkap" => ["Dusun", "RW", "RT"]
        ]);
    }
};
