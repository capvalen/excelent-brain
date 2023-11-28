ALTER TABLE `payment_method` ADD `activo` INT NULL DEFAULT '1' AFTER `tipo`;
UPDATE `seguimientos` SET `seguimiento` = 'Dado de alta (Desde recepción)' WHERE `seguimientos`.`id` = 4;
INSERT INTO `seguimientos` (`id`, `seguimiento`, `color`) VALUES (NULL, 'Dado de alta (Por profesional)', 'text-success');
ALTER TABLE `patients` CHANGE `gender` `gender` TINYINT(4) NULL DEFAULT NULL COMMENT '0=female; 1=male; 2=not def. 3=lgtb';
