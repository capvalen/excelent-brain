ALTER TABLE `reschedules` ADD `fechaProxima` VARCHAR(12) NULL DEFAULT '' AFTER `reason`;
UPDATE `relatives` SET `name` = '' where name is null;
UPDATE `relatives` SET `name` = '' where name = 'null';
UPDATE `relatives` SET `phone` = '' where phone is null;
UPDATE `relatives` SET `phone` = '' where phone = 'null';
UPDATE `relatives` SET `kinship` = '' where kinship is null;
UPDATE `relatives` SET `kinship` = '' where kinship = 'null';