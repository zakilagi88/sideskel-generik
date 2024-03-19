<?php

namespace App\Models\Desa;

use App\Models\Dokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Peraturan extends Model
{
    use HasFactory;

    protected $table = 'peraturans';

    protected $fillable =
    [
        'per_jenis',
        'per_tentang',
        'per_uraian_singkat',
        'per_no_ditetapkan',
        'per_tgl_ditetapkan',
        'per_tgl_kesepakatan',
        'per_no_dilaporkan',
        'per_tgl_dilaporkan',
        'per_no_diundangkan_l',
        'per_tgl_diundangkan_l',
        'per_no_diundangkan_b',
        'per_tgl_diundangkan_b',
        'per_keterangan'
    ];

    protected $casts =
    [
        'per_tgl_ditetapkan' => 'date',
        'per_tgl_kesepakatan' => 'date',
        'per_tgl_dilaporkan' => 'date',
        'per_tgl_diundangkan_l' => 'date',
        'per_tgl_diundangkan_b' => 'date'
    ];

    public function dokumens(): MorphMany
    {
        return $this->morphMany(Dokumen::class, 'dokumenable');
    }
}