<?php

namespace App\Models;

use App\Models\Desa\Aparatur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans';

    protected $fillable = [
        'nama',
        'tupoksi',
    ];

    public function aparaturs(): HasMany
    {
        return $this->hasMany(Aparatur::class, 'id', 'jabatan_id');
    }
}
