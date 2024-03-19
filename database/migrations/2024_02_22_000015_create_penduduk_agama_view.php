<?php

use App\Enums\Kependudukan\AgamaType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use function PHPSTORM_META\map;

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
        $agama = AgamaType::cases();
        $agamaEnum = array_map(function ($enum) {
            return $enum->value;
        }, $agama);

        $unionQuery = collect($agamaEnum)->map(function ($agama) {
            return "SELECT '$agama' AS agama,0 AS laki_laki, 0 AS perempuan";
        })->implode(' UNION ALL ');

        return <<<SQL
            CREATE VIEW penduduk_agama_view AS
            SELECT 
                ROW_NUMBER() OVER (ORDER BY enum_values.agama) AS id,
                enum_values.agama, 
                COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = 'Laki-laki' THEN 1 ELSE 0 END), 0) AS laki_laki,
                COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = 'Perempuan' THEN 1 ELSE 0 END), 0) AS perempuan,
                COALESCE(MAX(wilayah.wilayah_id), 0) AS wilayah_id,
                COALESCE(MAX(wilayah.parent_id), 0) AS parent_id,
                COALESCE(COUNT(penduduk.nik), 0) AS total
            FROM (
                $unionQuery
            ) AS enum_values
            LEFT JOIN penduduk ON penduduk.agama = enum_values.agama
            LEFT JOIN kartu_keluarga ON penduduk.kk_id = kartu_keluarga.kk_id
            LEFT JOIN wilayah ON kartu_keluarga.wilayah_id = wilayah.wilayah_id
            WHERE LOWER(penduduk.status_dasar) = 'hidup' OR penduduk.agama IS NULL
            GROUP BY wilayah.wilayah_id,wilayah.parent_id, enum_values.agama;
        SQL;
    }

    /**
     * Drop the view.
     */

    private function dropView(): string
    {
        return
            <<<SQL
                DROP VIEW IF EXISTS penduduk_agama_view
            SQL;
    }
};
