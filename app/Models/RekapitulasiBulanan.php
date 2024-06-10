<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekapitulasiBulanan extends Model
{
    use HasFactory;

    protected $table = 'rekapitulasi_view';

    protected $fillable = [
        'id',
        'Perincian',
        'Laki_Laki',
        'Perempuan',
        'Total',
    ];
}
