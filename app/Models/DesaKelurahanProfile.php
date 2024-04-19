<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'kepala',
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
        'kantor',
        'prasarana_pendidikan',
        'prasarana_kesehatan',
        'prasarana_ibadah',
        'prasarana_umum',
        'prasarana_transportasi',
        'prasarana_air_bersih',
        'prasarana_sanitasi_irigasi',
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
        'prasarana_pendidikan' => 'array',
        'prasarana_kesehatan' => 'array',
        'prasarana_ibadah' => 'array',
        'prasarana_umum' => 'array',
        'prasarana_transportasi' => 'array',
        'prasarana_air_bersih' => 'array',
        'prasarana_sanitasi_irigasi' => 'array',

    ];


    public function dk(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'deskel_id', 'deskel_id');
    }
}
