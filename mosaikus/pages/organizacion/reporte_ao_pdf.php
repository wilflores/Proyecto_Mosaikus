<?php
    
ini_set('memory_limit', '-1');
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
        $nombre_area = $pagina->BuscaOrganizacional(array('id_organizacion'=>$params['b-id_organizacion']));
        
        $params[cod_link] = $_GET[cod_link];
        $params[modo] = $_GET[modo];
        $params[pag] = 1;
        $params[reg_por_pagina] = 25;
        $params[niveles] = $pagina->numeroNivelesHijos(array($params["b-id_organizacion"]));
        $total_registros = $pagina->ContarArbolOrganizacionalReporte($params);
        switch ($params[niveles]) {
            case 1:
            case 2:
            case 3:
                $params[reg_por_pagina] = 28;
                 break;
            case 4:
                $params[reg_por_pagina] = 24;
                 break;
            case 5:
            $params[reg_por_pagina] = 21;
                        break;

            default:
                $params[reg_por_pagina] = 18;
                break;
        }
        //$params[reg_por_pagina] = $params[niveles] <= 5 ? 25 : 18;
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'organizacion/';
        
        $total_paginas = $total_registros / $params[reg_por_pagina];
        $resto = $total_registros % $params[reg_por_pagina];
        if ($resto > 0)
            $total_paginas++;
        for($i=1;$i<=$total_paginas;$i++){
            $params[pag] = $i;
            $tabla = $pagina->verListaArbolOrganizacionalReporte($params);

            $contenido_1 = array();
            $contenido_1[AREA] = $nombre_area;
            $contenido_1[TABLA] = $tabla[tabla];
            $contenido_1[TITULO] = $tabla[titulo];
            $contenido_1['HOME'] = HOME;
            $contenido_1[FECHA] = date('d/m/Y');
            $contenido_1['N_PAG'] = '{PAGENO}/{nbpg}';
            $contenido_1[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
            $template->setTemplate("listar_reporte_pdf");     
            $template->setVars($contenido_1);                    
            $paginas[] = ($template->show());
        }

        //echo $template->show();


        $string = "";
        require("clases/GenerarPDFReportes.php");
        $pdf = new GenerarPDFReportes();
        //echo 1;
        //echo $template->show();
        //$tabla[niveles] = 5;
        if ($params[niveles] <= 5){
            //if ($tabla[filas] > 30)
            //    $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 3, true, 0,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
            //else
            $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 1, true, 0,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
        }
        else //if ($tabla[niveles] <= 10) 
            $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 3, true, 1,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
        //else 
        //    $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 3, true, 1,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
            
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
