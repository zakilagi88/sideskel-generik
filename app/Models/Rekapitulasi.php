<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rekapitulasi extends Model
{
    use HasFactory;

    protected $table = 'rekapitulasi_view';

    protected $fillable = [
        'id',
        'Perincian',
        'WNA_lk',   // Menambahkan kolom untuk WNA Laki-laki
        'WNA_pr',   // Menambahkan kolom untuk WNA Perempuan
        'WNA_total',       // Kolom total WNA
        'WNI_lk',   // Menambahkan kolom untuk WNI Laki-laki
        'WNI_pr',   // Menambahkan kolom untuk WNI Perempuan
        'WNI_total',       // Kolom total WNI
        'Total',           // Kolom total
    ];
}
