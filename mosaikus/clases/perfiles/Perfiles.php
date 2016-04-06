<?php
 import("clases.interfaz.Pagina");        
        class Perfiles extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function Perfiles(){
                parent::__construct();
                $this->asigna_script('perfiles/perfiles.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 19";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }                
            }

            public function verMenuArbol($id){                                
                $atr=array();
                $sql = "SELECT cod_perfil,cod_link
                         FROM mos_link_por_perfil 
                         WHERE cod_perfil = $id ";                
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            public function ingresarMenuArbol($atr){
                try {                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "INSERT INTO mos_link_por_perfil(cod_perfil,cod_link)
                            VALUES(
                                $atr[cod_perfil],$atr[id]
                                )";
                    $this->dbl->insert_update($sql);
                    return "El Perfil '$atr[cod_perfil]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function eliminarMenuArbol($atr){
                    try {                        
                        $respuesta = $this->dbl->delete("mos_link_por_perfil", "cod_perfil = $atr[cod_perfil]");
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
            
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 19";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }

             public function verPerfiles($id){                 
                $atr=array();
                $sql = "SELECT cod_perfil
                            ,descripcion_perfil,nuevo,modificar,eliminar,recordatorio,modificar_terceros, visualizar_terceros
                         FROM mos_perfil 
                         WHERE cod_perfil = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }

             public function verPerfilesPortal($id){                 
                $atr=array();
                $sql = "SELECT cod_perfil
                            ,descripcion_perfil,nuevo,modificar,eliminar,recordatorio,modificar_terceros, visualizar_terceros
                         FROM mos_perfil_portal 
                         WHERE cod_perfil = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }

            
            public function ingresarPerfiles($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "INSERT INTO mos_perfil(descripcion_perfil,nuevo, modificar,eliminar, recordatorio,modificar_terceros, visualizar_terceros)
                            VALUES(
                                '$atr[descripcion_perfil]'
                                ,'$atr[nuevo]'
                                ,'$atr[modificar]'
                                ,'$atr[eliminar]'
                                ,'$atr[recordatorio]'
                                ,'$atr[modificar_terceros]'
                                ,'$atr[visualizar_terceros]'                                    
                                )";
                    $this->dbl->insert_update($sql);
                    $nuevo = "Descripcion Perfil : \'$atr[descripcion_perfil]\', Nuevo: \'$atr[nuevo]\', Modificar: \'$atr[modificar]\', Eliminar: \'$atr[eliminar]\', Recordatorio: \'$atr[recordatorio]\', ";
                    $this->registraTransaccionLog(19,$nuevo,'', '');
                    return "El Perfil '$atr[descripcion_perfil]' ha sido ingresado con exito";
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

            public function modificarPerfiles($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_perfil SET                            
                                    descripcion_perfil = '$atr[descripcion_perfil]'
                                    ,nuevo  = '$atr[nuevo]'
                                    ,modificar  = '$atr[modificar]'
                                    ,eliminar  = '$atr[eliminar]'
                                    ,recordatorio  = '$atr[recordatorio]'
                                    ,modificar_terceros = '$atr[modificar_terceros]'
                                    ,visualizar_terceros = '$atr[visualizar_terceros]'    
                            WHERE  cod_perfil = $atr[cod_perfil]";      
                    $val = $this->verPerfiles($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Perfil: \'$atr[cod_perfil]\', Descripcion Perfil: \'$atr[descripcion_perfil]\',  ";
                    $anterior = "Cod Perfil: \'$val[cod_perfil]\', Descripcion Perfil: \'$val[descripcion_perfil]\',  ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    return "El Perfil '$atr[descripcion_perfil]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarPerfiles($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_perfil 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_perfil"])>0)
                        $sql .= " AND cod_perfil = '". $atr["b-cod_perfil"] . "'";
                        if (strlen($atr["b-descripcion_perfil"])>0)
                            $sql .= " AND upper(descripcion_perfil) like '%" . strtoupper($atr["b-descripcion_perfil"]) . "%'";
                        if (strlen($atr["b-orden"])>0)
                            $sql .= " AND orden = '". $atr["b-orden"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT cod_perfil,descripcion_perfil, nuevo, modificar, eliminar, recordatorio, modificar_terceros, visualizar_terceros
                                     $sql_col_left
                            FROM mos_perfil $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_perfil"])>0)
                        $sql .= " AND cod_perfil = '". $atr["b-cod_perfil"] . "'";
            if (strlen($atr["b-descripcion_perfil"])>0)
                        $sql .= " AND upper(descripcion_perfil) like '%" . strtoupper($atr["b-descripcion_perfil"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             public function eliminarPerfiles($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_perfil", "cod_perfil = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaPerfiles($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarPerfiles($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblPerfiles", "");
                $config_col=array(
                    
               array( "width"=>"0%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_perfil], "cod_perfil", $parametros)),
               array( "width"=>"40%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion_perfil], "descripcion_perfil", $parametros)),
               array( "width"=>"8%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nuevo], "nuevo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[modificar], "modificar", $parametros)),                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[eliminar], "eliminar", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[recordatorio], "recordatorio", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[modificar_terceros], "modificar_terceros", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[visualizar_terceros], "visualizar_terceros", $parametros)),
                    
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
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarPerfiles','imagen'=> "<i style='cursor:pointer' class=\"icon icon-edit\" title='Editar Perfiles'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarPerfiles','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar Perfiles'></i>"));
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'configurarPerfiles','imagen'=> "<i style='cursor:pointer' class=\"icon icon-more\" title='Configurar Perfiles'></i>"));
               
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
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                //}
                return $out;
            }
     
 
        public function exportarExcel($parametros){
            $grid= new DataGrid();
            $this->listarPerfiles($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblPerfiles", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_perfil], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion_perfil], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[orden], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexPerfiles($parametros)
            {
                //print_r($parametros);
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_perfil";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7"; 
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
                $grid = $this->verListaPerfiles($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Perfiles();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Perfiles';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
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
                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';

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
                $objResponse->addAssign('modulo_actual',"value","perfiles");
                $objResponse->addIncludeScript(PATH_TO_JS . 'perfiles/perfiles.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
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
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;Perfiles";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Perfiles";
                $contenido['PAGINA_VOLVER'] = "listarPerfiles.php";
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
               // print_r($parametros);
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
                    if (!isset($parametros[nuevo])) $parametros[nuevo] = 'N';
                    if (!isset($parametros[modificar])) $parametros[modificar] = 'N';
                    if (!isset($parametros[eliminar])) $parametros[eliminar] = 'N';
                    if (!isset($parametros[recordatorio])) $parametros[recordatorio] = 'N';
                    if (!isset($parametros[modificar_terceros])) $parametros[modificar_terceros] = 'N';
                    if (!isset($parametros[visualizar_terceros])) $parametros[visualizar_terceros] = 'N';
                    
                    $respuesta = $this->ingresarPerfiles($parametros);
                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);

                    ////////////
                    //print_r($parametros);
                    
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        
                return $objResponse;
            }
     
 
            public function configurar($parametros){
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verPerfiles($parametros[cod_perfil]); 

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
                
                $contenido_1['COD_PERFIL'] = $val["cod_perfil"];
                $contenido_1['DESCRIPCION_PERFIL'] = ($val["descripcion_perfil"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
                $template->setTemplate("configurar");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Configurar&nbsp;Perfiles";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Perfiles";
                $contenido['PAGINA_VOLVER'] = "listarPerfiles.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "conf";
                $contenido['ID'] = $val["id"];

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
            public function editar($parametros)
            {
                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verPerfiles($parametros[id]); 

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
                
                $contenido_1['COD_PERFIL'] = $val["cod_perfil"];
                $contenido_1['DESCRIPCION_PERFIL'] = ($val["descripcion_perfil"]);
                $contenido_1['NUEVO'] = ($val["nuevo"]);
                $contenido_1['MODIFICAR'] = ($val["modificar"]);
                $contenido_1['ELIMINAR'] = ($val["eliminar"]);
                $contenido_1['RECORDATORIO'] = ($val["recordatorio"]);
                $contenido_1['MODIFICAR_TERCEROS'] = ($val["modificar_terceros"]);
                $contenido_1['VISUALIZAR_TERCEROS'] = ($val["visualizar_terceros"]);
                
                $contenido_1[CHECKED_NUEVO] = $val["nuevo"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_MODIFICAR] = $val["modificar"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_ELIMINAR] = $val["eliminar"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_RECORDATORIO] = $val["recordatorio"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_MODIFICAR_TERCEROS] = $val["modificar_terceros"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_VISUALIZAR_TERCEROS] = $val["visualizar_terceros"] == 'S' ? 'checked="checked"' : '';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Perfiles";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Perfiles";
                $contenido['PAGINA_VOLVER'] = "listarPerfiles.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

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
     
            public function cargarConfiguracion($parametros)
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
//                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $arr = explode(",", $parametros[nodos]);
                        $params[cod_perfil] = $parametros[cod_perfil];
                        $this->eliminarMenuArbol($parametros);
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarMenuArbol($params);
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
//                    }
//                    else
//                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                    
                }
                
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
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
                    if (!isset($parametros[nuevo])) $parametros[nuevo] = 'N';
                    if (!isset($parametros[modificar])) $parametros[modificar] = 'N';
                    if (!isset($parametros[eliminar])) $parametros[eliminar] = 'N';
                    if (!isset($parametros[recordatorio])) $parametros[recordatorio] = 'N';
                    if (!isset($parametros[modificar_terceros])) $parametros[modificar_terceros] = 'N';
                    if (!isset($parametros[visualizar_terceros])) $parametros[visualizar_terceros] = 'N';
                    
                    $respuesta = $this->modificarPerfiles($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
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
                //print_r($parametros);
                $params[cod_perfil] = $parametros[id];
                $this->eliminarMenuArbol($params);

                $val = $this->verPerfiles($parametros[id]);
                $respuesta = $this->eliminarPerfiles($parametros);
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
                $grid = $this->verListaPerfiles($parametros);                
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

                $val = $this->verPerfiles($parametros[cod]);

                            $contenido_1['COD_PERFIL'] = $val["cod_perfil"];
            $contenido_1['DESCRIPCION_PERFIL'] = ($val["descripcion_perfil"]);
            $contenido_1['ORDEN'] = $val["orden"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
                $template->setTemplate("verPerfiles");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Perfiles";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }


    public function MuestraPadre(){
        $sql="SELECT * FROM mos_link WHERE dependencia = 0 AND tipo = 1 ORDER BY orden";
        $data = $this->dbl->query($sql, $atr);
        $cabecera_padre = "<ul>";
        $padre_final = "";
        foreach ($data as $arrP) {
            $cuerpo .= "<li id=\"phtml_".$arrP[cod_link]."\"><a href=\"#\">".($arrP[nombre_link])."</a>".$this->MuestraHijos($arrP[cod_link])."</li>";
        }
        $pie_padre = "</ul>";
        return $cabecera_padre.$cuerpo.$pie_padre;
    }            

    public function MuestraHijos($id){
        $sql="SELECT * FROM mos_link WHERE dependencia = ".$id." ORDER BY orden";
        $data = $this->dbl->query($sql, $atr);       
        $cabecera = "<ul>";
        foreach ($data as $arr) {
                $extra .= "<li id=\"phtml_".$arr[cod_link]."\"><a href=\"#\">".($arr[nombre_link])."</a>".$this->MuestraHijos($arr[cod_link])."</li>";		                
        }
        $pie = "</ul>";
        return $cabecera.$extra.$pie;
    }
    
            
 }?>