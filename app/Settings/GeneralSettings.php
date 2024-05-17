<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $brand_name;
    public ?string $brand_logo;
    public string $brand_logoHeight;
    public ?string $brand_logo_dark;
    public bool $site_init;
    public bool $site_active;
    public ?string $site_favicon;
    public array $site_theme;
    public string $sebutan_kepala;
    public string $sebutan_deskel;
    public string $sebutan_prov;
    public string $sebutan_kabkota;
    public string $sebutan_kec;
    public string $singkatan_prov;
    public string $singkatan_kabkota;
    public string $singkatan_kec;
    public string $singkatan_nipd;

    public static function group(): string
    {
        return 'general';
    }
}

