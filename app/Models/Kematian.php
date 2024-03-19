<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Kematian extends Model
{
    use HasFactory;

    protected $table = 'kematians';

    protected $fillable = [
        'nik',
        'waktu_kematian',
        'tempat_kematian',
        'penyebab_kematian',
        'menerangkan_kematian',
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