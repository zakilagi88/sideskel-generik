<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistik extends Model
{
    use HasFactory;

    protected $table = 'statistiks';

    protected $fillable = [
        'stat_key',
        'stat_heading',
        'stat_subheading',
        'stat_slug',
        'stat_deskripsi',
        'stat_grafik_path',
        'stat_tabel_path',
        'stat_grafik_jenis',
        'stat_tampil'
    ];

    protected $casts = [
        'tampilkan_grafik' => 'boolean',
        'tampilkan_tabel' => 'boolean',
    ];
}
