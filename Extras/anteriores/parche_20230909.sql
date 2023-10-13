UPDATE `precios` SET `nuevos` = '100' WHERE `precios`.`id` = 13;
UPDATE `precios` SET `continuos` = '100' WHERE `precios`.`id` = 13;
UPDATE `precios` SET `nuevos` = '90' WHERE `precios`.`id` = 14;
UPDATE `precios` SET `continuos` = '70' WHERE `precios`.`id` = 14;
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `sesiones`, `activo`) VALUES (NULL, 'Reingreso', '150', '150', '1', '0', '1');
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `sesiones`, `activo`) VALUES (NULL, 'Validez mental', '200', '200', '3', '0', '1');
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `sesiones`, `activo`) VALUES (NULL, 'Lectura de resultados', '0', '0', '1', '0', '1');
