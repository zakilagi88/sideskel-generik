<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class KartuKeluarga extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'kartu_keluarga';

    protected $primaryKey = 'kk_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kk_id',
        'kk_kepala',
        'kk_alamat',
        'wilayah_id',
        'updated_at'
    ];

    protected $casts = [
        'kk_id' => 'string',
    ];

    protected $auditInclude = [
        'kk_id',
        'kk_kepala',
        'kk_alamat',
        'wilayah_id',
        'updated_at'
    ];

    protected $auditTimestamps = true;

    public function penduduks(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, AnggotaKeluarga::class, 'kk_id', 'nik', 'kk_id', 'nik');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(wilayah::class, 'wilayah_id', 'wilayah_id');
    }


    public function kepalaKeluarga(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kk_kepala', 'nik');
    }


    public function anggotaKeluarga(): HasMany
    {
        return $this->hasMany(AnggotaKeluarga::class, 'kk_id', 'kk_id');
    }
}