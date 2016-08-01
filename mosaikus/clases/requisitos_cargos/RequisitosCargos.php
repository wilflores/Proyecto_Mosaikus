<?php
 import("clases.interfaz.Pagina");        
        class RequisitosCargos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        
        private $restricciones;
        
            
            public function RequisitosCargos(){
                parent::__construct();
                $this->asigna_script('requisitos_cargos/requisitos_cargos.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 31";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 31";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }

           
            
            public function colum_admin($tupla)
            {
                $html = "&nbsp;";
                $html .= '<a onclick="javascript:relacion_RequisitosCargos(\''.$tupla[cod_cargo].'\',\''.$tupla[id_area].'\' );">
                                    <i style="cursor:pointer" class="icon icon-more"  title="Administrar Requisitos del Cargo" style="cursor:pointer"></i>
                                </a>';
                /*if (strlen($tupla[id_registro])<=0){
                    if($this->restricciones->per_editar  == 'S'){
                        $html .= '<a onclick="javascript:editarRequisitosCargos(\''.$tupla[cod_cargo].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar RequisitosCargos" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->restricciones->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarRequisitosCargos(\''.$tupla[cod_cargo].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar RequisitosCargos" style="cursor:pointer"></i>
                                </a>';
                    }
                }*/
                return $html;
            }
            
           /* public function colum_admin_arbol($tupla)
            {                
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarRequisitosCargos('". $tupla[id] . "');\"  title=\"Editar RequisitosCargos\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= "<a href=\"#\" onclick=\"javascript:eliminarRequisitosCargos('". $tupla[id] . "');\" title=\"Eliminar RequisitosCargos\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                }
                return $html;
            }

     
*/
             public function verRequisitosCargos($id){
                $atr=array();
                $sql = "SELECT id
,id_cargo
,id_requisito_items

                         FROM mos_requisitos_cargos 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarRequisitosCargos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    /*Carga Acceso segun el arbol*/
                    if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    
                    /*Valida Restriccion*/
                    if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';

                    $sql = "INSERT INTO mos_requisitos_cargos(id,id_cargo,id_requisito_items)
                            VALUES(
                                $atr[id],$atr[id_cargo],$atr[id_requisito_items]
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_requisitos_cargos ' . $atr[descripcion_ano], 'mos_requisitos_cargos');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_requisitos_cargos"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Id Cargo: \'$atr[id_cargo]\', Id Requisito Items: \'$atr[id_requisito_items]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', $id_new);
                    return "El mos_requisitos_cargos '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = 'NULL'){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip, id_registro) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]',$id)";            
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarRequisitosCargos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol*/
                    if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    
                    /*Valida Restriccion*/
                    if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';

                    $sql = "UPDATE mos_requisitos_cargos SET                            
                                    id = $atr[id],id_cargo = $atr[id_cargo],id_requisito_items = $atr[id_requisito_items]
                            WHERE  id = $atr[id]";      
                    $val = $this->verRequisitosCargos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Cargo: \'$atr[id_cargo]\', Id Requisito Items: \'$atr[id_requisito_items]\', ";
                    $anterior = "Id Cargo: \'$val[id_cargo]\', Id Requisito Items: \'$val[id_requisito_items]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el RequisitosCargos ' . $atr[descripcion_ano], 'mos_requisitos_cargos');
                    */
                    return "El mos_requisitos_cargos '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarRequisitosCargos($atr, $pag, $registros_x_pagina){
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
                    }
                    
                    if (count($this->restricciones->id_org_acceso) <= 0){
                        $this->restricciones->cargar_acceso_nodos($atr);
                    }*/
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_requisitos_cargos 
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(id_personal) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (1 = 1";
                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
                        } 
                        $sql .= " ) ";
                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-id_cargo"])>0)
                        $sql .= " AND id_cargo = '". $atr["b-id_cargo"] . "'";
             if (strlen($atr["b-id_requisito_items"])>0)
                        $sql .= " AND id_requisito_items = '". $atr["b-id_requisito_items"] . "'";

                    if (count($this->restricciones->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->restricciones->id_org_acceso)) . ")";
                    }
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT mco.id id_area,mc.cod_cargo cod_cargo, mo.title descrip_area,mc.descripcion descrip_cargo $sql_col_left
FROM mos_cargo mc
INNER JOIN mos_cargo_estrorg_arbolproc mco ON mc.cod_cargo = mco.cod_cargo
INNER JOIN mos_organizacion mo ON mo.id = mco.id $sql_left";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(descrip_cargo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    
                        $sql .= " OR (upper(descrip_area) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-id_cargo"])>0)
                        $sql .= " AND descrip_cargo = '". $atr["b-id_cargo"] . "'";
             if (strlen($atr["b-id_requisito_items"])>0)
                        $sql .= " AND descrip_area = '". $atr["b-id_requisito_items"] . "'";

                    $sql .= " order by mc.$atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarRequisitosCargos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verRequisitosCargos($atr[id]);
                        $respuesta = $this->dbl->delete("mos_requisitos_cargos", "id = " . $atr[id]);
                        $nuevo = "Id Cargo: \'$val[id_cargo]\', Id Requisito Items: \'$val[id_requisito_items]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaRequisitosCargos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarRequisitosCargos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblRequisitosCargos", "");
                $config_col=array(
                    
               array( "width"=>"5%","ValorEtiqueta"=>"&nbsp;"),
               array( "width"=>"5%","ValorEtiqueta"=>"&nbsp;"),
               array( "width"=>"35%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_area],ENT_QUOTES, "UTF-8")),
               array( "width"=>"40%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_cargo], ENT_QUOTES, "UTF-8"))
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

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 4;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verRequisitosCargos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver RequisitosCargos'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarRequisitosCargos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar RequisitosCargos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarRequisitosCargos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar RequisitosCargos'>"));
               */
                $config=array();
                //$config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        /*case 1:
                        case 2:
                        case 3:
                        case 4:
                            array_push($config,$config_col[$i]);
                            break;
*/
                        default:
                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;
                            
                            break;
                    }
                }
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id_area", "colum_admin");
                //$grid->setFuncion("id_area", "colum_admin");
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
            $this->listarRequisitosCargos($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblRequisitosCargos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_cargo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_requisito_items], ENT_QUOTES, "UTF-8"))
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
                        /*case 1:
                        case 2:
                        case 3:
                        case 4:
                            array_push($config,$config_col[$i]);
                            break;*/
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
 
 
            public function indexRequisitosCargos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
                $this->restricciones->cargar_permisos($parametros);
                if ($parametros['corder'] == null) $parametros['corder']="cod_cargo";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-2-3-"; 
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
                $this->restricciones->cargar_permisos($parametros);
                $grid = $this->verListaRequisitosCargos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
               // $contenido['JS_NUEVO'] = 'nuevo_RequisitosCargos();';
                $contenido['JS_NUEVO'] = 'relacion_RequisitosCargos('.$parametros["cod_cargo"].','.$parametros["id_area"].');';//agregar relacion de cargos areas y requisitos
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;RequisitosCargos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = 'display:none;';//para que no salga la opcion de nuevo
                //$this->restricciones->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'requisitos_cargos/';
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
                $template->PATH = PATH_TO_TEMPLATES.'requisitos_cargos/';

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
                $objResponse->addAssign('modulo_actual',"value","requisitos_cargos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'requisitos_cargos/requisitos_cargos.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_simple();');
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {
                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                import("clases.organizacion.ArbolOrganizacional");
                $arbol = new ArbolOrganizacional();
                 $sql_area="select title from mos_organizacion where id=".$parametros[id_area];
                 $this->operacion($sql_area, $parametros);
                $descripcion_area=$this->dbl->data[0][title];
               // $descripcion_area=$arbol->BuscaOrganizacional(array('id_organizacion'=>$parametros[id_area]));//obtener descripcion del area
                $sql_cargo="select descripcion from mos_cargo where cod_cargo=".$parametros[cod_cargo];
                    $this->operacion($sql_cargo, $parametros);
                $descripcion_cargo=$this->dbl->data[0][descripcion];
                $contenido_1[OTROS_CAMPOS] = '<div class="form-group">
                                        <label for="id_cargo" class="col-md-4 control-label">Area</label>
                                        <div class="col-md-10">

                                        <input type="text" class="form-control" value="'.$descripcion_area.'" id="id_area" readonly="true" name="id_area" data-validation="required"/>
                                      </div>                                
                                  </div>
                                    <div class="form-group">
                                        <label for="id_cargo" class="col-md-4 control-label">Cargo</label>
                                        <div class="col-md-10">

                                        <input type="text" class="form-control" value="'.$descripcion_cargo.'" id="id_cargo" readonly="true"name="id_cargo" data-validation="required"/>
                                      </div>                                
                                  </div>
                                  '; 
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
                $template->PATH = PATH_TO_TEMPLATES.'requisitos_cargos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;RequisitosCargos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Requisitos Cargos";
                $contenido['PAGINA_VOLVER'] = "listarRequisitosCargos.php";
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
$objResponse->addScript("$('#tabs-hv').tab();$('#tabs-hv a:last').tab('show');");
                    $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");
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
                    
                    $respuesta = $this->ingresarRequisitosCargos($parametros);

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
                $val = $this->verRequisitosCargos($parametros[id]); 

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
                            $contenido_1['ID_CARGO'] = $val["id_cargo"];
            $contenido_1['ID_REQUISITO_ITEMS'] = $val["id_requisito_items"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'requisitos_cargos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;RequisitosCargos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;RequisitosCargos";
                $contenido['PAGINA_VOLVER'] = "listarRequisitosCargos.php";
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
                    
                    $respuesta = $this->modificarRequisitosCargos($parametros);

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
                $val = $this->verRequisitosCargos($parametros[id]);
                $respuesta = $this->eliminarRequisitosCargos($parametros);
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
                /*Permisos en caso de que no se use el arbol organizacional*/
                import('clases.utilidades.NivelAcceso');                
                $this->restricciones = new NivelAcceso();
                $this->restricciones->cargar_permisos($parametros);
                $grid = $this->verListaRequisitosCargos($parametros);                
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

                $val = $this->verRequisitosCargos($parametros[id]);

                            $contenido_1['ID_CARGO'] = $val["id_cargo"];
            $contenido_1['ID_REQUISITO_ITEMS'] = $val["id_requisito_items"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'requisitos_cargos/';
                $template->setTemplate("verRequisitosCargos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la RequisitosCargos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>