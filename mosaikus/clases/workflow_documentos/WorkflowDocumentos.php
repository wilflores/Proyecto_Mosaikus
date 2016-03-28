<?php
 import("clases.interfaz.Pagina");        
        class WorkflowDocumentos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function WorkflowDocumentos(){
                parent::__construct();
                $this->asigna_script('workflow_documentos/workflow_documentos.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 23";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 23";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verWorkflowDocumentos($id){
                $atr=array();
                $sql = "SELECT id
,id_personal_responsable
,email_responsable
,id_personal_revisa
,email_revisa
,id_personal_aprueba
,email_aprueba

                         FROM mos_workflow_documentos 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarWorkflowDocumentos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    if($atr[id_personal_revisa]=='')$atr[id_personal_revisa]='NULL';
                    $sql = "INSERT INTO mos_workflow_documentos(id_personal_responsable,email_responsable,id_personal_revisa,email_revisa,id_personal_aprueba,email_aprueba)
                            VALUES(
                                $atr[id_personal_responsable],'$atr[email_responsable]',$atr[id_personal_revisa],'$atr[email_revisa]',$atr[id_personal_aprueba],'$atr[email_aprueba]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_workflow_documentos ' . $atr[descripcion_ano], 'mos_workflow_documentos');
                      */
                    $nuevo = "Id Personal Responsable: \'$atr[id_personal_responsable]\', Email Responsable: \'$atr[email_responsable]\', Id Personal Revisa: \'$atr[id_personal_revisa]\', Email Revisa: \'$atr[email_revisa]\', Id Personal Aprueba: \'$atr[id_personal_aprueba]\', Email Aprueba: \'$atr[email_aprueba]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', '');
                    return "El mos_workflow_documentos '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarWorkflowDocumentos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    if($atr[id_personal_revisa]=='')$atr[id_personal_revisa]='NULL';
                    $sql = "UPDATE mos_workflow_documentos SET                            
                                    id = $atr[id],id_personal_responsable = $atr[id_personal_responsable],email_responsable = '$atr[email_responsable]',id_personal_revisa = $atr[id_personal_revisa],email_revisa = '$atr[email_revisa]',id_personal_aprueba = $atr[id_personal_aprueba],email_aprueba = '$atr[email_aprueba]'
                            WHERE  id = $atr[id]";      
                    $val = $this->verWorkflowDocumentos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Personal Responsable: \'$atr[id_personal_responsable]\', Email Responsable: \'$atr[email_responsable]\', Id Personal Revisa: \'$atr[id_personal_revisa]\', Email Revisa: \'$atr[email_revisa]\', Id Personal Aprueba: \'$atr[id_personal_aprueba]\', Email Aprueba: \'$atr[email_aprueba]\', ";
                    $anterior = "Id Personal Responsable: \'$val[id_personal_responsable]\', Email Responsable: \'$val[email_responsable]\', Id Personal Revisa: \'$val[id_personal_revisa]\', Email Revisa: \'$val[email_revisa]\', Id Personal Aprueba: \'$val[id_personal_aprueba]\', Email Aprueba: \'$val[email_aprueba]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el WorkflowDocumentos ' . $atr[descripcion_ano], 'mos_workflow_documentos');
                    */
                    return "El mos_workflow_documentos '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarWorkflowDocumentos($atr, $pag, $registros_x_pagina){
                 //print_r($atr);
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
                         FROM mos_workflow_documentos 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-id_personal_responsable"])>0)
                        $sql .= " AND CONCAT(CONCAT(UPPER(LEFT(perso_resp.apellido_paterno, 1)), LOWER(SUBSTRING(perso_resp.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_resp.apellido_materno, 1)), LOWER(SUBSTRING(perso_resp.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_resp.nombres, 1)), LOWER(SUBSTRING(perso_resp.nombres, 2)))) like '%" . $atr["b-id_personal_responsable"] . "%'";
                    if (strlen($atr["b-email_responsable"])>0)
                                $sql .= " AND upper(email_responsable) like '%" . strtoupper($atr["b-email_responsable"]) . "%'";
                     if (strlen($atr["b-id_personal_revisa"])>0)
                                $sql .= " AND CONCAT(CONCAT(UPPER(LEFT(perso_revisa.apellido_paterno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_revisa.apellido_materno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_revisa.nombres, 1)), LOWER(SUBSTRING(perso_revisa.nombres, 2)))) like '%" . $atr["b-id_personal_revisa"] . "%'";
                    if (strlen($atr["b-email_revisa"])>0)
                                $sql .= " AND upper(email_revisa) like '%" . strtoupper($atr["b-email_revisa"]) . "%'";
                     if (strlen($atr["b-id_personal_aprueba"])>0)
                                $sql .= " AND CONCAT(CONCAT(UPPER(LEFT(perso_aprueba.apellido_paterno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_aprueba.apellido_materno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_aprueba.nombres, 1)), LOWER(SUBSTRING(perso_aprueba.nombres, 2)))) like '%" . $atr["b-id_personal_aprueba"] . "%'";
                    if (strlen($atr["b-email_aprueba"])>0)
                            $sql .= " AND upper(email_aprueba) like '%" . strtoupper($atr["b-email_aprueba"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT wf.id
                                ,CONCAT(CONCAT(UPPER(LEFT(perso_resp.apellido_paterno, 1)), LOWER(SUBSTRING(perso_resp.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_resp.apellido_materno, 1)), LOWER(SUBSTRING(perso_resp.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_resp.nombres, 1)), LOWER(SUBSTRING(perso_resp.nombres, 2))))  id_personal_responsable
                                ,email_responsable
                                ,CONCAT(CONCAT(UPPER(LEFT(perso_revisa.apellido_paterno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_revisa.apellido_materno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_revisa.nombres, 1)), LOWER(SUBSTRING(perso_revisa.nombres, 2)))) id_personal_revisa
                                ,email_revisa
                                ,CONCAT(CONCAT(UPPER(LEFT(perso_aprueba.apellido_paterno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_aprueba.apellido_materno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_aprueba.nombres, 1)), LOWER(SUBSTRING(perso_aprueba.nombres, 2)))) id_personal_aprueba
                                ,email_aprueba
                                     $sql_col_left
                            FROM mos_workflow_documentos AS wf
                            INNER JOIN mos_personal AS perso_resp ON wf.id_personal_responsable = perso_resp.cod_emp
                            left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                            INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp
                            $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-id_personal_responsable"])>0)
                        $sql .= " AND CONCAT(CONCAT(UPPER(LEFT(perso_resp.apellido_paterno, 1)), LOWER(SUBSTRING(perso_resp.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_resp.apellido_materno, 1)), LOWER(SUBSTRING(perso_resp.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_resp.nombres, 1)), LOWER(SUBSTRING(perso_resp.nombres, 2)))) like '%" . $atr["b-id_personal_responsable"] . "%'";
                    if (strlen($atr["b-email_responsable"])>0)
                                $sql .= " AND upper(email_responsable) like '%" . strtoupper($atr["b-email_responsable"]) . "%'";
                     if (strlen($atr["b-id_personal_revisa"])>0)
                                $sql .= " AND CONCAT(CONCAT(UPPER(LEFT(perso_revisa.apellido_paterno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_revisa.apellido_materno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_revisa.nombres, 1)), LOWER(SUBSTRING(perso_revisa.nombres, 2)))) like '%" . $atr["b-id_personal_revisa"] . "%'";
                    if (strlen($atr["b-email_revisa"])>0)
                                $sql .= " AND upper(email_revisa) like '%" . strtoupper($atr["b-email_revisa"]) . "%'";
                     if (strlen($atr["b-id_personal_aprueba"])>0)
                                $sql .= " AND CONCAT(CONCAT(UPPER(LEFT(perso_aprueba.apellido_paterno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_aprueba.apellido_materno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_aprueba.nombres, 1)), LOWER(SUBSTRING(perso_aprueba.nombres, 2)))) like '%" . $atr["b-id_personal_aprueba"] . "%'";
                    if (strlen($atr["b-email_aprueba"])>0)
                            $sql .= " AND upper(email_aprueba) like '%" . strtoupper($atr["b-email_aprueba"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarWorkflowDocumentos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_workflow_documentos", "id = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaWorkflowDocumentos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarWorkflowDocumentos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblWorkflowDocumentos", "");
                $config_col=array(
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "Id", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_personal_responsable], "id_personal_responsable", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email_responsable], "email_responsable", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_personal_revisa], "id_personal_revisa", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email_revisa], "email_revisa", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_personal_aprueba], "id_personal_aprueba", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email_aprueba], "email_aprueba", $parametros))
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
                    
                    $columna_funcion = 8;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verWorkflowDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver WorkflowDocumentos'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarWorkflowDocumentos','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar WorkflowDocumentos'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarWorkflowDocumentos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar WorkflowDocumentos'></i>"));
               
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
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
               // if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                //}
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarWorkflowDocumentos($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblWorkflowDocumentos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_personal_responsable], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email_responsable], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_personal_revisa], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email_revisa], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_personal_aprueba], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email_aprueba], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexWorkflowDocumentos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-4-5-6-7"; 
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
                $grid = $this->verListaWorkflowDocumentos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_WorkflowDocumentos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;WorkflowDocumentos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_documentos/';
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
                $template->PATH = PATH_TO_TEMPLATES.'workflow_documentos/';

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
                $objResponse->addAssign('modulo_actual',"value","workflow_documentos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'workflow_documentos/workflow_documentos.js');
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
                $contenido_1[ID_PERSONAL_RESPONSABLE] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                $contenido_1[ID_PERSONAL_REVISA] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                $contenido_1[ID_PERSONAL_APRUEBA] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_documentos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;WorkflowDocumentos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;WorkflowDocumentos";
                $contenido['PAGINA_VOLVER'] = "listarWorkflowDocumentos.php";
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
                    if($parametros[id_personal_responsable]!=$parametros[id_personal_revisa]&&$parametros[id_personal_responsable]!=$parametros[id_personal_aprueba]&&$parametros[id_personal_revisa]!=$parametros[id_personal_aprueba])
                    {
                        $respuesta = $this->ingresarWorkflowDocumentos($parametros);
                        if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                            $objResponse->addScriptCall("MostrarContenido");
                            $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        }
                        else
                            $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                    }
                    else{
                        $respuesta = 'El personal seleccionado para el workflow deben ser diferentes';
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                    }

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
                $val = $this->verWorkflowDocumentos($parametros[id]); 

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
                $contenido_1[ID_PERSONAL_RESPONSABLE] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_personal_responsable"]);
                $contenido_1[ID_PERSONAL_REVISA] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_personal_revisa"]);
                $contenido_1[ID_PERSONAL_APRUEBA] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_personal_aprueba"]);

                $contenido_1['EMAIL_RESPONSABLE'] = ($val["email_responsable"]);
                $contenido_1['EMAIL_REVISA'] = ($val["email_revisa"]);
                $contenido_1['EMAIL_APRUEBA'] = ($val["email_aprueba"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_documentos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;WorkflowDocumentos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;WorkflowDocumentos";
                $contenido['PAGINA_VOLVER'] = "listarWorkflowDocumentos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");  
                $objResponse->addScriptCall("cargar_autocompletado");
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
                }else
                  {
                    if($parametros[id_personal_responsable]!=$parametros[id_personal_revisa]&&$parametros[id_personal_responsable]!=$parametros[id_personal_aprueba]&&$parametros[id_personal_revisa]!=$parametros[id_personal_aprueba])
                    {
                        $respuesta = $this->modificarWorkflowDocumentos($parametros);
                        if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                            $objResponse->addScriptCall("MostrarContenido");
                            $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        }
                        else
                            $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                    }
                    else{
                        $respuesta = 'El personal seleccionado para el workflow deben ser diferentes';
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                    }

                }        
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verWorkflowDocumentos($parametros[id]);
                $respuesta = $this->eliminarWorkflowDocumentos($parametros);
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
                $grid = $this->verListaWorkflowDocumentos($parametros);                
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

                $val = $this->verWorkflowDocumentos($parametros[id]);

                            $contenido_1['ID_PERSONAL_RESPONSABLE'] = $val["id_personal_responsable"];
            $contenido_1['EMAIL_RESPONSABLE'] = ($val["email_responsable"]);
            $contenido_1['ID_PERSONAL_REVISA'] = $val["id_personal_revisa"];
            $contenido_1['EMAIL_REVISA'] = ($val["email_revisa"]);
            $contenido_1['ID_PERSONAL_APRUEBA'] = $val["id_personal_aprueba"];
            $contenido_1['EMAIL_APRUEBA'] = ($val["email_aprueba"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_documentos/';
                $template->setTemplate("verWorkflowDocumentos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la WorkflowDocumentos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>