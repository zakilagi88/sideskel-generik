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
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;


class KartuKeluarga extends Model implements Auditable
{
    use HasFactory, Dumpable;

    use \OwenIt\Auditing\Auditable;

    use TraitsBelongsToThrough;

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
        'updated_at'
    ];


    protected $auditInclude = [
        'kk_id',
        'kk_alamat',
        'wilayah_id',
        'updated_at'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('wilayah', function (Builder $query) {
            /** @var \App\Models\User */
            // $role = Filament::auth()->user();
            // return $query->whereHas('wilayah', function ($query) use ($authUser) {
            //     $query->where('parent_id', 116);
            // });


            // $descendants = Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id');






            // return $query;
            // whereHas('wilayah', function ($query) use ($authUser) {
            //     $query->where('wilayah_id', $authUser->wilayah_id)->get()->dd();
            // })->first();
            // $child = $level->descendants;
            // if ($child->isEmpty()) {
            //     return $query->whereIn('wilayah_id', $descendants);
            // } else {
            //     return $query->where('wilayah_id', $authUser->wilayah_id);
            // }
            // if (auth()->check()) {
            //     if ($authUser->hasRole('Admin')) {
            //         return $query;
            //     } else {
            //         return $query->byWilayah($authUser);
            //     }
            // }
        });
    }

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
}
