<?php
 import("clases.interfaz.Pagina");        
        class ListaDistribucionDoc extends Pagina{
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
        private $arbol;
        
            
            public function ListaDistribucionDoc(){
                parent::__construct();
                $this->asigna_script('lista_distribucion_doc/lista_distribucion_doc.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 27";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 27";
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
                        $html .= '<a onclick="javascript:editarListaDistribucionDoc(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar ListaDistribucionDoc" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarListaDistribucionDoc(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar ListaDistribucionDoc" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
            public function colum_admin_arbol($tupla)
            {                
                if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarListaDistribucionDoc('". $tupla[id] . "');\"  title=\"Editar ListaDistribucionDoc\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= "<a href=\"#\" onclick=\"javascript:eliminarListaDistribucionDoc('". $tupla[id] . "');\" title=\"Eliminar ListaDistribucionDoc\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                }
                return $html;
            }

     

             public function verListaDistribucionDoc($id){
                $atr=array();
                $sql = "SELECT id
,estado
,CONCAT(Codigo_doc,'-',nombre_doc,'-V',lpad(version,2,'0')) documento
,id_documento
,fecha_notificacion
,DATE_FORMAT(fecha_ejecutada, '%d/%m/%Y') fecha_ejecutada
,id_responsable

                         FROM mos_documentos_distribucion dd
                         INNER JOIN mos_documentos d ON d.IDDoc = id_documento 
                         WHERE id = $id "; 
                
              
                            
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
        
        /**
         * Busca las areas relacionada al Documento de lista de distribucion
         * @param type $tupla
         * @return string
         */
        public function BuscaOrganizacionalTodosVerMas($tupla)
        {
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select id_area from mos_documentos_distribucion_area where id_doc_distribucion='".$tupla[id]."' group by id_area";                    
                    
                    $Resp3 = $this->dbl->query($Consulta3,array());    
                    //print_r($Resp3);
                    //$this->arbol = new ArbolOrganizacional();
                    foreach ($Resp3 as $Fila3) 
                    {                        
                        //$Nivls .= 123456789;
                        $Nivls .= $this->arbol->BuscaOrganizacional(array('id_organizacion' => $Fila3[id_area]))."<br /><br />";
                    }
                    if($Nivls!='')
                            $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                    else
                            $Nivls='-- Sin información --';
            }
                        
            if (strlen($Nivls)>200){
                $string = explode($Nivls, '<br /><br />');
                $valor_final = '';
                foreach ($string as $value) {
                    $valor_final .= $value;
                    if (strlen($valor_final)>200){
                        return substr($valor_final, 0, 200) . '.. <br/>
                        <a href="#" tok="' .$tupla[IDDoc]. '-doc" class="ver-mas">
                            <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                            <input type="hidden" id="ver-mas-' .$tupla[IDDoc]. '-doc" value="'.$Nivls.'"/>
                        </a>';
                    }
                    $valor_final .= "<br /><br />";
                    
                }
                
                return substr($Nivls, 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[IDDoc]. '-doc" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[IDDoc]. '-doc" value="'.$Nivls.'"/>
                    </a>';
            }
            //return $tupla[analisis_causal];
            
            return $Nivls;

        }
            public function ingresarListaDistribucionDoc($atr){
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

                    $sql = "INSERT INTO mos_documentos_distribucion(id,estado,id_documento,fecha_notificacion,fecha_ejecutada,id_responsable)
                            VALUES(
                                $atr[id],'$atr[estado]',$atr[id_documento],$atr[fecha_notificacion],'$atr[fecha_ejecutada]',$atr[id_responsable]
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos_distribucion ' . $atr[descripcion_ano], 'mos_documentos_distribucion');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_documentos_distribucion"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Estado: \'$atr[estado]\', Id Documento: \'$atr[id_documento]\', Fecha Notificacion: \'$atr[fecha_notificacion]\', Fecha Ejecutada: \'$atr[fecha_ejecutada]\', Id Responsable: \'$atr[id_responsable]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', $id_new);
                    return "El mos_documentos_distribucion '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarListaDistribucionDocNotioficacion($id, $id_organizacion,$id_responsable){
                try {
                    //$atr = $this->dbl->corregir_parametros($atr);
                    
                    $sql = "INSERT INTO mos_documentos_distribucion(id_documento,id_responsable)
                            VALUES(
                                $id,$id_responsable
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos_distribucion ' . $atr[descripcion_ano], 'mos_documentos_distribucion');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_documentos_distribucion"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $sql = "insert into mos_documentos_distribucion_area(id_doc_distribucion,id_cargo,id_area)
                            select $id_new, cod_cargo, id from mos_cargo_estrorg_arbolproc
                            where id in ($id_organizacion)
                            and cod_cargo in (select cod_cargo from mos_documentos_cargos where IDDoc = $id)";
                    $this->dbl->insert_update($sql);
                    $nuevo = "Estado: \'Pendiente\', Id Documento: \'$id\', Id Responsable: \'$id_responsable\', ";
                    $this->registraTransaccionLog(11,$nuevo,'', $id_new);
                    return $id_new;
                    //return "El mos_documentos_distribucion '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarListaDistribucionDoc($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol
                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);
                    }                    
                    ///*Valida Restriccion
                    if (!isset($this->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';
                    */
                    if (strlen($atr[fecha_ejecutada]) == 0){
                        $atr[fecha_ejecutada] = 'NULL';
                        $atr[estado] = 'Pendiente';
                    }
                    else{
                        $atr[fecha_ejecutada] = "'$atr[fecha_ejecutada]'";
                        $atr[estado] = 'Completado';
                    }
                    $sql = "UPDATE mos_documentos_distribucion SET                            
                                    estado = '$atr[estado]',fecha_ejecutada = $atr[fecha_ejecutada]
                            WHERE  id = $atr[id]";      
                    $val = $this->verListaDistribucionDoc($atr[id]);
                    $this->dbl->insert_update($sql);
                    if ($atr[fecha_ejecutada] != 'NULL'){
                        $atr[fecha_ejecutada] = "\\" . substr($atr[fecha_ejecutada], 0, strlen($atr[fecha_ejecutada])-1) . "\\";
                    }
                    $nuevo = "Estado: \'$atr[estado]\', Fecha Ejecutada: $atr[fecha_ejecutada] ";
                    $anterior = "Estado: \'$val[estado]\', Fecha Ejecutada: \'$val[fecha_ejecutada]\' ";
                    $this->registraTransaccionLog(10,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el ListaDistribucionDoc ' . $atr[descripcion_ano], 'mos_documentos_distribucion');
                    */
                    return "El mos_documentos_distribucion '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
        public function semaforo_reporte($tupla)
        {
            if (($tupla[estado])== 'Pendiente'){
                $html = "<img class=\"SinBorde\" title=\"Pendiente\" src=\"diseno/images/rojo.png\"> $tupla[estado]";                                                                    
                return $html;
            }
            /*if ($tupla[dias_vig]<$tupla[semaforo]){
                $html = "<img class=\"SinBorde\" title=\"Revisión en plazo\" src=\"diseno/images/amarillo.png\"> $tupla[dias_vig]";                                                                    
                return $html;
            }*/
            return "<img class=\"SinBorde\" title=\"Completado\" src=\"diseno/images/verde.png\"> $tupla[estado]";
        }
        
             public function listarListaDistribucionDoc($atr, $pag, $registros_x_pagina){
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
                         FROM mos_documentos_distribucion dd
                            INNER JOIN mos_personal p ON p.cod_emp = dd.id_responsable
                            INNER JOIN mos_documentos d ON d.IDDoc = id_documento
                         WHERE 1 = 1 ";
                    $sql_where = '';
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql_where .= " AND (CONCAT(Codigo_doc,'-',nombre_doc,'-V',lpad(version,2,'0')) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql_where .= " OR (1 = 1";
                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql_where .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
                        } 
                        $sql_where .= " ) ";
                        //$sql_where .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql_where .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (is_array($atr["b-estado"])>0){
                        for ($index = 0; $index < count($atr["b-estado"]); $index++) {
                            $atr["b-estado"][$index] = "'".$atr["b-estado"][$index] ."'";
                        }
                        $sql_where .= " AND estado in (" . implode(',', $atr["b-estado"]) . ")";
                    }
                    if (strlen($atr["b-id_documento"])>0)
                        $sql_where .= " AND CONCAT(Codigo_doc,'-',nombre_doc,'-V',lpad(version,2,'0')) like '%". $atr["b-id_documento"] . "%'";
                    if (strlen($atr["b-fecha_notificacion"])>0)
                        $sql_where .= " AND fecha_notificacion = '". $atr["b-fecha_notificacion"] . "'";
                    if (strlen($atr['b-fecha_ejecutada-desde'])>0)                        
                    {
                        $atr['b-fecha_ejecutada-desde'] = formatear_fecha($atr['b-fecha_ejecutada-desde']);                        
                        $sql_where .= " AND fecha_ejecutada >= '" . ($atr['b-fecha_ejecutada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_ejecutada-hasta'])>0)                        
                    {
                        $atr['b-fecha_ejecutada-hasta'] = formatear_fecha($atr['b-fecha_ejecutada-hasta']);                        
                        $sql_where .= " AND fecha_ejecutada <= '" . ($atr['b-fecha_ejecutada-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-id_responsable"])>0)
                        $sql_where .= " AND id_responsable = '". $atr["b-id_responsable"] . "'";

                    if (count($this->id_org_acceso)>0){                            
                        $sql_where .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                    }
                    $sql = $sql . $sql_where;
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                    ,estado
                                    ,CONCAT(Codigo_doc,'-',nombre_doc,'-V',lpad(version,2,'0')) id_documento
                                    ,id id_area
                                    ,fecha_notificacion
                                    ,DATE_FORMAT(fecha_ejecutada, '%d/%m/%Y') fecha_ejecutada_aux
                                    ,concat(initcap(initcap(SUBSTR(nombres,1,IF(LOCATE(' ' ,nombres,1)=0,LENGTH(nombres),LOCATE(' ' ,nombres,1))))),' ', initcap(apellido_paterno)) id_responsable
                                    ,DATEDIFF(fecha_ejecutada,fecha_notificacion) dias_eje
                                    ,DATEDIFF(CURRENT_DATE(),fecha_notificacion) dias_ret
                                     $sql_col_left
                            FROM mos_documentos_distribucion dd
                            INNER JOIN mos_personal p ON p.cod_emp = dd.id_responsable
                            INNER JOIN mos_documentos d ON d.IDDoc = id_documento $sql_left
                            WHERE 1 = 1 ";
                    /*if (strlen($atr['b-filtro-sencillo'])>0){
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
                    if (is_array($atr["b-estado"])>0){                       
                        $sql .= " AND estado in (" . implode(',', $atr["b-estado"]) . ")";
                    }
             if (strlen($atr["b-id_documento"])>0)
                        $sql .= " AND CONCAT(Codigo_doc,'-',nombre_doc,'-V',lpad(version,2,'0')) like '%". $atr["b-id_documento"] . "%'";
             if (strlen($atr["b-fecha_notificacion"])>0)
                        $sql .= " AND fecha_notificacion = '". $atr["b-fecha_notificacion"] . "'";
             if (strlen($atr['b-fecha_ejecutada-desde'])>0)                        
                    {
                        $atr['b-fecha_ejecutada-desde'] = formatear_fecha($atr['b-fecha_ejecutada-desde']);                        
                        $sql .= " AND fecha_ejecutada >= '" . ($atr['b-fecha_ejecutada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_ejecutada-hasta'])>0)                        
                    {
                        $atr['b-fecha_ejecutada-hasta'] = formatear_fecha($atr['b-fecha_ejecutada-hasta']);                        
                        $sql .= " AND fecha_ejecutada <= '" . ($atr['b-fecha_ejecutada-hasta']) . "'";                        
                    }
             if (strlen($atr["b-id_responsable"])>0)
                        $sql .= " AND id_responsable = '". $atr["b-id_responsable"] . "'";
                        */
                    $sql .= $sql_where . " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             public function eliminarListaDistribucionDoc($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verListaDistribucionDoc($atr[id]);
                        $respuesta = $this->dbl->delete("mos_documentos_distribucion", "id = " . $atr[id]);
                        $nuevo = "Estado: \'$val[estado]\', Id Documento: \'$val[id_documento]\', Fecha Notificacion: \'$val[fecha_notificacion]\', Fecha Ejecutada: \'$val[fecha_ejecutada]\', Id Responsable: \'$val[id_responsable]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaListaDistribucionDoc($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarListaDistribucionDoc($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblListaDistribucionDoc", "");
                $config_col=array(
                    
               array( "width"=>"5%","ValorEtiqueta"=>"&nbsp;" ),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estado], "estado", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_documento], "id_documento", $parametros)),
                    array( "width"=>"20%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_area], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_notificacion], "fecha_notificacion", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_ejecutada], "fecha_ejecutada", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable], "id_responsable", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable], "dias_eje", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable], "dias_ret", $parametros))
                    
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
                    array_push($func,array('nombre'=> 'verListaDistribucionDoc','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver ListaDistribucionDoc'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarListaDistribucionDoc','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar ListaDistribucionDoc'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarListaDistribucionDoc','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar ListaDistribucionDoc'>"));
               */
                $config=array();
                //$config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
                        case 2:
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
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id", "colum_admin");
                $grid->setFuncion("id_area", "BuscaOrganizacionalTodosVerMas");
                $grid->setFuncion("estado", "semaforo_reporte");
                
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
            $this->listarListaDistribucionDoc($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblListaDistribucionDoc", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estado], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_documento], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_notificacion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_ejecutada], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_responsable], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexListaDistribucionDoc($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="estado";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-5-6-"; 
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
                $grid = $this->verListaListaDistribucionDoc($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_ListaDistribucionDoc();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;ListaDistribucionDoc';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'lista_distribucion_doc/';
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
                $template->PATH = PATH_TO_TEMPLATES.'lista_distribucion_doc/';

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
                $objResponse->addAssign('modulo_actual',"value","lista_distribucion_doc");
                $objResponse->addIncludeScript(PATH_TO_JS . 'lista_distribucion_doc/lista_distribucion_doc.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_multiple();');
                $objResponse->addScript("$('#b-fecha_ejecutada-desde').datepicker();");
                $objResponse->addScript("$('#b-fecha_ejecutada-hasta').datepicker();");
                
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
                $template->PATH = PATH_TO_TEMPLATES.'lista_distribucion_doc/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;ListaDistribucionDoc";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ListaDistribucionDoc";
                $contenido['PAGINA_VOLVER'] = "listarListaDistribucionDoc.php";
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
                $objResponse->addScript("$('#fecha_ejecutada').datepicker();");
                $objResponse->addScript("$('#id_area').selectpicker({
                                            style: 'btn-combo'
                                          });");
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
                    $parametros["fecha_ejecutada"] = formatear_fecha($parametros["fecha_ejecutada"]);

                    $respuesta = $this->ingresarListaDistribucionDoc($parametros);

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
                $val = $this->verListaDistribucionDoc($parametros[id]); 

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
                $contenido_1['ESTADO'] = ($val["estado"]);
                $contenido_1['ID_DOCUMENTO'] = $val["documento"];
                $contenido_1['FECHA_NOTIFICACION'] = $val["fecha_notificacion"];
                $contenido_1['FECHA_EJECUTADA'] = ($val["fecha_ejecutada"]);
                $contenido_1['ID_RESPONSABLE'] = $val["id_responsable"];
                //CARGAMOS LAS AREAS
                $sql = "SELECT id_area, title FROM mos_documentos_distribucion_area dda "
                        . "INNER JOIN mos_organizacion_nombres AS o ON o.id = dda.id_area WHERE id_doc_distribucion = $val[id]";
                $data_areas = $this->dbl->query($sql);
                import("clases.organizacion.ArbolOrganizacional");
                $arbol = new ArbolOrganizacional();
                $ids = $titles = $descs = array();
                foreach ($data_areas as $value) {
                    $ids[] = $value[id_area];
                    $titles[] = $value[title];
                    $descs[] = $arbol->BuscaOrganizacional(array('id_organizacion'=>$value[id_area]));
                }
                $ids_areas = $ids;
                $contenido_1['OPTION_AREAS'] = $ut_tool->OptionsComboArrayMultiple($ids, $descs, $ids, $titles);
                //CARGAMOS LOS CARGOS
                $sql = "SELECT id_cargo, descripcion title FROM mos_documentos_distribucion_area dda "
                        . "INNER JOIN mos_cargo AS c ON c.cod_cargo = dda.id_cargo WHERE id_doc_distribucion = $val[id]";
                $data_areas = $this->dbl->query($sql);                
                $ids = $titles = $descs = array();
                foreach ($data_areas as $value) {
                    $ids[] = $value[id_area];
                    $descs[] = $value[title];
                    
                }
                $contenido_1['OPTION_CARGOS'] = $ut_tool->OptionsComboArrayMultiple($ids, $descs, $ids, $titles);
                /*PERSONAL CAPACITADO*/
                $sql = "select id_persona FROM mos_documentos_distribucion_per where id_doc_distribucion = $val[id]";
                $ids_per_aux = $this->dbl->query($sql);
                $ids_per = array();
                foreach ($ids_per_aux as $value) {
                    $ids_per[] = $value[id_persona];
                }
                /*FIN PERSONAL CAPACITADO*/
                /*PERSONAS ORIGEN*/
                $html = '';
                $parametros['b-no_cod_emp'] = implode(',', $ids_per) ;
                $parametros['order'] = 'nombres asc';
                //AREAS QUE APLICA EL DOCUMENTO
                $parametros['b-id_organizacion'] = implode(',', $ids_areas);
                //CARGOS QUE APLICA EL DOCUMENTO
                $parametros['b-id_cargo'] = implode(',', $ids);
                import("clases.personas.Personas");
                $personas = new Personas();
                $personas->listarPersonasSinFiltro($parametros);
                $data=$personas->dbl->data;                
                foreach ($data as $value) {
                    $html .= '<option value="'.$value[cod_emp].'" rut="'.$value[id_personal].'"';
                    /*$html .= ' nom="' .$value[nombres].'"' ;
                    $html .= ' ap_p="' .$value[apellido_paterno].'"' ;
                    $html .= ' ap_m="' .$value[apellido_materno].'"' ;*/
                    $html .= ' arb="' . $value[id_organizacion] . '"';
                    $html .= ' car="' . $value[id_cargo] . '"';
                    $html .= '>' /*. completar_espacios($i,1).' - '*/. str_pad($value[id_personal], 9, '0',STR_PAD_LEFT).' - '.$value[nombres].' '.$value[apellido_paterno].' '.$value[apellido_materno].'</option>';
                    $i++;
                }
                //$html .= '</select>';
                $contenido_1[ORIGEN] = $html;
                $contenido_1[TOTAl_PER_SEL] = 0;
                $contenido_1[TOTAl_PER] = count($data);
                /*FIN PERSONAL ORIGEN*/
                /*CARGA PERSONAL CAPACITADO*/
                $html = '';
                $parametros['b-no_cod_emp'] = '';
                $parametros['b-cod_emp'] = implode(',', $ids_per) == '' ? 0 : implode(',', $ids_per);                
                $personas->listarPersonasSinFiltro($parametros);
                $data=$personas->dbl->data;        
                //print_r($data);
                foreach ($data as $value) {
                    $html .= '<option value="'.$value[cod_emp].'" rut="'.$value[id_personal].'"';
                    /*$html .= ' nom="' .$value[nombres].'"' ;
                    $html .= ' ap_p="' .$value[apellido_paterno].'"' ;
                    $html .= ' ap_m="' .$value[apellido_materno].'"' ;*/
                    $html .= ' arb="' . $value[id_organizacion] . '"';
                    $html .= ' car="' . $value[id_cargo] . '"';
                    $html .= '>' /*. completar_espacios($i,1).' - '*/. str_pad($value[id_personal], 9, '0',STR_PAD_LEFT).' - '.$value[apellido_paterno].' '.$value[apellido_materno].' '.$value[nombres].'</option>';
                    $i++;
                }
                //$html .= '</select>';
                $contenido_1[DESTINO] = $html;
                $contenido_1[TOTAl_PER_SEL] = count($data);
                $contenido_1[TOTAl_PER] = $contenido_1[TOTAl_PER] + count($data);
                /*FIN CARGA PERSONAL CAPACITADO*/
                /* EVIDENCIAS ADJUNTADAS*/
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();
                $array_nuevo = $adjuntos->crear_archivos_adjuntos('mos_documentos_distribucion_evi', 'fk_id_doc_distribucion',$val["id"]);
                $contenido_1[ARCHIVOS_ADJUNTOS] = $array_nuevo[html];
                $js .= $array_nuevo[js];
                
                /*FIN EVIDENNCIAS*/

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'lista_distribucion_doc/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();
                

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Lista de Distribución";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ListaDistribucionDoc";
                $contenido['PAGINA_VOLVER'] = "listarListaDistribucionDoc.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $html = $template->show();
                /*LLAMADA DESDE NOTIFICACIONES*/
                if (isset($parametros[vienede])){
                    $html = str_replace("div-titulo-for", "div-titulo-for-wf", $html); 
                    $html = str_replace("validar(document);", "validar_ld_noti(document);", $html); 
                }
                /*FIN LLAMADA NOTIFICACION*/
                $objResponse->addAssign('contenido-form',"innerHTML",$html);
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2"); 
                
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('#fecha_ejecutada').datepicker();");
                $objResponse->addScript("$('#id_area').selectpicker({
                                            style: 'btn-combo'
                                          });
                                          $('#id_cargo').selectpicker({
                                            style: 'btn-combo'
                                          });");
                $objResponse->addScriptCall("cargar_autocompletado");
                /*LLAMADA DESDE NOTIFICACIONES*/
                if (isset($parametros[vienede])){
                    $objResponse->addIncludeScript(PATH_TO_JS . 'lista_distribucion_doc/lista_distribucion_doc_not.js');
                    $objResponse->addScript("$('.pasar').click(function() { !$('#origen option:selected').remove().appendTo('#destino'); total_per_sel();});  
                        $('.quitar').click(function() { !$('#destino option:selected').remove().appendTo('#origen'); total_per_sel();});
                        $('.pasartodos').click(function() { $('#origen option').each(function() { $(this).remove().appendTo('#destino'); }); total_per_sel();});
                        $('.quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); total_per_sel();});
                        $('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });

                        $('#origen').on('dblclick', 'option', function() {
                            !$('#origen option:selected').remove().appendTo('#destino');
                            total_per_sel();
                        });  ");
                }
                /*FIN LLAMADA DESDE NOTIFICACIONES*/
                $objResponse->addScript($js);
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
                    $parametros["fecha_ejecutada"] = formatear_fecha($parametros["fecha_ejecutada"]);

                    /* EVIDENCIAS ADJUNTADAS*/
                    if(!class_exists('ArchivosAdjuntos')){
                        import("clases.utilidades.ArchivosAdjuntos");
                    }
                    $adjuntos = new ArchivosAdjuntos();
                    $total_evidencias = $adjuntos->contar_evidencias($parametros);
                    if ($total_evidencias<=0){
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        $objResponse->addScriptCall('VerMensaje','error',utf8_encode("Evidencias es Requerido"));
                        return $objResponse;
                    }
                    //VALIDACION AL MENOS UN COLABORADOR
                    if(!is_array($parametros[destino])){
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                        $objResponse->addScriptCall('VerMensaje','error',utf8_encode("Personal Capacitado es requerido"));
                        return $objResponse;
                    }
                    /*FIN EVIDENNCIAS*/
                    $respuesta = $this->modificarListaDistribucionDoc($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {                        
                        $ids_personal = implode(",",$parametros[destino]);
                        //ELIMINAMOS PERSONAL CAPACITADO
                        $sql = "DELETE FROM mos_documentos_distribucion_per FROM id_doc_distribucion = $parametros[id] AND NOT id_persona IN ($ids_personal)";
                        $this->dbl->query($sql);
                        
                        //INSERTAMOS PERSONAL CAPACITADO
                        $sql = "INSERT into mos_documentos_distribucion_per(id_doc_distribucion,id_persona, id_area, id_cargo)
                        select $parametros[id],cod_emp, id_organizacion, cod_cargo from mos_personal where vigencia = 'S' and cod_emp in ($ids_personal)
                        and not cod_emp in (select id_persona from mos_documentos_distribucion_per where id_doc_distribucion = $parametros[id])";
                         $this->dbl->query($sql);
                        /* EVIDENCIAS ADJUNTADAS*/
                        $parametros[tabla] = 'mos_documentos_distribucion_evi';
                        $parametros[clave_foranea] = 'fk_id_doc_distribucion';
                        $parametros[valor_clave_foranea] = $parametros[id];
                        $adjuntos->guardar($parametros);
                        /*FIN EVIDENNCIAS*/
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
                $val = $this->verListaDistribucionDoc($parametros[id]);
                $respuesta = $this->eliminarListaDistribucionDoc($parametros);
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
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();
                $grid = $this->verListaListaDistribucionDoc($parametros);                
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

                $val = $this->verListaDistribucionDoc($parametros[id]);

                            $contenido_1['ESTADO'] = ($val["estado"]);
            $contenido_1['ID_DOCUMENTO'] = $val["id_documento"];
            $contenido_1['FECHA_NOTIFICACION'] = $val["fecha_notificacion"];
            $contenido_1['FECHA_EJECUTADA'] = ($val["fecha_ejecutada"]);
            $contenido_1['ID_RESPONSABLE'] = $val["id_responsable"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'lista_distribucion_doc/';
                $template->setTemplate("verListaDistribucionDoc");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la ListaDistribucionDoc";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>