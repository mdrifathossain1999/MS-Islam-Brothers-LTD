-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2026 at 09:20 PM
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
-- Database: `b17_41452072_jolchobi_setabgonj`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `reference_type`, `reference_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'payment', 'customer', 'Received payment ৳145.00 from Md Rifat Hossain', 'customer', 13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-21 18:15:49'),
(2, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 09:48:36'),
(3, 1, 'sale', 'sale', 'Sale completed: INV-20260322-0001 - Total: ৳820.00', 'sale', 26, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 09:49:14'),
(4, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:24:48'),
(5, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:28:29'),
(6, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:28:53'),
(7, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:28:55'),
(8, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:38:16'),
(9, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:49:41'),
(10, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:49:44'),
(11, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:49:53'),
(12, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 17:54:49'),
(13, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:09:11'),
(14, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:09:15'),
(15, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:09:29'),
(16, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:10:04'),
(17, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:10:37'),
(18, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:13:03'),
(19, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:13:08'),
(20, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:13:31'),
(21, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:13:34'),
(22, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:13:38'),
(23, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:13:50'),
(24, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:33:59'),
(25, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:34:09'),
(26, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:37:54'),
(27, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:37:59'),
(28, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:38:01'),
(29, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:41:30'),
(30, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:50:00'),
(31, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:53:07'),
(32, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:58:11'),
(33, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:59:09'),
(34, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 18:59:34'),
(35, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:00:55'),
(36, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:03:10'),
(37, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:04:26'),
(38, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:04:57'),
(39, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:08:03'),
(40, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:08:26'),
(41, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:08:55'),
(42, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:09:33'),
(43, 1, 'update', 'settings', 'Updated system settings', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:09:41'),
(44, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:09:46'),
(45, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:12:39'),
(46, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:15:58'),
(47, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:16:44'),
(48, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:17:06'),
(49, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:35:12'),
(50, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:35:37'),
(51, 1, 'login', 'auth', 'User logged in: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:35:46'),
(52, 1, 'sale', 'sale', 'Sale completed: INV-20260322-0002 - Total: ৳355.00', 'sale', 27, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:36:30'),
(53, 1, 'logout', 'auth', 'User logged out: admin', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 19:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total_purchases` decimal(10,2) DEFAULT 0.00,
  `loyalty_points` int(11) DEFAULT 0,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `phone`, `email`, `address`, `total_purchases`, `loyalty_points`, `total_amount`, `paid_amount`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Walk-in Customer', 'WALKIN', '', 'Local/Walk-in Sales', 2320.00, 2320, 1520.00, 1145.00, 'active', '2026-03-21 17:23:29', '2026-03-22 19:36:30'),
(13, 'Md Rifat Hossain', '01889933541', 'mdrifathossainpersonal@gmail.com', 'dinajpur', 1145.00, 1145, 145.00, 145.00, 'active', '2026-03-21 18:02:01', '2026-03-21 18:15:49');

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE `customer_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `display_order` int(11) DEFAULT 0,
  `is_active` enum('yes','no') DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `type_name`, `description`, `discount_percent`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Retailer', 'Regular retail customers', 0.00, 1, 'yes', '2026-03-21 17:11:42', '2026-03-21 17:11:42'),
(2, 'Dealer', 'Wholesale dealers with discount', 5.00, 2, 'yes', '2026-03-21 17:11:42', '2026-03-21 17:11:42'),
(3, 'Wholesaler', 'Bulk buyers with special rates', 10.00, 3, 'yes', '2026-03-21 17:11:42', '2026-03-21 17:11:42'),
(4, 'VIP', 'VIP customers with extra benefits', 15.00, 4, 'yes', '2026-03-21 17:11:42', '2026-03-21 17:11:42'),
(5, 'Local', 'Local', 0.00, 0, 'yes', '2026-03-21 17:12:43', '2026-03-21 17:12:43');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `payment_method` enum('cash','card','mobile') DEFAULT 'cash',
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT 0.00,
  `sell_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `low_stock_threshold` int(11) DEFAULT 10,
  `unit` varchar(20) DEFAULT 'piece',
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `barcode`, `product_name`, `category`, `description`, `cost_price`, `sell_price`, `stock_quantity`, `low_stock_threshold`, `unit`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, '1234567890123', 'Coca Cola 500ml', 'Beverages', NULL, 20.00, 35.00, 88, 10, 'bottle', NULL, 'active', '2026-03-17 19:24:20', '2026-03-22 19:36:30'),
(2, '2345678901234', 'Pepsi 500ml', 'Beverages', NULL, 20.00, 35.00, 97, 10, 'bottle', NULL, 'active', '2026-03-17 19:24:20', '2026-03-21 18:02:26'),
(3, '3456789012345', 'Water Bottle 1L', 'Beverages', NULL, 10.00, 20.00, 199, 10, 'bottle', NULL, 'active', '2026-03-17 19:24:20', '2026-03-19 01:09:22'),
(4, '4567890123456', 'Bread (Loaf)', 'Bakery', NULL, 25.00, 40.00, 32, 10, 'piece', NULL, 'active', '2026-03-17 19:24:20', '2026-03-21 17:48:21'),
(5, '5678901234567', 'Milk Packet 1L', 'Dairy', NULL, 45.00, 60.00, 74, 10, 'packet', NULL, 'active', '2026-03-17 19:24:20', '2026-03-22 09:49:14'),
(6, '6789012345678', 'Eggs (Dozen)', 'Dairy', NULL, 80.00, 120.00, 27, 10, 'dozen', NULL, 'active', '2026-03-17 19:24:20', '2026-03-22 19:36:30'),
(7, '7890123456789', 'Sugar 1kg', 'Groceries', NULL, 55.00, 70.00, 60, 10, 'kg', NULL, 'active', '2026-03-17 19:24:20', '2026-03-17 19:24:20'),
(8, '8901234567890', 'Rice 1kg', 'Groceries', NULL, 40.00, 55.00, 92, 10, 'kg', NULL, 'active', '2026-03-17 19:24:20', '2026-03-19 01:09:22'),
(9, '9012345678901', 'Cooking Oil 1L', 'Groceries', NULL, 90.00, 120.00, 29, 10, 'liter', NULL, 'active', '2026-03-17 19:24:20', '2026-03-22 09:49:14'),
(10, '0123456789012', 'Salt 1kg', 'Groceries', NULL, 15.00, 25.00, 75, 10, 'kg', NULL, 'active', '2026-03-17 19:24:20', '2026-03-17 20:44:36'),
(11, '1234567890124', 'White T-Shert', 'T-Shert', 'shilat', 100.00, 150.00, 49, 10, 'piece', NULL, 'active', '2026-03-18 20:13:55', '2026-03-19 21:12:25'),
(15, '1234567890001', 'text', 'T-Shert', 'dfsadfsa', 200.00, 500.00, 29, 10, 'piece', NULL, 'active', '2026-03-18 20:26:19', '2026-03-19 21:12:25'),
(16, NULL, 'Gildan® Budget Unisex T-shirt', 'White T-Shert', 'Spotlight your cause effortlessly thanks to this custom Gildan® tee. A budget-friendly, durable & unisex favorite for everyday wear.', 120.00, 200.00, 29, 10, 'L', 'uploads/products/gildan___budget_unisex_t-shirt.webp', 'active', '2026-03-19 05:43:32', '2026-03-22 19:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','mobile') DEFAULT 'cash',
  `mobile_type` varchar(20) DEFAULT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `change_amount` decimal(10,2) DEFAULT 0.00,
  `sale_date` date NOT NULL,
  `sale_time` time NOT NULL,
  `status` enum('completed','returned','cancelled') DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_number`, `customer_id`, `user_id`, `subtotal`, `discount_amount`, `tax_amount`, `total_amount`, `payment_method`, `mobile_type`, `paid_amount`, `change_amount`, `sale_date`, `sale_time`, `status`, `notes`, `created_at`) VALUES
(25, 'INV-20260321-0001', 13, 1, 1145.00, 0.00, 0.00, 1145.00, 'cash', NULL, 1000.00, 0.00, '2026-03-21', '19:02:26', 'completed', NULL, '2026-03-21 18:02:26'),
(26, 'INV-20260322-0001', 8, 1, 820.00, 0.00, 0.00, 820.00, 'cash', NULL, 500.00, 0.00, '2026-03-22', '10:49:14', 'completed', NULL, '2026-03-22 09:49:14'),
(27, 'INV-20260322-0002', 8, 1, 355.00, 0.00, 0.00, 355.00, 'cash', NULL, 300.00, 0.00, '2026-03-22', '20:36:30', 'completed', NULL, '2026-03-22 19:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `barcode`, `product_name`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(72, 25, 1, '1234567890123', 'Coca Cola 500ml', 1, 35.00, 35.00, '2026-03-21 18:02:26'),
(73, 25, 6, '6789012345678', 'Eggs (Dozen)', 1, 120.00, 120.00, '2026-03-21 18:02:26'),
(74, 25, 16, NULL, 'Gildan® Budget Unisex T-shirt', 4, 200.00, 800.00, '2026-03-21 18:02:26'),
(75, 25, 9, '9012345678901', 'Cooking Oil 1L', 1, 120.00, 120.00, '2026-03-21 18:02:26'),
(76, 25, 2, '2345678901234', 'Pepsi 500ml', 2, 35.00, 70.00, '2026-03-21 18:02:26'),
(77, 26, 6, '6789012345678', 'Eggs (Dozen)', 2, 120.00, 240.00, '2026-03-22 09:49:14'),
(78, 26, 16, NULL, 'Gildan® Budget Unisex T-shirt', 2, 200.00, 400.00, '2026-03-22 09:49:14'),
(79, 26, 5, '5678901234567', 'Milk Packet 1L', 1, 60.00, 60.00, '2026-03-22 09:49:14'),
(80, 26, 9, '9012345678901', 'Cooking Oil 1L', 1, 120.00, 120.00, '2026-03-22 09:49:14'),
(81, 27, 1, '1234567890123', 'Coca Cola 500ml', 1, 35.00, 35.00, '2026-03-22 19:36:30'),
(82, 27, 16, NULL, 'Gildan® Budget Unisex T-shirt', 1, 200.00, 200.00, '2026-03-22 19:36:30'),
(83, 27, 6, '6789012345678', 'Eggs (Dozen)', 1, 120.00, 120.00, '2026-03-22 19:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `stock_logs`
--

CREATE TABLE `stock_logs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `previous_quantity` int(11) NOT NULL,
  `new_quantity` int(11) NOT NULL,
  `change_type` enum('add','remove','adjust','sale') NOT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_logs`
--

INSERT INTO `stock_logs` (`id`, `product_id`, `previous_quantity`, `new_quantity`, `change_type`, `reference_type`, `reference_id`, `notes`, `user_id`, `created_at`) VALUES
(1, 4, 47, 46, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:34'),
(2, 9, 45, 44, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:34'),
(3, 8, 100, 99, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:34'),
(4, 10, 80, 79, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:34'),
(5, 4, 46, 45, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:43'),
(6, 9, 44, 43, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:43'),
(7, 8, 99, 98, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:43'),
(8, 10, 79, 78, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:43'),
(9, 4, 45, 44, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:47'),
(10, 9, 43, 42, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:47'),
(11, 8, 98, 97, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:47'),
(12, 10, 78, 77, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:47'),
(13, 4, 44, 43, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:55'),
(14, 9, 42, 41, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:55'),
(15, 8, 97, 96, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:55'),
(16, 10, 77, 76, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:21:55'),
(17, 4, 43, 42, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:26:42'),
(18, 9, 41, 40, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:26:42'),
(19, 8, 96, 95, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:26:42'),
(20, 5, 80, 79, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:44:36'),
(21, 9, 40, 39, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:44:36'),
(22, 10, 76, 75, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:44:36'),
(23, 5, 79, 78, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:45:14'),
(24, 6, 40, 39, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:45:14'),
(25, 8, 95, 94, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-17 20:45:14'),
(26, 15, 0, 30, 'add', NULL, NULL, 'Initial stock on product creation', 1, '2026-03-18 20:26:19'),
(27, 9, 39, 37, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 00:49:25'),
(28, 8, 94, 93, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 00:49:25'),
(29, 2, 100, 99, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 01:09:22'),
(30, 8, 93, 92, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 01:09:22'),
(31, 3, 200, 199, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 01:09:22'),
(32, 4, 42, 41, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 04:54:34'),
(33, 4, 41, 39, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 04:57:28'),
(34, 4, 39, 38, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 05:01:34'),
(35, 1, 100, 99, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 05:01:34'),
(36, 16, 0, 50, 'add', NULL, NULL, 'Initial stock on product creation', 1, '2026-03-19 05:43:32'),
(37, 4, 38, 37, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 21:12:25'),
(38, 1, 99, 97, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 21:12:25'),
(39, 6, 39, 38, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 21:12:25'),
(40, 9, 37, 36, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 21:12:25'),
(41, 15, 30, 29, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 21:12:25'),
(42, 11, 50, 49, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-19 21:12:25'),
(43, 6, 38, 36, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:23:45'),
(44, 4, 37, 36, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:23:45'),
(45, 16, 50, 49, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:23:45'),
(46, 16, 49, 47, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:26:23'),
(47, 1, 97, 96, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:26:23'),
(48, 1, 96, 94, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:28:33'),
(49, 16, 47, 45, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:28:33'),
(50, 1, 94, 93, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:31:13'),
(51, 4, 36, 35, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:31:13'),
(52, 9, 36, 35, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:31:13'),
(53, 16, 45, 44, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:32:44'),
(54, 6, 36, 35, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:32:44'),
(55, 5, 78, 77, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:32:44'),
(56, 9, 35, 34, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:32:44'),
(57, 4, 35, 34, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:38:20'),
(58, 1, 93, 91, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:38:20'),
(59, 16, 44, 42, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:38:20'),
(60, 4, 34, 33, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:42:17'),
(61, 9, 34, 33, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:42:17'),
(62, 6, 35, 33, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:42:17'),
(63, 16, 42, 39, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:42:17'),
(64, 5, 77, 76, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:42:17'),
(65, 4, 33, 32, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:48:21'),
(66, 1, 91, 90, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:48:21'),
(67, 9, 33, 31, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:48:21'),
(68, 16, 39, 36, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:48:21'),
(69, 5, 76, 75, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:48:21'),
(70, 6, 33, 31, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 17:48:21'),
(71, 1, 90, 89, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 18:02:26'),
(72, 6, 31, 30, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 18:02:26'),
(73, 16, 36, 32, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 18:02:26'),
(74, 9, 31, 30, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 18:02:26'),
(75, 2, 99, 97, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-21 18:02:26'),
(76, 6, 30, 28, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-22 09:49:14'),
(77, 16, 32, 30, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-22 09:49:14'),
(78, 5, 75, 74, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-22 09:49:14'),
(79, 9, 30, 29, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-22 09:49:14'),
(80, 1, 89, 88, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-22 19:36:30'),
(81, 16, 30, 29, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-22 19:36:30'),
(82, 6, 28, 27, 'remove', NULL, NULL, 'Stock remove via sale', 1, '2026-03-22 19:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `category_unit_mapping`
--

CREATE TABLE `category_unit_mapping` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_unit_mapping`
--

INSERT INTO `category_unit_mapping` (`id`, `category`, `unit_name`, `created_at`, `updated_at`) VALUES
(1, 'Beverages', 'bottle', '2026-03-19 05:37:32', '2026-03-19 05:37:32'),
(2, 'Dairy', 'packet', '2026-03-19 05:37:32', '2026-03-19 05:37:32'),
(3, 'Groceries', 'kg', '2026-03-19 05:37:32', '2026-03-19 05:37:32'),
(4, 'Bakery', 'piece', '2026-03-19 05:37:32', '2026-03-19 05:37:32'),
(5, 'T-Shert', 'piece', '2026-03-19 05:37:32', '2026-03-19 05:37:32'),
(6, 'White T-Shert', 'piece', '2026-03-19 05:37:32', '2026-03-19 05:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `summary_cards`
--

CREATE TABLE `summary_cards` (
  `id` int(11) NOT NULL,
  `card_key` varchar(50) NOT NULL,
  `card_title` varchar(100) NOT NULL,
  `card_type` enum('users','products','customers','low_stock','custom') DEFAULT 'custom',
  `icon_class` varchar(50) DEFAULT 'bi bi-collection',
  `color_class` varchar(50) DEFAULT 'blue',
  `custom_query` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` enum('yes','no') DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `summary_cards`
--

INSERT INTO `summary_cards` (`id`, `card_key`, `card_title`, `card_type`, `icon_class`, `color_class`, `custom_query`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'total_users', 'Total Users', 'users', 'bi bi-people', 'purple', NULL, 1, 'yes', '2026-03-19 06:44:25', '2026-03-19 06:44:25'),
(2, 'total_products', 'Total Products', 'products', 'bi bi-box-seam', 'green', NULL, 2, 'yes', '2026-03-19 06:44:25', '2026-03-19 06:44:25'),
(3, 'total_customers', 'Total Customers', 'customers', 'bi bi-person-hearts', 'orange', NULL, 3, 'yes', '2026-03-19 06:44:25', '2026-03-19 06:44:25'),
(4, 'low_stock', 'Low Stock Items', 'low_stock', 'bi bi-exclamation-triangle', 'red', NULL, 4, 'yes', '2026-03-19 06:44:25', '2026-03-19 06:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'cash',
  `transaction_id` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `size_types`
--

CREATE TABLE `size_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size_type_name` varchar(100) NOT NULL,
  `size_type_options` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `size_type_name` (`size_type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_name`, `created_at`) VALUES
(1, 'S', '2026-03-19 05:37:32'),
(2, 'M', '2026-03-19 05:37:32'),
(3, 'L', '2026-03-19 05:37:32'),
(4, 'XL', '2026-03-19 05:37:32'),
(5, 'XXL', '2026-03-19 05:37:32'),
(6, 'OVER', '2026-03-19 05:37:32'),
(7, 'SMALL', '2026-03-19 05:37:32'),
(8, 'MEDIUM', '2026-03-19 05:37:32'),
(9, 'LARGE', '2026-03-19 05:37:32'),
(10, 'PIECE', '2026-03-19 05:37:32'),
(11, 'KG', '2026-03-19 05:37:32'),
(12, 'GRAM', '2026-03-19 05:37:32'),
(13, 'LITER', '2026-03-19 05:37:32'),
(14, 'ML', '2026-03-19 05:37:32'),
(15, 'BOX', '2026-03-19 05:37:32'),
(16, 'PACKET', '2026-03-19 05:37:32'),
(17, 'DOZEN', '2026-03-19 05:37:32'),
(18, 'BOTTLE', '2026-03-19 05:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','cashier') DEFAULT 'cashier',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$JnHU.yFROp.BrQr8Q4mb/ue7V/OJT9BWa0FOBJQY9lxtHfzMyLaIG', 'Md Rifat Hossian', 'mdrifathossainpersonal@gmail.com', 'admin', 'active', '2026-03-17 19:24:20', '2026-03-22 19:35:35'),
(2, 'cashier', '$2y$10$rFZkc7odvzKQm0sN93pPOOuRziMXURlhOmW9m0joedl00dJ7YLztu', 'Cashier', 'cashier@jolchobi.com', 'cashier', 'active', '2026-03-17 19:24:20', '2026-03-19 03:29:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `summary_cards`
--
ALTER TABLE `summary_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_key` (`card_key`);

--
-- Indexes for table `category_unit_mapping`
--
ALTER TABLE `category_unit_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unit_name` (`unit_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `summary_cards`
--
ALTER TABLE `summary_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category_unit_mapping`
--
ALTER TABLE `category_unit_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD CONSTRAINT `stock_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
