-- cambios sql
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('cod_perfil','Codigo Perfil',19,'cod_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('descripcion_perfil','Descripcion Perfil',19,'descripcion_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('nuevo','Acceso a Nuevo',19,'nuevo');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('modificar','Acceso a Modificar',19,'modificar');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('eliminar','Acceso a Eliminar',19,'eliminar');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('recordatorio','Acceso a Recordatorio',19,'recordatorio');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('modificar_terceros','Modificar a Terceros',19,'modificar_terceros');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('visualizar_terceros','Visualizar de Terceros',19,'visualizar_terceros');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('cod_perfil','Codigo Perfil',21,'cod_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('descripcion_perfil','Descripcion Perfil',21,'descripcion_perfil');
insert into mos_nombres_campos(nombre_campo, texto, modulo, placeholder) values ('visualizar_terceros','Visualizar de Terceros',21,'visualizar_terceros');


ALTER TABLE `mos_perfil`
ADD COLUMN `modificar_terceros`  char(2) NULL AFTER `recordatorio`,
ADD COLUMN `visualizar_terceros`  char(2) NULL AFTER `modificar_terceros`;


ALTER TABLE `mos_perfil_portal`
ADD COLUMN `modificar_terceros`  char(2) NULL DEFAULT NULL AFTER `recordatorio`,
ADD COLUMN `visualizar_terceros`  char(2) NULL DEFAULT NULL AFTER `modificar_terceros`;

ALTER TABLE `mos_usuario`
ADD COLUMN `cedula`  varchar(10) NULL AFTER `password_1`;

UPDATE `mos_usuario` SET `cedula`= id_usuario;

-- primera ejecucion

UPDATE `mos_nombres_campos` SET `texto`='Nombres' WHERE (`id`='248');
UPDATE `mos_link` SET `descripcion`='mos_usuario-indexMos_usuario-clases.mos_usuario.mos_usuario' WHERE (`cod_link`='64');

-- segundo corte


-- tercer corte


-- ALTER TABLE `mos_usuario_filial` AUTO_INCREMENT=8:


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


-- cuarto corte 


UPDATE `mos_usuario` SET `id_usuario`='1' WHERE (`id_usuario`='10107598');
UPDATE `mos_usuario` SET `id_usuario`='2' WHERE (`id_usuario`='10712831');
UPDATE `mos_usuario` SET `id_usuario`='3' WHERE (`id_usuario`='13029681');
UPDATE `mos_usuario` SET `id_usuario`='4' WHERE (`id_usuario`='13064872');
UPDATE `mos_usuario` SET `id_usuario`='5' WHERE (`id_usuario`='13315840');

UPDATE `mos_usuario_filial` SET `id_usuario`='1' WHERE (`id_usuario`='10107598') AND (`id_filial`='1');
UPDATE `mos_usuario_filial` SET `id_usuario`='2' WHERE (`id_usuario`='10712831') AND (`id_filial`='1');
UPDATE `mos_usuario_filial` SET `id_usuario`='3' WHERE (`id_usuario`='13029681') AND (`id_filial`='1');
UPDATE `mos_usuario_filial` SET `id_usuario`='4' WHERE (`id_usuario`='13064872') AND (`id_filial`='1');
UPDATE `mos_usuario_filial` SET `id_usuario`='5' WHERE (`id_usuario`='13315840') AND (`id_filial`='1');

UPDATE mos_log SET realizo = 1 WHERE realizo = 10107598;
UPDATE mos_log SET realizo = 2 WHERE realizo = 10107598;

ALTER TABLE `mos_usuario`
MODIFY COLUMN `id_usuario`  int(11) NOT NULL AUTO_INCREMENT FIRST ;
-- revisar numero para el autoincrement
ALTER TABLE `mos_usuario`
AUTO_INCREMENT=7;




-- fin primera actualizacion

-- inicio segunda actualizacion 
--punto 1
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('visualizar_terceros', 'Visualizar de Terceros', '21', 'visualizar_terceros');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('id_usuario', 'id_usuario', '21', 'id_usuario');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('nombres', 'Nombres', '21', 'nombres');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('apellido_paterno', 'Apellido Paterno', '21', 'apellido_paterno');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('apellido_materno', 'Apellido Materno', '21', 'apellido_materno');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('telefono', 'Telefono', '21', 'telefono');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('fecha_expi', 'Fecha de Expiracion', '21', 'fecha_expi');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('vigencia', 'Vigencia', '21', 'vigencia');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('super_usuario', 'Super Usuario', '21', 'super_usuario');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('email', 'email', '21', 'email');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('password_1', 'Password', '21', 'password_1');
    INSERT INTO `mos_nombres_campos` (nombre_campo,texto, modulo, placeholder) VALUES ('cedula', 'Cedula', '21', 'cedula');
-- fin punto 1
-- inicio punto 2
    INSERT INTO `mos_link` VALUES ('80', 'Perfiles-indexPerfiles-clases.perfiles.Perfiles', 'Administrador de Perfiles', '66', '2', 'mos_perfil.php', '74', 'configuracion.png');
    INSERT INTO `mos_link` VALUES ('81', 'Perfiles_portal-indexPerfiles-clases.perfiles_portal.Perfiles_portal', 'Administrador de Perfiles Portal', '66', '2', 'mos_perfil_portal.php', '75', 'configuracion.png');
-- fin punto 2


-- inicio punto 3 revisar al momento de ejecutar para el auto_increment
    ALTER TABLE `mos_usuario_filial`
    ADD COLUMN `id`  int NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (`id`);
    ALTER TABLE `mos_usuario_filial` AUTO_INCREMENT=8;
-- fin punto 3
-- fin segunda actualizacion


-- tercera actualizacion
ALTER TABLE `mos_usuario_estructura`
ADD COLUMN `portal`  char(1) NULL AFTER `cod_perfil`;
-- fin tercera actualizacion

INSERT INTO  `mos_link_por_perfil` (`cod_perfil` ,`cod_link`)VALUES ('1',  '10'), ('1',  '80'), ('1',  '81');

ALTER TABLE `mos_perfil`
MODIFY COLUMN `cod_perfil`  int(3) NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `mos_perfil_portal`
MODIFY COLUMN `cod_perfil`  int(3) NOT NULL AUTO_INCREMENT FIRST ;

