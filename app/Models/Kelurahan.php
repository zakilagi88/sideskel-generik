<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelurahan extends Model
{
    use HasFactory;

    protected $table = 'kelurahan';

    protected $primaryKey = 'kel_id';

    protected $keyType = 'string';

    protected $fillable = [
        'kel_id',
        'kel_nama',
        'kel_profil',
        'kel_tipe',
        'kec_id',

    ];

    public function kec_groups(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id', 'kec_id');
    }

    public function wilayah_groups(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'kel_id', 'kel_id');
    }
}