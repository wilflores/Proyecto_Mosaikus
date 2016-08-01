<?php
 import("clases.interfaz.Pagina");        
        class Requisitos extends Pagina{
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
        
            
            public function Requisitos(){
                parent::__construct();
                $this->asigna_script('requisitos/requisitos.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                //$this->id_org_acceso = $this->id_org_acceso_explicito = array();
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 30";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 30";
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
            
         /*   public function colum_admin($tupla)
            {
                $html = "&nbsp;";
                if (strlen($tupla[id_registro])<=0){
                    if($this->per_editar == 'S'){
                        $html .= '<a onclick="javascript:editarRequisitos(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar Requisitos" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarRequisitos(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar Requisitos" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            */
   /*         public function colum_admin_arbol($tupla)
            {                
                print_r($tupla);
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarRequisitos('". $tupla[id] . "');\"  title=\"Editar Requisitos\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= "<a href=\"#\" onclick=\"javascript:eliminarRequisitos('". $tupla[id] . "');\" title=\"Eliminar Requisitos\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                }
                return $html;
            }
*/
 /*funcion para permisos de eliminar o editar en areas asociadas al requisito****/
            public function colum_admin_arbol($tupla)
            {        
            //print_r($tupla);
                $organizacion = array();
                if(strpos($tupla[id_area],',')){    
                    $organizacion = explode(",", $tupla[id_area]);
                }
                else{
                    $organizacion[] = $tupla[id_area];                                 
                }        
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if ((isset($this->restricciones->id_org_acceso_explicito[$value_2]))&& ($this->restricciones->id_org_acceso_explicito[$value_2][modificar]=='S')){
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                    if (($editar == true)||($_SESSION[SuperUser] == 'S'))
                    {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarRequisitos('". $tupla[id] . "');\"  title=\"Editar Requisitos\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                    }
                    $eliminar = false;                        
                    $organizacion = array();
                    if(strpos($tupla[id_area],',')){    
                        $organizacion = explode(",", $tupla[id_area]);
                    }
                    else{
                        $organizacion[] = $tupla[id_area];                                 
                    }
                    /*SE VALIDA QUE PUEDE ELIMINAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if ((isset($this->restricciones->id_org_acceso_explicito[$value_2]))&&($this->restricciones->id_org_acceso_explicito[$value_2][eliminar]=='S')){
                                $eliminar = true;
                        } else{
                            $eliminar = false;
                            break;
                        }
                    }
                    if (($eliminar == true)||($_SESSION[SuperUser] == 'S'))                  
                    //if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                    {
                   $html .= "<a href=\"#\" onclick=\"javascript:eliminarRequisitos('". $tupla[id] . "');\" title=\"Eliminar Requisitos\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                    }
                
                   
                return $html;


            }

/***** adaptacion de column_admin_arbol- FIN ******/    

             public function verRequisitos($id){
                $atr=array();
                $sql = "SELECT id
,nombre
,tipo
,vigencia
,estatus
,orden

                         FROM mos_requisitos 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarRequisitos($atr){
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    import('clases.utilidades.NivelAcceso');
                    $this->restricciones = new NivelAcceso();
                    /*Carga Acceso segun el arbol*/
                    if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    

                    //***********************************
                    //para validar que los nodos seleccionados
                    //tenga permisos
                    $organizacion = array();
                    if(strpos($atr[nodos],',')){    
                        $organizacion = explode(",", $atr[nodos]);
                    }
                    else{
                        $organizacion[] = $atr[nodos];                    
                    }

                    $areas='';

                    foreach ($organizacion as $value) {
                       //echo "valor: ".$this->restricciones->id_org_acceso_explicito[$value];//esto no me muestra nada
                        if (isset($this->restricciones->id_org_acceso_explicito[$value])){
                            //echo "si hayyyy";
                            
                            if(!($this->restricciones->id_org_acceso_explicito[$value][nuevo]=='S' || $this->restricciones->id_org_acceso_explicito[$value][modificar]=='S')){
                                $areas .= $this->restricciones->id_org_acceso_explicito[$value][title].',';
                            }
                        } else{
                            $areas='break';
                            break;
                        }
                    }
                   // echo "AREAS: ".$areas;
                    /*Valida Restriccion*/
                    if ($areas=='break')
                        return 'Acceso denegado para registrar Requisitos en el &aacute;rea seleccionada.';
                    if ($areas!='break' && $areas!='' )
                        return 'Acceso denegado para registrar Requisitos en el &aacute;rea ' . $areas . '.';                  
                     //***********************************
                    if(!isset($atr[vigencia]))
                        $atr[vigencia]='N';
                    if(!isset($atr[estatus]))
                        $atr[estatus]=0;
                   // print_r($atr);
                    $sql = "INSERT INTO mos_requisitos(nombre,tipo,vigencia,estatus,orden)
                            VALUES(
                                '$atr[nombre]','$atr[tipo]','$atr[vigencia]',$atr[estatus],$atr[orden]
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_requisitos ' . $atr[descripcion_ano], 'mos_requisitos');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_requisitos"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Nombre: \'$atr[nombre]\', Tipo: \'$atr[tipo]\', Vigencia: \'$atr[vigencia]\', Estatus: \'$atr[estatus]\', Orden: \'$atr[orden]\', ";
                    $this->registraTransaccionLog(94,$nuevo,'', $id_new);
                    return $id_new;
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

            public function modificarRequisitos($atr){
                try {
                
                    import('clases.utilidades.NivelAcceso');
                    $this->restricciones = new NivelAcceso();                    
                    /*Carga Acceso segun el arbol*/
                    if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    
                    //***********************************
                    //para validar que los nodos seleccionados
                    //tenga permisos
                    $organizacion = array();
                    if(strpos($atr[nodos],',')){    
                        $organizacion = explode(",", $atr[nodos]);
                    }
                    else{
                        $organizacion[] = $atr[nodos];                    
                    }

                    $areas='';
                    //print_r($organizacion);
                    foreach ($organizacion as $value) {
                       //echo "valor: ".$this->restricciones->id_org_acceso_explicito[$value];//esto no me muestra nada
                        if (isset($this->restricciones->id_org_acceso_explicito[$value])){
                            //echo "si hayyyy";
                            
                            if(!($this->restricciones->id_org_acceso_explicito[$value][nuevo]=='S' || $this->restricciones->id_org_acceso_explicito[$value][modificar]=='S')){
                                $areas .= $this->restricciones->id_org_acceso_explicito[$value][title].',';
                            }
                        } else{
                            $areas='break';
                            break;
                        }
                    }
                  // echo "AREAS: ".$areas;
                    /*Valida Restriccion*/
                    if ($areas=='break')
                        return 'Acceso denegado para modificar Requisitos en el &aacute;rea seleccionada.';
                    if ($areas!='break' && $areas!='' )
                        return 'Acceso denegado para modificar Requisitos en el &aacute;rea ' . $areas . '.';                  
                     //***********************************
                                         //***********************************
                    if(!isset($atr[vigencia]))
                        $atr[vigencia]='N';
                    if(!isset($atr[estatus]))
                        $atr[estatus]=0;
                   // print_r($atr);
                    $sql = "UPDATE mos_requisitos SET                            
                                    nombre = '$atr[nombre]',tipo = '$atr[tipo]',vigencia = '$atr[vigencia]',estatus = $atr[estatus],orden = $atr[orden]
                            WHERE  id = $atr[id]";      
                    $val = $this->verRequisitos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Nombre: \'$atr[nombre]\', Tipo: \'$atr[tipo]\', Vigencia: \'$atr[vigencia]\', Estatus: \'$atr[estatus]\', Orden: \'$atr[orden]\', ";
                    $anterior = "Nombre: \'$val[nombre]\', Tipo: \'$val[tipo]\', Vigencia: \'$val[vigencia]\', Estatus: \'$val[estatus]\', Orden: \'$val[orden]\', ";
                    $this->registraTransaccionLog(95,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Requisitos ' . $atr[descripcion_ano], 'mos_requisitos');
                    */
                    return "El Requisito '$atr[nombre]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe un requisito con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarRequisitos($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    /* if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }*/     
                    $columnas_fam=$this->cargar_parametros_familias();             
                    $k = 1;                    
                    foreach ($columnas_fam as $value) {
                        $sql_left .= " LEFT JOIN(select t1.id_item as id_item, id_requisitos, t2.descripcion as nom_detalle from mos_requisitos_item t1
                                inner join mos_requisitos_items_familias t2 on t1.id_item=t2.id
                        where t2.id_familia='$value[id]' ) AS p$k on p$k.id_requisitos=mr.id"; 
                        $sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }
                    
                  /*  if (count($this->restricciones->id_org_acceso) <= 0){
                        $this->restricciones->cargar_acceso_nodos($atr);
                    }*/
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_requisitos 
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(id) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(nombre) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                if (strlen($atr["b-nombre"])>0)
                        $sql .= " AND upper(nombre) like '%" . strtoupper($atr["b-nombre"]) . "%'";
            if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
             if (strlen($atr["b-estatus"])>0)
                        $sql .= " AND estatus = '". $atr["b-estatus"] . "'";
             if (strlen($atr["b-orden"])>0)
                        $sql .= " AND orden = '". $atr["b-orden"] . "'";

                   /* if (count($this->restricciones->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->restricciones->id_org_acceso)) . ")";
                    }*/
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
                    /*FILTRO PARA EL ARBOL ORGANIZACIONAL*/
                    $filtro_ao='';
                    if ((strlen($atr["b-id_organizacion"])>0)){ // filtro para el arbol organizacional
                        $id_org = ($atr["b-id_organizacion"]);
                        $filtro_ao= " where id_area in (". $id_org . ") ";
                   }//REVISAR LA CONSULTA PORQUE NO FUNCIONA
                    $sql = "SELECT id, nombre, tipo, vigencia, estatus, orden, arbol_organizacional id_area $sql_col_left
FROM mos_requisitos AS mr
INNER JOIN (

SELECT id_requisito, GROUP_CONCAT( DISTINCT id_area ) arbol_organizacional
FROM mos_requisitos_organizacion
$filtro_ao GROUP BY id_requisito) AS mro ON mro.id_requisito = mr.id  $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(id) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                       ;
                        $sql .= " OR (upper(nombre) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                if (strlen($atr["b-nombre"])>0)
                        $sql .= " AND upper(nombre) like '%" . strtoupper($atr["b-nombre"]) . "%'";
           if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
            if (strlen($atr["b-estatus"])>0)
                        $sql .= " AND estatus = '". $atr["b-estatus"] . "'";
             if (strlen($atr["b-orden"])>0)
                        $sql .= " AND orden = '". $atr["b-orden"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarRequisitos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verRequisitos($atr[id]);
                        $respuesta = $this->dbl->delete("mos_requisitos", "id = " . $atr[id]);
                        $nuevo = "Nombre: \'$val[nombre]\', Tipo: \'$val[tipo]\', Vigencia: \'$val[vigencia]\', Estatus: \'$val[estatus]\', Orden: \'$val[orden]\', ";
                        $this->registraTransaccionLog(96,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el Requisito.";                        
                        return "No se puede eliminar el Requisito."; 
                    }
             }
/***cargar nombre de columnas dinamicas de las familias***/
            private function cargar_parametros_familias(){
                $sql = "SELECT id,codigo,descripcion FROM mos_requisitos_familias ORDER BY orden";
                $columnas_fam = $this->dbl->query($sql, array());
                return $columnas_fam;
            }     
 
     public function verListaRequisitos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarRequisitos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblRequisitos", "");
                $config_col=array(
                    
               array( "width"=>"5%","ValorEtiqueta"=>"&nbsp;"),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombre], "nombre", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[tipo], "tipo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estatus], "estatus", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[orden], "orden", $parametros)),
                array( "width"=>"20%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_area], ENT_QUOTES, "UTF-8"))
                );
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/
                $k = 1;
                $columnas_fam=$this->cargar_parametros_familias();
                foreach ($columnas_fam as $value) {                  
                    array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos((ucwords(strtolower($value[descripcion]))), "p$k", $columnas_fam)));
                    $k++;
                }

                $func= array();

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 7;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verRequisitos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Requisitos'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarRequisitos','imagen'=> "<img style='cursor:pointer' src='dis
                    eno/images/ico_modificar.png' title='Editar Requisitos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarRequisitos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar Requisitos'>"));
               */
                $config=array();
                //$config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                      /*  case 1:
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
                if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                    $this->restricciones->cargar_acceso_nodos_explicito($parametros);
                } 
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("id", "colum_admin");
                $grid->setFuncion("id", "colum_admin_arbol");/**** Agregue funcion para arbol - Raquel ****/
                $grid->setFuncion("id_area", "BuscaOrganizacionalTodosVerMas");
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
            $this->listarRequisitos($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblRequisitos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nombre], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[tipo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estatus], ENT_QUOTES, "UTF-8")),
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
/*************************** CARGAR SELECT DINAMICOS DE FAMILIAS EN EL FORM DE REQUISITOS - RAQUEL ******/
            public function crear_campos_dinamicos($modulo,$id_registro=null,$col_lab=4,$col_cam=10){
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $html = '';
  
                $columnas_fam=$this->cargar_parametros_familias();//cargar datos de familias
                 
                $desc_valores_params = $valores_params = array();
                if ($id_registro!= null){
                    //echo "id registro: ".$id_registro;
                    $sql = "SELECT item_f.id id_item,item_f.descripcion descripcion_item FROM mos_requisitos_item as item INNER JOIN mos_requisitos_items_familias as item_f ON item.id_item=item_f.id  WHERE  id_requisitos = $id_registro";
                    //echo $sql;
                    $data_params = $this->dbl->query($sql, array());
                    foreach ($data_params as $value_data_params) {
                        $valores_params[$value_data_params[id_item]]       = $value_data_params[id_item];
                        $desc_valores_params[$value_data_params[id_item]]  = $value_data_params[descripcion_item];
                    }                
                }
                
                $js = $html = $nombre_campos = "";
                $html .= '<div class="form-group">'
                . '<label class="col-md-4 control-label" control-label">FAMILIAS:</label></div>';
                $cont=0;
                foreach ($columnas_fam as $value) {
                         if($id_registro!= null){//para editar
                             $primera_opc= '<option selected="" value="'.$data_params[$cont][id_item].'">'.$data_params[$cont][descripcion_item].'</option>';
                             $condicion="and id<>".$data_params[$cont][id_item];
                         }
                        else{// un nuevo*/
                            $primera_opc=' <option selected="" value="">-- Seleccione --</option>';
                            $condicion="";
                        }
                    $nombre_campos .= "campo_".$value[id].",";
//construir los select dinamicos para el formulario de requisitos
                            $html .= '<div class="form-group">'
                                . '<label for="campo-'.$value[id].'" class="col-md-4 control-label">' . ucwords(strtolower($value[descripcion] )). '</label>'; 
                            $html .= '<div class="col-md-10">                                              
                                                      <select class="form-control" name="campo_' . $value[id] . '" id="campo_' . $value[id] . '" data-validation="required">';
                                                        $html.=$primera_opc;// si es editar muestra de primera opcion el que tiene y si es nuevo. muestra opcion seleccione
                    //echo "SELECT id, descripcion from mos_requisitos_items_familias where id_familia=".$value[id]." ".$condicion."";
                            $html .= $ut_tool->OptionsCombo("SELECT id, descripcion from mos_requisitos_items_familias where id_familia=".$value[id]." ".$condicion.""
                                                                , 'id'
                                                                , 'descripcion', $valores_params[$value[id]]);
                            $cont++;
                            $html .= '</select></div>';
                            $html .= '</div>';

                    
                    
                }
                $array[nombre_campos] = $nombre_campos;
                $array[html] = $html;
                return $array;
            }

             
            public function indexRequisitos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
                if ($parametros['corder'] == null) $parametros['corder']="orden";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc";//ordenar ascendente por orden 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-4-5-6-"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */  

                $columnas_fam=$this->cargar_parametros_familias();//cargar la descripcion de las familias para la grilla            
                $k = 7;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($columnas_fam as $value) {                   
                    $parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                  <label for="SelectAcc" class="col-md-9 control-label">' . $value[descripcion] . '</label>
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
                $grid = $this->verListaRequisitos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Requisitos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Requisitos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->restricciones->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'requisitos/';
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
                $template->PATH = PATH_TO_TEMPLATES.'requisitos/';

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
                $objResponse->addAssign('modulo_actual',"value","requisitos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'requisitos/requisitos.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_multiple();');
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                    event.preventDefault();
                                    var id = $(this).attr('tok');
                                    $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                    $('#myModal-Ventana-Titulo').html('');
                                    $('#myModal-Ventana').modal('show');
                                });");
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                                /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                $parametros[opcion] = 'simple';//se usa cuando utilizamos el arbol en algun formulario, el id del div es div-ao-form
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);

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


                $array = $this->crear_campos_dinamicos(1,null,6,14);
                    
                $contenido_1[OTROS_CAMPOS] = $array[html];     
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'requisitos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;Requisitos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Requisitos";
                $contenido['PAGINA_VOLVER'] = "listarRequisitos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScript('ao_multiple();');//PARA EL ARBOL - RAQUEL
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");   return $objResponse;
            }


 /******* iNGRESAR ARBOL RELACION CON REQUISITOS - RAQUEL ************/
      public function ingresarArbol($atr){
               // print_r($atr);
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_requisitos_organizacion(id_area,id_requisito)
                            VALUES(
                                $atr[id],$atr[id_requisito]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "La asociacion '$atr[id_requisito]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_requisitos_organizacion_key/",$error ) == true) 
                            return "Ya existe el area seleccionada asociada al requisito.";                        
                        return $error; 
                    }
            }


 /******* RELACION CON REQUISITOS E ITEMS DE FAMILIAS- RAQUEL ************/
      public function ingresarRequisitosItems($id_item,$id_requisito){
               // print_r($atr);
                try {
                    //$atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_requisitos_item(id_item,id_requisitos)
                            VALUES(
                                $id_item,$id_requisito
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "La asociacion '$id_requisito' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_requisitos_organizacion_key/",$error ) == true) 
                            return "Ya existe el area seleccionada asociada al requisito.";                        
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
                    //print_r($parametros);
                    $respuesta = $this->ingresarRequisitos($parametros);

                    if (strlen($respuesta)<10) {

                        /********** EXTRAER DATOS DEL ARBOL +++++******/
                    $arr = explode(",", $parametros[nodos]);
                    $params[id_requisito] = $respuesta;
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarArbol($params);
                        }

                         /******** guardar relacion de requisitos con items ****/
                         $columnas_fam=$this->cargar_parametros_familias();//extraer id de familias para los select dnamicos            
                        foreach ($columnas_fam as $value) {
                            $id_item=$parametros["campo_".$value[id].""];
                         $this->ingresarRequisitosItems($id_item,$params[id_requisito]);
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',"El requisito '$parametros[nombre]' se ha ingresado con exito");
                        

                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        
                return $objResponse;
            }
     
 /******************+OBTENER AREAS ASOCIADAS A LA FAMILIA **************/
        public function BuscaOrganizacionalTodosVerMas($tupla)
        {
            $Nivls = "";
                                                       
            //print_r($tupla);
            $Resp3 = explode(',', $tupla[id_area]);//, $pieces)
            foreach ($Resp3 as $Fila3) 
            {                        
                $Nivls .= $this->arbol->BuscaOrganizacional(array('id_organizacion' => $Fila3))."<br /><br />";
            }
            if($Nivls!='')
                    $Nivls=substr($Nivls,0,strlen($Nivls)-6);
            else
                    $Nivls='-- Sin información --';
            
                        
            if (strlen($Nivls)>200){
                $string = explode($Nivls, '<br /><br />');
                $valor_final = '';
                foreach ($string as $value) {
                    $valor_final .= $value;
                    if (strlen($valor_final)>200){
                        return substr($valor_final, 0, 200) . '.. <br/>
                        <a href="#" tok="' .$tupla[id]. '-doc" class="ver-mas">
                            <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                            <input type="hidden" id="ver-mas-' .$tupla[id]. '-doc" value="'.$Nivls.'"/>
                        </a>';
                    }
                    $valor_final .= "<br /><br />";
                    
                }
                
                return substr($Nivls, 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[id]. '-doc" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[id]. '-doc" value="'.$Nivls.'"/>
                    </a>';
            }
            //return $tupla[analisis_causal];
            
            return $Nivls;

        }

            public function editar($parametros)
            {
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $contenido_1   = array();
                $parametros[opcion] = 'simple';//se usa cuando utilizamos el arbol en algun formulario, el id del div es div-ao-form

                $sql="SELECT GROUP_CONCAT(DISTINCT id_area) arbol_organizacional 
                                FROM mos_requisitos_organizacion where id_requisito=".$parametros[id];
                //echo $sql;
               $data_areas=$this->dbl->query($sql);
                //print_r($data_areas);
               if(strpos($data_areas[0][arbol_organizacional],',')){    
                        $organizacion = explode(",", $data_areas[0][arbol_organizacional]);
                    }
                    else{
                        $organizacion[] = $data_areas[0][arbol_organizacional];                    
                    }
                    //print_r($organizacion);
                $parametros[nodos_seleccionados] = $organizacion;
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);
               $array = $this->crear_campos_dinamicos(1,$parametros[id],6,14);
               $contenido_1[OTROS_CAMPOS] = $array[html];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verRequisitos($parametros[id]); 

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
            $contenido_1['NOMBRE'] = ($val["nombre"]);
            $contenido_1['TIPO'] = ($val["tipo"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['ESTATUS'] = $val["estatus"];
            $contenido_1['ORDEN'] = $val["orden"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'requisitos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Requisitos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Requisitos";
                $contenido['PAGINA_VOLVER'] = "listarRequisitos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScript('ao_multiple();');//PARA EL ARBOL - RAQUEL
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  return $objResponse;
            }
     
 
/********** ELIMINAR RELACION ARBOL CON REWUISITOS - RAQUEL */
             public function eliminarCargosArbol($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_requisitos_organizacion", "id_requisito = $atr[id]");
                        return "ha sido eliminada la asociacion con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_requisitos_organizacion_fk_id_matriz_fkey/",$error ) == true) 
                            return "No se puede eliminar la asociacion de areas con requisito.";                        
                        return $error; 
                    }
             }


             /********** ELIMINAR RELACION ARBOL MATRIZ DE COMPETENCIA - RAQUEL */
             public function eliminarRequisitosItems($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_requisitos_item", "id_requisitos = $atr[id]");
                        return "ha sido eliminada la asociacion con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_requisitos_item_fk_id_matriz_fkey/",$error ) == true) 
                            return "No se puede eliminar la asociacion de requisitos con item.";                        
                        return $error; 
                    }
             }
/**********************************FIN DE ELIMINACION relacion  *******/

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
                    
                    $respuesta = $this->modificarRequisitos($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $arr = explode(",", $parametros[nodos]);
                        $params[id_requisito] = $parametros[id];
                        $this->eliminarCargosArbol($parametros);
                        //print_r($params);
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarArbol($params);
                        }

                                                 /******** guardar relacion de requisitos con items ****/
                         $columnas_fam=$this->cargar_parametros_familias();//extraer id de familias para los select dnamicos
                          $this->eliminarRequisitosItems($parametros);           
                        foreach ($columnas_fam as $value) {
                            $id_item=$parametros["campo_".$value[id].""];
                         $this->ingresarRequisitosItems($id_item,$params[id_requisito]);
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
                $val = $this->verRequisitos($parametros[id]);
                $respuesta = $this->eliminarRequisitos($parametros);
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
                //print_r($parametros);
               /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();
                /*Permisos en caso de que no se use el arbol organizacional*/
                /*Permisos en caso de que no se use el arbol organizacional*/
                import('clases.utilidades.NivelAcceso');                
                $this->restricciones = new NivelAcceso();
                /*Carga Acceso segun el arbol*/
                //echo "en el buscar";
                //print_r($this->restricciones->id_org_acceso_explicito);
                if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                    $this->restricciones->cargar_acceso_nodos_explicito($parametros);
                } 
               // $this->restricciones->cargar_permisos($parametros);
                $grid = $this->verListaRequisitos($parametros);                
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

                $val = $this->verRequisitos($parametros[id]);

            $contenido_1['NOMBRE'] = ($val["nombre"]);
            $contenido_1['TIPO'] = ($val["tipo"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['ESTATUS'] = $val["estatus"];
            $contenido_1['ORDEN'] = $val["orden"];
            import("clases.organizacion.ArbolOrganizacional");
            $arbol = new ArbolOrganizacional();
            $ids = $titles = $descs = array();
            foreach ($data_areas as $value) {
                    //$ids[] = $value[id_area];
                    //$titles[] = $value[title];
                    $descs[] = $arbol->BuscaOrganizacional(array('id_organizacion'=>$value[id_area]));
                }
                //$ids_areas = $ids;
                $contenido_1['OPTION_AREAS'] = implode('<br>', $descs);


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'requisitos/';
                $template->setTemplate("verRequisitos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Requisitos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>