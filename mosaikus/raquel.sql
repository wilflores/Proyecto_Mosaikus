/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Raquel
 * Created: 28/06/2016
 */


 /*************** creacion de tablas para modulo COMPETENCIAS***********/

DROP TABLE IF EXISTS `mos_matriz_competencia`;
 CREATE TABLE IF NOT EXISTS `mos_matriz_competencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='matrices de competencia del personal' AUTO_INCREMENT=17 ;



/********** tabla matriz categorias*****/
--
-- Estructura de tabla para la tabla `mos_matriz_categorias`
--
DROP TABLE IF EXISTS `mos_matriz_categorias`;
CREATE TABLE IF NOT EXISTS `mos_matriz_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `id_matriz` int(11) NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `codigo_matriz` (`id_matriz`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='categorias o familia de una matriz de competencia' AUTO_INCREMENT=13 ;



/************ tabla de items de una categoria asociada a la matriz ***/
--
-- Estructura de tabla para la tabla `mos_matriz_items_categorias`
--
DROP TABLE IF EXISTS `mos_matriz_items_categorias`;
CREATE TABLE IF NOT EXISTS `mos_matriz_items_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `orden` int(11) NOT NULL,
  `vigencia` varchar(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`),
  KEY `codigo_categoria` (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=15 ;

/************ tabla relacion entre matriz y areas del arbol organizacional ***/
--
-- Estructura de tabla para la tabla `mos_matriz_organizacion`
--
DROP TABLE IF EXISTS `mos_matriz_organizacion`;
CREATE TABLE IF NOT EXISTS `mos_matriz_organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) NOT NULL,
  `id_matriz` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_area` (`id_area`),
  KEY `codigo_matriz` (`id_matriz`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=46 ;


INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id_area', 'Arbol Organizacional', 28, 'Arbol Organizacional');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('codigo', 'Código', 28, 'Ingrese Código');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('descripcion', 'Descripción', 28, 'Descripcion de la Matriz');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id', 'id', 28, 'id');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('categoria', 'Categoría', 28, 'Categoría');

/********** asignar modulo en el menu*****/
UPDATE `santateresa`.`mos_link` SET `descripcion` = 'MatrizCompetencias-indexMatrizCompetencias-clases.matriz_competencia.MatrizCompetencias' WHERE `mos_link`.`cod_link` =58;

/******* actualizar categoria por familia en la vista matriz competencia***********/
UPDATE `santateresa`.`mos_nombres_campos` SET texto = 'Familias',placeholder='Familias' WHERE id =388;

/***************NUEVAS TABLLAS PARA MODULO DE MATRIZ COMPETENCIAS**/
CREATE TABLE IF NOT EXISTS `mos_requisitos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `vigencia` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` int(11) NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mos_requisitos_familias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='categorias o familia de una matriz de competencia' AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `mos_requisitos_items_familias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `id_familia` int(11) NOT NULL,
  `orden` int(11) NOT NULL,
  `vigencia` varchar(2) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id`),
  KEY `id_familia` (`id_familia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

ALTER TABLE `mos_requisitos_items_familias`
  ADD CONSTRAINT `mos_requisitos_items_familias_ibfk_2` FOREIGN KEY (`id_familia`) REFERENCES `mos_requisitos_familias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mos_requisitos_item` (
  `id` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_requisitos` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_item` (`id_item`),
  KEY `id_requisitos` (`id_requisitos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Filtros para la tabla `mos_requisitos_item`
--
ALTER TABLE `mos_requisitos_item`
  ADD CONSTRAINT `mos_requisitos_item_ibfk_2` FOREIGN KEY (`id_requisitos`) REFERENCES `mos_requisitos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mos_requisitos_item_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `mos_requisitos_items_familias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `mos_requisitos_organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) NOT NULL,
  `id_requisito` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_area` (`id_area`),
  KEY `id_requisito` (`id_requisito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

ALTER TABLE `mos_requisitos_organizacion`
  ADD CONSTRAINT `mos_requisitos_organizacion_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `mos_organizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mos_requisitos_organizacion_ibfk_2` FOREIGN KEY (`id_requisito`) REFERENCES `mos_requisitos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*nUEVOS CAMPOS ****/


INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('descripcion', 'Descripción', 29, 'Ingrese Descripción');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('codigo', 'Código', 29, 'Ingrese Código');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id_items', 'Items', 29, 'Items');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id', 'id', 29, 'id');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('orden', 'Orden', 29, 'Orden');
/*********** Campos usados en el modulo de requisitos************/
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id', 'Id', 30, 'Id');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('nombre', 'Nombre', 30, 'Nombre');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('tipo', 'Tipo', 30, 'Tipo');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('vigencia', 'Aplica Vigencia', 30, 'Aplica Vigencia');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('estatus', 'Estatus', 30, 'Estatus');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('orden', 'Orden', 30, 'Orden');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id_area', 'Arbol Organizacional', 30, 'Arbol Organizacional');

/************ modulo competencias cargos 3-07-16****/
CREATE TABLE IF NOT EXISTS `mos_requisitos_cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cargo` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_requisito_items` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cargo` (`id_cargo`),
  KEY `id_requisito_items` (`id_requisito_items`),
  KEY `id_area` (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mos_requisitos_parametros_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_requisitos_formularios` int(11) NOT NULL,
  `id_parametro_formulario` int(11) NOT NULL,
  `id_parametro_items` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_requisitos_formulario` (`id_requisitos_formularios`),
  KEY `id_parametro_formulario` (`id_parametro_formulario`),
  KEY `id_parametro_items` (`id_parametro_items`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `mos_requisitos_formularios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_requisito_cargo` int(11) NOT NULL,
  `id_documento_form` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_requisito_cargo` (`id_requisito_cargo`),
  KEY `id_documento_form` (`id_documento_form`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

/*** ELIMINAR RELACION CON MOS_ORGANIZACION***/
DROP TABLE IF EXISTS `mos_requisitos_organizacion`;
CREATE TABLE `mos_requisitos_organizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) NOT NULL,
  `id_requisito` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_area` (`id_area`),
  KEY `id_requisito` (`id_requisito`),
  CONSTRAINT `mos_requisitos_organizacion_ibfk_2` FOREIGN KEY (`id_requisito`) REFERENCES `mos_requisitos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



/******** FORANEAS ****/
--
-- Filtros para la tabla `mos_requisitos_cargos`
--
ALTER TABLE `mos_requisitos_cargos`
  ADD CONSTRAINT `mos_requisitos_cargos_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `mos_cargo` (`cod_cargo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mos_requisitos_cargos_ibfk_2` FOREIGN KEY (`id_requisito_items`) REFERENCES `mos_requisitos_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

  --
-- Filtros para la tabla `mos_requisitos_formularios`
--
ALTER TABLE `mos_requisitos_formularios`
  ADD CONSTRAINT `mos_requisitos_formularios_ibfk_1` FOREIGN KEY (`id_requisito_cargo`) REFERENCES `mos_requisitos_cargos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mos_requisitos_formularios_ibfk_2` FOREIGN KEY (`id_documento_form`) REFERENCES `mos_documentos` (`IDDoc`) ON DELETE CASCADE ON UPDATE CASCADE;



--
-- Filtros para la tabla `mos_requisitos_parametros_index`
--
ALTER TABLE `mos_requisitos_parametros_index`
  ADD CONSTRAINT `mos_requisitos_parametros_index_ibfk_1` FOREIGN KEY (`id_requisitos_formularios`) REFERENCES `mos_requisitos_formularios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mos_requisitos_parametros_index_ibfk_2` FOREIGN KEY (`id_parametro_formulario`) REFERENCES `mos_documentos_datos_formulario` (`id_unico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mos_requisitos_parametros_index_ibfk_3` FOREIGN KEY (`id_parametro_items`) REFERENCES `mos_documentos_formulario_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/********* FALTAN INSERT NOMBRE_CAMPOS****/
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id', 'Id', 31, 'Id');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id_cargo', 'Cargo', 31, 'Cargo');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id_requisito_items', 'id_requisito_items', 31, 'id_requisito_items');
INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES('id_area', 'Areas', 31, 'Areas');
/*****  MOS_LINK PARA EL MENU del modulo competencia***********/
UPDATE `santateresa`.`mos_link` SET `descripcion` = 'Familias-indexFamilias-clases.competencia_familias.Familias',
`nombre_link` = 'Competencias Familias' WHERE `mos_link`.`cod_link` =58;

UPDATE `santateresa`.`mos_link` SET `descripcion` = 'Requisitos-indexRequisitos-clases.requisitos.Requisitos' WHERE `mos_link`.`cod_link` =59;

UPDATE `santateresa`.`mos_link` SET `descripcion` = 'RequisitosCargos-indexRequisitosCargos-clases.requisitos_cargos.RequisitosCargos' WHERE `mos_link`.`cod_link` =60;

ALTER TABLE `mos_requisitos_item`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST;
/*************20/08/2016******************/
/**** CAMBIOS EN LA ESTRUCTURA DE TABLAS RELACIONADAS A REQUISITOS CARGOS*********/
DROP TABLE IF EXISTS `mos_requisitos_cargos`;
CREATE TABLE IF NOT EXISTS `mos_requisitos_cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cargo` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `id_requisito` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cargo` (`id_cargo`),
  KEY `id_requisito` (`id_requisito`),
  KEY `id_area` (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;
/***************FORANEA CON MOS_REQUISITOS ******/
ALTER TABLE `mos_requisitos_cargos` ADD FOREIGN KEY (`id_requisito`) 
REFERENCES `mos_requisitos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*********23/08/16 TAblaa para relacion de requisitoscargos con mos_cursos****/
DROP TABLE IF EXISTS `mos_requisitos_cursos`;
CREATE TABLE IF NOT EXISTS `mos_requisitos_cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_requisito_cargo` int(11) NOT NULL,
  `cod_curso` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_requisito_cargo` (`id_requisito_cargo`),
  KEY `cod_curso` (`cod_curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

ALTER TABLE `mos_requisitos_cursos` ADD FOREIGN KEY ( `id_requisito_cargo` ) REFERENCES `santateresa`.`mos_requisitos_cargos` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE `mos_requisitos_cursos` ADD FOREIGN KEY ( `cod_curso` ) REFERENCES `santateresa`.`mos_cursos` (
`cod_curso`
) ON DELETE CASCADE ON UPDATE CASCADE ;
/****** nuevo campo en mos_cursos 24/08/16*/
ALTER TABLE mos_cursos ADD estatus INT NOT NULL DEFAULT '1';
/**** cambios en tabla mos_cursos 26/08/16***/
ALTER TABLE `mos_cursos` CHANGE `estatus` `aplica_vigencia` CHAR( 1 ) NOT NULL DEFAULT 'S';
