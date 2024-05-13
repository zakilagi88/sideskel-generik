<?php

namespace App\Models;

use App\Enums\Kependudukan\{AgamaType, EtnisSukuType, GolonganDarahType, JenisKelaminType, KewarganegaraanType, PekerjaanType, StatusPengajuanType, PerkawinanType, PendidikanType, StatusDasarType, StatusHubunganType, StatusTempatTinggalType};
use App\Facades\Deskel;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne, MorphMany, MorphTo, MorphToMany};
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Znck\Eloquent\Relations\BelongsToThrough;

class Penduduk extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use \Znck\Eloquent\Traits\BelongsToThrough;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $table = 'penduduk';

    protected $primaryKey = 'nik';
    protected $keyType = 'string';
    protected $foreignKey = 'kk_id';
    public $incrementing = false;
    protected $fillable = [
        'nik',
        'kk_id',
        'nama_lengkap',
        'foto',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'umur',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'tgl_perkawinan',
        'tgl_perceraian',
        'kewarganegaraan',
        'nama_ayah',
        'nama_ibu',
        'nik_ayah',
        'nik_ibu',
        'golongan_darah',
        'status_penduduk',
        'status_dasar',
        'status_pengajuan',
        'status_tempat_tinggal',
        'status_hubungan',
        'etnis_suku',
        'alamat_sekarang',
        'alamat_sebelumnya',
        'alamatKK',
        'telepon',
        'email',
    ];

    protected $casts =
    [
        'agama' => AgamaType::class,
        'jenis_kelamin' => JenisKelaminType::class,
        'pendidikan' => PendidikanType::class,
        'pekerjaan' => PekerjaanType::class,
        'etnis_suku' => EtnisSukuType::class,
        'status_dasar' => StatusDasarType::class,
        'status_pengajuan' => StatusPengajuanType::class,
        'status_perkawinan' => PerkawinanType::class,
        'status_tempat_tinggal' => StatusTempatTinggalType::class,
        'status_hubungan' => StatusHubunganType::class,
        'golongan_darah' => GolonganDarahType::class,
        'kewarganegaraan' => KewarganegaraanType::class,
        'tanggal_lahir' => 'date',
        'tgl_perkawinan' => 'datetime',
        'tgl_perceraian' => 'datetime',
    ];

    protected $auditInclude = [
        'nik',
        'kk_id',
        'status_identitas',
        'jenis_identitas',
        'status_rekam_identitas',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'umur',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'kewarganegaraan',
        'nama_ayah',
        'nama_ibu',
        'nik_ayah',
        'nik_ibu',
        'golongan_darah',
        'status_penduduk',
        'status_dasar',
        'status_pengajuan',
        'status_tempat_tinggal',
        'status_hubungan',
        'etnis_suku',
        'alamat_sekarang',
        'alamat_sebelumnya',
        'alamatKK',
        'telepon',
        'email',
        'is_nik_sementara',
    ];

    protected $auditTimestamps = true;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     *

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('wilayah', function (Builder $query) {
    //         /** @var \App\Models\User */
    //         $authUser = Filament::auth()->user();
    //         if (auth()->check()) {
    //             if ($authUser->hasRole('Admin')) {
    //                 return $query;
    //             } else {
    //                 return $query->byWilayah($authUser->wilayah_id);
    //             }
    //         }
    //     });
    // }

    public function scopeByWilayah($query, $user, $descendants = null): Builder
    {
        switch (true) {
            case $user->hasRole('Admin') || $user->hasRole('Admin Web'):
                return $query->with(['wilayah', 'kartuKeluarga']);
                break;

            case $user->hasRole('Monitor Wilayah'):
                return $query
                    ->with(['wilayah', 'kartuKeluarga'])
                    ->whereHas(
                        'kartuKeluarga',
                        fn ($query) => $query->whereIn('wilayah_id', $descendants)
                    );
                break;

            case $user->hasRole('Operator Wilayah'):
                return $query
                    ->with(['wilayah', 'kartuKeluarga'])
                    ->whereHas(
                        'kartuKeluarga',
                        fn ($query) => $query->where('wilayah_id', $user->wilayah_id)
                    );
                break;

            default:
                return $query;
                break;
        }
    }

    protected function umur(): Attribute
    {
        return Attribute::make(
            get: fn () => round(Carbon::parse($this->tanggal_lahir)->age),
        );
    }

    public function kepalaWilayah(): HasMany
    {
        return $this->hasMany(KepalaWilayah::class, 'kepala_nik', 'nik');
    }

    // public function scopeByWilayah($query, $wilayah_id): Builder
    // {
    //     $struktur = Deskel::getFacadeRoot();

    //     switch ($struktur->struktur) {
    //         case 'Khusus':
    //             return $query->whereHas('kartuKeluarga', function ($query) use ($wilayah_id) {
    //                 $query->where('wilayah_id', $wilayah_id);
    //             });
    //             break;

    //         case 'Dasar':
    //             $level = Wilayah::tree()->find($wilayah_id);

    //             if ($level->depth == 0) {
    //                 $descendants = $level->descendants->pluck('wilayah_id');
    //                 return $query->whereHas('kartuKeluarga', function ($query) use ($descendants) {
    //                     $query->whereIn('wilayah_id', $descendants);
    //                 });
    //             } else {
    //                 return $query->whereHas('kartuKeluarga', function ($query) use ($wilayah_id) {
    //                     $query->where('wilayah_id', $wilayah_id);
    //                 });
    //             }
    //             break;

    //         case 'Lengkap':
    //             $level = Wilayah::tree()->find($wilayah_id);
    //             if ($level->depth == 0) {
    //                 $descendants = $level->descendants()->whereDepth(2)->pluck('wilayah_id');
    //             } elseif ($level->depth == 1) {
    //                 $descendants = $level->descendants->pluck('wilayah_id');
    //             } else {
    //                 return $query->whereHas('kartuKeluarga', function ($query) use ($wilayah_id) {
    //                     $query->where('wilayah_id', $wilayah_id);
    //                 });
    //             }
    //             return $query->whereHas('kartuKeluarga', function ($query) use ($descendants) {
    //                 $query->whereIn('wilayah_id', $descendants);
    //             });
    //             break;

    //         default:
    //             return $query;
    //             break;
    //     }
    // }

    public function lembagas(): BelongsToMany
    {
        return $this->belongsToMany(Lembaga::class, 'lembaga_anggotas', 'anggota_id', 'lembaga_id')->withPivot('jabatan', 'keterangan')->withTimestamps();
    }


    public function anak(): HasMany
    {
        return $this->hasMany(KesehatanAnak::class, 'nik', 'nik')
            ->whereDate('tanggal_lahir', '>', now()->subYears(5));
    }

    public function kelahiran(): HasMany
    {
        return $this->hasMany(Kelahiran::class, 'nik', 'nik');
    }

    public function kesehatan(): BelongsToMany
    {
        return $this->belongsToMany(Kesehatan::class, 'penduduk_kesehatan', 'nik', 'kes_id')->withTimestamps()->withPivot('kes_cacat_mental_fisik', 'kes_penyakit_menahun', 'kes_penyakit_lain', 'kes_akseptor_kb');
    }

    public function asuransiKesehatan(): BelongsToMany
    {
        return $this->belongsToMany(AsuransiKesehatan::class, 'penduduk_kesehatan', 'nik', 'as_kes_id')->withTimestamps();
    }

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id', 'kk_id');
    }

    public function scopeKepalaKeluarga($query)
    {
        return $query->where('status_hubungan', StatusHubunganType::KEPALA_KELUARGA->value);
    }

    public function dokumens(): MorphMany
    {
        return $this->morphMany(Dokumen::class, 'dokumenable');
    }
    public function dinamikas(): HasMany
    {
        return $this->hasMany(Dinamika::class, 'nik', 'nik');
    }

    public function kematian(): HasOne
    {
        return $this->hasOne(Kematian::class, 'nik', 'nik');
    }

    public function kepindahan(): HasOne
    {
        return $this->hasOne(Kepindahan::class, 'nik', 'nik');
    }

    public function pendatang(): HasOne
    {
        return $this->hasOne(Pendatang::class, 'nik', 'nik');
    }

    public function tambahans(): MorphToMany
    {
        return $this->morphToMany(Tambahan::class, 'tambahanable', 'tambahanables', 'tambahanable_id', 'tambahan_id')
            ->withPivot('tambahanable_type', 'tambahanable_id', 'tambahanable_ket')
            ->withTimestamps();
    }

    public function bantuans(): MorphToMany
    {
        return $this->morphToMany(Bantuan::class, 'bantuanable', 'bantuanables', 'bantuanable_id', 'bantuan_id')->withTimestamps()->withPivot('bantuanable_type', 'bantuanable_id');
    }

    public function kesehatanAnak(): HasMany
    {
        return $this->hasMany(KesehatanAnak::class, 'anak_id', 'nik')->whereDate('tanggal_lahir', '>', now()->subYears(5));
    }

    public function wilayah(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Wilayah::class,
            KartuKeluarga::class,
            null,
            '',
            [Wilayah::class => 'wilayah_id', KartuKeluarga::class => 'kk_id'],
        );
    }
}
