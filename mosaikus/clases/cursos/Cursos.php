<?php
    function formatear_codigo($tupla){
        $codigo = str_pad($tupla[cod_curso], 3, "0", STR_PAD_LEFT);
        return 'CUR' . ($codigo);
    }
?>
<?php
 import("clases.interfaz.Pagina");        
        class Cursos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        private $campos_activos;
            
            public function Cursos(){
                parent::__construct();
                $this->asigna_script('cursos/cursos.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = array();
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 4";
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
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 4";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }
     

             public function verCursos($id){
                $atr=array();
                $sql = "SELECT cod_curso
                            ,identificacion
                            ,descripcion
                            ,cod_clase
                            ,cod_tipo
                            ,vigencia
                            ,aplica_evaluacion

                         FROM mos_cursos 
                         WHERE cod_curso = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_curso) total_registros
                         FROM mos_cursos";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarCursos($atr){
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[cod_curso] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_cursos(cod_curso,identificacion,descripcion,cod_clase,cod_tipo,vigencia,aplica_evaluacion)
                            VALUES(
                                $atr[cod_curso],'$atr[identificacion]','$atr[descripcion]',$atr[cod_clase],$atr[cod_tipo],'$atr[vigencia]','$atr[aplica_evaluacion]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_cursos ' . $atr[descripcion_ano], 'mos_cursos');
                      */
                    $nuevo = "Cod Curso: \'$atr[cod_curso]\', Identificacion: \'$atr[identificacion]\', Descripcion: \'$atr[descripcion]\', Cod Clase: \'$atr[cod_clase]\', Cod Tipo: \'$atr[cod_tipo]\', Vigencia: \'$atr[vigencia]\', Aplica Evaluacion: \'$atr[aplica_evaluacion]\', ";
                    $this->registraTransaccionLog(48,$nuevo,'', '');
                    return "El curso '$atr[identificacion]' ha sido ingresado con exito";
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

            public function modificarCursos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_cursos SET                            
                                    identificacion = '$atr[identificacion]',descripcion = '$atr[descripcion]',cod_clase = $atr[cod_clase],cod_tipo = $atr[cod_tipo],vigencia = '$atr[vigencia]',aplica_evaluacion = '$atr[aplica_evaluacion]'
                            WHERE  cod_curso = $atr[id]";      
                    $val = $this->verCursos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Curso: \'$atr[cod_curso]\', Identificacion: \'$atr[identificacion]\', Descripcion: \'$atr[descripcion]\', Cod Clase: \'$atr[cod_clase]\', Cod Tipo: \'$atr[cod_tipo]\', Vigencia: \'$atr[vigencia]\', Aplica Evaluacion: \'$atr[aplica_evaluacion]\', ";
                    $anterior = "Cod Curso: \'$val[cod_curso]\', Identificacion: \'$val[identificacion]\', Descripcion: \'$val[descripcion]\', Cod Clase: \'$val[cod_clase]\', Cod Tipo: \'$val[cod_tipo]\', Vigencia: \'$val[vigencia]\', Aplica Evaluacion: \'$val[aplica_evaluacion]\', ";
                    $this->registraTransaccionLog(49,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Cursos ' . $atr[descripcion_ano], 'mos_cursos');
                    */
                    return "El curso '$atr[identificacion]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarCursos($atr, $pag, $registros_x_pagina){
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
                         FROM mos_cursos c
                            INNER JOIN mos_tipo t ON t.cod_tipo = c.cod_tipo
                            INNER JOIN mos_clase cl ON cl.cod_clase = c.cod_clase
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        //$sql .= " AND ((upper(c.cod_curso) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(c.identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";                        
                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";                        
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_curso"])>0)
                        $sql .= " AND cod_curso = '". $atr["b-cod_curso"] . "'";
                    if (strlen($atr["b-identificacion"])>0)
                        $sql .= " AND upper(identificacion) like '%" . strtoupper($atr["b-identificacion"]) . "%'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND descripcion like '%". $atr["b-descripcion"] . "%'";
                    if (strlen($atr["b-cod_clase"])>0)
                        $sql .= " AND cl.descripcion like '%". $atr["b-cod_clase"] . "%'";
                    if (strlen($atr["b-cod_tipo"])>0)
                        $sql .= " AND t.descripcion like '%". $atr["b-cod_tipo"] . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-aplica_evaluacion"])>0)
                        $sql .= " AND upper(aplica_evaluacion) like '%" . strtoupper($atr["b-aplica_evaluacion"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT cod_curso
                                    ,identificacion
                                    ,c.descripcion
                                    ,cl.descripcion cod_clase
                                    ,t.descripcion cod_tipo
                                    ,CASE c.vigencia WHEN 'S' THEN 'Si' ELSE 'No' END vigencia
                                    ,CASE c.aplica_evaluacion  WHEN 'S' THEN 'Si' ELSE 'No' END aplica_evaluacion

                                     $sql_col_left
                            FROM mos_cursos c
                            INNER JOIN mos_tipo t ON t.cod_tipo = c.cod_tipo
                            INNER JOIN mos_clase cl ON cl.cod_clase = c.cod_clase
                            $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        //$sql .= " AND ((upper(c.cod_curso) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(c.identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";                        
                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";                        
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_curso"])>0)
                        $sql .= " AND cod_curso = '". $atr["b-cod_curso"] . "'";
                    if (strlen($atr["b-identificacion"])>0)
                        $sql .= " AND upper(identificacion) like '%" . strtoupper($atr["b-identificacion"]) . "%'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND descripcion like '%". $atr["b-descripcion"] . "%'";
                    if (strlen($atr["b-cod_clase"])>0)
                        $sql .= " AND cl.descripcion like '%". $atr["b-cod_clase"] . "%'";
                    if (strlen($atr["b-cod_tipo"])>0)
                        $sql .= " AND t.descripcion like '%". $atr["b-cod_tipo"] . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-aplica_evaluacion"])>0)
                        $sql .= " AND upper(aplica_evaluacion) like '%" . strtoupper($atr["b-aplica_evaluacion"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarCursos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_personal_capacitacion 
                                        WHERE cod_curso = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];  
                        if ($total > 0){
                            return "- No se puede eliminar curso, existen capacitaciones asignadas.";
                        }
                        $respuesta = $this->dbl->delete("mos_cursos", "cod_curso = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaCursos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarCursos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblCursos", "");
                $config_col=array(
                    
               array( "width"=>"6%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_curso], "cod_curso", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[identificacion], "identificacion", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_clase], "cod_clase", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_tipo], "cod_tipo", $parametros)),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[aplica_evaluacion], "aplica_evaluacion", $parametros))
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
                    
                    $columna_funcion = 8;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verCursos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Cursos'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarCursos','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\" title='Editar Cursos'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarCursos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar Cursos'></i>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:50px'>&nbsp;</div>"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
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
                $grid->setFuncion("cod_curso", "formatear_codigo");
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
            $this->listarCursos($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                
             $grid->SetConfiguracion("tblCursos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[cod_curso], ENT_QUOTES, "UTF-8"))),
         array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[identificacion], ENT_QUOTES, "UTF-8"))),
         array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8"))),
         array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[cod_clase], ENT_QUOTES, "UTF-8"))),
         array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[cod_tipo], ENT_QUOTES, "UTF-8"))),
         array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8"))),
         array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[aplica_evaluacion], ENT_QUOTES, "UTF-8")))
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
//                        case 2:
//                        case 3:
//                        case 4:
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
                $grid->setFuncion("cod_curso", "formatear_codigo");
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexCursos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="identificacion";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-"; 
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
                $grid = $this->verListaCursos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Cursos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Cursos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cursos/';

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
                $template->PATH = PATH_TO_TEMPLATES.'cursos/';

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
                $objResponse->addAssign('modulo_actual',"value","cursos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'cursos/cursos.js?'.rand());
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
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cursos/';
                $template->setTemplate("formulario");
                $contenido_1[CHECKED_VIGENCIA] = 'checked="checked"';
                //$atr[cod_curso] = $this->codigo_siguiente();
                $contenido_1['COD_CURSO'] = formatear_codigo(array(cod_curso => $this->codigo_siguiente()));
                $contenido_1['CLASES'] = $ut_tool->OptionsCombo("SELECT cod_clase, descripcion 
                                                                            FROM mos_clase "
                                                                    , 'cod_clase'
                                                                    , 'descripcion', $val['cod_clase']);
                $contenido_1['TIPOS'] = $ut_tool->OptionsCombo("SELECT cod_tipo, descripcion 
                                                                            FROM mos_tipo "
                                                                    , 'cod_tipo'
                                                                    , 'descripcion', $val['cod_tipo']);
                
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Cursos";
                $contenido['PAGINA_VOLVER'] = "listarCursos.php";
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
                    if (!isset($parametros[aplica_evaluacion])) $parametros[aplica_evaluacion] = 'N';
                    $respuesta = $this->ingresarCursos($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
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
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verCursos($parametros[id]); 

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
                $contenido_1['COD_CURSO'] = formatear_codigo($val);
                $contenido_1['IDENTIFICACION'] = ($val["identificacion"]);
                $contenido_1['DESCRIPCION'] = $val["descripcion"];
                $contenido_1['COD_CLASE'] = $val["cod_clase"];
                $contenido_1['COD_TIPO'] = $val["cod_tipo"];
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1[CHECKED_VIGENCIA] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
                $contenido_1['APLICA_EVALUACION'] = ($val["aplica_evaluacion"]);
                $contenido_1[CHECKED_APLICA_EVALUACION] = $val["aplica_evaluacion"] == 'S' ? 'checked="checked"' : '';
                $contenido_1['CLASES'] = $ut_tool->OptionsCombo("SELECT cod_clase, descripcion 
                                                                            FROM mos_clase "
                                                                    , 'cod_clase'
                                                                    , 'descripcion', $val['cod_clase']);
                $contenido_1['TIPOS'] = $ut_tool->OptionsCombo("SELECT cod_tipo, descripcion 
                                                                            FROM mos_tipo "
                                                                    , 'cod_tipo'
                                                                    , 'descripcion', $val['cod_tipo']);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cursos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Cursos";
                $contenido['PAGINA_VOLVER'] = "listarCursos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["cod_curso"];

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
            
            public function tipo_curso($parametros)
            {
                
                $val = $this->verCursos($parametros[id]); 
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('tipo_curso',"value",$val["aplica_evaluacion"]);
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
                    if (!isset($parametros[aplica_evaluacion])) $parametros[aplica_evaluacion] = 'N';
                    $respuesta = $this->modificarCursos($parametros);

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
                $val = $this->verCursos($parametros[id]);
                $respuesta = $this->eliminarCursos($parametros);
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
                $grid = $this->verListaCursos($parametros);                
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

                $val = $this->verCursos($parametros[id]);

                            $contenido_1['COD_CURSO'] = $val["cod_curso"];
            $contenido_1['IDENTIFICACION'] = ($val["identificacion"]);
            $contenido_1['DESCRIPCION'] = $val["descripcion"];
            $contenido_1['COD_CLASE'] = $val["cod_clase"];
            $contenido_1['COD_TIPO'] = $val["cod_tipo"];
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['APLICA_EVALUACION'] = ($val["aplica_evaluacion"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cursos/';
                $template->setTemplate("verCursos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Cursos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>