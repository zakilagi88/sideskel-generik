<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KartuKeluarga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'kartu_keluarga';
    protected $primaryKey = 'kk_id';
    protected $fillable = [
        'kk_no',
        'kk_alamat',
        'rt_id',
        'rw_id',
    ];

    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'kk_id', 'kk_id');
    }

    public function rt()
    {
        return $this->belongsTo(RT::class, 'kk_id', 'kk_id');
    }

    public function rw()
    {
        return $this->belongsTo(RW::class, 'kk_id', 'kk_id');
    }
}
