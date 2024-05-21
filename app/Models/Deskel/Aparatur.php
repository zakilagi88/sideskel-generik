<?php

namespace App\Models\Deskel;

use App\Models\Deskel\Jabatan;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aparatur extends Model
{
    use HasFactory;

    protected $table = 'aparaturs';

    protected $fillable = [
        'nama',
        'slug',
        'niap',
        'nip',
        'foto',
        'jabatan_id',
        'pangkat_golongan',
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
        'masa_jabatan',
        'keterangan',
    ];

    protected $casts = [];

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }

    public function getLinkLabel(): string
    {
        return $this->nama;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
