<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatans';

    protected $primaryKey = 'kec_id';

    protected $fillable = [
        'kec_id',
        'kec_nama',
        'kabkota_id',
    ];

    public function kabkota_groups(): BelongsTo
    {
        return $this->belongsTo(Kab_Kota::class, 'kabkota_id', 'kabkota_id');
    }
}
