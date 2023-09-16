<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kedatangan extends Model
{
    use HasFactory;

    protected $table = 'kedatangan';

    protected $fillable = [
        'nik',
        'sls_id',
        'tanggal_datang',
        'alamat_asal',
        'keterangan',
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }
}
