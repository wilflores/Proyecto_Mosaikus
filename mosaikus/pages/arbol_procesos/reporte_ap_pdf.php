<?php
    
    ini_set('memory_limit', '-1');
        session_name('mosaikus');            
        session_start();

	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.arbol_procesos.ArbolProcesos');

	$pagina = new ArbolProcesos();
        
        $params['b-id_organizacion'] = $_GET['b-id_organizacion'];
        $params[cod_link] = $_GET[cod_link];
        $params[modo] = $_GET[modo];
        $pagina->cargar_acceso_nodos($params);
        $params[reg_por_pagina] = 18;
        $params[niveles] = 2;
        $total_registros = $pagina->ContarArbolProcesosReporte($params);
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';
        
	//echo $total_registros;
        $total_paginas = $total_registros / $params[reg_por_pagina];
        $resto = $total_registros % $params[reg_por_pagina];
        if ($resto > 0)
            $total_paginas++;
        $paginas = array();
        for($i=1;$i<=$total_paginas;$i++){
            $params[pag] = $i;
            $tabla = $pagina->verListaArbolProcesosReporte($params);
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
        }
        if (count($pagina)<=0){
            $params[pag] = 1;
            $tabla = $pagina->verListaArbolProcesosReporte($params);
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
        }
        

        //echo $template->show();


        $string = "";
        require("clases/GenerarPDFReportes.php");
        $pdf = new GenerarPDFReportes();
        //echo 1;
        //echo $template->show();
        $pdf->pdf_create_reporte($paginas, "Reporte_AO" . $val["Codigo_doc"], false, 1, true, 1,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     


?>
