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


-- Dumping database structure for inventory
CREATE DATABASE IF NOT EXISTS `inventory` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `inventory`;

-- Dumping structure for table inventory.borrowings
CREATE TABLE IF NOT EXISTS `borrowings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `borrow_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowings_user_id_foreign` (`user_id`),
  KEY `borrowings_product_id_foreign` (`product_id`),
  CONSTRAINT `borrowings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `borrowings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.borrowings: ~3 rows (approximately)
INSERT INTO `borrowings` (`id`, `user_id`, `product_id`, `qty`, `borrow_date`, `return_date`, `status`, `notes`, `admin_notes`, `created_at`, `updated_at`) VALUES
	(1, 6, 6, 14, '2026-07-09', NULL, 'pending', 'a', NULL, '2026-07-09 00:26:34', '2026-07-09 00:26:34'),
	(2, 7, 12, 40, '2026-07-09', '2026-07-09', 'returned', 'a', 'Disetujui oleh Admin | Dikembalikan', '2026-07-09 00:52:20', '2026-07-09 01:10:17'),
	(3, 7, 18, 1, '2026-07-09', NULL, 'pending', 'untuk ngeprint', NULL, '2026-07-09 01:34:47', '2026-07-09 01:34:47');

-- Dumping structure for table inventory.branches
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.branches: ~3 rows (approximately)
INSERT INTO `branches` (`id`, `name`, `code`, `address`, `created_at`, `updated_at`) VALUES
	(1, 'Cabang Jakarta', 'JKT', 'Jl. Sudirman No. 12, Jakarta', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(2, 'Cabang Surabaya', 'SBY', 'Jl. Basuki Rahmat No. 45, Surabaya', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(3, 'Cabang Bandung', 'BDG', 'Jl. Dago No. 100, Bandung', '2026-07-08 07:01:11', '2026-07-08 07:01:11');

-- Dumping structure for table inventory.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.cache: ~0 rows (approximately)

-- Dumping structure for table inventory.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.cache_locks: ~0 rows (approximately)

-- Dumping structure for table inventory.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.categories: ~4 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
	(1, 'Elektronik & Gadget', 'ELEK', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(2, 'Alat Tulis Kantor', 'ATK', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(3, 'Mebel & Furniture', 'MBL', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(4, 'Barang Bekas', 'BKS', '2026-07-08 07:38:57', '2026-07-08 07:38:57');

-- Dumping structure for table inventory.divisions
CREATE TABLE IF NOT EXISTS `divisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.divisions: ~4 rows (approximately)
INSERT INTO `divisions` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'IT Department', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(2, 'Human Resources', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(3, 'Finance & Accounting', '2026-07-08 07:01:11', '2026-07-08 07:01:11'),
	(4, 'Operational', '2026-07-08 07:01:11', '2026-07-08 07:01:11');

-- Dumping structure for table inventory.failed_jobs
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

-- Dumping data for table inventory.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table inventory.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.jobs: ~0 rows (approximately)

-- Dumping structure for table inventory.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.job_batches: ~0 rows (approximately)

-- Dumping structure for table inventory.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.migrations: ~7 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_branches_and_divisions_tables', 1),
	(2, '0001_01_01_000000_create_users_table', 1),
	(3, '0001_01_01_000001_create_cache_table', 1),
	(4, '0001_01_01_000002_create_jobs_table', 1),
	(5, '2026_07_08_000001_create_categories_table', 1),
	(6, '2026_07_08_000002_create_products_table', 1),
	(7, '2026_07_08_000003_create_borrowings_table', 1);

-- Dumping structure for table inventory.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table inventory.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `available_stock` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_branch_id_foreign` (`branch_id`),
  CONSTRAINT `products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.products: ~24 rows (approximately)
INSERT INTO `products` (`id`, `name`, `category_id`, `branch_id`, `description`, `barcode`, `stock`, `available_stock`, `created_at`, `updated_at`) VALUES
	(1, 'Laptop ASUS ROG', 1, 1, 'Laptop Gaming untuk kebutuhan rendering berat', 'BRG-JKT-ELEK-60056', 10, 10, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(2, 'Printer HP LaserJet', 1, 1, 'Printer hitam putih kecepatan tinggi', 'BRG-JKT-ELEK-79175', 5, 5, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(3, 'Meja Kantor Kayu Jati', 3, 1, 'Meja direktur dengan laci ganda', 'BRG-JKT-MBL-50630', 8, 8, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(4, 'PC Desktop Lenovo Core i7', 1, 2, 'Komputer kantor standar administrasi', 'BRG-SBY-ELEK-96085', 12, 12, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(5, 'Buku Catatan A5', 2, 2, 'Buku catatan bersampul kulit keras', 'BRG-SBY-ATK-16868', 100, 100, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(6, 'Kursi Ergonomis Kantor', 3, 3, 'Kursi jaring dengan sandaran kepala', 'BRG-BDG-MBL-65147', 15, 15, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(7, 'Meja ruang meeting', 3, 1, 'untuk ruang meeting saja', 'BRG-JKT-MBL-70099', 3, 3, '2026-07-08 07:11:59', '2026-07-08 07:11:59'),
	(8, 'Laptop ASUS', 1, 1, 'Core i7 Ram 16GB', 'BRG-JKT-ELEK-93748', 10, 10, '2026-07-08 07:16:39', '2026-07-08 07:16:39'),
	(9, 'Meja Kantor', 3, 3, 'Meja kayu jati minimalis', 'BRG-BDG-MBL-12335', 5, 5, '2026-07-08 07:16:39', '2026-07-08 07:16:39'),
	(10, 'Kursi Jaring', 3, 3, 'Kursi jaring dengan sandaran kepala', 'BRG-BDG-MBL-30644', 12, 12, '2026-07-08 07:16:39', '2026-07-08 07:16:39'),
	(11, 'Printer Canon', 1, 2, 'Printer Inkjet All-in-One', 'BRG-SBY-ELEK-40848', 8, 8, '2026-07-08 07:16:39', '2026-07-08 07:16:39'),
	(12, 'Kertas A4', 2, 1, 'Kertas HVS 80gr 1 rim', 'BRG-JKT-ATK-20112', 50, 50, '2026-07-08 07:16:39', '2026-07-09 01:10:17'),
	(13, 'kursi meeting', 3, 2, 'kursi meting', 'BRG-SBY-MBL-66823', 2, 2, '2026-07-08 07:37:03', '2026-07-08 07:37:03'),
	(14, 'Laptop ASUS', 1, 1, 'Core i7 Ram 16GB', 'BRG-JKT-ELEK-14016', 10, 10, '2026-07-09 00:11:18', '2026-07-09 00:11:18'),
	(15, 'Meja Kantor', 3, 3, 'Meja kayu jati minimalis', 'BRG-BDG-MBL-56753', 5, 5, '2026-07-09 00:11:19', '2026-07-09 00:11:19'),
	(16, 'Kursi Jaring', 3, 3, 'Kursi jaring dengan sandaran kepala', 'BRG-BDG-MBL-58049', 12, 12, '2026-07-09 00:11:19', '2026-07-09 00:11:19'),
	(17, 'Printer Canon', 1, 2, 'Printer Inkjet All-in-One', 'BRG-SBY-ELEK-22841', 8, 8, '2026-07-09 00:11:19', '2026-07-09 00:11:19'),
	(18, 'Kertas A4', 2, 1, 'Kertas HVS 80gr 1 rim', 'BRG-JKT-ATK-25098', 50, 50, '2026-07-09 00:11:19', '2026-07-09 00:11:19'),
	(19, 'Laptop ASUS', 1, 1, 'Core i7 Ram 16GB', 'BRG-JKT-ELEK-25953', 10, 10, '2026-07-09 00:12:48', '2026-07-09 00:12:48'),
	(20, 'Meja Kantor', 3, 3, 'Meja kayu jati minimalis', 'BRG-BDG-MBL-87639', 5, 5, '2026-07-09 00:12:48', '2026-07-09 00:12:48'),
	(21, 'Kursi Jaring', 3, 3, 'Kursi jaring dengan sandaran kepala', 'BRG-BDG-MBL-66994', 12, 12, '2026-07-09 00:12:48', '2026-07-09 00:12:48'),
	(22, 'Printer Canon', 1, 2, 'Printer Inkjet All-in-One', 'BRG-SBY-ELEK-18800', 8, 8, '2026-07-09 00:12:48', '2026-07-09 00:12:48'),
	(23, 'Kertas A4', 2, 1, 'Kertas HVS 80gr 1 rim', 'BRG-JKT-ATK-21353', 50, 50, '2026-07-09 00:12:48', '2026-07-09 00:12:48'),
	(24, 'laptop redmi', 1, 3, 'laptop', 'asdasdasdasd', 3, 3, '2026-07-09 00:14:54', '2026-07-09 00:14:54');

-- Dumping structure for table inventory.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.sessions: ~7 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('12ezMVEhzdSwmMeGRL5kdgkLrTElf7nkORK8HdcP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.0 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiR1BnbFpLSU1DS2pzMU9IaGpDdEtFQlZKQ05VRnBTUGQ1RmpxWmlodiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1783580922),
	('3SY7BRFuYeR88Bxd6cTLZwpohPn9HiAaZD5IDnzg', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHRkZWdxREI1VGd0VzBQTXpaYzVDeHNxZnhwMTVGbHh6QWpBelJ2ZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O30=', 1783586095),
	('DkcJ03O7lnVHXAqvxyJOXQgBVGfh3tf0KvpDMrgJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidm1GS3h0YUYxSHhUVXdRVzZNUUJwMGgwMmZsdEYxTlpYQmxWaTQ5MSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21hc3RlciI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbWFzdGVyIjtzOjU6InJvdXRlIjtzOjEyOiJtYXN0ZXIuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783581824),
	('FJWaasREfrsJQV3mv5aL2YufaN8QCTHu0gcHOpYZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUG11Z3ZIbFBBZVFWMk5XOU5nSk5NOWVhWGpaaTNFeGx5RGFJRGhNWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783581824),
	('GYsXnKpuvXiPPgYlfya6E8TQFHRNQlX0AwOidtz6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzZuWWRQcE5NcU9meHBhbXJLUFJIMWhqUzRXc1JBc2xqcW8xYW42OSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783584857),
	('pt4ZHBk3IPMoeSrGPkxVIY2wI1ekXolmMyqTJBB6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTjljcHhudW1NdVEzaTdVTndVSmFMWVJoS250Z3dZYk0wMFRXaWtBQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Byb2R1Y3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783584857),
	('s08AXQR7VSmdSlJh5NF2IHcWYNNPPvkY2EUorpr1', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQndJWXVYTkRINzFJZ1E3RVFhdW52QXZaQzdGY0hYaUhqd3dBT0p0MiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib3Jyb3dpbmdzIjtzOjU6InJvdXRlIjtzOjE2OiJib3Jyb3dpbmdzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1783585454);

-- Dumping structure for table inventory.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `branch_id` bigint unsigned DEFAULT NULL,
  `division_id` bigint unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_branch_id_foreign` (`branch_id`),
  KEY `users_division_id_foreign` (`division_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table inventory.users: ~7 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `branch_id`, `division_id`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'superadmin@inventory.com', NULL, '$2y$12$J9ZOHlSrdH2wznqe2DZFHO2VOgy9X7XsraaV7iEQsmIavTp/5Ss8y', 'admin', NULL, NULL, NULL, '2026-07-08 07:01:12', '2026-07-08 07:01:12'),
	(2, 'Admin Jakarta', 'admin.jkt@inventory.com', NULL, '$2y$12$tSdcfVl9NAPXh7rtj3E6AORwxael2kVGRgLm9u0iQvaVYzWtchrTa', 'admin', 1, NULL, NULL, '2026-07-08 07:01:12', '2026-07-08 07:01:12'),
	(3, 'Admin Surabaya', 'admin.sby@inventory.com', NULL, '$2y$12$GGDPkoTk3uuIAQYGVRACTul7iCPDNA45aFrL5aBe0h1DlTYUVDF12', 'admin', 2, NULL, NULL, '2026-07-08 07:01:12', '2026-07-08 07:01:12'),
	(4, 'User JKT (IT)', 'user.jkt@inventory.com', NULL, '$2y$12$scBusNYm9F2d2nChsfRdSupGZfmOX/alHfNQEDxC14AEWzf3tDGSO', 'user', 1, 1, NULL, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(5, 'User SBY (Finance)', 'user.sby@inventory.com', NULL, '$2y$12$igMNhqVRuCc5FP9UqG5ckulwNipmYx3NMZ4dUcus2jYaZlI6zmwjK', 'user', 2, 3, NULL, '2026-07-08 07:01:13', '2026-07-08 07:01:13'),
	(6, 'depi', 'depi@gmail.com', NULL, '$2y$12$6Rvz.GwH3vMyU0mf0oB7LeYcFcL/4TN9HwkpgRWUKUyKIx/rma0Vi', 'user', 3, 4, NULL, '2026-07-09 00:21:39', '2026-07-09 00:21:39'),
	(7, 'dadan', 'dadan@gmail.com', NULL, '$2y$12$4oFoPCyPQvmh28VJ4CKWoujOiQvpDHPzTCgIkg8iBd9MBnd4EALom', 'user', 1, 4, NULL, '2026-07-09 00:32:26', '2026-07-09 00:32:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
