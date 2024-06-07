<?php

namespace App\Models\Deskel;

use App\Models\DesaKelurahan;
use App\Models\Deskel\Aparatur;
use App\Models\KabKota;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;

class DesaKelurahanProfile extends Model
{
    use HasFactory;
    use TraitsBelongsToThrough;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'deskel_profils';

    protected $fillable = [
        'deskel_id',
        'sebutan',
        'struktur',
        'alamat',
        'kodepos',
        'thn_bentuk',
        'dasar_hukum_id',
        'kepala_id',
        'tipologi',
        'klasifikasi',
        'kategori',
        'orbitrasi', 
        'luaswilayah', 
        'jmlh_sert_tanah',
        'jmlh_pdd',
        'tanah_kas',
        'koordinat_lat',
        'koordinat_long',
        'bts_utara',
        'bts_timur',
        'bts_selatan',
        'bts_barat',
        'visi',
        'misi',
        'sejarah',
        'gambar',
        'logo',
        'telepon',
        'email',
        'status',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
    ];

    protected $casts = [
        'logo' => 'array',
        'gambar' => 'array',
        'luaswilayah' => 'array',
        'orbitrasi' => 'array',
        'jmlh_pdd' => 'integer',
        'status' => 'boolean',
        'tanah_kas' => 'double',
    ];

    public function getLogo(): string
    {
        return $this->logo ? Storage::url($this->logo) : Storage::url('images/logo.png');
    }

    public function getGambar(): string
    {
        return $this->gambar ? Storage::url($this->gambar) : Storage::url('images/bg-kantor.png');
    }

    public function Dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'dasar_hukum_id', 'id');
    }

    public function Aparatur(): BelongsTo
    {
        return $this->belongsTo(Aparatur::class, 'aparatur_id', 'kepala_id');
    }


    public function dk(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'deskel_id', 'deskel_id');
    }

    public function kec(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Kecamatan::class,
            DesaKelurahan::class,
            null,
            '',
            [Kecamatan::class => 'kec_id', DesaKelurahan::class => 'deskel_id'],
        );
    }

    public function kabkota(): BelongsToThrough
    {
        return $this->belongsToThrough(
            KabKota::class,
            [Kecamatan::class, DesaKelurahan::class],
            null,
            '',
            [KabKota::class => 'kabkota_id', Kecamatan::class => 'kec_id', DesaKelurahan::class => 'deskel_id']
        );
    }

    public function prov(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Provinsi::class,
            [KabKota::class, Kecamatan::class, DesaKelurahan::class],
            null,
            '',
            [Provinsi::class => 'prov_id', KabKota::class => 'kabkota_id', Kecamatan::class => 'kec_id', DesaKelurahan::class => 'deskel_id']
        );
    }

    public function getLinkLabel(): string
    {
        return $this->id . ' - ' . $this->dk?->deskel_nama;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}