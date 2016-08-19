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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and modulo in (31,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and modulo in (31,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }

           
            
            public function colum_admin($tupla)
            {
                $html = "&nbsp;";
                $sql="select COUNT(*) from mos_requisitos_cargos where id_area=".$tupla[id_area]." and id_cargo=".$tupla[cod_cargo];
                $this->operacion($sql, $tupla);
                $id_cantidad = $this->dbl->data[0][0];//para saber si hy ya alguna relcion
                if($id_cantidad==0){//NO hay asociacion. se crea nuevo
                        $html .= '<a onclick="javascript:relacion_RequisitosCargos(\''.$tupla[cod_cargo].'\',\''.$tupla[id_area].'\' );">
                                    <i style="cursor:pointer" class="icon icon-more"  title="Administrar Requisitos del Cargo" style="cursor:pointer"></i>
                                </a>';
                }
                else{//para modificar la asociacion con requiditos que ya esta echa
                        $html .= '<a onclick="javascript:editarRequisitosCargos(\''.$tupla[cod_cargo].'\',\''.$tupla[id_area].'\' );">
                                    <i style="cursor:pointer" class="icon icon-more"  title="Administrar Requisitos del Cargo" style="cursor:pointer"></i>
                                </a>';
                }
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
             public function verRequisitosCargos($id_cargo,$id_area){
                $atr=array();
                $sql = "SELECT id,id_cargo,id_area,id_requisito_items
                         FROM mos_requisitos_cargos 
                         WHERE id_area = $id_area and id_cargo=$id_cargo order by id_requisito_items"; 
                $requisitos_cargos= $this->dbl->query($sql, array());
                return $requisitos_cargos;
            }
            public function ingresarRequisitosCargos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                  /*  import('clases.utilidades.NivelAcceso');
                    $this->restricciones = new NivelAcceso();
                    /*Carga Acceso segun el arbol
                    if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    
                    /*Valida Restriccion
                    if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_area]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_area]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_area]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_area]][title] . '.';
                    */
                        for($i=0;$i<count($atr[vector_req_item])-1;$i++){
                            $sql = "INSERT INTO mos_requisitos_cargos(id_cargo,id_area,id_requisito_items)
                            VALUES($atr[id_cargo],$atr[id_area],".$atr[vector_req_item][$i].")";
                                $this->dbl->insert_update($sql);
                            $sql = "SELECT MAX(id) ultimo FROM mos_requisitos_cargos"; 
                            $this->operacion($sql, $atr);
                            $vector_req_cargos[$i] = $this->dbl->data[0][0];//guardar el id de requisitos cargos
                            $nuevo = "Id Cargo: \'$atr[id_cargo]\', Id Requisito Items: \'$atr[vector_req_item][$i]\', ";
                            $this->registraTransaccionLog(97,$nuevo,'', $vector_req_cargos[$i]);
                            }
                    
 
                    

                    return $vector_req_cargos;
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
                    $val = $this->verRequisitosCargos($atr[id_cargo],$atr[id_area]);
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
                    
                    $sql = "SELECT COUNT(*) FROM mos_cargo mc
INNER JOIN mos_cargo_estrorg_arbolproc mco ON mc.cod_cargo = mco.cod_cargo
INNER JOIN mos_organizacion mo ON mo.id = mco.id
                         WHERE 1 = 1 ";
                     if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(descrip_cargo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    
                        $sql .= " OR (upper(descrip_area) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-id_cargo"])>0)
                        $sql .= " AND descrip_cargo = '". $atr["b-id_cargo"] . "'";
             if (strlen($atr["b-id_area"])>0)
                        $sql .= " AND descrip_area = '". $atr["b-id_area"] . "'";

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
             if (strlen($atr["b-id_area"])>0)
                        $sql .= " AND descrip_area = '". $atr["b-id_area"] . "'";

                    $sql .= " order by mc.$atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarRequisitosCargos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verRequisitosCargos($atr[id_cargo],$atr[id_area]);
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
        //print_r($parametros);
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
                //$contenido['JS_NUEVO'] = 'relacion_RequisitosCargos('.$parametros["cod_cargo"].','.$parametros["id_area"].');';//agregar relacion de cargos areas y requisitos
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

    /***cargar nombre de columnas dinamicas de las familias***/
            private function cargar_parametros_familias(){
                $sql = "SELECT id,codigo,descripcion FROM mos_requisitos_familias ORDER BY orden";
                $columnas_fam = $this->dbl->query($sql, array());
                return $columnas_fam;
            }
/*********** obtener los combos parametros de un formulario seleccionado*******/
            public function Comboparametros($parametros){
                //print_r($parametros);
            $js = $combos='';
           
/************ id del tipo persona del formulario seleccionado***/
$sql_parametros_persona="select id_unico, Nombre,tipo
from mos_documentos_datos_formulario where IDDoc = ".$parametros[id_form]."  and tipo in (6)";//tipo persona


$parametros_index_persona = $this->dbl->query($sql_parametros_persona, array());
$id_tipo_persona=$parametros_index_persona[0][id_unico];
//$id_tipo_persona=1;//mientras para probar
$combo.='<input type="hidden" id="id_persona_'.$parametros[id_req_item].'" name=""id_persona_'.$parametros[id_req_item].'" value="'.$id_tipo_persona.'"/>';            
/********** consulta de parametros del formulario seleccionado*********/
$grupo1=$grupo2='';
if(($parametros[tipo_req]=='Listado') && ($parametros[vigencia_req]=='N')){//1. requisito tipo listado y no aplica vigencia
        $grupo1='Combo';
    $sql_parametros_1="select id_unico, Nombre from mos_documentos_datos_formulario 
where IDDoc =".$parametros[id_form]." and tipo in (9)";//tipo combo
$parametros_index_1 = $this->dbl->query($sql_parametros_1, array());

                //echo $sql; 
$combo .='<select id="parametro_'.$parametros[id_req_item].'" name="parametro_'.$parametros[id_req_item].'" class="form-control" data-validation="required"  onchange="CargaValoresParametros(this,'.$parametros[id_form].','.$parametros[id_req_item].','.$id_tipo_persona.',1)">';
//$combo.='<option value="">--Seleccione--</option>';
 /* $combo.='<option value="1">Mustard</option>
    <option value="2">Ketchup</option>
    <option value="3">Relish</option>';
*/
 $combo.='<option value="">--Seleccione--</option>';   
for($i=0;$i<count($parametros_index_1);$i++){
        $combo .='<option value="'.$parametros_index_1[$i][id_unico].'">'.$parametros_index_1[$i][Nombre].'</option>';
    }

$combo .='</select>';
}
if(($parametros[tipo_req]=='Listado') && ($parametros[vigencia_req]=='S')){//2. requisito tipo listado y aplica vigencia
    $grupo1='Combo';
   $sql_parametros_1="select id_unico, Nombre from mos_documentos_datos_formulario 
where IDDoc =".$parametros[id_form]." and tipo in (9)";//tipo combo

$grupo2='Vigencia';
$sql_parametros_2="select id_unico, Nombre
from mos_documentos_datos_formulario where IDDoc=".$parametros[id_form]." and tipo in (13) ";//tipo vigencia 


$parametros_index_1 = $this->dbl->query($sql_parametros_1, array());
$parametros_index_2 = $this->dbl->query($sql_parametros_2, array());

                //echo $sql; 
$combo .='<select id="parametro_'.$parametros[id_req_item].'" name="parametro_'.$parametros[id_req_item].'" class="selectpicker" class="form-control" data-validation="required" onChange="CargaValoresParametros(this,'.$parametros[id_form].','.$parametros[id_req_item].','.$id_tipo_persona.',2)" multiple>';

 /* $combo.='<optgroup label="Combo" data-max-options="1">
    <option value="1">Mustard</option>
    <option value="2">Ketchup</option>
    <option value="3">Relish</option>
  </optgroup>
  <optgroup label="Vigencia" data-max-options="1">
    <option value="4">Plain</option>
    <option value="5">Steamed</option>
    <option value="6">Toasted</option>
  </optgroup>';*/
  $combo .='<optgroup label="'.$grupo1.'" data-max-options="1">';
    for($i=0;$i<count($parametros_index_1);$i++){
        $combo .='<option value="'.$parametros_index_1[$i][id_unico].'">'.$parametros_index_1[$i][Nombre].'</option>';
    }
  $combo .='</optgroup>';
   $combo .='<optgroup label="'.$grupo2.'" data-max-options="1">';
    for($j=0;$j<count($parametros_index_2);$j++){
        $combo .='<option value="'.$parametros_index_2[$j][id_unico].'">'.$parametros_index_2[$j][Nombre].'</option>';
    }
   $combo .='</optgroup>';
$combo .='</select>';

}
if(($parametros[tipo_req]=='Unico') && ($parametros[vigencia_req]=='N')){//3. requisito tipo unico y no aplica vigencia

}
if(($parametros[tipo_req]=='Unico') && ($parametros[vigencia_req]=='S')){//4. requisito tipo unico y aplica vigencia
    $grupo1='Vigencia';
$sql_parametros_1="select id_unico, Nombre,tipo from mos_documentos_datos_formulario 
where IDDoc =".$parametros[id_form]." and tipo in (13)";//tipo vigencia

$parametros_index_1 = $this->dbl->query($sql_parametros_1, array());

$combo .='<select id="parametro_'.$parametros[id_req_item].'" name="parametro_'.$parametros[id_req_item].'" class="form-control"  data-validation="required" onChange="CargaValoresParametros(this,'.$parametros[id_form].','.$parametros[id_req_item].','.$id_tipo_persona.',4)">';
  /*$combo.='<option value="">--Seleccione--</option>';
  $combo.='<option value="1">Mustard</option>
    <option value="2">Ketchup</option>
    <option value="3">Relish</option>';
*/
    $combo.='<option value="">--Seleccione--</option>';
for($i=0;$i<count($parametros_index_1);$i++){
        $combo .='<option value="'.$parametros_index_1[$i][id_unico].'">'.$parametros_index_1[$i][Nombre].'</option>';
    }

$combo .='</select>';
}


//extraer el parametro tipo persona del formulario seleccionado


           $js="$('#combos_form_'+".$parametros[id_req_item].").show()";
            $objResponse = new xajaxResponse();            
            //echo $combo;
            $objResponse->addAssign('combos_form_'.$parametros[id_req_item],"innerHTML",$combo);
                        $objResponse->addScript("$('#parametro_".$parametros[id_req_item]."').selectpicker({
                                            style: 'btn-combo'
                                          });");
            $objResponse->addScript("$js");
            return $objResponse;
            }


 /*********** obtener los valores del combo o vigencia de los parametros de un formulario seleccionado*******/
            public function ValoresParametros($parametros){
               //print_r($parametros);
            $js = $combos='';
           
if($parametros[condicion]==1){//1. requisito tipo listado y no aplica vigencia - solo combo
    /********** consulta de valores del parametro seleccionado*********/
$sql_valores="SELECT id,descripcion FROM mos_documentos_formulario_items where fk_id_unico=".$parametros[id_combo];
//echo $sql_valores;
$valores_index = $this->dbl->query($sql_valores, array());
   $combo .='<select id="valores_'.$parametros[id_req_item].'" name="valores_'.$parametros[id_req_item].'" class="form-control" data-validation="required">';
/*$combo.='<option value="">--Seleccione--</option>';
  $combo.='
    <option value="1">valor1</option>
    <option value="2">valor2</option>
    <option value="3">valor3</option>';*/
  $combo.='<option value="">--Seleccione--</option>';
    for($i=0;$i<count($valores_index);$i++){
        $combo .='<option value="'.$valores_index[$i][id].'">'.$valores_index[$i][descripcion].'</option>';
    }
      
 $combo .='</select>'; 
 $combo.='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_combo].'"/>';
  $combo.='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
}

if($parametros[condicion]==2){//2. requisito tipo listado y aplica vigencia - combo y vigencia
    /********** consulta de valores del parametro seleccionado*********/
$sql_valores="SELECT id,descripcion FROM mos_documentos_formulario_items where fk_id_unico=".$parametros[id_combo];

$valores_index = $this->dbl->query($sql_valores, array());
$combo .='<select id="valores_'.$parametros[id_req_item].'" name="valores_'.$parametros[id_req_item].'" class="form-control" data-validation="required">';

 /* $combo.=' <option value="">--Seleccione--</option>
    <option value="1">valor1</option>
    <option value="2">valor2</option>
    <option value="3">valor3</option>';*/
  $combo.='<option value="">--Seleccione--</option>';
    for($i=0;$i<count($valores_index);$i++){
        $combo .='<option value="'.$valores_index[$i][id].'">'.$valores_index[$i][descripcion].'</option>';
    }
      
 $combo .='</select>'; 
 $combo.='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_combo].'"/>';
  $combo.='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_vigencia].'"/>'; 

}
if($parametros[condicion]==3){//3. no aplica ni vigencia ni combo
$combo.='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
     $combo.='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';

}
if($parametros[condicion]==4){//4. requisito tipo unico y aplica vigencia -aplica vigencia
    $combo.='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_vigencia].'"/>';
     $combo.='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>'; 
}
 $combo.='<input name="id_persona_'.$parametros[id_req_item].'" id="id_persona_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_persona].'"/>';//id tipo persona de cada formulario escogido
$js="$('#valores_form_'+".$parametros[id_req_item].").show()";
            $objResponse = new xajaxResponse();            
            //echo $combo;
            $objResponse->addAssign('valores_form_'.$parametros[id_req_item],"innerHTML",$combo);
            $objResponse->addScript("$('#valores_".$parametros[id_req_item]."').selectpicker({
                                            style: 'btn-combo'
                                          });");
            $objResponse->addScript("$js");

            return $objResponse;

             //echo $sql; 
            }                               
 
            public function crear($parametros)
            {
                $js='';
                //print_r($parametros);
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $requisitos_items= '';
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                import("clases.organizacion.ArbolOrganizacional");
                $arbol = new ArbolOrganizacional();
                 $sql_area="select title from mos_organizacion where id=".$parametros[id_area];
                 $this->operacion($sql_area, $parametros);
                $descripcion_area=$this->dbl->data[0][title];
               // $descripcion_area=$arbol->BuscaOrganizacional(array('id_organizacion'=>$parametros[id_area]));//obtener descripcion del area
                $sql_cargo="select descripcion from mos_cargo where cod_cargo=".$parametros[id_cargo];
                    $this->operacion($sql_cargo, $parametros);
                $descripcion_cargo=$this->dbl->data[0][descripcion];
                $contenido_1[OTROS_CAMPOS] = '<input type="hidden"  value="'.$parametros[id_area].'" id="id_area" name="id_area"/>
                                        <input type="hidden" value="'.$parametros[id_cargo].'" id="id_cargo" name="id_cargo" />
                <div class="form-group">
                                        <label for="id_cargo" class="col-md-4 control-label">Area</label>
                                        <div class="col-md-10">

                                        <input type="text" class="form-control" value="'.$descripcion_area.'" id="desc_area" readonly="true" name="desc_area" data-validation="required"/>
                                      </div>                                
                                  </div>
                                    <div class="form-group">
                                        <label for="id_cargo" class="col-md-4 control-label">Cargo</label>
                                        <div class="col-md-10">

                                        <input type="text" class="form-control" value="'.$descripcion_cargo.'" id="desc_cargo" readonly="true"name="desc_cargo" data-validation="required"/>
                                      </div>                                
                                  </div>
                                  ';
/*** generar las pestañas de las familias existentes******/
                 $contenido_1[FAMILIAS]='<div class="form-group"> 
                                    <div class="col-md-24">
                                        <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">';
$columnas_fam=$this->cargar_parametros_familias();
$k=2;
                foreach ($columnas_fam as $value) {                  
                    $descripcion_fam=ucwords(strtolower($value[descripcion]));
                    $contenido_1[FAMILIAS].='<li id="li1"><a href="#hv-red-'.$k.'" data-toggle="tab" style="padding: 8px 32px;">'.$descripcion_fam.'</a></li>';
                    $k++;
                 }      
                $contenido_1[FAMILIAS].='</ul>';
                $k=2;
                 $contenido_1[ITEMS_FAMILIA]='';
                for($i=0;$i<count($columnas_fam);$i++){

/***** obtener items  de la familia*******/
                    $sql_items="SELECT mif.id id_items, mif.descripcion desc_items
                FROM mos_requisitos_items_familias mif
INNER JOIN mos_requisitos_item mri ON mri.id_item = mif.id
INNER JOIN mos_requisitos mr ON mr.id = mri.id_requisitos
INNER JOIN mos_requisitos_organizacion mro ON mro.id_requisito = mr.id
INNER JOIN mos_organizacion mo ON mo.id = mro.id_area
INNER JOIN mos_cargo_estrorg_arbolproc map ON map.id = mro.id_area
AND mo.id = map.id
WHERE id_familia =".$columnas_fam[$i][id]."
AND (
mo.parent_id =".$parametros[id_area]."
OR mo.id =".$parametros[id_area]."
)
AND map.cod_cargo =".$parametros[id_cargo]." GROUP BY id_items";
//echo $sql_items;
                    $items = $this->dbl->query($sql_items, array());
                        $contenido_1[ITEMS_FAMILIA].='<div class="tab-pane active" id="hv-red-'.$k.'">';
                    for($j=0;$j<count($items);$j++){ //cantidad de items que se relacionan con la familia y tienen requisitos asociados             
                        $contenido_1[ITEMS_FAMILIA].='<div class="form-group"><label><strong>&nbsp;*'.ucwords(strtolower($items[$j][desc_items])).'</strong></label>
                                                <br>';
                        /***** obtener requisitos del items mostrado*/
                        $sql_requisitos= "SELECT mr.nombre nomb_req, mri.id_requisitos id_req, mri.id id_req_item,mr.tipo tipo_req,mr.vigencia vigencia_req
FROM mos_requisitos_items_familias mif
INNER JOIN mos_requisitos_item mri ON mri.id_item = mif.id
INNER JOIN mos_requisitos mr ON mr.id = mri.id_requisitos
INNER JOIN mos_requisitos_organizacion mro ON mro.id_requisito = mr.id
INNER JOIN mos_organizacion mo ON mo.id = mro.id_area
INNER JOIN mos_cargo_estrorg_arbolproc map ON map.id = mro.id_area
AND mo.id = map.id
WHERE id_familia =".$columnas_fam[$i][id]."
AND (
mo.parent_id =".$parametros[id_area]."
OR mo.id =".$parametros[id_area]."
)
AND map.cod_cargo =".$parametros[id_cargo]." and 
 mif.id=".$items[$j][id_items]."
GROUP BY ID_REQ";
                        //echo  $sql_requisitos;
                        $requisitos = $this->dbl->query($sql_requisitos, array());
                        for($x=0;$x<count($requisitos);$x++){//requisitos del items
                            $requisitos_items.=$requisitos[$x][id_req_item].',';
                               $contenido_1[ITEMS_FAMILIA].='<br><label for="vigencia" class="col-md-6 control-label">'.$requisitos[$x][nomb_req].'</label>
                                          <div class="col-md-6">
                                    <label class="checkbox-inline" style="padding-top: 5px;">
                                        <input type="checkbox" name="req_'.$requisitos[$x][id_req_item].'" id="req_'.$requisitos[$x][id_req_item].'" value="'.$requisitos[$x][id_req_item].'" onchange="CargaComboForm(this)"></label>
                                             </div>';
/*** consulta para obtener los formularios que cumplan con la condicion del requisito ***/
if(($requisitos[$x][tipo_req]=='Listado') && ($requisitos[$x][vigencia_req]=='N')){//1. requisito tipo listado y no aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S'
     AND muestra_doc = 'S' and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc 
    HAVING COUNT(id_unico) <= 1) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (9))";
}
if(($requisitos[$x][tipo_req]=='Listado') && ($requisitos[$x][vigencia_req]=='S')){//2. requisito tipo listado y aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (13)) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (9))";
}
if(($requisitos[$x][tipo_req]=='Unico') && ($requisitos[$x][vigencia_req]=='N')){//3. requisito tipo unico y no aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 )";
}
if(($requisitos[$x][tipo_req]=='Unico') && ($requisitos[$x][vigencia_req]=='S')){//4. requisito tipo unico y aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (13))";
}
    // echo $sql_formulario;                                      
     $formularios_req = $this->dbl->query($sql_formulario, array());
     //Combo para formularios
                    $contenido_1[ITEMS_FAMILIA].= '<div id="formulario_doc_'.$requisitos[$x][id_req_item].'" style="display:none;"  class="col-md-10"><select id="form_'.$requisitos[$x][id_req_item].'" name="form_'.$requisitos[$x][id_req_item].'" class="form-control" data-validation="required" onchange="CargaComboParametros(this,'.$requisitos[$x][id_req_item].',\''.$requisitos[$x][tipo_req].'\',\''.$requisitos[$x][vigencia_req].'\')">';
                    $contenido_1[ITEMS_FAMILIA].='<option value="">--Seleccione--</option>';
                  //  $contenido_1[ITEMS_FAMILIA].= '<option  value="1">hola</option><option  value="2">pedrooo</option>';
                            for($z=0;$z<count($formularios_req);$z++){
                                $contenido_1[ITEMS_FAMILIA].= '<option  value="'.$formularios_req[$z][IDDoc].'">'.$formularios_req[$z][Codigo_doc].'-'.$formularios_req[$z][nombre_doc].'</option>';
                            }
                    $contenido_1[ITEMS_FAMILIA].= '</select></div>'; 
$js.="$('#form_".$parametros[id_req_item]."').selectpicker({
                                            style: 'btn-combo'
                                          });";
/*************** combos de los parametros*****/
                            $contenido_1[ITEMS_FAMILIA].= '<br>
                                           <label  style="padding-top: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><div class="col-md-12"></div><div id="combos_form_'.$requisitos[$x][id_req_item].'" class="col-md-10" style="display:none;" >';  

$contenido_1[ITEMS_FAMILIA].= '</div>';

/************** Combo en caso de que aplique de los valores*/
                                         $contenido_1[ITEMS_FAMILIA].='
                                           <label  style="padding-top: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><div class="col-md-12"></div><div id="valores_form_'.$requisitos[$x][id_req_item].'" class="col-md-10" style="display:none;"> <br><div class="col-md-6"></div></div><br><br>';

                                 
                              }
                               $contenido_1[ITEMS_FAMILIA].='</div> 
                                  <br>';
                          }$contenido_1[ITEMS_FAMILIA].='</div>';
                              $k++;}
                             
                
                         $contenido_1[FAMILIAS].='<input type="hidden" id="vector_req_item" name="vector_req_item" value="'.$requisitos_items.'">';
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
                                //aqui va el foreach que mando melvin
                foreach ( $this->nombres_columnas as $key => $value) {
                        $contenido["N_" . strtoupper($key)] = $value;
                }
                //print_r($this->nombres_columnas);
                $template->setVars($contenido);
                $objResponse = new xajaxResponse();  
                $objResponse->addScript("$js");             
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");   
 $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
                    $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");

// $objResponse->addScript("$js");
                return $objResponse;
            }
     
 
            public function guardar($parametros)
            {
                
               // print_r($parametros);
                $requisitos_items = array();
                    if(strpos($parametros[vector_req_item],',')){    
                        $requisitos_items = explode(",", $parametros[vector_req_item]);
                    }
                    else{
                        $requisitos_items[] = $parametros[vector_req_item];                    
                    }

                    $parametros[vector_req_item]=$requisitos_items;
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
                    
                    $requisitos_cargos = $this->ingresarRequisitosCargos($parametros);

                    if (is_array($requisitos_cargos)) {//guardar relacion requisitos cargos con los formularios
                        for($i=0;$i<count($parametros[vector_req_item])-1;$i++){
                        $id_form=$parametros["form_".$parametros[vector_req_item][$i]];
                        $id_vigencia=$parametros["id_vigencia_".$parametros[vector_req_item][$i]];
                        $id_combo=$parametros["id_combo_".$parametros[vector_req_item][$i]];
                        $id_persona=$parametros["id_persona_".$parametros[vector_req_item][$i]];
                        $id_valor_params=$parametros["valores_".$parametros[vector_req_item][$i]];


                            $sql = "INSERT INTO mos_requisitos_formularios(id_requisito_cargo,id_documento_form)
                            VALUES(".$requisitos_cargos[$i].",".$id_form.")";
                                $this->dbl->insert_update($sql);
                            $sql = "SELECT MAX(id) ultimo FROM mos_requisitos_formularios"; 
                            $this->operacion($sql, $parametros);
                            $id_requisitos_form = $this->dbl->data[0][0];//guardar el id de requisitos formulario
                            /********** guardar relacion parametros con formulario*****/
                            if($id_combo!='NULL'){//si se tiene un tipo combo
                            $sql_parametros = "INSERT INTO mos_requisitos_parametros_index(id_requisitos_formularios,id_parametro_formulario,id_parametro_items)
                            VALUES(".$id_requisitos_form.",".$id_combo.",".$id_valor_params.")";
                            $this->dbl->insert_update($sql_parametros);
                            }
                            if($id_vigencia!='NULL'){//si se tiene un tipo vigencia
                            $sql_parametros = "INSERT INTO mos_requisitos_parametros_index(id_requisitos_formularios,id_parametro_formulario,id_parametro_items)
                            VALUES(".$id_requisitos_form.",".$id_vigencia.",NULL)";
                            $this->dbl->insert_update($sql_parametros);
                            }
                            //tipo persona siempre aplica un solo tipo persona
                            $sql_parametros = "INSERT INTO mos_requisitos_parametros_index(id_requisitos_formularios,id_parametro_formulario,id_parametro_items)
                            VALUES(".$id_requisitos_form.",".$id_persona.",NULL)";
                            $this->dbl->insert_update($sql_parametros);    
                            }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito','Asignacion de Requisitos asignados al cargo exitosamente');
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error','No se pudo guardar la asociacion con los requisitos');
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
               // print_r($parametros);
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $requisitos_cargos = $this->verRequisitosCargos($parametros[id_cargo],$parametros[id_area]); 

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
                //construir el formulario con los datos cargados
                $sql_area="select title from mos_organizacion where id=".$parametros[id_area];
                 $this->operacion($sql_area, $parametros);
                $descripcion_area=$this->dbl->data[0][title];
               // $descripcion_area=$arbol->BuscaOrganizacional(array('id_organizacion'=>$parametros[id_area]));//obtener descripcion del area
                $sql_cargo="select descripcion from mos_cargo where cod_cargo=".$parametros[id_cargo];
                    $this->operacion($sql_cargo, $parametros);
                $descripcion_cargo=$this->dbl->data[0][descripcion];
                $contenido_1[OTROS_CAMPOS] = '<input type="hidden"  value="'.$parametros[id_area].'" id="id_area" name="id_area"/>
                                        <input type="hidden" value="'.$parametros[id_cargo].'" id="id_cargo" name="id_cargo" />
                <div class="form-group">
                                        <label for="id_cargo" class="col-md-4 control-label">Area</label>
                                        <div class="col-md-10">

                                        <input type="text" class="form-control" value="'.$descripcion_area.'" id="desc_area" readonly="true" name="desc_area" data-validation="required"/>
                                      </div>                                
                                  </div>
                                    <div class="form-group">
                                        <label for="id_cargo" class="col-md-4 control-label">Cargo</label>
                                        <div class="col-md-10">

                                        <input type="text" class="form-control" value="'.$descripcion_cargo.'" id="desc_cargo" readonly="true"name="desc_cargo" data-validation="required"/>
                                      </div>                                
                                  </div>
                                  ';
/*** generar las pestañas de las familias existentes******/
                 $contenido_1[FAMILIAS]='<div class="form-group"> 
                                    <div class="col-md-24">
                                        <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">';
$columnas_fam=$this->cargar_parametros_familias();
$k=2;
                foreach ($columnas_fam as $value) {                  
                    $descripcion_fam=ucwords(strtolower($value[descripcion]));
                    $contenido_1[FAMILIAS].='<li id="li1"><a href="#hv-red-'.$k.'" data-toggle="tab" style="padding: 8px 32px;">'.$descripcion_fam.'</a></li>';
                    $k++;
                 }      
                $contenido_1[FAMILIAS].='</ul>';
                $k=2;
                /********* obtener los requisitos de cada familia*****/ 

                $contenido_1['ID_CARGO'] = $val["id_cargo"];
                $contenido_1['ID_AREA'] = $val["id_area"];

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



                //aqui va el forech que mando melvin
                foreach ( $this->nombres_columnas as $key => $value) {
                        $contenido["N_" . strtoupper($key)] = $value;
                }
                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
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

                $val = $this->verRequisitosCargos($parametros[id_cargo],$parametros[id_area]);

                            $contenido_1['ID_CARGO'] = $val["id_cargo"];
            $contenido_1['ID_AREA'] = $val["id_area"];
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