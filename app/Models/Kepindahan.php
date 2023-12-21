<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kepindahan extends Model
{
    use HasFactory;

    protected $table = 'kepindahan';

    protected $fillable = [
        'nik',
        'wilayah_id',
        'tanggal_pindah',
        'alamat_tujuan',
        'keterangan',
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }
}