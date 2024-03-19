<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';

    protected $primaryKey = 'kec_id';

    protected $keyType = 'string';


    protected $fillable = [
        'kec_id',
        'kec_nama',
        'kabkota_id',
    ];

    public function kabkota(): BelongsTo
    {
        return $this->belongsTo(KabKota::class, 'kabkota_id', 'kabkota_id');
    }
}
