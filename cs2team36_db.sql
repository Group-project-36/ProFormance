-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2026 at 06:59 PM
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
-- Database: `cs2team36_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_name` varchar(200) NOT NULL,
  `house_number` varchar(10) NOT NULL,
  `address_line_one` varchar(100) NOT NULL,
  `address_line_two` varchar(100) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `county` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) NOT NULL,
  `country_code` char(2) NOT NULL,
  `country_phone_code` int(11) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `is_default_shipping_address` tinyint(1) DEFAULT 0,
  `is_default_billing_address` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `user_id`, `recipient_name`, `house_number`, `address_line_one`, `address_line_two`, `city`, `county`, `postcode`, `country_code`, `country_phone_code`, `phone_number`, `is_default_shipping_address`, `is_default_billing_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tilly Register', '27', 'Penlock Road', NULL, 'Wolverhampton', 'West Midlands', 'WV11 4HP', 'GB', 1, '3094555873', 0, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 2, 'Jane Plain', '11', 'Ocean Way', NULL, 'Stafford', 'Staffordshire', 'ST16 8UD', 'GB', 44, '7762398615', 0, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 3, 'Holly Bush', '9', 'Tipple Drive', NULL, 'Devon', 'Devonshire', 'EX39 6VR', 'GB', 7, '8555924671', 0, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 4, 'Alaska Bake', '96', 'Groove Street', NULL, 'Wednesbury', 'West Midlands', 'WS10 9TS', 'GB', 36, '306598667', 0, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 5, 'Billy Thekid', '10', 'Downing Street', 'Westminster', 'London', 'Greater London', 'SW1A 2AA', 'GB', 36, '706598667', 0, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 9, 'Brendon Kovacs', '15', 'Brunswick Park Road', NULL, 'Wednesbury', NULL, 'ws109hl', 'GB', 44, NULL, 1, 1, '2026-02-13 17:12:49', '2026-02-13 17:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `brand_description` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `slug`, `brand_description`, `logo_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'IronForge Fitness', 'ironforge-fitness', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'FlexCore Gear', 'flexcore-gear', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'ZenFlow Active', 'zenflow-active', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'GravityGrip', 'gravitygrip', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'AeroBurn Athletics', 'aeroburn-athletics', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 'LunaFit Apparel', 'lunafit-apparel', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 'TorqueWear', 'torquewear', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 'CorePulse Performance', 'corepulse-performance', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 'StrideMax Sports', 'stridemax-sports', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 'SilhouetteFit', 'silhouettefit', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 'PureBulk Labs', 'purebulk-labs', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 'IgniteFuel Nutrition', 'ignitefuel-nutrition', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 'MaxCharge Labs', 'maxcharge-labs', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 'GreenLean Nutrition', 'greenlean-nutrition', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 'TrailBite Energy', 'trailbite-energy', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 'StormPack Gear', 'stormpack-gear', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 'HydraPeak', 'hydrapeak', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 'SyncMotion Tech', 'syncmotion-tech', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 'BeatShield Audio', 'beatshield-audio', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 'GripBand Active', 'gripband-active', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 'PulseRelief Pro', 'pulserelief-pro', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 'RollFlex Recovery', 'rollflex-recovery', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 'SootheForce', 'sootheforce', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 'ThermaFlex', 'thermaflex', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 'CalmNest Naturals', 'calmnest-naturals', NULL, NULL, 1, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_token` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `slug` varchar(100) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_description`, `sort_order`, `slug`, `parent_id`, `is_active`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'Gym Equipment', NULL, 0, 'gym-equipment', NULL, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'Gym Apparel', NULL, 0, 'gym-apparel', NULL, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'Nutrition & Supplements', NULL, 0, 'nutrition-and-supplements', NULL, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'Accessories', NULL, 0, 'accessories', NULL, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'Recovery & Wellness', NULL, 0, 'recovery-and-wellness', NULL, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 'Adjustable Dumbbells', NULL, 0, 'adjustable-dumbbells', 1, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 'Resistance Bands', NULL, 0, 'resistance-bands', 1, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 'Yoga Mats', NULL, 0, 'yoga-mats', 1, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 'Kettlebells', NULL, 0, 'kettlebells', 1, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 'Jump Ropes', NULL, 0, 'jump-ropes', 1, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 'Women Hoodies', NULL, 0, 'women-hoodies', 2, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 'Men Joggers', NULL, 0, 'men-joggers', 2, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 'Men Compression Wear', NULL, 0, 'compression-tops-men', 2, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 'Sports Socks', NULL, 0, 'sports-socks', 2, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 'Women Leggings', NULL, 0, 'women-leggings', 2, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 'Protein Powders', NULL, 0, 'protein-powders', 3, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 'Pre-Workout', NULL, 0, 'pre-workout', 3, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 'Creatine', NULL, 0, 'creatine', 3, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 'Vegan Products', NULL, 0, 'vegan-products', 3, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 'Energy Snacks', NULL, 0, 'energy-snacks', 3, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 'Gym Bags', NULL, 0, 'gym-bags', 4, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 'Water Bottles', NULL, 0, 'water-bottles', 4, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 'Fitness Watches', NULL, 0, 'fitness-watches', 4, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 'Headphones', NULL, 0, 'headphones', 4, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 'Phone Holders', NULL, 0, 'phone-holders', 4, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(26, 'Massage Tools', NULL, 0, 'massage-tools', 5, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(27, 'Foam Rollers', NULL, 0, 'foam-rollers', 5, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(28, 'Relief Balms', NULL, 0, 'relief-balms', 5, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(29, 'Gel Packs', NULL, 0, 'gel-packs', 5, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(30, 'Sleep & Relaxation Teas', NULL, 0, 'sleep-relaxation-teas', 5, 1, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_code` char(2) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `iso_alpha_3` char(3) NOT NULL,
  `iso_numeric` smallint(6) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_code`, `country_name`, `is_active`, `iso_alpha_3`, `iso_numeric`, `sort_order`, `created_at`, `updated_at`) VALUES
('AD', 'Andorra', 1, 'AND', 20, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AE', 'United Arab Emirates', 1, 'ARE', 784, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AF', 'Afghanistan', 1, 'AFG', 4, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AG', 'Antigua and Barbuda', 1, 'ATG', 28, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AI', 'Anguilla', 1, 'AIA', 660, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AL', 'Albania', 1, 'ALB', 8, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AM', 'Armenia', 1, 'ARM', 51, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AO', 'Angola', 1, 'AGO', 24, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AQ', 'Antarctica', 1, 'ATA', 10, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AR', 'Argentina', 1, 'ARG', 32, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AS', 'American Samoa', 1, 'ASM', 16, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AT', 'Austria', 1, 'AUT', 40, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AU', 'Australia', 1, 'AUS', 36, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AW', 'Aruba', 1, 'ABW', 533, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AX', 'Åland Islands', 1, 'ALA', 248, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('AZ', 'Azerbaijan', 1, 'AZE', 31, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BA', 'Bosnia and Herzegovina', 1, 'BIH', 70, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BB', 'Barbados', 1, 'BRB', 52, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BD', 'Bangladesh', 1, 'BGD', 50, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BE', 'Belgium', 1, 'BEL', 56, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BF', 'Burkina Faso', 1, 'BFA', 854, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BG', 'Bulgaria', 1, 'BGR', 100, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BH', 'Bahrain', 1, 'BHR', 48, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BI', 'Burundi', 1, 'BDI', 108, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BJ', 'Benin', 1, 'BEN', 204, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BL', 'Saint Barthélemy', 1, 'BLM', 652, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BM', 'Bermuda', 1, 'BMU', 60, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BN', 'Brunei Darussalam', 1, 'BRN', 96, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BO', 'Bolivia (Plurinational State of)', 1, 'BOL', 68, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BQ', 'Bonaire, Sint Eustatius and Saba', 1, 'BES', 535, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BR', 'Brazil', 1, 'BRA', 76, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BS', 'Bahamas', 1, 'BHS', 44, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BT', 'Bhutan', 1, 'BTN', 64, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BV', 'Bouvet Island', 1, 'BVT', 74, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BW', 'Botswana', 1, 'BWA', 72, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BY', 'Belarus', 1, 'BLR', 112, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('BZ', 'Belize', 1, 'BLZ', 84, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CA', 'Canada', 1, 'CAN', 124, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CC', 'Cocos (Keeling) Islands', 1, 'CCK', 166, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CD', 'Congo, Democratic Republic of the', 1, 'COD', 180, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CF', 'Central African Republic', 1, 'CAF', 140, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CG', 'Congo', 1, 'COG', 178, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CH', 'Switzerland', 1, 'CHE', 756, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CI', 'Côte d\'Ivoire', 1, 'CIV', 384, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CK', 'Cook Islands', 1, 'COK', 184, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CL', 'Chile', 1, 'CHL', 152, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CM', 'Cameroon', 1, 'CMR', 120, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CN', 'China', 1, 'CHN', 156, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CO', 'Colombia', 1, 'COL', 170, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CR', 'Costa Rica', 1, 'CRI', 188, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CU', 'Cuba', 1, 'CUB', 192, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CV', 'Cabo Verde', 1, 'CPV', 132, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CW', 'Curaçao', 1, 'CUW', 531, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CX', 'Christmas Island', 1, 'CXR', 162, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CY', 'Cyprus', 1, 'CYP', 196, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('CZ', 'Czechia', 1, 'CZE', 203, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('DE', 'Germany', 1, 'DEU', 276, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('DJ', 'Djibouti', 1, 'DJI', 262, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('DK', 'Denmark', 1, 'DNK', 208, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('DM', 'Dominica', 1, 'DMA', 212, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('DO', 'Dominican Republic', 1, 'DOM', 214, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('DZ', 'Algeria', 1, 'DZA', 12, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('EC', 'Ecuador', 1, 'ECU', 218, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('EE', 'Estonia', 1, 'EST', 233, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('EG', 'Egypt', 1, 'EGY', 818, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('EH', 'Western Sahara', 1, 'ESH', 732, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ER', 'Eritrea', 1, 'ERI', 232, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ES', 'Spain', 1, 'ESP', 724, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ET', 'Ethiopia', 1, 'ETH', 231, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('FI', 'Finland', 1, 'FIN', 246, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('FJ', 'Fiji', 1, 'FJI', 242, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('FK', 'Falkland Islands', 1, 'FLK', 238, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('FM', 'Micronesia (Federated States of)', 1, 'FSM', 583, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('FO', 'Faroe Islands', 1, 'FRO', 234, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('FR', 'France', 1, 'FRA', 250, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GA', 'Gabon', 1, 'GAB', 266, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GB', 'United Kingdom', 1, 'GBR', 826, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GD', 'Grenada', 1, 'GRD', 308, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GE', 'Georgia', 1, 'GEO', 268, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GF', 'French Guiana', 1, 'GUF', 254, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GG', 'Guernsey', 1, 'GGY', 831, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GH', 'Ghana', 1, 'GHA', 288, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GI', 'Gibraltar', 1, 'GIB', 292, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GL', 'Greenland', 1, 'GRL', 304, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GM', 'Gambia', 1, 'GMB', 270, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GN', 'Guinea', 1, 'GIN', 324, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GP', 'Guadeloupe', 1, 'GLP', 312, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GQ', 'Equatorial Guinea', 1, 'GNQ', 226, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GR', 'Greece', 1, 'GRC', 300, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GS', 'South Georgia and South Sandwich Islands', 1, 'SGS', 239, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GT', 'Guatemala', 1, 'GTM', 320, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GU', 'Guam', 1, 'GUM', 316, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GW', 'Guinea-Bissau', 1, 'GNB', 624, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GY', 'Guyana', 1, 'GUY', 328, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('HK', 'Hong Kong', 1, 'HKG', 344, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('HM', 'Heard Island and McDonald Islands', 1, 'HMD', 334, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('HN', 'Honduras', 1, 'HND', 340, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('HR', 'Croatia', 1, 'HRV', 191, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('HT', 'Haiti', 1, 'HTI', 332, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('HU', 'Hungary', 1, 'HUN', 348, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ID', 'Indonesia', 1, 'IDN', 360, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IE', 'Ireland', 1, 'IRL', 372, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IL', 'Israel', 1, 'ISR', 376, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IM', 'Isle of Man', 1, 'IMN', 833, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IN', 'India', 1, 'IND', 356, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IO', 'British Indian Ocean Territory', 1, 'IOT', 86, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IQ', 'Iraq', 1, 'IRQ', 368, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IR', 'Iran (Islamic Republic of)', 1, 'IRN', 364, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IS', 'Iceland', 1, 'ISL', 352, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('IT', 'Italy', 1, 'ITA', 380, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('JE', 'Jersey', 1, 'JEY', 832, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('JM', 'Jamaica', 1, 'JAM', 388, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('JO', 'Jordan', 1, 'JOR', 400, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('JP', 'Japan', 1, 'JPN', 392, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KE', 'Kenya', 1, 'KEN', 404, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KG', 'Kyrgyzstan', 1, 'KGZ', 417, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KH', 'Cambodia', 1, 'KHM', 116, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KI', 'Kiribati', 1, 'KIR', 296, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KM', 'Comoros', 1, 'COM', 174, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KN', 'Saint Kitts and Nevis', 1, 'KNA', 659, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KP', 'Korea (Democratic People\'s Republic of)', 1, 'PRK', 408, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KR', 'Korea, Republic of', 1, 'KOR', 410, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KW', 'Kuwait', 1, 'KWT', 414, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KY', 'Cayman Islands', 1, 'CYM', 136, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('KZ', 'Kazakhstan', 1, 'KAZ', 398, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LA', 'Lao People\'s Democratic Republic', 1, 'LAO', 418, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LB', 'Lebanon', 1, 'LBN', 422, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LC', 'Saint Lucia', 1, 'LCA', 662, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LI', 'Liechtenstein', 1, 'LIE', 438, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LK', 'Sri Lanka', 1, 'LKA', 144, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LR', 'Liberia', 1, 'LBR', 430, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LS', 'Lesotho', 1, 'LSO', 426, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LT', 'Lithuania', 1, 'LTU', 440, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LU', 'Luxembourg', 1, 'LUX', 442, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LV', 'Latvia', 1, 'LVA', 428, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('LY', 'Libya', 1, 'LBY', 434, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MA', 'Morocco', 1, 'MAR', 504, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MC', 'Monaco', 1, 'MCO', 492, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MD', 'Moldova, Republic of', 1, 'MDA', 498, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ME', 'Montenegro', 1, 'MNE', 499, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MF', 'Saint Martin (French part)', 1, 'MAF', 663, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MG', 'Madagascar', 1, 'MDG', 450, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MH', 'Marshall Islands', 1, 'MHL', 584, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MK', 'North Macedonia', 1, 'MKD', 807, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ML', 'Mali', 1, 'MLI', 466, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MM', 'Myanmar', 1, 'MMR', 104, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MN', 'Mongolia', 1, 'MNG', 496, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MO', 'Macao', 1, 'MAC', 446, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MP', 'Northern Mariana Islands', 1, 'MNP', 580, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MQ', 'Martinique', 1, 'MTQ', 474, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MR', 'Mauritania', 1, 'MRT', 478, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MS', 'Montserrat', 1, 'MSR', 500, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MT', 'Malta', 1, 'MLT', 470, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MU', 'Mauritius', 1, 'MUS', 480, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MV', 'Maldives', 1, 'MDV', 462, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MW', 'Malawi', 1, 'MWI', 454, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MX', 'Mexico', 1, 'MEX', 484, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MY', 'Malaysia', 1, 'MYS', 458, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('MZ', 'Mozambique', 1, 'MOZ', 508, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NA', 'Namibia', 1, 'NAM', 516, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NC', 'New Caledonia', 1, 'NCL', 540, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NE', 'Niger', 1, 'NER', 562, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NF', 'Norfolk Island', 1, 'NFK', 574, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NG', 'Nigeria', 1, 'NGA', 566, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NI', 'Nicaragua', 1, 'NIC', 558, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NL', 'Netherlands', 1, 'NLD', 528, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NO', 'Norway', 1, 'NOR', 578, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NP', 'Nepal', 1, 'NPL', 524, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NR', 'Nauru', 1, 'NRU', 520, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NU', 'Niue', 1, 'NIU', 570, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('NZ', 'New Zealand', 1, 'NZL', 554, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('OM', 'Oman', 1, 'OMN', 512, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PA', 'Panama', 1, 'PAN', 591, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PE', 'Peru', 1, 'PER', 604, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PF', 'French Polynesia', 1, 'PYF', 258, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PG', 'Papua New Guinea', 1, 'PNG', 598, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PH', 'Philippines', 1, 'PHL', 608, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PK', 'Pakistan', 1, 'PAK', 586, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PL', 'Poland', 1, 'POL', 616, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PM', 'Saint Pierre and Miquelon', 1, 'SPM', 666, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PN', 'Pitcairn', 1, 'PCN', 612, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PR', 'Puerto Rico', 1, 'PRI', 630, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PS', 'Palestine, State of', 1, 'PSE', 275, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PT', 'Portugal', 1, 'PRT', 620, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PW', 'Palau', 1, 'PLW', 585, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('PY', 'Paraguay', 1, 'PRY', 600, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('QA', 'Qatar', 1, 'QAT', 634, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('RE', 'Réunion', 1, 'REU', 638, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('RO', 'Romania', 1, 'ROU', 642, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('RS', 'Serbia', 1, 'SRB', 688, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('RU', 'Russian Federation', 1, 'RUS', 643, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('RW', 'Rwanda', 1, 'RWA', 646, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SA', 'Saudi Arabia', 1, 'SAU', 682, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SB', 'Solomon Islands', 1, 'SLB', 90, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SC', 'Seychelles', 1, 'SYC', 690, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SD', 'Sudan', 1, 'SDN', 729, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SE', 'Sweden', 1, 'SWE', 752, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SG', 'Singapore', 1, 'SGP', 702, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SH', 'Saint Helena, Ascension and Tristan da Cunha', 1, 'SHN', 654, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SI', 'Slovenia', 1, 'SVN', 705, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SJ', 'Svalbard and Jan Mayen', 1, 'SJM', 744, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SK', 'Slovakia', 1, 'SVK', 703, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SL', 'Sierra Leone', 1, 'SLE', 694, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SM', 'San Marino', 1, 'SMR', 674, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SN', 'Senegal', 1, 'SEN', 686, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SO', 'Somalia', 1, 'SOM', 706, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SR', 'Suriname', 1, 'SUR', 740, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SS', 'South Sudan', 1, 'SSD', 728, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ST', 'Sao Tome and Principe', 1, 'STP', 678, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SV', 'El Salvador', 1, 'SLV', 222, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SX', 'Sint Maarten (Dutch part)', 1, 'SXM', 534, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SY', 'Syrian Arab Republic', 1, 'SYR', 760, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SZ', 'Eswatini', 1, 'SWZ', 748, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TC', 'Turks and Caicos Islands', 1, 'TCA', 796, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TD', 'Chad', 1, 'TCD', 148, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TF', 'French Southern Territories', 1, 'ATF', 260, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TG', 'Togo', 1, 'TGO', 768, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TH', 'Thailand', 1, 'THA', 764, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TJ', 'Tajikistan', 1, 'TJK', 762, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TK', 'Tokelau', 1, 'TKL', 772, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TL', 'Timor-Leste', 1, 'TLS', 626, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TM', 'Turkmenistan', 1, 'TKM', 795, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TN', 'Tunisia', 1, 'TUN', 788, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TO', 'Tonga', 1, 'TON', 776, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TR', 'Türkiye', 1, 'TUR', 792, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TT', 'Trinidad and Tobago', 1, 'TTO', 780, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TV', 'Tuvalu', 1, 'TUV', 798, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TW', 'Taiwan, Province of China', 1, 'TWN', 158, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('TZ', 'Tanzania, United Republic of', 1, 'TZA', 834, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('UA', 'Ukraine', 1, 'UKR', 804, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('UG', 'Uganda', 1, 'UGA', 800, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('UM', 'United States Minor Outlying Islands', 1, 'UMI', 581, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('US', 'United States of America', 1, 'USA', 840, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('UY', 'Uruguay', 1, 'URY', 858, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('UZ', 'Uzbekistan', 1, 'UZB', 860, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('VA', 'Holy See', 1, 'VAT', 336, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('VC', 'Saint Vincent and the Grenadines', 1, 'VCT', 670, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('VE', 'Venezuela (Bolivarian Republic of)', 1, 'VEN', 862, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('VG', 'Virgin Islands (British)', 1, 'VGB', 92, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('VI', 'Virgin Islands (U.S.)', 1, 'VIR', 850, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('VN', 'Viet Nam', 1, 'VNM', 704, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('VU', 'Vanuatu', 1, 'VUT', 548, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('WF', 'Wallis and Futuna', 1, 'WLF', 876, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('WS', 'Samoa', 1, 'WSM', 882, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('YE', 'Yemen', 1, 'YEM', 887, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('YT', 'Mayotte', 1, 'MYT', 175, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ZA', 'South Africa', 1, 'ZAF', 710, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ZM', 'Zambia', 1, 'ZMB', 894, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('ZW', 'Zimbabwe', 1, 'ZWE', 716, 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `currency_code` char(3) NOT NULL,
  `currency_name` varchar(50) NOT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency_code`, `currency_name`, `symbol`, `is_active`, `created_at`, `updated_at`) VALUES
('EUR', 'Euro', '€', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('GBP', 'Pound Sterling', '£', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('RUB', 'Russian Ruble', '₽', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('SLE', 'Leone', NULL, 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
('USD', 'US Dollar', '$', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `customer_contacts`
--

CREATE TABLE `customer_contacts` (
  `query_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_address` varchar(225) NOT NULL,
  `subject_select` enum('General','Support','Feedback') NOT NULL,
  `contact_status` enum('Pending','Solved') NOT NULL,
  `message_description` text NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_search` varchar(225) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `customer_contacts`
--

INSERT INTO `customer_contacts` (`query_id`, `first_name`, `last_name`, `email_address`, `subject_select`, `contact_status`, `message_description`, `product_id`, `variant_id`, `product_search`, `order_id`) VALUES
(1, 'Brendon', 'Kovacs', 'kvcsbrendon@gmail.com', 'General', 'Pending', 'ds', NULL, NULL, NULL, NULL),
(2, 'Brendon', 'Kovacs', 'kvcsbrendon@gmail.com', 'General', 'Pending', 'ds', NULL, NULL, NULL, NULL),
(3, 'Brendon', 'Kovacs', 'kvcsbrendon@gmail.com', 'Support', 'Pending', 'fdfd', NULL, NULL, NULL, NULL),
(4, 'Brendon', 'Kovacs', 'kvcsbrendon@gmail.com', 'General', 'Pending', 'fdfd', NULL, NULL, NULL, NULL),
(5, '43434', '4343', '434343@fdf.com', 'Support', 'Pending', 'fdfdf', NULL, NULL, '4343434', 43434),
(6, 'Brendon', 'Kovacs', 'kvcsbrendon@gmail.com', 'Feedback', 'Pending', 'Test', NULL, NULL, NULL, NULL),
(7, 'brendon', 'kovacs', 'kvcsbrendon@gmail.com', 'General', 'Pending', 'Testing message', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` bigint(20) UNSIGNED NOT NULL,
  `discount_code` varchar(50) DEFAULT NULL,
  `discoun_type` varchar(50) NOT NULL,
  `discount_value` decimal(12,2) NOT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `per_user_limit` int(11) DEFAULT NULL,
  `min_subtotal_penny` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`discount_id`, `discount_code`, `discoun_type`, `discount_value`, `starts_at`, `ends_at`, `usage_limit`, `per_user_limit`, `min_subtotal_penny`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SAVE10', 'percentage', 10.00, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 16:45:44', '2026-02-13 16:45:44'),
(2, 'FIVER', 'fixed_amount', 5.00, NULL, NULL, NULL, NULL, NULL, 1, '2026-02-13 16:46:07', '2026-02-13 16:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `discount_redemptions`
--

CREATE TABLE `discount_redemptions` (
  `redemption_id` bigint(20) UNSIGNED NOT NULL,
  `discount_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `redeemed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `discount_redemptions`
--

INSERT INTO `discount_redemptions` (`redemption_id`, `discount_id`, `user_id`, `order_id`, `redeemed_at`) VALUES
(1, 2, 9, 4, '2026-02-13 17:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `available_stock` int(11) NOT NULL DEFAULT 0,
  `stock_allocated` int(11) NOT NULL DEFAULT 0,
  `reorder_point` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`variant_id`, `available_stock`, `stock_allocated`, `reorder_point`, `created_at`, `updated_at`) VALUES
(1, 30, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 20, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 80, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 60, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 70, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 75, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 40, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 30, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 90, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 85, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 120, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 130, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 110, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 100, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 110, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 95, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 140, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 150, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 130, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 200, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 180, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 120, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 115, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 100, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 90, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(26, 80, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(27, 100, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(28, 85, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(29, 120, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(30, 80, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(31, 70, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(32, 150, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(33, 150, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(34, 60, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(35, 60, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(36, 110, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(37, 95, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(38, 35, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(39, 35, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(40, 50, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(41, 45, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(42, 130, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(44, 25, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(45, 75, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(46, 70, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(47, 140, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(48, 125, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(49, 160, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(50, 140, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(51, 100, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(52, 90, 0, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `login_details`
--

CREATE TABLE `login_details` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_salt` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `login_details`
--

INSERT INTO `login_details` (`user_id`, `email_address`, `password_hash`, `password_salt`) VALUES
(1, 'hpettus0@psu.edu', '$2a$04$28kZSRR3kp3GwLIeb8r4fe9XBHu/uV540wUSH5ji0PY4pgxLNwdlK', 'am4Ea5ZTTQ'),
(2, 'causello1@cbslocal.com', '$2a$04$ygDrUzkwGvVqNHXSxqMSCep/59vXYB2cvLRt8wQTfqAosQKj93coO', '5yu553a8B5'),
(3, 'fscottesmoor2@ask.com', '$2a$04$5m7pxeXyCAgBICz2o24jKeGgtEwRDoPlNMZLpPvEpFCHZeol6t46i', 'IujT388hhQ'),
(4, 'jferon3@topsy.com', '$2a$04$YSJ5pzYskR51hxu6o/SvL.BsOJ4dTpr9Yqlyb0C5bs7NBSkH8gJ82', '33zLFTDwzK'),
(5, 'bfife4@google.com.br', '$2a$04$hCBsQazm8XAWCcf4c7noAe3zPiulfocHlGTU.nO2re7G4oUPkO81e', 'r5U19qbDZP'),
(7, 'PhoebeAllen@evidence.com', '9bd96968abb532f0f839fb5e01cc12029aa64677dba3d6078d7ed004f1ca6751', 'baed7e58c35cf1e6e414609b3616be51'),
(9, 'kvcsbrendon@gmail.com', '80a7aaf611fa4148d0671b138dff1b6766c9baafef3d689deb539dbd47c4109d', '2834fcfb6672629190386650ba75514d');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_code` char(3) NOT NULL,
  `order_status` enum('Pending','Paid','Fulfilled','Cancelled','Refunded') NOT NULL DEFAULT 'Pending',
  `subtotal_penny` int(11) NOT NULL,
  `shipping_penny` int(11) NOT NULL DEFAULT 0,
  `tax_penny` int(11) NOT NULL DEFAULT 0,
  `discount_penny` int(11) NOT NULL DEFAULT 0,
  `total_penny` int(11) NOT NULL,
  `ship_recipient_name` varchar(200) DEFAULT NULL,
  `ship_house_number` varchar(10) DEFAULT NULL,
  `ship_address_line_one` varchar(100) DEFAULT NULL,
  `ship_address_line_two` varchar(100) DEFAULT NULL,
  `ship_city` varchar(100) DEFAULT NULL,
  `ship_county` varchar(100) DEFAULT NULL,
  `ship_postcode` varchar(100) DEFAULT NULL,
  `ship_country_code` char(2) DEFAULT NULL,
  `ship_phone_number` varchar(30) DEFAULT NULL,
  `shipping_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bill_recipient_name` varchar(200) DEFAULT NULL,
  `bill_house_number` varchar(10) DEFAULT NULL,
  `bill_address_line_one` varchar(100) DEFAULT NULL,
  `bill_address_line_two` varchar(100) DEFAULT NULL,
  `bill_city` varchar(100) DEFAULT NULL,
  `bill_county` varchar(100) DEFAULT NULL,
  `bill_postcode` varchar(100) DEFAULT NULL,
  `bill_country_code` char(2) DEFAULT NULL,
  `bill_phone_number` varchar(30) DEFAULT NULL,
  `billing_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_number`, `user_id`, `currency_code`, `order_status`, `subtotal_penny`, `shipping_penny`, `tax_penny`, `discount_penny`, `total_penny`, `ship_recipient_name`, `ship_house_number`, `ship_address_line_one`, `ship_address_line_two`, `ship_city`, `ship_county`, `ship_postcode`, `ship_country_code`, `ship_phone_number`, `shipping_address_id`, `bill_recipient_name`, `bill_house_number`, `bill_address_line_one`, `bill_address_line_two`, `bill_city`, `bill_county`, `bill_postcode`, `bill_country_code`, `bill_phone_number`, `billing_address_id`, `created_at`, `updated_at`) VALUES
(1, 'ORD-ZJ9KHNGGLV', NULL, 'GBP', 'Pending', 18000, 0, 0, 0, 18000, 'Brendon', '15', 'Brunswick', NULL, 'vdfgf', 'dfgdg', 'dfgdfgd', 'GB', '07450154745', NULL, 'Brendon', '15', 'Brunswick', NULL, 'vdfgf', 'dfgdg', 'dfgdfgd', 'GB', '07450154745', NULL, '2025-12-06 16:44:34', '2025-12-06 16:44:34'),
(2, 'ORD-VDGY3NSHHM', NULL, 'GBP', 'Pending', 600, 0, 0, 0, 600, 'dsds', '21', 'dsa', 'asda', 'dasd', 'dasd', 'asdasd', 'GB', '07450154745', NULL, 'dsds', '21', 'dsa', 'asda', 'dasd', 'dasd', 'asdasd', 'GB', '07450154745', NULL, '2025-12-06 16:46:32', '2025-12-06 16:46:32'),
(3, 'ORD-48OROILEHB', NULL, 'GBP', 'Pending', 500, 0, 0, 0, 500, 'Brendon', '15', 'bfds', 'fsdf', 'fsdfs', 'fdsf', 'sfdfs', 'GB', '07450154745', NULL, 'Brendon', '15', 'bfds', 'fsdf', 'fsdfs', 'fdsf', 'sfdfs', 'GB', '07450154745', NULL, '2025-12-09 10:35:18', '2025-12-09 10:35:18'),
(4, 'ORD-LYBUGZEK2D', 9, 'GBP', 'Paid', 7200, 0, 0, 500, 6700, 'Brendon Kovacs', '15', 'Brunswick Park Road', NULL, 'Wednesbury', NULL, 'WS10 9Hl', 'GB', NULL, NULL, 'Brendon Kovacs', '15', 'Brunswick Park Road', NULL, 'Wednesbury', NULL, 'WS10 9Hl', 'GB', NULL, NULL, '2026-02-13 17:11:34', '2026-02-13 17:11:34'),
(5, 'ORD-CHOXEDKHSY', 9, 'USD', 'Paid', 750, 0, 0, 0, 750, 'Brendon Kovacs', '15', 'Brunswick Park Road', NULL, 'Wednesbury', NULL, 'ws109hl', 'GB', NULL, 6, 'Brendon Kovacs', '15', 'Brunswick Park Road', NULL, 'Wednesbury', NULL, 'ws109hl', 'GB', NULL, 6, '2026-02-13 17:15:38', '2026-02-13 17:15:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_discounts`
--

CREATE TABLE `order_discounts` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `discount_id` bigint(20) UNSIGNED NOT NULL,
  `amount_applied_penny` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `order_discounts`
--

INSERT INTO `order_discounts` (`order_id`, `discount_id`, `amount_applied_penny`) VALUES
(4, 2, 500);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `unit_price_penny` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `tax_rate` decimal(4,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `variant_id`, `sku`, `title`, `unit_price_penny`, `quantity`, `tax_rate`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'UNKNOWN', 'Adjustable Dumbbell Set', 4500, 4, 0.00, '2025-12-06 16:44:34', '2025-12-06 16:44:34'),
(2, 2, 1, 'UNKNOWN', 'Muscle Relief Balm', 600, 1, 0.00, '2025-12-06 16:46:32', '2025-12-06 16:46:32'),
(3, 3, 1, 'UNKNOWN', 'Hot/Cold Gel Pack', 500, 1, 0.00, '2025-12-09 10:35:18', '2025-12-09 10:35:18'),
(4, 4, 29, 'CREA-STD', 'Creatine Monohydrate', 1800, 4, 0.00, '2026-02-13 17:11:34', '2026-02-13 17:11:34'),
(5, 5, 51, 'SLTEA-20', 'Sleep Support Herbal Tea', 750, 1, 0.00, '2026-02-13 17:15:38', '2026-02-13 17:15:38');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `provider_ref` varchar(100) DEFAULT NULL,
  `amount_penny` int(11) NOT NULL,
  `currency_code` char(3) NOT NULL,
  `return_status` enum('Authorised','Voided','Refunded','Failed') NOT NULL DEFAULT 'Authorised',
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `provider_id`, `provider_ref`, `amount_penny`, `currency_code`, `return_status`, `error_message`, `created_at`, `updated_at`) VALUES
(1, 4, 3, 'SIM-UUTZKMIVMQDW', 6700, 'GBP', 'Authorised', NULL, '2026-02-13 17:11:34', '2026-02-13 17:11:34'),
(2, 5, 1, 'SIM-EXCGWHAK8P2U', 750, 'USD', 'Authorised', NULL, '2026-02-13 17:15:38', '2026-02-13 17:15:38');

-- --------------------------------------------------------

--
-- Table structure for table `payment_providers`
--

CREATE TABLE `payment_providers` (
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `provider_key` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `logo_url` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `payment_providers`
--

INSERT INTO `payment_providers` (`provider_id`, `provider_key`, `display_name`, `logo_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'paypal', 'PayPal', NULL, 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'amex', 'American Express', NULL, 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'visa', 'Visa', NULL, 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'mastercard', 'Mastercard', NULL, 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'affirm', 'Affirm', NULL, 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `product_description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `short_description` varchar(500) DEFAULT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `brand_id`, `product_description`, `is_active`, `short_description`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Adjustable Dumbbell Set', 1, 'Durable adjustable dumbbells designed for customizable strength training sessions.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'Resistance Band Set', 2, 'A complete set of resistance bands ideal for strength, mobility, and rehabilitation exercises.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'Yoga Mat', 3, 'Non-slip, cushioned yoga mat offering comfort and stability for all floor workouts.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'Kettlebell (Door Frame Mount)', 4, 'Innovative kettlebell system that mounts to the door frame for versatile home workouts.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'Weighted Jump Rope (Adjustable)', 5, 'Adjustable weighted jump rope for cardio, endurance, and high-intensity training.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 'Women’s Zip Up Hoodie', 6, 'Soft, lightweight zip-up hoodie designed for performance, comfort, and warmth.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 'Men’s Athletic Joggers', 7, 'Breathable, flexible athletic joggers built for training and everyday wear.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 'Men’s Compression Tops', 8, 'High-performance compression top that enhances circulation and muscle support.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 'Sports Socks', 9, 'Moisture-wicking sports socks providing cushioning and all-day comfort.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 'Women’s Seamless Leggings (High Waist)', 10, 'High-waisted seamless leggings offering exceptional stretch, comfort, and shaping.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 'Whey Protein', 11, 'Premium whey protein powder formulated for muscle recovery and lean growth.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 'Pre-Workout Powder', 12, 'High-energy pre-workout blend designed to boost focus, endurance, and performance.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 'Creatine Monohydrate', 13, 'Pure creatine monohydrate powder to improve power, strength, and muscle output.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 'Vegan Protein Shake', 14, 'Plant-based protein shake made from clean ingredients for a smooth, nutritious boost.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 'Energy Bars', 15, 'Healthy energy bars packed with natural ingredients to fuel workouts and daily activity.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 'Gym Bag (Water Resistant)', 16, 'Spacious water-resistant gym bag with multiple compartments for organized storage.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 'Water Bottle', 17, 'Durable reusable water bottle designed to keep beverages cold for extended periods.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 'Smart Fitness Watch', 18, 'Advanced fitness watch that monitors health metrics, activity levels, and workouts.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 'Sweat Proof Headphones', 19, 'Wireless sweat-proof headphones engineered for secure and comfortable workouts.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 'Phone Armband Holder', 20, 'Flexible and secure phone armband holder ideal for running and fitness training.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 'Massage Gun', 21, 'Powerful handheld massage gun designed to relieve muscle tension and boost recovery.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 'Foam Roller', 22, 'High-density foam roller perfect for muscle release, stretching, and mobility work.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 'Muscle Relief Balm', 23, 'Cooling muscle relief balm that soothes soreness and promotes recovery.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 'Hot/Cold Gel Pack', 24, 'Reusable hot/cold gel pack designed for injury relief and recovery therapy.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 'Sleep Support Herbal Tea', 25, 'Relaxing herbal tea blend formulated to promote calmness and restful sleep.', 1, NULL, NULL, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(1, 6),
(2, 7),
(3, 8),
(4, 9),
(5, 10),
(6, 11),
(7, 12),
(8, 13),
(9, 14),
(10, 15),
(11, 16),
(12, 17),
(13, 18),
(14, 19),
(15, 20),
(16, 21),
(17, 22),
(18, 23),
(19, 24),
(20, 25),
(21, 26),
(22, 27),
(23, 28),
(24, 29),
(25, 30);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `variant_id`, `image_url`, `alt_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'products/creatine_monohydrate.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 2, 'products/dumbells.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 3, 'products/energy_bar.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 4, 'products/fitness_smart_watch.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 5, 'products/kettlebell.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 6, 'products/mens_athletic_joggers.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 7, 'products/mens_compession_top.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 8, 'products/phone_holding_arm_strap.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 9, 'products/protein_powder.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 10, 'products/resestance_bands.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 11, 'products/sports_socks.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 12, 'products/sweat_proof_headphones.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 13, 'products/vegan_protein_shake.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 14, 'products/waterproof_gym_bag.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 15, 'products/water_bottle.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 16, 'products/weighted_skipping_rope.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 17, 'products/whey.jpg', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 18, 'products/womens_seemless_leggings.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 19, 'products/womens_zipped_hoddie.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 20, 'products/yogamat.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 21, 'products/creatine_monohydrate.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 22, 'products/dumbells.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 23, 'products/energy_bar.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 24, 'products/fitness_smart_watch.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 25, 'products/kettlebell.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(26, 26, 'products/mens_athletic_joggers.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(27, 27, 'products/mens_compession_top.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(28, 28, 'products/phone_holding_arm_strap.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(29, 29, 'products/protein_powder.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(30, 30, 'products/resestance_bands.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(31, 31, 'products/sports_socks.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(32, 32, 'products/sweat_proof_headphones.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(33, 33, 'products/vegan_protein_shake.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(34, 34, 'products/waterproof_gym_bag.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(35, 35, 'products/water_bottle.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(36, 36, 'products/weighted_skipping_rope.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(37, 37, 'products/whey.jpg', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(38, 38, 'products/womens_seemless_leggings.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(39, 39, 'products/womens_zipped_hoddie.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(40, 40, 'products/yogamat.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(41, 41, 'products/creatine_monohydrate.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(42, 42, 'products/dumbells.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(43, 43, 'products/energy_bar.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(44, 44, 'products/fitness_smart_watch.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(45, 45, 'products/kettlebell.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(46, 46, 'products/mens_athletic_joggers.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(47, 47, 'products/mens_compession_top.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(48, 48, 'products/phone_holding_arm_strap.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(49, 49, 'products/protein_powder.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(50, 50, 'products/resestance_bands.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(51, 51, 'products/sports_socks.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(52, 52, 'products/sweat_proof_headphones.png', 'Product image', 0, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(64) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `barcode` varchar(64) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `options_key` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `sku`, `stock_quantity`, `barcode`, `title`, `options_key`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'ADJDB-20KG', 0, NULL, 'Adjustable Dumbbell Set - 20 kg Set', 'weight:20kg_set', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 1, 'ADJDB-30KG', 0, NULL, 'Adjustable Dumbbell Set - 30 kg Set', 'weight:30kg_set', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 2, 'RESBND-PCK6', 0, NULL, 'Resistance Band Set - Pack of 6', 'pack_size:6', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 2, 'RESBND-PCK12', 0, NULL, 'Resistance Band Set - Pack of 12', 'pack_size:12', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 3, 'YOGMAT-BLK-173', 0, NULL, 'Yoga Mat - Black / 173 cm', 'colour:black;length:173cm', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 3, 'YOGMAT-BLU-183', 0, NULL, 'Yoga Mat - Blue / 183 cm', 'colour:blue;length:183cm', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 4, 'KETDF-8KG', 0, NULL, 'Kettlebell (Door Frame Mount) - 8 kg', 'weight:8kg', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 4, 'KETDF-12KG', 0, NULL, 'Kettlebell (Door Frame Mount) - 12 kg', 'weight:12kg', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 5, 'WJROPE-2_6M', 0, NULL, 'Weighted Jump Rope (Adjustable) - 2.6 m', 'length:2.6m', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 5, 'WJROPE-2_8M', 0, NULL, 'Weighted Jump Rope (Adjustable) - 2.8 m', 'length:2.8m', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 6, 'WZIPHD-BLK-S', 0, NULL, 'Women\'s Zip Up Hoodie - Black / S', 'colour:black;size:S', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 6, 'WZIPHD-BLK-M', 0, NULL, 'Women\'s Zip Up Hoodie - Black / M', 'colour:black;size:M', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 6, 'WZIPHD-BLK-L', 0, NULL, 'Women\'s Zip Up Hoodie - Black / L', 'colour:black;size:L', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 7, 'MAJOG-GRY-S', 0, NULL, 'Men\'s Athletic Joggers - Grey / S', 'colour:grey;size:S', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 7, 'MAJOG-GRY-M', 0, NULL, 'Men\'s Athletic Joggers - Grey / M', 'colour:grey;size:M', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 7, 'MAJOG-GRY-L', 0, NULL, 'Men\'s Athletic Joggers - Grey / L', 'colour:grey;size:L', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 8, 'MCOMP-NVY-S', 0, NULL, 'Men\'s Compression Tops - Navy / S', 'colour:navy;size:S', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 8, 'MCOMP-NVY-M', 0, NULL, 'Men\'s Compression Tops - Navy / M', 'colour:navy;size:M', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 8, 'MCOMP-NVY-L', 0, NULL, 'Men\'s Compression Tops - Navy / L', 'colour:navy;size:L', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 9, 'SSOCK-WHT-M', 0, NULL, 'Sports Socks - White / M', 'colour:white;size:M', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 9, 'SSOCK-WHT-L', 0, NULL, 'Sports Socks - White / L', 'colour:white;size:L', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 10, 'WLEG-BLK-S', 0, NULL, 'Women\'s Seamless Leggings (High Waist) - Black / S', 'colour:black;size:S', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 10, 'WLEG-BLK-M', 0, NULL, 'Women\'s Seamless Leggings (High Waist) - Black / M', 'colour:black;size:M', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 10, 'WLEG-BLK-L', 0, NULL, 'Women\'s Seamless Leggings (High Waist) - Black / L', 'colour:black;size:L', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 11, 'WHEY-CHOC', 0, NULL, 'Whey Protein - Chocolate', 'flavour:chocolate', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(26, 11, 'WHEY-VAN', 0, NULL, 'Whey Protein - Vanilla', 'flavour:vanilla', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(27, 12, 'PREW-BER', 0, NULL, 'Pre-Workout Powder - Berry Mix', 'flavour:berry', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(28, 12, 'PREW-UNFLAV', 0, NULL, 'Pre-Workout Powder - Unflavoured', 'flavour:unflavoured', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(29, 13, 'CREA-STD', 0, NULL, 'Creatine Monohydrate - Standard', NULL, 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(30, 14, 'VPROT-CHOC', 0, NULL, 'Vegan Protein Shake - Chocolate', 'flavour:chocolate', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(31, 14, 'VPROT-VAN', 0, NULL, 'Vegan Protein Shake - Vanilla', 'flavour:vanilla', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(32, 15, 'ENBAR-CHOC-12', 0, NULL, 'Energy Bars - Chocolate / Pack of 12', 'flavour:chocolate;pack_size:12', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(33, 15, 'ENBAR-BER-12', 0, NULL, 'Energy Bars - Berry Mix / Pack of 12', 'flavour:berry;pack_size:12', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(34, 16, 'GYMBAG-BLK', 0, NULL, 'Gym Bag (Water Resistant) - Black', 'colour:black', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(35, 16, 'GYMBAG-BLU', 0, NULL, 'Gym Bag (Water Resistant) - Blue', 'colour:blue', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(36, 17, 'WBOTT-BLK-500', 0, NULL, 'Water Bottle - Black / 500 ml', 'colour:black;volume:500ml', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(37, 17, 'WBOTT-BLU-750', 0, NULL, 'Water Bottle - Blue / 750 ml', 'colour:blue;volume:750ml', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(38, 18, 'SFWATCH-BLK', 0, NULL, 'Smart Fitness Watch - Black', 'colour:black', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(39, 18, 'SFWATCH-WHT', 0, NULL, 'Smart Fitness Watch - White', 'colour:white', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(40, 19, 'SPHEAD-BLK', 0, NULL, 'Sweat Proof Headphones - Black', 'colour:black', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(41, 19, 'SPHEAD-BLU', 0, NULL, 'Sweat Proof Headphones - Blue', 'colour:blue', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(42, 20, 'PABAND-BLK-M', 0, NULL, 'Phone Armband Holder - Black / M', 'colour:black;size:M', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(43, 20, 'PABAND-BLK-L', 0, NULL, 'Phone Armband Holder - Black / L', 'colour:black;size:L', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(44, 21, 'MASSG-BLK', 0, NULL, 'Massage Gun - Black', 'colour:black', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(45, 22, 'FROLLER-45', 0, NULL, 'Foam Roller - 45 cm', 'length:45cm', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(46, 22, 'FROLLER-60', 0, NULL, 'Foam Roller - 60 cm', 'length:60cm', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(47, 23, 'MRBALM-50', 0, NULL, 'Muscle Relief Balm - 50 ml', 'volume:50ml', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(48, 23, 'MRBALM-100', 0, NULL, 'Muscle Relief Balm - 100 ml', 'volume:100ml', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(49, 24, 'HCGEL-SINGLE', 0, NULL, 'Hot/Cold Gel Pack - Single Pack', 'pack_size:1', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(50, 24, 'HCGEL-PCK2', 0, NULL, 'Hot/Cold Gel Pack - Pack of 2', 'pack_size:2', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(51, 25, 'SLTEA-20', 0, NULL, 'Sleep Support Herbal Tea - 20 Bags', 'pack_size:20', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(52, 25, 'SLTEA-40', 0, NULL, 'Sleep Support Herbal Tea - 40 Bags', 'pack_size:40', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `refund_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `amount_penny` int(11) NOT NULL,
  `refund_status` enum('Pending','Succeeded','Rejected') NOT NULL DEFAULT 'Pending',
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `return_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `return_status` enum('Requested','Approved','Received','Rejected','Refunded','Cancelled') NOT NULL DEFAULT 'Requested',
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_items`
--

CREATE TABLE `return_items` (
  `return_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `refund_amount_penny` int(11) DEFAULT NULL,
  `condition_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` smallint(6) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `country_phone_code` int(11) NOT NULL,
  `phone_number` bigint(20) UNSIGNED NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `user_role` enum('admin','customer','business') NOT NULL DEFAULT 'customer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `company_name`, `country_phone_code`, `phone_number`, `email_verified`, `user_role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Anita', 'Go', NULL, 1, 3094555873, 0, 'admin', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'Orla', 'Shore', NULL, 44, 7762398615, 0, 'customer', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'Herman', 'Isgone', NULL, 7, 8555924671, 0, 'customer', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'Issac', 'Lollies', 'Confectionary&Co.', 36, 306598667, 0, 'business', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'John', 'Smith', 'Papermaker', 36, 706598667, 0, 'business', 1, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 'Phoebe', 'Allen', NULL, 44, 7450154745, 0, 'customer', 1, '2025-12-06 20:35:33', '2025-12-06 20:35:33'),
(9, 'Brendon', 'Kovacs', NULL, 44, 7450154745, 0, 'customer', 1, '2026-02-13 16:53:48', '2026-02-13 16:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `user_tax_profiles`
--

CREATE TABLE `user_tax_profiles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vat_number` varchar(50) DEFAULT NULL,
  `tax_exempt` tinyint(1) NOT NULL DEFAULT 0,
  `tax_validation_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `user_tax_profiles`
--

INSERT INTO `user_tax_profiles` (`user_id`, `vat_number`, `tax_exempt`, `tax_validation_date`, `created_at`) VALUES
(1, NULL, 0, NULL, '2025-12-05 13:10:27'),
(2, NULL, 0, NULL, '2025-12-05 13:10:27'),
(3, NULL, 0, NULL, '2025-12-05 13:10:27'),
(4, 'GB672076234', 1, '2030-05-14 23:00:00', '2025-12-05 13:10:27'),
(5, 'GB982617630', 1, '2030-05-14 23:00:00', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `variant_attributes`
--

CREATE TABLE `variant_attributes` (
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `selection_order` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `variant_attributes`
--

INSERT INTO `variant_attributes` (`attribute_id`, `attribute_name`, `display_name`, `selection_order`, `is_active`) VALUES
(1, 'size', 'Size', 1, 1),
(2, 'colour', 'Colour', 2, 1),
(3, 'flavour', 'Flavour', 3, 1),
(4, 'volume', 'Volume', 4, 1),
(5, 'weight', 'Weight', 5, 1),
(6, 'length', 'Length', 6, 1),
(7, 'pack_size', 'Pack Size', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `variant_combinations`
--

CREATE TABLE `variant_combinations` (
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `option_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `variant_combinations`
--

INSERT INTO `variant_combinations` (`variant_id`, `option_id`) VALUES
(1, 26),
(2, 27),
(3, 43),
(4, 44),
(5, 6),
(5, 35),
(6, 10),
(6, 36),
(7, 29),
(8, 30),
(9, 37),
(10, 38),
(11, 2),
(11, 6),
(12, 3),
(12, 6),
(13, 4),
(13, 6),
(14, 2),
(14, 7),
(15, 3),
(15, 7),
(16, 4),
(16, 7),
(17, 2),
(17, 8),
(18, 3),
(18, 8),
(19, 4),
(19, 8),
(20, 3),
(20, 13),
(21, 4),
(21, 13),
(22, 2),
(22, 6),
(23, 3),
(23, 6),
(24, 4),
(24, 6),
(25, 14),
(26, 15),
(27, 18),
(28, 19),
(30, 14),
(31, 15),
(32, 14),
(32, 44),
(33, 18),
(33, 44),
(34, 6),
(35, 10),
(36, 6),
(36, 20),
(37, 10),
(37, 22),
(38, 6),
(39, 13),
(40, 6),
(41, 10),
(42, 3),
(42, 6),
(43, 4),
(43, 6),
(44, 6),
(45, 41),
(46, 42),
(47, 24),
(48, 25),
(49, 46),
(50, 47),
(51, 48),
(52, 49);

-- --------------------------------------------------------

--
-- Table structure for table `variant_currency_prices`
--

CREATE TABLE `variant_currency_prices` (
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `currency_code` char(3) NOT NULL,
  `price_penny` int(11) NOT NULL,
  `was_price_penny` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `variant_currency_prices`
--

INSERT INTO `variant_currency_prices` (`variant_id`, `currency_code`, `price_penny`, `was_price_penny`, `created_at`, `updated_at`) VALUES
(1, 'EUR', 5175, 5750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(1, 'GBP', 4500, 5000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(1, 'USD', 5625, 6250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'EUR', 6325, 6900, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'GBP', 5500, 6000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(2, 'USD', 6875, 7500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'EUR', 1380, 1725, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'GBP', 1200, 1500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(3, 'USD', 1500, 1875, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'EUR', 2070, 2300, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'GBP', 1800, 2000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(4, 'USD', 2250, 2500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'EUR', 2070, 2300, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'GBP', 1800, 2000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(5, 'USD', 2250, 2500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 'EUR', 2300, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 'GBP', 2000, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(6, 'USD', 2500, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 'EUR', 2300, 2875, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 'GBP', 2000, 2500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(7, 'USD', 2500, 3125, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 'EUR', 2990, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 'GBP', 2600, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(8, 'USD', 3250, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 'EUR', 1380, 1725, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 'GBP', 1200, 1500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(9, 'USD', 1500, 1875, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 'EUR', 1495, 1840, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 'GBP', 1300, 1600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(10, 'USD', 1625, 2000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 'EUR', 2530, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 'GBP', 2500, 3000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(11, 'USD', 3125, 3750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 'EUR', 2990, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 'GBP', 2600, 3000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(12, 'USD', 3250, 3750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 'EUR', 3105, 3680, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 'GBP', 2700, 3200, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(13, 'USD', 3375, 4000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 'EUR', 2530, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 'GBP', 2200, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(14, 'USD', 2750, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 'EUR', 2645, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 'GBP', 2300, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(15, 'USD', 2875, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 'EUR', 2760, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 'GBP', 2400, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(16, 'USD', 3000, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 'EUR', 2070, 2530, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 'GBP', 1800, 2200, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(17, 'USD', 2250, 2750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 'EUR', 2185, 2530, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 'GBP', 1900, 2200, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(18, 'USD', 2375, 2750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 'EUR', 2300, 2530, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 'GBP', 2000, 2200, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(19, 'USD', 2500, 2750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 'EUR', 805, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 'GBP', 700, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(20, 'USD', 875, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 'EUR', 920, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 'GBP', 800, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(21, 'USD', 1000, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 'EUR', 2530, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 'GBP', 2200, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(22, 'USD', 2750, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 'EUR', 2645, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 'GBP', 2300, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(23, 'USD', 2875, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 'EUR', 2760, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 'GBP', 2400, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(24, 'USD', 3000, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 'EUR', 2875, 3450, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 'GBP', 2500, 3000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(25, 'USD', 3125, 3750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(26, 'EUR', 2990, 3450, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(26, 'GBP', 2600, 3000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(26, 'USD', 3250, 3750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(27, 'EUR', 2530, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(27, 'GBP', 2200, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(27, 'USD', 2750, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(28, 'EUR', 2300, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(28, 'GBP', 2000, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(28, 'USD', 2500, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(29, 'EUR', 2070, 2300, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(29, 'GBP', 1800, 2000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(29, 'USD', 2250, 2500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(30, 'EUR', 2645, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(30, 'GBP', 2300, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(30, 'USD', 2875, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(31, 'EUR', 2760, 2990, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(31, 'GBP', 2400, 2600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(31, 'USD', 3000, 3250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(32, 'EUR', 1725, 2070, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(32, 'GBP', 1500, 1800, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(32, 'USD', 1875, 2250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(33, 'EUR', 1725, 2070, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(33, 'GBP', 1500, 1800, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(33, 'USD', 1875, 2250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(34, 'EUR', 2300, 2875, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(34, 'GBP', 2000, 2500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(34, 'USD', 2500, 3125, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(35, 'EUR', 2300, 2875, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(35, 'GBP', 2000, 2500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(35, 'USD', 2500, 3125, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(36, 'EUR', 920, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(36, 'GBP', 800, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(36, 'USD', 1000, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(37, 'EUR', 1150, 1380, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(37, 'GBP', 1000, 1200, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(37, 'USD', 1250, 1500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(38, 'EUR', 5175, 5750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(38, 'GBP', 4500, 5000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(38, 'USD', 5625, 6250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(39, 'EUR', 5175, 5750, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(39, 'GBP', 4500, 5000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(39, 'USD', 5625, 6250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(40, 'EUR', 3450, 4025, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(40, 'GBP', 3000, 3500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(40, 'USD', 3750, 4375, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(41, 'EUR', 3450, 4025, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(41, 'GBP', 3000, 3500, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(41, 'USD', 3750, 4375, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(42, 'EUR', 1035, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(42, 'GBP', 900, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(42, 'USD', 1125, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(43, 'EUR', 1035, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(43, 'GBP', 900, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(43, 'USD', 1125, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(44, 'EUR', 4025, 4600, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(44, 'GBP', 3500, 4000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(44, 'USD', 4375, 5000, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(45, 'EUR', 1380, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(45, 'GBP', 1200, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(45, 'USD', 1500, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(46, 'EUR', 1725, 2070, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(46, 'GBP', 1500, 1800, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(46, 'USD', 1875, 2250, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(47, 'EUR', 690, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(47, 'GBP', 600, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(47, 'USD', 750, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(48, 'EUR', 1035, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(48, 'GBP', 900, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(48, 'USD', 1125, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(49, 'EUR', 575, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(49, 'GBP', 500, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(49, 'USD', 625, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(50, 'EUR', 1035, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(50, 'GBP', 900, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(50, 'USD', 1125, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(51, 'EUR', 690, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(51, 'GBP', 600, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(51, 'USD', 750, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(52, 'EUR', 1035, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(52, 'GBP', 900, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27'),
(52, 'USD', 1125, NULL, '2025-12-05 13:10:27', '2025-12-05 13:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `variant_options`
--

CREATE TABLE `variant_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `variant_value` varchar(100) NOT NULL,
  `display_value` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `variant_options`
--

INSERT INTO `variant_options` (`option_id`, `attribute_id`, `variant_value`, `display_value`, `sort_order`, `is_active`) VALUES
(1, 1, 'XS', 'XS', 1, 1),
(2, 1, 'S', 'Small', 2, 1),
(3, 1, 'M', 'Medium', 3, 1),
(4, 1, 'L', 'Large', 4, 1),
(5, 1, 'XL', 'XL', 5, 1),
(6, 2, 'black', 'Black', 1, 1),
(7, 2, 'grey', 'Grey', 2, 1),
(8, 2, 'navy', 'Navy', 3, 1),
(9, 2, 'red', 'Red', 4, 1),
(10, 2, 'blue', 'Blue', 5, 1),
(11, 2, 'green', 'Green', 6, 1),
(12, 2, 'purple', 'Purple', 7, 1),
(13, 2, 'white', 'White', 8, 1),
(14, 3, 'chocolate', 'Chocolate', 1, 1),
(15, 3, 'vanilla', 'Vanilla', 2, 1),
(16, 3, 'strawberry', 'Strawberry', 3, 1),
(17, 3, 'banana', 'Banana', 4, 1),
(18, 3, 'berry', 'Berry Mix', 5, 1),
(19, 3, 'unflavoured', 'Unflavoured', 6, 1),
(20, 4, '500ml', '500 ml', 1, 1),
(21, 4, '600ml', '600 ml', 2, 1),
(22, 4, '750ml', '750 ml', 3, 1),
(23, 4, '1l', '1 L', 4, 1),
(24, 4, '50ml', '50 ml', 5, 1),
(25, 4, '100ml', '100 ml', 6, 1),
(26, 5, '20kg_set', '20 kg Set', 1, 1),
(27, 5, '30kg_set', '30 kg Set', 2, 1),
(28, 5, '40kg_set', '40 kg Set', 3, 1),
(29, 5, '8kg', '8 kg', 4, 1),
(30, 5, '12kg', '12 kg', 5, 1),
(31, 5, '16kg', '16 kg', 6, 1),
(32, 5, '20kg', '20 kg', 7, 1),
(33, 5, '0.5kg', '0.5 kg', 8, 1),
(34, 5, '1kg', '1 kg', 9, 1),
(35, 6, '173cm', '173 cm', 1, 1),
(36, 6, '183cm', '183 cm', 2, 1),
(37, 6, '2.6m', '2.6 m', 3, 1),
(38, 6, '2.8m', '2.8 m', 4, 1),
(39, 6, '3.0m', '3.0 m', 5, 1),
(40, 6, '30cm', '30 cm', 6, 1),
(41, 6, '45cm', '45 cm', 7, 1),
(42, 6, '60cm', '60 cm', 8, 1),
(43, 7, '6', 'Pack of 6', 1, 1),
(44, 7, '12', 'Pack of 12', 2, 1),
(45, 7, '10', 'Pack of 10', 3, 1),
(46, 7, '1', 'Single Pack', 4, 1),
(47, 7, '2', 'Pack of 2', 5, 1),
(48, 7, '20', 'Pack of 20', 6, 1),
(49, 7, '40', 'Pack of 40', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `wishlist_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `wishlist_name` varchar(100) NOT NULL DEFAULT 'My Wishlist',
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `wishlists_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_default_billing` (`is_default_billing_address`),
  ADD KEY `idx_default_shipping` (`is_default_shipping_address`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD UNIQUE KEY `brand_name` (`brand_name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `client_token` (`client_token`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_sort_order` (`sort_order`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_code`),
  ADD UNIQUE KEY `iso_alpha_3` (`iso_alpha_3`),
  ADD UNIQUE KEY `iso_numeric` (`iso_numeric`),
  ADD KEY `idx_country_name` (`country_name`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currency_code`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD PRIMARY KEY (`query_id`),
  ADD KEY `idx_contact_status` (`contact_status`),
  ADD KEY `idx_customer_contacts_product_id` (`product_id`),
  ADD KEY `idx_customer_contacts_variant_id` (`variant_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`),
  ADD UNIQUE KEY `discount_code` (`discount_code`),
  ADD KEY `idx_code` (`discount_code`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_starts_at` (`starts_at`),
  ADD KEY `idx_ends_at` (`ends_at`);

--
-- Indexes for table `discount_redemptions`
--
ALTER TABLE `discount_redemptions`
  ADD PRIMARY KEY (`redemption_id`),
  ADD KEY `idx_discount_id` (`discount_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`variant_id`);

--
-- Indexes for table `login_details`
--
ALTER TABLE `login_details`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD KEY `idx_email_address` (`email_address`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `currency_code` (`currency_code`),
  ADD KEY `ship_country_code` (`ship_country_code`),
  ADD KEY `bill_country_code` (`bill_country_code`),
  ADD KEY `shipping_address_id` (`shipping_address_id`),
  ADD KEY `billing_address_id` (`billing_address_id`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`order_status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `order_discounts`
--
ALTER TABLE `order_discounts`
  ADD PRIMARY KEY (`order_id`,`discount_id`),
  ADD KEY `discount_id` (`discount_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `currency_code` (`currency_code`);

--
-- Indexes for table `payment_providers`
--
ALTER TABLE `payment_providers`
  ADD PRIMARY KEY (`provider_id`),
  ADD UNIQUE KEY `provider_key` (`provider_key`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_brand_id` (`brand_id`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`refund_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_payment_id` (`payment_id`),
  ADD KEY `idx_status` (`refund_status`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_status` (`return_status`);

--
-- Indexes for table `return_items`
--
ALTER TABLE `return_items`
  ADD PRIMARY KEY (`return_id`,`order_item_id`),
  ADD KEY `idx_return_id` (`return_id`),
  ADD KEY `idx_order_item_id` (`order_item_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_approved` (`is_approved`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_role` (`user_role`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `user_tax_profiles`
--
ALTER TABLE `user_tax_profiles`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_vat_number` (`vat_number`),
  ADD KEY `idx_tax_exempt` (`tax_exempt`);

--
-- Indexes for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  ADD PRIMARY KEY (`attribute_id`),
  ADD UNIQUE KEY `attribute_name` (`attribute_name`),
  ADD UNIQUE KEY `display_name` (`display_name`);

--
-- Indexes for table `variant_combinations`
--
ALTER TABLE `variant_combinations`
  ADD PRIMARY KEY (`variant_id`,`option_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `variant_currency_prices`
--
ALTER TABLE `variant_currency_prices`
  ADD PRIMARY KEY (`variant_id`,`currency_code`),
  ADD KEY `idx_variant_id` (`variant_id`),
  ADD KEY `idx_currency_code` (`currency_code`);

--
-- Indexes for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `idx_attribute_sort` (`attribute_id`,`sort_order`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`wishlists_id`,`variant_id`),
  ADD KEY `idx_wishlists_id` (`wishlists_id`),
  ADD KEY `idx_variant_id` (`variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  MODIFY `query_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `discount_redemptions`
--
ALTER TABLE `discount_redemptions`
  MODIFY `redemption_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_providers`
--
ALTER TABLE `payment_providers`
  MODIFY `provider_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `refund_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  MODIFY `attribute_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `variant_options`
--
ALTER TABLE `variant_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `addresses_ibfk_2` FOREIGN KEY (`country_code`) REFERENCES `countries` (`country_code`) ON UPDATE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD CONSTRAINT `fk_customer_contacts_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_customer_contacts_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE SET NULL;

--
-- Constraints for table `discount_redemptions`
--
ALTER TABLE `discount_redemptions`
  ADD CONSTRAINT `discount_redemptions_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`discount_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discount_redemptions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `discount_redemptions_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login_details`
--
ALTER TABLE `login_details`
  ADD CONSTRAINT `login_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`currency_code`) REFERENCES `currencies` (`currency_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`ship_country_code`) REFERENCES `countries` (`country_code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`bill_country_code`) REFERENCES `countries` (`country_code`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`address_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_6` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`address_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_discounts`
--
ALTER TABLE `order_discounts`
  ADD CONSTRAINT `order_discounts_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_discounts_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`discount_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `payment_providers` (`provider_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`currency_code`) REFERENCES `currencies` (`currency_code`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON UPDATE CASCADE;

--
-- Constraints for table `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `refunds_ibfk_2` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON UPDATE CASCADE;

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `return_items`
--
ALTER TABLE `return_items`
  ADD CONSTRAINT `return_items_ibfk_1` FOREIGN KEY (`return_id`) REFERENCES `returns` (`return_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `return_items_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_tax_profiles`
--
ALTER TABLE `user_tax_profiles`
  ADD CONSTRAINT `user_tax_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `variant_combinations`
--
ALTER TABLE `variant_combinations`
  ADD CONSTRAINT `variant_combinations_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `variant_combinations_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `variant_options` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `variant_currency_prices`
--
ALTER TABLE `variant_currency_prices`
  ADD CONSTRAINT `variant_currency_prices_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `variant_currency_prices_ibfk_2` FOREIGN KEY (`currency_code`) REFERENCES `currencies` (`currency_code`) ON UPDATE CASCADE;

--
-- Constraints for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD CONSTRAINT `variant_options_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `variant_attributes` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `wishlist_items_ibfk_1` FOREIGN KEY (`wishlists_id`) REFERENCES `wishlists` (`wishlist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wishlist_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
