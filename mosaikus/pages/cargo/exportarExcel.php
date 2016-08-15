<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Cargos.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php

session_name('mosaikus');
session_start();
chdir('..');
chdir('..');
include_once('clases/clases.php');
include_once('configuracion/import.php');
include_once('configuracion/configuracion.php');
import('clases.cargo.Cargos');
$pagina = new Cargos();

$params['b-id_organizacion'] = $_GET['b-id_organizacion'];
$params[cod_link] = $_GET[cod_link];
$params[modo] = $_GET[modo];
$params[reg_por_pagina] = 5000;
$pagina->cargar_acceso_nodos($params);
$html = $pagina->verListaCargosReporte($params);
//echo 1;
$fichero_texto = fopen("dist/css/style_export_excel.css", "r");
$contenido_fichero = fread($fichero_texto, filesize("dist/css/style_export_excel.css"));


?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style type="text/css">
        <?php
            echo $contenido_fichero;
        ?>


    </style>
</head>
<body style="background-color:transparent">


<table style="border: 0px solid #ddd;">
    <tr>
        <td style="border: 0px solid #ddd;">Cargos<br/></td>
        <td colspan="3" style="border: 0px solid #ddd;">&nbsp;</td>
        <td style="border: 0px solid #ddd;">
            <img class="img-responsive" src="<?php echo PATH_TO_IMG . 'logo_empresa/' . $_SESSION[CookIdEmpresa] . '_logo_empresa_report.png'; ?>">
        </td>
    </tr>
    <tr>
        <td style="border: 0px solid #ddd;">Fecha: <?php echo date('d/m/Y'); ?></td>
    </tr>
</table>
<br>
<br>


<table class="table table-report  ">
    <thead>
    <tr>
        <?php echo $html[titulo]; ?>
    </tr>
    </thead>
    <tbody>

    <?php echo $html[tabla] ?>

    </tbody>
</table>


<br/>
<div class="report-footer">
    <div class="row">
        <div class=" col-xs-2 mosaikus-mini-logo">
            <img class="img-responsive"
                 src="<?php echo APPLICATION_HOST . '/' . RUTA . 'dist/images/logo_report.png'; ?>">
        </div>


    </div>

</div>

<?php

?>
</body>
</html>
    