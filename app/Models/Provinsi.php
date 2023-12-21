<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';

    protected $primaryKey = 'prov_id';

    protected $keyType = 'string';

    protected $fillable = [
        'prov_id',
        'prov_nama',
    ];

    public function kabkota_groups(): HasMany
    {
        return $this->hasMany(Kab_Kota::class, 'prov_id', 'prov_id');
    }
}
