<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SLS extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];
    protected $table = 'sls';
    protected $primaryKey = 'sls_id';
    // public $keyType = 'string';
    protected $fillable = [
        'sls_id',
        'sls_kode',
        'sls_nama',
        'rw_id',
        'rt_id',
    ];

    public function rw_groups(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'rw_id', 'rw_id');
    }


    public function rt_groups(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'rt_id', 'rt_id');
    }

    public function sls_kk(): HasMany
    {
        return $this->hasMany(KartuKeluarga::class, 'sls_id', 'sls_id');
    }

    public function sls_pdd(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, KartuKeluarga::class, 'sls_id', 'kk_id', 'sls_id', 'kk_id');
    }

    // public function getSlsNamaAttribute()
    // {
    //     $rw_nama = $this->rw_groups->rw_nama;
    //     $rt_nama = $this->rt_groups->rt_nama;

    //     return "{$rw_nama} / {$rt_nama}";
    // }

    // public function setSlsNamaAttribute($value)
    // {
    //     // Memastikan sls_nama mengikuti accessor
    //     $this->attributes['sls_nama'] = $this->getSlsNamaAttribute();
    // }


    // public static function generateUniqueSlsKode($rw_id, $rt_id)
    // {
    //     // Mencari kode sls terbaru dengan kombinasi rw_id dan rt_id
    //     $latestSLS = SLS::where('rw_id', $rw_id)
    //         ->where('rt_id', $rt_id)
    //         ->orderBy('sls_kode', 'desc')
    //         ->first();

    //     // Jika sudah ada kode sls sebelumnya, tambahkan 1
    //     if ($latestSLS) {
    //         $nextCode = (int) $latestSLS->sls_kode + 1;
    //     } else {
    //         $nextCode = 1; // Jika belum ada kode sls, mulai dari 1
    //     }

    //     // Format kode sls menjadi 4 digit dengan leading zeros
    //     $formattedCode = sprintf('%04d', $nextCode);

    //     return $formattedCode;
    // }




    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($sls) {
    //         // Saat akan menyimpan data, lakukan validasi unik kombinasi rw_id dan rt_id
    //         $sls->validateUniqueRWRTCombination();

    //         $sls->sls_kode = $sls->generateUniqueSlsKode($sls->rw_id, $sls->rt_id);
    //     });
    // }

    // public function validateUniqueRWRTCombination()
    // {
    //     $rules = [
    //         'rw_id' => [
    //             'required',
    //             'exists:rukun_warga,rw_id', // Pastikan rw_id ada dalam tabel rukun_warga
    //         ],
    //         'rt_id' => [
    //             'required',
    //             'exists:rukun_tetangga,rt_id', // Pastikan rt_id ada dalam tabel rukun_tetangga
    //             Rule::unique('sls')->where(function ($query) {
    //                 // Validasi unik kombinasi rw_id dan rt_id dalam tabel sls
    //                 return $query->where('rw_id', $this->rw_id)
    //                     ->where('rt_id', $this->rt_id);
    //             })->ignore($this->getKey()), // Ignore saat memperbarui
    //         ],
    //     ];

    //     $validator = Validator::make($this->attributes, $rules);

    //     if ($validator->fails()) {
    //         throw new \Exception($validator->messages()->first());
    //     }
    // }
}
