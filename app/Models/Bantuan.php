<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Bantuan extends Model
{
    use HasFactory;

    protected $table = 'bantuans';

    protected $primaryKey = 'bantuan_id';

    protected $fillable = [
        'bantuan_id',
        'jenis_bantuan',
        'keterangan_bantuan',
        'tanggal_bantuan',
    ];


    public function penduduks(): MorphToMany
    {
        return $this->morphedByMany(Penduduk::class, 'bantuanable', 'bantuanables', 'bantuan_id', 'bantuanable_id');
    }

    public function keluargas(): MorphToMany
    {
        return $this->morphedByMany(KartuKeluarga::class, 'bantuanable', 'bantuanables', 'bantuan_id', 'bantuanable_id');
    }

    public function bantuanables(): HasMany
    {
        return $this->hasMany(Bantuanable::class, 'bantuan_id', 'bantuan_id');
    }
}