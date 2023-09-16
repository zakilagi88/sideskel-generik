<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kematian extends Model
{
    use HasFactory;

    protected $table = 'kematian';

    protected $fillable = [
        'nik',
        'sls_id',
        'tanggal_kematian',
        'tempat_kematian',
        'sebab_kematian',
        'keterangan',
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }
}
