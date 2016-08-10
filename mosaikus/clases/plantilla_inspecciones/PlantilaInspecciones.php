<?php
 import("clases.interfaz.Pagina");        
        class PlantilaInspecciones extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        private $id_org_acceso;
        private $id_org_acceso_explicito;
        private $per_crear;
        private $per_editar;
        private $per_eliminar;
        
            
            public function PlantilaInspecciones(){
                parent::__construct();
                $this->asigna_script('plantilla_inspecciones/plantilla_inspecciones.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->id_org_acceso = $this->id_org_acceso_explicito = array();
                $this->per_crear = $this->per_editar = $this->per_eliminar = 'N';
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (20,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (20,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }

            /**
            * Activa los nodos donde se tiene acceso
            */
           public function cargar_acceso_nodos($parametros){
               if (strlen($parametros[cod_link])>0){
                   if(!class_exists('mos_acceso')){
                       import("clases.mos_acceso.mos_acceso");
                   }
                   $acceso = new mos_acceso();
                   $data_ids_acceso = $acceso->obtenerArbolEstructura($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);                   
                   foreach ($data_ids_acceso as $value) {
                       $this->id_org_acceso[$value[id]] = $value;
                   }                                            
               }
           }

           /**
            * Activa los nodos donde se tiene acceso
            */
           private function cargar_acceso_nodos_explicito($parametros){
               if (strlen($parametros[cod_link])>0){
                   if(!class_exists('mos_acceso')){
                       import("clases.mos_acceso.mos_acceso");
                   }
                   $acceso = new mos_acceso();
                   $data_ids_acceso = $acceso->obtenerNodosArbol($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);                   
                   foreach ($data_ids_acceso as $value) {
                       $this->id_org_acceso_explicito[$value[id]] = $value;
                   }                                            
               }
           }
           
            /**
             * Busca los permisos que tiene el usuario en el modulo
             */
            private function cargar_permisos($parametros){
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();
                    $data_permisos = $acceso->obtenerPermisosModulo($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros['b-id_organizacion']);                    
                    foreach ($data_permisos as $value) {
                        if ($value[nuevo] == 'S'){
                            $this->per_crear =  'S';
                            break;
                        }
                    }                                               
                    foreach ($data_permisos as $value) {
                        if ($value[modificar] == 'S'){
                            $this->per_editar =  'S';
                            break;
                        }
                    } 
                    foreach ($data_permisos as $value) {
                        if ($value[eliminar] == 'S'){
                            $this->per_eliminar =  'S';
                            break;
                        }
                    } 
                }
            }
            
            public function colum_admin($tupla)
            {
                $html = "&nbsp;";
                if (strlen($tupla[id_registro])<=0){
                    if($this->per_editar == 'S'){
                        $html .= '<a onclick="javascript:editarPlantilaInspecciones(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar PlantilaInspecciones" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarPlantilaInspecciones(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar PlantilaInspecciones" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
            public function colum_admin_arbol($tupla)
            {                
                if ($this->id_org_acceso[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarPlantilaInspecciones('". $tupla[id] . "');\"  title=\"Editar PlantilaInspecciones\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->id_org_acceso[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= '<a href=\"#\" onclick=\"javascript:eliminarPlantilaInspecciones(\''. $tupla[id] . '\');\" title=\"Eliminar PlantilaInspecciones\">
                            <i class=\"icon icon-remove\"></i>

                        </a>'; 
                }
                return $html;
            }

     

             public function verPlantilaInspecciones($id){
                $atr=array();
                $sql = "SELECT id
,codigo
,descripcion

                         FROM mos_tipo_inspecciones 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarPlantilaInspecciones($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    
                    $sql = "INSERT INTO mos_tipo_inspecciones(codigo,descripcion)
                            VALUES(
                                '$atr[codigo]','$atr[descripcion]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_tipo_inspecciones ' . $atr[descripcion_ano], 'mos_tipo_inspecciones');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_tipo_inspecciones"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Codigo: \'$atr[codigo]\', Descripcion: \'$atr[descripcion]\', ";
                    $this->registraTransaccionLog(84,$nuevo,'', $id_new);
                    return $id_new;
                    return "El mos_tipo_inspecciones '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/for key 'codigo'/",$error ) == true) 
                            return "Ya existe una plantilla con el mismo c&oacute;digo.";                        
                        return $error; 
                    }
            }
            
            public function ingresarCategoria($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_tipo_inspecciones_categorias(id_tipo_insp,categoria,peso,orden)
                            VALUES(
                                $atr[id_tipo_insp],'$atr[categoria]',0,$atr[orden]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $sql = "SELECT MAX(id) total_registros
                         FROM mos_tipo_inspecciones_categorias";
                    $total_registros = $this->dbl->query($sql, $atr);
                    $num_viaje = $total_registros[0][total_registros];                
                    return $num_viaje;     
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
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

            public function modificarPlantilaInspecciones($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_tipo_inspecciones SET                            
                                    codigo = '$atr[codigo]',descripcion = '$atr[descripcion]'
                            WHERE  id = $atr[id]";      
                    $val = $this->verPlantilaInspecciones($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Codigo: \'$atr[codigo]\', Descripcion: \'$atr[descripcion]\', ";
                    $anterior = "Codigo: \'$val[codigo]\', Descripcion: \'$val[descripcion]\', ";
                    $this->registraTransaccionLog(85,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el PlantilaInspecciones ' . $atr[descripcion_ano], 'mos_tipo_inspecciones');
                    */
                    return "La plantilla '$atr[codigo]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarPlantilaInspecciones($atr, $pag, $registros_x_pagina){
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
                    
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);
                    }*/
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_tipo_inspecciones 
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(codigo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (1 = 1";
                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
//                        foreach ($nombre_supervisor as $supervisor_aux) {
//                           $sql .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
//                        } 
                        $sql .= " ) ";
                        $sql .= " OR (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                ,codigo
                                ,descripcion

                                     $sql_col_left
                            FROM mos_tipo_inspecciones $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(codigo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (1 = 1";
                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
//                        foreach ($nombre_supervisor as $supervisor_aux) {
//                           $sql .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
//                        } 
                        $sql .= " ) ";
                        $sql .= " OR (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             
             public function listarCategorias($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT 
                                categoria Nombre
                                ,peso
                                -- ,valores
                                ,id id_unico
                                ,orden                               
                            FROM mos_tipo_inspecciones_categorias  
                            
                            WHERE id_tipo_insp = $atr[id] ORDER BY orden"; 
                            
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function eliminarPlantilaInspecciones($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verPlantilaInspecciones($atr[id]);
                        $respuesta = $this->dbl->delete("mos_tipo_inspecciones", "id = " . $atr[id]);
                        $nuevo = "Codigo: \'$val[codigo]\', Descripcion: \'$val[descripcion]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaPlantilaInspecciones($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarPlantilaInspecciones($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblPlantilaInspecciones", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[codigo], "codigo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros))
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
                    array_push($func,array('nombre'=> 'verPlantilaInspecciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver PlantilaInspecciones'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarPlantilaInspecciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar PlantilaInspecciones'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarPlantilaInspecciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar PlantilaInspecciones'>"));
               */
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
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
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id", "colum_admin");
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
            $this->listarPlantilaInspecciones($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblPlantilaInspecciones", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[codigo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexPlantilaInspecciones($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-"; 
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
                $this->cargar_permisos($parametros);
                $grid = $this->verListaPlantilaInspecciones($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_PlantilaInspecciones();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;PlantilaInspecciones';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'plantilla_inspecciones/';
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
                $template->PATH = PATH_TO_TEMPLATES.'plantilla_inspecciones/';

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
                $objResponse->addAssign('modulo_actual',"value","plantilla_inspecciones");
                $objResponse->addIncludeScript(PATH_TO_JS . 'plantilla_inspecciones/plantilla_inspecciones.js');
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
                $contenido_1['TOK_NEW'] = time();
                $contenido_1[NUM_ITEMS_ESP] = 0;
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'plantilla_inspecciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;PlantilaInspecciones";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;PlantilaInspecciones";
                $contenido['PAGINA_VOLVER'] = "listarPlantilaInspecciones.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }          
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
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
                $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
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
                    
                    $respuesta = $this->ingresarPlantilaInspecciones($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) 
                    if (strlen($respuesta ) < 10 ) 
                    {
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["nombre_din_$i"])){                                                                
                                $params[id_tipo_insp] = $respuesta;
                                $params[categoria] = $parametros["nombre_din_$i"];                                        
                                $params[orden] = $parametros["orden_din_$i"];                                  
                                 
                                
                                //echo $parametros["cuerpo_$i"];
                                $id_unico = $this->ingresarCategoria($params);
                                //if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9" ))
                                {
                                    $sql = 'INSERT INTO mos_tipo_inspecciones_verificadores(id_tipo_insp_cat,id_tipo_insp, verificador, desc_verificador, vigencia, peso)'
                                            . ' SELECT ' . $id_unico . ', ' . $respuesta . ', descripcion, descripcion_larga, vigencia, peso '
                                            . ' FROM mos_documentos_formulario_items_temp '
                                            . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                            . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    
                                }
                            }
                        }
                    
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito','La plantilla "'.$parametros[codigo].'" ha sido ingresado con exito');
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
                $val = $this->verPlantilaInspecciones($parametros[id]); 

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
                
                $contenido_1['CODIGO'] = ($val["codigo"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);            
                
                /* Categorias */                
                $this->listarCategorias($parametros);
                $data=$this->dbl->data;
                //print_r($data);
                $item = "";
                $js = "";
                $i = 0;
                $contenido_1['TOK_NEW'] = time();                                
                //$ids = array('7','8','9','1','2','3','5','6','10');
                //$desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo');
                foreach ($data as $value) {                          
                    $i++;
                    //echo $i;
                    /*CARGA de items temporal para campos combo, seleccion simple y multiple*/
                        {
                            $sql = "INSERT INTO mos_documentos_formulario_items_temp(fk_id_unico, descripcion, vigencia, peso, fk_id_item, id_usuario, tok, estado, descripcion_larga,orden)"
                                    . " SELECT id_tipo_insp_cat, verificador, vigencia, peso, id, $_SESSION[CookIdUsuario],$contenido_1[TOK_NEW],0, desc_verificador, orden "
                                    . " FROM mos_tipo_inspecciones_verificadores"
                                    . " WHERE id_tipo_insp_cat = $value[id_unico] ";
                            $this->dbl->insert_update($sql);
                            $sql = "SELECT 
                                    descripcion
                                    ,vigencia
                                    
                                     
                            FROM mos_documentos_formulario_items_temp 
                            WHERE tok = $contenido_1[TOK_NEW] and id_usuario = $_SESSION[CookIdUsuario] and fk_id_unico = $value[id_unico] ORDER BY descripcion";
                            $data_items = $this->dbl->query($sql);
                            $value[valores] = '';
                            foreach ($data_items as $value_items) {
                                $value[valores] .= $value_items[descripcion] . '<br />';                                
                                
                            }
                            $value[valores] = substr($value[valores], 0, strlen($value[valores])-6);
                            
                        }
                    $item = $item. '<tr id="tr-esp-' . $i . '">';                      
                    

                    {
                        
                                                                    
                        $item = $item. '<td align="center">'.
                                            ' <a href="' . $i . '"  title="Eliminar " id="eliminar_esp_' . $i . '"> ' . 
                                            //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' . 
                                             '<i class="icon icon-remove" style="width: 18px;"></i>'.
                                             '</a>' .
                                             '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
                                              <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'.
                                              
                                              '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id_unico].'" type="hidden" >'.
                                              '<input id="cmb_din_'. $i . '" type="hidden" name="cmb_din_'. $i . '" tok="' . $i . '" value="'.$value[id_unico].'">'.
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.$value[orden].'" type="hidden" >'.
                                       '  </td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input id="nombre_din_'. $i . '" value="'.$value[Nombre].'" class="form-control" type="text" data-validation="required" name="nombre_din_'. $i . '">'.
                                        '</td>';
//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';
                         $item = $item.  '<td>' .
                                            ' <textarea id="valores_din_'. $i . '" rows="5" name="valores_din_'. $i . '" readonly="" class="form-control" data-validation="required">'. str_replace("<br />", "<br>", $value[valores]) .'</textarea>'.
                                         '</td>';
                         $item = $item. '<td>' .
                                            '<i class="icon icon-more cursor-pointer" title="Administrar Verificadores" id="ico_cmb_din_'. $i . '" tok="'. $i .'"></i>'.
                                         '</td>';
                        
                        
                        $item = $item. '</tr>' ;                    
                        $js .= '$("#eliminar_esp_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("href");  
                                    $("#id_unico_del").val($("#id_unico_del").val() + $("#id_unico_din_"+id).val() + ",");
                                    $("tr-esp-'. $i .'").remove();
                                    var parent = $(this).parents().parents().get(0);
                                        $(parent).remove();
                            });';
                        $js .= '$("#ico_cmb_din_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("tok");            
                                    array = new XArray();
                                    array.setObjeto("ItemsFormulario","indexItemsFormulario");
                                    array.addParametro("tok",id);
                                    array.addParametro("id",$("#cmb_din_"+id).val());
                                    array.addParametro("titulo",$("#nombre_din_"+id).val());
                                    array.addParametro("token", $("#tok_new_edit").val());
                                    array.addParametro("desc_larga", 1);
                                    array.addParametro("import","clases.items_formulario.ItemsFormulario");
                                    xajax_Loading(array.getArray());
                                }); ';
                        $js .= "ajustar_valor_atributo_dinamico($i);";
                        
                    }
                }               
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'plantilla_inspecciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;PlantilaInspecciones";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;PlantilaInspecciones";
                $contenido['PAGINA_VOLVER'] = "listarPlantilaInspecciones.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }          
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
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
                $objResponse->addScript("$js");
                $objResponse->addScript('$(".subir").click(function(){
                    var row = $(this).parents("tr:first");               
                    row.insertBefore(row.prev());
                    ordenar_tabla();

                });
                $(".bajar").click(function(){
                    var row = $(this).parents("tr:first");        
                    row.insertAfter(row.next());         
                    ordenar_tabla();
                });');
                $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
                return $objResponse;
            }
 
            public function actualizarCategoria($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "UPDATE mos_tipo_inspecciones_categorias "
                            . " SET orden = $atr[orden], categoria = '$atr[categoria]', peso = '$atr[peso]'
                            WHERE id = $atr[id_unico]";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una secciÃ³n con el mismo nombre.";                        
                        return $error; 
                    }
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
                    
                    $respuesta = $this->modificarPlantilaInspecciones($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        if (strlen($parametros[id_unico_del])>0){
                            $parametros[id_unico_del] = substr($parametros[id_unico_del], 0, strlen($parametros[id_unico_del]) - 1);
                            $sql = "DELETE FROM mos_tipo_inspecciones_categorias WHERE id IN ($parametros[id_unico_del]) ";
                                //. " AND NOT id_unico IN (SELECT id_unico FROM mos_registro_formulario WHERE IDDoc = $parametros[id]) ";                               
                            $this->dbl->insert_update($sql);
                        }

                        $params[id_tipo_insp] = $parametros[id];
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                                                          
                            if (!isset($parametros["id_unico_din_$i"])){                                
                                
                                $params[categoria] = $parametros["nombre_din_$i"];                                
                                $params[orden] = $parametros["orden_din_$i"];  
                                if (isset($parametros["orden_din_$i"])){  
                                    $id_unico = $this->ingresarCategoria($params);
                                    {
                                        $sql = 'INSERT INTO mos_tipo_inspecciones_verificadores(id_tipo_insp_cat,id_tipo_insp, verificador, desc_verificador, vigencia, peso)'
                                                . ' SELECT ' . $id_unico . ', ' . $parametros[id] . ', descripcion, descripcion_larga, vigencia, peso '
                                                . ' FROM mos_documentos_formulario_items_temp '
                                                . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                                . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                        //echo $sql;
                                        $this->dbl->insert_update($sql);

                                    }
                                }
                            }
                            else
                                //if (isset($parametros["valores_din_$i"]))
                                { 
                                    $params[orden] = $parametros["orden_din_$i"];  
                                    $params[categoria] = $parametros["nombre_din_$i"];
                                    $params[peso] = 0;                                                                    
                                    $params[id_unico] = $parametros["id_unico_din_$i"];                                      
                                    $this->actualizarCategoria($params);
                                    $sql = 'INSERT INTO mos_tipo_inspecciones_verificadores(id_tipo_insp_cat,id_tipo_insp, verificador, desc_verificador, vigencia, peso)'
                                            . ' SELECT ' . $params[id_unico] . ', ' . $parametros[id] . ', descripcion, descripcion_larga, vigencia, peso '
                                            . ' FROM mos_documentos_formulario_items_temp '
                                            . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                            . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    $sql = 'update mos_tipo_inspecciones_verificadores,mos_documentos_formulario_items_temp
                                            set mos_tipo_inspecciones_verificadores.verificador = mos_documentos_formulario_items_temp.descripcion,
                                            mos_tipo_inspecciones_verificadores.desc_verificador = mos_documentos_formulario_items_temp.descripcion_larga,
                                            mos_tipo_inspecciones_verificadores.vigencia = mos_documentos_formulario_items_temp.vigencia,
                                            mos_tipo_inspecciones_verificadores.orden = mos_documentos_formulario_items_temp.orden
                                            where id_usuario = ' . $_SESSION['CookIdUsuario'] . ' and tok = ' . $parametros[tok_new_edit] . ' and mos_documentos_formulario_items_temp.fk_id_unico = ' . $parametros["cmb_din_$i"] . ' and mos_tipo_inspecciones_verificadores.id = mos_documentos_formulario_items_temp.fk_id_item and estado = 2';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    $sql = 'delete from mos_tipo_inspecciones_verificadores
                                            where id in (select fk_id_item from mos_documentos_formulario_items_temp 
                                            where id_usuario = ' . $_SESSION['CookIdUsuario'] . ' and tok = ' . $parametros[tok_new_edit] . ' and fk_id_unico = '. $params[id_unico] .' and estado = 3)
--                                            and 0 = (SELECT count(*) from mos_registro_item where id_unico = fk_id_unico and tipo = mos_documentos_formulario_items.tipo and id = valor)'
                                            ;
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    
                                }
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
                $val = $this->verPlantilaInspecciones($parametros[id]);
                $respuesta = $this->eliminarPlantilaInspecciones($parametros);
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
                $this->cargar_permisos($parametros);
                $grid = $this->verListaPlantilaInspecciones($parametros);                
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

                $val = $this->verPlantilaInspecciones($parametros[id]);

                            $contenido_1['CODIGO'] = ($val["codigo"]);
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'plantilla_inspecciones/';
                $template->setTemplate("verPlantilaInspecciones");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la PlantilaInspecciones";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>