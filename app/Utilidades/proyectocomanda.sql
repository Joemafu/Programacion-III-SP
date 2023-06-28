-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2023 a las 13:55:27
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectocomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(5) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `estado`) VALUES
(1, 'cerrada'),
(2, 'abierta'),
(3, 'abierta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(5) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `producto` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tiempoEstimado` int(3) NOT NULL,
  `foto` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `nombreCliente`, `producto`, `estado`, `tiempoEstimado`, `foto`) VALUES
(45, 'Juan', 'milanesa con fritas', 'listo para servir', 25, '../ImagenesPedidos/2023-06-26 11-37-17hs. Juan.jpg'),
(46, 'Pedro', 'Daiquiri', 'en preparacion', 25, '../ImagenesPedidos/2023-06-26 11-37-46hs. Pedro.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(5) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `tipo`) VALUES
(1, 'hamburguesa', '26.00', 'comida'),
(2, 'daiquiri', '26.00', 'trago'),
(3, 'ipa', '11.00', 'cerveza'),
(4, 'tiramisu', '11.00', 'postre'),
(5, 'bife', '30.99', 'comida'),
(6, 'bife con fritas', '34.99', 'comida'),
(7, 'milanesa con fritas', '34.99', 'comida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(5) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `clave`, `rol`) VALUES
(1, 'socioTest', 'socioTest', 'socio'),
(2, 'mozoTest', 'mozoTest', 'mozo'),
(3, 'bartenderTest', 'bartenderTest', 'bartender'),
(4, 'cerveceroTest', 'cerveceroTest', 'cervecero'),
(5, 'cocineroTest', 'cocineroTest', 'cocinero'),
(6, 'altaTest', 'pa$$', 'mozo'),
(8, 'altaTest2', 'pa$$', 'mozo'),
(9, 'altaTest3', 'pa$$', 'cervecero'),
(10, 'altaCSVTest1', 'claveTest', 'bartender'),
(11, 'altaCSVTest2', 'claveTest', 'bartender');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
