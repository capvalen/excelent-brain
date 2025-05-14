ALTER TABLE `precios` ADD `meses` INT NULL DEFAULT '0' AFTER `sesiones`;
CREATE TABLE `triaje_archivo` (`id` INT NOT NULL AUTO_INCREMENT , `patient_id` INT NOT NULL , `ruta` INT NOT NULL , `activo` INT NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `triaje_archivo` CHANGE `ruta` `ruta` VARCHAR(250) NOT NULL;