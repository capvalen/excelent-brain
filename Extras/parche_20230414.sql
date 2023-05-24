
ALTER TABLE `patients` CHANGE `gender` `gender` TINYINT(4) NULL DEFAULT NULL COMMENT '0=female; 1=male';
CREATE TABLE `triaje` (`id` INT NOT NULL AUTO_INCREMENT , `patient_id` INT NOT NULL , `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `motivo` TEXT NOT NULL , `sintomatologia` TEXT NOT NULL , `antecedentes` TEXT NOT NULL , `prioridad` INT NOT NULL , `especialista` VARCHAR(250) NOT NULL , `activo` TINYINT NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `triaje` CHANGE `fecha` `fecha` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `triaje` CHANGE `activo` `activo` TINYINT(4) NULL DEFAULT '1';
ALTER TABLE `triaje` CHANGE `id` `id` INT(11) NULL AUTO_INCREMENT;
ALTER TABLE `patients` CHANGE `dni` `dni` VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `patients` ADD `type_dni` INT NULL DEFAULT '1' COMMENT '1=dni,2=carnet;3=pasaporte' AFTER `faults`;

