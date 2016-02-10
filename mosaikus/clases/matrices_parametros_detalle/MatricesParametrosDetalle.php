<?php
 import("clases.interfaz.Pagina");        
        class MatricesParametrosDetalle extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function MatricesParametrosDetalle(){
                parent::__construct();
                $this->asigna_script('matrices_parametros_detalle/matrices_parametros_detalle.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 14";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 14";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verMatricesParametrosDetalle($id){
                 session_name("$GLOBALS[SESSION]");
                session_start();
                $atr=array();
                $sql = "SELECT cod_categoria
                                ,id_cmb_acap
                                ,id_item
                                ,nombre
                                ,codigo
                                ,vigencia
                                ,factor

                         FROM mos_matrices_parametros_detalle 
                         WHERE id_item = $id AND id_cmb_acap = $_SESSION[id_cmb_acap] AND cod_categoria = 8"; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT max(id_item) total_registros
                         FROM mos_matrices_parametros_detalle";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarMatricesParametrosDetalle($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[id_item] = $this->codigo_siguiente();
                    $atr[vigencia] = 'S';
                    $atr[factor] = 'NULL';
                    $sql = "INSERT INTO mos_matrices_parametros_detalle(cod_categoria,id_cmb_acap,id_item,nombre,codigo,vigencia,factor)
                            VALUES(
                                8,$_SESSION[id_cmb_acap],$atr[id_item],'$atr[nombre]','$atr[id_item]','$atr[vigencia]',$atr[factor]
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_matrices_parametros_detalle ' . $atr[descripcion_ano], 'mos_matrices_parametros_detalle');
                      */
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Cmb Acap: \'$atr[id_cmb_acap]\', Id Item: \'$atr[id_item]\', Nombre: \'$atr[nombre]\', Codigo: \'$atr[codigo]\', Vigencia: \'$atr[vigencia]\', Factor: \'$atr[factor]\', ";
                    $this->registraTransaccionLog(71,$nuevo,'', '');
                    return "El Item '$atr[nombre]' ha sido ingresado con exito";
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

            public function modificarMatricesParametrosDetalle($atr){
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_matrices_parametros_detalle SET                            
                                    nombre = '$atr[nombre]'
                            WHERE  cod_categoria = 8 AND id_cmb_acap = $_SESSION[id_cmb_acap] AND  id_item = $atr[id]";      
                    $val = $this->verMatricesParametrosDetalle($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Cmb Acap: \'$atr[id_cmb_acap]\', Id Item: \'$atr[id_item]\', Nombre: \'$atr[nombre]\', Codigo: \'$atr[codigo]\', Vigencia: \'$atr[vigencia]\', Factor: \'$atr[factor]\', ";
                    $anterior = "Cod Categoria: \'$val[cod_categoria]\', Id Cmb Acap: \'$val[id_cmb_acap]\', Id Item: \'$val[id_item]\', Nombre: \'$val[nombre]\', Codigo: \'$val[codigo]\', Vigencia: \'$val[vigencia]\', Factor: \'$val[factor]\', ";
                    $this->registraTransaccionLog(72,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el MatricesParametrosDetalle ' . $atr[descripcion_ano], 'mos_matrices_parametros_detalle');
                    */
                    return "El Item '$atr[nombre]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarMatricesParametrosDetalle($atr, $pag, $registros_x_pagina){
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
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_matrices_parametros_detalle 
                         WHERE id_cmb_acap = $_SESSION[id_cmb_acap] AND cod_categoria  = 8 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-id_cmb_acap"])>0)
                        $sql .= " AND id_cmb_acap = '". $atr["b-id_cmb_acap"] . "'";
             if (strlen($atr["b-id_item"])>0)
                        $sql .= " AND id_item = '". $atr["b-id_item"] . "'";
            if (strlen($atr["b-nombre"])>0)
                        $sql .= " AND upper(nombre) like '%" . strtoupper($atr["b-nombre"]) . "%'";
            if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
             if (strlen($atr["b-factor"])>0)
                        $sql .= " AND factor = '". $atr["b-factor"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT 
                                id_item
                                ,cod_categoria
                                ,id_cmb_acap                                
                                ,nombre
                                ,codigo
                                ,vigencia
                                ,factor

                                     $sql_col_left
                            FROM mos_matrices_parametros_detalle $sql_left
                            WHERE id_cmb_acap = $_SESSION[id_cmb_acap] AND cod_categoria  = 8 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-id_cmb_acap"])>0)
                        $sql .= " AND id_cmb_acap = '". $atr["b-id_cmb_acap"] . "'";
             if (strlen($atr["b-id_item"])>0)
                        $sql .= " AND id_item = '". $atr["b-id_item"] . "'";
            if (strlen($atr["b-nombre"])>0)
                        $sql .= " AND upper(nombre) like '%" . strtoupper($atr["b-nombre"]) . "%'";
            if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
             if (strlen($atr["b-factor"])>0)
                        $sql .= " AND factor = '". $atr["b-factor"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             public function eliminarMatricesParametrosDetalle($atr){
                    try {
                        session_name("$GLOBALS[SESSION]");
                        session_start();
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                    FROM mos_matrices_detalle 
                                WHERE cod_categoria = 8 AND id_cmb_acap = $_SESSION[id_cmb_acap] AND id_item = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene accion(es) correctiva(s) asociada(s).";
                        }
                        
                        $sql = "SELECT COUNT(*) total_registros
                                    FROM mos_matrices_control_detalle 
                                WHERE cod_categoria = 8 AND id_cmb_acap = $_SESSION[id_cmb_acap] AND id_item = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene accion(es) asociada(s).";
                        }
                        $val = $this->verMatricesParametrosDetalle($atr[id]);
                        $respuesta = $this->dbl->delete("mos_matrices_parametros_detalle", "cod_categoria = 8 AND id_cmb_acap = $_SESSION[id_cmb_acap] AND id_item = " . $atr[id]);
                        $nuevo = "Cod Categoria: \'$val[cod_categoria]\', Id Cmb Acap: \'$val[id_cmb_acap]\', Id Item: \'$val[id_item]\', Nombre: \'$val[nombre]\'";
                        $this->registraTransaccionLog(73,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaMatricesParametrosDetalle($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarMatricesParametrosDetalle($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblMatricesParametrosDetalle", "");
                $config_col=array(
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_item], "id_item", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_categoria], "cod_categoria", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_cmb_acap], "id_cmb_acap", $parametros)),
               
               array( "width"=>"80%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombre], "nombre", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[codigo], "codigo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[factor], "factor", $parametros))
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
                    
                    $columna_funcion = 8;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verMatricesParametrosDetalle','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver MatricesParametrosDetalle'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarMatricesParametrosDetalle','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar MatricesParametrosDetalle'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarMatricesParametrosDetalle','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar MatricesParametrosDetalle'>"));
               
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
//                        case 2:
//                        case 3:
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
                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarMatricesParametrosDetalle($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblMatricesParametrosDetalle", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_categoria], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_cmb_acap], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_item], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nombre], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[codigo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[factor], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexMatricesParametrosDetalle($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $_SESSION[id_cmb_acap] = $parametros[id];                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="nombre";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="3"; 
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
                $grid = $this->verListaMatricesParametrosDetalle($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_MatricesParametrosDetalle();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;MatricesParametrosDetalle';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['OPC'] = "new";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros_detalle/';
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
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros_detalle/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros_detalle/';

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
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","matrices_parametros_detalle");
                $objResponse->addIncludeScript(PATH_TO_JS . 'matrices_parametros_detalle/matrices_parametros_detalle.js');
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('$parametros[nombre] - Items');");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show'); ");
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
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
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros_detalle/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;MatricesParametrosDetalle";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;MatricesParametrosDetalle";
                $contenido['PAGINA_VOLVER'] = "listarMatricesParametrosDetalle.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");   return $objResponse;
            }
     
 
            public function guardar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                unset ($parametros['id']);
                $parametros['id_usuario']= $_SESSION['USERID'];

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    
                    $respuesta = $this->ingresarMatricesParametrosDetalle($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        //$objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).val('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verMatricesParametrosDetalle($parametros[id]); 

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
                $objResponse = new xajaxResponse();
                $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
                $contenido_1['ID_CMB_ACAP'] = $val["id_cmb_acap"];
                $contenido_1['ID_ITEM'] = $val["id_item"];
                $contenido_1['NOMBRE'] = ($val["nombre"]);
                $contenido_1['CODIGO'] = ($val["codigo"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1['FACTOR'] = $val["factor"];

//                $template = new Template();
//                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros_detalle/';
//                $template->setTemplate("formulario");
//                $template->setVars($contenido_1);
//
//                $contenido['CAMPOS'] = $template->show();
//
//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;MatricesParametrosDetalle";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;MatricesParametrosDetalle";
                $contenido['PAGINA_VOLVER'] = "listarMatricesParametrosDetalle.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];
                $objResponse->addScript("$('#hv-nombre').val('$val[nombre]');");
                
                $objResponse->addScript("$('#id-hv').val('$parametros[id]');");
                $objResponse->addScript("$('#opc-hv').val('upd');");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('.nav-tabs a[href=\"#hv-red\"]').tab('show');");

//                $template->setVars($contenido);
                
//                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
//                $objResponse->addScriptCall("calcHeight");
//                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  
                return $objResponse;
            }
     
 
            public function actualizar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                $parametros['id_usuario']= $_SESSION['USERID'];

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    
                    $respuesta = $this->modificarMatricesParametrosDetalle($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        //$objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).val('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verMatricesParametrosDetalle($parametros[id]);
                $respuesta = $this->eliminarMatricesParametrosDetalle($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    //$objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScript("verPagina_hv(1,1);");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
                public function buscar($parametros)
            {
                $grid = $this->verListaMatricesParametrosDetalle($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid-hv',"innerHTML",$grid[tabla]);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verMatricesParametrosDetalle($parametros[id]);

                $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
                $contenido_1['ID_CMB_ACAP'] = $val["id_cmb_acap"];
                $contenido_1['ID_ITEM'] = $val["id_item"];
                $contenido_1['NOMBRE'] = ($val["nombre"]);
                $contenido_1['CODIGO'] = ($val["codigo"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1['FACTOR'] = $val["factor"];



                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros_detalle/';
                $template->setTemplate("verMatricesParametrosDetalle");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la MatricesParametrosDetalle";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>