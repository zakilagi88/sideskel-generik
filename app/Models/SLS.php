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
    protected $fillable = [
        'sls_id',
        'sls_kode',
        'sls_nama',
        'rt_id',
        'rw_id',
        'kel_id',
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

    public function kel_groups(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'kel_id', 'kel_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_sls_roles', 'sls_id', 'user_id')->as('sls')->withPivot('role_id')->withTimestamps();
    }
}