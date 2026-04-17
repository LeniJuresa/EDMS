-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 06:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ed_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_30_141225_create_reports_table', 2),
(5, '2026_01_30_155135_add_location_to_reports_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `messages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`messages`)),
  `location` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `file_location` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `claimed_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `session_id`, `id_number`, `messages`, `location`, `description`, `file_location`, `status`, `claimed_at`, `closed_at`, `created_at`, `updated_at`) VALUES
(12, 'xnfu6zEiNvmCyPTw2s9Oj9JXqSz8NYMOiDftw6eQ', '123456', '[{\"sender\":\"system\",\"text\":\"Report submitted. Waiting for a dispatcher.\",\"ts\":\"2026-01-30T17:54:28.966015Z\"},{\"sender\":\"system\",\"text\":\"Your report is under review.\",\"ts\":\"2026-01-30T17:54:31.957778Z\"},{\"sender\":\"system\",\"text\":\"Your report has been accepted. Thank you.\",\"ts\":\"2026-01-30T17:54:34.918824Z\"}]', 'location 1', 'he is evil', NULL, 'accepted', '2026-01-30 17:54:31', '2026-01-30 17:54:34', '2026-01-30 17:54:28', '2026-01-30 17:54:34'),
(13, 'xnfu6zEiNvmCyPTw2s9Oj9JXqSz8NYMOiDftw6eQ', '123456', '[{\"sender\":\"system\",\"text\":\"Report submitted. Waiting for a dispatcher.\",\"ts\":\"2026-01-30T17:54:53.275573Z\"},{\"sender\":\"citizen\",\"text\":\"hello, this is a citizen chat message\",\"ts\":\"2026-01-30T17:55:05.822929Z\"},{\"sender\":\"system\",\"text\":\"Your report is under review.\",\"ts\":\"2026-01-30T17:55:09.857388Z\"},{\"sender\":\"dispatcher\",\"text\":\"hello, this is a dispatcher message\",\"ts\":\"2026-01-30T17:55:26.849806Z\"},{\"sender\":\"system\",\"text\":\"Your report has been accepted. Thank you.\",\"ts\":\"2026-01-30T17:55:30.477624Z\"}]', 'location 12', 'super duper cool', NULL, 'accepted', '2026-01-30 17:55:09', '2026-01-30 17:55:30', '2026-01-30 17:54:53', '2026-01-30 17:55:30'),
(14, 'xnfu6zEiNvmCyPTw2s9Oj9JXqSz8NYMOiDftw6eQ', '123456', '[{\"sender\":\"system\",\"text\":\"Report submitted. Waiting for a dispatcher.\",\"ts\":\"2026-01-30T17:55:56.080856Z\"},{\"sender\":\"system\",\"text\":\"Your report is under review.\",\"ts\":\"2026-01-30T17:56:04.848853Z\"},{\"sender\":\"citizen\",\"text\":\"this ie an example of a denied report\",\"ts\":\"2026-01-30T17:56:22.581657Z\"},{\"sender\":\"dispatcher\",\"text\":\"yes it is\",\"ts\":\"2026-01-30T17:56:28.776031Z\"},{\"sender\":\"system\",\"text\":\"Your report has been denied. Thank you.\",\"ts\":\"2026-01-30T17:56:29.975325Z\"}]', 'location 3', 'super duper not cool', NULL, 'denied', '2026-01-30 17:56:04', '2026-01-30 17:56:29', '2026-01-30 17:55:56', '2026-01-30 17:56:29'),
(15, 'xnfu6zEiNvmCyPTw2s9Oj9JXqSz8NYMOiDftw6eQ', '123456', '[{\"sender\":\"system\",\"text\":\"Report submitted. Waiting for a dispatcher.\",\"ts\":\"2026-01-30T17:56:41.419110Z\"},{\"sender\":\"system\",\"text\":\"Your report is under review.\",\"ts\":\"2026-01-30T17:56:45.851889Z\"},{\"sender\":\"system\",\"text\":\"Your report has been denied. Thank you.\",\"ts\":\"2026-01-30T17:56:47.261019Z\"}]', 'location 3', 'super duper not cool', NULL, 'denied', '2026-01-30 17:56:45', '2026-01-30 17:56:47', '2026-01-30 17:56:41', '2026-01-30 17:56:47'),
(16, 'xnfu6zEiNvmCyPTw2s9Oj9JXqSz8NYMOiDftw6eQ', '123456', '[{\"sender\":\"system\",\"text\":\"Report submitted. Waiting for a dispatcher.\",\"ts\":\"2026-01-30T17:56:52.245420Z\"},{\"sender\":\"citizen\",\"text\":\"agegasrntdmzzfm\",\"ts\":\"2026-01-30T17:56:55.977265Z\"},{\"sender\":\"citizen\",\"text\":\"aegsrhdfzm,gu\",\"ts\":\"2026-01-30T17:56:58.016132Z\"},{\"sender\":\"system\",\"text\":\"Your report is under review.\",\"ts\":\"2026-01-30T17:57:06.050125Z\"},{\"sender\":\"dispatcher\",\"text\":\"aefgsrhdtjfkugih.,gfzdthrseaf\",\"ts\":\"2026-01-30T17:57:09.400572Z\"},{\"sender\":\"system\",\"text\":\"Your report has been denied. Thank you.\",\"ts\":\"2026-01-30T17:57:12.340354Z\"}]', 'location 3', 'super duper not cool', NULL, 'denied', '2026-01-30 17:57:06', '2026-01-30 17:57:12', '2026-01-30 17:56:52', '2026-01-30 17:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BXmmlxTScGftXy6IsZhh0AO74im2NtH4XeJYITX3', 42594, '192.168.0.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaGdueGIwMXltSXdOS0ZuQXpzUGJId1NscUg5SnVXR1YwOTBZV3Z3WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xOTIuMTY4LjAuNzo4MDAwL2Rpc3BhdGNoZXIvcG9sbCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6NToiNDI1OTQiO30=', 1769794739),
('xnfu6zEiNvmCyPTw2s9Oj9JXqSz8NYMOiDftw6eQ', 123456, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUk5GbmtuQ2dXMmhPd0hQdlJiREVjQmFDZmlMVXdVSHRxQVJLemgwUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXBvcnQvMTYvcG9sbCI7czo1OiJyb3V0ZSI7Tjt9czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rpc3BhdGNoZXIiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7czo2OiIxMjM0NTYiO30=', 1769795862);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_dispatcher` tinyint(1) NOT NULL DEFAULT 0,
  `id_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `is_dispatcher`, `id_number`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Leni Jureša', 'leni@admin.com', 1, 1, '123456', '$2y$12$lvBQZPB6XyzQgn3r9yxU4O1SaHZkv1WckHrg2P5Do0vye.SFuZ02a', NULL, '2026-01-30 12:30:21', '2026-01-30 12:30:21'),
(5, 'Nicholas Hintz', 'hahn.floy@example.net', 0, 1, '234567', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(6, 'Sylvan Miller III', 'fisher.rachelle@example.com', 0, 1, '32561345678', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(7, 'Tyra Buckridge', 'mdaugherty@example.net', 0, 1, '39144', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(8, 'Ahmed Friesen I', 'lkirlin@example.com', 0, 1, '15747', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(9, 'Dr. Lester Wolf', 'bogan.abagail@example.com', 0, 1, '41675', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(10, 'Adriel Wyman', 'ebogisich@example.net', 0, 1, '47864', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(11, 'Gay Howe', 'russel.autumn@example.org', 0, 1, '69392', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(12, 'Monique Krajcik', 'pfeffer.rodolfo@example.com', 0, 1, '57278', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(13, 'Ernesto Langosh DVM', 'laisha26@example.com', 0, 1, '15752', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49'),
(14, 'Finn Homenick', 'ruthie15@example.com', 0, 1, '54558', '$2y$12$8QOvIBjbTCLyCdAdcF30G.kDzbD6DcnJ/xlZTke9ivI0cCGzQ17sW', NULL, '2026-01-30 16:48:49', '2026-01-30 16:48:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_session_id_index` (`session_id`),
  ADD KEY `reports_id_number_index` (`id_number`),
  ADD KEY `reports_status_index` (`status`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_id_number_unique` (`id_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
