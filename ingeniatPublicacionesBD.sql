-- Versión de PHP: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `ingeniatPublicaciones` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ingeniatPublicaciones`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicacion`
--

CREATE TABLE `publicacion` (
  `IdPublicacion` int(11) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `Titulo` varchar(70) NOT NULL,
  `Descripcion` tinytext NOT NULL,
  `FecRegistro` datetime NOT NULL,
  `FecMovimiento` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla donde se guardan las publicaciones echas por el usuario';

-- --------------------------------------------------------
INSERT INTO `publicacion` (`IdPublicacion`, `IdUsuario`, `Titulo`, `Descripcion`, `FecRegistro`, `FecMovimiento`, `activo`) VALUES (NULL, '1', 'Publicacion Editada', 'Descripdion editada', '2021-10-04 00:50:02.000000', current_timestamp(), '1');
--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `IdRol` int(11) NOT NULL,
  `Nombre` varchar(70) NOT NULL COMMENT 'Nombre del Rol',
  `Descripcion` tinytext NOT NULL COMMENT 'Acciones que puede realizar el usuario con el Rol',
  `FecRegistro` datetime NOT NULL COMMENT 'Fecha de registro',
  `FecMovimiento` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de movimiento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla donde se identifican los roles';

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`IdRol`, `Nombre`, `Descripcion`, `FecRegistro`, `FecMovimiento`) VALUES
(1, 'Rol básico', 'Permiso de acceso', '2021-09-16 11:13:41', '2021-09-16 16:13:36'),
(2, 'Rol medio', 'Permiso de acceso y consulta', '2021-09-16 11:13:42', '2021-09-16 16:13:36'),
(3, 'Rol medio alto', 'Permiso de de acceso y agregar', '2021-09-16 11:13:42', '2021-09-16 16:13:36'),
(4, 'Rol alto medio', 'Permiso de acceso, consulta, agregar y actualizar', '2021-09-16 11:13:47', '2021-09-16 16:13:36'),
(5, 'Rol alto', 'Permiso de acceso, consulta, agregar, actualizar y eliminar', '2021-09-16 11:13:46', '2021-09-16 16:13:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `IdUsuario` int(11) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Correo` varchar(64) NOT NULL,
  `Password` text NOT NULL,
  `IdRol` int(11) NOT NULL,
  `FecRegistro` datetime NOT NULL,
  `FecMovimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `usuario` (`IdUsuario`, `Nombre`, `Apellido`, `Correo`, `Password`, `IdRol`, `FecRegistro`, `FecMovimiento`) VALUES (NULL, 'Admistrador', '.', 'administrador@correo.com', 'Admin', '5', '2021-10-04 00:48:33.000000', current_timestamp());
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD PRIMARY KEY (`IdPublicacion`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IdRol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD KEY `FK1_cat_roles_nIdRol` (`IdRol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `IdPublicacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `IdRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD CONSTRAINT `FK1_dat_usuario_nIdUsuario` FOREIGN KEY (`IdUsuario`) REFERENCES `usuario` (`IdUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `FK1_cat_roles_nIdRol` FOREIGN KEY (`IdRol`) REFERENCES `roles` (`IdRol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_publicacion`(IN `CkIdPublicacion` INT, IN `CkIdUsuario` INT)
BEGIN 
          SET @Mensaje ='';
          SET @Codigo = 1;
          SET @IdPublicacion = 0;
          SET @IdRegistro = 0;
          SET @pasoSiguiente = 0; 
          SELECT IdPublicacion INTO @IdPublicacion FROM publicacion WHERE IdPublicacion = CkIdPublicacion AND IdUsuario = CkIdUsuario;
          IF @IdPublicacion > 0 THEN
               SET @pasoSiguiente = 1;
          ELSE
          		SET @Mensaje = 'No se encontraron coindidencias para este id de publicacion';
          END IF; 
          IF @pasoSiguiente = 1 THEN
              UPDATE publicacion SET activo = 0 WHERE IdPublicacion = CkIdPublicacion AND IdUsuario = CkIdUsuario; 
               SET @IdRegistro = @IdPublicacion;
               IF @IdRegistro > 0 THEN
                    SET @Codigo = 0;
                    SET @Mensaje = 'Publiación elimnada';
               ELSE
                    SET @Mensaje = 'No fue posible eliminar la publicación';
               END IF; 
          END IF;          
          SELECT @Codigo AS Codigo, @Mensaje AS Mensaje, @IdRegistro AS IdPublicacion;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_publicacion`(IN `CkIdUsuario` INT, IN `CkTitulo` VARCHAR(70), IN `CkDescripcion` TINYTEXT)
BEGIN
          SET @Mensaje ='';
          SET @Codigo = 1;
          SET @IdUsuario = 0;
          SET @IdRegistro = 0;
          SET @SiguientePaso = 0; 
 
          SELECT IdUsuario INTO @IdUsuario FROM usuario WHERE IdUsuario = CkIdUsuario;
               
          IF @IdUsuario > 0 THEN
               SET @SiguientePaso = 1;
          ELSE
          	SET @Mensaje = 'Usuario ivalido';
          END IF;
 
          IF @SiguientePaso = 1 THEN
               INSERT INTO publicacion(`IdUsuario`, `Titulo`, `Descripcion`, FecRegistro) VALUES (CkIdUsuario, CkTitulo, CkDescripcion, NOW());
               SET @IdRegistro = LAST_INSERT_ID();
               IF @IdRegistro > 0 THEN
                    SET @Codigo = 0;
                    SET @Mensaje = 'Publicación registrada';
               ELSE
                    SET @Mensaje = 'No fue posible registrar la publicación';
               END IF; 
          END IF;          
          SELECT @Codigo AS Codigo, @Mensaje AS Mensaje, @IdRegistro AS IdPublicacion;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_usuario`(IN `CkNombre` VARCHAR(30), IN `CkApellido` VARCHAR(50), IN `CkCorreo` VARCHAR(64), IN `CkPassword` VARCHAR(30), IN `CkRol` VARCHAR(30))
BEGIN
          SET @MRespuesta ='';
          SET @Codigo = 1;
          SET @IdUsuario = 0;
          SET @IdRol = 0;
          SET @IdRegistro = 0;
          SET @siguientePaso = 0; 
 
          SELECT IdUsuario INTO @IdUsuario FROM usuario WHERE Correo = CkCorreo;
               
          IF @IdUsuario > 0 THEN
               SET @MRespuesta = 'Correo registrado anteriormente, favor de ingresar otro';
          ELSE
               SET @siguientePaso = 1;
          END IF;          
          IF @siguientePaso = 1 THEN
		          SELECT IdRol INTO @IdRol FROM roles WHERE IdRol = CkRol;		               
		          IF @IdRol > 0 THEN
		               SET @siguientePaso = 2;
		          ELSE
		               SET @MRespuesta = 'Rol ingresado, no es valido.';
		          END IF;
          END IF;
 
          IF @siguientePaso = 2 THEN
               INSERT INTO usuario(`Nombre`, `Apellido`, `Correo`, `Password`, `IdRol`, FecRegistro)
						   VALUES (CkNombre, CkApellido, CkCorreo , CkPassword, CkRol, NOW());
               SET @IdRegistro = LAST_INSERT_ID();          
               IF @IdRegistro > 0 THEN
                    SET @Codigo = 0;
                    SET @MRespuesta = 'Usuario registrado exitosamente';
               ELSE
                    SET @MRespuesta = 'No se pudo registrar el usuario, vuelva a intentarlo';
               END IF; 
          END IF;          
          SELECT @Codigo AS nCodigo, @MRespuesta AS sMensaje;
     END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_login`(IN `CkCorreo` CHAR(64), IN `CkPassword` TEXT)
BEGIN
	SET @Mensaje ='Usuario o contrasena incorrectos.';
	SET @Codigo = 1;
	SET @IdUsuario = 0;
	SELECT IdUsuario,IdRol,Nombre INTO @IdUsuario,@IdRol,@Nombre	FROM usuario	WHERE Correo= CkCorreo AND Password=CkPassword;
	IF(@IdUsuario > 0)THEN
		SET @Codigo = 0;
		SET @Mensaje = CONCAT('Bienvenido ', @Nombre);
	END IF;	
	SELECT @Codigo AS Codigo, @Mensaje AS Mensaje, @IdRol AS nIdRol, @IdUsuario AS IdUsuario, @Nombre AS Nombre;
	
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `select_publicacion`()
SELECT   pub.Titulo ,pub.Descripcion ,pub.FecRegistro,CONCAT (usua.Nombre, ' ', usua.Apellido) AS 'Autor de publicacion',rol.Nombre AS Rol
					FROM usuario AS usua
					INNER JOIN publicacion AS pub ON pub.IdUsuario = usua.IdUsuario
					INNER JOIN roles AS rol ON rol.IdRol = usua.IdRol$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_publicacion`(IN `CkIdPublicacion` INT, IN `CkIdUsuario` INT, IN `CkTitulo` VARCHAR(50), IN `CkDescripcion` TINYTEXT)
BEGIN 
          SET @Mensaje ='';
          SET @Codigo = 1;
          SET @IdPublicacion = 0;
          SET @IdRegistro = 0;
          SET @pasoSiguiente = 0; 
          SELECT IdPublicacion INTO @IdPublicacion FROM publicacion WHERE IdPublicacion = CkIdPublicacion AND IdUsuario = CkIdUsuario;
          IF @IdPublicacion > 0 THEN
               SET @pasoSiguiente = 1;
          ELSE
          		SET @Mensaje = 'No se encontraron coindidencias para este id de publicacion';
          END IF; 
          IF @pasoSiguiente = 1 THEN
              UPDATE publicacion SET `Descripcion` = CkDescripcion, `Titulo` = CkTitulo WHERE IdPublicacion = CkIdPublicacion AND IdUsuario = CkIdUsuario; 
               SET @IdRegistro = @IdPublicacion;
               IF @IdRegistro > 0 THEN
                    SET @Codigo = 0;
                    SET @Mensaje = 'Publiación actualizada';
               ELSE
                    SET @Mensaje = 'No fue posible actualizar la publicación';
               END IF; 
          END IF;          
          SELECT @Codigo AS Codigo, @Mensaje AS Mensaje, @IdRegistro AS IdPublicacion;
END$$
DELIMITER ;
