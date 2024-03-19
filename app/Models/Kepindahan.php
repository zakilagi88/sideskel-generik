<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Kepindahan extends Model
{
    use HasFactory;

    protected $table = 'kepindahans';

    protected $fillable = [
        'nik',
        'tujuan_pindah',
        'alamat_pindah',
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }

    public function dinamika(): MorphOne
    {
        return $this->morphOne(Dinamika::class, 'dinamika');
    }
}
