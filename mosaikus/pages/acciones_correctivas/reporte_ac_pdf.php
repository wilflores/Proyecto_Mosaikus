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
        
        $val = $pagina->verAccionesCorrectivas($_GET[id]);
        $contenido_1 = array();
        $contenido_1['ORIGEN_HALLAZGO'] = ($val["origen_hallazgo"]);
        $contenido_1['FECHA_GENERACION'] = ($val["fecha_generacion"]);
        $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
        $contenido_1['ANALISIS_CAUSAL'] = ($val["analisis_causal"]);
        $contenido_1['RESPONSABLE_ANALISIS'] = $val["responsable_analisis"];
        $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
        $contenido_1['ID_PROCESO'] = $val["id_proceso"];
        $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
        $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
        $contenido_1['ID_RESPONSABLE_SEGUI'] = $val["id_responsable_segui"];
	$template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
        
        $contenido_1[TABLA] = $tabla[tabla];
        $contenido_1['HOME'] = HOME;
        $contenido_1[FECHA] = date('d/m/Y');
        $contenido_1['N_PAG'] = '{PAGENO}/{nbpg}';
        $contenido_1[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
        $template->setTemplate("reporte_individual_pdf");     
        $template->setVars($contenido_1);                    
        $paginas[] = ($template->show());


        //echo $template->show();


        $string = "";
        require("clases/GenerarPDFReportes.php");
        $pdf = new GenerarPDFReportes();
        //echo 1;
        //echo $template->show();
        $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 1, true, 0,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     


?>
