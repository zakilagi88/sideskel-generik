<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Dusun extends Model
{
    use HasFactory;

    protected $table = 'dusun';

    protected $primaryKey = 'dusun_id';

    protected $fillable = [
        'dusun_id',
        'dusun_nama',
        'deskel_id',
    ];

    public function kelurahan()
    {
        return $this->belongsTo(DesaKelurahan::class, 'deskel_id', 'deskel_id');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'wilayah');
    }

    public function rws(): HasMany
    {
        return $this->hasMany(RW::class, 'dusun_id', 'dusun_id');
    }
}
