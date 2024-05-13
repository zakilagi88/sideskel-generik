<?php

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
        return
            <<<SQL
            CREATE VIEW penduduk_umur_view AS
            WITH umur_penduduk AS (
                SELECT 
                    p.tanggal_lahir, p.jenis_kelamin, p.kk_id, p.status_dasar,
                    CASE 
                        WHEN YEAR(NOW()) - YEAR(p.tanggal_lahir) >= 75 THEN '75+'
                        ELSE CAST(YEAR(NOW()) - YEAR(p.tanggal_lahir) AS CHAR)
                    END AS umur
                FROM penduduk p
            )
            SELECT 
                ROW_NUMBER() OVER (ORDER BY umur) AS id,
                umur,
                SUM(CASE WHEN LOWER(jenis_kelamin) = 'Laki-laki' THEN 1 ELSE 0 END) AS laki_laki,
                SUM(CASE WHEN LOWER(jenis_kelamin) = 'Perempuan' THEN 1 ELSE 0 END) AS perempuan,
                COALESCE(MAX(wilayah.wilayah_id), 0) AS wilayah_id,
                COALESCE(MAX(wilayah.parent_id), 0) AS parent_id,
                COUNT(*) AS total
            FROM umur_penduduk
            INNER JOIN kartu_keluarga ON umur_penduduk.kk_id = kartu_keluarga.kk_id
            LEFT JOIN wilayah ON kartu_keluarga.wilayah_id = wilayah.wilayah_id
            WHERE LOWER(umur_penduduk.status_dasar) = 'hidup'
            GROUP BY wilayah.wilayah_id, wilayah.parent_id, umur;

        SQL;
    }

    /**
     * Drop the view.
     */

    private function dropView(): string
    {
        return
            <<<SQL
                DROP VIEW IF EXISTS penduduk_umur_view
            SQL;
    }
};