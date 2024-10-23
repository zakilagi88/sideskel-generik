<?php

namespace App\Services;

use App\Models\Penduduk;
use App\Models\RekapitulasiBulanan;
use App\Models\Wilayah;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class RekapitulasiService
{
    public function getRekapitulasiQuery($bulan, $tahun, $wilayah_id): Builder
    {
        $this->createOrReplaceView($bulan, $tahun, $wilayah_id);

        return RekapitulasiBulanan::query();
    }

    protected function createOrReplaceView($bulan, $tahun, $wilayah_id)
    {
        // ubah jadi raw query
        $rekapitulasiQuery = $this->hitungRekapitulasiQuery($bulan, $tahun, $wilayah_id)->toSql();
        $bindings = $this->hitungRekapitulasiQuery($bulan, $tahun, $wilayah_id)->getBindings();

        // replace ? dengan binding
        foreach ($bindings as $binding) {
            $rekapitulasiQuery = preg_replace('/\?/', $this->quoteBinding($binding), $rekapitulasiQuery, 1);
        }

        $finalViewQuery = "
            CREATE OR REPLACE VIEW rekapitulasi_view AS
            SELECT ROW_NUMBER() OVER () AS id, Perincian, Laki_Laki, Perempuan, Total
            FROM (
                SELECT Perincian, SUM(Laki_Laki) as Laki_Laki, SUM(Perempuan) as Perempuan, SUM(Total) as Total
                FROM ($rekapitulasiQuery) AS subquery
                GROUP BY Perincian
                UNION ALL
                SELECT
                    'Penduduk Akhir' as Perincian,
                    SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN Laki_Laki ELSE 0 END)
                    + SUM(CASE WHEN Perincian = 'Kelahiran' THEN Laki_Laki ELSE 0 END)
                    + SUM(CASE WHEN Perincian = 'Pendatang' THEN Laki_Laki ELSE 0 END)
                    - SUM(CASE WHEN Perincian = 'Kematian' THEN Laki_Laki ELSE 0 END)
                    - SUM(CASE WHEN Perincian = 'Kepindahan' THEN Laki_Laki ELSE 0 END) as Laki_Laki,
                    SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN Perempuan ELSE 0 END)
                    + SUM(CASE WHEN Perincian = 'Kelahiran' THEN Perempuan ELSE 0 END)
                    + SUM(CASE WHEN Perincian = 'Pendatang' THEN Perempuan ELSE 0 END)
                    - SUM(CASE WHEN Perincian = 'Kematian' THEN Perempuan ELSE 0 END)
                    - SUM(CASE WHEN Perincian = 'Kepindahan' THEN Perempuan ELSE 0 END) as Perempuan,
                    SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN Total ELSE 0 END)
                    + SUM(CASE WHEN Perincian = 'Kelahiran' THEN Total ELSE 0 END)
                    + SUM(CASE WHEN Perincian = 'Pendatang' THEN Total ELSE 0 END)
                    - SUM(CASE WHEN Perincian = 'Kematian' THEN Total ELSE 0 END)
                    - SUM(CASE WHEN Perincian = 'Kepindahan' THEN Total ELSE 0 END) as Total
                FROM ($rekapitulasiQuery) AS subquery
            ) AS final_subquery
        ";

        DB::statement($finalViewQuery);
    }

    protected function quoteBinding($binding)
    {
        if (is_string($binding)) {
            return "'" . addslashes($binding) . "'";
        }

        return $binding;
    }

    protected function baseQuery($wilayah_id): Builder
    {
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;

        return Penduduk::query()
            ->leftJoin('kartu_keluarga as kk', 'penduduk.kk_id', '=', 'kk.kk_id')
            ->leftJoin('wilayah as w', 'kk.wilayah_id', '=', 'w.wilayah_id')
            ->byWilayah($authUser, $descendants);
    }

    protected function hitungRekapitulasiQuery($bulan, $tahun, $wilayah_id): Builder
    {

        return $this->baseQuery($wilayah_id)
            ->selectRaw("
                'Penduduk Awal' as Perincian,
                COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0) as Laki_Laki,
                COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0) as Perempuan,
                COALESCE(COUNT(*), 0) as Total
            ")
            ->whereRaw("
                (YEAR(penduduk.created_at) < ? OR (YEAR(penduduk.created_at) = ? AND MONTH(penduduk.created_at) < ?))
                AND (penduduk.status_dasar = 'HIDUP' OR penduduk.status_dasar IS NULL)
            ", [$tahun, $tahun, $bulan])
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                        'Kelahiran' as Perincian,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0) as Laki_Laki,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0) as Perempuan,
                        COALESCE(COUNT(*), 0) as Total
                    ")
                    ->whereRaw("
                        MONTH(penduduk.created_at) = ? AND YEAR(penduduk.created_at) = ? AND penduduk.status_dasar = 'LAHIR'
                    ", [$bulan, $tahun])
            )
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                        'Pendatang' as Perincian,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0) as Laki_Laki,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0) as Perempuan,
                        COALESCE(COUNT(*), 0) as Total
                    ")
                    ->whereRaw("
                        MONTH(penduduk.created_at) = ? AND YEAR(penduduk.created_at) = ? AND penduduk.status_dasar = 'HIDUP' AND penduduk.status_dasar != 'LAHIR'
                    ", [$bulan, $tahun])
            )
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                        'Kematian' as Perincian,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0) as Laki_Laki,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0) as Perempuan,
                        COALESCE(COUNT(*), 0) as Total
                    ")
                    ->whereRaw("
                        MONTH(penduduk.updated_at) = ? AND YEAR(penduduk.updated_at) = ? AND penduduk.status_dasar = 'MENINGGAL'
                    ", [$bulan, $tahun])
            )
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                        'Kepindahan' as Perincian,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0) as Laki_Laki,
                        COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0) as Perempuan,
                        COALESCE(COUNT(*), 0) as Total
                    ")
                    ->whereRaw("
                        MONTH(penduduk.updated_at) = ? AND YEAR(penduduk.updated_at) = ? AND penduduk.status_dasar = 'PINDAH'
                    ", [$bulan, $tahun])
            );
    }
}
