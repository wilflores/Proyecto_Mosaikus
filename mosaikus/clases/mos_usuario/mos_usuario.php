<?php
 import("clases.interfaz.Pagina");        
        class mos_usuario extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function mos_usuario(){
                parent::__construct();
                $this->asigna_script('mos_usuario/mos_usuario.js');                                             
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
                    $sql = "INSERT INTO mos_usuario(nombres,apellido_paterno,apellido_materno,telefono,fecha_creacion,fecha_expi,vigencia,super_usuario,email,password_1,cedula)
                            VALUES(
                                '$atr[nombres]','$atr[apellido_paterno]','$atr[apellido_materno]','$atr[telefono]','".date('Y-m-d G:h:s')."','$atr[fecha_expi]','$atr[vigencia]','$atr[super_usuario]','$atr[email]','".md5($atr[password_1])."','$atr[cedula]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_usuario ' . $atr[descripcion_ano], 'mos_usuario');
                      */
                    $nuevo = "Id Usuario: \'$atr[id_usuario]\', Nombres: \'$atr[nombres]\', Apellido Paterno: \'$atr[apellido_paterno]\', Apellido Materno: \'$atr[apellido_materno]\', Telefono: \'$atr[telefono]\', Fecha Creacion: \'$atr[fecha_creacion]\', Fecha Expi: \'$atr[fecha_expi]\', Vigencia: \'$atr[vigencia]\', Super Usuario: \'$atr[super_usuario]\', Email: \'$atr[email]\', Password 1: \'$atr[password_1]\', Cedula: \'$atr[cedula]\', ";
                    $this->registraTransaccionLog(21,$nuevo,'', '');
                    return "El mos_usuario '$atr[nombres]' ha sido ingresado con exito";
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
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarmos_usuario($atr){
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_usuario SET                            
                                    nombres = '$atr[nombres]',apellido_paterno = '$atr[apellido_paterno]',apellido_materno = '$atr[apellido_materno]',telefono = '$atr[telefono]',fecha_expi = '$atr[fecha_expi]',vigencia = '$atr[vigencia]',super_usuario = '$atr[super_usuario]',email = '$atr[email]',password_1 = '$atr[password_1]',cedula = '$atr[cedula]'
                            WHERE  id_usuario = $atr[id_usuario]"; 
                    
                    $val = $this->vermos_usuario($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Usuario: \'$atr[id_usuario]\', Nombres: \'$atr[nombres]\', Apellido Paterno: \'$atr[apellido_paterno]\', Apellido Materno: \'$atr[apellido_materno]\', Telefono: \'$atr[telefono]\', Fecha Creacion: \'$atr[fecha_creacion]\', Fecha Expi: \'$atr[fecha_expi]\', Vigencia: \'$atr[vigencia]\', Super Usuario: \'$atr[super_usuario]\', Email: \'$atr[email]\', Password 1: \'$atr[password_1]\', Cedula: \'$atr[cedula]\', ";
                    $anterior = "Id Usuario: \'$val[id_usuario]\', Nombres: \'$val[nombres]\', Apellido Paterno: \'$val[apellido_paterno]\', Apellido Materno: \'$val[apellido_materno]\', Telefono: \'$val[telefono]\', Fecha Creacion: \'$val[fecha_creacion]\', Fecha Expi: \'$val[fecha_expi]\', Vigencia: \'$val[vigencia]\', Super Usuario: \'$val[super_usuario]\', Email: \'$val[email]\', Password 1: \'$val[password_1]\', Cedula: \'$val[cedula]\', ";
                    $this->registraTransaccionLog(21,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el mos_usuario ' . $atr[descripcion_ano], 'mos_usuario');
                    */
                    return "El mos_usuario '$atr[nombres]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarmos_usuario($atr, $pag, $registros_x_pagina){
                
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
                    }*/
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

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                
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
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cedula], "perfil_portal", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cedula], "perfil_especialista", $parametros))
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
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 13;
                }*/
                if ($_SESSION[CookM] == 'S')
                    array_push($func,array('nombre'=> 'vermos_usuario','imagen'=> "<img style='cursor:pointer'  class=\"icon icon-view-document\" title='Ver Usuario'>"));                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarmos_usuario','imagen'=> "<img style='cursor:pointer'  class=\"icon icon-edit\" title='Editar Usuario'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarmos_usuario','imagen'=> "<img style='cursor:pointer'  class=\"icon icon-remove\" title='Eliminar Usuario'>"));
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'configurarmos_usuario','imagen'=> "<i style='cursor:pointer' class=\"icon icon-more\" title='Configurar Perfiles'></i>"));

                array_push($func,array('condicion'=> array('columna'=> 'perfil_portal', 'valor'=> " > 0"), 'parametros' => "$data[id_usuario]", 'nombre'=> 'perfil_portal','imagen'=> "<i style='cursor:pointer' class=\"icon-app-mode icon-app-mode-portal\" title='Administrar Perfiles Portal'></i>"));
                
                array_push($func,array('condicion'=> array('columna'=> 'perfil_especialista', 'valor'=> " > 0"), 'parametros' => "$data[id_usuario]", 'nombre'=> 'perfil_especialista','imagen'=> "<i style='cursor:pointer' class=\"icon-app-mode icon-app-mode-specialist\" title='Administrar Perfiles Especialista'></i>"));

                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
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
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                //}
                return $out;
            }

            public function configurarPerfil($parametros){
                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $filial = $this->verfilial($parametros[if_filial]);
                
                $val = $this->vermos_usuario($filial[id_usuario]); 
                //$usuario = $val[apellido_paterno].", ".$val[nombres];

                import("clases.perfiles.Perfiles");
                $perfil = new Perfiles();
                $val2 = $perfil->verPerfiles($filial[cod_perfil]);

                $contenido_1['ID_FILIAL'] = $parametros[if_filial];
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['NOMBRES'] = ($val[apellido_paterno].", ".$val[nombres]);
                $contenido_1['COD_PERFIL'] = $val2["cod_perfil"];
                $contenido_1['DESCRIPCION_PERFIL'] = $val2["descripcion_perfil"]; 
                $contenido_1['TITULO_FORMULARIO'] = "Configurar&nbsp;Perfiles";
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

                //$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->setTemplate("formulario");


                //$template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
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
             
            public function verListaPerfiles($parametros){
                
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
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
                $contenido[TITULO_MODULO] = 'Administracion Usuario / Perfil';
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_perfil";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                $grid = $this->verListaPerfiles($parametros);
//                $contenido['CORDER'] = $parametros['corder'];
//                $contenido['SORDER'] = $parametros['sorder'];
//                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                //$contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                //$contenido['JS_NUEVO'] = 'funcion_volver("listarmos_usuario.php");';
                $contenido['TITULO_NUEVO'] = 'Volver&nbsp;a&nbsp;Usuarios';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'perfiles/';
                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'mos_usuario/';
                $template->setTemplate("listar_perfil");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","Usuarios");
                $objResponse->addIncludeScript(PATH_TO_JS . 'mos_usuario/mos_usuario.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                return $objResponse;            
        }
 
            public function indexmos_usuario($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if ($parametros['corder'] == null) $parametros['corder']="nombres";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-8-9-11"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
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
                $grid = $this->verListamos_usuario($parametros);
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
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

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
                          });");$objResponse->addScript("$('#fecha_creacion').datepicker();");
$objResponse->addScript("$('#fecha_expi').datepicker();");
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
                    $parametros["fecha_creacion"] = formatear_fecha($parametros["fecha_creacion"]);
                    $parametros["fecha_expi"] = formatear_fecha($parametros["fecha_expi"]);

                    $respuesta = $this->ingresarmos_usuario($parametros);

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

    public function eliminarPerfilesUsuario($atr){
           try {                   
               $respuesta = $this->dbl->delete("mos_usuario_filial", "id_usuario = $atr[id_usuario]");
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
               
               $respuesta = $this->dbl->delete("mos_usuario_estructura", "id_usuario_filial = $atr[id_filial]");
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
            $sql = "INSERT INTO mos_usuario_estructura(id_usuario_filial,id_estructura,id_usuario, cod_perfil)
                    VALUES($atr[id_filial],$atr[id_estructura],$atr[id_usuario],$atr[cod_perfil])";
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
            
            $params[id_usuario] = $parametros[id_usuario];
            $params[id_filial] = $_SESSION[CookFilial]; 
             
            $this->eliminarPerfilesUsuario($parametros);
            foreach($arr as $temp){
                 $params[cod_perfil] = $temp;
                 $respuesta = $this->ingresarPerfilesUsuario($params);
            }

            foreach($arrportal as $temp){
                 $params[cod_perfil] = $temp;
                 $respuesta = $this->ingresarPerfilesPortalUsuario($params);
            }
            
            $objResponse->addScriptCall("MostrarContenido");
            $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
       }
       $objResponse->addScript("$('#MustraCargando').hide();"); 
       $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                               $( '#btn-guardar' ).prop( 'disabled', false );");
       return $objResponse;
   }    

   public function cargarConfiguracionPerfiles($parametros)
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
            $this->eliminarPerfilesEstructura($parametros);
            foreach($arr as $temp){
                 $params[id_estructura] = $temp;
                 $respuesta = $this->ingresarPerfilesEstructura($params);
            }           
            $objResponse->addScriptCall("MostrarContenido");
            $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
       }
       $objResponse->addScript("$('#MustraCargando').hide();"); 
       $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                               $( '#btn-guardar' ).prop( 'disabled', false );");
       return $objResponse;
   }    
   
   
 }?>