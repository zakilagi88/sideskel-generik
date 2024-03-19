<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinamika extends Model
{
    use HasFactory;

    protected $table = 'dinamikas';

    protected $fillable = [
        'nik',
        'dinamika_type',
        'dinamika_id',
        'jenis_dinamika',
        'catatan_dinamika',
        'tanggal_dinamika',
        'tanggal_lapor',
    ];

    protected $casts = [
        'tanggal_dinamika' => 'date',
        'tanggal_lapor' => 'date',
    ];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }

    public function dinamika()
    {
        return $this->morphTo();
    }
}