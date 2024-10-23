<?php

namespace App\Services;

use App\Models\Penduduk;
use App\Models\Rekapitulasi;
use App\Models\Wilayah;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class RekapitulasiService
{
    public function getRekapitulasiQuery($bulan, $tahun, $wilayah_id): Builder
    {
        $this->createOrReplaceView($bulan, $tahun, $wilayah_id);

        return Rekapitulasi::query();
    }

    protected function createOrReplaceView($bulan, $tahun, $wilayah_id)
    {
        // Ambil hasil dari hitungRekapitulasiQuery
        $rekapitulasiQueryBuilder = $this->hitungRekapitulasiQuery($bulan, $tahun, $wilayah_id);
        $rekapitulasiQuery = $rekapitulasiQueryBuilder->toSql();
        $bindings = $rekapitulasiQueryBuilder->getBindings();

        // Replace ? dengan binding
        foreach ($bindings as $binding) {
            $rekapitulasiQuery = preg_replace('/\?/', $this->quoteBinding($binding), $rekapitulasiQuery, 1);
        }

        // Tambahkan WNA_lk, WNA_pr, WNA_total, WNI_lk, WNI_pr, WNI_total, Total ke dalam query final
        $finalViewQuery = "
    CREATE OR REPLACE VIEW rekapitulasi_view AS
    SELECT ROW_NUMBER() OVER () AS id, Perincian, WNA_lk, WNA_pr, WNA_total, WNI_lk, WNI_pr, WNI_total, Total
    FROM (
        SELECT Perincian,
            SUM(WNA_lk) as WNA_lk,
            SUM(WNA_pr) as WNA_pr,
            SUM(WNA_total) as WNA_total,
            SUM(WNI_lk) as WNI_lk,
            SUM(WNI_pr) as WNI_pr,
            SUM(WNI_total) as WNI_total,
            SUM(WNI_total + WNA_total) as Total
        FROM ($rekapitulasiQuery) AS subquery
        GROUP BY Perincian
        UNION ALL
        SELECT
            'Penduduk Akhir' as Perincian,
            SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN WNA_lk ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Kelahiran' THEN WNA_lk ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Pendatang' THEN WNA_lk ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kematian' THEN WNA_lk ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kepindahan' THEN WNA_lk ELSE 0 END) as WNA_lk,
            SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN WNA_pr ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Kelahiran' THEN WNA_pr ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Pendatang' THEN WNA_pr ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kematian' THEN WNA_pr ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kepindahan' THEN WNA_pr ELSE 0 END) as WNA_pr,
            SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN WNA_total ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Kelahiran' THEN WNA_total ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Pendatang' THEN WNA_total ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kematian' THEN WNA_total ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kepindahan' THEN WNA_total ELSE 0 END) as WNA_total,
            SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN WNI_lk ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Kelahiran' THEN WNI_lk ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Pendatang' THEN WNI_lk ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kematian' THEN WNI_lk ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kepindahan' THEN WNI_lk ELSE 0 END) as WNI_lk,
            SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN WNI_pr ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Kelahiran' THEN WNI_pr ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Pendatang' THEN WNI_pr ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kematian' THEN WNI_pr ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kepindahan' THEN WNI_pr ELSE 0 END) as WNI_pr,
            SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN WNI_total ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Kelahiran' THEN WNI_total ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Pendatang' THEN WNI_total ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kematian' THEN WNI_total ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kepindahan' THEN WNI_total ELSE 0 END) as WNI_total,
            SUM(CASE WHEN Perincian = 'Penduduk Awal' THEN WNI_total + WNA_total ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Kelahiran' THEN WNI_total + WNA_total ELSE 0 END)
            + SUM(CASE WHEN Perincian = 'Pendatang' THEN WNI_total + WNA_total ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kematian' THEN WNI_total + WNA_total ELSE 0 END)
            - SUM(CASE WHEN Perincian = 'Kepindahan' THEN WNI_total + WNA_total ELSE 0 END) as Total
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
        Log::info("Tahun: $tahun, Bulan: $bulan");

        return $this->baseQuery($wilayah_id)
            ->selectRaw("
            'Penduduk Awal' as Perincian,
            COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_lk,
            COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_pr,
            COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_total,
            COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_lk,
            COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_pr,
            COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_total
        ")
            ->whereRaw("
            (YEAR(penduduk.updated_at) < ? OR (YEAR(penduduk.updated_at) = ? AND MONTH(penduduk.updated_at) < ?))
            AND (penduduk.status_dasar = 'HIDUP' OR penduduk.status_dasar IS NULL)
        ", [$tahun, $tahun, $bulan])
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                    'Kelahiran' as Perincian,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_total,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_total
                ")
                    ->whereRaw("
                    MONTH(penduduk.created_at) = ? AND YEAR(penduduk.created_at) = ? AND penduduk.status_dasar = 'LAHIR'
                ", [$bulan, $tahun])
            )
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                    'Pendatang' as Perincian,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_total,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_total
                ")
                    ->whereRaw("
                    MONTH(penduduk.created_at) = ? AND YEAR(penduduk.created_at) = ? AND penduduk.status_dasar = 'HIDUP'
                ", [$bulan, $tahun])
            )
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                    'Kematian' as Perincian,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_total,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_total
                ")
                    ->whereRaw("
                    MONTH(penduduk.updated_at) = ? AND YEAR(penduduk.updated_at) = ? AND penduduk.status_dasar = 'MENINGGAL'
                ", [$bulan, $tahun])
            )
            ->unionAll(
                $this->baseQuery($wilayah_id)
                    ->selectRaw("
                    'Kepindahan' as Perincian,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as WNA_total,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'LAKI-LAKI' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_lk,
                    COALESCE(SUM(CASE WHEN penduduk.jenis_kelamin = 'PEREMPUAN' AND penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_pr,
                    COALESCE(SUM(CASE WHEN penduduk.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as WNI_total
                ")
                    ->whereRaw("
                    MONTH(penduduk.updated_at) = ? AND YEAR(penduduk.updated_at) = ? AND penduduk.status_dasar = 'PINDAH'
                ", [$bulan, $tahun])
            );
    }
}
