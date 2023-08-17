
UPDATE `precios` SET `nuevos` = '60', continuos=60,  `descripcion` = 'Informe verbal' WHERE `precios`.`id` = 18;
ALTER TABLE `precios` CHANGE `idClasificacion` `idClasificacion` INT(11) NOT NULL COMMENT '1=Psiq, 2=Psico, 3=Cert, 4=Kurame, 5=membresia';