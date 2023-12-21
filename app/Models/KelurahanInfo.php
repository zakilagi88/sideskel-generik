<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelurahanProfil extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'kelurahan_profil';

    protected $primaryKey = 'kelurahan_profil_id';

    protected $fillable = [
        'kelurahan_profil_id',
        'kel_id',
        'prov_id',
        'kab_id',
        'kec_id',

        'kel_nama',
        'kel_alamat',
        'kel_telepon',
        'kel_email',

    ];
}
