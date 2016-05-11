<?php
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=AccionesCorrectivas.xls");
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
            import('clases.acciones_correctivas.AccionesCorrectivas');
            $pagina = new AccionesCorrectivas();

            $fichero_texto = fopen ("dist/css/style_export_excel.css", "r");
            $contenido_fichero = fread($fichero_texto, filesize("dist/css/style_export_excel.css"));

    ?>

    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
    <?php
        echo $contenido_fichero;
    ?>
    
    </style>
    </head>
    <body style="background-color:transparent" >

        <table style="border: 0px solid #ddd;">
            <tr>
                <td style="border: 0px solid #ddd;"><b style="font-size: 18px;">Acciones Correctivas</b><br/></td>
                <td colspan="3" style="border: 0px solid #ddd;">&nbsp;</td>
                <td style="border: 0px solid #ddd;"><img class="img-responsive" src="<?php echo PATH_TO_IMG . 'logo_empresa/'.$_SESSION[CookIdEmpresa].'_logo_empresa_report.png'; ?>"></td>
            </tr>
            <tr>
                <td style="border: 0px solid #ddd;">Fecha: <?php echo date('d/m/Y'); ?></td>
            </tr>
        </table>                
        <br>
        <br>
    <?php
    echo $pagina->exportarExcel($_GET);

    ?>
        
         <br/>
            <div class="report-footer">
                <div class="row">
                  <div class=" col-xs-2 mosaikus-mini-logo">
                    <img class="img-responsive" src="<?php echo APPLICATION_HOST.'/'.RUTA.'dist/images/logo_report.png'; ?>">
                  </div>

                  
                </div>
                
            </div>
    </body>
    </html>
    