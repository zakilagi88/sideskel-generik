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
    protected $keyType = 'string'; // Set the primary key data type

    protected $fillable = [
        'kel_id',
        'kel_nama',
        'kec_id',
    ];

    public function kec_groups(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id', 'kec_id');
    }

    public function sls_groups(): HasMany
    {
        return $this->hasMany(SLS::class, 'kel_id', 'kel_id');
    }
}
