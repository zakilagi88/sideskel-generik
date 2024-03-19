<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tambahan extends Model
{
    use HasFactory;

    protected $table = 'tambahans';

    protected $primaryKey = 'tambahan_id';

    protected $fillable = [
        'tambahan_id',
        'tambahan_nama',
        'tambahan_sasaran',
        'tambahan_keterangan',
        'tambahan_tgl_mulai',
        'tambahan_tgl_selesai',
        'tambahan_status',
    ];

    // protected $casts = [
    //     'tambahan_tgl_mulai' => 'date',
    //     'tambahan_tgl_selesai' => 'date',
    // ];

    public function penduduks(): MorphToMany
    {
        return $this->morphedByMany(Penduduk::class, 'tambahanable', 'tambahanables', 'tambahan_id', 'tambahanable_id')->withPivot('tambahanable_type', 'tambahanable_id');
    }

    public function keluargas(): MorphToMany
    {
        return $this->morphedByMany(KartuKeluarga::class, 'tambahanable', 'tambahanables', 'tambahan_id', 'tambahanable_id')->withPivot('tambahanable_type', 'tambahanable_id');
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
}
