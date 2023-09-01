INSERT INTO `payment_method`(`tipo`) VALUES ('Banco: BCP'), ('Banco: BBVA'), ('Banco: Interbank'), ('Banco: Nación'), ('Banco: Scotiabank');
INSERT INTO `payment_method`(`tipo`) VALUES ('Aplicativo Plin');
UPDATE `payment_method` SET `tipo` = 'Aplicativo Yape' WHERE `payment_method`.`id` = 4;
ALTER TABLE `interesados` ADD `correo` VARCHAR(250) NULL DEFAULT '' AFTER `fecha`;
ALTER TABLE `extra_payments` ADD `appointment_id` INT NULL DEFAULT '0' AFTER `date`;
DROP TABLE ` paciente `;
ALTER TABLE `patients` ADD `hobbies` JSON NOT NULL DEFAULT '[]' AFTER `club`;
ALTER TABLE `patients` ADD `activo` INT NULL DEFAULT '1' AFTER `hobbies`;
ALTER TABLE `appointments` CHANGE `type` `type` TINYINT(4) NULL DEFAULT NULL COMMENT 'es: sucamec, kurame, terapia';
