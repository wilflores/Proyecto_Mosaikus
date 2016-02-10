<?php
 import("clases.interfaz.Pagina");        
        class AccionesAC extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function AccionesAC(){
                parent::__construct();
                $this->asigna_script('acciones_ac/acciones_ac.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 16";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 16";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verAccionesAC($id){
                $atr=array();
                $sql = "SELECT id
                            ,tipo
                            ,accion
                            ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada
                            ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada
                            ,id_responsable
                            ,id_ac
                            ,id_correcion

                         FROM mos_acciones_ac_co 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarAccionesAC($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    //session_name("$GLOBALS[SESSION]");
                    //session_start();
                    if (isset($_SESSION[id_ac])){
                        $atr[id_ac] = $_SESSION[id_ac];                        
                    }
                    else $atr[id_ac] = 'NULL';
                    if (isset($_SESSION[id_correccion])){
                        $atr[id_correcion] = $_SESSION[id_correccion];                        
                    }
                    else $atr[id_correcion] = 'NULL';
                    if (strlen($atr[fecha_realizada]) == 0){
                        $atr[fecha_realizada] = 'NULL';
                    }
                    else{
                        $atr[fecha_realizada] = "'$atr[fecha_realizada]'";
                    }
                    $sql = "INSERT INTO mos_acciones_ac_co(tipo,accion,fecha_acordada,fecha_realizada,id_responsable,id_ac,id_correcion)
                            VALUES(
                                $atr[tipo],'$atr[accion]','$atr[fecha_acordada]',$atr[fecha_realizada],$atr[id_responsable],$atr[id_ac],$atr[id_correcion]
                                )";
                    $this->dbl->insert_update($sql);
                    if ($atr[fecha_realizada] != 'NULL'){
                        $atr[fecha_realizada] = "\\" . substr($atr[fecha_realizada], 0, strlen($atr[fecha_realizada])-1)  . "\'";
                    }
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_acciones_ac_co ' . $atr[descripcion_ano], 'mos_acciones_ac_co');
                      */
                    $nuevo = "Tipo: \'$atr[tipo]\', Accion: \'$atr[accion]\', Fecha Acordada: \'$atr[fecha_acordada]\', Fecha Realizada: $atr[fecha_realizada], Id Responsable: \'$atr[id_responsable]\', Id Ac: \'$atr[id_ac]\', Id Correcion: \'$atr[id_correcion]\', ";
                    $this->registraTransaccionLog(62,$nuevo,'', '');
                    return "la acción correctiva '$atr[accion]' ha sido ingresado con exito";
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

            public function modificarAccionesAC($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    if (strlen($atr[fecha_realizada]) == 0){
                        $atr[fecha_realizada] = 'NULL';
                    }
                    else{
                        $atr[fecha_realizada] = "'$atr[fecha_realizada]'";
                    }
                    $sql = "UPDATE mos_acciones_ac_co SET                            
                                    tipo = $atr[tipo],accion = '$atr[accion]',fecha_acordada = '$atr[fecha_acordada]',fecha_realizada = $atr[fecha_realizada],id_responsable = $atr[id_responsable]
                            WHERE  id = $atr[id]";      
                    $val = $this->verAccionesAC($atr[id]);
                    $this->dbl->insert_update($sql);
                    if ($atr[fecha_realizada] != 'NULL'){
                        $atr[fecha_realizada] = "\\" . substr($atr[fecha_realizada], 0, strlen($atr[fecha_realizada])-1)  . "\'";
                    }
                    $nuevo = "Tipo: \'$atr[tipo]\', Accion: \'$atr[accion]\', Fecha Acordada: \'$atr[fecha_acordada]\', Fecha Realizada: $atr[fecha_realizada], Id Responsable: \'$atr[id_responsable]\' ";
                    $anterior = "Tipo: \'$val[tipo]\', Accion: \'$val[accion]\', Fecha Acordada: \'$val[fecha_acordada]\', Fecha Realizada: \'$val[fecha_realizada]\', Id Responsable: \'$val[id_responsable]\'";
                    $this->registraTransaccionLog(63,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el AccionesAC ' . $atr[descripcion_ano], 'mos_acciones_ac_co');
                    */
                    return "La acción correctiva '$atr[accion]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarAccionesAC($atr, $pag, $registros_x_pagina){
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
                         FROM mos_acciones_ac_co 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND tipo = '". $atr["b-tipo"] . "'";
            if (strlen($atr["b-accion"])>0)
                        $sql .= " AND upper(accion) like '%" . strtoupper($atr["b-accion"]) . "%'";
             if (strlen($atr['b-fecha_acordada-desde'])>0)                        
                    {
                        $atr['b-fecha_acordada-desde'] = formatear_fecha($atr['b-fecha_acordada-desde']);                        
                        $sql .= " AND fecha_acordada >= '" . ($atr['b-fecha_acordada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_acordada-hasta'])>0)                        
                    {
                        $atr['b-fecha_acordada-hasta'] = formatear_fecha($atr['b-fecha_acordada-hasta']);                        
                        $sql .= " AND fecha_acordada <= '" . ($atr['b-fecha_acordada-hasta']) . "'";                        
                    }
             if (strlen($atr['b-fecha_realizada-desde'])>0)                        
                    {
                        $atr['b-fecha_realizada-desde'] = formatear_fecha($atr['b-fecha_realizada-desde']);                        
                        $sql .= " AND fecha_realizada >= '" . ($atr['b-fecha_realizada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_realizada-hasta'])>0)                        
                    {
                        $atr['b-fecha_realizada-hasta'] = formatear_fecha($atr['b-fecha_realizada-hasta']);                        
                        $sql .= " AND fecha_realizada <= '" . ($atr['b-fecha_realizada-hasta']) . "'";                        
                    }
             if (strlen($atr["b-id_responsable"])>0)
                        $sql .= " AND id_responsable = '". $atr["b-id_responsable"] . "'";
             if (strlen($atr["b-id_ac"])>0)
                        $sql .= " AND id_ac = '". $atr["b-id_ac"] . "'";
             if (strlen($atr["b-id_correcion"])>0)
                        $sql .= " AND id_correcion = '". $atr["b-id_correcion"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT acco.id
                                ,ta.descripcion tipo
                                ,accion
                                ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada_a
                                ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada_a
                                ,CONCAT(CONCAT(UPPER(LEFT(per.nombres, 1)), LOWER(SUBSTRING(per.nombres, 2))),' ', CONCAT(UPPER(LEFT(per.apellido_paterno, 1)), LOWER(SUBSTRING(per.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(per.apellido_materno, 1)), LOWER(SUBSTRING(per.apellido_materno, 2)))) as id_responsable
                                ,CASE WHEN NOT acco.fecha_acordada IS NULL THEN 
                                            CASE WHEN NOT acco.fecha_realizada IS NULL THEN
                                                           CASE WHEN acco.fecha_realizada <= acco.fecha_acordada 
                                                                           THEN 'Realizado'
                                                                           ElSE 'Realizado con atraso'
                                                           END
                                                   WHEN CURRENT_DATE() > acco.fecha_acordada THEN 
                                                                   'Plazo vencido'
                                                   ELSE 'En el plazo'
                                           END 
                                   ELSE ''
                                END sema
                                ,CASE WHEN NOT acco.fecha_acordada IS NULL THEN 
                                        CASE WHEN NOT acco.fecha_realizada IS NULL THEN
                                                CASE WHEN acco.fecha_realizada <= acco.fecha_acordada
                                                        THEN 0
                                                    ElSE DATEDIFF(acco.fecha_realizada,acco.fecha_acordada )
                                                END
                                            WHEN CURRENT_DATE() > acco.fecha_acordada THEN 
                                                DATEDIFF(CURRENT_DATE(),acco.fecha_acordada)
                                            ELSE DATEDIFF(acco.fecha_acordada,CURRENT_DATE())
                                        END 
                                    ELSE NULL 
                                END dias
                                ,(select count(id) from mos_acciones_evidencia where id_accion=acco.id) as cantidad 
                                ,id_ac
                                ,id_correcion

                                     $sql_col_left
                            FROM mos_acciones_ac_co acco 
                            INNER JOIN mos_tipo_ac ta ON ta.id = tipo
                            INNER JOIN mos_personal per on per.cod_emp = acco.id_responsable
                            $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND tipo = '". $atr["b-tipo"] . "'";
                    if (strlen($atr["b-accion"])>0)
                        $sql .= " AND upper(accion) like '%" . strtoupper($atr["b-accion"]) . "%'";
                    if (strlen($atr['b-fecha_acordada-desde'])>0)                        
                    {
                        $atr['b-fecha_acordada-desde'] = formatear_fecha($atr['b-fecha_acordada-desde']);                        
                        $sql .= " AND fecha_acordada >= '" . ($atr['b-fecha_acordada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_acordada-hasta'])>0)                        
                    {
                        $atr['b-fecha_acordada-hasta'] = formatear_fecha($atr['b-fecha_acordada-hasta']);                        
                        $sql .= " AND fecha_acordada <= '" . ($atr['b-fecha_acordada-hasta']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_realizada-desde'])>0)                        
                    {
                        $atr['b-fecha_realizada-desde'] = formatear_fecha($atr['b-fecha_realizada-desde']);                        
                        $sql .= " AND fecha_realizada >= '" . ($atr['b-fecha_realizada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_realizada-hasta'])>0)                        
                    {
                        $atr['b-fecha_realizada-hasta'] = formatear_fecha($atr['b-fecha_realizada-hasta']);                        
                        $sql .= " AND fecha_realizada <= '" . ($atr['b-fecha_realizada-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-id_responsable"])>0)
                        $sql .= " AND id_responsable = '". $atr["b-id_responsable"] . "'";
                    /*
                     if (isset($parametros[id_ac])){
                    $_SESSION[id_ac] = $parametros[id_ac];
                    unset ($_SESSION['id_correccion']);
                }
                else if (isset($parametros[id_correccion])){
                    $_SESSION[id_correccion] = $parametros[id_correccion];
                    unset ($_SESSION['id_ac']);
                }
                     */
                    if (strlen($_SESSION[id_ac])>0)
                        $sql .= " AND id_ac = '". $_SESSION[id_ac] . "'";
                    if (strlen($_SESSION[id_correccion])>0)
                        $sql .= " AND id_correcion = '". $_SESSION[id_correccion] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             public function eliminarAccionesAC($atr){
                    try {                        
                        session_name("$GLOBALS[SESSION]");
                        session_start();
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                            FROM mos_acciones_evidencia 
                                            WHERE id_accion = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene evidencias asociadas.";
                        }
                        $val = $this->verAccionesAC($atr[id]);
                        $respuesta = $this->dbl->delete("mos_acciones_ac_co", "id = " . $atr[id]);
                        $nuevo = "Tipo: \'$val[tipo]\', Accion: \'$val[accion]\', Fecha Acordada: \'$val[fecha_acordada]\', Fecha Realizada: \'$val[fecha_realizada]\', Id Responsable: \'$val[id_responsable]\'";
                        $this->registraTransaccionLog(65,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaAccionesAC($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarAccionesAC($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesAC", "");
                $config_col=array(
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "id", $parametros,"link_titulos_hv")),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[tipo], "tipo", $parametros,"link_titulos_hv")),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[accion], "accion", $parametros,"link_titulos_hv")),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[fecha_acordada], "fecha_acordada", $parametros,"link_titulos_hv")),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[fecha_realizada], "fecha_realizada", $parametros,"link_titulos_hv")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[id_responsable], "id_responsable", $parametros,"link_titulos_hv")),
               array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estado_seguimiento], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>"dias"),
               array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[trazabilidad], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_ac], "id_ac", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_correcion], "id_correcion", $parametros))
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
                    array_push($func,array('nombre'=> 'verAccionesAC','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesAC'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarAccionesAC','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar AccionesAC'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarAccionesAC','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar AccionesAC'></i>"));
               
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                $grid->setParent($this);
                //echo $parametros['mostrar-col'];
                //print_r($array_columns);
                //echo count($config_col);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
                        case 7:
                        case 9:
                        case 10:
                            $grid->hidden[$i] = true;
//                            array_push($config,$config_col[$i]);
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
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("cantidad", "cantidad_evidencia");
                $grid->setFuncion("sema", "semaforo_estado");
                $grid->setParent($this);
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
        public function cantidad_evidencia($tupla, $key){
                //,cantidad_evidencia    
            //return $tupla[$key];
            $html = str_pad($tupla[$key],3,0,STR_PAD_LEFT) . ' <a onclick="adminEvidencias('.$tupla[id].')" href="#"><i style="cursor:pointer"  class="icon icon-view-document" title="Evidencias"></i> </a>';
            return $html;
        }
        
        public function semaforo_estado($tupla, $key){
                //,cantidad_evidencia    
            switch ($tupla[$key]) {
                    case 'Realizado':
                        $html = '<img src="diseno/images/realizo.png" title="Realizado"/>';
                        break;
                    case 'Realizado con atraso':
                        $html = '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>';
                        break;
                    case 'Plazo vencido':
                        $html = '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>';
                        break;
                    case 'En el plazo':
                        $html = '<img src="diseno/images/SemPlazo.png" title="En el plazo"/>';
                        break;
                    default:
                        return '';
                        break;
                }
            if (strpos($tupla[$key],"vencido") === false){
                if (strpos($tupla[$key],"atraso") === false){
                    $html .= '<font color="#006600">'." ".str_pad(abs($tupla["dias"]) ,4,0,STR_PAD_LEFT).'</font>';                       
                }
                else{
                    $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias"]) ,4,0,STR_PAD_LEFT).'</font>';
                }
            }
            else{
                $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias"]) ,4,0,STR_PAD_LEFT).'</font>';
            }
            return $html;
        }
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarAccionesAC($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblAccionesAC", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[tipo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[accion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_acordada], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_realizada], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_responsable], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_ac], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_correcion], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexAccionesAC($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $nombre_pestana = "";
                if (isset($parametros[id_ac])){
                    $_SESSION[id_ac] = $parametros[id_ac];
                    unset ($_SESSION['id_correccion']);
                }
                else if (isset($parametros[id_correccion])){
                    $_SESSION[id_correccion] = $parametros[id_correccion];
                    unset ($_SESSION['id_ac']);
                    $nombre_pestana = "_2";
                }
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="fecha_acordada";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-8"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 9;
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
                //echo $parametros['mostrar-col'];
                $grid = $this->verListaAccionesAC($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_AccionesAC();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;AccionesAC';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['OPC'] = "new";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';
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
                $contenido[NOMBRE_PEST_2] = $this->nombres_columnas["nombre_seccion".$nombre_pestana];
                
                $ut_tool = new ut_Tool();
                if (isset($_SESSION[id_correccion])){
                    $value[valor] = 2;
                    $contenido['TIPO_DISPLAY'] = 'display:none;';
                }
                $contenido[TIPOS] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_tipo_ac ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', $value[valor]);
                $contenido[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                //$objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","acciones_ac");
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_ac/acciones_ac.js');
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Acciones ID: " . (isset($_SESSION[id_ac]) ? $_SESSION[id_ac] : $_SESSION[id_correccion]) . "');");
                
                
                //$objResponse->addScript("$('#hv-fecha').datepicker();");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show'); ");
                $objResponse->addScript("$('#hv-fecha_acordada').datepicker();");
                $objResponse->addScript("$('#hv-fecha_realizada').datepicker();");
                $objResponse->addScript('$( "#hv-id_responsable" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); ');
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;AccionesAC";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesAC";
                $contenido['PAGINA_VOLVER'] = "listarAccionesAC.php";
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
                          });");
                $objResponse->addScript("$('#fecha_acordada').datepicker();");
                $objResponse->addScript("$('#fecha_realizada').datepicker();");
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
                    $parametros["fecha_acordada"] = formatear_fecha($parametros["fecha_acordada"]);
                    if (strlen($parametros["fecha_realizada"])>0)
                        $parametros["fecha_realizada"] = formatear_fecha($parametros["fecha_realizada"]);

                    $respuesta = $this->ingresarAccionesAC($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar-hv' ).html('Guardar');
                                        $( '#btn-guardar-hv' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verAccionesAC($parametros[id]); 

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
                $objResponse = new xajaxResponse();
                $contenido_1['TIPO'] = $val["tipo"];
                $objResponse->addScript("$('#hv-tipo').val('$val[tipo]');");
                $objResponse->addScript("$('#hv-accion').html('$val[accion]');");
                $objResponse->addScript("$('#hv-fecha_acordada').val('$val[fecha_acordada]');");
                $objResponse->addScript("$('#hv-fecha_realizada').val('$val[fecha_realizada]');");
                $objResponse->addScript('$("#hv-id_responsable").select2("val", "'.$val["id_responsable"].'")'); 
            $contenido_1['ACCION'] = ($val["accion"]);
            $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
            $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
            $contenido_1['ID_RESPONSABLE'] = $val["id_responsable"];
            $contenido_1['ID_AC'] = $val["id_ac"];
            $contenido_1['ID_CORRECION'] = $val["id_correcion"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;AccionesAC";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesAC";
                $contenido['PAGINA_VOLVER'] = "listarAccionesAC.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                
                
                $objResponse->addScript("$('#id-hv').val('$parametros[id]');");
                $objResponse->addScript("$('#opc-hv').val('upd');");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('.nav-tabs a[href=\"#hv-red\"]').tab('show');");
//                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
//                $objResponse->addScriptCall("calcHeight");
//                $objResponse->addScriptCall("MostrarContenido2");          
//                $objResponse->addScript("$('#MustraCargando').hide();");
//                $objResponse->addScript("$.validate({
//                            lang: 'es'  
//                          });");
//                $objResponse->addScript("$('#fecha_acordada').datepicker();");
//                $objResponse->addScript("$('#fecha_realizada').datepicker();");
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
                    $parametros["fecha_acordada"] = formatear_fecha($parametros["fecha_acordada"]);
                    if (strlen($parametros["fecha_realizada"])>0)
                        $parametros["fecha_realizada"] = formatear_fecha($parametros["fecha_realizada"]);

                    $respuesta = $this->modificarAccionesAC($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar-hv' ).html('Guardar');
                                        $( '#btn-guardar-hv' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verAccionesAC($parametros[id]);
                $respuesta = $this->eliminarAccionesAC($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    $objResponse->addScript("verPagina_hv(1,1);");
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
             public function buscar($parametros)
            {
                $grid = $this->verListaAccionesAC($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid-hv',"innerHTML",$grid[tabla]);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verAccionesAC($parametros[id]);

                            $contenido_1['TIPO'] = $val["tipo"];
            $contenido_1['ACCION'] = ($val["accion"]);
            $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
            $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
            $contenido_1['ID_RESPONSABLE'] = $val["id_responsable"];
            $contenido_1['ID_AC'] = $val["id_ac"];
            $contenido_1['ID_CORRECION'] = $val["id_correcion"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';
                $template->setTemplate("verAccionesAC");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la AccionesAC";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>