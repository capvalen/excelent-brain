CREATE TABLE `consultorio`.`recordatorios` (`id` INT NOT NULL , `fecha` DATETIME NOT NULL , `actividad` INT NOT NULL , `responsable` VARCHAR(250) NOT NULL , `estado` INT NOT NULL , `activo` INT NULL DEFAULT '1' , `actualizador` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `recordatorios` CHANGE `activo` `estado` INT(11) NULL DEFAULT '1' COMMENT '1=creado, 2=atendido, 3=solucionado';
ALTER TABLE `recordatorios` ADD `creador` INT NOT NULL AFTER `activo`;
ALTER TABLE `recordatorios` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `recordatorios` CHANGE `estado` `estado` INT(11) NULL DEFAULT '1';
ALTER TABLE `recordatorios` CHANGE `actividad` `actividad` TEXT NOT NULL;
