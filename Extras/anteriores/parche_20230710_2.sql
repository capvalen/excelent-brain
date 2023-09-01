ALTER TABLE `interesados` ADD `idPaciente` INT NULL DEFAULT '0' AFTER `correo`;
UPDATE `patients` SET `discharge`=0 where `discharge` is NULL;
ALTER TABLE `appointments` ADD `verAviso` INT NULL DEFAULT '1' AFTER `formato_nuevo`;