INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `activo`) VALUES (NULL, 'Otros Certificados', '60', '60', '3', '1');
UPDATE `precios` SET `descripcion` = 'Reevaluación por informe verbal' WHERE `precios`.`id` = 18;
ALTER TABLE `appointments` CHANGE `status` `status` TINYINT(4) NULL DEFAULT NULL COMMENT '1=sin conf, 2=conf, 3=anulado, 4=reprog';