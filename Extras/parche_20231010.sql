ALTER TABLE `recordatorios` ADD `idResponsable` INT NOT NULL DEFAULT '1' AFTER `actividad`;
ALTER TABLE `faltas` ADD `esFalta` INT NULL DEFAULT '1' COMMENT '1=falta, 2=eliminado' AFTER `observaciones`;
