INSERT INTO `seguimientos` (`id`, `seguimiento`, `color`, `icono`) VALUES (NULL, 'Reingreso', 'text-primary', '🔵');
UPDATE `seguimientos` SET `icono` = '🟣' WHERE `seguimientos`.`id` = 5;
DELETE FROM `schedules` WHERE `id` = 1167;
DELETE FROM `schedules` WHERE `id` = 1168;
DELETE FROM `schedules` WHERE `id` = 1169;
DELETE FROM `schedules` WHERE `id` = 1170;
DELETE FROM `schedules` WHERE `id` = 1171;
DELETE FROM `schedules` WHERE `id` = 1172;