
--Parche 5.50
ALTER TABLE `appointments` ADD `entrance` TIME NULL DEFAULT NULL AFTER `num_sesion`, ADD `attention` TIME NULL DEFAULT NULL AFTER `entrance`;
ALTER TABLE `membresias` ADD `descuento` FLOAT NULL DEFAULT '0' AFTER `cuotas`; 