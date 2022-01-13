-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2022 at 11:58 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `walangtatak`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `address_home` varchar(100) NOT NULL,
  `address_barangay` varchar(100) NOT NULL,
  `address_city` varchar(100) NOT NULL,
  `address_province` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `credential_id` int(11) NOT NULL,
  `credential_employee_id` int(11) NOT NULL,
  `credential_password` mediumtext NOT NULL,
  `credential_last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `credentials`
--

INSERT INTO `credentials` (`credential_id`, `credential_employee_id`, `credential_password`, `credential_last_login`) VALUES
(1, 1, '$2y$10$VzaQM0r3NSKGcWb4zD3gceL1eGBV9rk3hjAUWqK4pnTGgqQCTTMra', '2022-01-13 09:46:00'),
(2, 2, '$2y$10$VzaQM0r3NSKGcWb4zD3gceL1eGBV9rk3hjAUWqK4pnTGgqQCTTMra', '2022-01-08 04:33:24'),
(3, 3, '$2y$10$VzaQM0r3NSKGcWb4zD3gceL1eGBV9rk3hjAUWqK4pnTGgqQCTTMra', '2022-01-13 10:44:01'),
(4, 4, '$2y$10$VzaQM0r3NSKGcWb4zD3gceL1eGBV9rk3hjAUWqK4pnTGgqQCTTMra', '2022-01-08 04:55:39'),
(5, 6, '$2y$10$VzaQM0r3NSKGcWb4zD3gceL1eGBV9rk3hjAUWqK4pnTGgqQCTTMra', '2022-01-13 09:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_firstname` varchar(50) NOT NULL,
  `customer_lastname` varchar(50) NOT NULL,
  `customer_lastorder` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` int(11) NOT NULL,
  `discount_code` varchar(100) NOT NULL,
  `discount_amount` int(11) NOT NULL,
  `discount_start_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `discount_end_timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `employee_firstname` varchar(50) NOT NULL,
  `employee_lastname` varchar(50) NOT NULL,
  `employee_mobile_number` varchar(11) NOT NULL,
  `employee_email_address` varchar(65) NOT NULL,
  `employee_team_id` int(11) NOT NULL,
  `employee_account_start` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `employee_account_end` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `employee_firstname`, `employee_lastname`, `employee_mobile_number`, `employee_email_address`, `employee_team_id`, `employee_account_start`, `employee_account_end`) VALUES
(1, 'Administrator', 'Root', '', '', 1, '2021-12-11 14:42:20', NULL),
(2, 'Operation', 'Root', '', '', 2, '2021-12-11 14:42:26', NULL),
(3, 'Marketing', 'Root', '', '', 3, '2021-12-11 14:42:32', NULL),
(4, 'Support', 'Root', '', '', 4, '2022-01-07 22:44:08', '2022-01-07 22:44:08'),
(6, 'Demo', 'Demo', '', '', 1, '2022-01-13 09:22:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `inventory_product_id` int(11) NOT NULL,
  `inventory_product_count` int(11) NOT NULL,
  `inventory_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_customer_id` int(11) NOT NULL,
  `order_total_price` int(11) NOT NULL,
  `order_mobile_number` varchar(11) NOT NULL,
  `order_address_id` int(11) NOT NULL,
  `order_delivery_date` date NOT NULL,
  `order_handler_employee_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `order_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `password_reset_id` int(11) NOT NULL,
  `password_reset_employee_id` int(11) NOT NULL,
  `password_reset_password` mediumtext NOT NULL,
  `password_reset_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password_reset_handler` int(11) DEFAULT NULL,
  `password_reset_approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `password_reset`
--

INSERT INTO `password_reset` (`password_reset_id`, `password_reset_employee_id`, `password_reset_password`, `password_reset_timestamp`, `password_reset_handler`, `password_reset_approved`) VALUES
(1, 6, '$2y$10$gCC1QmfuCM6e0iHZ4VheievU9dyqc2SdjefxEjikLb3hvOWq1p8o6', '2022-01-13 09:33:14', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `price_id` int(11) NOT NULL,
  `price_product_id` int(11) NOT NULL,
  `price_amount` int(11) NOT NULL,
  `price_start_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `price_end_timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`price_id`, `price_product_id`, `price_amount`, `price_start_timestamp`, `price_end_timestamp`) VALUES
(1, 1, 1300, '2021-12-23 04:35:54', NULL),
(2, 2, 1300, '2021-12-23 04:38:58', '2021-12-24 01:03:33'),
(3, 2, 1100, '2021-12-24 01:03:33', '2021-12-27 11:17:49'),
(4, 3, 1300, '2021-12-24 03:07:27', NULL),
(5, 2, 1300, '2021-12-27 11:17:50', '2022-01-13 09:58:27'),
(6, 2, 1200, '2022-01-08 04:10:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_code` varchar(10) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_type` int(11) NOT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_start_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_end_timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_code`, `product_name`, `product_type`, `product_description`, `product_image`, `product_start_timestamp`, `product_end_timestamp`) VALUES
(1, 'NYCB', 'New York Style Cheesecake - Blueberry', 2, 'Size: 10x3\" Round\nShelf Life: 1 week (Refrigerated)', '20211223123554_61c3fcaa46f71.jpeg', '2021-12-23 04:35:54', NULL),
(2, 'NYCS', 'New York Style Cheesecake - Strawberry', 2, 'Size: 10x3\" Round\r\nShelf Life: 1 week (Refrigerated)', '20211227191132_61c99f6467e5b.jpeg', '2021-12-23 04:38:58', NULL),
(3, 'NYCM', 'New York Style Cheesecake - Mango', 2, 'Size: 10x3\" Round\r\nShelf Life: 1 week (Refrigerated)', '20211224110724_61c5396cad731.jpg', '2021-12-24 03:07:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `promotion_id` int(11) NOT NULL,
  `promotion_code` varchar(100) NOT NULL,
  `promotion_image` mediumtext NOT NULL,
  `promotion_start_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `promotion_end_timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`promotion_id`, `promotion_code`, `promotion_image`, `promotion_start_timestamp`, `promotion_end_timestamp`) VALUES
(1, 'TEST1', ' ', '2021-12-29 13:15:33', '2021-12-30 16:00:00'),
(2, 'TEST2', ' ', '2021-12-30 16:00:01', '2021-12-31 15:59:59'),
(3, 'TEST3', '20220102171253_61d16c95aaede.png', '2022-01-01 16:00:00', '2022-01-02 15:59:00'),
(4, 'PROMO1', '20211230145426_61cd57a28bfc4.png', '2021-12-30 13:13:00', '2021-12-26 13:14:00'),
(5, 'PROMO2', '20220104212233_61d44a19dd894.png', '2022-01-04 16:00:00', '2022-01-07 16:00:00'),
(6, 'PROMO3', '20220113184951_61e003cf7a0e0.jpg', '2022-01-23 10:49:00', '2022-01-29 10:49:00');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`) VALUES
(1, 'Administration'),
(2, 'Operation'),
(3, 'Marketing'),
(4, 'Support');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`type_id`, `type_name`) VALUES
(1, 'COOKIES'),
(2, 'CAKES'),
(3, 'CRINKLES'),
(4, 'DISCOUNT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`credential_id`),
  ADD KEY `credential_employee_id` (`credential_employee_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `employee_id` (`employee_team_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `inventory_product_id` (`inventory_product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cutsomer_id` (`order_customer_id`),
  ADD KEY `address_id` (`order_address_id`),
  ADD KEY `employee_handler` (`order_handler_employee_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_product_id` (`order_product_id`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`password_reset_id`),
  ADD KEY `reset_employee_id` (`password_reset_employee_id`),
  ADD KEY `reset_handler` (`password_reset_handler`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `product_id` (`price_product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_type` (`product_type`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `credential_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `password_reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `credentials`
--
ALTER TABLE `credentials`
  ADD CONSTRAINT `credential_employee_id` FOREIGN KEY (`credential_employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employee_id` FOREIGN KEY (`employee_team_id`) REFERENCES `teams` (`team_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_product_id` FOREIGN KEY (`inventory_product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `address_id` FOREIGN KEY (`order_address_id`) REFERENCES `address` (`address_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `cutsomer_id` FOREIGN KEY (`order_customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_handler` FOREIGN KEY (`order_handler_employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `order_product_id` FOREIGN KEY (`order_product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD CONSTRAINT `reset_employee_id` FOREIGN KEY (`password_reset_employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `reset_handler` FOREIGN KEY (`password_reset_handler`) REFERENCES `employees` (`employee_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `prices`
--
ALTER TABLE `prices`
  ADD CONSTRAINT `product_id` FOREIGN KEY (`price_product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `product_type` FOREIGN KEY (`product_type`) REFERENCES `types` (`type_id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
