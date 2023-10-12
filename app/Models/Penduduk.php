<?php

namespace App\Models;

use App\Enum\Penduduk\Agama;
use App\Enum\Penduduk\JenisKelamin;
use App\Enum\Penduduk\Pekerjaan;
use App\Enum\Penduduk\Pendidikan;
use App\Enum\Penduduk\Pengajuan;
use App\Enum\Penduduk\Pernikahan;
use App\Enum\Penduduk\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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


    protected $table = 'penduduks';

    protected $primaryKey = 'nik';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'status_pernikahan',
        'pekerjaan',
        'status',
        'alamat',
        'alamatKK',
        'status_pengajuan',
        'updated_at'
    ];

    protected $casts =
    [
        'agama' => Agama::class,
        'jenis_kelamin' => JenisKelamin::class,
        'pendidikan' => Pendidikan::class,
        'status_pernikahan' => Pernikahan::class,
        'pekerjaan' => Pekerjaan::class,
        'status' => Status::class,
        'status_pengajuan' => Pengajuan::class,
    ];

    protected $auditInclude = [
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'status_pernikahan',
        'pekerjaan',
        'status',
        'alamat',
        'alamatKK',
        'status_pengajuan',
        'updated_at'
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

    public function anggotaKeluarga(): HasOne
    {
        return $this->hasOne(AnggotaKeluarga::class, 'nik', 'nik');
    }

    public function kesehatan(): BelongsToMany
    {
        return $this->belongsToMany(Kesehatan::class, 'penduduk_kesehatan', 'nik', 'kesehatan_id')->withTimestamps();
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id', 'id');
    }

    public function kartuKeluarga(): HasOneThrough
    {
        return $this->hasOneThrough(KartuKeluarga::class, AnggotaKeluarga::class, 'nik', 'kk_id', 'nik', 'kk_id');
    }
}
