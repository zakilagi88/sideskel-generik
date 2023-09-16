<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KartuKeluarga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'kartu_keluarga';

    protected $primaryKey = 'kk_id';
    protected $keyType = 'string';
    // public $incrementing = false;
    protected $fillable = [
        'kk_id',
        'kk_kepala',
        'kk_alamat',
        'sls_id'
    ];

    public function penduduks(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, AnggotaKeluarga::class, 'kk_id', 'nik', 'kk_id', 'nik');
    }

    public function sls(): BelongsTo
    {
        return $this->belongsTo(SLS::class, 'sls_id', 'sls_id');
    }


    public function kepalaKeluarga(): BelongsTo
    {
        return $this->BelongsTo(Penduduk::class, 'kk_id', 'nik');
    }

    public function anggotaKK(): HasMany
    {
        return $this->hasMany(AnggotaKeluarga::class, 'kk_id', 'kk_id');
    }



    public function generateUniqueKKid()
    {
        $lastKartuKeluarga = KartuKeluarga::orderBy('kk_id', 'desc')->first();
        if (!$lastKartuKeluarga) {
            return 'KK0000000000000001';
        }
        $lastKartuKeluargaId = $lastKartuKeluarga->kk_id;
        $lastKartuKeluargaId = substr($lastKartuKeluargaId, 2);
        $lastKartuKeluargaId = (int) $lastKartuKeluargaId;
        $lastKartuKeluargaId++;
        $lastKartuKeluargaId = 'KK' . sprintf("%016d", $lastKartuKeluargaId);
        return $lastKartuKeluargaId;
    }
}
