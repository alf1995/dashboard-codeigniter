-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-06-2021 a las 19:18:02
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ciruka`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_email`
--

CREATE TABLE `logs_email` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(30) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(50) DEFAULT NULL,
  `fecha_registro` int(11) NOT NULL,
  `desbloqueo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_error`
--

CREATE TABLE `logs_error` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `usuario` varchar(70) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `fecha_registro` int(11) NOT NULL,
  `desbloqueo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_accion`
--

CREATE TABLE `roles_accion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `fecha_registro` int(11) NOT NULL,
  `fecha_modificacion` int(11) NOT NULL,
  `eliminacion_logica` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles_accion`
--

INSERT INTO `roles_accion` (`id`, `nombre`, `slug`, `fecha_registro`, `fecha_modificacion`, `eliminacion_logica`) VALUES
(1, 'agregar', 'agregar', 1624641129, 0, 1),
(2, 'editar', 'editar', 1624641135, 0, 1),
(3, 'observar', 'observar', 1624641146, 0, 1),
(4, 'listar', 'listar', 1624641153, 0, 1),
(5, 'denegar', 'denegar', 1624641160, 0, 1),
(6, 'permitir', 'permitir', 1624641165, 0, 1),
(7, 'remover', 'remover', 1624641170, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_modulo`
--

CREATE TABLE `roles_modulo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `fecha_registro` int(11) NOT NULL,
  `fecha_modificacion` int(11) NOT NULL,
  `eliminacion_logica` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles_modulo`
--

INSERT INTO `roles_modulo` (`id`, `nombre`, `slug`, `fecha_registro`, `fecha_modificacion`, `eliminacion_logica`) VALUES
(1, 'usuario', 'usuario', 1624641369, 0, 1),
(2, 'accion', 'accion', 1624641375, 0, 1),
(3, 'modulo', 'modulo', 1624641382, 0, 1),
(4, 'permisos', 'permisos', 1624641389, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(3) NOT NULL,
  `modulo_id` int(3) NOT NULL,
  `acciones` text NOT NULL,
  `fecha_registro` int(11) NOT NULL,
  `fecha_modificacion` int(11) NOT NULL,
  `eliminacion_logica` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `imagen` varchar(350) DEFAULT NULL,
  `rol_id` int(11) NOT NULL,
  `fecha_registro` int(11) NOT NULL,
  `fecha_modificacion` int(11) NOT NULL,
  `email_key` varchar(50) DEFAULT NULL,
  `eliminacion_logica` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `correo`, `clave`, `nombre`, `apellido`, `imagen`, `rol_id`, `fecha_registro`, `fecha_modificacion`, `email_key`, `eliminacion_logica`) VALUES
(1, 'admin@gmail.com', '$2y$10$dq4Oo4tCzMzTmWtfdm2GLef3o3w0LpDMhvm5SjAfD2u7iz9w7D4Ry', 'Admin', 'Panel', '', 500, 1624641040, 1624641315, 'fa855952889ba9b4a4af9d0d26c6c1b1', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `logs_email`
--
ALTER TABLE `logs_email`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs_error`
--
ALTER TABLE `logs_error`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles_accion`
--
ALTER TABLE `roles_accion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles_modulo`
--
ALTER TABLE `roles_modulo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `logs_email`
--
ALTER TABLE `logs_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs_error`
--
ALTER TABLE `logs_error`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles_accion`
--
ALTER TABLE `roles_accion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles_modulo`
--
ALTER TABLE `roles_modulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
