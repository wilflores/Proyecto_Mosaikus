<?php
    set_time_limit (120);
    //include_once(dirname(__FILE__). "/bd/SQLServer.php");
    //include_once("bd/SQLServer.php");
    //echo dirname(__FILE__). "/bd/SQLServer.php";
    include_once("bd/Mysql.php");
    include_once("utilidades/validacion.php");
    include_once("utilidades/formvalidator.php");
    include_once("../msks/includes/EnDecryptText.php");
    include_once("utilidades/utilidades.php");
    include_once("usuarios/neg_usuario.php");
    include_once("interfaz/neg_menu.php");    
    include_once("usuarios/neg_rol.php");
    //include_once("neg_config.php");
    include_once("interfaz/Datagrid.php");
    include_once("interfaz/Pagina.php");
    include_once("utilidades/Utils.php");
    include_once("interfaz/Template.php");
    include_once("xajax/xajax.php");
    //include_once("mensajes/Mensajes.php");
    //include_once("Clase_phpmailer/class.phpmailer.php");
    include_once('PHPMailer_5.2.4/class.phpmailer.php');
    include_once("utilidades/ut_Tool.php");
    include_once("utilidades/descargaArchivos.php");
    
    //include_once("utilidades/analyticstracking.php");



// include de base de datos
 //include("ut_Database.php");
// include("ut_Calendario.php");
 //include("ut_Grid.php");
 //include("ut_Tool.php");
 //include("ut_SendMail.php");
 //include_once("validacion.php");
 // include de class de negocios

 //include_once("neg_usuario.php");
 //include_once("neg_rol.php");
// include_once("neg_menu.php");
// include_once("rgb.inc.php");
// include_once("neg_clientes.php");
// include_once("neg_poliza_seguro.php");
// include_once("neg_propietario.php");
// include_once("neg_vehiculo.php");
// include_once("neg_solicitud.php");
//include_once("neg_conductor.php");
//include_once("neg_escolta.php");
//include_once ("Datagrid.php");
?>