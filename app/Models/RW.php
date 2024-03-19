<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RW extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'rukun_warga';
    protected $primaryKey = 'rw_id';
    protected $fillable = [
        'rw_id',
        'rw_nama',
        'deskel_id',
        'dusun_id',

    ];

    public function deskel(): HasOne
    {
        return $this->hasOne(DesaKelurahan::class, 'deskel_id', 'deskel_id');
    }

    public function kepalaWilayah(): MorphOne
    {
        return $this->morphOne(KepalaWilayah::class, 'kepala');
    }

    public function rts(): HasMany
    {
        return $this->hasMany(RT::class, 'rw_id', 'rw_id');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'wilayah');
    }

    public function keluarga(): HasMany
    {
        return $this->hasMany(KartuKeluarga::class, 'rw_id', 'rw_id');
    }


    public function kepala_lk(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rw_id', 'kk_id', 'rw_id', 'kk_id')->where('status_hubungan', 'KEPALA KELUARGA')->where('jenis_kelamin', 'Laki-laki');
    }

    public function kepala_pr(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rw_id', 'kk_id', 'rw_id', 'kk_id')->where('status_hubungan', 'KEPALA KELUARGA')->where('jenis_kelamin', 'Perempuan');
    }

    public function penduduk_lk(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rw_id', 'kk_id', 'rw_id', 'kk_id')->where('jenis_kelamin', 'Laki-laki');
    }

    public function penduduk_pr(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rw_id', 'kk_id', 'rw_id', 'kk_id')->where('jenis_kelamin', 'Perempuan');
    }

    public function penduduk(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rw_id', 'kk_id', 'rw_id', 'kk_id');
    }
}
