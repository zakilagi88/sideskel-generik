<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Kelahiran extends Model
{
    use HasFactory;

    protected $table = 'kelahirans';

    protected $fillable = [
        'nik',
        'waktu_lahir',
        'anak_ke',
        'tempat_lahir',
        'jenis_lahir',
        'penolong_lahir',
        'berat_lahir',
        'panjang_lahir',
    ];

    public function dinamika(): MorphOne
    {
        return $this->morphOne(Dinamika::class, 'dinamika');
    }
}