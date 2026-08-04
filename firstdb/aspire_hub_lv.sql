-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 04, 2026 at 12:29 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aspire_hub_lv`
--

-- --------------------------------------------------------

--
-- Table structure for table `adspv_admins`
--

CREATE TABLE `adspv_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adspv_admins`
--

INSERT INTO `adspv_admins` (`id`, `user_id`, `role_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-08-03 04:57:10', '2026-08-03 04:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `adspv_clients`
--

CREATE TABLE `adspv_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `added_by` bigint(20) UNSIGNED DEFAULT NULL,
  `edited_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adspv_clients`
--

INSERT INTO `adspv_clients` (`id`, `user_id`, `company_name`, `phone`, `status`, `notes`, `added_by`, `edited_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'Whitney and Taylor Plc', '+1 (701) 133-7184', 'active', 'Proident nostrum al', 1, 1, '2026-08-04 03:15:24', '2026-08-04 03:21:43'),
(2, 3, 'Bethelpower', '(203) 333 6572', 'active', 'Steve Gagnon is the owner of Bethel Power Equipment, a trusted outdoor power equipment company serving customers through locations in Connecticut and New York. The company specializes in selling, renting, servicing, and repairing premium lawn care and landscaping equipment from leading brands. Under Steve Gagnon\'s leadership, Bethel Power Equipment is known for its customer-focused service, expert product knowledge, and commitment to providing reliable solutions for homeowners, landscapers, contractors, and municipalities.', 1, 1, '2026-08-04 03:24:31', '2026-08-04 03:52:50'),
(3, 4, 'White and Curry Traders', '+1 (621) 373-6555', 'inactive', 'Esse et quos ab non ', 1, NULL, '2026-08-04 03:27:20', '2026-08-04 03:27:20'),
(4, 5, 'Combs Carey Associates', '+1 (363) 765-6411', 'active', 'Officiis temporibus ', 1, NULL, '2026-08-04 03:27:24', '2026-08-04 03:27:24'),
(5, 6, 'Woodward Morgan Inc', '+1 (886) 411-8079', 'inactive', 'Molestias pariatur ', 1, NULL, '2026-08-04 03:27:29', '2026-08-04 03:27:29'),
(6, 7, 'Terrell Cooper Associates', '+1 (367) 134-2789', 'active', 'Id molestias dolore', 1, NULL, '2026-08-04 03:27:34', '2026-08-04 03:27:34'),
(7, 8, 'Parks and Phillips LLC', '+1 (466) 374-1386', 'inactive', 'Itaque proident ass', 1, NULL, '2026-08-04 03:27:39', '2026-08-04 03:27:39'),
(8, 9, 'Goodwin Allen Plc', '+1 (384) 245-1246', 'active', 'Culpa omnis tempore', 1, NULL, '2026-08-04 03:27:43', '2026-08-04 03:27:43'),
(9, 10, 'Mckinney Evans Associates', '+1 (553) 847-2963', 'active', 'Error qui ullamco vi', 1, NULL, '2026-08-04 03:27:47', '2026-08-04 03:27:47'),
(10, 11, 'Hooper and Mccray Associates', '+1 (441) 607-2745', 'active', 'Quae et elit accusa', 1, NULL, '2026-08-04 03:27:51', '2026-08-04 03:27:51'),
(11, 12, 'Fields and Clark LLC', '+1 (335) 678-3184', 'inactive', 'Anim voluptas sint ', 1, NULL, '2026-08-04 03:28:06', '2026-08-04 03:28:06'),
(12, 13, 'Stone and Conrad Associates', '+1 (662) 521-3433', 'inactive', 'Sed qui laboris ut s', 1, NULL, '2026-08-04 03:28:12', '2026-08-04 03:28:12'),
(13, 14, 'Glass Strong LLC', '+1 (523) 898-4363', 'active', 'Hic id incididunt qu', 1, NULL, '2026-08-04 03:28:16', '2026-08-04 03:28:16'),
(14, 15, 'Phelps Campos Inc', '+1 (207) 152-3946', 'active', 'Enim sint sit volup', 1, NULL, '2026-08-04 03:28:20', '2026-08-04 03:28:20'),
(15, 16, 'Clemons Wall Associates', '+1 (614) 856-8525', 'active', 'Vel ullamco doloribu', 1, NULL, '2026-08-04 03:28:24', '2026-08-04 03:28:24'),
(16, 17, 'Dorsey and Chan Plc', '+1 (497) 497-4764', 'inactive', 'Lorem sed earum dolo', 1, NULL, '2026-08-04 03:28:28', '2026-08-04 03:28:28'),
(17, 18, 'Wilson and Turner Co', '+1 (337) 224-9193', 'active', 'Ut ipsum quaerat qui', 1, NULL, '2026-08-04 03:28:32', '2026-08-04 03:28:32'),
(18, 19, 'Craig Haley LLC', '+1 (891) 715-4197', 'active', 'Deserunt corrupti f', 1, 1, '2026-08-04 03:28:36', '2026-08-04 03:56:16');

-- --------------------------------------------------------

--
-- Table structure for table `adspv_permissions`
--

CREATE TABLE `adspv_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adspv_permissions`
--

INSERT INTO `adspv_permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'View Dashboard', 'view-dashboard', 'Permission to view admin dashboard', '2026-08-03 04:57:10', '2026-08-03 04:57:10'),
(2, 'Manage Settings', 'manage-settings', 'Permission to edit general configuration', '2026-08-03 04:57:10', '2026-08-03 04:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `adspv_roles`
--

CREATE TABLE `adspv_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adspv_roles`
--

INSERT INTO `adspv_roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'System wide access for administrators', '2026-08-03 04:57:10', '2026-08-03 04:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `adspv_role_permissions`
--

CREATE TABLE `adspv_role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adspv_role_permissions`
--

INSERT INTO `adspv_role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adspv_websites`
--

CREATE TABLE `adspv_websites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `site_type` enum('maintenance','design','development','speed_optimisation','other') NOT NULL DEFAULT 'maintenance',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `admin_url` varchar(255) DEFAULT NULL,
  `admin_username` varchar(255) DEFAULT NULL,
  `admin_password` text DEFAULT NULL,
  `hosting_provider` varchar(255) DEFAULT NULL,
  `server_ip` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `added_by` bigint(20) UNSIGNED DEFAULT NULL,
  `edited_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adspv_websites`
--

INSERT INTO `adspv_websites` (`id`, `client_id`, `site_name`, `url`, `site_type`, `status`, `admin_url`, `admin_username`, `admin_password`, `hosting_provider`, `server_ip`, `notes`, `added_by`, `edited_by`, `created_at`, `updated_at`) VALUES
(1, 16, 'Calvin Yang', 'https://www.xevizilahoqufa.org.au', 'design', 'active', 'https://www.fymoritaninyp.in', 'viqep', 'eyJpdiI6InBuZ3lac1k5S1l0ek5OTFhpZVlGU0E9PSIsInZhbHVlIjoiaVJFZmkrT0pFd0t6M1puSE1oenpXdz09IiwibWFjIjoiODBiYjZlN2NhNmQzMDY4ZmU1YWYwNzI3MjIyOGJmYjYxNjFjMTZiNjcxY2NjYTRlNWQ2NmY5MGJjNGJiMDNiNSIsInRhZyI6IiJ9', 'Atque reprehenderit', 'Aliqua Eum ipsum c', 'Distinctio Sit quis', 1, 1, '2026-08-04 04:39:47', '2026-08-04 04:39:55'),
(2, 2, 'Bethel Power', 'http://bethelpower.com/', 'maintenance', 'active', 'http://bethelpower.com/wp-admin', 'pankaj', 'eyJpdiI6InZnUVdSTk5tby9jN0E2TFh2dkt4Tnc9PSIsInZhbHVlIjoicDF6c0RwY2FvY0ZKL1JObVhOVy9XQT09IiwibWFjIjoiYmI5OGNjYTFhNGM0Nzk0ZGE2NmYyZjg2ZTMwOTNkY2Q2ZDFjYzU0ZWUzOWIwMzk3MTY0NTE3ODk5YTg0NzhkNSIsInRhZyI6IiJ9', 'Wp Engine', NULL, NULL, 1, NULL, '2026-08-04 04:58:29', '2026-08-04 04:58:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
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
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
(4, '2026_08_03_100237_create_adspv_roles_table', 1),
(5, '2026_08_03_100238_create_adspv_permissions_table', 1),
(6, '2026_08_03_100239_create_adspv_role_permissions_table', 1),
(7, '2026_08_03_100240_create_adspv_admins_table', 1),
(8, '2026_08_03_124412_create_adspv_clients_table', 2),
(9, '2026_08_04_142500_add_added_by_and_edited_by_to_adspv_clients_table', 3),
(10, '2026_08_04_160000_create_adspv_websites_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('admin@aspirehub.com', '$2y$12$meH1dp4vyvqLnvKMYcxVJuAaFlJWtXQBY/i2e6AEUP41aSI231br2', '2026-08-04 03:59:41');

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
('4I86NNICu0EOiSSdJPEYoCwr8U9CO9i20X3KjEf1', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJjMk5ISmZ2YUU0b0p4aW1ldEdOZ3hidmxXQnQyUFJaOGJKRzFGSEZLIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6bnVsbH19', 1785827285),
('4TYaH4iDKF4JCgbU3Qvhmz988fpdoCVkF2rgaT7m', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI4VXA4bHhrVTlJUkJGdjVXNGFjc1J2Y1R2MnUwWUNRclBTcmJVSE9hIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYWRtaW5hZHNwbmxcL2NsaWVudHMiLCJyb3V0ZSI6ImFkbWluLmNsaWVudHMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1785826848),
('8kpWKjvoaaOwthlb3L4cHzig6ZfKL9ZVU33U3kOV', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqdDVxendMUjVTMGQzbVJWekczeUt6U0RlT0hlNUpZa3kzMGFQVnFIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmFkc3BubFwvY2xpZW50cyIsInJvdXRlIjoiYWRtaW4uY2xpZW50cyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1785827311),
('ThtZeU7H8JTp2A1vhgKk4nrOTCfQ11nnQsBDIip2', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ1SjhFSWdCSXVVMzF2S0tzbXlVSlFVQm9lTTFQZ2F2R1k4eW9ndW1vIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluYWRzcG5sXC9jbGllbnRzIiwicm91dGUiOiJhZG1pbi5jbGllbnRzIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1785827492),
('UGLzjXBk5AJpJGQO0e1pvWK01LvNP9YGqaiSAXU1', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJLS3JscDFUWmxPVnlNM09Kd2h1Tm9VRUlvSm82TXU2SU1hUG9TM21TIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmFkc3BubFwvY2xpZW50cyIsInJvdXRlIjoiYWRtaW4uY2xpZW50cyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1785827512);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'admin@aspirehub.com', '2026-08-03 04:57:10', '$2y$12$ezctHAyk9/bw/AqvNEvC6e50U7sC2B.1MS00Ih06v1D0m/Wh7QC0K', 'i1timpkmMQFh4SLRCXCuImn8C6HZKXQpeWgB0UKRB4NEu6KMNvp1zDFmrV9i', '2026-08-03 04:57:10', '2026-08-03 04:57:10'),
(2, 'Patience Chen', 'bewyvab@mailinator.com', NULL, '$2y$12$p0LSfwi3MOEUqdwdTANc7uC7ltiJ5ONjn40nucuwmD5za4OuldpRy', NULL, '2026-08-04 03:15:24', '2026-08-04 03:15:24'),
(3, 'Steve Gagnon', 'steveg@bethelpower.com', NULL, '$2y$12$WI3wkSjR.2bdE21Cg/X6xOqdPFC8P./u1JH6PR7Zhbf5/5.ba9MXu', 'qhrIAxvUg3kvQDgyzxtZv1JSSCIsZ1zVeBU54CFPmPggMp4SI5CXd7DMTJgV', '2026-08-04 03:24:31', '2026-08-04 03:46:58'),
(4, 'Melodie Burch', 'gyryziqa@mailinator.com', NULL, '$2y$12$yOIXIDqbkBd/F6crVO/5ZeZJtIJy/g2BrzhVcVjrHsps3vQDcRWFy', NULL, '2026-08-04 03:27:20', '2026-08-04 03:27:20'),
(5, 'Amos Mcfarland', 'bynaqubas@mailinator.com', NULL, '$2y$12$RxzH3wV9zVFf9FM01atRUOARI8HGhQSCLyGHy6gRscls3GZQC/BTi', NULL, '2026-08-04 03:27:24', '2026-08-04 03:27:24'),
(6, 'Cassidy Myers', 'xovuti@mailinator.com', NULL, '$2y$12$c0Rf1EDCAtsRCn3Ml79oBOx6VrcYRsMdV.kiqhSNWz65LAW0JPxaq', NULL, '2026-08-04 03:27:29', '2026-08-04 03:27:29'),
(7, 'Otto Wilkerson', 'ryporagag@mailinator.com', NULL, '$2y$12$IGU83R.cNwqB5dkVhfqDxO9Na07EjBR/y1cqh5LuTsi9KdaJaBLTe', NULL, '2026-08-04 03:27:34', '2026-08-04 03:27:34'),
(8, 'Eric Copeland', 'pexeqiba@mailinator.com', NULL, '$2y$12$ASA72Eb0D.dMGcKBJawpVOauHBN9oWSkWU2SXYWzPrhCAl2gNBo9S', NULL, '2026-08-04 03:27:39', '2026-08-04 03:27:39'),
(9, 'Lane Hernandez', 'qaxyno@mailinator.com', NULL, '$2y$12$hVw97Hrk5gFm3k.QpBN0C.iVh9GAVeFMjJkLMXgzmtveZe1YmUC.G', NULL, '2026-08-04 03:27:43', '2026-08-04 03:27:43'),
(10, 'Sloane Cortez', 'gihagesu@mailinator.com', NULL, '$2y$12$76As64x2bkNCoqoEVPh9Z.mgP4WAC9pa6u.dLledVX49VaecXjUny', NULL, '2026-08-04 03:27:47', '2026-08-04 03:27:47'),
(11, 'Abbot Lott', 'gyfawyva@mailinator.com', NULL, '$2y$12$J8SZPJ0/hlLteCmTa/ZlvOSxVSwhYIVF5S.QGRER5dlqToWLS.eZm', NULL, '2026-08-04 03:27:51', '2026-08-04 03:27:51'),
(12, 'Castor Avila', 'ridiregim@mailinator.com', NULL, '$2y$12$fgvcjiN.25ocoTuGdzBCk.FM2iG7f9cc5VdKapaxaZ1X.4omW/fW6', NULL, '2026-08-04 03:28:06', '2026-08-04 03:28:06'),
(13, 'Hiroko Small', 'wigu@mailinator.com', NULL, '$2y$12$KcYmT55y.WQSni26Lf/XxuqNxwwkHAbKP6vcMISaLijpu/Ow./qiG', NULL, '2026-08-04 03:28:12', '2026-08-04 03:28:12'),
(14, 'Keegan Byers', 'zuqeqa@mailinator.com', NULL, '$2y$12$HThJk.7eSNFK17u/M4xCp.BHVtTFgy5fbtsMVXVJKWSNdY2wkWrTC', NULL, '2026-08-04 03:28:16', '2026-08-04 03:28:16'),
(15, 'Adele Christensen', 'bosy@mailinator.com', NULL, '$2y$12$kcc74xabcs4xR9RE/0QORuenxJ5HYAPRlHycah7KXaTR1kaQVK2f6', NULL, '2026-08-04 03:28:20', '2026-08-04 03:28:20'),
(16, 'Murphy Bond', 'kypetekim@mailinator.com', NULL, '$2y$12$m5r72ToBFSmyhe4nx0oKSuyt/LgmQ43yLYm.uOg79alsxvLSZYHDO', NULL, '2026-08-04 03:28:24', '2026-08-04 03:28:24'),
(17, 'Reuben Kim', 'wabyzig@mailinator.com', NULL, '$2y$12$VMEKRGDkDhj6yiKmISv7uuHSDMtdeHmo.wHDnSTetyniQxfqqMiOO', NULL, '2026-08-04 03:28:28', '2026-08-04 03:28:28'),
(18, 'Alma Rivas', 'revefu@mailinator.com', NULL, '$2y$12$Z.J7mUbRL9Gi59Z6ZlNPh.aeNdsWm.cFshf/q.RO5BV78U2ViWznC', NULL, '2026-08-04 03:28:32', '2026-08-04 03:28:32'),
(19, 'Derek Gilbert', 'deba@mailinator.com', NULL, '$2y$12$9T3UH/6Kw6B/yYTaQbU5KuKMIei0QaEDyY7glX2T//Qls/P4uh0qy', NULL, '2026-08-04 03:28:36', '2026-08-04 03:28:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adspv_admins`
--
ALTER TABLE `adspv_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adspv_admins_user_id_unique` (`user_id`),
  ADD KEY `adspv_admins_role_id_foreign` (`role_id`);

--
-- Indexes for table `adspv_clients`
--
ALTER TABLE `adspv_clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adspv_clients_user_id_unique` (`user_id`),
  ADD KEY `adspv_clients_added_by_foreign` (`added_by`),
  ADD KEY `adspv_clients_edited_by_foreign` (`edited_by`);

--
-- Indexes for table `adspv_permissions`
--
ALTER TABLE `adspv_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adspv_permissions_slug_unique` (`slug`);

--
-- Indexes for table `adspv_roles`
--
ALTER TABLE `adspv_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `adspv_roles_slug_unique` (`slug`);

--
-- Indexes for table `adspv_role_permissions`
--
ALTER TABLE `adspv_role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adspv_role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `adspv_role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `adspv_websites`
--
ALTER TABLE `adspv_websites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adspv_websites_client_id_foreign` (`client_id`),
  ADD KEY `adspv_websites_added_by_foreign` (`added_by`),
  ADD KEY `adspv_websites_edited_by_foreign` (`edited_by`);

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
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adspv_admins`
--
ALTER TABLE `adspv_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `adspv_clients`
--
ALTER TABLE `adspv_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `adspv_permissions`
--
ALTER TABLE `adspv_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `adspv_roles`
--
ALTER TABLE `adspv_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `adspv_role_permissions`
--
ALTER TABLE `adspv_role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `adspv_websites`
--
ALTER TABLE `adspv_websites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adspv_admins`
--
ALTER TABLE `adspv_admins`
  ADD CONSTRAINT `adspv_admins_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `adspv_roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `adspv_admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adspv_clients`
--
ALTER TABLE `adspv_clients`
  ADD CONSTRAINT `adspv_clients_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `adspv_clients_edited_by_foreign` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `adspv_clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adspv_role_permissions`
--
ALTER TABLE `adspv_role_permissions`
  ADD CONSTRAINT `adspv_role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `adspv_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adspv_role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `adspv_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adspv_websites`
--
ALTER TABLE `adspv_websites`
  ADD CONSTRAINT `adspv_websites_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `adspv_websites_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `adspv_clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adspv_websites_edited_by_foreign` FOREIGN KEY (`edited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
