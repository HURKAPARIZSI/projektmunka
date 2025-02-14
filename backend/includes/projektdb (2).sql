-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Feb 14. 17:32
-- Kiszolgáló verziója: 10.4.27-MariaDB
-- PHP verzió: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `projektdb`
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
-- Tábla szerkezet ehhez a táblához `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Roulett asztal', '49.99', 20, 'img/kep_roulett.jpg', 'img/kep_roulett2.jpg', 'img/kep_roulett3.jpg', 'img/kep_roulett4.jpg'),
(2, 'Kártyák', '149.99', 10, 'img/kep_kartya.jpg', 'img/kep_kartya2.jpg', 'img/kep_kartya3.jpg', 'img/kep_kartya4.jpg'),
(3, 'Szerencsekerék', '9.99', 50, 'img/kep_szerencsekerek.jpg', 'img/kep_szerencskerek2.jpg', 'img/kep_szerencskerek3.jpg', 'img/kep_szerencskerek4.jpg'),
(4, 'Poker asztal', '89.99', 15, 'img/kep_poker.jpg', 'img/kep_poker2.jpg', 'img/kep_poker3.jpg', 'img/kep_poker4.jpg'),
(5, 'Slot', '4.99', 100, 'img/kep_slot.jpg', 'img/kep_slot2.jpg', 'img/kep_slot3.jpg', 'img/kep_slot4.jpg'),
(6, 'Blackjack asztal', '29.99', 25, 'img/kep_blackjack.jpg', 'img/kep_blackjack2.jpg', 'img/kep_blackjack3.jpg', 'img/kep_blackjack4.jpg'),
(7, 'Craps Dice Table', '199.99', 5, NULL, NULL, NULL, NULL),
(8, 'Dealer Button', '2.99', 50, NULL, NULL, NULL, NULL),
(9, 'Card Shuffler Machine', '39.99', 20, NULL, NULL, NULL, NULL),
(10, 'Casino Chips Case', '99.99', 10, NULL, NULL, NULL, NULL);

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
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `is_admin`) VALUES
(1, 'johndoe', 'johndoe@example.com', 'password123', '2024-11-11 10:27:10', 0),
(2, 'janedoe', 'janedoe@example.com', 'password456', '2024-11-11 10:27:10', 0),
(7, 'admin', 'admin@gmail.com', '$2y$10$cDJE9St1wWkPvC70FdlfGOT.zN4z9NG8Mc1W89YHaJcRO2J5IdzSW', '2025-01-13 11:17:13', 0),
(8, 'asdasd', 'asdasdasd@gmail.com', '$2y$10$pBLN0F2LzhAk3BwlIi.RVOd6ZesPeLdA6TVqI025pjH.4vLrA66sO', '2025-01-16 10:12:50', 0),
(12, 'username', 'username@gmail.com', '$2y$10$ZlTSECRBCQKMTYF/Rsx9uuATx7r6T6c9UmSrfvOkgKHdN80N3rSoW', '2025-01-23 11:16:29', 0),
(14, 'kecske', 'kecske@gmail.com', '$2y$10$ajVPHj7K8mMgOxEAyeyWfu9tbpdFg4h2Y85CN2wHPIBw2yS02aCyy', '2025-01-23 13:41:26', 0),
(15, 'kakiskuki', 'kakismacska@gmail.com', '$2y$10$muOXi/EFaaLDRSHWIGiClulq4AHE25C5HLzJj8sqzB/uqEerQxvDi', '2025-01-24 11:05:40', 0);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

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
  ADD PRIMARY KEY (`user_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
