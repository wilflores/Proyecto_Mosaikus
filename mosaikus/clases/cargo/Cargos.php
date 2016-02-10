<?php
 import("clases.interfaz.Pagina");        
        class Cargos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        private $campos_activos;
            
            public function Cargos(){
                parent::__construct();
                $this->asigna_script('cargo/cargo.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = $this->campos_activos = array();
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 3";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_campos_activos(){
                $sql = "SELECT campo, activo, orden FROM mos_campos_activos WHERE modulo = 1";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->campos_activos[$value[campo]] = array($value[activo],$value[orden]);
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 1";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }
     

             public function verCargos($id){
                $atr=array();
                $sql = "SELECT cod_cargo
                    ,descripcion
                    ,observacion
                    ,interno
                    ,vigencia

                         FROM mos_cargo 
                         WHERE cod_cargo = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_cargo) total_registros
                         FROM mos_cargo";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function verCargosArbol($id){
                $atr=array();
                $sql = "SELECT cod_cargo
                            ,id

                         FROM mos_cargo_estrorg_arbolproc 
                         WHERE cod_cargo = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            public function ingresarCargosArbol($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    //$atr[cod_cargo] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_cargo_estrorg_arbolproc(cod_cargo,id,aplica_subnivel)
                            VALUES(
                                $atr[cod_cargo],$atr[id],0
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_cargo ' . $atr[descripcion_ano], 'mos_cargo');
                      */
                    //$nuevo = "Cod Cargo: \'$atr[cod_cargo]\', Descripcion: \'$atr[descripcion]\', Observacion: \'$atr[observacion]\', Interno: \'$atr[interno]\', Vigencia: \'$atr[vigencia]\', ";
                    //$this->registraTransaccionLog(38,$nuevo,'', '');
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarCargos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[cod_cargo] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_cargo(cod_cargo,descripcion,observacion,interno,vigencia)
                            VALUES(
                                $atr[cod_cargo],'$atr[descripcion]','$atr[observacion]','$atr[interno]','$atr[vigencia]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_cargo ' . $atr[descripcion_ano], 'mos_cargo');
                      */
                    $nuevo = "Cod Cargo: \'$atr[cod_cargo]\', Descripcion: \'$atr[descripcion]\', Observacion: \'$atr[observacion]\', Interno: \'$atr[interno]\', Vigencia: \'$atr[vigencia]\', ";
                    $this->registraTransaccionLog(38,$nuevo,'', '');
                    return $atr[cod_cargo];
                    //return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
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

            public function modificarCargos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_cargo SET                            
                                    descripcion = '$atr[descripcion]',observacion = '$atr[observacion]',interno = '$atr[interno]',vigencia = '$atr[vigencia]'
                            WHERE  cod_cargo = $atr[id]";      
                    $val = $this->verCargos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Cargo: \'$atr[cod_cargo]\', Descripcion: \'$atr[descripcion]\', Observacion: \'$atr[observacion]\', Interno: \'$atr[interno]\', Vigencia: \'$atr[vigencia]\', ";
                    $anterior = "Cod Cargo: \'$val[cod_cargo]\', Descripcion: \'$val[descripcion]\', Observacion: \'$val[observacion]\', Interno: \'$val[interno]\', Vigencia: \'$val[vigencia]\', ";
                    $this->registraTransaccionLog(39,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Cargos ' . $atr[descripcion_ano], 'mos_cargo');
                    */
                    return "El Cargo '$atr[descripcion]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarCargos($atr, $pag, $registros_x_pagina){
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
                         FROM mos_cargo 
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_cargo"])>0)
                        $sql .= " AND cod_cargo = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND observacion = '". $atr[b-observacion] . "'";
                    if (strlen($atr["b-interno"])>0)
                        $sql .= " AND upper(interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT cod_cargo
                                    ,descripcion
                                    ,observacion
                                    ,CASE interno WHEN 1 THEN 'S' ELSE 'N' END interno
                                    ,vigencia

                                     $sql_col_left
                            FROM mos_cargo $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_cargo"])>0)
                        $sql .= " AND cod_cargo = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND observacion = '". $atr[b-observacion] . "'";
                    if (strlen($atr["b-interno"])>0)
                        $sql .= " AND upper(interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
            }
             
            public function eliminarCargos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_personal 
                                        WHERE cod_cargo = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];  
                        if ($total > 0){
                            return "- No se Puede Eliminar Cargo, Existen Personas Asociadas";
                        }                        
                        
                        $respuesta = $this->dbl->delete("mos_cargo_estrorg_arbolproc", "cod_cargo = $atr[id]");
                        $respuesta = $this->dbl->delete("mos_cargo", "cod_cargo = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
             
             public function eliminarCargosArbol($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_cargo_estrorg_arbolproc", "cod_cargo = $atr[id]");
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaCargos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarCargos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }                

                $grid->SetConfiguracionMSKS("tblCargos", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Cod Cargo", "cod_cargo", $parametros)),
               array( "width"=>"60%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Observaci&oacute;n", "observacion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[interno], "interno", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros))
                );
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(utf8_decode($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 6;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verCargos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Cargos'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarCargos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-edit\" title='Editar Cargos'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarCargos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar Cargos'></i>"));
               
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
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarCargos($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblCargos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>"Cod Cargo"),
         array( "width"=>"10%","ValorEtiqueta"=>"Descripci&oacute;n"),
         array( "width"=>"10%","ValorEtiqueta"=>"Observacion"),
         array( "width"=>"10%","ValorEtiqueta"=>"Interno"),
         array( "width"=>"10%","ValorEtiqueta"=>"Vigencia")
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
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexCargos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-3-4-5-6"; 
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
                $grid = $this->verListaCargos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Cargos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Cargos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
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
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","cargo");
                $objResponse->addIncludeScript(PATH_TO_JS . 'cargo/cargo.js?'. rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
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
                $contenido_1[CHECKED_INTERNO] = 'checked="checked"';
                $contenido_1[CHECKED_VIGENCIA] = 'checked="checked"';
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
                $template->setTemplate("formulario");
                
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Cargos";
                $contenido['PAGINA_VOLVER'] = "listarCargos.php";
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
                          });");   
                $objResponse->addScript('$("#id_pais").combobox({
                                            select: function( event, ui ) {alert(123);}
                                            }
                                );');
                return $objResponse;
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
                    if (!isset($parametros[interno])) $parametros[interno] = 0;
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->ingresarCargos($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {    
                        $arr = explode(",", $parametros[nodos]);
                        $params[cod_cargo] = $respuesta;
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarCargosArbol($params);
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',"El Cargo '$parametros[descripcion]' ha sido ingresado con exito");
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
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
                $val = $this->verCargos($parametros[id]); 

                $contenido_1['COD_CARGO'] = $val["cod_cargo"];
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['OBSERVACION'] = $val["observacion"];
                $contenido_1['INTERNO'] = ($val["interno"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                //$contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1[CHECKED_VIGENCIA] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
                //$contenido_1['INTERNO'] = ($val["interno"]);
                $contenido_1[CHECKED_INTERNO] = $val["interno"] == '1' ? 'checked="checked"' : '';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Cargos";
                $contenido['PAGINA_VOLVER'] = "listarCargos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["cod_cargo"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  return $objResponse;
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
                    if (!isset($parametros[interno])) $parametros[interno] = 0;
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->modificarCargos($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $arr = explode(",", $parametros[nodos]);
                        $params[cod_cargo] = $parametros[id];
                        $this->eliminarCargosArbol($parametros);
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarCargosArbol($params);
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                //$val = $this->verCargos($parametros[id]);
                $respuesta = $this->eliminarCargos($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
            public function buscar($parametros)
            {
                $grid = $this->verListaCargos($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verCargos($parametros[id]);

                            $contenido_1['COD_CARGO'] = $val["cod_cargo"];
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['OBSERVACION'] = $val["observacion"];
            $contenido_1['INTERNO'] = ($val["interno"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
                $template->setTemplate("verCargos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Cargos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>