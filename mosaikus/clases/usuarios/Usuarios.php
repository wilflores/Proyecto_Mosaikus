<?php 
        import("clases.interfaz.Pagina");        
        class Usuarios extends Pagina{
        private $templates;
        private $bd;

        public function Usuarios(){
            parent::__construct();           
            $this->asigna_script('usuarios/usuarios.js');
            $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
            //$this->dbl->conectar();
            $this->contenido = array();
        }
        
        private function operacion2($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
        private function operacion($sp, $atr, $parametros){
                $atr = $this->dbl->corregir_parametros($atr);
                $atr = array_intersect_key($atr, $parametros);
                $param=array();
                if(is_array($atr)){
                    foreach ($atr as $key => $value) {
                        $param["@p_".$key]=$parametros[$key] . $value . $parametros[$key];
                    }
                }                
                return $this->dbl->exe($sp, $param);;
            }

        public function ListarRolArray( &$arrval, &$arropt){
                $nombre_sql = "sp_rol_con";
                $this->dbl->exe($nombre_sql,'');
                $lista = $this->dbl->data;
                $i =0;
                $arrval[$i] = 0;
                $arropt[$i] = '[Seleccione]';
//                if ($this->dbl->nreg > 0)
//                        while ($i < $this->dbl->nreg ){
//                                $arrval[$i+1] = $lista[$i][0];
//                                $arropt[$i+1] = $lista[$i][1];
//                        $i++;
//                        }

        }    

        public function verUsuario($id){
//            $atr=array();
//            $atr['p_id']=$id;
//            $resultado = $this->dbl->exe("sp_usuario_con_id", $atr);
//            //print_r($resultado);
//            return $resultado[0];     
            $atr=array();
                $sql = "SELECT id_usuario
                            ,nombres
                            ,apellido_paterno
                            ,apellido_materno
                            ,telefono
                            ,DATE_FORMAT(fecha_creacion, '%d/%m/%Y') fecha_creacion
                            ,DATE_FORMAT(fecha_expi, '%d/%m/%Y') fecha_expi

                         FROM mos_usuario 
                         WHERE id_usuario = $id "; 
                $this->operacion2($sql, $atr);
                return $this->dbl->data[0];
        }
        
        public function verUsuarioBDG($atr){
//            $atr=array();
//            $atr['p_id']=$id;
//            $resultado = $this->dbl->exe("sp_usuario_con_id", $atr);
//            //print_r($resultado);
//            return $resultado[0];   
            include("clases/bd/MysqlBDP.php");
            $bd = new MysqlBDP();
            $atr = $bd->corregir_parametros($atr);            
            $sql = "select id_empresa from mos_adm_acceso where id_usuario='".$_SESSION[CookIdUsuario]."' and id_empresa='".$_SESSION[CookIdEmpresa]."' "
                    . "and password_1='".md5(trim($atr[password_actual]))."'";

            return $bd->query($sql, array());
        }

        public function verUsuarioLogin($id){
            $atr=array();
            $atr['login']= "'" .  $id . "'";
            $resultado = $this->dbl->exe("sp_usuario_con_login", $atr);
            return $resultado[0];
        }

        public function ingresarUsuario($atr){
            $parametros = array('login' => "'", 'nombre' => "'", 'cedula' => "'", 'password' => "'",  'correo' => "'", 'estado' => "", 'id_rol' => "", 'cargo' => "'", 'notificacion' => "");
            $respuesta = $this->operacion("sp_usuario_ins", $atr, $parametros);
            //$this->registraTransaccion('Insertar','Ingreso al usuario: '.$atr['login']);
            return $respuesta[0][0];
        }

        public function actualizarContrasena($atr){
            //include("clases/bd/MysqlBDP.php");
            $bd = new MysqlBDP();
            $atr = $bd->corregir_parametros($atr);            
            $sql = "UPDATE mos_adm_acceso SET password_1='".md5(trim($atr[password]))."' where id_usuario='".$_SESSION[CookIdUsuario]."' and id_empresa='".$_SESSION[CookIdEmpresa]."' ";
            $bd->insert_update($sql);


        }

        public function modificarUsuario($atr){            
            $parametros = array('login' => "'", 'nombre' => "'", 'cedula' => "'", 'password' => "'",  'correo' => "'", 'estado' => "", 'id_rol' => "", 'cargo' => "'", 'id' => '', 'notificacion' => "");
            $respuesta = $this->operacion("sp_usuario_upd", $atr, $parametros);
            //$this->operacion("sp_usuario_upd", $atr);
            return $respuesta[0][0];
        }
        
        public function modificarMeta($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                                            
                    $sql = "UPDATE tb_usuarios SET                                                                
                                    m_obs = $atr[m_obs],m_incc = $atr[m_incc]
                                    ,m_insp = $atr[m_insp]
                                    ,id_planta = $atr[id_empresa]
                                    ,id_area = $atr[id_area]
                            WHERE  id = $atr[id]";     
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $this->registraTransaccion('Modificar','Modifico el Reportes ' . $atr[descripcion_ano], '$GLOBALS[PREFIJO_BD]reporte');
                    return "El Reporte ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/Data too long for column 'magnitud_frec' at row 1/",$error ) == true) 
                            return "- La frecuencia del campo Magnitud Lesi&oacute;n / Da&ntilde;o es requerido."; 
                        if (preg_match("/Data too long for column 'magnitud_seve' at row 1/",$error ) == true) 
                            return "- La severidad del campo Magnitud Lesi&oacute;n / Da&ntilde;o es requerido.";                  
                        return $error; 
                    }
            }

        public function eliminarUsuario($atr2){
            //$this->operacion("sp_usuario_del", $atr);
            //return $this->dbl->data[0][0];
            $atr=array();
            $atr['p_id']=$atr2['id'];
            //print_r($atr);
            $resultado = $this->dbl->exe("sp_usuario_del", $atr);
            return $resultado[0][0];
        }

        public function listarUsuarios($atr, $pag, $registros_x_pagina){
            $atr = $this->dbl->corregir_parametros($atr);
                $sql = "SELECT COUNT(*) total_registros
                         FROM tb_usuarios u
                        left join tbl_usuarios_empresa ue ON ue.id_usuario = u.id and ue.id_empresa = $_SESSION[ID_EMPRESA]
                        left join tb_roles on ue.id_rol = tb_roles.id_rol
                         WHERE 1 = 1 ";
                if (strlen($atr[valor])>0){
                        //$sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";         
                        $nombre_supervisor = explode(' ', $atr[valor]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql .= " AND (upper($atr[campo]) like '%" . strtoupper($supervisor_aux) . "%') ";
                        } 
                    }
//                if (strlen($atr['campo']) > 0)
//                    $sql .= ' and ' . $atr['campo'] . " like '%" . $atr['valor'] . "%'";
                $total_registros = $this->dbl->query($sql, $atr);
                $this->total_registros = $total_registros[0][total_registros];   
                $sql = "select
                                u.id,
                                login,
                                nombre,
                                correo,                               
                                case estado when 1 then 'Activo' else 'Inactivo' end as estado,
                                tb_roles.descripcion,
                                cargo,                                
                                cedula
                        from
                                tb_usuarios u
                        left join tbl_usuarios_empresa ue ON ue.id_usuario = u.id and ue.id_empresa = $_SESSION[ID_EMPRESA]
                        left join tb_roles on ue.id_rol = tb_roles.id_rol
                        where 1 = 1 ";
                 if (strlen($atr[valor])>0){
                        //$sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";         
                        $nombre_supervisor = explode(' ', $atr[valor]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql .= " AND (upper($atr[campo]) like '%" . strtoupper($supervisor_aux) . "%') ";
                        } 
                    }
//                if (strlen($atr['campo']) > 0)
//                     $sql .= ' where ' . $atr['campo'] . " like '%" . $atr['valor'] . "%'";
                $sql .= ' order by nombre ';
                $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                //echo $sql;
                 $this->dbl->data = $this->dbl->query($sql, $atr);
                 //print_r($this->dbl->data);
                //$this->operacion("sp_usuario_con", $atr);
         }

        public function verListaUsuarios($parametros){
            $grid= new DataGrid();
            if ($parametros['pag'] == null) $parametros['pag'] = 1;
            $reg_por_pagina = getenv("PAGINACION");
            if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina'];
            $this->listarUsuarios($parametros, $parametros['pag'], $reg_por_pagina);
            $data=$this->dbl->data;

            $grid->SetConfiguracion("tblVehiculo", "align ='center' border='0' cellspacing='0' cellpadding='0'");
            $config=array(
                array( "width"=>"10%","ValorEtiqueta"=>"Login"),
                array( "width"=>"25%", "ValorEtiqueta"=>"Nombre"),
                array( "width"=>"10%","ValorEtiqueta"=>"Correo"),
                array( "width"=>"5%","ValorEtiqueta"=>"Estado"),
                //array( "width"=>"5%","ValorEtiqueta"=>"Perfil"),
                array( "width"=>"15%","ValorEtiqueta"=>"Cargo"),                

            );

            $func= array();
            $columna_funcion = -1;
            if (strrpos($parametros['permiso'], '1') > 0){
                array_push($config,array( "width"=>"10%", "ValorEtiqueta"=>"Acci&oacute;n"));
                $columna_funcion = 11;
            }
            if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'VerPermisos','imagen'=> "<img style='cursor:pointer' src='diseno/images/users.png' title='Ver Roles'>"));
            if ($parametros['permiso'][2] == "1")
                array_push($func,array('nombre'=> 'editarUsuario','imagen'=> "<img style='cursor:pointer' src='diseno/images/edit.png' title='Editar Clasificacion'>"));
            if ($parametros['permiso'][3] == "1")
                array_push($func,array('nombre'=> 'eliminarUsuario','imagen'=> "<img style='cursor:pointer' src='diseno/images/remove.png' title='Eliminar Clasificacion'>"));

            
            $grid->setPaginado($reg_por_pagina, $this->total_registros);
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->hidden = array(0 => true, 7=> true, 5=>TRUE);
            $grid->setData2("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
            $out['tabla']= $grid->armarTabla();
            //echo $this->total_registros;
            if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                $out['paginado']=$grid->setPaginadohtml("verPagina", "document");
            }
            
            return $out;
        }
        
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarUsuarios($parametros);
            $data=$this->dbl->data;

            $grid->SetConfiguracion("tblVehiculo", "width='650px' align ='center' border='0' cellspacing='0' cellpadding='0'");
            $config=array(
                array( "width"=>"10%","ValorEtiqueta"=>"Login"),
                array( "width"=>"25%", "ValorEtiqueta"=>"Nombre"),
                array( "width"=>"10%","ValorEtiqueta"=>"Correo"),
                array( "width"=>"5%","ValorEtiqueta"=>"Estado"),
                array( "width"=>"5%","ValorEtiqueta"=>"Perfil"),
                array( "width"=>"15%","ValorEtiqueta"=>"Cargo"),
                array( "width"=>"5%","ValorEtiqueta"=>"Admin. Curso"),
            );

            
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->hidden = array(0 => true, 4=> true, 8 => true, 9 => true);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }

        public function indexUsuarios($parametros)
        {
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }
            $grid = $this->verListaUsuarios($parametros);
            $contenido['PERMISO_INGRESAR'] = $parametros['permiso'][0] == "1" ? '' : 'display:none;';
            $contenido['TABLA'] = $grid['tabla'];
            $contenido['PAGINADO'] = $grid['paginado'];
            $contenido['OPCIONES_BUSQUEDA'] = "<option value='login'>Login</option>
                                               <option value='nombre'>Nombre/Apellido</option>
                                               <option value='tb_roles.descripcion'>Perfil</option>";
            $contenido['JS_NUEVO'] = 'nuevo_usuario();';
            $contenido['TITULO_NUEVO'] = 'Agregar Nuevo Usuario';
            //$contenido['ANCHO_GRID'] = 'width:650px;';
            $template = new Template();
            $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
            $template->setTemplate("listar");
            $template->setVars($contenido);
//            $this->contenido['CONTENIDO']  = $template->show();
//            $this->asigna_contenido($this->contenido);
//            return $template->show();
            if (isset($parametros['html']))
                    return $template->show();
            $objResponse = new xajaxResponse();
            $objResponse->addAssign('contenido',"innerHTML",$template->show());
            $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
            $objResponse->addAssign('modulo_actual',"value","usuarios");
            $objResponse->addIncludeScript(PATH_TO_JS . 'usuarios/usuarios.js?'.  rand(0, 100));
            $objResponse->addScriptCall("calcHeight");
            return $objResponse;
        }

        public function guardar($parametros)
        {
            unset ($parametros['opc']);
            unset ($parametros['id']);
            session_name("ajiebv");
            session_start();
            $parametros['usuario']= $_SESSION['USERID'];
            $parametros['numempl']= null;            
            $parametros['password'] = base64_encode($parametros['password']);
            $respuesta = $this->ingresarUsuario($parametros);
            $objResponse = new xajaxResponse();
            $mail = new ut_Tool();  
            if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
               $objResponse->addAssign('contenido',"innerHTML",$this->indexUsuarios(array('html' => true ,'permiso' => $parametros['permiso'])));
               $cuerpo = "Estimado <strong>". $parametros['nombre']."</strong>";
               $cuerpo.= "<br>Ha sido asignado un usuario en el sistema del Sistema de Excelencia SMS, por favor guarde esta informacion<br>";
               $cuerpo.= "<br><strong>Login/Usuario:</strong>".$parametros['login'];
               $cuerpo.= "<br><strong>Password:</strong>".$parametros['password2'];
               $cuerpo.= "<br><br>Para ingresar al sistema visite la siguiente direccion <a href='". APPLICATION_ROOT . "'>". APPLICATION_ROOT . "</a>";
               $cuerpo.= "<br><p>&nbsp;</p>IMPORTANTE: Por favor no respondas este correo electronico. Cualquier mensaje enviado como respuesta de este envio automatico no nos llegara.";
               $correos = array();               
               $correos[] = array('correo' => $parametros[correo], 'nombres'=>$parametros[nombre]);
               $mail->EnviarEMail(EMAIL_FROM,$correos,"Asignacion de Usuario de Sistema",$cuerpo);
               $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
               $sql = "SELECT MAX(id) ultimo FROM tb_usuarios"; 
               $this->operacion2($sql, $atr);
               $parametros[id] = $this->dbl->data[0][0];               
               $this->modificarMeta($parametros);

            }
            else
                $objResponse->addScriptCall('VerMensaje','error',$respuesta);
            if ($params[ajax] == 'NO')
                return;
            return $objResponse;
        }

        public function crear($parametros)
        {
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }

            $template = new Template();

            $template->PATH = PATH_TO_TEMPLATES.'usuarios/';
            $template->setTemplate("formularioNewUpd");
            $combo = new ut_Tool();                       
            $contenido_1['ESTADO'] = $combo->combo_array('estado',array('Activo','Inactivo'),array(1,0),false,$estado,false,false,false,false);
            $contenido_1['ES_ADMIN_CURSO'] = $combo->combo_array('notificacion',array('No','Si'),array(0,1),false,$usuario->es_admin_curso, false, false, false, false, 'margin-right:10px;display:none;');
            $contenido_1['ROL'] = $combo->OptionsCombo('SELECT id_rol, descripcion FROM tb_roles', 'id_rol', 'descripcion');
            $contenido_1['EMPRESAS'] = $combo->OptionsCombo("SELECT id, nombre 
                                                                                FROM $GLOBALS[PREFIJO_BD]empresa"
                                                                        , 'id'
                                                                        , 'nombre', $val["id_empresa"]); 
            $contenido_1[UND_PLANTA] = NOTACION_PLANTA;
            $template->setVars($contenido_1);
            $contenido['CAMPOS'] = $template->show();

            $contenido['TITULO_FORMULARIO'] = "Crear Usuario";
            $contenido['TITULO_VOLVER'] = "Volver a Listado de Usuarios";
            $contenido['PAGINA_VOLVER'] = "listarClasificacion.php";
            $contenido['DESC_OPERACION'] = "Guardar";
            $contenido['OPC'] = "new";
            $contenido['ID'] = "-1";
			$template->PATH = PATH_TO_TEMPLATES.'interfaz/';            
            $template->setTemplate("formulario");
            $template->setVars($contenido);
            $objResponse = new xajaxResponse();
            $objResponse->addAssign('contenido',"innerHTML",$template->show());
            return $objResponse;
        }

        public function editar_contrasena()
        {
            
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }
            session_name($GLOBALS[SESSION]);
            session_start();
            //echo 1;
            $val = $this->verUsuario($_SESSION['CookIdUsuario']);
            $template = new Template();
            //echo 2;
            
            $template->PATH = PATH_TO_TEMPLATES.'usuarios/';
            $template->setTemplate("formulario");
            $contenido['LOGIN'] = $val["id_usuario"];
            $contenido['NOMBRE'] = $val["nombres"] . ' ' . $val[apellido_paterno] . ' ' .$val[apellido_materno];
            $contenido['TELEFONO'] = $val["telefono"];
            $contenido['FECHA_C'] = $val["fecha_creacion"];
            $contenido['FECHA_E'] = $val["fecha_expi"];
           
            $template->setVars($contenido);
            //$this->contenido['CONTENIDO']  = $template->show();
            
            $objResponse = new xajaxResponse();
            //$objResponse->addAssign('contenido',"innerHTML",$template->show());
            
            $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
            $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
            //$objResponse->addAssign('modulo_actual',"value","parametros_det");
            $objResponse->addIncludeScript(PATH_TO_JS . 'usuarios/usuarios.js');
            $objResponse->addScript("$('#MustraCargando').hide();");
            $objResponse->addScript("$('#myModal-Ventana').modal('show');");
            $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Cambiar Contraseña');");

            //$objResponse->addScript("$('#hv-fecha').datepicker();");
            $objResponse->addScript("$.formUtils.addValidator({
            name : 'igual_pw',
            validatorFunction : function(value, \$el, config, language, \$form) { 
                if ((value != $('#password').val())){                    
                    
                    return false;
                }                  
              return true;
            },
            errorMessage : 'La contraseña nueva no coincide con la confirmacion de contraseña.',
            errorMessageKey: 'badEvenNumber'
          });");
            return $objResponse;
//            $this->asigna_contenido($this->contenido);
//            return $template->show();
        }

        public function recuperar_contrasena()
        {
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }
//            session_name("ajiebv");
//            session_start();

            
            $template = new Template();
            $this->asigna_css('login.css');

            $template->PATH = PATH_TO_TEMPLATES.'usuarios/';
            $template->setTemplate("recuperar_contrasena");
            

            $template->setVars($contenido);
            $this->contenido['CONTENIDO']  = $template->show();
            $this->asigna_contenido($this->contenido);
            return $template->show();
        }

        public function ejecutar_recuperar_contrasena($parametros)
        {
            //echo '1';
            $val = $this->verUsuarioLogin($parametros['login']);
            //echo '2';
            $objResponse = new xajaxResponse();
            if ($val == null)
                $objResponse->addScriptCall('VerMensaje','error',"El Usuario " . $parametros['login'] . " no existe.");
            else
            {
               $mail = new ut_Tool();
               $cuerpo = "Estimado <strong>". $val['nombre'] ."</strong>";
               $cuerpo.= "<br>a continuacion se le envia la informacion de su cuenta<br>";
               $cuerpo.= "<br><strong>Login/Usuario:</strong>".$val['login'];
               $cuerpo.= "<br><strong>Password:</strong>".base64_decode($val['password']);
               $cuerpo.= "<br><p>&nbsp;</p>IMPORTANTE: Por favor no respondas este correo electronico. Cualquier mensaje enviado como respuesta de este envio automatico no nos llegara.";
               $correos = array();
               $correos[] = array('correo' => $val['correo'], 'nombres'=>$val['nombre']);
               if ($mail->EnviarEMail(EMAIL_FROM,$correos,"Asignacion de Usuario de Sistema",$cuerpo))
                    $objResponse->addScriptCall('VerMensaje','exito',"Contrase&ntilde;a enviada con exito ");
               else
                    $objResponse->alert(utf8_encode($cuerpo));
            }
            return $objResponse;

        }

        public function editar($parametros)
        {
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }
            $ut_tool = new ut_Tool();
            $val = $this->verUsuario($parametros[id]);
            $contenido_1['ESTADO'] = $ut_tool->combo_array('estado',array('Activo','Inactivo'),array(1,0),false,$val['estado'],false,false,false,false);
            $contenido_1['ES_ADMIN_CURSO'] = $ut_tool->combo_array('notificacion',array('No','Si'),array(0,1),false,$val['notificacion'], false, false, false, false, 'display:none;');
            $contenido_1['ROL'] = $ut_tool->OptionsCombo('SELECT id_rol, descripcion FROM tb_roles', 'id_rol', 'descripcion' ,$val['id_rol']);
            
            $contenido_1['LOGIN'] = $val['login'];
            $contenido_1['NOMBRE'] = ($val["nombre"]);
            $contenido_1['CEDULA'] = utf8_encode($val["cedula"]);
            $contenido_1['CORREO'] = utf8_encode($val["correo"]);
            $contenido_1['CARGO'] = utf8_encode($val["cargo"]);
            $contenido_1['PASSWORD'] = utf8_encode(base64_decode($val["password"]));
            $contenido_1['PASSWORD2'] = utf8_encode(base64_decode($val["password"]));
            $contenido_1['TELEFONO'] = $val["telefono"];
            $contenido_1['ABREVIATURA'] = $val["abreviatura"];
            $contenido_1['MUNICIPIOS'] = $ut_tool->OptionsCombo('sp_tb_municipios_lst', 'id', 'nombre', $val["id_municipio"], array("@id_estado" => $val["id_estado"]));
            $contenido_1['PARROQUIAS'] = $ut_tool->OptionsCombo('sp_tb_parroquias_lst', 'id', 'nombre', $val["id_parroquia"], array("@id_municipio" => $val["id_municipio"]));
	    $contenido_1['M_OBS'] = $val["m_obs"];
            //print_r($val);
            $contenido_1['M_INCC'] = $val["m_incc"];
            $contenido_1['M_INSP'] = $val["m_insp"];
            $contenido_1['EMPRESAS'] = $ut_tool->OptionsCombo("SELECT id, nombre 
                                                                                FROM $GLOBALS[PREFIJO_BD]empresa"
                                                                        , 'id'
                                                                        , 'nombre', $val["id_empresa"]); 
            $contenido_1['AREAS'] = $ut_tool->OptionsCombo("SELECT id, lugar_evento 
                                                                                FROM $GLOBALS[PREFIJO_BD]levento WHERE id_empresa = $val[id_empresa] "
                                                                        , 'id'
                                                                        , 'lugar_evento', $val["id_area"]); 
            $contenido_1[UND_PLANTA] = NOTACION_PLANTA;
            $template = new Template();
            $template->PATH = PATH_TO_TEMPLATES.'usuarios/';
            $template->setTemplate("formularioNewUpd");
            $template->setVars($contenido_1);

            $contenido['CAMPOS'] = $template->show();

            $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
            $template->setTemplate("formulario");

            $contenido['TITULO_FORMULARIO'] = "Editar Empresa";
            $contenido['TITULO_VOLVER'] = "Volver a Listado de Empresas";
            $contenido['PAGINA_VOLVER'] = "listarEmpresas.php";
            $contenido['DESC_OPERACION'] = "Guardar";
            $contenido['OPC'] = "upd";
            $contenido['ID'] = $val["id"];

            $template->setVars($contenido);
            $objResponse = new xajaxResponse();
            $objResponse->addAssign('contenido',"innerHTML",$template->show());
            $objResponse->addScriptCall("calcHeight");
            return $objResponse;
        }

        public function actualizar($parametros)
        {
            
            unset ($parametros['opc']);
            session_name($GLOBALS[SESSION]);
            session_start();
            $parametros['usuario']= $_SESSION['USERID'];
            $parametros['password'] = base64_encode($parametros['password']);
            $parametros['numempl']= null;
            
            $val = $this->verUsuario($parametros[id]);
            //base64_decode($val["password"])
            $respuesta = $this->modificarUsuario($parametros);
            $this->modificarMeta($parametros);
            $objResponse = new xajaxResponse();
            $mail = new ut_Tool(); 
            if (preg_match("/Ha sido actualizado con exito/",$respuesta ) == true) {
                $objResponse->addAssign('contenido',"innerHTML",$this->indexUsuarios(array('html' => true ,'permiso' => $parametros['permiso'])));
                $cuerpo = "Estimado <strong>". $parametros['nombre']."</strong>";
                $cuerpo.= "<br>Ha sido Modificada la informacion para este usuario en el Sistema de Excelencia SMS, por favor guarde esta informacion<br>";
                $cuerpo.= "<br><strong>Login/Usuario:</strong>".$parametros['login'];
                $cuerpo.= "<br><strong>Password:</strong>".$parametros['password2'];
                $cuerpo.= "<br><br>Para ingresar al sistema visite la siguiente direccion <a href='". APPLICATION_ROOT . "'>". APPLICATION_ROOT . "</a>";
                $cuerpo.= "<br><p>&nbsp;</p>IMPORTANTE: Por favor no respondas este correo electronico. Cualquier mensaje enviado como respuesta de este envio automatico no nos llegara.";
                $correos = array();
                $cc = array();
                //$cc[] = array('correo' => 'victor.gil@masisa.com', 'nombres'=>'Victor Gil');
                //$cc[] = array('correo' => 'jose.gonzalezflores@masisa.com', 'nombres'=>'Jose Gonzalez Flores');
                $correos[] = array('correo' => $parametros['correo'], 'nombres'=>$parametros['nombre']);
                if ((strlen($parametros["password"])>0) && (((($val["password"]) != $parametros['password'])|| ($parametros[login] != $val[login])))){
                    
                     
                    $mail->EnviarEMail(EMAIL_FROM,$correos,"Asignacion de Usuario de Sistema",$cuerpo, array(), $cc);
                }

                $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
            }
            else
                $objResponse->addScriptCall('VerMensaje','error',$respuesta);
            return $objResponse;

        }

        public function actualizar_contrasena($parametros)
        {
            session_name($GLOBALS[SESSION]);
            session_start();
            $objResponse = new xajaxResponse();
            $val = $this->verUsuarioBDG($parametros);
            //$pas_actual = base64_decode($val["password"]);//);
            //echo $pas_actual;
            if (count($val)<=0)
                $objResponse->addScriptCall('VerMensaje','error',utf8_encode("- Contrase&ntilde;a actual incorrecta"));
            else
            {
                unset($parametros['password_actual']);
                unset($parametros['password2']);
                //$parametros['id'] = $_SESSION['USERID'];
                //$parametros['password'] = base64_encode($parametros['password']);
                $this->actualizarContrasena($parametros);
                $objResponse->addScriptCall('VerMensaje','exito',utf8_encode("Cambio de contrase&ntilde;a realizada con exito."));
                //$objResponse->addScript('window.close();');
            }
            
            return $objResponse;

        }

        public function eliminar($parametros)
        {

            $respuesta = $this->eliminarUsuario($parametros);
            $objResponse = new xajaxResponse();
            if (preg_match("/ha sido eliminado con exito/",$respuesta ) == true) {
                $objResponse->addAssign('contenido',"innerHTML",$this->indexUsuarios(array('html' => true ,'permiso' => $parametros['permiso'])));
                $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
            }
            else
                $objResponse->addScriptCall('VerMensaje','error',$respuesta);
            return $objResponse;
        }

        public function buscar($parametros)
        {
            $grid = $this->verListaUsuarios($parametros);
            $html = $grid['tabla'];
            $html .= '<br>
                        <div class="td-table-data" style="cursor:pointer">';
            $html .= $grid['paginado'];
            $html .= '</div>';
            $objResponse = new xajaxResponse();
            $objResponse->addAssign('grid',"innerHTML",$html);
            return $objResponse;
        }
        
        public function listarRolEmpresa($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT e.id empresa,e.nombre, ue.* FROM $GLOBALS[PREFIJO_BD]empresa e
                            LEFT JOIN $GLOBALS[PREFIJO_BD]usuarios_empresa ue ON ue.id_empresa = e.id  and  id_usuario = $atr[id]                                                                            
                            WHERE 1= 1";  
                    //echo $sql;
                    $this->dbl->data = $this->dbl->query($sql, $atr);
        }
        
        public function rol_empresa($parametros)
            {
                session_name($GLOBALS[SESSION]);
                session_start();
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }                

                $this->listarRolEmpresa($parametros);
                $data=$this->dbl->data;
                //print_r($data);
                $item = "";
                $js = "";
                $i = 0;
                if (count($data) > 0){ 
                    $ut_tool = new ut_Tool();
                    $ids = array('Si', 'No'); 
                    //$ids_aux = array(1, 0); 
                    $desc = array('Si', 'No');                                    
                    foreach ($data as $value) {     
                        $i++;                      
                        $item = $item."<tr id='tr-$i'>";
                        $item = $item."<td><input type=\"text\" id=\"empresa_$i\" name=\"empresa_$i\" value='". $value[nombre] . "' size=\"12\" readonly=\"readonly\">"
                                . "<input type=\"hidden\" id=\"id_empresa_$i\" name=\"id_empresa_$i\" value='". $value[empresa] . "' size=\"12\" readonly=\"readonly\"></td>";
                        
                        $parametros[id_rol] = strlen($value[id_rol]) > 0 ? 'Si' : 'No';
                        $value['notificacion'] = strlen($value[notificacion]) > 0 && $value[notificacion] == 1 ? 'Si' : 'No';
                        $item = $item."<td>".$ut_tool->combo_array("activo_$i", $desc, $ids, false, $parametros['id_rol'], false, false, false, false, 'width:60px;margin-right:0px;')."</td>";
                        $item = $item."<td><select id=\"id_rol_$i\"  name=\"id_rol_$i\" class=\"form-box\" style=\"width:160px;margin-right:0px;\">";
                        $value['id_rol'] = strlen($value[id_rol]) > 0 ? $value['id_rol'] : '4';
                        $item = $item.$ut_tool->OptionsCombo('SELECT id_rol, descripcion FROM tb_roles', 'id_rol', 'descripcion' ,$value['id_rol']);
                        $item = $item."</select></td>"; 
                        $item = $item."<td>".$ut_tool->combo_array("notificacion_$i", $desc, $ids, false, $value['notificacion'], false, false, false, false, 'width:60px;margin-right:0px;')."</td>";
                        $checked = $value['predeterminado'] == "1" ? 'checked="checked"' : '';
                        $item = $item."<td align=\"center\"><input type=\"radio\" id=\"predeterminado_$i\" name=\"predeterminado\" value='". $value[empresa] . "' $checked></td>";
                        $item = $item."</tr>"; 
                       
                    }                                   
                }
                $contenido['ITEMS_REPUESTOS'] = $item;
                $contenido['ID_USUARIO'] = $parametros[id];
                $contenido['NUM_ITEMS'] = $i;
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'usuarios/';
                $template->setTemplate("rol_empresa");
                //$template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la LibroNovedades";

                //$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
            
            public function  guardarRolEmpresa($parametros){
                session_name($GLOBALS[SESSION]);
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                //$parametros['id_usuario']= $_SESSION['USERID'];

                $validator = new FormValidator();                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{                    

                    //if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) 
                    {
                        //$params[id_pts] = $parametros[id];
                        $params['id_usuario']= $parametros[id_usuario];
                        $sql = "DELETE FROM $GLOBALS[PREFIJO_BD]usuarios_empresa WHERE id_usuario = $parametros[id_usuario]";
                        $this->dbl->insert_update($sql);
                        for($i=1;$i <= $parametros[num_items] * 1; $i++){                              
                            
                            if (($parametros["activo_$i"])=="Si"){                                
                                //echo $parametros["nro_pts_$i"];
                                $params[id_empresa] =  $parametros["id_empresa_$i"];
                                $params[id_rol] =  $parametros["id_rol_$i"];
                                $params[notificacion] = $parametros["notificacion_$i"];                                
                                $params[predeterminado] = $parametros["predeterminado"];
                                //print_r($params);
                                //echo $parametros["cuerpo_$i"];
                                $this->ingresarRolEmpresa($params);
                            }
                        }                        
                        //$objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje_GB','exito',"Operacion realizada con exito");
                    }
                    //else
                    //    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                return $objResponse;
            }
            public function ingresarRolEmpresa($atr){
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);   
                    $atr[predeterminado] = $atr[predeterminado] == $atr[id_empresa] ? 1 : 0;
                    $atr[notificacion] = $atr[notificacion] == 'Si' ? 1 : 0;
                    $sql = "INSERT INTO $GLOBALS[PREFIJO_BD]usuarios_empresa(id_rol,id_empresa,id_usuario,notificacion,predeterminado)
                            VALUES(
                                $atr[id_rol],$atr[id_empresa],$atr[id_usuario],$atr[notificacion],$atr[predeterminado]
                                )";                    
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    
                    $this->registraTransaccion('Insertar','Ingreso el tbl_guias ' . $atr[descripcion_ano], 'tbl_guias');
                    return "La Guia '$atr[codigo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
    }
?>