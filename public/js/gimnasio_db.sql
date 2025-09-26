-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2025 at 12:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gimnasio_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas_gimnasio`
--

CREATE TABLE `areas_gimnasio` (
  `id` int(11) NOT NULL,
  `nombre_area` varchar(100) NOT NULL,
  `ubicacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `areas_gimnasio`
--

INSERT INTO `areas_gimnasio` (`id`, `nombre_area`, `ubicacion`) VALUES
(1, 'Recepción', 'Planta Baja'),
(2, 'Área de Fuerza', 'Planta Baja'),
(3, 'Cardio Funcionales y Spinning', 'Planta Alta');

-- --------------------------------------------------------

--
-- Table structure for table `entrenadores`
--

CREATE TABLE `entrenadores` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(200) NOT NULL,
  `numero_cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `especialidad` varchar(150) DEFAULT NULL,
  `costo_mensual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `ruta_foto` varchar(255) DEFAULT 'uploads/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entrenadores`
--

INSERT INTO `entrenadores` (`id`, `nombre_completo`, `numero_cedula`, `telefono`, `email`, `especialidad`, `costo_mensual`, `estatus`, `ruta_foto`) VALUES
(1, 'gabriel ramos', '125886535', '04143665986', 'gabrielramos14@gmail.com', 'spining', 25.00, 'activo', 'uploads/entrenador-689242a88fdbf-default.png');

-- --------------------------------------------------------

--
-- Table structure for table `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `codigo_item` varchar(50) NOT NULL,
  `nombre_item` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('Tienda','Operaciones') NOT NULL COMMENT 'Distingue entre artículos de venta y equipos del gym.',
  `departamento` varchar(100) NOT NULL COMMENT 'Categoría específica (Ej: Máquinas, Suplementos).',
  `stock` int(11) DEFAULT 1,
  `precio` decimal(10,2) DEFAULT 0.00 COMMENT 'Precio de venta para Tienda, costo para Operaciones.',
  `id_area` int(11) DEFAULT NULL COMMENT 'ID del área donde se ubica el equipo.',
  `estado` varchar(50) DEFAULT 'Operativo' COMMENT 'Ej: Operativo, Mantenimiento, Averiado, Para la venta.',
  `fecha_adquisicion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventario`
--

INSERT INTO `inventario` (`id`, `codigo_item`, `nombre_item`, `descripcion`, `tipo`, `departamento`, `stock`, `precio`, `id_area`, `estado`, `fecha_adquisicion`) VALUES
(3, 'IG-SUP-1754420173', 'CREATINA MONOHIDRATADA', 'creatina de 60 servicios', 'Tienda', 'Suplementos', 15, 60.00, 1, 'Para la venta', NULL),
(4, 'IG-SUP-1754496467', 'proteina', 'proteina', 'Tienda', 'Suplementos', 12, 50.00, 1, 'Para la venta', NULL),
(5, 'IG-SUP-1754497729', 'CREATINA MONOHIDRATADA', '5015151', 'Tienda', 'Suplementos', 2, 30.00, 1, 'Para la venta', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `miembros`
--

CREATE TABLE `miembros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `numero_cedula` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `ruta_foto` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estatus` enum('activo','inactivo','vetado') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miembros`
--

INSERT INTO `miembros` (`id`, `nombre`, `telefono`, `numero_cedula`, `fecha_nacimiento`, `ruta_foto`, `fecha_registro`, `estatus`) VALUES
(2, 'Adrian Bello', '04129451597', '19674244', '1991-08-03', 'uploads/miembro-68920f19afaa0-default.png', '2025-08-05 02:03:03', 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `miembro_suscripciones`
--

CREATE TABLE `miembro_suscripciones` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `promocion_id` int(11) DEFAULT NULL,
  `entrenador_id` int(11) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `referencia_pago` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miembro_suscripciones`
--

INSERT INTO `miembro_suscripciones` (`id`, `miembro_id`, `plan_id`, `promocion_id`, `entrenador_id`, `fecha_inicio`, `fecha_fin`, `monto_pagado`, `metodo_pago`, `referencia_pago`, `fecha_registro`) VALUES
(3, 2, 1, 1, 1, '2025-08-07', '2025-09-06', 50.00, 'Punto de Venta (Bs.)', '19181', '2025-08-06 22:22:37');

-- --------------------------------------------------------

--
-- Table structure for table `planes`
--

CREATE TABLE `planes` (
  `id` int(11) NOT NULL,
  `nombre_plan` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `duracion_dias` int(11) NOT NULL COMMENT 'Duración del plan en días (ej: 30 para mensual)',
  `estatus` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planes`
--

INSERT INTO `planes` (`id`, `nombre_plan`, `descripcion`, `precio_base`, `duracion_dias`, `estatus`) VALUES
(1, 'Mensualidad Regular', 'Acceso completo al gimnasio por 30 días.', 35.00, 30, 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `promociones`
--

CREATE TABLE `promociones` (
  `id` int(11) NOT NULL,
  `nombre_promo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo_descuento` enum('porcentaje','precio_fijo') NOT NULL,
  `valor_descuento` decimal(10,2) NOT NULL,
  `aplica_a` enum('suscripcion','producto') NOT NULL DEFAULT 'suscripcion',
  `condicion_personas` int(11) DEFAULT 1 COMMENT 'Num de personas para que aplique (ej: 2 para promo de amigos)',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estatus` enum('activa','inactiva') NOT NULL DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promociones`
--

INSERT INTO `promociones` (`id`, `nombre_promo`, `descripcion`, `tipo_descuento`, `valor_descuento`, `aplica_a`, `condicion_personas`, `fecha_inicio`, `fecha_fin`, `estatus`) VALUES
(1, 'Promo Apertura Amigos', 'Trae un amigo y ambos pagan un precio especial por su primer mes.', 'precio_fijo', 25.00, 'suscripcion', 2, NULL, NULL, 'activa');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('admin','supervisor','recepcionista') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `cedula`, `telefono`, `usuario`, `clave`, `rol`) VALUES
(1, 'Adrian Bello', 'V19674244', '412-9451597', 'admin', '$2y$10$pVaW9nBkXrwYFe5/SgtpluxU04DpQLsW/6AjrkKZ4CO0bmhDW57bS', 'admin'),
(2, 'ronal ojeda', '13131313', '04141515663', 'rojeda', '$2y$10$EwekDdIiIuGRIXGrP1HqBu1jdadzDoDkYDfPRW5zP4CecwoSbTeKy', 'supervisor'),
(3, 'kali rondon', '31087369', '04122870983', 'krondon', '$2y$10$Z4hxfh4Jip4499R/NYzSiOLZ4AFCDzZeHmhANU.lhs.7UVs5jVIi.', 'recepcionista');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_venta` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `referencia_pago` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venta_detalles`
--

CREATE TABLE `venta_detalles` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `inventario_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas_gimnasio`
--
ALTER TABLE `areas_gimnasio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_area` (`nombre_area`);

--
-- Indexes for table `entrenadores`
--
ALTER TABLE `entrenadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_cedula` (`numero_cedula`);

--
-- Indexes for table `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_item` (`codigo_item`),
  ADD KEY `fk_area_inventario` (`id_area`);

--
-- Indexes for table `miembros`
--
ALTER TABLE `miembros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_cedula` (`numero_cedula`);

--
-- Indexes for table `miembro_suscripciones`
--
ALTER TABLE `miembro_suscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indexes for table `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_miembro_venta` (`miembro_id`);

--
-- Indexes for table `venta_detalles`
--
ALTER TABLE `venta_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_venta_detalle` (`venta_id`),
  ADD KEY `fk_inventario_detalle` (`inventario_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas_gimnasio`
--
ALTER TABLE `areas_gimnasio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `entrenadores`
--
ALTER TABLE `entrenadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `miembro_suscripciones`
--
ALTER TABLE `miembro_suscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `venta_detalles`
--
ALTER TABLE `venta_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_area_inventario` FOREIGN KEY (`id_area`) REFERENCES `areas_gimnasio` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `miembro_suscripciones`
--
ALTER TABLE `miembro_suscripciones`
  ADD CONSTRAINT `miembro_suscripciones_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_miembro_venta` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `venta_detalles`
--
ALTER TABLE `venta_detalles`
  ADD CONSTRAINT `fk_inventario_detalle` FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venta_detalle` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
