<?php
 import("clases.interfaz.Pagina");        
        class Familias extends Pagina{
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
        private $restricciones;
        
            
            public function Familias(){
                parent::__construct();
                $this->asigna_script('competencia_familias/competencia_familias.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                //$this->id_org_acceso = $this->id_org_acceso_explicito = array();
                //$this->restricciones->per_crear = $this->restricciones->per_editar = $this->restricciones->per_eliminar = 'N';
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and modulo in (29,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and modulo in (29,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }

            /**
            * Activa los nodos donde se tiene acceso
            
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
            */
           /**
            * Activa los nodos donde se tiene acceso
            
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
           */
            /**
             * Busca los permisos que tiene el usuario en el modulo
             */
            private function cargar_permisos($parametros){
                //print_r($parametros);
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
                    if($this->restricciones->per_editar == 'S'){
                        $html .= '<a onclick="javascript:editarFamilias(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar Familias" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->restricciones->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarFamilias(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar Familias" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
        /*    public function colum_admin_arbol($tupla)
            {                
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarFamilias('". $tupla[id] . "');\"  title=\"Editar Familias\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= "<a href=\"#\" onclick=\"javascript:eliminarFamilias('". $tupla[id] . "');\" title=\"Eliminar Familias\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                }
                return $html;
            }

     */

             public function verFamilias($id){
                $atr=array();
                $sql = "SELECT id
,codigo
,descripcion
,orden

                         FROM mos_requisitos_familias 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarFamilias($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    /*Carga Acceso segun el arbol*/
                   /* if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    
                    /*Valida Restriccion*/
                   /* if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';
*/
                    $sql_orden = "SELECT MAX(orden) ult_orden FROM mos_requisitos_familias"; 
                    $this->operacion($sql_orden, $atr);
                    $orden = $this->dbl->data[0][0];
                    if(isset($orden)) 
                        $orden++;
                    else 
                        $orden=1;
                    $sql = "INSERT INTO mos_requisitos_familias(codigo,descripcion,orden)
                            VALUES(
                                '$atr[codigo]','$atr[descripcion]',$orden
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_requisitos_familias ' . $atr[descripcion_ano], 'mos_requisitos_familias');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_requisitos_familias"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Codigo: \'$atr[codigo]\', Descripcion: \'$atr[descripcion]\', Orden: \'$atr[orden]\', ";
                    $this->registraTransaccionLog(91,$nuevo,'', $id_new);
                    //echo "El mos_requisitos_familias '$atr[descripcion]' ha sido ingresado con exito";
                    return $id_new;//retorna id de familia
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una familia con el mismo nombre.";                        
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

            public function modificarFamilias($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol*/
                   /* if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    } */                   
                    /*Valida Restriccion*/
                   /* if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';
                    */
                    $sql = "UPDATE mos_requisitos_familias SET        
                                    codigo = '$atr[codigo]',descripcion = '$atr[descripcion]' WHERE  id = $atr[id]";
                           // echo $sql;      
                    $val = $this->verFamilias($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Codigo: \'$atr[codigo]\', Descripcion: \'$atr[descripcion]\', Orden: \'$atr[orden]\', ";
                    $anterior = "Codigo: \'$val[codigo]\', Descripcion: \'$val[descripcion]\', Orden: \'$val[orden]\', ";
                    $this->registraTransaccionLog(92,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Familias ' . $atr[descripcion_ano], 'mos_requisitos_familias');
                    */
                    return "La familia '$atr[descripcion]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una famillia con el mismo nombre.";                        
                        return $error; 
                    }
            }



 /****** BUscar items de una familia para mostrarla en la lista - Raquel*****/
 
 public function BuscaItemsFamilia($tupla)
        {
            $Nivls = "";
            // print_r($tupla);                                          
 
            $Resp3 = explode(',', $tupla[id_items]);//, $pieces)
            //print_r($Resp3);
            for($i=0;$i<count($Resp3);$i++) 
            {                        
                $Nivls .= $this->listarItemsFamilia($Resp3[$i])."<br /><br />";
            }
            if($Nivls=='')
                    $Nivls='-- Sin información --';
            
            ///echo "Categorias: ".$Nivls;
            return $Nivls;

        }

/********devuelve la descripcion de un item DE LA familia -RAQUEL ************/
         public function listarItemsFamilia($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT descripcion                               
                            FROM mos_requisitos_items_familias 
                            WHERE id = $atr ORDER BY orden";         
                    //echo $sql;
                    $data = $this->dbl->query($sql, $atr);
                    $descripcion = $data[0][descripcion];
                    return $descripcion;//Retorna la descripcion de un items
             }
/********* LISTA LOS ITEMS DE UNA fAMLIA - RAQUEL*********/
         public function listarItemsFamiliaTod($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT    id,codigo,descripcion,orden,vigencia                               
                            FROM mos_requisitos_items_familias  
                            WHERE id_familia = $atr[id] ORDER BY orden";         
                    $this->operacion($sql, $atr);
                    //$data = $this->dbl->query($sql, $atr);
        
                    //return $data;//Retorna los items de la familia seleccionada
             }
           
             public function listarFamilias($atr, $pag, $registros_x_pagina){
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
                    if (count($this->restricciones->id_org_acceso) <= 0){
                        $this->restricciones->cargar_acceso_nodos($atr);
                    }
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_requisitos_familias 
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(codigo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
            /* if (strlen($atr["b-orden"])>0)
                        $sql .= " AND orden = '". $atr["b-orden"] . "'";*/

                    /*if (count($this->restricciones->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->restricciones->id_org_acceso)) . ")";
                    }*/
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id, codigo, descripcion, orden, items id_items
                    FROM mos_requisitos_familias rf
                    INNER JOIN (
                    SELECT id_familia, GROUP_CONCAT( DISTINCT id) items
                    FROM mos_requisitos_items_familias
                    GROUP BY id_familia) AS it_f ON it_f.id_familia = rf.id
                    WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(codigo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
             /*if (strlen($atr["b-orden"])>0)
                        $sql .= " AND orden = '". $atr["b-orden"] . "'";
*/
                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarFamilias($atr){
                    try {
                        //print_r($atr);
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verFamilias($atr[id]);
                        $respuesta = $this->dbl->delete("mos_requisitos_familias", "id = " . $atr[id]);
                        $nuevo = "Codigo: \'$val[codigo]\', Descripcion: \'$val[descripcion]\', Orden: \'$val[orden]\', ";
                        $this->registraTransaccionLog(93,$nuevo,'', $atr[id]);
                        return true;
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        return  false;                        
                       // return $error; 
                    }
             }
     
 
     public function verListaFamilias($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarFamilias($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblFamilias", "");
                $config_col=array(
                    
               array( "width"=>"5%","ValorEtiqueta"=>"&nbsp;"),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[codigo], "codigo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[orden], "orden", $parametros)),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_items], ENT_QUOTES, "UTF-8"))
                );
                //CREEEAAAAAR LOS NOMBRE CAMPOS FALTANTES
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
                    
                    $columna_funcion = 5;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verFamilias','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Familias'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarFamilias','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar Familias'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarFamilias','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar Familias'>"));
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
                $grid->setFuncion("id", "colum_admin");
                $grid->setFuncion("id_items", "BuscaItemsFamilia");
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
            $this->listarFamilias($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblFamilias", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[codigo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8")),
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
                       /* case 1:
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
 
 
            public function indexFamilias($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
                //$this->restricciones->cargar_acceso_nodos_explicito($parametros);
                $this->restricciones->cargar_permisos($parametros);
                if ($parametros['corder'] == null) $parametros['corder']="orden";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; //accedente por el atributo orden
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-4-"; 
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
                //print_r($this->restricciones);
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $grid = $this->verListaFamilias($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Familias();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Familias';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->restricciones->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'competencia_familias/';
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
                $template->PATH = PATH_TO_TEMPLATES.'competencia_familias/';

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
                $objResponse->addAssign('modulo_actual',"value","competencia_familias");
                $objResponse->addIncludeScript(PATH_TO_JS . 'competencia_familias/competencia_familias.js');
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
                /***** variables para familias e items *******/
                $contenido_1['TOK_NEW'] = time();
                $contenido_1[NUM_ITEMS_ESP] = 0;    
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'competencia_familias/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;Familias";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Familias";
                $contenido['PAGINA_VOLVER'] = "listarFamilias.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";
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
                          });");   return $objResponse;
            }
     
   /******* REGISTRAR items de familias - RAQUEL*****/
            public function ingresarItemsFamilia($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_requisitos_items_familias(codigo,descripcion,id_familia,orden,vigencia)
                            VALUES(
                                '$atr[codigo]','$atr[descripcion]',$atr[id_familia],$atr[orden],'$atr[vigencia]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $sql = "SELECT MAX(id) total_registros
                         FROM mos_requisitos_items_familias";
                    $total_registros = $this->dbl->query($sql, $atr);
                    $id_categoria_matriz = $total_registros[0][total_registros];                
                    return $id_categoria_matriz;     
                    return "El items '$atr[descripcion]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/for key 'codigo'/",$error ) == true) 
                            return "Ya existe una items asociada a la familia con el mismo código.";                        
                        return $error; 
                    }
            }



     /*** metodo para actualizar items de la familia - Raquel ***/
 public function actualizarItemsFamilia($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "UPDATE mos_requisitos_items_familias "
                            . " SET orden = $atr[orden], descripcion = '$atr[descripcion]', codigo = '$atr[codigo]', vigencia='$atr[vigencia]'
                            WHERE id = $atr[id_unico]";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "El item . '$atr[codigo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_requisitos_items_familias_key/",$error ) == true) 
                            return "Ya existe un items en la familia con el mismo nombre.";                        
                        return $error; 
                    }
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
                    
                //VAALIDAR QUE LLENO AL MENOS UNA FAMILIA - RAQUEL+++/
                      $cant_familias=0;
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            if ((isset($parametros["descripcion_din_$i"]))&&(isset($parametros["codigo_din_$i"]))&&(isset($parametros["orden_din_$i"])))
                                $cant_familias++;
                            }

                    if($cant_familias>0){// SI LLENO ALGUNA FAMILIA SE PUEDE REGISTRAR
                    $respuesta = $this->ingresarFamilias($parametros);
                     if (strlen($respuesta ) < 10 ) {
/******************** INSERTAR CATEGORIAS DE LA MATRIZ - RAQUEL ****************/                       $params[id_familia]=$respuesta;
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["descripcion_din_$i"])){                                                                
                                $params[descripcion] = $parametros["descripcion_din_$i"];
                                $params[codigo] = $parametros["codigo_din_$i"];      
                                $params[orden] = $parametros["orden_din_$i"];
                                if(isset($parametros["vigencia_din_$i"]))
                                    $params[vigencia]='S';
                                else
                                    $params[vigencia]='N';
                               // $params[vigencia] = $parametros["vigencia_din_$i"];  
                               
                                $id_items_fam = $this->ingresarItemsFamilia($params);
                            }
                        }

                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito','La Familia se Ingreso con Exito!!!');

                }
                else

                        $objResponse->addScriptCall('VerMensaje','error','Error al Ingresar los datos. Verifique');
                
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        

                }
                else{
                    $objResponse->addScriptCall('VerMensaje','error','Debe ingresar al menos una items para la FAMILIA');
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                        }
                          
            }
            return $objResponse;
        }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verFamilias($parametros[id]); 

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
            $contenido_1['ORDEN'] = $val["orden"];
/************ adaptar lo de items de las familias**********/
 //LLAMAR AL LISTAR DE CATEGORIAS DE LA MATRIZ SELECCIONAD
//PENDIENTEEEEEEEEEEEEEEEEE DE REVISAR
                $this->listarItemsFamiliaTod($parametros);
                $data=$this->dbl->data;
                //print_r($data);
                $item = "";
                $js = "";
                $i = 0;

                foreach ($data as $value) {                          
                    $i++;
                    $item = $item. '<tr id="tr-esp-' . $i . '">';
                    {   
                        $item = $item. '<td align="center">'.
                                            ' <a href="' . $i . '"  title="Eliminar " id="eliminar_esp_' . $i . '"> ' . 
                                            //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' . 
                                             '<i class="icon icon-remove" style="width: 18px;"></i>'.
                                             '</a>' .
                                             '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
                                              <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'.
                                              
                                              '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id].'" type="hidden" >'.
                                              '<input id="cmb_din_'. $i . '" type="hidden" name="cmb_din_'. $i . '" tok="' . $i . '" value="'.$value[id].'">'.
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.$value[orden].'" type="hidden" >'.
                                       '  </td>';
                                if($value[vigencia]=='S') {
                                    $chequeado='checked';
                                    
                                }
                                else {
                                    $chequeado='';
                                        
                                }
                                $item = $item. '<td class="td-table-data">'.
                                             '<input id="vigencia_din_'. $i . '" value="'.$value[vigencia].'" '.$chequeado.' class="form-control" type="checkbox" name="vigencia_din_'. $i . '">'.
                                        '</td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input id="codigo_din_'. $i . '" value="'.$value[codigo].'" class="form-control" type="text" data-validation="required" name="codigo_din_'. $i . '">'.
                                        '</td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input id="descripcion_din_'. $i . '" value="'.$value[descripcion].'" class="form-control" type="text" data-validation="required" name="descripcion_din_'. $i . '">'.
                                        '</td>';

//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';

                        $item = $item. '</tr>' ;                    
                        $js .= '$("#eliminar_esp_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("href");  
                                    $("#id_unico_del").val($("#id_unico_del").val() + $("#id_unico_din_"+id).val() + ",");
                                    $("tr-esp-'. $i .'").remove();
                                    var parent = $(this).parents().parents().get(0);
                                        $(parent).remove();
                            });';
                        $js .= "ajustar_valor_atributo_dinamico($i);";
                        
                    }
                }               
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'competencia_familias/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Familias";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Familias";
                $contenido['PAGINA_VOLVER'] = "listarFamilias.php";
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

/*********** agregue esto - raquel*/
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
                $objResponse->addScript("$('#tabs-hv-2').tab();". "$('#tabs-hv-2 a:first').tab('show');");
/****** hasta aqui - raquel*/
                           return $objResponse;
            }
     
 
            public function actualizar($parametros)
            {
                //print_r($parametros);
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
                }else{//else 1
                    /**** verificar si se han eliminado items al editar. debe haber al menos 1-raquel***/
                    $cant_familias=0;
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            if ((isset($parametros["descripcion_din_$i"]))&&(isset($parametros["codigo_din_$i"]))&&(isset($parametros["orden_din_$i"])))
                                $cant_familias++;
                            }
                    if($cant_familias>0){//if si hay items 2
                    $respuesta = $this->modificarFamilias($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {// se modifico familia 3


/**************desde aqui para empezar a editar parecido a plantillas con lo de categorias -RAQUEL ****/
                        if (strlen($parametros[id_unico_del])>0){
                            $parametros[id_unico_del] = substr($parametros[id_unico_del], 0, strlen($parametros[id_unico_del]) - 1);
                            $sql = "DELETE FROM mos_requisitos_items_familias WHERE id IN ($parametros[id_unico_del])";
                            $this->dbl->insert_update($sql);
                        }

                        $params[id_familia] = $parametros[id];
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                                                          
                            if (!isset($parametros["id_unico_din_$i"])){                                
                                $params[descripcion] = $parametros["descripcion_din_$i"];
                                $params[codigo] = $parametros["codigo_din_$i"];      
                                if(isset($parametros["vigencia_din_$i"]))
                                    $params[vigencia]='S';
                                else
                                    $params[vigencia]='N';
                               // $params[vigencia] = $parametros["vigencia_din_$i"];  
                                if (isset($parametros["orden_din_$i"])){  
                                    $id_unico = $this->ingresarItemsFamilia($params);
                                }
                            }
                            else
                                //if (isset($parametros["valores_din_$i"]))
                                { 
                                    
                                $params[codigo] = $parametros["codigo_din_$i"];
                                $params[orden] = $parametros["orden_din_$i"];  
                                $params[descripcion] = $parametros["descripcion_din_$i"]; 
                                if(isset($parametros["vigencia_din_$i"]))
                                    $params[vigencia]='S';
                                else
                                    $params[vigencia]='N';
                               // $params[vigencia] = $parametros["vigencia_din_$i"];  
                                $params[id_unico] = $parametros["id_unico_din_$i"];
                                $this->actualizarItemsFamilia($params);
                                    
                                }
                        }



                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }//fin de if de modifico familia 3
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);

                }//fin if si hay items 2
                else{//no se agrego ningun items 2
                     $objResponse->addScriptCall('VerMensaje','error','Debe ingresar al menos un Items para la familia');
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");
                        return $objResponse;


                }//fin de else falta items 2

                }//fin de else1
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                print_r($parametros);
                $descripcion=$parametros[descripcion];
                $val = $this->verFamilias($parametros[id]);
                $respuesta = $this->eliminarFamilias($parametros);
                $objResponse = new xajaxResponse();
               // echo $respuesta;
                if ($respuesta == true) {
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',"La Familia ha sido eliminada con exito");
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error','NO se pudieron eliminar la familia. Verifique.');
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
                public function buscar($parametros)
            {
                /*Permisos en caso de que no se use el arbol organizacional*/
                import('clases.utilidades.NivelAcceso');                
                $this->restricciones = new NivelAcceso();
                $this->restricciones->cargar_permisos($parametros);
                $grid = $this->verListaFamilias($parametros);                
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

                $val = $this->verFamilias($parametros[id]);

                            $contenido_1['CODIGO'] = ($val["codigo"]);
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['ORDEN'] = $val["orden"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'competencia_familias/';
                $template->setTemplate("verFamilias");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Familias";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>