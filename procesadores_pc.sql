Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `procesadores` (
  `id` int(11) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `arquitectura` varchar(50) NOT NULL,
  `nucleos` int(11) NOT NULL,
  `frecuencia` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `procesadores` (`id`, `modelo`, `marca`, `arquitectura`, `nucleos`, `frecuencia`, `precio`) VALUES
(1, 'Core i9-13900K', 'Intel', 'Raptor Lake', 24, '3.0 GHz base / 5.8 GHz turbo', 589.99),
(2, 'Core i7-13700K', 'Intel', 'Raptor Lake', 16, '3.4 GHz base / 5.4 GHz turbo', 409.99),
(3, 'Core i5-13600K', 'Intel', 'Raptor Lake', 14, '3.5 GHz base / 5.1 GHz turbo', 319.99),
(4, 'Ryzen 9 7950X', 'AMD', 'Zen 4', 16, '4.5 GHz base / 5.7 GHz boost', 699.99),
(5, 'Ryzen 7 7700X', 'AMD', 'Zen 4', 8, '4.5 GHz base / 5.4 GHz boost', 399.99),
(6, 'Ryzen 5 7600X', 'AMD', 'Zen 4', 6, '4.7 GHz base / 5.3 GHz boost', 299.99);


ALTER TABLE `procesadores`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `procesadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

