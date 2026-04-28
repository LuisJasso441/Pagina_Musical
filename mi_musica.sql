-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-04-2026 a las 17:33:41
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
-- Base de datos: `mi_musica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albumes`
--

CREATE TABLE `albumes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `anio` int(11) DEFAULT NULL,
  `artista_id` int(11) NOT NULL,
  `es_favorito` tinyint(1) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `albumes`
--

INSERT INTO `albumes` (`id`, `titulo`, `anio`, `artista_id`, `es_favorito`, `creado_en`, `imagen`) VALUES
(1, 'OK Computer', 1997, 1, 1, '2026-04-22 20:50:07', 'album_1776978486_4360.jpg'),
(2, 'Discovery', 2001, 2, 0, '2026-04-22 20:50:07', 'album_1776978471_3200.jpg'),
(6, 'Reanimation', 2002, 6, 0, '2026-04-23 20:36:39', 'album_1776976599_7698.jpg'),
(7, '3MEN2 KBRN', NULL, 8, 0, '2026-04-24 19:55:02', 'album_1777060499_4452.jpg'),
(8, 'Sauce Boyz 2', NULL, 8, 0, '2026-04-24 19:55:04', 'album_1777060503_5357.jpg'),
(9, 'Sauce Boyz', NULL, 8, 0, '2026-04-24 19:55:06', 'album_1777060505_7247.jpg'),
(10, 'SEN2 KBRN VOL. 2', NULL, 8, 0, '2026-04-24 19:55:07', 'album_1777060506_1209.jpg'),
(11, 'Sol María', NULL, 8, 0, '2026-04-24 19:55:09', 'album_1777060508_2758.jpg'),
(12, 'Monarca', NULL, 8, 0, '2026-04-24 19:55:13', 'album_1777060509_8708.jpg'),
(13, 'DON KBRN', 0, 8, 1, '2026-04-24 19:55:16', 'album_1777060513_8786.jpg'),
(14, 'SEN2 KBRN VOL. 1', NULL, 8, 0, '2026-04-24 19:55:20', 'album_1777060519_2632.jpg'),
(15, 'Sauce Boyz Care Package', NULL, 8, 0, '2026-04-24 19:55:33', 'album_1777060521_5873.jpg'),
(16, 'Singles', NULL, 9, 0, '2026-04-24 20:51:13', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artistas`
--

CREATE TABLE `artistas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `es_favorito` tinyint(1) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `artistas`
--

INSERT INTO `artistas` (`id`, `nombre`, `genero`, `es_favorito`, `creado_en`, `imagen`) VALUES
(1, 'Radiohead', 'Rock Alternativo', 0, '2026-04-22 20:49:56', NULL),
(2, 'Daft Punk', 'Electrónica', 0, '2026-04-22 20:49:56', NULL),
(3, 'Akapellah', 'Rap', 1, '2026-04-22 22:12:23', 'artista_1777063260_6527.jpg'),
(4, 'Kase.O', 'Rap', 1, '2026-04-22 22:32:39', NULL),
(6, 'Linkin Park', 'Rock Alternativo', 1, '2026-04-23 20:36:09', NULL),
(8, 'Eladio Carrion', 'trap', 0, '2026-04-24 19:54:31', NULL),
(9, 'Duki', 'trap', 0, '2026-04-24 20:51:13', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canciones`
--

CREATE TABLE `canciones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `duracion` varchar(10) DEFAULT NULL,
  `album_id` int(11) NOT NULL,
  `es_favorito` tinyint(1) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `canciones`
--

INSERT INTO `canciones` (`id`, `titulo`, `duracion`, `album_id`, `es_favorito`, `creado_en`) VALUES
(1, 'Paranoid Android', '6:23', 1, 1, '2026-04-22 20:50:15'),
(2, 'One More Time', '5:20', 2, 1, '2026-04-22 20:50:15'),
(3, 'Padre Tiempo', '3:39', 7, 0, '2026-04-24 19:55:02'),
(4, 'Gladiador - Remix', '3:17', 7, 0, '2026-04-24 19:55:02'),
(5, 'El Hokage', '2:05', 7, 0, '2026-04-24 19:55:02'),
(6, 'Mbappé - Remix', '3:57', 7, 0, '2026-04-24 19:55:02'),
(7, 'Si Salimos', '3:21', 7, 0, '2026-04-24 19:55:02'),
(8, '¿Qué Carajos Quieres Tú Ahora?', '2:35', 7, 0, '2026-04-24 19:55:02'),
(9, 'Cuevita', '2:40', 7, 0, '2026-04-24 19:55:02'),
(10, 'Coco Chanel', '3:28', 7, 0, '2026-04-24 19:55:02'),
(11, 'Si La Calle Llama - Remix', '4:00', 7, 0, '2026-04-24 19:55:02'),
(12, 'Peso a Peso', '3:37', 7, 0, '2026-04-24 19:55:02'),
(13, 'Mala Mía Otra Vez', '2:47', 7, 0, '2026-04-24 19:55:02'),
(14, 'Friends - Remix', '4:08', 7, 0, '2026-04-24 19:55:02'),
(15, 'Quizás, Tal Vez', '2:27', 7, 0, '2026-04-24 19:55:02'),
(16, 'M3', '3:12', 7, 0, '2026-04-24 19:55:02'),
(17, 'Betty', '3:22', 7, 0, '2026-04-24 19:55:02'),
(18, 'Haciendo Dinero', '2:27', 7, 0, '2026-04-24 19:55:02'),
(19, '¿Como? (Skit)', '0:36', 7, 0, '2026-04-24 19:55:02'),
(20, 'Air France', '3:06', 7, 0, '2026-04-24 19:55:02'),
(21, 'Par de Tenis', '3:01', 8, 0, '2026-04-24 19:55:04'),
(22, 'Claro Cristal', '2:41', 8, 0, '2026-04-24 19:55:04'),
(23, 'No Te Deseo el Mal', '3:52', 8, 0, '2026-04-24 19:55:04'),
(24, 'Flores en Anónimo', '2:50', 8, 0, '2026-04-24 19:55:04'),
(25, 'Fuego', '2:35', 8, 0, '2026-04-24 19:55:04'),
(26, 'Miradas Raras', '3:15', 8, 0, '2026-04-24 19:55:04'),
(27, 'Me Gustas Natural', '2:50', 8, 0, '2026-04-24 19:55:04'),
(28, 'Quienes Son Ustedes', '2:20', 8, 0, '2026-04-24 19:55:04'),
(29, 'Alejarme de Ti', '3:53', 8, 0, '2026-04-24 19:55:04'),
(30, 'Gastar', '2:29', 8, 0, '2026-04-24 19:55:04'),
(31, 'Hola Como Vas', '3:18', 8, 0, '2026-04-24 19:55:04'),
(32, 'Sin Frenos', '3:31', 8, 0, '2026-04-24 19:55:04'),
(33, 'Socio', '4:13', 8, 0, '2026-04-24 19:55:04'),
(34, 'Jóvenes Millonarios', '3:02', 8, 0, '2026-04-24 19:55:04'),
(35, 'No Me Importa un Carajo', '3:10', 8, 0, '2026-04-24 19:55:04'),
(36, 'Mami Dijo', '2:13', 8, 0, '2026-04-24 19:55:04'),
(37, 'Cheque', '3:30', 8, 0, '2026-04-24 19:55:04'),
(38, 'Como Sea', '3:22', 8, 0, '2026-04-24 19:55:04'),
(39, 'Primera Vez', '3:09', 8, 0, '2026-04-24 19:55:04'),
(40, 'Cuarentena', '2:11', 8, 0, '2026-04-24 19:55:04'),
(41, 'Touch Your Body', '2:27', 8, 0, '2026-04-24 19:55:04'),
(42, 'Sauce Boy Freestyle 5', '2:56', 8, 0, '2026-04-24 19:55:04'),
(43, 'Vida Buena', NULL, 9, 0, '2026-04-24 19:55:06'),
(44, 'Hielo', '3:11', 9, 0, '2026-04-24 19:55:06'),
(45, '3 Am', '3:28', 9, 0, '2026-04-24 19:55:06'),
(46, 'Mala Mia', '3:33', 9, 0, '2026-04-24 19:55:06'),
(47, 'Mi Error', '5:15', 9, 0, '2026-04-24 19:55:06'),
(48, 'Actriz', '3:04', 9, 0, '2026-04-24 19:55:06'),
(49, 'Lluvia', '5:19', 9, 0, '2026-04-24 19:55:06'),
(50, 'Mi Funeral', '5:08', 9, 0, '2026-04-24 19:55:06'),
(51, 'Kemba Walker', '2:32', 9, 0, '2026-04-24 19:55:06'),
(52, 'Huh?', '2:42', 9, 0, '2026-04-24 19:55:06'),
(53, 'Rápido', '5:06', 9, 0, '2026-04-24 19:55:06'),
(54, 'Coroné', NULL, 9, 0, '2026-04-24 19:55:06'),
(55, 'Hennessy', '3:04', 9, 0, '2026-04-24 19:55:06'),
(56, 'Safe With Me', '3:06', 9, 0, '2026-04-24 19:55:06'),
(57, 'Ponte Linda', NULL, 9, 0, '2026-04-24 19:55:06'),
(58, 'Mi Error', '3:35', 9, 0, '2026-04-24 19:55:06'),
(59, 'Gladiador', '3:07', 10, 0, '2026-04-24 19:55:07'),
(60, 'Si la Calle Llama', '2:51', 10, 0, '2026-04-24 19:55:07'),
(61, 'Mbappe', '2:27', 10, 0, '2026-04-24 19:55:07'),
(62, 'Hp Freestyle', '2:03', 10, 0, '2026-04-24 19:55:07'),
(63, 'Caras Vemos', '2:36', 10, 0, '2026-04-24 19:55:07'),
(64, 'Hugo', '2:58', 10, 0, '2026-04-24 19:55:07'),
(65, 'Te Dijeron', '2:40', 10, 0, '2026-04-24 19:55:07'),
(66, 'Friends', '2:49', 10, 0, '2026-04-24 19:55:07'),
(67, 'La Fama', '2:14', 10, 0, '2026-04-24 19:55:07'),
(68, 'Carta a Dios', '2:01', 10, 0, '2026-04-24 19:55:07'),
(69, 'Bendecido', '2:22', 11, 0, '2026-04-24 19:55:09'),
(70, 'La Canción Feliz Del Disco', '2:53', 11, 0, '2026-04-24 19:55:09'),
(71, 'TQMQA', '2:49', 11, 0, '2026-04-24 19:55:09'),
(72, 'Sonrisa', '2:32', 11, 0, '2026-04-24 19:55:09'),
(73, 'Sigo Enamorau\'', '3:14', 11, 0, '2026-04-24 19:55:09'),
(74, 'Tu Ritmo', '2:38', 11, 0, '2026-04-24 19:55:09'),
(75, 'Hey Lil Mama', '3:36', 11, 0, '2026-04-24 19:55:09'),
(76, 'Tranquila Baby', '2:08', 11, 0, '2026-04-24 19:55:09'),
(77, 'Tanta Droga', '4:08', 11, 0, '2026-04-24 19:55:09'),
(78, 'El Malo', '3:29', 11, 0, '2026-04-24 19:55:09'),
(79, 'Fé, Cojones y Paciencia', '3:10', 11, 0, '2026-04-24 19:55:09'),
(80, 'Todo Lit', '4:01', 11, 0, '2026-04-24 19:55:09'),
(81, 'That mother***** Eladio (Skit)', '0:40', 11, 0, '2026-04-24 19:55:09'),
(82, 'Mencionar', '2:21', 11, 0, '2026-04-24 19:55:09'),
(83, 'RKO', '1:46', 11, 0, '2026-04-24 19:55:09'),
(84, 'Luchas Mentales', '2:46', 11, 0, '2026-04-24 19:55:09'),
(85, 'Mama\'s Boy', '3:42', 11, 0, '2026-04-24 19:55:09'),
(86, 'Mírame', NULL, 12, 0, '2026-04-24 19:55:13'),
(87, 'Mariposas', NULL, 12, 0, '2026-04-24 19:55:13'),
(88, 'Nena Buena', NULL, 12, 0, '2026-04-24 19:55:13'),
(89, 'Progreso', NULL, 12, 0, '2026-04-24 19:55:13'),
(90, 'Todo o Nada', NULL, 12, 0, '2026-04-24 19:55:13'),
(91, 'Tata', NULL, 12, 0, '2026-04-24 19:55:13'),
(92, 'Mami Me Pregunta Si Trapeo', NULL, 12, 0, '2026-04-24 19:55:13'),
(93, 'Toretto', NULL, 12, 0, '2026-04-24 19:55:13'),
(94, 'Ele Uve (Remix)', NULL, 12, 0, '2026-04-24 19:55:13'),
(95, 'Sauce Boy Freestyle 3', NULL, 12, 0, '2026-04-24 19:55:13'),
(96, 'Adiós', NULL, 12, 0, '2026-04-24 19:55:13'),
(97, 'Discoteca', NULL, 12, 0, '2026-04-24 19:55:13'),
(98, '4 Am', NULL, 12, 0, '2026-04-24 19:55:13'),
(99, 'Mala Mia 2', NULL, 12, 0, '2026-04-24 19:55:13'),
(100, 'Invencible', '1:30', 13, 0, '2026-04-24 19:55:17'),
(101, 'Ohtani', '3:18', 13, 0, '2026-04-24 19:55:17'),
(102, 'Vetements', '3:20', 13, 0, '2026-04-24 19:55:17'),
(103, 'H.I.M', '2:10', 13, 0, '2026-04-24 19:55:17'),
(104, 'Broly', '2:51', 13, 0, '2026-04-24 19:55:17'),
(105, 'Call My Line', '3:32', 13, 0, '2026-04-24 19:55:17'),
(106, '100 Conmigo', '2:31', 13, 0, '2026-04-24 19:55:17'),
(107, 'Tifanny', '4:24', 13, 0, '2026-04-24 19:55:17'),
(108, 'El Reggaeton del Disco', '3:23', 13, 0, '2026-04-24 19:55:17'),
(109, 'Me Muero', '3:15', 13, 0, '2026-04-24 19:55:17'),
(110, 'Cuenta a 10', '3:20', 13, 0, '2026-04-24 19:55:17'),
(111, 'Branzino', '4:22', 13, 0, '2026-04-24 19:55:17'),
(112, 'Cancela To\' (Skit)', '1:06', 13, 0, '2026-04-24 19:55:17'),
(113, 'E.L.A.D.I.O.', '2:51', 13, 0, '2026-04-24 19:55:17'),
(114, 'Mosh Put Muzik', '2:40', 13, 0, '2026-04-24 19:55:17'),
(115, 'Comodo', '2:33', 13, 0, '2026-04-24 19:55:17'),
(116, 'AMG', '3:15', 13, 0, '2026-04-24 19:55:17'),
(117, 'Ozil', '2:39', 13, 0, '2026-04-24 19:55:17'),
(118, 'Romeo y Julieta', '3:26', 13, 0, '2026-04-24 19:55:17'),
(119, 'Piedras en la Ventana', '2:56', 13, 0, '2026-04-24 19:55:17'),
(120, 'Y U So Cold?', '2:30', 13, 0, '2026-04-24 19:55:17'),
(121, 'Carta a Dios 2', '1:53', 13, 0, '2026-04-24 19:55:17'),
(122, 'Guerrero', '2:56', 14, 0, '2026-04-24 19:55:20'),
(123, 'Midas', '2:04', 14, 0, '2026-04-24 19:55:20'),
(124, '5 Star', '2:32', 14, 0, '2026-04-24 19:55:20'),
(125, 'Problemas', '2:53', 14, 0, '2026-04-24 19:55:20'),
(126, 'Paz Mental', '2:31', 14, 0, '2026-04-24 19:55:20'),
(127, 'Sauce Boy Freestyle 4', '1:59', 14, 0, '2026-04-24 19:55:20'),
(128, 'AL CAPONE', '2:42', 14, 0, '2026-04-24 19:55:20'),
(129, 'La H', '2:03', 14, 0, '2026-04-24 19:55:20'),
(130, 'La Novena', '2:17', 14, 0, '2026-04-24 19:55:20'),
(131, 'Ele Uve', NULL, 15, 0, '2026-04-24 19:55:33'),
(132, 'Hielo (Remix)', NULL, 15, 0, '2026-04-24 19:55:33'),
(133, 'Molly Con Henny 2', NULL, 15, 0, '2026-04-24 19:55:33'),
(134, 'Vida Buena (Remix)', NULL, 15, 0, '2026-04-24 19:55:33'),
(135, 'Sin Ti', NULL, 15, 0, '2026-04-24 19:55:33'),
(136, 'Ponte Linda (Remix)', NULL, 15, 0, '2026-04-24 19:55:33'),
(137, 'Especial', NULL, 15, 0, '2026-04-24 19:55:33'),
(138, 'Corona Freestyle', NULL, 15, 0, '2026-04-24 19:55:33'),
(139, 'Tu Amor', NULL, 15, 0, '2026-04-24 19:55:33'),
(140, 'Entre Tantas', NULL, 15, 0, '2026-04-24 19:55:33'),
(141, 'Vida Buena', NULL, 15, 0, '2026-04-24 19:55:33'),
(142, 'Hielo', NULL, 15, 0, '2026-04-24 19:55:33'),
(143, '3 AM', NULL, 15, 0, '2026-04-24 19:55:33'),
(144, 'Mala Mia', NULL, 15, 0, '2026-04-24 19:55:33'),
(145, 'Mi Error (Remix)', NULL, 15, 0, '2026-04-24 19:55:33'),
(146, 'Actriz', NULL, 15, 0, '2026-04-24 19:55:33'),
(147, 'Lluvia (Remix)', NULL, 15, 0, '2026-04-24 19:55:33'),
(148, 'Mi Funeral', NULL, 15, 0, '2026-04-24 19:55:33'),
(149, 'Kemba Walker', NULL, 15, 0, '2026-04-24 19:55:33'),
(150, 'Huh?', NULL, 15, 0, '2026-04-24 19:55:33'),
(151, 'Rápido', NULL, 15, 0, '2026-04-24 19:55:33'),
(152, 'Coroné', NULL, 15, 0, '2026-04-24 19:55:33'),
(153, 'Hennessy', NULL, 15, 0, '2026-04-24 19:55:34'),
(154, 'Safe With Me', NULL, 15, 0, '2026-04-24 19:55:34'),
(155, 'Ponte Linda', NULL, 15, 0, '2026-04-24 19:55:34'),
(156, 'Mi Error', NULL, 15, 0, '2026-04-24 19:55:34'),
(157, 'Cine ????', NULL, 16, 0, '2026-04-24 20:51:13'),
(158, 'Cine 🚬', NULL, 16, 0, '2026-04-24 22:15:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `playlists`
--

CREATE TABLE `playlists` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `playlists`
--

INSERT INTO `playlists` (`id`, `nombre`, `descripcion`, `creado_en`) VALUES
(1, 'Desaburrimiento en el trabajo', '', '2026-04-24 18:31:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `playlist_canciones`
--

CREATE TABLE `playlist_canciones` (
  `id` int(11) NOT NULL,
  `playlist_id` int(11) NOT NULL,
  `cancion_id` int(11) NOT NULL,
  `orden` int(11) DEFAULT 0,
  `agregado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `playlist_canciones`
--

INSERT INTO `playlist_canciones` (`id`, `playlist_id`, `cancion_id`, `orden`, `agregado_en`) VALUES
(3, 1, 158, 0, '2026-04-24 22:15:33'),
(4, 1, 32, 0, '2026-04-28 14:59:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `albumes`
--
ALTER TABLE `albumes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artista_id` (`artista_id`);

--
-- Indices de la tabla `artistas`
--
ALTER TABLE `artistas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `canciones`
--
ALTER TABLE `canciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indices de la tabla `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `playlist_canciones`
--
ALTER TABLE `playlist_canciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `playlist_id` (`playlist_id`),
  ADD KEY `cancion_id` (`cancion_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `albumes`
--
ALTER TABLE `albumes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `artistas`
--
ALTER TABLE `artistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `canciones`
--
ALTER TABLE `canciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT de la tabla `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `playlist_canciones`
--
ALTER TABLE `playlist_canciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `albumes`
--
ALTER TABLE `albumes`
  ADD CONSTRAINT `albumes_ibfk_1` FOREIGN KEY (`artista_id`) REFERENCES `artistas` (`id`);

--
-- Filtros para la tabla `canciones`
--
ALTER TABLE `canciones`
  ADD CONSTRAINT `canciones_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albumes` (`id`);

--
-- Filtros para la tabla `playlist_canciones`
--
ALTER TABLE `playlist_canciones`
  ADD CONSTRAINT `playlist_canciones_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`id`),
  ADD CONSTRAINT `playlist_canciones_ibfk_2` FOREIGN KEY (`cancion_id`) REFERENCES `canciones` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
