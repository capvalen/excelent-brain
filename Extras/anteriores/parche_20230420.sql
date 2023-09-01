INSERT INTO `relatives`(`id`, `name`, `phone`, `kinship`, `patient_id`, `created_at`, `updated_at`) select null,null,null,null, p.id,now(),now() FROM patients p LEFT JOIN relatives r ON p.id = r.patient_id WHERE r.patient_id IS NULL;
ALTER TABLE `patients` CHANGE `phone` `phone` VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `tipo` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `payment_method` (`id`, `tipo`) VALUES
(1, 'Efectivo'),
(2, 'Depósito bancario'),
(3, 'POS'),
(4, 'Aplicativo Yape/Plin');

ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

