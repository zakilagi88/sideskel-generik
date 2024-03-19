<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'penduduk_aggregat';

    protected $fillable = [
        'Perincian',
        'Laki_Laki',
        'Perempuan',
        'Total',
        'tahun',
        'bulan',
        'jumlah_kumulatif_penduduk_awal'
    ];
}
