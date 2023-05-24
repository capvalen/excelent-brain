CREATE TABLE `faltas` (`id` INT NOT NULL AUTO_INCREMENT , `idPaciente` INT NOT NULL , `idProfesional` INT NOT NULL , `idHorario` INT NOT NULL , `registro` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `activo` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `faltas` CHANGE `activo` `activo` INT(11) NULL DEFAULT '1';
ALTER TABLE `faltas` ADD `fecha` DATE NOT NULL AFTER `idProfesional`;