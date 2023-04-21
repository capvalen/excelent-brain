ALTER TABLE `patients` CHANGE `phone` `phone` VARCHAR(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '';
ALTER TABLE `patients` CHANGE `gender` `gender` TINYINT(4) NULL DEFAULT NULL COMMENT '0=female; 1=male; 2=undefined';
