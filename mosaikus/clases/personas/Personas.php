<?php                        
?>
<?php
 import("clases.interfaz.Pagina");        
        class Personas extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        public $parametros;
        public $nombres_columnas;
        private $placeholder;
        public $campos_activos;
        private $id_org_acceso;

            public function Personas(){
                parent::__construct();
                $this->asigna_script('personas/personas.js');                                                
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = $this->campos_activos = array();
                $this->contenido = $this->id_org_acceso = array();
            }
            
            /**
             * Activa los nodos donde se tiene explicitamente acceso
             */
            private function cargar_acceso_nodos($parametros){
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();
                    $data_ids_acceso = $acceso->obtenerNodosArbol($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);
                    foreach ($data_ids_acceso as $value) {
                        $this->id_org_acceso[$value[id]] = $value;
                    }                                            
                }
            }
            
            /**
             * Devuelve si el usuario tiene permiso de crear personas
             * @param array $parametros 
             * @return string
             */
            private function permiso_crear($parametros){
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($parametros);
                }                
                foreach ($this->id_org_acceso as $value) {
                    if ($value[nuevo] == 'S'){
                        return 'S';
                    }
                }                
                return 'N';
            }
                    

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            public function cargar_parametros(){
                $sql = "SELECT cod_parametro, espanol, tipo FROM mos_parametro WHERE cod_categoria = '3' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            public function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 1";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            public function cargar_campos_activos(){
                $sql = "SELECT campo, activo, orden FROM mos_campos_activos WHERE modulo = 1";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->campos_activos[$value[campo]] = array($value[activo],$value[orden]);
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 1";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }
     

             public function verPersonas($id){
                $atr=array();
                $sql = "SELECT cod_emp
                            ,id_personal
                            ,nombres
                            ,apellido_paterno
                            ,apellido_materno
                            ,genero
                            ,DATE_FORMAT(fecha_nacimiento, '%d/%m/%Y') fecha_nacimiento
                            ,p.vigencia
                            ,p.interno
                            ,id_filial
                            ,id_organizacion
                            ,p.cod_cargo
                            ,c.descripcion cargo
                            ,workflow
                            ,email
                            ,relator
                            ,reviso
                            ,elaboro
                            ,aprobo
                            ,extranjero
                            ,DATE_FORMAT(fecha_ingreso, '%d/%m/%Y') fecha_ingreso
                            ,DATE_FORMAT(fecha_egreso, '%d/%m/%Y') fecha_egreso
                         FROM mos_personal p
                        LEFT JOIN mos_cargo c ON c.cod_cargo = p.cod_cargo
                         WHERE cod_emp = $id "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_emp) total_registros
                         FROM mos_personal";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarPersonas($atr){
                try {
                    
                    $atr[id_filial] = $_SESSION[CookFilial];
                    $atr[cod_emp] = $this->codigo_siguiente();
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_personal 
                                        WHERE id_personal = '$atr[id_personal]' AND extranjero = 'NO'";                    
                    $total_registros = $this->dbl->query($sql, $atr);
                    $total = $total_registros[0][total_registros];  
                    if ($total > 0){
                        return "- Ya existe una persona registrada con la misma cedula";
                    }
                    /*Carga Acceso segun el arbol*/
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);
                    }                    
                    /*Valida Restriccion*/
                    if (!isset($this->id_org_acceso[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->id_org_acceso[$atr[id_organizacion]][nuevo]== 'S') || ($this->id_org_acceso[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->id_org_acceso[$atr[id_organizacion]][title] . '.';
                    
                    if (strlen($atr[fecha_ingreso])== 10){
                        $atr[fecha_ingreso] = "'$atr[fecha_ingreso]'";                        
                    }
                    else $atr[fecha_ingreso] = "NULL";
                    if (strlen($atr[fecha_nacimiento])== 10){
                        $atr[fecha_nacimiento] = "'$atr[fecha_nacimiento]'";                        
                    }
                    else $atr[fecha_nacimiento] = "NULL";
                    if (strlen($atr[fecha_egreso])== 10){
                        $atr[fecha_egreso] = "'$atr[fecha_egreso]'";                        
                    }
                    else $atr[fecha_egreso] = "NULL";
                    
                    $sql = "INSERT INTO mos_personal(cod_emp,id_personal,nombres,apellido_paterno,apellido_materno,genero,fecha_nacimiento,vigencia,interno,id_filial,id_organizacion,cod_cargo,workflow,email,relator,reviso,elaboro,aprobo,extranjero, fecha_ingreso, fecha_egreso)
                            VALUES(
                                $atr[cod_emp],'$atr[id_personal]','$atr[nombres]','$atr[apellido_paterno]','$atr[apellido_materno]','$atr[genero]',$atr[fecha_nacimiento],'$atr[vigencia]','$atr[interno]',$atr[id_filial],$atr[id_organizacion],$atr[cod_cargo],'$atr[workflow]','$atr[email]','$atr[relator]','$atr[reviso]','$atr[elaboro]','$atr[aprobo]','$atr[extranjero]'
                                    ,$atr[fecha_ingreso],$atr[fecha_egreso]
                                )";
                    $this->dbl->insert_update($sql);
                    
                    $this->registraTransaccionLog(18,$atr[cod_emp]. " ".trim($atr[apellido_paterno])." ".trim($atr[apellido_materno])." ".trim($atr[nombres]),'', '');
                    return $atr[cod_emp];
                    return "El mos_personal '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = ''){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]')";            
                //echo $sql;
                $this->dbl->insert_update($sql);

                return true;
        }
            
            public function eliminarParametros($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_parametro_modulos", "cod_categoria = 3 AND id_registro = $atr[id_registro]");
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
             
            public function ingresarParametro($atr){
                try {                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    if ($atr[cod_parametro_det] == '') 
                        $atr[cod_parametro_det] = 0;
                    $sql = "INSERT INTO mos_parametro_modulos(cod_categoria,cod_parametro,cod_parametro_det,id_registro,cod_categoria_aux)
                            VALUES(
                                3,$atr[cod_parametro],$atr[cod_parametro_det],$atr[id_registro],3
                                )";
                    $this->dbl->insert_update($sql);
                    return "El mos_personal '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            public function modificarPersonas($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_personal 
                                        WHERE cod_emp <> $atr[id] AND id_personal = '$atr[id_personal]' AND extranjero = 'NO'";                    
                    $total_registros = $this->dbl->query($sql, $atr);
                    $total = $total_registros[0][total_registros];  
                    if ($total > 0){
                        return "- Ya existe una persona registrada con la misma cedula";
                    }
                    /*Carga Acceso segun el arbol*/
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);
                    }                    
                    /*Valida Restriccion*/
                    if (!isset($this->id_org_acceso[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->id_org_acceso[$atr[id_organizacion]][nuevo]== 'S') || ($this->id_org_acceso[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->id_org_acceso[$atr[id_organizacion]][title] . '.';

                    if (strlen($atr[fecha_ingreso])== 10){
                        $atr[fecha_ingreso] = "'$atr[fecha_ingreso]'";                        
                    }
                    else $atr[fecha_ingreso] = "NULL";
                    if (strlen($atr[fecha_egreso])== 10){
                        $atr[fecha_egreso] = "'$atr[fecha_egreso]'";                        
                    }
                    else $atr[fecha_egreso] = "NULL";
                    if (strlen($atr[fecha_nacimiento])== 10){
                        $atr[fecha_nacimiento] = "'$atr[fecha_nacimiento]'";                        
                    }
                    else $atr[fecha_nacimiento] = "NULL";
                    $sql = "UPDATE mos_personal SET                            
                                    id_personal = '$atr[id_personal]',nombres = '$atr[nombres]',apellido_paterno = '$atr[apellido_paterno]',apellido_materno = '$atr[apellido_materno]',genero = '$atr[genero]',fecha_nacimiento = $atr[fecha_nacimiento],vigencia = '$atr[vigencia]',interno = '$atr[interno]',id_organizacion = $atr[id_organizacion],cod_cargo = $atr[cod_cargo],workflow = '$atr[workflow]',email = '$atr[email]',relator = '$atr[relator]',reviso = '$atr[reviso]',elaboro = '$atr[elaboro]',aprobo = '$atr[aprobo]',extranjero = '$atr[extranjero]'
                                        ,fecha_ingreso=$atr[fecha_ingreso], fecha_egreso=$atr[fecha_egreso]
                            WHERE  cod_emp = $atr[id]";    
                    $val = $this->verPersonas($atr[id]);
                    $this->dbl->insert_update($sql);
                    if ($atr[fecha_ingreso] != 'NULL'){
                        $atr[fecha_ingreso] = "\\" . substr($atr[fecha_ingreso], 0, strlen($atr[fecha_ingreso])-1) . "\\";
                    }
                    if ($atr[fecha_egreso] != 'NULL'){
                        $atr[fecha_egreso] = "\\" . substr($atr[fecha_egreso], 0, strlen($atr[fecha_egreso])-1)  . "\'";
                    }
                    if ($atr[fecha_nacimiento] != 'NULL'){
                        $atr[fecha_nacimiento] = "\\" . substr($atr[fecha_nacimiento], 0, strlen($atr[fecha_nacimiento])-1)  . "\'";
                    }
                    $nuevo = "Rut: \'$atr[id_personal]\',Nombres: \'$atr[nombres]\', Apellido Paterno: \'$atr[apellido_paterno]\', Apellido Materno: \'$atr[apellido_materno]\', Genero: \'$atr[genero]\', Fecha Nacimiento: $atr[fecha_nacimiento], Vigencia: \'$atr[vigencia]\', Interno: \'$atr[interno]\', Id Organizacion: $atr[id_organizacion], Cargo: $atr[cod_cargo], Workflow: \'$atr[workflow]\', Email: \'$atr[email]\', Relator: \'$atr[relator]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', Extranjero: \'$atr[extranjero]\'";
                    $anterior = "Rut: \'$val[id_personal]\',Nombres: \'$val[nombres]\', Apellido Paterno: \'$val[apellido_paterno]\', Apellido Materno: \'$val[apellido_materno]\', Genero: \'$val[genero]\', Fecha Nacimiento: \'$val[fecha_nacimiento]\', Vigencia: \'$val[vigencia]\', Interno: \'$val[interno]\', Id Organizacion: $val[id_organizacion], Cargo: $val[cod_cargo], Workflow: \'$val[workflow]\', Email: \'$val[email]\', Relator: \'$val[relator]\', Reviso: \'$val[reviso]\', Elaboro: \'$val[elaboro]\', Aprobo: \'$val[aprobo]\', Extranjero: \'$val[extranjero]\'";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    //$this->registraTransaccion('Modificar','Modifico el Personas ' . $atr[descripcion_ano], 'mos_personal');
                    return "'$atr[nombres] $atr[apellido_paterno]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarPersonas($atr, $pag, $registros_x_pagina){
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    if (count($this->parametros) <= 0){
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
                    }
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_personal p
                            LEFT JOIN mos_cargo c ON c.cod_cargo = p.cod_cargo
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(id_personal) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(nombres) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(apellido_paterno) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(apellido_materno) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-cod_emp"])>0)
                        $sql .= " AND cod_emp = '". $atr[b-cod_emp] . "'";
                    if (strlen($atr["b-id_personal"])>0)
                                $sql .= " AND upper(id_personal) like '%" . strtoupper($atr["b-id_personal"]) . "%'";
                    if (strlen($atr["b-nombres"])>0)
                                $sql .= " AND upper(nombres) like '%" . strtoupper($atr["b-nombres"]) . "%'";
                    if (strlen($atr["b-apellido_paterno"])>0)
                                $sql .= " AND upper(apellido_paterno) like '%" . strtoupper($atr["b-apellido_paterno"]) . "%'";
                    if (strlen($atr["b-apellido_materno"])>0)
                                $sql .= " AND upper(apellido_materno) like '%" . strtoupper($atr["b-apellido_materno"]) . "%'";
                    if (strlen($atr["b-genero"])>0)
                                $sql .= " AND upper(genero) like '%" . strtoupper($atr["b-genero"]) . "%'";
                    if (strlen($atr['b-fecha_nacimiento-desde'])>0)                        
                    {
                        $atr['b-fecha_nacimiento-desde'] = formatear_fecha($atr['b-fecha_nacimiento-desde']);                        
                        $sql .= " AND fecha_nacimiento >= '" . ($atr['b-fecha_nacimiento-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_nacimiento-hasta'])>0)                        
                    {
                        $atr['b-fecha_nacimiento-hasta'] = formatear_fecha($atr['b-fecha_nacimiento-hasta']);                        
                        $sql .= " AND fecha_nacimiento <= '" . ($atr['b-fecha_nacimiento-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(p.vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-interno"])>0)
                        $sql .= " AND upper(p.interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-id_filial"])>0)
                        $sql .= " AND id_filial = '". $atr[b-id_filial] . "'";
                    $id_org = -1;
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){                             
                        $id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $sql .= " AND id_organizacion IN (". $id_org . ")";
                    }
                    if (strlen($atr["b-cod_cargo"])>0)
                        $sql .= " AND c.descripcion = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-workflow"])>0)
                                $sql .= " AND upper(workflow) like '%" . strtoupper($atr["b-workflow"]) . "%'";
                    if (strlen($atr["b-email"])>0)
                                $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
                    if (strlen($atr["b-relator"])>0)
                                $sql .= " AND upper(relator) like '%" . strtoupper($atr["b-relator"]) . "%'";
                    if (strlen($atr["b-reviso"])>0)
                                $sql .= " AND upper(reviso) like '%" . strtoupper($atr["b-reviso"]) . "%'";
                    if (strlen($atr["b-elaboro"])>0)
                                $sql .= " AND upper(elaboro) like '%" . strtoupper($atr["b-elaboro"]) . "%'";
                    if (strlen($atr["b-aprobo"])>0)
                                $sql .= " AND upper(aprobo) like '%" . strtoupper($atr["b-aprobo"]) . "%'";
                    if (strlen($atr["b-extranjero"])>0)
                        $sql .= " AND upper(extranjero) like '%" . strtoupper($atr["b-extranjero"]) . "%'";
                    if (count($this->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                    }
                    

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];                       
                    //print_r($atr);
                    
            
                    $sql = "SELECT cod_emp
                                    ,id_personal
                                    ,nombres
                                    ,apellido_paterno
                                    ,apellido_materno
                                    ,id_organizacion
                                    ,DATE_FORMAT(fecha_nacimiento, '%d/%m/%Y') fecha_nacimiento
                                    ,CASE genero WHEN 1 THEN 'Masculino' ELSE 'Femenino' END genero
                                    ,c.descripcion cod_cargo
                                    ,CASE workflow  when 'S' then 'Si' Else 'No' END workflow
                                    ,p.vigencia
                                    ,CASE p.interno   when 1 then 'Si' Else 'No' END interno                                                                      
                                    ,id_filial                                                                                                            
                                    ,email
                                    ,CASE relator  when 'S' then 'Si' Else 'No' END relator
                                    ,CASE reviso when 'S' then 'Si' Else 'No' END reviso
                                    ,CASE elaboro when 'S' then 'Si' Else 'No' END elaboro
                                    ,CASE aprobo  when 'S' then 'Si' Else 'No' END aprobo
                                    ,extranjero
                                    ,DATE_FORMAT(fecha_ingreso, '%d/%m/%Y') fecha_ingreso
                                    ,DATE_FORMAT(fecha_egreso, '%d/%m/%Y') fecha_egreso
                                    $sql_col_left
                            FROM mos_personal p
                            LEFT JOIN mos_cargo c ON c.cod_cargo = p.cod_cargo $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(id_personal) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(nombres) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(apellido_paterno) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(apellido_materno) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_emp"])>0)
                        $sql .= " AND cod_emp = '". $atr[b-cod_emp] . "'";
                    if (strlen($atr["b-id_personal"])>0)
                                $sql .= " AND upper(id_personal) like '%" . strtoupper($atr["b-id_personal"]) . "%'";
                    if (strlen($atr["b-nombres"])>0)
                                $sql .= " AND upper(nombres) like '%" . strtoupper($atr["b-nombres"]) . "%'";
                    if (strlen($atr["b-apellido_paterno"])>0)
                                $sql .= " AND upper(apellido_paterno) like '%" . strtoupper($atr["b-apellido_paterno"]) . "%'";
                    if (strlen($atr["b-apellido_materno"])>0)
                                $sql .= " AND upper(apellido_materno) like '%" . strtoupper($atr["b-apellido_materno"]) . "%'";
                    if (strlen($atr["b-genero"])>0)
                                $sql .= " AND upper(genero) like '%" . strtoupper($atr["b-genero"]) . "%'";
                    if (strlen($atr['b-fecha_nacimiento-desde'])>0)                        
                    {
                        //$atr['b-fecha_nacimiento-desde'] = formatear_fecha($atr['b-fecha_nacimiento-desde']);                        
                        $sql .= " AND fecha_nacimiento >= '" . ($atr['b-fecha_nacimiento-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_nacimiento-hasta'])>0)                        
                    {
                        //$atr['b-fecha_nacimiento-hasta'] = formatear_fecha($atr['b-fecha_nacimiento-hasta']);                        
                        $sql .= " AND fecha_nacimiento <= '" . ($atr['b-fecha_nacimiento-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(p.vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-interno"])>0)
                        $sql .= " AND upper(p.interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-id_filial"])>0)
                               $sql .= " AND id_filial = '". $atr[b-id_filial] . "'";
                    //if (strlen($atr["b-id_organizacion"])>0)
                    //           $sql .= " AND id_organizacion = '". $atr[b-id_organizacion] . "'";
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){                             
                        //$id_org = $this->BuscaOrgNivelHijos($atr[b-id_organizacion]);
                        $sql .= " AND id_organizacion IN (". $id_org . ")";
                    }
                    if (strlen($atr["b-cod_cargo"])>0)
                               $sql .= " AND c.descripcion = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-workflow"])>0)
                                $sql .= " AND upper(workflow) like '%" . strtoupper($atr["b-workflow"]) . "%'";
                    if (strlen($atr["b-email"])>0)
                                $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
                    if (strlen($atr["b-relator"])>0)
                                $sql .= " AND upper(relator) like '%" . strtoupper($atr["b-relator"]) . "%'";
                    if (strlen($atr["b-reviso"])>0)
                                $sql .= " AND upper(reviso) like '%" . strtoupper($atr["b-reviso"]) . "%'";
                    if (strlen($atr["b-elaboro"])>0)
                                $sql .= " AND upper(elaboro) like '%" . strtoupper($atr["b-elaboro"]) . "%'";
                    if (strlen($atr["b-aprobo"])>0)
                                $sql .= " AND upper(aprobo) like '%" . strtoupper($atr["b-aprobo"]) . "%'";
                    if (strlen($atr["b-extranjero"])>0)
                                $sql .= " AND upper(extranjero) like '%" . strtoupper($atr["b-extranjero"]) . "%'";
                    if (count($this->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                    }
                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function listarPersonasSinFiltro($atr){
                    $atr = $this->dbl->corregir_parametros($atr);
                    
            //,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))))  cod_emp_relator
                    $sql = "SELECT cod_emp
                                    ,id_personal
                                    ,CONCAT(UPPER(LEFT(nombres, 1)), LOWER(SUBSTRING(nombres, 2))) nombres
                                    ,CONCAT(UPPER(LEFT(apellido_paterno, 1)), LOWER(SUBSTRING(apellido_paterno, 2))) apellido_paterno
                                    ,CONCAT(UPPER(LEFT(apellido_materno, 1)), LOWER(SUBSTRING(apellido_materno, 2))) apellido_materno
                                    ,id_organizacion
                                    ,DATE_FORMAT(fecha_nacimiento, '%d/%m/%Y') fecha_nacimiento
                                    ,CASE genero WHEN 1 THEN 'Masculino' ELSE 'Femenino' END genero
                                    ,c.descripcion cod_cargo
                                                                 
                            FROM mos_personal p
                            LEFT JOIN mos_cargo c ON c.cod_cargo = p.cod_cargo 
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_emp"])>0)
                        $sql .= " AND cod_emp = '". $atr[b-cod_emp] . "'";
                    if (strlen($atr["b-id_personal"])>0)
                                $sql .= " AND upper(id_personal) like '%" . strtoupper($atr["b-id_personal"]) . "%'";
                    if (strlen($atr["b-nombres"])>0)
                                $sql .= " AND upper(nombres) like '%" . strtoupper($atr["b-nombres"]) . "%'";
                    if (strlen($atr["b-apellido_paterno"])>0)
                                $sql .= " AND upper(apellido_paterno) like '%" . strtoupper($atr["b-apellido_paterno"]) . "%'";
                    if (strlen($atr["b-apellido_materno"])>0)
                                $sql .= " AND upper(apellido_materno) like '%" . strtoupper($atr["b-apellido_materno"]) . "%'";
                    if (strlen($atr["b-genero"])>0)
                                $sql .= " AND upper(genero) like '%" . strtoupper($atr["b-genero"]) . "%'";
                    if (strlen($atr['b-fecha_nacimiento-desde'])>0)                        
                    {
                        //$atr['b-fecha_nacimiento-desde'] = formatear_fecha($atr['b-fecha_nacimiento-desde']);                        
                        $sql .= " AND fecha_nacimiento >= '" . ($atr['b-fecha_nacimiento-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_nacimiento-hasta'])>0)                        
                    {
                        //$atr['b-fecha_nacimiento-hasta'] = formatear_fecha($atr['b-fecha_nacimiento-hasta']);                        
                        $sql .= " AND fecha_nacimiento >= '" . ($atr['b-fecha_nacimiento-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(p.vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-interno"])>0)
                        $sql .= " AND upper(p.interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-id_filial"])>0)
                               $sql .= " AND id_filial = '". $atr[b-id_filial] . "'";
                    //if (strlen($atr["b-id_organizacion"])>0)
                    //           $sql .= " AND id_organizacion = '". $atr[b-id_organizacion] . "'";
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){                             
                        //$id_org = $this->BuscaOrgNivelHijos($atr[b-id_organizacion]);
                        $sql .= " AND id_organizacion IN (". $id_org . ")";
                    }
                    if (strlen($atr["b-cod_cargo"])>0)
                               $sql .= " AND c.descripcion = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-workflow"])>0)
                                $sql .= " AND upper(workflow) like '%" . strtoupper($atr["b-workflow"]) . "%'";
                    if (strlen($atr["b-email"])>0)
                                $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
                    if (strlen($atr["b-relator"])>0)
                                $sql .= " AND upper(relator) like '%" . strtoupper($atr["b-relator"]) . "%'";
                    if (strlen($atr["b-reviso"])>0)
                                $sql .= " AND upper(reviso) like '%" . strtoupper($atr["b-reviso"]) . "%'";
                    if (strlen($atr["b-elaboro"])>0)
                                $sql .= " AND upper(elaboro) like '%" . strtoupper($atr["b-elaboro"]) . "%'";
                    if (strlen($atr["b-aprobo"])>0)
                                $sql .= " AND upper(aprobo) like '%" . strtoupper($atr["b-aprobo"]) . "%'";
                    if (strlen($atr["b-extranjero"])>0)
                                $sql .= " AND upper(extranjero) like '%" . strtoupper($atr["b-extranjero"]) . "%'";

                    $sql .= " order by apellido_paterno asc ";                    
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarPersonas($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_personal", "cod_emp = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_personal_hoja_vida", "cod_emp = " . $atr[id]);
                        $this->eliminarParametros(array(id_registro => $atr[id]));
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaPersonas($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarPersonas($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;

                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                
                $grid->SetConfiguracionMSKS("tblPersonas", "");
                $config_col=array(
                    
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Cod Emp", "cod_emp", $parametros,80)),
                    array("width"=>"15%", "ValorEtiqueta"=>"<div style='width:80px'>&nbsp;</div>"),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_personal], "id_personal", $parametros,90)),
               array( "width"=>"8%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombres], "nombres", $parametros)),
               array( "width"=>"8%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apellido_paterno], "apellido_paterno", $parametros)),
               array( "width"=>"8%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apellido_materno], "apellido_materno", $parametros)),
               
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros,200)),     
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_nacimiento], "fecha_nacimiento", $parametros)),
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[genero], "genero", $parametros)),
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_cargo], "cod_cargo", $parametros)),     
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[interno], "interno", $parametros)),
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_filial], "id_filial", $parametros)),
               
               
               
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email], "email", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[relator], "relator", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[reviso], "reviso", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[elaboro], "elaboro", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[aprobo], "aprobo", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[extranjero], "extranjero", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_ingreso], "fecha_ingreso", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_egreso], "fecha_egreso", $parametros))
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 20;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verPersonas','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Personas'>"));
               
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarPersonas','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"   title='Editar Personas'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarPersonas','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar Personas'></i>"));
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")<img style='cursor:pointer' src='diseno/images/hoja_vida.png' title='Hoja de Vida'>
                    array_push($func,array('nombre'=> 'HojadeVida','imagen'=> "<i style='cursor:pointer' class=\"icon icon-hoja-vida\" title='Hoja de Vida'></i>"));
                */
                $config=array();
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                //print_r($array_columns);
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
                 //print_r($grid->hidden);
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id_personal", "formatear_rut");
                $grid->setFuncion("cod_emp", "colum_admin");
                $grid->setFuncion("id_organizacion", "BuscaOrganizacional");
                //
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
            
        public function colum_admin($tupla)
        {
            //echo 1;
            //if($_SESSION[CookM] == 'S')
            if ($this->id_org_acceso[$tupla[id_organizacion]][modificar] == 'S')
            {
                //<img title=\"Modificar Documento $tupla[nombre_doc]\" src=\"diseno/images/ico_modificar.png\" style=\"cursor:pointer\">
                $html = "<a href=\"#\" onclick=\"javascript:editarPersonas('". $tupla[cod_emp] . "');\"  title=\"Editar Personas\">                            
                            <i class=\"icon icon-edit\"></i>
                        </a>";
            }
            //if($_SESSION[CookE] == 'S')
            if ($this->id_org_acceso[$tupla[id_organizacion]][eliminar] == 'S')
            {
                //<img title="Eliminar '.$tupla[nombre_doc].'" src="diseno/images/ico_eliminar.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:eliminarPersonas(\''. $tupla[cod_emp] . '\');" title="Eliminar Personas">
                        <i class="icon icon-remove"></i>
                        
                    </a>'; 
            }
            //if ($_SESSION[CookN] == 'S')
            {
                //<img title="Crear Versión '.$tupla[nombre_doc].'" src="diseno/images/ticket_ver.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:HojadeVida(\''. $tupla[cod_emp] . '\');" title="Hoja de Vida">                        
                            <i class="icon icon-hoja-vida"></i>
                    </a>'; 
            }
            
            return $html;
            
        }
            
        function BuscaOrganizacional($tupla)
        {
                $OrgNom = "";
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if($Fila3[organizacion_padre]==2)
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
        
        
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarPersonas($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblPersonas", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");                                                                                                                                                                                                                  
                $config_col=array(                 
                    array( "width"=>"10%","ValorEtiqueta"=>("Cod Emp")),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[id_personal], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"8%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[nombres], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"8%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[apellido_paterno], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"8%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[apellido_materno], ENT_QUOTES, "UTF-8"))),              
                    array( "width"=>"15%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8"))),    
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[fecha_nacimiento], ENT_QUOTES, "UTF-8"))),                   
                    array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[genero], ENT_QUOTES, "UTF-8"))),                    
                    array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[cod_cargo], ENT_QUOTES, "UTF-8"))),    
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[workflow], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[interno], ENT_QUOTES, "UTF-8"))),                    
                    array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[id_filial], ENT_QUOTES, "UTF-8"))),                                             
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[email], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[relator], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[reviso], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[elaboro], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[aprobo], ENT_QUOTES, "UTF-8"))),
                    array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[extranjero], ENT_QUOTES, "UTF-8"))),
                     array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[fecha_ingreso], ENT_QUOTES, "UTF-8"))),
                     array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($this->nombres_columnas[fecha_egreso], ENT_QUOTES, "UTF-8"))),
              );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
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
                $grid->setFuncion("id_personal", "formatear_rut");
                $grid->setFuncion("id_organizacion", "BuscaOrganizacional");
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexPersonas($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $parametros['b-interno'] = 1;
                if ($parametros['corder'] == null) $parametros['corder']="apellido_paterno";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-4-5-8-9-10-13-14-15-16-17"; 
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                }                 
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){
                        $parametros['mostrar-col'].= '-' . $value[1];
                        if ($value[1] < 15){
                            $contenido["CHECKED_". strtoupper($key)] = 'checked="checked"';
                        }
                    }else
                    {
                        $contenido["DISPLAY_". strtoupper($key)] = 'style="display:none;"';
                    }
                }                
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }                
                $k = 21;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    $parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                      ' . $value[espanol] . '</label>
                                  </div>
                            ';
                    $k++;
                }
                
                $grid = $this->verListaPersonas($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Personas();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Personas';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->permiso_crear($parametros) == 'S' ? '' : 'display:none;';
                
                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao();

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personas/';
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
                $template->PATH = PATH_TO_TEMPLATES.'personas/';

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
                $objResponse->addAssign('modulo_actual',"value","personas");
                $objResponse->addIncludeScript(PATH_TO_JS . 'personas/personas.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
//                $objResponse->addScript("var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
//
//                        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
//
//                        \$confModal.on('hidden', function() {
//                            $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
//                        });
//
//                        \$confModal.modal({ backdrop : false });");
                $objResponse->addScript("$('#b-fecha_nacimiento-desde').datepicker({
                                            changeMonth: true,
                                            yearRange: '-100:+0',
                                            changeYear: true
                                          });");
                $objResponse->addScript("$('#b-fecha_nacimiento-hasta').datepicker({
                                            changeMonth: true,
                                            yearRange: '-100:+0',
                                            changeYear: true
                                          });");
//                $objResponse->addScript("$('#tabs').tab();"
//                        . "$('#tabs a:first').tab('show');"
//                        . "$('a[data-toggle=\"tab\"]').on('shown.bs.tab', function (e) {
//
//                        console.log ( $(e.target).attr('id') );
//                }); ");
                //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                /*JS init_filtrar()*/
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_simple();');
                //$objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
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
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                }           
                $genero_validacion = '';
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($value[1] != '20'){
                            $contenido_1["VALIDACION_". strtoupper($key)] = 'data-validation="required"';
                        }
                        if ($value[1] == 7){
                            $genero_validacion = 'data-validation="required"';
                        }
                    }else
                    {
                        $contenido_1["DISPLAY_". strtoupper($key)] = 'style="display:none;"';
                    }
                }   
                if ($this->campos_activos['fecha_nacimiento'][0]=="0"){
                    $contenido_1[COL_NOM] = "16";
                }
                else
                    $contenido_1[COL_NOM] = "8";
                if ($this->campos_activos['genero'][0]=="0"){
                    $contenido_1[COL_MAT] = "16";
                }
                else
                    $contenido_1[COL_MAT] = "8";
                $num_col_email = 8;
                if ($this->campos_activos['fecha_ingreso'][0]=="0"){
                    $num_col_email = $num_col_email +8;
                }
                if ($this->campos_activos['fecha_egreso'][0]=="0"){
                    $num_col_email = $num_col_email +8;
                }
                $contenido_1[COL_EMA] = $num_col_email;
                //echo $this->campos_activos['fecha_nacimiento'][0];
                if(($this->campos_activos['genero'][0] == "1")&&($this->campos_activos['fecha_nacimiento'][0]=="1")){
                    $contenido_1["DISPLAY_FECHA_NACIMIENTO_ENTRE"] = 'style="display:none;"';
                }
                if(($this->campos_activos['genero'][0] == "0")&&($this->campos_activos['fecha_nacimiento'][0]=="0")){
                    $contenido_1["DISPLAY_FECHA_GENERO"] = 'style="display:none;"';
                }
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personas/';
                $template->setTemplate("formulario");
                $contenido_1[CHECKED_EXT_NO] = 'checked="checked"';
                $contenido_1[CHECKED_INTERNO] = 'checked="checked"';
                $contenido_1[CHECKED_VIGENCIA] = 'checked="checked"';
                $ids = array('', '1', '2'); 
                $desc = array('Seleccione', 'Masculino', 'Femenino');
                $contenido_1['GENERO'] = $ut_tool->combo_array("genero", $desc, $ids, $genero_validacion);
                $contenido_1['OPCION_CARGO_VACIO'] = 'Ingrese &Aacute;rbol Organizacional antes de especificar un cargo';
                $contenido_1['OTROS_CAMPOS'] = '';
                
                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                $array = $campos_dinamicos->crear_campos_dinamicos_form_h(3);
                $contenido_1[OTROS_CAMPOS] = $array[html];
                $js = $array[js];
                               
//                if (count($this->parametros) <= 0){
//                        $this->cargar_parametros();
//                }                
//                $k = 19;
//                $contenido_1[OTROS_CAMPOS] = "";
//                foreach ($this->parametros as $value) {                    
//                    $sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria='3' and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
//                    $data = $this->dbl->query($sql, array());
//                    $ids = array(''); 
//                    $desc = array('Seleccione');
//                    foreach ($data as $value_combos) {
//                        $ids[] = $value_combos[cod_parametro_det]; 
//                        $desc[] = $value_combos[descripcion];                                                
//                    }
//                    $combo_dinamico = $ut_tool->combo_array("cmb-".$value[cod_parametro], $desc, $ids, 'data-validation="required"');
//                    $contenido_1[OTROS_CAMPOS] .= '<div class="form-group">
//                                  <label for="cmb-'.$value[cod_parametro].'" class="col-md-3 control-label">' . $value[espanol] . '</label>
//                                  <div class="col-md-6">      
//                                      '.$combo_dinamico.' 
//                                  </div>
//                            </div>';
//                    $k++;
//                }
                
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario_h");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Personas";
                $contenido['PAGINA_VOLVER'] = "listarPersonas.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");    
                $objResponse->addScriptCall("cargar_autocompletado");    
                
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("                          
                            $.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('#fecha_nacimiento').datepicker({
                        changeMonth: true,
                        yearRange: '-100:+0',
                        changeYear: true
                      });");
                $objResponse->addScript("$('#fecha_ingreso').datepicker({
                        changeMonth: true,
                        yearRange: '-100:+0',
                        changeYear: true
                      });");
                $objResponse->addScript("$('#fecha_egreso').datepicker({
                        changeMonth: true,
                        yearRange: '-100:+0',
                        changeYear: true
                      });");
                $objResponse->addScript($js);
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
                    $parametros["fecha_nacimiento"] = strlen($parametros["fecha_nacimiento"]) >=10 ? formatear_fecha($parametros["fecha_nacimiento"]) : '';                    
                    $parametros["fecha_egreso"] = strlen($parametros["fecha_egreso"]) >=10 ? formatear_fecha($parametros["fecha_egreso"]) : '';   
                    $parametros["fecha_ingreso"] = strlen($parametros["fecha_ingreso"]) >=10 ? formatear_fecha($parametros["fecha_ingreso"]) : '';   
                    $parametros[id_personal] = str_replace(".", "" , $parametros[id_personal]);
                    $parametros[id_personal] = str_replace("-", "" , $parametros[id_personal]);
                    if (!isset($parametros[interno])) $parametros[interno] = 0;
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    if (!isset($parametros[relator])) $parametros[relator] = 'N';
                    if (!isset($parametros[workflow])) $parametros[workflow] = 'N';
                    if (!isset($parametros[reviso])) $parametros[reviso] = 'N';
                    if (!isset($parametros[elaboro])) $parametros[elaboro] = 'N';
                    $respuesta = $this->ingresarPersonas($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $parametros[id] = $respuesta;
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($parametros, 3);
//                        if (count($this->parametros) <= 0){
//                            $this->cargar_parametros();
//                        }                
//                        
//                        
//                        foreach ($this->parametros as $value) {                    
//                            //$sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria='3' and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
//                            //$data = $this->dbl->query($sql, array());
//                            //$ids = array(''); 
//                            //$desc = array('Seleccione');
//                            //foreach ($data as $value_combos) {
//                            //    $ids[] = $value_combos[cod_parametro_det]; 
//                            //    $desc[] = $value_combos[descripcion];                                                
//                            //}
//                            $params[cod_parametro_det] = $parametros["cmb-".$value[cod_parametro]];
//                            $params[cod_parametro] = $value[cod_parametro];
//                            $params[id_registro] = $respuesta;
//                            if (strlen($params[cod_parametro_det])>0)
//                                $this->ingresarParametro($params);
//                            //$this->ingresarParametro($params);
//                        }
                        $respuesta = "'$parametros[nombres] $parametros[apellido_paterno]' ha sido ingresado con exito";
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
                $contenido_1 = array();
                
                $genero_validacion = '';
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                }
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($value[1] != 20){
                            $contenido_1["VALIDACION_". strtoupper($key)] = 'data-validation="required"';
                        }
                        if ($value[1] == 7){
                            $genero_validacion = 'data-validation="required"';
                        }
                    }else
                    {
                        $contenido_1["DISPLAY_". strtoupper($key)] = 'style="display:none;"';
                    }
                }   
                if ($this->campos_activos['fecha_nacimiento'][0]=="0"){
                    $contenido_1[COL_NOM] = "16";
                }
                else
                    $contenido_1[COL_NOM] = "8";
                if ($this->campos_activos['genero'][0]=="0"){
                    $contenido_1[COL_MAT] = "16";
                }
                else
                    $contenido_1[COL_MAT] = "8";
                $num_col_email = 8;
                if ($this->campos_activos['fecha_ingreso'][0]=="0"){
                    $num_col_email = $num_col_email +8;
                }
                if ($this->campos_activos['fecha_egreso'][0]=="0"){
                    $num_col_email = $num_col_email +8;
                }
                $contenido_1[COL_EMA] = $num_col_email;
                if(($this->campos_activos['genero'][0] == "1")&&($this->campos_activos['fecha_nacimiento'][0]=="1")){
                    $contenido_1["DISPLAY_FECHA_NACIMIENTO_ENTRE"] = 'style="display:none;"';
                }
                if(($this->campos_activos['genero'][0] == "0")&&($this->campos_activos['fecha_nacimiento'][0]=="0")){
                    $contenido_1["DISPLAY_FECHA_GENERO"] = 'style="display:none;"';
                }
                $val = $this->verPersonas($parametros[id]); 

                
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
                $contenido_1['COD_EMP'] = $val["cod_emp"];
                $contenido_1['ID_PERSONAL'] = ($val["id_personal"]);
                if ($val["extranjero"] == 'NO'){
                    $RutEmp=$val[id_personal];
                    $cadena = $RutEmp;
                    $largo_cadena = strlen($cadena);
                    $cadena_izquierda = substr($cadena, 0, $largo_cadena-1);
                    $cadena_derecha = substr($cadena, $largo_cadena-1, 1);
                    $final = number_format($cadena_izquierda,0,"",".")."-".$cadena_derecha;
                    $contenido_1['ID_PERSONAL'] = $final;
                }
                $contenido_1['NOMBRES'] = ($val["nombres"]);
                $contenido_1['APELLIDO_PATERNO'] = ($val["apellido_paterno"]);
                $contenido_1['APELLIDO_MATERNO'] = ($val["apellido_materno"]);
                //$contenido_1['GENERO'] = ($val["genero"]);
                $ids = array('', '1', '2'); 
                $desc = array('Seleccione', 'Masculino', 'Femenino');
                $contenido_1['GENERO'] = $ut_tool->combo_array("genero", $desc, $ids, $genero_validacion, $val["genero"]);
                $contenido_1['OPCION_CARGO_VACIO'] = 'Ingrese &Aacute;rbol Organizacional antes de especificar un cargo';
                $contenido_1['FECHA_NACIMIENTO'] = ($val["fecha_nacimiento"]);
                $contenido_1['FECHA_INGRESO'] = ($val["fecha_ingreso"]);
                $contenido_1['FECHA_EGRESO'] = ($val["fecha_egreso"]);
                //$contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1[CHECKED_VIGENCIA] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
                //$contenido_1['INTERNO'] = ($val["interno"]);
                $contenido_1[CHECKED_INTERNO] = $val["interno"] == '1' ? 'checked="checked"' : '';
                $contenido_1['ID_FILIAL'] = $val["id_filial"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                $contenido_1['COD_CARGO'] = $val["cod_cargo"];
                $contenido_1['WORKFLOW'] = ($val["workflow"]);
                $contenido_1[CHECKED_WORKFLOW] = $val["workflow"] == 'S' ? 'checked="checked"' : '';
                $contenido_1['EMAIL'] = ($val["email"]);
                //$contenido_1['RELATOR'] = ($val["relator"]);
                $contenido_1[CHECKED_RELATOR] = $val["relator"] == 'S' ? 'checked="checked"' : '';
                //$contenido_1['REVISO'] = ($val["reviso"]);
                $contenido_1[CHECKED_REVISO] = $val["reviso"] == 'S' ? 'checked="checked"' : '';
                //$contenido_1['ELABORO'] = ($val["elaboro"]);
                $contenido_1[CHECKED_ELABORO] = $val["elaboro"] == 'S' ? 'checked="checked"' : '';
                //$contenido_1['APROBO'] = ($val["aprobo"]);
                $contenido_1[CHECKED_APROBO] = $val["aprobo"] == 'S' ? 'checked="checked"' : '';
                //$contenido_1['EXTRANJERO'] = ($val["extranjero"]);
                $contenido_1[CHECKED_EXT_NO] = $val["extranjero"] == 'NO' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_EXT_SI] = $val["extranjero"] == 'SI' ? 'checked="checked"' : '';

                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                $array = $campos_dinamicos->crear_campos_dinamicos_form_h(3,$val["cod_emp"]);
                $contenido_1[OTROS_CAMPOS] = $array[html];
                $js = $array[js];
                
//                if (count($this->parametros) <= 0){
//                        $this->cargar_parametros();
//                }                                
//                $contenido_1[OTROS_CAMPOS] = "";
//                $sql = "SELECT cod_parametro, cod_parametro_det FROM mos_parametro_modulos WHERE cod_categoria = 3 and id_registro = $val[cod_emp]";
//                
//                $data_params = $this->dbl->query($sql, array());
//                
//                $valores_params = array();
//                foreach ($data_params as $value_data_params) {
//                    $valores_params[$value_data_params[cod_parametro]]  = $value_data_params[cod_parametro_det];
//                }
//                //print_r($valores_params);
//                foreach ($this->parametros as $value) {                    
//                    $sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria=3 and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
//                    $data = $this->dbl->query($sql, array());
//                    $ids = array(''); 
//                    $desc = array('Seleccione');
//                    foreach ($data as $value_combos) {
//                        $ids[] = $value_combos[cod_parametro_det]; 
//                        $desc[] = $value_combos[descripcion];                                                
//                    }
//                    //echo $valores_params[$value[cod_parametro]];
//                    $combo_dinamico = $ut_tool->combo_array("cmb-".$value[cod_parametro], $desc, $ids, 'data-validation="required"', $valores_params[$value[cod_parametro]]);
//                    $contenido_1[OTROS_CAMPOS] .= '<div class="form-group">
//                                  <label for="cmb-'.$value[cod_parametro].'" class="col-md-3 control-label">' . $value[espanol] . '</label>
//                                  <div class="col-md-6">      
//                                      '.$combo_dinamico.' 
//                                  </div>
//                            </div>';                    
//                }
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personas/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario_h");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Personas";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Personas";
                $contenido['PAGINA_VOLVER'] = "listarPersonas.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["cod_emp"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScriptCall("cargar_autocompletado");    
                
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("
                          
                            $.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('#fecha_nacimiento').datepicker({
                    changeMonth: true,
                    yearRange: '-100:+0',
                    changeYear: true
                  });");
                $objResponse->addScript("$('#fecha_ingreso').datepicker({
                        changeMonth: true,
                        yearRange: '-100:+0',
                        changeYear: true
                      });");
                $objResponse->addScript("$('#fecha_egreso').datepicker({
                        changeMonth: true,
                        yearRange: '-100:+0',
                        changeYear: true
                      });");
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
                    $parametros["fecha_nacimiento"] = strlen($parametros["fecha_nacimiento"]) >=10 ? formatear_fecha($parametros["fecha_nacimiento"]) : '';                    
                    $parametros["fecha_egreso"] = strlen($parametros["fecha_egreso"]) >=10 ? formatear_fecha($parametros["fecha_egreso"]) : '';   
                    $parametros["fecha_ingreso"] = strlen($parametros["fecha_ingreso"]) >=10 ? formatear_fecha($parametros["fecha_ingreso"]) : '';   
                    $parametros[id_personal] = str_replace(".", "" , $parametros[id_personal]);
                    $parametros[id_personal] = str_replace("-", "" , $parametros[id_personal]);
                    if (!isset($parametros[interno])) $parametros[interno] = 0;
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    if (!isset($parametros[relator])) $parametros[relator] = 'N';
                    if (!isset($parametros[workflow])) $parametros[workflow] = 'N';
                    if (!isset($parametros[reviso])) $parametros[reviso] = 'N';
                    if (!isset($parametros[elaboro])) $parametros[elaboro] = 'N';
                    $respuesta = $this->modificarPersonas($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) 
                    //if (1==1)
                    {
//                        if (count($this->parametros) <= 0){
//                            $this->cargar_parametros();
//                        }                
//                                                
//                        $params[id_registro] = $parametros[id];
//                        $this->eliminarParametros($params);
//                        foreach ($this->parametros as $value) {                                                
//                            $params[cod_parametro_det] = $parametros["cmb-".$value[cod_parametro]];
//                            $params[cod_parametro] = $value[cod_parametro];
//                            if (strlen($params[cod_parametro_det])>0)
//                                $this->ingresarParametro($params);
//                        }
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($parametros, 3);
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
                $val = $this->verPersonas($parametros[id]);
                $respuesta = $this->eliminarPersonas($parametros);
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
                $grid = $this->verListaPersonas($parametros);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid['tabla']);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
            
            public function buscar_personas($parametros){
                $this->listarPersonasSinFiltro($parametros);
                $data=$this->dbl->data;
                $html = '<select name="origen[]" class="form-control" id="origen" multiple="multiple" style="height: 350px;">';
                /*
                 cod_emp
                                    ,id_personal
                                    ,nombres
                                    ,apellido_paterno
                                    ,apellido_materno
                                    ,id_organizacion
                 */
                $i=1;
                foreach ($data as $value) {
                    $html .= '<option value="'.$value[cod_emp].'" rut="'.$value[id_personal].'"';
                    $html .= ' nom="' .$value[nombres].'"' ;
                    $html .= ' ap_p="' .$value[apellido_paterno].'"' ;
                    $html .= ' ap_m="' .$value[apellido_materno].'"' ;
                    $html .= ' arb="' . $value[id_organizacion] . '"';
                    $html .= '>' /*. completar_espacios($i,1).' - '*/. str_pad($value[id_personal], 9, '0',STR_PAD_LEFT).' - '.$value[apellido_paterno].' '.$value[apellido_materno].' '.$value[nombres].'</option>';
                    $i++;
                }
                $html .= '</select>';
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('div-origen',"innerHTML",$html);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$('#origen').on('dblclick', 'option', function() {
                                                !$('#origen option:selected').remove().appendTo('#destino');                
                                        }); "); 
                $objResponse->addScript("$('#destino').on('dblclick', 'option', function() {
                                                !$('#destino option:selected').remove().appendTo('#origen');         
                                        }); "); 
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verPersonas($parametros[id]);

                            $contenido_1['COD_EMP'] = $val["cod_emp"];
            $contenido_1['ID_PERSONAL'] = ($val["id_personal"]);
            $contenido_1['NOMBRES'] = ($val["nombres"]);
            $contenido_1['APELLIDO_PATERNO'] = ($val["apellido_paterno"]);
            $contenido_1['APELLIDO_MATERNO'] = ($val["apellido_materno"]);
            $contenido_1['GENERO'] = ($val["genero"]);
            $contenido_1['FECHA_NACIMIENTO'] = ($val["fecha_nacimiento"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['INTERNO'] = ($val["interno"]);
            $contenido_1['ID_FILIAL'] = $val["id_filial"];
            $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
            $contenido_1['COD_CARGO'] = $val["cod_cargo"];
            $contenido_1['WORKFLOW'] = ($val["workflow"]);
            $contenido_1['EMAIL'] = ($val["email"]);
            $contenido_1['RELATOR'] = ($val["relator"]);
            $contenido_1['REVISO'] = ($val["reviso"]);
            $contenido_1['ELABORO'] = ($val["elaboro"]);
            $contenido_1['APROBO'] = ($val["aprobo"]);
            $contenido_1['EXTRANJERO'] = ($val["extranjero"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personas/';
                $template->setTemplate("verPersonas");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Personas";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
            
            public function MuestraPadre(){
		$sql="Select * from mos_organizacion
				Where parent_id = 2";
                
                $data = $this->dbl->query($sql, $atr);
                
		//$resp = mysql_query($sql);
		$cabecera_padre = "<ul>";
		$padre_final = "";
		//while($arrP=mysql_fetch_assoc($resp)){
                foreach ($data as $arrP) {
                        
                    
			$cuerpo .= "<li id=\"phtml_".$arrP[id]."\">
							<a href=\"#\">".($arrP[title])."</a>
							".$this->MuestraHijos($arrP[id])."
						</li>";
		}
		$pie_padre = "</ul>";
		return $cabecera_padre.$cuerpo.$pie_padre;
	}

	public function MuestraHijos($id){
		$sql="select * from mos_organizacion
				Where parent_id = $id";
		//$resp = mysql_query($sql);
                $data = $this->dbl->query($sql, $atr);
		$cabecera = "<ul>";
		//while($arr=mysql_fetch_assoc($resp)){
                foreach ($data as $arr) {
			$extra .= "<li id=\"phtml_".$arr[id]."\">
							<a href=\"#\">".($arr[title])."</a>
							".$this->MuestraHijos($arr[id])."
						</li>";		}
		$pie = "</ul>";
		return $cabecera.$extra.$pie;
	}

            public function MuestraPadreReg(){
                    $sql="select 
                            mos_organizacion.*
                            from mos_organizacion 
                            Where parent_id = 2";
                
                $data = $this->dbl->query($sql, $atr);
                
		//$resp = mysql_query($sql);
		$cabecera_padre = "<ul>";
		$padre_final = "";
		//while($arrP=mysql_fetch_assoc($resp)){
                foreach ($data as $arrP) {
                         $id_org = $this->BuscaOrgNivelHijos($arrP[id]);
                         //echo 'Idorg='.$id_org;
                        $sql="SELECT
                            IFNULL(count(mos_registro_item.idRegistro),0) cant
                            FROM
                            mos_registro_item
                            WHERE
                            valor in (".$id_org.")
                            and tipo = 11;";  
                        
                        $cuenta = $this->dbl->query($sql, $atr);
                        $registros='';
                        if($cuenta[0][cant]>0) $registros='('.$cuenta[0][cant].')';
                        //echo($sql);        
        		$cuerpo .= "<li id=\"phtml_".$arrP[id]."\">
							<a href=\"#\">".($arrP[title]).$registros."</a>
							".$this->MuestraHijosReg($arrP[id])."
						</li>";
		}
		$pie_padre = "</ul>";
		return $cabecera_padre.$cuerpo.$pie_padre;
	}

	public function MuestraHijosReg($id){
                
               
                if($id_org=='')$id_org='0';
		$sql="select 
                            mos_organizacion.*,
                            0 registros
                            from mos_organizacion 
                            Where parent_id = $id";
		//$resp = mysql_query($sql);
                $data = $this->dbl->query($sql, $atr);
		$cabecera = "<ul>";
		//while($arr=mysql_fetch_assoc($resp)){
                foreach ($data as $arr) {
                         $id_org = $this->BuscaOrgNivelHijos($arr[id]);
                         //echo 'Idorg='.$id_org;
                        $sql="SELECT
                            IFNULL(count(mos_registro_item.idRegistro),0) cant
                            FROM
                            mos_registro_item
                            WHERE
                            valor in (".$id_org.")
                            and tipo = 11;";  
                        $cuenta = $this->dbl->query($sql, $atr);
                        $registros='';
                        if($cuenta[0][cant]>0) $registros='('.$cuenta[0][cant].')';
			$extra .= "<li id=\"phtml_".$arr[id]."\">
							<a href=\"#\">".($arr[title]).$registros."</a>
							".$this->MuestraHijosReg($arr[id])."
						</li>";		}
		$pie = "</ul>";
		return $cabecera.$extra.$pie;
	}
      
        public function cargos($parametros){
                $objResponse = new xajaxResponse();
                $objResponse->call('clearOptions','cod_cargo');
                $objResponse->call('addOption','cod_cargo','Seleccione','');
                //$data = $this->listarProceso(array('corder' => 'nombre', 'sorder' => 'asc', 'area'=>$parametros[id_area]), 1, 100);
                $sql = "Select c.cod_cargo,c.descripcion From mos_cargo c
                        inner join mos_cargo_estrorg_arbolproc r ON r.cod_cargo = c.cod_cargo
                        Where r.id = ".$parametros["id_arbol"]." 
                        Order By c.descripcion ";
                
                //$this->listarEquipos(array('id_area' => $parametros[id_area], 'id_proceso' => $parametros[id_proceso], 'corder' => 'equipo', 'sorder' => 'asc'), 1, 100);
                $data = $this->dbl->query($sql,array());
                //$data=$pagina->dbl->data;
                foreach( $data as $option){
                    $objResponse->call('addOption','cod_cargo',($option['descripcion']),$option['cod_cargo']);
                }
                if (strlen($parametros[cod_cargo])> 0){
                    $objResponse->addScript("$('#cod_cargo').val('$parametros[cod_cargo]');");
                }
                return $objResponse;
             }
             
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
     
 }?>