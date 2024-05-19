<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Lembaga extends Model
{
    use HasFactory;

    protected $table = 'lembagas';

    protected $fillable = [
        'nama',
        'singkatan',
        'deskripsi',
        'logo_url',
        'slug',
        'kategori_jabatan',
        'alamat',
        'dokumen_id',
    ];

    protected $casts = [
        'kategori_jabatan' => 'array',
    ];

    public function getLogoUrl(): ?string
    {
        return $this->logo_url ? Storage::url($this->logo_url) : 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=random';
    }

    public function getLinkLabel(): string
    {
        return $this->nama;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id', 'id');
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(Penduduk::class, 'lembaga_anggotas', 'lembaga_id', 'anggota_id')->withPivot('jabatan', 'keterangan')->withTimestamps();
    }
}
