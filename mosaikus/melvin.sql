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