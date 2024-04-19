<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatKategori extends Model
{
    use HasFactory;

    protected $table = 'stat_kategoris';

    protected $fillable = [
        'nama'
    ];

    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class, 'stat_kategori_id');
    }
}
