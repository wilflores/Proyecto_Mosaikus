<?php
 import("clases.interfaz.Pagina");        
        class Notificaciones extends Pagina{
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
        
            
            public function Notificaciones(){
                parent::__construct();
                $this->asigna_script('notificaciones/notificaciones.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 24";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 24";
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
                        $html .= '<a onclick="javascript:editarNotificaciones(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar Notificaciones" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarNotificaciones(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar Notificaciones" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
            public function colum_admin_arbol($tupla)
            {                
                if ($this->id_org_acceso[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarNotificaciones('". $tupla[id] . "');\"  title=\"Editar Notificaciones\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->id_org_acceso[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= '<a href=\"#\" onclick=\"javascript:eliminarNotificaciones(\''. $tupla[id] . '\');\" title=\"Eliminar Notificaciones\">
                            <i class=\"icon icon-remove\"></i>

                        </a>'; 
                }
                return $html;
            }

            public function LeerNotificacion($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    

                    $sql = "UPDATE mos_notificaciones SET                            
                                    fecha_leido = now()
                            WHERE  id = $atr[id]";      
                    $this->dbl->insert_update($sql);
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
              public function VerNotificacionEmergente($atr){
                $sql = "SELECT id
                        ,DATE_FORMAT(fecha, '%d/%m/%Y %H:%i')fecha
                        ,email
                        ,asunto
                        ,cuerpo
                        ,fecha_leido
                        ,modulo
                        ,funcion
                         FROM mos_notificaciones 
                         WHERE email = '".$atr['email']."' and fecha_leido is null and fecha_alerta is null "
                     . " order by id desc;"; 
                //echo $sql;
                $this->operacion($sql, $atr);
                $val = $this->dbl->data;
                if(sizeof($val)>0){
                    $ids = array();
                    foreach ($val as $valor) {
                        $ids[]=$valor[id];
                    }
                    $sql = "UPDATE mos_notificaciones SET                            
                                    fecha_alerta = now()
                            WHERE  id in (".implode(',',$ids )." )";      
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                }
                return $this->dbl->data;
            }    
             public function ListarNotificacionesNoLeidas($atr){
                $sql = "SELECT id
                        ,DATE_FORMAT(fecha, '%d/%m/%Y %H:%i')fecha
                        ,email
                        ,asunto
                        ,cuerpo
                        ,fecha_leido
                        ,modulo
                        ,funcion
                         FROM mos_notificaciones 
                         WHERE email = '".$atr['email']."' and fecha_leido is null "
                     . " order by id desc"; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
             public function verNotificaciones($id){
                $atr=array();
                $sql = "SELECT id
,fecha
,email
,asunto
,cuerpo
,fecha_leido
,modulo

                         FROM mos_notificaciones 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarNotificaciones($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    /*Carga Acceso segun el arbol*/
                    //echo $sql;
                    $sql = "INSERT INTO mos_notificaciones(fecha,email,asunto,cuerpo,modulo,funcion)
                            VALUES(
                                now(),'$atr[email]','$atr[asunto]','$atr[cuerpo]','$atr[modulo]','$atr[funcion]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_notificaciones ' . $atr[descripcion_ano], 'mos_notificaciones');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_notificaciones"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Fecha: \'$atr[fecha]\', Email: \'$atr[email]\', Asunto: \'$atr[asunto]\', Cuerpo: \'$atr[cuerpo]\', Fecha Leido: \'$atr[fecha_leido]\', Id Modulo: \'$atr[modulo]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', $id_new);
                    return "El mos_notificaciones '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarNotificaciones($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol*/

                    $sql = "UPDATE mos_notificaciones SET                            
                                    fecha = now(),email = '$atr[email]',asunto = '$atr[asunto]',cuerpo = '$atr[cuerpo]',fecha_leido = NULL,fecha_alerta = NULL,modulo = '$atr[modulo]'
                            WHERE  id = $atr[id]";      
                    //echo $sql;
                    $val = $this->verNotificaciones($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Fecha: \'$atr[fecha]\', Email: \'$atr[email]\', Asunto: \'$atr[asunto]\', Cuerpo: \'$atr[cuerpo]\', Fecha Leido: \'$atr[fecha_leido]\', Id Modulo: \'$atr[modulo]\', ";
                    $anterior = "Fecha: \'$val[fecha]\', Email: \'$val[email]\', Asunto: \'$val[asunto]\', Cuerpo: \'$val[cuerpo]\', Fecha Leido: \'$val[fecha_leido]\', Id Modulo: \'$val[modulo]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Notificaciones ' . $atr[descripcion_ano], 'mos_notificaciones');
                    */
                    return "El mos_notificaciones '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarNotificaciones($atr, $pag, $registros_x_pagina){
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
                         FROM mos_notificaciones 
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
                                 if (strlen($atr["b-fecha"])>0)
                        $sql .= " AND fecha = '". $atr["b-fecha"] . "'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
            if (strlen($atr["b-asunto"])>0)
                        $sql .= " AND upper(asunto) like '%" . strtoupper($atr["b-asunto"]) . "%'";
            if (strlen($atr["b-cuerpo"])>0)
                        $sql .= " AND upper(cuerpo) like '%" . strtoupper($atr["b-cuerpo"]) . "%'";
             if (strlen($atr["b-fecha_leido"])>0)
                        $sql .= " AND fecha_leido = '". $atr["b-fecha_leido"] . "'";
             if (strlen($atr["b-modulo"])>0)
                        $sql .= " AND modulo = '". $atr["b-modulo"] . "'";

                    if (count($this->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                    }
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
,fecha
,email
,asunto
,cuerpo
,fecha_leido
,modulo

                                     $sql_col_left
                            FROM mos_notificaciones $sql_left
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
                                 if (strlen($atr["b-fecha"])>0)
                        $sql .= " AND fecha = '". $atr["b-fecha"] . "'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
            if (strlen($atr["b-asunto"])>0)
                        $sql .= " AND upper(asunto) like '%" . strtoupper($atr["b-asunto"]) . "%'";
            if (strlen($atr["b-cuerpo"])>0)
                        $sql .= " AND upper(cuerpo) like '%" . strtoupper($atr["b-cuerpo"]) . "%'";
             if (strlen($atr["b-fecha_leido"])>0)
                        $sql .= " AND fecha_leido = '". $atr["b-fecha_leido"] . "'";
             if (strlen($atr["b-modulo"])>0)
                        $sql .= " AND modulo = '". $atr["b-modulo"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function listarNotificacionesHistorico($atr, $pag, $registros_x_pagina){
                 //print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_notificaciones 
                         WHERE 1 = 1 and email='$_SESSION[CookEmail]'";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(asunto) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%') OR (upper(cuerpo) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-fecha"])>0)
                        $sql .= " AND DATE_FORMAT(fecha, '%d/%m/%Y') like '%" . strtoupper($atr["b-fecha"]) . "%'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
            if (strlen($atr["b-asunto"])>0)
                        $sql .= " AND upper(asunto) like '%" . strtoupper($atr["b-asunto"]) . "%'";
            if (strlen($atr["b-cuerpo"])>0)
                        $sql .= " AND upper(cuerpo) like '%" . strtoupper($atr["b-cuerpo"]) . "%'";
             if (strlen($atr["b-fecha_leido"])>0)
                        $sql .= " AND DATE_FORMAT(fecha_leido, '%d/%m/%Y %H:%d') like '%" . strtoupper($atr["b-fecha_leido"]). "%'";
             if (strlen($atr["b-modulo"])>0)
                        $sql .= " AND modulo = '". $atr["b-modulo"] . "'";

                    if (count($this->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                    }
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha
                                    ,asunto
                                    ,cuerpo
                                    ,DATE_FORMAT(fecha_leido, '%d/%m/%Y %H:%d') fecha_leido
                                    ,modulo
                                     $sql_col_left
                            FROM mos_notificaciones $sql_left
                            WHERE 1 = 1 and email='$_SESSION[CookEmail]'";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(asunto) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%') OR (upper(cuerpo) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-fecha"])>0)
                        $sql .= " AND DATE_FORMAT(fecha, '%d/%m/%Y') like '%" . strtoupper($atr["b-fecha"]). "%'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
            if (strlen($atr["b-asunto"])>0)
                        $sql .= " AND upper(asunto) like '%" . strtoupper($atr["b-asunto"]) . "%'";
            if (strlen($atr["b-cuerpo"])>0)
                        $sql .= " AND upper(cuerpo) like '%" . strtoupper($atr["b-cuerpo"]) . "%'";
             if (strlen($atr["b-fecha_leido"])>0)
                        $sql .= " AND DATE_FORMAT(fecha_leido, '%d/%m/%Y %H:%d') like '%" . strtoupper($atr["b-fecha_leido"]). "%'";
             if (strlen($atr["b-modulo"])>0)
                        $sql .= " AND modulo = '". $atr["b-modulo"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarNotificaciones($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verNotificaciones($atr[id]);
                        $respuesta = $this->dbl->delete("mos_notificaciones", "id = " . $atr[id]);
                        $nuevo = "Fecha: \'$val[fecha]\', Email: \'$val[email]\', Asunto: \'$val[asunto]\', Cuerpo: \'$val[cuerpo]\', Fecha Leido: \'$val[fecha_leido]\', Id Modulo: \'$val[modulo]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaNotificaciones($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarNotificaciones($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblNotificaciones", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha], "fecha", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email], "email", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[asunto], "asunto", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cuerpo], "cuerpo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_leido], "fecha_leido", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[modulo], "modulo", $parametros))
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
                    
                    $columna_funcion = 8;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verNotificaciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Notificaciones'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarNotificaciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar Notificaciones'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarNotificaciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar Notificaciones'>"));
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
        
            public function verListaNotificacionesHistorico($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarNotificacionesHistorico($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblNotificaciones", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha], "fecha", $parametros)),
               array( "width"=>"30%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[asunto], "asunto", $parametros)),
               array( "width"=>"35%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cuerpo], "cuerpo", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_leido], "fecha_leido", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[modulo], "modulo", $parametros))
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
                    
                    $columna_funcion = 8;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verNotificaciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Notificaciones'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarNotificaciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar Notificaciones'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarNotificaciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar Notificaciones'>"));
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
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPaginaHistorico", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarNotificaciones($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblNotificaciones", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[asunto], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cuerpo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_leido], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[modulo], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexNotificaciones($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-"; 
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
                $grid = $this->verListaNotificaciones($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Notificaciones();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Notificaciones';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'notificaciones/';
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
                $template->PATH = PATH_TO_TEMPLATES.'notificaciones/';

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
                $objResponse->addAssign('modulo_actual',"value","notificaciones");
                $objResponse->addIncludeScript(PATH_TO_JS . 'notificaciones/notificaciones.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_simple();');
                return $objResponse;
            }

            public function indexNotificacionesHistorico($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-"; 
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
                $grid = $this->verListaNotificacionesHistorico($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Notificaciones();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Notificaciones';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'notificaciones/';
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
                $contenido["VERHISTO"] =  'S';
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'notificaciones/';

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
                $objResponse->addAssign('modulo_actual',"value","notificaciones");
                $objResponse->addIncludeScript(PATH_TO_JS . 'notificaciones/notificaciones.js');
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
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'notificaciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;Notificaciones";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Notificaciones";
                $contenido['PAGINA_VOLVER'] = "listarNotificaciones.php";
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
                    
                    $respuesta = $this->ingresarNotificaciones($parametros);

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
                $val = $this->verNotificaciones($parametros[id]); 

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
                            $contenido_1['FECHA'] = $val["fecha"];
            $contenido_1['EMAIL'] = ($val["email"]);
            $contenido_1['ASUNTO'] = ($val["asunto"]);
            $contenido_1['CUERPO'] = ($val["cuerpo"]);
            $contenido_1['FECHA_LEIDO'] = $val["fecha_leido"];
            $contenido_1['ID_MODULO'] = $val["modulo"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'notificaciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Notificaciones";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Notificaciones";
                $contenido['PAGINA_VOLVER'] = "listarNotificaciones.php";
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
                    
                    $respuesta = $this->modificarNotificaciones($parametros);

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
                $val = $this->verNotificaciones($parametros[id]);
                $respuesta = $this->eliminarNotificaciones($parametros);
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
                $grid = $this->verListaNotificaciones($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
                public function buscarHistorico($parametros)
            {
                /*Permisos en caso de que no se use el arbol organizacional*/
                $this->cargar_permisos($parametros);
                $grid = $this->verListaNotificacionesHistorico($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
                public function VerNotificacionesMenu($parametros)
            {
                session_name("mosaikus");
                session_start();
                //echo $_SESSION['CookEmail'];
                $parametros['email']=$_SESSION['CookEmail'];
                $parametros['leidas']='NO';
                $datos = $this->ListarNotificacionesNoLeidas($parametros);
                $i=0;
                $esconder='no';
                $cant= sizeof($datos);
                $heightdiv=0;
                //echo $cant;
                foreach ($datos as $value) {
                    $html .='<li id=noti'.$value[id].' '.$styleul.'>';
                    $html .= "<div  align='right' style='float:right;margin-top: -8px;'><a id='cerrar".$value[id]."' title='cerrar notificacion' style='
                        color: #000;font-family: impact; font-size: 23px;text-shadow:   -1px -1px 0 #fff,1px -1px 0 #fff,-1px 1px 0 #fff,1px 1px 0 #fff;' 
                        onclick='LeerNotificacionesMenu(".$value[id].");' href='#'>&#215;</a></div>";
                    if($value[funcion]!='')
                        $html .= "<a onclick='".$value[funcion]."' href='#'>";
                    $html .= '<strong>'.($i+1).'-'.$value[asunto].'&nbsp;['.$value[fecha].']</strong><br>';
                    $html .= "<span id='cuerpo_".$value[id]."'>";
                    //$html .= '<br>'.$value[cuerpo];
                    $html .= ''.$value[cuerpo];
                        //$html .= "<a onclick=\"document.getElementById('cuerpo_".$value[id]."').innerHTML='".$value[cuerpo]."';LeerNotificacionesMenu(".$value[id].");\" href='#'>";
                    if($value[funcion]!='')
                        $html .= "</a>";
                        //$html .= "<br><a id='marcarleido".$value[id]."' onclick='LeerNotificacionesMenu(".$value[id].");this.innerHTML=\"\";'; href='#'>";
                        //$html .= "<strong>(Marcar como leido)</strong></a>";                        
                    $html .= '</span>';
                    $html .='</li>';
                    $i++;
                    if($i>3){
                        if($esconder=='no'){
                            $esconder='si';
                            $styleul="style='display:none;'";
                        }
                        if($i<=$cant)
                            $clicvermas .= " document.getElementById('noti".$value[id]."').style.display='';"; 
                    }
                    else{
                        $heightdiv=$heightdiv+230;
                    }
                }
                if($cant>3){
                        $html .="<li  id=vermas>";
                        $html .= "<a onclick=\"" .$clicvermas."\" ><strong>Ver todas las alertas</strong></a>" ;
                        $html .='</li>';

                }
                else{
                    $heightdiv=$heightdiv-80;
                }
                if($cant==0){
                        $html .='<li id=vermas>';
                        $html .= "NO HAY NUEVAS NOTIFICACIONES" ;
                        $html .='</li>';
                        $heightdiv=150;

                }
                $html .='<li id=verhistorial >';
                $html .= "<a onclick='VerhistoricoNotificaciones()' ><strong>Ver&nbsp;Historial</strong></a>" ;
                $html .='</li>';

                $objResponse = new xajaxResponse();
                $objResponse->addScript("document.getElementById('div-notificaciones').style.height='".$heightdiv."px';"); 
                $objResponse->addAssign('popover-notificaciones',"innerHTML",$html);
                $objResponse->addAssign('cantidad_notificaciones',"innerHTML",$i);
                return $objResponse;
            }
            public function LeerNotificacionesMenu($parametros)
            {
                //echo $_SESSION['CookEmail'];
                $this->LeerNotificacion($parametros);
                session_name("mosaikus");
                session_start();
                $parametros['email']=$_SESSION['CookEmail'];
                $parametros['leidas']='NO';
                $datos = $this->ListarNotificacionesNoLeidas($parametros);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('cantidad_notificaciones',"innerHTML",sizeof($datos));
                return $objResponse;
            }
            public function MostrarNotificacionesEmergente($parametros)
            {
                session_name("mosaikus");
                session_start();
                //echo $_SESSION['CookEmail'];
                $parametros['email']=$_SESSION['CookEmail'];
                $val = $this->VerNotificacionEmergente($parametros);
               // notifyBrowser('Notificacion','xxxx xxxxxxx ','');
                $datos = $this->ListarNotificacionesNoLeidas($parametros);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('cantidad_notificaciones',"innerHTML",sizeof($datos));
                if(sizeof($val)>3){
                    //print_r($val);
                    $objResponse->addScript('notifyBrowser("Notificaciones Mosaikus","Tiene '.sizeof($val).' nuevas notificaciones por leer.","mostrarventana");');
                }
                else if(sizeof($val)>0){
                   // echo 'si';
                    $funciones='';
                    foreach ($val as $value) {                       
                        //echo $value[asunto];
                        $funciones ='notifyBrowser("Notificaciones Mosaikus","'.$value[asunto].' del '.$value[fecha].'","'.$value[funcion].'");';
                        $objResponse->addScript($funciones);
                        $objResponse->addScript('sleep(5000);');
                        //$funciones .= 'notifyBrowser("Notificaciones Mosaikus","fdsfsdf","");sleep(2000);';
                    }
                    //echo $funciones;
                    
                    //$objResponse->addScript('notifyBrowser("Notificaciones Mosaikus","fdsfsdf","");');
                }   
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verNotificaciones($parametros[id]);

                            $contenido_1['FECHA'] = $val["fecha"];
            $contenido_1['EMAIL'] = ($val["email"]);
            $contenido_1['ASUNTO'] = ($val["asunto"]);
            $contenido_1['CUERPO'] = ($val["cuerpo"]);
            $contenido_1['FECHA_LEIDO'] = $val["fecha_leido"];
            $contenido_1['ID_MODULO'] = $val["modulo"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'notificaciones/';
                $template->setTemplate("verNotificaciones");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Notificaciones";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>