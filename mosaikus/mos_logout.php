<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mosaikus</title>
    <!-- Bootstrap Core CSS -->
    <link
        href='https://fonts.googleapis.com/css?family=Roboto:400,700,300,700italic,400italic,300italic'
        rel='stylesheet' type='text/css'>

    <link href="vendor/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <!-- jstree -->
    <link href="dist/css/jstree-mosaikus/style.css" rel="stylesheet">
    <link href="dist/css/styles.css" rel="stylesheet">
</head>

<body>
<!-- #page-wrapper -->
<div id="page-wrapper" class="page-sesion-caducada">
    <!-- #page -->
    <div id="page" class="page">


        <!-- #header -->
        <div class=" header-wrap">
            <header class=" header">

                <?php //include '..\templates\components\client-info.php' ?>
                <!-- .branding Logotipo -->
                <div class="branding">
                    <a class="logo" href="#">
                        <img src="dist/images/logo.png" title="">
                    </a>
                </div>
                <!-- .branding Fin Logotipo -->
                <!-- .status-bar -->
<!--                --><?php //include 'templates\components\status-bar.php' ?>
                <!-- Fin  .status-bar -->
            </header>
        </div>
        <!-- Fin #header -->


        <!-- .content-wrap  -->
        <div class=" content-wrap">
            <!-- #nav  Navegaci�n  -->
<!--            <nav id="nav" class="col">-->
<!--                --><?php //include 'templates\example\menu.php' ?>
<!--            </nav>-->
<!--            <!-- #nav Nevegaci�n -->
<!--            --><?php //include 'templates\components\alert.php' ?>
            <!-- .panels -->
            <div class="panels">


                <div class="container-fluid">
                    <div class="row">

                        <!-- .panel Panel Busqueda  -->
<!--                        <div id="search"-->
<!--                             class=" panel-hidden  panel-container col-md-offset-1  col-xs-4 ">-->
<!--                            <div class="content-panel panel">-->
<!--                                <div class="content">-->
<!---->
<!--<!--                                    --><?php ////include 'templates\components\search.php' ?>
<!---->
<!--                                </div>-->
<!--                            </div>-->
<!---->
<!---->
<!--                        </div>-->
                        <!-- Fin .panel Panel Busqueda -->

                        <!-- .panel Panel Contenido -->
                        <div id="sesion-caducada" class=" col-md-offset-1 panel-container col-xs-23 ">

<!--                            <a class="search-show" >-->
<!--                                <i class="glyphicon glyphicon-search" data-toggle="collapse"  href="#search"></i>-->
<!--                            </a>-->

                            <div class="content-panel panel">
                                <div class="content">

                                    <div class="caducada"> 
    <div class="head">
        <h4>Su sesión ha caducado.</h4>
    </div>
    <div class="body">
        <a href="../msks/index.php"> Retornar a Página Principal</a>
    </div>
</div>

                                </div>
                            </div>

                        </div>
                        <!-- Fin .panel Panel Contenido -->
                        <!-- .panel Panel Detalle -->
<!--                        <div id="detail-content" class=" panel-hidden panel-container col-xs-7 ">-->
<!---->
<!--                            <div class="content-panel panel">-->
<!--                                <div class="content">-->
<!--                                    <a class="close-detail"   href="#search">-->
<!--                                        <i class="glyphicon glyphicon-remove"></i>-->
<!--                                    </a>-->
<!--                                    --><?php //include 'templates\base\detail.php' ?>
<!---->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                        <!-- Fin .panel Panel Detalle -->



                    </div>
                </div>
            </div>
            <!-- Fin .panels -->

        </div>
        <!-- Fin content-wrap Area de Contenido -->

    </div>
    <!-- Fin #page -->
</div>
<!-- Fin #page-wrapper -->


<!-- jQuery -->
<script src="../vendor/jquery/dist/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script
    src="../vendor/bootstrap-sass/assets/javascripts/bootstrap.min.js"></script>

<!-- jstree -->
<script src="../vendor/jstree/dist/jstree.min.js"></script>
<!-- Scrollbar ------------------>
<script src="../vendor/perfect-scrollbar/js/perfect-scrollbar.jquery.js" type="application/javascript"></script>

<!-- Scripts personalizaods -->


<script src="../dist/js/ui.js"></script>

</body>