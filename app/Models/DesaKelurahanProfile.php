<?php

namespace App\Models;

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
        'dasar_hukum_bentuk',
        'aparatur_id',
        'tipologi',
        'klasifikasi',
        'kategori',
        'orbitrasi', // 'orbitasi' => 'array',
        'luaswilayah', // 'luaswilayah' => 'array',
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

    ];

    protected $casts = [
        'luaswilayah' => 'array',
        'orbitrasi' => 'array',
        'jmlh_pdd' => 'integer',
        'status' => 'boolean',
        'tanah_kas' => 'double',
    ];

    public function getLogo(): string
    {
        return $this->logo ? Storage::url($this->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($this->dk->deskel_nama) . '&background=random&size=512';
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
