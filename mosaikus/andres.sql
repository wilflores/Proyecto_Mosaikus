/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Author:  Andres Zambrano
 * Created: Feb 26, 2016
 */
/****************************************/
/*cambio del domingo 28-02*/
/****************************************/
DROP TABLE IF EXISTS `mos_workflow_acciones`;
CREATE TABLE `mos_workflow_acciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personal` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `id_personal_wf` int(11) DEFAULT NULL,
  `email_wf` varchar(255) DEFAULT NULL,
  `id_personal_vaca` int(11) DEFAULT NULL,
  `email_wf_vaca` varchar(255) DEFAULT NULL,
  `email_alerta` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO `mos_nombres_campos` VALUES ('234', 'id', 'id', '30', 'id');
INSERT INTO `mos_nombres_campos` VALUES ('235', 'id_personal', 'id_personal', '30', 'id_personal');
INSERT INTO `mos_nombres_campos` VALUES ('236', 'email', 'email', '30', 'email');
INSERT INTO `mos_nombres_campos` VALUES ('237', 'id_personal_wf', 'id_personal_wf', '30', 'id_personal_wf');
INSERT INTO `mos_nombres_campos` VALUES ('238', 'email_wf', 'email_wf', '30', 'email_wf');
INSERT INTO `mos_nombres_campos` VALUES ('239', 'id_personal_vaca', 'id_personal_vaca', '30', 'id_personal_vaca');
INSERT INTO `mos_nombres_campos` VALUES ('240', 'email_wf_vaca', 'email_wf_vaca', '30', 'email_wf_vaca');
INSERT INTO `mos_nombres_campos` VALUES ('241', 'email_alerta', 'email_alerta', '30', 'email_alerta');

/****************************************/
/*cambio del 09-04*/
/****************************************/

DROP TABLE IF EXISTS `mos_registro_item`;
CREATE TABLE `mos_registro_item` (
  `IDDoc` int(9) NOT NULL,
  `idRegistro` int(9) NOT NULL,
  `valor` varchar(250) NOT NULL,
  `tipo` char(2) NOT NULL,
  `id_unico` int(11) NOT NULL,
  PRIMARY KEY (`idRegistro`,`id_unico`,`valor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/****************************************/
/*cambio del 27-03*/
/****************************************/
DROP TABLE IF EXISTS `mos_workflow_documentos`;
CREATE TABLE `mos_workflow_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personal_responsable` int(11) DEFAULT NULL,
  `email_responsable` varchar(255) DEFAULT NULL,
  `id_personal_revisa` int(11) DEFAULT NULL,
  `email_revisa` varchar(255) DEFAULT NULL,
  `id_personal_aprueba` int(11) DEFAULT NULL,
  `email_aprueba` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `mos_link` VALUES ('82', 'WorkflowDocumentos-indexWorkflowDocumentos-clases.workflow_documentos.WorkflowDocumentos', 'WorkFlow de Documentos', '3', '2', '', '55', null);

-- INSERT INTO `mos_link_por_perfil` VALUES ('1', '82');

INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('id', 'id', '23', 'id');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('id_personal_responsable', 'Responsable', '23', 'Responsable');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('email_responsable', 'Email Responsable', '23', 'Email');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('id_personal_revisa', 'Revisa', '23', 'Revisa');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('email_revisa', 'Email Revisa', '23', 'Email');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('id_personal_aprueba', 'Aprueba', '23', 'Aprueba');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('email_aprueba', 'Email Aprueba', '23', 'Email');
/****************************************/
/*cambio del 29-03*/
/****************************************/
ALTER TABLE `mos_documentos`
ADD COLUMN `id_workflow_documento`  int NULL AFTER `aprobo`,
ADD COLUMN `estado_workflow`  varchar(50) NULL AFTER `id_workflow_documento`,
ADD COLUMN `fecha_estado_workflow`  datetime NULL AFTER `estado_workflow`,
ADD COLUMN `id_usuario_workflow`  int NULL AFTER `fecha_estado_workflow`;

ALTER TABLE `mos_documentos`
MODIFY COLUMN `fecha_estado_workflow`  timestamp NULL DEFAULT CURRENT_TIMESTAMP AFTER `estado_workflow`;

ALTER TABLE `mos_documentos`
ADD COLUMN `etapa_workflow`  varchar(50) NULL AFTER `id_workflow_documento`;

ALTER TABLE `mos_documentos`
ADD COLUMN `observacion_rechazo`  text NULL AFTER `id_usuario_workflow`;

DROP TABLE IF EXISTS `mos_historico_wf_documentos`;
CREATE TABLE `mos_historico_wf_documentos` (
  `id` int(11) NOT NULL DEFAULT '0',
  `IDDoc` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `descripcion_operacion` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('id_workflow_documento', 'Flujo de Trabajo de Documento', '6', 'Flujo de Trabajo de Documento');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('estado_workflow', 'Estado de Flujo de Trabajo', '6', 'Estado de Flujo de Trabajo');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('fecha_estado_workflow', 'Fecha de Flujo de Trabajo', '6', 'Fecha de Flujo de Trabajo');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('id_usuario_workflow', 'Usuario de Flujo de Trabajo', '6', 'Usuario de Flujo de Trabajo');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('estado_pendiente_aprobacion', 'Pendiente de Aprobacion', '6', 'Pendiente de Aprobacion');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('estado_pendiente_revision', 'Pendiente de Revision', '6', 'Pendiente de Revision');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('estado_aprobado', 'Aprobado', '6', 'Aprobado');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES ('etapa_workflow', 'Etapa de Flujo de Trabajo', '6', 'Etapa de Flujo de Trabajo');

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
				VALUES (NEW.IDDoc,CONCAT('NUEVO DOCUMENTO',IFNULL(NEW.estado_workflow,''),etapa ),NEW.id_usuario_workflow);
			END IF;
END;

;;
DELIMITER ;

DROP TRIGGER IF EXISTS `registra_mos_historico_wf_documentos_cambio`;

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
				VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambio a ',etapa),NEW.id_usuario_workflow);
			END IF;
			IF(OLD.etapa_workflow is Null and NEW.etapa_workflow<>'') THEN
        INSERT into mos_historico_wf_documentos (IDDoc,descripcion_operacion,id_usuario) 
				VALUES (NEW.IDDoc,CONCAT('ESTADO:',NEW.estado_workflow,' ',IFNULL(NEW.observacion_rechazo,''),',cambio a ',etapa),NEW.id_usuario_workflow);
			END IF;
END;

;;
DELIMITER ;

/****************************************/
/*cambio del 20-04*/
/****************************************/
ALTER TABLE `mos_usuario`
ADD COLUMN `recibe_notificaciones`  varchar(1) NULL AFTER `cedula`;
/****************************************/
/*cambio del 01-05
/modulo de notificaiones
/****************************************/
DROP TABLE IF EXISTS `mos_notificaciones`;
CREATE TABLE `mos_notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(100) NOT NULL,
  `asunto` varchar(200) NOT NULL,
  `cuerpo` text,
  `fecha_leido` datetime DEFAULT NULL,
  `modulo` text,
  `fecha_alerta` datetime DEFAULT NULL,
  `funcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;


INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('id', 'id', '24', 'id');
INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('fecha', 'fecha', '24', 'fecha');
INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('email', 'email', '24', 'email');
INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('asunto', 'asunto', '24', 'asunto');
INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('cuerpo', 'cuerpo', '24', 'cuerpo');
INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('fecha_leido', 'fecha_leido', '24', 'fecha_leido');
INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('modulo', 'modulo', '24', 'modulo');
INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) VALUES ('fecha_alerta', 'fecha_alerta', '24', 'fecha_alerta');


INSERT INTO `mos_link` VALUES ('85', 'Notificaciones-indexNotificaciones-clases.notificaciones.Notificaciones', 'Notificaciones', '10', '2', 'notificaciones.php', '75', 'configuracion.png');

/****************************************/
/*cambio del 13-05*/
/****************************************/

INSERT INTO `mos_parametro_categoria` VALUES ('15', 'Formularios', 'Formularios', 'S');

INSERT INTO `mos_link` VALUES ('87', 'Documentos-indexDocumentos-clases.documentos.Documentos-formulario', 'Administrador de Formularios', '3', '2', 'mos_documentos.php', '12', 'documental.png');
INSERT INTO `mos_link` VALUES ('88', 'Parametros-indexParametros-clases.parametros.Parametros-formulario', 'Parámetros de Formularios', '3', '2', '', '13', 'documental.png');

update mos_link
set descripcion='Documentos-indexDocumentosFormulario-clases.documentos.Documentos-formulario'
where cod_link=16;

/****************************************/
/*cambio del 26-05*/
/****************************************/
ALTER TABLE `mos_documentos`
ADD COLUMN `actualizacion_activa`  char(1) NULL DEFAULT 'N' AFTER `publico`;

INSERT INTO `mos_nombres_campos`(nombre_campo, texto, modulo, placeholder) 
VALUES ('actualizacion_activa', 'Activar actualización de Registro', '6', 'Activar actualización de Registro');

ALTER TABLE `mos_registro`
ADD COLUMN `vigencia`  varchar(1) NULL DEFAULT 'S' AFTER `identificacion`,
ADD COLUMN `idRegistro_original`  int(9) NULL AFTER `vigencia`,
DROP PRIMARY KEY;

update mos_registro
set idRegistro_original=idRegistro;

/****************************************/
/*cambio del 10-06*/
/****************************************/

DROP TABLE IF EXISTS `mos_responsable_area`;
CREATE TABLE `mos_responsable_area` (
  `id_organizacion` int(11) NOT NULL DEFAULT '0',
  `cod_emp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_organizacion`,`cod_emp`),
  KEY `fk_respo_emp` (`cod_emp`),
  CONSTRAINT `fk_respo_emp` FOREIGN KEY (`cod_emp`) REFERENCES `mos_personal` (`cod_emp`) ON DELETE CASCADE,
  CONSTRAINT `fk_respo_org` FOREIGN KEY (`id_organizacion`) REFERENCES `mos_organizacion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/****************************************/
/*cambio del 14-06*/
/****************************************/
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
			END IF;
END;

;;
DELIMITER ;

insert into mos_nombres_campos (nombre_campo,texto,modulo,placeholder) values
('estado_sin_asignar', 'No Asignado',6, 'No Asignado');
/****************************************/
/*cambio del 16-06*/
/****************************************/
insert into mos_nombres_campos (nombre_campo,texto,modulo,placeholder) values
('requiere_lista_distribucion', 'Requiere Lista de Distribución',6, 'Requiere Lista de Distribución');

ALTER TABLE `mos_documentos`
ADD COLUMN `requiere_lista_distribucion`  char(1) NULL DEFAULT 'N' AFTER `actualizacion_activa`;

ALTER TABLE `mos_notificaciones`
ADD COLUMN `id_entidad`  int NULL AFTER `funcion`;

DROP TABLE IF EXISTS `mos_documentos_cargos`;
CREATE TABLE `mos_documentos_cargos` (
  `IDDoc` int(11) NOT NULL DEFAULT '0',
  `cod_cargo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDDoc`,`cod_cargo`),
  KEY `fk_cargo_doc` (`cod_cargo`),
  CONSTRAINT `fk_cargo_doc` FOREIGN KEY (`cod_cargo`) REFERENCES `mos_cargo` (`cod_cargo`) ON DELETE CASCADE,
  CONSTRAINT `fk_doc_cargo` FOREIGN KEY (`IDDoc`) REFERENCES `mos_documentos` (`IDDoc`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/****************************************/
/*cambio del 19-06*/
/****************************************/

insert into mos_nombres_campos (nombre_campo,texto,modulo,placeholder) values
('estado', 'Estado Entidad',24, 'Estado Entidad');
/****************************************/
/*cambio del 21-06*/
/****************************************/
DROP TABLE IF EXISTS `mos_documentos_anexos`;
CREATE TABLE `mos_documentos_anexos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_documento` int(11) DEFAULT NULL,
  `nomb_archivo` varchar(250) DEFAULT NULL,
  `archivo` longblob,
  `contenttype` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_documento_mos_doc` (`id_documento`),
  CONSTRAINT `fk_id_documento_mos_doc` FOREIGN KEY (`id_documento`) REFERENCES `mos_documentos` (`IDDoc`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/****************************************/
/*cambio del 23-06*/
/****************************************/
insert into mos_nombres_campos (nombre_campo,texto,modulo,placeholder) values
('fecha_hora', 'Fecha',89, 'Fecha');
insert into mos_nombres_campos (nombre_campo,texto,modulo,placeholder) values
('accion', 'Acciones',89, 'Acciones');
insert into mos_nombres_campos (nombre_campo,texto,modulo,placeholder) values
('codigo', 'Documento',89, 'Documento');
insert into mos_nombres_campos (nombre_campo,texto,modulo,placeholder) values
('usuario', 'Usuario',89, 'Usuario');

DROP TABLE IF EXISTS `mos_correos_temporales`;
CREATE TABLE `mos_correos_temporales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_entidad` int(11) DEFAULT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  `asunto` text,
  `cuerpo` text,
  `email` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_insert` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/****************************************/
/*cambio del 29-06*/
/****************************************/
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'id_responsable_actual', 'Responsable Actual', '90', 'Responsable Actual');
INSERT INTO `mos_nombres_campos` (nombre_campo,texto,modulo,placeholder) VALUES ( 'id_nuevo_responsable', 'Nuevo Responsable', '90', 'Nuevo Responsable');
INSERT INTO `mos_nombres_campos` (nombre_campo,texto,modulo,placeholder) VALUES ( 'fecha_operacion', 'Fecha', '90', 'Fecha');
INSERT INTO `mos_nombres_campos` (nombre_campo,texto,modulo,placeholder) VALUES ( 'migrar_responsable_doc', 'Migrar Responsable', '90', 'Migrar Responsable');
INSERT INTO `mos_nombres_campos` (nombre_campo,texto,modulo,placeholder) VALUES ( 'migrar_wf_revisa', 'Migrar Revisor', '90', 'Migrar Revisor');
INSERT INTO `mos_nombres_campos` (nombre_campo,texto,modulo,placeholder) VALUES ( 'id_revisa', 'Nuevo Revisor', '90', 'Nuevo Revisor');
INSERT INTO `mos_nombres_campos` (nombre_campo,texto,modulo,placeholder) VALUES ( 'migrar_wf_aprueba', 'Migrar Aprobador', '90', 'Migrar Aprobador');
INSERT INTO `mos_nombres_campos` (nombre_campo,texto,modulo,placeholder) VALUES ( 'id_aprueba', 'Nuevo Aprobador', '90', 'Nuevo Aprobador');

DROP TABLE IF EXISTS `mos_documentos_migracion`;
CREATE TABLE `mos_documentos_migracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_responsable_actual` int(11) DEFAULT NULL,
  `id_nuevo_responsable` int(11) DEFAULT NULL,
  `fecha_operacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `migrar_responsable_doc` varchar(1) DEFAULT NULL,
  `migrar_wf_revisa` varchar(1) DEFAULT NULL,
  `id_revisa` int(11) DEFAULT NULL,
  `migrar_wf_aprueba` varchar(1) DEFAULT NULL,
  `id_aprueba` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

INSERT INTO `mos_link` VALUES ('92', 'DocumentosMigracion-indexDocumentosMigracion-clases.documentos_migracion.DocumentosMigracion', 'Migración de Responsabilidades', '89', '2', 'documentos_migracion.php', '56', null);
/****************************************/
/*cambio del 01-07*/
/****************************************/
DROP TABLE IF EXISTS `mos_documentos_relacionados`;
CREATE TABLE `mos_documentos_relacionados` (
  `IDDoc` int(11) NOT NULL DEFAULT '0',
  `IDDoc_relacionado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDDoc`,`IDDoc_relacionado`),
  CONSTRAINT `fk_iddoc_doc_relacionado` FOREIGN KEY (`IDDoc`) REFERENCES `mos_documentos` (`IDDoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/****************************************/
/*cambio del 15-07*/
/****************************************/
DROP TABLE IF EXISTS `mos_historico_cargos_promocion`;
CREATE TABLE `mos_historico_cargos_promocion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_emp` int(11) DEFAULT NULL,
  `id_organizacion` int(11) DEFAULT NULL,
  `cod_cargo` int(11) DEFAULT NULL,
  `id_organizacion_promovida` int(11) DEFAULT NULL,
  `cod_cargo_promovido` int(11) DEFAULT NULL,
  `fecha_promocion` datetime DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


ALTER TABLE `mos_personal`
ADD COLUMN `promover_cargo`  varchar(1) NULL DEFAULT 'N' AFTER `responsable_area`;
ALTER TABLE `mos_personal`
ADD COLUMN `fecha_promocion`  date NULL AFTER `promover_cargo`;

INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'fecha_promocion', 'Fecha de promoción', '1', 'Fecha de promoción');
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'promover_cargo', 'Promover Cargo', '1', 'Promover Cargo');

DROP TRIGGER IF EXISTS `registra_mos_historico_cargos_promocion_upd`;

CREATE TRIGGER `registra_mos_historico_cargos_promocion_upd` BEFORE UPDATE ON `mos_personal`
FOR EACH ROW BEGIN
/*guarda historico de la promocion de los cargos*/
			IF(NEW.promover_cargo='S') THEN
        INSERT into mos_historico_cargos_promocion (cod_emp, id_organizacion, cod_cargo, id_organizacion_promovida, cod_cargo_promovido, fecha_promocion) 
				VALUES (NEW.cod_emp, OLD.id_organizacion, OLD.cod_cargo, NEW.id_organizacion, NEW.cod_cargo, NEW.fecha_promocion);
			END IF;
END;

DROP TRIGGER IF EXISTS `registra_mos_historico_cargos_promocion_ins`;

CREATE TRIGGER `registra_mos_historico_cargos_promocion_ins` AFTER INSERT ON `mos_personal`
FOR EACH ROW BEGIN
/*guarda historico de la promocion de los cargos*/
			IF(NEW.promover_cargo='S') THEN
        INSERT into mos_historico_cargos_promocion (cod_emp, id_organizacion, cod_cargo, id_organizacion_promovida, cod_cargo_promovido, fecha_promocion) 
				VALUES (NEW.cod_emp, NEW.id_organizacion, NEW.cod_cargo, NEW.id_organizacion, NEW.cod_cargo, NEW.fecha_ingreso);
			END IF;
END;

INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'dias_vig', 'Días', '6', 'Días');
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'num_rev', 'Revisión', '6', 'Revisión');
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'arbol_organizacional', 'Árbol Organizacional (Niveles)', '6', 'Árbol Organizacional (Niveles)');
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'fecha_rev', 'Fecha de Revisión', '6', 'Fecha de Revisión');

/****************************************/
/*cambio del 27-07*/
/****************************************/

ALTER TABLE `mos_usuario`
ADD COLUMN `id_idioma`  int NULL DEFAULT 1 AFTER `recibe_notificaciones`;

DROP TABLE IF EXISTS `mos_idiomas`;
CREATE TABLE `mos_idiomas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idioma` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mos_idiomas
-- ----------------------------
INSERT INTO `mos_idiomas` VALUES ('1', 'Español');
INSERT INTO `mos_idiomas` VALUES ('2', 'Portugues');

INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'password_2', 'Nuevo Password', '21', 'Nuevo Password');
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'password_3', 'Confirme Password ', '21', 'Confirme Password');
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder) VALUES ( 'id_idioma', 'Idioma', '21', 'Idioma');

ALTER TABLE `mos_nombres_campos`
ADD COLUMN `id_idioma`  int NULL DEFAULT 1 AFTER `placeholder`;

-- CON ESTO DUPLICAMOS LOS REGISTROS DE DOCUMENTOS, MOS_USUARIO Y REGISTROS 
insert into mos_nombres_campos (nombre_campo, texto, modulo, placeholder,id_idioma)
SELECT nombre_campo, texto, modulo, placeholder, 2 idioma
FROM `mos_nombres_campos`
WHERE modulo in (21,6,9) ORDER BY modulo;


DROP TABLE IF EXISTS `mos_nombres_link_idiomas`;
CREATE TABLE `mos_nombres_link_idiomas` (
  `cod_link` int(11) NOT NULL DEFAULT '0',
  `id_idioma` int(11) NOT NULL DEFAULT '0',
  `nombre_link` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cod_link`,`id_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mos_nombres_link_idiomas
-- ----------------------------
INSERT INTO `mos_nombres_link_idiomas` VALUES ('1', '1', 'Indicadores');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('1', '2', 'indicadores');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('2', '1', 'Matriz');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('2', '2', 'matriz');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('3', '1', 'Documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('3', '2', 'documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('4', '1', 'Estructura');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('4', '2', 'estrutura');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('5', '1', 'Personal');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('5', '2', 'pessoal');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('6', '1', 'Accidentes Ley');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('6', '2', 'acidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('7', '1', 'Incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('7', '2', 'incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('8', '1', 'Ocurrencias');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('8', '2', 'Ocorrências');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('9', '1', 'Competencias');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('9', '2', 'competências');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('10', '1', 'Configuración');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('10', '2', 'configuração');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('11', '1', 'Administrador de Matriz Aspectos - Impactos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('11', '2', 'gestor de matriz - aspectos e impactos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('12', '1', 'Administrador de Documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('12', '2', 'gestor de documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('13', '1', 'Reportes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('13', '2', 'resumos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('14', '1', 'Maestro de Documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('14', '2', 'mestre de documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('15', '1', 'Maestro de Documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('15', '2', 'mestre de documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('16', '1', 'Maestro de Registros');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('16', '2', 'mestre de registros');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('17', '1', 'Reporte documentos por Árbol');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('17', '2', 'resumo de documentos por árvore');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('18', '1', 'Árbol Organizacional');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('18', '2', 'árvore organizacional');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('19', '1', 'Árbol de Procesos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('19', '2', 'árvore de processos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('20', '1', 'Personal Directo');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('20', '2', 'pessoal direto');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('21', '1', 'Listado de Cargos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('21', '2', 'lista de cargos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('22', '1', 'Administrador de actividades de Capacitación');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('22', '2', 'gestor da atividades de treinamento');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('23', '1', 'Reporte Personal');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('23', '2', 'resumo de pessoal');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('24', '1', 'Reporte Personal PDF');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('24', '2', 'resumo pessoal pdf');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('25', '1', 'Fichas de cursos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('25', '2', 'ficha de cursos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('26', '1', 'Reporte Capacitaciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('26', '2', 'resumo de capacitação');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('27', '1', 'Reporte Personal XLS');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('27', '2', 'resumo de pessoal xls');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('28', '1', 'Administrador de Accidentes Ley');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('28', '2', 'gerente lei de acidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('29', '1', 'Cierres de Mes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('29', '2', 'fechamento de mês');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('30', '1', 'reportes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('30', '2', 'resumos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('31', '1', 'Registro Accidente Ley');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('31', '2', 'registro de acidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('32', '1', 'Cierre Mes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('32', '2', 'fechamento de mês');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('33', '1', 'Tasas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('33', '2', 'taxas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('34', '1', 'Estadística Distributiva');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('34', '2', 'estatísticas distributivas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('35', '1', 'Estadística Semestral');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('35', '2', 'estatísticas semestrais');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('36', '1', 'Condiciones Lógicas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('36', '2', 'condições lógicas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('37', '1', 'Aplicación DS 67');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('37', '2', 'aplicação nr xx');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('38', '1', 'Estadistica Mensual');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('38', '2', 'estatísticas mensais');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('39', '1', 'Estadistica Mensual Informe');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('39', '2', 'relatório estatístico mensal');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('40', '1', 'Cierre de Mes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('40', '2', 'fechamento de mês');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('41', '1', 'Cierre de Mes Por Árbol de Procesos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('41', '2', 'fechamento de mês por árvore de processos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('42', '1', 'Cierre de Mes Por Árbol Organizacional');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('42', '2', 'fechamento de mês por árvore organizacional');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('43', '1', 'Tasas Web');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('43', '2', 'taxas de web');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('44', '1', 'Tasas Excel');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('44', '2', 'taxas de excel');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('45', '1', 'Tasas Gráficos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('45', '2', 'taxas gráficos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('46', '1', 'Maestro Accidentes PDF');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('46', '2', 'mestre de acidentes pdf');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('47', '1', 'Maestro Accidentes Excel');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('47', '2', 'mestre de acidentes xls');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('48', '1', 'Informes Individuales');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('48', '2', 'relatórios individuais');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('49', '1', 'Árbol Organizacional');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('49', '2', 'árvore organizacional');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('50', '1', 'Administrador de  Ocurrencias con Plan de Acción');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('50', '2', 'gestor de ocorrências com plano de acção');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('51', '1', 'Configuración');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('51', '2', 'configuração');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('52', '1', 'Reporte de Ocurrencias con Plan de Acción');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('52', '2', 'Resumos Ocorrências com Plano de Acção');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('55', '1', 'Incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('55', '2', 'Incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('56', '1', 'Reportes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('56', '2', 'resumos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('57', '1', 'Incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('57', '2', 'incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('58', '1', 'Competencia familia');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('58', '2', 'família de competências');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('59', '1', 'Competencias requisitos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('59', '2', 'requisitos de competências');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('60', '1', 'Competencias cargos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('60', '2', 'competências cargos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('61', '1', 'Competencias personas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('61', '2', 'competências de pessoas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('62', '1', 'reportes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('62', '2', 'resumos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('63', '1', 'Reporte Competencias - Matriz');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('63', '2', 'resumo competências de pessoas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('64', '1', 'Usuarios');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('64', '2', 'usuários');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('65', '1', 'Parámetros Módulos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('65', '2', 'parâmetros modules');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('66', '1', 'Administrador de Perfiles');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('66', '2', 'gestor de perfil');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('67', '1', 'Unidad de Negocio');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('67', '2', 'unidade de negócio');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('68', '1', 'Configuración');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('68', '2', 'configuração');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('69', '1', 'Parámetro Formulario');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('69', '2', 'formulário de parâmetro');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('70', '1', 'Parámetros Generales Sistema');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('70', '2', 'parâmetros gerales  do sistema');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('71', '1', 'Reportes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('71', '2', 'resumos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('72', '1', 'Excel');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('72', '2', 'excel');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('73', '1', 'Capacitaciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('73', '2', 'capacitações');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('74', '1', 'Personal Indirecto');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('74', '2', 'pessoal indireto');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('75', '1', 'Parámetros de Documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('75', '2', 'configurações de documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('76', '1', 'Parámetros de Personas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('76', '2', 'parâmetros de pessoas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('77', '1', 'Administrador de  Ocurrencias con Correcciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('77', '2', 'gestor de ocorrências com correções');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('78', '1', 'Parámetros de Correcciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('78', '2', 'parâmetros correções');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('79', '1', 'Parámetros de AC');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('79', '2', 'parâmetros ac');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('80', '1', 'Perfiles Especialistas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('80', '2', 'perfis de especialistas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('81', '1', 'Perfiles Portal');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('81', '2', 'perfis Portal');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('82', '1', 'WorkFlow de Documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('82', '2', 'WorkFlow de documentos');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('83', '1', 'Inspecciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('83', '2', 'inspeções');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('84', '1', 'Plantillas de Inspecciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('84', '2', 'modelos de inspecções');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('85', '1', 'Notificaciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('85', '2', 'notificações');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('86', '1', 'Parametros de Inspecciones');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('86', '2', 'parâmetros de inspeção');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('87', '1', 'Administrador de Formularios');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('87', '2', 'Forms Manager');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('88', '1', 'Parámetros de Formularios');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('88', '2', 'parâmetros de formulários');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('89', '1', 'Configuración');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('89', '2', 'configuração');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('90', '1', 'Códigos de Areas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('90', '2', 'códigos de Áreas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('91', '1', 'Lista de Distribución');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('91', '2', 'Lista de distribuição');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('92', '1', 'Migración de Responsabilidades');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('92', '2', 'migração de Responsabilidades');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('93', '1', 'Seguimiento de Acciones Correctivas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('93', '2', 'Seguimento de Ação Corretiva');



/****************************************/
/*cambio del 10-08*/
/****************************************/

ALTER TABLE `mos_nombres_campos`
MODIFY COLUMN `texto`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `nombre_campo`,
MODIFY COLUMN `placeholder`  varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `modulo`;
/****************************************/
/*cambio del 12-08*/
/****************************************/
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nivel', 'Nivel', '34','Nivel',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nivel', 'Nível', '34','Nível',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nivel', 'Nivel', '33','Nivel',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nivel', 'Nível', '33','Nível',2);

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'proceso', 'Proceso', '34','Proceso',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'actividad', 'Actividad', '34','Actividad',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'sub_actividad', 'Sub Actividad', '34','Sub Actividad',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'proceso', 'Processo', '34','Processo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'actividad', 'Atividade', '34','Atividade',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'sub_actividad', 'Sub Atividade', '34','Sub Atividade',2);

/* TRADUCCION CARGADA */
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cancelar', 'Cancelar', '100', 'cancelar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cerrar', 'Cerrar', '100', 'cerrar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'exportar', 'Exportar', '100', 'exportar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtrar', 'Filtrar', '100', 'filtrar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtrar_listado', 'Filtrar Listado', '100', 'filtrar_listado',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtrar_por', 'Filtrar por', '100', 'filtrar_por',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtro_avanzado', 'Filtro Avanzado', '100', 'filtro_avanzado',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'minimizar_filtro', 'Minimizar Filtro', '100', 'minimizar_filtro',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nuevo', 'Nuevo', '100', 'nuevo',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'opciones_visualizacion', 'Opciones de visualización', '100', 'opciones_visualizacion',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personalizar', 'Personalizar', '100', 'personalizar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'seleccionar', 'Seleccionar', '100', 'seleccionar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'todos', 'Todos', '100', 'todos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'volver', 'volver', '100', 'volver',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cancelar', 'Cancelar', '100', 'cancelar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cerrar', 'Perto', '100', 'cerrar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'exportar', 'Exportação', '100', 'exportar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtrar', 'Filtro', '100', 'filtrar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtrar_listado', 'Lista de filtros', '100', 'filtrar_listado',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtrar_por', 'Filtrar por', '100', 'filtrar_por',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'filtro_avanzado', 'Filtro avançado', '100', 'filtro_avanzado',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'minimizar_filtro', 'Fechar Filtro', '100', 'minimizar_filtro',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nuevo', 'Novo', '100', 'nuevo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'opciones_visualizacion', 'Opções de exibição', '100', 'opciones_visualizacion',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personalizar', 'Personalizar', '100', 'personalizar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'seleccionar', 'Selecionar', '100', 'seleccionar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'todos', 'Tudo', '100', 'todos',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'volver', 'Retorno', '100', 'volver',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'mi_usuario', 'Mi Usuario', '100', 'mi_usuario',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'general', 'General', '100', 'general',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_perfil_especialista', 'Accesos&nbsp;a&nbsp;Perfiles&nbsp;Especialista', '100', 'acceso_perfil_especialista',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_perfil_portal', 'Accesos&nbsp;a&nbsp;Perfiles&nbsp;Portal', '100', 'acceso_perfil_portal',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'usuario', 'USUARIO', '100', 'usuario',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'perfil', 'PERFIL', '100', 'perfil',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_estructura', 'Accesos&nbsp;a&nbsp;Estructura', '100', 'acceso_estructura',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'administracion_acceso', 'Administracion de Accesos', '100', 'administracion_acceso',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'mi_usuario', 'meu usuário', '100', 'mi_usuario',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'general', 'geral', '100', 'general',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_perfil_especialista', 'Acesso&nbsp;para &nbsp; Perfis &nbsp; Specialist', '100', 'acceso_perfil_especialista',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_perfil_portal', 'Acesso&nbsp;para&nbsp;Perfis &nbsp;Portal', '100', 'acceso_perfil_portal',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'usuario', 'USUÁRIO', '100', 'usuario',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'perfil', 'PERFIL', '100', 'perfil',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_estructura', 'Acesso&nbsp;a&nbsp;Estrutura', '100', 'acceso_estructura',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'administracion_acceso', 'Gerenciamento de acesso', '100', 'administracion_acceso',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_menu', 'Accesos&nbsp;a&nbsp;Menu', '100', 'acceso_menu',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acceso_menu', 'Acesso&nbsp;ao&nbsp;menu', '100', 'acceso_menu',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'agregar', 'Agregar', '100', 'agregar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'agregar', 'Adicionar', '100', 'agregar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'si', 'Si', '100', 'si',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'si', 'Se', '100', 'si',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'no', 'No', '100', 'no',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'no', 'Não', '100', 'no',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'aceptar', 'Aceptar', '100', 'aceptar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'aceptar', 'Aceitar', '100', 'aceptar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'desde', 'Desde', '100', 'desde',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'hasta', 'Hasta', '100', 'hasta',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'desde', 'de', '100', 'desde',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'hasta', 'para cima', '100', 'hasta',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'siguiente', 'Siguiente', '100', 'siguiente',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'siguiente', 'Seguinte', '100', 'siguiente',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'limpiar', 'Limpiar', '100', 'limpiar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'limpiar', 'Limpo', '100', 'limpiar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'imprimir', 'Imprimir', '100', 'imprimir',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'imprimir', 'impressão', '100', 'imprimir',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'buscar', 'Buscar', '100', 'buscar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'buscar', 'Pesquisa', '100', 'buscar',2);

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'recordatorio', 'acesso Reminder', '19','acesso Reminder',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'modificar_terceros', 'nomes', '19','nomes',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'visualizar_terceros', 'Terceiros de exibição', '19','Terceiros de exibição',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar', 'Remover o acesso', '19','Remover o acesso',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'modificar', 'Alterar Acesso', '19','Alterar Acesso',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nuevo', 'Entrar Novo', '19','Entrar Novo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'descripcion_perfil', 'Descrição Perfil', '19','Descrição Perfil',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cod_perfil', 'Perfil código', '19','Perfil código',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'id', 'id', '24','id',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'fecha', 'data', '24','data',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'email', 'e-mail', '24','e-mail',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'asunto', 'negócio', '24','negócio',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo', 'corpo', '24','corpo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'fecha_leido', 'Eu li data', '24','Eu li data',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'modulo', 'modulo', '24','modulo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'fecha_alerta', 'alerta data', '24','alerta data',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'estado', 'Entidade Estado', '24','Entidade Estado',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'dias_validez', 'Días validez', '6','Días validez',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'desde', 'Desde', '6','Desde',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'hasta', 'Hasta', '6','Hasta',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'numero_dias', 'Nro. de Dias', '6','Nro. de Dias',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'version', 'Versión', '6','Versión',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'fecha_revision', 'Fecha Revisión', '6','Fecha Revisión',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_original_1', 'Se ha publicado el Documento', '6','Se ha publicado el Documento',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_original_2', 'se debe enviar copia controlada a los siguientes colaboradores', '6','se debe enviar copia controlada a los siguientes colaboradores',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_version_1', 'Se ha Actualizado el Documento', '6','Se ha Actualizado el Documento',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_version_2', 'a la Versión', '6','a la Versión',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_version_3', 'se debe actualizar copia controlada a los siguientes colaboradores', '6','se debe actualizar copia controlada a los siguientes colaboradores',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'senior', 'Sr.(a)', '6','Sr.(a)',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'aviso', 'Aviso', '6','Aviso',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'semanas', 'Semanas', '6','Semanas',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'vigencia_titulo', 'Vigencia', '6','Vigencia',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'meses', 'Mes(es)', '6','Mes(es)',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'otros_datos', 'Otros Datos', '6','Otros Datos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'parametros_indexacion', 'Parámetros para Indexación de Registros', '6','Parámetros para Indexación de Registros',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'anexos_doc_relacionados', 'Anexos / Documentos Relacionados', '6','Anexos / Documentos Relacionados',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cargos', 'Cargos', '6','Cargos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombre_registros', 'Nombre', '6','Nombre',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'tipo_registros', 'Tipo', '6','Tipo',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'valores_registros', 'Valores', '6','Valores',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'anexos', 'Anexos', '6','Anexos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'documentos_relacionados', 'Documentos Relacionados', '6','Documentos Relacionados',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'motivo_vigencia', 'Motivo de Vigencia', '6','Motivo de Vigencia',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'historico_flujo_datos', 'Hist&oacute;rico Flujo de Datos', '6','Hist&oacute;rico Flujo de Datos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'operacion', 'Operaci&oacute;n', '6','Operaci&oacute;n',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'usuario_responsable', 'Usuario Responsable', '6','Usuario Responsable',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'revisiones_documentos', 'Revisiones del Documento', '6','Revisiones del Documento',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear_revision', 'Crear Revisión', '6','Crear Revisión',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'historico_revision', 'Histórico de Revisiones', '6','Histórico de Revisiones',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'revision', 'Revisión', '6','Revisión',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'versiones_documento', 'Versiones del Documento', '6','Versiones del Documento',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear_version', 'Crear Versión', '6','Crear Versión',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'historico_versiones', 'Histórico de Versiones', '6','Histórico de Versiones',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'agregar_nueva_version_documento', 'Agregar Nueva Versión Documento', '6','Agregar Nueva Versión Documento',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'flujo_trabajo', 'Flujo de Trabajo', '6','Flujo de Trabajo',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'observacion_rechazo_documento', 'Observaciones de Rechazo de Documento', '6','Observaciones de Rechazo de Documento',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'documento_fuente', 'DOCUMENTO FUENTE ', '6','DOCUMENTO FUENTE ',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'documento_visualizacion', 'DOCUMENTO DE VISUALIZACI&Oacute;N', '6','DOCUMENTO DE VISUALIZACI&Oacute;N',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'dias_validez', 'dias de validade', '6','dias de validade',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'desde', 'de', '6','de',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'hasta', 'para cima', '6','para cima',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'numero_dias', 'Número de Dias', '6','Número de Dias',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'version', 'versão', '6','versão',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'fecha_revision', 'data de revisão', '6','data de revisão',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_original_1', 'Documento foi publicado', '6','Documento foi publicado',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_original_2', 'cópia controlada devem ser enviadas para os seguintes contribuintes', '6','cópia controlada devem ser enviadas para os seguintes contribuintes',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_version_1', 'Documento é atualizado', '6','Documento é atualizado',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_version_2', 'para a Versão', '6','para a Versão',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cuerpo_notificacion_lista_version_3', 'cópia controlada deve ser atualizado pelos seguintes contribuintes', '6','cópia controlada deve ser atualizado pelos seguintes contribuintes',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'senior', 'Sr. (a)', '6','Sr. (a)',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'aviso', 'aviso', '6','aviso',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'semanas', 'semanas', '6','semanas',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'vigencia_titulo', 'validade', '6','validade',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'meses', 'Mês (s)', '6','Mês (s)',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'otros_datos', 'Outros dados', '6','Outros dados',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'parametros_indexacion', 'Parâmetros para registros de indexação', '6','Parâmetros para registros de indexação',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'anexos_doc_relacionados', 'Anexos / Documentos relacionados', '6','Anexos / Documentos relacionados',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cargos', 'encargos', '6','encargos',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombre_registros', 'nome', '6','nome',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'tipo_registros', 'tipo', '6','tipo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'valores_registros', 'valores', '6','valores',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'anexos ', 'anexos', '6','anexos',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'documentos_relacionados', 'documentos relacionados', '6','documentos relacionados',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'motivo_vigencia', 'motivo eficaz', '6','motivo eficaz',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'historico_flujo_datos', 'Dataflow rica; Hist & oacute', '6','Dataflow rica; Hist & oacute',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'operacion', 'Trade & oacute; n', '6','Trade & oacute; n',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'usuario_responsable', 'user Manager', '6','user Manager',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'revisiones_documentos', 'Comentários de documentos', '6','Comentários de documentos',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear_revision', 'Criar comentário', '6','Criar comentário',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'historico_revision', 'Histórico da revisão', '6','Histórico da revisão',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'revision', 'revisão', '6','revisão',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'versiones_documento', 'Versões de documentos', '6','Versões de documentos',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear_version', 'Criar versão', '6','Criar versão',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'historico_versiones', 'Histórico de Versões', '6','Histórico de Versões',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'agregar_nueva_version_documento', 'Adicionar nova versão do documento', '6','Adicionar nova versão do documento',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'flujo_trabajo', 'O fluxo de trabalho', '6','O fluxo de trabalho',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'observacion_rechazo_documento', 'Observações de documentos Rejeição', '6','Observações de documentos Rejeição',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'documento_fuente', 'documento de origem', '6','documento de origem',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'documento_visualizacion', 'DOCUMENTO DE EXIBIÇÃO & oacute; N', '6','DOCUMENTO DE EXIBIÇÃO & oacute; N',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'completado', 'Completado', '27','Completado',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'pendiente', 'Pendiente', '27','Pendiente',1);


INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'completado', 'concluído', '27','concluído',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'pendiente', 'pendente', '27','pendente',2);


INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personal_disponible', 'Personal Disponible', '27','Personal Disponible',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personal_capacitado', 'Personal Capacitado', '27','Personal Capacitado',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personas_seleccionadas', 'Personas seleccionadas', '27','Personas seleccionadas',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personal_disponible', 'pessoal Disponível', '27','pessoal Disponível',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personal_capacitado', 'Pessoal capacitado', '27','Pessoal capacitado',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personas_seleccionadas', 'pessoas selecionadas', '27','pessoas selecionadas',2);


INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'masculino', 'Masculino', '1','Masculino',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'femenino', 'Femenino', '1','Femenino',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'masculino', 'Masculino', '1','Masculino',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'femenino', 'Feminino', '1','Feminino',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'datos_capacitacion', 'Datos Capacitación', '5','Datos Capacitación',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personal_capacitado', 'Personal Capacitado', '5','Personal Capacitado',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'evaluacion_capacitacion', 'Evaluación de Capacitación', '5','Evaluación de Capacitación',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombres', 'Nombres', '5','Nombres',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'apellido_paterno', 'Apellido Paterno', '5','Apellido Paterno',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'apellido_materno', 'Apellido Materno', '5','Apellido Materno',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_organizacional', 'Arbol Organizacional', '5','Arbol Organizacional',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personas', 'Personas', '5','Personas',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personas_seleccionadas', 'Personas seleccionadas', '5','Personas seleccionadas',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombres_apellidos', 'Nombres y Apellidos', '5','Nombres y Apellidos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'datos_capacitacion', 'Dados de treinamento', '5','Dados de treinamento',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personal_capacitado', 'Pessoal capacitado', '5','Pessoal capacitado',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'evaluacion_capacitacion', 'Avaliação da formação', '5','Avaliação da formação',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombres', 'Nomes', '5','Nomes',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'apellido_paterno', 'Apelido paterno', '5','Apelido paterno',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'apellido_materno', 'Sobrenome materno', '5','Sobrenome materno',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_organizacional', 'árvore organizacional', '5','árvore organizacional',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personas', 'Pessoas', '5','Pessoas',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'personas_seleccionadas', 'Pessoas selecionadas', '5','Pessoas selecionadas',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombres_apellidos', 'Nomes e sobrenomes', '5','Nomes e sobrenomes',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'categorias_verificadores', 'Categorías y Verificadores', '20','Categorías y Verificadores',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'agregar_categoria', 'Agregar Categoría', '20','Agregar Categoría',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombre', 'Nombre', '20','Nombre',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'verificadores', 'Verificadores', '20','Verificadores',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'categorias_verificadores', 'Categorias e verificadores', '20','Categorias e verificadores',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'agregar_categoria', 'Adicionar categoria', '20','Adicionar categoria',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombre', 'Nome', '20','Nome',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'verificadores', 'Damas', '20','Damas',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear', 'Crear', '33','Crear',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'renombrar', 'Renombrar', '33','Renombrar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar', 'Eliminar', '33','Eliminar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'abrir_todos', 'Abrir Todos', '33','Abrir Todos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cerrar_todos', 'Cerrar Todos', '33','Cerrar Todos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_organizacional', 'Árbol Organizacional', '33','Árbol Organizacional',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'vincular_area', 'Vincular Área', '33','Vincular Área',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar_vinculo_area', 'Eliminar Vinculo Área', '33','Eliminar Vinculo Área',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear', 'Criar', '33','Criar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'renombrar', 'Rebatizar', '33','Rebatizar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar', 'Remover', '33','Remover',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'abrir_todos', 'Aberto todo', '33','Aberto todo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cerrar_todos', 'Fechar tudo', '33','Fechar tudo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_organizacional', 'árvore organizacional', '33','árvore organizacional',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'vincular_area', 'área de ligação', '33','área de ligação',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar_vinculo_area', 'Remover Vinculo Área', '33','Remover Vinculo Área',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear', 'Crear', '34','Crear',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'renombrar', 'Renombrar', '34','Renombrar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar', 'Eliminar', '34','Eliminar',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'abrir_todos', 'Abrir Todos', '34','Abrir Todos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cerrar_todos', 'Cerrar Todos', '34','Cerrar Todos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_organizacional', 'Árbol Organizacional', '34','Árbol Organizacional',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'vincular_area', 'Vincular Área', '34','Vincular Área',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar_vinculo_area', 'Eliminar Vinculo Área', '34','Eliminar Vinculo Área',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'crear', 'Criar', '34','Criar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'renombrar', 'Rebatizar', '34','Rebatizar',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar', 'Remover', '34','Remover',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'abrir_todos', 'Aberto todo', '34','Aberto todo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cerrar_todos', 'Fechar tudo', '34','Fechar tudo',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_organizacional', 'árvore organizacional', '34','árvore organizacional',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'vincular_area', 'área de ligação', '34','área de ligação',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'eliminar_vinculo_area', 'Remover Vinculo Área', '34','Remover Vinculo Área',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_proceso', 'Árbol de Procesos', '34','Árbol de Procesos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'seleccione_area_admin_arbol', 'Seleccione un Área para administrar el Arbol de Procesos', '34','Seleccione un Área para administrar el Arbol de Procesos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'arbol_proceso', 'Árvore de processos', '34','Árvore de processos',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'seleccione_area_admin_arbol', 'Selecione uma área para gerenciar a árvore de processo', '34','Selecione uma área para gerenciar a árvore de processo',2);

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `mos_nombres_campos_aux`
-- ----------------------------
DROP TABLE IF EXISTS `mos_nombres_campos_aux`;
CREATE TABLE `mos_nombres_campos_aux` (
  `campo` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `Modulo` varchar(255) DEFAULT NULL,
  `placeholder` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mos_nombres_campos_aux
-- ----------------------------
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_personal', 'ID', '1', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nombres', 'nomes', '1', 'nomes');
INSERT INTO `mos_nombres_campos_aux` VALUES ('apellido_paterno', 'sobrenome principal', '1', 'sobrenome principal');
INSERT INTO `mos_nombres_campos_aux` VALUES ('apellido_materno', 'sobrenome secundario', '1', 'sobrenome secundario');
INSERT INTO `mos_nombres_campos_aux` VALUES ('genero', 'sexo', '1', 'sexo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_nacimiento', 'data de nascimento', '1', 'data');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'ativo', '1', 'ativo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('interno', 'pessoal própria', '1', 'pessoal própria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_organizacion', 'árvore organizacional', '1', 'árvore organizacional');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_cargo', 'cargo', '1', 'cargo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('workflow', 'fluxo de trabalho', '1', 'fluxo de trabalho');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email', 'email', '1', 'email');
INSERT INTO `mos_nombres_campos_aux` VALUES ('relator', 'relator', '1', 'caixa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('reviso', 'analisa', '1', 'analisa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('elaboro', 'autor', '1', 'autor');
INSERT INTO `mos_nombres_campos_aux` VALUES ('aprobo', 'aprovador', '1', 'aprovador');
INSERT INTO `mos_nombres_campos_aux` VALUES ('extranjero', 'estrangeiro', '1', 'estrangeiro');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_ingreso', 'data de admissão', '1', 'data de admissão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_egreso', 'data de demissão', '1', 'data de demissão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_emp', 'CPF', '2', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha', 'data', '2', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('anotacion', 'anotações', '2', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('archivo', 'arquivo', '2', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nom_archivo', 'nom. arquivo', '2', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_hoja_vida', 'código', '2', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_contratista', 'empresa contratada', '1', 'empresa contratada');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'identificação', '3', 'identificação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'ativo', '3', 'ativo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('interno', 'interno', '3', 'interno');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_curso', 'código', '4', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('identificacion', 'identificação', '4', 'identificação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '4', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_clase', 'classe', '4', 'classe');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_tipo', 'tipo', '4', 'tipo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'validade', '4', 'validade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('aplica_evaluacion', 'aplicar a avaliação', '4', 'aplicar a avaliação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_curso', 'capacitação', '5', 'Capacitação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_emp_relator', 'relator', '5', 'relator');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha', 'data de início', '5', 'Data de início');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nom_archivo', 'traçabilidade', '5', 'traçabilidade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('archivo', 'traçabilidade', '5', 'traçabilidade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('observacion', 'observação', '5', 'observação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('hora', 'duração', '5', 'duração');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_termino', 'data de conclusão', '5', 'data de conclusão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_emp', 'CPF', '5', 'CPF');
INSERT INTO `mos_nombres_campos_aux` VALUES ('aprobacion', 'aprovo', '5', 'aprovo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('asistencia', 'assistência', '5', 'assistência');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nota_evaluacion', 'avaliar (% ou nota)', '5', 'Avaliar (% ou Nota)');
INSERT INTO `mos_nombres_campos_aux` VALUES ('IDDoc', 'IDDoc', '6', 'IDDoc');
INSERT INTO `mos_nombres_campos_aux` VALUES ('hh', 'horas homen', '5', 'Horas homen');
INSERT INTO `mos_nombres_campos_aux` VALUES ('Codigo_doc', 'código', '6', 'código_doc');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nombre_doc', 'nome do documento', '6', 'nombre_doc');
INSERT INTO `mos_nombres_campos_aux` VALUES ('version', 'versão', '6', 'versão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha', 'data', '6', 'data');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '6', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('palabras_claves', 'palavras chaves', '6', 'palavras chaves');
INSERT INTO `mos_nombres_campos_aux` VALUES ('formulario', 'registros', '6', 'formulário');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'validade', '6', 'validade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('doc_fisico', 'documento de origem', '6', 'documento de origem');
INSERT INTO `mos_nombres_campos_aux` VALUES ('contentType', 'descarregar PDF', '6', 'descarregar PDF');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_filial', 'id_filial', '6', 'id_filial');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nom_visualiza', 'vista do documento', '6', 'nom_visualiza');
INSERT INTO `mos_nombres_campos_aux` VALUES ('doc_visualiza', 'doc_exibe', '6', 'doc_exibe');
INSERT INTO `mos_nombres_campos_aux` VALUES ('contentType_visualiza', 'descarregar documento fonte', '6', 'contentType_visualiza');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_usuario', 'id do usuário', '6', 'ID do usuário');
INSERT INTO `mos_nombres_campos_aux` VALUES ('observacion', 'observação', '6', 'observação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('muestra_doc', '\nexibição_doc', '6', '\nexibição_doc');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estrucorg', 'estrucorg', '6', 'estrucorg');
INSERT INTO `mos_nombres_campos_aux` VALUES ('arbproc', 'árvore organizacional (níveis)', '6', 'arbproc');
INSERT INTO `mos_nombres_campos_aux` VALUES ('apli_reg_estrorg', 'apli_reg_estrorg', '6', 'apli_reg_estrorg');
INSERT INTO `mos_nombres_campos_aux` VALUES ('apli_reg_arbproc', 'apli_reg_arbproc', '6', 'apli_reg_arbproc');
INSERT INTO `mos_nombres_campos_aux` VALUES ('workflow', 'fluxo de trabalho', '6', 'fluxo de trabalho');
INSERT INTO `mos_nombres_campos_aux` VALUES ('semaforo', 'semáforo', '6', 'Semáforo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('v_meses', 'meses de validade', '6', 'v_meses');
INSERT INTO `mos_nombres_campos_aux` VALUES ('reviso', 'analisa', '6', 'analisado');
INSERT INTO `mos_nombres_campos_aux` VALUES ('elaboro', 'responsável', '6', 'responsável');
INSERT INTO `mos_nombres_campos_aux` VALUES ('aprobo', 'aprovado', '6', 'aprovado');
INSERT INTO `mos_nombres_campos_aux` VALUES ('idRegistro', 'idRegistro', '9', 'idRegistro');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'validade', '8', 'validade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '8', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_parametro_det', 'cod_parâmetro_det', '8', 'cod_parâmetro_det');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_parametro', 'cod_parâmetro', '8', 'cod_parâmetro');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_categoria', 'cod_categoria', '8', 'cod_categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tipo', 'tipo', '7', 'tipo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'validade', '7', 'validade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('ingles', 'identificação inglês', '7', 'identificação inglês');
INSERT INTO `mos_nombres_campos_aux` VALUES ('espanol', 'identificação espanhol', '7', 'identificação espanhol');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_parametro', 'cod_parâmetro', '7', 'cod_parâmetro');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_categoria', 'cod_categoria', '7', 'cod_categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('observacion_rev', 'controle de mudanças', '6', 'Controle de mudanças');
INSERT INTO `mos_nombres_campos_aux` VALUES ('IDDoc', 'código', '9', 'IDDoc');
INSERT INTO `mos_nombres_campos_aux` VALUES ('identificacion', 'identificação', '9', 'identificação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('version', 'versão', '9', 'versão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('correlativo', 'correlativo', '9', 'correlativo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_usuario', 'id do usuário', '9', 'ID do usuário');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '9', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('doc_fisico', 'documento', '9', 'doc_fisico');
INSERT INTO `mos_nombres_campos_aux` VALUES ('contentType', 'registros', '9', 'contentType');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_procesos', 'árvore de processos', '9', 'id_processos');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_organizacion', 'árvore organizacional', '9', 'id_organizacion');
INSERT INTO `mos_nombres_campos_aux` VALUES ('peso_especifico', 'peso específico', '11', 'peso específico');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_control_detalle', 'id_control_detalle', '11', 'id_control_detalle');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_acap', 'id_acap', '11', 'id_acap');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_categoria', 'cod_categoria', '11', 'cod_categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_categoria', 'cod_categoria', '10', 'cod_categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_acap', 'id_acap', '10', 'id_acap');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_cargo', 'cod_cargo', '10', 'cod_cargo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_emp', 'cod_emp', '10', 'cod_emp');
INSERT INTO `mos_nombres_campos_aux` VALUES ('bloqueo', 'bloqueio', '10', 'bloqueio');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_proceso', 'árvore de processos', '10', 'process_id');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_organizacion', 'árvore organizacional', '10', 'id_organizacion');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_evidencia', 'cod_traçabilidade', '12', 'cod_traçabilidade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_categoria', 'cod_categoria', '12', 'cod_categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_acap', 'id_acap', '12', 'id_acap');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_control_detalle', 'id_control_detalle', '12', 'id_control_detalle');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_detalle', 'id_detalhe', '12', 'id_detalhe');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_evi', 'data', '12', 'data_evi');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_emp', 'responsável', '12', 'cod_emp');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nom_archivo', 'o nome do arquivo', '12', 'nom_archivo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('archivo', 'arquivo', '12', 'arquivo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('contenttype', 'contenttype', '12', 'contenttype');
INSERT INTO `mos_nombres_campos_aux` VALUES ('observacion', 'nota', '12', 'observação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_categoria', 'cod_categoria', '13', 'cod_categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_cmb_acap', 'ID', '13', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nombre', 'nome', '13', 'nome');
INSERT INTO `mos_nombres_campos_aux` VALUES ('dependencia', 'seção', '13', 'seção');
INSERT INTO `mos_nombres_campos_aux` VALUES ('texto', 'texto', '13', 'texto');
INSERT INTO `mos_nombres_campos_aux` VALUES ('formula', 'formula', '13', 'formula');
INSERT INTO `mos_nombres_campos_aux` VALUES ('calculo_formula', 'calculo_formula', '13', 'calculo_formula');
INSERT INTO `mos_nombres_campos_aux` VALUES ('muestra', 'exibe', '13', 'exibe');
INSERT INTO `mos_nombres_campos_aux` VALUES ('muestrarpt', 'muestrarpt', '13', 'muestrarpt');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tipo', 'tipo', '13', ' tipo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('indicador', 'data semaforo', '13', 'indicador');
INSERT INTO `mos_nombres_campos_aux` VALUES ('orden', 'N° Orden', '13', 'Despacho n.º');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_nom1', 'nome data (1)', '13', 'Nome Data (1)');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_nom2', 'nome data (2)', '13', 'Nome Data (2)');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_sem', 'nome do semáforo', '13', 'nome do semáforo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('datos', 'nome do fluxo de trabalho', '13', 'nome do fluxo de trabalho');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tip_familia_requisito', 'tip_família_requisito', '13', 'tip_família_requisito');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_categoria', 'cod_categoria', '14', 'cod_categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_cmb_acap', 'id_cmb_acap', '14', 'id_cmb_acap');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_item', 'id_item', '14', 'id_item');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nombre', 'nome', '14', 'nome');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '14', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'validade', '14', 'validade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('factor', 'fator', '14', 'fator');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '15', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('origen_hallazgo', 'origem hallazgo', '15', 'origem hallazgo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_generacion', 'data de hallazgo', '15', 'dd / mm / aaaa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '15', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('analisis_causal', 'análise causal', '15', 'análise causal');
INSERT INTO `mos_nombres_campos_aux` VALUES ('responsable_analisis', 'análise responsável', '15', 'Análise responsável');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_organizacion', 'árvore organizacional', '15', 'id_organizacion');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_proceso', 'processos árvore', '15', 'process_id');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_acordada', 'concordou data de verificação', '15', 'dd / mm / aaaa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_realizada', 'data de ação de verificação', '15', 'dd / mm / aaaa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_responsable_segui', 'verificação responsável', '15', 'id_responsable_segui');
INSERT INTO `mos_nombres_campos_aux` VALUES ('alto_potencial', 'potencial', '15', 'potencial');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '16', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tipo', 'tipo', '16', 'tipo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('accion', 'ação', '16', 'ação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_acordada', 'data confirmada', '16', 'data_acordada');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_realizada', 'data interpretada', '16', 'data_realizada');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_responsable', 'atuação responsável', '16', 'id_responsable');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_ac', 'id_ac', '16', 'id_ac');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_correcion', 'id_correção', '16', 'id_correção');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado_seguimiento', 'acção estado', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('trazabilidad', 'verificação rastreabilidade', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado_seguimiento', 'verificação estado', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nombre_seccion', 'ações corretivas', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('trazabilidad', 'acção rastreabilidade', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_persona', 'id_pessoa', '17', 'id_pessoa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_evi', 'data_evi', '17', 'data_evi');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tipo', 'tipo', '17', 'tipo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_accion', 'id_ação', '17', 'id_ação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_accion_correctiva', 'id_ação_correctiva', '17', 'id_ação_correctiva');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '18', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('origen_hallazgo', 'origem hallazgo', '18', 'origen_hallazgo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_generacion', 'data de geração', '18', 'data_geração');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '18', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_organizacion', 'árvore organizacional', '18', 'id_organizacion');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_proceso', 'processos árvore', '18', 'process_id');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nombre_seccion_2', 'correções', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '30', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_personal', 'id_pessoal', '30', 'id_pessoal');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email', 'email', '30', 'email');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_personal_wf', 'id_pessoal_wf', '30', 'id_pessoal_wf');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email_wf', 'email_wf', '30', 'email_wf');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_personal_vaca', 'id_pessoal_vaca', '30', 'id_pessoal_vaca');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email_wf_vaca', 'email_wf_vaca', '30', 'email_wf_vaca');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email_alerta', 'email_alerta', '30', 'email_alerta');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_perfil', 'código perfil', '19', 'cod_perfil');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion_perfil', 'descrição perfil', '19', 'descrição_perfil');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nuevo', 'entrar novo', '19', 'novo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('modificar', 'acesso para alterar', '19', 'mudança');
INSERT INTO `mos_nombres_campos_aux` VALUES ('eliminar', 'acesso para apagar', '19', 'remover');
INSERT INTO `mos_nombres_campos_aux` VALUES ('recordatorio', 'acesso a lembrete', '19', 'lembrete');
INSERT INTO `mos_nombres_campos_aux` VALUES ('modificar_terceros', 'nomes', '19', 'alterar_terceiros');
INSERT INTO `mos_nombres_campos_aux` VALUES ('visualizar_terceros', 'exibição de terceiros', '19', 'exibição de terceiros');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cod_perfil', 'perfil código', '21', 'cod_perfil');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion_perfil', 'descrição perfil', '21', 'descrição_perfil');
INSERT INTO `mos_nombres_campos_aux` VALUES ('visualizar_terceros', 'exibição de terceiros', '21', 'exibição de terceiros');
INSERT INTO `mos_nombres_campos_aux` VALUES ('visualizar_terceros', 'exibição de terceiros', '21', 'exibição de terceiros');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_usuario', 'ID do usuário', '21', 'ID do usuário');
INSERT INTO `mos_nombres_campos_aux` VALUES ('nombres', 'nomes', '21', 'nomes');
INSERT INTO `mos_nombres_campos_aux` VALUES ('apellido_paterno', 'sobrenome principal', '21', 'sobrenome principal');
INSERT INTO `mos_nombres_campos_aux` VALUES ('apellido_materno', 'sobrenome secundario', '21', 'sobrenome secundario');
INSERT INTO `mos_nombres_campos_aux` VALUES ('telefono', 'telefone', '21', 'telefone');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_expi', 'data de expiração', '21', 'data_expiração');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'validade', '21', 'validade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('super_usuario', 'super user', '21', 'super_usuario');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email', 'email', '21', 'email');
INSERT INTO `mos_nombres_campos_aux` VALUES ('password_1', 'senha', '21', 'password_1');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cedula', 'cédula', '21', 'carteira de identidade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '22', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fk_id_unico', 'fk_id_unico', '22', 'fk_id_unico');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '22', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('vigencia', 'validade', '22', 'validade');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tipo', 'tipo', '22', 'tipo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('publico', 'público', '6', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '20', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '20', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '20', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_workflow_documento', 'fluxo de trabalho para documento', '6', 'fluxo de trabalho para documento');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado_workflow', 'estado de fluxo de trabalho', '6', 'Estado de fluxo de trabalho');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_estado_workflow', 'data de fluxo de trabalho', '6', 'data de fluxo de trabalho');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_usuario_workflow', 'fluxo de trabalho do usuário', '6', 'Fluxo de trabalho do usuário');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado_pendiente_aprobacion', 'aprovação pendente', '6', 'Aprovação pendente');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado_pendiente_revision', 'pendente de revisão', '6', 'pendente de revisão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado_aprobado', 'aprovado', '6', 'aprovado');
INSERT INTO `mos_nombres_campos_aux` VALUES ('etapa_workflow', 'Fase de fluxo de trabalho', '6', 'Fase de fluxo de trabalho');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '23', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_personal_responsable', 'id_pessoal_responsável', '23', 'id_pessoal_responsável');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email_responsable', 'email_responsável', '23', 'email_responsável');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_personal_revisa', 'id_pessoal_avalia', '23', 'id_pessoal_avalia');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email_revisa', 'email_avalia', '23', 'email_avalia');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_personal_aprueba', 'id_pessoal_aprova', '23', 'id_pessoal_aprova');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email_aprueba', 'email_aprova', '23', 'email_aprova');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion_larga', 'descrição completa', '22', 'Descrição completa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '25', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '25', 'descrição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tipo_inspeccion', 'tipo_inspeção', '25', 'tipo_inspeção');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha', 'data', '25', 'data');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_responsable', 'id_responsável', '25', 'id_responsável');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_organizacion', 'id_organização', '25', 'id_organização');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_proceso', 'id_processo', '25', 'id_processo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('ubicacion', 'localização', '25', 'localização');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '24', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha', 'data', '24', 'data');
INSERT INTO `mos_nombres_campos_aux` VALUES ('email', 'email', '24', 'email');
INSERT INTO `mos_nombres_campos_aux` VALUES ('asunto', 'negócio', '24', 'negócio');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cuerpo', 'corpo', '24', 'corpo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_leido', 'data_ler', '24', 'data_ler');
INSERT INTO `mos_nombres_campos_aux` VALUES ('modulo', 'modulo', '24', 'modulo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_alerta', 'data_alerta', '24', 'data_alerta');
INSERT INTO `mos_nombres_campos_aux` VALUES ('responsable_area', 'gestor da área', '1', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '26', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_organizacion', 'árvore organizacional', '26', 'id_organizacion');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '26', 'código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('bloqueo_codigo', 'bloqueio do código', '26', 'bloqueio_código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('bloqueo_version', 'bloqueio da versão', '26', 'bloqueio_versão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('correlativo', 'correlativo', '26', 'correlativo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('recibe_notificaciones', 'receber notificações', '21', 'recebe_notificações');
INSERT INTO `mos_nombres_campos_aux` VALUES ('responsable_desvio', 'responsável da ocorrência', '15', 'responsável da ocorrência');
INSERT INTO `mos_nombres_campos_aux` VALUES ('reportado_por', 'relatado pelo', '15', 'relatado pelo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('actualizacion_activa', 'activação actualização do registo', '6', 'activação actualização do registo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '27', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado', 'Estado da lista de distribuição', '27', 'estado');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_documento', 'documento', '27', 'document_id');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_notificacion', 'data_notificação', '27', 'data_notificação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_ejecutada', 'data execução', '27', 'dd / mm / aaaa');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_responsable', 'responsável', '27', 'id_responsável');
INSERT INTO `mos_nombres_campos_aux` VALUES ('evidencias', 'evidências', '27', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_area', 'árvore organizacional', '27', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('requiere_lista_distribucion', 'requer lista de distribuição', '6', 'Requer lista de distribuição');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado_sin_asignar', 'Não designado', '6', 'Não designado');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_cargo', 'cargos', '27', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estado', 'estado entidade', '24', 'Entidade Estado');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_hora', 'data', '89', 'data');
INSERT INTO `mos_nombres_campos_aux` VALUES ('accion', 'ações', '89', 'ações');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'documento', '89', 'documento');
INSERT INTO `mos_nombres_campos_aux` VALUES ('usuario', 'usuário', '89', 'usuário');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_area', 'árvore organizacional', '28', 'árvore organizacional');
INSERT INTO `mos_nombres_campos_aux` VALUES ('codigo', 'código', '28', 'Digite o Código');
INSERT INTO `mos_nombres_campos_aux` VALUES ('descripcion', 'descrição', '28', 'Descrição da Matriz');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '28', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('categoria', 'categoria', '28', 'categoria');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_responsable_actual', 'responsável atual', '90', 'responsável atual');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_nuevo_responsable', 'novo gerente', '90', 'novo gerente');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_operacion', 'data', '90', 'data');
INSERT INTO `mos_nombres_campos_aux` VALUES ('migrar_responsable_doc', 'migração responsável', '90', 'migre responsável');
INSERT INTO `mos_nombres_campos_aux` VALUES ('migrar_wf_revisa', 'migração revisor', '90', 'migração Revisor');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_revisa', 'novo revisor', '90', 'novos Revisores');
INSERT INTO `mos_nombres_campos_aux` VALUES ('migrar_wf_aprueba', 'aprovando migrar', '90', 'aprovando migrar');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id_aprueba', 'novo aprovador', '90', 'novo aprovador');
INSERT INTO `mos_nombres_campos_aux` VALUES ('tipo_documento', 'tipo de documento', '6', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('dias_vig', 'dias', '6', 'dias');
INSERT INTO `mos_nombres_campos_aux` VALUES ('num_rev', 'revisão', '6', 'revisão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('arbol_organizacional', 'árvore organizacional (níveis)', '6', 'Árvore organizacional (níveis)');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_rev', 'data de revisão', '6', 'Data de revisão');
INSERT INTO `mos_nombres_campos_aux` VALUES ('fecha_promocion', 'data de promoção', '1', 'Data de promoção');
INSERT INTO `mos_nombres_campos_aux` VALUES ('promover_cargo', 'promover cargo', '1', 'promover cargo');
INSERT INTO `mos_nombres_campos_aux` VALUES ('en_elaboracion', 'rascunho', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('en_buzon', 'caixa de entrada', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('sin_responsable_analisis', 'sim esponsável de análise', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('sin_plan_accion', 'sim plano de ação', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('implementacion_acciones', 'execução de acções', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('verificacion_eficacia', 'verificação de eficácia', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cerrada_verificada', 'fechado e verificado', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('estatus_wf', 'fluxo de trabalho', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('en_ejecucion', 'em execução', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cerrada_verificar', 'concluída por verificar', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('cerrada_verificada', 'concluída e verificado', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('accion_ejecutada', 'ação executada', '16', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('observacion', 'observação', '17', 'observação');
INSERT INTO `mos_nombres_campos_aux` VALUES ('id', 'ID', '17', 'ID');
INSERT INTO `mos_nombres_campos_aux` VALUES ('anexos', 'anexos', '15', '');
INSERT INTO `mos_nombres_campos_aux` VALUES ('validador_accion', 'validador', '16', '');


 delete c.* from mos_nombres_campos  c
 inner join mos_nombres_campos_aux a on a.campo = c.nombre_campo and c.modulo = a.Modulo
 where c.id_idioma = 2;

 INSERT INTO mos_nombres_campos (nombre_campo, texto, placeholder, modulo, id_idioma)
 select campo,nombre,placeholder,Modulo,2 from mos_nombres_campos_aux;

update mos_nombres_campos set texto = initcap(texto), placeholder = initcap(placeholder) where id_idioma = 2;

update  mos_nombres_link_idiomas set nombre_link = initcap(nombre_link);

update mos_nombres_campos set texto = UPPER(texto), placeholder = UPPER(placeholder) where id_idioma = 2  and nombre_campo in ( 'id', 'id_personal');

UPDATE `mos_idiomas` SET `idioma`='Português' WHERE (`id`='2');

/** cambios 14/08*/
DROP TABLE IF EXISTS `mos_nombres_link_portal_idiomas`;
CREATE TABLE `mos_nombres_link_portal_idiomas` (
  `cod_link` int(11) NOT NULL DEFAULT '0',
  `id_idioma` int(11) NOT NULL DEFAULT '0',
  `nombre_link` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cod_link`,`id_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mos_nombres_link_portal_idiomas
-- ----------------------------
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('1', '1', 'Indicadores');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('1', '2', 'Indicadores');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('2', '1', 'Matriz');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('2', '2', 'Matriz');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('3', '1', 'Documentos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('3', '2', 'Documentos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('4', '1', 'Estructura');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('4', '2', 'Estrutura');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('5', '1', 'Personal');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('5', '2', 'Pessoal');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('6', '1', 'Accidentes Ley');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('6', '2', 'Lei De Acidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('7', '1', 'Incidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('7', '2', 'Incidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('8', '1', 'Acciones Correctivas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('8', '2', 'Ações Corretivas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('9', '1', 'Competencias');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('9', '2', 'Competências');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('10', '1', 'Configuración');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('10', '2', 'Configuração');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('11', '1', 'Administrador de Matriz Aspectos - Impactos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('11', '2', 'Aspectos Gestor De Matriz - Impactos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('14', '1', 'Maestro de Documentos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('14', '2', 'Documentos Mestre');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('16', '1', 'Maestro de Registros');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('16', '2', 'Registro Mestre');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('18', '1', 'Árbol Organizacional');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('18', '2', 'Árvore Organizacional');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('19', '1', 'Árbol de Procesos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('19', '2', 'Árvore De Processos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('20', '1', 'Personal Directo');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('20', '2', 'Equipe Direta');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('21', '1', 'Listado de Cargos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('21', '2', 'Lista De Custos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('22', '1', 'Administrador de actividades de Capacitación');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('22', '2', 'As Atividades De Treinamento Gerente');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('23', '1', 'Reporte Personal');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('23', '2', 'Relatório Pessoal');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('24', '1', 'Reporte Personal PDF');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('24', '2', 'Pessoal Report Pdf');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('25', '1', 'Fichas de cursos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('25', '2', 'Cursos De Folhas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('26', '1', 'Reporte Capacitaciones');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('26', '2', 'Relatório De Treino');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('27', '1', 'Reporte Personal XLS');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('27', '2', 'Relatório Xls Pessoal');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('28', '1', 'Administrador de Accidentes Ley');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('28', '2', 'Gerente Lei De Acidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('29', '1', 'Cierres de Mes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('29', '2', 'Mês Closures');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('30', '1', 'reportes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('30', '2', 'Relatórios');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('31', '1', 'Registro Accidente Ley');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('31', '2', 'Lei De Acidentes De Registro');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('32', '1', 'Cierre Mes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('32', '2', 'Mês De Fechar');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('33', '1', 'Tasas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('33', '2', 'Taxas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('34', '1', 'Estadística Distributiva');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('34', '2', 'Distributivos Estatísticas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('35', '1', 'Estadística Semestral');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('35', '2', 'Estatísticas Semestrais');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('36', '1', 'Condiciones Lógicas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('36', '2', 'Condições Lógicas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('37', '1', 'Aplicación DS 67');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('37', '2', 'Aplicação Ds 67');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('38', '1', 'Estadistica Mensual');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('38', '2', 'Estatísticas Mensais');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('39', '1', 'Estadistica Mensual Informe');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('39', '2', 'Relatório Estatístico Mensal');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('40', '1', 'Cierre de Mes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('40', '2', 'Fim Do Mês');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('41', '1', 'Cierre de Mes Por Árbol de Procesos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('41', '2', 'Árvore Mth De Processos De Encerramento');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('42', '1', 'Cierre de Mes Por Árbol Organizacional');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('42', '2', 'Mth Árvore Organizacional Encerramento');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('43', '1', 'Tasas Web');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('43', '2', 'As Taxas De Web');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('44', '1', 'Tasas Excel');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('44', '2', 'Taxas De Excel');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('45', '1', 'Tasas Gráficos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('45', '2', 'Taxas De Gráficos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('46', '1', 'Maestro Accidentes PDF');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('46', '2', 'Mestre Pdf Acidente');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('47', '1', 'Maestro Accidentes Excel');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('47', '2', 'Acidente Mestre Excel');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('48', '1', 'Informes Individuales');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('48', '2', 'Relatórios Individuais');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('49', '1', 'Árbol Organizacional');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('49', '2', 'Árvore Organizacional');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('50', '1', 'Administrador de  AC');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('50', '2', 'Ac Gestor');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('51', '1', 'Configuración de AC');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('51', '2', 'Configuração Ac');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('52', '1', 'Reportes de AC');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('52', '2', 'Relata Ac');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('55', '1', 'Incidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('55', '2', 'Incidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('56', '1', 'reportes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('56', '2', 'Relatórios');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('57', '1', 'Incidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('57', '2', 'Incidentes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('58', '1', 'Competencia familia');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('58', '2', 'Competição Família');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('59', '1', 'Competencias requisitos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('59', '2', 'Necessidades De Competências');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('60', '1', 'Competencias cargos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('60', '2', 'Competências Cobranças');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('61', '1', 'Competencias personas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('61', '2', 'Habilidades De Pessoas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('62', '1', 'reportes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('62', '2', 'Relatórios');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('63', '1', 'Reporte Competencias - Matriz');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('63', '2', 'Habilidades Relatório - Matrix');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('64', '1', 'Usuarios');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('64', '2', 'Usuários');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('65', '1', 'Parámetros Módulos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('65', '2', 'Parâmetros Modules');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('66', '1', 'Perfil');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('66', '2', 'Perfil');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('67', '1', 'Unidad de Negocio');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('67', '2', 'Unidade De Negócio');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('68', '1', 'Configuración');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('68', '2', 'Configuração');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('69', '1', 'Parámetro Formulario');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('69', '2', 'Formulário De Parâmetro');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('70', '1', 'Parámetros Generales Sistema');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('70', '2', 'Geral Parâmetros Do Sistema');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('71', '1', 'Reportes');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('71', '2', 'Relatórios');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('72', '1', 'Excel');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('72', '2', 'Sobressair');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('73', '1', 'Capacitaciones');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('73', '2', 'Treinamento');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('74', '1', 'Personal Indirecto');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('74', '2', 'Equipe Indireta');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('75', '1', 'Parámetros de Documentos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('75', '2', 'Configurações De Documentos');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('76', '1', 'Parámetros de Personas');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('76', '2', 'Pessoas Parâmetros');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('77', '1', 'Administrar Correcciones');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('77', '2', 'Gerenciar Correções');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('78', '1', 'Parámetros de Correcciones');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('78', '2', 'Parâmetros Correções');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('79', '1', 'Parámetros de AC');
INSERT INTO `mos_nombres_link_portal_idiomas` VALUES ('79', '2', 'Parâmetros Ac');


/*******19/08/2016*********/
ALTER TABLE `mos_personal`
ADD COLUMN `analisis_causa`  varchar(1) NULL DEFAULT 'N' AFTER `fecha_promocion`,
ADD COLUMN `verifica_eficacia`  varchar(1) NULL DEFAULT 'N' AFTER `analisis_causa`,
ADD COLUMN `valida_acc_co`  varchar(1) NULL DEFAULT 'N' AFTER `verifica_eficacia`,
ADD COLUMN `impresion_cc`  varchar(255) NULL DEFAULT 'N' AFTER `valida_acc_co`;

update mos_nombres_link_idiomas
set nombre_link='Administrador de Usuarios'
where cod_link=66 and id_idioma=1;
update mos_nombres_link_idiomas
set nombre_link='Administrador de Usuário'
where cod_link=66 and id_idioma=2;
update mos_link
set dependencia=66
where cod_link=64;
INSERT INTO `mos_link` VALUES ('94', 
'Personas-indexPersonasUsuarioAsignacion-clases.personas.Personas', 
'Asignación de Usuarios', '66', '2', 
'mos_personal.php', '2', null);
INSERT INTO `mos_nombres_link_idiomas` VALUES ('94', '1', 'Asignación de Usuarios');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('94', '2', 'Atribuição de Usuários');

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'analisis_causa', 'Análisis de Causa', '1','Análisis de Causa',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'verifica_eficacia', 'Verificación de Eficacia', '1','Verificación de Eficacia',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'valida_acc_co', 'Validar Acciones correctivas y Correcciones', '1','Validar Acciones correctivas y Correcciones',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'impresion_cc', 'Impresión de Copia Controlada', '1','Impresion de Copia Controlada',1);

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'analisis_causa', 'Análise de causa', '1','Análise de causa',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'verifica_eficacia', 'Verificação de eficácia', '1','Verificação de eficácia',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'valida_acc_co', 'Validar as ações corretivas e correções', '1','Validar as ações corretivas e correções',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'impresion_cc', 'Controlado Copiar Imprimir', '1','Controlado Copiar Imprimir',2);

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'gestion_modulo_documento', 'Gestión en Modulo de Documentos', '1','Gestión en Modulo de Documentos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acc_co', 'Acciones Correctivas y Correcciones', '1','Acciones Correctivas y Correcciones',1);

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'gestion_modulo_documento', 'Módulo de Gestão de Documentos', '1','Módulo de Gestão de Documentos',2);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'acc_co', 'Ações corretivas e correções', '1','Ações corretivas e correções',2);

/*******23/08/2016*********/
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombres_apellidos', 'Nombres y Apellidos', '1','Nombres y Apellidos',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'nombres_apellidos', 'Nomes e Sobrenomes', '1','Nomes e Sobrenomes',2);

ALTER TABLE `mos_personal`
ADD COLUMN `comentario_promocion`  text NULL AFTER `fecha_promocion`;

ALTER TABLE `mos_historico_cargos_promocion`
ADD COLUMN `comentario_promocion`  text NULL AFTER `fecha_registro`;

INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'comentario_promocion', 'Comentario', '1','Comentario',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'comentario_promocion', 'Comentário', '1','Comentário',2);

DROP TRIGGER IF EXISTS `registra_mos_historico_cargos_promocion_ins`;
DELIMITER ;;
CREATE TRIGGER `registra_mos_historico_cargos_promocion_ins` AFTER INSERT ON `mos_personal` FOR EACH ROW BEGIN
/*guarda historico de la promocion de los cargos*/
			IF(NEW.promover_cargo='S') THEN
        INSERT into mos_historico_cargos_promocion (cod_emp, id_organizacion, cod_cargo, id_organizacion_promovida, cod_cargo_promovido, fecha_promocion, comentario_promocion) 
				VALUES (NEW.cod_emp, NEW.id_organizacion, NEW.cod_cargo, NEW.id_organizacion, NEW.cod_cargo, NEW.fecha_ingreso, NEW.comentario_promocion);
			END IF;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `registra_mos_historico_cargos_promocion_upd`;
DELIMITER ;;
CREATE TRIGGER `registra_mos_historico_cargos_promocion_upd` BEFORE UPDATE ON `mos_personal` FOR EACH ROW BEGIN
/*guarda historico de la promocion de los cargos*/
			IF(NEW.promover_cargo='S') THEN
        INSERT into mos_historico_cargos_promocion (cod_emp, id_organizacion, cod_cargo, id_organizacion_promovida, cod_cargo_promovido, fecha_promocion, comentario_promocion) 
				VALUES (NEW.cod_emp, OLD.id_organizacion, OLD.cod_cargo, NEW.id_organizacion, NEW.cod_cargo, NEW.fecha_promocion, NEW.comentario_promocion);
			END IF;
END
;;
DELIMITER ;

/*******25/08/2016*********/
ALTER TABLE `mos_documentos_relacionados` DROP FOREIGN KEY `fk_iddoc_doc_relacionado`;


/***********26/08/2016*******/
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cargo', 'Cargo', '9','Cargo',1);
INSERT INTO mos_nombres_campos (nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cargo', 'Posição', '9','Posição',2);

DROP TABLE IF EXISTS `mos_historico_registro_persona`;
CREATE TABLE `mos_historico_registro_persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRegistro` int(11) DEFAULT NULL,
  `id_unico` int(11) DEFAULT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `cod_cargo` int(11) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `id_organizacion` int(11) DEFAULT NULL,
  `organizacion` varchar(150) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


DROP TRIGGER IF EXISTS `carga_mos_historico_registro_persona_ins`;
DELIMITER ;;
CREATE TRIGGER `carga_mos_historico_registro_persona_ins` AFTER INSERT ON `mos_registro_formulario` FOR EACH ROW BEGIN
/*guarda historico de la promocion de los cargos*/
			IF(NEW.tipo='6') THEN
        INSERT into mos_historico_registro_persona (idRegistro,id_unico,id_persona,cod_cargo,cargo,id_organizacion,organizacion) 
				SELECT NEW.idRegistro,NEW.id_unico, p.cod_emp ,p.cod_cargo ,c.descripcion cargo, id_organizacion, org.title organizacion
				FROM mos_personal p LEFT JOIN mos_cargo c 
				ON c.cod_cargo = p.cod_cargo inner join
				mos_organizacion org on p.id_organizacion = org.id
				WHERE p.cod_emp = NEW.Nombre;			
			END IF;
END;

;;

DROP TRIGGER IF EXISTS `carga_mos_historico_registro_persona_upd`;;
DELIMITER ;;
CREATE TRIGGER `carga_mos_historico_registro_persona_upd` BEFORE UPDATE ON `mos_registro_formulario` FOR EACH ROW BEGIN
			IF(NEW.tipo='6') THEN
				IF EXISTS (select * from mos_historico_registro_persona where id_persona<>NEW.Nombre and idRegistro = NEW.idRegistro and id_unico = NEW.id_unico) THEN					
					update mos_historico_registro_persona 
					set id_persona = NEW.Nombre
					where idRegistro = NEW.idRegistro and  id_unico = NEW.id_unico;

					update mos_historico_registro_persona h INNER JOIN mos_personal p 
								on h.id_persona = p.cod_emp inner JOIN mos_cargo c 
								ON c.cod_cargo = p.cod_cargo inner join mos_organizacion org 
								on p.id_organizacion = org.id
					set h.cod_cargo = p.cod_cargo,
							h.cargo = c.descripcion,
							h.id_organizacion = p.id_organizacion,
							h.organizacion = org.title,
							h.fecha_registro = now()
					where h.idRegistro = NEW.idRegistro and  h.id_unico = NEW.id_unico;
				ELSE
					IF NOT EXISTS (select * from mos_historico_registro_persona where id_persona=NEW.Nombre and idRegistro = NEW.idRegistro and id_unico = NEW.id_unico) THEN	
						INSERT into mos_historico_registro_persona (idRegistro,id_unico,id_persona,cod_cargo,cargo,id_organizacion,organizacion) 
						SELECT NEW.idRegistro,NEW.id_unico, p.cod_emp ,p.cod_cargo ,c.descripcion cargo, id_organizacion, org.title organizacion
						FROM mos_personal p LEFT JOIN mos_cargo c 
						ON c.cod_cargo = p.cod_cargo inner join
						mos_organizacion org on p.id_organizacion = org.id
						WHERE p.cod_emp = NEW.Nombre;
					END IF;
				END IF;
			END IF;
END;

-- Se ejecuta SQL para que cargue la tabla de historico
 update mos_registro_formulario set Nombre  = Nombre where tipo = 6;

/***********30/08/2016*******/
DROP TRIGGER IF EXISTS `carga_mos_historico_registro_persona_del`;
DELIMITER ;
CREATE TRIGGER `carga_mos_historico_registro_persona_del` BEFORE DELETE ON `mos_registro_formulario` FOR EACH ROW BEGIN
			IF(OLD.tipo='6') THEN
					delete from mos_historico_registro_persona 
					where idRegistro = OLD.idRegistro and  id_unico = OLD.id_unico;
			END IF;
END;
/***********2/09/2016*******/
DROP TABLE IF EXISTS `mos_historico_registro_cargo`;
CREATE TABLE `mos_historico_registro_cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRegistro` int(11) DEFAULT NULL,
  `id_unico` int(11) DEFAULT NULL,
  `personas` text,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

DROP TRIGGER IF EXISTS `carga_mos_historico_registro_persona_upd`;
DELIMITER ;;
CREATE TRIGGER `carga_mos_historico_registro_persona_upd` BEFORE UPDATE ON `mos_registro_formulario` FOR EACH ROW BEGIN
			IF(NEW.tipo='6') THEN
				IF EXISTS (select * from mos_historico_registro_persona where id_persona<>NEW.Nombre and idRegistro = NEW.idRegistro and id_unico = NEW.id_unico) THEN					
					update mos_historico_registro_persona 
					set id_persona = NEW.Nombre
					where idRegistro = NEW.idRegistro and  id_unico = NEW.id_unico;

					update mos_historico_registro_persona h INNER JOIN mos_personal p 
								on h.id_persona = p.cod_emp inner JOIN mos_cargo c 
								ON c.cod_cargo = p.cod_cargo inner join mos_organizacion org 
								on p.id_organizacion = org.id
					set h.cod_cargo = p.cod_cargo,
							h.cargo = c.descripcion,
							h.id_organizacion = p.id_organizacion,
							h.organizacion = org.title,
							h.fecha_registro = now()
					where h.idRegistro = NEW.idRegistro and  h.id_unico = NEW.id_unico;
				ELSE
					IF NOT EXISTS (select * from mos_historico_registro_persona where id_persona=NEW.Nombre and idRegistro = NEW.idRegistro and id_unico = NEW.id_unico) THEN	
						INSERT into mos_historico_registro_persona (idRegistro,id_unico,id_persona,cod_cargo,cargo,id_organizacion,organizacion) 
						SELECT NEW.idRegistro,NEW.id_unico, p.cod_emp ,p.cod_cargo ,c.descripcion cargo, id_organizacion, org.title organizacion
						FROM mos_personal p LEFT JOIN mos_cargo c 
						ON c.cod_cargo = p.cod_cargo inner join
						mos_organizacion org on p.id_organizacion = org.id
						WHERE p.cod_emp = NEW.Nombre;
					END IF;
				END IF;
			END IF;
END;
;;

/***********7/09/2016*******/
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'id_organizacion_hist', 'Organizacion Actual', '1', 'Organizacion Actual',1);
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'cargo_act', 'Cargo Actual', '1', 'Cargo Actual',1);

ADD COLUMN `correlativo`  varchar(5) NULL AFTER `id_organizacion`;
/***********8/09/2016*******/
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'id_organizacion_hist', 'Area de Desempeño', '1', 'Area de Desempeño',1);
INSERT INTO `mos_nombres_campos`(nombre_campo,texto,modulo,placeholder,id_idioma) VALUES ( 'id_organizacion_hist', 'área de atuação', '1', 'área de atuação',2);



