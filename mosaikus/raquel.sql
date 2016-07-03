/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Raquel
 * Created: 28/06/2016
 */
UPDATE `santateresa`.`mos_link` SET `descripcion` = 'MatrizCompetencias-indexMatrizCompetencias-clases.matriz_competencia.MatrizCompetencias' WHERE `mos_link`.`cod_link` =58;

DELETE FROM `santateresa`.`mos_nombres_campos` WHERE `mos_nombres_campos`.`id` =378;

DELETE FROM `santateresa`.`mos_nombres_campos` WHERE `mos_nombres_campos`.`id` =379;

DELETE FROM `santateresa`.`mos_nombres_campos` WHERE `mos_nombres_campos`.`id` =380;

DELETE FROM `santateresa`.`mos_nombres_campos` WHERE `mos_nombres_campos`.`id` =381;

UPDATE `santateresa`.`mos_nombres_campos` SET `texto` = 'Código',
`placeholder` = 'Ingrese Código' WHERE `mos_nombres_campos`.`id` =382;

UPDATE `santateresa`.`mos_nombres_campos` SET `texto` = 'Descripción',
`placeholder` = 'Descripcion de la Matriz' WHERE `mos_nombres_campos`.`id` =383;