-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Máj 28. 14:46
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `projekt_db`
--

DELIMITER $$
--
-- Eljárások
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddToCart` (IN `user_id` INT, IN `product_id` INT, IN `quantity` INT)   BEGIN
    INSERT INTO cart (user_id, product_id, quantity)
    VALUES (user_id, product_id, quantity)
    ON DUPLICATE KEY UPDATE
    quantity = quantity + VALUES(quantity);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateOrder` (IN `user_id` INT)   BEGIN
    DECLARE order_id INT;

    -- Create the order
    INSERT INTO orders (user_id, order_date)
    VALUES (user_id, NOW());

    -- Get the newly created order ID
    SET order_id = LAST_INSERT_ID();

    -- Move items from cart to order_items
    INSERT INTO order_items (order_id, product_id, quantity)
    SELECT order_id, product_id, quantity
    FROM cart
    WHERE user_id = user_id;

    -- Clear the cart for the user
    DELETE FROM cart
    WHERE user_id = user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_user` (IN `p_username` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_password` VARCHAR(255), IN `p_is_admin` TINYINT)   BEGIN
    -- Ellenőrizzük, hogy az email már létezik-e
    IF EXISTS (
        SELECT 1 FROM `users` WHERE `email` = p_email
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Email already exists!';
    ELSE
        -- Új felhasználó hozzáadása
        INSERT INTO `users` (`username`, `email`, `password`, `created_at`, `is_admin`)
        VALUES (p_username, p_email, p_password, NOW(), p_is_admin);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProductsByCategory` (IN `category_id` INT)   BEGIN
    SELECT * 
    FROM products
    WHERE category_id = category_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateProductCategory` (IN `old_category_id` INT, IN `new_category_id` INT)   BEGIN
    -- Update all products with the old category_id
    UPDATE products
    SET category_id = new_category_id
    WHERE category_id = old_category_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`) VALUES
(1, 7, '2025-04-15 16:51:34'),
(2, 16, '2025-04-19 12:12:02');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cart_items`
--

CREATE TABLE `cart_items` (
  `item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `created_at`) VALUES
(1, 16, '2025-04-19 12:15:51'),
(2, 7, '2025-04-22 18:29:25'),
(3, 7, '2025-05-28 14:44:10');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1, 149.99),
(2, 2, 3, 5, 9.99),
(3, 2, 1, 1, 49.99),
(4, 2, 4, 1, 89.99),
(5, 2, 5, 2, 4.99),
(6, 3, 3, 1, 3240.00);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_default` varchar(255) DEFAULT NULL,
  `image_ver1` varchar(255) DEFAULT NULL,
  `image_ver2` varchar(255) DEFAULT NULL,
  `image_ver3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `stock`, `image_default`, `image_ver1`, `image_ver2`, `image_ver3`) VALUES
(1, 'Roulett asztal', 61000.00, 20, 'img/kep_roulett.jpg', 'img/kep_roulett2.jpg', 'img/kep_roulett3.jpg', 'img/kep_roulett4.jpg'),
(2, 'Kártyák', 4600.00, 10, 'img/kep_kartya.jpg', 'img/kep_kartya2.jpg', 'img/kep_kartya3.jpg', 'img/kep_kartya4.jpg'),
(3, 'Szerencsekerék', 3240.00, 50, 'img/kep_szerencsekerek.jpg', 'img/kep_szerencsekerek2.jpg', 'img/kep_szerencskerek3.jpg', 'img/kep_szerencskerek4.jpg'),
(4, 'Poker asztal', 612000.00, 15, 'img/kep_poker.jpg', 'img/kep_poker2.jpg', 'img/kep_poker3.jpg', 'img/kep_poker4.jpg'),
(5, 'Slot', 580000.00, 100, 'img/kep_slot.jpg', 'img/kep_slot2.jpg', 'img/kep_slot3.jpg', 'img/kep_slot4.jpg'),
(6, 'Blackjack asztal', 612000.00, 25, 'img/kep_blackjack.jpg', 'img/kep_blackjack2.jpg', 'img/kep_blackjack3.jpg', 'img/kep_blackjack4.jpg'),
(7, 'Craps Dice Table', 612000.00, 5, 'img/kep_craps.jpg', 'img/kep_craps2.jpg', 'img/kep_craps3.jpg', 'img/kep_craps4.jpg'),
(8, 'Dealer Button', 1080.00, 50, 'img/kep_button.jpg', 'img/kep_button2.jpg', 'img/kep_button3.jpg', 'img/kep_button4.jpg'),
(9, 'Card Shuffler Machine', 36000.00, 20, 'img/kep_shuffler.jpg', 'img/kep_shuffler2.jpg', 'img/kep_shuffler3.jpg', 'img/kep_shuffler4.jpg'),
(10, 'Casino Chips Case', 25000.00, 10, 'img/kep_chips.jpg', 'img/kep_chips2.jpg', 'img/kep_chips3.jpg', 'img/kep_chips4.jpg');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `is_admin`, `phone`, `address`) VALUES
(1, 'johndoe', 'johndoe@example.com', 'password123', '2024-11-11 10:27:10', 0, NULL, NULL),
(2, 'janedoe', 'janedoe@example.com', 'password456', '2024-11-11 10:27:10', 0, NULL, NULL),
(7, 'admin', 'admin@gmail.com', '$2y$10$cDJE9St1wWkPvC70FdlfGOT.zN4z9NG8Mc1W89YHaJcRO2J5IdzSW', '2025-01-13 11:17:13', 0, NULL, NULL),
(8, 'asdasd', 'asdasdasd@gmail.com', '$2y$10$pBLN0F2LzhAk3BwlIi.RVOd6ZesPeLdA6TVqI025pjH.4vLrA66sO', '2025-01-16 10:12:50', 0, NULL, NULL),
(12, 'username', 'username@gmail.com', '$2y$10$ZlTSECRBCQKMTYF/Rsx9uuATx7r6T6c9UmSrfvOkgKHdN80N3rSoW', '2025-01-23 11:16:29', 0, NULL, NULL),
(14, 'kecske', 'kecske@gmail.com', '$2y$10$ajVPHj7K8mMgOxEAyeyWfu9tbpdFg4h2Y85CN2wHPIBw2yS02aCyy', '2025-01-23 13:41:26', 0, NULL, NULL),
(15, 'kakiskuki', 'kakismacska@gmail.com', '$2y$10$muOXi/EFaaLDRSHWIGiClulq4AHE25C5HLzJj8sqzB/uqEerQxvDi', '2025-01-24 11:05:40', 0, NULL, NULL),
(16, 'KOKO', 'nudl@gmail.hu', '$2y$10$NwkcteWBI61yrRQCUr9hWe0opqdS1vfF/oph2MRk0xwJuMn2lX4li', '2025-04-19 12:09:49', 0, 'Dómbóvár első út 1', '0670303030');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- A tábla indexei `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- A tábla indexei `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT a táblához `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Megkötések a táblához `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Megkötések a táblához `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
