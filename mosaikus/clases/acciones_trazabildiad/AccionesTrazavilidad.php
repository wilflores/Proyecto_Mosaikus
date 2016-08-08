<?php
 import("clases.interfaz.Pagina");        
        class AccionesTrazavilidad extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        
        private $restricciones;
        
            
            public function AccionesTrazavilidad(){
                parent::__construct();
                $this->asigna_script('acciones_trazabildiad/acciones_trazabildiad.js');                                             
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

           
            
            public function colum_admin($tupla)
            {
                $html = "&nbsp;";
                if (strlen($tupla[id_registro])<=0){
                    if($this->per_editar == 'S'){
                        $html .= '<a onclick="javascript:editarAccionesTrazavilidad(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar AccionesTrazavilidad" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarAccionesTrazavilidad(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar AccionesTrazavilidad" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
            public function colum_admin_arbol($tupla)
            {                
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {                    
                    $html = "<a href=\"#\" onclick=\"javascript:editarAccionesTrazavilidad('". $tupla[id] . "');\"  title=\"Editar AccionesTrazavilidad\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= "<a href=\"#\" onclick=\"javascript:eliminarAccionesTrazavilidad('". $tupla[id] . "');\" title=\"Eliminar AccionesTrazavilidad\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                }
                return $html;
            }

     

             public function verAccionesTrazavilidad($id){
                $atr=array();
                $sql = "SELECT id
,id_accion_correctiva
,id_accion
,tipo
,fecha_evi
,id_persona
,observacion

                         FROM mos_acciones_trazabilidad 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarAccionesTrazavilidad($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    /*Carga Acceso segun el arbol*/
                    /*if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                        $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    }                    
                    /*Valida Restriccion*/
                    /*if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]]))
                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';
                    *///id_accion_correctiva,
                    $sql = "INSERT INTO mos_acciones_trazabilidad(id_accion,tipo,fecha_evi,id_persona,observacion)
                            VALUES(
                                $atr[id_accion],'$atr[tipo]','$atr[fecha_evi]',$atr[id_persona],'$atr[observacion]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);//$atr[id_accion_correctiva],
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_acciones_trazabilidad ' . $atr[descripcion_ano], 'mos_acciones_trazabilidad');
                      */
                    $sql = "SELECT MAX(id) ultimo FROM mos_acciones_trazabilidad"; 
                    $this->operacion($sql, $atr);
                    $id_new = $this->dbl->data[0][0];
                    $nuevo = "Id Accion Correctiva: \'$atr[id_accion_correctiva]\', Id Accion: \'$atr[id_accion]\', Tipo: \'$atr[tipo]\', Fecha Evi: \'$atr[fecha_evi]\', Id Persona: \'$atr[id_persona]\', Observacion: \'$atr[observacion]\', ";
                    $this->registraTransaccionLog(20,$nuevo,'', $id_new);
                    return $id_new;
                    return "El mos_acciones_trazabilidad '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = 'NULL'){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip, id_registro) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]',$id)";            
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarAccionesTrazavilidad($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    /*Carga Acceso segun el arbol*/
                    //if (count($this->restricciones->id_org_acceso_explicito) <= 0){
                    //    $this->restricciones->cargar_acceso_nodos_explicito($atr);
                    //}                    
                    /*Valida Restriccion*/
//                    if (!isset($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]]))
//                        return '- Acceso denegado para registrar persona en el &aacute;rea seleccionada.';
//                    if (!(($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][nuevo]== 'S') || ($this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][modificar] == S)))
//                        return '- Acceso denegado para registrar persona en el &aacute;rea ' . $this->restricciones->id_org_acceso_explicito[$atr[id_organizacion]][title] . '.';
// --  id_accion_correctiva = $atr[id_accion_correctiva],"
                    $sql = "UPDATE mos_acciones_trazabilidad SET                            
                              id_accion = $atr[id_accion],tipo = '$atr[tipo]',fecha_evi = '$atr[fecha_evi]',id_persona = $atr[id_persona],observacion = '$atr[observacion]'
                            WHERE  id = $atr[id]";      
                    $val = $this->verAccionesTrazavilidad($atr[id]);
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $nuevo = "Id Accion Correctiva: \'$atr[id_accion_correctiva]\', Id Accion: \'$atr[id_accion]\', Tipo: \'$atr[tipo]\', Fecha Evi: \'$atr[fecha_evi]\', Id Persona: \'$atr[id_persona]\', Observacion: \'$atr[observacion]\', ";
                    $anterior = "Id Accion Correctiva: \'$val[id_accion_correctiva]\', Id Accion: \'$val[id_accion]\', Tipo: \'$val[tipo]\', Fecha Evi: \'$val[fecha_evi]\', Id Persona: \'$val[id_persona]\', Observacion: \'$val[observacion]\', ";
                    $this->registraTransaccionLog(21,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el AccionesTrazavilidad ' . $atr[descripcion_ano], 'mos_acciones_trazabilidad');
                    */
                    return "El mos_acciones_trazabilidad '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarAccionesTrazavilidad($atr, $pag, $registros_x_pagina){
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
                    }
                    
                    if (count($this->restricciones->id_org_acceso) <= 0){
                        $this->restricciones->cargar_acceso_nodos($atr);
                    }*/
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_acciones_trazabilidad 
                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(id_personal) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (1 = 1";
                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
                        } 
                        $sql .= " ) ";
                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-id_accion_correctiva"])>0)
                        $sql .= " AND id_accion_correctiva = '". $atr["b-id_accion_correctiva"] . "'";
             if (strlen($atr["b-id_accion"])>0)
                        $sql .= " AND id_accion = ". $atr["b-id_accion"] . "";
            if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";
             if (strlen($atr["b-fecha_evi"])>0)
                        $sql .= " AND fecha_evi = '". $atr["b-fecha_evi"] . "'";
             if (strlen($atr["b-id_persona"])>0)
                        $sql .= " AND id_persona = '". $atr["b-id_persona"] . "'";
            if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND upper(observacion) like '%" . strtoupper($atr["b-observacion"]) . "%'";

                    if (count($this->restricciones->id_org_acceso)>0){                            
                        $sql .= " AND id_organizacion IN (". implode(',', array_keys($this->restricciones->id_org_acceso)) . ")";
                    }
                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                ,id_accion_correctiva
                                ,id_accion
                                ,tipo
                                ,DATE_FORMAT(fecha_evi, '%d/%m/%Y %H:%i') fecha_evi_a
                                ,id_persona
                                ,observacion
                                ,CONCAT(initcap(SUBSTR(per.nombres,1,IF(LOCATE(' ' ,per.nombres,1)=0,LENGTH(per.nombres),LOCATE(' ' ,per.nombres,1)-1))),' ',initcap(per.apellido_paterno)) as persona
                                $sql_col_left
                            FROM mos_acciones_trazabilidad 
                            INNER JOIN mos_personal per ON per.cod_emp = id_persona
                            $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(id_personal) like '" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (1 = 1";
                        $nombre_supervisor = explode(' ', $atr["b-filtro-sencillo"]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql .= " AND (upper(concat(nombres, ' ', apellido_paterno, ' ' , apellido_materno)) like '%" . strtoupper($supervisor_aux) . "%') ";
                        } 
                        $sql .= " ) ";
                        $sql .= " OR (upper(c.descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-id_accion_correctiva"])>0)
                        $sql .= " AND id_accion_correctiva = '". $atr["b-id_accion_correctiva"] . "'";
             if (strlen($atr["b-id_accion"])>0)
                        $sql .= " AND id_accion = '". $atr["b-id_accion"] . "'";
            if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";
             if (strlen($atr["b-fecha_evi"])>0)
                        $sql .= " AND fecha_evi = '". $atr["b-fecha_evi"] . "'";
             if (strlen($atr["b-id_persona"])>0)
                        $sql .= " AND id_persona = '". $atr["b-id_persona"] . "'";
            if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND upper(observacion) like '%" . strtoupper($atr["b-observacion"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             public function eliminarAccionesTrazavilidad($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verAccionesTrazavilidad($atr[id]);
                        $respuesta = $this->dbl->delete("mos_acciones_trazabilidad", "id = " . $atr[id]);
                        $nuevo = "Id Accion Correctiva: \'$val[id_accion_correctiva]\', Id Accion: \'$val[id_accion]\', Tipo: \'$val[tipo]\', Fecha Evi: \'$val[fecha_evi]\', Id Persona: \'$val[id_persona]\', Observacion: \'$val[observacion]\', ";
                        $this->registraTransaccionLog(86,$nuevo,'', $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaAccionesTrazavilidad($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarAccionesTrazavilidad($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesTrazavilidad", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "id", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_accion_correctiva], "id_accion_correctiva", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_accion], "id_accion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[tipo], "tipo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_evi], "fecha_evi", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_persona], "id_persona", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[observacion], "observacion", $parametros))
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

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 8;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verAccionesTrazavilidad','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesTrazavilidad'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarAccionesTrazavilidad','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar AccionesTrazavilidad'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarAccionesTrazavilidad','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar AccionesTrazavilidad'>"));
               */
                $config=array();
                //$config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
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
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id", "colum_admin");
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarAccionesTrazavilidad($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblAccionesTrazavilidad", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_accion_correctiva], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_accion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[tipo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_evi], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_persona], ENT_QUOTES, "UTF-8")),
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
 
 
            public function indexAccionesTrazavilidad($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_ano";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-4-5-6-7-"; 
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
                $this->restricciones->cargar_permisos($parametros);
                $grid = $this->verListaAccionesTrazavilidad($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_AccionesTrazavilidad();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;AccionesTrazavilidad';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->restricciones->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_trazabildiad/';
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_trazabildiad/';

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
                $objResponse->addAssign('modulo_actual',"value","acciones_trazabildiad");
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_trazabildiad/acciones_trazabildiad.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_simple();');
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_trazabildiad/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;AccionesTrazavilidad";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesTrazavilidad";
                $contenido['PAGINA_VOLVER'] = "listarAccionesTrazavilidad.php";
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
                    
                    $respuesta = $this->ingresarAccionesTrazavilidad($parametros);

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
                        
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verAccionesTrazavilidad($parametros[id]); 

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
            $contenido_1['TIPO'] = ($val["tipo"]);
            $contenido_1['FECHA_EVI'] = $val["fecha_evi"];
            $contenido_1['ID_PERSONA'] = $val["id_persona"];
            $contenido_1['OBSERVACION'] = ($val["observacion"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_trazabildiad/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;AccionesTrazavilidad";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesTrazavilidad";
                $contenido['PAGINA_VOLVER'] = "listarAccionesTrazavilidad.php";
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
                    
                    $respuesta = $this->modificarAccionesTrazavilidad($parametros);

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
                $val = $this->verAccionesTrazavilidad($parametros[id]);
                $respuesta = $this->eliminarAccionesTrazavilidad($parametros);
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
                /*Permisos en caso de que no se use el arbol organizacional*/
                import('clases.utilidades.NivelAcceso');                
                $this->restricciones = new NivelAcceso();
                $this->restricciones->cargar_permisos($parametros);
                $grid = $this->verListaAccionesTrazavilidad($parametros);                
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

                $val = $this->verAccionesTrazavilidad($parametros[id]);

                            $contenido_1['ID_ACCION_CORRECTIVA'] = $val["id_accion_correctiva"];
            $contenido_1['ID_ACCION'] = $val["id_accion"];
            $contenido_1['TIPO'] = ($val["tipo"]);
            $contenido_1['FECHA_EVI'] = $val["fecha_evi"];
            $contenido_1['ID_PERSONA'] = $val["id_persona"];
            $contenido_1['OBSERVACION'] = ($val["observacion"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_trazabildiad/';
                $template->setTemplate("verAccionesTrazavilidad");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la AccionesTrazavilidad";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>