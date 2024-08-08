ALTER TABLE `appointments` CHANGE `status` `status` TINYINT(4) NULL DEFAULT NULL COMMENT '1=sin conf, 2=conf, 3=anulado, 4=reprog, 5=eliminado';
CREATE TABLE `precios_clasificacion` (`id` INT NOT NULL AUTO_INCREMENT , `clasificacion` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `precios_clasificacion` (`id`, `clasificacion`) VALUES ('1', 'Psiquiatrica'), ('2', 'Psicológica'), ('3', 'Certificado'), ('4', 'Kurame'),  ('5', 'Membresía'),  ('6', 'Nutrición');
ALTER TABLE `precios` CHANGE `idClasificacion` `idClasificacion` INT(11) NOT NULL COMMENT 'table precios_clasificacion';
UPDATE `professionals` SET `profession` = 'Ninguno' WHERE `professionals`.`id` = 1;
UPDATE `professionals` SET `profession` = 'Nutricionista' WHERE `professionals`.`id` = 23;
UPDATE `professionals` SET `photo` = '-' WHERE `professionals`.`id` = 23;
