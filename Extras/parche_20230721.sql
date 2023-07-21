UPDATE `relatives` SET `name` = null where name = 'null';
UPDATE `relatives` SET `phone` = null where phone = 'null';
UPDATE `relatives` SET `kinship` = null where kinship = 'null';
ALTER TABLE `appointments` ADD `byDoctor` TINYINT NOT NULL DEFAULT '0' COMMENT '0=recep, 1=doctor' AFTER `verAviso`;
CREATE TABLE `archivos` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `patient_id` int(11) NOT NULL,
 `fecha` datetime DEFAULT current_timestamp(),
 `nombre` text NOT NULL DEFAULT '',
 `archivo` varchar(1000) NOT NULL,
 `activo` tinyint(4) DEFAULT 1,
 `user_id` tinyint(4) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;