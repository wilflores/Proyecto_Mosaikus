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
INSERT INTO `mos_nombres_link_idiomas` VALUES ('8', '1', 'Acciones Correctivas');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('8', '2', 'ocorrências');
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
INSERT INTO `mos_nombres_link_idiomas` VALUES ('50', '1', 'Administrador de  AC');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('50', '2', 'gestor de ocorrências com plano de acção');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('51', '1', 'Configuración de AC');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('51', '2', 'configuração');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('52', '1', 'Reportes de AC');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('52', '2', 'resumos ocorrências com plano de acção');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('55', '1', 'Incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('55', '2', 'incidentes');
INSERT INTO `mos_nombres_link_idiomas` VALUES ('56', '1', 'reportes');
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
INSERT INTO `mos_nombres_link_idiomas` VALUES ('77', '1', 'Administrar Correcciones');
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
INSERT INTO `mos_nombres_link_idiomas` VALUES ('93', '2', 'Seguimento de Ação Corretiva');

