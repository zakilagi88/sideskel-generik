<?php

namespace App\Models\Desa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aparatur extends Model
{
    use HasFactory;

    protected $table = 'aparaturs';

    protected $fillable = [
        'nama',
        'niap',
        'nip',
        'foto',
        'jabatan',
        'pangkat',
        'golongan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'no_kep_pengangkatan',
        'tgl_kep_pengangkatan',
        'no_kep_pemberhentian',
        'tgl_kep_pemberhentian',
        'status_pegawai',
        'keterangan',
    ];
}