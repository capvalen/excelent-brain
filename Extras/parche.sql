--versión 5.56
ALTER TABLE `extra_payments` ADD `patient_id` INT NULL AFTER `id`;
UPDATE extra_payments e1
INNER JOIN appointments a ON a.id = e1.appointment_id
SET e1.patient_id = a.patient_id
WHERE e1.appointment_id IS NOT NULL 
  AND e1.appointment_id > 0;
UPDATE extra_payments e1
INNER JOIN membresias m ON m.id = e1.idMembresia
SET e1.patient_id = m.patient_id
WHERE e1.appointment_id = 0
  AND e1.idMembresia > 0;