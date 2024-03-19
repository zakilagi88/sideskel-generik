<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RT extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'rukun_tetangga';

    protected $casts = [
        'rts' => 'array'
    ];

    protected $primaryKey = 'rt_id';
    protected $fillable = [
        'rt_id',
        'rt_nama',
        'rw_id',
    ];

    public function KepalaWilayah(): MorphOne
    {
        return $this->morphOne(KepalaWilayah::class, 'kepala');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'wilayah');
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'rw_id', 'rw_id');
    }

    public function penduduk_lk(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rt_id', 'kk_id', 'rt_id', 'kk_id')->where('jenis_kelamin', 'Laki-laki');
    }

    public function penduduk_pr(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rt_id', 'kk_id', 'rt_id', 'kk_id')->where('jenis_kelamin', 'Perempuan');
    }

    public function penduduk(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'rt_id', 'kk_id', 'rt_id', 'kk_id');
    }
}
