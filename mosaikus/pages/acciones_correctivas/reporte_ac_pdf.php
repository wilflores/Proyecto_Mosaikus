<?php
    
        session_name('mosaikus');            
        session_start();

	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.acciones_correctivas.AccionesCorrectivas');
        if (!isset($_SESSION[CookIdUsuario])) {
                echo "<h1>Acceso denegado</h1>";
                exit();
        }
        $pagina = new AccionesCorrectivas();
        
        $val = $pagina->verAccionesCorrectivas($_GET[id]);
        $contenido_1 = array();
        if (count($pagina->nombres_columnas) <= 0){
                $pagina->cargar_nombres_columnas();
        }
        foreach ( $pagina->nombres_columnas as $key => $value) {
            $contenido_1["N_" . strtoupper($key)] =  $value;
        }   
        
        if (count($pagina->nombres_columnas_ac) <= 0){
                $pagina->cargar_nombres_columnas_acciones();
        }
        
        foreach ( $pagina->nombres_columnas_ac as $key => $value) {
            $contenido_1["NA_" . strtoupper($key)] =  $value;
        }
        
        if (count($pagina->campos_activos) <= 0){
            $pagina->cargar_campos_activos();
        } 
        

        if(!class_exists('Parametros')){
            import("clases.parametros.Parametros");
        }
        $campos_dinamicos = new Parametros();
        $array = $campos_dinamicos->crear_campos_dinamicos_td(8,$val["id"],9);
        $contenido_1[CAMPOS_DINAMICOS] = $array[html];
        if (($array[contador] + 9) % 2 == 0){
            $contenido_1[CLASES_A] = $class_ao = '';
            $contenido_1[CLASES_B] = $class_ap = 'even gradeC';
        }
        else{
            $contenido_1[CLASES_A] = $class_ao = 'even gradeC';
            $contenido_1[CLASES_B] = $class_ap = '';
        }
        foreach ($pagina->campos_activos as $key => $value) {
            if ($value[0] == '1'){                        
                if ($key == 'id_organizacion'){                
                    import('clases.organizacion.ArbolOrganizacional');
                    $arbol = new ArbolOrganizacional();
                    $contenido_1[ID_ORGANIZACIONES] = '<tr class="'. $class_ao .'">
                                <td>' . $pagina->nombres_columnas[id_organizacion] . '</td>';
                    $contenido_1[ID_ORGANIZACIONES] .= '<td>' . $arbol->BuscaOrganizacional($val) . '</td></tr>';
                   
                }

                else{

                    $contenido_1[ID_PROCESOS] = '<tr class="'. $class_ap .'"><td>' . $pagina->nombres_columnas[id_proceso] . '</td>';
                    $contenido_1[ID_PROCESOS] .= '<td>' . $pagina->BuscaProceso($val) . '<td></tr>';
                   
                }
            }   

        }         
        $contenido_1[CHECKED_ALTO_POTENCIAL] = $val["alto_potencial"] == 'S' ? 'checked="checked"' : '';
        $contenido_1['ORIGEN_HALLAZGO'] = ($val["origen"]);
        $contenido_1['FECHA_GENERACION'] = ($val["fecha_generacion"]);
        $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
        $contenido_1['ANALISIS_CAUSAL'] = ($val["analisis_causal"]);
        $contenido_1['RESPONSABLE_ANALISIS'] = $val["responsable_ana"];
        $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
        $contenido_1['ID_PROCESO'] = $val["id_proceso"];
        $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
        $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
        $contenido_1['ID_RESPONSABLE_SEGUI'] = $val["responsable_segui"];
        $contenido_1['ID'] = $val[id];
        
        import('clases.acciones_ac.AccionesAC');
	$acciones_ac = new AccionesAC();
        
        import('clases.acciones_evidencia.AccionesEvidencia');

	$evidencia = new AccionesEvidencia();        
        
        $acciones_ac->listarAccionesAC(array('b-id_ac'=>$val[id],'corder'=>'dias','sorder'=>'asc'), 1, 100);
        $data=$acciones_ac->dbl->data;
        $contenido_1[TABLA_ACCIONES] = '';
        foreach ($data as $value) {
            $contenido_1[TABLA_ACCIONES] .= '<tr>';
            //$contenido_1[TABLA_ACCIONES] .= '<td>'.$value[tipo].'&nbsp;</td>';
            $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[accion].'&nbsp;</td>';
            $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[fecha_acordada_a].'&nbsp;</td>';
            $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[fecha_realizada_a].'&nbsp;</td>';
            $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[responsable].'&nbsp;</td>';
            $contenido_1[TABLA_ACCIONES] .= '<td style="vertical-align: middle;" align="center">'.$acciones_ac->semaforo_estado($value, 'estado') .'&nbsp;</td>';
            $contenido_1[TABLA_ACCIONES] .= '</tr>';
            $contenido_1[TABLA_ACCIONES] .= '';
        }
        
        foreach ($data as $fila_aux) {
                                
                                
                                foreach($fila_aux as $key=>$value){
                                    switch ($value) {
                                        case 'Realizado':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            break;
                                        case 'Realizado con atraso':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            $plazo_atrasado = 1;
                                            break;
                                        case 'Plazo vencido':
                                            $num_factor_sema++;
                                            $plazo_vencido = 1;
                                            break;
                                        case 'En el plazo':
                                            $num_factor_sema++;
                                            $plazo_plazo = 1;
                                            break;

                                        default:
                                            //echo $value;
                                            break;
                                    }
                                }
                                if ($num_factor_sema>0)
                                    $total_semaforo_final = $total_semaforo_final + ($fila_aux[peso_especifico]/$num_factor_sema)*$sum_factor_sema;

                            }
                            switch ($val[sema_evi]) {
                                case 'Realizado':
                                    $num_factor_sema++;
                                    $sum_factor_sema++;
                                    break;
                                case 'Realizado con atraso':
                                    $num_factor_sema++;
                                    $sum_factor_sema++;
                                    $plazo_atrasado = 1;
                                    break;
                                case 'Plazo vencido':
                                    $num_factor_sema++;
                                    $plazo_vencido = 1;
                                    break;
                                case 'En el plazo':
                                    $num_factor_sema++;
                                    $plazo_plazo = 1;
                                    break;

                                default:
                                    //echo $value;
                                    break;
                            }
                            
                            //$valor = $total_semaforo_final;
                            if ($plazo_vencido >= 1){
                                $valor = '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>';
                            }
                            else if ($plazo_plazo >= 1){
                                $valor = '<img src="diseno/images/SemPlazo.png" title="En el plazo"/>';
                            }
                            else if ($plazo_atrasado >= 1){
                                $valor = '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>';
                            }
                            else if (strlen($data[0][id])<=0){
                                $valor = '<img src="diseno/images/atrasado.png" title="Sin Acciones Cargadas"/>';
                            }else{
                               
                                $valor = '<img src="diseno/images/realizo.png" title="Realizado"/>';
                            }                      
                            $contenido_1[ESTADO] = $valor;
        
        $contenido_1[TABLA_TRAZA] = '';
        foreach ($data as $value) {
            $contenido_1[TABLA_TRAZA] .= '<tr>';
            //$contenido_1[TABLA_TRAZA] .= '<td>'.$value[tipo].'&nbsp;</td>';
            $contenido_1[TABLA_TRAZA] .= '<td>'.$value[accion].'&nbsp;</td>';
            $evidencia->listarAccionesEvidencia(array('b-id_accion'=>$value[id],'corder'=>'fecha_evi', 'sorder'=>'desc'), 1, 1000);
            $data_evidencias = $evidencia->dbl->data;
            $html = "";
            foreach ($data_evidencias as $value_Evi) {
                $html .= "$value_Evi[observacion].<br/>"    ;
                $html .= $evidencia->archivo_descarga_pdf($value_Evi, 'nomb_archivo');
                $html .= "$value_Evi[id_persona], $value_Evi[fecha_evi_a]<br/><br/>"    ;
            }                                               
            $contenido_1[TABLA_TRAZA] .= '<td>'.$html.'&nbsp;</td>';
            $contenido_1[TABLA_TRAZA] .= '</tr>';
            $contenido_1[TABLA_TRAZA] .= '';
        }
        
        $evidencia->listarAccionesEvidencia(array('b-id_accion_correctiva'=>$val[id],'corder'=>'fecha_evi', 'sorder'=>'desc'), 1, 1000);
        $data_evidencias = $evidencia->dbl->data;
        $html = "";
        foreach ($data_evidencias as $value_Evi) {
            $html .= "$value_Evi[observacion].<br/>"    ;
            $html .= $evidencia->archivo_descarga_pdf($value_Evi, 'nomb_archivo');
            $html .= "$value_Evi[id_persona], $value_Evi[fecha_evi_a]<br/><br/>"    ;
        }
        $contenido_1[TRAZABILIDAD] = $html;
        
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
        $pdf->pdf_create_reporte($paginas, "Reporte_AC_" . $val["id"], false, 1, true, 0,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     


?>
