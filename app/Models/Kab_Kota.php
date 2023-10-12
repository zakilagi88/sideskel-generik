<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kab_Kota extends Model
{
    use HasFactory;

    protected $table = 'kab_kotas';

    protected $primaryKey = 'kabkota_id';

    protected $fillable = [
        'kabkota_id',
        'prov_id',
        'kabkota_nama',
    ];

    public function prov_groups(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'prov_id', 'prov_id');
    }
}
