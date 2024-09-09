<?php

namespace App\Models;

use App\Facades\Deskel;
use App\Facades\DeskelProfile;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Traits\Dumpable;
use OwenIt\Auditing\Contracts\Auditable;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;


class KartuKeluarga extends Model implements Auditable
{
    use HasFactory, Dumpable;

    use \OwenIt\Auditing\Auditable;

    use TraitsBelongsToThrough;

    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'kartu_keluarga';

    protected $primaryKey = 'kk_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $auditTimestamps = true;

    protected $fillable = [
        'kk_id',
        'kk_alamat',
        'wilayah_id',
        'created_at',
        'updated_at'
    ];


    protected $auditInclude = [
        'kk_id',
        'kk_alamat',
        'wilayah_id',
        'created_at',
        'updated_at'
    ];


    public function scopeByWilayah($query, $user, $descendants = null): Builder
    {
        switch (true) {
            case $user->hasRole('Admin') || $user->hasRole('Admin Web'):
                return $query;
                break;

            case $user->hasRole('Monitor Wilayah'):
                return $query->whereIn('wilayah_id', $descendants);
                break;

            case $user->hasRole('Operator Wilayah'):
                return $query->where('wilayah_id', $user->wilayah_id);
                break;

            default:
                return $query;
                break;
        }
    }

    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'kk_id', 'kk_id');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id', 'wilayah_id');
    }

    public function parentWilayah(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->wilayah(), (new Wilayah)->setAlias('alias')->parent());
    }

    public function kepalaKeluarga(): HasOne
    {
        return $this->hasOne(Penduduk::class, 'kk_id', 'kk_id')->where('status_hubungan', 'KEPALA KELUARGA');
    }

    public function bantuans(): MorphToMany
    {
        return $this->morphToMany(Bantuan::class, 'bantuanable', 'bantuanables', 'bantuanable_id', 'bantuan_id');
    }

    public function tambahans(): MorphToMany
    {
        return $this->morphToMany(Tambahan::class, 'tambahanable', 'tambahanables', 'tambahanable_id', 'tambahan_id')
            ->withPivot('tambahan_id', 'tambahanable_type', 'tambahanable_id', 'tambahanable_ket')
            ->withTimestamps();
    }

    public function dk(): BelongsToThrough
    {
        return $this->belongsToThrough(
            DesaKelurahan::class,
            Wilayah::class,
            null,
            '',
            [DesaKelurahan::class => 'deskel_id', Wilayah::class => 'wilayah_id'],
        );
    }

    public function kec(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Kecamatan::class,
            [DesaKelurahan::class, Wilayah::class],
            null,
            '',
            [Kecamatan::class => 'kec_id', DesaKelurahan::class => 'deskel_id', Wilayah::class => 'wilayah_id']
        );
    }

    public function kabkota(): BelongsToThrough
    {
        return $this->belongsToThrough(
            KabKota::class,
            [Kecamatan::class, DesaKelurahan::class, Wilayah::class],
            null,
            '',
            [KabKota::class => 'kabkota_id', Kecamatan::class => 'kec_id', DesaKelurahan::class => 'deskel_id', Wilayah::class => 'wilayah_id']
        );
    }

    public function prov(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Provinsi::class,
            [KabKota::class, Kecamatan::class, DesaKelurahan::class, Wilayah::class],
            null,
            '',
            [Provinsi::class => 'prov_id', KabKota::class => 'kabkota_id', Kecamatan::class => 'kec_id', DesaKelurahan::class => 'deskel_id', Wilayah::class => 'wilayah_id']
        );
    }
}
