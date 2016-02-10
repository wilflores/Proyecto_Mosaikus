<?php
include_once('clases/bd/Mysql.php');
$nombre_clase=$_POST['clase'];
$nombre_fisico=$_POST['fisico'];
$tabla =$_POST['tabla'];

if ($nombre_clase!='' && $nombre_fisico!='' && $tabla!=''){
// SI VIENEN LOS TRES CAMPOS LLENOS, CONSTRUIMOS TODOS LOS ARCHIVOS
    $dbl = new Mysql();
    //$dbl->conectar();
    $data=$dbl->query("DESCRIBE $tabla");
    //$dbl->data;
    //print_r($data);



    /*******************PARA CLASES******************/
    /*******************PARA CLASES******************/
    /*******************PARA CLASES******************/
    /*******************PARA CLASES******************/
    /*******************PARA CLASES******************/
    /*******************************************************************/
    $campos ="";
    $titulos_grilla="";
    $campos_form="";
    $campos_ver="";
    $campos_validator="";
    $js_date_crear_editar = "";
    $validar_fecha = "";
    $columnas=0;
    foreach($data as $fila ){
        if($fila['Field']!='id'){
            //PARA EL DATAGRID
            $titulos_grilla .="\n               array( \"width\"=>\"10%\",\"ValorEtiqueta\"=>link_titulos(\"".ucfirst($fila['Field'])."\", \"".$fila['Field']."\", \$parametros)),";
            $titulos_grilla_excel .="\n         array( \"width\"=>\"10%\",\"ValorEtiqueta\"=>\"".ucfirst($fila['Field'])."\"),";
            //PARA EL EDIT Y VER
            $pos = strpos($fila['Type'], "(");
            if ($pos === false)
                $campo = $fila['Type'];
            else
                $campo = substr ($fila['Type'], 0, $pos);
            switch ($campo) {
                case 'text':
                case 'varchar':
                case 'varchar(250)':
                case 'char':
                case 'nvarchar':
                case 'nchar':
                    $campos .="            \$contenido_1['".strtoupper($fila['Field'])."'] = (\$val[\"".$fila['Field']."\"]);\n";
                     $campos_form.= "<tr>
                                        <td class=\"title-left\">
                                            <label>".ucfirst($fila['Field'])."</label>                                        
                                            <input type=\"text\" id=\"".$fila['Field']."\" name=\"".$fila['Field']."\" maxlength=\"".$fila['length']."\" size=\"50\" value=\"{".strtoupper($fila['Field'])."}\"  class=\"form-box\">
                                        </td>
                                    </tr>\n";
                    break;
                case 'date':
                    $campos .="            \$contenido_1['".strtoupper($fila['Field'])."'] = (\$val[\"".$fila['Field']."\"]);\n";
                    $campos_form.= "<tr>
                                        <td class=\"title-left\">
                                            <label>".ucfirst($fila['Field'])."</label>                                        
                                            <input type=\"text\" id=\"".$fila['Field']."\" name=\"".$fila['Field']."\" maxlength=\"".$fila['length']."\" size=\"10\" value=\"{".strtoupper($fila['Field'])."}\"  class=\"form-box\">                                                
                                        </td>
                                    </tr>\n";
                    $js_date_crear_editar .= "\$objResponse->addScript(\"$('#".$fila['Field']."').datepicker();\");\n";
                    $validar_fecha .= "\$parametros[\"".$fila['Field']."\"] = formatear_fecha(\$parametros[\"".$fila['Field']."\"]);\n";
                    break;
                default:
                    $campos .="            \$contenido_1['".strtoupper($fila['Field'])."'] = \$val[\"".$fila['Field']."\"];\n";
                    $campos_form.= "<tr>
                                        <td class=\"title-left\">
                                            <label>".ucfirst($fila['Field'])."</label>                                        
                                            <input type=\"text\" id=\"".$fila['Field']."\" name=\"".$fila['Field']."\" maxlength=\"".$fila['length']."\" size=\"20\" value=\"{".strtoupper($fila['Field'])."}\"  class=\"form-box\">
                                        </td>
                                    </tr>\n";
                 
            }
            $campos_ver .="
                            <tr>
                                <td class=\"title-left\">
                                    <label>".ucfirst($fila['Field']).":</label> 
                                        <span>{".strtoupper($fila['Field'])."}</span>
                                </td>
                            </tr>";
            $campos_validator .="           \$validator->addValidation(\"".$fila['Field']."\",\"req\",\"".ucfirst($fila['Field'])." es requerido.\");\n";
            if ($fila['Type']=='integer')
                $campos_validator .="           \$validator->addValidation(\"".$fila['Field']."\",\"num\",\"".ucfirst($fila['Field'])." de la hora debe ser un numero valido.\");\n";
            if ($fila['Type']=='real')
                $campos_validator .="           \$validator->addValidation(\"".$fila['Field']."\",\"real\",\"".ucfirst($fila['Field'])." de la hora debe ser un numero valido.\");\n";
        }
        $columnas++;
    }
    $titulos_grilla = substr($titulos_grilla, 0, strlen($titulos_grilla)-1);
    $titulos_grilla_excel = substr($titulos_grilla_excel, 0, strlen($titulos_grilla_excel)-1);
    /*******************************************************************/


    /*******************************************************************/
    $basico =   "import(\"clases.interfaz.Pagina\");        
        class $nombre_clase extends Pagina{
        private \$templates;
        private \$bd;
        private \$total_registros;

            public function $nombre_clase(){
                parent::__construct();
                \$this->asigna_script('$nombre_fisico/$nombre_fisico.js');
                \$this->dbl = new Mysql();                
                \$this->contenido = array();
            }

            private function operacion(\$sp, \$atr){
                \$param=array();
                \$this->dbl->data = \$this->dbl->query(\$sp, \$param);
            }

    ";
    $end_basico="}";
    /*******************************************************************/
    $sql_ver = "";
    foreach($data as $fila ){
        switch ($fila['Type']) {
//                case 'text':
//                case 'varchar':
//                case 'char':
//                case 'nvarchar':
//                case 'nchar':
//                    break;
                case 'date':
                     $sql_ver .= ",DATE_FORMAT(".$fila['Field'].", '%d/%m/%Y') ".$fila['Field']."\n";
                     break;
                default:     
                     $sql_ver .= "," . $fila['Field']."\n";
        }        
    }
    $sql_insertar_nombres = "";
    $sql_insertar_valores = "";
    foreach($data as $fila ){
        $sql_insertar_nombres .= $fila['Field'] . ",";
        $pos = strpos($fila['Type'], "(");
        if ($pos === false)
            $campo = $fila['Type'];
        else
            $campo = substr ($fila['Type'], 0, $pos);
        switch ($campo) {
                case 'text':
                case 'varchar':
                case 'char':
                case 'nvarchar':
                case 'nchar':
                case 'date':
                    $sql_insertar_valores .= "'\$atr[".$fila['Field']."]',";
                    break;                                     
                default:     
                     $sql_insertar_valores .= "\$atr[".$fila['Field']."],";
        }        
    }
    $sql_modificar = "";
    foreach($data as $fila ){
        $pos = strpos($fila['Type'], "(");
        if ($pos === false)
            $campo = $fila['Type'];
        else
            $campo = substr ($fila['Type'], 0, $pos);
        echo $pos . $campo;
        switch ($campo) {
                case 'text':
                case 'varchar':
                case 'char':
                case 'nvarchar':
                case 'nchar':
                case 'date':
                case 'timestamp':
                    $sql_modificar .= $fila['Field'] . " = '\$atr[".$fila['Field']."]',";
                    break;                                     
                default:     
                    $sql_modificar .= $fila['Field'] . " = \$atr[".$fila['Field']."],";
        }        
    }
    $sql_modificar = substr($sql_modificar, 0, strlen($sql_modificar)-1);
    $sql_insertar_nombres = substr($sql_insertar_nombres, 0, strlen($sql_insertar_nombres)-1);
    $sql_insertar_valores = substr($sql_insertar_valores, 0, strlen($sql_insertar_valores)-1);
    $sql_ver = substr($sql_ver, 1, strlen($sql_ver));
    $storeds = "
             public function ver$nombre_clase(\$id){
                \$atr=array();
                \$sql = \"SELECT $sql_ver
                         FROM $tabla 
                         WHERE id = \$id \"; 
                \$this->operacion(\$sql, \$atr);
                return \$this->dbl->data[0];
            }
            public function ingresar$nombre_clase(\$atr){
                try {
                    \$atr = \$this->dbl->corregir_parametros(\$atr);
                    \$sql = \"INSERT INTO $tabla($sql_insertar_nombres)
                            VALUES(
                                $sql_insertar_valores
                                )\";
                    \$this->dbl->insert_update(\$sql);
                    \$this->registraTransaccion('Insertar','Ingreso el $tabla ' . \$atr[descripcion_ano], '$tabla');
                    return \"El $tabla '\$atr[descripcion_ano]' ha sido ingresado con exito\";
                } catch(Exception \$e) {
                        \$error = \$e->getMessage();                     
                        if (preg_match(\"/ano_escolar_niveles_secciones_nivel_academico_key/\",\$error ) == true) 
                            return \"Ya existe una sección con el mismo nombre.\";                        
                        return \$error; 
                    }
            }
            public function modificar$nombre_clase(\$atr){
                try {
                   \$atr = \$this->dbl->corregir_parametros(\$atr);
                    \$sql = \"UPDATE $tabla SET                            
                                    $sql_modificar
                            WHERE  id = \$atr[id]\";                
                    \$this->dbl->insert_update(\$sql);
                    \$this->registraTransaccion('Modificar','Modifico el $nombre_clase ' . \$atr[descripcion_ano], '$tabla');
                    return \"El $tabla '\$atr[descripcion_ano]' ha sido actualizado con exito\";
                } catch(Exception \$e) {
                        \$error = \$e->getMessage();                     
                        if (preg_match(\"/ano_escolar_niveles_secciones_nivel_academico_key/\",\$error ) == true) 
                            return \"Ya existe una sección con el mismo nombre.\";                        
                        return \$error; 
                    }
            }
             public function listar$nombre_clase(\$atr, \$pag, \$registros_x_pagina){
                    \$atr = \$this->dbl->corregir_parametros(\$atr);
                    \$sql = \"SELECT COUNT(*) total_registros
                         FROM $tabla 
                         WHERE 1 = 1 \";
                    if (strlen(\$atr[valor])>0)
                        \$sql .= \" AND upper(\$atr[campo]) like '%\" . strtoupper(\$atr[valor]) . \"%'\";              
                    \$total_registros = \$this->dbl->query(\$sql, \$atr);
                    \$this->total_registros = \$total_registros[0][total_registros];   
            
                    \$sql = \"SELECT $sql_ver
                            FROM $tabla 
                            WHERE 1 = 1 \";
                    if (strlen(\$atr[valor])>0)
                        \$sql .= \" AND upper(\$atr[campo]) like '%\" . strtoupper(\$atr[valor]) . \"%'\";
                    \$sql .= \" order by \$atr[corder] \$atr[sorder] \";
                    \$sql .= \"LIMIT \" . ((\$pag - 1) * \$registros_x_pagina) . \", \$registros_x_pagina \";
                    \$this->operacion(\$sql, \$atr);
             }
             public function eliminar$nombre_clase(\$atr){
                    try {
                        \$atr = \$this->dbl->corregir_parametros(\$atr);
                        \$respuesta = \$this->dbl->delete(\"$tabla\", \"id = \" . \$atr[id]);
                        return \"ha sido eliminada con exito\";
                    } catch(Exception \$e) {
                        \$error = \$e->getMessage();                     
                        if (preg_match(\"/alumno_inscrito_fk_id_ano_escolar_fkey/\",\$error ) == true) 
                            return \"No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.\";                        
                        return \$error; 
                    }
             }
    ";
    /*******************************************************************/
    $index = "
            public function index$nombre_clase(\$parametros)
            {
                if(!class_exists('Template')){
                    import(\"clases.interfaz.Template\");
                }
                if (\$parametros['corder'] == null) \$parametros['corder']=\"descripcion_ano\";
                if (\$parametros['sorder'] == null) \$parametros['sorder']=\"desc\"; 
                \$grid = \$this->verLista$nombre_clase(\$parametros);
                \$contenido['CORDER'] = \$parametros['corder'];
                \$contenido['SORDER'] = \$parametros['sorder'];
                \$contenido['TABLA'] = \$grid['tabla'];
                \$contenido['PAGINADO'] = \$grid['paginado'];
                \$contenido['OPCIONES_BUSQUEDA'] = \" <option value='campo'>campo</option>\";
                \$contenido['JS_NUEVO'] = 'nuevo_$nombre_clase();';
                \$contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;$nombre_clase';
                \$contenido['TABLA'] = \$grid['tabla'];
                \$contenido['PAGINADO'] = \$grid['paginado'];
                \$contenido['PERMISO_INGRESAR'] = \$parametros['permiso'][0] == \"1\" ? '' : 'display:none;';

                \$template = new Template();
                \$template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                \$template->setTemplate(\"listar\");
                \$template->setVars(\$contenido);
                //\$this->contenido['CONTENIDO']  = \$template->show();
                //\$this->asigna_contenido(\$this->contenido);
                //return \$template->show();
                if (isset(\$parametros['html']))
                    return \$template->show();
                \$objResponse = new xajaxResponse();
                \$objResponse->addAssign('contenido',\"innerHTML\",\$template->show());
                \$objResponse->addAssign('permiso_modulo',\"value\",\$parametros['permiso']);
                \$objResponse->addAssign('modulo_actual',\"value\",\"$nombre_fisico\");
                \$objResponse->addIncludeScript(PATH_TO_JS . '$nombre_fisico/$nombre_fisico.js');
                \$objResponse->addScriptCall(\"calcHeight\");
                return \$objResponse;
            }
        ";
    /*******************************************************************/
    $crear="
            public function crear(\$parametros)
            {
                if(!class_exists('Template')){
                    import(\"clases.interfaz.Template\");
                }

                \$ut_tool = new ut_Tool();
                \$contenido_1   = array();
                \$template = new Template();
                \$template->PATH = PATH_TO_TEMPLATES.'$nombre_fisico/';
                \$template->setTemplate(\"formulario\");
                \$template->setVars(\$contenido_1);
                \$contenido['CAMPOS'] = \$template->show();

                \$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                \$template->setTemplate(\"formulario\");
                \$contenido['TITULO_FORMULARIO'] = \"Crear&nbsp;$nombre_clase\";
                \$contenido['TITULO_VOLVER'] = \"Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;$nombre_clase\";
                \$contenido['PAGINA_VOLVER'] = \"listar$nombre_clase.php\";
                \$contenido['DESC_OPERACION'] = \"Guardar\";
                \$contenido['OPC'] = \"new\";
                \$contenido['ID'] = \"-1\";

                \$template->setVars(\$contenido);
                \$objResponse = new xajaxResponse();               
                \$objResponse->addAssign('contenido-form',\"innerHTML\",\$template->show());
                \$objResponse->addScriptCall(\"calcHeight\");
                \$objResponse->addScriptCall(\"MostrarContenido2\");";
                
                if (strlen($js_date_crear_editar) > 0) $crear.=$js_date_crear_editar;
                $crear.="   return \$objResponse;
            }
    ";
    /*******************************************************************/
    $editar="
            public function editar(\$parametros)
            {
                if(!class_exists('Template')){
                    import(\"clases.interfaz.Template\");
                }
                \$ut_tool = new ut_Tool();
                \$val = \$this->ver$nombre_clase(\$parametros[id]); \n
                $campos
                \$template = new Template();
                \$template->PATH = PATH_TO_TEMPLATES.'$nombre_fisico/';
                \$template->setTemplate(\"formulario\");
                \$template->setVars(\$contenido_1);

                \$contenido['CAMPOS'] = \$template->show();

                \$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                \$template->setTemplate(\"formulario\");

                \$contenido['TITULO_FORMULARIO'] = \"Editar&nbsp;$nombre_clase\";
                \$contenido['TITULO_VOLVER'] = \"Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;$nombre_clase\";
                \$contenido['PAGINA_VOLVER'] = \"listar$nombre_clase.php\";
                \$contenido['DESC_OPERACION'] = \"Guardar\";
                \$contenido['OPC'] = \"upd\";
                \$contenido['ID'] = \$val[\"id\"];

                \$template->setVars(\$contenido);
                \$objResponse = new xajaxResponse();
                \$objResponse->addAssign('contenido-form',\"innerHTML\",\$template->show());
                \$objResponse->addScriptCall(\"calcHeight\");
                \$objResponse->addScriptCall(\"MostrarContenido2\");";
                if (strlen($js_date_crear_editar) > 0) $editar.=$js_date_crear_editar;
    $editar.="  return \$objResponse;
            }
    ";
    /*******************************************************************/
    $ver = "
     public function ver(\$parametros)
            {
                if(!class_exists('Template')){
                    import(\"clases.interfaz.Template\");
                }

                \$val = \$this->ver$nombre_clase(\$parametros[id]);

                $campos;


                \$template = new Template();
                \$template->PATH = PATH_TO_TEMPLATES.'$nombre_fisico/';
                \$template->setTemplate(\"ver$nombre_clase\");
                \$template->setVars(\$contenido_1);
                \$contenido['DATOS'] = \$template->show();
                \$contenido['TITULO'] = \"Datos de la $nombre_clase\";

                \$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                \$template->setTemplate(\"ver\");

                \$template->setVars(\$contenido);
                \$this->contenido['CONTENIDO']  = \$template->show();
                \$this->asigna_contenido(\$this->contenido);

                return \$template->show();
            }
    ";
    /*******************************************************************/
    $guardar="
            public function guardar(\$parametros)
            {
                session_name(\$GLOBALS[SESSION]);
                session_start();
                \$objResponse = new xajaxResponse();
                unset (\$parametros['opc']);
                unset (\$parametros['id']);
                \$parametros['id_usuario']= \$_SESSION['USERID'];

                \$validator = new FormValidator();
                $campos_validator
                if(!\$validator->ValidateForm(\$parametros)){
                        \$error_hash = \$validator->GetErrors();
                        \$mensaje=\"\";
                        foreach(\$error_hash as \$inpname => \$inp_err){
                                \$mensaje.=\"- \$inp_err <br/>\";
                        }
                         \$objResponse->addScriptCall('VerMensaje','error',utf8_encode(\$mensaje));
                }else{
                    $validar_fecha
                    \$respuesta = \$this->ingresar$nombre_clase(\$parametros);

                    if (preg_match(\"/ha sido ingresado con exito/\",\$respuesta ) == true) {
                        \$objResponse->addScriptCall(\"MostrarContenido\");
                        \$objResponse->addScriptCall('VerMensaje','exito',\$respuesta);
                    }
                    else
                        \$objResponse->addScriptCall('VerMensaje','error',\$respuesta);
                }
                return \$objResponse;
            }
    ";
    /*******************************************************************/
    $actualizar = "
            public function actualizar(\$parametros)
            {
                session_name(\$GLOBALS[SESSION]);
                session_start();
                \$objResponse = new xajaxResponse();
                unset (\$parametros['opc']);
                \$parametros['id_usuario']= \$_SESSION['USERID'];

                \$validator = new FormValidator();
                $campos_validator
                if(!\$validator->ValidateForm(\$parametros)){
                        \$error_hash = \$validator->GetErrors();
                        \$mensaje=\"\";
                        foreach(\$error_hash as \$inpname => \$inp_err){
                                \$mensaje.=\"- \$inp_err <br/>\";
                        }
                         \$objResponse->addScriptCall('VerMensaje','error',utf8_encode(\$mensaje));
                }else{
                    $validar_fecha
                    \$respuesta = \$this->modificar$nombre_clase(\$parametros);

                    if (preg_match(\"/ha sido actualizado con exito/\",\$respuesta ) == true) {
                        \$objResponse->addScriptCall(\"MostrarContenido\");
                        \$objResponse->addScriptCall('VerMensaje','exito',\$respuesta);
                    }
                    else
                        \$objResponse->addScriptCall('VerMensaje','error',\$respuesta);
                }
                return \$objResponse;
            }
    ";
    /*******************************************************************/
    $eliminar = "
            public function eliminar(\$parametros)
            {
                \$val = \$this->ver$nombre_clase(\$parametros[id]);
                \$respuesta = \$this->eliminar$nombre_clase(\$parametros);
                \$objResponse = new xajaxResponse();
                if (preg_match(\"/ha sido eliminada con exito/\",\$respuesta ) == true) {
                    \$objResponse->addScriptCall(\"MostrarContenido\");
                    \$objResponse->addScriptCall('VerMensaje','exito',\$respuesta);
                }
                else
                    \$objResponse->addScriptCall('VerMensaje','error',\$respuesta);

            return \$objResponse;
            }
    ";
    /*******************************************************************/
    $buscar = "
                public function buscar(\$parametros)
            {
                \$grid = \$this->verLista$nombre_clase(\$parametros);
                \$html = \$grid['tabla'];
                \$html .= '<br>
                            <div class=\"td-table-data\" style=\"cursor:pointer\">';
                \$html .= \$grid['paginado'];
                \$html .= '</div>';
                \$objResponse = new xajaxResponse();
                \$objResponse->addAssign('grid',\"innerHTML\",\$html);
                return \$objResponse;
            }
        ";
    /*******************************************************************/
    $grilla = "
     public function verLista$nombre_clase(\$parametros){
                \$grid= \"\";
                \$grid= new DataGrid();
                if (\$parametros['pag'] == null) 
                    \$parametros['pag'] = 1;
                \$reg_por_pagina = getenv(\"PAGINACION\");
                if (\$parametros['reg_por_pagina'] != null) \$reg_por_pagina = \$parametros['reg_por_pagina']; 
                \$this->listar$nombre_clase(\$parametros, \$parametros['pag'], \$reg_por_pagina);
                \$data=\$this->dbl->data;

                \$grid->SetConfiguracion(\"tbl$nombre_clase\", \"width='100%' align ='center' border='0' cellspacing='0' cellpadding='0'\");
                \$config=array(
                    $titulos_grilla
                );

                \$func= array();

                \$columna_funcion = -1;
                if (strrpos(\$parametros['permiso'], '1') > 0){
                    array_push(\$config,array(\"width\"=>\"10%\", \"ValorEtiqueta\"=>\"Acci&oacute;n\"));
                    \$columna_funcion = $columnas;
                }
                if (\$parametros['permiso'][1] == \"1\")
                    array_push(\$func,array('nombre'=> 'ver$nombre_clase','imagen'=> \"<img style='cursor:pointer' src='diseno/images/find.png' title='Ver $nombre_clase'>\"));
                if (\$parametros['permiso'][2] == \"1\")
                    array_push(\$func,array('nombre'=> 'editar$nombre_clase','imagen'=> \"<img style='cursor:pointer' src='diseno/images/edit.png' title='Editar $nombre_clase'>\"));
                if (\$parametros['permiso'][3] == \"1\")
                    array_push(\$func,array('nombre'=> 'eliminar$nombre_clase','imagen'=> \"<img style='cursor:pointer' src='diseno/images/remove.png' title='Eliminar $nombre_clase'>\"));
               
                \$grid->setPaginado(\$reg_por_pagina, \$this->total_registros);
                \$grid->SetTitulosTabla(\"td-titulo-tabla-row\", \$config);
                //\$grid->setFuncion(\"en_proceso_inscripcion\", \"enProcesoInscripcion\");
                //\$grid->setAligns(1,\"center\");
                \$grid->hidden = array(0 => true);
    
                \$grid->setData2(\"td-table-data\", \$data, \$func,\$columna_funcion, \$parametros['pag'] );
                \$out['tabla']= \$grid->armarTabla();
                if ((\$parametros['pag'] != 1)  || (\$this->total_registros >= \$reg_por_pagina)){
                    \$out['paginado']=\$grid->setPaginadohtml(\"verPagina\", \"document\");
                }
                return \$out;
            }
    ";
    /*******************************************************************/
$grilla_excel="
        public function exportarExcel(\$parametros){


            \$grid= new DataGrid();
            \$this->listar$nombre_clase(\$parametros, 1, 100000);
            \$data=\$this->dbl->data;

             \$grid->SetConfiguracion(\"tbl$nombre_clase\", \"width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'\");
                \$config=array(
                 $titulos_grilla_excel
              );
                \$columna_funcion =10;
            \$grid->hidden = array(0 => true);
            \$grid->SetTitulosTabla(\"td-titulo-tabla-row\", \$config);
            \$grid->setData2(\"td-table-data\", \$data);

            return \$grid->armarTabla();
        }
";
    /*******************************************************************/
    if (!is_dir("clases/".$nombre_fisico))
        mkdir("clases/".$nombre_fisico, 0700);

    $nombre_temp = $nombre_clase;
    $archivo=$nombre_temp.".php";
    $gestor = fopen("clases/".$nombre_fisico."/".$archivo, "w");
    fwrite($gestor, "<?php\n $basico \n$storeds \n $grilla \n $grilla_excel \n $index \n $crear \n $guardar \n $editar \n $actualizar \n $eliminar \n $buscar \n $ver \n $end_basico?>");
    fclose($gestor);

    /*******************PARA PAGE******************/
    /*******************PARA PAGE******************/
    /*******************PARA PAGE******************/
    /*******************PARA PAGE******************/
    /*******************PARA PAGE******************/
    $pages_listar = "
            function Loading(\$parametros){
               if(isset(\$parametros['import'])){
                             import(\$parametros['import']);
                    }
                eval('\$Obj = new '.\$parametros['objeto'].'();');
                    eval('\$objResponse = \$Obj->'.\$parametros['metodo'].'(\$parametros);');
                    return \$objResponse;
            }

            chdir('..');
            chdir('..');
            include_once('clases/clases.php');
            include_once('configuracion/import.php');
            include_once('configuracion/configuracion.php');
            import('clases.$nombre_fisico.$nombre_clase');


            \$pagina = new $nombre_clase();
            \$pagina->registrar(\"Loading\");
            \$pagina->asigna_permiso(\$_POST['permiso']);
            \$pagina->index$nombre_clase(array('permiso' => \$_POST['permiso']));
            \$pagina->show();


    ";
    $pages_exportar_excel = "<?php
    header('Content-type: application/vnd.ms-excel');
    header(\"Content-Disposition: attachment; filename=$nombre_clase.xls\");
    header(\"Pragma: no-cache\");
    header(\"Expires: 0\");

    ?>
    <?php

            chdir('..');
            chdir('..');
            include_once('clases/clases.php');
            include_once('configuracion/import.php');
            include_once('configuracion/configuracion.php');
            import('clases.$nombre_fisico.$nombre_clase');
            \$pagina = new $nombre_clase();


    ?>

    <html>
    <head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
    </head>
    <body style=\"background-color:transparent\" >

    <?
    echo \$pagina->exportarExcel(\$_GET);

    ?>
    </body>
    </html>
    ";
$pages_ver="<?php
    function Loading(\$parametros){
	   if(isset(\$parametros['import'])){
			 import(\$parametros['import']);
		}
	    eval('\$Obj = new '.\$parametros['objeto'].'();');
		eval('\$objResponse = \$Obj->'.\$parametros['metodo'].'(\$parametros);');
		return \$objResponse;
	}

	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.$nombre_fisico.$nombre_clase');

	\$pagina = new $nombre_clase();
	\$pagina->registrar(\"Loading\");
	\$pagina->ver(array('id' => \$_GET['id']));
	\$pagina->show();

?>
";
    /*******************************************************************/
    if (!is_dir("pages/".$nombre_fisico))
        mkdir("pages/".$nombre_fisico, 0700);

    $nombre_temp = $nombre_clase;
    $archivo=$nombre_temp.".php";
    $gestor = fopen("pages/".$nombre_fisico."/listar".$archivo, "w");
    fwrite($gestor, "<?php\n $pages_listar ?>");
    fclose($gestor);

    $gestor = fopen("pages/".$nombre_fisico."/exportarExcel.php", "w");
    fwrite($gestor, $pages_exportar_excel);
    fclose($gestor);

    $gestor = fopen("pages/".$nombre_fisico."/ver".$archivo, "w");
    fwrite($gestor, $pages_ver);
    fclose($gestor);
    /*******************PARA JS******************/
    /*******************PARA JS******************/
    /*******************PARA JS******************/
    /*******************PARA JS******************/
    $texto_js="
    function nuevo_$nombre_clase(){
            array = new XArray();
            array.setObjeto('$nombre_clase','crear');
            array.addParametro('import','clases.$nombre_fisico.$nombre_clase');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        array = new XArray();
        if (doc.getElementById(\"opc\").value == \"new\")
            array.setObjeto('$nombre_clase','guardar');
        else
            array.setObjeto('$nombre_clase','actualizar');
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.getForm('idFormulario');
        array.addParametro('import','clases.$nombre_fisico.$nombre_clase');
        xajax_Loading(array.getArray());

    }

    function editar$nombre_clase(id){
        array = new XArray();
        array.setObjeto('$nombre_clase','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.$nombre_fisico.$nombre_clase');
        xajax_Loading(array.getArray());
    }


    function eliminar$nombre_clase(id){
        if(confirm(\"¿Desea Eliminar el $nombre_clase Seleccionado?\")){
            array = new XArray();
            array.setObjeto('$nombre_clase','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.$nombre_fisico.$nombre_clase');
            xajax_Loading(array.getArray());
        }
    }
    function verPagina(pag,doc){
        array = new XArray();
        if (doc== null)
        {
             $('form')[0].reset();             
        }
        array.getForm('busquedaFrm'); 
        if ((isNaN(document.getElementById(\"reg_por_pag\").value) == true) || (parseInt(document.getElementById(\"reg_por_pag\").value) <= 0)){
            array.addParametro('reg_por_pagina', 10);
            document.getElementById(\"reg_por_pag\").value = 10
        }
        else
        {
            array.addParametro('reg_por_pagina', document.getElementById(\"reg_por_pag\").value);
        }
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('$nombre_clase','buscar');
        array.addParametro('import','clases.$nombre_fisico.$nombre_clase');
        xajax_Loading(array.getArray());
    }

    function ver$nombre_clase(id){
        var src = 'pages/' +  document.getElementById(\"modulo_actual\").value + '/ver$nombre_clase.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $(\"#ver_ficha_trabajador\").trigger('click');        
    }
    ";

    if (!is_dir("js/".$nombre_fisico))
        mkdir("js/".$nombre_fisico, 0700);

    $archivo=$nombre_fisico.".js";
    $gestor = fopen("js/".$nombre_fisico."/".$archivo, "w");
    fwrite($gestor, $texto_js);
    fclose($gestor);


    /*******************PARA LOS TEMPLATES******************/
    /*******************PARA LOS TEMPLATES******************/
    /*******************PARA LOS TEMPLATES******************/
    /*******************PARA LOS TEMPLATES******************/
    /*******************PARA LOS TEMPLATES******************/
    if (!is_dir("diseno/templates/".$nombre_fisico))
        mkdir("diseno/templates/".$nombre_fisico, 0700);

    $archivo="formulario.tpl";
    $gestor = fopen("diseno/templates/".$nombre_fisico."/".$archivo, "w");
    fwrite($gestor, $campos_form);
    fclose($gestor);

    $archivo="ver".$nombre_clase.".tpl";
    $gestor = fopen("diseno/templates/".$nombre_fisico."/".$archivo, "w");
    fwrite($gestor, $campos_ver);
    fclose($gestor);

echo "SE CREARON LOS TEMPLATES, PAGES, JS Y CLASES PARA: ".$nombre_clase;
}// FIN DEL SCAFFOLD
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="verify-v1" content="o9+SkXLM06qvwKNgzXHX/Wa3opKllp3AAGSN842/3aI=" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <link type="text/css" href="diseno/css/estilos.css" rel="stylesheet" />
</head>
<body onload="">
    <form id="busquedaFrm" method="post">
            <table >
                <tr>
                        <td class='td-titulo-tabla' align='center'>Clase</td>
                        <td class='td-titulo-tabla' align='center'>Ubicacion Fisica</td> 
                        <td class='td-titulo-tabla' align='center'>Tabla</td>
                </tr>
                <tr>
                    <td class='td-table-data-alt'>
                        <input type='text' name='clase' size="20" id='clase' class="form-box" value="<?php echo $nombre_clase;?>"/>
                    </td>
                    <td class='td-table-data-alt'>
                        <input type='text' name='fisico' id='fisico' class="form-box" value="<?php echo $nombre_fisico;?>"/>
                    </td>
                    <td class='td-table-data-alt'>
                        <input type='text' name='tabla' id='tabla' class="form-box" value="<?php echo $tabla;?>"/>
                    </td>
                </tr>
                <tr> <td class='td-table-data-alt' colspan="3" align="center">
                        <input class="form-button" type='submit' value='Scaffold' />
                </td> </tr>
            </table>
        </form>

</body>
</html>



