-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 04:46 PM
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
-- Database: `linenese`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` int(15) NOT NULL,
  `password` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'amine', 'aminemokhtari028@gmail.com', 551239279, '123', '2024-09-25 18:51:54'),
(2, 'Mohamed Mokhtari', 'hello028@gmail.com', 551239279, '123', '2024-09-26 18:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` mediumtext NOT NULL,
  `image` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `created_at`) VALUES
(30, 'vistat', ' vistat', 'category-1.jpg', '2024-09-23 15:16:20'),
(31, 'des bonnie', ' des bonnie chabin bzaf', 'category-4.jpg', '2024-09-23 15:16:37'),
(32, 'basket', ' des basket', 'category-5.jpg', '2024-09-23 15:16:55'),
(33, 'srawl', ' srawl', 'category-7.jpg', '2024-09-23 15:17:13'),
(34, 'triko', ' trikouat', 'product-7-2.jpg', '2024-09-24 13:50:07'),
(40, 'blayr', ' fwqfaw', 'category-3.jpg', '2024-10-03 14:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `id` int(11) NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone_number` int(191) NOT NULL,
  `message` varchar(266) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inbox`
--

INSERT INTO `inbox` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `message`, `created_at`) VALUES
(44, 'Mohamed', 'Mokhtari', 'hello028@gmail.com', 551239279, 'sgsv', '2024-10-03 14:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `brand` varchar(191) NOT NULL,
  `description` mediumtext NOT NULL,
  `original_price` int(11) NOT NULL,
  `selling_price` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `images` text NOT NULL,
  `qty` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `popular` tinyint(4) NOT NULL,
  `color` varchar(191) NOT NULL,
  `size` varchar(191) NOT NULL,
  `width` int(191) NOT NULL,
  `height` int(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `description`, `original_price`, `selling_price`, `category_id`, `images`, `qty`, `status`, `popular`, `color`, `size`, `width`, `height`, `created_at`) VALUES
(39, 'triko', 'adidas', 'trikhouat', 12, 11, 30, 'product-7-1.jpg,product-7-2.jpg', 1, 0, 1, 'green,bland,green', '12', 1, 1, '2024-09-23 21:42:45'),
(40, 'vista 003', 'adidas', 'yeyeyeyeyey ', 20, 11, 30, 'product-8-1.jpg,product-8-2.jpg', 1, 0, 1, 'vista 003', '12', 1, 1, '2024-09-25 14:13:44'),
(41, 'vista 002', 'adidas', 'selling ', 230, 210, 30, 'product-6-1.jpg,product-6-2.jpg', 10, 0, 1, 'vista 002', '14', 10, 15, '2024-09-24 13:58:00'),
(42, 'afaw', 'fas', '1', 1, 1, 0, 'showcase-img-7.jpg,showcase-img-8.jpg', 1, 0, 1, '1', '1', 1, 1, '2024-10-02 15:08:37'),
(43, 'trikoo', 'adidas', '1 ', 1, 1, 34, 'avatar-1.jpg,avatar-2.jpg,avatar-3.jpg', 1, 0, 1, 'triko', '1', 1, 1, '2024-10-03 14:08:44'),
(45, 'triko2', 'adidas', 'sfsz', 1, 1, 30, 'product-1-1.jpg,product-1-2.jpg', 1, 0, 1, '1', '1', 1, 1, '2024-10-04 18:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(19) NOT NULL,
  `place` varchar(191) NOT NULL,
  `price` int(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`id`, `place`, `price`) VALUES
(17, 'oran', 1200),
(18, 'Algeria', 900);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(8, 'amine', 'aminemokhtari028@gmail.com', '$2y$10$ZprtnJHmyIjbl21RYr8cG.z7FyFt9e.S7yM5LGsXqkrUbYoJbcTvm', '2024-09-25 15:26:06'),
(9, 'omar', 'hello028@gmail.com', '$2y$10$neTzsuHGuGyigQ3Q.HwbU.xk35MQrPwaENg7P61agE5d1gcNfOzQe', '2024-09-25 15:33:28'),
(10, 'hfwi', 'a@gmail.com', '$2y$10$D3mD4FMNB6XNeUlHIFyLcuUYr2CX3SMuY.h4lJ2/GGA/63LgyXQhq', '2024-10-03 19:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`, `image`, `selling_price`, `qty`) VALUES
(11, 8, 40, '2024-10-04 18:31:58', '../uploads/images/product/product-8-1.jpg', 11.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbox`
--
ALTER TABLE `inbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `inbox`
--
ALTER TABLE `inbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
