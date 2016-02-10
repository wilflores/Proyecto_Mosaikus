<?php
/* file:import
*****************************************************************************
Autore  :  Jorge Rodriguez
Version :  0.1
Fecha   :  30-07-2007
Jorge Rodriguez (rodriguez.jorge.mail@gmail.com)
*****************************************************************************
*/

if(!defined('IMPORT')){
	//::::: Variables :::::
	define('IMPORT', '0.1' );
	define('APPLICATION_PATH',dirname(dirname(__FILE__)));
	define('DELIMITER_PACKAGE', '.');
	define('DELIMITER_PATH', '/');
	define('PHP_EXT', 'php');
	define('WILDCART', '*');	
  //::::: Funciones :::::
	/************************************************************************************************/
	/** 
	* Verifica que la clase que se esta importando 
	* exista dentro del archivo 
	*
    *@param $ClassName Nombre de la clase o interfaz a comprobar
	* @return boolean
	*/
	function isClassOrInterFace($ClassName){
		return class_exists($ClassName)  or interface_exists($ClassName);
	}
	/************************************************************************************************/
	/** 
	* Incluye el archivo que recibe por parametro.
	* el archivo es incluido con include_once
	*
    * @param FileToInclude Archivo que se va a incluir
	* @return null
	*/
	function include_file($FileToInclude){
		if(!is_file(APPLICATION_PATH.DELIMITER_PATH.$FileToInclude)){
			trigger_error("$FileToInclude no existe, por favor verifica");
			return null;
		}
		include_once(APPLICATION_PATH.DELIMITER_PATH.$FileToInclude);
	}
	/************************************************************************************************/
	/** 
	* Extrae el "nombre" del archivo sin la extension 
	* del parametro que recibe
	*
    * @param $FileName Direccion del archivo 
	* @return cadena: nombre del archivo
	*/
	function extractClassName($FileName){
		return substr(basename($FileName),0,-strlen(PHP_EXT)-1);
	}
  /************************************************************************************************/
  /** 
	* Verifica si el parametro puede es un archivo 
	* ademas de que posea la extension de PHP_EXT
	*
    * @param $FileName Direccion del archivo 
	* @return booleano
	*/
	function isValid($FileName){
		return (is_file($FileName)) && (strrpos($FileName, "." . PHP_EXT)!==false);
	}  
	/************************************************************************************************/
	/**
	* Lee las clases contenidas en un paquete.
	* 
    * @param $PackageName Direccion del paquete
	* @return arreglo con el contenido del paquete
	*/
	function readPackage($PackageName){
		$PackageName	=	substr($PackageName,0,-1*strlen(DELIMITER_PACKAGE . WILDCART));
		$PackageName	=	str_replace(DELIMITER_PACKAGE, DELIMITER_PATH, $PackageName);
		$PackageName	=	APPLICATION_PATH . DELIMITER_PATH . $PackageName;
    if (!is_dir($PackageName)){
			return false;
		}
		$cDir = dir ($PackageName);
		$package = array();
    while (false !== ($file = $cDir->read())){
      if($file != '.' && $file != '..'){
			  $file	= $PackageName . DELIMITER_PATH . $file;
				if(isValid($file)){
					$class	=	extractClassName($file);
					array_push($package, $class);
				}
      }
		}
		$cDir->close();
		return $package;
	}
	/************************************************************************************************/
	/**
	* Obtiene el nombre de la clase que se
	* desea importar
	*
    * @param $ClassToImport Nombre de la clase a importar
	* @return boolean
	*/
  function getClassName($ClassToImport){
		$posicion = strrpos($ClassToImport,DELIMITER_PACKAGE);
    switch($posicion === 0 ? 0 :($posicion > 0 ? 1 : -1)){
      case 0 :
        $className = false;
      break;
      case 1 :
        $className = substr($ClassToImport, $posicion + strlen(DELIMITER_PACKAGE));
      break;
      case -1:
        $className = $ClassToImport;
      break;
    }
		return $className;
	}
	/************************************************************************************************/
	/**
	* Realiza la importacion de la o las clases
	* que se deseen
	*
    * @param $ClassToImport Nombre de la clase a importar
	* @return null
	*/
  function import($ClassToImport){
		$className	=	getClassName($ClassToImport);
		if (!$className){
			trigger_error("$ClassToImport Clase para importar incorrecta");
			return null;
		}
		/**********************************************************************************************/
		if($className == WILDCART){
			$package		=	readPackage($ClassToImport);
			if(!empty($package)){
				foreach ($package as $class){
					$class = str_replace(WILDCART,$class,$ClassToImport);
					import($class);
				}
			}
			else{
				trigger_error("$ClassToImport / El Paquete $ClassToImport esta vacio");
			}
			return null;
		}
		/**********************************************************************************************/
		if(!isClassOrInterface($className)){
			$ClassToImport 		= 	str_replace(DELIMITER_PACKAGE, DELIMITER_PATH, $ClassToImport);
			include_file($ClassToImport.".".PHP_EXT);
			if(!isClassOrInterface($className)){
        trigger_error("La clase o interfaz $className no existe en $ClassToImport");
      }
      return null;
		}
    return null;
	}
}
?>
