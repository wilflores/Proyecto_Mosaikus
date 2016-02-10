<?php 
        import("clases.interfaz.Pagina");
        include_once(dirname(dirname(dirname(__FILE__))).'/clases/clases.php');
        //include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/SQLServer.php');
        class log_transaccion extends Pagina{
        private $templates;
        private $bd;

        public function log_transaccion(){
            parent::__construct();
            import('clases.xajax.xajax');
            import('clases.interfaz.Datagrid');
            include_once(dirname(dirname(dirname(__FILE__))).'/clases/utilidades/utilidades.php');
            $this->asigna_script('log_transaccion/log_transaccion.js');
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

 
        private function listarLog_transaccion($atr){
                $this->operacion("sp_tb_log_transacciones_lst", $atr);
         }

        public function verListaLog_transaccion($parametros){
            $grid= new DataGrid();
            $this->listarLog_transaccion($parametros);
            $data=$this->dbl->data;

            $grid->SetConfiguracion("Log_transaccion", "width='750px' align ='center' border='0' cellspacing='0' cellpadding='0'");
            $config=array(
                array( "width"=>"5%","ValorEtiqueta"=>"Codigo"),
                array( "width"=>"10%", "ValorEtiqueta"=>"Accion"),
                array( "width"=>"30%", "ValorEtiqueta"=>"Descripcion"),
                array( "width"=>"15%", "ValorEtiqueta"=>"Fecha"),
                array( "width"=>"10%", "ValorEtiqueta"=>"Usuario"),
                array( "width"=>"15%","ValorEtiqueta"=>"ip_equipo")

            );

            $func= array();
            $columna_funcion = -1;
            if (strrpos($parametros['permiso'], '1') > 0){
                array_push($config,array( "width"=>"10%", "ValorEtiqueta"=>"Acci&oacute;n"));
                $columna_funcion = 4;
            }
 
            if ($parametros['pag'] == null) $parametros['pag'] = 1;
            $reg_por_pagina = getenv("PAGINACION");
            if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina'];
            $grid->setPaginado($reg_por_pagina);
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            //$grid->setHide(0);
            $grid->setData2("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
            $out['tabla']= $grid->armarTabla();
            if(count($data)>$reg_por_pagina-1){
                $out['paginado']=$grid->setPaginadohtml("verPagina", "document");
            }
            return $out;
        }
        
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarClasificacion($parametros);
            $data=$this->dbl->data;

            $grid->SetConfiguracion("tblVehiculo", "width='650px' align ='center' border='0' cellspacing='0' cellpadding='0'");
            $config=array(
                array( "width"=>"20%","ValorEtiqueta"=>"Codigo"),
                array( "width"=>"40%", "ValorEtiqueta"=>"Descripcion"),
                array( "width"=>"30%","ValorEtiqueta"=>"Modulo")
            );

            
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->hidden[0] = true;
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }

        public function indexLog_transaccion($parametros)
        {
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }
            $grid = $this->verListaLog_transaccion($parametros);
            $contenido['PERMISO_INGRESAR'] = $parametros['permiso'][0] == "1" ? '' : 'display:none;';
            $contenido['TABLA'] = $grid['tabla'];
            $contenido['PAGINADO'] = $grid['paginado'];
            $contenido['OPCIONES_BUSQUEDA'] = "<option value='nombre_usuario'>Login</option><option value='accion'>accion</option>";
            $contenido['JS_NUEVO'] = 'nueva_clasificacion();';
            //$contenido['TITULO_NUEVO'] = 'Agregar Nueva Clasificaci&oacute;n';
            $contenido['ANCHO_GRID'] = 'width:650px;';
            $template = new Template();
            $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
            $template->setTemplate("listar");
            $template->setVars($contenido);
            $this->contenido['CONTENIDO']  = $template->show();
            $this->asigna_contenido($this->contenido);
            return $template->show();
        }

        public function buscar($parametros)
        {
            $grid = $this->verListaLog_transaccion($parametros);
            $html = $grid['tabla'];
            $html .= '<br>
                        <div class="td-table-data" style="cursor:pointer">';
            $html .= $grid['paginado'];
            $html .= '</div>';
            $objResponse = new xajaxResponse();
            $objResponse->addAssign('grid',"innerHTML",$html);
            $objResponse->addScriptCall('calcHeight');
            return $objResponse;
        }

    }
?>