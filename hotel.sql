-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-02-2025 a las 21:16:54
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
-- Base de datos: `hotel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_reserva`
--

CREATE TABLE `detalles_reserva` (
  `id` int(11) NOT NULL,
  `reserva_id` int(11) DEFAULT NULL,
  `elemento_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_reserva`
--

INSERT INTO `detalles_reserva` (`id`, `reserva_id`, `elemento_id`, `tipo`) VALUES
(41, 17, 25, 'habitacion'),
(42, 17, 26, 'habitacion'),
(44, 19, 18, 'habitacion'),
(45, 19, 19, 'habitacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elementos`
--

CREATE TABLE `elementos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tipo` enum('habitacion','servicio') NOT NULL,
  `creado_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` varchar(20) DEFAULT NULL,
  `fecha_ocupacion` date DEFAULT NULL,
  `fecha_liberacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elementos`
--

INSERT INTO `elementos` (`id`, `codigo`, `nombre`, `descripcion`, `precio`, `tipo`, `creado_at`, `updated_at`, `estado`, `fecha_ocupacion`, `fecha_liberacion`) VALUES
(16, 'S0001', 'CAMIONETA USADA', 'Camioneta de Renta', 500.00, 'servicio', '2025-02-17 17:26:09', '2025-02-17 17:26:09', 'activo', NULL, NULL),
(17, 'H0001', '101 (Habitacion Cuadruple)', 'Master Suite', 5000.00, 'habitacion', '2025-02-17 17:29:14', '2025-02-17 17:29:14', 'disponible', NULL, NULL),
(18, 'H0002', '102 (doble)', 'Habitacion Estandar', 2000.00, 'habitacion', '2025-02-17 17:30:20', '2025-02-18 19:42:00', 'disponible', '2025-02-19', '2025-02-21'),
(19, 'H0003', '103 (doble)', 'Habitacion Estandar', 2000.00, 'habitacion', '2025-02-17 17:31:29', '2025-02-18 19:42:00', 'disponible', '2025-02-19', '2025-02-21'),
(20, 'H0004', '104 (doble)', 'Habitación Estándar', 2000.00, 'habitacion', '2025-02-17 17:32:05', '2025-02-17 17:32:05', 'disponible', NULL, NULL),
(21, 'H0005', '105 (doble)', 'Habitación Estándar', 2000.00, 'habitacion', '2025-02-17 17:33:03', '2025-02-17 17:33:03', 'disponible', NULL, NULL),
(22, 'H0006', '106 (doble)', 'Habitación Estandar', 2000.00, 'habitacion', '2025-02-17 17:58:34', '2025-02-17 18:12:10', 'disponible', NULL, NULL),
(23, 'H0007', '107 (Sencillo)', 'Bungalow Chico', 2000.00, 'habitacion', '2025-02-17 17:59:52', '2025-02-17 17:59:52', 'disponible', NULL, NULL),
(24, 'H0008', '108', 'Bungalow Mediano', 4000.00, 'habitacion', '2025-02-17 18:09:48', '2025-02-18 17:04:23', 'disponible', '2025-02-20', '2025-02-22'),
(25, 'H0009', '109 (Sencillo)', 'Bungalow Chico', 2000.00, 'habitacion', '2025-02-17 18:10:14', '2025-02-17 19:02:19', 'disponible', '2025-02-20', '2025-02-21'),
(26, 'H0010', '110 (Sencillo)', 'Bungalow Chico', 2000.00, 'habitacion', '2025-02-17 18:12:48', '2025-02-17 19:02:19', 'disponible', '2025-02-20', '2025-02-21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `huespedes`
--

CREATE TABLE `huespedes` (
  `id` int(11) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `tipo_huesped` enum('persona','empresa') NOT NULL DEFAULT 'persona',
  `nombre_empresa` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `huespedes`
--

INSERT INTO `huespedes` (`id`, `rfc`, `nombre`, `telefono`, `correo`, `tipo_huesped`, `nombre_empresa`, `logo`) VALUES
(1, 'CUHA810117EN1', 'Ana Judith Cruz Hernandez', '3141002619', 'Ana12@gmail.com', 'persona', NULL, NULL),
(2, 'AJKSL9038238', 'Ana', '3331285991', 'nnadiamichelle@gmail.com', 'persona', NULL, NULL),
(3, 'ANCKOLSW12449', 'Laura Adaia', '312578895', 'laura23@hotmail.com', 'persona', NULL, NULL),
(10, 'PJIMIN1310953', 'Park Jimin', '8257019882', 'PJIMIN16@gmail.com', 'persona', NULL, NULL),
(11, 'SSA23723FD', 'SSA', '3331285991', 'nanananssdjsq@gmail.com', 'empresa', 'SSA', 'uploads/logo.jpg'),
(12, 'ADIKSOAÑL', 'Aduanas', '312578895', 'ADUANAS12@gmail.com', 'empresa', 'Aduanas', 'logos/67a553d3c198a.jpg'),
(18, 'OCH20233', 'Heriberto Ochoa', '3145562227', 'heribertoochoa12@gmail.com', 'persona', NULL, NULL),
(19, 'MCM345542', 'Mauricio Chavez Mancilla', '314778962', 'mmachave12@gmail.com', 'persona', NULL, NULL),
(20, 'CAD541585', 'Delia Amairani Chavez', '3141221495', 'delia1@gmail.com', 'persona', NULL, NULL),
(21, 'NMNC52540', 'Nadia Michelle', '3141221495', 'nnava1@ucol.mx', 'persona', NULL, NULL),
(23, 'NMNCjjjj', '', '3141221452', 'nnava1@gmail.com', 'empresa', 'CHEFS FAFS', NULL),
(24, 'CAD541589', 'Nadia Michelle', '3141221495', 'nnava1@ucol.mx', 'persona', NULL, NULL),
(25, 'ALDFJK4128', 'Alejandra Guzmán', '3141556876', NULL, 'persona', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos`
--

CREATE TABLE `recibos` (
  `id` int(11) NOT NULL,
  `id_huesped` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_pagar` decimal(10,2) NOT NULL,
  `tipo_pago` enum('tarjeta','transferencia','efectivo') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(20) DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recibos`
--

INSERT INTO `recibos` (`id`, `id_huesped`, `check_in`, `check_out`, `total_pagar`, `tipo_pago`, `created_at`, `estado`) VALUES
(17, 1, '2025-02-20', '2025-02-22', 9280.00, 'efectivo', '2025-02-17 19:02:19', 'cancelada'),
(19, 25, '2025-02-19', '2025-02-21', 4640.00, 'transferencia', '2025-02-18 19:42:00', 'activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `contrasena`, `rol`, `correo`, `telefono`) VALUES
(1, 'Nadia', '$2y$10$Oj/Jj1WaHSVqv8mdUqOyY.R3Kepr2LrMrUhwheTBvnzGv8yyemLhi', 'usuario', 'nnava1@ucol.mx', '3141217379'),
(2, 'Aylinrecepcion', '$2y$10$MKMzfu8uH9716SAyLuYlFeeEdaKOdisaba/SA.URYfJ/Gs1cpNKc2', 'usuario', 'aylin16@gmail.com', '3141002618');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalles_reserva`
--
ALTER TABLE `detalles_reserva`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reserva_id` (`reserva_id`),
  ADD KEY `detalles_reserva_ibfk_2` (`elemento_id`);

--
-- Indices de la tabla `elementos`
--
ALTER TABLE `elementos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `huespedes`
--
ALTER TABLE `huespedes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfc` (`rfc`);

--
-- Indices de la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_huesped` (`id_huesped`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalles_reserva`
--
ALTER TABLE `detalles_reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `elementos`
--
ALTER TABLE `elementos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `huespedes`
--
ALTER TABLE `huespedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `recibos`
--
ALTER TABLE `recibos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_reserva`
--
ALTER TABLE `detalles_reserva`
  ADD CONSTRAINT `detalles_reserva_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `recibos` (`id`),
  ADD CONSTRAINT `detalles_reserva_ibfk_2` FOREIGN KEY (`elemento_id`) REFERENCES `elementos` (`id`);

--
-- Filtros para la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD CONSTRAINT `recibos_ibfk_1` FOREIGN KEY (`id_huesped`) REFERENCES `huespedes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
