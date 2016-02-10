<?php

    function acomodar_texto($texto){
    //$texto="El mundo del PHP es grande porque se erequiere mucho conocimiento";
        $cont = 0;
        $text = "";
        $texto = html_entity_decode($texto, ENT_QUOTES, "UTF-8");
        for($i=0;$i<strlen($texto);$i++){ 
            $cont++;
            $text = $text . $texto[$i];
            if ($cont >=25){
                //echo 1;
                if ($texto[$i] == " "){
                    $text .= "\n";
                    //echo 2;
                    $cont = 0;
                }
            }
            //echo $texto[$i]; 
        }
        $texto = $text;
        return $texto;
    }
    
    chdir("../..");
    $paginas = array();
    $paso = false;
    include_once('clases/clases.php');
    include_once("clases/interfaz/Template.php");
    include_once('configuracion/import.php');
    include_once('configuracion/configuracion.php');
    
    function semaforo_report($tupla)
        {
            if (($tupla[dias_vig])<0){
                $html = "<img src=\"". HOME . "/diseno/images/rojo.png\">";                                                                    
                $html = "<img src=\"rojo.png\">";                                                                    
                return $html . ' ' . $tupla[dias_vig];
            }
            if ($tupla[dias_vig]<$tupla[semaforo]){
                $html = "<img src=\"". HOME . "/diseno/images/amarillo.png\">";                                                                    
                $html = "<img src=\"amarillo.png\">";     
                return $html. ' ' . $tupla[dias_vig];
            }
            return "<img src=\"verde.png\">" . ' ' . $tupla[dias_vig];
            return "<img src=\"". HOME . "/diseno/images/verde2.png\">";
        }
    
    //import('clases.no_conformidad.NoConformidad');

    session_name('mosaikus');            
    session_start();
    import('clases.documentos.Documentos');
    $pagina = new Documentos();

    
    $contenido_1[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
    //$val = $pagina->verNoConformidad($_GET[id]);
    if ((isset($_SESSION[CookIdUsuario]) )){// && (md5($_GET[id]) == $_GET[token])){
          if ((isset($_GET["b-id_organizacion"]))&&(strlen($_GET["b-id_organizacion"])>0)){
              $ids = explode('-', $_GET["b-id_organizacion"]);
              $contenido_1[ARBPROC] = BuscaOrganizacional(array('id_organizacion'=> $ids[0]));
              //echo $contenido_1[ARBPROC];
          }
          else{
              $contenido_1[ARBPROC] = BuscaOrganizacional(array('id_organizacion'=> 2));
              //echo $contenido_1[ARBPROC];
          }
//        $val = $pagina->verDocumentos($_GET[id]);
//
//        $contenido_1['IDDOC'] = $val["IDDoc"];
//        $contenido_1['CODIGO_DOC'] = ($val["Codigo_doc"]);
//        $contenido_1['NOMBRE_DOC'] = ($val["nombre_doc"]);
//        $contenido_1['VERSION'] = $val["version"];
//        $contenido_1['FECHA'] = ($val["fecha"]);
//        $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
//        $contenido_1['PALABRAS_CLAVES'] = ($val["palabras_claves"]);
//        $contenido_1['FORMULARIO'] = ($val["formulario"]) == 'S' ? 'Si' : 'No';
//        $contenido_1['VIGENCIA'] = ($val["vigencia"]) == 'S' ? 'Si' : 'No';
//        $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
//        $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
//        $contenido_1['ID_FILIAL'] = $val["id_filial"];
//        $contenido_1['NOM_VISUALIZA'] = ($val["nom_visualiza"]);
//        $contenido_1['DOC_VISUALIZA'] = $val["doc_visualiza"];
//        $contenido_1['CONTENTTYPE_VISUALIZA'] = ($val["contentType_visualiza"]);
//        $contenido_1['ID_USUARIO'] = $val["id_usuario"];
//        $contenido_1['OBSERVACION'] = $val["observacion"];
//        $contenido_1['MUESTRA_DOC'] = ($val["muestra_doc"]);
//        $contenido_1['ESTRUCORG'] = ($val["estrucorg"]);
//        $contenido_1['ARBPROC'] = BuscaOrganizacionalTodos($val);
//        $contenido_1['APLI_REG_ESTRORG'] = ($val["apli_reg_estrorg"]);
//        $contenido_1['APLI_REG_ARBPROC'] = ($val["apli_reg_arbproc"]);
//        $contenido_1['WORKFLOW'] = ($val["workflow"]);
//        $contenido_1['SEMAFORO'] = $val["semaforo"] == 'S' ? 'Si' : 'No';
//        $contenido_1['V_MESES'] = $val["v_meses"];
//        $contenido_1['REVISO'] = $val["reviso_a"];
//        $contenido_1['ELABORO'] = $val["elaboro_a"];
//        $contenido_1['APROBO'] = $val["aprobo_a"];
//        
        if (count($pagina->nombres_columnas) <= 0){
                $pagina->cargar_nombres_columnas();
        }
        foreach ( $pagina->nombres_columnas as $key => $value) {
            $contenido_1["N_" . strtoupper($key)] =  $value;
        }  
//        $qry = "SELECT t1.codigo_doc,t1.nombre_doc,t1.version,t1.observacion,t1.workflow,t1.fecha,t1.IDDoc";
//		$qry.= " ,t2.IDDoc,t2.revision,DATE_FORMAT(t2.fechaRevision, '%d/%m/%Y') fechaRevision_a,t2.responsable,t2.observacion,t2.IDDoc_version";
//                $qry.= " ,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) elaboro_a";
//		$qry.= " FROM mos_documentos t1";
//		$qry.= " left join mos_documento_revision t2 on t1.IDDoc=t2.IDDoc"
//                        . " left join mos_personal p on t1.elaboro=p.cod_emp ";
//		$qry.= " where t1.codigo_doc='".$val["Codigo_doc"]."'";
//		$qry.= " order by t1.version desc,t1.fecha desc,t2.fechaRevision desc,t2.revision desc";
        $pagina->listarDocumentos($_GET, 1, 100000);
        $data = $pagina->dbl->data;
        $contenido_1[DATOS] = '';
        foreach ($data as $value) {
            $contenido_1[DATOS] .= '<tr>';
            $contenido_1[DATOS] .= '<td align="center">' . semaforo_report($value) . '</td>';
            //$contenido_1[DATOS] .= '<td>' . $value[dias_vig] . '</td>';
            $contenido_1[DATOS] .= '<td>' . $value[Codigo_doc] . '</td>';
            $contenido_1[DATOS] .= '<td>' . $value[nombre_doc] . '</td>';
            $contenido_1[DATOS] .= '<td>' . $value[version] . '</td>';
            $contenido_1[DATOS] .= '<td>' . $value[num_rev] . '</td>';
            $contenido_1[DATOS] .= '<td>' . $value[elaboro] . '</td>';
            
            $contenido_1[DATOS] .= '<td>' . BuscaOrganizacionalTodos($value) . '</td>';
            $contenido_1[DATOS] .= '<td>' . ($value[formulario]=='S'?'Si':'No') . '</td>';
            //$contenido_1[DATOS] .= '<td>' . $value[v_meses] . '</td>';
            
            
            
            //$contenido_1[DATOS] .= '<td>' . $value[fecha] . '</td>';
            
            //$contenido_1[DATOS] .= '<td>' . $value[fecha_rev] . '</td>';
            
            //$contenido_1[DATOS] .= '<td>' . $value[observacion] . '</td>';
            $contenido_1[DATOS] .= '</tr>';
        }
        $contenido_1['HOME'] = HOME;
        $contenido_1[FECHA] = date('d/m/Y');
        //$contenido_1[IMAGES_ANTES] = $html_img_antes;
        $contenido_1['N_PAG'] = '{PAGENO}/{nbpg}';
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'documentos/';
        $template->setTemplate("reporte_arbol_pdf");     
        $template->setVars($contenido_1);                    
        $paginas[] = ($template->show());


        //echo $template->show();


        $string = "";
        require("clases/GenerarPDFReportes.php");
        $pdf = new GenerarPDFReportes();
        //echo 1;
        //echo $template->show();
        $pdf->pdf_create_reporte($paginas, "Reporte_Individual_" . $val["Codigo_doc"], false, 3, true, 1,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     
        //echo 2;
    //    
    //    header('Content-Type: "application/octect-stream"');                
    //    header("Content-Disposition: attachment; filename=informe_$_GET[id].xls");                
    //    header('Expires: 0');
    //    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    //    header("Content-Transfer-Encoding: binary");
    //    header('Pragma: public');
    //    echo $paginas[0];
    //    echo $paginas[1];
    }

?>