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