CREATE TABLE `nutriciones` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `idPaciente` int(11) NOT NULL,
 `peso` varchar(250) NOT NULL,
 `talla` varchar(250) NOT NULL,
 `imc` varchar(250) NOT NULL,
 `perimetro` varchar(250) NOT NULL,
 `grasa` varchar(250) NOT NULL,
 `comidas` int(11) NOT NULL,
 `intolerancia` int(11) NOT NULL COMMENT '1=si, 0=no',
 `alergias` int(11) NOT NULL COMMENT '1=si, 0=no',
 `fuma` int(11) NOT NULL COMMENT '1=si, 0=no',
 `bebe` int(11) NOT NULL COMMENT '1=si, 0=no',
 `signos` text NOT NULL,
 `diagnostico` text NOT NULL,
 `dieta` text NOT NULL,
 `suplemento` text NOT NULL,
 `activo` int(11) DEFAULT 1,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `nutriciones` ADD `creado` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `suplemento`;
ALTER TABLE `nutriciones` CHANGE `signos` `signos` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '\'\'';
ALTER TABLE `nutriciones` CHANGE `diagnostico` `diagnostico` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '\'\'';
ALTER TABLE `nutriciones` CHANGE `dieta` `dieta` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '\'\'';
ALTER TABLE `nutriciones` CHANGE `suplemento` `suplemento` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '\'\'';
