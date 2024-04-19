<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LembagaAnggota extends Pivot
{
    use HasFactory;

    protected $table = 'lembaga_anggotas';

    protected $fillable = [
        'lembaga_id',
        'anggota_id',
        'jabatan',
        'keterangan',
    ];
}
