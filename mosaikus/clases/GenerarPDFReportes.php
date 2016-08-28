<?php
require_once (dirname(__FILE__).'/MPDF60/mpdf.php');

/**
 * Handles the saving generated PDF to user-defined output file on server
 */

/**
 * Runs the HTML->PDF conversion with default settings
 *
 * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
 * use absolute links (like http://my.host/image.gif).
 *
 * @param $path_to_html String HTML code to be converted
 * @param $path_to_pdf  String path to  file to save generated PDF to.
 * @param $base_path    String base path to use when resolving relative links in HTML code.
 */
class GenerarPDFReportes{

    function pdf_create($html, $filename, $baja=false, $sizepage=1, $piepagina=true, $orientacion=0, $textopiepagina = 'Página ',$textoemision='Fecha Emisión ') {
        //usando MFPDF

        $path_to_pdf="adjuntos/".$filename.".pdf";
        $formato = "";

        switch ($sizepage) {
            case 1:
                if($orientacion==1){
                    $formato = "Letter-L";
                }else{
                    $formato = "Letter";
                }
                break;
            case 2:
                $formato = array(216,139.5);
                break;
            }

        //$mpdf=new mPDF('','Letter','','',5,5,5,15);
        $mpdf=new mPDF('',$formato,'','',5,5,5,15);
        //$mpdf->pagenumPrefix = 'Fecha de Emisi�n'.date('d/m/Y').' P�gina ';
		$mpdf->pagenumPrefix = ($textoemision.date('d/m/Y').' - '.$textopiepagina);
        $mpdf->pagenumSuffix = '';
        $mpdf->nbpgPrefix = ' de ';

        if ($piepagina) {
             $mpdf->SetFooter('{PAGENO}{nbpg}');
        }
        

        //$mpdf->Bookmark('Start of the document');
        $mpdf->WriteHTML($html);

        if($baja){
            $mpdf->Output($filename.".pdf",'D');
        }else{
            $mpdf->Output($path_to_pdf,'F');
        }

    }

    function pdf_create_separador($html, $filename, $baja=false, $sizepage=1, $piepagina=true, $orientacion=0, $textopiepagina = 'Página ',$textoemision='Fecha Emisión ') {
        //usando MFPDF
        
        $path_to_pdf="adjuntos/".$filename.".pdf";
        $formato = "";

        switch ($sizepage) {
            case 1:
                if($orientacion==1){
                    $formato = "Letter-L";
                }else{
                    $formato = "Letter";
                }
                break;
            case 2:
                $formato = array(216,139.5);
                break;
            }

        $mpdf=new mPDF('',$formato,'','',10,10,10,15);
        //$mpdf=new mPDF('',array(216,139.5),'','',5,5,5,15);
        //$mpdf->pagenumPrefix = 'Fecha de Emisi�n'.date('d/m/Y').' P�gina ';
		$mpdf->pagenumPrefix = ($textoemision.date('d/m/Y').' - '.$textopiepagina);
        $mpdf->pagenumSuffix = '';
        $mpdf->nbpgPrefix = ' de ';
        //$mpdf->SetHeader("pruebaaa");
        if ($piepagina) {
             $mpdf->SetFooter('{PAGENO}{nbpg}');
        }

        //$mpdf->Bookmark('Start of the document');
        $tam = count($html);
        $i = 0;        
        foreach ($html as $cont) {            
            $mpdf->WriteHTML($cont);
            if ($i!=(count($html) - 1)) 
            {
                $mpdf->AddPage();
            }
            $i++;
        }        


        if($baja){
            $mpdf->Output($filename.".pdf",'D');
        }else{
            $mpdf->Output($path_to_pdf,'F');
        }
        
        /*
        $path_to_pdf="adjuntos/".$filename.".pdf";
        $formato = "";

        switch ($sizepage) {
            case 1:
                if($orientacion==1){
                    $formato = "Letter-L";
                }else{
                    $formato = "Letter";
                }
                break;
            case 2:
                $formato = array(216,139.5);
                break;
            }

        $mpdf=new mPDF('',$formato,'','',10,10,40,25, 10, 8);        
        if ($piepagina) {
             
        }
        $mpdf->SetHTMLHeaderByName('MyHeader1');

        
        $tam = count($html);
        $i = 0;
        
        foreach ($html as $cont) {        
            $mpdf->WriteHTML($cont);
            if ($i!=(count($html) - 1)) 
            {
                $mpdf->AddPage();
            }
            $i++;
        }


        if($baja){
            $mpdf->Output($filename.".pdf",'D');
        }else{
            $mpdf->Output($path_to_pdf,'F');
        }*/
    }
    
    function pdf_create_informe_alerta($html, $filename, $baja=false, $sizepage=1, $piepagina=true, $orientacion=0, $textopiepagina = 'Página ',$textoemision='Fecha Emisión ') {
        //usando MFPDF

        $path_to_pdf="adjuntos/".$filename.".pdf";
        $formato = "";

        switch ($sizepage) {
            case 1:
                if($orientacion==1){
                    $formato = "Letter-L";
                }else{
                    $formato = "Letter";
                }
                break;
            case 2:
                $formato = array(216,139.5);
                break;
            }

        $mpdf=new mPDF('',$formato,'','',20,20,20,25);
        //$mpdf=new mPDF('',array(216,139.5),'','',5,5,5,15);
        //$mpdf->pagenumPrefix = 'Fecha de Emisi�n'.date('d/m/Y').' P�gina ';
		$mpdf->pagenumPrefix = ($textoemision.date('d/m/Y').' - '.$textopiepagina);
        $mpdf->pagenumSuffix = '';
        $mpdf->nbpgPrefix = ' de ';
        //$mpdf->SetHeader("pruebaaa");
//        if ($piepagina) {
//             $mpdf->SetFooter('{PAGENO}{nbpg}');
//        }

        //$mpdf->Bookmark('Start of the document');
        $tam = count($html);
        $i = 0;        
        foreach ($html as $cont) {            
            $mpdf->WriteHTML($cont);
            if ($i!=(count($html) - 1)) 
            {
                $mpdf->AddPage();
            }
            $i++;
        }        


        if($baja){
            $mpdf->Output($filename.".pdf",'D');
        }else{
            $mpdf->Output($path_to_pdf,'F');
        }

    }
    
    function pdf_create_informe_alerta_new($html, $filename, $baja=false, $sizepage=1, $piepagina=true, $orientacion=0, $textopiepagina = 'Página ',$textoemision='Fecha Emisión ') {
        //usando MFPDF

        $path_to_pdf="adjuntos/".$filename.".pdf";
        $formato = "";

        switch ($sizepage) {
            case 1:
                if($orientacion==1){
                    $formato = "Letter-L";
                }else{
                    $formato = "Letter";
                }
                
                $mpdf=new mPDF('',$formato,'','',20,20,20,25);
                break;
            case 2:
               
                $formato = "Letter-L";
                $mpdf=new mPDF('',$formato,'','',0,0,0,1);
                break;
            }

        
        //$mpdf=new mPDF('',array(216,139.5),'','',5,5,5,15);
        //$mpdf->pagenumPrefix = 'Fecha de Emisi�n'.date('d/m/Y').' P�gina ';
		$mpdf->pagenumPrefix = ($textoemision.date('d/m/Y').' - '.$textopiepagina);
        $mpdf->pagenumSuffix = '';
        $mpdf->nbpgPrefix = ' de ';
        //$mpdf->SetHeader("pruebaaa");
//        if ($piepagina) {
//             $mpdf->SetFooter('{PAGENO}{nbpg}');
//        }

        //$mpdf->Bookmark('Start of the document');
        $tam = count($html);
        $i = 0;        
        foreach ($html as $cont) {            
            $mpdf->WriteHTML($cont);
            if ($i!=(count($html) - 1)) 
            {
                $mpdf->AddPage();
            }
            $i++;
        }        


        if($baja){
            $mpdf->Output($filename.".pdf",'D');
        }else{
            $mpdf->Output($path_to_pdf,'F');
        }

    }
    
    function pdf_create_reporte($html, $filename, $baja=false, $sizepage=1, $piepagina=true, $orientacion=0,$ruta) {
        //usando MFPDF
                
        
        if (!file_exists("downloads/tmp_doc/".$ruta."/")) {
	    	mkdir("downloads/tmp_doc/".$ruta."/", 0777);
			chmod("downloads/tmp_doc/".$ruta."/", 0777);
		}
        $path_to_pdf="downloads/tmp_doc/".$ruta."/".$filename.".pdf";
        $formato = "";

        switch ($sizepage) {
            case 1:
                if($orientacion==1){
                    $formato = "Letter-L";
                }else{
                    $formato = "Letter";
                }
                break;
            case 3:
                if($orientacion==1){
                    $formato = "Legal-L";
                }else{
                    $formato = "Legal";
                }
                break;
            case 2:
                $formato = array(216,139.5);
                break;
            }

        $mpdf=new mPDF('',$formato,'','',10,10,33,25, 5, 0);  
        $stylesheet = file_get_contents('dist/css/styles_pdf.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);
        if ($piepagina) {
             
        }
        $mpdf->SetHTMLHeaderByName('MyHeader1');

        
        $tam = count($html);
        $i = 0;
        
        foreach ($html as $cont) {        
            $mpdf->WriteHTML($cont,2+$i);
            if ($i!=(count($html) - 1)) 
            {
                $mpdf->AddPage();
            }
            $i++;
        }


        if($baja){
            $mpdf->Output($filename.".pdf",'I');
        }else{
            $mpdf->Output($path_to_pdf,'F');
            $mpdf->debug = true;
            Header("Location: " .APPLICATION_ROOT . $path_to_pdf);
        }
    }
    
    function pdf_create_reporte_portada($html, $filename, $baja=false, $sizepage=1, $piepagina=true, $orientacion=0,$ruta) {
        //usando MFPDF
                
        
        if (!file_exists("downloads/tmp_doc/".$ruta."/")) {
	    	mkdir("downloads/tmp_doc/".$ruta."/", 0777);
			chmod("downloads/tmp_doc/".$ruta."/", 0777);
		}
        $path_to_pdf="downloads/tmp_doc/".$ruta."/".$filename.".pdf";
        $formato = "";

        switch ($sizepage) {
            case 1:
                if($orientacion==1){
                    $formato = "Letter-L";
                }else{
                    $formato = "Letter";
                }
                break;
            case 3:
                if($orientacion==1){
                    $formato = "Legal-L";
                }else{
                    $formato = "Legal";
                }
                break;
            case 2:
                $formato = array(216,139.5);
                break;
            }

        $mpdf=new mPDF('',$formato,'','',0,0,30,0, 0, 0);  
        $stylesheet = file_get_contents('dist/css/styles_pdf_formato_1.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);
        if ($piepagina) {
             
        }
        $mpdf->SetHTMLHeaderByName('MyHeader1');

        
        $tam = count($html);
        $i = 0;
        
        foreach ($html as $cont) {        
            $mpdf->WriteHTML($cont,2+$i);
            if ($i!=(count($html) - 1)) 
            {
                $mpdf->AddPage();
            }
            $i++;
        }


        if($baja){
            $mpdf->Output($filename.".pdf",'I');
        }else{
            $mpdf->Output($path_to_pdf,'F');
            $mpdf->debug = true;
            return $path_to_pdf;
            //Header("Location: " .APPLICATION_ROOT . $path_to_pdf);
        }
    }
}

?>