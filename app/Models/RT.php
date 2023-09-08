<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RT extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'rukun_tetangga';
    protected $primaryKey = 'rt_id';
    protected $fillable = [
        'rt_nama',
        'rw_id'
    ];

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'rw_id', 'rw_id');
    }

    // public function penduduk()
    // {
    //     return $this->hasMany(Penduduk::class, 'rt_id', 'penduduk_id');
    // }
}
