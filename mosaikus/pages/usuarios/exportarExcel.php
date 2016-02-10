<?php
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Usuarios.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    ?>
    <?php

            chdir('..');
            chdir('..');
            include_once('clases/clases.php');
            include_once('configuracion/import.php');
            include_once('configuracion/configuracion.php');
            import('clases.usuarios.Usuarios');
            $pagina = new Usuarios();


    ?>

    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body style="background-color:transparent" >

    <?
    echo $pagina->exportarExcel(array('campo' => $_GET['campo'], 'valor' => $_GET['valor']));

    ?>
    </body>
    </html>
    