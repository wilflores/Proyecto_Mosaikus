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
            import('clases.acciones_correc.AccionesCorrectivas');
            $pagina = new AccionesCorrectivas();


    ?>

    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body style="background-color:transparent" >

    <?php
    echo $pagina->exportarExcel($_GET);

    ?>
    </body>
    </html>
    