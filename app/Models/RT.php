<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RT extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'rukun_tetangga';

    protected $casts = [
        'rts' => 'array'
    ];

    protected $primaryKey = 'rt_id';
    protected $fillable = [
        'rt_id',
        'rt_nama',
        // 'rw_id'
    ];



    // public function rw_group(): BelongsTo
    // {
    //     return $this->belongsTo(RW::class, 'rw_id', 'rw_id');
    // }


    // public function sls_group(): BelongsToMany
    // {
    //     return $this->belongsToMany(SLS::class, 'sls', 'rw_id', 'rt_id');
    // }


    // public function penduduk()
    // {
    //     return $this->hasMany(Penduduk::class, 'rt_id', 'penduduk_id');
    // }
}