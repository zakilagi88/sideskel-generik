<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;

    // // Kelurahan.php (Model)
    // protected $primaryKey = 'kode_kelurahan';

    // public $incrementing = true; // Set to true for auto-incrementpa

    protected $keyType = 'string'; // Set the primary key data type

    protected $table = 'kelurahan';

    protected $fillable = [
        'kelurahan_id',
        'kelurahan_nama',
    ];

    public function penduduk()
    {
        return $this->hasMany(Penduduk::class);
    }

    public function RW()
    {
        return $this->hasMany(RW::class);
    }

    public function RT()
    {
        return $this->hasMany(RT::class);
    }
}
