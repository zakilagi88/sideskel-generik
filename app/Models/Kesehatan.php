<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kesehatan extends Model
{
    use HasFactory;

    protected $table = 'kesehatans';

    protected $primaryKey = 'kesehatan_id';

    protected $fillable = [
        'kesehatan_jaminan',
    ];

    protected $casts = [];

    public function penduduks(): BelongsToMany
    {
        return $this->belongsToMany(Penduduk::class, 'penduduk_kesehatan', 'kesehatan_id', 'nik');
    }
}
