<?php

namespace App\Models;

use App\Facades\Deskel;
use App\Facades\DeskelProfile;
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
use OwenIt\Auditing\Contracts\Auditable;
use Znck\Eloquent\Relations\BelongsToThrough;

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
        static::addGlobalScope('wilayahs', function (Builder $query) {
            if (auth()->check()) {
                if (auth()->user()->hasRole('Admin')) {
                    return $query;
                } else {
                    return $query->byWilayah(auth()->user()->wilayah_id);
                }
            }
        });
    }

    public function scopeByWilayah($query, $wilayah_id): Builder
    {
        $struktur = Deskel::getFacadeRoot();

        switch ($struktur->deskel_tipe) {
            case 'Khusus':
                return $query->where('wilayah_id', $wilayah_id);
                break;

            case 'Dasar':
                $level = Wilayah::tree()->find($wilayah_id);
                if ($level->depth == 0) {
                    $descendants = $level->descendants->pluck('wilayah_id');
                    return $query->whereIn('wilayah_id', $descendants);
                } else {
                    return $query->where('wilayah_id', $wilayah_id);
                }
                break;

            case 'Lengkap':
                $level = Wilayah::tree()->find($wilayah_id);
                if ($level->depth == 0) {
                    $descendants = $level->descendants()->whereDepth(2)->pluck('wilayah_id');
                } elseif ($level->depth == 1) {
                    $descendants = $level->descendants->pluck('wilayah_id');
                } else {
                    return $query->where('wilayah_id', $wilayah_id);
                }
                return $query->whereIn('wilayah_id', $descendants);
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

    public function wilayahs(): BelongsTo
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
        return $this->morphToMany(Tambahan::class, 'tambahanable', 'tambahanables', 'tambahanable_id', 'tambahan_id');
    }

    public function deskelProfil(): BelongsToThrough
    {
        return $this->belongsToThrough(DesaKelurahanProfile::class, DesaKelurahan::class, 'deskel_id', 'deskel_id', 'deskel_id', 'deskel_id');
    }

    public function dk(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'deskel_id', 'deskel_id');
    }
}
