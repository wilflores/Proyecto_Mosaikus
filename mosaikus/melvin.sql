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

-- nuevo 22-04-2016
ALTER TABLE `mos_historico_wf_documentos`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `mos_historico_wf_documentos`
MODIFY COLUMN `fecha_registro`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `IDDoc`;



update mos_documentos
set mos_documentos.etapa_workflow='estado_aprobado',
mos_documentos.estado_workflow='OK',
mos_documentos.fecha_estado_workflow=CURRENT_TIMESTAMP(),
mos_documentos.id_usuario_workflow= elaboro;

-- correo unico
ALTER TABLE `mos_usuario`
DROP INDEX `ind05` ,
ADD UNIQUE INDEX `ind05` (`email`) USING BTREE ;

UPDATE `mos_link` SET `nombre_link`='Administrador de Perfiles' WHERE (`cod_link`='66');
UPDATE `mos_link` SET `nombre_link`='Perfiles Especialistas' WHERE (`cod_link`='80');
UPDATE `mos_link` SET `nombre_link`='Perfiles Portal' WHERE (`cod_link`='81');

ALTER TABLE `mos_documentos`
MODIFY COLUMN `observacion`  text NULL AFTER `id_usuario`;

/*atributo responsable area*/

ALTER TABLE `mos_personal`
ADD COLUMN `responsable_area`  varchar(2) NULL DEFAULT 'N' AFTER `cod_contratista`;

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('responsable_area', 'Responsable de Área', '1');
UPDATE `mos_nombres_campos` SET `texto`='ID', `placeholder`='Nº Documento de Identidad' WHERE (`id`='1');

/*Dar permiso a un area a un usuario segun perfil */

CREATE TRIGGER `permisos_perfil_usuarios` AFTER INSERT ON `mos_organizacion`
FOR EACH ROW INSERT into mos_usuario_estructura(id_usuario_filial,id_estructura, id_usuario,cod_perfil,portal)
select id_usuario_filial,NEW.id,id_usuario,cod_perfil,portal from mos_usuario_estructura 
where id_estructura = NEW.parent_id;;



/**/

/* CAMBIOS WF DOCUMENTOS*/
DROP TRIGGER `registra_mos_historico_wf_documentos`;

DROP TRIGGER `registra_mos_historico_wf_documentos_cambio`;

DELIMITER ;;

CREATE TRIGGER `registra_mos_historico_wf_documentos` AFTER INSERT ON `mos_documentos`
FOR EACH ROW BEGIN
/*guarda historico al insertar un doc*/
         DECLARE etapa text;  
			IF(NEW.id_workflow_documento is not null)THEN
				set etapa= (SELECT
				IFNULL(mos_nombres_campos.texto,'')
				FROM
				mos_nombres_campos
				WHERE
				mos_nombres_campos.modulo = 6 AND
				mos_nombres_campos.nombre_campo = NEW.etapa_workflow);

        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,CONCAT('CREADO:  ',IFNULL(NEW.estado_workflow,''),' ',etapa ),NEW.id_usuario_workflow);
			END IF;
END;

;;
DELIMITER ;

DELIMITER ;;

CREATE TRIGGER `registra_mos_historico_wf_documentos_cambio` BEFORE UPDATE ON `mos_documentos`
FOR EACH ROW BEGIN
/*guarda historico al modificar un doc si cambian los datos del wf*/
        DECLARE etapa text;  
				set etapa= (SELECT
				IFNULL(mos_nombres_campos.texto,'')
				FROM
				mos_nombres_campos
				WHERE
				mos_nombres_campos.modulo = 6 AND
				mos_nombres_campos.nombre_campo = NEW.etapa_workflow);

			IF((NEW.etapa_workflow<>OLD.etapa_workflow) or (NEW.estado_workflow<>OLD.estado_workflow)) THEN
        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambió a ',etapa),NEW.id_usuario_workflow);
			END IF;
			IF(OLD.etapa_workflow is Null and NEW.etapa_workflow<>'') THEN
        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambió a ',etapa),NEW.id_usuario_workflow);
			END IF;
END;

;;
DELIMITER ;
/*FIN CAMBIOS*/

/*AJuste en menu documentos*/
INSERT INTO `mos_link` (`cod_link`, `nombre_link`) VALUES ('89', 'Configuración');
UPDATE `mos_link` SET `dependencia`='3', `tipo`='2', `orden`='18' WHERE (`cod_link`='89');
UPDATE `mos_link` SET `dependencia`='89' WHERE (`cod_link`='88');
UPDATE `mos_link` SET `dependencia`='89' WHERE (`cod_link`='75');
UPDATE `mos_link` SET `orden`='60' WHERE (`cod_link`='89');
/*FIN AJUSTE DOCUMENTO*/



/*Codigo Documentos*/
INSERT INTO `mos_link` (`cod_link`, `descripcion`, `nombre_link`, `dependencia`) VALUES ('90', 'DocumentoCodigos-indexDocumentoCodigos-clases.documento_codigos.DocumentoCodigos', 'Códigos de Areas', '3');
UPDATE `mos_link` SET `orden`='59' WHERE (`cod_link`='90');

ALTER TABLE `mos_organizacion_nombres`
MODIFY COLUMN `id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;
ALTER TABLE `mos_organizacion_nombres`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ;


ALTER TABLE `mos_acciones_correctivas` DROP FOREIGN KEY `mos_acciones_correctivas_ibfk_1`;

ALTER TABLE `mos_acciones_correctivas` ADD CONSTRAINT `mos_acciones_correctivas_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion_nombres` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_correcciones` DROP FOREIGN KEY `mos_correcciones_ibfk_1`;

ALTER TABLE `mos_correcciones` ADD CONSTRAINT `mos_correcciones_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion_nombres` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_personal` DROP FOREIGN KEY `mos_personal_ibfk_1`;

ALTER TABLE `mos_personal` ADD CONSTRAINT `mos_personal_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion_nombres` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_documentos_estrorg_arbolproc` DROP FOREIGN KEY `mos_documentos_estrorg_arbolproc_ibfk_1`;

ALTER TABLE `mos_documentos_estrorg_arbolproc` ADD CONSTRAINT `mos_documentos_estrorg_arbolproc_ibfk_1` FOREIGN KEY (`id_organizacion_proceso`) REFERENCES `mos_organizacion_nombres` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `mos_arbol_procesos` DROP FOREIGN KEY `mos_arbol_procesos_ibfk_1`;

ALTER TABLE `mos_arbol_procesos` ADD CONSTRAINT `mos_arbol_procesos_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion_nombres` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mos_cargo_estrorg_arbolproc` DROP FOREIGN KEY `mos_cargo_estrorg_arbolproc_ibfk_2`;

ALTER TABLE `mos_cargo_estrorg_arbolproc` ADD CONSTRAINT `mos_cargo_estrorg_arbolproc_ibfk_2` FOREIGN KEY (`id`) REFERENCES `mos_organizacion_nombres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE `mos_organizacion`
ADD COLUMN `area_espejo`  int NULL AFTER `type`;

ALTER TABLE `mos_organizacion_nombres`
ADD COLUMN `area_espejo`  int NULL AFTER `title`;

DROP TRIGGER `actualizar_nombre_area_ins`;

DROP TRIGGER `actualizar_nombre_area`;

DELIMITER ;;

CREATE TRIGGER `actualizar_nombre_area_ins` AFTER INSERT ON `mos_organizacion_nombres`
FOR EACH ROW begin
DECLARE padre INT;  
UPDATE mos_organizacion SET title = NEW.title  WHERE id = NEW.id;

SET padre =  (SELECT parent_id FROM mos_organizacion WHERE id = NEW.id);
 insert into mos_documentos_codigo (id_organizacion, codigo)
      select NEW.id, codigo
       from mos_documentos_codigo      
      where id_organizacion =padre; 

end;
;;
DELIMITER ;

DELIMITER ;;
CREATE TRIGGER `actualizar_nombre_area` BEFORE UPDATE ON `mos_organizacion_nombres`
FOR EACH ROW begin 
       UPDATE mos_organizacion SET title = NEW.title, area_espejo = NEW.area_espejo WHERE id = NEW.id;
       IF (NEW.title <> OLD.title) AND OLD.title = 'New Node' THEN
                UPDATE mos_documentos_codigo SET codigo = CONCAT(codigo,'_', UPPER(SUBSTR(NEW.title, 1, 3))) WHERE id_organizacion = NEW.id;
       END IF;
end;

;;
DELIMITER ;
DROP TRIGGER `permisos_perfil_usuarios`;


DELIMITER ;;
CREATE  TRIGGER `permisos_perfil_usuarios` BEFORE INSERT ON `mos_organizacion`
FOR EACH ROW Begin 
      SET NEW.title = (SELECT title FROM mos_organizacion_nombres WHERE id = NEW.ID);

      INSERT into mos_usuario_estructura(id_usuario_filial,id_estructura, id_usuario,cod_perfil,portal)
      select id_usuario_filial,NEW.id,id_usuario,cod_perfil,portal from mos_usuario_estructura 
      where id_estructura = NEW.parent_id;
     
     
END;
;;

DELIMITER ;



UPDATE `mos_link_portal` SET `descripcion`='Documentos-indexDocumentosFormulario-clases.documentos.Documentos-formulario' WHERE (`cod_link`='16');
UPDATE `mos_link` SET `dependencia`='89' WHERE (`cod_link`='90');
UPDATE `mos_link` SET `dependencia`='89' WHERE (`cod_link`='82');


DROP TABLE IF EXISTS `mos_documentos_codigo`;
CREATE TABLE `mos_documentos_codigo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_organizacion` int(11) DEFAULT NULL,
  `codigo` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `bloqueo_codigo` varchar(2) CHARACTER SET latin1 DEFAULT 'S',
  `bloqueo_version` varchar(2) CHARACTER SET latin1 DEFAULT 'S',
  `correlativo` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_organizacion` (`id_organizacion`),
  CONSTRAINT `mos_documentos_codigo_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion_nombres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

insert into mos_documentos_codigo(id_organizacion,codigo,bloqueo_codigo,bloqueo_version,correlativo)
select 
o.id,
concat(
IFNULL(concat(UPPER(SUBSTR(p4.title, 1, 3)),'_'),''),
IFNULL(concat(UPPER(SUBSTR(p3.title, 1, 3)),'_'),''),
IFNULL(concat(UPPER(SUBSTR(p2.title, 1, 3)),'_'),''), 
IFNULL(concat(UPPER(SUBSTR(p1.title, 1, 3)),'_'),''), 
IFNULL(concat(UPPER(SUBSTR(padre.title, 1, 3)),'_'),''), 
UPPER(SUBSTR(o.title, 1, 3)) 
) codigo,
'S',
'S',
IFNULL(d.total+1,1)
from mos_organizacion o
inner join mos_organizacion padre on padre.id = o.parent_id
left join mos_organizacion p1 on p1.id = padre.parent_id
left join mos_organizacion p2 on p2.id = p1.parent_id
left join mos_organizacion p3 on p3.id = p2.parent_id
left join mos_organizacion p4 on p4.id = p3.parent_id
left join (
	select da.id_organizacion_proceso, COUNT(DISTINCT d.IDDoc) total from mos_documentos_estrorg_arbolproc da
  INNER JOIN mos_documentos d on d.IDDoc = da.IDDoc
GROUP BY da.id_organizacion_proceso

) as d on d.id_organizacion_proceso = o.id;

INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id', 'id', '26', 'id');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id_organizacion', 'Árbol Organizacional', '26', 'id_organizacion');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('codigo', 'Código', '26', 'codigo');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('bloqueo_codigo', 'Bloqueo Código', '26', 'bloqueo_codigo');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('bloqueo_version', 'Bloqueo Versión', '26', 'bloqueo_version');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('correlativo', 'correlativo', '26', 'correlativo');

/**/

/* ERROR ARBOL**/

delete from mos_usuario_estructura
where id_estructura not in (select id from mos_organizacion);

DROP TABLE IF EXISTS `mos_usuario_estructura_temp`;
CREATE TABLE `mos_usuario_estructura_temp` (  
  `id_usuario_filial` int(11) NOT NULL,
  `id_estructura` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cod_perfil` int(11) NOT NULL,
  `portal` char(1) DEFAULT NULL
);

INSERT into mos_usuario_estructura_temp
select id_usuario_filial, id_estructura, id_usuario, cod_perfil, portal from mos_usuario_estructura
where id_estructura in (select id from mos_organizacion)
GROUP BY id_estructura, id_usuario_filial, id_usuario, cod_perfil, portal;

DELETE from mos_usuario_estructura;

ALTER TABLE `mos_usuario_estructura`
AUTO_INCREMENT=1;


INSERT into mos_usuario_estructura(id_usuario_filial, id_estructura, id_usuario, cod_perfil, portal) 
select id_usuario_filial, id_estructura, id_usuario, cod_perfil, portal from mos_usuario_estructura_temp;
DROP TABLE IF EXISTS `mos_usuario_estructura_temp`;

ALTER TABLE `mos_organizacion_nombres`
MODIFY COLUMN `id`  int(11) NOT NULL FIRST ;

ALTER TABLE `mos_acciones_correctivas` DROP FOREIGN KEY `mos_acciones_correctivas_ibfk_1`;

-- ALTER TABLE `mos_acciones_correctivas` ADD CONSTRAINT `mos_acciones_correctivas_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `mos_correcciones` DROP FOREIGN KEY `mos_correcciones_ibfk_1`;

-- ALTER TABLE `mos_correcciones` ADD CONSTRAINT `mos_correcciones_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `mos_personal` DROP FOREIGN KEY `mos_personal_ibfk_1`;

-- ALTER TABLE `mos_personal` ADD CONSTRAINT `mos_personal_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `mos_documentos_estrorg_arbolproc` DROP FOREIGN KEY `mos_documentos_estrorg_arbolproc_ibfk_1`;

-- ALTER TABLE `mos_documentos_estrorg_arbolproc` ADD CONSTRAINT `mos_documentos_estrorg_arbolproc_ibfk_1` FOREIGN KEY (`id_organizacion_proceso`) REFERENCES `mos_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `mos_arbol_procesos` DROP FOREIGN KEY `mos_arbol_procesos_ibfk_1`;

-- ALTER TABLE `mos_arbol_procesos` ADD CONSTRAINT `mos_arbol_procesos_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `mos_cargo_estrorg_arbolproc` DROP FOREIGN KEY `mos_cargo_estrorg_arbolproc_ibfk_2`;

-- ALTER TABLE `mos_cargo_estrorg_arbolproc` ADD CONSTRAINT `mos_cargo_estrorg_arbolproc_ibfk_2` FOREIGN KEY (`id`) REFERENCES `mos_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `mos_documentos_codigo` DROP FOREIGN KEY `mos_documentos_codigo_ibfk_1`;

-- ALTER TABLE `mos_documentos_codigo` ADD CONSTRAINT `mos_documentos_codigo_ibfk_1` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

DELETE from mos_documentos_codigo;
-- WHERE id_organizacion not IN (select id from mos_organizacion);
ALTER TABLE `mos_documentos_codigo`
AUTO_INCREMENT=1;
insert into mos_documentos_codigo(id_organizacion,codigo,bloqueo_codigo,bloqueo_version,correlativo)
select 
o.id,
concat(
IFNULL(concat(UPPER(SUBSTR(p4.title, 1, 3)),'_'),''),
IFNULL(concat(UPPER(SUBSTR(p3.title, 1, 3)),'_'),''),
IFNULL(concat(UPPER(SUBSTR(p2.title, 1, 3)),'_'),''), 
IFNULL(concat(UPPER(SUBSTR(p1.title, 1, 3)),'_'),''), 
IFNULL(concat(UPPER(SUBSTR(padre.title, 1, 3)),'_'),''), 
UPPER(SUBSTR(o.title, 1, 3)) 
) codigo,
'S',
'S',
IFNULL(d.total+1,1)
from mos_organizacion o
inner join mos_organizacion padre on padre.id = o.parent_id
left join mos_organizacion p1 on p1.id = padre.parent_id
left join mos_organizacion p2 on p2.id = p1.parent_id
left join mos_organizacion p3 on p3.id = p2.parent_id
left join mos_organizacion p4 on p4.id = p3.parent_id
left join (
	select da.id_organizacion_proceso, COUNT(DISTINCT d.IDDoc) total from mos_documentos_estrorg_arbolproc da
  INNER JOIN mos_documentos d on d.IDDoc = da.IDDoc
GROUP BY da.id_organizacion_proceso

) as d on d.id_organizacion_proceso = o.id;

DROP TRIGGER IF EXISTS `actualizar_nombre_area_ao`;

DROP TRIGGER IF EXISTS `permisos_perfil_usuarios`;

DELIMITER ;;
CREATE TRIGGER `actualizar_nombre_area_ao` BEFORE INSERT ON `mos_organizacion`
FOR EACH ROW Begin 
      SET NEW.title = (SELECT title FROM mos_organizacion_nombres WHERE id = NEW.ID);

      

/*SET padre =  (SELECT parent_id FROM mos_organizacion WHERE id = NEW.id)
 insert into mos_documentos_codigo (id_organizacion, codigo)
      select NEW.id, codigo
       from mos_documentos_codigo      
      where id_organizacion =NEW.parent_id; 
     */
     
END;
;;
DELIMITER ;
DELIMITER ;;
CREATE TRIGGER `permisos_perfil_usuarios` AFTER INSERT ON `mos_organizacion`
FOR EACH ROW begin
       IF NEW.id NOT IN ( SELECT id_estructura FROM mos_usuario_estructura WHERE id_estructura = NEW.id) THEN
               INSERT into mos_usuario_estructura(id_usuario_filial,id_estructura, id_usuario,cod_perfil,portal)
               select id_usuario_filial,NEW.id,id_usuario,cod_perfil,portal from mos_usuario_estructura 
                where id_estructura = NEW.parent_id;
      END IF; 
end;
;;
DELIMITER ;

DROP TRIGGER `actualizar_nombre_area_ins`;

DROP TRIGGER `actualizar_nombre_area`;
DELIMITER ;;
CREATE TRIGGER `actualizar_nombre_area_ins` AFTER INSERT ON `mos_organizacion_nombres`
FOR EACH ROW begin

DECLARE padre INT;   
UPDATE mos_organizacion SET title = NEW.title  WHERE id = NEW.id;

    IF NEW.id NOT IN ( SELECT id_organizacion FROM mos_documentos_codigo WHERE id_organizacion = NEW.id) THEN
           SET padre =  (SELECT parent_id FROM mos_organizacion WHERE id = NEW.id);
           insert into mos_documentos_codigo (id_organizacion, codigo)
           select NEW.id, codigo
           from mos_documentos_codigo      
           where id_organizacion =padre; 
    END IF;

end;
;;
DELIMITER ;
DELIMITER ;;
CREATE TRIGGER `actualizar_nombre_area` BEFORE UPDATE ON `mos_organizacion_nombres`
FOR EACH ROW begin 
 
       UPDATE mos_organizacion SET title = NEW.title, area_espejo = NEW.area_espejo WHERE id = NEW.id;
       IF (NEW.title <> OLD.title) AND OLD.title = 'New Node' THEN
                UPDATE mos_documentos_codigo SET codigo = CONCAT(codigo,'_', UPPER(SUBSTR(NEW.title, 1, 3))) WHERE id_organizacion = NEW.id;
       END IF;

end;
;;
/*FIN ERROR*/



/*Ejecutar solo en mosaikus_admin*/

DROP VIEW IF EXISTS `mos_admin_usuarios`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mos_admin_usuarios` AS select `santateresa`.`mos_usuario`.`email` AS `email`,`santateresa`.`mos_usuario`.`password_1` AS `password_1`,concat(`santateresa`.`mos_usuario`.`nombres`,' ',`santateresa`.`mos_usuario`.`apellido_paterno`) AS `nombres`,11 AS `id_empresa`,`santateresa`.`mos_usuario`.`id_usuario` AS `id_usuario` from `santateresa`.`mos_usuario`;
/* fin */






/*ejecutado en BD desarrollo*/
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id', 'id', '25', 'id');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('descripcion', 'Descripción', '25', 'descripcion');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ( 'tipo_inspeccion', 'Plantilla de Inspección', '25', 'tipo_inspeccion');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ( 'fecha', 'Fecha', '25', 'fecha');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ( 'id_responsable', 'Responsable', '25', 'id_responsable');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ( 'id_organizacion', 'Árbol Organizacional', '25', 'id_organizacion');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ( 'id_proceso', 'Árbol de Procesos', '25', 'id_proceso');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('ubicacion', 'Ubicación', '25', 'ubicacion');

INSERT INTO `mos_link` (`descripcion`, `nombre_link`) VALUES ('Parametros-indexParametrosInspecciones-clases.parametros.Parametros', 'Parametros de Inspecciones');
UPDATE `mos_link` SET `dependencia`='83' WHERE (`cod_link`='0');
UPDATE `mos_link` SET `cod_link`='86' WHERE (`cod_link`='0');

/*FIN EJECUTADO BD Desarrollo*/

/* LISTA de Distribucion 07/06/2016*/

INSERT INTO `mos_link` (`cod_link`, `nombre_link`, `dependencia`, `tipo`, `orden`) VALUES ('91', 'Lista de Distribución', '3', '2', '59');
UPDATE `mos_link` SET `descripcion`='ListaDistribucionDoc-indexListaDistribucionDoc-clases.lista_distribucion_doc.ListaDistribucionDoc' WHERE (`cod_link`='91');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id', 'id', '27', 'id');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('estado', 'Estado Lista de Distribución', '27', 'estado');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id_documento', 'Documento', '27', 'id_documento');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('fecha_notificacion', 'fecha_notificacion', '27', 'fecha_notificacion');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('fecha_ejecutada', 'Fecha de Ejecución', '27', 'dd/mm/yyyy');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id_responsable', 'Responsable', '27', 'id_responsable');
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('evidencias', 'Evidencias', '27', null);
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id_area', 'Árbol Organizacional', '27', null);
INSERT INTO `mos_nombres_campos`(`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('id_cargo', 'Cargos', '27', null);

DROP TABLE IF EXISTS `mos_documentos_distribucion`;
CREATE TABLE `mos_documentos_distribucion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) DEFAULT 'Pendiente' COMMENT 'Pendiente\r\nCompletado',
  `id_documento` int(11) DEFAULT NULL,
  `fecha_notificacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_ejecutada` date DEFAULT NULL,
  `id_responsable` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_documento` (`id_documento`),
  KEY `id_responsable` (`id_responsable`),
  CONSTRAINT `mos_documentos_distribucion_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `mos_documentos` (`IDDoc`) ON UPDATE CASCADE,
  CONSTRAINT `mos_documentos_distribucion_ibfk_2` FOREIGN KEY (`id_responsable`) REFERENCES `mos_personal` (`cod_emp`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mos_documentos_distribucion
-- ----------------------------

-- ----------------------------
-- Table structure for `mos_documentos_distribucion_area`
-- ----------------------------
DROP TABLE IF EXISTS `mos_documentos_distribucion_area`;
CREATE TABLE `mos_documentos_distribucion_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc_distribucion` int(11) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_doc_distribucion` (`id_doc_distribucion`),
  KEY `id_cargo` (`id_cargo`),
  KEY `id_area` (`id_area`),
  CONSTRAINT `mos_documentos_distribucion_area_ibfk_1` FOREIGN KEY (`id_doc_distribucion`) REFERENCES `mos_documentos_distribucion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mos_documentos_distribucion_area_ibfk_3` FOREIGN KEY (`id_cargo`) REFERENCES `mos_cargo` (`cod_cargo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mos_documentos_distribucion_area
-- ----------------------------
-- ----------------------------
-- Table structure for `mos_documentos_distribucion_evi`
-- ----------------------------
DROP TABLE IF EXISTS `mos_documentos_distribucion_evi`;
CREATE TABLE `mos_documentos_distribucion_evi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_doc_distribucion` int(11) DEFAULT NULL,
  `nomb_archivo` varchar(250) DEFAULT NULL,
  `archivo` longblob,
  `contenttype` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_trazabilidad` (`fk_id_doc_distribucion`),
  CONSTRAINT `mos_documentos_distribucion_evi_ibfk_1` FOREIGN KEY (`fk_id_doc_distribucion`) REFERENCES `mos_documentos_distribucion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mos_documentos_distribucion_evi
-- ----------------------------

-- ----------------------------
-- Table structure for `mos_documentos_distribucion_per`
-- ----------------------------
DROP TABLE IF EXISTS `mos_documentos_distribucion_per`;
CREATE TABLE `mos_documentos_distribucion_per` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doc_distribucion` int(11) DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_doc_distribucion` (`id_doc_distribucion`),
  KEY `id_persona` (`id_persona`),
  KEY `id_cargo` (`id_cargo`),
  KEY `id_area` (`id_area`),
  CONSTRAINT `mos_documentos_distribucion_per_ibfk_1` FOREIGN KEY (`id_doc_distribucion`) REFERENCES `mos_documentos_distribucion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mos_documentos_distribucion_per_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `mos_personal` (`cod_emp`) ON UPDATE CASCADE,
  CONSTRAINT `mos_documentos_distribucion_per_ibfk_3` FOREIGN KEY (`id_cargo`) REFERENCES `mos_cargo` (`cod_cargo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


delete from mos_historico_wf_documentos where IDDoc not in (select IDDoc from mos_documentos);
ALTER TABLE `mos_historico_wf_documentos` ADD FOREIGN KEY (`IDDoc`) REFERENCES `mos_documentos` (`IDDoc`) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TRIGGER IF EXISTS `registra_mos_historico_wf_documentos`;
DELIMITER ;;
CREATE TRIGGER `registra_mos_historico_wf_documentos` AFTER INSERT ON `mos_documentos`
FOR EACH ROW BEGIN
/*guarda historico al insertar un doc*/
         DECLARE etapa text;  
			IF(NEW.id_workflow_documento is not null)THEN
				set etapa= (SELECT
				IFNULL(mos_nombres_campos.texto,'')
				FROM
				mos_nombres_campos
				WHERE
				mos_nombres_campos.modulo = 6 AND
				mos_nombres_campos.nombre_campo = NEW.etapa_workflow);

                                INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,'Documento Creado',NEW.id_usuario_workflow);
                                IF (NEW.estado_workflow is NOT NULL) THEN
                                     INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
                                     VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambió a ',etapa),NEW.id_usuario_workflow);
                                END IF;
                        ELSE
                                INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,'Documento Creado',NEW.id_usuario_workflow);
			END IF;
END;

;;
DELIMITER ;

DROP TABLE IF EXISTS `mos_evidencias_temp`;
CREATE TABLE `mos_evidencias_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_md5` varchar(250) DEFAULT NULL,
  `nomb_archivo` varchar(250) DEFAULT NULL,
  `contenttype` varchar(50) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tok` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL COMMENT '0 => Sin Cambios\r\n1 => Nuevo\r\n2 => Editar\r\n3 => Eliminar',
  `clave_foranea` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*FIN LISTA DISTRIBUCION*/

/*TAMAÑO CONTENTTYPE*/
ALTER TABLE `mos_documentos_anexos`
MODIFY COLUMN `contenttype`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `archivo`;

ALTER TABLE `mos_evidencias_temp`
MODIFY COLUMN `contenttype`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `nomb_archivo`;

ALTER TABLE `mos_documentos_distribucion_evi`
MODIFY COLUMN `contenttype`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `archivo`;


/*FIN TAMAÑO*/


ALTER TABLE `mos_cargo`
MODIFY COLUMN `cod_cargo`  int(9) NOT NULL AUTO_INCREMENT FIRST ;

/* CAMBIO 12/07/2016*/
ALTER TABLE `mos_registro`
ADD PRIMARY KEY (`idRegistro`);

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('tipo_documento', 'Tipo de Documento', '6');
ALTER TABLE `mos_documentos`
ADD COLUMN `tipo_documento`  int NULL DEFAULT 6 AFTER `requiere_lista_distribucion`;

DROP TABLE IF EXISTS `mos_documentos_codigo_correlativo`;
CREATE TABLE `mos_documentos_codigo_correlativo` (
  `id_organizacion` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `correlativo` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mos_documentos_codigo_correlativo
-- ----------------------------

-- ----------------------------
-- Table structure for `mos_documentos_tipos`
-- ----------------------------
DROP TABLE IF EXISTS `mos_documentos_tipos`;
CREATE TABLE `mos_documentos_tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `mos_documentos_tipos` VALUES ('1', 'FOR', 'Formularios');
INSERT INTO `mos_documentos_tipos` VALUES ('2', 'POL', 'Políticas ');
INSERT INTO `mos_documentos_tipos` VALUES ('3', 'INT', 'Instructivos');
INSERT INTO `mos_documentos_tipos` VALUES ('4', 'MAN', 'Manual');
INSERT INTO `mos_documentos_tipos` VALUES ('5', 'HDS', 'Hojas de Seguridad');
INSERT INTO `mos_documentos_tipos` VALUES ('6', 'PRO', 'Procedimientos');
INSERT INTO `mos_documentos_tipos` VALUES ('7', 'STW', 'Standard Work');
INSERT INTO `mos_documentos_tipos` VALUES ('8', 'PAP', 'Paso a Paso');


-- Cambios nuevos para inspecciones
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('descripcion_larga', 'Descripción Larga', '22', 'Descripción Larga');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('responsable_desvio', 'Responsable de Ocurrencia', '15', 'Responsable de Ocurrencia');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('reportado_por', 'Reportado Pór', '15', 'Reportado Pór');


ALTER TABLE `mos_parametro_det`
DROP PRIMARY KEY,
ADD PRIMARY KEY (`cod_parametro_det`);

ALTER TABLE `mos_parametro_det` DROP FOREIGN KEY `mos_parametro_det_ibfk_1`;

ALTER TABLE `mos_parametro_det`
DROP INDEX `Ind04`,
DROP INDEX `Ind02`,
DROP INDEX `Ind03`,
DROP INDEX `Ind01`,
DROP INDEX `Ind05`;

ALTER TABLE `mos_acciones_correctivas`
ADD COLUMN `responsable_desvio`  int NULL AFTER `fecha_cambia_estado`;

ALTER TABLE `mos_acciones_correctivas`
ADD COLUMN `reportado_por`  int NULL AFTER `responsable_desvio`;



rename table mos_acciones_evidencia TO mos_acciones_trazabilidad;

DROP TABLE IF EXISTS `mos_acciones_evidencia`;
CREATE TABLE `mos_acciones_evidencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_trazabilidad` int(11) DEFAULT NULL,
  `nomb_archivo` varchar(250) DEFAULT NULL,
  `archivo` longblob,
  `contenttype` varchar(50) DEFAULT NULL,
  `fk_id_accion_c` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_trazabilidad` (`fk_id_trazabilidad`),
  KEY `fk_id_accion_c` (`fk_id_accion_c`),
  CONSTRAINT `mos_acciones_evidencia_ibfk_1` FOREIGN KEY (`fk_id_trazabilidad`) REFERENCES `mos_acciones_trazabilidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mos_acciones_evidencia_ibfk_2` FOREIGN KEY (`fk_id_accion_c`) REFERENCES `mos_acciones_correctivas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

insert into mos_acciones_evidencia(fk_id_trazabilidad, nomb_archivo,archivo,contenttype)
select id, nomb_archivo, archivo, contenttype from mos_acciones_trazabilidad where LENGTH(archivo) > 0;
/*
DROP TABLE IF EXISTS `mos_evidencias_temp`;
CREATE TABLE `mos_evidencias_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_md5` varchar(250) DEFAULT NULL,
  `nomb_archivo` varchar(250) DEFAULT NULL,
  `contenttype` varchar(50) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tok` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL COMMENT '0 => Sin Cambios\r\n1 => Nuevo\r\n2 => Editar\r\n3 => Eliminar',
  `clave_foranea` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
*/
ALTER TABLE `mos_acciones_ac_co` 
ADD COLUMN `orden`  int NULL AFTER `fecha_cambia_estado`;

ALTER TABLE `mos_acciones_correctivas`
ADD COLUMN `estatus`  varchar(100) NULL AFTER `reportado_por`;

ALTER TABLE `mos_acciones_correctivas`
ADD COLUMN `id_usuario`  int NULL AFTER `estatus`;

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('en_elaboracion', 'Borrador', '15');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('en_buzon', 'En Buzón', '15');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('sin_responsable_analisis', 'Sin Responsable de Analisis', '15');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('sin_plan_accion', 'Sin Plan de Acción', '15');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('implementacion_acciones', 'Implementación de Acciones', '15');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('verificacion_eficacia', 'Verificación de Eficacia', '15');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('cerrada_verificada', 'Cerrada y Verificada', '15');

INSERT INTO `mos_link` (`descripcion`, `nombre_link`, `dependencia`, `tipo`, `orden`) VALUES ('AccionesCorrectivas-indexAccionesCorrectivas-clases.acciones_correctivas.AccionesCorrectivas', 'Seguimiento de Acciones y Correcciones', '8', '2', '51');
UPDATE `mos_link` SET `cod_link`='93' WHERE (`cod_link`='0');
UPDATE `mos_link` SET `descripcion`='AccionesAC-indexAccionesAC-clases.acciones_ac.AccionesAC' WHERE (`cod_link`='93');

ALTER TABLE `mos_acciones_ac_co`
ADD COLUMN `fecha_realizada_temp`  date NULL COMMENT 'Guarda la fecha ejecutada de una accion de manera temporal, hasta que la misma sea aprobada' AFTER `orden`,
ADD COLUMN `estatus_wf`  varchar(50) NULL DEFAULT 'en_ejecucion' COMMENT 'borrador\r\nen_ejecucion\r\ncerrada_verificar\r\ncerrada_verificada' AFTER `fecha_realizada_temp`;

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('estatus_wf', 'Flujo de Trabajo', '16');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('en_ejecucion', 'En Ejecución', '16');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('cerrada_verificar', 'Cerrada por Verificar', '16');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('cerrada_verificada', 'Cerrada y Verificada', '16');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('accion_ejecutada', 'Acción Ejecutada', '16');

ALTER TABLE `mos_acciones_trazabilidad`
DROP COLUMN `archivo`,
DROP COLUMN `contenttype`,
MODIFY COLUMN `fecha_evi`  timestamp NULL DEFAULT NULL AFTER `id_accion`;

ALTER TABLE `mos_acciones_trazabilidad`
DROP COLUMN `nomb_archivo`,
ADD COLUMN `tipo`  varchar(25) NULL COMMENT 'Avance\r\nCierre' AFTER `id_accion`;

update mos_acciones_trazabilidad set tipo = 'Avance';

DELETE from mos_nombres_campos where modulo = 17;

UPDATE `mos_link` SET `nombre_link`='Ocurrencias' WHERE (`cod_link`='8');
UPDATE `mos_link` SET `nombre_link`='Administrador de  Ocurrencias con Plan de Acción' WHERE (`cod_link`='50');
UPDATE `mos_link` SET `nombre_link`='Administrador de  Ocurrencias con Correcciones' WHERE (`cod_link`='77');
UPDATE `mos_link` SET `nombre_link`='Seguimiento de Acciones Correctivas' WHERE (`cod_link`='93');
UPDATE `mos_nombres_campos` SET `texto`='Fecha Comprometida' WHERE (`nombre_campo`='fecha_acordada' and modulo = 16);
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('anexos', 'Anexos', '15');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('validador_accion', 'Validador', '16');
UPDATE `mos_link` SET `orden`='50' WHERE (`cod_link`='77');
UPDATE `mos_link` SET `nombre_link`='Reportes de Ocurrencias con Plan de Acción', `orden`='52' WHERE (`cod_link`='52');
UPDATE `mos_link` SET `nombre_link`='Configuración', `orden`='53' WHERE (`cod_link`='51');
UPDATE `mos_link` SET `dependencia`='51' WHERE (`cod_link`='78');
UPDATE `mos_link` SET `dependencia`='51' WHERE (`cod_link`='79');

DROP TRIGGER IF EXISTS `colocar_estatus_de_ac`;
DELIMITER ;;
CREATE TRIGGER `colocar_estatus_de_ac` BEFORE INSERT ON `mos_acciones_correctivas` FOR EACH ROW BEGIN
          IF (NEW.estatus IS NULL) THEN
                      IF NOT (NEW.responsable_analisis IS NULL) THEN
                              SET NEW.estatus = 'sin_plan_accion';                      
                       ELSEIF NOT (NEW.responsable_desvio IS NULL) THEN
                              SET NEW.estatus = 'sin_responsable_analisis';
                       ELSE 
                              SET NEW.estatus = 'en_buzon';
                       END IF;
          END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `validar_verificacion_acciones`;
DELIMITER ;;
CREATE TRIGGER `validar_verificacion_acciones` BEFORE UPDATE ON `mos_acciones_correctivas` FOR EACH ROW BEGIN
   DECLARE contador INT; 
   DECLARE edo varchar(30);
    IF NOT (NEW.fecha_acordada IS NULL) THEN
            SET contador = (select count(*) from mos_acciones_ac_co  where id_ac = NEW.id and fecha_realizada is null);
            IF (contador > 0) THEN
                         SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '- Existen acciones por cerrar, no se puede planificar la verificacion mientras esten acciones abiertas';
            END IF;
            
            /*VALIDAMOS QUE LA FECHA REALIZADA SEA MENOR O IGUAL AL DIA DE HOY*/
           IF (NEW.fecha_realizada is not null)THEN
	 IF (DATEDIFF(NEW.fecha_realizada,NOW())<=0)THEN
		/*CALCULAMOS EL ESTADO DE LA ACCION CUANDO TIENE FECHA REALIZADA*/
		SET edo = (case 
				 when DATEDIFF(NEW.fecha_acordada,NEW.fecha_realizada)<0 then 3
				 when DATEDIFF(NEW.fecha_acordada,NEW.fecha_realizada)>=0 then 4 end);
		SET NEW.estado= edo;
		SET NEW.fecha_cambia_estado= now();
                                     SET NEW.estatus = 'cerrada_verificada';
	ELSE
		set edo=-1;
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '- La fecha realizada no puede ser mayor a la fecha de hoy';
	 END IF;
           ELSE
		SET edo = (case 
				 when  DATEDIFF(NEW.fecha_acordada,NOW())<0  then 1 
				 else 2 end);
		SET NEW.estado= edo;
		SET NEW.fecha_cambia_estado= now();
                                     SET NEW.estatus = 'verificacion_eficacia';
           END IF;
   ELSE
            SET NEW.fecha_realizada = NULL;
            SET NEW.id_responsable_segui = NULL;
            IF (NEW.estatus IS NULL) THEN
                      IF NOT (NEW.responsable_analisis IS NULL) THEN
                              SET NEW.estatus = 'sin_plan_accion';                      
                       ELSEIF NOT (NEW.responsable_desvio IS NULL) THEN
                              SET NEW.estatus = 'sin_responsable_analisis';
                       ELSE 
                              SET NEW.estatus = 'en_buzon';
                       END IF;
          -- ELSE 
                --  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = NEW.estatus;
          END IF;
   END IF;
   
END
;;
DELIMITER ;

DROP TRIGGER IF EXISTS `modifica_estado_acciones_ins`;
DELIMITER ;;
CREATE TRIGGER `modifica_estado_acciones_ins` BEFORE INSERT ON `mos_acciones_ac_co` FOR EACH ROW BEGIN
DECLARE edo varchar(30);
DECLARE peso int;
/*VALIDAMOS QUE LA FECHA REALIZADA SEA MENOR O IGUAL AL DIA DE HOY*/
IF (NEW.fecha_realizada is not null)THEN
	IF (DATEDIFF(NEW.fecha_realizada,NOW())<=0)THEN
		/*CALCULAMOS EL ESTADO DE LA ACCION CUANDO TIENE FECHA REALIZADA*/
		SET edo = (case 
				 when DATEDIFF(NEW.fecha_acordada,NEW.fecha_realizada)<0 then 3
				 when DATEDIFF(NEW.fecha_acordada,NEW.fecha_realizada)>=0 then 4 end);
			SET NEW.estado= edo;
			SET NEW.fecha_cambia_estado= now();
	ELSE
		set edo=-1;
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '- La fecha realizada no puede ser mayor a la fecha de hoy';
	END IF;
ELSE
		SET edo = (case 
				 when  DATEDIFF(NEW.fecha_acordada,NOW())<0  then 1 
				 else 2 end);
			SET NEW.estado= edo;
			SET NEW.fecha_cambia_estado= now();
END IF;

end
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `modificar_estado_ac_ins`;
DELIMITER ;;
CREATE TRIGGER `modificar_estado_ac_ins` AFTER INSERT ON `mos_acciones_ac_co` FOR EACH ROW BEGIN
         DECLARE contador INT;  
			/*PARA ACCIONES CORRECTIVAS*/
			IF(NEW.id_ac is not null)THEN
        SET contador = (select MIN(estado) from mos_acciones_ac_co  where id_ac = NEW.id_ac);
       UPDATE mos_acciones_correctivas SET estado = contador,fecha_cambia_estado= now()  WHERE id = NEW.id_ac and mos_acciones_correctivas.fecha_realizada is null;
			END IF;
			/*PARA CORRECCIONES*/
			IF(NEW.id_correcion is not null) THEN
        SET contador = (select MIN(estado) from mos_acciones_ac_co  where id_correcion = NEW.id_correcion);
       UPDATE mos_correcciones SET estado = contador, fecha_cambia_estado= now()  WHERE id = NEW.id_correcion;
			END IF;

END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `modifica_estado_acciones_upd`;
DELIMITER ;;
CREATE TRIGGER `modifica_estado_acciones_upd` BEFORE UPDATE ON `mos_acciones_ac_co` FOR EACH ROW BEGIN
DECLARE edo varchar(30);
DECLARE edo_correcion_ant varchar(30);
DECLARE id_accion_correctiva int;
DECLARE peso int;
DECLARE peso2 int;
set peso=0;
/*VALIDAMOS QUE LA FECHA REALIZADA SEA MENOR O IGUAL AL DIA DE HOY*/
IF (NEW.fecha_realizada is not null)THEN
	IF (DATEDIFF(NEW.fecha_realizada,NOW())<=0)THEN
		/*CALCULAMOS EL ESTADO DE LA ACCION CUANDO TIENE FECHA REALIZADA*/
		SET edo = (case 
				 when DATEDIFF(NEW.fecha_acordada,NEW.fecha_realizada)<0 then 3
				 when DATEDIFF(NEW.fecha_acordada,NEW.fecha_realizada)>=0 then 4 end);
			SET NEW.estado= edo;
			SET NEW.fecha_cambia_estado= now();
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '- La fecha realizada no puede ser mayor a la fecha de hoy';
	END IF;
ELSE
		SET edo = (case 
				 when  DATEDIFF(NEW.fecha_acordada,NOW())<0  then 1 
				 else 2 end);
			SET NEW.estado= edo;
			SET NEW.fecha_cambia_estado= now();
END IF;
end
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `modificar_estado_ac`;
DELIMITER ;;
CREATE TRIGGER `modificar_estado_ac` AFTER UPDATE ON `mos_acciones_ac_co` FOR EACH ROW BEGIN
         DECLARE contador INT;  
			IF(NEW.id_ac is not null)THEN
        SET contador = (select MIN(estado) from mos_acciones_ac_co  where id_ac = NEW.id_ac);
       UPDATE mos_acciones_correctivas SET estado = contador,  fecha_cambia_estado= now()  WHERE id = NEW.id_ac and mos_acciones_correctivas.fecha_realizada is null;
			END IF;
			/*PARA CORRECCIONES*/
			IF(NEW.id_correcion is not null) THEN
        SET contador = (select MIN(estado) from mos_acciones_ac_co  where id_correcion = NEW.id_correcion);
       UPDATE mos_correcciones SET estado = contador ,  fecha_cambia_estado= now() WHERE id = NEW.id_correcion;
			END IF;
END
;;
DELIMITER ;

/*NUEVOS AJUSTES ACCIONES CORRECTIVAS*/
ALTER TABLE `mos_acciones_correctivas`
MODIFY COLUMN `estado`  int(11) NULL DEFAULT 2 AFTER `alto_potencial`;

ALTER TABLE `mos_acciones_ac_co`
ADD COLUMN `id_validador`  int NULL AFTER `fecha_realizada_temp`;

ALTER TABLE `mos_acciones_ac_co`
ADD COLUMN `id_usuario_wf`  int NULL AFTER `estatus_wf`,
ADD COLUMN `fecha_estado_wf`  datetime NULL AFTER `id_usuario_wf`;

ALTER TABLE `mos_acciones_ac_co`
ADD COLUMN `observacion_rechazo`  text NULL AFTER `estatus_wf`;

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`) VALUES ('rechazado', 'Rechazado', '16');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `id_idioma`) VALUES ('rechazado', 'Rejeitados', '16', '2');

ALTER TABLE `mos_acciones_correctivas`
ADD COLUMN `desc_verificacion`  text NULL AFTER `id_usuario`;

ALTER TABLE `mos_acciones_correctivas`
ADD COLUMN `fecha_realizada_temp`  date NULL COMMENT 'Guarda la fecha ejecutada de verifiacion de forma temporal' AFTER `fecha_realizada`;

ALTER TABLE `mos_acciones_evidencia`
ADD COLUMN `fk_id_accion_c_ver`  int NULL AFTER `fk_id_accion_c`;

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('desc_verificacion', 'Observación', '15', 'Observación');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('desc_verificacion', 'Observação', '15', 'Observação', '2');

ALTER TABLE `mos_acciones_correctivas`
ADD COLUMN `descripcion_val`  text NULL COMMENT 'Descripcion Final de La Ocurrencia' AFTER `descripcion`,
ADD COLUMN `alto_potencial_val`  varchar(2) NULL COMMENT 'Validación de Alto Potencial' AFTER `alto_potencial`;

INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('descripcion_val', 'Descripción Final', '15', 'Descripción Final');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('descripcion_val', 'Descrição Final', '15', 'Descrição Final',2);
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`) VALUES ('alto_potencial_val', 'Verificación de Alto Potencial', '15', 'Alto Potencial');
INSERT INTO `mos_nombres_campos` (`nombre_campo`, `texto`, `modulo`, `placeholder`, `id_idioma`) VALUES ('alto_potencial_val', 'Verificación de Alto Potencial', '15', 'Alto Potencial',2);



/*CAMBIO EN TRIGGER CREAR/MODIFICAR DOCUMENTO PARA CORREGIR ERROR DE IDIOMA*/
DELIMITER ;
DROP TRIGGER IF EXISTS `registra_mos_historico_wf_documentos`;
DELIMITER ;;
CREATE TRIGGER `registra_mos_historico_wf_documentos` AFTER INSERT ON `mos_documentos` FOR EACH ROW BEGIN
/*guarda historico al insertar un doc*/
         DECLARE etapa text;  
			IF(NEW.id_workflow_documento is not null)THEN
				set etapa= (SELECT
				IFNULL(mos_nombres_campos.texto,'')
				FROM
				mos_nombres_campos
				WHERE
				mos_nombres_campos.modulo = 6 AND
				mos_nombres_campos.nombre_campo = NEW.etapa_workflow AND mos_nombres_campos.id_idioma = (select id_idioma from mos_usuario where id_usuario = NEW.id_usuario_workflow));

        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,'Documento Creado',NEW.id_usuario_workflow);
        IF (NEW.estado_workflow is NOT NULL) THEN
            INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
            VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambió a ',etapa),NEW.id_usuario_workflow);
        END IF;
      ELSE
        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,'Documento Creado',NEW.id_usuario_workflow);
			END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `registra_mos_historico_wf_documentos_cambio`;
DELIMITER ;;
CREATE TRIGGER `registra_mos_historico_wf_documentos_cambio` BEFORE UPDATE ON `mos_documentos` FOR EACH ROW BEGIN
/*guarda historico al modificar un doc si cambian los datos del wf*/
        DECLARE etapa text;  
				set etapa= (SELECT
				IFNULL(mos_nombres_campos.texto,'')
				FROM
				mos_nombres_campos
				WHERE
				mos_nombres_campos.modulo = 6 AND
				mos_nombres_campos.nombre_campo = NEW.etapa_workflow AND mos_nombres_campos.id_idioma = (select id_idioma from mos_usuario where id_usuario = NEW.id_usuario_workflow));

			IF((NEW.etapa_workflow<>OLD.etapa_workflow) or (NEW.estado_workflow<>OLD.estado_workflow)) THEN
        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambió a ',etapa),NEW.id_usuario_workflow);
			END IF;
			IF(OLD.etapa_workflow is Null and NEW.etapa_workflow<>'') THEN
        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambió a ',etapa),NEW.id_usuario_workflow);
			END IF;
END
;;
DELIMITER ;

/*FIN*/

/**/

-- 30-08-2016 Ajuste en codigos sugerido por el Sistema
ALTER TABLE `mos_documentos_codigo`
ADD COLUMN `activo`  varchar(1) NULL DEFAULT 'N' AFTER `correlativo`;

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'activo', 'Vigente', '26','Vigente',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'activo', 'Validade', '26','Validade',2);

UPDATE `mos_nombres_campos` SET `texto`='Se' WHERE (`modulo`='100' and nombre_campo = 'si');
UPDATE `mos_nombres_campos` SET `texto`='Não' WHERE (`modulo`='100' and nombre_campo = 'no');
UPDATE `mos_nombres_campos` SET `texto`='Recibe Notificaciones de Correo Electrónico' WHERE (`modulo`='21' and nombre_campo = 'recibe_notificaciones');
UPDATE `mos_nombres_campos` SET `texto`='Email', `placeholder`='Email' WHERE (`modulo`='21' and nombre_campo = 'email');
UPDATE `mos_nombres_link_idiomas` SET `nombre_link`='Gestor de Formulários' WHERE (`cod_link`='87') AND (`id_idioma`='2');
UPDATE `mos_nombres_link_portal_idiomas` SET `nombre_link`='Mestre de Documentos' WHERE (`cod_link`='14') AND (`id_idioma`='2');
UPDATE `mos_nombres_link_portal_idiomas` SET `nombre_link`='Mestre de Registros' WHERE (`cod_link`='16') AND (`id_idioma`='2');

