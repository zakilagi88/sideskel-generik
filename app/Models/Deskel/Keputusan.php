<?php

namespace App\Models\Deskel;

use App\Models\Deskel\Dokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Keputusan extends Model
{
    use HasFactory;

    protected $table = 'keputusans';

    protected $fillable = [
        'no',
        'tgl',
        'tentang',
        'uraian_singkat',
        'no_dilaporkan',
        'tgl_dilaporkan',
        'keterangan',
    ];

    protected $casts = [
        'tgl' => 'datetime:Y-m-d',
        'tgl_dilaporkan' => 'datetime:Y-m-d',
    ];

    public function dokumens(): MorphOne
    {
        return $this->morphOne(Dokumen::class, 'dokumenable');
    }
}
