<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Pendatang extends Model
{
    use HasFactory;

    protected $table = 'pendatangs';

    protected $fillable = [
        'nik',
        'alamat_sebelumnya',
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
