<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        // 'kelurahan_id'

    ];

    // public function kelurahan()
    // {
    //     return $this->belongsTo(Kelurahan::class, 'rw_id', 'rw_id');
    // }

    public function rt_group(): HasMany
    {
        return $this->hasMany(RT::class, 'rw_id', 'rw_id');
    }
}
