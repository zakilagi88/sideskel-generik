<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Traits\Dumpable;

class Tambahan extends Model
{
    use HasFactory, Dumpable;

    protected $table = 'tambahans';

    protected $fillable = [
        'id',
        'nama',
        'slug',
        'sasaran',
        'kategori',
        'keterangan',
        'tgl_mulai',
        'tgl_selesai',
        'status',
    ];

    protected $casts = [
        'kategori' => 'array',
    ];

    public function getLinkLabel(): string
    {
        return $this->nama;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function penduduks(): MorphToMany
    {
        return $this->morphedByMany(Penduduk::class, 'tambahanable', 'tambahanables', 'tambahan_id', 'tambahanable_id')
            ->withPivot('tambahanable_type', 'tambahanable_id', 'tambahanable_ket', 'tambahan_id')
            ->withTimestamps();
    }

    public function keluargas(): MorphToMany
    {
        return $this->morphedByMany(KartuKeluarga::class, 'tambahanable', 'tambahanables', 'tambahan_id', 'tambahanable_id')
            ->withPivot('tambahanable_type', 'tambahanable_id', 'tambahanable_ket', 'tambahan_id')
            ->withTimestamps();
    }

    public function related(): MorphOne
    {
        return $this->morphOne(Tambahanable::class, 'tambahanable');
    }

    public function terdaftar($record)
    {
        if ($record == 'Penduduk') {
            return $this->penduduks;
        } else {
            return $this->keluargas;
        }
    }

    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }
}
