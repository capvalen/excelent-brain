ALTER TABLE `extra_payments` ADD `voucher` VARCHAR(250) NULL AFTER `date`;
ALTER TABLE `payments` ADD `continuo` INT NULL DEFAULT '1' COMMENT '1= nuevo, 2=continuo' AFTER `updated_at`;
ALTER TABLE `extra_payments` ADD `continuo` INT NULL DEFAULT '1' COMMENT '1= nuevo, 2=continuo' AFTER `updated_at`;ALTER TABLE `extra_payments` ADD `voucher_issued` VARCHAR(250) NULL DEFAULT '' AFTER `voucher`;
ALTER TABLE `extra_payments` ADD `voucher_issued` VARCHAR(250) NULL DEFAULT '' AFTER `voucher`;
ALTER TABLE `users` ADD `nombre` VARCHAR(250) NULL DEFAULT '' AFTER `id`;
ALTER TABLE `users` ADD `privilegios` INT NULL DEFAULT '0' AFTER `rol`;
ALTER TABLE `users` ADD `activo` INT NULL DEFAULT '1' AFTER `updated_at`;
ALTER TABLE `prescriptions` ADD `professional_id` INT NULL DEFAULT NULL AFTER `signature`;
ALTER TABLE `burns` ADD `professional_id` INT NULL DEFAULT NULL AFTER `patient_id`;
ALTER TABLE `exams` ADD `professional_id` INT NULL DEFAULT NULL AFTER `exam`;
ALTER TABLE `scrs` ADD `professional_id` INT NULL AFTER `patient_id`;
ALTER TABLE `millons` ADD `professional_id` INT NULL AFTER `patient_id`;
ALTER TABLE `zung_depressions` ADD `professional_id` INT NULL AFTER `patient_id`;
ALTER TABLE `zung_anxieties` ADD `professional_id` INT NULL AFTER `patient_id`;
ALTER TABLE `appointments` CHANGE `status` `status` TINYINT(4) NULL DEFAULT NULL COMMENT '4=reprogramado';
ALTER TABLE `payments` CHANGE `pay_status` `pay_status` TINYINT(4) NULL DEFAULT NULL COMMENT '1=pend., 2=cancel, 3=anulado';
ALTER TABLE `extra_payments` ADD `user_id` INT NULL DEFAULT NULL AFTER `continuo`;
ALTER TABLE `payments` ADD `user_id` INT NULL DEFAULT NULL AFTER `continuo`;
ALTER TABLE `extra_payments` ADD `activo` INT NULL DEFAULT '1' AFTER `user_id`;
INSERT INTO `users` (`id`, `nombre`, `email`, `email_verified_at`, `password`, `rol`, `privilegios`, `remember_token`, `created_at`, `updated_at`, `activo`) VALUES (NULL, 'Luz Cano', 'luzcano@admin', NULL, '$2y$10$IkqdJbO6mJWWY9QfgfFGu.MudzgA7kDvZROQPvFTSF/p21bK3u1Oa', 'recepcionista', '1', NULL, NOW(), NULL, '1');
CREATE TABLE `recommendations` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `professional_id` int(11) NOT NULL,
 `comment` text NOT NULL,
 `date` datetime DEFAULT current_timestamp(),
 `patient_id` int(11) NOT NULL,
 `activo` int(11) DEFAULT 1,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1