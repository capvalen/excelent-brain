ALTER TABLE `triaje` ADD `responsable` VARCHAR(250) NULL DEFAULT NULL AFTER `especialista`, ADD `fv` VARCHAR(50) NULL DEFAULT NULL AFTER `responsable`, ADD `fc` VARCHAR(50) NULL DEFAULT NULL AFTER `fv`, ADD `fr` VARCHAR(50) NULL DEFAULT NULL AFTER `fc`, ADD `pa` VARCHAR(50) NULL DEFAULT NULL AFTER `fr`, ADD `t` VARCHAR(50) NULL DEFAULT NULL AFTER `pa`;
ALTER TABLE `triaje` ADD `pruebas` VARCHAR(250) NULL AFTER `responsable`;
ALTER TABLE `triaje` ADD `referencia` INT NOT NULL COMMENT '1=psicologia; 2=psiquiatria' AFTER `pruebas`;
ALTER TABLE `triaje` CHANGE `motivo` `motivo` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `triaje` CHANGE `sintomatologia` `sintomatologia` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `triaje` CHANGE `antecedentes` `antecedentes` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `triaje` CHANGE `especialista` `especialista` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
ALTER TABLE `triaje` CHANGE `referencia` `referencia` INT(11) NULL DEFAULT '1' COMMENT '1=psicologia; 2=psiquiatria';
ALTER TABLE `triaje` CHANGE `prioridad` `prioridad` INT(11)  NULL DEFAULT '4';