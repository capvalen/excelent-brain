INSERT INTO `precios_clasificacion` (`id`, `clasificacion`) VALUES ('7', 'Terapista'), ('8', 'Tecnólogo');
UPDATE `professionals` SET `idProfesion`=6 where `profession`='Nutricionista';

INSERT INTO `precios` (`id`, `descripcion`, `nuevos`, `continuos`, `especialMembresias`, `idClasificacion`, `sesiones`, `servicio`, `activo`) VALUES
(NULL, 'Terápia incial - Terapista', '0', '0', '0', '7', '0', '1', '1'),
(NULL, 'Terápia contínua - Terapista', '0', '0', '0', '7', '0', '1', '1'),
(NULL, 'Terápia incial - Tec. Médico', '0', '0', '0', '8', '0', '1', '1'),
(NULL, 'Terápia contínua - Tec. Médico', '0', '0', '0', '8', '0', '1', '1');