<?php

namespace App\Models;

use App\Enums\Kependudukan\Hubungan;
use App\Rules\UniqueKepalaKeluarga;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AnggotaKeluarga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'anggota_keluarga';
    protected $primaryKey = 'anggota_id';
    public $incrementing = false;


    protected $fillable = [
        'anggota_id',
        'nik',
        'kk_id',
        'hubungan',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hubungan' => Hubungan::class,
    ];

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id', 'kk_id');
    }
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }
}
