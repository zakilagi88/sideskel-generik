<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.brand_name', 'SIDeskel');
        $this->migrator->add('general.brand_logo', 'sites/logo.png');
        $this->migrator->add('general.brand_logo_dark', 'sites/logo-dark.png');
        $this->migrator->add('general.brand_logoHeight', '10rem');
        $this->migrator->add('general.site_active', true);
        $this->migrator->add('general.site_favicon', 'sites/logo.png');
        $this->migrator->add('general.site_theme', [
            "primary" => "rgb(19, 83, 196)",
            "secondary" => "rgb(95, 178, 217)",
            "gray" => "rgb(82, 93, 99)",
            "success" => "rgb(12, 195, 178)",
            "danger" => "rgb(242, 15, 83)",
            "info" => "rgb(116, 84, 227)",
            "warning" => "rgb(255, 186, 93)",
        ]);
    }
};
