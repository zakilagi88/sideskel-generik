<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KabKota extends Model
{
    use HasFactory;

    protected $table = 'kab_kota';

    protected $primaryKey = 'kabkota_id';

    protected $keyType = 'string';

    protected $fillable = [
        'kabkota_id',
        'kabkota_nama',
        'prov_id',
    ];

    public function prov(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'prov_id', 'prov_id');
    }
}
