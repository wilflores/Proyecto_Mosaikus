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
	$tabla = $pagina->verListaArbolOrganizacionalReporte($params);
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'organizacion/';
        $contenido_1 = array();
        $contenido_1[TABLA] = $tabla[tabla];
        $contenido_1[TITULO] = $tabla[titulo];
        $contenido_1['HOME'] = HOME;
        $contenido_1[FECHA] = date('d/m/Y');
        $contenido_1['N_PAG'] = '{PAGENO}/{nbpg}';
        $contenido_1[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
        $template->setTemplate("listar_reporte_pdf");     
        $template->setVars($contenido_1);                    
        $paginas[] = ($template->show());


        //echo $template->show();


        $string = "";
        require("clases/GenerarPDFReportes.php");
        $pdf = new GenerarPDFReportes();
        //echo 1;
        //echo $template->show();
        //$tabla[niveles] = 9;
        if ($tabla[niveles] <= 4)
            $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 1, true, 0,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
        else if ($tabla[niveles] <= 8) 
            $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 1, true, 1,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
        else 
            $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 3, true, 1,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
            
//        require_once ('clases/MPDF60/mpdf.php');
//        $mpdf = new mPDF();
//$html = '<div class="container">
//    <div class="row">
//        <div class="col-xs-4" style="background-color: palevioletred;"><strong>A</strong></div>
//        <div class="col-xs-8" style="background-color: #808080;"><strong>B</strong></div>
//        <div class="col-xs-9" style="background-color: #008000;"><strong>C</strong></div>
//    </div>
//</div>';
////echo file_get_contents('diseno/css/bootstrap.min.css');
//$mpdf->WriteHTML(file_get_contents('dist/css/styles_pdf.css'), 1);
//$mpdf->WriteHTML($html, 2);
//$mpdf->Output();
        

?>
