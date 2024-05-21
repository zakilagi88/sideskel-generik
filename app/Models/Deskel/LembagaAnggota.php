<?php

namespace App\Models\Deskel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $casts = [];

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id', 'id');
    }
}
