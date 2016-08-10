<?php
 import("clases.interfaz.Pagina");        
        class DocumentoCodigos extends Pagina{
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
        
            
            public function DocumentoCodigos(){
                parent::__construct();
                $this->asigna_script('documento_codigos/documento_codigos.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (26,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (26,100)";
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
                        $html .= '<a onclick="javascript:editarDocumentoCodigos(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar DocumentoCodigos" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarDocumentoCodigos(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar DocumentoCodigos" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
            public function colum_admin_arbol($tupla)
            {                
                $html = '';
                if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarDocumentoCodigos('". $tupla[id] . "');\"  title=\"Editar Código\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                /*
                if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    //echo 1;
                    $html .= '<a href="#" onclick="javascript:eliminarDocumentoCodigos('. $tupla[id] . ');" title="Eliminar DocumentoCodigos">
                            <i class="icon icon-remove"></i>

                        </a>'; 
                }*/
                return $html;
            }

     

             public function verDocumentoCodigos($id){
                $atr=array();
                $sql = "SELECT id
                        ,id_organizacion
                        ,codigo
                        ,bloqueo_codigo
                        ,bloqueo_version
                        ,correlativo

                         FROM mos_documentos_codigo 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verDocumentoCodigosArea($id){
                //$atr = $this->dbl->corregir_parametros($atr);
                $sql = "SELECT id
                        ,id_organizacion
                        ,codigo
                        ,bloqueo_codigo
                        ,bloqueo_version
                        ,correlativo

                         FROM mos_documentos_codigo 
                         WHERE id_organizacion = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarDocumentoCodigos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    /*Carga Acceso segun el arbol*/
                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);
                    }                    
                    /*Valida Restriccion*/
                    if (!isset($this->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';

                    $sql = "INSERT INTO mos_documentos_codigo(id,id_organizacion,codigo,bloqueo_codigo,bloqueo_version,correlativo)
                            VALUES(
                                $atr[id],$atr[id_organizacion],'$atr[codigo]','$atr[bloqueo_codigo]','$atr[bloqueo_version]',$atr[correlativo]
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos_codigo ' . $atr[descripcion_ano], 'mos_documentos_codigo');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_documentos_codigo"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Id Organizacion: \'$atr[id_organizacion]\', Codigo: \'$atr[codigo]\', Bloqueo Codigo: \'$atr[bloqueo_codigo]\', Bloqueo Version: \'$atr[bloqueo_version]\', Correlativo: \'$atr[correlativo]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', $id_new);
                    return "El mos_documentos_codigo '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarDocumentoCodigos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol*/
                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);
                    }                  
                    $val = $this->verDocumentoCodigos($atr[id]);
                    /*Valida Restriccion*/
                    if (!isset($this->id_org_acceso_explicito[$val[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->id_org_acceso_explicito[$val[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->id_org_acceso_explicito[$val[id_organizacion]][title] . '.';
                    /*VALIDACION CODIGOS DISTINTOS*/
                    $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_documentos_codigo 
                                        WHERE codigo = '$atr[codigo]' AND id <> $atr[id]";                    
                    $total_registros = $this->dbl->query($sql);
                    $total = $total_registros[0][total_registros];  
                    if ($total > 0){
                        return "- Ya existe una área registrada con el mismo código";
                    }
                    /*FIN VALIDACION CODIGOS DISTINTOS*/
                    
                    $sql = "UPDATE mos_documentos_codigo SET                            
                                    codigo = '$atr[codigo]',bloqueo_codigo = '$atr[bloqueo_codigo]',bloqueo_version = '$atr[bloqueo_version]'
                            WHERE  id = $atr[id]";      
                    
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Organizacion: \'$atr[id_organizacion]\', Codigo: \'$atr[codigo]\', Bloqueo Codigo: \'$atr[bloqueo_codigo]\', Bloqueo Version: \'$atr[bloqueo_version]\', Correlativo: \'$atr[correlativo]\', ";
                    $anterior = "Id Organizacion: \'$val[id_organizacion]\', Codigo: \'$val[codigo]\', Bloqueo Codigo: \'$val[bloqueo_codigo]\', Bloqueo Version: \'$val[bloqueo_version]\', Correlativo: \'$val[correlativo]\', ";
                    $this->registraTransaccionLog(87,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el DocumentoCodigos ' . $atr[descripcion_ano], 'mos_documentos_codigo');
                    */
                    return "El C&oacute;digo '$atr[codigo]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function aumentarCorrelativo($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol*/
                    
                    $sql = "UPDATE mos_documentos_codigo_correlativo SET                            
                                    correlativo = correlativo + 1
                            WHERE  id_organizacion = $atr[id_organizacion] AND tipo = $atr[tipo]";      
                    
                    $this->dbl->insert_update($sql);
//                    $nuevo = "Id Organizacion: \'$atr[id_organizacion]\', Codigo: \'$atr[codigo]\', Bloqueo Codigo: \'$atr[bloqueo_codigo]\', Bloqueo Version: \'$atr[bloqueo_version]\', Correlativo: \'$atr[correlativo]\', ";
//                    $anterior = "Id Organizacion: \'$val[id_organizacion]\', Codigo: \'$val[codigo]\', Bloqueo Codigo: \'$val[bloqueo_codigo]\', Bloqueo Version: \'$val[bloqueo_version]\', Correlativo: \'$val[correlativo]\', ";
//                    $this->registraTransaccionLog(88,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el DocumentoCodigos ' . $atr[descripcion_ano], 'mos_documentos_codigo');
                    */
                    return "El C&oacute;digo '$atr[codigo]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
             public function listarDocumentoCodigos($atr, $pag, $registros_x_pagina){
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
                         FROM mos_documentos_codigo 
                         WHERE 1 = 1 ";
                    if ((strlen($atr["b-id_organizacion"])>0)){                             
                        //$id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $id_org = ($atr["b-id_organizacion"]);
                        $sql .= " AND id_organizacion IN (". $id_org . ")";
                    }
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(codigo) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
//                        $sql .= " OR (1 = 1";
//                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
//                        foreach ($nombre_supervisor as $supervisor_aux) {
//                           $sql .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
//                        } 
//                        $sql .= " ) ";
//                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND id_organizacion = '". $atr["b-id_organizacion"] . "'";
                    if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
                    if (strlen($atr["b-bloqueo_codigo"])>0)
                        $sql .= " AND upper(bloqueo_codigo) like '%" . strtoupper($atr["b-bloqueo_codigo"]) . "%'";
                    if (strlen($atr["b-bloqueo_version"])>0)
                        $sql .= " AND upper(bloqueo_version) like '%" . strtoupper($atr["b-bloqueo_version"]) . "%'";
                    if (strlen($atr["b-correlativo"])>0)
                        $sql .= " AND correlativo = '". $atr["b-correlativo"] . "'";

                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);                    
                    }
                    //print_r($this->id_org_acceso_explicito);
                    //if (count($this->id_org_acceso)>0)
                    {                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso_explicito)) . ")";
                    }
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT dc.id
                                ,id_organizacion
                                ,codigo
                                ,bloqueo_codigo
                                ,bloqueo_version
                                ,correlativo
                                     $sql_col_left
                            FROM mos_documentos_codigo dc $sql_left
                                inner join mos_organizacion o on o.id = dc.id_organizacion
                            WHERE 1 = 1 ";
                    if ((strlen($atr["b-id_organizacion"])>0)){                             
                        //$id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $id_org = ($atr["b-id_organizacion"]);
                        $sql .= " AND id_organizacion IN (". $id_org . ")";
                    }
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(codigo) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
//                        $sql .= " OR (1 = 1";
//                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
//                        foreach ($nombre_supervisor as $supervisor_aux) {
//                           $sql .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
//                        } 
//                        $sql .= " ) ";
//                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";                    
            if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-bloqueo_codigo"])>0)
                        $sql .= " AND upper(bloqueo_codigo) like '%" . strtoupper($atr["b-bloqueo_codigo"]) . "%'";
            if (strlen($atr["b-bloqueo_version"])>0)
                        $sql .= " AND upper(bloqueo_version) like '%" . strtoupper($atr["b-bloqueo_version"]) . "%'";
             if (strlen($atr["b-correlativo"])>0)
                        $sql .= " AND correlativo = '". $atr["b-correlativo"] . "'";
                    //if (count($this->id_org_acceso)>0)
                    {                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso_explicito)) . ")";
                    }
                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             function BuscaOrganizacional($tupla)
        {
                $OrgNom = "";
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion, level from mos_organizacion where id in ($tupla[id_organizacion])";
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if(($Fila3[organizacion_padre]==2)||($Fila3[organizacion_padre]==1)||($Fila3[level] == $this->nivel_area))
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= $this->BuscaOrganizacional(array('id_organizacion' => $Fila3[organizacion_padre])) . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= $_SESSION[CookNomEmpresa];
                return $OrgNom;

        }
        
             public function eliminarDocumentoCodigos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verDocumentoCodigos($atr[id]);
                        $respuesta = $this->dbl->delete("mos_documentos_codigo", "id = " . $atr[id]);
                        $nuevo = "Id Organizacion: \'$val[id_organizacion]\', Codigo: \'$val[codigo]\', Bloqueo Codigo: \'$val[bloqueo_codigo]\', Bloqueo Version: \'$val[bloqueo_version]\', Correlativo: \'$val[correlativo]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaDocumentoCodigos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarDocumentoCodigos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblDocumentoCodigos", "");
                $config_col=array(
               array( "width"=>"5%","ValorEtiqueta"=>""),     
               array( "width"=>"40%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[codigo], "codigo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[bloqueo_codigo], "bloqueo_codigo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[bloqueo_version], "bloqueo_version", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[correlativo], "correlativo", $parametros))
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
                    
                    $columna_funcion = 7;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verDocumentoCodigos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver DocumentoCodigos'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarDocumentoCodigos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar DocumentoCodigos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarDocumentoCodigos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar DocumentoCodigos'>"));
               */
                $config=array();
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                //print_r($array_columns);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
                        //case 2:
                        //case 3:
                        //case 4:
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
                $grid->setFuncion("id", "colum_admin_arbol");
                $grid->setFuncion("id_organizacion", "BuscaOrganizacional");
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
            $this->listarDocumentoCodigos($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblDocumentoCodigos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),        
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[codigo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[bloqueo_codigo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[bloqueo_version], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[correlativo], ENT_QUOTES, "UTF-8"))
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
                        case 0:
                            $grid->hidden[$i] = true;                            
                            break;
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
                
                $grid->setFuncion("id_organizacion", "BuscaOrganizacional");
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexDocumentoCodigos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="codigo";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-4"; 
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
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                
                $this->cargar_acceso_nodos_explicito($parametros);
                $grid = $this->verListaDocumentoCodigos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_DocumentoCodigos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;DocumentoCodigos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documento_codigos/';
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
                
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);
                
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documento_codigos/';

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
                $objResponse->addAssign('modulo_actual',"value","documento_codigos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'documento_codigos/documento_codigos.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_multiple();');
                return $objResponse;
            }
         
 /*
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
                $template->PATH = PATH_TO_TEMPLATES.'documento_codigos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;DocumentoCodigos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;DocumentoCodigos";
                $contenido['PAGINA_VOLVER'] = "listarDocumentoCodigos.php";
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
     
 */
            
            public function codigo_sugerido($parametros){
                $val = $this->verDocumentoCodigosArea($parametros[id_organizacion]);                    
                $sql = "select correlativo from mos_documentos_codigo_correlativo where id_organizacion = " .$parametros[id_organizacion] . " AND tipo = $parametros[tipo]"; 
                $data_correlativo = $this->dbl->query($sql);
                if (count($data_correlativo)>0){
                    $correlativo = $data_correlativo[0][correlativo];
                }
                else{
                    $sql = "insert into mos_documentos_codigo_correlativo(id_organizacion, tipo) values (".$parametros[id_organizacion] . ",$parametros[tipo])";
                    $this->dbl->insert_update($sql);
                    $correlativo = 1;
                }
                $sql = "select codigo from mos_documentos_tipos where id = $parametros[tipo]";
                $data_tipo = $this->dbl->query($sql);

                $val[codigo] = $val["codigo"] . '_' . $data_tipo[0][codigo] . '_' . str_pad($correlativo, 3, "0", STR_PAD_LEFT);
                $val[id_organizacion] = $parametros[id_organizacion];
                $val[tipo] = $parametros[tipo];
                
                return $val;
            }
                    
                    
                    
            public function validar_codigo_version($parametros)
            {
                $objResponse = new xajaxResponse();   
                $sql = "select min(level), id from mos_organizacion where id >= 2 and id in(".$parametros['nodos'].")";
                $data = $this->dbl->query($sql);
                if (count($data) > 0){
                    $parametros[id_organizacion] = $data[0][id];
                    $val = $this->codigo_sugerido($parametros);                    
                    
                    
                    $codigo = $val["codigo"];
                    if (($val[bloqueo_codigo] == 'S')&&($_SESSION[SuperUser]!='S')){
                        $objResponse->addScript("$('#Codigo_doc').val('$codigo');");
                        $objResponse->addScript("$('#Codigo_doc').attr('readonly', true);");
                    }
                    else{ 
                        $objResponse->addScript("cambiar_codigo('$codigo',1);");
                        $objResponse->addScript("$('#Codigo_doc').removeAttr('readonly');");
                    }
                    if ($val[bloqueo_version] == 'S'){
                        $objResponse->addScript("$('#version').val('01');");
                        $objResponse->addScript("$('#version').attr('readonly', true);");
                    }
                    else {
                        $objResponse->addScript("if ($('#version').val().length == 0) $('#version').val('01');");
                         $objResponse->addScript("$('#version').removeAttr('readonly');");
                    }
                    
                    $objResponse->addScript("$('#nodo_area').val('".$data[0][id]."');");
                    
                }
                //+print_r($data);
                
                            
//                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
//                $objResponse->addScriptCall("calcHeight");
//                $objResponse->addScriptCall("MostrarContenido2");          
//                $objResponse->addScript("$('#MustraCargando').hide();"); 
//                $objResponse->addScript("$.validate({
//                            lang: 'es'  
//                          });");   
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
                    
                    $respuesta = $this->ingresarDocumentoCodigos($parametros);

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
                $val = $this->verDocumentoCodigos($parametros[id]); 

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
                $contenido_1['ID_ORGANIZACION'] = $this->BuscaOrganizacional($val);// $val["id_organizacion"];
                $contenido_1['CODIGO'] = ($val["codigo"]);
                $contenido_1['BLOQUEO_CODIGO'] = ($val["bloqueo_codigo"]);
                $contenido_1['BLOQUEO_VERSION'] = ($val["bloqueo_version"]);
                $contenido_1['CORRELATIVO'] = $val["correlativo"];
                $contenido_1[CHECKED_BLOQUEO_CODIGO] = $val["bloqueo_codigo"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_BLOQUEO_VERSION] = $val["bloqueo_version"] == 'S' ? 'checked="checked"' : '';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documento_codigos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;DocumentoCodigos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;DocumentoCodigos";
                $contenido['PAGINA_VOLVER'] = "listarDocumentoCodigos.php";
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
                    if (!isset($parametros[bloqueo_codigo])) $parametros[bloqueo_codigo] = 'N';
                    if (!isset($parametros[bloqueo_version])) $parametros[bloqueo_version] = 'N';
                    $respuesta = $this->modificarDocumentoCodigos($parametros);

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
     
 /*
            public function eliminar($parametros)
            {
                $val = $this->verDocumentoCodigos($parametros[id]);
                $respuesta = $this->eliminarDocumentoCodigos($parametros);
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
   */  
 
                public function buscar($parametros)
            {
                /*Permisos en caso de que no se use el arbol organizacional*/
                $this->cargar_permisos($parametros);
                $grid = $this->verListaDocumentoCodigos($parametros);                
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

                $val = $this->verDocumentoCodigos($parametros[id]);

                            $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
            $contenido_1['CODIGO'] = ($val["codigo"]);
            $contenido_1['BLOQUEO_CODIGO'] = ($val["bloqueo_codigo"]);
            $contenido_1['BLOQUEO_VERSION'] = ($val["bloqueo_version"]);
            $contenido_1['CORRELATIVO'] = $val["correlativo"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documento_codigos/';
                $template->setTemplate("verDocumentoCodigos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la DocumentoCodigos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>