<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RW extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'rukun_warga';
    protected $primaryKey = 'rw_id';
    protected $fillable = [
        'rw_id',
        'rw_nama',
        'kel_id',
        'dusun_id',

    ];

    public function kepala(): MorphOne
    {
        return $this->morphOne(KepalaEntitas::class, 'entitas');
    }

    public function rts(): HasMany
    {
        return $this->hasMany(RT::class, 'rw_id', 'rw_id');
    }
}