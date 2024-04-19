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

-- Dumping structure for table kelurahan.asuransi_kesehatans
CREATE TABLE IF NOT EXISTS `asuransi_kesehatans` (
  `as_kes_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `as_kes_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `as_kes_nomor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`as_kes_id`)
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.authors
CREATE TABLE IF NOT EXISTS `authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bio` longtext COLLATE utf8mb4_unicode_ci,
  `links` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `authors_user_id_foreign` (`user_id`),
  CONSTRAINT `authors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `bantuan_keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

-- Dumping structure for table kelurahan.breezy_sessions
CREATE TABLE IF NOT EXISTS `breezy_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `authenticatable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authenticatable_id` bigint unsigned NOT NULL,
  `panel_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guard` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `expires_at` timestamp NULL DEFAULT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
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
  `struktur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kodepos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `luaswilayah` double DEFAULT NULL,
  `jmlh_pdd` int DEFAULT NULL,
  `bts_utara` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bts_timur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bts_selatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bts_barat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visi` longtext COLLATE utf8mb4_unicode_ci,
  `misi` longtext COLLATE utf8mb4_unicode_ci,
  `sejarah` longtext COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deskel_profils_deskel_id_foreign` (`deskel_id`),
  CONSTRAINT `deskel_profils_deskel_id_foreign` FOREIGN KEY (`deskel_id`) REFERENCES `desa_kelurahan` (`deskel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Dumping structure for table kelurahan.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
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
  `kk_kepala` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kk_alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wilayah_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kk_id`),
  KEY `kartu_keluarga_wilayah_id_foreign` (`wilayah_id`),
  KEY `kartu_keluarga_kk_kepala_foreign` (`kk_kepala`),
  KEY `kartu_keluarga_kk_id_kk_kepala_index` (`kk_id`,`kk_kepala`),
  CONSTRAINT `kartu_keluarga_kk_kepala_foreign` FOREIGN KEY (`kk_kepala`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kartu_keluarga_wilayah_id_foreign` FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah` (`wilayah_id`) ON DELETE CASCADE ON UPDATE CASCADE
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

-- Dumping structure for table kelurahan.kedatangan
CREATE TABLE IF NOT EXISTS `kedatangan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_datang` date DEFAULT NULL,
  `alamat_asal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kedatangan_nik_foreign` (`nik`),
  CONSTRAINT `kedatangan_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kelahirans
CREATE TABLE IF NOT EXISTS `kelahirans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ayah` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ibu` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  KEY `kelahirans_ayah_foreign` (`ayah`),
  KEY `kelahirans_ibu_foreign` (`ibu`),
  CONSTRAINT `kelahirans_ayah_foreign` FOREIGN KEY (`ayah`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelahirans_ibu_foreign` FOREIGN KEY (`ibu`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kelahirans_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.kematian
CREATE TABLE IF NOT EXISTS `kematian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_kematian` date DEFAULT NULL,
  `tempat_kematian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sebab_kematian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kematian_nik_foreign` (`nik`),
  CONSTRAINT `kematian_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
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

-- Dumping structure for table kelurahan.kepindahan
CREATE TABLE IF NOT EXISTS `kepindahan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pindah` date NOT NULL,
  `alamat_tujuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kepindahan_nik_foreign` (`nik`),
  CONSTRAINT `kepindahan_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
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

-- Dumping structure for table kelurahan.media
CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.penduduk
CREATE TABLE IF NOT EXISTS `penduduk` (
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kk_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wilayah_id` bigint unsigned DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pendidikan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_perkawinan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_perceraian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WNI',
  `ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `golongan_darah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etnis_suku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cacat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penyakit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akseptor_kb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BELUM DIVERIFIKASI',
  `status_tempat_tinggal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamatKK` tinyint(1) DEFAULT '0',
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_hubungan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `penduduk_nik_unique` (`nik`),
  KEY `penduduk_kk_id_foreign` (`kk_id`),
  KEY `penduduk_wilayah_id_foreign` (`wilayah_id`),
  KEY `penduduk_nik_kk_id_index` (`nik`,`kk_id`),
  CONSTRAINT `penduduk_kk_id_foreign` FOREIGN KEY (`kk_id`) REFERENCES `kartu_keluarga` (`kk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penduduk_wilayah_id_foreign` FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah` (`wilayah_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.penduduk_kesehatan
CREATE TABLE IF NOT EXISTS `penduduk_kesehatan` (
  `pddkes_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `as_kes_id` bigint unsigned DEFAULT NULL,
  `kes_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pddkes_id`),
  KEY `penduduk_kesehatan_nik_foreign` (`nik`),
  KEY `penduduk_kesehatan_as_kes_id_foreign` (`as_kes_id`),
  KEY `penduduk_kesehatan_kes_id_foreign` (`kes_id`),
  CONSTRAINT `penduduk_kesehatan_as_kes_id_foreign` FOREIGN KEY (`as_kes_id`) REFERENCES `asuransi_kesehatans` (`as_kes_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penduduk_kesehatan_kes_id_foreign` FOREIGN KEY (`kes_id`) REFERENCES `kesehatans` (`kes_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penduduk_kesehatan_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
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

-- Dumping structure for table kelurahan.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- Dumping structure for table kelurahan.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.rukun_warga
CREATE TABLE IF NOT EXISTS `rukun_warga` (
  `rw_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `deskel_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw_nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dusun_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rw_id`),
  KEY `rukun_warga_dusun_id_foreign` (`dusun_id`),
  KEY `rukun_warga_deskel_id_foreign` (`deskel_id`),
  CONSTRAINT `rukun_warga_deskel_id_foreign` FOREIGN KEY (`deskel_id`) REFERENCES `desa_kelurahan` (`deskel_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rukun_warga_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`dusun_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.sarana_prasarana_ibadahs
CREATE TABLE IF NOT EXISTS `sarana_prasarana_ibadahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.sarana_prasarana_kesehatans
CREATE TABLE IF NOT EXISTS `sarana_prasarana_kesehatans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.sarana_prasarana_pendidikans
CREATE TABLE IF NOT EXISTS `sarana_prasarana_pendidikans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.sarana_prasarana_umums
CREATE TABLE IF NOT EXISTS `sarana_prasarana_umums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.statistiks
CREATE TABLE IF NOT EXISTS `statistiks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stat_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stat_subheading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stat_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stat_deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stat_grafik_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stat_tabel_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stat_grafik_jenis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stat_tampil` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `statistiks_stat_slug_unique` (`stat_slug`),
  KEY `statistiks_id_index` (`id`),
  KEY `statistiks_stat_heading_index` (`stat_heading`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.stuntings
CREATE TABLE IF NOT EXISTS `stuntings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint unsigned NOT NULL,
  `subkategori_id` bigint unsigned NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ibu` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `berat_badan` double(8,2) NOT NULL,
  `tinggi_badan` double(8,2) NOT NULL,
  `indeks_massa_tubuh` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stuntings_kategori_id_foreign` (`kategori_id`),
  KEY `stuntings_subkategori_id_foreign` (`subkategori_id`),
  KEY `stuntings_nik_foreign` (`nik`),
  KEY `stuntings_ibu_foreign` (`ibu`),
  CONSTRAINT `stuntings_ibu_foreign` FOREIGN KEY (`ibu`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stuntings_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_stuntings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stuntings_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stuntings_subkategori_id_foreign` FOREIGN KEY (`subkategori_id`) REFERENCES `subkategori_stuntings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_nik_foreign` (`nik`),
  CONSTRAINT `users_nik_foreign` FOREIGN KEY (`nik`) REFERENCES `penduduk` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.user_wilayah
CREATE TABLE IF NOT EXISTS `user_wilayah` (
  `user_id` bigint unsigned NOT NULL,
  `wilayah_id` bigint unsigned NOT NULL,
  KEY `user_wilayah_user_id_foreign` (`user_id`),
  KEY `user_wilayah_wilayah_id_foreign` (`wilayah_id`),
  CONSTRAINT `user_wilayah_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_wilayah_wilayah_id_foreign` FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah` (`wilayah_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table kelurahan.wilayah
CREATE TABLE IF NOT EXISTS `wilayah` (
  `wilayah_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wilayah_nama` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wilayah_kodepos` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskel_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dusun_id` bigint unsigned DEFAULT NULL,
  `rw_id` bigint unsigned DEFAULT NULL,
  `rt_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`wilayah_id`),
  KEY `wilayah_deskel_id_foreign` (`deskel_id`),
  KEY `wilayah_dusun_id_foreign` (`dusun_id`),
  KEY `wilayah_rw_id_foreign` (`rw_id`),
  KEY `wilayah_rt_id_foreign` (`rt_id`),
  CONSTRAINT `wilayah_deskel_id_foreign` FOREIGN KEY (`deskel_id`) REFERENCES `desa_kelurahan` (`deskel_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wilayah_dusun_id_foreign` FOREIGN KEY (`dusun_id`) REFERENCES `dusun` (`dusun_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wilayah_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `rukun_tetangga` (`rt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wilayah_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `rukun_warga` (`rw_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
