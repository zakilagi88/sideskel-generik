<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Wilayah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];
    protected $table = 'wilayah';
    protected $primaryKey = 'wilayah_id';
    protected $fillable = [
        'wilayah_id',
        'wilayah_nama',
        'wilayah_kodepos',
        'rw_id',
        'rt_id',
        'kel_id',
        'kec_id',
        'kabkota_id',
        'prov_id',
        'dusun_id',


    ];

    public function rws(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'rw_id', 'rw_id');
    }


    public function rts(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'rt_id', 'rt_id');
    }

    public function kk(): HasMany
    {
        return $this->hasMany(KartuKeluarga::class, 'wilayah_id', 'wilayah_id');
    }

    public function wilayah_pdd(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'wilayah_id', 'kk_id', 'wilayah_id', 'kk_id');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id', 'dusun_id');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'kel_id', 'kel_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id', 'kec_id');
    }

    public function kabkota(): BelongsTo
    {
        return $this->belongsTo(KabKota::class, 'kabkota_id', 'kabkota_id');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'prov_id', 'prov_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_wilayah_roles', 'wilayah_id', 'user_id')->as('wilayah')->withPivot('role_id')->withTimestamps();
    }
}