-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-03-24 21:00:38
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `fearofgod`
--

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(4, 'Accessori'),
(3, 'Bambini'),
(2, 'Donna'),
(1, 'Uomo');

-- --------------------------------------------------------

--
-- 表的结构 `itemorder`
--

CREATE TABLE `itemorder` (
  `item_order_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_time_of_purchase` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 转存表中的数据 `itemorder`
--

INSERT INTO `itemorder` (`item_order_id`, `order_id`, `product_id`, `quantity`, `price_at_time_of_purchase`, `created_at`) VALUES
(1, 1, NULL, 1, 19.99, '2025-03-22 08:18:59'),
(2, 1, NULL, 1, 29.99, '2025-03-22 08:18:59'),
(3, 2, NULL, 1, 49.99, '2025-03-22 08:18:59'),
(4, 3, NULL, 1, 29.99, '2025-03-22 08:18:59'),
(5, 3, NULL, 1, 24.99, '2025-03-22 08:18:59');

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `billing_address` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `shipping_address`, `billing_address`, `payment_method`, `created_at`) VALUES
(1, 2, '2025-03-22 08:18:50', 69.98, 'Spedito', 'Via Roma 123, Roma, RM, 00100', 'Via Roma 123, Roma, RM, 00100', 'Carta di Credito', '2025-03-22 08:18:50'),
(2, 3, '2025-03-22 08:18:50', 49.99, 'In elaborazione', 'Via Milano 456, Milano, MI, 20100', 'Via Milano 456, Milano, MI, 20100', 'PayPal', '2025-03-22 08:18:50'),
(3, 4, '2025-03-22 08:18:50', 44.98, 'Consegnato', 'Via Napoli 789, Napoli, NA, 80100', 'Via Napoli 789, Napoli, NA, 80100', 'Carta di Credito', '2025-03-22 08:18:50');

-- --------------------------------------------------------

--
-- 表的结构 `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 转存表中的数据 `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `image_url`) VALUES
(6, 'Essentials Hoodie White', 'Classic FOG Essential hoodie', 120.00, 150, 'img/FearOfGod_Essentials_Hoodie_White.webp'),
(7, 'Essentials Hoodie Black', 'Classic FOG Essential hoodie', 120.00, 75, 'img/FearOfGod_Essentials_Hoodie_Black.webp'),
(8, 'Essentials T-Shirt White', 'Classic FOG Essential T-Shirt', 90.00, 50, 'img/FearOfGod_Essentials_T-Shirt_White.webp'),
(9, 'Essentials T-Shirt Black', 'Classic FOG Essential T-Shirt', 90.00, 85, 'img/FearOfGod_Essentials_T-Shirt_Black.webp');

-- --------------------------------------------------------

--
-- 表的结构 `productcategory`
--

CREATE TABLE `productcategory` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `pwd`, `role`, `created_at`) VALUES
(1, 'admin_user', 'admin@example.com', 'adminpassword', 'admin', '2025-03-22 08:16:03'),
(2, 'mario_rossi', 'mario.rossi@example.com', 'password123', 'user', '2025-03-22 08:16:03'),
(3, 'laura_bianchi', 'laura.bianchi@example.com', 'password456', 'user', '2025-03-22 08:16:03'),
(4, 'giuseppe_verdi', 'giuseppe.verdi@example.com', 'password789', 'user', '2025-03-22 08:16:03'),
(5, 'anna_esposito', 'anna.esposito@example.com', 'password101', 'user', '2025-03-22 08:16:03'),
(6, 'luca_ferrari', 'luca.ferrari@example.com', 'password202', 'user', '2025-03-22 08:16:03'),
(7, 'sara_russo', 'sara.russo@example.com', 'password303', 'user', '2025-03-22 08:16:03'),
(8, 'marco_gallo', 'marco.gallo@example.com', 'password404', 'user', '2025-03-22 08:16:03'),
(9, 'elena_conti', 'elena.conti@example.com', 'password505', 'user', '2025-03-22 08:16:03'),
(10, 'francesco_marini', 'francesco.marini@example.com', 'password606', 'user', '2025-03-22 08:16:03'),
(11, 'nigger', 'nigger@nigga.com', 'ImANigga', 'user', '2025-03-24 19:50:39');

--
-- 转储表的索引
--

--
-- 表的索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- 表的索引 `itemorder`
--
ALTER TABLE `itemorder`
  ADD PRIMARY KEY (`item_order_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 表的索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- 表的索引 `productcategory`
--
ALTER TABLE `productcategory`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- 表的索引 `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `itemorder`
--
ALTER TABLE `itemorder`
  MODIFY `item_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 限制导出的表
--

--
-- 限制表 `itemorder`
--
ALTER TABLE `itemorder`
  ADD CONSTRAINT `itemorder_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itemorder_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL;

--
-- 限制表 `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- 限制表 `productcategory`
--
ALTER TABLE `productcategory`
  ADD CONSTRAINT `productcategory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productcategory_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- 限制表 `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
