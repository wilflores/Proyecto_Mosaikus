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

INSERT INTO `mos_link_por_perfil` VALUES ('1', '82');

INSERT INTO `mos_nombres_campos` VALUES ('242', 'id', 'id', '23', 'id');
INSERT INTO `mos_nombres_campos` VALUES ('243', 'id_personal_responsable', 'id_personal_responsable', '23', 'id_personal_responsable');
INSERT INTO `mos_nombres_campos` VALUES ('244', 'email_responsable', 'email_responsable', '23', 'email_responsable');
INSERT INTO `mos_nombres_campos` VALUES ('245', 'id_personal_revisa', 'id_personal_revisa', '23', 'id_personal_revisa');
INSERT INTO `mos_nombres_campos` VALUES ('246', 'email_revisa', 'email_revisa', '23', 'email_revisa');
INSERT INTO `mos_nombres_campos` VALUES ('247', 'id_personal_aprueba', 'id_personal_aprueba', '23', 'id_personal_aprueba');
INSERT INTO `mos_nombres_campos` VALUES ('248', 'email_aprueba', 'email_aprueba', '23', 'email_aprueba');
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