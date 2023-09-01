INSERT INTO `tipo_pagos` (`id`, `descripcion`) VALUES (8, 'Adelanto de cita');
ALTER TABLE `interesados` ADD `idProfesional` INT NOT NULL AFTER `idPaciente`;
ALTER TABLE `interesados` CHANGE `idProfesional` `idProfesional` INT(11) NULL DEFAULT '1';
INSERT INTO `professionals` (`id`, `name`, `nombre`, `phone`, `idProfesion`, `profession`, `cv_description`, `photo`, `signing`, `user_id`, `created_at`, `updated_at`, `activo`) VALUES ('1', 'Ninguno', 'Ninguno', NULL, '1', NULL, NULL, NULL, NULL, '1', NULL, NOW(), '0');
ALTER TABLE `interesados` ADD `respuesta` TEXT NULL DEFAULT '' AFTER `idProfesional`;
ALTER TABLE `interesados` ADD `origen` INT NOT NULL DEFAULT '1' COMMENT '1=Manual, 2=Cartera, 3=anulacion cita' AFTER `idProfesional`;
ALTER TABLE `interesados` ADD `idUsuario` INT NOT NULL AFTER `idProfesional`;