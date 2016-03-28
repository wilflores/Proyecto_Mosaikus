<?php
 import("clases.interfaz.Pagina");        
        class WorkflowAcciones extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function WorkflowAcciones(){
                parent::__construct();
                $this->asigna_script('workflow_acciones/workflow_acciones.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 30";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 30";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verWorkflowAcciones($id){
                $atr=array();
                $sql = "SELECT id
,id_personal
,email
,id_personal_wf
,email_wf
,id_personal_vaca
,email_wf_vaca
,email_alerta

                         FROM mos_workflow_acciones 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarWorkflowAcciones($atr){
                try {
                    if($atr[id_personal_vaca]==''){
                        $atr[id_personal_vaca]='NULL';
                    }
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "INSERT INTO mos_workflow_acciones(id_personal,email,id_personal_wf,email_wf,id_personal_vaca,email_wf_vaca,email_alerta)
                            VALUES(
                                $atr[id_personal],'$atr[email]',$atr[id_personal_wf],'$atr[email_wf]',$atr[id_personal_vaca],'$atr[email_wf_vaca]','$atr[email_alerta]'
                                )";
                   // echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_workflow_acciones ' . $atr[descripcion_ano], 'mos_workflow_acciones');
                      */
                    $nuevo = "Id Personal: \'$atr[id_personal]\', Email: \'$atr[email]\', Id Personal Wf: \'$atr[id_personal_wf]\', Email Wf: \'$atr[email_wf]\', Id Personal Vaca: \'$atr[id_personal_vaca]\', Email Wf Vaca: \'$atr[email_wf_vaca]\', Email Alerta: \'$atr[email_alerta]\', ";
                    $this->registraTransaccionLog(78,$nuevo,'', '');
                    return "El mos_workflow_acciones '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarWorkflowAcciones($atr){
                try {
                    if($atr[id_personal_vaca]==''){
                        $atr[id_personal_vaca]='NULL';
                    }
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_workflow_acciones SET                            
                                    id = $atr[id],id_personal = $atr[id_personal],email = '$atr[email]',id_personal_wf = $atr[id_personal_wf],email_wf = '$atr[email_wf]',id_personal_vaca = $atr[id_personal_vaca],email_wf_vaca = '$atr[email_wf_vaca]',email_alerta = '$atr[email_alerta]'
                            WHERE  id = $atr[id]";      
                    $val = $this->verWorkflowAcciones($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Personal: \'$atr[id_personal]\', Email: \'$atr[email]\', Id Personal Wf: \'$atr[id_personal_wf]\', Email Wf: \'$atr[email_wf]\', Id Personal Vaca: \'$atr[id_personal_vaca]\', Email Wf Vaca: \'$atr[email_wf_vaca]\', Email Alerta: \'$atr[email_alerta]\', ";
                    $anterior = "Id Personal: \'$val[id_personal]\', Email: \'$val[email]\', Id Personal Wf: \'$val[id_personal_wf]\', Email Wf: \'$val[email_wf]\', Id Personal Vaca: \'$val[id_personal_vaca]\', Email Wf Vaca: \'$val[email_wf_vaca]\', Email Alerta: \'$val[email_alerta]\', ";
                    $this->registraTransaccionLog(79,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el WorkflowAcciones ' . $atr[descripcion_ano], 'mos_workflow_acciones');
                    */
                    return "El mos_workflow_acciones '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarWorkflowAcciones($atr, $pag, $registros_x_pagina){
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
                    $sql = "SELECT COUNT(wf.id) total_registros
                         FROM mos_workflow_acciones AS wf
                            INNER JOIN mos_personal AS perso ON wf.id_personal = perso.cod_emp
                            INNER JOIN mos_personal AS perso_wf ON wf.id_personal_wf = perso_wf.cod_emp
                            left JOIN mos_personal AS perso_vaca ON wf.id_personal_vaca = perso_vaca.cod_emp
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-id_personal"])>0)
                        $sql .= " AND id_personal = '". $atr["b-id_personal"] . "'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
             if (strlen($atr["b-id_personal_wf"])>0)
                        $sql .= " AND id_personal_wf = '". $atr["b-id_personal_wf"] . "'";
            if (strlen($atr["b-email_wf"])>0)
                        $sql .= " AND upper(email_wf) like '%" . strtoupper($atr["b-email_wf"]) . "%'";
             if (strlen($atr["b-id_personal_vaca"])>0)
                        $sql .= " AND id_personal_vaca = '". $atr["b-id_personal_vaca"] . "'";
            if (strlen($atr["b-email_wf_vaca"])>0)
                        $sql .= " AND upper(email_wf_vaca) like '%" . strtoupper($atr["b-email_wf_vaca"]) . "%'";
            if (strlen($atr["b-email_alerta"])>0)
                        $sql .= " AND upper(email_alerta) like '%" . strtoupper($atr["b-email_alerta"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT
                            wf.id,
                            CONCAT(CONCAT(UPPER(LEFT(perso.apellido_paterno, 1)), LOWER(SUBSTRING(perso.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso.apellido_materno, 1)), LOWER(SUBSTRING(perso.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso.nombres, 1)), LOWER(SUBSTRING(perso.nombres, 2)))) id_personal,
                            wf.email,
                            CONCAT(CONCAT(UPPER(LEFT(perso_wf.apellido_paterno, 1)), LOWER(SUBSTRING(perso_wf.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_wf.apellido_materno, 1)), LOWER(SUBSTRING(perso_wf.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_wf.nombres, 1)), LOWER(SUBSTRING(perso_wf.nombres, 2)))) nombre_wf,
                            wf.email_wf,
                            CONCAT(CONCAT(UPPER(LEFT(perso_vaca.apellido_paterno, 1)), LOWER(SUBSTRING(perso_vaca.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_vaca.apellido_materno, 1)), LOWER(SUBSTRING(perso_vaca.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_vaca.nombres, 1)), LOWER(SUBSTRING(perso_vaca.nombres, 2)))) nombre_vaca,
                            wf.email_wf_vaca,
                            wf.email_alerta
                            $sql_col_left
                            FROM
                            mos_workflow_acciones AS wf
                            INNER JOIN mos_personal AS perso ON wf.id_personal = perso.cod_emp
                            INNER JOIN mos_personal AS perso_wf ON wf.id_personal_wf = perso_wf.cod_emp
                            left JOIN mos_personal AS perso_vaca ON wf.id_personal_vaca = perso_vaca.cod_emp
                            WHERE
                            1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-id_personal"])>0)
                        $sql .= " AND id_personal = '". $atr["b-id_personal"] . "'";
            if (strlen($atr["b-email"])>0)
                        $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
             if (strlen($atr["b-id_personal_wf"])>0)
                        $sql .= " AND id_personal_wf = '". $atr["b-id_personal_wf"] . "'";
            if (strlen($atr["b-email_wf"])>0)
                        $sql .= " AND upper(email_wf) like '%" . strtoupper($atr["b-email_wf"]) . "%'";
             if (strlen($atr["b-id_personal_vaca"])>0)
                        $sql .= " AND id_personal_vaca = '". $atr["b-id_personal_vaca"] . "'";
            if (strlen($atr["b-email_wf_vaca"])>0)
                        $sql .= " AND upper(email_wf_vaca) like '%" . strtoupper($atr["b-email_wf_vaca"]) . "%'";
            if (strlen($atr["b-email_alerta"])>0)
                        $sql .= " AND upper(email_alerta) like '%" . strtoupper($atr["b-email_alerta"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarWorkflowAcciones($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verWorkflowAcciones($atr[id]);
                        $respuesta = $this->dbl->delete("mos_workflow_acciones", "id = " . $atr[id]);
                        $eliminado = "Id Personal: \'$val[id_personal]\', Email: \'$val[email]\', Id Personal Wf: \'$val[id_personal_wf]\', Email Wf: \'$val[email_wf]\', Id Personal Vaca: \'$val[id_personal_vaca]\', Email Wf Vaca: \'$val[email_wf_vaca]\', Email Alerta: \'$val[email_alerta]\', ";
                        $this->registraTransaccionLog(80,$eliminado,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaWorkflowAcciones($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarWorkflowAcciones($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblWorkflowAcciones", "");
                $config_col=array(
                    array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "Id", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_personal], "Responsable", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email], "email", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_personal_wf], "id_personal_wf", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email_wf], "email_wf", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_personal_vaca], "id_personal_vaca", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email_wf_vaca], "email_wf_vaca", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[email_alerta], "email_alerta", $parametros))
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
                    
                    $columna_funcion = 9;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verWorkflowAcciones','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver WorkflowAcciones'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarWorkflowAcciones','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar WorkflowAcciones'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarWorkflowAcciones','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar WorkflowAcciones'></i>"));
               
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
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                 $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                //}
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarWorkflowAcciones($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblWorkflowAcciones", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_personal], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_personal_wf], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email_wf], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_personal_vaca], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email_wf_vaca], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[email_alerta], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexWorkflowAcciones($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-8"; 
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
                $grid = $this->verListaWorkflowAcciones($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='wf.id'>ID</option>";
                $contenido['JS_NUEVO'] = 'nuevo_WorkflowAcciones();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;WorkflowAcciones';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_acciones/';
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
                $template->PATH = PATH_TO_TEMPLATES.'workflow_acciones/';

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
                $objResponse->addAssign('modulo_actual',"value","workflow_acciones");
                $objResponse->addIncludeScript(PATH_TO_JS . 'workflow_acciones/workflow_acciones.js');
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
                $contenido_1[ID_PERSONAL] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and workflow = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                $contenido_1[ID_PERSONAL_WF] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and workflow = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                $contenido_1[ID_PERSONAL_VACA] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and workflow = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                 $contenido_1[EMAIL_ALERTA] .= $ut_tool->OptionsCombo("SELECT email, 
                                                                    CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                    (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                    FROM mos_usuario p "
                                                                    , 'email'
                                                                    , 'nombres', $value[valor]);               
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_acciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;WorkflowAcciones";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;WorkflowAcciones";
                $contenido['PAGINA_VOLVER'] = "listarWorkflowAcciones.php";
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
                    if($parametros[id_personal]!=$parametros[id_personal_wf]&&$parametros[id_personal]!=$parametros[id_personal_vaca]&&$parametros[id_personal_wf]!=$parametros[id_personal_vaca]){
                        $respuesta = $this->ingresarWorkflowAcciones($parametros);
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
                $val = $this->verWorkflowAcciones($parametros[id]); 

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
                            $contenido_1['ID_PERSONAL'] = $val["id_personal"];
            $contenido_1['EMAIL'] = ($val["email"]);
            $contenido_1['ID_PERSONAL_WF'] = $val["id_personal_wf"];
            $contenido_1['EMAIL_WF'] = ($val["email_wf"]);
            $contenido_1['ID_PERSONAL_VACA'] = $val["id_personal_vaca"];
            $contenido_1['EMAIL_WF_VACA'] = ($val["email_wf_vaca"]);
            $contenido_1['EMAIL_ALERTA'] = ($val["email_alerta"]);
                $contenido_1[ID_PERSONAL] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and workflow = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_personal"]);
                $contenido_1[ID_PERSONAL_WF] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and workflow = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_personal_wf"]);
                $contenido_1[ID_PERSONAL_VACA] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                        (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                            FROM mos_personal p WHERE interno = 1 and workflow = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_personal_vaca"]);
                 $contenido_1[EMAIL_ALERTA] .= $ut_tool->OptionsCombo("SELECT email, 
                                                                    CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),
                                                                    (case when email<>'' and email is not null then CONCAT('=>',email) else '' end) )  nombres
                                                                    FROM mos_usuario p "
                                                                    , 'email'
                                                                    , 'nombres', $val["email_alerta"]);               
            

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_acciones/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;WorkflowAcciones";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;WorkflowAcciones";
                $contenido['PAGINA_VOLVER'] = "listarWorkflowAcciones.php";
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
                }else{
                     if($parametros[id_personal]!=$parametros[id_personal_wf]&&$parametros[id_personal]!=$parametros[id_personal_vaca]&&$parametros[id_personal_wf]!=$parametros[id_personal_vaca]){
                        $respuesta = $this->modificarWorkflowAcciones($parametros);
                        if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                            $objResponse->addScriptCall("MostrarContenido");
                            $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        }
                        else
                         $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                    }
                    else {
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
                $val = $this->verWorkflowAcciones($parametros[id]);
                $respuesta = $this->eliminarWorkflowAcciones($parametros);
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
                $grid = $this->verListaWorkflowAcciones($parametros);                
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

                $val = $this->verWorkflowAcciones($parametros[id]);

                            $contenido_1['ID_PERSONAL'] = $val["id_personal"];
            $contenido_1['EMAIL'] = ($val["email"]);
            $contenido_1['ID_PERSONAL_WF'] = $val["id_personal_wf"];
            $contenido_1['EMAIL_WF'] = ($val["email_wf"]);
            $contenido_1['ID_PERSONAL_VACA'] = $val["id_personal_vaca"];
            $contenido_1['EMAIL_WF_VACA'] = ($val["email_wf_vaca"]);
            $contenido_1['EMAIL_ALERTA'] = ($val["email_alerta"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'workflow_acciones/';
                $template->setTemplate("verWorkflowAcciones");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la WorkflowAcciones";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>