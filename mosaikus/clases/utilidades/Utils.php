<?php
/*file:Utils
*****************************************************************************
Autor   :  Jorge Rodriguez
Version :  0.1
Fecha   :  31-07-2007
Jorge Rodriguez (rodriguez.jorge.mail@gmail.com)
*****************************************************************************
Modificaciones : 
# 01-08-2007 : Jorge Rodriguez (rodriguez.jorge.mail@gmail.com)
Limpio la palabra antes de pasarla por str_replace en el metodo acentos.
# 14-09-2007 : Jorge Rodriguez (rodriguez.jorge.mail@gmail.com)
Metodo acentos_array()
*/
abstract class Utils{
	//:::::Metodos :::::
  /************************************************************************************************/
	/**
	* Obtiene la direccion ip de la maquina 
	* del cliente
  *
	* @return string con la direccion ip
	*/  
	public static function getIP(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$ip	=	$_SERVER['HTTP_X_FORWARDED_FOR']; 
    }
    elseif (isset($_SERVER['HTTP_VIA'])){ 
      $ip	=	$_SERVER['HTTP_VIA']; 
    } 
    elseif(isset($_SERVER['REMOTE_ADDR'])){ 
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    else{
      $ip = "Desconocido"; 
    }
    return $ip;
	}
	/************************************************************************************************/
	/**
	* Verifica que un email este bien escrito
  * 
  * @param $Email cadena con la direccion de correo a verificar
	* @return boolean
	*/  
  public static function esEmail($Email = ""){
		return (preg_match("/^[\w.%-]+@[\w.%-]+\.[[:alpha:]]{2,6}$/",$Email)==1);
	}
	/************************************************************************************************/
	/**
	* Limpia la cadena pasada por  parametro
  * elimina los espacios al principio y final de la cadena
  * los espacios multiples entre letras los sustituye por espacios simples
  *
  * @param $cadena palabra que sera limpiada
	* @return string
	*/  
	public static function limpiarPalabra($cadena = ""){
		$regex = array( 
              '/\s+/'   , // Cualquier espacio entre letras
       				'/^\s+/'  , // Cualquier espacio al principio 
       				'/\s+$/s' );// Cualquier espacio al final
		$replacement = array (
                " " , // sustituye a un espacio
                ""  , // elimina el espacio
							  ""  );// elimina el espacio
		return preg_replace($regex,$replacement,$cadena);
  }
  /************************************************************************************************/
	/**
	* Sustituye los caracteres especiales por codigos
  * que pueden ser interpretados por el navegador
  *
  * @param $str cadena original con los caracteres especiales
	* @return string cadena ya con los caracteres cambiados
	*/    
  public static function acentos($str){
    $original = array('€','@','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�');
    $cambiar  = array('&#8364;','&#64;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#170;','&#174;','&#191;','&#192;','&#193;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;');
    return str_replace($original,$cambiar,self::limpiarPalabra($str));//return $str;
  }
  /*****************************************************************************************'&#160;',**' ',*****/
	/**
	* Sustituye los caracteres especiales por codigos
  * que pueden ser interpretados por el navegador
  *
  * @param $array vector con las cadenas originales con los caracteres especiales
	* @return vector con las cadenas ya con los caracteres cambiados
	*/    
  public static function acentos_array($array){
    $original = array('@','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�');
    $cambiar  = array('&#64;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#170;','&#174;','&#191;','&#192;','&#193;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;');
    if(is_array($array))    
      foreach($array as $key => $valor)
        $array[$key] = str_replace($original,$cambiar,self::limpiarPalabra($valor));
    return $array;
  }

/**********************************************************************************************/
	/** en el grafico tipo 2 modoleyenda 1
	* Sustituye los caracteres con abreviasiones por sus significado
 que pueden ser interpretados por el navegador
  *
  * @param $array vector con las cadenas originales con los caracteres especiales
	* @return vector con las cadenas ya con los caracteres cambiados
	*/     // hay q colocar los parametros a sustituir en el mismo orden en de los datos q van a sustituir
  public static function leyenda_array($array){
    $original = array('P','ONC','G','AI','D','A','T','PU','OC'); //array('P','ONC','G','AI','D','AO','A','T','PU','OC')
    $cambiar  = array('Profesional','Obrero no capa','Gerente','A I','Director','Administrativo','Tecnico','Pasante Universitario','Obrero Capacitado');
       //('Profesional','Obrero no capa','Gerente','A I','Director','A O','Administrativo','Tecnico','Pasante Universitario','Obrero Capacitado');
    if(is_array($array))    
      foreach($array as $key => $valor)
        $array[$key] = $cambiar[$key];//str_replace($original,$cambiar,self::limpiarPalabra($valor));
    return $array;
  }

  /*******es para cambiar la leyenda de este grafico*****/
 public static function leyenda_array_graficatipo4($array){
    $original = array('N','I'); //representa el orden en el q estan los datos
    $cambiar  = array('Nacional','Internacional');// en este orden estan en el vector original N, I
   if(is_array($array))    
      foreach($array as $key => $valor)
        $array[$key] = $cambiar[$key];//str_replace($original,$cambiar,self::limpiarPalabra($valor));
    return $array;
  }

 /*******es para cambiar la leyenda de este grafico planificado*****/
 public static function leyenda_array_graficatipo61($array){
    $original = array('n','V','N','','F');
    $cambiar  = array('otro','Planificado','otro','otro','No Planificado');
   if(is_array($array)){                    //las preguntas es por q hay mas valores en el array original q las q se quieren mostrar
      foreach($array as $key => $valor){
        //if($array[$key]=='V')
           //$array[$key] = $cambiar[$key];
       // if($array[$key]=='F')
           $array[$key] = $cambiar[$key];
      }
   }
   return $array;
  }

  /*******es para cambiar la leyenda de este grafico preventivo*****/
 public static function leyenda_array_graficatipo62($array){
    $original = array('COR','','N','PRE','null');
    $cambiar  = array('Correctivo','','otro','Preventivo','otro');
   if(is_array($array)){                    //las preguntas es por q hay mas valores en el array original q las q se quieren mostrar
      foreach($array as $key => $valor){
        if($array[$key]=='COR')
           $array[$key] = $cambiar[$key];
        if($array[$key]=='PRE')
           $array[$key] = $cambiar[$key];
      }
   }
   return $array;
  }

  /*******es para cambiar la leyenda de este grafico certificado*****/
 public static function leyenda_array_graficatipo63($array){
    $original = array('n','V','N','','F');
    $cambiar  = array('otro','Certificado','otro','otro','No Certificado');
   if(is_array($array)){                    //las preguntas es por q hay mas valores en el array original q las q se quieren mostrar
      foreach($array as $key => $valor){
        if($array[$key]=='V')
           $array[$key] = $cambiar[$key];
        if($array[$key]=='F')
           $array[$key] = $cambiar[$key];
      }
   }
   return $array;
  }
 
  /*******es para cambiar la leyenda de este grafico certificado*****/
 public static function leyenda_array_graficatipo64($array){
    $original = array('n','N','P','T','','M');
    $cambiar  = array('otro','otro','Propia','Terceros','otro','Mixto');
   if(is_array($array)){                    //las preguntas es por q hay mas valores en el array original q las q se quieren mostrar
      foreach($array as $key => $valor){
          if($array[$key]=='P')
            $array[$key] = $cambiar[$key];
          if($array[$key]=='T')
            $array[$key] = $cambiar[$key];
          if($array[$key]=='M')
            $array[$key] = $cambiar[$key];
      }
   }
   return $array;
  }


/*****es para la leyenda de tipo de emleado fijo o contratado**************/
 public static function leyenda_array_graficatipo31($array){
    $original = array('C','F');
    $cambiar  = array('Contratado','Fijo');
   if(is_array($array)){                    //las preguntas es por q hay mas valores en el array original q las q se quieren mostrar
      foreach($array as $key => $valor){
          if($array[$key]=='C')
            $array[$key] = $cambiar[$key];
          if($array[$key]=='F')
            $array[$key] = $cambiar[$key];         
      }
   }
   return $array;
  }


/*****es para la leyenda empresas en expancion**************/

 public static function leyenda_array_graficatipo8($array){
    $original = array('','F','V');
    $cambiar  = array('Otros','Sin Expancion','En Expancion');
   if(is_array($array)){                    //las preguntas es por q hay mas valores en el array original q las q se quieren mostrar
      foreach($array as $key => $valor){
         // if($array[$key]=='C')
           // $array[$key] = $cambiar[$key];
         // if($array[$key]=='F')
            $array[$key] = $cambiar[$key];         
      }
   }
   return $array;
  }


 /*****es para la leyenda empresas en expancion**************/

 public static function leyenda_array_graficatipo121($array){
    $original = array('F','V');
    $cambiar  = array('No Automatizado','Automatizado');
   if(is_array($array)){                    //las preguntas es por q hay mas valores en el array original q las q se quieren mostrar
      foreach($array as $key => $valor){
         // if($array[$key]=='C')
           // $array[$key] = $cambiar[$key];
         // if($array[$key]=='F')
            $array[$key] = $cambiar[$key];         
      }
   }
   return $array;
  }


}
?>
