<?php
 import("clases.interfaz.Pagina");        
        class ParametrosDet extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function ParametrosDet(){
                parent::__construct();
                $this->asigna_script('parametros_det/parametros_det.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 8";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 8";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verParametrosDet($id){
                $atr=array();
                $sql = "SELECT pt.cod_categoria
                        ,pt.cod_parametro
                        ,pt.cod_parametro_det
                        ,pt.descripcion
                        ,pt.vigencia
                        ,pc.descripcion cat
                         FROM mos_parametro_det pt
                         INNER JOIN mos_parametro_categoria pc ON pc.cod_categoria = pt.cod_categoria
                         WHERE pt.cod_parametro_det = $id AND pt.cod_categoria = $_SESSION[cod_categoria] AND pt.cod_parametro = $_SESSION[cod_parametro]"; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verParametros($id){
                $atr=array();
                $sql = "SELECT cod_categoria
                            ,cod_parametro
                            
                            ,espanol
                            ,ingles
                            ,vigencia
                            ,tipo

                         FROM mos_parametro 
                         WHERE cod_parametro = $id AND cod_categoria = $_SESSION[cod_categoria]"; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_parametro_det) total_registros
                         FROM mos_parametro_det";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarParametrosDet($atr){
                try {
                    session_name("$GLOBALS[SESSION]");
                    session_start();
                    //print_r($_SESSION);
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[cod_parametro_det] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_parametro_det(cod_categoria,cod_parametro,cod_parametro_det,descripcion,vigencia)
                            VALUES(
                                $_SESSION[cod_categoria],$_SESSION[cod_parametro],$atr[cod_parametro_det],'$atr[descripcion]','$atr[vigencia]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_parametro_det ' . $atr[descripcion_ano], 'mos_parametro_det');
                      */
                    $nuevo = "Cod Parametro Det: \'$atr[cod_parametro_det]\', Descripcion: \'$atr[descripcion]\', Vigencia: \'$atr[vigencia]\', ";
                    $this->registraTransaccionLog(15,$nuevo,'', '');
                    return "El Item '$atr[descripcion]' ha sido ingresado con exito";
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

            public function modificarParametrosDet($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_parametro_det SET                            
                                    descripcion = '$atr[descripcion]',vigencia = '$atr[vigencia]'
                            WHERE  cod_parametro_det = $atr[id] AND cod_categoria = $_SESSION[cod_categoria] AND cod_parametro = $_SESSION[cod_parametro] ";      
                    $val = $this->verParametrosDet($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Cod Parametro: \'$atr[cod_parametro]\', Cod Parametro Det: \'$atr[cod_parametro_det]\', Descripcion: \'$atr[descripcion]\', Vigencia: \'$atr[vigencia]\', ";
                    $anterior = "Cod Categoria: \'$val[cod_categoria]\', Cod Parametro: \'$val[cod_parametro]\', Cod Parametro Det: \'$val[cod_parametro_det]\', Descripcion: \'$val[descripcion]\', Vigencia: \'$val[vigencia]\', ";
                    $this->registraTransaccionLog(16,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el ParametrosDet ' . $atr[descripcion_ano], 'mos_parametro_det');
                    */
                    return "El Item '$atr[descripcion]'  ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarParametrosDet($atr, $pag, $registros_x_pagina){
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
                         FROM mos_parametro_det 
                         WHERE cod_categoria = $_SESSION[cod_categoria] AND cod_parametro = $_SESSION[cod_parametro] ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-cod_parametro"])>0)
                        $sql .= " AND cod_parametro = '". $atr["b-cod_parametro"] . "'";
             if (strlen($atr["b-cod_parametro_det"])>0)
                        $sql .= " AND cod_parametro_det = '". $atr["b-cod_parametro_det"] . "'";
            if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT cod_parametro_det,
                        cod_categoria
,cod_parametro

,descripcion
,vigencia

                                     $sql_col_left
                            FROM mos_parametro_det $sql_left
                            WHERE cod_categoria = $_SESSION[cod_categoria] AND cod_parametro = $_SESSION[cod_parametro] ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-cod_parametro"])>0)
                        $sql .= " AND cod_parametro = '". $atr["b-cod_parametro"] . "'";
             if (strlen($atr["b-cod_parametro_det"])>0)
                        $sql .= " AND cod_parametro_det = '". $atr["b-cod_parametro_det"] . "'";
            if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    
                    $this->operacion($sql, $atr);
             }
             public function eliminarParametrosDet($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_parametro_modulos 
                                        WHERE cod_parametro_det = " . $atr[id] . " AND cod_categoria = $_SESSION[cod_categoria] AND cod_parametro = $_SESSION[cod_parametro]";                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];  
                        $val = $this->verParametrosDet($atr[id]);
                        if ($total > 0){
                            return "- No se puede Eliminar el registro, Existen relaciones en $val[cat].";
                        }
                        $respuesta = $this->dbl->delete("mos_parametro_det", "cod_parametro_det = " . $atr[id]);
                        $nuevo = "Cod Parametro Det: \'$val[cod_parametro_det]\', Descripcion: \'$val[descripcion]\'";
                        $this->registraTransaccionLog(17,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaParametrosDet($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarParametrosDet($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblParametrosDet", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_categoria], "cod_categoria", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_parametro], "cod_parametro", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_parametro_det], "cod_parametro_det", $parametros)),
               array( "width"=>"50%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros))
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
                    array_push($func,array('nombre'=> 'verParametrosDet','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver ParametrosDet'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarParametrosDet','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar ParametrosDet'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarParametrosDet','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar ParametrosDet'></i>"));
               
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
//                        case 2:
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
            $this->listarParametrosDet($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblParametrosDet", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_categoria], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_parametro], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_parametro_det], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexParametrosDet($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="4-5"; 
                $contenido[CSS_DESC_LARGA] = 'display:none';
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
                $parametros['b-cod_parametro'] = $_SESSION[cod_parametro] = $parametros[id];
                $grid = $this->verListaParametrosDet($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_ParametrosDet();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;ParametrosDet';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['OPC'] = "new";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros_det/';
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
                $template->PATH = PATH_TO_TEMPLATES.'parametros_det/';

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
                $val = $this->verParametros($parametros[id]); 
                $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","parametros_det");
                $objResponse->addIncludeScript(PATH_TO_JS . 'parametros_det/parametros_det.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Items: $val[espanol]');");
                
                //$objResponse->addScript("$('#hv-fecha').datepicker();");
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
                $template->PATH = PATH_TO_TEMPLATES.'parametros_det/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;ParametrosDet";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ParametrosDet";
                $contenido['PAGINA_VOLVER'] = "listarParametrosDet.php";
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
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->ingresarParametrosDet($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        //$objResponse->addScriptCall("MostrarContenido");
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
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verParametrosDet($parametros[id]); 
//
//                if (count($this->nombres_columnas) <= 0){
//                        $this->cargar_nombres_columnas();
//                }
//                foreach ( $this->nombres_columnas as $key => $value) {
//                    $contenido_1["N_" . strtoupper($key)] =  $value;
//                }                
//                if (count($this->placeholder) <= 0){
//                        $this->cargar_placeholder();
//                }
//                foreach ( $this->placeholder as $key => $value) {
//                    $contenido_1["P_" . strtoupper($key)] =  $value;
//                }    
//                            $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
//            $contenido_1['COD_PARAMETRO'] = $val["cod_parametro"];
//            $contenido_1['COD_PARAMETRO_DET'] = $val["cod_parametro_det"];
//            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
//            $contenido_1['VIGENCIA'] = ($val["vigencia"]);

//                $template = new Template();
//                $template->PATH = PATH_TO_TEMPLATES.'parametros_det/';
//                $template->setTemplate("formulario");
//                $template->setVars($contenido_1);
//
//                $contenido['CAMPOS'] = $template->show();
//
//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
//
//                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;ParametrosDet";
//                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ParametrosDet";
//                $contenido['PAGINA_VOLVER'] = "listarParametrosDet.php";
//                $contenido['DESC_OPERACION'] = "Guardar";
//                $contenido['OPC'] = "upd";
//                $contenido['ID'] = $val["id"];

//                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addScript("$('#hv-descripcion').val('$val[descripcion]');");
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
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->modificarParametrosDet($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        //$objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
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
                
                $respuesta = $this->eliminarParametrosDet($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScript("reset_formulario();");
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
                    $parametros['b-cod_parametro'] = $_SESSION[cod_parametro];
                $grid = $this->verListaParametrosDet($parametros);                
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

                $val = $this->verParametrosDet($parametros[id]);

                            $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
            $contenido_1['COD_PARAMETRO'] = $val["cod_parametro"];
            $contenido_1['COD_PARAMETRO_DET'] = $val["cod_parametro_det"];
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros_det/';
                $template->setTemplate("verParametrosDet");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la ParametrosDet";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>