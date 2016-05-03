<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>MOSAIKUS - Administrador de Sistemas de Gesti&oacute;n</title>
  <link href="../msks/images2/icono.ico" rel="shortcut icon">
  <!-- Bootstrap Core CSS -->
  <link
    href='https://fonts.googleapis.com/css?family=Roboto:400,700,300,700italic,400italic,300italic'
    rel='stylesheet' type='text/css'>
  <link href="vendor/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <!-- jstree -->

  
  <!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        {CSS}
        {JAVASCRIPT}
</head>
<script type="text/javascript">
//setTimeout(function(){ MostrarNotificacionesEmergente(); }, 5000);
setInterval("MostrarNotificacionesEmergente()",10000)
MostrarNotificacionesEmergente();
document.addEventListener('DOMContentLoaded', function () 
{
    
if (Notification.permission !== "granted")
{
Notification.requestPermission();
}
});

function notifyBrowser(title,desc,url) 
{
    if (!Notification) {
        console.log('Desktop notifications not available in your browser..'); 
        return;
    }
    if (Notification.permission !== "granted")
    {
        Notification.requestPermission();
    }
    else {
        var notification = new Notification(title, {
        //icon:'diseno/images/logo_empresa/{LOGO_EMPRESA}_logo_empresa.png',
        icon:'dist/images/logo.png',
        body: desc,
    });

    // Remove the notification from Notification Center when clicked.
    notification.onclick = function () {
    //window.open(url); 
    if(url=='mostrarventana'){
        VerNotificacionesMenu();
        $('#messages').collapse("show");
    }
    else
        if(url!='') eval(url);
    };

    // Callback function when the notification is closed.
    notification.onclose = function () {
    console.log('Notification closed');
    };

    }
}
</script>

<body>
<!-- #page-wrapper -->
<div id="page-wrapper">
  <!-- #page -->
  <div id="page" class="page">


    <!-- #header -->
    <div class=" header-wrap">
      <header class=" header">

        <div class="client-info">
            <a class="logo" href="#">
                <img src="dist/images/logo.png">
            </a>
        </div>
        <!-- .branding Logotipo -->
        <div class="branding">
          
            <img src="diseno/images/logo_empresa/{LOGO_EMPRESA}_logo_empresa.png">
          
        </div>
        <!-- .branding Fin Logotipo -->
        <!-- .status-bar -->
        <div class="status-bar">
            <div class="user-menu" style="margin-top: -3px;">
              <span>{NOMBREMPRESA}</span><br/>
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="glyphicon glyphicon-user"></i>
                {USUARIO}<span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="javascript:contrasena()">Cambiar contraseña</a></li>
                <li><a href="login.php?cerrar=true">Salir</a></li>
              </ul>
            </div>
            <div class="mode-toggle"  style="padding-right: 10px;">
                
                {MODO_PORTAL}
                <a title="Modo Especialista" class="active">
                    <i class="icon-app-mode icon-app-mode-specialist"></i>

                </a>
            </div>
            <div class="notifications">
              <a id="notificaciones" onclick="VerNotificacionesMenu();" class="noti-icon" data-toggle="collapse" href="#messages">
                <span id="cantidad_notificaciones"></span>
              </a>
                <div  id="messages" class="popover bottom">
                  <div class="arrow"></div>
                  <div class="popover-content" id="div-notificaciones" style="overflow: auto;" >
                    <ul class="" id="popover-notificaciones">
                        <!--AQUI SE CARGAN LAS NOTIFICACIONES-->
                    </ul>
                  </div>
                </div>


            </div>
            

          </div>
        <!-- Fin  .status-bar -->
      </header>
    </div>
    <!-- Fin #header -->


    <!-- .content-wrap  -->
    <div class=" content-wrap">
      <!-- #nav  Navegaci�n  -->
      <nav id="nav" class="col">
        <a class="hide-menu">
            <i class="glyphicon glyphicon-minus"></i>
        </a>
        <!-- Menu -->

        <div id="menu-wrap">
          <ul id="menu" class="sidebar-nav nav-pills nav-stacked">
               {MENU_PRINCIPAL} 



          </ul>
        </div>
        <!-- Menu -->


      </nav>
      <!-- #nav Nevegaci�n -->
      <div id="mensaje_error"  style="display:none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Warning!</strong> Better check yourself, you're not looking too good.
      </div>
      <div id="mensaje_exito"  style="display:none;">
          <div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Warning!</strong> Better check yourself, you're not looking too good.</div>
      </div>
      <div class="alert alert-info alert-dismissible" role="alert" style="display:none;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="alert alert-danger alert-dismissible" role="alert" style="display:none;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <!-- .panels -->
      <div class="panels">

<!--   -->
        <div class="container-fluid" >
          <div class="row" id="contenido" >

            <!-- .panel Panel Busqueda  -->
            <div id="search"
                 class=" panel-hidden  panel-container col-md-offset-1  col-xs-4 ">
              <div class="content-panel panel">
                <div class="content">

                  <?php include '..\templates\components\search.php' ?>

                </div>
              </div>


            </div>
            <!-- Fin .panel Panel Busqueda -->

            <!-- .panel Panel Contenido -->
            <div id="main-content" class=" col-md-offset-1 panel-container col-xs-23 ">

              <a class="search-show" >
                <i class="glyphicon glyphicon-search" data-toggle="collapse"  href="#search"></i>
              </a>

              <div class="content-panel panel">
                <div class="content">

                  <?php include '..\templates\base\table.php' ?>

                </div>
              </div>

            </div>
            <!-- Fin .panel Panel Contenido -->
            <!-- .panel Panel Detalle -->
            <div id="detail-content" class=" panel-hidden panel-container col-xs-7 ">

              <div class="content-panel panel">
                <div class="content">
                  <a class="close-detail"   href="#detail-content">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>
                  <?php include '..\templates\base\detail.php' ?>

                </div>
              </div>
            </div>
            <!-- Fin .panel Panel Detalle -->



          </div>
        </div>
          
        <div class="container-fluid" style="margin-left: 50px;display: none;">
          <div class="row" id="contenido-form" style="display: none;">           

            <!-- .panel Panel Contenido -->
            <div class="panel-container col-xs-23 ">
              <div class="content-panel panel">
                <div class="content">
                  <?php include '../templates/base/panel-heading.php'; ?>
                  <div class="row">
                    <div class="col-xs-12">
                      <?php include '..\templates\base\form.php' ?>
                    </div>
                    <div class="col-xs-offset-1 col-xs-11">
                      <?php include '..\templates\base\buttons.php' ?>
                      <?php include '..\templates\base\pager.php' ?>
                    </div>


                  </div>
                  <div class="row">
                    <div class="col-xs-24">
                    <?php include '..\templates\components\tabs.php' ?>
                    </div>
                  </div>

                </div>
              </div>

            </div>
            <!-- Fin .panel Panel Contenido -->




          </div>
        </div>
         

 <!-- Nuevo prueba -->
        <div class="container-fluid" style="display: none;">
          <div class="row" id="contenido-aux" style="display: none;">

            <!-- .panel Panel Busqueda  -->
            <div id="search-aux"
                 class=" panel-hidden  panel-container col-md-offset-1  col-xs-4 ">
              <div class="content-panel panel">
                <div class="content">

                  <?php include '..\templates\components\search.php' ?>

                </div>
              </div>


            </div>
            <!-- Fin .panel Panel Busqueda -->

            <!-- .panel Panel Contenido -->
            <div id="main-content-aux" class=" col-md-offset-1 panel-container col-xs-23 ">

              <a class="search-show" >
                <i class="glyphicon glyphicon-search" data-toggle="collapse"  href="#search-aux"></i>
              </a>

              <div class="content-panel panel">
                <div class="content">

                  <?php include '..\templates\base\table.php' ?>

                </div>
              </div>

            </div>
            <!-- Fin .panel Panel Contenido -->
            <!-- .panel Panel Detalle -->
            <div id="detail-content-aux" class=" panel-hidden panel-container col-xs-7 ">

              <div class="content-panel panel">
                <div class="content">
                  <a class="close-detail"   href="#detail-content-aux">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>
                  <?php include '..\templates\base\detail.php' ?>

                </div>
              </div>
            </div>
            <!-- Fin .panel Panel Detalle -->



          </div>
        </div>
          
        <div class="container-fluid" style="margin-left: 50px;display: none;">
          <div class="row" id="contenido-form-aux" style="display: none;">           

            <!-- .panel Panel Contenido -->
            <div class="panel-container col-xs-23 ">
              <div class="content-panel panel">
                <div class="content">
                  <?php include '../templates/base/panel-heading.php'; ?>
                  <div class="row">
                    <div class="col-xs-12">
                      <?php include '..\templates\base\form.php' ?>
                    </div>
                    <div class="col-xs-offset-1 col-xs-11">
                      <?php include '..\templates\base\buttons.php' ?>
                      <?php include '..\templates\base\pager.php' ?>
                    </div>


                  </div>
                  <div class="row">
                    <div class="col-xs-24">
                    <?php include '..\templates\components\tabs.php' ?>
                    </div>
                  </div>

                </div>
              </div>

            </div>
            <!-- Fin .panel Panel Contenido -->




          </div>
        </div>
 
 <!-- Fin Nuevo Prueba -->
        
      </div>
      <!-- Fin .panels -->

    </div>
    <!-- Fin content-wrap Area de Contenido -->

  </div>
  <!-- Fin #page -->
</div>
<!-- Fin #page-wrapper -->


<!-- jQuery 
<script src="../../juziel/vendor/jquery/dist/jquery.min.js"></script>-->

<!-- Bootstrap Core JavaScript 
<script
  src="../../juziel/vendor/bootstrap-sass/assets/javascripts/bootstrap.min.js"></script>-->

<!-- jstree 
<script src="../../juziel/vendor/jstree/dist/jstree.min.js"></script>-->

<!-- Scripts personalizaods 


<script src="../../juziel/dist/js/ui.js"></script>-->

                       
        <div class="modal fade bs-example-modal-lg" id="myModal-Ventana" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog  modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModal-Ventana-Titulo">Filtrar Por</h4>
                </div>
                <div class="modal-body" id="myModal-Ventana-Cuerpo">
                    
                </div>
                <div class="modal-footer">
                  
                </div>
              </div>
            </div>
        </div>
                    
        <div class="modal fade bs-example-modal-lg" id="myModal-Ventana-2" tabindex="-2" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog  modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModal-Ventana-Titulo-2">Filtrar Por</h4>
                </div>
                <div class="modal-body" id="myModal-Ventana-Cuerpo-2">
                    
                </div>
                <div class="modal-footer">
                  
                </div>
              </div>
            </div>
        </div>
        
        
                    
                    
        <input type="hidden" id="permiso_modulo" name="permiso_modulo" value="{PERMISO}"/>
        <input type="hidden" id="modulo_actual" name="modulo_actual" value="{MODULO_ACTUAL}"/>
        <input type="hidden" id="modulo_actual_id" name="modulo_actual_id"/>
        <!--$.validate({
                            lang: 'es'  
                          }); -->
       
        <script type="text/javascript">
            $(document).ready(function(){
                {SCRIPT_LOAD}
                                   
                    
            });
            
                                 
        </script>
        <a id="ver_ficha_trabajador"></a>

</body>
<!--
﻿<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="../msks/images2/favicon.ico" rel="shortcut icon">

        <!-- Bootstrap Core CSS 
        <link
          href='https://fonts.googleapis.com/css?family=Roboto:400,700,300,700italic,400italic,300italic'
          rel='stylesheet' type='text/css'>-->
<!--        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MOSAIKUS - Administrador de Sistemas de Gesti&oacute;n</title>
        <!--meta name="viewport" content="width=device-width, initial-scale=1">-->
        <!-- CSS de Bootstrap -->
        

<!--            
  </head>
    <body>
                

        
        
        <div id="mensaje_error"  style="z-index:10000;position:absolute; top:250px; left:350px; width: 60%;display: none; "></div>
        <div id="mensaje_exito" class="alert alert-success" style="z-index:10000;position:absolute; top:250px; left:350px; width: 400px;display: none; "></div>
        <div id="mensaje_info" class="alert alert-info" style="z-index:10000;position:absolute; top:250px; left:350px; width: 400px; display: none;"></div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">                   
                    <img src="diseno/images/Logo.png" class="SinBorde" />                                       
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-1 center-block" style="height: 60px;">
                            
                        </div>
                        <div class="col-md-11 center-block" style="height: 60px;">
                            <h4 class="TitulosHeader14px"><br>Administrador de Sistemas de Gesti&oacute;n</h4>
                        </div>                        
                     </div>
                    <div class="row">
                        <div class="col-md-1 center-block">
                            &nbsp;
                        </div>
                        <div class="col-md-6" style="height: 28px;">
                            <nav class="navbar navbar-default" role="navigation" >   
                                
                                
                                  <ul class="nav navbar-nav">

                                    <li class="dropdown">
                                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        Menú Principal <b class="caret"></b>
                                      </a>
                                      <ul class="dropdown-menu">
                                                {MENU_PRINCIPAL}                                                
                                      </ul>
                                    </li>
                                  </ul>
                                
                            </nav>
                        </div>      
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-10" style="padding-right: 5px;">
                                <br><br>
                                <p class="TitulosHeader10px text-right">
                                
                                {NOMBREMPRESA}
                                <br>
                                {USUARIO}
                                
                                </p>
                            </div>
                            <div class="col-md-2" style="text-align: right;padding-left: 5px;">
                                <br><br>
                                <a href="javascript:contrasena()" class="LinksinLineaBlanco"><img src="diseno/images/CambiaPass.png" class="SinBorde" title="Cambio Password" /></a>
                                

                                <img src="diseno/images/super_usuario.png" class="SinBorde" />

                                <a href="login.php?cerrar=true"><img src="diseno/images/logout.png" title="Cerrar Sesi&oacute;n" class="SinBorde" /></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <div class="container-fluid" style="width: 98%">
            <div class="row" >
                <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="vertical-align:top;">
                <tbody>
                    <tr class="TitulosSubtitulos14px" height="20px">
                        <td width="16" align="LEFT"> </td>
                        <td width="32" valign="top" align="LEFT" rowspan="2">
                            <div style="width:100%; ">
                                <div id="order_updatepanel3" class="_kup " style="position:relative;">
                                    <div>
                                        <img class="SinBorde" src="diseno/images/Indicadores.png">
                                    </div>
                                </div>

                            </div>
                        </td>
                        <td width="747" align="LEFT"> </td>
                        <td width="200" align="LEFT"> </td>
                    </tr>
                    <tr class="TitulosSubtitulos14px" height="30px">
                        <td bgcolor="#003895" height="18" align="LEFT"> </td>
                        <td width="85%" bgcolor="#003895" align="LEFT" style="padding-left:5px;"> <div id="desc-mod-act"></div></td>
                        <td bgcolor="#003895" align="center">
                            <div id="MustraCargando" style="z-index:600; display:none;">
                                <img src="diseno/images/CargaPrin.gif">
                            </div>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <div id="contenido"class="row" style="min-height: 500px;">                                
                <div  class="col-md-12" style=" background-image: url('diseno/images/FondoDIVPrin.png');padding-left: 0px; padding-right: 0px;">                
                             {CONTENIDO}                    
                <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                </div>
            </div>
            <div class="row" style="">
                <div id="contenido-form" class="col-md-12" style="display: none;background-image: url('diseno/images/FondoDIVPrin.png');padding-left: 0px; padding-right: 0px;">
                    <div  class="content-wrapper clear-block">
                         {CONTENIDOFORM}                                                  
                    </div>
                </div>  
            </div>
            
            <div id="contenido-aux"class="row" style="display: none;min-height: 500px;">                                
                <div  class="col-md-12" style=" background-image: url('diseno/images/FondoDIVPrin.png');padding-left: 0px; padding-right: 0px;">                
                             {CONTENIDO}                    
                <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                </div>
            </div>
            <div class="row" style="">
                <div id="contenido-form-aux" class="col-md-12" style="display: none;background-image: url('diseno/images/FondoDIVPrin.png');padding-left: 0px; padding-right: 0px;">
                    <div  class="content-wrapper clear-block">
                         {CONTENIDOFORM}                                                  
                    </div>
                </div>  
            </div>
            
        </div>

                    
                    

        
          
         
        
    </body>
</html>

-->
