<?php

namespace App\Models;

use App\Models\Deskel\DesaKelurahanProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;

class DesaKelurahan extends Model
{
    use HasFactory;
    use TraitsBelongsToThrough;


    protected $table = 'desa_kelurahan';

    protected $primaryKey = 'deskel_id';

    protected $keyType = 'string';

    protected $fillable = [
        'deskel_id',
        'kec_id',
        'deskel_nama',

    ];

    public function kec(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id', 'kec_id');
    }

    public function kabkota(): BelongsToThrough
    {
        return $this->belongsToThrough(
            KabKota::class,
            Kecamatan::class,
            null,
            '',
            [KabKota::class => 'kabkota_id', Kecamatan::class => 'kec_id']
        );
    }

    public function prov(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Provinsi::class,
            [KabKota::class, Kecamatan::class],
            null,
            '',
            [Provinsi::class => 'prov_id', KabKota::class => 'kabkota_id', Kecamatan::class => 'kec_id']
        );
    }


    public function wilayahs(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'deskel_id', 'deskel_id');
    }

    public function kelurahan_profil(): HasOne
    {
        return $this->hasOne(DesaKelurahanProfile::class, 'deskel_id', 'deskel_id');
    }
}
