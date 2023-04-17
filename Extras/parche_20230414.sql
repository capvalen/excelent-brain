
ALTER TABLE `patients` CHANGE `gender` `gender` TINYINT(4) NULL DEFAULT NULL COMMENT '0=female; 1=male';
CREATE TABLE `triaje` (`id` INT NOT NULL AUTO_INCREMENT , `patient_id` INT NOT NULL , `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `motivo` TEXT NOT NULL , `sintomatologia` TEXT NOT NULL , `antecedentes` TEXT NOT NULL , `prioridad` INT NOT NULL , `especialista` VARCHAR(250) NOT NULL , `activo` TINYINT NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `triaje` CHANGE `fecha` `fecha` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `triaje` CHANGE `activo` `activo` TINYINT(4) NULL DEFAULT '1';
ALTER TABLE `triaje` CHANGE `id` `id` INT(11) NULL AUTO_INCREMENT;
ALTER TABLE `patients` CHANGE `dni` `dni` VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `patients` ADD `type_dni` INT NULL DEFAULT '1' COMMENT '1=dni,2=carnet;3=pasaporte' AFTER `faults`;


/*

:: Lugares que se crean cuando registramos un nuevo paciente:
- Tabla addresses, donde va la dirección del paciente
- Tabla appointments, acá es donde se debe agregar un operador lógico para eliminar cita
- Tabla patients, donde va el nombre
- Tabla payments, por defecto ya le agrega un pago en modo pendiente
- Tabla relatives, parece que ya estaba planificado para crear un parentesco auxiliar, pero se inserta todo en blanco


:: Cuando se paga se hace lo siguiente:
- En la tabla paymets, el pay_status pasa 1 a 2 cuando se cancela el pago de la cita, luego se inserta en observacion. Ya hay campos para el banco o el método de pago

:: Cuando se elimina la cita, actualmente con el error se hace lo siguiente:
- De la tabla appointments, se borra realmente el registro que se genero como cita

:: Cuando se reasigna una nueva fecha
- En la tabla appointments, se modifica los valores del profesional, fecha, y hora
- En la tabla reschedules, se registra el motivo exacto por el cual se esta reprogramando -> hacer visible en algún lugar

*/