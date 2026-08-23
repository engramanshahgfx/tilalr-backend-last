-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 31, 2026 at 12:08 PM
-- Server version: 11.8.8-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u710227726_newtilalrcom`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_sections`
--

CREATE TABLE `about_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `paragraph_en` text NOT NULL,
  `paragraph_ar` text NOT NULL,
  `mission_title_en` varchar(255) NOT NULL,
  `mission_title_ar` varchar(255) NOT NULL,
  `mission_paragraph_en` text NOT NULL,
  `mission_paragraph_ar` text NOT NULL,
  `vision_title_en` varchar(255) NOT NULL,
  `vision_title_ar` varchar(255) NOT NULL,
  `vision_paragraph_en` text NOT NULL,
  `vision_paragraph_ar` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `app_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `whatsapp_phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `travel_date` date DEFAULT NULL,
  `room_type` enum('DoubleRoom','SingleRoom') NOT NULL DEFAULT 'DoubleRoom',
  `package_id` varchar(255) NOT NULL,
  `package_code` varchar(255) NOT NULL,
  `package_title` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `order_stat` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `booking_type` varchar(255) NOT NULL DEFAULT 'destination',
  `guests` int(11) NOT NULL DEFAULT 1,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_number`, `first_name`, `last_name`, `email`, `mobile`, `travel_date`, `room_type`, `package_id`, `package_code`, `package_title`, `price`, `total_amount`, `status`, `notes`, `special_requests`, `order_stat`, `user_id`, `booking_type`, `guests`, `payment_method`, `payment_status`, `payment_id`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 'BK4856026', 'Samer', 'Ombabi', 'samerombabi@gmail.com', '0536682178', '2026-07-29', 'DoubleRoom', '1', 'PKG-1', 'Britain & Ireland Tour', 0.00, 0.00, 'pending', NULL, '', 'New', NULL, 'destination', 1, 'credit_card', 'pending', NULL, NULL, '2026-07-13 13:01:33', '2026-07-13 13:01:33'),
(2, 'BK5632226', 'Samer', 'Ombabi', 'samerombabi@gmail.com', '0536682178', '2026-07-30', 'DoubleRoom', '6', 'PKG-6', 'Thailand Beach Getaway', 0.00, 0.00, 'pending', NULL, '', 'New', NULL, 'destination', 1, 'credit_card', 'pending', NULL, NULL, '2026-07-13 13:02:52', '2026-07-13 13:02:52'),
(3, 'BK1141426', 'Samer', 'Ombabi', 'samerombabi@gmail.com', '0536682178', '2026-07-29', 'DoubleRoom', '1', 'PKG-1', 'Britain & Ireland Tour', 0.00, 0.00, 'pending', NULL, '', 'New', NULL, 'destination', 1, 'credit_card', 'pending', NULL, NULL, '2026-07-13 13:03:27', '2026-07-13 13:03:27'),
(4, 'BK1956126', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-30', 'DoubleRoom', '13', 'TRIP-001', '🏝️ Marine Trip to Al Baridi Island – Yanbu 🌊✨', 2500.00, 2500.00, 'pending', NULL, '', 'New', NULL, 'tourism_offer', 1, 'credit_card', 'pending', NULL, NULL, '2026-07-26 16:19:09', '2026-07-26 16:19:09'),
(5, 'BK8197426', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-30', 'DoubleRoom', '14', 'TRIP-001', '🏔️ Abha Tour Package 🌿✨', 2500.00, 2500.00, 'pending', NULL, '', 'New', NULL, 'tourism_offer', 1, 'credit_card', 'pending', NULL, NULL, '2026-07-26 16:37:41', '2026-07-26 16:37:41'),
(6, 'BK1657226', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-30', 'DoubleRoom', '14', 'TRIP-001', 'vietnam', 3980.00, 3980.00, 'pending', NULL, '', 'New', NULL, 'destination', 1, 'credit_card', 'pending', NULL, NULL, '2026-07-26 17:06:21', '2026-07-26 17:06:21'),
(7, 'BK2269426', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-28', 'SingleRoom', '14', 'TRIP-001', '🏔️ Abha Tour Package 🌿✨', 1800.00, 1800.00, 'pending', NULL, '', 'New', NULL, 'tourism_offer', 1, 'credit_card', 'pending', NULL, NULL, '2026-07-27 11:31:28', '2026-07-27 11:31:28'),
(8, 'BK1192526', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-28', 'DoubleRoom', '15', 'TRIP-001', 'malaysia', 3700.00, 3700.00, 'pending', NULL, '', 'New', NULL, 'destination', 2, 'credit_card', 'pending', NULL, NULL, '2026-07-27 14:33:07', '2026-07-27 14:33:07'),
(9, 'BK3529426', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-30', 'DoubleRoom', '15', 'TRIP-001', 'malaysia', 3700.00, 3700.00, 'pending', NULL, 'dddd', 'New', NULL, 'destination', 2, 'credit_card', 'pending', NULL, NULL, '2026-07-27 14:33:51', '2026-07-27 14:33:51'),
(10, 'BK1690326', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-28', 'DoubleRoom', '15', 'TRIP-001', 'malaysia', 3700.00, 3700.00, 'pending', NULL, '', 'New', NULL, 'destination', 2, 'credit_card', 'pending', NULL, NULL, '2026-07-27 14:35:24', '2026-07-27 14:35:24'),
(11, 'BK2624826', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-31', 'DoubleRoom', '14', 'TRIP-001', 'vietnam', 3980.00, 3980.00, 'pending', NULL, '', 'New', NULL, 'destination', 2, 'credit_card', 'pending', NULL, NULL, '2026-07-27 16:11:33', '2026-07-27 16:11:33'),
(12, 'BK9170126', 'Aman', 'Shah', 'amanshah12sweer@gmail.com', '0551981751', '2026-07-31', 'DoubleRoom', '14', 'TRIP-001', 'vietnam', 3980.00, 3980.00, 'pending', NULL, '', 'New', NULL, 'destination', 2, 'credit_card', 'pending', NULL, NULL, '2026-07-27 16:13:00', '2026-07-27 16:13:00');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tilalr-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1785198441),
('tilalr-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1785198441;', 1785198441),
('tilalr-cache-livewire-rate-limiter:20265b03cfbed3ca92d8444e729d9536c80d2a50', 'i:1;', 1785326004),
('tilalr-cache-livewire-rate-limiter:20265b03cfbed3ca92d8444e729d9536c80d2a50:timer', 'i:1785326004;', 1785326004),
('tilalr-cache-livewire-rate-limiter:4a55601c20368e42271bcb16ddc8e2b90ef8f7b0', 'i:1;', 1785421388),
('tilalr-cache-livewire-rate-limiter:4a55601c20368e42271bcb16ddc8e2b90ef8f7b0:timer', 'i:1785421388;', 1785421388),
('tilalr-cache-livewire-rate-limiter:583ca22faa02a98bdc19f0e8901edd38d69ca98b', 'i:1;', 1785185872),
('tilalr-cache-livewire-rate-limiter:583ca22faa02a98bdc19f0e8901edd38d69ca98b:timer', 'i:1785185872;', 1785185872);

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
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `country` varchar(255) NOT NULL DEFAULT 'Saudi Arabia',
  `order` int(11) NOT NULL DEFAULT 0,
  `lang` varchar(10) NOT NULL DEFAULT 'ar',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','archived') NOT NULL DEFAULT 'new',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_infos`
--

CREATE TABLE `contact_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`title`)),
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `icon` varchar(255) NOT NULL,
  `working_hours` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_infos`
--

INSERT INTO `contact_infos` (`id`, `type`, `title`, `content`, `icon`, `working_hours`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'general', '{\"en\":\"Contact Us\",\"ar\":\"\\u0627\\u062a\\u0635\\u0644 \\u0628\\u0646\\u0627\"}', '{\"en\":\"Phone: +966-500-000-000\\\\nEmail: info@example.com\",\"ar\":\"\\u0627\\u0644\\u0647\\u0627\\u062a\\u0641: +966-500-000-000\\\\n\\u0627\\u0644\\u0628\\u0631\\u064a\\u062f: info@example.com\"}', 'bi-telephone', 'Sun-Thu 9:00-17:00', 0, 1, '2026-07-13 04:31:42', '2026-07-13 04:31:42');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_payment_offers`
--

CREATE TABLE `custom_payment_offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `token_number` varchar(255) DEFAULT NULL,
  `payment_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_data`)),
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evisa_applications`
--

CREATE TABLE `evisa_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `country_slug` varchar(255) NOT NULL,
  `passport_type` varchar(255) NOT NULL,
  `visa_type` varchar(255) NOT NULL,
  `interview_city` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `passport_number` varchar(255) DEFAULT NULL,
  `passport_expiry` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `notes` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `hero_sections`
--

CREATE TABLE `hero_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `headline_en` varchar(255) NOT NULL,
  `headline_ar` varchar(255) NOT NULL,
  `paragraph_en` text NOT NULL,
  `paragraph_ar` text NOT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `snapchat_url` varchar(255) DEFAULT NULL,
  `tiktok_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internet_package_requests`
--

CREATE TABLE `internet_package_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internet_package_requests`
--

INSERT INTO `internet_package_requests` (`id`, `country`, `mobile_number`, `package`, `created_at`, `updated_at`) VALUES
(1, 'LONDON', '+966577317209', '1GB', '2026-07-13 13:05:22', '2026-07-13 13:05:22'),
(2, 'Saudi Arabia', '+966661234567', '20GB', '2026-07-27 14:39:54', '2026-07-27 14:39:54');

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
(353, '2026_07_10_000000_add_type_ar_to_tourism_offers_table', 1),
(530, '0001_01_01_000000_create_users_table', 2),
(531, '0001_01_01_000001_create_cache_table', 2),
(532, '0001_01_01_000002_create_jobs_table', 2),
(533, '2025_07_18_040713_create_projects_table', 2),
(534, '2025_07_18_040714_create_services_table', 2),
(535, '2025_07_18_040715_create_team_members_table', 2),
(536, '2025_07_18_173628_create_portfolios_table', 2),
(537, '2025_07_18_180000_create_contact_messages_table', 2),
(538, '2025_08_07_152444_add_translatable_columns_to_projects_table', 2),
(539, '2025_08_07_152709_add_translatable_columns_to_services_table', 2),
(540, '2025_08_07_152750_add_translatable_columns_to_team_members_table', 2),
(541, '2025_08_07_152822_add_translatable_columns_to_portfolios_table', 2),
(542, '2025_08_16_202653_add_slug_to_projects_table', 2),
(543, '2025_08_16_202700_add_slug_to_team_members_table', 2),
(544, '2025_08_16_210515_add_performance_indexes_to_tables', 2),
(545, '2025_08_16_212426_create_notifications_table', 2),
(546, '2025_08_16_214123_remove_description_from_portfolios_table', 2),
(547, '2025_08_16_223235_remove_slug_from_team_members_table', 2),
(548, '2025_08_16_231226_create_contact_infos_table', 2),
(549, '2025_08_17_074213_add_short_description_to_services_table', 2),
(550, '2025_08_17_120000_create_app_settings_table', 2),
(551, '2025_08_18_085041_add_is_admin_to_users_table', 2),
(552, '2025_08_19_101052_add_is_featured_to_projects_table', 2),
(553, '2025_08_30_204137_create_trainings_table', 2),
(554, '2025_08_30_214013_add_short_description_to_trainings_table', 2),
(555, '2025_08_30_232819_remove_is_active_and_sort_order_from_trainings_table', 2),
(556, '2025_08_31_211103_create_hero_sections_table', 2),
(557, '2025_08_31_212430_create_about_sections_table', 2),
(558, '2025_08_31_214402_create_roles_table', 2),
(559, '2025_08_31_214630_add_role_id_to_team_members_table', 2),
(560, '2025_12_21_155702_create_pages_table', 2),
(561, '2025_12_21_155703_create_products_table', 2),
(562, '2025_12_21_155703_create_services_table', 2),
(563, '2025_12_21_155703_create_testimonials_table', 2),
(564, '2025_12_21_155704_create_settings_table', 2),
(565, '2025_12_21_155704_create_team_members_table', 2),
(566, '2025_12_21_155705_create_cities_table', 2),
(567, '2025_12_21_155705_create_trips_table', 2),
(568, '2025_12_21_155706_create_trips_table', 2),
(569, '2025_12_24_071246_create_personal_access_tokens_table', 2),
(570, '2025_12_24_120000_add_phone_to_users_table', 2),
(571, '2025_12_24_130000_create_contacts_table', 2),
(572, '2025_12_25_000001_create_reservations_table', 2),
(573, '2025_12_25_132000_create_tourism_destinations_table', 2),
(574, '2025_12_28_000001_add_highlights_and_group_size_to_trips_table', 2),
(575, '2025_12_28_130000_add_city_name_to_trips_table', 2),
(576, '2025_12_28_131500_add_video_to_trips_table', 2),
(577, '2025_12_28_132500_add_translations_to_trips_table', 2),
(578, '2026_01_05_113304_create_permissions_table', 2),
(579, '2026_01_05_113305_create_permission_role_table', 2),
(580, '2026_01_05_114500_add_rbac_columns', 2),
(581, '2026_01_05_114600_add_description_to_roles', 2),
(582, '2026_01_13_100000_recreate_permissions_table', 2),
(583, '2026_01_13_add_name_to_roles', 2),
(584, '2026_01_13_create_user_roles_table', 2),
(585, '2026_01_13_simplify_rbac', 2),
(586, '2026_01_14_120000_restore_cities_table', 2),
(587, '2026_01_14_174500_add_phone_to_users_table', 2),
(588, '2026_01_14_175500_make_email_nullable_on_users', 2),
(589, '2026_01_14_180000_create_otp_codes_table', 2),
(590, '2026_01_14_180100_add_phone_verified_at_to_users_table', 2),
(591, '2026_01_15_080000_create_otp_codes_table', 2),
(592, '2026_01_15_080100_create_role_user_table', 2),
(593, '2026_01_15_080200_add_is_active_to_services_table', 2),
(594, '2026_01_15_100000_add_blocked_dates_to_trips_table', 2),
(595, '2026_01_15_100001_add_city_id_foreign_to_trips', 2),
(596, '2026_01_15_100100_add_phone_verified_at_to_users_table', 2),
(597, '2026_01_18_000001_add_user_id_to_reservations_table', 2),
(598, '2026_01_18_122032_make_title_en_nullable_in_roles_table', 2),
(599, '2026_01_18_122214_make_title_fields_nullable_in_roles_table', 2),
(600, '2026_02_11_090000_recreate_permissions_tables_after_rbac', 2),
(601, '2026_02_11_090001_add_fk_to_permission_role', 2),
(602, '2026_04_29_162036_create_evisa_applications_table', 2),
(603, '2026_04_29_162645_create_visa_countries_table', 2),
(604, '2026_04_30_103640_create_schengen_applications_table', 2),
(605, '2026_05_03_103934_add_multilingual_columns_to_visa_countries_table', 2),
(606, '2026_05_04_111247_create_visa_applications_table', 2),
(607, '2026_05_06_create_internet_package_requests_table', 2),
(608, '2026_05_06_create_private_jet_requests_table', 2),
(609, '2026_06_29_094841_create_bookings_table', 2),
(610, '2026_06_29_112536_create_custom_payment_offers_table', 2),
(611, '2026_06_29_132618_remove_guests_from_bookings_table', 2),
(612, '2026_06_29_133750_add_payment_fields_to_bookings_table', 2),
(613, '2026_06_30_045335_create_tourism_offers_table', 2),
(614, '2026_06_30_092818_add_booking_type_to_bookings_table', 2),
(615, '2026_06_30_095423_add_booking_type_and_guests_to_bookings_table', 2),
(616, '2026_07_03_150129_add_permission_columns_to_permissions_table', 2),
(617, '2026_07_06_add_total_amount_and_transaction_id_to_bookings_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'login',
  `attempts` int(11) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `lang` varchar(10) NOT NULL DEFAULT 'ar',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_bookings', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(2, 'create_bookings', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(3, 'edit_bookings', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(4, 'delete_bookings', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(5, 'view_users', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(6, 'create_users', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(7, 'edit_users', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(8, 'delete_users', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(9, 'view_roles', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(10, 'create_roles', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(11, 'edit_roles', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(12, 'delete_roles', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(13, 'view_settings', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42'),
(14, 'edit_settings', 'web', '2026-07-13 04:31:42', '2026-07-13 04:31:42');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`name`)),
  `category` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`category`)),
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `private_jet_requests`
--

CREATE TABLE `private_jet_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number_of_people` int(11) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `departure_airport` varchar(255) NOT NULL,
  `departure_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `special_requirements` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `category` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `lang` varchar(10) NOT NULL DEFAULT 'ar',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`name`)),
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `project_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `trip_type` varchar(255) DEFAULT NULL,
  `trip_slug` varchar(255) DEFAULT NULL,
  `trip_title` varchar(255) DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `guests` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `status` enum('pending','contacted','confirmed','converted','cancelled') NOT NULL DEFAULT 'pending',
  `admin_contacted` tinyint(1) NOT NULL DEFAULT 0,
  `contacted_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `converted_booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text DEFAULT NULL,
  `allowed_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schengen_applications`
--

CREATE TABLE `schengen_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `passport_number` varchar(255) DEFAULT NULL,
  `applicant_type` enum('saudi','resident') NOT NULL DEFAULT 'saudi',
  `travel_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_family` tinyint(1) NOT NULL DEFAULT 0,
  `travelers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`travelers`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `status` enum('pending','processing','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`name`)),
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description`)),
  `short_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`short_description`)),
  `icon` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`name`)),
  `role` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`role`)),
  `bio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bio`)),
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `order` int(11) NOT NULL DEFAULT 0,
  `lang` varchar(10) NOT NULL DEFAULT 'ar',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourism_destinations`
--

CREATE TABLE `tourism_destinations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `description_en` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `long_description_en` text DEFAULT NULL,
  `long_description_ar` text DEFAULT NULL,
  `location_en` varchar(255) DEFAULT NULL,
  `location_ar` varchar(255) DEFAULT NULL,
  `duration_en` varchar(255) DEFAULT NULL,
  `duration_ar` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT 4.5,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `features_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_en`)),
  `features_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_ar`)),
  `includes_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`includes_en`)),
  `includes_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`includes_ar`)),
  `not_includes_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`not_includes_en`)),
  `not_includes_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`not_includes_ar`)),
  `itinerary_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`itinerary_en`)),
  `itinerary_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`itinerary_ar`)),
  `basic_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`basic_info`)),
  `contact_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contact_info`)),
  `payment_methods` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_methods`)),
  `region` varchar(255) DEFAULT NULL,
  `trip_code` varchar(255) DEFAULT NULL,
  `available_to` date DEFAULT NULL,
  `double_room_price` decimal(10,2) DEFAULT NULL,
  `single_room_price` decimal(10,2) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tourism_destinations`
--

INSERT INTO `tourism_destinations` (`id`, `slug`, `title_en`, `title_ar`, `description_en`, `description_ar`, `long_description_en`, `long_description_ar`, `location_en`, `location_ar`, `duration_en`, `duration_ar`, `price`, `rating`, `image`, `images`, `features_en`, `features_ar`, `includes_en`, `includes_ar`, `not_includes_en`, `not_includes_ar`, `itinerary_en`, `itinerary_ar`, `basic_info`, `contact_info`, `payment_methods`, `region`, `trip_code`, `available_to`, `double_room_price`, `single_room_price`, `active`, `created_at`, `updated_at`) VALUES
(11, 'China - August', 'China - August', 'الصين - أغسطس', 'Discover China with unmissable summer offers ', 'اكتشف الصين بعروض صيفية لا تُفوّت\n', NULL, NULL, 'China', 'الصين', '8 Nights', '8 ليالي ', 5657.00, 4.9, 'tourism/image_1200x675.jpg', NULL, '[]', '[]', NULL, NULL, NULL, NULL, NULL, NULL, '{\"trip_code\":null,\"days_num\":null,\"destination_name_en\":null,\"destination_name_ar\":null,\"available_to\":\"2026-09-01\",\"double_room_price\":null,\"single_room_price\":null}', '{\"address\":null,\"phone\":null,\"whatsapp\":null,\"email\":null}', '[]', 'asia', NULL, NULL, 6188.00, 4999.00, 1, '2026-07-13 15:22:56', '2026-07-28 03:44:10'),
(12, 'singapore-4-nights-family', 'Singapore', 'سينغافورة عائلي 4 بالغين- 4 ليالي ', 'Enjoy an unforgettable family vacation in Singapore 🇸🇬 with a stay at Mercure ICON Singapore, including daily breakfast 🍽️, airport pick-up and drop-off ✈️, and private sightseeing tours 🚘. Explore iconic attractions such as Marina Bay, Gardens by the Bay, Universal Studios Singapore 🎢, and the S.E.A. Aquarium 🐠 for a fun-filled family adventure! ✨', 'استمتعوا برحلة عائلية ممتعة إلى سنغافورة 🇸🇬 مع إقامة في فندق ميركيور آيكون سنغافورة شاملة الإفطار 🍽️، واستقبال وتوديع من المطار ✈️، وجولات بسيارة خاصة 🚘 لزيارة أشهر المعالم مثل مارينا باي، حدائق الخليج، يونيفرسال ستوديوز 🎢، والأكواريوم 🐠.', NULL, NULL, 'singapore', 'سينغافورة', '4 nights', '4 ليالي', 3300.00, 4.5, 'tourism/The Merlion and How to Get to the Merlion Park in Singapore.jpg', NULL, '[\"\\ud83c\\udf7d\\ufe0f Daily Breakfast\",\"\\u2708\\ufe0f Airport Pick-up & Drop-off\",\"\\ud83d\\ude98 Private Car with Driver\",\"\\ud83c\\udf06 Sightseeing Tours to Singapore\'s Top Attractions\",\"\\ud83c\\udfa2 Visit to Universal Studios Singapore\",\"\\ud83d\\udc20 Visit to the S.E.A. Aquarium\",\"\\ud83d\\udece\\ufe0f Taxes & Service Charges Included\",\"\\ud83d\\udd04 Customizable Itinerary\",\"\\ud83d\\udcf6 SIM Card with Mobile Data\"]', '[\"\\ud83c\\udf7d\\ufe0f \\u0625\\u0641\\u0637\\u0627\\u0631 \\u064a\\u0648\\u0645\\u064a\",\"\\u2708\\ufe0f \\u0627\\u0633\\u062a\\u0642\\u0628\\u0627\\u0644 \\u0648\\u062a\\u0648\\u062f\\u064a\\u0639 \\u0645\\u0646 \\u0648\\u0625\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0637\\u0627\\u0631\",\"\\ud83d\\ude98 \\u0633\\u064a\\u0627\\u0631\\u0629 \\u062e\\u0627\\u0635\\u0629 \\u0645\\u0639 \\u0633\\u0627\\u0626\\u0642\",\"\\ud83c\\udf06 \\u062c\\u0648\\u0644\\u0627\\u062a \\u0633\\u064a\\u0627\\u062d\\u064a\\u0629 \\u0644\\u0623\\u0634\\u0647\\u0631 \\u0645\\u0639\\u0627\\u0644\\u0645 \\u0633\\u0646\\u063a\\u0627\\u0641\\u0648\\u0631\\u0629\",\"\\ud83d\\udd04 \\u0625\\u0645\\u0643\\u0627\\u0646\\u064a\\u0629 \\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0628\\u0631\\u0646\\u0627\\u0645\\u062c\",\"\\ud83d\\udece\\ufe0f \\u0627\\u0644\\u0636\\u0631\\u0627\\u0626\\u0628 \\u0648\\u0631\\u0633\\u0648\\u0645 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0629 \\u0645\\u0634\\u0645\\u0648\\u0644\\u0629\",\"\\ud83d\\udc20 \\u0632\\u064a\\u0627\\u0631\\u0629 \\u0623\\u0643\\u0648\\u0627\\u0631\\u064a\\u0648\\u0645 \\u0633\\u0646\\u063a\\u0627\\u0641\\u0648\\u0631\\u0629\",\"\\ud83c\\udfa2 \\u0632\\u064a\\u0627\\u0631\\u0629 \\u064a\\u0648\\u0646\\u064a\\u0641\\u0631\\u0633\\u0627\\u0644 \\u0633\\u062a\\u0648\\u062f\\u064a\\u0648\\u0632 \\u0633\\u0646\\u063a\\u0627\\u0641\\u0648\\u0631\\u0629\",\"\\ud83d\\udcf6 \\u0634\\u0631\\u0627\\u0626\\u062d \\u0625\\u0646\\u062a\\u0631\\u0646\\u062a \\u0648\\u0627\\u062a\\u0635\\u0627\\u0644\"]', NULL, NULL, NULL, NULL, NULL, NULL, '{\"trip_code\":null,\"days_num\":null,\"destination_name_en\":null,\"destination_name_ar\":null,\"available_to\":\"2026-09-01\",\"double_room_price\":null,\"single_room_price\":null}', '{\"address\":null,\"phone\":null,\"whatsapp\":null,\"email\":null}', '[]', 'asia', NULL, NULL, 0.00, 0.00, 1, '2026-07-13 22:56:47', '2026-07-28 03:44:21'),
(13, 'malaysia-family-8-nights', 'malaysia', 'ماليزيا عائلي 4 بالغين- 8 ليالي ', '🇲🇾✨ Enjoy an unforgettable 8-night family vacation in Malaysia, featuring wonderful stays in Selangor, Langkawi, and Kuala Lumpur. The package includes daily breakfast 🍽️, domestic flights ✈️, airport transfers 🚐, and private sightseeing tours with a dedicated driver 🚘.\n\nDiscover breathtaking attractions, pristine beaches, world-class shopping destinations, and exciting family-friendly experiences, all while creating unforgettable memories in one incredible journey that perfectly blends relaxation, adventure, and fun! 🌴🛍️✨', '🇲🇾✨ استمتعوا بإجازة عائلية مميزة لمدة 8 ليالٍ في ماليزيا مع إقامة رائعة في سيلانجور، لنكاوي، وكوالالمبور، لتعيشوا أجمل الأوقات بين الطبيعة الخلابة، والشواطئ الساحرة، وأجواء المدينة النابضة بالحياة.\n\nيشمل البرنامج الإفطار اليومي 🍽️، والطيران الداخلي ✈️، والاستقبال والتوديع من وإلى المطار 🚐، وسيارة خاصة مع سائق 🚘، بالإضافة إلى جولات ممتعة لاستكشاف أبرز المعالم السياحية، والاستمتاع بالتسوق، والأنشطة الترفيهية، في رحلة تجمع بين الراحة، والمغامرة، والذكريات العائلية التي لا تُنسى. 🌴🛍️✨', NULL, NULL, 'malaysia', 'ماليزيا ', '8 nights', '8 ليالي', 3350.00, 4.5, 'tourism/248331366939263527.jpg', NULL, '[\"\\ud83c\\udfe8 8 Nights Accommodation\",\"\\ud83c\\udf7d\\ufe0f Daily Breakfast\",\"\\u2708\\ufe0f Airport Pick-up & Drop-off\",\"\\ud83d\\ude98 Private Car with Driver\",\"\\ud83d\\udeeb Domestic Flights Included\",\"\\ud83d\\udcf6 SIM Card with Mobile Data\",\"\\ud83c\\udf39 Complimentary Flower Welcome\",\"\\ud83c\\udf0a Stay in Langkawi with Sea View\",\"\\ud83c\\udfd9\\ufe0f Kuala Lumpur City Tours\",\"\\ud83d\\udd04 Customizable Itinerary\",\"\\ud83d\\udece\\ufe0f Taxes & Service Charges Included\"]', '[\"\\ud83c\\udfe8 \\u0625\\u0642\\u0627\\u0645\\u0629 8 \\u0644\\u064a\\u0627\\u0644\\u064a\",\"\\ud83c\\udf7d\\ufe0f \\u0625\\u0641\\u0637\\u0627\\u0631 \\u064a\\u0648\\u0645\\u064a\",\"\\u2708\\ufe0f \\u0627\\u0633\\u062a\\u0642\\u0628\\u0627\\u0644 \\u0648\\u062a\\u0648\\u062f\\u064a\\u0639 \\u0645\\u0646 \\u0648\\u0625\\u0644\\u0649 \\u0627\\u0644\\u0645\\u0637\\u0627\\u0631\",\"\\ud83d\\ude98 \\u0633\\u064a\\u0627\\u0631\\u0629 \\u062e\\u0627\\u0635\\u0629 \\u0645\\u0639 \\u0633\\u0627\\u0626\\u0642\",\"\\ud83d\\udeeb \\u062a\\u0630\\u0627\\u0643\\u0631 \\u0637\\u064a\\u0631\\u0627\\u0646 \\u062f\\u0627\\u062e\\u0644\\u064a\",\"\\ud83c\\udf39 \\u0627\\u0633\\u062a\\u0642\\u0628\\u0627\\u0644 \\u0628\\u0627\\u0644\\u0648\\u0631\\u062f \\u0639\\u0646\\u062f \\u0627\\u0644\\u0648\\u0635\\u0648\\u0644\",\"\\ud83c\\udf0a \\u0627\\u0644\\u0625\\u0642\\u0627\\u0645\\u0629 \\u0641\\u064a \\u0644\\u0646\\u0643\\u0627\\u0648\\u064a \\u0645\\u0639 \\u0625\\u0637\\u0644\\u0627\\u0644\\u0629 \\u0628\\u062d\\u0631\\u064a\\u0629\",\"\\ud83c\\udfd9\\ufe0f \\u062c\\u0648\\u0644\\u0627\\u062a \\u0633\\u064a\\u0627\\u062d\\u064a\\u0629 \\u0641\\u064a \\u0643\\u0648\\u0627\\u0644\\u0627\\u0644\\u0645\\u0628\\u0648\\u0631\",\"\\ud83d\\udece\\ufe0f \\u0627\\u0644\\u0636\\u0631\\u0627\\u0626\\u0628 \\u0648\\u0631\\u0633\\u0648\\u0645 \\u0627\\u0644\\u062e\\u062f\\u0645\\u0629 \\u0645\\u0634\\u0645\\u0648\\u0644\\u0629\",\"\\ud83d\\udd04 \\u0625\\u0645\\u0643\\u0627\\u0646\\u064a\\u0629 \\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0628\\u0631\\u0646\\u0627\\u0645\\u062c\"]', NULL, NULL, NULL, NULL, NULL, NULL, '{\"trip_code\":null,\"days_num\":null,\"destination_name_en\":null,\"destination_name_ar\":null,\"available_to\":null,\"double_room_price\":null,\"single_room_price\":null}', '{\"address\":null,\"phone\":null,\"whatsapp\":null,\"email\":null}', '[]', 'asia', NULL, NULL, 0.00, 0.00, 1, '2026-07-14 00:00:16', '2026-07-28 03:10:55'),
(14, 'vietnam-honeymoon-9-nights', 'vietnam', 'فيتنام', '🇻🇳❤️ Enjoy a romantic **9-night honeymoon in Vietnam** with premium stays at **Rue De L\'amour Boutique Hotel Hanoi**, **Amazing Sapa Hotel** with stunning mountain views, **Wyndham Garden Grandworld Phu Quoc**, and **Wyndham Garden Hanoi**. The package includes daily breakfast 🍽️, domestic flights ✈️, airport transfers, and private sightseeing tours 🚘, offering the perfect blend of luxury, breathtaking landscapes, and unforgettable experiences. ✨🌴\n', '🇻🇳❤️ اقضوا **9 ليالٍ رومانسية في فيتنام** مع إقامة مميزة في **Rue De L\'amour Boutique Hotel Hanoi**، و**Amazing Sapa Hotel** بإطلالاته الجبلية الساحرة، و**Wyndham Garden Grandworld Phu Quoc**، بالإضافة إلى **Wyndham Garden Hanoi**. يشمل البرنامج الإفطار اليومي 🍽️، 
<truncated 180982 bytes>

NOTE: The output was truncated because it was too long. Use a more targeted query or a smaller range to get the information you need.