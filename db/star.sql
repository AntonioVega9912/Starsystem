-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-12-2025 a las 14:02:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `star`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `IdInventario` int(11) NOT NULL,
  `IdProducto` int(11) DEFAULT NULL,
  `Inventario_actual` int(11) DEFAULT 0,
  `Inventario_minimo` int(11) DEFAULT 0,
  `Fecha_creación` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`IdInventario`, `IdProducto`, `Inventario_actual`, `Inventario_minimo`, `Fecha_creación`) VALUES
(1, 2, 0, 0, '2025-11-28 20:06:20'),
(2, 6, 0, 0, '2025-11-28 20:06:20'),
(3, 7, 0, 0, '2025-11-28 20:06:20'),
(4, 8, 44, 0, '2025-12-16 19:09:49'),
(5, NULL, 0, 0, '2025-11-28 20:12:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `idModulo` int(11) NOT NULL,
  `nombre_modulo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`idModulo`, `nombre_modulo`) VALUES
(1, 'dashboard'),
(3, 'ingresos'),
(2, 'productos'),
(5, 'roles_permisos'),
(4, 'salidas'),
(6, 'usuarios'),
(7, 'Ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `IdMovimiento` int(11) NOT NULL,
  `IdProducto` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `Tipo_movimiento` enum('INGRESO','SALIDA') DEFAULT NULL,
  `Cantidad` int(11) NOT NULL,
  `Fecha_movimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`IdMovimiento`, `IdProducto`, `IdUsuario`, `Tipo_movimiento`, `Cantidad`, `Fecha_movimiento`) VALUES
(11, 2, 1, 'INGRESO', 1, '2025-11-28 15:22:53'),
(12, 2, 1, 'INGRESO', 1, '2025-11-28 17:08:03'),
(13, 6, 1, 'INGRESO', 4, '2025-11-28 17:11:59'),
(14, 7, 1, 'INGRESO', 25, '2025-11-28 17:13:16'),
(15, 8, 1, 'INGRESO', 25, '2025-11-28 20:12:39'),
(16, 8, 1, 'SALIDA', 5, '2025-11-28 20:18:50'),
(17, 8, 1, 'SALIDA', 1, '2025-12-16 19:09:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `idMovimiento` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `tipo` enum('INGRESO','SALIDA') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `idPermiso` int(11) NOT NULL,
  `IdRol` int(11) NOT NULL,
  `Nombre_permiso` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fecha_creación` date DEFAULT NULL,
  `idModulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`idPermiso`, `IdRol`, `Nombre_permiso`, `Descripcion`, `Fecha_creación`, `idModulo`) VALUES
(5, 2, NULL, NULL, NULL, 0),
(6, 3, NULL, NULL, NULL, 0),
(13, 5, NULL, NULL, NULL, 3),
(14, 5, NULL, NULL, NULL, 2),
(15, 5, NULL, NULL, NULL, 4),
(16, 1, NULL, NULL, NULL, 1),
(17, 1, NULL, NULL, NULL, 2),
(18, 1, NULL, NULL, NULL, 3),
(19, 1, NULL, NULL, NULL, 4),
(20, 1, NULL, NULL, NULL, 5),
(21, 1, NULL, NULL, NULL, 6),
(22, 1, NULL, NULL, NULL, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `Codigo_producto` varchar(50) DEFAULT NULL,
  `Nombre_producto` varchar(150) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Precio` decimal(10,2) DEFAULT NULL,
  `Fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `Codigo_producto`, `Nombre_producto`, `Descripcion`, `Precio`, `Fecha_creacion`, `stock`) VALUES
(2, '2', 'Portátil', 'Asus ', 1000000.00, '2025-11-25 21:08:15', 0),
(6, '4', 'Cargador', 'Cargador generico', 4500.00, '2025-11-26 16:34:42', 0),
(7, '5', 'Llavero ', 'Llavero ', 2000.00, '2025-11-28 17:11:50', 0),
(8, '7', 'Forro de celular ', 'Forro de celular Honor X6C ', 15000.00, '2025-11-28 20:12:21', 44);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `Idrol` int(11) NOT NULL,
  `Nombre_rol` varchar(50) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fecha_cración` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`Idrol`, `Nombre_rol`, `Descripcion`, `Fecha_cración`) VALUES
(1, 'Admin', 'Super Administrador del Sistema', '2025-11-25'),
(5, 'VENTAS', NULL, NULL),
(6, 'ALMACEN', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id` int(11) NOT NULL,
  `idRol` int(11) DEFAULT NULL,
  `idPermiso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `IdSalida` int(11) NOT NULL,
  `IdProducto` int(11) DEFAULT NULL,
  `IdUsuario` int(11) DEFAULT NULL,
  `Cantidad` decimal(10,2) DEFAULT NULL,
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IdUsuario` int(11) NOT NULL,
  `Nombre_usuario` varchar(100) DEFAULT NULL,
  `Apellido_usuario` varchar(100) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `Contraseña` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `Fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IdUsuario`, `Nombre_usuario`, `Apellido_usuario`, `usuario`, `Contraseña`, `email`, `estado`, `Fecha_creacion`) VALUES
(1, 'Admin', 'Admin', 'Admin', '$2y$10$JTNzTesKYQcAP.TWTGk1eeIkn33jImDz4lOEvBMULQj.1G0rIYClS', 'admin@star.com', 1, '2025-11-25 18:40:14'),
(2, 'Antonio ', 'Vega ', 'Antoni', '$2y$10$6DuJG6g4w6Dm33qPlGZh/u4M3gN5ceKJ6nXkjtk10.mj.c4jJqSuq', NULL, 1, '2025-12-09 17:01:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `idRol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`id`, `idUsuario`, `idRol`) VALUES
(1, 1, 1),
(4, 2, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `idVenta` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `idUsuario` int(11) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `iva` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`idVenta`, `fecha`, `idUsuario`, `total`, `subtotal`, `iva`) VALUES
(6, '2025-12-16 14:09:49', 1, 17850.00, 15000.00, 2850.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_detalle`
--

CREATE TABLE `ventas_detalle` (
  `IdDetalle` int(11) NOT NULL,
  `IdVenta` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas_detalle`
--

INSERT INTO `ventas_detalle` (`IdDetalle`, `IdVenta`, `IdProducto`, `cantidad`, `precio`, `subtotal`, `total`) VALUES
(1, 6, 8, 1, 15000.00, 15000.00, 0.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`IdInventario`),
  ADD KEY `IdProducto` (`IdProducto`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`idModulo`),
  ADD UNIQUE KEY `nombre_modulo` (`nombre_modulo`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`IdMovimiento`),
  ADD KEY `IdProducto` (`IdProducto`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`idMovimiento`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`idPermiso`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD UNIQUE KEY `Codigo_producto` (`Codigo_producto`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`Idrol`),
  ADD UNIQUE KEY `Nombre_rol` (`Nombre_rol`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idRol` (`idRol`),
  ADD KEY `idPermiso` (`idPermiso`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`IdSalida`),
  ADD KEY `IdProducto` (`IdProducto`),
  ADD KEY `IdUsuario` (`IdUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idRol` (`idRol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`idVenta`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD PRIMARY KEY (`IdDetalle`),
  ADD KEY `IdVenta` (`IdVenta`),
  ADD KEY `IdProducto` (`IdProducto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `IdInventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `idModulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `IdMovimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `idMovimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `idPermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `Idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `IdSalida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `idVenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  MODIFY `IdDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `productos` (`idProducto`);

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `productos` (`idProducto`),
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`);

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idrol`),
  ADD CONSTRAINT `rol_permiso_ibfk_2` FOREIGN KEY (`idPermiso`) REFERENCES `permisos` (`idPermiso`);

--
-- Filtros para la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `salidas_ibfk_1` FOREIGN KEY (`IdProducto`) REFERENCES `productos` (`idProducto`),
  ADD CONSTRAINT `salidas_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`),
  ADD CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`idRol`) REFERENCES `rol` (`idrol`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD CONSTRAINT `ventas_detalle_ibfk_1` FOREIGN KEY (`IdVenta`) REFERENCES `ventas` (`idVenta`),
  ADD CONSTRAINT `ventas_detalle_ibfk_2` FOREIGN KEY (`IdProducto`) REFERENCES `productos` (`idProducto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
