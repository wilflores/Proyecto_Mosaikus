ALTER TABLE `mos_registro`
DROP COLUMN `correlativo`,
DROP INDEX `ind01` ,
ADD UNIQUE INDEX `ind01` (`idRegistro`, `IDDoc`, `identificacion`, `version`) USING BTREE ;

ALTER TABLE `mos_registro_formulario`
DROP COLUMN `IDCodigoCate`;

ALTER TABLE `mos_registro_formulario`
MODIFY COLUMN `tipo`  char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `Nombre`;

-- deris roles

INSERT INTO `mos_nombres_campos` VALUES ('242', 'cod_perfil', 'Codigo Perfil', '19', 'cod_perfil');
INSERT INTO `mos_nombres_campos` VALUES ('243', 'descripcion_perfil', 'Descripcion Perfil', '19', 'descripcion_perfil');
INSERT INTO `mos_nombres_campos` VALUES ('244', 'nuevo', 'Acceso a Nuevo', '19', 'nuevo');
INSERT INTO `mos_nombres_campos` VALUES ('245', 'modificar', 'Acceso a Modificar', '19', 'modificar');
INSERT INTO `mos_nombres_campos` VALUES ('246', 'eliminar', 'Acceso a Eliminar', '19', 'eliminar');
INSERT INTO `mos_nombres_campos` VALUES ('247', 'recordatorio', 'Acceso a Recordatorio', '19', 'recordatorio');
INSERT INTO `mos_nombres_campos` VALUES ('248', 'modificar_terceros', 'Nombres', '19', 'modificar_terceros');
INSERT INTO `mos_nombres_campos` VALUES ('249', 'visualizar_terceros', 'Visualizar de Terceros', '19', 'visualizar_terceros');
INSERT INTO `mos_nombres_campos` VALUES ('250', 'cod_perfil', 'Codigo Perfil', '21', 'cod_perfil');
INSERT INTO `mos_nombres_campos` VALUES ('251', 'descripcion_perfil', 'Descripcion Perfil', '21', 'descripcion_perfil');
INSERT INTO `mos_nombres_campos` VALUES ('252', 'visualizar_terceros', 'Visualizar de Terceros', '21', 'visualizar_terceros');
INSERT INTO `mos_nombres_campos` VALUES ('253', 'visualizar_terceros', 'Visualizar de Terceros', '21', 'visualizar_terceros');
INSERT INTO `mos_nombres_campos` VALUES ('254', 'id_usuario', 'id_usuario', '21', 'id_usuario');
INSERT INTO `mos_nombres_campos` VALUES ('255', 'nombres', 'Nombres', '21', 'nombres');
INSERT INTO `mos_nombres_campos` VALUES ('256', 'apellido_paterno', 'Apellido Paterno', '21', 'apellido_paterno');
INSERT INTO `mos_nombres_campos` VALUES ('257', 'apellido_materno', 'Apellido Materno', '21', 'apellido_materno');
INSERT INTO `mos_nombres_campos` VALUES ('258', 'telefono', 'Telefono', '21', 'telefono');
INSERT INTO `mos_nombres_campos` VALUES ('259', 'fecha_expi', 'Fecha de Expiracion', '21', 'fecha_expi');
INSERT INTO `mos_nombres_campos` VALUES ('260', 'vigencia', 'Vigencia', '21', 'vigencia');
INSERT INTO `mos_nombres_campos` VALUES ('261', 'super_usuario', 'Super Usuario', '21', 'super_usuario');
INSERT INTO `mos_nombres_campos` VALUES ('262', 'email', 'email', '21', 'email');
INSERT INTO `mos_nombres_campos` VALUES ('263', 'password_1', 'Password', '21', 'password_1');
INSERT INTO `mos_nombres_campos` VALUES ('264', 'cedula', 'Cedula', '21', 'cedula');

-- melvin items temporales

INSERT INTO `mos_nombres_campos` VALUES ('265', 'id', 'id', '22', 'id');
INSERT INTO `mos_nombres_campos` VALUES ('266', 'fk_id_unico', 'fk_id_unico', '22', 'fk_id_unico');
INSERT INTO `mos_nombres_campos` VALUES ('267', 'descripcion', 'Descripción', '22', 'descripcion');
INSERT INTO `mos_nombres_campos` VALUES ('268', 'vigencia', 'Vigencia', '22', 'vigencia');
INSERT INTO `mos_nombres_campos` VALUES ('269', 'tipo', 'tipo', '22', 'tipo');

DROP TABLE IF EXISTS `mos_documentos_formulario_items`;
CREATE TABLE `mos_documentos_formulario_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_unico` int(11) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `vigencia` varchar(2) DEFAULT 'S',
  `tipo` int(11) DEFAULT NULL COMMENT '7 => Seleccion Simple\r\n8 => Seleccion Multiple\r\n9 => Combo',
  PRIMARY KEY (`id`),
  KEY `fk_id_unico` (`fk_id_unico`),
  CONSTRAINT `mos_documentos_formulario_items_ibfk_1` FOREIGN KEY (`fk_id_unico`) REFERENCES `mos_documentos_datos_formulario` (`id_unico`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `mos_documentos_formulario_items_temp`;
CREATE TABLE `mos_documentos_formulario_items_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_unico` int(11) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `vigencia` varchar(2) DEFAULT 'S',
  `tipo` int(11) DEFAULT NULL COMMENT '7 => Seleccion Simple\r\n8 => Seleccion Multiple\r\n9 => Combo',
  `fk_id_item` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL COMMENT '0 => Sin Cambios\r\n1 => Nuevo\r\n2 => Editar\r\n3 => Eliminar',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_usuario` int(11) DEFAULT NULL,
  `tok` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_unico` (`fk_id_unico`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=latin1;

-- campo publico documento 07/03/2016

ALTER TABLE `mos_documentos`
ADD COLUMN `publico`  char(1) NULL DEFAULT 'N' AFTER `aprobo`;

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('publico', 'Público', '6');

-- cambio en mos_roganizacon

ALTER TABLE `mos_organizacion`
CHANGE COLUMN `left` `left_a`  bigint(20) UNSIGNED NOT NULL AFTER `position`;

ALTER TABLE `mos_organizacion`
CHANGE COLUMN `right` `right_a`  bigint(20) UNSIGNED NOT NULL AFTER `left_a`;

delete from mos_cargo_estrorg_arbolproc where id not in (select id from  mos_organizacion);

ALTER TABLE `mos_cargo_estrorg_arbolproc`
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `mos_organizacion`
MODIFY COLUMN `id`  int(9) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ,
MODIFY COLUMN `parent_id`  int(9) UNSIGNED NOT NULL AFTER `id`;

ALTER TABLE `mos_organizacion`
ADD UNIQUE INDEX (`id`) ;

ALTER TABLE `mos_organizacion`
MODIFY COLUMN `id`  int(9) NOT NULL AUTO_INCREMENT FIRST ;


ALTER TABLE `mos_cargo_estrorg_arbolproc` ADD FOREIGN KEY (`id`) REFERENCES `mos_organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mos_documentos_estrorg_arbolproc` ADD FOREIGN KEY (`id_organizacion_proceso`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_personal` ADD FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_acciones_correctivas` ADD FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_correcciones` ADD FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_cargo_estrorg_arbolproc` DROP FOREIGN KEY `FK_REFERENCE_18`;

ALTER TABLE `mos_cargo_estrorg_arbolproc` ADD CONSTRAINT `FK_REFERENCE_18` FOREIGN KEY (`cod_cargo`) REFERENCES `mos_cargo` (`cod_cargo`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `mos_arbol_procesos`
CHANGE COLUMN `left` `left_a`  bigint(20) UNSIGNED NOT NULL AFTER `position`,
CHANGE COLUMN `right` `right_a`  bigint(20) UNSIGNED NOT NULL AFTER `left_a`;


ALTER TABLE `mos_arbol_procesos`
ADD COLUMN `id_organizacion`  int NULL AFTER `type`;

ALTER TABLE `mos_arbol_procesos`
MODIFY COLUMN `id`  bigint(20) NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `mos_arbol_procesos`
MODIFY COLUMN `id`  int(20) NOT NULL AUTO_INCREMENT FIRST ,
MODIFY COLUMN `parent_id`  int(20) UNSIGNED NOT NULL AFTER `id`;

ALTER TABLE `mos_arbol_procesos`
ENGINE=InnoDB;


ALTER TABLE `mos_arbol_procesos` ADD FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_acciones_correctivas` ADD FOREIGN KEY (`id_proceso`) REFERENCES `mos_arbol_procesos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_correcciones` ADD CONSTRAINT `mos_correcciones_ibfk_2` FOREIGN KEY (`id_proceso`) REFERENCES `mos_arbol_procesos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


DROP TABLE IF EXISTS `mos_arbol_procesos_nombres`;
CREATE TABLE `mos_arbol_procesos_nombres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

insert into mos_arbol_procesos_nombres (id,title) select id, title from mos_arbol_procesos;

DROP TABLE IF EXISTS `mos_organizacion_nombres`;
CREATE TABLE `mos_organizacion_nombres` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

insert into mos_organizacion_nombres (id,title) select id, title from mos_organizacion;

DROP TRIGGER IF EXISTS `actualiza_nombre_proceso_ins`;
DELIMITER ;;
CREATE TRIGGER `actualiza_nombre_proceso_ins` BEFORE INSERT ON `mos_arbol_procesos_nombres` FOR EACH ROW UPDATE mos_arbol_procesos SET title = NEW.title WHERE id = NEW.id
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `actualiza_nombre_proceso`;
DELIMITER ;;
CREATE TRIGGER `actualiza_nombre_proceso` BEFORE UPDATE ON `mos_arbol_procesos_nombres` FOR EACH ROW UPDATE mos_arbol_procesos SET title = NEW.title WHERE id = NEW.id
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `actualizar_nombre_area_ins`;
DELIMITER ;;
CREATE TRIGGER `actualizar_nombre_area_ins` BEFORE INSERT ON `mos_organizacion_nombres` FOR EACH ROW UPDATE mos_organizacion SET title = NEW.title WHERE id = NEW.id
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `actualizar_nombre_area`;
DELIMITER ;;
CREATE TRIGGER `actualizar_nombre_area` BEFORE UPDATE ON `mos_organizacion_nombres` FOR EACH ROW UPDATE mos_organizacion SET title = NEW.title WHERE id = NEW.id
;;
DELIMITER ;

-- cambios en documentos

ALTER TABLE `mos_documentos_estrorg_arbolproc`
DROP INDEX `ind02`;


ALTER TABLE  `mos_documentos_estrorg_arbolproc` DROP FOREIGN KEY  `mos_documentos_estrorg_arbolproc_ibfk_1`;

ALTER TABLE `mos_documentos_estrorg_arbolproc` ADD FOREIGN KEY (`id_organizacion_proceso`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_personal`
DROP INDEX `ind02`,
DROP INDEX `ind03`,
DROP INDEX `ind04`,
DROP INDEX `Ind05`;

ALTER TABLE `mos_personal` DROP FOREIGN KEY `mos_personal_ibfk_1`;

ALTER TABLE `mos_personal` ADD FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_cargo_estrorg_arbolproc` DROP FOREIGN KEY `FK_REFERENCE_18`;

ALTER TABLE `mos_cargo_estrorg_arbolproc` DROP FOREIGN KEY `mos_cargo_estrorg_arbolproc_ibfk_1`;

ALTER TABLE `mos_cargo_estrorg_arbolproc` ADD FOREIGN KEY (`cod_cargo`) REFERENCES `mos_cargo` (`cod_cargo`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_cargo_estrorg_arbolproc` ADD FOREIGN KEY (`id`) REFERENCES `mos_organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mos_arbol_procesos` DROP FOREIGN KEY `mos_arbol_procesos_ibfk_1`;


ALTER TABLE `mos_arbol_procesos` ADD FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_acciones_correctivas` ADD FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_acciones_correctivas` ADD FOREIGN KEY (`id_proceso`) REFERENCES `mos_arbol_procesos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_correcciones` DROP FOREIGN KEY `mos_correcciones_ibfk_1`;

ALTER TABLE `mos_correcciones` DROP FOREIGN KEY `mos_correcciones_ibfk_2`;


DROP TRIGGER IF EXISTS `libera_areas`;

DELIMITER ;;
CREATE TRIGGER `libera_areas` BEFORE UPDATE ON `mos_arbol_procesos`
FOR EACH ROW BEGIN 
      IF (NEW.level > 2) THEN
            SET new.id_organizacion = NULL;
     END IF;
   
END;

;;
DELIMITER ;

-- ajustes en personal 14/04/2016

update mos_personal set email = NULL where email = '';

ALTER TABLE `mos_personal`
ADD UNIQUE INDEX (`email`) ;

INSERT INTO `mos_parametro_categoria` (`cod_categoria`, `descripcion`, `nombre_div`, `activo`) VALUES ('14', 'Inspecciones', 'Inspecciones', 'S');

INSERT INTO `mos_link` (`nombre_link`, `dependencia`, `tipo`, `orden`) VALUES ('Inspecciones', '0', '1', '10');
UPDATE `mos_link` SET `cod_link`='83' WHERE (`cod_link`='0');
INSERT INTO `mos_link` (`cod_link`, `nombre_link`, `dependencia`, `tipo`, `orden`) VALUES ('84', 'Plantillas de Inspecciones', '83', '3', '2');
-- INSERT INTO `mos_link_por_perfil` (`cod_perfil`, `cod_link`) VALUES ('1', '83');
-- INSERT INTO `mos_link_por_perfil` (`cod_perfil`, `cod_link`) VALUES ('1', '84');
UPDATE `mos_link` SET `imagen`='planesp' WHERE (`cod_link`='83');
UPDATE `mos_link` SET `descripcion`='PlantilaInspecciones-indexPlantilaInspecciones-clases.plantilla_inspecciones.PlantilaInspecciones' WHERE (`cod_link`='84');

-- ALTER TABLE `mos_tipo_inspecciones` ADD COLUMN `codigo`  varchar(50) NULL AFTER `id`;

ALTER TABLE `mos_log`
ADD COLUMN `id_registro`  int NULL AFTER `id`;

ALTER TABLE `mos_log`
MODIFY COLUMN `accion`  text NULL AFTER `fecha_hora`,
MODIFY COLUMN `anterior`  text NULL AFTER `accion`;

ALTER TABLE `mos_documentos_formulario_items_temp`
ADD COLUMN `descripcion_larga`  text NULL AFTER `tok`,
ADD COLUMN `peso`  int NULL DEFAULT 0 AFTER `descripcion_larga`;

ALTER TABLE `mos_documentos_formulario_items_temp`
ADD COLUMN `orden`  int NULL AFTER `peso`;



