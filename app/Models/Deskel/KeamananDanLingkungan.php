<?php

namespace App\Models\Deskel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeamananDanLingkungan extends Model
{
    use HasFactory;

    protected $table = 'keamanan_dan_lingkungans';

    protected $fillable = [
        'deskel_profil_id',
        'jenis',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function deskelProfil(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahanProfile::class, 'deskel_profil_id', 'id');
    }
}
