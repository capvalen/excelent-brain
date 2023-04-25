ALTER TABLE `extra_payments` CHANGE `type` `type` TINYINT(4) NULL DEFAULT NULL COMMENT '5=cobro de cita';
ALTER TABLE `extra_payments` CHANGE `date` `date` DATE NULL DEFAULT CURRENT_TIMESTAMP;
