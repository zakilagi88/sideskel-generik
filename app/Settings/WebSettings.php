<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WebSettings extends Settings
{
    public array $menus;
    public bool $web_active;
    public string $web_title;
    public ?string $web_gambar;
    public ?string $kepala_gambar;
    public string $kepala_nama;
    public string $kepala_judul;
    public string $kepala_deskripsi;
    public string $berita_judul;
    public string $berita_deskripsi;
    public string $footer_deskripsi;


    public static function group(): string
    {
        return 'web';
    }
}
