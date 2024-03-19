-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for kelurahan
CREATE DATABASE IF NOT EXISTS `kelurahan` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kelurahan`;

-- Dumping structure for table kelurahan.apbdes
CREATE TABLE IF NOT EXISTS `apbdes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahun` year NOT NULL,
  `komponen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `subkategori_id` bigint unsigned NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `realisasi` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `apbdes_kategori_id_foreign` (`kategori_id`),
  KEY `apbdes_subkategori_id_foreign` (`subkategori_id`),
  CONSTRAINT `apbdes_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `apbdes_kategoris` (`id`) ON DELETE CASCADE,
  CONSTRAINT `apbdes_subkategori_id_foreign` FOREIGN KEY (`subkategori_id`) REFERENCES `apbdes_subkategoris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.apbdes_kategoris
CREATE TABLE IF NOT EXISTS `apbdes_kategoris` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.apbdes_subkategoris
CREATE TABLE IF NOT EXISTS `apbdes_subkategoris` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_subkategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.audits
CREATE TABLE IF NOT EXISTS `audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint unsigned NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.authentication_log
CREATE TABLE IF NOT EXISTS `authentication_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `authenticatable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authenticatable_id` bigint unsigned NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `login_at` timestamp NULL DEFAULT NULL,
  `login_successful` tinyint(1) NOT NULL DEFAULT '0',
  `logout_at` timestamp NULL DEFAULT NULL,
  `cleared_by_user` tinyint(1) NOT NULL DEFAULT '0',
  `location` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `authentication_log_authenticatable_type_authenticatable_id_index` (`authenticatable_type`,`authenticatable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.bantuanables
CREATE TABLE IF NOT EXISTS `bantuanables` (
  `bantuan_id` bigint unsigned NOT NULL,
  `bantuanable_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bantuanable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `bantuanables_bantuan_id_bantuanable_id_bantuanable_type_unique` (`bantuan_id`,`bantuanable_id`,`bantuanable_type`),
  CONSTRAINT `bantuanables_bantuan_id_foreign` FOREIGN KEY (`bantuan_id`) REFERENCES `bantuans` (`bantuan_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.bantuans
CREATE TABLE IF NOT EXISTS `bantuans` (
  `bantuan_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bantuan_program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bantuan_sasaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bantuan_keterangan` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bantuan_tgl_mulai` date NOT NULL,
  `bantuan_tgl_selesai` date NOT NULL,
  `bantuan_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`bantuan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.berita
CREATE TABLE IF NOT EXISTS `berita` (
  `berita_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kategori_berita_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_for` datetime DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`berita_id`),
  KEY `berita_user_id_foreign` (`user_id`),
  KEY `berita_kategori_berita_id_foreign` (`kategori_berita_id`),
  CONSTRAINT `berita_kategori_berita_id_foreign` FOREIGN KEY (`kategori_berita_id`) REFERENCES `kategori_berita` (`kategori_berita_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `berita_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.desa_kelurahan
CREATE TABLE IF NOT EXISTS `desa_kelurahan` (
  `deskel_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskel_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kec_id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`deskel_id`),
  KEY `desa_kelurahan_kec_id_foreign` (`kec_id`),
  CONSTRAINT `desa_kelurahan_kec_id_foreign` FOREIGN KEY (`kec_id`) REFERENCES `kecamatan` (`kec_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.deskel_profils
CREATE TABLE IF NOT EXISTS `deskel_profils` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `deskel_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskel_tipe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_kodepos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_luaswilayah` double DEFAULT NULL,
  `deskel_jumlahpenduduk` int DEFAULT NULL,
  `deskel_batas_utara` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_batas_timur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_batas_selatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_batas_barat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_visi` longtext COLLATE utf8mb4_unicode_ci,
  `deskel_misi` longtext COLLATE utf8mb4_unicode_ci,
  `deskel_sejarah` longtext COLLATE utf8mb4_unicode_ci,
  `deskel_gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deskel_profils_deskel_id_foreign` (`deskel_id`),
  CONSTRAINT `deskel_profils_deskel_id_foreign` FOREIGN KEY (`deskel_id`) REFERENCES `desa_kelurahan` (`deskel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.dusun
CREATE TABLE IF NOT EXISTS `dusun` (
  `dusun_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dusun_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskel_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`dusun_id`),
  KEY `dusun_deskel_id_foreign` (`deskel_id`),
  CONSTRAINT `dusun_deskel_id_foreign` FOREIGN KEY (`deskel_id`) REFERENCES `desa_kelurahan` (`deskel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kab_kota
CREATE TABLE IF NOT EXISTS `kab_kota` (
  `kabkota_id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prov_id` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kabkota_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kabkota_id`),
  KEY `kab_kota_prov_id_foreign` (`prov_id`),
  CONSTRAINT `kab_kota_prov_id_foreign` FOREIGN KEY (`prov_id`) REFERENCES `provinsi` (`prov_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kartu_keluarga
CREATE TABLE IF NOT EXISTS `kartu_keluarga` (
  `kk_id` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kk_alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskel_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dusun_id` bigint unsigned DEFAULT NULL,
  `rw_id` bigint unsigned DEFAULT NULL,
  `rt_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kk_id`),
  KEY `kartu_keluarga_deskel_id_foreign` (`deskel_id`),
  KEY `kartu_keluarga_dusun_id_foreign` (`dusun_id`),
  KEY `kartu_keluarga_rw_id_foreign` (`rw_id`),
  KEY `kartu_keluarga_rt_id_foreign` (`rt_id`),
  CONSTRAINT `kartu_keluarga_deskel_id_foreign` FOREIGN KEY (`deskel_id`) REFERENCES `desa_kelurahan` (`deskel_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kartu_keluarga_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`dusun_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kartu_keluarga_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `rukun_tetangga` (`rt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kartu_keluarga_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `rukun_warga` (`rw_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kategori_berita
CREATE TABLE IF NOT EXISTS `kategori_berita` (
  `kategori_berita_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kategori_berita_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kategori_stuntings
CREATE TABLE IF NOT EXISTS `kategori_stuntings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `indeks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kecamatan
CREATE TABLE IF NOT EXISTS `kecamatan` (
  `kec_id` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kec_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kabkota_id` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kec_id`),
  KEY `kecamatan_kabkota_id_foreign` (`kabkota_id`),
  CONSTRAINT `kecamatan_kabkota_id_foreign` FOREIGN KEY (`kabkota_id`) REFERENCES `kab_kota` (`kabkota_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kelahirans
CREATE TABLE IF NOT EXISTS `kelahirans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anak_ke` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penolong_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `berat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `panjang_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelahirans_nik_foreign` (`nik`),
  CONSTRAINT `kelahirans_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kematians
CREATE TABLE IF NOT EXISTS `kematians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_kematian` time DEFAULT NULL,
  `tempat_kematian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penyebab_kematian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menerangkan_kematian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kematians_nik_foreign` (`nik`),
  CONSTRAINT `kematians_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kepala_wilayah
CREATE TABLE IF NOT EXISTS `kepala_wilayah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kepala_nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepala_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kepala_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kepala_wilayah_kepala_nik_kepala_id_kepala_type_unique` (`kepala_nik`,`kepala_id`,`kepala_type`),
  KEY `kepala_wilayah_kepala_type_kepala_id_index` (`kepala_type`,`kepala_id`),
  CONSTRAINT `kepala_wilayah_kepala_nik_foreign` FOREIGN KEY (`kepala_nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kepindahans
CREATE TABLE IF NOT EXISTS `kepindahans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tujuan_pindah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_pindah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kepindahans_nik_foreign` (`nik`),
  CONSTRAINT `kepindahans_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kesehatans
CREATE TABLE IF NOT EXISTS `kesehatans` (
  `kes_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kes_cacat_mental_fisik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kes_penyakit_menahun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kes_penyakit_lain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kes_akseptor_kb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kesehatan_anaks
CREATE TABLE IF NOT EXISTS `kesehatan_anaks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint unsigned DEFAULT NULL,
  `subkategori_id` bigint unsigned DEFAULT NULL,
  `anak_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ibu_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `berat_badan` double(8,2) NOT NULL,
  `tinggi_badan` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kesehatan_anaks_kategori_id_foreign` (`kategori_id`),
  KEY `kesehatan_anaks_subkategori_id_foreign` (`subkategori_id`),
  KEY `kesehatan_anaks_anak_id_foreign` (`anak_id`),
  CONSTRAINT `kesehatan_anaks_anak_id_foreign` FOREIGN KEY (`anak_id`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kesehatan_anaks_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_stuntings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kesehatan_anaks_subkategori_id_foreign` FOREIGN KEY (`subkategori_id`) REFERENCES `subkategori_stuntings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.laporans
CREATE TABLE IF NOT EXISTS `laporans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `Perincian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Jumlah_Penduduk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Jumlah_Keluarga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.pendatangs
CREATE TABLE IF NOT EXISTS `pendatangs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_sebelumnya` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pendatangs_nik_foreign` (`nik`),
  CONSTRAINT `pendatangs_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.penduduk
CREATE TABLE IF NOT EXISTS `penduduk` (
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kk_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pendidikan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_perkawinan` date DEFAULT NULL,
  `tgl_perceraian` date DEFAULT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WNI',
  `nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ayah` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ibu` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `golongan_darah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etnis_suku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cacat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penyakit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akseptor_kb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_penduduk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tetap',
  `status_dasar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'HIDUP',
  `status_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BELUM DIVERIFIKASI',
  `status_tempat_tinggal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_sekarang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_sebelumnya` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamatKK` tinyint(1) DEFAULT '1',
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_hubungan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`nik`),
  KEY `penduduk_kk_id_index` (`kk_id`),
  CONSTRAINT `penduduk_kk_id_foreign` FOREIGN KEY (`kk_id`) REFERENCES `kartu_keluarga` (`kk_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.peristiwas
CREATE TABLE IF NOT EXISTS `peristiwas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `peristiwa_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peristiwa_id` bigint unsigned NOT NULL,
  `jenis_peristiwa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_peristiwa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_peristiwa` date DEFAULT NULL,
  `tanggal_lapor` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peristiwas_nik_foreign` (`nik`),
  KEY `peristiwas_peristiwa_type_peristiwa_id_index` (`peristiwa_type`,`peristiwa_id`),
  CONSTRAINT `peristiwas_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.provinsi
CREATE TABLE IF NOT EXISTS `provinsi` (
  `prov_id` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prov_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`prov_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.rukun_tetangga
CREATE TABLE IF NOT EXISTS `rukun_tetangga` (
  `rt_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rt_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rw_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rt_id`),
  KEY `rukun_tetangga_rw_id_foreign` (`rw_id`),
  CONSTRAINT `rukun_tetangga_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `rukun_warga` (`rw_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.rukun_warga
CREATE TABLE IF NOT EXISTS `rukun_warga` (
  `rw_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rw_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskel_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dusun_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rw_id`),
  KEY `rukun_warga_dusun_id_foreign` (`dusun_id`),
  KEY `rukun_warga_deskel_id_foreign` (`deskel_id`),
  CONSTRAINT `rukun_warga_deskel_id_foreign` FOREIGN KEY (`deskel_id`) REFERENCES `desa_kelurahan` (`deskel_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rukun_warga_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`dusun_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.subkategori_stuntings
CREATE TABLE IF NOT EXISTS `subkategori_stuntings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subkategori_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subkategori_batas_bawah` double(8,2) NOT NULL,
  `subkategori_batas_atas` double(8,2) NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subkategori_stuntings_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `subkategori_stuntings_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_stuntings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.taggables
CREATE TABLE IF NOT EXISTS `taggables` (
  `tag_id` bigint unsigned NOT NULL,
  `taggable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taggable_id` bigint unsigned NOT NULL,
  UNIQUE KEY `taggables_tag_id_taggable_id_taggable_type_unique` (`tag_id`,`taggable_id`,`taggable_type`),
  KEY `taggables_taggable_type_taggable_id_index` (`taggable_type`,`taggable_id`),
  CONSTRAINT `taggables_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.tags
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_column` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `wilayah_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wilayah_id` bigint unsigned NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_wilayah_type_wilayah_id_index` (`wilayah_type`,`wilayah_id`),
  KEY `users_nik_foreign` (`nik`),
  CONSTRAINT `users_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
