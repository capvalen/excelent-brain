UPDATE `users` SET `nombre` = 'Administrador' WHERE `users`.`id` = 3;
CREATE TABLE `acontecimientos` (
 `idAcontecimiento` int(11) NOT NULL AUTO_INCREMENT,
 `idProfesional` int(11) NOT NULL,
 `idPaciente` int(11) NOT NULL,
 `fecha` datetime DEFAULT current_timestamp(),
 `edad` int(11) NOT NULL,
 `acontecimiento` text NOT NULL,
 `sintomas` text NOT NULL,
 `activo` int(11) DEFAULT 1,
 PRIMARY KEY (`idAcontecimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1