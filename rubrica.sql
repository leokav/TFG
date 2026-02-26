SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `rubrica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE IF NOT EXISTS `asignatura` (
  `id_asignatura` int(15) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `creditos` float NOT NULL,
  PRIMARY KEY (`id_asignatura`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`id_asignatura`, `nombre`, `descripcion`, `creditos`) VALUES
(01, 'asignatura01', 'descripcion asignatura01', 6),


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_estudiantes`
--

CREATE TABLE IF NOT EXISTS `asignatura_estudiantes` (
  `id_asignatura` int(4) NOT NULL,
  `id_estudiante` int(7) NOT NULL,
  PRIMARY KEY (`id_asignatura`,`id_estudiante`),
  KEY `id_estudiante` (`id_estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `asignatura_estudiantes`
--

INSERT INTO `asignatura_estudiantes` (`id_asignatura`, `id_estudiante`) VALUES
(01, 01);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criterio`
--

CREATE TABLE IF NOT EXISTS `criterio` (
  `id_criterio` int(15) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(15) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_criterio`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Volcado de datos para la tabla `criterio`
--

INSERT INTO `criterio` (`id_criterio`, `id_usuario`, `descripcion`) VALUES
(01, 01, 'Criterio01');


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criterio_niveles`
--

CREATE TABLE IF NOT EXISTS `criterio_niveles` (
  `id_criterio` int(15) NOT NULL,
  `id_nivel` int(15) NOT NULL,
  `predef` varchar(500) NOT NULL,
  KEY `id_nivel` (`id_nivel`),
  KEY `id_criterio` (`id_criterio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `criterio_niveles`
--

INSERT INTO `criterio_niveles` (`id_criterio`, `id_nivel`, `predef`) VALUES
(01, 01, 'Buena ent'),
(01, 02, 'Reg ent'),
(01, 03, 'Nt ent'),
(01, 04, 'Sb ent');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE IF NOT EXISTS `estudiante` (
  `id_estudiante` int(7) NOT NULL AUTO_INCREMENT,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_estudiante`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_estudiante`, `dni`, `nombre`, `email`) VALUES
(01, '00000000X', 'Soy un ejemplo', 'ejemplo@ejemplo.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel`
--

CREATE TABLE IF NOT EXISTS `nivel` (
  `id_nivel` int(15) NOT NULL AUTO_INCREMENT,
  `calificacion` int(2) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_nivel`),
  KEY `calificacion` (`calificacion`),
  KEY `calificacion_2` (`calificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Volcado de datos para la tabla `nivel`
--

INSERT INTO `nivel` (`id_nivel`, `calificacion`, `nombre`) VALUES
(01, 6, 'Aprobado'),
(02, 4, 'Suspenso'),
(03, 8, 'Notable'),
(04, 10, 'Sobresaliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE IF NOT EXISTS `profesor` (
  `id_usuario` int(15) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`id_usuario`, `titulo`) VALUES
(01, 'Profesor de ejemplo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_asignaturas`
--

CREATE TABLE IF NOT EXISTS `profesor_asignaturas` (
  `id_usuario` int(15) NOT NULL,
  `id_asignatura` int(15) NOT NULL,
  KEY `id_usuario` (`id_usuario`),
  KEY `id_asignatura` (`id_asignatura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesor_asignaturas`
--

INSERT INTO `profesor_asignaturas` (`id_usuario`, `id_asignatura`) VALUES
(01, 01);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba`
--

CREATE TABLE IF NOT EXISTS `prueba` (
  `id_asignatura` int(15) NOT NULL,
  `id_prueba` int(15) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_prueba`),
  KEY `id_asignatura` (`id_asignatura`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Volcado de datos para la tabla `prueba`
--

INSERT INTO `prueba` (`id_asignatura`, `id_prueba`, `descripcion`) VALUES
(01, 01, 'Documentacion'),
(01, 02, 'Presentacion'),
(01, 03, 'Desarrollo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba_criterios`
--

CREATE TABLE IF NOT EXISTS `prueba_criterios` (
  `id_prueba` int(15) NOT NULL,
  `id_criterio` int(15) NOT NULL,
  `peso` float NOT NULL,
  KEY `id_prueba` (`id_prueba`),
  KEY `id_categoria` (`id_criterio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `prueba_criterios`
--

INSERT INTO `prueba_criterios` (`id_prueba`, `id_criterio`, `peso`) VALUES
(01, 01, 33.3333),
(02, 01, 25),
(02, 01, 25),
(02, 01, 25),
(02, 01, 25),
(03, 01, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubrica_estudiante`
--

CREATE TABLE IF NOT EXISTS `rubrica_estudiante` (
  `id_estudiante` int(15) NOT NULL,
  `id_prueba` int(15) NOT NULL,
  `id_criterio` int(15) NOT NULL,
  `id_nivel` int(15) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_prueba` (`id_prueba`),
  KEY `id_criterio` (`id_criterio`),
  KEY `id_nivel` (`id_nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rubrica_estudiante`
--

INSERT INTO `rubrica_estudiante` (`id_estudiante`, `id_prueba`, `id_criterio`, `id_nivel`, `descripcion`) VALUES
(01, 02, 01, 04, 'Sb');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(15) NOT NULL AUTO_INCREMENT,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(50) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `dni`, `nombre`, `password`, `admin`) VALUES
(01, '00000000X', 'Usuario 01', '216707b4e410e5824e8f8622ccffd05ec865507', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignatura_estudiantes`
--
ALTER TABLE `asignatura_estudiantes`
  ADD CONSTRAINT `asignatura_estudiantes_ibfk_1` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asignatura_estudiantes_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `criterio`
--
ALTER TABLE `criterio`
  ADD CONSTRAINT `criterio_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `profesor` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `criterio_niveles`
--
ALTER TABLE `criterio_niveles`
  ADD CONSTRAINT `criterio_niveles_ibfk_1` FOREIGN KEY (`id_criterio`) REFERENCES `criterio` (`id_criterio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `criterio_niveles_ibfk_2` FOREIGN KEY (`id_nivel`) REFERENCES `nivel` (`id_nivel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesor_asignaturas`
--
ALTER TABLE `profesor_asignaturas`
  ADD CONSTRAINT `profesor_asignaturas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `profesor` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profesor_asignaturas_ibfk_2` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prueba`
--
ALTER TABLE `prueba`
  ADD CONSTRAINT `prueba_ibfk_1` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prueba_criterios`
--
ALTER TABLE `prueba_criterios`
  ADD CONSTRAINT `prueba_criterios_ibfk_1` FOREIGN KEY (`id_prueba`) REFERENCES `prueba` (`id_prueba`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prueba_criterios_ibfk_2` FOREIGN KEY (`id_criterio`) REFERENCES `criterio` (`id_criterio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rubrica_estudiante`
--
ALTER TABLE `rubrica_estudiante`
  ADD CONSTRAINT `rubrica_estudiante_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rubrica_estudiante_ibfk_2` FOREIGN KEY (`id_prueba`) REFERENCES `prueba_criterios` (`id_prueba`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rubrica_estudiante_ibfk_3` FOREIGN KEY (`id_criterio`) REFERENCES `prueba_criterios` (`id_criterio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rubrica_estudiante_ibfk_4` FOREIGN KEY (`id_nivel`) REFERENCES `criterio_niveles` (`id_nivel`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
