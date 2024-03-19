<?php

use App\Enums\Kependudukan\PendidikanType;
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

    /**
     * Create the view.
     */

    private function createView(): string
    {
        $pendidikan = PendidikanType::cases();
        $pendidikanEnum = array_map(function ($enum) {
            return $enum->value;
        }, $pendidikan);

        $unionQuery = collect($pendidikanEnum)->map(function ($pendidikan) {
            return "SELECT '$pendidikan' AS pendidikan,0 AS laki_laki, 0 AS perempuan";
        })->implode(' UNION ALL ');

        return <<<SQL
            CREATE VIEW penduduk_pendidikan_view AS
            SELECT 
                ROW_NUMBER() OVER (ORDER BY enum_values.pendidikan) AS id,
                enum_values.pendidikan, 
                COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = 'Laki-laki' THEN 1 ELSE 0 END), 0) AS laki_laki,
                COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = 'Perempuan' THEN 1 ELSE 0 END), 0) AS perempuan,
                COALESCE(MAX(wilayah.wilayah_id), 0) AS wilayah_id,
                COALESCE(MAX(wilayah.parent_id), 0) AS parent_id,
                COALESCE(COUNT(penduduk.nik), 0) AS total
            FROM (
                $unionQuery
            ) AS enum_values
            LEFT JOIN penduduk ON penduduk.pendidikan = enum_values.pendidikan
            LEFT JOIN kartu_keluarga ON penduduk.kk_id = kartu_keluarga.kk_id
            LEFT JOIN wilayah ON kartu_keluarga.wilayah_id = wilayah.wilayah_id
            WHERE LOWER(penduduk.status_dasar) = 'hidup' OR penduduk.pendidikan IS NULL
            GROUP BY wilayah.wilayah_id, wilayah.parent_id, enum_values.pendidikan;

        SQL;
    }

    /**
     * Drop the view.
     */

    private function dropView(): string
    {
        return
            <<<SQL
                DROP VIEW IF EXISTS penduduk_pendidikan_view
            SQL;
    }
};
