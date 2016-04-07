<?php
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=ArbolOrganizacional.xls");
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
            import('clases.organizacion.ArbolOrganizacional');
            $pagina = new ArbolOrganizacional();
            if (strlen($_GET['b-id_organizacion'])== 0)
                $params['b-id_organizacion'] = 2;
            else $params['b-id_organizacion'] = $_GET['b-id_organizacion'];
            $html = $pagina->verListaArbolOrganizacionalReporte($params);

    ?>

    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
    .table-report {
    border-color: #afbdc3;
}
.table-bordered, .table-report {
    border: 1px solid #ddd;
}
.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
}
table {
    //background-color: #fafafa;
}
table {
    border-collapse: collapse;
    border: 1px solid #ddd;
    border-spacing: 0;
}
.table-report thead tr th {
    background-color: #ced7db;
}

.table-bordered > thead > tr > th, .table-report > thead > tr > th, .table-bordered > thead > tr > td, .table-report > thead > tr > td, .table-bordered > tbody > tr > th, .table-report > tbody > tr > th, .table-bordered > tbody > tr > td, .table-report > tbody > tr > td, .table-bordered > tfoot > tr > th, .table-report > tfoot > tr > th, .table-bordered > tfoot > tr > td, .table-report > tfoot > tr > td {
    border: 1px solid #ddd;
}
table > thead > tr > th, table > thead > tr > td, table > tbody > tr > th, table > tbody > tr > td, table > tfoot > tr > th, table > tfoot > tr > td {
    border-top: 1px solid #ddd;
    line-height: 1.42857;
    padding: 8px;
    vertical-align: top;
}

th{
     background-color: #ced7db;
     border-bottom-width: 2px;
}
td, th {
    padding: 0;
}

td{
      border: 1px solid #ddd;
}
* {
    box-sizing: border-box;
}
    
    </style>
    </head>
    <body style="background-color:transparent" >



        <table style="border: 0px solid #ddd;">
            <tr>
                <td style="border: 0px solid #ddd;">Árbol Organizacional<br/></td>
                <td colspan="3" style="border: 0px solid #ddd;">&nbsp;</td>
                <td style="border: 0px solid #ddd;"><img class="img-responsive" src="<?php echo PATH_TO_IMG . 'logo_empresa/'.$_SESSION[CookIdEmpresa].'_logo_empresa_report.png'; ?>"></td>
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
                          <?php echo $html[titulo]; ?><!--
                        <th style="width: 450px;">GERENCIAS</th>
                        <th style="width: 450px;">ÁREAS</th>
                        <th style="width: 450px;">SUB - ÁREAS</th>
                        <th style="width: 450px;">SUB - ÁREAS</th>-->


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
                    <img class="img-responsive" src="<?php echo APPLICATION_HOST.'/'.RUTA.'dist/images/logo_report.png'; ?>">
                  </div>

                  
                </div>
                
            </div>
        
    <?php
    //echo $pagina->exportarExcel($_GET);

    ?>
    </body>
    </html>
    