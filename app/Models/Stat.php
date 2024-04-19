<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stat extends Model
{
    use HasFactory;

    protected $table = 'stats';

    protected $fillable = [
        'stat_kategori_id',
        'key',
        'nama',
        'slug',
        'deskripsi',
        'grafik_path',
        'tabel_path',
        'tampil'
    ];

    protected $casts = [];

    public function kat(): BelongsTo
    {
        return $this->belongsTo(StatKategori::class, 'stat_kategori_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
