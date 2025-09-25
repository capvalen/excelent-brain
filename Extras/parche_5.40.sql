ALTER TABLE `medical_evolutions` ADD `treatment` TEXT NULL DEFAULT NULL AFTER `diagnostic`;
ALTER TABLE medical_evolutions ADD diagnostic JSON DEFAULT (JSON_ARRAY()) AFTER treatment;