UPDATE `precios` SET `descripcion` = 'Sesión kurame M1' WHERE `precios`.`id` = 19;
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `activo`) VALUES (NULL, 'Sesión kurame M2', '0', '0', '4', '1'), (NULL, 'Sesión kurame M3', '0', '0', '4', '1');
UPDATE `precios` SET `descripcion` = 'Membresía Kurame' WHERE `precios`.`id` = 15;
ALTER TABLE `patients` ADD `etiqueta` VARCHAR(250) NULL DEFAULT '' AFTER `activo`;
UPDATE `precios` SET `descripcion` = 'Revaluación Psicológica' WHERE `precios`.`id` = 18;
UPDATE `precios` SET `descripcion` = 'Revaluación Psiquiátrica' WHERE `precios`.`id` = 17;
ALTER TABLE `payments` ADD `rebaja` FLOAT NULL DEFAULT '0' AFTER `continuo`, ADD `motivoRebaja` VARCHAR(250) NULL DEFAULT NULL AFTER `rebaja`, ADD `descuento` FLOAT NULL DEFAULT '0' AFTER `motivoRebaja`, ADD `motivoDescuento` VARCHAR(250) NULL DEFAULT NULL AFTER `descuento`;
ALTER TABLE `extra_payments` ADD `rebaja` FLOAT NULL DEFAULT '0' AFTER `activo`, ADD `motivoRebaja` VARCHAR(250) NULL DEFAULT NULL AFTER `rebaja`, ADD `descuento` FLOAT NULL DEFAULT '0' AFTER `motivoRebaja`, ADD `motivoDescuento` VARCHAR(250) NULL DEFAULT NULL AFTER `descuento`;
INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `idClasificacion`, `activo`) VALUES (NULL, 'Membresía Clínica de día', '600', '600', '5', '1');