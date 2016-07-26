<?php
 import("clases.interfaz.Pagina");        
        class ArchivosAdjuntos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function ArchivosAdjuntos(){
                parent::__construct();
                //$this->asigna_script('items_formulario/items_formulario.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros(){
                $sql = "SELECT cod_parametro, espanol FROM mos_parametro WHERE cod_categoria = '3' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            private function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 22";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 22";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verEvidencia($atr){
                $atr = $this->dbl->corregir_parametros($atr);
                //$atr=array();
                $sql = "SELECT clave_foranea
                         ,id_md5
                         FROM mos_evidencias_temp 
                         WHERE clave_foranea = $atr[id] AND tok = $atr[token] AND id_usuario = $_SESSION[CookIdUsuario] "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarItemsFormulario($atr){
                try {
                    session_name("$GLOBALS[SESSION]");
                    session_start();
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "INSERT INTO mos_documentos_formulario_items_temp(fk_id_unico,descripcion,vigencia,estado,id_usuario,tok,descripcion_larga)
                            VALUES(
                                $_SESSION[fk_id_unico],'$atr[descripcion]','$atr[vigencia]',1,$_SESSION[CookIdUsuario],$atr[token],'$atr[descripcion_larga]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos_formulario_items_temp ' . $atr[descripcion_ano], 'mos_documentos_formulario_items_temp');
                      */
                    //$nuevo = "Fk Id Unico: \'$atr[fk_id_unico]\', Descripcion: \'$atr[descripcion]\', Vigencia: \'$atr[vigencia]\', Tipo: \'$atr[tipo]\', ";
                    //$this->registraTransaccionLog(18,$nuevo,'', '');
                    return "El Item '$atr[descripcion]' ha sido ingresado temporalmente con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = ''){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]')";            
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarItemsFormulario($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_documentos_formulario_items_temp SET                            
                                    descripcion = '$atr[descripcion]',descripcion_larga = '$atr[descripcion_larga]',vigencia = '$atr[vigencia]',estado = 2
                            WHERE  id = $atr[id]";      
                    //$val = $this->verItemsFormulario($atr[id]);
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_documentos_formulario_items_temp SET                            
                                    estado = 2
                            WHERE  id = $atr[id] AND estado <> 1";      
                    //$val = $this->verItemsFormulario($atr[id]);
                    $this->dbl->insert_update($sql);
                    //$nuevo = "Fk Id Unico: \'$atr[fk_id_unico]\', Descripcion: \'$atr[descripcion]\', Vigencia: \'$atr[vigencia]\', Tipo: \'$atr[tipo]\', ";
                    //$anterior = "Fk Id Unico: \'$val[fk_id_unico]\', Descripcion: \'$val[descripcion]\', Vigencia: \'$val[vigencia]\', Tipo: \'$val[tipo]\', ";
                    //$this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el ItemsFormulario ' . $atr[descripcion_ano], 'mos_documentos_formulario_items_temp');
                    */
                    return "El Items '$atr[descripcion]' ha sido actualizado temporalmente con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarItemsFormulario($atr, $pag, $registros_x_pagina){
                 
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    /* if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 1;                    
                    foreach ($this->parametros as $value) {
                        $sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                                inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                        where t1.cod_categoria='3' and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = p.cod_emp "; 
                        $sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }*/
//                    $sql = "SELECT COUNT(*) total_registros
//                         FROM mos_documentos_formulario_items_temp 
//                         WHERE 1 = 1 ";
//                    if (strlen($atr[valor])>0)
//                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
//                                 //if (strlen($atr["b-fk_id_unico"])>0)
//                        $sql .= " AND fk_id_unico = '". $_SESSION["fk_id_unico"] . "'";
//            if (strlen($atr["b-descripcion"])>0)
//                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
//            if (strlen($atr["b-vigencia"])>0)
//                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
//             if (strlen($atr["b-tipo"])>0)
//                        $sql .= " AND tipo = '". $atr["b-tipo"] . "'";
//
//                    $total_registros = $this->dbl->query($sql, $atr);
//                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                ,fk_id_unico
                                ,descripcion
                                ,descripcion_larga
                                ,vigencia
                                ,tipo

                                     $sql_col_left
                            FROM mos_documentos_formulario_items_temp $sql_left
                            WHERE estado <> 3 AND tok = $atr[token] and id_usuario = $_SESSION[CookIdUsuario]";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    //             if (strlen($atr["b-fk_id_unico"])>0)
                        $sql .= " AND fk_id_unico = ". $_SESSION["fk_id_unico"] . "";
            if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
             if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND tipo = '". $atr["b-tipo"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    //print_r($atr);
                    
                    $this->operacion($sql, $atr);
             }
             public function eliminarItemsFormulario($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verItemsFormulario($atr[id]);
                        if (strlen($val[fk_id_item])>0){
                            $sql = "SELECT COUNT(*) total_registros
                                                FROM mos_registro_item 
                                                WHERE valor = $val[fk_id_item] and id_unico = " . $val[fk_id_unico];    
                            //echo $sql;
                            $total_registros = $this->dbl->query($sql, $atr);
                            $total = $total_registros[0][total_registros];

                            if ($total+0 > 0){
                                //echo $total; 
                                return "- No se puede eliminar el Items, existen registros asociados.";
                            }                               
                        }
                        
                        $respuesta = $this->dbl->delete("mos_documentos_formulario_items_temp", "id = " . $atr[id] . " AND estado = 1 ");
                        $sql = "UPDATE mos_documentos_formulario_items_temp SET                            
                                    estado = 3
                            WHERE  id = $atr[id] "; 
                        //echo $sql;
                        $this->dbl->insert_update($sql);
                        
                        return "ha sido eliminada temporalmente con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaItemsFormulario($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = 1000;//getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $reg_por_pagina = 1000;
                $this->listarItemsFormulario($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                /*PARA cargar los items en el textarea de documentos*/
                if (isset($parametros[tok_item])){
                    $out['items'] = '';
                    foreach ($data as $value) {
                        $out['items'] .= $value[descripcion].'<br>';
                    }
                    $out['items'] = substr($out['items'], 0, strlen($out['items'])-4);
                }
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblItemsFormulario", "");
                $config_col=array(
                array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "id", $parametros)),    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fk_id_unico], "fk_id_unico", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
                    array( "width"=>"40%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion_larga], "descripcion_larga", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[tipo], "tipo", $parametros))
                );
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 6;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verItemsFormulario','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver ItemsFormulario'>"));
                */
                //if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarItemsFormulario','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar Items'></i>"));
                //if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarItemsFormulario','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar Items'></i>"));
               
                $config=array(array("width"=>"7%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
                        //case 2:
                        //case 3:
//                        case 4:
//                            array_push($config,$config_col[$i]);
//                            break;

                        default:
                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;
                            
                            break;
                    }
                }
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
//                {
//                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
//                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarItemsFormulario($parametros, 1, 100000);
            $data=$this->dbl->data;
            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

             $grid->SetConfiguracion("tblItemsFormulario", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fk_id_unico], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[tipo], ENT_QUOTES, "UTF-8"))
              );
                $columna_funcion =10;
           /* $grid->hidden = array(0 => true);
           if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  utf8_decode($value[espanol])));
                    $k++;
                }
                $columna_funcion =10;
                $config = array();            
                $array_columns =  explode('-', $parametros['mostrar-col']);            
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                            array_push($config,$config_col[$i]);
                            break;
                        default:                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;                            
                            break;
                    }
                }
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 /*crear_archivos_adjuntos_solo_html*/
            public function indexItemsFormulario($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                switch ($parametros[tipo]) {
                    case '1':
                        $tabla = 'mos_acciones_evidencia';
                        $fk = 'fk_id_trazabilidad';
                        $extensiones = 'jpg,png,pdf';
                        break;

                    default:
                        break;
                }
                /*SI EXISTE LA VARIABLE FK_ID, ESTA EDITANDO, POR LO TANTO SE CARGA EL VALOR*/
                if (isset($parametros[fk_id])){
                    $token = $parametros[token]*1 - $parametros[i];
                    $fk_valor = $parametros[fk_id];
                    //echo 1;
                }
                else{
                    /*NO EXISTE EL REGISTRO PADRE Y SE AGREGAN DE FORMA TEMPORAL LAS EVIDENCIAS*/
                    $fk_valor = null;
                    $token = $parametros[token]*1 + $parametros[i];
                }
                $array_nuevo = $this->crear_archivos_adjuntos_solo_html($tabla, $fk,$fk_valor,24,$extensiones,$token);
                //}
                //echo $array_nuevo[html];
                $html = '<div class="row">'.$array_nuevo[html].'</div>';
                $js .= $array_nuevo[js];
                
               
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$html);
                //$objResponse->addAssign('contenido',"innerHTML",$template->show());
                //$objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
                //$objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","items_formulario");
                $objResponse->addIncludeScript(PATH_TO_JS . 'items_formulario/items_formulario.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Trazabilidad');");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show'); ");
                $objResponse->addScript($js);
                return $objResponse;
            }
         
            public function indexItemsFormulario_old($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $parametros['b-fk_id_unico'] = $_SESSION[fk_id_unico] = $parametros[id];
                /*ID del campo padre*/
                $contenido['OTROS_CAMPOS']='<input type="hidden" id="tok_item" name="tok_item" value="'.$parametros[tok].'"/>';
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-4"; 
                if (isset($parametros[desc_larga])){
                    $parametros['mostrar-col']="2-3-4"; 
                }
                else{
                    $contenido[CSS_DESC_LARGA] = 'display:none';
                }
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    $parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                  <label for="SelectAcc" class="col-md-9 control-label">' . $value[espanol] . '</label>
                                  <div class="col-md-3">      
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                      </label>
                                  </div>
                            </div>';
                    $k++;
                }
                $grid = $this->verListaItemsFormulario($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_ItemsFormulario();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;ItemsFormulario';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['OPC'] = "new";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'items_formulario/';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }  
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
                } 
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'items_formulario/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'parametros_det/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                //$objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
                //$objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","items_formulario");
                $objResponse->addIncludeScript(PATH_TO_JS . 'items_formulario/items_formulario.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Items: $parametros[titulo]');");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show'); ");
                return $objResponse;
            }
 
            public function crear_archivos_adjuntos($tabla,$clave_foranea=null, $valor_clave_foranea=null,$valor_col=19, $extensiones='')
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $return = array();
                $token = time();
                $data_items = array();
                //echo $valor_clave_foranea;
                if (strlen($valor_clave_foranea)>0){
                    $sql = "INSERT INTO mos_evidencias_temp(nomb_archivo, contenttype, clave_foranea, id_usuario, tok, estado, id_md5)"
                                    . " SELECT nomb_archivo, contenttype, id, $_SESSION[CookIdUsuario],$token,0, '$tabla-$clave_foranea'"
                                    . " FROM $tabla"
                                    . " WHERE $clave_foranea = $valor_clave_foranea";
                    //echo $sql;
                            $this->dbl->insert_update($sql);
                            $sql = "SELECT 
                                    id,nomb_archivo, contenttype, (length(archivo))/(1024) tamano
                            FROM $tabla 
                            WHERE $clave_foranea = $valor_clave_foranea ";
                            //echo $sql;
                            $data_items = $this->dbl->query($sql);

                }
                if (count($data_items)>0){
                    $i = 1;
                    $html = '';
                    $js = '';
                    foreach ($data_items as $value) {
                        //$target = $value[contenttype] == 'application/pdf' ? 'target="_blank"' : 'data-gallery';
                        switch ($value[contenttype]) {
                            case 'image/jpeg':
                            case 'image/pjpeg':                
                            case 'image/png':
                            case 'image/x-png':                
                                $target = 'data-gallery';
                                break;

                            default:
                                $target = 'target="_blank"';
                                break;
                        }
                        $html .= '<tr id="tr-adj-' .$i. '">'; 
                        $html.= '<td align="center">'.
                                           ' ' .
                                      '  </td>';
                        $html.= '<td >'.
                                            '<a id="a-img-adj-'.$i.'" '. $target .' href="pages/evidencias/ver_evidencia.php?id='.$value[id].'&token='.$token.'" title="'.$value[nomb_archivo]. '" >'.
                                                $value[nomb_archivo].
                                            '</a>'.                                            
                                       '</td>';
                        $html.= '<td>' .
                                            number_format($value[tamano]). ' KB ' .
                                            
                                        '</td>';

                        $html.= '<td>' .
                                           '<i class="glyphicon glyphicon-trash cursor-pointer" href="'.$i. '" id="ico_trash_img_'.$i. '" tok="'.$value[id]. '"></i>'.
                                        '</td>';
                        $html.= '</tr>' ;  
                        
                        $js .= "$('#ico_trash_img_$i').click(function(e){ 
                                        e.preventDefault();
                                        var id = $(this).attr('href');
                                        $('tr-adj-$i').remove();
                                        var parent = $(this).parents().parents().get(0);
                                            $(parent).remove();
                                        var id = $(this).attr('tok');            
                                        array = new XArray();
                                        array.setObjeto('ArchivosAdjuntos','actualizar_creada');
                                        array.addParametro('tok',id);                        
                                        array.addParametro('token', $('#tok_new_edit').val());
                                        array.addParametro('import','clases.utilidades.ArchivosAdjuntos');
                                        xajax_Loading(array.getArray());
                                    }); ";
                        $i++;
                    }
                    $return[html] = '<div class="col-md-'.$valor_col.'">
                                            <input type="file" accept="image/jpeg image/png image/x-png" multiple id="fileUpload_adjuntos" name="fileUpload_adjuntos"/>
                                            <input type="hidden" id="num_items_adj" name="num_items" value="'.$i.'"/> 
                                            <input type="hidden" id="tok_new_edit" name="tok_new_edit" value="'.$token.'"/>
                                            <input type="hidden" id="extensiones" name="extensiones" value="'.$extensiones.'"/>    
                                            <br>    
                                            <table id="table-items-adj" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">                                                        
                                                <tbody>
                                                    ' . $html . '
                                                </tbody>
                                            </table>    
                                 </div>';
                    $return[js] = $js.'init_archivos_adjuntos();'; 
                }
                else
                {
                $return[html] = '<div class="col-md-'.$valor_col.'">
                                            <input type="file" accept="image/jpeg image/png image/x-png" multiple id="fileUpload_adjuntos" name="fileUpload_adjuntos"/>
                                            <input type="hidden" id="num_items_adj" name="num_items" value="0"/> 
                                            <input type="hidden" id="tok_new_edit" name="tok_new_edit" value="'.$token.'"/>
                                            <input type="hidden" id="extensiones" name="extensiones" value="'.$extensiones.'"/>    
                                            <br>    
                                            <table id="table-items-adj" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">                                                        
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>    
                                 </div>';
                $return[js] = 'init_archivos_adjuntos();'; 
                
                }
                return $return;
            }
            
            public function crear_archivos_adjuntos_solo_html($tabla,$clave_foranea=null, $valor_clave_foranea=null,$valor_col=19, $extensiones='',$token)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $return = array();
                //$token = time();
                $data_items = array();
                //echo $valor_clave_foranea;
                if (strlen($valor_clave_foranea)>0){
//                    $sql = "INSERT INTO mos_evidencias_temp(nomb_archivo, contenttype, clave_foranea, id_usuario, tok, estado, id_md5)"
//                                    . " SELECT nomb_archivo, contenttype, id, $_SESSION[CookIdUsuario],$token,0, '$tabla-$clave_foranea'"
//                                    . " FROM $tabla"
//                                    . " WHERE $clave_foranea = $valor_clave_foranea";
                    //echo $sql;
                            //$this->dbl->insert_update($sql);, (length(archivo))/(1024) tamano
                            $sql = "SELECT 
                                    id,nomb_archivo, contenttype, 1 tamano
                            FROM mos_evidencias_temp 
                            WHERE id_usuario = $_SESSION[CookIdUsuario] and tok = $token ";
                            //echo $sql;
                            $data_items = $this->dbl->query($sql);

                }
                if (count($data_items)>0){
                    $i = 1;
                    $html = '';
                    $js = '';
                    foreach ($data_items as $value) {
                        //$target = $value[contenttype] == 'application/pdf' ? 'target="_blank"' : 'data-gallery';
                        switch ($value[contenttype]) {
                            case 'image/jpeg':
                            case 'image/pjpeg':                
                            case 'image/png':
                            case 'image/x-png':                
                                $target = 'data-gallery';
                                break;

                            default:
                                $target = 'target="_blank"';
                                break;
                        }
                        $html .= '<tr id="tr-adj-' .$i. '">'; 
                        $html.= '<td align="center">'.
                                           ' ' .
                                      '  </td>';
                        $html.= '<td >'.
                                            '<a id="a-img-adj-'.$i.'" '. $target .' href="pages/evidencias/ver_evidencia.php?id='.$value[id].'&token='.$token.'" title="'.$value[nomb_archivo]. '" >'.
                                                $value[nomb_archivo].
                                            '</a>'.                                            
                                       '</td>';
                        $html.= '<td>' .
                                            number_format($value[tamano]). ' KB ' .
                                            
                                        '</td>';

                        $html.= '<td>' .
                                           '<i class="glyphicon glyphicon-trash cursor-pointer" href="'.$i. '" id="ico_trash_img_'.$i. '" tok="'.$value[id]. '"></i>'.
                                        '</td>';
                        $html.= '</tr>' ;  
                        
                        $js .= "$('#ico_trash_img_$i').click(function(e){ 
                                        e.preventDefault();
                                        var id = $(this).attr('href');
                                        $('tr-adj-$i').remove();
                                        var parent = $(this).parents().parents().get(0);
                                            $(parent).remove();
                                        var id = $(this).attr('tok');            
                                        array = new XArray();
                                        array.setObjeto('ArchivosAdjuntos','actualizar_creada');
                                        array.addParametro('tok',id);                        
                                        array.addParametro('token', $('#tok_new_edit').val());
                                        array.addParametro('import','clases.utilidades.ArchivosAdjuntos');
                                        xajax_Loading(array.getArray());
                                    }); ";
                        $i++;
                    }
                    $return[html] = '<div class="col-md-'.$valor_col.'">
                                            <input type="file" accept="image/jpeg image/png image/x-png" multiple id="fileUpload_adjuntos" name="fileUpload_adjuntos"/>
                                            <input type="hidden" id="num_items_adj" name="num_items" value="'.$i.'"/> 
                                            <input type="hidden" id="tok_new_edit" name="tok_new_edit" value="'.$token.'"/>
                                            <input type="hidden" id="extensiones" name="extensiones" value="'.$extensiones.'"/>    
                                            <br>    
                                            <table id="table-items-adj" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">                                                        
                                                <tbody>
                                                    ' . $html . '
                                                </tbody>
                                            </table>    
                                 </div>';
                    $return[js] = $js.'init_archivos_adjuntos();'; 
                }
                else
                {
                $return[html] = '<div class="col-md-'.$valor_col.'">
                                            <input type="file" accept="image/jpeg image/png image/x-png" multiple id="fileUpload_adjuntos" name="fileUpload_adjuntos"/>
                                            <input type="hidden" id="num_items_adj" name="num_items" value="0"/> 
                                            <input type="hidden" id="tok_new_edit" name="tok_new_edit" value="'.$token.'"/>
                                            <input type="hidden" id="extensiones" name="extensiones" value="'.$extensiones.'"/>    
                                            <br>    
                                            <table id="table-items-adj" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">                                                        
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>    
                                 </div>';
                $return[js] = 'init_archivos_adjuntos();'; 
                
                }
                return $return;
            }
            
            public function ingresar_archivos_adjuntos_temp($tabla,$clave_foranea=null, $valor_clave_foranea=null,$token)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                //$return = array();
                //$token = time();
                $data_items = array();
                //echo $valor_clave_foranea;
                if (strlen($valor_clave_foranea)>0){
                    $sql = "INSERT INTO mos_evidencias_temp(nomb_archivo, contenttype, clave_foranea, id_usuario, tok, estado, id_md5)"
                                    . " SELECT nomb_archivo, contenttype, id, $_SESSION[CookIdUsuario],$token,0, '$tabla-$clave_foranea'"
                                    . " FROM $tabla"
                                    . " WHERE $clave_foranea = $valor_clave_foranea";
                    //echo $sql;
                            $this->dbl->insert_update($sql);
//                            $sql = "SELECT 
//                                    id,nomb_archivo, contenttype, (length(archivo))/(1024) tamano
//                            FROM $tabla 
//                            WHERE $clave_foranea = $valor_clave_foranea ";
//                            //echo $sql;
//                            $data_items = $this->dbl->query($sql);

                }
                                                
                return $return;
            }
            
            public function visualizar_archivos_adjuntos($tabla,$clave_foranea=null, $valor_clave_foranea=null,$valor_col=19)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $return = array();
                $token = time();
                $data_items = array();
                //echo $valor_clave_foranea;
                if (strlen($valor_clave_foranea)>0){
                    $sql = "INSERT INTO mos_evidencias_temp(nomb_archivo, contenttype, clave_foranea, id_usuario, tok, estado, id_md5)"
                                    . " SELECT nomb_archivo, contenttype, id, $_SESSION[CookIdUsuario],$token,0, '$tabla-$clave_foranea'"
                                    . " FROM $tabla"
                                    . " WHERE $clave_foranea = $valor_clave_foranea";
                    //echo $sql;
                            $this->dbl->insert_update($sql);
                            $sql = "SELECT 
                                    id,nomb_archivo, contenttype, (length(archivo))/(1024) tamano
                            FROM $tabla 
                            WHERE $clave_foranea = $valor_clave_foranea ";
                            //echo $sql;
                            $data_items = $this->dbl->query($sql);

                }
                if (count($data_items)>0){
                    $i = 1;
                    $html = '';
                    $js = '';
                    foreach ($data_items as $value) {
                        $target = $value[contenttype] == 'application/pdf' ? 'target="_blank"' : 'data-gallery';
                        switch ($value[contenttype]) {
                            case 'image/jpeg':
                            case 'image/pjpeg':                
                            case 'image/png':
                            case 'image/x-png':                
                                $target = 'data-gallery';
                                break;

                            default:
                                $target = 'target="_blank"';
                                break;
                        }
                        $html .= '<tr id="tr-esp-' .$i. '">'; 
                        $html.= '<td align="center">'.
                                           ' ' .
                                      '  </td>';
                        $html.= '<td >'.
                                            '<a id="a-img-'.$i.'" '. $target .' href="pages/evidencias/ver_evidencia.php?id='.$value[id].'&token='.$token.'" title="'.$value[nomb_archivo]. '" >'.
                                                $value[nomb_archivo].
                                            '</a>'.                                            
                                       '</td>';
                        $html.= '<td>' .
                                            number_format($value[tamano]). ' KB ' .
                                            
                                        '</td>';

                        $html.= '<td>' .'<a id="a-img-'.$i.'" target="_blank" href="pages/evidencias/ver_evidencia.php?id='.$value[id].'&token='.$token.'&des=1" title="'.$value[nomb_archivo]. '" >'.
                                           '<i class="icon icon-download cursor-pointer" href="'.$i. '" id="ico_trash_img_'.$i. '" tok="'.$value[id]. '"></i>'.
                                '</a>'.    
                                        '</td>';
                        $html.= '</tr>' ;  
                        $return[html] = '
                                            <table id="table-items-esp-vis" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">                                                        
                                                <tbody>
                                                    ' . $html . '
                                                </tbody>
                                            </table>    
                                 </div>';
//                        $js .= "$('#ico_trash_img_$i').click(function(e){ 
//                                        e.preventDefault();
//                                        var id = $(this).attr('href');
//                                        $('tr-esp-$i').remove();
//                                        var parent = $(this).parents().parents().get(0);
//                                            $(parent).remove();
//                                        var id = $(this).attr('tok');            
//                                        array = new XArray();
//                                        array.setObjeto('ArchivosAdjuntos','actualizar_creada');
//                                        array.addParametro('tok',id);                        
//                                        array.addParametro('token', $('#tok_new_edit').val());
//                                        array.addParametro('import','clases.utilidades.ArchivosAdjuntos');
//                                        xajax_Loading(array.getArray());
//                                    }); ";
                        $i++;
                    }
                    //$return[js] = $js.'init_archivos_adjuntos();'; 
                }
                else
                {
                $return[html] = '';
                //$return[js] = 'init_archivos_adjuntos();'; 
                
                }
                return $return;
            }
     
 
            public function guardar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $sql = "SELECT * FROM mos_evidencias_temp WHERE tok = '$parametros[tok_new_edit]' AND id_usuario = $_SESSION[CookIdUsuario]"
                                . " AND  estado = 1 ";
                //echo $sql;
                $data_evi = $this->dbl->query($sql);
                foreach ($data_evi as $value_evi) {       
                    if ((file_exists(APPLICATION_DOWNLOADS. 'temp/' . $value_evi[id_md5]))) {
                        $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . $value_evi[id_md5]);
                        $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . $value_evi[id_md5], "rb");
                        $archivo = fread($fp, $tamanio);
                        $archivo = addslashes($archivo);
                        fclose($fp);
                        
                        $sql = "INSERT INTO $parametros[tabla](nomb_archivo,archivo, contenttype,$parametros[clave_foranea])"
                                . " VALUES('$value_evi[nomb_archivo]','$archivo','$value_evi[contenttype]',$parametros[valor_clave_foranea])";
                        $this->dbl->insert_update($sql);
                        unlink(APPLICATION_DOWNLOADS. 'temp/' . $value_evi[id_md5]);
                    }
                }                
                $sql = 'delete from '.$parametros[tabla].' 
                        where id in (select clave_foranea from mos_evidencias_temp 
                        where id_usuario = ' . $_SESSION['CookIdUsuario'] . ' and tok = ' . $parametros[tok_new_edit] . ' 
                            and estado = 3)
                        ';
                //echo $sql;
                $this->dbl->insert_update($sql);
                
            }
            
            public function contar_evidencias($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $sql = "SELECT COUNT(*) total_registros FROM mos_evidencias_temp WHERE tok = '$parametros[tok_new_edit]' AND id_usuario = $_SESSION[CookIdUsuario]"
                                . " AND  estado IN (1,0) ";
                //echo $sql;
                $data_evi = $this->dbl->query($sql);
                return $data_evi[0][total_registros];   
                
                
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verItemsFormulario($parametros[id]); 

                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido_1["N_" . strtoupper($key)] =  $value;
                }                
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido_1["P_" . strtoupper($key)] =  $value;
                }    
                            $contenido_1['FK_ID_UNICO'] = $val["fk_id_unico"];
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['TIPO'] = $val["tipo"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'items_formulario/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;ItemsFormulario";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ItemsFormulario";
                $contenido['PAGINA_VOLVER'] = "listarItemsFormulario.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addScript("$('#hv-descripcion').val('$val[descripcion]');");
                $objResponse->addScript("$('#hv-descripcion_larga').html('$val[descripcion_larga]');");
                if ($val[vigencia] =='S' )
                    $objResponse->addScript('$("#hv-vigencia").prop("checked", true); ');
                else
                    $objResponse->addScript('$("#hv-vigencia").prop("checked", false); ');
                $objResponse->addScript("$('#id-hv').val('$parametros[id]');");
                $objResponse->addScript("$('#opc-hv').val('upd');");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('.nav-tabs a[href=\"#hv-red\"]').tab('show');");
//                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
//                $objResponse->addScriptCall("calcHeight");
//                $objResponse->addScriptCall("MostrarContenido2");          
//                $objResponse->addScript("$('#MustraCargando').hide();");
//                $objResponse->addScript("$.validate({
//                            lang: 'es'  
//                          });");  
                return $objResponse;
            }
     
 
            public function actualizar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                $sql = "select * from mos_evidencias_temp 
                        where id_usuario = " . $_SESSION['CookIdUsuario'] . " and tok = '" . $parametros[token] . "' 
                            and id = $parametros[tok]";
                //echo $sql;
                $val = $this->dbl->query($sql);
                if (count($val)>0){
                    $val = $val[0];
                    $sql = "UPDATE mos_evidencias_temp SET estado = 3 
                        where id_usuario = " . $_SESSION['CookIdUsuario'] . " and tok = '" . $parametros[token] . "'
                            and id = $parametros[tok]";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    if (strlen($val[id_md5])>0){
                        if ((file_exists(APPLICATION_DOWNLOADS. 'temp/' . $value[id_md5]))){
                             unlink(APPLICATION_DOWNLOADS. 'temp/' . $val[id_md5]);
                        }
                    }
                }
                
                
                return $objResponse;
            }
            
            public function actualizar_creada($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                $sql = "select * from mos_evidencias_temp 
                        where id_usuario = " . $_SESSION['CookIdUsuario'] . " and tok = '" . $parametros[token] . "' 
                            and clave_foranea = $parametros[tok]";
                //echo $sql;
                $val = $this->dbl->query($sql);
                if (count($val)>0){
                    $val = $val[0];
                    $sql = "UPDATE mos_evidencias_temp SET estado = 3 
                        where id_usuario = " . $_SESSION['CookIdUsuario'] . " and tok = '" . $parametros[token] . "'
                            and clave_foranea = $parametros[tok]";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    if (strlen($val[id_md5])>0){
                        if ((file_exists(APPLICATION_DOWNLOADS. 'temp/' . $val[id_md5]))){
                             unlink(APPLICATION_DOWNLOADS. 'temp/' . $val[id_md5]);
                        }
                    }
                }
                
                
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                
                $respuesta = $this->eliminarItemsFormulario($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada temporalmente con exito/",$respuesta ) == true) {
                    //$objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    $objResponse->addScript("r_verPagina(1,1);");
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
            public function buscar($parametros)
            {
                $grid = $this->verListaItemsFormulario($parametros);  
                //print_r($parametros);
                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid-hv',"innerHTML",$grid[tabla]);
                /*Muestra los Items en el formulario de documentos*/
//                $objResponse->addAssign('valores_din_'.$parametros[tok_item],"innerHTML",$grid[items]);
                $objResponse->addScript('$("#valores_din_'.$parametros[tok_item].'").val("'.$grid[items].'")');                     
                $objResponse->addScript("ajustar_valor_atributo_dinamico($parametros[tok_item])");
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verItemsFormulario($parametros[id]);

                            $contenido_1['FK_ID_UNICO'] = $val["fk_id_unico"];
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['TIPO'] = $val["tipo"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'items_formulario/';
                $template->setTemplate("verItemsFormulario");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la ItemsFormulario";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>