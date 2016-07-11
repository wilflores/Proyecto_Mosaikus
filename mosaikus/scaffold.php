<?php
include_once('clases/bd/Mysql.php');
$nombre_clase=$_POST['clase'];
$nombre_fisico=$_POST['fisico'];
$tabla =$_POST['tabla'];
$modulo =$_POST['modulo'];

if ($nombre_clase!='' && $nombre_fisico!='' && $tabla!=''){
// SI VIENEN LOS TRES CAMPOS LLENOS, CONSTRUIMOS TODOS LOS ARCHIVOS
    $dbl = new Mysql('santateresa', 'root', '123456');
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
    $campos_form_busq="";
    $campos_form_colums="";
    $campos_ver="";
    $campos_validator="";
    $js_date_crear_editar = "";
    $validar_fecha = "";
    $columnas=1;
    $columnas_mostrar = '';
    $campos_log_ant = $campos_log_new = '';
    $filtro_listar = '';
    foreach($data as $fila ){
        $sql = "INSERT INTO mos_nombres_campos(nombre_campo, texto, modulo, placeholder) VALUES( '$fila[Field]','$fila[Field]',$modulo,'$fila[Field]')";
        $dbl->insert_update($sql);
        if($fila['Field']!='id'){
            //PARA EL DATAGRID
            $titulos_grilla .="\n               array( \"width\"=>\"10%\",\"ValorEtiqueta\"=>link_titulos(\$this->nombres_columnas[".$fila['Field']."], \"".$fila['Field']."\", \$parametros)),";
            $titulos_grilla_excel .="\n         array( \"width\"=>\"10%\",\"ValorEtiqueta\"=>htmlentities(\$this->nombres_columnas[".$fila['Field']."], ENT_QUOTES, \"UTF-8\")),";
            //PARA EL EDIT Y VER
            $pos = strpos($fila['Type'], "(");
            if ($pos === false)
                $campo = $fila['Type'];
            else
                $campo = substr ($fila['Type'], 0, $pos);
            switch ($campo) {
                case 'text':                
                    $campos .="            \$contenido_1['".strtoupper($fila['Field'])."'] = (\$val[\"".$fila['Field']."\"]);\n";
                     $campos_form.= "<div class=\"form-group\">
                                        <label for=\"".$fila['Field']."\" class=\"col-md-4 control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                        <div class=\"col-md-10\">                                          
                                          <textarea class=\"form-control\" rows=\"3\" id=\"".$fila['Field']."\" name=\"".$fila['Field']."\" data-validation=\"required\" placeholder=\"{N_".strtoupper($fila['Field'])."}\">{".strtoupper($fila['Field'])."}</textarea>
                                      </div>                                
                                  </div>\n";
                     $campos_form_busq .= "<div class=\"form-group\">
                                  <label for=\"".$fila['Field']."\" class=\"control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                  
                                    <input type=\"text\" class=\"form-control\" id=\"b-".$fila['Field']."\" name=\"b-".$fila['Field']."\" placeholder=\"{N_".strtoupper($fila['Field'])."}\" />
                                                             
                            </div>\n";
                     $campos_form_colums .="<div class=\"checkbox\">                                  
                                     
                                      <label >
                                          <input type=\"checkbox\" name=\"SelectAcc\" id=\"SelectAcc\" value=\"".($columnas - 1) ."\" class=\"checkbox-mos-col\" checked=\"checked\">   &nbsp;
                                      {N_".strtoupper($fila['Field'])."}</label>
                                  
                            </div>";
                     
                    $filtro_listar .= "            if (strlen(\$atr[\"b-".$fila['Field']."\"])>0)
                        \$sql .= \" AND upper(".$fila['Field'].") like '%\" . strtoupper(\$atr[\"b-".$fila['Field']."\"]) . \"%'\";\n"; 
                    break;
                case 'blob':
                case 'varchar':
                case 'varchar(250)':
                case 'char':
                case 'nvarchar':
                case 'nchar':
                    $campos .="            \$contenido_1['".strtoupper($fila['Field'])."'] = (\$val[\"".$fila['Field']."\"]);\n";
                     $campos_form.= "<div class=\"form-group\">
                                        <label for=\"".$fila['Field']."\" class=\"col-md-4 control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                        <div class=\"col-md-10\">
                                          <input type=\"text\" class=\"form-control\" value=\"{".strtoupper($fila['Field'])."}\" id=\"".$fila['Field']."\" name=\"".$fila['Field']."\" placeholder=\"{N_".strtoupper($fila['Field'])."}\" data-validation=\"required\"/>
                                      </div>                                
                                  </div>\n";
                     $campos_form_busq .= "<div class=\"form-group\">
                                  <label for=\"".$fila['Field']."\" class=\"control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                  
                                    <input type=\"text\" class=\"form-control\" id=\"b-".$fila['Field']."\" name=\"b-".$fila['Field']."\" placeholder=\"{N_".strtoupper($fila['Field'])."}\" />
                                                          
                            </div>\n";
                     $campos_form_colums .="<div class=\"checkbox\">
                                      
                                      <label >
                                          <input type=\"checkbox\" name=\"SelectAcc\" id=\"SelectAcc\" value=\"".($columnas - 1) ."\" class=\"checkbox-mos-col\" checked=\"checked\">   &nbsp;
                                      {N_".strtoupper($fila['Field'])."}</label>
                                  
                            </div>";
                     
                    $filtro_listar .= "            if (strlen(\$atr[\"b-".$fila['Field']."\"])>0)
                        \$sql .= \" AND upper(".$fila['Field'].") like '%\" . strtoupper(\$atr[\"b-".$fila['Field']."\"]) . \"%'\";\n"; 
                    break;
                case 'date':
                    $campos .="            \$contenido_1['".strtoupper($fila['Field'])."'] = (\$val[\"".$fila['Field']."\"]);\n";
                    $campos_form.= "<div class=\"form-group\">
                                        <label for=\"".$fila['Field']."\" class=\"col-md-4 control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                        <div class=\"col-md-10\">
                                          <input type=\"text\" class=\"form-control\" style=\"width: 120px;\" value=\"{".strtoupper($fila['Field'])."}\" id=\"".$fila['Field']."\" name=\"".$fila['Field']."\" placeholder=\"dd/mm/yyyy\"  data-validation=\"required\"/>
                                      </div>                                
                                  </div>\n";
                     $campos_form_busq .= "<div class=\"form-group\">
                                  <label for=\"".$fila['Field']."\" class=\"control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                  <div class=\"row\">
                                        <div class=\"col-xs-12\">
                                            <label>Desde</label>
                                            <input type=\"text\" class=\"form-control\" id=\"b-".$fila['Field']."-desde\" name=\"b-".$fila['Field']."-desde\" placeholder=\"dd/mm/yyyy\"  />
                                        </div>   
                                        <div class=\"col-xs-12\">
                                            <label>Hasta</label>
                                          <input type=\"text\" class=\"form-control\" id=\"b-".$fila['Field']."-hasta\" name=\"b-".$fila['Field']."-hasta\" placeholder=\"dd/mm/yyyy\"  />
                                        </div> 
                                  </div>
                            </div>\n";
                     $campos_form_colums .="<div class=\"checkbox\">
                                  
                                      <label >
                                          <input type=\"checkbox\" name=\"SelectAcc\" id=\"SelectAcc\" value=\"".($columnas - 1) ."\" class=\"checkbox-mos-col\" checked=\"checked\">   &nbsp;
                                      {N_".strtoupper($fila['Field'])."}</label>
                                  
                            </div>";
                    $filtro_listar .= "             if (strlen(\$atr['b-".$fila['Field']."-desde'])>0)                        
                    {
                        \$atr['b-".$fila['Field']."-desde'] = formatear_fecha(\$atr['b-".$fila['Field']."-desde']);                        
                        \$sql .= \" AND ".$fila['Field']." >= '\" . (\$atr['b-".$fila['Field']."-desde']) . \"'\";                        
                    }
                    if (strlen(\$atr['b-".$fila['Field']."-hasta'])>0)                        
                    {
                        \$atr['b-".$fila['Field']."-hasta'] = formatear_fecha(\$atr['b-".$fila['Field']."-hasta']);                        
                        \$sql .= \" AND ".$fila['Field']." <= '\" . (\$atr['b-".$fila['Field']."-hasta']) . \"'\";                        
                    }\n";
                    $js_date_crear_editar .= "\$objResponse->addScript(\"$('#".$fila['Field']."').datepicker();\");\n";
                    $validar_fecha .= "\$parametros[\"".$fila['Field']."\"] = formatear_fecha(\$parametros[\"".$fila['Field']."\"]);\n";
                    break;
                default:
                    $campos .="            \$contenido_1['".strtoupper($fila['Field'])."'] = \$val[\"".$fila['Field']."\"];\n";
                    $campos_form.= "<div class=\"form-group\">
                                        <label for=\"".$fila['Field']."\" class=\"col-md-4 control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                        <div class=\"col-md-10\">
                                          <input type=\"text\" class=\"form-control\" value=\"{".strtoupper($fila['Field'])."}\" id=\"".$fila['Field']."\" name=\"".$fila['Field']."\" placeholder=\"{N_".strtoupper($fila['Field'])."}\"  data-validation=\"required\"/>
                                      </div>                                
                                  </div>\n";
                     $campos_form_busq .= "<div class=\"form-group\">
                                  <label for=\"".$fila['Field']."\" class=\"control-label\">{N_".strtoupper($fila['Field'])."}</label>
                                  
                                    <input type=\"text\" class=\"form-control\" id=\"b-".$fila['Field']."\" name=\"b-".$fila['Field']."\" placeholder=\"{N_".strtoupper($fila['Field'])."}\"/>
                                                             
                            </div>\n";
                     $campos_form_colums .="<div class=\"checkbox\">
                                    
                                      <label >
                                          <input type=\"checkbox\" name=\"SelectAcc\" id=\"SelectAcc\" value=\"".($columnas - 1) ."\" class=\"checkbox-mos-col\" checked=\"checked\">   &nbsp;
                                      {N_".strtoupper($fila['Field'])."}</label>
                                  
                            </div>";
                     $filtro_listar .= "             if (strlen(\$atr[\"b-".$fila['Field']."\"])>0)
                        \$sql .= \" AND ".$fila['Field']. " = '\". \$atr[\"b-".$fila['Field']."\"] . \"'\";\n"; 

                 
            }
            $campos_log_new .= "".ucwords(str_replace('_',' ',$fila['Field'])).": \\'\$atr[$fila[Field]]\\', ";
            $campos_log_ant .= "".ucwords(str_replace('_',' ',$fila['Field'])).": \\'\$val[$fila[Field]]\\', ";
            $campos_ver .="
                            <tr>
                                <td width=\"30%\" class=\"title\">".ucfirst($fila['Field']).":</td>
                                <td class=\"td-table-data\">{".strtoupper($fila['Field'])."}</td>
                            </tr>";
            //$campos_validator .="           \$validator->addValidation(\"".$fila['Field']."\",\"req\",\"".ucfirst($fila['Field'])." es requerido.\");\n";
            //if ($fila['Type']=='integer')
            //    $campos_validator .="           \$validator->addValidation(\"".$fila['Field']."\",\"num\",\"".ucfirst($fila['Field'])." de la hora debe ser un numero valido.\");\n";
            //if ($fila['Type']=='real')
            //    $campos_validator .="           \$validator->addValidation(\"".$fila['Field']."\",\"real\",\"".ucfirst($fila['Field'])." de la hora debe ser un numero valido.\");\n";
        }
        else{
            $titulos_grilla .="\n               array( \"width\"=>\"10%\",\"ValorEtiqueta\"=>link_titulos(\$this->nombres_columnas[".$fila['Field']."], \"".$fila['Field']."\", \$parametros)),";
            $titulos_grilla_excel .="\n         array( \"width\"=>\"10%\",\"ValorEtiqueta\"=>htmlentities(\$this->nombres_columnas[".$fila['Field']."], ENT_QUOTES, \"UTF-8\")),";

        }
        $columnas_mostrar .= $columnas . '-';
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
        private \$parametros;
        private \$nombres_columnas;
        private \$placeholder;
        private \$id_org_acceso;
        private \$id_org_acceso_explicito;
        private \$per_crear;
        private \$per_editar;
        private \$per_eliminar;
        private \$restricciones;
        
            
            public function $nombre_clase(){
                parent::__construct();
                \$this->asigna_script('$nombre_fisico/$nombre_fisico.js');                                             
                \$this->dbl = new Mysql(\$this->encryt->Decrypt_Text(\$_SESSION[BaseDato]), \$this->encryt->Decrypt_Text(\$_SESSION[LoginBD]), \$this->encryt->Decrypt_Text(\$_SESSION[PwdBD]) );
                \$this->parametros = \$this->nombres_columnas = \$this->placeholder = array();
                //\$this->id_org_acceso = \$this->id_org_acceso_explicito = array();
                \$this->per_crear = \$this->per_editar = \$this->per_eliminar = 'N';
                \$this->contenido = array();
            }

            private function operacion(\$sp, \$atr){
                \$param=array();
                \$this->dbl->data = \$this->dbl->query(\$sp, \$param);
            }
            
            private function cargar_parametros(){
                \$sql = \"SELECT cod_parametro, espanol FROM mos_parametro WHERE cod_categoria = '3' AND vigencia = 'S' ORDER BY cod_parametro\";
                \$this->parametros = \$this->dbl->query(\$sql, array());
            }
            
            private function cargar_nombres_columnas(){
                \$sql = \"SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = $modulo\";
                \$nombres_campos = \$this->dbl->query(\$sql, array());
                foreach (\$nombres_campos as \$value) {
                    \$this->nombres_columnas[\$value[nombre_campo]] = \$value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                \$sql = \"SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = $modulo\";
                \$nombres_campos = \$this->dbl->query(\$sql, array());
                foreach (\$nombres_campos as \$value) {
                    \$this->placeholder[\$value[nombre_campo]] = \$value[placeholder];
                }
                
            }

            /**
            * Activa los nodos donde se tiene acceso
            
           public function cargar_acceso_nodos(\$parametros){
               if (strlen(\$parametros[cod_link])>0){
                   if(!class_exists('mos_acceso')){
                       import(\"clases.mos_acceso.mos_acceso\");
                   }
                   \$acceso = new mos_acceso();
                   \$data_ids_acceso = \$acceso->obtenerArbolEstructura(\$_SESSION[CookIdUsuario],\$parametros[cod_link],\$parametros[modo]);                   
                   foreach (\$data_ids_acceso as \$value) {
                       \$this->id_org_acceso[\$value[id]] = \$value;
                   }                                            
               }
           }
            */
           /**
            * Activa los nodos donde se tiene acceso
            
           private function cargar_acceso_nodos_explicito(\$parametros){
               if (strlen(\$parametros[cod_link])>0){
                   if(!class_exists('mos_acceso')){
                       import(\"clases.mos_acceso.mos_acceso\");
                   }
                   \$acceso = new mos_acceso();
                   \$data_ids_acceso = \$acceso->obtenerNodosArbol(\$_SESSION[CookIdUsuario],\$parametros[cod_link],\$parametros[modo]);                   
                   foreach (\$data_ids_acceso as \$value) {
                       \$this->id_org_acceso_explicito[\$value[id]] = \$value;
                   }                                            
               }
           }
           */
            /**
             * Busca los permisos que tiene el usuario en el modulo
             */
            private function cargar_permisos(\$parametros){
                if (strlen(\$parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import(\"clases.mos_acceso.mos_acceso\");
                    }
                    \$acceso = new mos_acceso();
                    \$data_permisos = \$acceso->obtenerPermisosModulo(\$_SESSION[CookIdUsuario],\$parametros[cod_link],\$parametros['b-id_organizacion']);                    
                    foreach (\$data_permisos as \$value) {
                        if (\$value[nuevo] == 'S'){
                            \$this->per_crear =  'S';
                            break;
                        }
                    }                                               
                    foreach (\$data_permisos as \$value) {
                        if (\$value[modificar] == 'S'){
                            \$this->per_editar =  'S';
                            break;
                        }
                    } 
                    foreach (\$data_permisos as \$value) {
                        if (\$value[eliminar] == 'S'){
                            \$this->per_eliminar =  'S';
                            break;
                        }
                    } 
                }
            }
            
            public function colum_admin(\$tupla)
            {
                \$html = \"&nbsp;\";
                if (strlen(\$tupla[id_registro])<=0){
                    if(\$this->per_editar == 'S'){
                        \$html .= '<a onclick=\"javascript:editar$nombre_clase(\''.\$tupla[id].'\' );\">
                                    <i style=\"cursor:pointer\" class=\"icon icon-edit\"  title=\"Editar $nombre_clase\" style=\"cursor:pointer\"></i>
                                </a>';
                    }                
                    if(\$this->per_eliminar == 'S'){
                        \$html .= '<a onclick=\"javascript:eliminar$nombre_clase(\''.\$tupla[id].'\');;\">
                                    <i style=\"cursor:pointer\" class=\"icon icon-remove\" title=\"Eliminar $nombre_clase\" style=\"cursor:pointer\"></i>
                                </a>';
                    }
                }
                return \$html;
            }
            
            public function colum_admin_arbol(\$tupla)
            {                
                if (\$this->restricciones->id_org_acceso_explicito[\$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    \$html = \"<a href=\\\"#\\\" onclick=\\\"javascript:editar$nombre_clase('\". \$tupla[id] . \"');\\\"  title=\\\"Editar $nombre_clase\\\">                            
                                <i class=\\\"icon icon-edit\\\"></i>
                            </a>\";
                }
                if (\$this->restricciones->id_org_acceso_explicito[\$tupla[id_organizacion]][eliminar] == 'S')
                {
                    \$html .= \"<a href=\\\"#\\\" onclick=\\\"javascript:eliminar$nombre_clase('\". \$tupla[id] . \"');\\\" title=\\\"Eliminar $nombre_clase\\\">
                            <i class=\\\"icon icon-remove\\\"></i>

                        </a>\"; 
                }
                return \$html;
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
                    /*Carga Acceso segun el arbol*/
                    if (count(\$this->restricciones->id_org_acceso_explicito) <= 0){
                        \$this->restricciones->cargar_acceso_nodos_explicito(\$atr);
                    }                    
                    /*Valida Restriccion*/
                    if (!isset(\$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!((\$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]][nuevo]== 'S') || (\$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . \$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]][title] . '.';

                    \$sql = \"INSERT INTO $tabla($sql_insertar_nombres)
                            VALUES(
                                $sql_insertar_valores
                                )\";
                    \$this->dbl->insert_update(\$sql);
                    /*
                    \$this->registraTransaccion('Insertar','Ingreso el $tabla ' . \$atr[descripcion_ano], '$tabla');
                      */
                    \$sql = \"SELECT MAX(id) ultimo FROM $tabla\"; 
                    \$this->operacion(\$sql, \$atr);
                    \$id_new = \$this->dbl->data[0][0];
                    \$nuevo = \"$campos_log_new\";
                    \$this->registraTransaccionLog(18,\$nuevo,'', \$id_new);
                    return \"El $tabla '\$atr[descripcion_ano]' ha sido ingresado con exito\";
                } catch(Exception \$e) {
                        \$error = \$e->getMessage();                     
                        if (preg_match(\"/ano_escolar_niveles_secciones_nivel_academico_key/\",\$error ) == true) 
                            return \"Ya existe una sección con el mismo nombre.\";                        
                        return \$error; 
                    }
            }
            
            public function registraTransaccionLog(\$accion,\$descr, \$tabla, \$id = 'NULL'){
                session_name(\"mosaikus\");
                session_start();
                \$sql = \"INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip, id_registro) VALUES ('\$accion','\".date('Y-m-d G:h:s').\"','\$descr', '\$tabla','\$_SESSION[CookIdUsuario]','\$_SERVER[REMOTE_ADDR]',\$id)\";            
                \$this->dbl->insert_update(\$sql);

                return true;
            }

            public function modificar$nombre_clase(\$atr){
                try {
                    \$atr = \$this->dbl->corregir_parametros(\$atr);                    
                    /*Carga Acceso segun el arbol*/
                    if (count(\$this->restricciones->id_org_acceso_explicito) <= 0){
                        \$this->restricciones->cargar_acceso_nodos_explicito(\$atr);
                    }                    
                    /*Valida Restriccion*/
                    if (!isset(\$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!((\$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]][nuevo]== 'S') || (\$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . \$this->restricciones->id_org_acceso_explicito[\$atr[id_organizacion]][title] . '.';

                    \$sql = \"UPDATE $tabla SET                            
                                    $sql_modificar
                            WHERE  id = \$atr[id]\";      
                    \$val = \$this->ver$nombre_clase(\$atr[id]);
                    \$this->dbl->insert_update(\$sql);
                    \$nuevo = \"$campos_log_new\";
                    \$anterior = \"$campos_log_ant\";
                    \$this->registraTransaccionLog(19,\$nuevo,\$anterior, \$atr[id]);
                    /*
                    \$this->registraTransaccion('Modificar','Modifico el $nombre_clase ' . \$atr[descripcion_ano], '$tabla');
                    */
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
                    \$sql_left = \$sql_col_left = \"\";
                    /* if (count(\$this->parametros) <= 0){
                        \$this->cargar_parametros();
                    }                    
                    \$k = 1;                    
                    foreach (\$this->parametros as \$value) {
                        \$sql_left .= \" LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                                inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                        where t1.cod_categoria='3' and t1.cod_parametro='\$value[cod_parametro]' ) AS p\$k ON p\$k.id_registro = p.cod_emp \"; 
                        \$sql_col_left .= \",p\$k.nom_detalle p\$k \";
                        \$k++;
                    }
                    
                    if (count(\$this->restricciones->id_org_acceso) <= 0){
                        \$this->restricciones->cargar_acceso_nodos(\$atr);
                    }*/
                    
                    \$sql = \"SELECT COUNT(*) total_registros
                         FROM $tabla 
                         WHERE 1 = 1 \";
                    if (strlen(\$atr['b-filtro-sencillo'])>0){
                        \$sql .= \" AND ((upper(id_personal) like '\" . strtoupper(\$atr[\"b-filtro-sencillo\"]) . \"%')\";
                        \$sql .= \" OR (1 = 1\";
                        \$nombre_supervisor = explode(' ', \$atr[\"b-filtro-sencillo\"]);                                                  
                        foreach (\$nombre_supervisor as \$supervisor_aux) {
                           \$sql .= \" AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%\" . strtoupper(\$supervisor_aux) . \"%') \";
                        } 
                        \$sql .= \" ) \";
                        \$sql .= \" OR (upper(c.descripcion) like '%\" . strtoupper(\$atr[\"b-filtro-sencillo\"]) . \"%'))\";
                    }
                    if (strlen(\$atr[valor])>0)
                        \$sql .= \" AND upper(\$atr[campo]) like '%\" . strtoupper(\$atr[valor]) . \"%'\";      
                    $filtro_listar
                    if (count(\$this->restricciones->id_org_acceso)>0){                            
                        \$sql .= \" AND id_organizacion IN (\". implode(',', array_keys(\$this->restricciones->id_org_acceso)) . \")\";
                    }
                    \$total_registros = \$this->dbl->query(\$sql, \$atr);
                    \$this->total_registros = \$total_registros[0][total_registros];   
            
                    \$sql = \"SELECT $sql_ver
                                     \$sql_col_left
                            FROM $tabla \$sql_left
                            WHERE 1 = 1 \";
                    if (strlen(\$atr['b-filtro-sencillo'])>0){
                        \$sql .= \" AND ((upper(id_personal) like '\" . strtoupper(\$atr[\"b-filtro-sencillo\"]) . \"%')\";
                        \$sql .= \" OR (1 = 1\";
                        \$nombre_supervisor = explode(' ', \$atr[\"b-filtro-sencillo\"]);                                                  
                        foreach (\$nombre_supervisor as \$supervisor_aux) {
                           \$sql .= \" AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%\" . strtoupper(\$supervisor_aux) . \"%') \";
                        } 
                        \$sql .= \" ) \";
                        \$sql .= \" OR (upper(c.descripcion) like '%\" . strtoupper(\$atr[\"b-filtro-sencillo\"]) . \"%'))\";
                    }
                    if (strlen(\$atr[valor])>0)
                        \$sql .= \" AND upper(\$atr[campo]) like '%\" . strtoupper(\$atr[valor]) . \"%'\";
                    $filtro_listar
                    \$sql .= \" order by \$atr[corder] \$atr[sorder] \";
                    \$sql .= \"LIMIT \" . ((\$pag - 1) * \$registros_x_pagina) . \", \$registros_x_pagina \";
                    \$this->operacion(\$sql, \$atr);
             }
             public function eliminar$nombre_clase(\$atr){
                    try {
                        \$atr = \$this->dbl->corregir_parametros(\$atr);
                        \$val = \$this->ver$nombre_clase(\$atr[id]);
                        \$respuesta = \$this->dbl->delete(\"$tabla\", \"id = \" . \$atr[id]);
                        \$nuevo = \"$campos_log_ant\";
                        \$this->registraTransaccionLog(86,\$nuevo,'', \$atr[id]);
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
                \$contenido[TITULO_MODULO] = \$parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import(\"clases.interfaz.Template\");
                }
                import('clases.utilidades.NivelAcceso');
                \$this->restricciones = new NivelAcceso();
                if (\$parametros['corder'] == null) \$parametros['corder']=\"descripcion_ano\";
                if (\$parametros['sorder'] == null) \$parametros['sorder']=\"desc\"; 
                if (\$parametros['mostrar-col'] == null) 
                    \$parametros['mostrar-col']=\"0-$columnas_mostrar\"; 
                /*if (count(\$this->parametros) <= 0){
                        \$this->cargar_parametros();
                } */               
                \$k = 19;
                \$contenido[PARAMETROS_OTROS] = \"\";
                foreach (\$this->parametros as \$value) {                    
                    \$parametros['mostrar-col'] .= \"-\$k\";
                    \$contenido[PARAMETROS_OTROS] .= '<div class=\"form-group\">
                                  <label for=\"SelectAcc\" class=\"col-md-9 control-label\">' . \$value[espanol] . '</label>
                                  <div class=\"col-md-3\">      
                                      <label class=\"checkbox-inline\">
                                          <input type=\"checkbox\" name=\"SelectAcc\" id=\"SelectAcc\" value=\"' . \$k . '\" class=\"checkbox-mos-col\" checked=\"checked\">   &nbsp;
                                      </label>
                                  </div>
                            </div>';
                    \$k++;
                }
                \$this->restricciones->cargar_permisos(\$parametros);
                \$grid = \$this->verLista$nombre_clase(\$parametros);
                \$contenido['CORDER'] = \$parametros['corder'];
                \$contenido['MODO'] = \$parametros['modo'];
                \$contenido['COD_LINK'] = \$parametros['cod_link'];
                \$contenido['SORDER'] = \$parametros['sorder'];
                \$contenido['MOSTRAR_COL'] = \$parametros['mostrar-col'];
                \$contenido['TABLA'] = \$grid['tabla'];
                \$contenido['PAGINADO'] = \$grid['paginado'];
                \$contenido['OPCIONES_BUSQUEDA'] = \" <option value='campo'>campo</option>\";
                \$contenido['JS_NUEVO'] = 'nuevo_$nombre_clase();';
                \$contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;$nombre_clase';
                \$contenido['TABLA'] = \$grid['tabla'];
                \$contenido['PAGINADO'] = \$grid['paginado'];
                \$contenido['PERMISO_INGRESAR'] = \$this->per_crear == 'S' ? '' : 'display:none;';

                \$template = new Template();
                \$template->PATH = PATH_TO_TEMPLATES.'$nombre_fisico/';
                if (count(\$this->nombres_columnas) <= 0){
                        \$this->cargar_nombres_columnas();
                }
                foreach ( \$this->nombres_columnas as \$key => \$value) {
                    \$contenido[\"N_\" . strtoupper(\$key)] =  \$value;
                }  
                if (count(\$this->placeholder) <= 0){
                        \$this->cargar_placeholder();
                }
                foreach ( \$this->placeholder as \$key => \$value) {
                    \$contenido[\"P_\" . strtoupper(\$key)] =  \$value;
                } 
                \$template->setTemplate(\"busqueda\");
                \$template->setVars(\$contenido);
                \$contenido['CAMPOS_BUSCAR'] = \$template->show();
                \$template = new Template();
                \$template->PATH = PATH_TO_TEMPLATES.'$nombre_fisico/';

                \$template->setTemplate(\"mostrar_colums\");
                \$template->setVars(\$contenido);
                \$contenido['CAMPOS_MOSTRAR_COLUMNS'] = \$template->show();
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
                \$objResponse->addScript(\"\$('#MustraCargando').hide();\");
                \$objResponse->addScript('PanelOperator.initPanels(\"\");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_simple();');
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
                if (count(\$this->nombres_columnas) <= 0){
                        \$this->cargar_nombres_columnas();
                }
                foreach ( \$this->nombres_columnas as \$key => \$value) {
                    \$contenido_1[\"N_\" . strtoupper(\$key)] =  \$value;
                }                
                if (count(\$this->placeholder) <= 0){
                        \$this->cargar_placeholder();
                }
                foreach ( \$this->placeholder as \$key => \$value) {
                    \$contenido_1[\"P_\" . strtoupper(\$key)] =  \$value;
                }     
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
                \$objResponse->addScriptCall(\"MostrarContenido2\");          
                \$objResponse->addScript(\"\$('#MustraCargando').hide();\"); 
                \$objResponse->addScript(\"$.validate({
                            lang: 'es'  
                          });\");";
                
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
                if (count(\$this->nombres_columnas) <= 0){
                        \$this->cargar_nombres_columnas();
                }
                foreach ( \$this->nombres_columnas as \$key => \$value) {
                    \$contenido_1[\"N_\" . strtoupper(\$key)] =  \$value;
                }                
                if (count(\$this->placeholder) <= 0){
                        \$this->cargar_placeholder();
                }
                foreach ( \$this->placeholder as \$key => \$value) {
                    \$contenido_1[\"P_\" . strtoupper(\$key)] =  \$value;
                }    
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
                \$objResponse->addScriptCall(\"MostrarContenido2\");          
                \$objResponse->addScript(\"\$('#MustraCargando').hide();\");
                \$objResponse->addScript(\"$.validate({
                            lang: 'es'  
                          });\");";
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
                session_name(\"\$GLOBALS[SESSION]\");
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
                          
                \$objResponse->addScript(\"\$('#MustraCargando').hide();\"); 
                \$objResponse->addScript(\"$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );\");
                        
                return \$objResponse;
            }
    ";
    /*******************************************************************/
    $actualizar = "
            public function actualizar(\$parametros)
            {
                session_name(\"\$GLOBALS[SESSION]\");
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
                          
                \$objResponse->addScript(\"\$('#MustraCargando').hide();\"); 
                \$objResponse->addScript(\"$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );\");
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
                       
                \$objResponse->addScript(\"\$('#MustraCargando').hide();\");
            return \$objResponse;
            }
    ";
    /*******************************************************************/
    $buscar = "
                public function buscar(\$parametros)
            {
                /*Permisos en caso de que no se use el arbol organizacional*/
                import('clases.utilidades.NivelAcceso');                
                \$this->restricciones = new NivelAcceso();
                \$this->restricciones->cargar_permisos(\$parametros);
                \$grid = \$this->verLista$nombre_clase(\$parametros);                
                \$objResponse = new xajaxResponse();
                \$objResponse->addAssign('grid',\"innerHTML\",\$grid[tabla]);
                \$objResponse->addAssign('grid-paginado',\"innerHTML\",\$grid['paginado']);
                          
                \$objResponse->addScript(\"\$('#MustraCargando').hide();\");
                \$objResponse->addScript(\"PanelOperator.resize();\");
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
                
                if (count(\$this->nombres_columnas) <= 0){
                        \$this->cargar_nombres_columnas();
                }

                \$grid->SetConfiguracionMSKS(\"tbl$nombre_clase\", \"\");
                \$config_col=array(
                    $titulos_grilla
                );
                /*if (count(\$this->parametros) <= 0){
                        \$this->cargar_parametros();
                }*/
                \$k = 1;
                foreach (\$this->parametros as \$value) {                    
                    array_push(\$config_col,array( \"width\"=>\"5%\",\"ValorEtiqueta\"=>link_titulos((\$value[espanol]), \"p\$k\", \$parametros)));
                    \$k++;
                }

                \$func= array();

                \$columna_funcion = -1;
                /*if (strrpos(\$parametros['permiso'], '1') > 0){
                    
                    \$columna_funcion = $columnas;
                }
                if (\$parametros['permiso'][1] == \"1\")
                    array_push(\$func,array('nombre'=> 'ver$nombre_clase','imagen'=> \"<img style='cursor:pointer' src='diseno/images/find.png' title='Ver $nombre_clase'>\"));
                
                if(\$_SESSION[CookM] == 'S')//if (\$parametros['permiso'][2] == \"1\")
                    array_push(\$func,array('nombre'=> 'editar$nombre_clase','imagen'=> \"<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar $nombre_clase'>\"));
                if(\$_SESSION[CookE] == 'S')//if (\$parametros['permiso'][3] == \"1\")
                    array_push(\$func,array('nombre'=> 'eliminar$nombre_clase','imagen'=> \"<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar $nombre_clase'>\"));
               */
                \$config=array();
                //\$config=array(array(\"width\"=>\"10%\", \"ValorEtiqueta\"=>\"&nbsp;\"));
                \$grid->setPaginado(\$reg_por_pagina, \$this->total_registros);
                \$array_columns =  explode('-', \$parametros['mostrar-col']);
                for(\$i=0;\$i<count(\$config_col);\$i++){
                    switch (\$i) {                                             
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                            array_push(\$config,\$config_col[\$i]);
                            break;

                        default:
                            
                            if (in_array(\$i, \$array_columns)) {
                                array_push(\$config,\$config_col[\$i]);
                            }
                            else                                
                                \$grid->hidden[\$i] = true;
                            
                            break;
                    }
                }
                \$grid->setParent(\$this);
                \$grid->SetTitulosTablaMSKS(\"td-titulo-tabla-row\", \$config);
                \$grid->setFuncion(\"id\", \"colum_admin\");
                //\$grid->setFuncion(\"en_proceso_inscripcion\", \"enProcesoInscripcion\");
                //\$grid->setAligns(1,\"center\");
                //\$grid->hidden = array(0 => true);
    
                \$grid->setDataMSKS(\"td-table-data\", \$data, \$func,\$columna_funcion, \$parametros['pag'] );
                \$out['tabla']= \$grid->armarTabla();
                //if ((\$parametros['pag'] != 1)  || (\$this->total_registros >= \$reg_por_pagina))
                {
                    \$out['paginado']=\$grid->setPaginadohtmlMSKS(\"verPagina\", \"document\");
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

            if (count(\$this->nombres_columnas) <= 0){
                        \$this->cargar_nombres_columnas();
                }
             \$grid->SetConfiguracion(\"tbl$nombre_clase\", \"width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'\");
                \$config_col=array(
                 $titulos_grilla_excel
              );
                \$columna_funcion =10;
           /* \$grid->hidden = array(0 => true);
           if (count(\$this->parametros) <= 0){
                        \$this->cargar_parametros();
                }*/
                \$k = 1;
                foreach (\$this->parametros as \$value) {                    
                    array_push(\$config_col,array( \"width\"=>\"5%\",\"ValorEtiqueta\"=>  utf8_decode(\$value[espanol])));
                    \$k++;
                }
                \$columna_funcion =10;
                \$config = array();            
                \$array_columns =  explode('-', \$parametros['mostrar-col']);            
                for(\$i=0;\$i<count(\$config_col);\$i++){
                    switch (\$i) {                                             
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                            array_push(\$config,\$config_col[\$i]);
                            break;
                        default:                            
                            if (in_array(\$i, \$array_columns)) {
                                array_push(\$config,\$config_col[\$i]);
                            }
                            else                                
                                \$grid->hidden[\$i] = true;                            
                            break;
                    }
                }
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
            session_name('mosaikus');            
            session_start();
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
    /*
    $gestor = fopen("pages/".$nombre_fisico."/listar".$archivo, "w");
    fwrite($gestor, "<?php\n $pages_listar ?>");
    fclose($gestor);
*/
    $gestor = fopen("pages/".$nombre_fisico."/exportarExcel.php", "w");
    fwrite($gestor, $pages_exportar_excel);
    fclose($gestor);

//    $gestor = fopen("pages/".$nombre_fisico."/ver".$archivo, "w");
//    fwrite($gestor, $pages_ver);
//    fclose($gestor);
    /*******************PARA JS******************/
    /*******************PARA JS******************/
    /*******************PARA JS******************/
    /*******************PARA JS******************/
    $texto_js="
    
    function init_filtrar(){        
            PanelOperator.initPanels('');
            ScrollBar.initScroll();
            init_filtro_rapido();
    }

    function filtrar_mostrar_colums(){
        var colums = '1-2-3-4-';
         $('.checkbox-mos-col').each(function() {
                if (this.checked){
                    colums = colums + this.value + '-';
                }
         });
         colums = colums.substring(0, colums.length - 1);
         $('#mostrar-col').val(colums);
         verPagina($('#pag_actual').val(),1);
         $('#myModal-Mostrar-Colums').modal('hide');
         
    }

    function nuevo_$nombre_clase(){
            array = new XArray();
            array.setObjeto('$nombre_clase','crear');
            array.addParametro('import','clases.$nombre_fisico.$nombre_clase');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( \"#btn-guardar\" ).html('Procesando..');
            $( \"#btn-guardar\" ).prop( \"disabled\", true );
            array = new XArray();
            if (doc.getElementById(\"opc\").value == \"new\")
                array.setObjeto('$nombre_clase','guardar');
            else
                array.setObjeto('$nombre_clase','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.$nombre_fisico.$nombre_clase');
            xajax_Loading(array.getArray());
        }else{
        
        }
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
        $('#MustraCargando').show();
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

    if (!is_dir("dist/js/".$nombre_fisico))
        mkdir("dist/js/".$nombre_fisico, 0700);

    $archivo=$nombre_fisico.".js";
    $gestor = fopen("dist/js/".$nombre_fisico."/".$archivo, "w");
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
    
    $archivo="busqueda.tpl";
    $gestor = fopen("diseno/templates/".$nombre_fisico."/".$archivo, "w");
    fwrite($gestor, $campos_form_busq);
    fclose($gestor);
    
    $archivo="mostrar_colums.tpl";
    $gestor = fopen("diseno/templates/".$nombre_fisico."/".$archivo, "w");
    fwrite($gestor, $campos_form_colums);
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
                        <td class='td-titulo-tabla-row' align='center'>Clase</td>
                        <td class='td-titulo-tabla-row' align='center'>Ubicacion Fisica</td> 
                        <td class='td-titulo-tabla-row' align='center'>Tabla</td>
                        <td class='td-titulo-tabla-row' align='center'>Modulo</td>
                </tr>
                <tr>
                    <td class='td-table-data-alt'>
                        <input type='text' name='clase' size="20" id='clase' class="form-box" value="<? echo $nombre_clase;?>"/>
                    </td>
                    <td class='td-table-data-alt'>
                        <input type='text' name='fisico' id='fisico' class="form-box" value="<? echo $nombre_fisico;?>"/>
                    </td>
                    <td class='td-table-data-alt'>
                        <input type='text' name='tabla' id='tabla' class="form-box" value="<? echo $tabla;?>"/>
                    </td>
                    <td class='td-table-data-alt'>
                        <input type='text' name='modulo' id='modulo' class="form-box" value="<? echo $modulo;?>"/>
                    </td>
                </tr>
                <tr> <td class='td-table-data-alt' colspan="3" align="center">
                        <input class="form-button" type='submit' value='Scaffold' />
                </td> </tr>
            </table>
        </form>

</body>
</html>



