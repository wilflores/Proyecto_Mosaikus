/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Author:  Andres Zambrano
 * Created: Feb 26, 2016
 */

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
/*cambio del domingo 28-02*/
/****************************************/