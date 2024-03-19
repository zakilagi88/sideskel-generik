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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return <<<SQL

        CREATE PROCEDURE sp_rekapitulasi(IN bulan INT, IN tahun INT, IN wilayah_id INT)
        BEGIN
            DROP TEMPORARY TABLE IF EXISTS rekapitulasi;
            CREATE TEMPORARY TABLE rekapitulasi (
                id INT AUTO_INCREMENT PRIMARY KEY,
                Perincian VARCHAR(255),
                Laki_Laki INT,
                Perempuan INT,
                Total INT
            );

            -- Hitung jumlah penduduk awal
            SET @awal_laki := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    (YEAR(p.created_at) < tahun OR (YEAR(p.created_at) = tahun AND MONTH(p.created_at) < bulan))
                    AND (p.status_dasar = 'HIDUP' OR p.status_dasar IS NULL)
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @awal_perempuan := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    (YEAR(p.created_at) < tahun OR (YEAR(p.created_at) = tahun AND MONTH(p.created_at) < bulan))
                    AND (p.status_dasar = 'HIDUP' OR p.status_dasar IS NULL)
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @awal_total := @awal_laki + @awal_perempuan;

            INSERT INTO rekapitulasi (Perincian, Laki_Laki, Perempuan, Total)
            VALUES ('Penduduk Awal', @awal_laki, @awal_perempuan, @awal_total);

            -- Hitung jumlah kelahiran
            SET @pendatang_laki := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.created_at) = bulan AND YEAR(p.created_at) = tahun
                    AND p.status_dasar = 'LAHIR'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @pendatang_perempuan := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.created_at) = bulan AND YEAR(p.created_at) = tahun
                    AND p.status_dasar = 'LAHIR'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @pendatang_total := @pendatang_laki + @pendatang_perempuan;

            INSERT INTO rekapitulasi (Perincian, Laki_Laki, Perempuan, Total)
            VALUES ('Pendatang', @pendatang_laki, @pendatang_perempuan, @pendatang_total);

            -- Hitung jumlah pendatang
            SET @kelahiran_laki := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.created_at) = bulan AND YEAR(p.created_at) = tahun
                    AND p.status_dasar = 'HIDUP'
                    AND p.status_dasar != 'LAHIR'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @kelahiran_perempuan := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.created_at) = bulan AND YEAR(p.created_at) = tahun
                    AND p.status_dasar = 'HIDUP'
                    AND p.status_dasar != 'LAHIR'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @kelahiran_total := @kelahiran_laki + @kelahiran_perempuan;

            INSERT INTO rekapitulasi (Perincian, Laki_Laki, Perempuan, Total)
            VALUES ('Kelahiran', @kelahiran_laki, @kelahiran_perempuan, @kelahiran_total);

            -- Hitung jumlah kematian
            SET @kematian_laki := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.updated_at) = bulan AND YEAR(p.updated_at) = tahun
                    AND p.status_dasar = 'MENINGGAL'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @kematian_perempuan := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.updated_at) = bulan AND YEAR(p.updated_at) = tahun
                    AND p.status_dasar = 'MENINGGAL'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @kematian_total := @kematian_laki + @kematian_perempuan;

            INSERT INTO rekapitulasi (Perincian, Laki_Laki, Perempuan, Total)
            VALUES ('Kematian', @kematian_laki, @kematian_perempuan, @kematian_total);

            -- Hitung jumlah kepindahan
            SET @kepindahan_laki := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.updated_at) = bulan AND YEAR(p.updated_at) = tahun
                    AND p.status_dasar = 'PINDAH'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @kepindahan_perempuan := (
                SELECT COALESCE(SUM(CASE WHEN p.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END), 0)
                FROM penduduk p
                LEFT JOIN kartu_keluarga kk ON p.kk_id = kk.kk_id
                LEFT JOIN wilayah w ON kk.wilayah_id = w.wilayah_id
                WHERE 
                    MONTH(p.updated_at) = bulan AND YEAR(p.updated_at) = tahun
                    AND p.status_dasar = 'PINDAH'
                    AND (w.wilayah_id = wilayah_id OR wilayah_id IS NULL)
            );

            SET @kepindahan_total := @kepindahan_laki + @kepindahan_perempuan;

            INSERT INTO rekapitulasi (Perincian, Laki_Laki, Perempuan, Total)
            VALUES ('Kepindahan', @kepindahan_laki, @kepindahan_perempuan, @kepindahan_total);

            -- Hitung jumlah penduduk akhir
            INSERT INTO rekapitulasi (Perincian, Laki_Laki, Perempuan, Total)
            VALUES ('Penduduk Akhir', 
                @awal_laki + @kelahiran_laki + @pendatang_laki - @kematian_laki - @kepindahan_laki,
                @awal_perempuan + @kelahiran_perempuan + @pendatang_perempuan - @kematian_perempuan - @kepindahan_perempuan,
                @awal_total + @kelahiran_total + @pendatang_total - @kematian_total - @kepindahan_total
            );

            END


        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
        DROP PROCEDURE IF EXISTS sp_rekapitulasi;
        SQL;
    }
};
