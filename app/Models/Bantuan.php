<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Bantuan extends Model
{
    use HasFactory;

    protected $table = 'bantuans';

    protected $primaryKey = 'bantuan_id';

    protected $fillable = [
        'bantuan_id',
        'bantuan_program',
        'bantuan_sasaran',
        'bantuan_keterangan',
        'bantuan_tgl_mulai',
        'bantuan_tgl_selesai',
        'bantuan_status',
    ];

    protected $casts = [
        'bantuan_tgl_mulai' => 'date',
        'bantuan_tgl_selesai' => 'date',
    ];

    public function penduduks(): MorphToMany
    {
        return $this->morphedByMany(Penduduk::class, 'bantuanable', 'bantuanables', 'bantuan_id', 'bantuanable_id');
    }

    public function keluargas(): MorphToMany
    {
        return $this->morphedByMany(KartuKeluarga::class, 'bantuanable', 'bantuanables', 'bantuan_id', 'bantuanable_id');
    }

    public function related(): MorphOne
    {
        return $this->morphOne(Bantuanable::class, 'bantuanable');
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