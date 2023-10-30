ALTER TABLE `extra_payments` ADD `tipo` INT NULL DEFAULT '-1' COMMENT '-1=ninguno, 1=factura, 3=boleta' AFTER `idMembresia`;
CREATE TABLE `consultorio`.`tipoComprobante` (`id` INT NOT NULL AUTO_INCREMENT , `descripcion` VARCHAR(250) NOT NULL , `activo` INT NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `tipoComprobante` (`id`, `descripcion`, `activo`) VALUES ('3', 'Boleta de venta', '1'), ('1', 'Factura', '1'), ('2', 'Recibo por honorarios', '1'), ('-1', 'Ninguno', '1'), ('4', 'Contrato', '1');
UPDATE `patients` SET `activo` = '0' WHERE `patients`.`id` = 5;
