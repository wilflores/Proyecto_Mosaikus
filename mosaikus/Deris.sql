ALTER TABLE `mos_perfil`
ADD COLUMN `modificar_terceros`  char(2) NULL AFTER `recordatorio`,
ADD COLUMN `visualizar_terceros`  char(2) NULL AFTER `modificar_terceros`;

insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('cod_perfil','Codigo Perfil',19,'cod_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('descripcion_perfil','Descripcion Perfil',19,'descripcion_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('nuevo','Acceso a Nuevo',19,'nuevo');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('modificar','Acceso a Modificar',19,'modificar');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('eliminar','Acceso a Eliminar',19,'eliminar');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('recordatorio','Acceso a Recordatorio',19,'recordatorio');

insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('modificar_terceros','Modificar de Terceros',19,'modificar_terceros');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('visualizar_terceros','Visualizar de Terceros',19,'visualizar_terceros');

ALTER TABLE `mos_perfil_portal`
ADD COLUMN `modificar_terceros`  char(2) NULL DEFAULT NULL AFTER `recordatorio`,
ADD COLUMN `visualizar_terceros`  char(2) NULL DEFAULT NULL AFTER `modificar_terceros`;


insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('cod_perfil','Codigo Perfil',21,'cod_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('descripcion_perfil','Descripcion Perfil',21,'descripcion_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('visualizar_terceros','Visualizar de Terceros',21,'visualizar_terceros');


ALTER TABLE `mos_usuario`
ADD COLUMN `cedula`  varchar(10) NULL AFTER `password_1`;

UPDATE `mos_usuario` SET `cedula`= `id_usuario;

ALTER TABLE `mos_usuario`
MODIFY COLUMN `id_usuario`  int(11) NOT NULL AUTO_INCREMENT FIRST ;

UPDATE `mos_usuario` SET `id_usuario`='1' WHERE (`id_usuario`='10107598')
UPDATE `mos_usuario` SET `id_usuario`='2' WHERE (`id_usuario`='10712831')
UPDATE `mos_usuario` SET `id_usuario`='3' WHERE (`id_usuario`='13029681')
UPDATE `mos_usuario` SET `id_usuario`='4' WHERE (`id_usuario`='13064872')
UPDATE `mos_usuario` SET `id_usuario`='5' WHERE (`id_usuario`='13315840')


UPDATE `mos_nombres_campos` SET `texto`='Nombres' WHERE (`id`='248')
UPDATE `mos_link` SET `descripcion`='mos_usuario-indexMos_usuario-clases.mos_usuario.mos_usuario' WHERE (`cod_link`='64')


UPDATE `mos_usuario_filial` SET `id_usuario`='1' WHERE (`id_usuario`='10107598') AND (`id_filial`='1')
UPDATE `mos_usuario_filial` SET `id_usuario`='2' WHERE (`id_usuario`='10712831') AND (`id_filial`='1')
UPDATE `mos_usuario_filial` SET `id_usuario`='3' WHERE (`id_usuario`='13029681') AND (`id_filial`='1')
UPDATE `mos_usuario_filial` SET `id_usuario`='4' WHERE (`id_usuario`='13064872') AND (`id_filial`='1')
UPDATE `mos_usuario_filial` SET `id_usuario`='5' WHERE (`id_usuario`='13315840') AND (`id_filial`='1')


ALTER TABLE `mos_usuario_filial` AUTO_INCREMENT=8


ALTER TABLE `mos_usuario_filial`
DROP PRIMARY KEY,
DROP INDEX `ind01`;


DROP TABLE IF EXISTS `mos_usuario_estructura`;
CREATE TABLE `mos_usuario_estructura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_filial` int(11) NOT NULL,
  `id_estructura` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cod_perfil` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

