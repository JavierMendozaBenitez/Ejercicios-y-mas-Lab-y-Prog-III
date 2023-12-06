-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2023 a las 07:28:55
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `garage_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autos`
--

CREATE TABLE `autos` (
  `patente` varchar(30) NOT NULL,
  `marca` varchar(30) NOT NULL,
  `color` varchar(15) NOT NULL,
  `precio` double NOT NULL,
  `foto` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `autos`
--

INSERT INTO `autos` (`patente`, `marca`, `color`, `precio`, `foto`) VALUES
('AYF714', 'Renault', 'gris', 150000, NULL),
('TOC623', 'Fiat', 'blanco', 198000, NULL),
('AB555DC', 'Ford', 'verde', 256900, './AB555DC.105905.jpg'),
('AA666AA', 'Chevrolet', 'rojo', 323200, './AA666AA.105905.jpg'),
('AA888CC', 'Citroen', 'Blanco', 3000666, './autos/imagenes/AA888CC.030627.jpg'),
('sss', 'Citroen', 'Blanco', 3000666, '../autos/imagenes/sss.030901.jpg'),
('a', 'Citroen', 'Blanco', 3000666, '../autos/imagenes/a.031057.jpg'),
('ad', 'Citroen', 'Blanco', 3000666, '../autos/imagenes/ad.221503.jpg'),
('adjuju', 'Citroen', 'Blanco', 3000666, '../autos/imagenes/adjuju.221940.jpg'),
('abc123', 'fiat4', 'Blanco4', 100554, '../autos/imagenes/abc123.155336.jpg'),
('abc1230', 'ford_modif', 'rojo_modif', 1, 'abc1230.modificado.205602.jpg'),
('abc1231', 'fiat4', 'Blanco4', 100554, '../autos/imagenes/abc1231.155401.jpg'),
('abc123', 'ford', 'rojo', 133000, 'sin foto');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
