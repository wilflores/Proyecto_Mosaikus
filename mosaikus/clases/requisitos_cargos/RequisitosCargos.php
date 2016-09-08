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
/***** mostrar area y cargo*************/
            
            public function colum_admin_matriz($tupla)
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
                $sql = "SELECT id,id_cargo,id_area,id_requisito
                         FROM mos_requisitos_cargos 
                         WHERE id_area = $id_area and id_cargo=$id_cargo order by id_requisito"; 
                $requisitos_cargos= $this->dbl->query($sql, array());
                return $requisitos_cargos;
            }
            public function ingresarRequisitosCargos($atr){
                try {
                    //print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                  import('clases.utilidades.NivelAcceso');
                  $this->restricciones = new NivelAcceso();

                    //VERIFICARRRRR POR QUE NO VALIDA EL ACCESO CON Las areS
                    /*Carga Acceso segun el arbol*/
                    if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                      //  echo "ENTRO ID";
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                 }  
                               
                    /*Valida Restriccion*/
                    if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_area]]))
                        return '- Acceso denegado para registrar Asociacion del requisito al cargo en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_area]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_area]][modificar] == S)))
                        return '- Acceso denegado para registrar Asociacion del requisito al cargo en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_area]][title] . '.';
                    /// fin de validacion de acceso por areas
                        for($i=0;$i<count($atr[vector_req_item])-1;$i++){
                            $req=$atr[vector_req_item][$i];
                            if(isset($atr[req_.$req])){//si esta marcado el requisito
                            $sql = "INSERT INTO mos_requisitos_cargos(id_cargo,id_area,id_requisito)
                            VALUES($atr[id_cargo],$atr[id_area],".$atr[vector_req_item][$i].")";
                                $this->dbl->insert_update($sql);
                            $sql = "SELECT MAX(id) ultimo FROM mos_requisitos_cargos"; 
                            $this->operacion($sql, $atr);
                            $vector_req_cargos[$i] = $this->dbl->data[0][0];//guardar el id de requisitos cargos
                            $nuevo = "Id Cargo: \'$atr[id_cargo]\', Id Requisito Items: \'$atr[vector_req_item][$i]\', ";
                            $this->registraTransaccionLog(97,$nuevo,'', $vector_req_cargos[$i]);
                             }
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
                   /* if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    
                    /*Valida Restriccion*/
                  /*  if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';
                    ***********/
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
                    */
                    if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    } 
                    if (count($this->restricciones->id_org_acceso_explicito)>0){                            
                        $acceso_areas = " AND mco.id IN (". implode(',', array_keys($this->restricciones->id_org_acceso_explicito)) . ")";
                    }
                       
                    /*FILTRO PARA EL ARBOL ORGANIZACIONAL*/
                    $filtro_ao='';
                    if ((strlen($atr["b-id_organizacion"])>0)){ // filtro para el arbol organizacional
                        $id_org = ($atr["b-id_organizacion"]);
                        $acceso_areas= " and mco.id in (". $id_org . ") ";
                   }           
                    
                    $sql = "SELECT COUNT(*) total_registros FROM mos_cargo mc
INNER JOIN mos_cargo_estrorg_arbolproc mco ON mc.cod_cargo = mco.cod_cargo
INNER JOIN mos_organizacion mo ON mo.id = mco.id
                         WHERE 1 = 1 $acceso_areas";
                     if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(mc.descripcion) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    
                        $sql .= " OR (upper(mo.title) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-id_cargo"])>0)
                        $sql .= " AND mc.descripcion = '". $atr["b-id_cargo"] . "'";
             if (strlen($atr["b-id_area"])>0)
                        $sql .= " AND mo.title = '". $atr["b-id_area"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];

            
                    $sql = "SELECT mco.id id_area,mc.cod_cargo cod_cargo, mo.title descrip_area,mc.descripcion descrip_cargo $sql_col_left
FROM mos_cargo mc
INNER JOIN mos_cargo_estrorg_arbolproc mco ON mc.cod_cargo = mco.cod_cargo
INNER JOIN mos_organizacion mo ON mo.id = mco.id where 1=1 $acceso_areas";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(mc.descripcion) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    
                        $sql .= " OR (upper(mo.title) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 
                    if (strlen($atr["b-id_cargo"])>0)
                        $sql .= " AND mc.descripcion = '". $atr["b-id_cargo"] . "'";
                   if (strlen($atr["b-id_area"])>0)
                        $sql .= " AND mo.title = '". $atr["b-id_area"] . "'";

                    $sql .= " order by mc.$atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarRequisitosCargos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verRequisitosCargos($atr[id_cargo],$atr[id_area]);
                        $respuesta = $this->dbl->delete("mos_requisitos_cargos", "id_area = ".$atr[id_area]." and id_cargo=".$atr[id_cargo]);
                        $nuevo = "Id Cargo: \'$val[id_cargo]\', Id Area: \'$val[id_area]\', ";
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
                //print_r($this->nombres_columnas);
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
               /* foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }*/

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
                    /*Carga Acceso segun el arbol*/
               /* if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                    $this->restricciones->cargar_acceso_nodos_explicito($parametros);
                } */
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

//VER MATRIZ DE COMPETENCIAS 04-09-16
            public function verListaMatriz($parametros){
        //print_r($parametros);
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarMatriz($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
               // print_r($data);
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                $familias=$this->cargar_parametros_familias();
                $id_familia=$familias[0][id];//para mostrar la primera familia en el listado. con el filtro se escoge la que se quiera mostrar
                /******** extraer item de familias****/  
                $items_familia=$this->items_familia_requisitos($id_familia);
                //print_r($this->nombres_columnas);
                $grid->SetConfiguracionMSKS("tblRequisitosCargos", "");
                $config_col=array();
                $config_col=array(array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_area], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_cargo], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>$familias[0][descripcion])/*,
               */
                );
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/

                $k = 3;
                foreach ($items_familia as $value) {                  
                    array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos((ucwords(strtolower($value[desc_items]))), "p$k", $columnas_fam)));
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
                    /*Carga Acceso segun el arbol*/
               /* if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                    $this->restricciones->cargar_acceso_nodos_explicito($parametros);
                } */
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id_area", "colum_admin_matriz");
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
/***************Listar de la matriz *************/
             public function listarMatriz($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    
                  $sql = "SELECT COUNT(*) total_registros FROM mos_cargo mc
INNER JOIN mos_cargo_estrorg_arbolproc mco ON mc.cod_cargo = mco.cod_cargo
INNER JOIN mos_organizacion mo ON mo.id = mco.id
                         WHERE 1 = 1 ";
                     if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(mc.descripcion) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    
                        $sql .= " OR (upper(mo.title) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-id_cargo"])>0)
                        $sql .= " AND mc.descripcion = '". $atr["b-id_cargo"] . "'";
             if (strlen($atr["b-id_area"])>0)
                        $sql .= " AND mo.title = '". $atr["b-id_area"] . "'";

                    /*FILTRO PARA EL ARBOL ORGANIZACIONAL*/
                    $filtro_ao='';
                    if ((strlen($atr["b-id_organizacion"])>0)){ // filtro para el arbol organizacional
                        $id_org = ($atr["b-id_organizacion"]);
                        $filtro_ao= " and mco.id in (". $id_org . ") ";
                    }// 
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];

                    $sql = "SELECT mo.title descrip_area,mc.descripcion descrip_cargo,mrf.descripcion descrip_familia,mif.descripcion descripcion_item,mr.nombre nombre_req
FROM mos_cargo mc
INNER JOIN mos_cargo_estrorg_arbolproc mco ON mc.cod_cargo = mco.cod_cargo
INNER JOIN mos_organizacion mo ON mo.id = mco.id
INNER JOIN mos_requisitos_cargos mrc on mrc.id_cargo=mc.cod_cargo and mrc.id_area=mo.id  INNER JOIN mos_requisitos_familias mrf
INNER JOIN mos_requisitos_items_familias mif ON mif.id_familia = mrf.id
INNER JOIN mos_requisitos_item mri ON mri.id_item = mif.id
INNER JOIN mos_requisitos mr ON mr.id = mri.id_requisitos
group by mri.id
ORDER BY mrf.id,mif.id";
                  /*  if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(mc.descripcion) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    
                        $sql .= " OR (upper(mo.title) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 
                    if (strlen($atr["b-id_cargo"])>0)
                        $sql .= " AND mc.descripcion = '". $atr["b-id_cargo"] . "'";
                   if (strlen($atr["b-id_area"])>0)
                        $sql .= " AND mo.title = '". $atr["b-id_area"] . "'";
*/
                   // $sql .= " order by mc.$atr[corder] $atr[sorder] ";
                    $sql .= " LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
/*************** obtener requisitos de un items de familias********/
         private function requisitos_items($id_item){
            $sql = "SELECT mr.nombre nombre_req FROM mos_requisitos mr WHERE id IN (SELECT  mr.id_requisito from mos_requisitos_item 
                    WHERE id_items =$id_item)";
                $requisitos_items = $this->dbl->query($sql, array());
                return $requisitos_items;

         }  
/***cargar nombre de columnas dinamicas de las familias***/
            private function items_familia_requisitos($id_familia){
                $sql = "SELECT mrf.id id_familia, mrf.descripcion descrip_familia, mif.id id_item, mif.descripcion desc_items, GROUP_CONCAT( DISTINCT mr.id ) id_requisito FROM mos_requisitos_familias mrf
INNER JOIN mos_requisitos_items_familias mif ON mif.id_familia = mrf.id 
INNER JOIN mos_requisitos_item mri ON mri.id_item = mif.id INNER JOIN mos_requisitos mr ON mr.id = mri.id_requisitos where mrf.id=$id_familia
GROUP BY mif.descripcion ORDER BY mif.id";
                $columnas_fam = $this->dbl->query($sql, array());
                return $columnas_fam;
            }        
//METODO PARA GENERAR LA MATRIZ DE COMPETENCIA 02/09/2016
            public function indexMatrizCompetencia($parametros)
            {
                //print_r($parametros);
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
                if ($parametros['corder'] == null) $parametros['corder']="cod_cargo";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */
                $familias=$this->cargar_parametros_familias();
                $id_familia=$familias[0][id];//para mostrar la primera familia en el listado. con el filtro se escoge la que se quiera mostrar
                /******** extraer item de familias****/  
                $items_familia=$this->items_familia_requisitos($id_familia);             
                $k = 3;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($items_familia as $value) {                    
                    $parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                  <label for="SelectAcc" class="col-md-9 control-label">' . $value[desc_items] . '</label>
                                  <div class="col-md-3">      
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                      </label>
                                  </div>
                            </div>';
                    $k++;
                }
            /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();

                $this->restricciones->cargar_permisos($parametros);
                $this->restricciones->cargar_acceso_nodos_explicito($parametros);

                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $this->arbol->jstree_ao(0,$parametros);
                /*FIN ARBOL ORGANIZACIONAL*/

                $grid = $this->verListaMatriz($parametros);
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
                        init_filtro_ao_multiple();');
                return $objResponse;
            } 
 
            public function indexRequisitosCargos($parametros)
            {
                //print_r($parametros);
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
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
            /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();

                $this->restricciones->cargar_permisos($parametros);
                $this->restricciones->cargar_acceso_nodos_explicito($parametros);

                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $this->arbol->jstree_ao(0,$parametros);
                /*FIN ARBOL ORGANIZACIONAL*/

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
                        init_filtro_ao_multiple();');
                return $objResponse;
            }

    /***cargar nombre de columnas dinamicas de las familias***/
            private function cargar_parametros_familias(){
                $sql = "SELECT id,codigo,descripcion FROM mos_requisitos_familias ORDER BY orden";
                $columnas_fam = $this->dbl->query($sql, array());
                return $columnas_fam;
            }


 /******** guardar relacion de requisitos cargos con curso seleccionado*****/
 public function GuardarRequisitoCapacitacion($parametros){
   // print_r($parametros);
        $combo.='<input type="hidden" id="id_capacitacion_'.$parametros[id_req_item].'" name="id_capacitacion_'.$parametros[id_req_item].'" value="'.$parametros[id_capacitacion].'"/>'; 
        $combo.='<input type="hidden" id="id_persona_'.$parametros[id_req_item].'" name="id_persona_'.$parametros[id_req_item].'" value="NULL"/>';   
    $combo .='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
     $combo .='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
     if($parametros[nuevo]!=0)//esto aplica si se va editar
                return $combo;
        $objResponse = new xajaxResponse();            
        $objResponse->addAssign('combos_form_'.$parametros[id_req_item],"innerHTML",$combo);
        $js="$('#combos_form_'+".$parametros[id_req_item].").show()";
        $objResponse->addScript("$js");
        return $objResponse;   


 }
 
/*********** obtener los combos parametros de un formulario seleccionado*******/
            public function Comboparametros($parametros){
                //print_r($parametros);
            $js = $combo=$completar_consulta=$primera_opcion_combo=$primera_opcion_vigencia='';

    $completar_consulta_combo=$completar_consulta_vigencia='';     
/************ id del tipo persona del formulario seleccionado***/
$sql_parametros_persona="select id_unico, Nombre,tipo
from mos_documentos_datos_formulario where IDDoc = ".$parametros[id_form]."  and tipo in (6)";//tipo persona


$parametros_index_persona = $this->dbl->query($sql_parametros_persona, array());
$id_tipo_persona=$parametros_index_persona[0][id_unico];
//$id_tipo_persona=1;//mientras para probar
$combo='<input type="hidden" id="id_persona_'.$parametros[id_req_item].'" name="id_persona_'.$parametros[id_req_item].'" value="'.$id_tipo_persona.'"/>';            
/********** consulta de parametros del formulario seleccionado*********/
$grupo1=$grupo2='';
if(($parametros[tipo_req]=='Listado') && ($parametros[vigencia_req]=='N')){//1. requisito tipo listado y no aplica vigencia
     $primera_opcion_combo='<option value="">--Seleccione--</option>'; 
    if($parametros[nuevo]!=0){// es edicion. debe salir marcada la opcion seleccionada
        //combo
        $sql_param_select="SELECT id_unico, Nombre FROM mos_documentos_datos_formulario WHERE IDDoc =".$parametros[id_form]." AND tipo IN ( 9 ) AND id_unico IN (SELECT id_parametro_formulario FROM mos_requisitos_parametros_index WHERE id_requisitos_formularios =".$parametros[nuevo].")";//obtener opcion marcada
        $res_primera_opcion = $this->dbl->query($sql_param_select, array());
        $completar_consulta_combo=' and id_unico<>'.$res_primera_opcion[0][id_unico];
        $primera_opcion_combo='<option selected value="'.$res_primera_opcion[0][id_unico].'">'.$res_primera_opcion[0][Nombre].'</option>'; 

    }
    $grupo1='Combo';
   $sql_parametros_1="select id_unico, Nombre from mos_documentos_datos_formulario 
where IDDoc =".$parametros[id_form]." and tipo in (9) ".$completar_consulta_combo;//tipo combo
$parametros_index_1 = $this->dbl->query($sql_parametros_1, array());

                //echo $sql; 
$combo .='<select id="parametro_'.$parametros[id_req_item].'" name="parametro_'.$parametros[id_req_item].'" class="form-control" data-validation="required"  onchange="CargaValoresParametros(this,'.$parametros[id_form].','.$parametros[id_req_item].','.$id_tipo_persona.',1)">';

 $combo.=$primera_opcion_combo;  
for($i=0;$i<count($parametros_index_1);$i++){
        $combo .='<option value="'.$parametros_index_1[$i][id_unico].'">'.$parametros_index_1[$i][Nombre].'</option>';
    }

$combo .='</select>';
if($parametros[nuevo]!=0){
    $datos[id_persona]=$id_tipo_persona;
$datos[id_combo]=$res_primera_opcion[0][id_unico];
$datos[id_vigencia]=NULL;
    return array($combo,$datos);//si se va editar y aparezca el seleccionado
}
}
if(($parametros[tipo_req]=='Listado') && ($parametros[vigencia_req]=='S')){//2. requisito tipo listado y aplica vigencia
    if($parametros[nuevo]!=0){// es edicion. debe salir marcada la opcion seleccionada
        //combo
        $sql_param_select="SELECT id_unico, Nombre FROM mos_documentos_datos_formulario WHERE IDDoc =".$parametros[id_form]." AND tipo IN ( 9 ) AND id_unico IN (SELECT id_parametro_formulario FROM mos_requisitos_parametros_index WHERE id_requisitos_formularios =".$parametros[nuevo].")";//obtener opcion marcada
        $res_primera_opcion = $this->dbl->query($sql_param_select, array());
        $completar_consulta_combo=' and id_unico<>'.$res_primera_opcion[0][id_unico];
        $datos[id_combo]=$res_primera_opcion[0][id_unico];
        $primera_opcion_combo='<option selected value="'.$res_primera_opcion[0][id_unico].'">'.$res_primera_opcion[0][Nombre].'</option>'; 
         //vigencia
         $sql_param_select="select id_unico, Nombre
from mos_documentos_datos_formulario where IDDoc=".$parametros[id_form]." and tipo in (13) AND id_unico IN (SELECT id_parametro_formulario FROM mos_requisitos_parametros_index WHERE id_requisitos_formularios =".$parametros[nuevo].")";//obtener opcion marcada
        $res_primera_opcion = $this->dbl->query($sql_param_select, array());
        $completar_consulta_vigencia=' and id_unico<>'.$res_primera_opcion[0][id_unico];
        $datos[id_vigencia]=$res_primera_opcion[0][id_unico];
        $primera_opcion_vigencia='<option selected value="'.$res_primera_opcion[0][id_unico].'">'.$res_primera_opcion[0][Nombre].'</option>';

    }
    $grupo1='Combo';
   $sql_parametros_1="select id_unico, Nombre from mos_documentos_datos_formulario 
where IDDoc =".$parametros[id_form]." and tipo in (9) ".$completar_consulta_combo;//tipo combo

$grupo2='Vigencia';
$sql_parametros_2="select id_unico, Nombre
from mos_documentos_datos_formulario where IDDoc=".$parametros[id_form]." and tipo in (13) ".$completar_consulta_vigencia;//tipo vigencia 


$parametros_index_1 = $this->dbl->query($sql_parametros_1, array());
$parametros_index_2 = $this->dbl->query($sql_parametros_2, array());

                //echo $sql; 
$combo .='<select id="parametro_'.$parametros[id_req_item].'" name="parametro_'.$parametros[id_req_item].'" class="selectpicker" class="form-control" data-validation="required" onChange="CargaValoresParametros(this,'.$parametros[id_form].','.$parametros[id_req_item].','.$id_tipo_persona.',2)" multiple>';

  $combo .='<optgroup label="'.$grupo1.'" data-max-options="1">';
  $combo .=$primera_opcion_combo;
    for($i=0;$i<count($parametros_index_1);$i++){
        $combo .='<option value="'.$parametros_index_1[$i][id_unico].'">'.$parametros_index_1[$i][Nombre].'</option>';
    }
  $combo .='</optgroup>';
   $combo .='<optgroup label="'.$grupo2.'" data-max-options="1">';
   $combo .=$primera_opcion_vigencia;
    for($j=0;$j<count($parametros_index_2);$j++){
        $combo .='<option value="'.$parametros_index_2[$j][id_unico].'">'.$parametros_index_2[$j][Nombre].'</option>';
    }
   $combo .='</optgroup>';
$combo .='</select>';
if($parametros[nuevo]!=0){
    $datos[id_persona]=$id_tipo_persona;
    return array($combo,$datos);//si se va editar y aparezca el seleccionado
}
}
if(($parametros[tipo_req]=='Unico') && ($parametros[vigencia_req]=='N')){//3. requisito tipo unico y no aplica vigencia
    $combo .='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
     $combo .='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
    if($parametros[nuevo]!=0){
    $datos[id_persona]=$id_tipo_persona;
    $datos[id_combo]=NULL;
    $datos[id_vigencia]=NULL;        
    return array($combo,$datos);//si se va editar y aparezca el seleccionado
    }

}
if(($parametros[tipo_req]=='Unico') && ($parametros[vigencia_req]=='S')){//4. requisito tipo unico y aplica vigencia
    $primera_opcion_vigencia='<option value="">--Seleccione--</option>';
        if($parametros[nuevo]!=0){// es edicion. debe salir marcada la opcion 
    //vigencia
         $sql_param_select="select id_unico, Nombre
from mos_documentos_datos_formulario where IDDoc=".$parametros[id_form]." and tipo in (13) AND id_unico IN (SELECT id_parametro_formulario FROM mos_requisitos_parametros_index WHERE id_requisitos_formularios =".$parametros[nuevo].")";//obtener opcion marcada
        $res_primera_opcion = $this->dbl->query($sql_param_select, array());
        $completar_consulta_vigencia=' and id_unico<>'.$res_primera_opcion[0][id_unico];
        $primera_opcion_vigencia='<option selected value="'.$res_primera_opcion[0][id_unico].'">'.$res_primera_opcion[0][Nombre].'</option>';

}
    $grupo1='Vigencia';
$sql_parametros_1="select id_unico, Nombre,tipo from mos_documentos_datos_formulario 
where IDDoc =".$parametros[id_form]." and tipo in (13)".$completar_consulta_vigencia;//tipo vigencia

$parametros_index_1 = $this->dbl->query($sql_parametros_1, array());

$combo .='<select id="parametro_'.$parametros[id_req_item].'" name="parametro_'.$parametros[id_req_item].'" class="form-control"  data-validation="required" onChange="CargaValoresParametros(this,'.$parametros[id_form].','.$parametros[id_req_item].','.$id_tipo_persona.',4)">';
  /*$combo.='<option value="">--Seleccione--</option>';
  $combo.='<option value="1">Mustard</option>
    <option value="2">Ketchup</option>
    <option value="3">Relish</option>';
*/
    $combo .=$primera_opcion_vigencia;
for($i=0;$i<count($parametros_index_1);$i++){
        $combo .='<option value="'.$parametros_index_1[$i][id_unico].'">'.$parametros_index_1[$i][Nombre].'</option>';
    }

$combo .='</select>';
if($parametros[nuevo]!=0){
    $datos[id_persona]=$id_tipo_persona;
    $datos[id_combo]=NULL;
    $datos[id_vigencia]=$res_primera_opcion[0][id_unico];
    return array($combo,$datos);//si se va editar y aparezca el seleccionado
}
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
            $js = $combo=$primera_opcion='';
          $primera_opcion= '<option value="">--Seleccione--</option>';

          $combo.='<input name="id_persona_'.$parametros[id_req_item].'" id="id_persona_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_persona].'"/>';//id tipo persona de cada formulario escogido
if($parametros[condicion]==1){//1. requisito tipo listado y no aplica vigencia - solo combo
    /********** consulta de valores del parametro seleccionado*********/
$sql_valores="SELECT id,descripcion FROM mos_documentos_formulario_items where fk_id_unico=".$parametros[id_combo];


if($parametros[nuevo]!=0){// es edicion. debe salir marcada la opcion seleccionada
        $sql_param_select="SELECT id, descripcion FROM mos_documentos_formulario_items
WHERE fk_id_unico IN (SELECT id_unico FROM mos_documentos_datos_formulario
WHERE IDDoc = ".$parametros[id_form]." AND  tipo IN ( 9 )) 
AND id IN (SELECT id_parametro_items FROM mos_requisitos_parametros_index WHERE id_requisitos_formularios =".$parametros[nuevo]." and id_parametro_items<>'NULL')";//obtener opcion marcada
      
        $res_primera_opcion = $this->dbl->query($sql_param_select, array());
        $completar_consulta_combo=' and id<>'.$res_primera_opcion[0][id];
        $primera_opcion='<option selected value="'.$res_primera_opcion[0][id].'">'.$res_primera_opcion[0][descripcion].'</option>'; 

$sql_valores="SELECT id, descripcion
FROM mos_documentos_formulario_items
WHERE fk_id_unico IN (SELECT id_unico FROM mos_documentos_datos_formulario
WHERE IDDoc = ".$parametros[id_form]." AND tipo IN ( 9 ))". $completar_consulta_combo;
             }

$valores_index = $this->dbl->query($sql_valores, array());
   $combo .='<select id="valores_'.$parametros[id_req_item].'" name="valores_'.$parametros[id_req_item].'" class="form-control" data-validation="required">';

  $combo.=$primera_opcion;
    for($i=0;$i<count($valores_index);$i++){
        $combo .='<option value="'.$valores_index[$i][id].'">'.$valores_index[$i][descripcion].'</option>';
    }
      
 $combo .='</select>';
  $combo .='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_combo].'"/>';
  $combo .='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>'; 
 if($parametros[nuevo]!=0){
    return $combo;//si se va editar y aparezca el seleccionado
}

}

if($parametros[condicion]==2){//2. requisito tipo listado y aplica vigencia - combo y vigencia
    $sql_valores="SELECT id,descripcion FROM mos_documentos_formulario_items where fk_id_unico=".$parametros[id_combo];

if($parametros[nuevo]!=0){// es edicion. debe salir marcada la opcion seleccionada
        $sql_param_select="SELECT id, descripcion FROM mos_documentos_formulario_items
WHERE fk_id_unico IN (SELECT id_unico FROM mos_documentos_datos_formulario
WHERE IDDoc = ".$parametros[id_form]." AND  tipo IN ( 9 )) 
AND id IN (SELECT id_parametro_items FROM mos_requisitos_parametros_index WHERE id_requisitos_formularios =".$parametros[nuevo]." and id_parametro_items<>'NULL')";//obtener opcion marcada
      
        $res_primera_opcion = $this->dbl->query($sql_param_select, array());
        $completar_consulta_combo=' and id<>'.$res_primera_opcion[0][id];
        $primera_opcion='<option selected value="'.$res_primera_opcion[0][id].'">'.$res_primera_opcion[0][descripcion].'</option>'; 

$sql_valores="SELECT id, descripcion
FROM mos_documentos_formulario_items
WHERE fk_id_unico IN (SELECT id_unico FROM mos_documentos_datos_formulario
WHERE IDDoc = ".$parametros[id_form]." AND tipo IN ( 9 ))". $completar_consulta_combo;
             }
    /********** consulta de valores del parametro seleccionado*********/

$valores_index = $this->dbl->query($sql_valores, array());
$combo .='<select id="valores_'.$parametros[id_req_item].'" name="valores_'.$parametros[id_req_item].'" class="form-control" data-validation="required">';


  $combo.=$primera_opcion;
    for($i=0;$i<count($valores_index);$i++){
        $combo .='<option value="'.$valores_index[$i][id].'">'.$valores_index[$i][descripcion].'</option>';
    }
      
 $combo .='</select>'; 
  $combo.='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_combo].'"/>';
  $combo.='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_vigencia].'"/>';
 if($parametros[nuevo]!=0){
    return $combo;//si se va editar y aparezca el seleccionado
}
 

}
if($parametros[condicion]==3){//3. no aplica ni vigencia ni combo
$combo.='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
     $combo.='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>';
      if($parametros[nuevo]!=0){
       // echo "aquiiiiiiiiiii";
    return $combo;//si se va editar y aparezca el seleccionado
}

}
if($parametros[condicion]==4){//4. requisito tipo unico y aplica vigencia -aplica vigencia
    $combo.='<input name="id_vigencia_'.$parametros[id_req_item].'" id="id_vigencia_'.$parametros[id_req_item].'" type="hidden" value="'.$parametros[id_vigencia].'"/>';
     $combo.='<input name="id_combo_'.$parametros[id_req_item].'" id="id_combo_'.$parametros[id_req_item].'" type="hidden" value="NULL"/>'; 
      if($parametros[nuevo]!=0){
       // echo "aquiiiiiiiiiii";
    return $combo;//si se va editar y aparezca el seleccionado
}
}
 
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
/*****************************************************/ 
            public function crear($parametros)
            {
                $js='';
                //print_r($parametros);
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $requisitos_items= '';
                $contenido_1   = array();
                import("clases.organizacion.ArbolOrganizacional");
                $arbol = new ArbolOrganizacional();
                $areas_involucradas=$arbol->BuscaOrgNivelPadres($parametros[id_area]);// areas padres
                $sql_area="select title from mos_organizacion where id=".$parametros[id_area];
                 $this->operacion($sql_area, $parametros);
                $descripcion_area=$this->dbl->data[0][title];
               // $descripcion_area=$arbol->BuscaOrganizacional(array('id_organizacion'=>$parametros[id_area]));//obtener descripcion del area
                $sql_cargo="select descripcion from mos_cargo where cod_cargo=".$parametros[id_cargo];
                    $this->operacion($sql_cargo, $parametros);
                $descripcion_cargo=$this->dbl->data[0][descripcion];
                $contenido_1[OTROS_CAMPOS] = '<input type="hidden"  value="'.$parametros[id_area].'" id="id_area" name="id_area"/>
                                        <input type="hidden" value="'.$parametros[id_cargo].'" id="id_cargo" name="id_cargo" />
                                        <input type="hidden" value="'.$parametros[modo].'" id="modo" name="modo" />
                                        <input type="hidden" value="'.$parametros[cod_link].'" id="cod_link" name="cod_link" />
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
                $contenido_1[FAMILIAS]='<div class="form-group"> 
                                    <div class="col-md-24">
                                        <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">';
                $k=2;
                $contenido_1[FAMILIAS].='<li id="li1"><a href="#hv-red-'.$k.'" data-toggle="tab" style="padding: 8px 32px;">Requisitos Minimos</a></li>';
      
                $contenido_1[FAMILIAS].='</ul>';
                $contenido_1[ITEMS_FAMILIA].='<div class="tab-pane active" id="hv-red-'.$k.'">';
                        /***** obtener requisitos del items mostrado*/
                $sql_requisitos= "SELECT distinct mr.id id_req,mr.nombre nomb_req,mr.tipo tipo_req,mr.vigencia vigencia_req
FROM mos_requisitos mr
INNER JOIN mos_requisitos_organizacion mro ON mro.id_requisito = mr.id
INNER JOIN mos_organizacion mo ON mo.id = mro.id_area
INNER JOIN mos_cargo_estrorg_arbolproc map ON map.id = mro.id_area
AND mo.id = map.id
WHERE map.cod_cargo =".$parametros[id_cargo]." AND mo.id in(".$areas_involucradas.") and mr.estatus=1 ORDER BY id_req";
/*AND (
mo.parent_id =".$parametros[id_area]."
OR mo.id =".$parametros[id_area]."
)";*/

                        //Secho  $sql_requisitos;
                $requisitos = $this->dbl->query($sql_requisitos, array());
                for($x=0;$x<count($requisitos);$x++){//requisitos que cumplen con la condicion
                    $requisitos_items.=$requisitos[$x][id_req].',';
                    $contenido_1[ITEMS_FAMILIA].='<br><label for="vigencia" class="col-md-6 control-label">'.$requisitos[$x][nomb_req].'</label>
                         <div class="col-md-6">
                                    <label class="checkbox-inline" style="padding-top: 5px;">
                                        <input type="checkbox" name="req_'.$requisitos[$x][id_req].'" id="req_'.$requisitos[$x][id_req].'" value="'.$requisitos[$x][id_req].'" onchange="CargaComboForm(this,0)"></label>
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
/********** consulta para las capacitaciones***********/
    $sql_cursos="SELECT * FROM mos_cursos WHERE vigencia = 'S' AND aplica_vigencia = 'N'";
    $capacitaciones = $this->dbl->query($sql_cursos, array());

}
if(($requisitos[$x][tipo_req]=='Unico') && ($requisitos[$x][vigencia_req]=='S')){//4. requisito tipo unico y aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (13))";
/********** consulta para capacitaciones***/
    $sql_cursos="SELECT * FROM mos_cursos WHERE vigencia = 'S' AND aplica_vigencia = 'S'";
    $capacitaciones = $this->dbl->query($sql_cursos, array());
}
    // echo $sql_formulario;                                      
     $formularios_req = $this->dbl->query($sql_formulario, array());
     //Combo para formularios
                    $contenido_1[ITEMS_FAMILIA].= '<div id="formulario_doc_'.$requisitos[$x][id_req].'" style="display:none;"  class="col-md-10">';
                    if(($requisitos[$x][tipo_req]=='Listado')){
                    $contenido_1[ITEMS_FAMILIA].='<select id="form_'.$requisitos[$x][id_req].'" name="form_'.$requisitos[$x][id_req].'" class="form-control" class="selectpicker" onchange="CargaComboParametros(this,'.$requisitos[$x][id_req].',\''.$requisitos[$x][tipo_req].'\',\''.$requisitos[$x][vigencia_req].'\',0)">';
                    $contenido_1[ITEMS_FAMILIA].='<option value="">--Seleccione--</option>';
                            for($z=0;$z<count($formularios_req);$z++){
                                $contenido_1[ITEMS_FAMILIA].= '<option  value="'.$formularios_req[$z][IDDoc].'">'.$formularios_req[$z][Codigo_doc].'-'.$formularios_req[$z][nombre_doc].'</option>';
                            }
                            
                    $contenido_1[ITEMS_FAMILIA].= '</select>';
                    }
                    /******* SI el requisito es unico***/
                     if(($requisitos[$x][tipo_req]=='Unico')){

                    $contenido_1[ITEMS_FAMILIA].='<select id="form_'.$requisitos[$x][id_req].'" name="form_'.$requisitos[$x][id_req].'" class="form-control" class="selectpicker" onchange="CargaComboParametros(this,'.$requisitos[$x][id_req].',\''.$requisitos[$x][tipo_req].'\',\''.$requisitos[$x][vigencia_req].'\',0)">';
                    $contenido_1[ITEMS_FAMILIA].='<optgroup label="Formularios"><option value="">--Seleccione--</option>';
                            for($z=0;$z<count($formularios_req);$z++){
                                $contenido_1[ITEMS_FAMILIA].= '<option  value="'.$formularios_req[$z][IDDoc].'">'.$formularios_req[$z][Codigo_doc].'-'.$formularios_req[$z][nombre_doc].'</option>';
                            }
                            $contenido_1[ITEMS_FAMILIA].='</optgroup>
                            <optgroup label="Capacitaciones">';
                            for($z=0;$z<count($capacitaciones);$z++){
                                $contenido_1[ITEMS_FAMILIA].= '<option  value="cap_'.$capacitaciones[$z][cod_curso].'">'.$capacitaciones[$z][cod_curso].'-'.$capacitaciones[$z][identificacion].'</option></optgroup>';
                            }
                            
                    $contenido_1[ITEMS_FAMILIA].= '</select>';
                    }

                     $contenido_1[ITEMS_FAMILIA].= '</div>'; 
$js.="$('#form_".$requisitos[$x][id_req]."').selectpicker({
                                            style: 'btn-combo'
                                          });";
/*************** combos de los parametros*****/
                            $contenido_1[ITEMS_FAMILIA].= '<br>
                                           <label  style="padding-top: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><div class="col-md-12"></div><div id="combos_form_'.$requisitos[$x][id_req].'" class="col-md-10" style="display:none;" >';  

$contenido_1[ITEMS_FAMILIA].= '</div>';

/************** Combo en caso de que aplique de los valores*/
                                         $contenido_1[ITEMS_FAMILIA].='
                                           <label  style="padding-top: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><div class="col-md-12"></div><div id="valores_form_'.$requisitos[$x][id_req].'" class="col-md-10" style="display:none;"> <br><div class="col-md-6"></div></div><br><br>';

                                 
                              }
                               $contenido_1[ITEMS_FAMILIA].='</div> 
                                  <br>';

                             
                
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
               // $objResponse->addScript("$js");             
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

 $objResponse->addScript("$js");
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
                //print_r($parametros);
                $requisitos_items = array();
                    if(strpos($parametros[vector_req_item],',')){    
                        $requisitos_items = explode(",", $parametros[vector_req_item]);
                    }
                    else{
                        $requisitos_items[] = $parametros[vector_req_item];                    
                    }
                $parametros[vector_req_item]=$requisitos_items;


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
                    //echo $requisitos_cargos;
                    if (is_array($requisitos_cargos)) {//guardar relacion requisitos cargos con los formularios
                        for($i=0;$i<count($parametros[vector_req_item])-1;$i++){
                            $req=$parametros[vector_req_item][$i];
                            if(isset($parametros[req_.$req])){//si esta marcado el requisito
                        $id_form=$parametros["form_".$req];
                        $id_vigencia=$parametros["id_vigencia_".$req];
                        $id_combo=$parametros["id_combo_".$req];
                        $id_persona=$parametros["id_persona_".$req];
                        $id_valor_params=$parametros["valores_".$req];
                        $id_capacitacion=$parametros["id_capacitacion_".$req];
                        if(isset($id_capacitacion)){//se escogio n curso
                            $sql = "INSERT INTO mos_requisitos_cursos(id_requisito_cargo,cod_curso)
                            VALUES(".$requisitos_cargos[$i].",".$id_capacitacion.")";
                                $this->dbl->insert_update($sql);

                        }
                        else{
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
                            //echo $sql_parametros;
                            $this->dbl->insert_update($sql_parametros); 
                            }   
                            }
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito','Asignacion de Requisitos asignados al cargo exitosamente');
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$requisitos_cargos);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        
                return $objResponse;
            }
/************ obtener formulario del requisito seleccionado editar *******/
public function formulario_select($req_cargo){
    $parametros= array();
     $sql_formulario_marcado="select IDDoc,Codigo_doc,nombre_doc,mrf.id id_req_form from mos_documentos,mos_requisitos_formularios mrf where IDDoc=mrf.id_documento_form AND IDDoc in (select id_documento_form from mos_requisitos_formularios where id_requisito_cargo=".$req_cargo.") AND mrf.id_requisito_cargo=".$req_cargo;
        $this->operacion($sql_formulario_marcado, $parametros);
        return $this->dbl->data[0];


}
public function curso_select($req_cargo){
    $parametros= array();
     $sql_curso_marcado="SELECT mc.cod_curso,identificacion,mrc.id id_req_curso FROM mos_cursos mc INNER JOIN mos_requisitos_cursos mrc ON mc.cod_curso=mrc.cod_curso where mrc.id_requisito_cargo=".$req_cargo;
        $this->operacion($sql_curso_marcado, $parametros);
        return $this->dbl->data[0];


}

     
 
            public function editar($parametros)
            {
               $js=$respuesta='';
               // print_r($parametros);
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                import("clases.organizacion.ArbolOrganizacional");
                $arbol = new ArbolOrganizacional();
                $areas_involucradas=$arbol->BuscaOrgNivelPadres($parametros[id_area]);// areas padres
                $requisitos_cargos = $this->verRequisitosCargos($parametros[id_cargo],$parametros[id_area]); 

                //construir el formulario con los datos cargados
                $sql_area="select title from mos_organizacion where id=".$parametros[id_area];
                 $this->operacion($sql_area, $parametros);
                $descripcion_area=$this->dbl->data[0][title];
                $sql_cargo="select descripcion from mos_cargo where cod_cargo=".$parametros[id_cargo];
                    $this->operacion($sql_cargo, $parametros);
                $descripcion_cargo=$this->dbl->data[0][descripcion];
                $contenido_1[OTROS_CAMPOS] = '<input type="hidden"  value="'.$parametros[id_area].'" id="id_area" name="id_area"/>
                                        <input type="hidden" value="'.$parametros[id_cargo].'" id="id_cargo" name="id_cargo" />
                                        <input type="hidden" value="'.$parametros[modo].'" id="modo" name="modo" />
                                        <input type="hidden" value="'.$parametros[cod_link].'" id="cod_link" name="cod_link" />
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
/*** PESTAÑA DE REQUISITOS******/
                 $contenido_1[FAMILIAS]='<div class="form-group"> 
                                    <div class="col-md-24">
                                        <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">';
                $k=2;
                $contenido_1[FAMILIAS].='<li id="li1"><a href="#hv-red-'.$k.'" data-toggle="tab" style="padding: 8px 32px;">Requisitos Minimos</a></li>';
      
                $contenido_1[FAMILIAS].='</ul>';
                $contenido_1[ITEMS_FAMILIA].='<div class="tab-pane active" id="hv-red-'.$k.'">';
                /********* *****/ 
/***** obtener requisitos del items mostrado*/
                $sql_requisitos= "SELECT distinct mr.id id_req,mr.nombre nomb_req,mr.tipo tipo_req,mr.vigencia vigencia_req
FROM mos_requisitos mr
INNER JOIN mos_requisitos_organizacion mro ON mro.id_requisito = mr.id
INNER JOIN mos_organizacion mo ON mo.id = mro.id_area
INNER JOIN mos_cargo_estrorg_arbolproc map ON map.id = mro.id_area
AND mo.id = map.id
WHERE map.cod_cargo =".$parametros[id_cargo]." AND mo.id in(".$areas_involucradas.") and mr.estatus=1 ORDER BY id_req";

                        //Secho  $sql_requisitos;
    $requisitos = $this->dbl->query($sql_requisitos, array());
    for($x=0;$x<count($requisitos);$x++){//requisitos que cumplen con la condicion
                    $marcado='';
                    $primera_opcion='<option value="">--Seleccione--</option>';
                    for($y=0;$y<count($requisitos_cargos);$y++){//ver cuales requisitos estan marcados seleccionADOS
                        if($requisitos_cargos[$y][id_requisito]==$requisitos[$x][id_req]){//el requisito esta ya seleccionado
                            $marcado='checked';
                            $req_cargo=$requisitos_cargos[$y][id];
                            break;
                        }
                    }
                    //echo "Requisito cargo ".$req_cargo;
                    $requisitos_items.=$requisitos[$x][id_req].',';
                    $contenido_1[ITEMS_FAMILIA].='<br><label for="vigencia" class="col-md-6 control-label">'.$requisitos[$x][nomb_req].'</label>
                         <div class="col-md-6">
                                    <label class="checkbox-inline" style="padding-top: 5px;">';
                                    if($marcado=='checked'){
                                        $contenido_1[ITEMS_FAMILIA].='<input type="checkbox" '.$marcado.' name="req_'.$requisitos[$x][id_req].'" id="req_'.$requisitos[$x][id_req].'" value="'.$requisitos[$x][id_req].'" onchange="CargaComboForm(this,1)">';
                                    }
                                    else{
                                        $contenido_1[ITEMS_FAMILIA].='<input type="checkbox" name="req_'.$requisitos[$x][id_req].'" id="req_'.$requisitos[$x][id_req].'" value="'.$requisitos[$x][id_req].'" onchange="CargaComboForm(this,0)">';
                                    }

                                    $contenido_1[ITEMS_FAMILIA].='</label></div>';
                                    
                                    
/*** consulta para obtener los formularios que cumplan con la condicion del requisito ***/
        $atrib['id_req_item']=$requisitos[$x][id_req];
        $atrib['tipo_req']=$requisitos[$x][tipo_req];
        $atrib['vigencia_req']=$requisitos[$x][vigencia_req];
        $resp=$respuestas=$respuesta=$respuesta_2='';//variables auxiliares
if(($requisitos[$x][tipo_req]=='Listado') && ($requisitos[$x][vigencia_req]=='N')){//1. requisito tipo listado y no aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S'
     AND muestra_doc = 'S' and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc 
    HAVING COUNT(id_unico) <= 1) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (9))";
    if($marcado=='checked'){//obtener el formulario que fue seleccionado
        $datos_form=$this->formulario_select($req_cargo);
        $primera_opcion='<option  value="'.$datos_form[IDDoc].'">'.$datos_form[Codigo_doc].'-'.$datos_form[nombre_doc].'</option>';
            $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S'
     AND muestra_doc = 'S' and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc 
    HAVING COUNT(id_unico) <= 1) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (9)) and IDDoc <>".$datos_form[IDDoc];
        $atrib['id_form']=$datos_form[IDDoc];
        $atrib['nuevo']=$datos_form[id_req_form];//editar      
        $atrib['id_req_formulario']=$datos_form[id_req_form];// id de la relacion del form seleccionado
        $respuestas= $this->Comboparametros($atrib);
        $respuesta=$respuestas[0];
        $atrib['condicion']=1;
        $atrib[id_combo]=$respuestas[1][id_combo];
        $atrib[id_vigencia]=$respuestas[1][id_vigencia];
        $atrib[id_persona]=$respuestas[1][id_persona];
        $respuesta_2=$this->ValoresParametros($atrib);
        $js.=  '$("#formulario_doc_"+'.$requisitos[$x][id_req].').show();
                $("#combos_form_"+'.$requisitos[$x][id_req].').show();';

        $js.=   "$('#parametro_".$requisitos[$x][id_req]."').selectpicker({
             style: 'btn-combo'});";

        $js.="$('#valores_form_'+".$requisitos[$x][id_req].").show();";
        $js.="$('#valores_".$requisitos[$x][id_req]."').selectpicker({
                                            style: 'btn-combo'
                                          });";

    }
}
if(($requisitos[$x][tipo_req]=='Listado') && ($requisitos[$x][vigencia_req]=='S')){//2. requisito tipo listado y aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (13)) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (9))";
    if($marcado=='checked'){//obtener el formulario que fue seleccionado
        $datos_form=$this->formulario_select($req_cargo);
        $primera_opcion='<option  value="'.$datos_form[IDDoc].'">'.$datos_form[Codigo_doc].'-'.$datos_form[nombre_doc].'</option>';
            $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (13)) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (9)) and IDDoc <>".$datos_form[IDDoc];
        $atrib['id_form']=$datos_form[IDDoc];
        $atrib['nuevo']=$datos_form[id_req_form];//editar      
        $atrib['id_req_formulario']=$datos_form[id_req_form];// id de la relacion del form seleccionado
        $respuestas= $this->Comboparametros($atrib);
        $respuesta=$respuestas[0];
        $atrib['condicion']=2;
        $atrib[id_combo]=$respuestas[1][id_combo];
        $atrib[id_vigencia]=$respuestas[1][id_vigencia];
        $atrib[id_persona]=$respuestas[1][id_persona];
        $respuesta_2=$this->ValoresParametros($atrib);
        $js.=  '$("#formulario_doc_"+'.$requisitos[$x][id_req].').show();
                $("#combos_form_"+'.$requisitos[$x][id_req].').show();';

        $js.=   "$('#parametro_".$requisitos[$x][id_req]."').selectpicker({
             style: 'btn-combo'});";

        $js.="$('#valores_form_'+".$requisitos[$x][id_req].").show();";
        $js.="$('#valores_".$requisitos[$x][id_req]."').selectpicker({
                                            style: 'btn-combo'
                                          });";

        
    }
}
if(($requisitos[$x][tipo_req]=='Unico') && ($requisitos[$x][vigencia_req]=='N')){//3. requisito tipo unico y no aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 )";
/********** consulta para las capacitaciones***********/
    $sql_cursos="SELECT * FROM mos_cursos WHERE vigencia = 'S' AND aplica_vigencia = 'N'";
    if($marcado=='checked'){//obtener el formulario O CURSO  que fue seleccionado
        $datos_form=$this->formulario_select($req_cargo);
        $datos_curso=$this->curso_select($req_cargo);
       // print_r($datos_form);
        if(isset($datos_form)){// SI FUE SELECCIONADO UN FORM
            $primera_opcion='<option  value="'.$datos_form[IDDoc].'">'.$datos_form[Codigo_doc].'-'.$datos_form[nombre_doc].'</option>';
            $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) and IDDoc <>".$datos_form[IDDoc];
            $atrib['id_form']=$datos_form[IDDoc];
            $atrib['nuevo']=$datos_form[id_req_form];//editar 
            $atrib['id_req_formulario']=$datos_form[id_req_form];// id de la relacion del form seleccionado 
            $respuestas= $this->Comboparametros($atrib);
            $respuesta=$respuestas[0];
            $atrib[condicion]=3;
            $atrib[id_combo]=$respuestas[1][id_combo];
            $atrib[id_vigencia]=$respuestas[1][id_vigencia];
            $atrib[id_persona]=$respuestas[1][id_persona];
            $respuesta_2=$this->ValoresParametros($atrib);
        }
        if(isset($datos_curso)){// SI FUE SELECCIONADO UN CURSO
            $primera_opcion='<option  value="cap_'.$datos_curso[cod_curso].'">'.$datos_curso[cod_curso].'-'.$datos_curso[identificacion].'</option>';
            $sql_cursos="SELECT * FROM mos_cursos WHERE vigencia = 'S' AND aplica_vigencia = 'N' and cod_curso <>".$datos_curso[cod_curso];
            $atrib['id_capacitacion']=$datos_curso[cod_curso];
            $atrib['nuevo']=$datos_curso[id_req_curso];//editar  
            $resp=$this->GuardarRequisitoCapacitacion($atrib);
        }


    $js.=' $("#formulario_doc_"+'.$requisitos[$x][id_req].').show();';
    $js.='$("#combos_form_"+'.$requisitos[$x][id_req].').show();';

    }
// extraer datos de las capacitaciones
    $capacitaciones = $this->dbl->query($sql_cursos, array());

}
if(($requisitos[$x][tipo_req]=='Unico') && ($requisitos[$x][vigencia_req]=='S')){//4. requisito tipo unico y aplica vigencia
    $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (13))";
/********** consulta para capacitaciones***/
    $sql_cursos="SELECT * FROM mos_cursos WHERE vigencia = 'S' AND aplica_vigencia = 'S'";
    if($marcado=='checked'){//obtener el formulario que fue seleccionado
        $datos_form=$this->formulario_select($req_cargo);
        $datos_curso=$this->curso_select($req_cargo);

        if(isset($datos_form)){// SI FUE SELECCIONADO UN FORM
            $primera_opcion='<option  value="'.$datos_form[IDDoc].'">'.$datos_form[Codigo_doc].'-'.$datos_form[nombre_doc].'</option>';
            $sql_formulario="select IDDoc,Codigo_doc,nombre_doc from mos_documentos where vigencia = 'S' AND muestra_doc = 'S'  and formulario='S' and IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (6) GROUP BY IDDoc  
HAVING COUNT(id_unico) <= 1 ) AND IDDoc in (select IDDoc from mos_documentos_datos_formulario where tipo in (13)) and IDDoc <>".$datos_form[IDDoc];
            $atrib['id_form']=$datos_form[IDDoc];
            $atrib['nuevo']=$datos_form[id_req_form];//editar  
            $atrib['id_req_formulario']=$datos_form[id_req_form];
            $respuestas= $this->Comboparametros($atrib);
            $respuesta=$respuestas[0];
            $atrib[condicion]=3;
            $atrib[id_combo]=$respuestas[1][id_combo];
            $atrib[id_vigencia]=$respuestas[1][id_vigencia];
            $atrib[id_persona]=$respuestas[1][id_persona];
            $respuesta_2=$this->ValoresParametros($atrib);
        }
        if(isset($datos_curso)){// SI FUE SELECCIONADO UN CURSO
            $primera_opcion='<option  value="cap_'.$datos_curso[cod_curso].'">'.$datos_curso[cod_curso].'-'.$datos_curso[identificacion].'</option>';
            $sql_cursos="SELECT * FROM mos_cursos WHERE vigencia = 'S' AND aplica_vigencia = 'S' and cod_curso <>".$datos_curso[cod_curso];
            $atrib['id_capacitacion']=$datos_curso[cod_curso];
            $atrib['nuevo']=$datos_curso[id_req_curso];//editar  
            $resp=$this->GuardarRequisitoCapacitacion($atrib);
        }
        

    $js.=' $("#formulario_doc_"+'.$requisitos[$x][id_req].').show();';
    $js.='$("#combos_form_"+'.$requisitos[$x][id_req].').show();';

    }


    $capacitaciones = $this->dbl->query($sql_cursos, array());
}
    // echo $sql_formulario;                                      
     $formularios_req = $this->dbl->query($sql_formulario, array());
     //Combo para formularios
                    $contenido_1[ITEMS_FAMILIA].= '<div id="formulario_doc_'.$requisitos[$x][id_req].'" style="display:none;"  class="col-md-10">';
                    if(($requisitos[$x][tipo_req]=='Listado')){
                    $contenido_1[ITEMS_FAMILIA].='<select id="form_'.$requisitos[$x][id_req].'" name="form_'.$requisitos[$x][id_req].'" class="form-control" class="selectpicker" onchange="CargaComboParametros(this,'.$requisitos[$x][id_req].',\''.$requisitos[$x][tipo_req].'\',\''.$requisitos[$x][vigencia_req].'\',0)">';
                    $contenido_1[ITEMS_FAMILIA].=$primera_opcion;
                            for($z=0;$z<count($formularios_req);$z++){
                                $contenido_1[ITEMS_FAMILIA].= '<option  value="'.$formularios_req[$z][IDDoc].'">'.$formularios_req[$z][Codigo_doc].'-'.$formularios_req[$z][nombre_doc].'</option>';
                            }
                            
                    $contenido_1[ITEMS_FAMILIA].= '</select>';
                    }
                    /******* SI el requisito es unico***/
                     if(($requisitos[$x][tipo_req]=='Unico')){

                    $contenido_1[ITEMS_FAMILIA].='<select id="form_'.$requisitos[$x][id_req].'" name="form_'.$requisitos[$x][id_req].'" class="form-control" class="selectpicker" onchange="CargaComboParametros(this,'.$requisitos[$x][id_req].',\''.$requisitos[$x][tipo_req].'\',\''.$requisitos[$x][vigencia_req].'\',0)">';
                        $contenido_1[ITEMS_FAMILIA].=$primera_opcion;
                    $contenido_1[ITEMS_FAMILIA].='<optgroup label="Formularios">';
                            for($z=0;$z<count($formularios_req);$z++){
                                $contenido_1[ITEMS_FAMILIA].= '<option  value="'.$formularios_req[$z][IDDoc].'">'.$formularios_req[$z][Codigo_doc].'-'.$formularios_req[$z][nombre_doc].'</option>';
                            }
                            $contenido_1[ITEMS_FAMILIA].='</optgroup>
                            <optgroup label="Capacitaciones">';
                            for($z=0;$z<count($capacitaciones);$z++){
                                $contenido_1[ITEMS_FAMILIA].= '<option  value="cap_'.$capacitaciones[$z][cod_curso].'">'.$capacitaciones[$z][cod_curso].'-'.$capacitaciones[$z][identificacion].'</option></optgroup>';
                            }
                            
                    $contenido_1[ITEMS_FAMILIA].= '</select>';
                    }

                     $contenido_1[ITEMS_FAMILIA].= '</div>'; 
$js.="$('#form_".$requisitos[$x][id_req]."').selectpicker({
                                            style: 'btn-combo'
                                          });";
/*************** combos de los parametros*****/
                            $contenido_1[ITEMS_FAMILIA].= '<br>
                                           <label  style="padding-top: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><div class="col-md-12"></div><div id="combos_form_'.$requisitos[$x][id_req].'" class="col-md-10"  style="display:none;">';  
                                          $contenido_1[ITEMS_FAMILIA].= $respuesta.$resp;

$contenido_1[ITEMS_FAMILIA].= '</div>';

/************** Combo en caso de que aplique de los valores*/
                                         $contenido_1[ITEMS_FAMILIA].='
                                           <label  style="padding-top: 2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><div class="col-md-12"></div><div id="valores_form_'.$requisitos[$x][id_req].'" class="col-md-10" style="display:none;">';
                                        $contenido_1[ITEMS_FAMILIA].= $respuesta_2.$resp;
                                           $contenido_1[ITEMS_FAMILIA].='<br><div class="col-md-6"></div></div><br><br>';

                                 
                              }
                               $contenido_1[ITEMS_FAMILIA].='</div> 
                                  <br>';

                             
                
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

                $contenido_1['ID_CARGO'] = $parametros[id_cargo];
                $contenido_1['ID_AREA'] = $parametros[id_area];

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
                 $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
                    $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");
                $objResponse->addScript("$js");
                return $objResponse;
            }
/************* eliminar relacion de cargos -area con requisitos ********/
public function eliminarRelacionRequisitosCargos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_requisitos_cargos", "id_area = ".$atr[id_area]." and id_cargo=".$atr[id_cargo]);
                        return true;
                    } catch(Exception $e) {
                        $error = $e->getMessage();            
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
                $requisitos = array();
                if(strpos($parametros[vector_req_item],',')){    
                    $requisitos = explode(",", $parametros[vector_req_item]);
                }
                else{
                    $requisitos[] = $parametros[vector_req_item];
                }
                    $parametros[vector_req_item]=$requisitos;


                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    //************eliminamos los requisitos cargos guardados y los volvemos a registrar**********/
                    $respuesta=$this->eliminarRelacionRequisitosCargos($parametros);
                    if($respuesta==true){
                    $requisitos_cargos = $this->ingresarRequisitosCargos($parametros);

                    if (is_array($requisitos_cargos)) {//guardar relacion requisitos cargos con los formularios
                        for($i=0;$i<count($parametros[vector_req_item])-1;$i++){
                            $req=$parametros[vector_req_item][$i];
                            if(isset($parametros[req_.$req])){//si esta marcado el requisito
                        $id_form=$parametros["form_".$req];
                        $id_vigencia=$parametros["id_vigencia_".$req];
                        $id_combo=$parametros["id_combo_".$req];
                        $id_persona=$parametros["id_persona_".$req];
                        $id_valor_params=$parametros["valores_".$req];
                        $id_capacitacion=$parametros["id_capacitacion_".$req];
                        if(isset($id_capacitacion)){//se escogio n curso
                            $sql = "INSERT INTO mos_requisitos_cursos(id_requisito_cargo,cod_curso)
                            VALUES(".$requisitos_cargos[$i].",".$id_capacitacion.")";
                                $this->dbl->insert_update($sql);

                        }
                        else{
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
                            //echo $sql_parametros;
                            $this->dbl->insert_update($sql_parametros); 
                            }   
                            }
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito','Asignacion de Requisitos actualizados al cargo exitosamente');
                    }
                    else{
                        $objResponse->addScriptCall('VerMensaje','error',$requisitos_cargos);
                    }

                    
                   // $respuesta = $this->modificarRequisitosCargos($parametros);
                }
                else{
                    $objResponse->addScriptCall('VerMensaje','error','Error al actualizar relacion de cargos con requisitos');
                }
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