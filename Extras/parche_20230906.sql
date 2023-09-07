UPDATE `tipo_pagos` SET `descripcion` = 'Otros Ingresos' WHERE `tipo_pagos`.`id` = 4;
ALTER TABLE `precios` ADD `sesiones` INT NULL DEFAULT '0' AFTER `idClasificacion`;
UPDATE `precios` SET `sesiones` = '1' WHERE `precios`.`id` = 15;
UPDATE `precios` SET `sesiones` = '1' WHERE `precios`.`id` = 28;
UPDATE `precios` SET `descripcion` = 'Membresía Kurame M1' WHERE `precios`.`id` = 15;
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `sesiones`, `activo`) VALUES (NULL, 'Membresía Kurame M2', '400', '400', '4', '1', '1');
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `sesiones`, `activo`) VALUES (NULL, 'Niños - 06 sesiones', '528', '528', '4', '6', '1');
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `sesiones`, `activo`) VALUES 
(NULL, 'Niños - 08 sesiones', '700', 0, '4', '8', '1'),
(NULL, 'Niños - 10 sesiones', '860', 0, '4', '10', '1'),
(NULL, 'Niños - 12 sesiones', '1020', 0, '4', '12', '1'),
(NULL, 'Niños - 14 sesiones', '1176', 0, '4', '14', '1'),
(NULL, 'Niños - 15 sesiones', '1245', 0, '4', '15', '1');
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `sesiones`, `activo`) VALUES 
(NULL, 'Adultos - 06 sesiones', '468', 0, '4', '6', '1'),
(NULL, 'Adultos - 08 sesiones', '620', 0, '4', '8', '1'),
(NULL, 'Adultos - 10 sesiones', '760', 0, '4', '10', '1'),
(NULL, 'Adultos - 12 sesiones', '900', 0, '4', '12', '1'),
(NULL, 'Adultos - 14 sesiones', '1136', 0, '4', '14', '1'),
(NULL, 'Niños - 15 sesiones', '1095', 0, '4', '15', '1');
UPDATE `precios` SET `nuevos` = '1500', `continuos` = '0' WHERE `precios`.`id` = 28;
UPDATE `precios` SET `continuos` = '0' WHERE `precios`.`id` = 30;
UPDATE `precios` SET `continuos` = '0' WHERE `precios`.`id` = 15;
