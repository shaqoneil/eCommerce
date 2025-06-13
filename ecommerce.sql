-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 03:22 PM
-- Server version: 8.0.40
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `Admin_user_ID` varchar(50) NOT NULL,
  `Admin_Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int NOT NULL,
  `brand_title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_title`) VALUES
(1, 'San Miguel'),
(2, 'Coca-cola'),
(3, 'Jack\'n Jill'),
(4, 'Nissin'),
(5, 'Del Monte'),
(6, 'Silver Swan');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `qty` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `ip_address`, `qty`, `added_at`) VALUES
(68, 32, '10.10.100.53', 1, '2025-01-17 02:59:21'),
(69, 31, '10.10.100.53', 1, '2025-01-17 02:59:24'),
(70, 20, '10.10.100.53', 1, '2025-01-17 02:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int NOT NULL,
  `cat_title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_title`) VALUES
(1, 'Beverages'),
(2, 'Noodles'),
(3, 'Condiments '),
(4, 'Chips');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `Customer_ID` int NOT NULL,
  `Customer_Name` varchar(50) NOT NULL,
  `Customer_Address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `order_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `order_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`order_id`, `customer_id`, `ip_address`, `customer_name`, `customer_email`, `customer_address`, `payment_method`, `order_total`, `order_date`, `order_status`) VALUES
(1, NULL, '127.0.0.1', 'Ronelio Estoya', 'oneilestoya@yahoo.com', 'Sta Cruz Laguna', 'credit_card', 130.00, '2025-06-09 22:29:45', 'Pending'),
(2, NULL, '127.0.0.1', 'Rondi Sebastian Estoya', 'gladstone_31@hotmail.com', 'Pawing Palo', 'credit_card', 90.00, '2025-06-10 08:33:30', 'Pending'),
(3, NULL, '127.0.0.1', 'Rondi Sebastian Estoya', 'gladstone_31@hotmail.com', 'Pawing Palo', 'cash', 260.00, '2025-06-10 11:57:20', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Order_ID` int NOT NULL,
  `Order_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payments_ID` int NOT NULL,
  `payments_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_orders`
--

CREATE TABLE `pending_orders` (
  `order_item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `order_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pending_orders`
--

INSERT INTO `pending_orders` (`order_item_id`, `order_id`, `product_id`, `qty`, `price_at_purchase`, `ip_address`, `order_status`) VALUES
(1, 1, 34, 1, 65.00, '127.0.0.1', 'Pending'),
(2, 1, 33, 1, 65.00, '127.0.0.1', 'Pending'),
(3, 2, 35, 1, 25.00, '127.0.0.1', 'Pending'),
(4, 2, 34, 1, 65.00, '127.0.0.1', 'Pending'),
(5, 3, 35, 1, 25.00, '127.0.0.1', 'Pending'),
(6, 3, 33, 2, 65.00, '127.0.0.1', 'Pending'),
(7, 3, 40, 1, 65.00, '127.0.0.1', 'Pending'),
(8, 3, 41, 1, 40.00, '127.0.0.1', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_id` int NOT NULL,
  `Product_title` varchar(255) NOT NULL,
  `Product_cat` int NOT NULL,
  `Product_brand` int NOT NULL,
  `Product_price` int NOT NULL,
  `Product_desc` text NOT NULL,
  `Product_image` text NOT NULL,
  `Product_keywords` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_id`, `Product_title`, `Product_cat`, `Product_brand`, `Product_price`, `Product_desc`, `Product_image`, `Product_keywords`) VALUES
(33, 'Coca Cola Original 1.5L', 1, 2, 65, 'Carbonated water, Sugar, Caramel color, Acidity regulator (Phosphoric acid), Natural flavors, Caffeine, Sweetener (Sucralose)', 'coke orig 1.5L.jpg', 'coke'),
(34, 'Royal 1.5L', 1, 2, 65, 'Carbonated water, Sugar, Caramel color, Acidity regulator (Phosphoric acid), Natural flavors, Caffeine, Sweetener (Sucralose)', 'royal.png', 'coca cola'),
(35, 'Cup Noodles', 2, 4, 25, 'Creamy', 'cup noodles.jpg', 'NIS'),
(36, 'Nissin Ramen Instant Noodles | 55g', 2, 4, 12, 'Add noodles and seasoning to boiling water. Boil until noodles are cooked. Enjoy while hot.', 'SM101659225-8.png', 'NIS'),
(37, 'Ho-Mi Cup Instant Noodles', 2, 4, 28, 'Ho-Mi Cup Instant Mami Noodles Beefy Beef | 40g', 'Ho-Mi.jpg', 'NIS'),
(38, 'Otoki Jin Ramen Spicy Korean Style', 2, 4, 45, 'Otoki Jin Ramen Spicy Korean Style Instant Bowl Noodles | 110g | Noodle: Wheat Flour, Palm Oil, Modified Potato Starch, Potato Starch, Salt, Emulsified Oil, Beefbone Extract, Calcium, Alkaline Agent (Potassium Carbonate, Sodium Carbonate),', 'Jin Ramen.jpg', 'NIS'),
(39, 'Coca-Cola Original Taste | 320ml', 1, 2, 38, 'Carbonated water, Sugar, Caramel color, Acidity regulator (Phosphoric acid), Natural flavors, Caffeine', 'Coca-Cola.jpg', 'coke'),
(40, 'Coca Cola Zero Sugar | 1.5L', 1, 2, 65, 'Carbonated water, Caramel color, Acidulant (Phosphoric acid), Sweeteners (Sucralose and Acesulfame-K), Preservative (Sodium benzoate), Natural flavors, Acidity regulator (Sodium citrate), Caffeine', 'Cola Zero.jpg', 'coke'),
(41, 'San Miguel Beer Pale Pilsen', 1, 1, 40, 'San Miguel Beer Pale Pilsen | 330ml | It\'s a great choice for casual drinking, especially on a hot day.', 'Beer Pale.jpg', 'RH'),
(42, 'Maggi Magic Sarap Seasoning', 3, 6, 48, 'Maggi Magic Sarap Seasoning | 8g x 16pcs |Ingredients: Iodized salt, Flavor enhancers (Monosodium glutamate, Ribonucleotide), Sugar, Garlic, Chicken fat, Onion, Spices, Nature-identical flavor, Chicken meat, Egg yolk.', 'Magic Sarap.jpg', 'DEL'),
(43, 'Silver Swan Soy Sauce', 3, 6, 12, 'Silver Swan Soy Sauce | 200ml | WATER, SOYBEAN EXTRACT, WHEAT, IODIZED SALT, CARAMEL COLOR AND LESS THAN 0.1% POTASSIUM SORBATE AS PRESERVATIVE.', 'Soy Sauce.jpg', 'NIS'),
(44, 'Silver Swan Sukang Puti | 200ml', 3, 6, 10, 'Silver Swan Sukang Puti | 200ml | Naturally fermented sugarcane vinegar with 4.5% acidity', 'Sukang Puti.jpg', 'NIS'),
(45, 'UFC Banana Ketchup', 3, 5, 23, 'UFC Banana Ketchup Savers Pack | 200g | Water, Sugar, Banana (10%), Modified Starch (Corn), Vinegar, Iodized Salt (Salt, Potassium Iodate), Onion, Chili, Garlic, 0.1% Sodium Benzoate (E211) as preservative and Colors: E102 and E129 (may have adverse effect on activity and attention in children).', 'Banana Ketchup.jpg', 'DEL'),
(46, 'Mr. Chips Nacho Cheese', 4, 3, 32, 'Jack \'n Jill Mr. Chips Nacho Cheese | 26g | corn, vegetable oil (palm oil), cheese powder (whey cheddar cheese (cultured pasterized milk, salt, enzymes (rennet from cow), butter, cream, salt), dry buttermilk, salt, natural cheese and butter flavour, disodium phosphate (E450), extractive of annatto (natural color E160 silicon dioxide (E551) as anticaking agent) skimmed milk, natural spices onion, garlic, chili, cayenne, white pepper.', 'Mr. Chips.jpg', 'SMB'),
(47, 'Clover Chips Cheesier', 4, 2, 24, 'Clover Chips Cheesier | 26g | The chips are thin, airy, and crisp, offering a satisfying crunch with every bite.', 'Clover Chips.jpg', 'NIS');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `email`, `phone`, `username`, `password`, `created_at`, `is_admin`) VALUES
(1, 'Ronelio Estoya', 'Pawing, Palo Leyte', 'oneilestoya@gmail.com', '09485235368', 'admin', '$2y$10$ZDL4lUbnshhVudFX2nlOTeSAK4NaeBWHZ4FkJJxkig5uFtYPz/d0a', '2025-05-09 08:16:02', 1),
(2, 'Rondi Sebastian Estoya', 'Pawing Palo, Leyte', 'gladstone_31@hotmail.com', '09914783724', 'baste', '$2y$10$3RZn.kQYI9IDURpFYQYdg.3xCkkOYYidaoCG5xjAdBKJnNP4kXFvy', '2025-06-09 13:08:56', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `pending_orders`
--
ALTER TABLE `pending_orders`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pending_orders`
--
ALTER TABLE `pending_orders`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pending_orders`
--
ALTER TABLE `pending_orders`
  ADD CONSTRAINT `pending_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `customer_orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`Product_id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
