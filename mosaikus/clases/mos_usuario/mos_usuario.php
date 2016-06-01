<?php
 import("clases.interfaz.Pagina");        
        class mos_usuario extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;

        private $per_crear;
        private $per_editar;
        private $per_eliminar;        
        
            public function mos_usuario(){
                parent::__construct();
                $this->asigna_script('mos_usuario/mos_usuario.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->contenido = array();
            }
            /*
            * Busca los permisos que tiene el usuario en el modulo
            */
            private function cargar_permisos($parametros){
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();
                    $data_permisos = $acceso->obtenerPermisosModulo($_SESSION[CookIdUsuario],$parametros[cod_link]);   
                    
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

            public function columna_accion($tupla)
            {
                $html = "&nbsp;";
                //print_r($tupla);                
                if (strlen($tupla[id_usuario])>0){
                    if($this->per_editar == 'S'){
                        $html .= '<a onclick="javascript:editarmos_usuario(\''.$tupla[id_usuario].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar Usuario" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarmos_usuario(\''.$tupla[id_usuario].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar Usuario" style="cursor:pointer"></i>
                                </a>';
                    }
                    if($this->per_editar == 'S'){
                        $html .= '<a onclick="javascript:configurarmos_usuario(\''.$tupla[id_usuario].'\' );">
                                    <i style="cursor:pointer" class="icon icon-more"  title="Configurar Perfiles" style="cursor:pointer"></i>
                                </a>';
                        if($tupla[perfil_portal] > 0){
                            $html .= '<a onclick="javascript:perfil_portal(\''.$tupla[id_usuario].'\' );">
                                        <i style="cursor:pointer" class="icon-app-mode icon-app-mode-portal"  title="Administrar Perfiles Portal" style="cursor:pointer"></i>
                                    </a>';                            
                        }
                        if($tupla[perfil_especialista] > 0){
                            $html .= '<a onclick="javascript:perfil_especialista(\''.$tupla[id_usuario].'\' );">
                                        <i style="cursor:pointer" class="icon-app-mode icon-app-mode-specialist"  title="Administrar Perfiles Especialista" style="cursor:pointer"></i>
                                    </a>';                            
                        }                        
                    }
                }
                return $html;
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 21";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }                
            }
            
            private function cargar_nombres_columnas_perfil(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 19";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }                
            }            
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 21";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }                
            }

            public function verfilial($id){
               $atr=array();
               $sql="SELECT id, id_usuario, cod_perfil FROM mos_usuario_filial WHERE id = $id";
               $this->operacion($sql, $atr);
               return $this->dbl->data[0];
            }
     
            public function verfilialPortal($id){
               $atr=array();
               $sql="SELECT id, id_usuario, cod_perfil_portal as cod_perfil FROM mos_usuario_filial WHERE id = $id";
               $this->operacion($sql, $atr);
               return $this->dbl->data[0];
            }

             public function vermos_usuario($id){
                $atr=array();
                $sql = "SELECT id_usuario
                        ,nombres
                        ,apellido_paterno
                        ,apellido_materno
                        ,telefono
                        ,DATE_FORMAT(fecha_creacion, '%d/%m/%Y') fecha_creacion
                        ,DATE_FORMAT(fecha_expi, '%d/%m/%Y') fecha_expi
                        ,vigencia
                        ,super_usuario
                        ,email
                        ,password_1
                        ,cedula
                        ,recibe_notificaciones
                         FROM mos_usuario 
                         WHERE id_usuario = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verusuario_filial($id_usuario){
                $atr = array();
                $sql = "SELECT id, id_usuario, id_filial,cod_perfil,cod_perfil_portal,ultimo_acceso 
                        FROM mos_usuario_filial 
                        WHERE id_usuario = $id_usuario";
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            public function verusuario_estructura($id_usuario){
                $atr = array();
                $sql = "SELECT mue.id, mue.id_usuario_filial, mue.id_estructura,mue.id_usuario, mue.cod_perfil
                        FROM 
                        mos_usuario_estructura AS mue INNER JOIN
                        mos_usuario_filial AS muf ON mue.id_usuario_filial = muf.id
                        WHERE
                        muf.id_usuario = $id_usuario";
                
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            public function ingresarmos_usuario($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "INSERT INTO mos_usuario(nombres,apellido_paterno,apellido_materno,telefono,fecha_creacion,fecha_expi,vigencia,super_usuario,email,password_1,cedula,recibe_notificaciones)
                            VALUES(
                                '$atr[nombres]','$atr[apellido_paterno]','$atr[apellido_materno]','$atr[telefono]','".date('Y-m-d G:h:s')."','$atr[fecha_expi]','$atr[vigencia]','$atr[super_usuario]','$atr[email]','".md5($atr[password_1])."','$atr[cedula]','$atr[recibe_notificaciones]'
                                )";
                    $respuesta = $this->dbl->insert_update($sql);                    
                    $nuevo = "Id Usuario: \'$atr[id_usuario]\', Nombres: \'$atr[nombres]\', Apellido Paterno: \'$atr[apellido_paterno]\', Apellido Materno: \'$atr[apellido_materno]\', Telefono: \'$atr[telefono]\', Fecha Creacion: \'$atr[fecha_creacion]\', Fecha Expi: \'$atr[fecha_expi]\', Vigencia: \'$atr[vigencia]\', Super Usuario: \'$atr[super_usuario]\', Email: \'$atr[email]\', Password 1: \'$atr[password_1]\', Cedula: \'$atr[cedula]\', Recibe Notificaciones: \'$atr[recibe_notificaciones]\', ";
                    $this->registraTransaccionLog(21,$nuevo,'', '');
                    return "El usuario '$atr[nombres]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/Duplicate entry/",$error ) == true) 
                            return "Usuario ya existe en el sistema.";                        
                        return $error; 
                    }
            }
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = ''){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]')";            
                $this->dbl->insert_update($sql);
                return true;
            }

            public function modificarmos_usuario($atr){
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $val = $this->vermos_usuario($atr[id_usuario]);
                    if($val[password_1]== $atr[password_1]){
                        $pass = $val[password_1];
                    }else{
                        $pass = md5($atr[password_1]);
                    }
                    $sql = "UPDATE mos_usuario SET                            
                                    nombres = '$atr[nombres]',apellido_paterno = '$atr[apellido_paterno]',apellido_materno = '$atr[apellido_materno]',telefono = '$atr[telefono]',fecha_expi = '$atr[fecha_expi]',vigencia = '$atr[vigencia]',super_usuario = '$atr[super_usuario]',email = '$atr[email]',password_1 = '$pass',cedula = '$atr[cedula]',recibe_notificaciones = '$atr[recibe_notificaciones]'
                            WHERE  id_usuario = $atr[id_usuario]"; 
                    
                    
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Usuario: \'$atr[id_usuario]\', Nombres: \'$atr[nombres]\', Apellido Paterno: \'$atr[apellido_paterno]\', Apellido Materno: \'$atr[apellido_materno]\', Telefono: \'$atr[telefono]\', Fecha Creacion: \'$atr[fecha_creacion]\', Fecha Expi: \'$atr[fecha_expi]\', Vigencia: \'$atr[vigencia]\', Super Usuario: \'$atr[super_usuario]\', Email: \'$atr[email]\', Password 1: \'$atr[password_1]\', Cedula: \'$atr[cedula]\', ";
                    $anterior = "Id Usuario: \'$val[id_usuario]\', Nombres: \'$val[nombres]\', Apellido Paterno: \'$val[apellido_paterno]\', Apellido Materno: \'$val[apellido_materno]\', Telefono: \'$val[telefono]\', Fecha Creacion: \'$val[fecha_creacion]\', Fecha Expi: \'$val[fecha_expi]\', Vigencia: \'$val[vigencia]\', Super Usuario: \'$val[super_usuario]\', Email: \'$val[email]\', Password 1: \'$val[password_1]\', Cedula: \'$val[cedula]\', ";
                    $this->registraTransaccionLog(21,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el mos_usuario ' . $atr[descripcion_ano], 'mos_usuario');
                    */
                    return "El usuario '$atr[nombres]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarmos_usuario($atr, $pag, $registros_x_pagina){
                //print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_usuario 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
            if (strlen($atr["b-nombres"])>0)
                        $sql .= " AND upper(nombres) like '%" . strtoupper($atr["b-nombres"]) . "%'";
            if (strlen($atr["b-apellido_paterno"])>0)
                        $sql .= " AND upper(apellido_paterno) like '%" . strtoupper($atr["b-apellido_paterno"]) . "%'";
            if (strlen($atr["b-apellido_materno"])>0)
                        $sql .= " AND upper(apellido_materno) like '%" . strtoupper($atr["b-apellido_materno"]) . "%'";
            if (strlen($atr["b-telefono"])>0)
                        $sql .= " AND upper(telefono) like '%" . strtoupper($atr["b-telefono"]) . "%'";
             if (strlen($atr['b-fecha_creacion-desde'])>0)                        
                    {
                        $atr['b-fecha_creacion-desde'] = formatear_fecha($atr['b-fecha_creacion-desde']);                        
                        $sql .= " AND fecha_creacion >= '" . ($atr['b-fecha_creacion-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_creacion-hasta'])>0)                        
                    {
                        $atr['b-fecha_creacion-hasta'] = formatear_fecha($atr['b-fecha_creacion-hasta']);                        
                        $sql .= " AND fecha_creacion <= '" . ($atr['b-fecha_creacion-hasta']) . "'";                        
                    }
             if (strlen($atr['b-fecha_expi-desde'])>0)                        
                    {
                        $atr['b-fecha_expi-desde'] = formatear_fecha($atr['b-fecha_expi-desde']);                        
                        $sql .= " AND fecha_expi >= '" . ($atr['b-fecha_expi-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_expi-hasta'])>0)                        
                    {
                        $atr['b-fecha_expi-hasta'] = formatear_fecha($atr['b-fecha_expi-hasta']);                        
                        $sql .= " AND fecha_expi <= '" . ($atr['b-fecha_expi-hasta']) . "'";                        
                    }
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
            if (strlen($atr["b-super_usuario"])>0)
                        $sql .= " AND upper(super_usuario) like '%" . strtoupper($atr["b-super_usuario"]) . "%'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
            if (strlen($atr["b-password_1"])>0)
                        $sql .= " AND upper(password_1) like '%" . strtoupper($atr["b-password_1"]) . "%'";
            if (strlen($atr["b-cedula"])>0)
                        $sql .= " AND upper(cedula) like '%" . strtoupper($atr["b-cedula"]) . "%'";
            if (strlen($atr["b-perfil_especialista"])>0)
                    $sql .= "AND id_usuario IN (SELECT id_usuario FROM mos_usuario_filial INNER JOIN mos_perfil ON mos_usuario_filial.cod_perfil = mos_perfil.cod_perfil WHERE mos_perfil.cod_perfil = ". strtoupper($atr["b-perfil_especialista"]) . ")";
            if (strlen($atr["b-perfil_portal"])>0)
                    $sql .= "AND id_usuario IN (SELECT id_usuario FROM mos_usuario_filial INNER JOIN mos_perfil_portal ON mos_usuario_filial.cod_perfil_portal = mos_perfil_portal.cod_perfil WHERE mos_perfil_portal.cod_perfil = ". strtoupper($atr["b-perfil_portal"]) . ")";
            
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id_usuario
                            ,nombres
                            ,apellido_paterno
                            ,apellido_materno
                            ,telefono
                            ,DATE_FORMAT(fecha_creacion, '%d/%m/%Y') fecha_creacion
                            ,DATE_FORMAT(fecha_expi, '%d/%m/%Y') fecha_expi
                            ,vigencia
                            ,super_usuario
                            ,email
                            ,password_1
                            ,cedula
                            ,recibe_notificaciones
                            ,(SELECT COUNT(muf.cod_perfil_portal) FROM mos_usuario_filial as muf WHERE muf.cod_perfil_portal IS NOT NULL AND muf.id_usuario = mu.id_usuario ) as perfil_portal 
                            ,(SELECT COUNT(muf.cod_perfil) FROM mos_usuario_filial as muf WHERE muf.cod_perfil IS NOT NULL AND muf.id_usuario = mu.id_usuario ) as perfil_especialista                            
                            $sql_col_left
                            FROM mos_usuario as mu $sql_left
                            WHERE 1 = 1 ";
                
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
            if (strlen($atr["b-nombres"])>0)
                        $sql .= " AND upper(nombres) like '%" . strtoupper($atr["b-nombres"]) . "%'";
            if (strlen($atr["b-apellido_paterno"])>0)
                        $sql .= " AND upper(apellido_paterno) like '%" . strtoupper($atr["b-apellido_paterno"]) . "%'";
            if (strlen($atr["b-apellido_materno"])>0)
                        $sql .= " AND upper(apellido_materno) like '%" . strtoupper($atr["b-apellido_materno"]) . "%'";
            if (strlen($atr["b-telefono"])>0)
                        $sql .= " AND upper(telefono) like '%" . strtoupper($atr["b-telefono"]) . "%'";
             if (strlen($atr['b-fecha_creacion-desde'])>0)                        
                    {
                        $atr['b-fecha_creacion-desde'] = formatear_fecha($atr['b-fecha_creacion-desde']);                        
                        $sql .= " AND fecha_creacion >= '" . ($atr['b-fecha_creacion-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_creacion-hasta'])>0)                        
                    {
                        $atr['b-fecha_creacion-hasta'] = formatear_fecha($atr['b-fecha_creacion-hasta']);                        
                        $sql .= " AND fecha_creacion <= '" . ($atr['b-fecha_creacion-hasta']) . "'";                        
                    }
             if (strlen($atr['b-fecha_expi-desde'])>0)                        
                    {
                        $atr['b-fecha_expi-desde'] = formatear_fecha($atr['b-fecha_expi-desde']);                        
                        $sql .= " AND fecha_expi >= '" . ($atr['b-fecha_expi-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_expi-hasta'])>0)                        
                    {
                        $atr['b-fecha_expi-hasta'] = formatear_fecha($atr['b-fecha_expi-hasta']);                        
                        $sql .= " AND fecha_expi <= '" . ($atr['b-fecha_expi-hasta']) . "'";                        
                    }
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
            if (strlen($atr["b-super_usuario"])>0)
                        $sql .= " AND upper(super_usuario) like '%" . strtoupper($atr["b-super_usuario"]) . "%'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
            if (strlen($atr["b-password_1"])>0)
                        $sql .= " AND upper(password_1) like '%" . strtoupper($atr["b-password_1"]) . "%'";
            if (strlen($atr["b-cedula"])>0)
                        $sql .= " AND upper(cedula) like '%" . strtoupper($atr["b-cedula"]) . "%'";
            if (strlen($atr["b-perfil_especialista"])>0)
                    $sql .= "AND id_usuario IN (SELECT id_usuario FROM mos_usuario_filial INNER JOIN mos_perfil ON mos_usuario_filial.cod_perfil = mos_perfil.cod_perfil WHERE mos_perfil.cod_perfil = ". strtoupper($atr["b-perfil_especialista"]) . ")";
            if (strlen($atr["b-perfil_portal"])>0)
                    $sql .= "AND id_usuario IN (SELECT id_usuario FROM mos_usuario_filial INNER JOIN mos_perfil_portal ON mos_usuario_filial.cod_perfil_portal = mos_perfil_portal.cod_perfil WHERE mos_perfil_portal.cod_perfil = ". strtoupper($atr["b-perfil_portal"]) . ")";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //print_r($sql);
                    $this->operacion($sql, $atr);
             }
             
             public function eliminarmos_usuario($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $estructura = $this->verusuario_estructura($atr[id]);                     
                    foreach ($estructura as $arrE){                        
                        $respuesta = $this->dbl->delete("mos_usuario_estructura", "id_usuario = " . $arrE[id_usuario]);
                    }
                    
                    $filial = $this->verusuario_filial($atr[id]);                    
                    foreach ($filial as $arrF){                         
                        $this->dbl->delete("mos_usuario_filial", "id_usuario = " . $arrF[id_usuario]);
                    }                    
                    //por ultimos se elimina el usuario
                    $respuesta = $this->dbl->delete("mos_usuario", "id_usuario = " . $atr[id]);
                    return "ha sido eliminada con exito";
                    
                } catch(Exception $e) {
                    $error = $e->getMessage();                     
                    if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                        return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                    return $error; 
                }
             }
     
 
     public function verListamos_usuario($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarmos_usuario($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblmos_usuario", "");
                $config_col=array(                    
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombres], "nombres", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apellido_paterno], "apellido_paterno", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apellido_materno], "apellido_materno", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[telefono], "telefono", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_creacion], "fecha_creacion", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_expi], "fecha_expi", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[super_usuario], "super_usuario", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email], "email", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[password_1], "password_1", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cedula], "cedula", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[recibe_notificaciones], "recibe_notificaciones", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Perfil Especialista", "perfil_especialista", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Perfil Portal", "perfil_portal", $parametros))                    
                );

                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array('funcion'=> 'columna_accion');
                $columna_funcion = 0;
                
                
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 13;
                }*/
                /*
                if ($_SESSION[CookM] == 'S')
                    //array_push($func,array('nombre'=> 'vermos_usuario','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-view-document\" title='Ver Usuario'></i>"));                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarmos_usuario','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\" title='Editar Usuario'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarmos_usuario','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-remove\" title='Eliminar Usuario'></i>"));
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'configurarmos_usuario','imagen'=> "<i style='cursor:pointer' class=\"icon icon-more\" title='Configurar Perfiles'></i>"));

                array_push($func,array('condicion'=> array('columna'=> 'perfil_portal', 'valor'=> " > 0"), 'parametros' => "$data[id_usuario]", 'nombre'=> 'perfil_portal','imagen'=> "<i style='cursor:pointer' class=\"icon-app-mode icon-app-mode-portal\" title='Administrar Perfiles Portal'></i>"));
                
                array_push($func,array('condicion'=> array('columna'=> 'perfil_especialista', 'valor'=> " > 0"), 'parametros' => "$data[id_usuario]", 'nombre'=> 'perfil_especialista','imagen'=> "<i style='cursor:pointer' class=\"icon-app-mode icon-app-mode-specialist\" title='Administrar Perfiles Especialista'></i>"));
*/
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                          //case 0:
                        //case 2:
//                        /case 3:
                        //case 4:
                            //array_push($config,$config_col[$i]);
                            //break;
                        default:
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;
                            break;
                    }
                }
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
                $grid->setParent($this);
///                $func= array('funcion'=> 'columna_accion');
                
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                //}
                return $out;
            }

            public function configurarPerfil($parametros){
                session_name("$GLOBALS[SESSION]");
                session_start();                
                import('clases.organizacion.ArbolOrganizacional');
                import("clases.perfiles.Perfiles");
                $perfil = new Perfiles();
                $ao = new ArbolOrganizacional();              
                $filial = $this->verfilial($parametros[if_filial]);                  
                $val = $this->vermos_usuario($filial[id_usuario]); 
                $val2 = $perfil->verPerfiles($filial[cod_perfil]);
                $val_aux = $this->verPerfilesEspecialista($filial[id_usuario], $filial[cod_perfil]);                
                $selec_nodo = array();
                foreach ($val_aux as $value) {
                    array_push($selec_nodo,$value[id_estructura]);
                }
                $parametros[nodos_seleccionados] = $selec_nodo;
                $parametros[opcion] = 'simple';                                                             
                $ut_tool = new ut_Tool();              
                $contenido_1   = array();                  
                $contenido_1['DIV_ARBOL_ORGANIZACIONAL'] =  $ao->jstree_ao(0,$parametros);                
                

                $contenido_1['ID_FILIAL'] = $parametros[if_filial];
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['NOMBRES'] = ($val[apellido_paterno].", ".$val[nombres]);
                $contenido_1['COD_PERFIL'] = $val2["cod_perfil"];
                $contenido_1['DESCRIPCION_PERFIL'] = $val2["descripcion_perfil"]; 
                $contenido_1['TITULO_FORMULARIO'] = 'Usuario [ '.$val[nombres] . ', ' .$val[apellido_paterno].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">Lista de Perfiles
                            <img class="SinBorde" src="diseno/images/flecha.gif">'.$val2["descripcion_perfil"];                
                //$contenido_1['TITULO_FORMULARIO'] = "Configurar&nbsp;Perfiles";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Usuarios";
                $contenido_1['PAGINA_VOLVER'] = "listarmos_usuario.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "conf_perfil_usuario";
                $contenido_1['ID'] = $val["id"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("configurar_perfil_usuario");
                $template->setVars($contenido_1);
                
                $contenido['CAMPOS'] = $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form-aux',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2Aux");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('ao_multiple();');
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  return $objResponse;                                
            }

            public function configurarPerfilPortal($parametros){                
                /*
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $filial = $this->verfilialPortal($parametros[if_filial]);
                
                $val = $this->vermos_usuario($filial[id_usuario]);                 

                import("clases.perfiles.Perfiles");
                $perfil = new Perfiles();
                $val2 = $perfil->verPerfilesPortal($filial[cod_perfil]);
                */
                session_name("$GLOBALS[SESSION]");
                session_start();                                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                import('clases.organizacion.ArbolOrganizacional');
                import("clases.perfiles.Perfiles");
                $perfil = new Perfiles();
                $ao = new ArbolOrganizacional();              				
                $ut_tool = new ut_Tool();
                $filial = $this->verfilialPortal($parametros[if_filial]);                
                $val = $this->vermos_usuario($filial[id_usuario]);                 
                $val2 = $perfil->verPerfilesPortal($filial[cod_perfil]);
                $val_aux = $this->verPerfilesPortal($filial[id_usuario], $filial[cod_perfil]);
                $selec_nodo = array();
                foreach ($val_aux as $value) {
                    array_push($selec_nodo,$value[id_estructura]);
                }
                $parametros[nodos_seleccionados] = $selec_nodo;
                $parametros[opcion] = 'simple';
                $contenido_1['DIV_ARBOL_ORGANIZACIONAL'] =  $ao->jstree_ao(0,$parametros);                
                
                
                $contenido_1['ID_FILIAL'] = $parametros[if_filial];
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['NOMBRES'] = ($val[apellido_paterno].", ".$val[nombres]);
                $contenido_1['COD_PERFIL'] = $val2["cod_perfil"];
                $contenido_1['DESCRIPCION_PERFIL'] = $val2["descripcion_perfil"]; 
                $contenido_1['TITULO_FORMULARIO'] = 'Usuario [ '.$val[nombres] . ', ' .$val[apellido_paterno].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">Lista de Perfiles Portal
                            <img class="SinBorde" src="diseno/images/flecha.gif">'.$val2["descripcion_perfil"];                
                //$contenido_1['TITULO_FORMULARIO'] = "Configurar&nbsp;Perfiles";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Usuarios";
                $contenido_1['PAGINA_VOLVER'] = "listarmos_usuario.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "conf_perfil_usuario";
                $contenido_1['ID'] = $val["id"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("configurar_perfil_usuario_portal");
                $template->setVars($contenido_1);               
                $contenido['CAMPOS'] = $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form-aux',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2Aux");
                $objResponse->addScript('ao_multiple();');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  return $objResponse;                
                
            }
            
            public function configurar($parametros){
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->vermos_usuario($parametros[id_usuario]); 

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
                
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['NOMBRES'] = ($val["nombres"].", ".$val["apellido_paterno"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("configurar");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Configurar&nbsp;Perfiles";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Usuarios";
                $contenido['PAGINA_VOLVER'] = "listarmos_usuario.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "conf";
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
 
        public function exportarExcel($parametros){
            $grid= new DataGrid();
            $this->listarmos_usuario($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblmos_usuario", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_usuario], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nombres], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[apellido_paterno], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[apellido_materno], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[telefono], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_creacion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_expi], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[super_usuario], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[password_1], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cedula], ENT_QUOTES, "UTF-8"))
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
//                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
//                            array_push($config,$config_col[$i]);
//                            break;
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
        
            public function listarPerfiles($atr, $pag, $registros_x_pagina){
                
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_usuario_filial AS muf INNER JOIN mos_perfil AS mp ON muf.cod_perfil = mp.cod_perfil 
                         WHERE muf.cod_perfil IS NOT NULL AND id_usuario ='$atr[id_usuario]' ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_perfil"])>0)
                        $sql .= " AND cod_perfil = '". $atr["b-cod_perfil"] . "'";
                        if (strlen($atr["b-descripcion_perfil"])>0)
                            $sql .= " AND upper(descripcion_perfil) like '%" . strtoupper($atr["b-descripcion_perfil"]) . "%'";
                        if (strlen($atr["b-orden"])>0)
                            $sql .= " AND orden = '". $atr["b-orden"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT muf.id, mp.cod_perfil,mp.descripcion_perfil
                                     $sql_col_left
                            FROM mos_usuario_filial AS muf INNER JOIN mos_perfil AS mp ON muf.cod_perfil = mp.cod_perfil $sql_left
                            WHERE muf.cod_perfil IS NOT NULL AND id_usuario ='$atr[id_usuario]' ";
                
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_perfil"])>0)
                        $sql .= " AND cod_perfil = '". $atr["b-cod_perfil"] . "'";
                    if (strlen($atr["b-descripcion_perfil"])>0)
                        $sql .= " AND upper(descripcion_perfil) like '%" . strtoupper($atr["b-descripcion_perfil"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                
                    $this->operacion($sql, $atr);
             }

            public function listarPerfilesPortal($atr, $pag, $registros_x_pagina){
                
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_usuario_filial AS muf INNER JOIN mos_perfil_portal AS mp ON muf.cod_perfil = mp.cod_perfil 
                         WHERE muf.cod_perfil IS NOT NULL AND id_usuario ='$atr[id_usuario]' ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_perfil"])>0)
                        $sql .= " AND cod_perfil = '". $atr["b-cod_perfil"] . "'";
                        if (strlen($atr["b-descripcion_perfil"])>0)
                            $sql .= " AND upper(descripcion_perfil) like '%" . strtoupper($atr["b-descripcion_perfil"]) . "%'";
                        if (strlen($atr["b-orden"])>0)
                            $sql .= " AND orden = '". $atr["b-orden"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT muf.id, mp.cod_perfil,mp.descripcion_perfil
                                     $sql_col_left
                            FROM mos_usuario_filial AS muf INNER JOIN mos_perfil_portal AS mp ON muf.cod_perfil_portal = mp.cod_perfil $sql_left
                            WHERE muf.cod_perfil_portal IS NOT NULL AND id_usuario ='$atr[id_usuario]' ";
                
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_perfil"])>0)
                        $sql .= " AND cod_perfil = '". $atr["b-cod_perfil"] . "'";
                    if (strlen($atr["b-descripcion_perfil"])>0)
                        $sql .= " AND upper(descripcion_perfil) like '%" . strtoupper($atr["b-descripcion_perfil"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                
                    $this->operacion($sql, $atr);
             }
             
            public function verListaPerfiles($parametros){
                
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 

                if($parametros[modo] == 'portal')
                        $this->listarPerfilesPortal($parametros, $parametros['pag'], $reg_por_pagina);
                else
                    $this->listarPerfiles($parametros, $parametros['pag'], $reg_por_pagina);
                
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas_perfil();
                }
                $grid->SetConfiguracionMSKS("tblPerfiles", "");
                $config_col=array(
                    
               array( "width"=>"0%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_perfil], "cod_perfil", $parametros)),
               array( "width"=>"60%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion_perfil], ENT_QUOTES, "UTF-8")),
                    
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

                $columna_funcion = 0;
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                
                if($parametros[modo] == 'portal')
                    array_push($func,array('condicion'=> array('columna'=> 'cod_perfil', 'valor'=> " > 0"), 'parametros' => "$data[id]", 'nombre'=> 'configurarPerfilesPortal','imagen'=> "<i style='cursor:pointer' class=\"icon icon-more\" title='Administrar Perfiles Usuario'></i>"));
                else
                    array_push($func,array('condicion'=> array('columna'=> 'cod_perfil', 'valor'=> " > 0"), 'parametros' => "$data[id]", 'nombre'=> 'configurarPerfiles','imagen'=> "<i style='cursor:pointer' class=\"icon icon-more\" title='Administrar Perfiles Usuario'></i>"));                    
                
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        default:                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;                            
                            break;
                    }
                }
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                $grid->hidden = array(0 => true,1 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                //}
                return $out;
            } 
            public function perfil_especialista($parametros){                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_perfil";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1"; 
                $parametros['modo'] = 'especialista';
                session_name("$GLOBALS[SESSION]");
                session_start();                                                                
                $_SESSION[IDDoc] = $parametros[id];                

                $grid = $this->verListaPerfiles($parametros); 
                $val = $this->vermos_usuario($parametros[id_usuario]);                
                $contenido[TITULO_MODULO] = 'Usuario [ '.$val[nombres] . ', ' .$val[apellido_paterno].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">
                            Lista de Perfiles Especialista';
                $contenido[TITULO] = $parametros[titulo];
                $_SESSION[Codigo_doc] = $val[Codigo_doc];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                //$contenido['JS_NUEVO'] = 'nuevo_Registros();';
                //$contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Registros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $template = new Template();

                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);


                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("listar_volver");
                
                $template->setVars($contenido);
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('desc-mod-act',"innerHTML","Registros - Documento [$val[Codigo_doc] - $val[nombre_doc]]");
                $objResponse->addAssign('contenido-aux',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);                
                $objResponse->addAssign('modulo_actual',"value","mos_usuario");
                $objResponse->addIncludeScript(PATH_TO_JS . 'mos_usuario/mos_usuario.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript($js);
                /*Js init_tabla*/
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                    event.preventDefault();
                                    var id = $(this).attr('tok');
                                    $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                    $('#myModal-Ventana-Titulo').html('');
                                    $('#myModal-Ventana').modal('show');
                                });");                
                //$objResponse->addScript('r_init_filtrar();');
                $objResponse->addScript('setTimeout(function(){ r_init_filtrar(); }, 500);');
                //$objResponse->addScriptCall("MostrarContenidoAux"); 
                return $objResponse;
                
            }
            
             public function perfil_portal($parametros){                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_perfil";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1"; 

                $parametros['modo'] = 'portal';
                session_name("$GLOBALS[SESSION]");
                session_start();                                                                
                $_SESSION[IDDoc] = $parametros[id];                

                $grid = $this->verListaPerfiles($parametros); 
                $val = $this->vermos_usuario($parametros[id_usuario]);                
                $contenido[TITULO_MODULO] = 'Usuario [ '.$val[nombres] . ', ' .$val[apellido_paterno].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">
                            Lista de Perfiles Portal';
                $contenido[TITULO] = $parametros[titulo];
                $_SESSION[Codigo_doc] = $val[Codigo_doc];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                //$contenido['JS_NUEVO'] = 'nuevo_Registros();';
                //$contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Registros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $template = new Template();

                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);


                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("listar_volver");
                
                $template->setVars($contenido);
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('desc-mod-act',"innerHTML","Registros - Documento [$val[Codigo_doc] - $val[nombre_doc]]");
                $objResponse->addAssign('contenido-aux',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);                
                $objResponse->addAssign('modulo_actual',"value","mos_usuario");
                $objResponse->addIncludeScript(PATH_TO_JS . 'mos_usuario/mos_usuario.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript($js);
                /*Js init_tabla*/
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                    event.preventDefault();
                                    var id = $(this).attr('tok');
                                    $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                    $('#myModal-Ventana-Titulo').html('');
                                    $('#myModal-Ventana').modal('show');
                                });");                
                //$objResponse->addScript('r_init_filtrar();');
                $objResponse->addScript('setTimeout(function(){ r_init_filtrar(); }, 500);');
                //$objResponse->addScriptCall("MostrarContenidoAux"); 
                return $objResponse;
                
            }
            
            public function indexmos_usuario($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if ($parametros['corder'] == null) $parametros['corder']="id_usuario";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-8-9-11"; 

                $k = 21;
                
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
                $grid = $this->verListamos_usuario($parametros);
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];  
                
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_mos_usuario();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;mos_usuario';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
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
                
                $ut_tool = new ut_Tool();
                $contenido['PERFIL_ESPECIALISTA'] = $ut_tool->OptionsCombo("SELECT cod_perfil, descripcion_perfil FROM mos_perfil ORDER BY descripcion_perfil"
                                                                    , 'cod_perfil', 'descripcion_perfil', $val['cod_perfil']); 
                
                $contenido['PERFIL_PORTAL'] = $ut_tool->OptionsCombo("SELECT cod_perfil, descripcion_perfil FROM mos_perfil_portal ORDER BY descripcion_perfil"
                                                                    , 'cod_perfil', 'descripcion_perfil', $val['cod_perfil']);                   
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';

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
                $objResponse->addAssign('modulo_actual',"value","mos_usuario");
                $objResponse->addIncludeScript(PATH_TO_JS . 'mos_usuario/mos_usuario.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                $objResponse->addScript('$( "#b-perfil_especialista" ).select2({
                                            placeholder: "Selecione el perfil especialista",
                                            allowClear: true
                                        }); 
                                        $( "#b-perfil_portal" ).select2({
                                            placeholder: "Selecione el perfil portal",
                                            allowClear: true
                                        }); 
                                        $("#b-fecha_expi-desde").datepicker();        
                                        $("#b-fecha_expi-hasta").datepicker();');                
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $val = $this->vermos_usuario($_SESSION[CookIdUsuario]);
                //print_r($val);
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
                $contenido_1['READ_SUPER_USUARIO'] = $val["super_usuario"] == 'S' ? '' : 'disabled="disabled"';
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;Usuario";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Usuario";
                $contenido['PAGINA_VOLVER'] = "listarmos_usuario.php";
                $contenido['DESC_OPERACION'] = "Guardar";                
                
                //print_r($contenido);
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
                $objResponse->addScript("$('#fecha_creacion').datepicker();");
            $objResponse->addScript("$('#fecha_expi').datepicker({changeMonth: true,yearRange: '-100:+0',changeYear: true});");
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
                $validator->addValidation("email","email","El email especificado es invalido");
                $validator->addValidation("cedula","num","La cedula especificada es un numero invalido.");
                $validator->addValidation("telefono","num","El telefono especificado es un numero invalido.");
                
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $parametros["fecha_creacion"] = formatear_fecha($parametros["fecha_creacion"]);
                    $parametros["fecha_expi"] = formatear_fecha($parametros["fecha_expi"]);

                    $respuesta = $this->ingresarmos_usuario($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        //$objResponse->addScript("verPagina(1,1);");
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                       // );
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
             
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->vermos_usuario($parametros[id]); 
             
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
             
                            $contenido_1['ID_USUARIO'] = $val["id_usuario"];
            $contenido_1['NOMBRES'] = ($val["nombres"]);
            $contenido_1['APELLIDO_PATERNO'] = ($val["apellido_paterno"]);
            $contenido_1['APELLIDO_MATERNO'] = ($val["apellido_materno"]);
            $contenido_1['TELEFONO'] = ($val["telefono"]);
            $contenido_1['FECHA_CREACION'] = ($val["fecha_creacion"]);
            $contenido_1['FECHA_EXPI'] = ($val["fecha_expi"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['SUPER_USUARIO'] = ($val["super_usuario"]);
            $contenido_1['EMAIL'] = ($val["email"]);
            $contenido_1['PASSWORD_1'] = ($val["password_1"]);
            $contenido_1['CEDULA'] = ($val["cedula"]);

            $contenido_1['SUPER_USUARIO'] = $val["super_usuario"] == 'S' ? 'checked="checked"' : '';
            $contenido_1['CHECKED_VIGENCIA'] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
            $contenido_1['RECIBE_NOTIFICACIONES'] = $val["recibe_notificaciones"] == 'S' ? 'checked="checked"' : '';
            $val_aux = $this->vermos_usuario($_SESSION[CookIdUsuario]);
            $contenido_1['READ_SUPER_USUARIO'] = $val_aux["super_usuario"] == 'S' ? '' : 'disabled="disabled"';
            //print_r($contenido_1);
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;mos_usuario";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;mos_usuario";
                $contenido['PAGINA_VOLVER'] = "listarmos_usuario.php";
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
                          });");$objResponse->addScript("$('#fecha_creacion').datepicker();");
$objResponse->addScript("$('#fecha_expi').datepicker();");
  return $objResponse;
            }
     
 
            public function actualizar($parametros)
            {
             
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);         

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{                    
                    $parametros["fecha_creacion"] = formatear_fecha($parametros["fecha_creacion"]);
                    $parametros["fecha_expi"] = formatear_fecha($parametros["fecha_expi"]);

                    $respuesta = $this->modificarmos_usuario($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScript("verPagina(1,1);");
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
                $val = $this->vermos_usuario($parametros[id]);                 
                $respuesta = $this->eliminarmos_usuario($parametros);
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
                $this->cargar_permisos($parametros);    
                $grid = $this->verListamos_usuario($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros){                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->vermos_usuario($parametros[id_usuario]);
                
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
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['NOMBRES'] = ($val["nombres"]);
                $contenido_1['APELLIDO_PATERNO'] = ($val["apellido_paterno"]);
                $contenido_1['APELLIDO_MATERNO'] = ($val["apellido_materno"]);
                $contenido_1['TELEFONO'] = ($val["telefono"]);
                $contenido_1['FECHA_CREACION'] = ($val["fecha_creacion"]);
                $contenido_1['FECHA_EXPI'] = ($val["fecha_expi"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1['SUPER_USUARIO'] = ($val["super_usuario"]);
                $contenido_1['EMAIL'] = ($val["email"]);
                $contenido_1['PASSWORD_1'] = ($val["password_1"]);
                $contenido_1['CEDULA'] = ($val["cedula"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("vermos_usuario");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de Usuario";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");
                $contenido['PAGINA_VOLVER'] = "listarmos_usuario.php";
//                $template->setVars($contenido);
//                $this->contenido['CONTENIDO']  = $template->show();
//                $this->asigna_contenido($this->contenido);
//
//                return $template->show();
                
                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");$objResponse->addScript("$('#fecha_creacion').datepicker();");
                $objResponse->addScript("$('#fecha_expi').datepicker();");
                return $objResponse;               
    }
            
            
    public function MuestraPerfiles(){
        $sql="SELECT * FROM mos_perfil ORDER BY descripcion_perfil";
        $data = $this->dbl->query($sql, $atr);
        $cabecera_padre = "<ul>";
        $padre_final = "";
        foreach ($data as $arrP) {
            $cuerpo .= "<li id=\"phtml_".$arrP[cod_perfil]."\"><a href=\"#\">".($arrP[descripcion_perfil])."</a></li>";
        }
        $pie_padre = "</ul>";
        return $cabecera_padre.$cuerpo.$pie_padre;
    }            

    public function verPerfilesUsuario($id){                
        $atr=array();
        $sql = "SELECT cod_perfil FROM mos_usuario_filial WHERE cod_perfil IS NOT NULL AND id_usuario = $id ";        
        $this->operacion($sql, $atr);
        return $this->dbl->data;
    }    

    public function verPerfilesEspecialista($id_usuario, $cod_perfil){
        $atr=array();
        $sql = "SELECT id_estructura FROM mos_usuario_estructura WHERE id_usuario = $id_usuario AND cod_perfil =  $cod_perfil AND portal = 'N'";        
        $this->operacion($sql, $atr);
        return $this->dbl->data;        
    }
    
    public function verPerfilesPortal($id_usuario, $cod_perfil){
        $atr=array();
        $sql = "SELECT id_estructura FROM mos_usuario_estructura WHERE id_usuario = $id_usuario AND cod_perfil =  $cod_perfil AND portal = 'S'";        
        $this->operacion($sql, $atr);
        return $this->dbl->data;        
    }
    
    public function MuestraPerfilesPortal(){
        $sql="SELECT * FROM mos_perfil_portal ORDER BY descripcion_perfil";
        $data = $this->dbl->query($sql, $atr);
        $cabecera_padre = "<ul>";
        $padre_final = "";
        foreach ($data as $arrP) {
            $cuerpo .= "<li id=\"phtml_".$arrP[cod_perfil]."\"><a href=\"#\">".($arrP[descripcion_perfil])."</a></li>";
        }
        $pie_padre = "</ul>";
        return $cabecera_padre.$cuerpo.$pie_padre;
    }    
    
    public function verPerfilesPortalUsuario($id){                
        $atr=array();
        $sql = "SELECT cod_perfil_portal FROM mos_usuario_filial WHERE cod_perfil_portal IS NOT NULL AND id_usuario = $id ";
        
        $this->operacion($sql, $atr);
        return $this->dbl->data;
    }    

    public function eliminarHuerfanoEstrustura(){
            $atr=array();
            $sql = "SELECT DISTINCT mue.id_usuario_filial FROM mos_usuario_estructura AS mue
                RIGHT JOIN mos_usuario_filial AS muf ON mue.id_usuario = muf.id_usuario AND (mue.cod_perfil = muf.cod_perfil OR mue.cod_perfil = muf.cod_perfil_portal)
                WHERE mue.id_usuario IS NOT NULL
                ";  
            $this->operacion($sql, $atr);             
            foreach ($this->dbl->data as $arrP) {
                $idnh .= $arrP[id_usuario_filial].",";
            }
            $idnh = substr($idnh, 0, -1);            
            $sql = "DELETE FROM mos_usuario_estructura WHERE NOT id_usuario_filial IN ($idnh)";  
            $this->operacion($sql, $atr);             
    }
    
    public function eliminarPerfilesUsuario($atr){
           try {                   
               $this->dbl->delete("mos_usuario_filial", "id_usuario = $atr[id_usuario]");               
               return "ha sido eliminada con exito";
           } catch(Exception $e) {
               $error = $e->getMessage();                     
               if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                   return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
               return $error; 
           }
    }  

    public function eliminarPerfilesEstructura($atr){
           try {   
        
               $respuesta = $this->dbl->delete("mos_usuario_estructura", "id_usuario_filial = $atr[id_filial]  AND portal = 'N'");
               return "ha sido eliminada con exito";
           } catch(Exception $e) {
               $error = $e->getMessage();                     
               if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                   return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
               return $error; 
           }
    } 

        public function eliminarPerfilesEstructuraPortal($atr){
           try {   
               
               $respuesta = $this->dbl->delete("mos_usuario_estructura", "id_usuario_filial = $atr[id_filial]  AND portal = 'S'");
               return "ha sido eliminada con exito";
           } catch(Exception $e) {
               $error = $e->getMessage();                     
               if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                   return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
               return $error; 
           }
    } 

    
    
    public function ingresarPerfilesEstructura($atr){
        try {  
            
            $atr = $this->dbl->corregir_parametros($atr);
            $sql = "INSERT INTO mos_usuario_estructura(id_usuario_filial,id_estructura,id_usuario, cod_perfil, portal)
                    VALUES($atr[id_filial],$atr[id_estructura],$atr[id_usuario],$atr[cod_perfil], 'N')";
            $this->dbl->insert_update($sql);
            return "Los Perfiles han sido ingresado con exito";
        } catch(Exception $e) {
                $error = $e->getMessage();                     
                if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                    return "Ya existe una sección con el mismo nombre.";                        
                return $error; 
            }        
    }
    
    public function ingresarPerfilesEstructuraPortal($atr){
        try {  
            
            $atr = $this->dbl->corregir_parametros($atr);
            $sql = "INSERT INTO mos_usuario_estructura(id_usuario_filial,id_estructura,id_usuario, cod_perfil, portal)
                    VALUES($atr[id_filial],$atr[id_estructura],$atr[id_usuario],$atr[cod_perfil], 'S')";
            $this->dbl->insert_update($sql);
            return "Los Perfiles han sido ingresado con exito";
        } catch(Exception $e) {
                $error = $e->getMessage();                     
                if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                    return "Ya existe una sección con el mismo nombre.";                        
                return $error; 
            }        
    }
    
    public function ingresarPerfilesUsuario($atr){
        try {                
            $atr = $this->dbl->corregir_parametros($atr);
            $sql = "INSERT INTO mos_usuario_filial(id_usuario,id_filial,cod_perfil,ultimo_acceso)
                    VALUES(
                        $atr[id_usuario],$atr[id_filial],$atr[cod_perfil],1
                        )";
            $this->dbl->insert_update($sql);
            return "Los Perfiles han sido ingresado con exito";
        } catch(Exception $e) {
                $error = $e->getMessage();                     
                if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                    return "Ya existe una sección con el mismo nombre.";                        
                return $error; 
            }
    }

    public function ingresarPerfilesPortalUsuario($atr){
        try {                
            $atr = $this->dbl->corregir_parametros($atr);
            $sql = "INSERT INTO mos_usuario_filial(id_usuario,id_filial,cod_perfil_portal,ultimo_acceso)
                    VALUES(
                        $atr[id_usuario],$atr[id_filial],$atr[cod_perfil],1
                        )";
            $this->dbl->insert_update($sql);
            return "Los Perfiles han sido ingresado con exito";
        } catch(Exception $e) {
                $error = $e->getMessage();                     
                if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                    return "Ya existe una sección con el mismo nombre.";                        
                return $error; 
            }
    }    


   public function obtenerPerfilesActuales($parametros){
        $atr=array();
        $sql = "SELECT * FROM mos_usuario_filial WHERE id_usuario = $parametros[id_usuario]";
        $this->operacion($sql, $atr);          
        return $this->dbl->data;
          
   }

    public function administrarPerfilesNuevos($parametros, $perfiles_actuales){
        $atr=array();
        import("clases.organizacion.ArbolOrganizacional");       
        $organizacion = new ArbolOrganizacional;        
        //$sql = "SELECT id_organizacion FROM mos_personal WHERE (SELECT SUBSTRING_INDEX(email, '@', -1)) = (SELECT SUBSTRING_INDEX(email, '@', -1) FROM mos_usuario WHERE id_usuario = $parametros[id_usuario])";        
        $sql = "SELECT id_organizacion FROM mos_personal p INNER JOIN mos_usuario u ON u.email = p.email WHERE id_usuario = $parametros[id_usuario]";
        $this->operacion($sql, $atr);        
        
        $nodos = explode(",",$organizacion->BuscaOrgNivelHijos($this->dbl->data[0]["id_organizacion"]));        
        $nodos_esp = explode(",", $parametros[nodos]);
        $nodos_portal = explode(",", $parametros[nodosportal]);

        foreach($perfiles_actuales as $act){
            for($i=0;$i<count($nodos_esp);$i++){
                if($act[cod_perfil] == $nodos_esp[$i]){                    
                    unset($nodos_esp[$i]);
                    $nodos_esp = array_values($nodos_esp);
                    break;
                }
            }
            for($i=0;$i<count($nodos_portal);$i++){
                if($act[cod_perfil_portal] == $nodos_portal[$i]){                    
                    unset($nodos_portal[$i]);
                    $nodos_portal = array_values($nodos_portal);
                    break;
                }
            }          
        }
        $params[id_usuario] = $parametros[id_usuario];
        foreach($nodos_esp as $temp){            
             $params[cod_perfil] = $temp;
             $sql ="SELECT * FROM mos_usuario_filial WHERE id_usuario = $params[id_usuario] AND cod_perfil = $params[cod_perfil];";
             $this->operacion($sql, $atr);
            $params[id_filial] = $this->dbl->data[0][id];           
             foreach($nodos as $est){
                $params[id_estructura] = $est; 
                //print_r($params);
                $this->ingresarPerfilesEstructura($params);
             }
        }     
        //echo("\n");
        foreach($nodos_portal as $temp){            
             $params[cod_perfil] = $temp;
             $sql ="SELECT * FROM mos_usuario_filial WHERE id_usuario = $params[id_usuario] AND cod_perfil_portal = $params[cod_perfil];";
             $this->operacion($sql, $atr);
            $params[id_filial] = $this->dbl->data[0][id];           
             foreach($nodos as $est){
                $params[id_estructura] = $est; 
                //print_r($params);
                $this->ingresarPerfilesEstructuraPortal($params);
             }
        }                        
   }
    
   public function cargarConfiguracion($parametros)
   {       
       session_name("$GLOBALS[SESSION]");
       session_start();
       $objResponse = new xajaxResponse();
       unset ($parametros['opc']);
            
       $validator = new FormValidator();                
       if(!$validator->ValidateForm($parametros)){
               $error_hash = $validator->GetErrors();
               $mensaje="";
               foreach($error_hash as $inpname => $inp_err){
                       $mensaje.="- $inp_err <br/>";
               }
                $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
       }else{
            $arr = explode(",", $parametros[nodos]);
            $arrportal = explode(",", $parametros[nodosportal]);
            
            $perfiles_actuales = $this->obtenerPerfilesActuales($parametros);
            
            $params[id_usuario] = $parametros[id_usuario];
            $params[id_filial] = $_SESSION[CookFilial]; 
             
            $this->eliminarPerfilesUsuario($parametros);
                        
            foreach($arr as $temp){
                 $params[cod_perfil] = $temp;
                 if($params[cod_perfil]!="")
                 $respuesta = $this->ingresarPerfilesUsuario($params);
            }
            
            foreach($arrportal as $temp){
                 $params[cod_perfil] = $temp;
                 if($params[cod_perfil]!="")
                 $respuesta = $this->ingresarPerfilesPortalUsuario($params);
            }
            $this->eliminarHuerfanoEstrustura();
            $this->administrarPerfilesNuevos($parametros,$perfiles_actuales);
            
            
            $objResponse->addScriptCall("MostrarContenido");
            
            $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
            
       }
       
       $objResponse->addScript("$('#MustraCargando').hide();"); 
       $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                               $( '#btn-guardar' ).prop('disabled', false );");
       
       return $objResponse;
   }  
   
   
   public function cargarConfiguracionPerfiles($parametros){          
       session_name("$GLOBALS[SESSION]");
       session_start();
       $objResponse = new xajaxResponse();
       unset ($parametros['opc']);
            
       $validator = new FormValidator();                
       if(!$validator->ValidateForm($parametros)){
               $error_hash = $validator->GetErrors();
               $mensaje="";
               foreach($error_hash as $inpname => $inp_err){
                       $mensaje.="- $inp_err <br/>";
               }
                $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
       }else{
          
            $arr = explode(",", $parametros[nodos]);           
            
            $params[id_usuario] = $parametros[id_usuario];
            $params[id_filial] = $parametros[id_filial]; 
            $params[cod_perfil] = $parametros[cod_perfil];
            $this->eliminarPerfilesEstructura($parametros);
                        
            foreach($arr as $temp){
                 $params[id_estructura] = $temp;
                 $nodos_sel .= $temp. ",";
                 $respuesta = $this->ingresarPerfilesEstructura($params);
            }      
            // al momento de enviar la asignacion de los nodos seleccionadas
            // se guardan los id de esos nodo y luego se realiza la consulta para 
            // buscar cual de ellos posee nodos espejo, luego si alguno posee nodos espejo
            // se toman los nodos y sus hijos y se hace la asignacion de esos nodos 
            // con los mismos parametros con los que se asignan los nodos iniciales 
                        
            $nodos_sel = substr ($nodos_sel, 0, strlen($nodos_sel) - 1);
            $sql = "select * from mos_organizacion where area_espejo > 0 and   id in (".$nodos_sel.")";  
            $this->operacion($sql, "");
            $nodos_espejos = $this->dbl->data;
            import("clases.organizacion.ArbolOrganizacional");       
            $organizacion = new ArbolOrganizacional; 
            $nodos = array();            
            foreach($nodos_espejos as $temp){
                $aux = explode(",",$organizacion->BuscaOrgNivelHijos($temp[area_espejo]));
                foreach($aux as $temp2){
                    array_push($nodos,$temp2);
                }                                
            }            
           foreach($nodos as $temp){
                 $params[id_estructura] = $temp;     
                 $respuesta = $this->ingresarPerfilesEstructura($params);
            }   
            
            $objResponse->addScriptCall("MostrarContenidoAux");
            $objResponse->addScriptCall('VerMensaje','exito',"Perfil actualizado con exito");
            
       }
       $objResponse->addScript("$('#MustraCargando').hide();"); 
       $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                               $( '#btn-guardar' ).prop( 'disabled', false );");
       return $objResponse;
   }    
   
  public function cargarConfiguracionPerfilesPortal($parametros)
   {          
       session_name("$GLOBALS[SESSION]");
       session_start();
       $objResponse = new xajaxResponse();
       unset ($parametros['opc']);
            
       $validator = new FormValidator();                
       if(!$validator->ValidateForm($parametros)){
               $error_hash = $validator->GetErrors();
               $mensaje="";
               foreach($error_hash as $inpname => $inp_err){
                       $mensaje.="- $inp_err <br/>";
               }
                $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
       }else{
          
            $arr = explode(",", $parametros[nodos]);
            $arrportal = explode(",", $parametros[nodosportal]);
            
            $params[id_usuario] = $parametros[id_usuario];
            $params[id_filial] = $parametros[id_filial]; 
            $params[cod_perfil] = $parametros[cod_perfil];
            $this->eliminarPerfilesEstructuraPortal($parametros);
            foreach($arr as $temp){
                 $params[id_estructura] = $temp;
                 $respuesta = $this->ingresarPerfilesEstructuraPortal($params);
            }           
//            $objResponse->addScriptCall("MostrarContenido");
//            $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
            $objResponse->addScriptCall("MostrarContenidoAux");
            $objResponse->addScriptCall('VerMensaje','exito',"Perfil actualizado con exito");
            
       }
       $objResponse->addScript("$('#MustraCargando').hide();"); 
       $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                               $( '#btn-guardar' ).prop( 'disabled', false );");
       return $objResponse;
   }    
    
 }?>