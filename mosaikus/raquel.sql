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


