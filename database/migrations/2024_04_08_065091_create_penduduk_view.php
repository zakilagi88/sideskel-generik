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

    public function up()
    {
        DB::statement($this->createView());
    }

    public function createView(): string
    {
        return <<<SQL
            CREATE PROCEDURE sp_create_penduduk_view(enum_query TEXT, enum_type VARCHAR(255), wilayah_id INT)
            BEGIN
                -- Hapus view sebelumnya jika ada
                DROP VIEW IF EXISTS penduduk_view;

                IF enum_type = 'rentang_umur' THEN
                    SET @sqlQuery = CONCAT('
                        CREATE VIEW penduduk_view AS
                        SELECT 
                            ROW_NUMBER() OVER (ORDER BY enum_values.', enum_type, ') AS id,
                            enum_values.', enum_type, ',
                            COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = "laki-laki" THEN 1 ELSE 0 END), 0) AS laki_laki,
                            COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = "perempuan" THEN 1 ELSE 0 END), 0) AS perempuan,
                            COALESCE(COUNT(penduduk.nik), 0) AS total
                        FROM (
                            ', enum_query, '
                        ) AS enum_values
                        LEFT JOIN penduduk ON penduduk.umur BETWEEN CAST(SUBSTRING_INDEX(enum_values.', enum_type, ', "-", 1) AS UNSIGNED) AND CAST(SUBSTRING_INDEX(enum_values.', enum_type, ', "-", -1) AS UNSIGNED)
                        LEFT JOIN kartu_keluarga ON penduduk.kk_id = kartu_keluarga.kk_id
                        LEFT JOIN wilayah ON kartu_keluarga.wilayah_id = wilayah.wilayah_id
                        WHERE (LOWER(penduduk.status_dasar) = "hidup" OR penduduk.umur IS NULL)
                    ');

                ELSE
                    SET @sqlQuery = CONCAT('
                        CREATE VIEW penduduk_view AS
                        SELECT 
                            ROW_NUMBER() OVER (ORDER BY enum_values.', enum_type, ') AS id, 
                            enum_values.', enum_type, ', 
                            COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = "laki-laki" THEN 1 ELSE 0 END), 0) AS laki_laki,
                            COALESCE(SUM(CASE WHEN LOWER(penduduk.jenis_kelamin) = "perempuan" THEN 1 ELSE 0 END), 0) AS perempuan,
                            COALESCE(COUNT(penduduk.nik), 0) AS total
                        FROM (
                            ', enum_query, '
                        ) AS enum_values
                        LEFT JOIN penduduk ON penduduk.', enum_type, ' = enum_values.', enum_type, '
                        LEFT JOIN kartu_keluarga ON penduduk.kk_id = kartu_keluarga.kk_id
                        LEFT JOIN wilayah ON kartu_keluarga.wilayah_id = wilayah.wilayah_id
                        WHERE (LOWER(penduduk.status_dasar) = "hidup" OR penduduk.', enum_type, ' IS NULL)
                    ');

                END IF;

                -- Jika wilayah_id tidak null, tambahkan filter WHERE
                IF wilayah_id IS NOT NULL THEN
                -- Ambil semua child wilayah yang terkait dengan parent wilayah
                    SET @sqlQuery = CONCAT(@sqlQuery, '
                        AND (wilayah.wilayah_id = ', wilayah_id, ' 
                        OR wilayah.parent_id = ', wilayah_id, ')');
                END IF;
                SET @sqlQuery = CONCAT(@sqlQuery, ' GROUP BY enum_values.', enum_type);

                -- Persiapkan dan eksekusi pernyataan SQL
                PREPARE stmt FROM @sqlQuery;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            END
        SQL;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop procedure dan view
        DB::statement('DROP PROCEDURE IF EXISTS sp_create_penduduk_view');
        DB::statement('DROP VIEW IF EXISTS penduduk_view');
    }
};
