<?php

namespace App\Models\Deskel;

use App\Models\Deskel\Jabatan;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Aparatur extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];

    protected $table = 'aparaturs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', // tambahkan 'id' agar bisa diisi sort
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

    protected $casts = [
        'foto' => 'array',
    ];

    public function getFotoUrl()
    {
        return $this->foto ? Storage::url($this->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=random';
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
