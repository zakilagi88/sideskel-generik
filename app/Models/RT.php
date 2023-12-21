<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
        'rw_id',
    ];

    public function kepala(): MorphOne
    {
        return $this->morphOne(KepalaEntitas::class, 'entitas');
    }
}
