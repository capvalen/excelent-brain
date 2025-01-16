ALTER TABLE `users` ADD `idSede` INT NULL DEFAULT '1' AFTER `privilegios`;
ALTER TABLE `extra_payments` ADD `idSede` INT NULL DEFAULT '1' AFTER `tipo`;
ALTER TABLE `payments` ADD `idSede` INT NULL DEFAULT '1' AFTER `user_id`;