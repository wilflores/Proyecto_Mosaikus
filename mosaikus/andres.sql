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
