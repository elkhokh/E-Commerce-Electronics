-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 11:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_commerce_electronics`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `user_id`, `title`, `content`, `image`, `created_at`) VALUES
(1, 1, 'Latest Tech Trends 2024', 'Exploring the newest technology trends...', 'Public\\assets\\front\\img\\blog\\Screenshot-2024.jpg', '2025-05-30 23:49:15'),
(2, 1, 'Smartphone Comparison', 'Comparing the latest smartphones...', 'Public\\assets\\front\\img\\blog\\Smartphone Comparison.jpg', '2025-05-30 23:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_comments`
--

INSERT INTO `blog_comments` (`id`, `blog_id`, `user_id`, `comment`, `created_at`) VALUES
(8, 1, 5, 'very good', '2025-06-10 09:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(21, 1, 1, 3, '2025-05-30 07:36:01'),
(30, 5, 39, 1, '2025-06-09 19:14:39');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `created_at`) VALUES
(2, 'phone', 1, '2025-06-06 21:45:23'),
(3, 'camera', 1, '2025-06-08 11:24:49'),
(4, 'Laptop', 1, '2025-06-09 17:27:37');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(7) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`, `created_at`) VALUES
(1, 'Red', '#FF0000', '2025-05-27 02:50:19'),
(2, 'Blue', '#0000FF', '2025-05-27 02:50:19'),
(3, 'Green', '#00FF00', '2025-05-27 02:50:19'),
(4, 'Black', '#000000', '2025-05-27 02:50:19'),
(5, 'White', '#FFFFFF', '2025-05-27 02:50:19'),
(6, 'Yellow', '#FFFF00', '2025-05-27 02:50:19'),
(7, 'Purple', '#800080', '2025-05-27 02:50:19'),
(8, 'Orange', '#FFA500', '2025-05-27 02:50:19'),
(9, 'Pink', '#FFC0CB', '2025-05-27 02:50:19'),
(10, 'Gray', '#808080', '2025-05-27 02:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `comment_replies`
--

CREATE TABLE `comment_replies` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_replies`
--

INSERT INTO `comment_replies` (`id`, `comment_id`, `user_id`, `reply`, `created_at`) VALUES
(15, 8, 5, 'test', '2025-06-10 09:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'osama', 'elgendyo240@gmail.com', 'test', '2025-06-02 23:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `value` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `code`, `value`, `status`, `created_at`) VALUES
(1, 'SUMMER2024', 20, 1, '2025-06-09 12:47:26'),
(2, 'WELCOME50', 50, 0, '2025-06-09 12:47:26'),
(6, '68470786B8', 55, 1, '2025-06-09 16:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `product_id`, `title`, `description`, `discount_percentage`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 1, 'iPhone 13 Special Offer', 'Get 15% off on iPhone 13 for a limited time', 15.00, '2025-05-30 02:37:40', '2026-03-01 00:00:00', 1, '2025-05-29 23:37:40'),
(3, 39, 'Camera Special Offer', 'Get 15% off on camera for a limited time', 15.00, '2025-06-07 15:31:40', '2026-03-31 23:59:00', 1, '2025-06-08 12:31:40');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `payment_method`, `created_at`, `phone`) VALUES
(2, 1, 2449.97, 'pending', 'test, test,<br>, test, Egypt, 3', 'PayPal', '2025-05-29 20:49:21', '012010'),
(3, 1, 2449.97, 'pending', 'test, test, test, Egypt, 3', 'PayPal', '2025-05-29 20:55:52', '012010'),
(4, 1, 999.99, 'cancelled', 'test, test, test, Egypt, 2', 'PayPal', '2025-05-29 21:39:21', '012010'),
(5, 1, 1449.98, 'processing', 'test, test, test, Egypt, 3', 'PayPal', '2025-05-29 22:46:35', '015632'),
(6, 1, 999.99, 'pending', 'test, test, test, Egypt, 3', 'PayPal', '2025-05-29 23:11:24', '589623'),
(8, 5, 1749.98, 'completed', 'test, test,<br>, test, Egypt, palestine', 'Direct bank transfer', '2025-06-08 10:50:13', '01202665670'),
(9, 5, 1749.98, 'cancelled', 'test, test,<br>, test, Egypt, palestine', 'Direct bank transfer', '2025-06-08 10:50:47', '01202665670'),
(10, 5, 850.00, 'pending', 'eldehara, streat 10,<br>, city, state, palestine', 'PayPal', '2025-06-08 12:51:38', '01202665670'),
(11, 5, 850.00, 'pending', 'eldehara, streat 10,<br>, city, state, palestine', 'PayPal', '2025-06-09 19:13:09', '01202665670');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(5, 5, 1, 3, 999.99, '2025-05-29 22:46:35'),
(6, 6, 1, 1, 999.99, '2025-05-29 23:11:24'),
(9, 9, 1, 2, 849.99, '2025-06-08 10:50:47'),
(10, 10, 39, 2, 400.00, '2025-06-08 12:51:38'),
(11, 11, 39, 2, 425.00, '2025-06-09 19:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `main_image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `main_image`, `status`, `created_at`, `category_id`, `subcategory_id`, `description`) VALUES
(1, 'iPhone 13', 999.99, 44, 'Public/assets/front/img/product/iPhone 13/6847271d84c0a.webp', 1, '2025-05-27 00:49:28', 2, 3, 'Latest Apple smartphone with advanced features'),
(39, 'Sony Digital Camera – Professional Imaging in Your Hands', 500.00, 46, 'Public/assets/front/img/product/Sony Digital Camera – Professional Imaging in Your Hands/6845ddf1080b0.jpg', 1, '2025-06-08 12:24:23', 3, 3, 'Capture every moment in stunning detail with the Sony Digital Camera, engineered for photographers, content creators, and videographers alike.\r\nWith a high-performance lens and advanced sensor technology, this camera delivers vivid colors, sharp contrast, and outstanding low-light performance.'),
(41, 'Samsung Galaxy S25 Ultra – The Pinnacle of Power and Precision', 1000.00, 70, 'Public/assets/front/img/product/Samsung Galaxy S25 Ultra – The Pinnacle of Power and Precision/684726c33a4cd.jpg', 1, '2025-06-09 16:29:17', 2, 7, 'Experience the future of mobile innovation with the Samsung Galaxy S25 Ultra. Featuring a sleek titanium body, a stunning 6.9-inch Dynamic AMOLED 2X display, and a powerful Snapdragon 8 Gen 4 processor, the S25 Ultra is designed for ultimate performance. Capture every detail with its groundbreaking 200MP quad camera system and enjoy all-day power with a massive 5500mAh battery and ultra-fast charging. With One UI 7 and advanced AI features, the Galaxy S25 Ultra redefines what a smartphone can do'),
(42, 'Ultra Performance Laptop – Power, Portability, and Precision Combined', 600.00, 20, 'Public/assets/front/img/product/Ultra Performance Laptop – Power, Portability, and Precision Combined/684728d03ba74.png', 1, '2025-06-09 18:32:48', 4, 8, 'Unlock next-level productivity with this high-performance laptop engineered for professionals and creators. Featuring a 14-inch QHD display with ultra-slim bezels, a blazing-fast Intel Core i9 processor (13th Gen), 32GB RAM, and a 1TB SSD, this device delivers lightning speed and seamless multitasking. The precision-engineered keyboard, long-lasting battery, and advanced cooling system make it ideal for both work and play. Whether you\'re editing 4K videos, running demanding software, or gaming on the go – this laptop handles it all with style and speed');

-- --------------------------------------------------------

--
-- Table structure for table `product_colors`
--

CREATE TABLE `product_colors` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_colors`
--

INSERT INTO `product_colors` (`id`, `product_id`, `color_id`, `created_at`) VALUES
(75, 39, 4, '2025-06-08 12:24:23'),
(76, 39, 10, '2025-06-08 12:24:23'),
(77, 39, 8, '2025-06-08 12:24:23'),
(94, 41, 4, '2025-06-09 18:24:03'),
(95, 41, 10, '2025-06-09 18:24:03'),
(96, 41, 5, '2025-06-09 18:24:03'),
(97, 1, 10, '2025-06-09 18:25:33'),
(98, 1, 1, '2025-06-09 18:25:33'),
(99, 42, 4, '2025-06-09 18:32:48'),
(100, 42, 5, '2025-06-09 18:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_name`, `image_path`, `created_at`) VALUES
(71, 39, 'WhatsApp Image 2025-06-08 at 15.06.05_bc635b3d.jpg', 'Public/assets/front/img/product/Sony Digital Camera – Professional Imaging in Your Hands/6845ddf0f1208.jpg', '2025-06-08 19:01:04'),
(72, 39, 'WhatsApp Image 2025-06-08 at 15.05.37_62c05684.jpg', 'Public/assets/front/img/product/Sony Digital Camera – Professional Imaging in Your Hands/6845ddf101156.jpg', '2025-06-08 19:01:05'),
(73, 39, 'WhatsApp Image 2025-06-08 at 15.05.13_3bdd0909.jpg', 'Public/assets/front/img/product/Sony Digital Camera – Professional Imaging in Your Hands/6845ddf1030f6.jpg', '2025-06-08 19:01:05'),
(74, 39, 'WhatsApp Image 2025-06-08 at 15.04.40_99ece086.jpg', 'Public/assets/front/img/product/Sony Digital Camera – Professional Imaging in Your Hands/6845ddf105a34.jpg', '2025-06-08 19:01:05'),
(76, 41, '4BkApRn9ShNCXWXEvPzJgK-650-80.jpg.webp', 'Public/assets/front/img/product/Samsung Galaxy S25 Ultra – The Pinnacle of Power and Precision/684726c32fd16.webp', '2025-06-09 18:24:03'),
(77, 41, 's25_ultra_1737571088741_1737571094005 (1).avif', 'Public/assets/front/img/product/Samsung Galaxy S25 Ultra – The Pinnacle of Power and Precision/684726c332bc5.avif', '2025-06-09 18:24:03'),
(78, 41, 'galaxy-s25-ultra-header-1.avif', 'Public/assets/front/img/product/Samsung Galaxy S25 Ultra – The Pinnacle of Power and Precision/684726c3343c3.avif', '2025-06-09 18:24:03'),
(79, 41, 'Galaxy-S24-Ultra-3.webp', 'Public/assets/front/img/product/Samsung Galaxy S25 Ultra – The Pinnacle of Power and Precision/684726c338651.webp', '2025-06-09 18:24:03'),
(80, 1, 'WhatsApp Image 2025-06-08 at 14.30.10_e08123ab.jpg', 'Public/assets/front/img/product/iPhone 13/6847271d7db94.jpg', '2025-06-09 18:25:33'),
(81, 1, 'WhatsApp Image 2025-06-08 at 14.29.16_c26729c1.jpg', 'Public/assets/front/img/product/iPhone 13/6847271d80c8c.jpg', '2025-06-09 18:25:33'),
(82, 1, 'iphone2.jpeg', 'Public/assets/front/img/product/iPhone 13/6847271d82bd2.jpeg', '2025-06-09 18:25:33'),
(83, 42, 'WhatsApp Image 2025-06-08 at 14.55.47_fcd5ce04.jpg', 'Public/assets/front/img/product/Ultra Performance Laptop – Power, Portability, and Precision Combined/684728d04164b.jpg', '2025-06-09 18:32:48'),
(84, 42, 'WhatsApp Image 2025-06-08 at 14.54.51_a1cd7960.jpg', 'Public/assets/front/img/product/Ultra Performance Laptop – Power, Portability, and Precision Combined/684728d04369b.jpg', '2025-06-09 18:32:48'),
(85, 42, 'WhatsApp Image 2025-06-08 at 14.50.06_c3777f6a.jpg', 'Public/assets/front/img/product/Ultra Performance Laptop – Power, Portability, and Precision Combined/684728d0455fc.jpg', '2025-06-09 18:32:48'),
(86, 42, 'WhatsApp Image 2025-06-08 at 14.44.31_1dcb11b0.jpg', 'Public/assets/front/img/product/Ultra Performance Laptop – Power, Portability, and Precision Combined/684728d047482.jpg', '2025-06-09 18:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(36, 5, 1, 4, 'very good', '2025-06-04 17:34:55'),
(39, 5, 39, 3, 'very good', '2025-06-08 11:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `description`, `created_at`) VALUES
(3, 2, 'Iphone', 'Portable computers', '2025-05-27 00:49:28'),
(5, 3, 'Sony', 'Camera', '2025-06-08 12:28:03'),
(7, 2, 'Samsung', NULL, '2025-06-09 16:32:03'),
(8, 4, 'HP', NULL, '2025-06-09 18:28:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'Public/assets/front/img/users/default_user.jpg',
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_image`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', 'Public/assets/front/img/users/default_user.jpg', '$2y$10$RTx7oMNb5w6jh5FLmgFHXOehIv2u8xgs8AFfZsjtDC7BBD4qtbb2W', 'admin', 1, '2025-05-27 02:29:47'),
(5, 'elgendy', 'elgendyo240@gmail.com', 'Public/assets/front/img/users/68428a671552c.png', '$2y$10$CU7JTKb9UNtEEbBrXk1Buu91MTp9rfZ5aozb2/iWwFpDhRmWdjNcS', 'user', 1, '2025-05-31 03:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 1, 1, '2025-05-30 18:11:35'),
(8, 5, 41, '2025-06-10 08:30:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_color` (`product_id`,`color_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_review` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comment_replies`
--
ALTER TABLE `comment_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `product_colors`
--
ALTER TABLE `product_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD CONSTRAINT `comment_replies_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_colors`
--
ALTER TABLE `product_colors`
  ADD CONSTRAINT `product_colors_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_colors_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

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
