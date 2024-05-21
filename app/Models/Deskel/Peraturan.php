<?php

namespace App\Models\Deskel;

use App\Models\Deskel\Dokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Peraturan extends Model
{
    use HasFactory;

    protected $table = 'peraturans';

    protected $fillable =
    [
        'jenis',
        'no_ditetapkan',
        'tgl_ditetapkan',
        'tentang',
        'uraian_singkat',
        'tgl_kesepakatan',
        'no_dilaporkan',
        'tgl_dilaporkan',
        'no_diundangkan_l',
        'tgl_diundangkan_l',
        'no_diundangkan_b',
        'tgl_diundangkan_b',
        'keterangan'
    ];

    protected $casts =
    [
        'tgl_ditetapkan' => 'datetime:Y-m-d',
        'tgl_kesepakatan' => 'datetime:Y-m-d',
        'tgl_dilaporkan' => 'datetime:Y-m-d',
        'tgl_diundangkan_l' => 'datetime:Y-m-d',
        'tgl_diundangkan_b' => 'datetime:Y-m-d',
    ];

    public function dokumens(): MorphOne
    {
        return $this->morphOne(Dokumen::class, 'dokumenable');
    }
}
