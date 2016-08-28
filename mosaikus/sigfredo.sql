-- 15/08/2016
-- Actualizacion del menu para la vista de cargos en modo portal
-- UPDATE `mos_link_portal` SET descripcion = 'Cargos-indexCargosReporte-clases.cargo.Cargos' WHERE cod_link =21;

-- 28/08/2016
-- TRIGGER Para actualizar mos_usuario cuando se modifique mos_personal
DROP TRIGGER IF EXISTS `actualiza_mos_personal_mos_usuario`;
DELIMITER ;;
CREATE TRIGGER `actualiza_mos_personal_mos_usuario` AFTER UPDATE ON `mos_personal` FOR EACH ROW UPDATE `mos_usuario` set nombres=NEW.nombres, apellido_paterno=NEW.apellido_paterno, apellido_materno=NEW.apellido_materno,email=NEW.email WHERE email=OLD.email;
;;
DELIMITER ;

