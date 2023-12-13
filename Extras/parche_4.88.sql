ALTER TABLE `precios` ADD `especialMembresias` FLOAT NOT NULL DEFAULT '0' COMMENT 'precio especial de membresias' AFTER `continuos`;
ALTER TABLE `membresias` ADD `estado` INT NULL DEFAULT '1' COMMENT '1=pendiente, 2=en curso, 3=deshabilitado' AFTER `monto`;
CREATE TABLE `medical_comments` (`id` INT NOT NULL , `idMedical` INT NOT NULL , `comment` INT NOT NULL , `created_at` TIMESTAMP NULL , `updated_at` TIMESTAMP NULL , `activo` INT NULL DEFAULT '1' ) ENGINE = InnoDB;
ALTER TABLE `medical_comments` ADD `professional_id` INT NOT NULL AFTER `idMedical`;
ALTER TABLE `medical_comments` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `medical_comments` CHANGE `comment` `comment` TEXT NOT NULL;
ALTER TABLE `appointments` ADD `recomendacion_comentario` TEXT NULL DEFAULT '' AFTER `idMembresia`;
ALTER TABLE `scrs` ADD `resultados` TEXT NULL DEFAULT NULL AFTER `professional_id`;
ALTER TABLE `burns` ADD `resultados` TEXT NULL DEFAULT NULL AFTER `professional_id`;