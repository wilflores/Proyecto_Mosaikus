<?php
 import("clases.interfaz.Pagina");        
        class DocumentosMigracion extends Pagina{
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
        
            
            public function DocumentosMigracion(){
                parent::__construct();
                $this->asigna_script('documentos_migracion/documentos_migracion.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 90";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 90";
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
                   //print_r($data_ids_acceso);
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
                        $html .= '<a onclick="javascript:editarDocumentosMigracion(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar DocumentosMigracion" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarDocumentosMigracion(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar DocumentosMigracion" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
            public function colum_admin_arbol($tupla)
            {                
                if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarDocumentosMigracion('". $tupla[id] . "');\"  title=\"Editar DocumentosMigracion\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= "<a href=\"#\" onclick=\"javascript:eliminarDocumentosMigracion('". $tupla[id] . "');\" title=\"Eliminar DocumentosMigracion\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                }
                return $html;
            }

     

             public function verDocumentosMigracion($id){
                $atr=array();
                $sql = "SELECT id_responsable_actual
,id_nuevo_responsable
,fecha_operacion
,migrar_responsable_doc
,migrar_wf_revisa
,id_revisa
,migrar_wf_aprueba
,id_aprueba

                         FROM mos_documentos_migracion 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarDocumentosMigracion($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    /*Carga Acceso segun el arbol*/
                    if (count($this->id_org_acceso_explicito) <= 0){
                        $this->cargar_acceso_nodos_explicito($atr);
                    }     
                    //print_r($atr);
                    /*Valida Restriccion*/
//                    if (!isset($this->id_org_acceso_explicito[$atr[id_organizacion]]))
//                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
//                    if (!(($this->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
//                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';
                    if($atr[migrar_wf_revisa]=='N') $atr[id_revisa]='NULL';
                    if($atr[migrar_responsable_doc]=='N') $atr[id_nuevo_responsable]='NULL';
                    if($atr[migrar_wf_aprueba]=='N') $atr[id_aprueba]='NULL';
                    $sql = "INSERT INTO mos_documentos_migracion(id_responsable_actual,id_nuevo_responsable,migrar_responsable_doc,migrar_wf_revisa,id_revisa,migrar_wf_aprueba,id_aprueba)
                            VALUES(
                                $atr[id_responsable_actual],$atr[id_nuevo_responsable],'$atr[migrar_responsable_doc]','$atr[migrar_wf_revisa]',$atr[id_revisa],'$atr[migrar_wf_aprueba]',$atr[id_aprueba]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos_migracion ' . $atr[descripcion_ano], 'mos_documentos_migracion');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_documentos_migracion"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Id Responsable Actual: \'$atr[id_responsable_actual]\', Id Nuevo Responsable: \'$atr[id_nuevo_responsable]\', Fecha Operacion: \'$atr[fecha_operacion]\', Migrar Responsable Doc: \'$atr[migrar_responsable_doc]\', Migrar Wf Revisa: \'$atr[migrar_wf_revisa]\', Id Revisa: \'$atr[id_revisa]\', Migrar Wf Aprueba: \'$atr[migrar_wf_aprueba]\', Id Aprueba: \'$atr[id_aprueba]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', $id_new);
                    return "El mos_documentos_migracion '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarDocumentosMigracion($atr){
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

                    $sql = "UPDATE mos_documentos_migracion SET                            
                                    id_responsable_actual = $atr[id_responsable_actual],id_nuevo_responsable = $atr[id_nuevo_responsable],fecha_operacion = '$atr[fecha_operacion]',migrar_responsable_doc = '$atr[migrar_responsable_doc]',migrar_wf_revisa = '$atr[migrar_wf_revisa]',id_revisa = $atr[id_revisa],migrar_wf_aprueba = '$atr[migrar_wf_aprueba]',id_aprueba = $atr[id_aprueba]
                            WHERE  id = $atr[id]";      
                    $val = $this->verDocumentosMigracion($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Responsable Actual: \'$atr[id_responsable_actual]\', Id Nuevo Responsable: \'$atr[id_nuevo_responsable]\', Fecha Operacion: \'$atr[fecha_operacion]\', Migrar Responsable Doc: \'$atr[migrar_responsable_doc]\', Migrar Wf Revisa: \'$atr[migrar_wf_revisa]\', Id Revisa: \'$atr[id_revisa]\', Migrar Wf Aprueba: \'$atr[migrar_wf_aprueba]\', Id Aprueba: \'$atr[id_aprueba]\', ";
                    $anterior = "Id Responsable Actual: \'$val[id_responsable_actual]\', Id Nuevo Responsable: \'$val[id_nuevo_responsable]\', Fecha Operacion: \'$val[fecha_operacion]\', Migrar Responsable Doc: \'$val[migrar_responsable_doc]\', Migrar Wf Revisa: \'$val[migrar_wf_revisa]\', Id Revisa: \'$val[id_revisa]\', Migrar Wf Aprueba: \'$val[migrar_wf_aprueba]\', Id Aprueba: \'$val[id_aprueba]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el DocumentosMigracion ' . $atr[descripcion_ano], 'mos_documentos_migracion');
                    */
                    return "El mos_documentos_migracion '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarDocumentosMigracion($atr, $pag, $registros_x_pagina){
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
                         FROM mos_documentos_migracion 
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
                    if (strlen($atr["b-id_responsable_actual"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(actual.nombres,1,IF(LOCATE(' ' ,actual.nombres,1)=0,LENGTH(actual.nombres),LOCATE(' ' ,actual.nombres,1)-1))),' ',initcap(actual.apellido_paterno)) like '%" . strtoupper($atr["b-id_responsable_actual"] ) . "%'";
                    if (strlen($atr["b-id_nuevo_responsable"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(nuevo.nombres,1,IF(LOCATE(' ' ,nuevo.nombres,1)=0,LENGTH(nuevo.nombres),LOCATE(' ' ,nuevo.nombres,1)-1))),' ',initcap(nuevo.apellido_paterno)) like '%" . strtoupper($atr["b-id_nuevo_responsable"] ) . "%'";
                    if (strlen($atr["b-fecha_operacion"])>0)
                               $sql .= " AND fecha_operacion = '". $atr["b-fecha_operacion"] . "'";
                   if (strlen($atr["b-migrar_responsable_doc"])>0)
                               $sql .= " AND upper(migrar_responsable_doc) like '%" . strtoupper($atr["b-migrar_responsable_doc"]) . "%'";
                   if (strlen($atr["b-migrar_wf_revisa"])>0)
                               $sql .= " AND upper(migrar_wf_revisa) like '%" . strtoupper($atr["b-migrar_wf_revisa"]) . "%'";
                    if (strlen($atr["b-id_revisa"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(revisa.nombres,1,IF(LOCATE(' ' ,revisa.nombres,1)=0,LENGTH(revisa.nombres),LOCATE(' ' ,revisa.nombres,1)-1))),' ',initcap(revisa.apellido_paterno)) like '%" . strtoupper($atr["b-id_revisa"] ) . "%'";
                   if (strlen($atr["b-migrar_wf_aprueba"])>0)
                               $sql .= " AND upper(migrar_wf_aprueba) like '%" . strtoupper($atr["b-migrar_wf_aprueba"]) . "%'";
                    if (strlen($atr["b-id_aprueba"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(aprueba.nombres,1,IF(LOCATE(' ' ,aprueba.nombres,1)=0,LENGTH(aprueba.nombres),LOCATE(' ' ,aprueba.nombres,1)-1))),' ',initcap(aprueba.apellido_paterno)) like '%" . strtoupper( $atr["b-id_aprueba"] ) . "%'";

                    if (count($this->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                    }
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT 
                            DATE_FORMAT(fecha_operacion, '%d/%m/%Y %H:%i') fecha_operacion
                            ,CONCAT(initcap(SUBSTR(actual.nombres,1,IF(LOCATE(' ' ,actual.nombres,1)=0,LENGTH(actual.nombres),LOCATE(' ' ,actual.nombres,1)-1))),' ',initcap(actual.apellido_paterno)) id_responsable_actual
                            ,CONCAT(initcap(SUBSTR(nuevo.nombres,1,IF(LOCATE(' ' ,nuevo.nombres,1)=0,LENGTH(nuevo.nombres),LOCATE(' ' ,nuevo.nombres,1)-1))),' ',initcap(nuevo.apellido_paterno)) id_nuevo_responsable
                            
                            ,migrar_responsable_doc
                            ,migrar_wf_revisa
                            ,CONCAT(initcap(SUBSTR(revisa.nombres,1,IF(LOCATE(' ' ,revisa.nombres,1)=0,LENGTH(revisa.nombres),LOCATE(' ' ,revisa.nombres,1)-1))),' ',initcap(revisa.apellido_paterno)) id_revisa
                            ,migrar_wf_aprueba
                            ,CONCAT(initcap(SUBSTR(aprueba.nombres,1,IF(LOCATE(' ' ,aprueba.nombres,1)=0,LENGTH(aprueba.nombres),LOCATE(' ' ,aprueba.nombres,1)-1))),' ',initcap(aprueba.apellido_paterno)) id_aprueba

                                     $sql_col_left
                            FROM mos_documentos_migracion inner join mos_personal actual
                            on actual.cod_emp = id_responsable_actual left join mos_personal nuevo
                            on id_nuevo_responsable =nuevo.cod_emp left join mos_personal revisa
                            on  id_revisa = revisa.cod_emp left join mos_personal aprueba
                            on  id_aprueba = aprueba.cod_emp
                            $sql_left
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
                    if (strlen($atr["b-id_responsable_actual"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(actual.nombres,1,IF(LOCATE(' ' ,actual.nombres,1)=0,LENGTH(actual.nombres),LOCATE(' ' ,actual.nombres,1)-1))),' ',initcap(actual.apellido_paterno)) like '%" . strtoupper($atr["b-id_responsable_actual"] ) . "%'";
                    if (strlen($atr["b-id_nuevo_responsable"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(nuevo.nombres,1,IF(LOCATE(' ' ,nuevo.nombres,1)=0,LENGTH(nuevo.nombres),LOCATE(' ' ,nuevo.nombres,1)-1))),' ',initcap(nuevo.apellido_paterno)) like '%" . strtoupper($atr["b-id_nuevo_responsable"] ) . "%'";
                    if (strlen($atr["b-fecha_operacion"])>0)
                               $sql .= " AND fecha_operacion = '". $atr["b-fecha_operacion"] . "'";
                   if (strlen($atr["b-migrar_responsable_doc"])>0)
                               $sql .= " AND upper(migrar_responsable_doc) like '%" . strtoupper($atr["b-migrar_responsable_doc"]) . "%'";
                   if (strlen($atr["b-migrar_wf_revisa"])>0)
                               $sql .= " AND upper(migrar_wf_revisa) like '%" . strtoupper($atr["b-migrar_wf_revisa"]) . "%'";
                    if (strlen($atr["b-id_revisa"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(revisa.nombres,1,IF(LOCATE(' ' ,revisa.nombres,1)=0,LENGTH(revisa.nombres),LOCATE(' ' ,revisa.nombres,1)-1))),' ',initcap(revisa.apellido_paterno)) like '%" . strtoupper($atr["b-id_revisa"] ) . "%'";
                   if (strlen($atr["b-migrar_wf_aprueba"])>0)
                               $sql .= " AND upper(migrar_wf_aprueba) like '%" . strtoupper($atr["b-migrar_wf_aprueba"]) . "%'";
                    if (strlen($atr["b-id_aprueba"])>0)
                               $sql .= " AND CONCAT(initcap(SUBSTR(aprueba.nombres,1,IF(LOCATE(' ' ,aprueba.nombres,1)=0,LENGTH(aprueba.nombres),LOCATE(' ' ,aprueba.nombres,1)-1))),' ',initcap(aprueba.apellido_paterno)) like '%" . strtoupper( $atr["b-id_aprueba"] ) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                   // echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarDocumentosMigracion($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verDocumentosMigracion($atr[id]);
                        $respuesta = $this->dbl->delete("mos_documentos_migracion", "id = " . $atr[id]);
                        $nuevo = "Id Responsable Actual: \'$val[id_responsable_actual]\', Id Nuevo Responsable: \'$val[id_nuevo_responsable]\', Fecha Operacion: \'$val[fecha_operacion]\', Migrar Responsable Doc: \'$val[migrar_responsable_doc]\', Migrar Wf Revisa: \'$val[migrar_wf_revisa]\', Id Revisa: \'$val[id_revisa]\', Migrar Wf Aprueba: \'$val[migrar_wf_aprueba]\', Id Aprueba: \'$val[id_aprueba]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
            public function EjecutarDocumentosMigracion($atr){
                session_name("mosaikus");
                session_start();
                if($atr[migrar_responsable_doc]=='S'){
                    $sql = "update mos_documentos "
                            . " set elaboro =".$atr[id_nuevo_responsable]
                            . " where elaboro=".$atr[id_responsable_actual];
                   // echo $sql;
                    $this->dbl->insert_update($sql);
                    $sql = "update mos_workflow_documentos "
                            . " set id_personal_responsable =".$atr[id_nuevo_responsable]
                            . " , email_responsable = (select email from mos_personal where cod_emp=".$atr[id_nuevo_responsable].")"                        
                            . " where id_personal_responsable=".$atr[id_responsable_actual];                
                //echo $sql;
                    $this->dbl->insert_update($sql);
                }
                if($atr[migrar_wf_revisa]=='S'){
                    $sql = "update mos_workflow_documentos "
                            . " set id_personal_revisa =".$atr[id_revisa]
                            . " , email_responsable = (select email from mos_personal where cod_emp=".$atr[id_revisa].")"                        
                            . " where id_personal_revisa=".$atr[id_responsable_actual];                
                //echo $sql;
                    $this->dbl->insert_update($sql);
                }                
                if($atr[migrar_wf_aprueba]=='S'){
                    $sql = "update mos_workflow_documentos "
                            . " set id_personal_aprueba =".$atr[id_aprueba]
                            . " , email_responsable = (select email from mos_personal where cod_emp=".$atr[id_aprueba].")"                        
                            . " where id_personal_aprueba=".$atr[id_responsable_actual];                
               // echo $sql;
                    $this->dbl->insert_update($sql);
                }                
                
                return true;
            }     
 
     public function verListaDocumentosMigracion($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarDocumentosMigracion($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblDocumentosMigracion", "");
                $config_col=array(
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_operacion], "fecha_operacion", $parametros)),                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable_actual], "id_responsable_actual", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_nuevo_responsable], "id_nuevo_responsable", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[migrar_responsable_doc], "migrar_responsable_doc", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[migrar_wf_revisa], "migrar_wf_revisa", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_revisa], "id_revisa", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[migrar_wf_aprueba], "migrar_wf_aprueba", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_aprueba], "id_aprueba", $parametros))
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
                    
                    $columna_funcion = 9;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verDocumentosMigracion','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver DocumentosMigracion'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarDocumentosMigracion','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar DocumentosMigracion'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarDocumentosMigracion','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar DocumentosMigracion'>"));
               */
                $config=array();
                //$config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
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
            $this->listarDocumentosMigracion($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblDocumentosMigracion", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_responsable_actual], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_nuevo_responsable], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_operacion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[migrar_responsable_doc], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[migrar_wf_revisa], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_revisa], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[migrar_wf_aprueba], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_aprueba], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexDocumentosMigracion($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-4-5-6-7-8-"; 
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
                $grid = $this->verListaDocumentosMigracion($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_DocumentosMigracion();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;DocumentosMigracion';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos_migracion/';
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
                $template->PATH = PATH_TO_TEMPLATES.'documentos_migracion/';

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
                $objResponse->addAssign('modulo_actual',"value","documentos_migracion");
                $objResponse->addIncludeScript(PATH_TO_JS . 'documentos_migracion/documentos_migracion.js');
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
                if (count($this->id_org_acceso_explicito) <= 0){
                    $this->cargar_acceso_nodos_explicito($parametros);                    
                }   
                
                $sql = "SELECT DISTINCT
                                nombre,
                                cod_emp id
                        from (
                        SELECT DISTINCT
                                CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno)) nombre,
                                perso_responsable.cod_emp
                        FROM mos_workflow_documentos AS wf
                        left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
                         WHERE perso_responsable.id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).") 
                        union all
                        SELECT DISTINCT
                                CONCAT(initcap(SUBSTR(perso_revisa.nombres,1,IF(LOCATE(' ' ,perso_revisa.nombres,1)=0,LENGTH(perso_revisa.nombres),LOCATE(' ' ,perso_revisa.nombres,1)-1))),' ',initcap(perso_revisa.apellido_paterno)) responsable,
                                perso_revisa.cod_emp
                        FROM mos_workflow_documentos AS wf
                        left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                         WHERE perso_revisa.id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).")
                        union all
                        SELECT DISTINCT
                                CONCAT(initcap(SUBSTR(perso_aprueba.nombres,1,IF(LOCATE(' ' ,perso_aprueba.nombres,1)=0,LENGTH(perso_aprueba.nombres),LOCATE(' ' ,perso_aprueba.nombres,1)-1))),' ',initcap(perso_aprueba.apellido_paterno)) responsable,
                                perso_aprueba.cod_emp
                        FROM mos_workflow_documentos AS wf
                        left JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp
                         WHERE perso_aprueba.id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).")
                        ) as wf
                        order by 1";
                //echo $sql;
                $contenido_1[ID_RESPONSABLE_ACTUAL] .= $ut_tool->OptionsCombo($sql
                                                                    , 'id'
                                                                    , 'nombre');      
                 $sql = "SELECT DISTINCT
                                CONCAT(initcap(SUBSTR(nombres,1,IF(LOCATE(' ' ,nombres,1)=0,LENGTH(nombres),LOCATE(' ' ,nombres,1)-1))),' ',initcap(apellido_paterno)) nombre,
                                cod_emp id
                        FROM  mos_personal 
                         WHERE id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).") and elaboro='S'";

                 $contenido_1[ID_NUEVO_RESPONSABLE] .= $ut_tool->OptionsCombo($sql
                                                                    , 'id'
                                                                    , 'nombre');      
                 $sql = "SELECT DISTINCT
                                CONCAT(initcap(SUBSTR(nombres,1,IF(LOCATE(' ' ,nombres,1)=0,LENGTH(nombres),LOCATE(' ' ,nombres,1)-1))),' ',initcap(apellido_paterno)) nombre,
                                cod_emp id
                        FROM  mos_personal 
                         WHERE id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).") and reviso='S'";

                 $contenido_1[ID_REVISA] .= $ut_tool->OptionsCombo($sql
                                                                    , 'id'
                                                                    , 'nombre');      
                 $sql = "SELECT DISTINCT
                                CONCAT(initcap(SUBSTR(nombres,1,IF(LOCATE(' ',nombres,1)=0,LENGTH(nombres),LOCATE(' ' ,nombres,1)-1))),' ',initcap(apellido_paterno)) nombre,
                                cod_emp id
                        FROM  mos_personal 
                         WHERE id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).") and aprobo='S'";

                 $contenido_1[ID_APRUEBA] .= $ut_tool->OptionsCombo($sql
                                                                    , 'id'
                                                                    , 'nombre');      
                 
                $sqlsino = "SELECT 'SI' nombre,'S' id union SELECT 'NO' nombre,'N' id ";
                //echo $sql;
                $contenido_1[MIGRAR_RESPONSABLE_DOC] .= $ut_tool->OptionsCombo($sqlsino
                                                                    , 'id'
                                                                    , 'nombre');                
                //echo $sql;
                $contenido_1[MIGRAR_WF_APRUEBA] .= $ut_tool->OptionsCombo($sqlsino
                                                                    , 'id'
                                                                    , 'nombre');                
                //echo $sql;
                $contenido_1[MIGRAR_WF_REVISA] .= $ut_tool->OptionsCombo($sqlsino
                                                                    , 'id'
                                                                    , 'nombre');                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos_migracion/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;DocumentosMigracion";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;DocumentosMigracion";
                $contenido['PAGINA_VOLVER'] = "listarDocumentosMigracion.php";
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
                $objResponse->addScript('$("#id_responsable_actual").select2({
                                            placeholder: "Selecione el usuario responsable",
                                            allowClear: true
                                          });'); 
                $objResponse->addScript('$("#id_nuevo_responsable").select2({
                                            placeholder: "Selecione el nuevo usuario responsable",
                                            allowClear: true
                                          });'); 
                $objResponse->addScript('$("#id_revisa").select2({
                                            placeholder: "Selecione el usuario que revisa",
                                            allowClear: true
                                          });'); 
                $objResponse->addScript('$("#id_aprueba").select2({
                                            placeholder: "Selecione el usuario que Aprueba",
                                            allowClear: true
                                          });'); 
                $objResponse->addScriptCall('cargar_autocompletado');
                
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
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                //print_r($this->nombres_columnas);
                $validator = new FormValidator();
                if($parametros[migrar_responsable_doc]!='S' && $parametros[migrar_wf_revisa]!='S' && $parametros[migrar_wf_aprueba]!='S'){
                    $validator = new FormValidator();
                    $objResponse->addScriptCall('VerMensaje','error','Debe seleccionar migrar alguna opcion');
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar y Notificar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                    return $objResponse; 
                }
                if($parametros[migrar_responsable_doc]=='S'){
                    if($parametros[id_nuevo_responsable]!=''){
                        if($parametros[id_responsable_actual]==$parametros[id_nuevo_responsable]){
                            $objResponse->addScriptCall('VerMensaje','error','No puede seleccionar el mismo '.$this->nombres_columnas[id_responsable_actual]);
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                $( '#btn-guardar' ).prop( 'disabled', false );
                                $('#btn-guardar-not' ).html('Guardar y Notificar');
                                $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                            return $objResponse;
                        }
                    }else{
                        $objResponse->addScriptCall('VerMensaje','error','Debe seleccionar un '.$this->nombres_columnas[id_nuevo_responsable]);
                        $objResponse->addScript("$('#MustraCargando').hide();"); 
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                            $( '#btn-guardar' ).prop( 'disabled', false );
                            $('#btn-guardar-not' ).html('Guardar y Notificar');
                            $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                        return $objResponse;                            
                    }
                }
                if($parametros[migrar_wf_revisa]=='S'){
                    if($parametros[id_revisa]!=''){
                        if($parametros[id_revisa]==$parametros[id_nuevo_responsable]){
                            $objResponse->addScriptCall('VerMensaje','error','No puede seleccionar el mismo '.$this->nombres_columnas[id_nuevo_responsable].'  como '.$this->nombres_columnas[id_revisa]);
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                $( '#btn-guardar' ).prop( 'disabled', false );
                                $('#btn-guardar-not' ).html('Guardar y Notificar');
                                $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                            return $objResponse;
                        }
                        if($parametros[id_revisa]==$parametros[id_responsable_actual]){
                            $objResponse->addScriptCall('VerMensaje','error','No puede seleccionar el mismo '.$this->nombres_columnas[id_responsable_actual].'  como '.$this->nombres_columnas[id_revisa]);
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                $( '#btn-guardar' ).prop( 'disabled', false );
                                $('#btn-guardar-not' ).html('Guardar y Notificar');
                                $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                            return $objResponse;
                        }
                        if($parametros[id_revisa]==$parametros[id_aprueba]){
                            $objResponse->addScriptCall('VerMensaje','error','No puede seleccionar el mismo '.$this->nombres_columnas[id_aprueba].' como '.$this->nombres_columnas[id_revisa]);
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                $( '#btn-guardar' ).prop( 'disabled', false );
                                $('#btn-guardar-not' ).html('Guardar y Notificar');
                                $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                            return $objResponse;
                        }                  
                        
                    }
                    else{
                        $objResponse->addScriptCall('VerMensaje','error','Debe seleccionar un '.$this->nombres_columnas[id_revisa]);
                        $objResponse->addScript("$('#MustraCargando').hide();"); 
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                            $( '#btn-guardar' ).prop( 'disabled', false );
                            $('#btn-guardar-not' ).html('Guardar y Notificar');
                            $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                        return $objResponse;                            
                    }
                    
                }
                if($parametros[migrar_wf_aprueba]=='S'){
                    if($parametros[id_aprueba]!=''){
                        if($parametros[id_aprueba]==$parametros[id_nuevo_responsable]){
                            $objResponse->addScriptCall('VerMensaje','error','No puede seleccionar el mismo '.$this->nombres_columnas[id_nuevo_responsable].' como '.$this->nombres_columnas[id_aprueba]);
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                $( '#btn-guardar' ).prop( 'disabled', false );
                                $('#btn-guardar-not' ).html('Guardar y Notificar');
                                $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                            return $objResponse;
                        }                  
                        if($parametros[id_aprueba]==$parametros[id_responsable_actual]){
                            $objResponse->addScriptCall('VerMensaje','error','No puede seleccionar el mismo '.$this->nombres_columnas[id_responsable_actual].' como '.$this->nombres_columnas[id_aprueba]);
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                $( '#btn-guardar' ).prop( 'disabled', false );
                                $('#btn-guardar-not' ).html('Guardar y Notificar');
                                $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                            return $objResponse;
                        }                  
                        if($parametros[id_revisa]==$parametros[id_aprueba]){
                            $objResponse->addScriptCall('VerMensaje','error','No puede seleccionar el mismo '.$this->nombres_columnas[id_aprueba].' como '.$this->nombres_columnas[id_revisa]);
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                $( '#btn-guardar' ).prop( 'disabled', false );
                                $('#btn-guardar-not' ).html('Guardar y Notificar');
                                $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                            return $objResponse;
                        }  
                    }
                    else{
                        $objResponse->addScriptCall('VerMensaje','error','Debe seleccionar un '.$this->nombres_columnas[id_aprueba]);
                        $objResponse->addScript("$('#MustraCargando').hide();"); 
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                            $( '#btn-guardar' ).prop( 'disabled', false );
                            $('#btn-guardar-not' ).html('Guardar y Notificar');
                            $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                        return $objResponse;                            
                    }
                }
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{                    
                    $respuesta = $this->ingresarDocumentosMigracion($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $this->EjecutarDocumentosMigracion($parametros);
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
                $val = $this->verDocumentosMigracion($parametros[id]); 

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
                            $contenido_1['ID_RESPONSABLE_ACTUAL'] = $val["id_responsable_actual"];
            $contenido_1['ID_NUEVO_RESPONSABLE'] = $val["id_nuevo_responsable"];
            $contenido_1['FECHA_OPERACION'] = $val["fecha_operacion"];
            $contenido_1['MIGRAR_RESPONSABLE_DOC'] = ($val["migrar_responsable_doc"]);
            $contenido_1['MIGRAR_WF_REVISA'] = ($val["migrar_wf_revisa"]);
            $contenido_1['ID_REVISA'] = $val["id_revisa"];
            $contenido_1['MIGRAR_WF_APRUEBA'] = ($val["migrar_wf_aprueba"]);
            $contenido_1['ID_APRUEBA'] = $val["id_aprueba"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos_migracion/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;DocumentosMigracion";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;DocumentosMigracion";
                $contenido['PAGINA_VOLVER'] = "listarDocumentosMigracion.php";
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
                    
                    $respuesta = $this->modificarDocumentosMigracion($parametros);

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
                $val = $this->verDocumentosMigracion($parametros[id]);
                $respuesta = $this->eliminarDocumentosMigracion($parametros);
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
                $grid = $this->verListaDocumentosMigracion($parametros);                
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

                $val = $this->verDocumentosMigracion($parametros[id]);

                            $contenido_1['ID_RESPONSABLE_ACTUAL'] = $val["id_responsable_actual"];
            $contenido_1['ID_NUEVO_RESPONSABLE'] = $val["id_nuevo_responsable"];
            $contenido_1['FECHA_OPERACION'] = $val["fecha_operacion"];
            $contenido_1['MIGRAR_RESPONSABLE_DOC'] = ($val["migrar_responsable_doc"]);
            $contenido_1['MIGRAR_WF_REVISA'] = ($val["migrar_wf_revisa"]);
            $contenido_1['ID_REVISA'] = $val["id_revisa"];
            $contenido_1['MIGRAR_WF_APRUEBA'] = ($val["migrar_wf_aprueba"]);
            $contenido_1['ID_APRUEBA'] = $val["id_aprueba"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos_migracion/';
                $template->setTemplate("verDocumentosMigracion");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la DocumentosMigracion";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>