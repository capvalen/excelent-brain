ALTER TABLE `extra_payments` ADD `correlativo` INT NULL DEFAULT '0' AFTER `idSede`;

DELIMITER $$
CREATE TRIGGER set_correlativo
BEFORE INSERT ON extra_payments
FOR EACH ROW
BEGIN
    -- Calcula el nuevo valor del campo correlativo
    SET NEW.correlativo = (
        SELECT COUNT(*) 
        FROM extra_payments 
        WHERE idSede = NEW.idSede
    ) + 1;
END$$

DELIMITER ;