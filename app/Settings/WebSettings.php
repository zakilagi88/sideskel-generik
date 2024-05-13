<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WebSettings extends Settings
{
    public array $menus;

    public static function group(): string
    {
        return 'web';
    }
}
