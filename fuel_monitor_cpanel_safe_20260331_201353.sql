-- Fuel database export for cPanel/phpMyAdmin
-- Database: `fuel_monitor`
-- Generated at: 2026-03-31 20:13:53

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `stations`;
DROP TABLE IF EXISTS `crowd_reports`;
DROP TABLE IF EXISTS `fuel_statuses`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `admin_audit_logs`;
DROP TABLE IF EXISTS `station_user`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `crowd_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `station_id` bigint(20) unsigned NOT NULL,
  `crowd_level` varchar(20) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crowd_reports_station_id_created_at_index` (`station_id`,`created_at`),
  CONSTRAINT `crowd_reports_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fuel_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `station_id` bigint(20) unsigned NOT NULL,
  `octane` tinyint(1) NOT NULL DEFAULT 0,
  `diesel` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fuel_statuses_station_id_foreign` (`station_id`),
  CONSTRAINT `fuel_statuses_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'station_manager',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `admin_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `target_type` varchar(255) DEFAULT NULL,
  `target_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_audit_logs_user_id_foreign` (`user_id`),
  KEY `admin_audit_logs_action_created_at_index` (`action`,`created_at`),
  KEY `admin_audit_logs_target_type_target_id_index` (`target_type`,`target_id`),
  CONSTRAINT `admin_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `station_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `station_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_user_station_id_user_id_unique` (`station_id`,`user_id`),
  KEY `station_user_user_id_foreign` (`user_id`),
  CONSTRAINT `station_user_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `station_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin@hathazari.com|127.0.0.1', 'i:1;', '1774976462'),
('laravel-cache-admin@hathazari.com|127.0.0.1:timer', 'i:1774976462;', '1774976462');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1', '0001_01_01_000000_create_users_table', '1'),
('2', '0001_01_01_000001_create_cache_table', '1'),
('3', '0001_01_01_000002_create_jobs_table', '1'),
('4', '2026_03_29_062518_create_stations_table', '1'),
('5', '2026_03_29_062519_create_fuel_statuses_table', '1'),
('6', '2026_03_29_070320_create_crowd_reports_table', '2'),
('7', '2026_03_29_074702_create_admin_audit_logs_table', '3'),
('8', '2026_03_31_000001_add_role_to_users_table', '4'),
('9', '2026_03_31_000002_create_station_user_table', '4');

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('HoDF6AgDLu6cIbZr1Zk9jojikibrl1jefNgUqHXI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJhQXdNZ2JuYmpQMEpJQzlLTGxqNnJ6WnZWb2RSUVFpR3hBWUpmbFU0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', '1774970335'),
('iboKZCrxkNiHbxOxfnPKlxM79TqeuyOIZYv18wh3', NULL, '127.0.0.1', 'curl/8.5.0', 'eyJfdG9rZW4iOiJCbUpzMHlTUVpCRVl1R3JFS0pVUVY5MnliRG1oa29IMUMzNkFqcEtkIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', '1774970335'),
('KtgS5KCpkTMg4HUJ2kTeMs77g9RDvlzL51n4cS4A', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJLTk9GSVRDOFlDT2tUaUlkYXRqaUlBYWFXTmtrcGRrVDhNNzF1VUdqIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==', '1774976407');

INSERT INTO `stations` (`id`, `name`, `location`, `created_at`, `updated_at`) VALUES
('29', 'পোস্টার ফিলিং স্টেশন', 'কর্ণিয়ার নদীর পাড়, হাটহাজারী পৌরসভা', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('30', 'এম আলম ফিলিং স্টেশন', 'বাস স্ট্যান্ড, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('31', 'হাট এম. ছিদ্দিক ফিলিং স্টেশন', 'নোয়াপাড়া মাদ্রাসা সংলগ্ন, বাস স্ট্যান্ড, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('32', 'টি.এম সোনারগাঁও ফিলিং স্টেশন', 'ইসলামিয়া হাট, ফতেপুর, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('33', 'জন জন ফিলিং স্টেশন', 'নলিয়ারহাট, খোশার দ্বিতীয় গেট, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('34', 'বিআরটিসি ফিলিং স্টেশন', 'নতুন পাড়া, বিবিরহাট অফিস সংলগ্ন, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('35', 'মুস্ত ফিলিং স্টেশন', 'নলিয়ারহাট পুরাতন বাস স্ট্যান্ড, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('36', 'মেসার্স আলাল ফিলিং স্টেশন', 'ফতেপুর, উপজেলা হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('37', 'লতিফ এন্ড ব্রাদার্স ফিলিং স্টেশন', 'নলিয়ারহাট নতুন ব্রিজ সংলগ্ন, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44'),
('38', 'হাজী মোহাম্মদ হক ফিলিং স্টেশন', 'বড়দিঘীর পাড়, হাটহাজারী, চট্টগ্রাম', '2026-03-31 15:29:44', '2026-03-31 15:29:44');

INSERT INTO `crowd_reports` (`id`, `station_id`, `crowd_level`, `ip_address`, `created_at`, `updated_at`) VALUES
('19', '29', 'low', '127.0.0.1', '2026-03-31 15:21:44', '2026-03-31 15:17:44'),
('20', '30', 'low', '127.0.0.1', '2026-03-31 15:25:44', '2026-03-31 15:19:44'),
('21', '31', 'low', '127.0.0.1', '2026-03-31 15:04:44', '2026-03-31 15:26:44'),
('22', '32', 'low', '127.0.0.1', '2026-03-31 15:25:44', '2026-03-31 15:17:44'),
('23', '33', 'low', '127.0.0.1', '2026-03-31 15:21:44', '2026-03-31 15:14:44'),
('24', '34', 'low', '127.0.0.1', '2026-03-31 15:20:44', '2026-03-31 15:20:44'),
('25', '35', 'low', '127.0.0.1', '2026-03-31 15:27:44', '2026-03-31 15:20:44'),
('26', '36', 'low', '127.0.0.1', '2026-03-31 15:11:44', '2026-03-31 15:15:44'),
('27', '37', 'low', '127.0.0.1', '2026-03-31 15:15:44', '2026-03-31 15:27:44'),
('28', '38', 'low', '127.0.0.1', '2026-03-31 15:25:44', '2026-03-31 15:16:44');

INSERT INTO `fuel_statuses` (`id`, `station_id`, `octane`, `diesel`, `created_at`, `updated_at`) VALUES
('29', '29', '1', '1', '2026-03-31 11:29:44', '2026-03-31 15:14:44'),
('30', '30', '0', '1', '2026-03-31 11:29:44', '2026-03-31 15:11:44'),
('31', '31', '1', '1', '2026-03-31 11:29:44', '2026-03-31 15:19:44'),
('32', '32', '0', '0', '2026-03-31 11:29:44', '2026-03-31 15:20:44'),
('33', '33', '0', '1', '2026-03-31 11:29:44', '2026-03-31 15:19:44'),
('34', '34', '0', '1', '2026-03-31 11:29:44', '2026-03-31 15:16:44'),
('35', '35', '1', '1', '2026-03-31 11:29:44', '2026-03-31 15:28:44'),
('36', '36', '0', '1', '2026-03-31 11:29:44', '2026-03-31 15:09:44'),
('37', '37', '0', '1', '2026-03-31 11:29:44', '2026-03-31 15:26:44'),
('38', '38', '0', '1', '2026-03-31 11:29:44', '2026-03-31 15:11:44');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
('2', 'Hathazari Admin', 'admin@hathazari.info', '2026-03-31 15:29:41', '$2y$12$8sXsZICKTidh8zwcDr9rE.Bc0zHV8qtU5MV9Ls/C6zeX/szV0OO3a', 'admin', 'cQCu9tjiftjcYEIqIqMjM8w0cXrC8ihfQf8EOqJimyb82YNo6FArw4Fv5h5F', '2026-03-29 07:16:07', '2026-03-31 16:58:38');

SET FOREIGN_KEY_CHECKS=1;
