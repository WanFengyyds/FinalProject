-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 22, 2025 alle 09:19
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
(4, 'Accessori'),
(3, 'Bambini'),
(2, 'Donna'),
(1, 'Uomo');

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
(1, 1, 1, 1, 19.99, '2025-03-22 08:18:59'),
(2, 1, 4, 1, 29.99, '2025-03-22 08:18:59'),
(3, 2, 2, 1, 49.99, '2025-03-22 08:18:59'),
(4, 3, 3, 1, 29.99, '2025-03-22 08:18:59'),
(5, 3, 5, 1, 24.99, '2025-03-22 08:18:59');

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
(3, 4, '2025-03-22 08:18:50', 44.98, 'Consegnato', 'Via Napoli 789, Napoli, NA, 80100', 'Via Napoli 789, Napoli, NA, 80100', 'Carta di Credito', '2025-03-22 08:18:50');

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
(1, 'Maglietta Uomo', 'Maglietta in cotone per uomo', 19.99, 100, 'https://example.com/maglietta-uomo.jpg'),
(2, 'Jeans Donna', 'Jeans skinny per donna', 49.99, 75, 'https://example.com/jeans-donna.jpg'),
(3, 'Felpa Bambini', 'Felpa con cappuccio per bambini', 29.99, 50, 'https://example.com/felpa-bambini.jpg'),
(4, 'Cintura Uomo', 'Cintura in pelle per uomo', 29.99, 85, 'https://example.com/cintura-uomo.jpg'),
(5, 'Occhiali da Sole', 'Occhiali da sole unisex', 24.99, 110, 'https://example.com/occhiali-sole.jpg');

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
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(4, 4),
(5, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `pwd`, `first_name`, `last_name`, `address`, `city`, `state`, `zip_code`, `country`, `phone_number`, `role`, `created_at`) VALUES
(1, 'admin_user', 'admin@example.com', 'adminpassword', 'Admin', 'User', 'Via Admin 1', 'Roma', 'RM', '00100', 'Italia', '3331111111', 'admin', '2025-03-22 08:16:03'),
(2, 'mario_rossi', 'mario.rossi@example.com', 'password123', 'Mario', 'Rossi', 'Via Roma 123', 'Roma', 'RM', '00100', 'Italia', '3331234567', 'user', '2025-03-22 08:16:03'),
(3, 'laura_bianchi', 'laura.bianchi@example.com', 'password456', 'Laura', 'Bianchi', 'Via Milano 456', 'Milano', 'MI', '20100', 'Italia', '3337654321', 'user', '2025-03-22 08:16:03'),
(4, 'giuseppe_verdi', 'giuseppe.verdi@example.com', 'password789', 'Giuseppe', 'Verdi', 'Via Napoli 789', 'Napoli', 'NA', '80100', 'Italia', '3339876543', 'user', '2025-03-22 08:16:03'),
(5, 'anna_esposito', 'anna.esposito@example.com', 'password101', 'Anna', 'Esposito', 'Via Firenze 101', 'Firenze', 'FI', '50100', 'Italia', '3332222222', 'user', '2025-03-22 08:16:03'),
(6, 'luca_ferrari', 'luca.ferrari@example.com', 'password202', 'Luca', 'Ferrari', 'Via Torino 202', 'Torino', 'TO', '10100', 'Italia', '3333333333', 'user', '2025-03-22 08:16:03'),
(7, 'sara_russo', 'sara.russo@example.com', 'password303', 'Sara', 'Russo', 'Via Palermo 303', 'Palermo', 'PA', '90100', 'Italia', '3334444444', 'user', '2025-03-22 08:16:03'),
(8, 'marco_gallo', 'marco.gallo@example.com', 'password404', 'Marco', 'Gallo', 'Via Genova 404', 'Genova', 'GE', '16100', 'Italia', '3335555555', 'user', '2025-03-22 08:16:03'),
(9, 'elena_conti', 'elena.conti@example.com', 'password505', 'Elena', 'Conti', 'Via Bologna 505', 'Bologna', 'BO', '40100', 'Italia', '3336666666', 'user', '2025-03-22 08:16:03'),
(10, 'francesco_marini', 'francesco.marini@example.com', 'password606', 'Francesco', 'Marini', 'Via Venezia 606', 'Venezia', 'VE', '30100', 'Italia', '3337777777', 'user', '2025-03-22 08:16:03');

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `itemorder`
--
ALTER TABLE `itemorder`
  MODIFY `item_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
