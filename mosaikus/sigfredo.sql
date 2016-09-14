-- 15/08/2016
-- Actualizacion del menu para la vista de cargos en modo portal
-- UPDATE `mos_link_portal` SET descripcion = 'Cargos-indexCargosReporte-clases.cargo.Cargos' WHERE cod_link =21;

-- 28/08/2016
-- TRIGGER Para actualizar mos_usuario cuando se modifique mos_personal
DROP TRIGGER IF EXISTS `actualiza_mos_personal_mos_usuario`;
DELIMITER ;;
CREATE TRIGGER `actualiza_mos_personal_mos_usuario` AFTER UPDATE ON `mos_personal` FOR EACH ROW UPDATE `mos_usuario` set nombres=NEW.nombres, apellido_paterno=NEW.apellido_paterno, apellido_materno=NEW.apellido_materno,email=NEW.email, cedula = NEW.id_personal WHERE email=OLD.email;
;;
DELIMITER ;

-- 14/09/2019
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('547', 'desvio', 'Acción Acordada', '16', 'Acción Acordada', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('548', 'trazabilidad_accion', 'Trazabildiad Acción', '16', 'Trazabildiad Acción', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('549', 'descripcion', 'Descripción', '16', 'Descripción', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('550', 'id', 'id', '16', 'id', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('551', 'fecha_generacion', 'Fecha de Generación', '16', 'Fecha de Generación', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('552', 'accion', 'Acción', '16', 'Acción', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('553', 'fecha_acordada', 'Fecha Acordada', '16', 'Fecha Acordada', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('554', 'fecha_realizada', 'Fecha Realizada', '16', 'Fecha Realizada', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('555', 'id_responsable', 'Responsable', '16', 'Responsable', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('556', 'tipo', 'Tipo', '16', 'Tipo', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('557', 'accion_ejecutada', 'Acción Ejecutada', '16', 'Acción Ejecutada', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('558', 'asunto_accion_rechazada', 'Acción Rechazada', '16', 'Acción Rechazada', '1');
INSERT INTO `santateresa`.`mos_nombres_campos` (`id`, `nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('559', 'asunto_accion_pendiente', 'Tiene una Acción Pendiente de Validación', '16', NULL, '1');
