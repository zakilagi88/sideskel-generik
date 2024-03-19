<?php

namespace App\Models\Desa;

use App\Models\Dokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Keputusan extends Model
{
    use HasFactory;

    protected $table = 'keputusans';

    protected $fillable = [
        'kep_nomor',
        'kep_tanggal',
        'kep_tentang',
        'kep_uraian_singkat',
        'kep_keterangan',
    ];

    protected $casts = [
        'kep_tanggal' => 'date',
    ];

    public function dokumens(): MorphMany
    {
        return $this->morphMany(Dokumen::class, 'dokumenable');
    }
}