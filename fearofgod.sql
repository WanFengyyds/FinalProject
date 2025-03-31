-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 31, 2025 alle 13:12
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fearofgod`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dump dei dati per la tabella `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(5, 'Accessories'),
(1, 'Hoodies'),
(3, 'Pants'),
(4, 'Shorts'),
(2, 'T-Shirts');

-- --------------------------------------------------------

--
-- Struttura della tabella `itemorder`
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
-- Dump dei dati per la tabella `itemorder`
--

INSERT INTO `itemorder` (`item_order_id`, `order_id`, `product_id`, `quantity`, `price_at_time_of_purchase`, `created_at`) VALUES
(1, 1, 6, 1, 19.99, '2025-03-22 08:18:59'),
(2, 1, 7, 1, 29.99, '2025-03-22 08:18:59'),
(3, 2, 10, 1, 49.99, '2025-03-22 08:18:59'),
(4, 3, 8, 1, 29.99, '2025-03-22 08:18:59'),
(5, 3, NULL, 1, 24.99, '2025-03-22 08:18:59'),
(6, 4, NULL, 1, 140.00, '2025-03-23 09:15:22'),
(7, 4, 12, 1, 110.00, '2025-03-23 09:15:22'),
(8, 4, NULL, 1, 45.00, '2025-03-23 09:15:22'),
(9, 5, 9, 2, 90.00, '2025-03-25 13:30:45');

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
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
-- Dump dei dati per la tabella `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `shipping_address`, `billing_address`, `payment_method`, `created_at`) VALUES
(1, 2, '2025-03-22 08:18:50', 69.98, 'Spedito', 'Via Roma 123, Roma, RM, 00100', 'Via Roma 123, Roma, RM, 00100', 'Carta di Credito', '2025-03-22 08:18:50'),
(2, 3, '2025-03-22 08:18:50', 49.99, 'In elaborazione', 'Via Milano 456, Milano, MI, 20100', 'Via Milano 456, Milano, MI, 20100', 'PayPal', '2025-03-22 08:18:50'),
(3, 4, '2025-03-22 08:18:50', 44.98, 'Consegnato', 'Via Napoli 789, Napoli, NA, 80100', 'Via Napoli 789, Napoli, NA, 80100', 'Carta di Credito', '2025-03-22 08:18:50'),
(4, 5, '2025-03-23 09:15:22', 275.00, 'In elaborazione', 'Piazza Garibaldi 10, Torino, TO, 10100', 'Piazza Garibaldi 10, Torino, TO, 10100', 'PayPal', '2025-03-23 09:15:22'),
(5, 6, '2025-03-25 13:30:45', 180.00, 'Spedito', 'Corso Vittorio Emanuele 20, Firenze, FI, 50100', 'Corso Vittorio Emanuele 20, Firenze, FI, 50100', 'Carta di Credito', '2025-03-25 13:30:45');

-- --------------------------------------------------------

--
-- Struttura della tabella `product`
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
-- Dump dei dati per la tabella `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `image_url`) VALUES
(6, 'Essentials Hoodie White', 'Classic FOG Essential hoodie', 120.00, 150, 'img/FearOfGod_Essentials_Hoodie_White.webp'),
(7, 'Essentials Hoodie Black', 'Classic FOG Essential hoodie', 120.00, 75, 'img/FearOfGod_Essentials_Hoodie_Black.webp'),
(8, 'Essentials T-Shirt White', 'Classic FOG Essential T-Shirt', 90.00, 50, 'img/FearOfGod_Essentials_T-Shirt_White.webp'),
(9, 'Essentials T-Shirt Black', 'Classic FOG Essential T-Shirt', 90.00, 85, 'img/FearOfGod_Essentials_T-Shirt_Black.webp'),
(10, 'Essentials Sweatpants Black', 'Classic FOG Essential sweatpants in black', 140.00, 60, 'img/FearOfGod_Essentials_Sweatpants_Black.webp'),
(12, 'Essentials Shorts Black', 'FOG Essential shorts in black', 110.00, 70, 'img/FearOfGod_Essentials_Shorts_Black.webp'),
(15, 'Essentials Hoodie Oatmeal', 'Classic FOG Essential hoodie in oatmeal color', 120.00, 80, 'img/FearOfGod_Essentials_Hoodie_Oatmeal.webp'),
(18, 'Essentials Hoodie Navy', 'Classic FOG Essential hoodie in navy blue', 120.00, 55, 'img/FearOfGod_Essentials_Hoodie_Navy.webp'),
(22, 'Essentials Hoodie Pink', 'Classic FOG Essential hoodie in pink', 120.00, 35, 'img/FearOfGod_Essentials_Hoodie_Pink.webp'),
(25, 'Essentials Hoodie Heather Brown', 'Classic FOG Essential hoodie in heather brown', 120.00, 45, 'img/FearOfGod_Essentials_Hoodie_HeatherBrown.webp'),
(30, 'Essentials Hoodie Ice Blue', 'Classic FOG Essential hoodie in ice blue', 120.00, 45, 'img/FearOfGod_Essentials_Hoodie_IceBlue.webp'),
(35, 'Essentials Hoodie Vintage Black', 'Classic FOG Essential hoodie in vintage black wash', 130.00, 30, 'img/FearOfGod_Essentials_Hoodie_VintageBlack.webp'),
(40, 'Essentials T-Shirt Navy', 'Classic FOG Essential T-Shirt in navy blue', 90.00, 60, 'img/FearOfGod_Essentials_TShirt_Navy.webp'),
(45, 'Essentials T-Shirt Cream', 'Classic FOG Essential T-Shirt in cream', 90.00, 65, 'img/FearOfGod_Essentials_TShirt_Cream.webp'),
(50, 'Essentials T-Shirt Lavender', 'Classic FOG Essential T-Shirt in lavender', 90.00, 40, 'img/FearOfGod_Essentials_TShirt_Lavender.webp'),
(60, 'Essentials Sweatpants Dark Grey', 'Classic FOG Essential sweatpants in dark grey', 140.00, 55, 'img/FearOfGod_Essentials_Sweatpants_DarkGrey.webp'),
(75, 'Essentials Sweatpants Brick', 'Classic FOG Essential sweatpants in brick red', 140.00, 35, 'img/FearOfGod_Essentials_Sweatpants_Brick.webp'),
(85, 'Essentials Shorts Olive', 'FOG Essential shorts in olive green', 110.00, 45, 'img/FearOfGod_Essentials_Shorts_Olive.webp'),
(103, 'Essentials Socks Black 3-Pack', 'Pack of 3 FOG Essential socks in black', 35.00, 100, 'img/FearOfGod_Essentials_Socks_Black.webp'),
(120, 'Essentials Backpack Black', 'FOG Essential backpack in black', 150.00, 30, 'img/FearOfGod_Essentials_Backpack_Black.webp');

-- --------------------------------------------------------

--
-- Struttura della tabella `productcategory`
--

CREATE TABLE `productcategory` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dump dei dati per la tabella `productcategory`
--

INSERT INTO `productcategory` (`product_id`, `category_id`) VALUES
(6, 1),
(7, 1),
(8, 2),
(9, 2),
(10, 3),
(12, 4),
(15, 1),
(18, 1),
(22, 1),
(25, 1),
(30, 1),
(35, 1),
(40, 2),
(45, 2),
(50, 2),
(60, 3),
(75, 3),
(85, 4),
(103, 5),
(120, 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `shipping_addresses`
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

--
-- Dump dei dati per la tabella `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`address_id`, `user_id`, `recipient_name`, `address`, `city`, `state`, `zip_code`, `country`, `phone_number`, `is_default`) VALUES
(1, 2, 'Mario Rossi', 'Via Roma 123', 'Roma', 'RM', '00100', 'Italy', '+393331234567', 1),
(2, 3, 'Laura Bianchi', 'Via Milano 456', 'Milano', 'MI', '20100', 'Italy', '+393332345678', 1),
(3, 4, 'Giuseppe Verdi', 'Via Napoli 789', 'Napoli', 'NA', '80100', 'Italy', '+393333456789', 1),
(4, 5, 'Anna Esposito', 'Piazza Garibaldi 10', 'Torino', 'TO', '10100', 'Italy', '+393334567890', 1),
(5, 6, 'Luca Ferrari', 'Corso Vittorio Emanuele 20', 'Firenze', 'FI', '50100', 'Italy', '+393335678901', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
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
-- Dump dei dati per la tabella `users`
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
(11, 'nigger', 'nigger@nigga.com', 'ImANigga', 'user', '2025-03-24 19:50:39'),
(12, 'admin', 'admin@fog.com', 'admin', 'admin', '2025-03-31 08:24:12');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indici per le tabelle `itemorder`
--
ALTER TABLE `itemorder`
  ADD PRIMARY KEY (`item_order_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indici per le tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indici per le tabelle `productcategory`
--
ALTER TABLE `productcategory`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indici per le tabelle `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `itemorder`
--
ALTER TABLE `itemorder`
  MODIFY `item_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT per la tabella `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `itemorder`
--
ALTER TABLE `itemorder`
  ADD CONSTRAINT `itemorder_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `itemorder_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `productcategory`
--
ALTER TABLE `productcategory`
  ADD CONSTRAINT `productcategory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productcategory_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
