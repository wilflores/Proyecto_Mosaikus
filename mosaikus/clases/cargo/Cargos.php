<?php
 import("clases.interfaz.Pagina");        
        class Cargos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        public $nombres_columnas;
        private $placeholder;
        private $campos_activos;
        private $restricciones;


            public function Cargos(){
                parent::__construct();
                $this->asigna_script('cargo/cargo.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = $this->campos_activos = array();
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

            public function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (3,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_campos_activos(){
                $sql = "SELECT campo, activo, orden FROM mos_campos_activos WHERE modulo = 1";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->campos_activos[$value[campo]] = array($value[activo],$value[orden]);
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (3,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }
     

             public function verCargos($id){
                $atr=array();
                $sql = "SELECT cod_cargo
                    ,descripcion
                    ,observacion
                    ,interno
                    ,vigencia
                    ,id_organizacion
                         FROM mos_cargo 
                         left JOIN (select mos_cargo_estrorg_arbolproc.cod_cargo id_cargo , GROUP_CONCAT(id) id_organizacion from mos_cargo_estrorg_arbolproc GROUP BY mos_cargo_estrorg_arbolproc.cod_cargo) AS dao ON  cod_cargo = dao.id_cargo
                         WHERE cod_cargo = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_cargo) total_registros
                         FROM mos_cargo";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function verCargosArbol($id){
                $atr=array();
                $sql = "SELECT cod_cargo
                            ,id

                         FROM mos_cargo_estrorg_arbolproc 
                         WHERE cod_cargo = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }

            public function ingresarCargosArbol($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    //$atr[cod_cargo] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_cargo_estrorg_arbolproc(cod_cargo,id,aplica_subnivel)
                            VALUES(
                                $atr[cod_cargo],$atr[id],0
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_cargo ' . $atr[descripcion_ano], 'mos_cargo');
                      */
                    //$nuevo = "Cod Cargo: \'$atr[cod_cargo]\', Descripcion: \'$atr[descripcion]\', Observacion: \'$atr[observacion]\', Interno: \'$atr[interno]\', Vigencia: \'$atr[vigencia]\', ";
                    //$this->registraTransaccionLog(38,$nuevo,'', '');
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarCargos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[cod_cargo] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_cargo(cod_cargo,descripcion,observacion,interno,vigencia)
                            VALUES(
                                $atr[cod_cargo],'$atr[descripcion]','$atr[observacion]','$atr[interno]','$atr[vigencia]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_cargo ' . $atr[descripcion_ano], 'mos_cargo');
                      */
                    $nuevo = "Cod Cargo: \'$atr[cod_cargo]\', Descripcion: \'$atr[descripcion]\', Observacion: \'$atr[observacion]\', Interno: \'$atr[interno]\', Vigencia: \'$atr[vigencia]\', ";
                    $this->registraTransaccionLog(38,$nuevo,'', '');
                    return $atr[cod_cargo];
                    //return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
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

            public function modificarCargos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_cargo SET                            
                                    descripcion = '$atr[descripcion]',observacion = '$atr[observacion]',interno = '$atr[interno]',vigencia = '$atr[vigencia]'
                            WHERE  cod_cargo = $atr[id]";      
                    $val = $this->verCargos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Cargo: \'$atr[cod_cargo]\', Descripcion: \'$atr[descripcion]\', Observacion: \'$atr[observacion]\', Interno: \'$atr[interno]\', Vigencia: \'$atr[vigencia]\', ";
                    $anterior = "Cod Cargo: \'$val[cod_cargo]\', Descripcion: \'$val[descripcion]\', Observacion: \'$val[observacion]\', Interno: \'$val[interno]\', Vigencia: \'$val[vigencia]\', ";
                    $this->registraTransaccionLog(39,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Cargos ' . $atr[descripcion_ano], 'mos_cargo');
                    */
                    return "El Cargo '$atr[descripcion]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarCargos($atr, $pag, $registros_x_pagina){
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
                    $sql_where = '';
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql_where .= " AND (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    }
                    if (strlen($atr[valor])>0)
                        $sql_where .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_cargo"])>0)
                        $sql_where .= " AND cod_cargo = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql_where .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql_where .= " AND observacion = '". $atr[b-observacion] . "'";
                    if (strlen($atr["b-interno"])>0)
                        $sql_where .= " AND upper(interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql_where .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_cargo 
                         WHERE 1 = 1 ";
                    

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT cod_cargo
                                    ,descripcion
                                    ,observacion
                                    ,CASE interno WHEN 1 THEN 'S' ELSE 'N' END interno
                                    ,vigencia

                                     $sql_col_left
                            FROM mos_cargo $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(descripcion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_cargo"])>0)
                        $sql .= " AND cod_cargo = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND observacion = '". $atr[b-observacion] . "'";
                    if (strlen($atr["b-interno"])>0)
                        $sql .= " AND upper(interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";

                    $this->operacion($sql, $atr);
            }

            public function listarCargosReporte($atr, $pag, $registros_x_pagina)
            {
                $atr = $this->dbl->corregir_parametros($atr);
                $sql_where = "";
                $sql_limit = "";

                $id_org = -1;
                if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){
                    if(!class_exists('ArbolOrganizacional')){
                        import('clases.organizacion.ArbolOrganizacional');
                    }

                    $ao = new ArbolOrganizacional();
                    $id_org = $ao->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                    $sql_where .= " AND mcea.id IN (". $id_org . ")";
                }

                if($atr['pdf_report']=='1') {
                    $sql_limit = " LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                }

                $sql = "SELECT 1, 
                               mcea.id,
                               mo.title,
                                     mcea.cod_cargo,
                               mc.descripcion
                        from   mos_cargo_estrorg_arbolproc mcea,
                                     mos_cargo mc,
                               mos_organizacion mo
                        where  
                               NOT mcea.id IS NULL $sql_where 
                        AND    mcea.cod_cargo = mc.cod_cargo
                        AND    mo.id = mcea.id 
                        AND    mcea.id IN (". implode(',', array_keys($this->id_org_acceso)) . ") 
                        ORDER BY mcea.id,mc.cod_cargo $sql_limit";
                $this->operacion($sql ,$atr);


            }
             
            public function eliminarCargos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_personal 
                                        WHERE cod_cargo = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];  
                        if ($total > 0){
                            return "- No se Puede Eliminar Cargo, Existen Personas Asociadas";
                        }                        
                        
                        $respuesta = $this->dbl->delete("mos_cargo_estrorg_arbolproc", "cod_cargo = $atr[id]");
                        $respuesta = $this->dbl->delete("mos_cargo", "cod_cargo = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
             
             public function eliminarCargosArbol($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_cargo_estrorg_arbolproc", "cod_cargo = $atr[id]");
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     

     public function verListaCargos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarCargos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }                

                $grid->SetConfiguracionMSKS("tblCargos", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Cod Cargo", "cod_cargo", $parametros)),
               array( "width"=>"60%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Observaci&oacute;n", "observacion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[interno], "interno", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros))
                );
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(utf8_decode($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 6;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verCargos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Cargos'>"));



                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarCargos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-edit\" title='Editar Cargos'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarCargos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar Cargos'></i>"));
                */
                if($this->restricciones->per_editar =='S'){
                    array_push($func,array('nombre'=> 'editarCargos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-edit\" title='Editar Cargos'></i>"));
                }
                if($this->restricciones->per_eliminar == 'S'){
                    array_push($func,array('nombre'=> 'eliminarCargos','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar Cargos'></i>"));
                }

                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
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
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
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

        public function verListaCargosReporte($parametros)
        {
            $grid= "";
            $grid= new DataGrid();
            if ($parametros['pag'] == null)
                $parametros['pag'] = 1;
            $reg_por_pagina = getenv("PAGINACION");
            if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina'];

            $out[niveles] = $niveles = $parametros[niveles] = 2;
            $out[titulo] = "";
            if ($niveles == 2){
                $ancho_area = 35;
            }
            else $ancho_area = 30;
            $out[titulo] .= "<th style=\"width: ".$ancho_area  . "%\" >&Aacute;rea</th>" ;
            $out[titulo] .= "<th style=\"width: ". (100  - $ancho_area) / $niveles  . "%\" >Cargos</th>" ;

            $this->listarCargosReporte($parametros, $parametros['pag'], $reg_por_pagina);
            $data=$this->dbl->data;

            $html = "";

            $areas = array();
            $aux_id = $data[0]['id'];
            $cargos = array();

            foreach ($data as $d){
                if($aux_id != $d['id']){
                    $areas[$aux_id] = $cargos;
                    $aux_id = $d['id'];
                    $cargos = array();
                    array_push($cargos, $d['descripcion']);
                }else{
                    array_push($cargos, $d['descripcion']);
                }
            }
            $areas[$aux_id] = $cargos;

            if (!class_exists('ArbolOrganizacional')){
                import('clases.organizacion.ArbolOrganizacional');
            }
            $ao = new ArbolOrganizacional();

            foreach($areas as $k => $values){
                $html .= '<tr class="odd gradeX">';
                $nombre_area = $ao->BuscaOrganizacional(array('id_organizacion'=>$k));
                $html .= '<td>'.$nombre_area.'</td>';
                $html .= '<td>';
                foreach ($values as $v){
                    $html .= $v . "<br/>";
                }
                $html .= '</td>';
                $html .= '</tr>';
            }

            $out[tabla] = $html;
            return $out;
        }
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarCargos($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblCargos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>"Cod Cargo"),
         array( "width"=>"10%","ValorEtiqueta"=>"Descripci&oacute;n"),
         array( "width"=>"10%","ValorEtiqueta"=>"Observacion"),
         array( "width"=>"10%","ValorEtiqueta"=>"Interno"),
         array( "width"=>"10%","ValorEtiqueta"=>"Vigencia")
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
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }

            public    function cargar_acceso_nodos($parametros){
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();
                    $data_ids_acceso = $acceso->obtenerArbolEstructura($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);
                    //print_r($data_ids_acceso);
                    foreach ($data_ids_acceso as $value) {
                        $this->id_org_acceso[$value[id]] = $value;
                    }
                }
            }

        public function indexCargosReporte($parametros)
        {
            $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }
            if ($parametros['corder'] == null) $parametros['corder']="descripcion";
            if ($parametros['sorder'] == null) $parametros['sorder']="asc";
            if ($parametros['mostrar-col'] == null)
                $parametros['mostrar-col']="1-3-4-5-6";

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

            if (count($this->id_org_acceso) <= 0){
                $this->cargar_acceso_nodos($parametros);
            }

            $parametros[reg_por_pagina] = 5000;
            $grid = $this->verListaCargosReporte($parametros);
            $contenido['MODO'] = $parametros['modo'];
            $contenido['COD_LINK'] = $parametros['cod_link'];
            $contenido['CORDER'] = $parametros['corder'];
            $contenido['SORDER'] = $parametros['sorder'];
            $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
            $contenido['TABLA'] = $grid['tabla'];
            $contenido[TITULO_TABLA] = $grid['titulo'];

            $contenido['PAGINADO'] = $grid['paginado'];
            $contenido['FECHA'] = date('d/m/Y');
            $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
            $contenido['JS_NUEVO'] = 'nuevo_ArbolProcesos();';
            $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Cargos';
            $contenido['TABLA'] = $grid['tabla'];
            $contenido['PAGINADO'] = $grid['paginado'];
            $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

            import('clases.organizacion.ArbolOrganizacional');
            $ao = new ArbolOrganizacional();
            $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);

            $template = new Template();
            $template->PATH = PATH_TO_TEMPLATES.'cargo/';
            $contenido[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
            $template->setTemplate("listar_reporte");
            $template->setVars($contenido);


            if (isset($parametros['html']))
                return $template->show();
            $objResponse = new xajaxResponse();
            $objResponse->addAssign('contenido',"innerHTML",$template->show());
            $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
            $objResponse->addAssign('modulo_actual',"value","cargo");
            $objResponse->addIncludeScript(PATH_TO_JS . 'cargo/cargo.js?'. rand());
            $objResponse->addScript("$('#MustraCargando').hide();");
            //$objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
            //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
            $objResponse->addScript('init_filtro_ao_simple();
                                        PanelOperator.initPanels("");
                                        ScrollBar.initScroll();
                                        PanelOperator.resize();');
            return $objResponse;

        }
 
            public function indexCargos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-3-4-5-6";
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
                /*if (!isset($parametros['b-formulario'])){
                    $contenido[OTRAS_OPCIONES] = '<li>
                                    <a href="#"  onClick="reporte_cargos_pdf();">
                                      <i class="icon icon-alert-print"></i>
                                      <span>Cargos</span>
                                    </a>
                                  </li>';
                }*/
                
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
                //$this->restricciones->cargar_acceso_nodos_explicito($parametros);
                $this->restricciones->cargar_permisos($parametros);
                
                $grid = $this->verListaCargos($parametros);
                $contenido['MODO'] = $parametros['modo'];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Cargos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Cargos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                //$contenido['PERMISO_INGRESAR'] = $this->restricciones->per_crear == 'S' ? '' : 'display:none;';
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                //$contenido['PERMISO_INGRESAR'] = 'display:none;';

                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $ao->cargar_acceso_nodos_explicito($parametros);
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(1,$parametros);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
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
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';

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
                $objResponse->addAssign('modulo_actual',"value","cargo");
                $objResponse->addIncludeScript(PATH_TO_JS . 'cargo/cargo.js?'. rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                $objResponse->addScript('init_filtro_ao_simple();');
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
                $contenido_1[CHECKED_INTERNO] = 'checked="checked"';
                $contenido_1[CHECKED_VIGENCIA] = 'checked="checked"';
                /*OBJETO ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $parametros[opcion] = 'simple';
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
                $template->setTemplate("formulario");
                
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Cargos";
                $contenido['PAGINA_VOLVER'] = "listarCargos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";

                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }          
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
                }
                $template->setVars($contenido); 
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");   
                $objResponse->addScript('ao_multiple();');
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
                    if (!isset($parametros[interno])) $parametros[interno] = 0;
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->ingresarCargos($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {    
                        $arr = explode(",", $parametros[nodos]);
                        $params[cod_cargo] = $respuesta;
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarCargosArbol($params);
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',"El Cargo '$parametros[descripcion]' ha sido ingresado con exito");
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
                $val = $this->verCargos($parametros[id]); 

                $contenido_1['COD_CARGO'] = $val["cod_cargo"];
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['OBSERVACION'] = $val["observacion"];
                $contenido_1['INTERNO'] = ($val["interno"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                
                
                //$contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1[CHECKED_VIGENCIA] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
                //$contenido_1['INTERNO'] = ($val["interno"]);
                $contenido_1[CHECKED_INTERNO] = $val["interno"] == '1' ? 'checked="checked"' : '';

                 /*OBJETO ARBOL ORGANIZACIONAL*/
                $organizacion = array();
                if(strpos($val[id_organizacion],',')){    
                        $organizacion = explode(",", $val[id_organizacion]);
                    }
                    else{
                        $organizacion[] = $val[id_organizacion];                    
                    }
                $parametros[nodos_seleccionados] = $organizacion;
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $parametros[opcion] = 'simple';
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Cargos";
                $contenido['PAGINA_VOLVER'] = "listarCargos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["cod_cargo"];

                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }          
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
                }
                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  
                $objResponse->addScript('ao_multiple();');
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
                    if (!isset($parametros[interno])) $parametros[interno] = 0;
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->modificarCargos($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $arr = explode(",", $parametros[nodos]);
                        $params[cod_cargo] = $parametros[id];
                        $this->eliminarCargosArbol($parametros);
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $this->ingresarCargosArbol($params);
                        }
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
                //$val = $this->verCargos($parametros[id]);
                $respuesta = $this->eliminarCargos($parametros);
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
                if (strlen($parametros['b-id_organizacion'])== 0)
                    $parametros['b-id_organizacion'] = 2;
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($parametros);
                }


                if($parametros['modo']=='Portal'){

                    $parametros[reg_por_pagina] = 5000;
                    $grid = $this->verListaCargosReporte($parametros);
                    $html = '<table class="table table-report  ">
                      <thead>
                      <tr>'.$grid[titulo].'</tr>
                      </thead>
                      <tbody>'.$grid[tabla].'</tbody>
                    </table>';
                    $objResponse = new xajaxResponse();
                    $objResponse->addAssign('grid',"innerHTML",$html);
                    //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);

                    $objResponse->addScript("$('#MustraCargando').hide();");
                    return $objResponse;
                }else{
                    import('clases.utilidades.NivelAcceso');
                    $this->restricciones = new NivelAcceso();
                    $this->restricciones->cargar_permisos($parametros);


                    $grid = $this->verListaCargos($parametros);
                    $objResponse = new xajaxResponse();
                    $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                    $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);

                    $objResponse->addScript("$('#MustraCargando').hide();");
                    $objResponse->addScript("PanelOperator.resize();");
                    return $objResponse;
                }



            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verCargos($parametros[id]);

                            $contenido_1['COD_CARGO'] = $val["cod_cargo"];
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['OBSERVACION'] = $val["observacion"];
            $contenido_1['INTERNO'] = ($val["interno"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'cargo/';
                $template->setTemplate("verCargos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Cargos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }

            public function ContarCargosReporte($atr)
            {
                $atr = $this->dbl->corregir_parametros($atr);
                $sql_where = "";

                $id_org = -1;
                if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){
                    if(!class_exists('ArbolOrganizacional')){
                        import('clases.organizacion.ArbolOrganizacional');
                    }

                    $ao = new ArbolOrganizacional();
                    $id_org = $ao->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                    $sql_where .= " AND mcea.id IN (". $id_org . ")";
                }
                $sql = "SELECT count(*) as total_registros
                        from   mos_cargo_estrorg_arbolproc mcea,
                                     mos_cargo mc,
                               mos_organizacion mo
                        where  
                               NOT mcea.id IS NULL $sql_where 
                        AND    mcea.cod_cargo = mc.cod_cargo
                        AND    mo.id = mcea.id
                        AND    mcea.id IN (". implode(',', array_keys($this->id_org_acceso)) . ")";

                $this->operacion($sql ,$atr);

                $total_registros = $this->dbl->query($sql, $atr);
                return $total_registros[0][total_registros];

            }
 }?>