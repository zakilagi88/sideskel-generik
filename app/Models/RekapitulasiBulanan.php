<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekapitulasiBulanan extends Model
{
    use HasFactory;

    protected $table = 'rekapitulasi';

    public static function getRekapitulasi($bulan, $tahun, $wilayah_id = null)
    {
        // Panggil stored procedure untuk mengisi tabel sementara Rekapitulasi
        DB::statement('CALL sp_rekapitulasi(?, ?, ?)', [$bulan, $tahun, $wilayah_id]);

        // Ambil hasil dari tabel sementara Rekapitulasi
        return static::query()
            ->from('rekapitulasi');
    }
}
