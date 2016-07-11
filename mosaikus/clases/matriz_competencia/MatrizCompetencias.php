<?php
 import("clases.interfaz.Pagina");        
        class MatrizCompetencias extends Pagina{
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
        
            
            public function MatrizCompetencias(){
                parent::__construct();
                $this->asigna_script('matriz_competencia/matriz_competencia.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 28";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 28";
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
                        $html .= '<a onclick="javascript:editarMatrizCompetencias(\''.$tupla[id_mat].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar MatrizCompetencias" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarMatrizCompetencias(\''.$tupla[id_mat].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar MatrizCompetencias" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
/*funcion para permisos de eliminar o editar en areas asociadas a la matriz****/
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
                        if ((isset($this->id_org_acceso_explicito[$value_2]))&& ($this->id_org_acceso_explicito[$value_2][modificar]=='S')){
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                    if (($editar == true)||($_SESSION[SuperUser] == 'S'))
                    //if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                    {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarMatrizCompetencias('". $tupla[id_mat] . "');\"  title=\"Editar MatrizCompetencias\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                    }
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($tupla[id_area],',')){    
                        $organizacion = explode(",", $tupla[id_area]);
                    }
                    else{
                        $organizacion[] = $tupla[id_area];                                 
                    }
                    /*SE VALIDA QUE PUEDE ELIMINAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if ((isset($this->id_org_acceso_explicito[$value_2]))&&($this->id_org_acceso_explicito[$value_2][eliminar]=='S')){
                                $eliminar = true;
                        } else{
                            $eliminar = false;
                            break;
                        }
                    }
                    if (($eliminar == true)||($_SESSION[SuperUser] == 'S'))                  
                    //if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                    {
                        $html .= '<a onclick="javascript:eliminarMatrizCompetencias(\''.$tupla[id_mat].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar MatrizCompetencias" style="cursor:pointer"></i>
                                </a>';
                    }
                
                   
                return $html;


            }

/***** adaptacion de column_admin_arbol- FIN ******/

       
     

             public function verMatrizCompetencias($id){
                $atr=array();
                $sql = "SELECT id
,codigo
,descripcion

                         FROM mos_matriz_competencia 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarMatrizCompetencias($atr){
                try {
                    //print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                    /*Carga Acceso segun el arbol*/
                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);
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
                    //echo "organizacion: ";
                    //print_r($organizacion);
                    foreach ($organizacion as $value) {
                        if (isset($this->id_org_acceso_explicito[$value])){
                            
                            if(!($this->id_org_acceso_explicito[$value][nuevo]=='S' || $this->id_org_acceso_explicito[$value][modificar]=='S')){
                                $areas .= $this->id_org_acceso_explicito[$value][title].',';
                                //echo "entrooooo aqui";
                            }
                        } else{
                            $areas='break';
                            break;
                        }
                    }
                    //echo "AREAS: ".$areas;
                    /*Valida Restriccion*/
                    if ($areas=='break')
                        return '- Acceso denegado para registrar Matriz en el &aacute;rea seleccionada.';
                    if ($areas!='break' && $areas!='' )
                        return '- Acceso denegado para registrar MAtriz en el &aacute;rea ' . $areas . '.';                    
                     //***********************************
                    $sql = "INSERT INTO mos_matriz_competencia(codigo,descripcion)
                            VALUES(
                                '$atr[codigo]','$atr[descripcion]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_matriz_competencia ' . $atr[descripcion_ano], 'mos_matriz_competencia');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_matriz_competencia"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Codigo: \'$atr[codigo]\', Descripcion: \'$atr[descripcion]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', $id_new);
                    return $id_new;
                //    return "El mos_matriz_competencia '$atr[descripcion]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/for key 'codigo'/",$error ) == true) 
                            return "Ya existe una matriz con el mismo código.";                        
                        return $error; 
                    }
            }

            /******* REGISTRAR CATEGORIAS DE MATRIZ - RAQUEL*****/
                        public function ingresarCategoriaMatriz($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_matriz_categorias(codigo,descripcion,id_matriz,orden)
                            VALUES(
                                '$atr[codigo]','$atr[descripcion]',$atr[id_matriz],$atr[orden]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $sql = "SELECT MAX(id) total_registros
                         FROM mos_matriz_categorias";
                    $total_registros = $this->dbl->query($sql, $atr);
                    $id_categoria_matriz = $total_registros[0][total_registros];                
                    return $id_categoria_matriz;     
                    return "La categoría '$atr[descripcion]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/for key 'codigo'/",$error ) == true) 
                            return "Ya existe una categoria asociada a la matriz con el mismo código.";                        
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

            public function modificarMatrizCompetencias($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol*/     

                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);
                    } 

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
                        if (isset($this->id_org_acceso_explicito[$value])){
                            if(!($this->id_org_acceso_explicito[$value][nuevo]=='S' || $this->id_org_acceso_explicito[$value][modificar]=='S'))
                                $areas .= $this->id_org_acceso_explicito[$value][title].',';
                        } else{
                            $areas='break';
                            break;
                        }
                    }
                    /*Valida Restriccion*/
                    if ($areas=='break')
                        return '- Acceso denegado para registrar Matriz en el &aacute;rea seleccionada.';
                    if ($areas!='break' && $areas!='' )
                        return '- Acceso denegado para registrar MAtriz en el &aacute;rea ' . $areas . '.';                    
                     //***********************************

                    $sql = "UPDATE mos_matriz_competencia SET            
                                    codigo = '$atr[codigo]',descripcion = '$atr[descripcion]'
                            WHERE  id = $atr[id]"; 
                            //echo $sql;     
                    $val = $this->verMatrizCompetencias($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Codigo: \'$atr[codigo]\', Descripcion: \'$atr[descripcion]\', ";
                    $anterior = "Codigo: \'$val[codigo]\', Descripcion: \'$val[descripcion]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el MatrizCompetencias ' . $atr[descripcion_ano], 'mos_matriz_competencia');
                    */
                    return "La Matriz de Competencia '$atr[codigo]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una matriz con el mismo codigo.";                        
                        return $error; 
                    }
            }

/******************+OBTENER AREAS ASOCIADAS A LAS MATRICES **************/
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
                        <a href="#" tok="' .$tupla[id_mat]. '-doc" class="ver-mas">
                            <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                            <input type="hidden" id="ver-mas-' .$tupla[id_mat]. '-doc" value="'.$Nivls.'"/>
                        </a>';
                    }
                    $valor_final .= "<br /><br />";
                    
                }
                
                return substr($Nivls, 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[id_mat]. '-doc" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[id_mat]. '-doc" value="'.$Nivls.'"/>
                    </a>';
            }
            //return $tupla[analisis_causal];
            
            return $Nivls;

        }


 /****** BUscar categorias de una matriz para mostrarla en la lista - Raquel*****/
 
 public function BuscaCategoriasMatriz($tupla)
        {
            $Nivls = "";
            // print_r($tupla);                                          
 
            $Resp3 = explode(',', $tupla[id]);//, $pieces)
            //print_r($Resp3);
            for($i=0;$i<count($Resp3);$i++) 
            {                        
                $Nivls .= $this->listarCategoriasMatriz($Resp3[$i])."<br /><br />";
            }
            if($Nivls=='')
                    $Nivls='-- Sin información --';
            
            ///echo "Categorias: ".$Nivls;
            return $Nivls;

        }

/********devuelve la descripcion de una categoria DE LA MATRIZ -RAQUEL ************/
         public function listarCategoriasMatriz($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT    descripcion                               
                            FROM mos_matriz_categorias  
                            WHERE id = $atr ORDER BY orden";         
                    //echo $sql;
                    $data = $this->dbl->query($sql, $atr);
                    $descripcion = $data[0][descripcion];
                    return $descripcion;//Retorna la descripcion de una categoria
             }
/********* LISTA LAS CATEGORIAS DE UNA MATRIZ - RAQUEL*********/
         public function listarCategoriasMatrizTod($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT    id,codigo,descripcion,orden                               
                            FROM mos_matriz_categorias  
                            WHERE id_matriz = $atr[id] ORDER BY orden";         
                    $this->operacion($sql, $atr);
                    //$data = $this->dbl->query($sql, $atr);
        
                    //return $data;//Retorna las categorias de la matriz seleccionada
             }

             public function listarMatrizCompetencias($atr, $pag, $registros_x_pagina){
                    //print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                     if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 1;                    

                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);
                    }


                    $sql = "SELECT COUNT( * ) total_registros
FROM mos_matriz_competencia AS ma
INNER JOIN mos_matriz_organizacion AS mo ON mo.id_matriz = ma.id
INNER JOIN mos_matriz_categorias AS mcat ON mcat.id_matriz = ma.id
WHERE 1 =1";
//echo $sql;
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(ma.codigo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(ma.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(ma.codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(ma.descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    /*FILTRO PARA EL ARBOL ORGANIZACIONAL*/
                    if ((strlen($atr["b-id_organizacion"])>0)){  
                        $id_org = ($atr["b-id_organizacion"]);
                        $sql .= " AND mo.id_area in (". $id_org . ") ";//" AND id_organizacion IN (". $id_org . ")";
                    }
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];
                    //echo $sql;       
           /* $sql = "SELECT   id,codigo
                                    ,descripcion
                                     $sql_col_left
                            FROM mos_matriz_competencia $sql_left
                            WHERE 1 = 1 ";*/
                           /*$sql= "SELECT ma.id id_mat,ma.codigo codigo_mat,ma.descripcion descripcion_mat,arbol_organizacional id_area
                           ,mcat.descripcion descripcion_cat $sql_col_left
                            FROM mos_matriz_competencia AS ma   
                            INNER JOIN (select id_matriz, GROUP_CONCAT(distinct id_area) arbol_organizacional 
                                from mos_matriz_organizacion GROUP BY id_matriz) AS mo ON mo.id_matriz = ma.id 
                             INNER JOIN mos_matriz_categorias AS mcat ON mcat.id_matriz= ma.id $sql_left  WHERE 1 = 1";*/
                    /*FILTRO PARA EL ARBOL ORGANIZACIONAL*/
                    $filtro_ao='';
                    if ((strlen($atr["b-id_organizacion"])>0)){ // filtro para el arbol organizacional
                        $id_org = ($atr["b-id_organizacion"]);
                        $filtro_ao= " where id_area in (". $id_org . ") ";
                   }
                             $sql="SELECT ma.id id_mat, ma.codigo codigo_mat, ma.descripcion descripcion_mat, arbol_organizacional id_area, categoria id
                                $sql_col_left FROM mos_matriz_competencia AS ma 
                                INNER JOIN (SELECT id_matriz, GROUP_CONCAT( DISTINCT id_area ) arbol_organizacional 
                                FROM mos_matriz_organizacion $filtro_ao GROUP BY id_matriz) AS mo ON mo.id_matriz = ma.id
                                INNER JOIN (SELECT id_matriz, GROUP_CONCAT( DISTINCT id ) categoria
                                FROM mos_matriz_categorias GROUP BY id_matriz) AS mcat ON mcat.id_matriz = ma.id $sql_left
                                WHERE 1 =1";
                            
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(ma.codigo) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";

                        $sql .= " OR (upper(ma.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(ma.codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(ma.descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                     //echo $sql;
                    $this->operacion($sql, $atr);
             }

             public function eliminarMatrizCompetencias($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verMatrizCompetencias($atr[id]);
                        $respuesta = $this->dbl->delete("mos_matriz_competencia", "id = " . $atr[id]);
                        $nuevo = "Codigo: \'$val[codigo]\', Descripcion: \'$val[descripcion]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada la matriz con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_fk_id_mat_fkey/",$error ) == true) 
                            return "No se puede eliminar la matriz seleccionada.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaMatrizCompetencias($parametros){
                $grid= "";

               // print_r($parametros);
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarMatrizCompetencias($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblMatrizCompetencias", "");
                $config_col=array(
               array( "width"=>"5%","ValorEtiqueta"=>"&nbsp;"),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[codigo], "codigo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_area], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[categoria], ENT_QUOTES, "UTF-8"))
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
                    array_push($func,array('nombre'=> 'verMatrizCompetencias','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver MatrizCompetencias'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarMatrizCompetencias','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar MatrizCompetencias'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarMatrizCompetencias','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar MatrizCompetencias'>"));
               */
                $config=array();
                //$config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        //case 1:
                        //case 2:
                        //case 3:
                        //case 4:
                           // array_push($config,$config_col[$i]);
                           // break;

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
               // $grid->setFuncion("id_mat", "colum_admin");
                $grid->setFuncion("id_mat", "colum_admin_arbol");/**** Agregue funcion para arbol - Raquel ****/
                $grid->setFuncion("id_area", "BuscaOrganizacionalTodosVerMas");
                 $grid->setFuncion("id", "BuscaCategoriasMatriz");
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
            $this->listarMatrizCompetencias($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblMatrizCompetencias", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),
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
 
 
            public function indexMatrizCompetencias($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_mat";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
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
                $this->cargar_permisos($parametros);
                                /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $this->arbol->jstree_ao(0,$parametros);
                /*FIN ARBOL ORGANIZACIONAL*/
                $grid = $this->verListaMatrizCompetencias($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_MatrizCompetencias();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;MatrizCompetencias';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matriz_competencia/';
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
                $template->PATH = PATH_TO_TEMPLATES.'matriz_competencia/';

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
                $objResponse->addAssign('modulo_actual',"value","matriz_competencia");
                $objResponse->addIncludeScript(PATH_TO_JS . 'matriz_competencia/matriz_competencia.js');
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
                /****** variables para categorias - raquel *****/
                $contenido_1['TOK_NEW'] = time();
                $contenido_1[NUM_ITEMS_ESP] = 0;
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matriz_competencia/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;MatrizCompetencias";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;MatrizCompetencias";
                $contenido['PAGINA_VOLVER'] = "listarMatrizCompetencias.php";
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
     
 
            public function guardar($parametros)
            {
                
                //print_r($parametros);
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
                    //VAALIDAR QUE LLENO AL MENOS UNA CATEGORIA - RAQUEL+++/
                      $cant_familias=0;/// cuadrar desde aquiiiiii
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            if ((isset($parametros["nombre_din_$i"]))&&(isset($parametros["codigo_din_$i"]))&&(isset($parametros["orden_din_$i"])))
                                $cant_familias++;
                            }

                    if($cant_familias>0){// SI LLENO ALGUNA FAMILIA SE PUEDE REGISTRAR
                    $respuesta = $this->ingresarMatrizCompetencias($parametros);
                    if (strlen($respuesta ) < 10 ) 
                    {
                    /********** EXTRAER DATOS DEL ARBOL +++++******/
                    $arr = explode(",", $parametros[nodos]);
                    $params[id_matriz] = $respuesta;
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarArbol($params);
                        }
/******************** INSERTAR CATEGORIAS DE LA MATRIZ - RAQUEL ****************/                       
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["nombre_din_$i"])){                                                                
                                $params[descripcion] = $parametros["nombre_din_$i"];
                                $params[codigo] = $parametros["codigo_din_$i"];                                      
                                $params[orden] = $parametros["orden_din_$i"];                                  
                                 
                                
                                //echo $parametros["cuerpo_$i"];
                                $id_cat_matriz = $this->ingresarCategoriaMatriz($params);/******* Ingresar las categorias de la matriz********/
                                //if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9" ))
                                {
                                    $orden=1; //orden de los items - RAQUEL
                                    $sql = 'INSERT INTO mos_matriz_items_categorias(id_categoria,nombre,descripcion, orden,vigencia)'
                                            . ' SELECT ' . $id_cat_matriz . ', descripcion,descripcion_larga, '.$orden.',vigencia '
                                            . ' FROM mos_documentos_formulario_items_temp '
                                            . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                            . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                   //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    
                                }
                            }
                        }
                    
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito','La matriz de competencia "'.$parametros[codigo].'" ha sido ingresada con exito');
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        }
                        else{
                    $objResponse->addScriptCall('VerMensaje','error','Debe ingresar al menos una Familia para la matriz');
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                        }
                
            }
return $objResponse;

            }
     
 /******* iNGRESAR ARBOL RELACION CON MATRIZ DE COMPETENCIA - RAQUEL ************/
      public function ingresarArbol($atr){
               // print_r($atr);
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_matriz_organizacion(id_area,id_matriz)
                            VALUES(
                                $atr[id],$atr[id_matriz]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "La asociacion '$atr[id_matriz]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe el area seleccionada asociada a la matriz.";                        
                        return $error; 
                    }
            }

/******************* FIN DE RELACION ARBOL MATRIZ**************/
/********** ELIMINAR RELACION ARBOL MATRIZ DE COMPETENCIA - RAQUEL */
             public function eliminarCargosArbol($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_matriz_organizacion", "id_matriz = $atr[id]");
                        return "ha sido eliminada la asociacion con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_matriz_area_fk_id_matriz_fkey/",$error ) == true) 
                            return "No se puede eliminar la asociacion de areas con la matriz.";                        
                        return $error; 
                    }
             }
/**********************************FIN DE ELIMINACION CARGOS *******/             
 
            public function editar($parametros)
            {
                               
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $contenido_1   = array();
                $parametros[opcion] = 'simple';//se usa cuando utilizamos el arbol en algun formulario, el id del div es div-ao-form
               //$sql="select id_area from mos_matriz_organizacion where id_matriz=".$parametros[id];

                $sql="SELECT GROUP_CONCAT(DISTINCT id_area) arbol_organizacional 
                                FROM mos_matriz_organizacion where id_matriz=".$parametros[id];
                //echo $sql;
               $data_areas=$this->dbl->query($sql);
                //print_r($data_areas);
               if(strpos($data_areas[0][arbol_organizacional],',')){    
                        $organizacion = explode(",", $data_areas[0][arbol_organizacional]);
                    }
                    else{
                        $organizacion[] = $data_areas[0][arbol_organizacional];                    
                    }
                $parametros[nodos_seleccionados] = $organizacion;
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verMatrizCompetencias($parametros[id]); 

                
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
                //LLAMAR AL LISTAR DE CATEGORIAS DE LA MATRIZ SELECCIONADA
                $this->listarCategoriasMatrizTod($parametros);
                $data=$this->dbl->data;
               // print_r($data);
                $item = "";
                $js = "";
                $i = 0;
                $contenido_1['TOK_NEW'] = time();

                foreach ($data as $value) {                          
                    $i++;
                    //echo $i;
                    /*CARGA de items temporal para campos combo, seleccion simple y multiple*/
                    $vigencia="S";
                        {
                            $sql = "INSERT INTO mos_documentos_formulario_items_temp(fk_id_unico, descripcion, fk_id_item, id_usuario, tok, estado, descripcion_larga,orden,vigencia)"
                                    . " SELECT id_categoria, nombre, id, $_SESSION[CookIdUsuario],$contenido_1[TOK_NEW], 0,descripcion, orden,vigencia "
                                    . " FROM mos_matriz_items_categorias"
                                    . " WHERE id_categoria = $value[id] ";
                            $this->dbl->insert_update($sql);
                            $sql = "SELECT 
                                    descripcion,descripcion_larga
                                    ,vigencia

                            FROM mos_documentos_formulario_items_temp 
                            WHERE tok = $contenido_1[TOK_NEW] and id_usuario = $_SESSION[CookIdUsuario] and fk_id_unico = $value[id] ORDER BY descripcion";
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
                                              
                                              '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id].'" type="hidden" >'.
                                              '<input id="cmb_din_'. $i . '" type="hidden" name="cmb_din_'. $i . '" tok="' . $i . '" value="'.$value[id].'">'.
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.$value[orden].'" type="hidden" >'.
                                       '  </td>';

                                $item = $item. '<td class="td-table-data">'.
                                             '<input id="codigo_din_'. $i . '" value="'.$value[codigo].'" class="form-control" type="text" data-validation="required" name="codigo_din_'. $i . '">'.
                                        '</td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input id="nombre_din_'. $i . '" value="'.$value[descripcion].'" class="form-control" type="text" data-validation="required" name="nombre_din_'. $i . '">'.
                                        '</td>';
//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';
                         $item = $item.  '<td>' .
                                            ' <textarea id="valores_din_'. $i . '" rows="5" name="valores_din_'. $i . '" readonly="" class="form-control" data-validation="required">'. str_replace("<br />", "<br>", $value[valores]) .'</textarea>'.
                                         '</td>';
                         $item = $item. '<td>' .
                                            '<i class="icon icon-more cursor-pointer" title="Administrar Items" id="ico_cmb_din_'. $i . '" tok="'. $i .'"></i>'.
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
                $template->PATH = PATH_TO_TEMPLATES.'matriz_competencia/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;MatrizCompetencias";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;MatrizCompetencias";
                $contenido['PAGINA_VOLVER'] = "listarMatrizCompetencias.php";
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
                          });");
/*********** agregue esto - rauquel*/
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
                }else{

                    /**** verificar si se han eliminado categorias al editar. debe haber al menos 1-raquel***/
                    $cant_familias=0;
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            if ((isset($parametros["nombre_din_$i"]))&&(isset($parametros["codigo_din_$i"]))&&(isset($parametros["orden_din_$i"])))
                                $cant_familias++;
                            }
                    if($cant_familias>0){

                    $respuesta = $this->modificarMatrizCompetencias($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                            $arr = explode(",", $parametros[nodos]);
                        $params[id_matriz] = $parametros[id];
                        $this->eliminarCargosArbol($parametros);
                        //print_r($params);
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarArbol($params);
                        }
/**************desde aqui para empezar a editar parecido a plantillas con lo de categorias -RAQUEL ****/
                        if (strlen($parametros[id_unico_del])>0){
                            $parametros[id_unico_del] = substr($parametros[id_unico_del], 0, strlen($parametros[id_unico_del]) - 1);
                            $sql = "DELETE FROM mos_matriz_categorias WHERE id IN ($parametros[id_unico_del])";
                                //. " AND NOT id_unico IN (SELECT id_unico FROM mos_registro_formulario WHERE IDDoc = $parametros[id]) ";                               
                            $this->dbl->insert_update($sql);
                        }

                        $params[id_matriz] = $parametros[id];
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                                                          
                            if (!isset($parametros["id_unico_din_$i"])){                                
                                $params[descripcion] = $parametros["nombre_din_$i"];
                                $params[codigo] = $parametros["codigo_din_$i"];                                      
                                $params[orden] = $parametros["orden_din_$i"];  
                                if (isset($parametros["orden_din_$i"])){  
                                    $id_unico = $this->ingresarCategoriaMatriz($params);
                                    {
                                        $orden=1;//colocar orden en 1 por ahora -RAQUEL
                                        $sql = 'INSERT INTO mos_matriz_items_categorias(id_categoria, nombre, descripcion, vigencia, orden)'
                                                . ' SELECT ' . $id_unico . ', descripcion, descripcion_larga, vigencia, '.$orden
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
                                    
                                    $params[codigo] = $parametros["codigo_din_$i"];
                                    $params[orden] = $parametros["orden_din_$i"];  
                                    $params[descripcion] = $parametros["nombre_din_$i"];   
                                    $params[id_unico] = $parametros["id_unico_din_$i"];                                      
                                    $this->actualizarCategoriaMatriz($params);
                                    $orden=1;//numero de orden- RAQUEL
                                    $sql = 'INSERT INTO mos_matriz_items_categorias(id_categoria, nombre, descripcion, vigencia, orden)'
                                            . ' SELECT ' . $params[id_unico] . ', descripcion, descripcion_larga, vigencia, '.$orden.'  '
                                            . ' FROM mos_documentos_formulario_items_temp '
                                            . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                            . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    $sql = 'update mos_matriz_items_categorias,mos_documentos_formulario_items_temp
                                            set mos_matriz_items_categorias.nombre = mos_documentos_formulario_items_temp.descripcion,
                                            mos_matriz_items_categorias.descripcion = mos_documentos_formulario_items_temp.descripcion_larga,
                                            mos_matriz_items_categorias.vigencia = mos_documentos_formulario_items_temp.vigencia,
                                            mos_matriz_items_categorias.orden = mos_documentos_formulario_items_temp.orden
                                            where id_usuario = ' . $_SESSION['CookIdUsuario'] . ' and tok = ' . $parametros[tok_new_edit] . ' and mos_documentos_formulario_items_temp.fk_id_unico = ' . $parametros["cmb_din_$i"] . ' and mos_matriz_items_categorias.id = mos_documentos_formulario_items_temp.fk_id_item and estado = 2';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    $sql = 'delete from mos_matriz_items_categorias
                                            where id in (select fk_id_item from mos_documentos_formulario_items_temp 
                                            where id_usuario = ' . $_SESSION['CookIdUsuario'] . ' and tok = ' . $parametros[tok_new_edit] . ' and fk_id_unico = '. $params[id_unico] .' and estado = 3)';
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
                else{//no se agrego ninguna categoria
                     $objResponse->addScriptCall('VerMensaje','error','Debe ingresar al menos una Familia para la matriz');
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");
                        return $objResponse;


                }
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }

/*** metodo para actualizar categorias de la matriz - Raquel ***/
 public function actualizarCategoriaMatriz($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "UPDATE mos_matriz_categorias "
                            . " SET orden = $atr[orden], descripcion = '$atr[descripcion]', codigo = '$atr[codigo]'
                            WHERE id = $atr[id_unico]";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "La categoria . '$atr[codigo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_matriz_categorias_key/",$error ) == true) 
                            return "Ya existe una categoria con el mismo nombre.";                        
                        return $error; 
                    }
            }            
     
 
            public function eliminar($parametros)
            {
                $val = $this->verMatrizCompetencias($parametros[id]);
                $respuesta = $this->eliminarMatrizCompetencias($parametros);
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
                    /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();
                /*Permisos en caso de que no se use el arbol organizacional*/
                $this->cargar_permisos($parametros);
                $grid = $this->verListaMatrizCompetencias($parametros);                
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
                //print_r($parametros);
                $val = $this->verMatrizCompetencias($parametros[id]);

                $contenido_1['CODIGO'] = ($val["codigo"]);
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            //$contenido_1['ARBOL_ORG'] = ($val[""]);
;
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
                $template->PATH = PATH_TO_TEMPLATES.'matriz_competencia/';
                $template->setTemplate("verMatrizCompetencias");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la MatrizCompetencias";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
//copioado de personas para buscar hijos del arbol -Raquel **/
        public function BuscaOrgNivelHijos($IDORG)
        {
            $OrgNom = $IDORG;
            //$Consulta3="select id_organizacion,organizacion_padre,identificacion from mos_organizacion where organizacion_padre='".$IDORG."' and id_filial='".$Filial."' order by id_organizacion";
            $Consulta3="select id as id_organizacion, parent_id as organizacion_padre, title as identificacion from mos_organizacion where parent_id='".$IDORG."' order by id";
            //echo $Consulta3;
            //$Resp3=mysql_query($Consulta3);
            //while($Fila3=mysql_fetch_assoc($Resp3))
            $data = $this->dbl->query($Consulta3,array());
            foreach( $data as $Fila3)
            {
                    //$OrgNom=$OrgNom.",".$Fila3[id_organizacion];
                    $OrgNom .= ",".$this->BuscaOrgNivelHijos($Fila3[id_organizacion]);
            }
            return $OrgNom;
        }
     
 }
 ?>