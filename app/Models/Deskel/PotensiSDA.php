<?php

namespace App\Models\Deskel;

use App\Models\Deskel\DesaKelurahanProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PotensiSDA extends Model
{
    use HasFactory;

    protected $table = 'potensi_sdas';

    protected $fillable =
    [
        'deskel_profil_id', // 'deskel_profil_id' is a foreign key from 'deskel_profils' table
        'jenis',
        'data'
    ];

    protected $casts =
    [
        'data' => 'array'
    ];

    public function getLinkLabel(): string
    {
        return $this->jenis;
    }

    public function getRouteKeyName()
    {
        return 'jenis';
    }

    public function deskelProfil(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahanProfile::class, 'deskel_profil_id', 'id');
    }
}
