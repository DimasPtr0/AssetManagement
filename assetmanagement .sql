-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 27, 2024 at 07:28 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assetmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `warranty_end_date` date DEFAULT NULL,
  `status` enum('active','maintenance','decommissioned') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `category_id`, `name`, `serial_number`, `purchase_date`, `warranty_end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Asus', '16542165541', NULL, NULL, 'active', '2024-07-26 03:59:02', '2024-07-26 03:59:02'),
(2, 2, 'Lenovo', '4165416484', NULL, NULL, 'maintenance', '2024-07-26 04:19:29', '2024-07-26 04:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Laptop', 'bahisdbiaoihd', '2024-07-26 03:58:36', '2024-07-26 03:58:36'),
(2, 'Laptop2', 'asdjnoasdn', '2024-07-26 04:19:01', '2024-07-26 04:19:01');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_details`
--

CREATE TABLE `inventory_details` (
  `asset_id` int NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `mac_address` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_details`
--

INSERT INTO `inventory_details` (`asset_id`, `ip_address`, `mac_address`, `location`, `notes`) VALUES
(1, '10.10.1.121', 'hj12b3hb13', 'smg', 'jasodnoasdn'),
(2, '10.10.1.128', 'jh2b1b313np1i', 'kendal', 'aosdboaihd');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','karyawan','user') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin1', '$2y$10$0l/xlG90vce8FYhmoDKFBu.EnCit7TSGmc/C0FR1AfrWMEifZiPui', 'admin', '2024-07-24 03:58:47', '2024-07-24 03:58:47'),
(2, 'karyawan1', '$2y$10$CXc9ultqqgd8efKKviBU2ewzf3D30iL1gGsE2/8X18BuB3PvzSDYC', 'karyawan', '2024-07-24 04:19:01', '2024-07-24 04:19:01'),
(3, 'guest1', '$2y$10$boLWopghbr2qpWkt1WDrWuLCaBY.Y.XRew.JHPcLby87QzZFn//pO', 'user', '2024-07-25 06:04:12', '2024-07-25 06:04:12'),
(4, 'user1', '$2y$10$26.14.p4YrlSTrJLZ2kDHeQtkzrZKK9YDTEhxyeg5IF9ycccpig0O', 'user', '2024-07-26 03:40:51', '2024-07-26 03:40:51'),
(5, 'admin2', '$2y$10$hRqgtECTU0mWapI7jxSFbOL.YN4SB6i7X5i0KndNsac3RgXSHAqGe', 'admin', '2024-07-26 03:41:56', '2024-07-26 03:41:56'),
(7, 'admin3', '$2y$10$H89y0tCBOPj1R6l9JXlBTOFMpoZWs5rVECF9qL3IVoQlYi30PW2UO', 'admin', '2024-07-26 03:43:13', '2024-07-26 03:43:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_details`
--
ALTER TABLE `inventory_details`
  ADD PRIMARY KEY (`asset_id`);

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
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_details`
--
ALTER TABLE `inventory_details`
  ADD CONSTRAINT `inventory_details_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
