<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    use HasFactory;

    protected $table = 'dusun';

    protected $primaryKey = 'dusun_id';

    protected $fillable = [
        'dusun_id',
        'dusun_nama',
        'kel_id',
    ];

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kel_id', 'kel_id');
    }
}
