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
        'deskel_sebutan',
        'deskel_tipe',
        'deskel_alamat',
        'deskel_kodepos',
        'deskel_thn_pembentukan',
        'deskel_dasar_hukum_pembentukan',
        'deskel_kepala',
        'deskel_luaswilayah',
        'deskel_jumlahpenduduk',
        'deskel_batas_utara',
        'deskel_batas_timur',
        'deskel_batas_selatan',
        'deskel_batas_barat',
        'deskel_visi',
        'deskel_misi',
        'deskel_sejarah',
        'deskel_gambar',
        'deskel_logo',
        'deskel_telepon',
        'deskel_email',
        'deskel_status',

    ];

    protected $casts = [
        'deskel_luaswilayah' => 'double',
        'deskel_jumlahpenduduk' => 'integer',
        'deskel_status' => 'boolean',
    ];


    public function dk(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'deskel_id', 'deskel_id');
    }
}
