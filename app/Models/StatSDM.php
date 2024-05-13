<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatSDM extends Model
{
    use HasFactory;

    protected $table = 'stat_sdms';

    protected $fillable = [
        'stat_kategori_id',
        'key',
        'nama',
        'slug',
        'deskripsi',
        'grafik_path',
        'tabel_path',
        'status'
    ];

    protected $casts = [];

    public function getLinkKey(): string
    {
        return $this->slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function kat(): BelongsTo
    {
        return $this->belongsTo(StatKategori::class, 'stat_kategori_id');
    }

    

    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }
}
