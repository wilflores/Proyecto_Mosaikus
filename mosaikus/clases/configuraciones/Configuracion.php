<?php
 import("clases.interfaz.Pagina");
        include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/SQLServer.php');
        class Configuracion extends Pagina{
        private $templates;
        private $bd;

            public function Configuracion(){
                parent::__construct();
                $this->asigna_script('configuraciones/configuraciones.js');
                $this->dbl = new ut_Database();
                $this->dbl->conectar();
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                if(is_array($atr)){
                    foreach ($atr as $key => $value) {
                        $param["@".$key]=$value;
                    }
                }
                $this->dbl->exe($sp, $param);
            }

     

             public function verConfiguracion($id){
                $atr=array();
                $atr['id']=$id;
                $this->operacion("sp_tb_configuraciones_con_id", $atr);
                return $this->dbl->data[0];
            }
            public function ingresarConfiguracion($atr){
                $this->operacion("sp_tb_configuraciones_ins", $atr);
                $this->registraTransaccion('Insertar','Ingreso el parametro: ' . $atr['nombre']);
                return utf8_encode($this->dbl->data[0][0]);
            }
            public function modificarConfiguracion($atr){
                $this->operacion("sp_tb_configuraciones_act", $atr);
                $this->registraTransaccion('Modificar','Modifico el parametro: ' . $atr['nombre']);
                 return utf8_encode($this->dbl->data[0][0]);
            }
             public function listarConfiguracion($atr){
                    $this->operacion("sp_tb_configuraciones_con", $atr);
             }
             public function eliminarConfiguracion($atr){
                $this->operacion("sp_tb_configuraciones_eli", $atr);
                return utf8_encode($this->dbl->data[0][0]);
             }
     
 
     public function verListaConfiguracion($parametros){
                $grid= "";
                $grid= new DataGrid();
                $this->listarConfiguracion($parametros);
                $data=$this->dbl->data;

                $grid->SetConfiguracion("tblConfiguracion", "width='100%' align ='center' border='0' cellspacing='0' cellpadding='0'");
                $config=array(
                    
               array( "width"=>"20%","ValorEtiqueta"=>"Nombre"),
               array( "width"=>"30%","ValorEtiqueta"=>"Descripci&oacute;n"),
               array( "width"=>"10%","ValorEtiqueta"=>"Valor"),
               array( "width"=>"10%","ValorEtiqueta"=>"Tipo Valor"),
                );

                $func= array();

                $columna_funcion = -1;
                if (strrpos($parametros['permiso'], '1') > 0){
                    array_push($config,array("width"=>"10%", "ValorEtiqueta"=>"Acci&oacute;n"));
                    $columna_funcion = 5;
                }
                if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarConfiguracion','imagen'=> "<img style='cursor:pointer' src='diseno/images/edit.png' title='Editar Configuracion'>"));
                if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarConfiguracion','imagen'=> "<img style='cursor:pointer' src='diseno/images/remove.png' title='Eliminar Configuracion'>"));

                if ($parametros['pag'] == null) $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina'];
                $grid->setPaginado_new($reg_por_pagina);
                $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
                $grid->setHide(0);
                $grid->setData2("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                if(count($data)>$reg_por_pagina-1){
                    $out['paginado']=$grid->setPaginadohtml("verPagina", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarConfiguracion($parametros);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblConfiguracion", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config=array(
                 
               array( "width"=>"20%","ValorEtiqueta"=>"Nombre"),
               array( "width"=>"30%","ValorEtiqueta"=>"Descripci&oacute;n"),
               array( "width"=>"10%","ValorEtiqueta"=>"Valor"),
               array( "width"=>"10%","ValorEtiqueta"=>"Tipo Valor"),
              );
                $columna_funcion =10;
            $grid->hidden = array(0 => true);
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexConfiguracion($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $grid = $this->verListaConfiguracion($parametros);
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='nombre'>Nombre</option>
                                                    <option value='descripcion'>Descripci&oacute;n</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Configuracion();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Configuracion';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $parametros['permiso'][0] == "1" ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
//                $this->contenido['CONTENIDO']  = $template->show();
//                $this->asigna_contenido($this->contenido);
//                return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","configuraciones");
                 
                $objResponse->addIncludeScript(PATH_TO_JS . 'configuraciones/configuraciones.js');
                $objResponse->addScriptCall("calcHeight");                              
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $ut_tool = new ut_Tool();
                $contenido_1['TIPOS']   = $ut_tool->combo_array("tipo_valor", array("Texto", "Numerico" , "Fecha"), array("Texto", "Numerico" , "Fecha"));
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'configuraciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;Configuracion";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Configuracion";
                $contenido['PAGINA_VOLVER'] = "listarConfiguracion.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                return $objResponse;
            }
     
 
            public function guardar($parametros)
            {
                session_name($GLOBALS[SESSION]);
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                unset ($parametros['id']);
                $parametros['id_user']= $_SESSION['USERID'];

                $validator = new FormValidator();
                $validator->addValidation("nombre","req","El nombre es requerido");
                $validator->addValidation("descripcion","req","La descripci&oacute;n es requerida");
                $validator->addValidation("valor","req","El valor es requerido");  

                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $respuesta = $this->ingresarConfiguracion($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addAssign('contenido',"innerHTML",$this->indexConfiguracion(array('html' => true ,'permiso' => $parametros['permiso'])));
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verConfiguracion($parametros[id]); 

                $contenido_1['TIPOS']   = $ut_tool->combo_array("tipo_valor", array("Texto", "Numerico" , "Fecha"), array("Texto", "Numerico" , "Fecha"), false, $val["tipo_valor"]);
                $contenido_1['NOMBRE'] = utf8_encode($val["nombre"]);
                $contenido_1['DESCRIPCION'] = utf8_encode($val["descripcion"]);
                $contenido_1['VALOR'] = utf8_encode($val["valor"]);
                $contenido_1['TIPO_VALOR'] = utf8_encode($val["tipo_valor"]);
                $contenido_1['FECHA'] = $val["fecha"];
                $contenido_1['ID_USER'] = $val["id_user"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'configuraciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Configuracion";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Configuracion";
                $contenido['PAGINA_VOLVER'] = "listarConfiguracion.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                return $objResponse;
            }
     
 
            public function actualizar($parametros)
            {
                session_name($GLOBALS[SESSION]);
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                $parametros['id_user']= $_SESSION['USERID'];

                $validator = new FormValidator();
                $validator->addValidation("nombre","req","El nombre es requerido");
                $validator->addValidation("descripcion","req","La descripci&oacute;n es requerida");
                $validator->addValidation("valor","req","El valor es requerido");

                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $respuesta = $this->modificarConfiguracion($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $objResponse->addAssign('contenido',"innerHTML",$this->indexConfiguracion(array('html' => true ,'permiso' => $parametros['permiso'])));
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verConfiguracion($parametros[id]);
                $respuesta = $this->eliminarConfiguracion($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminado con exito/",$respuesta ) == true) {
                    $this->registraTransaccion('Eliminar','Elimino el parametro: '. $val["nombre"]);
                    $respuesta = 'El parametro "' . utf8_encode($val['nombre']) . '"' . $respuesta;
                    $objResponse->addAssign('contenido',"innerHTML",$this->indexConfiguracion(array('html' => true ,'permiso' => $parametros['permiso'])));
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);

            return $objResponse;
            }
     
 
                public function buscar($parametros)
            {
                $grid = $this->verListaConfiguracion($parametros);
                $html = $grid['tabla'];
                $html .= '<br>
                            <div class="td-table-data" style="cursor:pointer">';
                $html .= $grid['paginado'];
                $html .= '</div>';
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$html);
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verConfiguracion($parametros[id]);

                            $contenido_1['NOMBRE'] = utf8_encode($val["nombre"]);
            $contenido_1['DESCRIPCION'] = utf8_encode($val["descripcion"]);
            $contenido_1['VALOR'] = utf8_encode($val["valor"]);
            $contenido_1['TIPO_VALOR'] = utf8_encode($val["tipo_valor"]);
            $contenido_1['FECHA'] = $val["fecha"];
            $contenido_1['ID_USER'] = $val["id_user"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'configuraciones/';
                $template->setTemplate("verConfiguracion");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Configuracion";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>