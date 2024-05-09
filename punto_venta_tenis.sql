-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-05-2024 a las 23:14:12
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
-- Base de datos: `punto_venta_tenis`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre_cliente` varchar(80) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre_cliente`, `email`, `password`, `direccion`, `status`) VALUES
(1, 'julio', 'julio@ejempo.com', '123', 'maiz 255', 1),
(2, 'bruno', 'bruno@ejemplo.com', '123', 'islas antillas 55', 1),
(3, 'angelllll', 'angel@gmail.com', '123', 'hidalgo 233', 2),
(4, 'ivan', 'ivan@hotmail.com', '123', 'boulevard 555', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `total_compra` float NOT NULL,
  `status_compra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `id_proveedor`, `id_empleado`, `fecha`, `total_compra`, `status_compra`) VALUES
(1, 1, 1, '2024-05-07', 6000, 1),
(2, 1, 2, '2024-05-09', 5000, 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `detallescompras`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `detallescompras` (
`id_compra` int(11)
,`id_detalle_compra` int(11)
,`id_producto` int(11)
,`nombre_producto` varchar(45)
,`id_producto_talla` int(11)
,`id_talla` int(11)
,`talla` varchar(30)
,`cantidad` int(11)
,`precio_unitario` float
,`subtotal` float
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `detallesventa`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `detallesventa` (
`id_venta` int(11)
,`id_detalle_venta` int(11)
,`id_producto` int(11)
,`nombre_producto` varchar(45)
,`id_producto_talla` int(11)
,`id_talla` int(11)
,`talla` varchar(30)
,`cantidad` int(11)
,`precio_unitario` float
,`subtotal` float
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compras`
--

CREATE TABLE `detalles_compras` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_producto_talla` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` float DEFAULT NULL,
  `subtotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_compras`
--

INSERT INTO `detalles_compras` (`id_detalle_compra`, `id_compra`, `id_producto`, `id_producto_talla`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 1, 4, 5, 2899, 14495),
(2, 1, 2, 5, 8, 3200, 25600),
(3, 2, 4, 15, 3, 2999, 8697),
(4, 2, 2, 5, 1, 3200, 3200),
(5, 2, 1, 4, 9, 2899, 26091);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_ventas`
--

CREATE TABLE `detalles_ventas` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_producto_talla` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` float DEFAULT NULL,
  `subtotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_ventas`
--

INSERT INTO `detalles_ventas` (`id_detalle_venta`, `id_venta`, `id_producto`, `id_producto_talla`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(6, 1, 1, 4, 2, 2899, 5798),
(7, 1, 2, 5, 2, 3200, 6400),
(21, 11, 1, 1, 2, 2899, 5798),
(22, 11, 2, 5, 3, 3200, 9600),
(23, 11, 2, 7, 2, 3200, 6400),
(25, 12, 2, 5, 1, 3200, 3200),
(26, 12, 4, 15, 2, 2999, 5998),
(27, 14, 4, 15, 2, 2999, 5998),
(28, 13, 1, 2, 2, 2899, 5798),
(29, 13, 1, 3, 1, 2899, 2899);

--
-- Disparadores `detalles_ventas`
--
DELIMITER $$
CREATE TRIGGER `ActualizarTotalVentaOnDelete` AFTER DELETE ON `detalles_ventas` FOR EACH ROW UPDATE ventas
SET total_venta = (
    SELECT SUM(precio_unitario * cantidad) 
    FROM detalles_ventas 
    WHERE id_venta = OLD.id_venta
)
WHERE id_venta = OLD.id_venta
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ActualizarTotalVentaOnUpdate` AFTER UPDATE ON `detalles_ventas` FOR EACH ROW UPDATE ventas
SET total_venta = (
    SELECT SUM(precio_unitario * cantidad) 
    FROM detalles_ventas 
    WHERE id_venta = NEW.id_venta
)
WHERE id_venta = NEW.id_venta
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_total_venta` AFTER INSERT ON `detalles_ventas` FOR EACH ROW UPDATE ventas 
    SET total_venta = total_venta + NEW.subtotal
    WHERE id_venta = NEW.id_venta
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombre_empleado` varchar(80) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `cargo` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre_empleado`, `email`, `password`, `cargo`, `status`) VALUES
(1, 'Ivan', 'ivan@gmail.com', '1234', 'Dueño', 1),
(2, 'Diego', 'diego@gmail.com', '123', 'empleado', 1),
(3, 'Emmanuel', 'emma@gmail.com', '123', 'empleado', 1),
(4, 'Luis', 'angelo@gmail.com', '123', 'gerente', 1),
(5, 'Alfredo', 'alfredo@gmail.com', '123', 'empleado', 0);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `informacioncliente`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `informacioncliente` (
`id_cliente` int(11)
,`nombre_cliente` varchar(80)
,`email` varchar(45)
,`direccion` varchar(45)
,`total_ventas_cliente` bigint(21)
,`total_venta_status_1` double
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(45) DEFAULT NULL,
  `descripcion` varchar(180) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `categoria` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion`, `precio`, `stock`, `categoria`, `status`) VALUES
(1, 'Air Jordan 1 Low Bred Toe 2.0', 'Un par reconocido por cualquiera, Esta silueta', 2899, 8, 'Tenis Jordan 1', 1),
(2, 'Air Jordan 1 Mid SE', 'Las Air Jordan 1 Mid SE mantienen el atractiv', 3200, 3, 'Tenis Jordan 1', 1),
(3, 'Jordan Jumpman', 'Póntelas y en marcha. Disfruta de la amortiguación de espuma gruesa y ligera para los días de playa o para salir después del partido.', 1149, 2, 'Sandalias', 0),
(4, 'Nike Dunk Low', 'Este ícono del básquetbol de los 80, creado para la cancha y adaptado al estilo urbano, vuelve con detalles clásicos y un estilo de básquetbol retro.', 2999, 7, 'Tenis dunk', 1),
(5, 'Air Jordan 1 High OG Black & White', 'Clásico, original, remasterizado. Este AJ1 en blanco y negro, que está listo para salir a las calles con sofisticación', 4499, 12, 'Tenis Jordan 1', 1),
(6, 'Jordan 1 Retro Low Black Phantom', 'Travis Scott, artista de hip-hop, fundador de una casa discográfica y nativo de Houston, Texas, continúa dejando su impronta en el AJ1 Low, al mantenerlo clásico y fiel a su amor p', 3800, 4, 'Tenis Jordan 1', 1),
(7, 'Jumpman Jack TR', 'La arena está completamente abarrotada. La gente te llama. El escenario está listo. Luces, cámara, Jack Diseñados para seguir las especificaciones exactas y el estilo exclusivo d', 4500, 2, 'Tenis Jumpman Jack', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_talla`
--

CREATE TABLE `producto_talla` (
  `id_producto_talla` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_talla` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_talla`
--

INSERT INTO `producto_talla` (`id_producto_talla`, `id_producto`, `id_talla`) VALUES
(1, 1, 3),
(2, 1, 4),
(3, 1, 5),
(4, 1, 6),
(5, 2, 4),
(6, 2, 5),
(7, 2, 6),
(8, 2, 7),
(9, 3, 5),
(10, 3, 6),
(11, 3, 7),
(12, 4, 3),
(13, 4, 4),
(14, 4, 5),
(15, 4, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_proveedor` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `telefono` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `cantidadReestock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_proveedor`, `direccion`, `telefono`, `status`, `cantidadReestock`) VALUES
(1, 'TenisVB', 'Corregidora 54', 332522323, 1, 100),
(2, 'TenisJD', 'islas antillas 55', 552522323, 1, 50),
(3, 'TenisDK', 'hidalgo 233', 332522323, 0, 0),
(4, 'Nike', 'Zapopan in C.C. Gran Terraza Belenes si', 333365872, 1, 120);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id_talla` int(11) NOT NULL,
  `talla` varchar(30) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id_talla`, `talla`, `status`) VALUES
(1, 'NA', 1),
(2, '11CM', 1),
(3, '11.5CM', 1),
(4, '12CM', 1),
(5, '12.5CM', 1),
(6, '13CM', 1),
(7, '13.5CM', 1),
(8, '14CM', 1),
(9, '14.5CM', 1),
(10, '15CM', 1),
(11, '15.5CM', 1),
(12, '16CM', 1),
(13, '16.5CM', 1),
(14, '17CM', 1),
(15, '17.5CM', 1),
(16, '18CM', 1),
(17, '18.5CM', 1),
(18, '19CM', 1),
(19, '19.5CM', 1),
(20, '20CM', 1),
(21, '20.5CM', 1),
(22, '21CM', 1),
(23, '21.5CM', 1),
(24, '22CM', 1),
(25, '22.5CM', 1),
(26, '23CM', 1),
(27, '23.5CM', 1),
(28, '24CM', 1),
(29, '24.5CM', 1),
(30, '25CM', 1),
(31, '25.5CM', 1),
(32, '26CM', 1),
(33, '26.5CM', 1),
(34, '27CM', 1),
(35, '27.5CM', 1),
(36, '28CM', 1),
(37, '28.5CM', 1),
(38, '29CM', 1),
(39, '29.5CM', 1),
(40, '30CM', 1),
(41, '30.5CM', 1),
(42, '31CM', 1),
(43, '5A', 1),
(44, '6A', 1),
(45, '7A', 1),
(46, '8A', 1),
(47, '9A', 1),
(48, '10A', 1),
(49, '11A', 1),
(50, '12A', 1),
(51, '13A', 1),
(52, '14A', 1),
(53, '3M', 1),
(54, '6M', 1),
(55, '9M', 1),
(56, '12M', 1),
(57, '15M', 1),
(58, '18M', 1),
(59, '21M', 1),
(60, '24M', 1),
(61, '2T', 1),
(62, '3T', 1),
(63, '4T', 1),
(64, '5T', 1),
(65, '7 / 8', 1),
(66, '9 / 10', 1),
(67, '11 / 12', 1),
(68, '13 / 14', 1),
(69, 'RN', 1),
(70, 'XS', 1),
(71, 'S', 1),
(72, 'M', 1),
(73, 'L', 1),
(74, 'XL', 1),
(75, 'XXL', 1),
(76, 'UNI', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `total_venta` float NOT NULL,
  `status_venta` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `id_empleado`, `fecha`, `total_venta`, `status_venta`) VALUES
(1, 3, 4, '2024-03-20', 13347, 1),
(11, 1, 1, '2024-05-08', 21798, 1),
(12, 2, 2, '2024-05-09', 9198, 1),
(13, 1, 1, '2024-05-09', 8697, 1),
(14, 4, 3, '2024-05-09', 5998, 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_compras`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_compras` (
`id_compra` int(11)
,`id_proveedor` int(11)
,`id_empleado` int(11)
,`fecha` date
,`total_compra` float
,`status_compra` int(11)
,`nombre_proveedor` varchar(45)
,`direccion` varchar(45)
,`telefono` int(11)
,`cantidadReestock` int(11)
,`nombre_empleado` varchar(80)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_ventas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_ventas` (
`id_venta` int(11)
,`id_cliente` int(11)
,`id_empleado` int(11)
,`fecha` date
,`total_venta` float
,`nombre_cliente` varchar(80)
,`email` varchar(45)
,`direccion` varchar(45)
,`nombre_empleado` varchar(80)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `detallescompras`
--
DROP TABLE IF EXISTS `detallescompras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detallescompras`  AS SELECT `cmp`.`id_compra` AS `id_compra`, `dc`.`id_detalle_compra` AS `id_detalle_compra`, `dc`.`id_producto` AS `id_producto`, `p`.`nombre_producto` AS `nombre_producto`, `dc`.`id_producto_talla` AS `id_producto_talla`, `pt`.`id_talla` AS `id_talla`, `t`.`talla` AS `talla`, `dc`.`cantidad` AS `cantidad`, `dc`.`precio_unitario` AS `precio_unitario`, `dc`.`subtotal` AS `subtotal` FROM ((((`compras` `cmp` join `detalles_compras` `dc` on(`cmp`.`id_compra` = `dc`.`id_compra`)) join `producto_talla` `pt` on(`dc`.`id_producto_talla` = `pt`.`id_producto_talla`)) join `tallas` `t` on(`pt`.`id_talla` = `t`.`id_talla`)) join `productos` `p` on(`dc`.`id_producto` = `p`.`id_producto`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `detallesventa`
--
DROP TABLE IF EXISTS `detallesventa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detallesventa`  AS SELECT `v`.`id_venta` AS `id_venta`, `dv`.`id_detalle_venta` AS `id_detalle_venta`, `dv`.`id_producto` AS `id_producto`, `p`.`nombre_producto` AS `nombre_producto`, `dv`.`id_producto_talla` AS `id_producto_talla`, `pt`.`id_talla` AS `id_talla`, `t`.`talla` AS `talla`, `dv`.`cantidad` AS `cantidad`, `dv`.`precio_unitario` AS `precio_unitario`, `dv`.`subtotal` AS `subtotal` FROM ((((`ventas` `v` join `detalles_ventas` `dv` on(`v`.`id_venta` = `dv`.`id_venta`)) join `producto_talla` `pt` on(`dv`.`id_producto_talla` = `pt`.`id_producto_talla`)) join `tallas` `t` on(`pt`.`id_talla` = `t`.`id_talla`)) join `productos` `p` on(`dv`.`id_producto` = `p`.`id_producto`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `informacioncliente`
--
DROP TABLE IF EXISTS `informacioncliente`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `informacioncliente`  AS SELECT `c`.`id_cliente` AS `id_cliente`, `c`.`nombre_cliente` AS `nombre_cliente`, `c`.`email` AS `email`, `c`.`direccion` AS `direccion`, count(`v`.`id_venta`) AS `total_ventas_cliente`, sum(`v`.`total_venta`) AS `total_venta_status_1` FROM (`clientes` `c` left join `ventas` `v` on(`c`.`id_cliente` = `v`.`id_cliente` and `v`.`status_venta` = 1)) GROUP BY `c`.`nombre_cliente`, `c`.`email`, `c`.`direccion` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_compras`
--
DROP TABLE IF EXISTS `vista_compras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_compras`  AS SELECT `cmp`.`id_compra` AS `id_compra`, `cmp`.`id_proveedor` AS `id_proveedor`, `cmp`.`id_empleado` AS `id_empleado`, `cmp`.`fecha` AS `fecha`, `cmp`.`total_compra` AS `total_compra`, `cmp`.`status_compra` AS `status_compra`, `prove`.`nombre_proveedor` AS `nombre_proveedor`, `prove`.`direccion` AS `direccion`, `prove`.`telefono` AS `telefono`, `prove`.`cantidadReestock` AS `cantidadReestock`, `empl`.`nombre_empleado` AS `nombre_empleado` FROM ((`compras` `cmp` join `proveedores` `prove` on(`cmp`.`id_proveedor` = `prove`.`id_proveedor`)) join `empleados` `empl` on(`cmp`.`id_empleado` = `empl`.`id_empleado`)) ORDER BY `cmp`.`fecha` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_ventas`
--
DROP TABLE IF EXISTS `vista_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ventas`  AS SELECT `v`.`id_venta` AS `id_venta`, `v`.`id_cliente` AS `id_cliente`, `v`.`id_empleado` AS `id_empleado`, `v`.`fecha` AS `fecha`, `v`.`total_venta` AS `total_venta`, `c`.`nombre_cliente` AS `nombre_cliente`, `c`.`email` AS `email`, `c`.`direccion` AS `direccion`, `e`.`nombre_empleado` AS `nombre_empleado` FROM ((`ventas` `v` join `clientes` `c` on(`v`.`id_cliente` = `c`.`id_cliente`)) join `empleados` `e` on(`v`.`id_empleado` = `e`.`id_empleado`)) ORDER BY `v`.`fecha` DESC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`);

--
-- Indices de la tabla `detalles_compras`
--
ALTER TABLE `detalles_compras`
  ADD PRIMARY KEY (`id_detalle_compra`);

--
-- Indices de la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  ADD PRIMARY KEY (`id_detalle_venta`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `producto_talla`
--
ALTER TABLE `producto_talla`
  ADD PRIMARY KEY (`id_producto_talla`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id_talla`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalles_compras`
--
ALTER TABLE `detalles_compras`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `producto_talla`
--
ALTER TABLE `producto_talla`
  MODIFY `id_producto_talla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id_talla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
