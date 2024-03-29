ALTER TABLE `users` CHANGE `activo` `activo` INT(11) NULL DEFAULT '1';
ALTER TABLE `patients` ADD `new_status` INT NULL DEFAULT '1' COMMENT 'tabla status' AFTER `etiqueta`;
CREATE TABLE `status` (`id` INT NOT NULL AUTO_INCREMENT , `stat` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `status` (`id`, `stat`) VALUES (NULL, 'Sin estatus'), (NULL, 'Kurame'), (NULL, 'Clínica de Día'), (NULL, 'Ambulatorio');
ALTER TABLE `patients` ADD `seguimiento` INT NULL DEFAULT '1' COMMENT 'tabla seguimiento' AFTER `new_status`;
CREATE TABLE `seguimientos` (`id` INT NOT NULL AUTO_INCREMENT , `seguimiento` VARCHAR(50) NOT NULL , `color` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `seguimientos` (`id`, `seguimiento`, `color`) VALUES (NULL, 'Sin acción', ''), (NULL, 'No llamar', 'text-danger'), (NULL, 'Volver a llamar', 'text-warning'), (NULL, 'Dado de alta', 'text-success'), (NULL, 'Constante', 'text-info'), (NULL, 'No contesta', 'text-amarillo');
CREATE TABLE `pacient_seguimiento` (`id` INT NOT NULL AUTO_INCREMENT , `registro` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `patient_id` INT NOT NULL , `idSeguimiento` INT NOT NULL , `observaciones` TEXT NOT NULL , `activo` INT NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `pacient_seguimiento` ADD `user_id` INT NOT NULL AFTER `patient_id`;
ALTER TABLE `pacient_seguimiento` CHANGE `observaciones` `observaciones` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `interesados` CHANGE `celular` `celular` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
CREATE TABLE `vida` (`id` INT NOT NULL AUTO_INCREMENT , `patient_id` INT NOT NULL , `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `icono` VARCHAR(20) NOT NULL , `edad` VARCHAR(20) NULL DEFAULT '' , `acontecimiento` TEXT NULL DEFAULT '' , `sintomas` TEXT NULL DEFAULT '' , PRIMARY KEY (`id`)) ENGINE = InnoDB;