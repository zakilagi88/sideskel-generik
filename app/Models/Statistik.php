<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistik extends Model
{
    use HasFactory;

    protected $table = 'statistik';

    protected $fillable = [
        'judul',
        'slug',
        'heading_grafik',
        'heading_tabel',
        'deskripsi_grafik',
        'deskripsi_tabel',
        'path_grafik',
        'path_tabel',
        'tampilkan_grafik',
        'tampilkan_tabel',
        'jenis_grafik',
    ];

    protected $casts = [
        'tampilkan_grafik' => 'boolean',
        'tampilkan_tabel' => 'boolean',
    ];
}