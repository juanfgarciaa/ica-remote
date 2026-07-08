-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-05-2026 a las 23:22:21
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
-- Base de datos: `ica_remote`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peticiones`
--

CREATE TABLE `peticiones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('pendiente','completada','rechazada') DEFAULT 'pendiente',
  `fecha_peticion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peticiones`
--

INSERT INTO `peticiones` (`id`, `id_usuario`, `descripcion`, `estado`, `fecha_peticion`, `fecha_modificacion`) VALUES
(1, 2, 'Hola buenas, mi ordenador va muy lento desde hace tiempo y ya no se que hacer, me han dicho que igual tiene muchos archivos basura acumulados pero no me atrevo a borrar nada por si me cargo algo importante.', 'pendiente', '2026-05-15 18:48:26', '2026-05-15 23:18:09'),
(4, 3, 'Hola, tengo una grafica Nvidia pero la verdad es que no se si esta bien puesta, los juegos me van regular y un amigo me dijo que se podia configurar para ir mejor.', 'completada', '2026-05-15 18:52:37', '2026-05-15 23:18:09'),
(5, 4, 'Buenas tardes, estoy pensando en cambiar de ordenador pero no se mucho de componentes y no quiero gastar de mas. ¿Me podriais aconsejar que es lo que necesito de verdad?', 'completada', '2026-05-15 18:52:37', '2026-05-15 23:18:09'),
(6, 5, 'Hola, tengo miedo de pillar un virus porque mis hijos usan el ordenador para todo y bajan cosas raras. Queria que me instalarais algun antivirus bueno pero que no sea muy complicado de usar.', 'pendiente', '2026-05-15 18:52:37', '2026-05-15 23:18:09'),
(7, 6, 'Buenas, tengo un hermano pequeño que conecta cualquier cosa al ordenador y me da un poco de miedo que algun dia me meta algo malo. Me han dicho que se pueden bloquear los puertos USB.', 'pendiente', '2026-05-15 18:52:37', '2026-05-15 23:18:09'),
(8, 6, 'Hola, no me fio mucho de como viene Windows por defecto y queria saber si se pueden cambiar algunos ajustes para que sea mas seguro, pero sin liarla tampoco.', 'rechazada', '2026-05-15 18:52:37', '2026-05-15 23:18:09'),
(9, 7, 'Buenas, queria que me echarais una mano con un par de cosas del ordenador, va lento y los juegos no me van todo lo bien que deberian, no se muy bien por donde empezar.', 'completada', '2026-05-15 18:52:37', '2026-05-15 23:18:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peticion_servicios`
--

CREATE TABLE `peticion_servicios` (
  `id` int(11) NOT NULL,
  `id_peticion` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peticion_servicios`
--

INSERT INTO `peticion_servicios` (`id`, `id_peticion`, `id_servicio`) VALUES
(4, 4, 3),
(5, 5, 5),
(6, 5, 4),
(7, 6, 6),
(8, 7, 2),
(9, 8, 1),
(10, 9, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`) VALUES
(1, 'Cambiar las directivas de seguridad'),
(2, 'Bloqueo de puertos de USB'),
(3, 'Optimizar los gráficos de Nvidia'),
(4, 'Asesoría de componentes de ordenador'),
(5, 'Instalación de aplicaciones'),
(6, 'Instalación de antivirus'),
(7, 'Limpiador de archivos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `numero_anydesk` varchar(255) NOT NULL,
  `rol` enum('admin','user') DEFAULT 'user',
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `numero_anydesk`, `rol`, `fecha_registro`) VALUES
(1, 'Administrador Demo', 'admin@demo.local', '$2y$10$abcdefghijklmnopqrstuv0123456789ABCDEFGHIJKLMNOPQRSTUV', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'admin', '2026-05-15 18:48:26'),
(2, 'Usuario Demo Uno', 'usuario1@demo.local', '$2y$10$abcdefghijklmnopqrstuv0123456789ABCDEFGHIJKLMNOPQRSTUV', 'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB', 'user', '2026-05-15 18:48:26'),
(3, 'Usuario Demo Dos', 'usuario2@demo.local', '$2y$10$abcdefghijklmnopqrstuv0123456789ABCDEFGHIJKLMNOPQRSTUV', 'CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC', 'user', '2026-05-15 18:48:26'),
(4, 'Usuario Demo Tres', 'usuario3@demo.local', '$2y$10$abcdefghijklmnopqrstuv0123456789ABCDEFGHIJKLMNOPQRSTUV', 'DDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD', 'user', '2026-05-15 18:48:26'),
(5, 'Usuario Demo Cuatro', 'usuario4@demo.local', '$2y$10$abcdefghijklmnopqrstuv0123456789ABCDEFGHIJKLMNOPQRSTUV', 'EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE', 'user', '2026-05-15 18:48:26'),
(6, 'Usuario Demo Cinco', 'usuario5@demo.local', '$2y$10$abcdefghijklmnopqrstuv0123456789ABCDEFGHIJKLMNOPQRSTUV', 'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF', 'user', '2026-05-15 18:48:26'),
(7, 'Usuario Demo Seis', 'usuario6@demo.local', '$2y$10$abcdefghijklmnopqrstuv0123456789ABCDEFGHIJKLMNOPQRSTUV', 'GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG', 'user', '2026-05-15 18:48:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `peticiones`
--
ALTER TABLE `peticiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `peticion_servicios`
--
ALTER TABLE `peticion_servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_peticion` (`id_peticion`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `peticiones`
--
ALTER TABLE `peticiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `peticion_servicios`
--
ALTER TABLE `peticion_servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `peticiones`
--
ALTER TABLE `peticiones`
  ADD CONSTRAINT `peticiones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `peticion_servicios`
--
ALTER TABLE `peticion_servicios`
  ADD CONSTRAINT `peticion_servicios_ibfk_1` FOREIGN KEY (`id_peticion`) REFERENCES `peticiones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peticion_servicios_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
