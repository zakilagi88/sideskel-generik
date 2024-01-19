<?php

namespace App\Models;

use App\Enum\Penduduk\{Agama, EtnisSuku, GolonganDarah, JenisKelamin, Kewarganegaraan, Pekerjaan, Pendidikan, Pengajuan, Perkawinan, Status, StatusHubungan, StatusTempatTinggal};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, MorphToMany};
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Penduduk extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'kewarganegaraan',
        'ayah',
        'ibu',
        'golongan_darah',
        'status',
        'status_pengajuan',
        'status_tempat_tinggal',
        'status_hubungan',
        'etnis_suku',
        'alamat',
        'alamatKK',
        'telepon',
        'email',
        'wilayah_id',
        'updated_at',

    ];

    protected $casts =
    [
        'agama' => Agama::class,
        'jenis_kelamin' => JenisKelamin::class,
        'pendidikan' => Pendidikan::class,
        'pekerjaan' => Pekerjaan::class,
        'etnis_suku' => EtnisSuku::class,
        'status' => Status::class,
        'status_pengajuan' => Pengajuan::class,
        'status_perkawinan' => Perkawinan::class,
        'status_tempat_tinggal' => StatusTempatTinggal::class,
        'status_hubungan' => StatusHubungan::class,
        'golongan_darah' => GolonganDarah::class,
        'kewarganegaraan' => Kewarganegaraan::class,
    ];

    protected $auditInclude = [
        'nik',
        'kk_id',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'kewarganegaraan',
        'ayah',
        'ibu',
        'golongan_darah',
        'status',
        'status_pengajuan',
        'status_tempat_tinggal',
        'status_hubungan',
        'etnis_suku',
        'alamat',
        'alamatKK',
        'telepon',
        'email',
        'wilayah_id',
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

    public function bantuans(): MorphToMany
    {
        return $this->morphToMany(Bantuan::class, 'bantuanable');
    }

    public function scopeAllPekerjaan($query, $wilayahId = null, $jk = null)
    {
        return $query
            ->select('pekerjaan', 'jenis_kelamin', DB::raw('count(*) as total'))
            ->when($wilayahId, function ($query, $wilayahId) {
                return $query->where('wilayah_id', $wilayahId);
            })
            ->when($jk, function ($query, $jk) {
                return $query->where('jenis_kelamin', $jk);
            })
            ->groupBy('pekerjaan', 'jenis_kelamin')
            ->orderBy('total', 'desc');
    }

    public function scopeSearchPekerjaan($query, $search)
    {
        return $query->where('pekerjaan', 'like', '%' . $search . '%');
    }

    public function scopeAllAgama($query, $wilayahId = null, $jk = null)
    {
        return $query
            ->select('agama', 'jenis_kelamin', DB::raw('count(*) as total'))
            ->when($wilayahId, function ($query, $wilayahId) {
                return $query->where('wilayah_id', $wilayahId);
            })
            ->when($jk, function ($query, $jk) {
                return $query->where('jenis_kelamin', $jk);
            })
            ->groupBy('agama', 'jenis_kelamin')
            ->orderBy('total', 'desc');
    }


    public function scopeSearchAgama($query, $search)
    {
        return $query->where('agama', 'like', '%' . $search . '%');
    }

    public function scopeAllPendidikan($query, $wilayahId = null, $jk = null)
    {
        return $query
            ->select('pendidikan', 'jenis_kelamin', DB::raw('count(*) as total'))
            ->when($wilayahId, function ($query, $wilayahId) {
                return $query->where('wilayah_id', $wilayahId);
            })
            ->when($jk, function ($query, $jk) {
                return $query->where('jenis_kelamin', $jk);
            })
            ->groupBy('pendidikan', 'jenis_kelamin')
            ->orderBy('total', 'desc');
    }

    public function scopeSearchPendidikan($query, $search)
    {
        return $query->where('pendidikan', 'like', '%' . $search . '%');
    }

    public function scopeAllPerkawinan($query, $wilayahId = null, $jk = null)
    {
        return $query
            ->select('status_perkawinan', 'jenis_kelamin', DB::raw('count(*) as total'))
            ->when($wilayahId, function ($query, $wilayahId) {
                return $query->where('wilayah_id', $wilayahId);
            })
            ->when($jk, function ($query, $jk) {
                return $query->where('jenis_kelamin', $jk);
            })
            ->groupBy('status_perkawinan', 'jenis_kelamin')
            ->orderBy('total', 'desc');
    }

    public function scopeSearchPerkawinan($query, $search)
    {
        return $query->where('status_perkawinan', 'like', '%' . $search . '%');
    }

    public function scopeAllEtnisSuku($query, $wilayahId = null, $jk = null)
    {
        return $query
            ->select('etnis_suku', 'jenis_kelamin', DB::raw('count(*) as total'))
            ->when($wilayahId, function ($query, $wilayahId) {
                return $query->where('wilayah_id', $wilayahId);
            })
            ->when($jk, function ($query, $jk) {
                return $query->where('jenis_kelamin', $jk);
            })
            ->groupBy('etnis_suku', 'jenis_kelamin')
            ->orderBy('total', 'desc');
    }

    public function scopeSearchEtnisSuku($query, $search)
    {
        return $query->where('etnis_suku', 'like', '%' . $search . '%');
    }

    public function scopeAllRentangUmur($query, $wilayahId = null)
    {
        return $query
            ->select('jenis_kelamin')
            ->selectRaw('CASE
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) >= 75 THEN "75+"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 0 AND 4 THEN "0-4"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 5 AND 9 THEN "5-9"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 10 AND 14 THEN "10-14"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 15 AND 19 THEN "15-19"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 20 AND 24 THEN "20-24"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 25 AND 29 THEN "25-29"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 30 AND 34 THEN "30-34"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 35 AND 39 THEN "35-39"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 40 AND 44 THEN "40-44"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 45 AND 49 THEN "45-49"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 50 AND 54 THEN "50-54"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 55 AND 59 THEN "55-59"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 60 AND 64 THEN "60-64"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 65 AND 69 THEN "65-69"
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) BETWEEN 70 AND 74 THEN "70-74"
            ELSE "75+"
            END as rentang_umur')
            ->selectRaw('COUNT(*) as total')
            ->when($wilayahId, function ($query, $wilayahId) {
                return $query->where('wilayah_id', $wilayahId);
            })
            ->groupBy('rentang_umur', 'jenis_kelamin')
            ->orderBy('rentang_umur', 'asc');
    }

    // public function scopeSearchRentangUmur($query, $search)
    // {
    //     return $query->where('rentang_umur', 'like', '%' . $search . '%');
    // }

    public function scopeAllSatuanUmur($query, $wilayahId = null)
    {
        return $query
            ->select('jenis_kelamin')
            ->selectRaw('CASE
            WHEN YEAR(NOW()) - YEAR(tanggal_lahir) >= 75 THEN "75+"
            ELSE CAST(YEAR(NOW()) - YEAR(tanggal_lahir) AS CHAR) 
            END as satuan_umur')
            ->selectRaw('COUNT(*) as total')
            ->when($wilayahId, function ($query, $wilayahId) {
                return $query->where('wilayah_id', $wilayahId);
            })
            ->groupBy('satuan_umur', 'jenis_kelamin')
            ->orderBy('satuan_umur', 'asc');
    }


    public function getWilayahPDD()
    {
        return $this->kartuKeluarga->wilayah_id;
    }
}
