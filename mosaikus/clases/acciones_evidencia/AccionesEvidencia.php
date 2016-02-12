<?php
 import("clases.interfaz.Pagina");        
        class AccionesEvidencia extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function AccionesEvidencia(){
                parent::__construct();
                $this->asigna_script('acciones_evidencia/acciones_evidencia.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                session_name("$GLOBALS[SESSION]");
                session_start();
                unset($_SESSION[id_accion]);
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 17";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 17";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verAccionesEvidencia($id){
                $atr=array();
                $sql = "SELECT id
                            ,id_accion_correctiva
                            ,id_accion
                            ,DATE_FORMAT(fecha_evi, '%d/%m/%Y') fecha_evi
                            ,id_persona
                            ,nomb_archivo
                            ,archivo
                            ,contenttype
                            ,observacion

                         FROM mos_acciones_evidencia 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarAccionesEvidencia($atr,$archivo){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[archivo] = $archivo;
                    if (isset($_SESSION[id_accion])){
                        $atr[id_accion] = $_SESSION[id_accion];                        
                    }
                    else $atr[id_accion] = 'NULL';
                    if (isset($_SESSION[id_accion_correctiva])){
                        $atr[id_accion_correctiva] = $_SESSION[id_accion_correctiva];                        
                    }
                    else $atr[id_accion_correctiva] = 'NULL';
                    $sql = "INSERT INTO mos_acciones_evidencia(id_accion_correctiva,id_accion,fecha_evi,id_persona,nomb_archivo,archivo,contenttype,observacion)
                            VALUES(
                                $atr[id_accion_correctiva],$atr[id_accion],'$atr[fecha_evi]',$atr[id_persona],'$atr[nomb_archivo]','$atr[archivo]','$atr[contenttype]','$atr[observacion]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_acciones_evidencia ' . $atr[descripcion_ano], 'mos_acciones_evidencia');
                      */
                    $nuevo = "Id Accion Correctiva: \'$atr[id_accion_correctiva]\', Id Accion: \'$atr[id_accion]\', Fecha Evi: \'$atr[fecha_evi]\', Id Persona: \'$atr[id_persona]\', Nomb Archivo: \'$atr[nomb_archivo]\', Contenttype: \'$atr[contenttype]\', Observacion: \'$atr[observacion]\', ";
                    $this->registraTransaccionLog(66,$nuevo,'', '');
                    return "El registro ha sido ingresado con exito";
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

            public function modificarAccionesEvidencia($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_acciones_evidencia SET                            
                                    id = $atr[id],id_accion_correctiva = $atr[id_accion_correctiva],id_accion = $atr[id_accion],fecha_evi = '$atr[fecha_evi]',id_persona = $atr[id_persona],nomb_archivo = '$atr[nomb_archivo]',archivo = $atr[archivo],contenttype = '$atr[contenttype]',observacion = '$atr[observacion]'
                            WHERE  id = $atr[id]";      
                    $val = $this->verAccionesEvidencia($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Accion Correctiva: \'$atr[id_accion_correctiva]\', Id Accion: \'$atr[id_accion]\', Fecha Evi: \'$atr[fecha_evi]\', Id Persona: \'$atr[id_persona]\', Nomb Archivo: \'$atr[nomb_archivo]\', Archivo: \'$atr[archivo]\', Contenttype: \'$atr[contenttype]\', Observacion: \'$atr[observacion]\', ";
                    $anterior = "Id Accion Correctiva: \'$val[id_accion_correctiva]\', Id Accion: \'$val[id_accion]\', Fecha Evi: \'$val[fecha_evi]\', Id Persona: \'$val[id_persona]\', Nomb Archivo: \'$val[nomb_archivo]\', Archivo: \'$val[archivo]\', Contenttype: \'$val[contenttype]\', Observacion: \'$val[observacion]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el AccionesEvidencia ' . $atr[descripcion_ano], 'mos_acciones_evidencia');
                    */
                    return "El mos_acciones_evidencia '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarAccionesEvidencia($atr, $pag, $registros_x_pagina){
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
                         FROM mos_acciones_evidencia 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-id_accion_correctiva"])>0)
                        $sql .= " AND id_accion_correctiva = '". $atr["b-id_accion_correctiva"] . "'";
                    if (strlen($atr["b-id_accion"])>0)
                        $sql .= " AND id_accion = '". $atr["b-id_accion"] . "'";
             if (strlen($atr['b-fecha_evi-desde'])>0)                        
                    {
                        $atr['b-fecha_evi-desde'] = formatear_fecha($atr['b-fecha_evi-desde']);                        
                        $sql .= " AND fecha_evi >= '" . ($atr['b-fecha_evi-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_evi-hasta'])>0)                        
                    {
                        $atr['b-fecha_evi-hasta'] = formatear_fecha($atr['b-fecha_evi-hasta']);                        
                        $sql .= " AND fecha_evi <= '" . ($atr['b-fecha_evi-hasta']) . "'";                        
                    }
             if (strlen($atr["b-id_persona"])>0)
                        $sql .= " AND id_persona = '". $atr["b-id_persona"] . "'";
            if (strlen($atr["b-nomb_archivo"])>0)
                        $sql .= " AND upper(nomb_archivo) like '%" . strtoupper($atr["b-nomb_archivo"]) . "%'";
            if (strlen($atr["b-archivo"])>0)
                        $sql .= " AND upper(archivo) like '%" . strtoupper($atr["b-archivo"]) . "%'";
            if (strlen($atr["b-contenttype"])>0)
                        $sql .= " AND upper(contenttype) like '%" . strtoupper($atr["b-contenttype"]) . "%'";
            if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND upper(observacion) like '%" . strtoupper($atr["b-observacion"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                    ,id_accion_correctiva
                                    ,id_accion
                                    ,DATE_FORMAT(fecha_evi, '%d/%m/%Y') fecha_evi_a
                                    ,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) id_persona
                                    ,nomb_archivo
                                    ,'' archivo
                                    ,ed.contenttype
                                    ,ed.observacion

                                     $sql_col_left
                            FROM mos_acciones_evidencia ed $sql_left
                                inner join mos_personal p on ed.id_persona=p.cod_emp
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-id_accion_correctiva"])>0)
                        $sql .= " AND id_accion_correctiva = '". $atr["b-id_accion_correctiva"] . "'";
                    if (strlen($atr["b-id_accion"])>0)
                        $sql .= " AND id_accion = ". $atr["b-id_accion"] . "";
                    /*
                     $_SESSION[id_accion] = $parametros[id_accion];
                    unset ($_SESSION['id_correccion']);
                }
                else if (isset($parametros[id_accion_correctiva])){
                    $_SESSION[id_accion_correctiva] = $parametros[id_accion_correctiva];
                     */
                    if (strlen($_SESSION[id_accion_correctiva])>0)
                        $sql .= " AND ed.id_accion_correctiva = '". $_SESSION[id_accion_correctiva] . "'";
                    if (strlen($_SESSION[id_accion])>0)
                        $sql .= " AND ed.id_accion = '". $_SESSION[id_accion] . "'";
                    if (strlen($atr['b-fecha_evi-desde'])>0)                        
                    {
                        $atr['b-fecha_evi-desde'] = formatear_fecha($atr['b-fecha_evi-desde']);                        
                        $sql .= " AND fecha_evi >= '" . ($atr['b-fecha_evi-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_evi-hasta'])>0)                        
                    {
                        $atr['b-fecha_evi-hasta'] = formatear_fecha($atr['b-fecha_evi-hasta']);                        
                        $sql .= " AND fecha_evi <= '" . ($atr['b-fecha_evi-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-id_persona"])>0)
                        $sql .= " AND id_persona = '". $atr["b-id_persona"] . "'";
                    if (strlen($atr["b-nomb_archivo"])>0)
                        $sql .= " AND upper(nomb_archivo) like '%" . strtoupper($atr["b-nomb_archivo"]) . "%'";
                    if (strlen($atr["b-archivo"])>0)
                        $sql .= " AND upper(archivo) like '%" . strtoupper($atr["b-archivo"]) . "%'";
                    if (strlen($atr["b-contenttype"])>0)
                        $sql .= " AND upper(contenttype) like '%" . strtoupper($atr["b-contenttype"]) . "%'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND upper(observacion) like '%" . strtoupper($atr["b-observacion"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarAccionesEvidencia($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_acciones_evidencia", "id = " . $atr[id]);
                        $nuevo = "Id Accion Correctiva: \'$val[id_accion_correctiva]\', Id Accion: \'$val[id_accion]\', Fecha Evi: \'$val[fecha_evi]\', Id Persona: \'$val[id_persona]\', Nomb Archivo: \'$val[nomb_archivo]\', Contenttype: \'$val[contenttype]\', Observacion: \'$val[observacion]\', ";
                        $this->registraTransaccionLog(65,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaAccionesEvidencia($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarAccionesEvidencia($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesEvidencia", "");
                $config_col=array(
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[id], "id_accion", $parametros,"link_titulos_hv_2")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_accion_correctiva], "id_accion_correctiva", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_accion], "id_accion", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[fecha_evi], "fecha_evi", $parametros,"link_titulos_hv_2")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[id_persona], "id_persona", $parametros,"link_titulos_hv_2")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[nomb_archivo], "nomb_archivo", $parametros,"link_titulos_hv_2")),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[archivo], "archivo", $parametros,"link_titulos_hv_2")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contenttype], "contenttype", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[observacion], "observacion", $parametros,"link_titulos_hv_2"))
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

                $columna_funcion = $parametros[col_accion];
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 10;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verAccionesEvidencia','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesEvidencia'>"));
                */
//                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
//                    array_push($func,array('nombre'=> 'editarAccionesEvidencia','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar AccionesEvidencia'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarAccionesEvidencia','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar AccionesEvidencia'></i>"));
                if ($parametros[col_accion] == -1){
                    $config=array();
                }
                else
                    $config=array(array("width"=>"5%", "ValorEtiqueta"=>"&nbsp;"));
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
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("archivo", "archivo_descarga");
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
            
            public function verDocumentoPDF($id){
                $atr=array();
                $sql = "SELECT                             
                            archivo doc_visualiza
                            ,contenttype contentType_visualiza                            
                            ,nomb_archivo nom_visualiza                            
                         FROM mos_acciones_evidencia 
                         WHERE id = $id "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
        public function archivo_descarga($tupla,$key)
        {
            
            if (strlen($tupla[nomb_archivo])>0){
                $html = "<a  title=\"Ver Trazabilidad\" target=\"_blank\" href=\"pages/acciones_evidencia/descargar_archivo.php?id=$tupla[id]&token=" . md5($tupla[id]) ."&des=1\">
                            <i class=\"icon icon-view-document\"></i>
                        </a>";
            }
            return $html;
        }
        
        public function archivo_descarga_pdf($tupla,$key)
        {
            
            if (strlen($tupla[nomb_archivo])>0){
                $html = "<a target=\"_blank\" href=\"". APPLICATION_ROOT . "pages/acciones_evidencia/descargar_archivo.php?id=$tupla[id]&token=" . md5($tupla[id]) ."&des=1\">
                            $tupla[nomb_archivo].$tupla[contenttype]
                        </a><br/>";
            }
            return $html;
        }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarAccionesEvidencia($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblAccionesEvidencia", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_accion_correctiva], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_accion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_evi], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_persona], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nomb_archivo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[archivo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[contenttype], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[observacion], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexAccionesEvidencia($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                if (isset($parametros[id_accion])){
                    $_SESSION[id_accion] = $parametros[id_accion];
                    unset ($_SESSION['id_accion_correctiva']);
                }
                else if (isset($parametros[id_accion_correctiva])){
                    $_SESSION[id_accion_correctiva] = $parametros[id_accion_correctiva];                    
                    unset ($_SESSION['id_accion']);
                }
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="fecha_evi";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    //$parametros['mostrar-col']="3-4-5-6-8"; 
                    $parametros['mostrar-col']="3-4-6-8"; 
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
                $parametros[col_accion] = 0;
                $grid = $this->verListaAccionesEvidencia($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_AccionesEvidencia();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;AccionesEvidencia';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $ut_tool = new ut_Tool();
                $contenido['EMPLEADOS'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE vigencia = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['OPC'] = "new";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                //$objResponse->addAssign('contenido',"innerHTML",$template->show());
                
                if (isset($parametros[id_accion])){
                    $objResponse->addAssign('myModal-Ventana-Cuerpo-2',"innerHTML",$template->show());                                
                    //$objResponse->addAssign('modulo_actual',"value","acciones_evidencia");                
                    $objResponse->addScript("$('#myModal-Ventana').modal('hide');");
                    $objResponse->addScript("$('#myModal-Ventana-2').modal('show');");                
                    $objResponse->addScript("$('#myModal-Ventana-Titulo-2').html('Acciones ID: $_SESSION[id_accion] - Evidencias');");                                
                    $objResponse->addScript("$('#myModal-Ventana-2').on('hidden.bs.modal', function () {  
                                                $('#myModal-Ventana').modal('show');
                                            })");
                }
                else if (isset($parametros[id_accion_correctiva])){
                    $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());                                
                    //$objResponse->addAssign('modulo_actual',"value","acciones_evidencia");                
                    //$objResponse->addScript("$('#myModal-Ventana').modal('hide');");
                    $objResponse->addScript("$('#myModal-Ventana').modal('show');");                
                    $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Acciones ID: $_SESSION[id_accion_correctiva] - Evidencias');");                                                    
                }
                
                
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_evidencia/acciones_evidencia.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show'); ");
                $objResponse->addScript('$( "#id_persona-hv-2" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ');
                $objResponse->addScript("$('#fecha_evi-hv-2').datepicker();");
                
                
                return $objResponse;
            }
            
            public function indexAccionesEvidenciaVisualizacion($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                if (isset($parametros[id_accion])){
                    $_SESSION[id_accion] = $parametros[id_accion];
                    unset ($_SESSION['id_accion_correctiva']);
                }
                else if (isset($parametros[id_accion_correctiva])){
                    $_SESSION[id_accion_correctiva] = $parametros[id_accion_correctiva];
                    unset ($_SESSION['id_accion']);
                }
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="fecha_evi";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="3-4-6-8"; 
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
                $parametros[col_accion] = -1;
                $grid = $this->verListaAccionesEvidencia($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_AccionesEvidencia();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;AccionesEvidencia';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $ut_tool = new ut_Tool();
                $contenido['EMPLEADOS'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE vigencia = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['OPC'] = "new";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';

                $template->setTemplate("listar_visualizacion");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                //$objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('myModal-Ventana-Cuerpo-2',"innerHTML","");
                $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
                
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","acciones_evidencia");
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript("$('#myModal-Ventana').modal('hide');");
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Acciones ID: $_SESSION[id_accion] - Evidencias');");
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_evidencia/acciones_evidencia.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                                                
                
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;AccionesEvidencia";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesEvidencia";
                $contenido['PAGINA_VOLVER'] = "listarAccionesEvidencia.php";
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
                          });");$objResponse->addScript("$('#fecha_evi').datepicker();");
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
                    $parametros["fecha_evi"] = formatear_fecha($parametros["fecha_evi"]);
                    $parametros[nomb_archivo] = $parametros[nom_archivo];
                    $archivo = '';
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            
                            $parametros[contenttype] = $parametros[tipo_doc];//'application/pdf';                                    
                            //$parametros[nom_archivo] = $parametros['filename'];
                           
                    }
                    $respuesta = $this->ingresarAccionesEvidencia($parametros,$archivo);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScript("verPagina_hv_2(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        $objResponse->addScript("reset_formulario_2();");
                        try{
                            if ((strlen($parametros['filename'])>0) && (file_exists(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename'])))) {
                                unlink(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));               
                            }
                        } catch (Exception $ex) {

                        }
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar-hv-2' ).val('Guardar');
                                        $( '#btn-guardar-hv-2' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verAccionesEvidencia($parametros[id]); 

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
                            $contenido_1['ID_ACCION_CORRECTIVA'] = $val["id_accion_correctiva"];
            $contenido_1['ID_ACCION'] = $val["id_accion"];
            $contenido_1['FECHA_EVI'] = ($val["fecha_evi"]);
            $contenido_1['ID_PERSONA'] = $val["id_persona"];
            $contenido_1['NOMB_ARCHIVO'] = ($val["nomb_archivo"]);
            $contenido_1['ARCHIVO'] = ($val["archivo"]);
            $contenido_1['CONTENTTYPE'] = ($val["contenttype"]);
            $contenido_1['OBSERVACION'] = ($val["observacion"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;AccionesEvidencia";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesEvidencia";
                $contenido['PAGINA_VOLVER'] = "listarAccionesEvidencia.php";
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
                          });");$objResponse->addScript("$('#fecha_evi').datepicker();");
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
                    $parametros["fecha_evi"] = formatear_fecha($parametros["fecha_evi"]);

                    $respuesta = $this->modificarAccionesEvidencia($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).val('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verAccionesEvidencia($parametros[id]);
                $respuesta = $this->eliminarAccionesEvidencia($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    $objResponse->addScript("verPagina_hv_2(1,1);");
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
                public function buscar($parametros)
            {
                $grid = $this->verListaAccionesEvidencia($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid-hv-2',"innerHTML",$grid[tabla]);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verAccionesEvidencia($parametros[id]);

                            $contenido_1['ID_ACCION_CORRECTIVA'] = $val["id_accion_correctiva"];
            $contenido_1['ID_ACCION'] = $val["id_accion"];
            $contenido_1['FECHA_EVI'] = ($val["fecha_evi"]);
            $contenido_1['ID_PERSONA'] = $val["id_persona"];
            $contenido_1['NOMB_ARCHIVO'] = ($val["nomb_archivo"]);
            $contenido_1['ARCHIVO'] = ($val["archivo"]);
            $contenido_1['CONTENTTYPE'] = ($val["contenttype"]);
            $contenido_1['OBSERVACION'] = ($val["observacion"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_evidencia/';
                $template->setTemplate("verAccionesEvidencia");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la AccionesEvidencia";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>