<?php
if(!defined('CONFIGURACION')){
  //::::: Datos Varios :::::
  define('HOME',dirname(dirname(__FILE__)));
  define('RUTA',"mosaikus/mosaikus/");
  if (isset($_SERVER['HTTPS'])) {
    define('APPLICATION_HOST',"https://".$_SERVER['SERVER_NAME']);
    define('APPLICATION_ADDR',"https://localhost/".RUTA);
    define('APPLICATION_ROOT',"https://".$_SERVER['SERVER_NAME'].'/'.RUTA);
  }
  else
  {
    define('APPLICATION_HOST',"http://".$_SERVER['SERVER_NAME']);
    define('APPLICATION_ADDR',"http://localhost/".RUTA);
    define('APPLICATION_ROOT',APPLICATION_HOST.'/'.RUTA);
  }
  //define('APPLICATION_HOST',"http://localhost");  
  
  define('APPLICATION_DOWNLOADS',HOME . '/'.'downloads/');
  define('APPLICATION_DOCUMENTOS',HOME . '/'.'downloads/documentos/');
  define('PATH_TO_TEMPLATES',HOME.'/diseno/templates/');
  define('PATH_TO_JS',APPLICATION_HOST.'/'.RUTA.'dist/js/');
  define('PATH_TO_JS_V',APPLICATION_HOST.'/'.RUTA.'vendor/');
  
  define('PATH_TO_CSS',APPLICATION_HOST.'/'.RUTA.'dist/css/');
  //$dbl = new ut_Database();
  //$dbl->conectar();
  //$dbl->query("select
   //                     nombre,
    //                    valor
   //             from
  //                      tb_configuraciones
  //              where nombre IN ('EMPRESA', 'FIRMA', 'CARGO') ORDER BY nombre");
  //$data = $dbl->array_desde_result($dbl->result);
  //define('APPLICATION_NOMBRE_EMPRESA',$data[1][valor]);
  //define('APPLICATION_NOMBRE_FIRMA_MSJ',$data[2][valor]);
  //define('APPLICATION_CARGO_FIRMA_MSJ',$data[0][valor]);

  define('PATH_TO_IMG',APPLICATION_HOST.'/'.RUTA.'diseno/images/');  
  
  define('EMAIL_FROM','Plataforma de Gestion SMS Chile');
  define('FIRMA_MSJ_CORREO','Plataforma de Gestión SMS Chile');
  define('PAIS','Chile');
  define('APPLICATION_NOMBRE_EMPRESA','Masisa Andina');
  define('CORREO_SALIENTE','saludmedioambienteseguridad.chile@masisa.com');
  define('CONTRASENA_CORREO_SALIENTE','masisa.2014');
  
  
  
  define('DIAS_HABILES_AVISO',3);
  define('DIAS_HABILES_ALERTA',10);
  define('NOMBRE_APLICATIVO','Sistema en Excelencia SMS');
  define('NOTACION_PLANTA','Planta');
  define('CLIENTID','415793471408-qg0ehdufh3fg6616b5u0ka6motcje4d0.apps.googleusercontent.com');
  define('CLIENTSECRET','X50ApYkdaig2_W7kQcexnX-G');
  define('URLCALLBACK', APPLICATION_HOST.'/'.RUTA.'login.php');
  //define('PATH_TO_MSGS',HOME.'/libreria/msgs/espanol/');  
  //define('PATH_GRA',HOME.'/libreria/graficos/grafi/datos/');  
  
  //::::: condiguracion de tcpdf :::::
  define('K_TCPDF_EXTERNAL_CONFIG','SI');
	define('K_PATH_MAIN',HOME.'/libreria/reportes/tcpdf/');
	define('K_PATH_URL', APPLICATION_HOST.'/libreria/reportes/tcpdf/');
	define('FPDF_FONTPATH', K_PATH_MAIN."fonts/");
	define('K_PATH_CACHE', K_PATH_MAIN."cache/");
	define('K_PATH_URL_CACHE', K_PATH_URL."cache/");
	define("K_PATH_IMAGES", HOME."/diseno/imgs/reportes/");
        define("K_PATH_IMAGES_GRAFICOS", HOME."/diseno/imgs/reportes/graficos/");
	define("K_BLANK_IMAGE", K_PATH_IMAGES."_blank.png");
	define("PDF_PAGE_FORMAT", "letter");
	define("PDF_PAGE_ORIENTATION", "L");
	define("PDF_CREATOR", "TCPDF");
	define("PDF_AUTHOR", "autor");
	define("PDF_HEADER_STRING", "first row\nsecond row\nthird row");
	define("PDF_UNIT", "mm");
	define("PDF_MARGIN_HEADER", 10);
	define("PDF_MARGIN_FOOTER", 10);
	define("PDF_MARGIN_TOP", 23);
	define("PDF_MARGIN_BOTTOM", 33);
	define("PDF_MARGIN_LEFT", 10);
	define("PDF_MARGIN_RIGHT", 15);
	define("PDF_FONT_NAME_MAIN", "vera"); //vera
	define("PDF_FONT_SIZE_MAIN", 10);
	define("PDF_FONT_NAME_DATA", "vera"); //verase
	define("PDF_FONT_SIZE_DATA", 8);
	define("PDF_IMAGE_SCALE_RATIO", 4);
	define("HEAD_MAGNIFICATION", 1.1);
	define("K_CELL_HEIGHT_RATIO", 1.25);
	define("K_TITLE_MAGNIFICATION", 1.3);
	define("K_SMALL_RATIO", 2/3);
	define ("PDF_HEADER_LOGO","logo.png");
	define ("PDF_HEADER_LOGO_WIDTH", 20);
  
  //::::: Datos conexion de la base de datos :::::
  global $BD;
  global $HOST;
  global $USER;
  global $PASSWORD;
  global $PORT;  
  global $SESSION;
  global $PREFIJO_BD;
  //:::: Conexion SIR  ::::
  $GLOBALS[SESSION] = "mosaikus";
  $GLOBALS[PREFIJO_BD] = "tbl_";
  $GLOBALS[FICHA_OBSERVACION] = "Se cumple con todas las anteriores";
  $CONEXION = 0;
  $BD[] = 'iglesiab';
  $HOST[] = 'localhost';
  $USER[] = 'iglesiab';
  $PASSWORD[] = '153616699';
  $PORT[] = '5432';
  //::::: Indica que ya se cargo la condiguracion :::::
  define('CONFIGURACION','Cargada');
  date_default_timezone_set('America/Santiago');
  //::::: Define si se muestran o no los errores Valores : E_ALL o 0  o E_ALL ^ E_NOTICE :::::
  //error_reporting(E_ALL ^ E_NOTICE);
  //error_reporting(0);
}
?>
