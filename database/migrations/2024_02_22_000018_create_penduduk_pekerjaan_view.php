<?php

use App\Enums\Kependudukan\PekerjaanType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement($this->createView());
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        $pekerjaan = PekerjaanType::cases();
        $pekerjaanEnum = array_map(function ($enum) {
            return $enum->value;
        }, $pekerjaan);

        $unionQuery = collect($pekerjaanEnum)->map(function ($pekerjaan) {
            return "SELECT '$pekerjaan' AS pekerjaan,0 AS laki_laki, 0 AS perempuan";
        })->implode(' UNION ALL ');

        return <<<SQL
            CREATE VIEW penduduk_pekerjaan_view AS
            SELECT 
                ROW_NUMBER() OVER (ORDER BY enum_values.pekerjaan) AS id,
                enum_values.pekerjaan, 
                COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = 'Laki-laki' THEN 1 ELSE 0 END), 0) AS laki_laki,
                COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = 'Perempuan' THEN 1 ELSE 0 END), 0) AS perempuan,
                COALESCE(MAX(wilayah.wilayah_id), 0) AS wilayah_id,
                COALESCE(MAX(wilayah.parent_id), 0) AS parent_id,
                COALESCE(COUNT(penduduk.nik), 0) AS total
            FROM (
                $unionQuery
            ) AS enum_values
            LEFT JOIN penduduk ON penduduk.pekerjaan = enum_values.pekerjaan
            LEFT JOIN kartu_keluarga ON penduduk.kk_id = kartu_keluarga.kk_id
            LEFT JOIN wilayah ON kartu_keluarga.wilayah_id = wilayah.wilayah_id
            WHERE LOWER(penduduk.status_dasar) = 'hidup' OR penduduk.pekerjaan IS NULL
            GROUP BY wilayah.wilayah_id,wilayah.parent_id, enum_values.pekerjaan;
        SQL;
    }

    /**
     * Drop the view.
     */

    private function dropView(): string
    {
        return
            <<<SQL
                DROP VIEW IF EXISTS penduduk_pekerjaan_view
            SQL;
    }
};
