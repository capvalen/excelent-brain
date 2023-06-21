CREATE TABLE `consultorio`.`precios` (`id` INT NOT NULL AUTO_INCREMENT , `descripcion` INT NOT NULL , `nuevos` INT NOT NULL , `continuos` INT NOT NULL , `idClasificacion` INT NOT NULL COMMENT '1=Psiq, 2=Psico, 3=Cert, 4=Kurame' , `activo` INT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `precios` CHANGE `descripcion` `descripcion` VARCHAR(250) NOT NULL;
ALTER TABLE `precios` CHANGE `nuevos` `nuevos` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `precios` CHANGE `continuos` `continuos` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `professionals` ADD `activo` INT NULL DEFAULT '1' AFTER `updated_at`;
ALTER TABLE `appointments` ADD `formato_nuevo` INT NULL DEFAULT '0' COMMENT '0=no de JSON, 1=si de DB' AFTER `updated_at`;
ALTER TABLE `professionals` ADD `idProfesion` INT NULL DEFAULT '1' AFTER `phone`;
UPDATE `professionals` SET `idProfesion` = 1 where `profession` = 'Psiquiatra';
UPDATE `professionals` SET `idProfesion` = 2 where `profession` = 'Psicólogo';
UPDATE `professionals` SET `activo` = '0' WHERE `professionals`.`id` = 12;

INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `activo`) VALUES 
(NULL, 'Terapia inicial niño / adolescente', '200', '190', '1', '1'),
(NULL, 'Terapia inicial adulto', '170', '160', '1', '1'),
(NULL, 'Terapia de pareja', '200', '190', '1', '1'),
(NULL, 'Terapia de familiar', '200', '190', '1', '1'),
(NULL, 'Terapia continua niño / adolescente', '160', '150', '1', '1'),
(NULL, 'Terapia continua adulto', '120', '110', '1', '1'),
(NULL, 'Terapia inicial niño / adolescente', '100', '70', '2', '1'),
(NULL, 'Terapia inicial adulto', '90', '70', '2', '1'),
(NULL, 'Terapia de pareja', '150', '120', '2', '1'),
(NULL, 'Terapia de familiar', '150', '120', '2', '1'),
(NULL, 'Terapia continua niño / adolescente', '90', '60', '2', '1'),
(NULL, 'Terapia continua adulto', '80', '60', '2', '1'),
(NULL, 'Sucamec inicial', '190', '190', '3', '1'),
(NULL, 'Sucamec renovación', '170', '170', '3', '1'),
(NULL, 'Kurame', '600', '600', '4', '1'),
(NULL, 'Orientación vocacional', '200', '200', '4', '1'),
(NULL, 'Revaluación', '0', '0', '1', '1'),
(NULL, 'Revaluación', '0', '0', '2', '1');