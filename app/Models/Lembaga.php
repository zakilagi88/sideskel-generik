<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lembaga extends Model
{
    use HasFactory;

    protected $table = 'lembagas';

    protected $fillable = [
        'nama',
        'singkatan',
        'kategori_jabatan',
        'alamat',
        'dokumen_id',
    ];

    protected $casts = [
        'kategori_jabatan' => 'array',
    ];

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class);
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(Penduduk::class, 'lembaga_anggotas', 'lembaga_id', 'anggota_id')->withPivot('jabatan', 'keterangan')->withTimestamps();
    }
}
