<?php
 import("clases.interfaz.Pagina");        
        class AccionesCorrectivas extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        private $funciones;
            
            public function AccionesCorrectivas(){
                parent::__construct();
                $this->asigna_script('acciones_correc/acciones_correc.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = $this->funciones = array();
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros(){
                $sql = "Select cod_categoria"
                        . ",id_cmb_acap"
                        . ",nombre"
                        . ",tipo"
                        . ",dependencia "
                        . ",indicador,fecha_nom1,fecha_nom2,fecha_sem,datos"
                        . " from mos_matrices_parametros "
                        . " where dependencia in ('2','1') and cod_categoria=8 "                        
                        . " order by dependencia, orden";
                //echo $sql;
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            private function cargar_valores_parametros($id){
                $sql = "Select mp.cod_categoria
                                ,mp.id_cmb_acap
                                ,mp.nombre
                                ,mp.tipo
                                ,mp.dependencia 
                                ,md.descripcion
                                ,md.id_item
                         from mos_matrices_parametros mp
                            left JOIN mos_matrices_detalle md on md.id_cmb_acap = mp.id_cmb_acap and md.cod_categoria = mp.cod_categoria and md.id_acap = $id
                         where  mp.dependencia in ('1') and mp.cod_categoria=8                        
                         order by mp.dependencia, mp.orden";
                //echo $sql;
                return $this->dbl->query($sql, array());
            }
            
            private function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 10";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 10";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verAccionesCorrectivas($id){
                $atr=array();
                $sql = "SELECT cod_categoria
                        ,id_acap
                        ,cod_cargo
                        ,cod_emp
                        ,bloqueo
                        ,id_proceso
                        ,id_organizacion

                         FROM mos_matrices 
                         WHERE id_acap = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            //select ifnull(max(cast(descripcion as signed))+1,1) as maximo from mos_matrices_detalle where id_item='txt' and id_cmb_acap='0'
                       
            
            private function codigo_siguiente_id(){
                $sql = "SELECT ifnull(max(cast(descripcion as signed)),1) total_registros
                         FROM mos_matrices_detalle  where id_item='txt' and id_cmb_acap='0'";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT max(id_acap) total_registros
                         FROM mos_matrices";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarAccionesCorrectivas($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[id_acap] = $this->codigo_siguiente();
                    $atr[cod_categoria] = 8;
                    $atr[bloqueo] = 'N';                    
                    if (strlen($atr[cod_cargo]) == 0){
                        $atr[cod_cargo] = 'NULL';
                    }
                    if (strlen($atr[cod_emp]) == 0){
                        $atr[cod_emp] = 'NULL';
                    }
                    if (strlen($atr[id_proceso]) == 0){
                        $atr[id_proceso] = 'NULL';
                    }
                    if (strlen($atr[id_organizacion]) == 0){
                        $atr[id_organizacion] = 'NULL';
                    }
                    
                    $sql = "INSERT INTO mos_matrices(cod_categoria,id_acap,cod_cargo,cod_emp,bloqueo,id_proceso,id_organizacion)
                            VALUES(
                                $atr[cod_categoria],$atr[id_acap],$atr[cod_cargo],$atr[cod_emp],'$atr[bloqueo]',$atr[id_proceso],$atr[id_organizacion]
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_matrices ' . $atr[descripcion_ano], 'mos_matrices');
                      */
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Acap: \'$atr[id_acap]\', Cod Cargo: \'$atr[cod_cargo]\', Cod Emp: \'$atr[cod_emp]\', Bloqueo: \'$atr[bloqueo]\', Id Proceso: \'$atr[id_proceso]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                    $this->registraTransaccionLog(60,$nuevo,'', '');
                    return $atr[id_acap];
                    return "El mos_matrices '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarCampoDinamico($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion                    
                    $sql = "INSERT INTO mos_matrices_detalle(cod_categoria,id_acap,id_cmb_acap,id_item,vigencia,descripcion)
                            VALUES(
                                $atr[cod_categoria],$atr[id_acap],$atr[id_cmb_acap],'$atr[id_item]','$atr[vigencia]','$atr[descripcion]'
                                )";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]                    
                    $this->dbl->insert_update($sql);
                    
                    return "El mos_registro '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarAccionesCorrectivas($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    if (strlen($atr[cod_cargo]) == 0){
                        $atr[cod_cargo] = 'NULL';
                    }
                    if (strlen($atr[cod_emp]) == 0){
                        $atr[cod_emp] = 'NULL';
                    }
                    if (strlen($atr[id_proceso]) == 0){
                        $atr[id_proceso] = 'NULL';
                    }
                    if (strlen($atr[id_organizacion]) == 0){
                        $atr[id_organizacion] = 'NULL';
                    }
                    $sql = "UPDATE mos_matrices SET                            
                                    cod_cargo = $atr[cod_cargo],cod_emp = $atr[cod_emp],id_proceso = $atr[id_proceso],id_organizacion = $atr[id_organizacion]
                            WHERE  id_acap = $atr[id]";      
                    $val = $this->verAccionesCorrectivas($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Acap: \'$atr[id_acap]\', Cod Cargo: \'$atr[cod_cargo]\', Cod Emp: \'$atr[cod_emp]\', Id Proceso: \'$atr[id_proceso]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                    $anterior = "Cod Categoria: \'$val[cod_categoria]\', Id Acap: \'$val[id_acap]\', Cod Cargo: \'$val[cod_cargo]\', Cod Emp: \'$val[cod_emp]\',Id Proceso: \'$val[id_proceso]\', Id Organizacion: \'$val[id_organizacion]\', ";
                    $this->registraTransaccionLog(61,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el AccionesCorrectivas ' . $atr[descripcion_ano], 'mos_matrices');
                    */
                    return "El mos_matrices '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
        public function BuscaOrgNivelHijos($IDORG)
        {
            $OrgNom = $IDORG;
            //$Consulta3="select id_organizacion,organizacion_padre,identificacion from mos_organizacion where organizacion_padre='".$IDORG."' and id_filial='".$Filial."' order by id_organizacion";
            $Consulta3="select id as id_organizacion, parent_id as organizacion_padre, title as identificacion from mos_organizacion where parent_id='".$IDORG."' order by id";
            //echo $Consulta3;
            //$Resp3=mysql_query($Consulta3);
            //while($Fila3=mysql_fetch_assoc($Resp3))
            $data = $this->dbl->query($Consulta3,array());
            foreach( $data as $Fila3)
            {
                    //$OrgNom=$OrgNom.",".$Fila3[id_organizacion];
                    $OrgNom .= ",".$this->BuscaOrgNivelHijos($Fila3[id_organizacion]);
            }
            return $OrgNom;
        }
        
        public function BuscaPrcNivelHijos($IDORG)
        {
            $OrgNom = $IDORG;
            //$Consulta3="select id_organizacion,organizacion_padre,identificacion from mos_organizacion where organizacion_padre='".$IDORG."' and id_filial='".$Filial."' order by id_organizacion";
            $Consulta3="select id as id_organizacion, parent_id as organizacion_padre, title as identificacion from mos_arbol_procesos where parent_id='".$IDORG."' order by id";
            //echo $Consulta3;
            //$Resp3=mysql_query($Consulta3);
            //while($Fila3=mysql_fetch_assoc($Resp3))
            $data = $this->dbl->query($Consulta3,array());
            foreach( $data as $Fila3)
            {
                    //$OrgNom=$OrgNom.",".$Fila3[id_organizacion];
                    $OrgNom .= ",".$this->BuscaOrgNivelHijos($Fila3[id_organizacion]);
            }
            return $OrgNom;
        }
        
             public function listarAccionesCorrectivas($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                     if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 1;                    
                    foreach ($this->parametros as $value) {
                        //,CONCAT(CONCAT(UPPER(LEFT(ap.nombres, 1)), LOWER(SUBSTRING(ap.nombres, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_paterno, 1)), LOWER(SUBSTRING(ap.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_materno, 1)), LOWER(SUBSTRING(ap.apellido_materno, 2)))) aprobo
                        //if ($value[tipo]== '6'){//-- , t1.Nombre as nom_detalle        
                        if ($value[dependencia]=='1')
                        {
                            switch ($value[tipo]) {
                                case '2':
                                
                                    $sql_left .= " LEFT JOIN mos_matrices_detalle p$k on m.id_acap=p$k.id_acap and m.cod_categoria=p$k.cod_categoria and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                    if ($value[id_cmb_acap] == '0')
                                        $sql_col_left .= ",CAST(p$k.descripcion AS UNSIGNED)  p$k ";
                                    else
                                        $sql_col_left .= ",p$k.descripcion p$k ";
                                    break;
                                case '3':
                                    $sql_left .= " LEFT JOIN mos_matrices_detalle p$k on m.id_acap=p$k.id_acap and m.cod_categoria=p$k.cod_categoria and p$k.id_cmb_acap=$value[id_cmb_acap] ";                                     
                                    $sql_col_left .= ",p$k.descripcion p$k ";
                                    if ($atr[corder] == "p$k"){
                                        $atr[corder] = "STR_TO_DATE(p$k.descripcion, '%d/%m/%Y')";
                                    }
                                    break;
                                case '1'://Combo
                                    $sql_left .= " LEFT JOIN mos_matrices_detalle p$k on m.id_acap=p$k.id_acap and m.cod_categoria=p$k.cod_categoria and p$k.id_cmb_acap=$value[id_cmb_acap] "
                                        . " LEFT JOIN mos_matrices_parametros_detalle pd$k on m.cod_categoria=pd$k.cod_categoria and pd$k.id_cmb_acap=$value[id_cmb_acap] and pd$k.id_item = p$k.id_item "; 
                                    $sql_col_left .= ",CASE p$k.id_item WHEN 'Acc' THEN 'Accidentes Ley' WHEN 'Inc' THEN 'Incidentes' ELSE pd$k.nombre END  p$k ";
                                    break;
                                case '4':
                                    $sql_left .= " LEFT JOIN mos_matrices_detalle p$k on m.id_acap=p$k.id_acap and m.cod_categoria=p$k.cod_categoria and p$k.id_cmb_acap=$value[id_cmb_acap] "
                                        . " left join mos_personal p on p.cod_emp = CAST(p$k.id_item AS UNSIGNED) "; 
                                    $sql_col_left .= ",CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as p$k ";
                                    break;
                                case '5':
                                    $sql_left .= " LEFT JOIN mos_matrices_detalle p$k on m.id_acap=p$k.id_acap and m.cod_categoria=p$k.cod_categoria and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                    $sql_col_left .= ",CASE WHEN p$k.id_item='Chk' AND  p$k.descripcion = '1' "
                                            . "THEN 'Bueno' "
                                            . "ELSE 'Malo' END p$k ";
                                    if ($registros_x_pagina == 100000)
                                        $this->funciones["p$k"] = 'estado_columna_excel';
                                    else
                                        $this->funciones["p$k"] = 'estado_columna'; 
                                    break;
                                default:
                                    break;
                            }
                                                                                       
                            
                        }
                        //else{
                        //    $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                        //    where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                        //}
                        
                        $k++;
                    }
                    
                    $sql_din = '';
                    $k = 1;   
                    foreach ($this->parametros as $value) {
                        switch ($value[tipo]) {                           
                           case '2':
                            //case '6':
                                if (strlen($atr["campo_".$value[id_cmb_acap]])>0){
                                    if ($value[id_cmb_acap] == '0')
                                        $sql_din .= " AND p$k.descripcion = '". $atr["campo_".$value[id_cmb_acap]] . "'";
                                    else
                                    $sql_din .= " AND p$k.descripcion like '%". $atr["campo_".$value[id_cmb_acap]] . "%'";
                                }                                
                                break;
                            case '3':
                                if (strlen($atr["campo_".$value[id_cmb_acap]."_desde"])>0){
//                                if (strlen($atr["p$k"])>0){
                                    $sql_din .= " AND STR_TO_DATE(p$k.descripcion, '%d/%m/%Y') >= '". formatear_fecha($atr["campo_".$value[id_cmb_acap]."_desde"]) . "'";
                                } 
                                if (strlen($atr["campo_".$value[id_cmb_acap]."_hasta"])>0){
//                                if (strlen($atr["p$k"])>0){
                                    $sql_din .= " AND STR_TO_DATE(p$k.descripcion, '%d/%m/%Y') <= '". formatear_fecha($atr["campo_".$value[id_cmb_acap]."_hasta"]) . "'";
                                } 
                                break;
                            case '1':                           
                                if (strlen($atr["campo_".$value[id_cmb_acap]])>0){
                                    $sql_din .= " AND p$k.id_item = '". $atr["campo_".$value[id_cmb_acap]] . "'";
                                }                                
                                break;
                            case '4':                           
                                if (strlen($atr["campo_".$value[id_cmb_acap]])>0){
                                    $sql_din .= " AND p$k.id_item = '". $atr["campo_".$value[id_cmb_acap]] . "'";
                                }                                
                                break;
                            case '5':                           
                                if (strlen($atr["campo_".$value[id_cmb_acap]])>0){
                                    $sql_din .= " AND p$k.descripcion = '". $atr["campo_".$value[id_cmb_acap]] . "'";
                                }                                
                                break;
                            default:
                                break;
                        }
                        //$sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                                
                        //where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                        //$sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_matrices m $sql_left
                         WHERE m.cod_categoria = 8 $sql_din ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
                    if (strlen($atr["b-id_acap"])>0)
                        $sql .= " AND id_acap = '". $atr["b-id_acap"] . "'";
                    if (strlen($atr["b-cod_cargo"])>0)
                        $sql .= " AND cod_cargo = '". $atr["b-cod_cargo"] . "'";
                    if (strlen($atr["b-cod_emp"])>0)
                        $sql .= " AND cod_emp = '". $atr["b-cod_emp"] . "'";
                    if (strlen($atr["b-bloqueo"])>0)
                        $sql .= " AND upper(bloqueo) like '%" . strtoupper($atr["b-bloqueo"]) . "%'";
                    if (strlen($atr["b-id_proceso"])>0){
                        $id_prc = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $sql .= " AND m.id_proceso IN (". $id_prc . ")";
                    }
                    if (strlen($atr["b-id_organizacion"])>0){
                        $id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $sql .= " AND m.id_organizacion In (". $id_org . ")";
                    }                    

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT 
                            m.id_acap
                            ,m.cod_categoria
                            ,m.cod_cargo
                            ,m.cod_emp
                            ,bloqueo
                            ,m.id_organizacion
                            ,id_proceso
                            
                            ,(select count(*) from mos_matrices_control_detalle_cabecera  where id_acap=m.id_acap and cod_categoria=8) num_acc
                                     $sql_col_left
                            FROM mos_matrices m $sql_left
                            WHERE m.cod_categoria = 8 $sql_din ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
                    if (strlen($atr["b-id_acap"])>0)
                        $sql .= " AND id_acap = '". $atr["b-id_acap"] . "'";
                    if (strlen($atr["b-cod_cargo"])>0)
                        $sql .= " AND cod_cargo = '". $atr["b-cod_cargo"] . "'";
                    if (strlen($atr["b-cod_emp"])>0)
                        $sql .= " AND cod_emp = '". $atr["b-cod_emp"] . "'";
                    if (strlen($atr["b-bloqueo"])>0)
                        $sql .= " AND upper(bloqueo) like '%" . strtoupper($atr["b-bloqueo"]) . "%'";
                    if (strlen($atr["b-id_proceso"])>0)
                        $sql .= " AND m.id_proceso IN (". $id_prc . ")";
                    if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND m.id_organizacion In (". $id_org . ")";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarAccionesCorrectivas($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                            FROM mos_matrices_control_detalle_cabecera 
                                            WHERE id_acap = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene accion(es) asociada(s).";
                        }
                        $respuesta = $this->dbl->delete("mos_matrices", "cod_categoria = 8 AND id_acap = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_matrices_control_detalle", "cod_categoria = 8 AND id_acap = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_matrices_detalle", "cod_categoria = 8 AND id_acap = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_matrices_control_detalle_cabecera", "cod_categoria = 8 AND id_acap = " . $atr[id]);
                        $nuevo = "cod_categoria: \'8\', id_acap:\'$atr[id]\'";
                        $this->registraTransaccionLog(64,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaAccionesCorrectivas($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarAccionesCorrectivas($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesCorrectivas", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_categoria], "cod_categoria", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_acap], "id_acap", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_cargo], "cod_cargo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_emp], "cod_emp", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[bloqueo], "bloqueo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_proceso], "id_proceso", $parametros)),
               
                    array( "width"=>"10%","ValorEtiqueta"=>"Num Acciones")
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 8;
                $band = 0;
                $column_semaforo_final = 0;
                foreach ($this->parametros as $value) {  
                    if (($value[dependencia]=='2')&&($band == 0)){
                        $band = 1;
                        array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                        $column_semaforo_final = $k;
                        //echo $column_semaforo_final;
                        //$k++;
                        $k=$k+2; 
                        
                        //$parametros['mostrar-col'] .= "-$k";    
                        
                        
                        array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                        array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                        //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                        array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                    }
                    switch ($value[tipo]) {
                        case '2':
                            if ($value[id_cmb_acap]=='0'){
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            }
                            else if ($value[id_cmb_acap]=='4'){
                                array_push($config_col,array( "width"=>"6%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            }
                            else array_push($config_col,array( "width"=>"9%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            break;
                        case '3':
                            if (($value[dependencia]=='2')&&($value[indicador]=='S')){//fecha_nom1,fecha_nom2,fecha_sem,datos
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom1]), "p$k", $parametros)));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom2]), "p$k", $parametros)));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_sem]), "p$k", $parametros)));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Dias"));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[datos]), "p$k", $parametros)));
                                
                            }else
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            break;
                        case '1':
                            array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            break;
                        case '4':
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));                                    break;
                        case '5':
                            array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            
                            break;
                        default:
                            break;
                    }

                    $k++;
                }
                if ($column_semaforo_final == 0)
                    $column_semaforo_final = $k;
                
                $sql = "Select * from mos_matrices_parametro_general where agrupacion in ('3') and cod_categoria='8'";
                $data_aux = $this->dbl->query($sql,array());
                foreach ($data_aux as $value) {
                    switch ($value[cod_param]) {
                        case '1':
                            $config_col[5] = array( "width"=>"10%","ValorEtiqueta"=>link_titulos($value[descripcion], "id_organizacion", $parametros));
                            break;
                        case '2':
                            $config_col[6] = array( "width"=>"10%","ValorEtiqueta"=>link_titulos($value[descripcion], "id_proceso", $parametros));
                            break;

                        default:
                            $config_col[$column_semaforo_final] = array( "width"=>"2%","ValorEtiqueta"=>(($value[descripcion])));
                            break;
                    }
                }
                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 8;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesCorrectivas'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar AccionesCorrectivas'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar AccionesCorrectivas'>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
                            $grid->hidden[$i] = true;
                            
                            break;
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
                $this->hidden = $grid->hidden;
                
                //$grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);                
                $titulosColumna="<thead><tr bgcolor=\"#FFFFFF\" height=\"30px\">";
                foreach($config as $detalle){
                    $titulosColumna.="<th ";
                    foreach($detalle as $key=>$value){
                        if ($key!='ValorEtiqueta')
                           $titulosColumna.=" $key = \"$value\"  ";
                        else
                        $titulosColumna.="><div align=\"left\">$value</div></th>\n";
                    }
                }
                $titulosColumna.="</tr></thead>";
                $this->funciones["id_organizacion"] = "BuscaOrganizacional";
                $this->funciones["id_proceso"] = "BuscaProceso";
                //BuscaProceso
                $colbotones = $columna_funcion;
                $funciones = array();
                $datos = '';
                if ((is_array($data)) && (count($data)>0)) {
                    foreach($data as $fila ){               
                        if($fila[0]!=-1){
                            $col=0;                                                    
                            $fila[num_acc] = ($fila[num_acc]) == '0' ? 1 : $fila[num_acc];
                            $datos.="<tr onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'  class=\"DatosGrilla\">";                            
                                $datos.="	<td rowspan=\"$fila[num_acc]\" align=\"center\" $atributos>";
                                
                                if($_SESSION[CookM] == 'S'){
                                    $datos .= "<a onclick=\"javascript:editarAccionesCorrectivas('". $fila[id_acap] . "');\">
                                                <img title=\"Modificar Acción Correctiva ". $fila['p1']."\" src=\"diseno/images/ico_modificar.png\" style=\"cursor:pointer\">
                                            </a>";
                                }
                                if($_SESSION[CookE] == 'S'){
                                    $datos .= '<a onclick="javascript:eliminarAccionesCorrectivas(\''. $fila[id_acap] . '\');">
                                            <img title="Eliminar Acción Correctiva '.$fila[p1].'" src="diseno/images/ico_eliminar.png" style="cursor:pointer">
                                        </a>'; 
                                }
                                if(($_SESSION[CookN] == 'S')||($_SESSION[CookM] == 'S')){
                                    $datos .= "<a onclick=\"javascript:verAcciones('". $fila[id_acap] . "','". $fila[p1] . "');\">
                                                <img title=\"Administrar Acciones Correctivas ". $fila['p1']."\" src=\"diseno/images/ico_explorer.png\" style=\"cursor:pointer\">
                                            </a>";
                                }
                                
                                
                                $datos.="	</td>\n";
                            

                            foreach($fila as $key=>$value){
                                
                                if ($col == 0) $col_id = $key;                       
                                if (!is_integer($key))
                                {                       
                                    //echo $key . ' - ';
                                    if($this->hidden[$col]==true){
                                        //echo $col . ' ';
                                    }
                                    elseif ($col==$this->hide)
                                        $datos.="<td rowspan=\"$fila[num_acc]\"  $atributos style=\"display:none\" > $fila[$col] &nbsp;</td>\n";
                                    else
                                    {
                                        //if(!is_numeric($this->valorColumna($key,$fila)))
                                        {
                                            if(isset($this->funciones[$key])){
                                                $function  =  $this->funciones[$key];
                                                //if ($this->Parent == null)
                                                  //@eval(" \$valor = \$function (\$Valores);");
                                                //else
                                                  @eval(" \$valor = \$this->$function (\$fila,\$key);");
                                              }
                                              else{
                                                $valor = htmlentities($fila[$key], ENT_QUOTES, "UTF-8");
                                                $valor = $fila[$key];
                                              }
                                            
                                            //$valor=$this->valorColumna($key,$fila);
                                        }
//                                        else
//                                            if(strpos($this->valorColumna($key,$fila), '.')===false)
//                                                    $valor=number_format($this->valorColumna($key,$fila),0,'','');
//                                            else
//                                                $valor=number_format($this->valorColumna($key,$fila),2,',','.');                                       
                                        //$datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_decode($valor)."</td>\n";
                                       // echo $key . ' - ';
//                                        if (($key == 'p5')||($key == 'p6')||($key == 'p4')||($key == 'p3')){                                        
//                                            $valor = $fila[$key];
//                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_encode($valor)."</td>\n";
//                                        }
//                                        else if ($key == 'p7'){
//                                            $valor = $fila[$key];
//                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_encode($valor)."</td>\n";
//                                        }
//                                        else 
                                            $datos.="<td rowspan=\"$fila[num_acc]\" $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                    }
                                    $col++;
                                }

                            }
                            $k = 100;
                            $sql_left = $sql_col_left ='';
                            foreach ($this->parametros as $value) {                                
                                if ($value[dependencia]=='2')
                                {
                                    switch ($value[tipo]) {
                                        case '2':                                        
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                            $sql_col_left .= ",p$k.descripcion p$k ";
                                            break;
                                        case '3'://  m.id_acap=p$k.id_acap and m.cod_categoria=p$k.cod_categoria and p$k.id_cmb_acap=$value[id_cmb_acap]
                                            if ($value[indicador] == 'S'){
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p_1$k on p_1$k.cod_categoria = 8 and p_1$k.id_acap = cdc.id_acap and p_1$k.id_control_detalle = cdc.id_control_detalle and p_1$k.id_cmb_acap=1$value[id_cmb_acap] "; 
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p_2$k on p_2$k.cod_categoria = 8 and p_2$k.id_acap = cdc.id_acap and p_2$k.id_control_detalle = cdc.id_control_detalle and p_2$k.id_cmb_acap=2$value[id_cmb_acap] "; 
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p_3$k on p_3$k.cod_categoria = 8 and p_3$k.id_acap = cdc.id_acap and p_3$k.id_control_detalle = cdc.id_control_detalle and p_3$k.id_cmb_acap=$value[id_cmb_acap] "
                                                . " left join mos_personal p_p$k on p_p$k.cod_emp = CAST(p_3$k.cod_workflow AS UNSIGNED) "; 
                                                $sql_col_left .= ",p_1$k.descripcion p_1$k,p_2$k.descripcion p_2$k ";
                                                $sql_col_left .= ",CASE WHEN p_2$k.descripcion <> '' THEN
                                                                            CASE WHEN STR_TO_DATE(p_2$k.descripcion, '%d/%m/%Y') <= STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') 
                                                                                THEN '<img src=\"diseno/images/realizo.png\" title=\"Realizado\"/>'
                                                                                ElSE '<img src=\"diseno/images/SemPlazoAtrasado.png\" title=\"Realizado con atraso\"/>'
                                                                            END
                                                                        WHEN CURRENT_DATE() > STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') THEN 
                                                                            '<img src=\"diseno/images/atrasado.png\" title=\"Plazo vencido\"/>'
                                                                        ELSE '<img src=\"diseno/images/SemPlazo.png\" title=\"En el plazo\"/>'
                                                                  END AS sem$k";
                                                $sql_col_left .= ",CASE WHEN p_2$k.descripcion <> '' THEN
                                                                            CASE WHEN STR_TO_DATE(p_2$k.descripcion, '%d/m/%Y') <= STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') 
                                                                                    THEN 0
                                                                                    ElSE DATEDIFF(STR_TO_DATE(p_2$k.descripcion, '%d/%m/%Y'),STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') )
                                                                            END
                                                                        WHEN CURRENT_DATE() > STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') THEN 
                                                                            DATEDIFF(CURRENT_DATE(),STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y'))
                                                                        ELSE DATEDIFF(STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y'),CURRENT_DATE())
                                                                    END dias$k";
                                                $sql_col_left .= ",CONCAT(CONCAT(UPPER(LEFT(p_p$k.nombres, 1)), LOWER(SUBSTRING(p_p$k.nombres, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_paterno, 1)), LOWER(SUBSTRING(p_p$k.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_materno, 1)), LOWER(SUBSTRING(p_p$k.apellido_materno, 2)))) as p$k ";
                                            }else{
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                                $sql_col_left .= ",p$k.descripcion p$k ";
                                            }
                                            $this->funciones["sem$k"] = "semaforo_estado";
                                            break;
                                        case '1':
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "
                                                . " LEFT JOIN mos_matrices_parametros_detalle pd$k on cdc.cod_categoria=pd$k.cod_categoria and pd$k.id_cmb_acap=$value[id_cmb_acap] and pd$k.id_item = p$k.id_item "; 
                                            $sql_col_left .= ",pd$k.nombre p$k ";
                                            break; 
                                        case '4':
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "
                                                . " left join mos_personal p_p$k on p_p$k.cod_emp = CAST(p$k.id_item AS UNSIGNED) "; 
                                            $sql_col_left .= ",CONCAT(CONCAT(UPPER(LEFT(p_p$k.nombres, 1)), LOWER(SUBSTRING(p_p$k.nombres, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_paterno, 1)), LOWER(SUBSTRING(p_p$k.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_materno, 1)), LOWER(SUBSTRING(p_p$k.apellido_materno, 2)))) as p$k ";
                                            break;
                                        case '5':
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                            $sql_col_left .= ",CASE WHEN p$k.id_item='Chk' AND  p$k.descripcion = '1' "
                                                    . "THEN '<img src=\"diseno/images/verde.png\"/ title=\"Verde\">' "
                                                    . "ELSE '<img src=\"diseno/images/rojo.png\"/ title=\"Rojo\">' END p$k ";
                                            break;
                                        default:
                                            break;
                                    }


                                }
                                //else{
                                //    $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                                //    where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                                //}

                                $k++;
                            }
                            $sql = "SELECT                                        
                                        
                                        cdc.id_control_detalle
                                        ,peso_especifico
                                        ,(select count(id_acap) from mos_matrices_evidencia_detalle where id_acap=cdc.id_acap and id_control_detalle=cdc.id_control_detalle and cod_categoria=8) as cantidad 

                                        $sql_col_left
                                        FROM mos_matrices_control_detalle_cabecera cdc                                     
                                    $sql_left
                                        WHERE cdc.cod_categoria = 8 AND cdc.id_acap = $fila[id_acap]";
                            echo $sql;
                            //semaforo_estado,cantidad_evidencia
                            $this->funciones["cantidad"] = "cantidad_evidencia";
                            $data_aux = $this->dbl->query($sql, array());
                            //print_r($data_aux);
                            $num_filas_recorridas_int = 0;
                            $col_aux = $col + 1;
                            $total_semaforo_final = 0;
                            foreach ($data_aux as $fila_aux) {
                                $num_factor_sema = 0; //para el calculo del semaforo final numero de factores
                                $sum_factor_sema = 0; //para el calculo del semaforo final numero de acciones completadas
                                $plazo_vencido = 0;
                                $plazo_atrasado = 0;
                                $plazo_plazo = 0;
                                foreach($fila_aux as $key=>$value){
                                    switch ($value) {
                                        case '<img src="diseno/images/realizo.png" title="Realizado"/>':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            break;
                                        case '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            $plazo_atrasado = 1;
                                            break;
                                        case '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>':
                                            $num_factor_sema++;
                                            $plazo_vencido = 1;
                                            break;
                                        case '<img src="diseno/images/SemPlazo.png" title="En el plazo"/>':
                                            $num_factor_sema++;
                                            $plazo_plazo = 1;
                                            break;

                                        default:
                                            //echo $value;
                                            break;
                                    }
                                }
                                if ($num_factor_sema>0)
                                    $total_semaforo_final = $total_semaforo_final + ($fila_aux[peso_especifico]/$num_factor_sema)*$sum_factor_sema;

                            }
                            $total_semaforo_final;
                            //$valor = $total_semaforo_final;
                            if ($plazo_vencido >= 1){
                                $valor = '<img src="diseno/images/atrasado.png" title="Valor Final ' . round($total_semaforo_final) . '"/>';
                            }
                            else if ($plazo_plazo >= 1){
                                $valor = '<img src="diseno/images/SemPlazo.png" title="Valor Final ' . round($total_semaforo_final) . '"/>';
                            }
                            else if ($plazo_atrasado >= 1){
                                $valor = '<img src="diseno/images/SemPlazoAtrasado.png" title="Valor Final ' . round($total_semaforo_final) . '"/>';
                            }
                            else{
                                $valor = '<img src="diseno/images/realizo.png" title="Valor Final ' . round($total_semaforo_final) . '"/>';
                            }
                            $datos.="<td rowspan=\"$fila[num_acc]\" align='center'>". ($valor)."</td>\n";     
                            
                            foreach ($data_aux as $fila_aux) {
                                $num_filas_recorridas_int++;
                                $col = $col_aux;
                                
                                foreach($fila_aux as $key=>$value){
                                    if ($col == 0) $col_id = $key;                       
                                    if (!is_integer($key))
                                    {                       
                                        //echo $key . ' - ';
                                        if($this->hidden[$col]==true){
                                            //echo $col . ' ';
                                        }
                                        elseif ($col==$this->hide)
                                            $datos.="<td $atributos style=\"display:none\" > $fila_aux[$col] &nbsp;</td>\n";
                                        else
                                        {
                                            //if(!is_numeric($this->valorColumna($key,$fila)))
                                            {
                                                if(isset($this->funciones[$key])){
                                                    $function  =  $this->funciones[$key];
                                                    //if ($this->Parent == null)
                                                      //@eval(" \$valor = \$function (\$Valores);");
                                                    //else
                                                      @eval(" \$valor = \$this->$function (\$fila_aux,\$key);");
                                                  }
                                                  else{
                                                    $valor = htmlentities($fila_aux[$key], ENT_QUOTES, "UTF-8");
                                                    $valor = $fila_aux[$key];
                                                  }

                                                //$valor=$this->valorColumna($key,$fila);
                                            }

                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                        }
                                        $col++;
                                    }
                                    
                                    

                                }
                                                                
                                //$datos.="</tr>\n";  
                                if ($num_filas_recorridas_int <count($data_aux)){
                                    $datos.="<tr onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'  class=\"DatosGrilla\">";  
                                }
                                /*CASE WHEN STR_TO_DATE(p_2$k.descripcion, '%d/%m/%Y') <= STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') 
                                                                                THEN '<img src=\"diseno/images/realizo.png\" title=\"Realizado\"/>'
                                                                                ElSE '<img src=\"diseno/images/realizo.png\" title=\"Realizado con atraso\"/>'
                                                                            END
                                                                        WHEN CURRENT_DATE() > STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') THEN 
                                                                            '<img src=\"diseno/images/atrasado.png\" title=\"Plazo vencido\"/>'
                                                                        ELSE '<img src=\"diseno/images/SemPlazo.png\" title=\"En el plazo\"/>'*/
                                
                                
                                
                            }
                            if (count($data_aux) === 0){
                                
                                $k = 8;                                
                                $band = 0;
                                foreach ($this->parametros as $value) {     
                                    if (($value[dependencia]=='2')&&($band == 0)){
                                        $band = 1;
                                        $k=$k+3;
                                        if (in_array($k, $array_columns)) {
                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                        }                                                                               
                                        $k++;
                                    }
                                    if ($value[dependencia]=='2')
                                    {
                                    switch ($value[tipo]) {                                                   
                                        case '3':
                                            if (($value[dependencia]=='2')&&($value[indicador]=='S')){//ESTO PARA LAS $ COLUMNAS DEL INDICADOR
                                                //$parametros['mostrar-col'] .= "-$k";  // Fecha 1                                                  
                                                if (in_array($k, $array_columns)) {
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                $k++;     
                                                if (in_array($k, $array_columns)) {
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                //$parametros['mostrar-col'] .= "-$k";  // Fecha 2                                                
                                                $k++;
                                                if (in_array($k, $array_columns)) {
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                //$parametros['mostrar-col'] .= "-$k";  // Estado                                                
                                                $k=$k+2;//Para NO Mostrar la columna de Dias 
                                                if (in_array($k, $array_columns)) {
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                //$parametros['mostrar-col'] .= "-$k";  // Responsable                                                                                                
                                            }else{
                                                if (in_array($k, $array_columns)) {
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }                                                  
                                            }
                                            break;
                                        default:
                                            if (in_array($k, $array_columns)) {
                                                $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                            }                                
                                            break;
                                    }
                                    }
                                    $k++;
                                }            
                                                               
                                
                            }
                               
                            $datos.="</tr>\n";
                            //echo $sql;
                                                        
                            $reg++;                
                        }
                         
                    }
                    
                }else{
                    $datos.="<tr> <td  colspan=\"200\" align=\"center\">";
                    $datos.="NO EXISTEN REGISTROS";
                    $datos.=" </td></tr>\n";
                }
                
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
                $grid->setPagina($parametros['pag']);
                //$grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                //$out['tabla']= $grid->armarTabla();
                $out['tabla'] = '<table id="tblAccionesCorrectivas" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">' . $titulosColumna . $datos.'</table>';
                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
         private function BuscaOrganizacional($tupla)
        {
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $OrgNom = "";            
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if($Fila3[organizacion_padre]==1)
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= $this->BuscaOrganizacional(array('id_organizacion' => $Fila3[organizacion_padre])) . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= '-------';//$_SESSION[CookNomEmpresa];
                return $OrgNom;

        }
        
        private function BuscaProceso($tupla)
        {
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $OrgNom = "";            
                if (strlen($tupla[id_proceso]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_arbol_procesos where id in ($tupla[id_proceso])";
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if($Fila3[organizacion_padre]==1)
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= $this->BuscaProceso(array('id_proceso' => $Fila3[organizacion_padre])) . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= '-------';//$_SESSION[CookNomEmpresa];
                return $OrgNom;

        }
        
        private function semaforo_estado($tupla, $key){
                //,cantidad_evidencia    
            $html = $tupla[$key];
            if (strpos($tupla[$key],"vencido") === false){
                if (strpos($tupla[$key],"atraso") === false){
                    $html .= '<font color="#006600">'." ".str_pad(abs($tupla["dias".substr($key, 3)]) ,4,0,STR_PAD_LEFT).'</font>';                       
                }
                else{
                    $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias".substr($key, 3)]) ,4,0,STR_PAD_LEFT).'</font>';
                }
            }
            else{
                $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias".substr($key, 3)]) ,4,0,STR_PAD_LEFT).'</font>';
            }
            return $html;
        }
        
        private function estado_columna($tupla,$key){            
            if ($tupla[$key]== 'Bueno'){
                return "<img src=\"diseno/images/verde.png\"/ title=\"Verde\">";
            }
            return "<img src=\"diseno/images/rojo.png\"/ title=\"Rojo\">";
        }
        
        private function estado_columna_excel($tupla,$key){            
            if ($tupla[$key]== 'Bueno'){
                return "<img src=\"".PATH_TO_IMG."verde.png\"/ title=\"Verde\">";
            }
            return "<img src=\"".PATH_TO_IMG."rojo.png\"/ title=\"Rojo\">";
        }
        
        private function cantidad_evidencia($tupla, $key){
                //,cantidad_evidencia    
            $html = str_pad($tupla[$key],3,0,STR_PAD_LEFT) . '<a href="EvidenciasMuestra('.$Fila[id_acap].',' .$tupla[id_control_detalle].')"><img src="diseno/images/ico_evidencia.png" title="Evidencias" class="SinBorde" /> </a>';
            return $html;
        }
        
        public function exportarExcel($parametros){

                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarAccionesCorrectivas($parametros, $parametros['pag'], 100000);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesCorrectivas", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[cod_categoria])),
               array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[id_acap])),
               array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[cod_cargo])),
               array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[cod_emp])),
               array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[bloqueo])),
               array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[id_organizacion])),
                    array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[id_proceso])),
               
                    array( "width"=>"10%","ValorEtiqueta"=>"Num Acciones")
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                $band = 0;
                foreach ($this->parametros as $value) {  
                    if (($value[dependencia]=='2')&&($band == 0)){
                        $band = 1;
                        $k=$k+2;
                        //$parametros['mostrar-col'] .= "-$k";    
                        $k++;
                        array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                        array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                        //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                        array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Evidencia"));
                    }
                    switch ($value[tipo]) {
                        case '2':
                            if ($value[id_cmb_acap]=='0'){
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[nombre]))));
                            }
                            else if ($value[id_cmb_acap]=='4'){
                                array_push($config_col,array( "width"=>"6%","ValorEtiqueta"=>(($value[nombre]))));
                            }
                            else array_push($config_col,array( "width"=>"9%","ValorEtiqueta"=>(($value[nombre]))));
                            break;
                        case '3':
                            if (($value[dependencia]=='2')&&($value[indicador]=='S')){//fecha_nom1,fecha_nom2,fecha_sem,datos
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[fecha_nom1]))));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[fecha_nom2]))));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[fecha_sem]))));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Dias"));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[datos]))));
                                
                            }else
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[nombre]))));
                            break;
                        case '1':
                            array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[nombre]))));
                            break;
                        case '4':
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(($value[nombre]))));                                    break;
                        case '5':
                            array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>(($value[nombre]))));
                            break;
                        default:
                            break;
                    }

                    $k++;
                }

                $func= array();

                $columna_funcion = 0;
                              
                $config=array();
                //$grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
                            $grid->hidden[$i] = true;
                            
                            break;
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
                $this->hidden = $grid->hidden;
                
                //$grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);                
                $titulosColumna="<thead><tr bgcolor=\"#FFFFFF\" height=\"30px\">";
                foreach($config as $detalle){
                    $titulosColumna.="<th ";
                    foreach($detalle as $key=>$value){
                        if ($key!='ValorEtiqueta')
                           $titulosColumna.=" $key = \"$value\"  ";
                        else
                        $titulosColumna.="><div align=\"left\">$value</div></th>\n";
                    }
                }
                $titulosColumna.="</tr></thead>";
                $this->funciones["id_organizacion"] = "BuscaOrganizacional";
                $this->funciones["id_proceso"] = "BuscaProceso";
                //BuscaProceso
                $colbotones = $columna_funcion;
                $funciones = array();
                $datos = '';
                if ((is_array($data)) && (count($data)>0)) {
                    foreach($data as $fila ){               
                        if($fila[0]!=-1){
                            $col=0;                                                    
                            $fila[num_acc] = ($fila[num_acc]) == '0' ? 1 : $fila[num_acc];
                            $datos.="<tr onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'  class=\"DatosGrilla\">";                            


                            foreach($fila as $key=>$value){
                                
                                if ($col == 0) $col_id = $key;                       
                                if (!is_integer($key))
                                {                       
                                    //echo $key . ' - ';
                                    if($this->hidden[$col]==true){
                                        //echo $col . ' ';
                                    }
                                    elseif ($col==$this->hide)
                                        $datos.="<td rowspan=\"$fila[num_acc]\"  $atributos style=\"display:none\" > $fila[$col] &nbsp;</td>\n";
                                    else
                                    {
                                        //if(!is_numeric($this->valorColumna($key,$fila)))
                                        {
                                            if(isset($this->funciones[$key])){
                                                $function  =  $this->funciones[$key];
                                                //if ($this->Parent == null)
                                                  //@eval(" \$valor = \$function (\$Valores);");
                                                //else
                                                  @eval(" \$valor = \$this->$function (\$fila,\$key);");
                                              }
                                              else{
                                                $valor = htmlentities($fila[$key], ENT_QUOTES, "UTF-8");
                                                $valor = $fila[$key];
                                              }
                                            
                                            //$valor=$this->valorColumna($key,$fila);
                                        }
//                                        else
//                                            if(strpos($this->valorColumna($key,$fila), '.')===false)
//                                                    $valor=number_format($this->valorColumna($key,$fila),0,'','');
//                                            else
//                                                $valor=number_format($this->valorColumna($key,$fila),2,',','.');                                       
                                        //$datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_decode($valor)."</td>\n";
                                       // echo $key . ' - ';
//                                        if (($key == 'p5')||($key == 'p6')||($key == 'p4')||($key == 'p3')){                                        
//                                            $valor = $fila[$key];
//                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_encode($valor)."</td>\n";
//                                        }
//                                        else if ($key == 'p7'){
//                                            $valor = $fila[$key];
//                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_encode($valor)."</td>\n";
//                                        }
//                                        else 
                                            $datos.="<td rowspan=\"$fila[num_acc]\" $atributos align='" . $this->aligns[$col] . "'>". utf8_encode($valor)."</td>\n";
                                    }
                                    $col++;
                                }

                            }
                            $k = 100;
                            $sql_left = $sql_col_left ='';
                            foreach ($this->parametros as $value) {                                
                                if ($value[dependencia]=='2')
                                {
                                    switch ($value[tipo]) {
                                        case '2':                                        
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                            $sql_col_left .= ",p$k.descripcion p$k ";
                                            break;
                                        case '3'://  m.id_acap=p$k.id_acap and m.cod_categoria=p$k.cod_categoria and p$k.id_cmb_acap=$value[id_cmb_acap]
                                            if ($value[indicador] == 'S'){
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p_1$k on p_1$k.cod_categoria = 8 and p_1$k.id_acap = cdc.id_acap and p_1$k.id_control_detalle = cdc.id_control_detalle and p_1$k.id_cmb_acap=1$value[id_cmb_acap] "; 
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p_2$k on p_2$k.cod_categoria = 8 and p_2$k.id_acap = cdc.id_acap and p_2$k.id_control_detalle = cdc.id_control_detalle and p_2$k.id_cmb_acap=2$value[id_cmb_acap] "; 
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p_3$k on p_3$k.cod_categoria = 8 and p_3$k.id_acap = cdc.id_acap and p_3$k.id_control_detalle = cdc.id_control_detalle and p_3$k.id_cmb_acap=$value[id_cmb_acap] "
                                                . " left join mos_personal p_p$k on p_p$k.cod_emp = CAST(p_3$k.cod_workflow AS UNSIGNED) "; 
                                                $sql_col_left .= ",p_1$k.descripcion p_1$k,p_2$k.descripcion p_2$k ";
                                                $sql_col_left .= ",CASE WHEN p_2$k.descripcion <> '' THEN
                                                                            CASE WHEN STR_TO_DATE(p_2$k.descripcion, '%d/%m/%Y') <= STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') 
                                                                                THEN 'Realizado'
                                                                                ElSE 'Realizado con atraso'
                                                                            END
                                                                        WHEN CURRENT_DATE() > STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') THEN 
                                                                            'Plazo vencido'
                                                                        ELSE 'En el plazo'
                                                                  END AS sem$k";
                                                $sql_col_left .= ",CASE WHEN p_2$k.descripcion <> '' THEN
                                                                            CASE WHEN STR_TO_DATE(p_2$k.descripcion, '%d/m/%Y') <= STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') 
                                                                                    THEN 0
                                                                                    ElSE DATEDIFF(STR_TO_DATE(p_2$k.descripcion, '%d/%m/%Y'),STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') )
                                                                            END
                                                                        WHEN CURRENT_DATE() > STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y') THEN 
                                                                            DATEDIFF(CURRENT_DATE(),STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y'))
                                                                        ELSE DATEDIFF(STR_TO_DATE(p_1$k.descripcion, '%d/%m/%Y'),CURRENT_DATE())
                                                                    END dias$k";
                                                $sql_col_left .= ",CONCAT(CONCAT(UPPER(LEFT(p_p$k.nombres, 1)), LOWER(SUBSTRING(p_p$k.nombres, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_paterno, 1)), LOWER(SUBSTRING(p_p$k.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_materno, 1)), LOWER(SUBSTRING(p_p$k.apellido_materno, 2)))) as p$k ";
                                            }else{
                                                $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                                $sql_col_left .= ",p$k.descripcion p$k ";
                                            }
                                            $this->funciones["sem$k"] = "semaforo_estado";
                                            break;
                                        case '1':
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "
                                                . " LEFT JOIN mos_matrices_parametros_detalle pd$k on cdc.cod_categoria=pd$k.cod_categoria and pd$k.id_cmb_acap=$value[id_cmb_acap] and pd$k.id_item = p$k.id_item "; 
                                            $sql_col_left .= ",pd$k.nombre p$k ";
                                            break; 
                                        case '4':
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "
                                                . " left join mos_personal p_p$k on p_p$k.cod_emp = CAST(p$k.id_item AS UNSIGNED) "; 
                                            $sql_col_left .= ",CONCAT(CONCAT(UPPER(LEFT(p_p$k.nombres, 1)), LOWER(SUBSTRING(p_p$k.nombres, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_paterno, 1)), LOWER(SUBSTRING(p_p$k.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p_p$k.apellido_materno, 1)), LOWER(SUBSTRING(p_p$k.apellido_materno, 2)))) as p$k ";
                                            break;
                                        case '5':
                                            $sql_left .= " LEFT JOIN mos_matrices_control_detalle p$k on p$k.cod_categoria = 8 and p$k.id_acap = cdc.id_acap and p$k.id_control_detalle = cdc.id_control_detalle and p$k.id_cmb_acap=$value[id_cmb_acap] "; 
                                            $sql_col_left .= ",CASE WHEN p$k.id_item='Chk' AND  p$k.descripcion = '1' "
                                                    . "THEN 'Bueno' "
                                                    . "ELSE 'Malo' END p$k ";
                                            break;
                                        default:
                                            break;
                                    }


                                }
                                //else{
                                //    $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                                //    where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                                //}

                                $k++;
                            }
                            $sql = "SELECT                                        
                                        cdc.id_control_detalle
                                        ,peso_especifico
                                        ,(select count(id_acap) from mos_matrices_evidencia_detalle where id_acap=cdc.id_acap and id_control_detalle=cdc.id_control_detalle and cod_categoria=8) as cantidad 

                                        $sql_col_left
                                        FROM mos_matrices_control_detalle_cabecera cdc                                     
                                    $sql_left
                                        WHERE cdc.cod_categoria = 8 AND cdc.id_acap = $fila[id_acap]";
                            //echo $sql;
                            //semaforo_estado,cantidad_evidencia
                            $this->funciones["cantidad"] = "cantidad_evidencia";
                            $data_aux = $this->dbl->query($sql, array());
                            //print_r($data_aux);
                            $num_filas_recorridas = 0;
                            $col_aux = $col;
                            foreach ($data_aux as $fila_aux) {
                                $num_filas_recorridas++;
                                $col = $col_aux;
                                foreach($fila_aux as $key=>$value){
                                    if ($col == 0) $col_id = $key;                       
                                    if (!is_integer($key))
                                    {                       
                                        //echo $key . ' - ';
                                        if($this->hidden[$col]==true){
                                            //echo $col . ' ';
                                        }
                                        elseif ($col==$this->hide)
                                            $datos.="<td $atributos style=\"display:none\" > $fila_aux[$col] &nbsp;</td>\n";
                                        else
                                        {
                                            //if(!is_numeric($this->valorColumna($key,$fila)))
                                            {
                                                if(isset($this->funciones[$key])){
                                                    $function  =  $this->funciones[$key];
                                                    //if ($this->Parent == null)
                                                      //@eval(" \$valor = \$function (\$Valores);");
                                                    //else
                                                      @eval(" \$valor = \$this->$function (\$fila_aux,\$key);");
                                                  }
                                                  else{
                                                    $valor = htmlentities($fila_aux[$key], ENT_QUOTES, "UTF-8");
                                                    $valor = $fila_aux[$key];
                                                  }

                                                //$valor=$this->valorColumna($key,$fila);
                                            }

                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_encode($valor)."</td>\n";
                                        }
                                        $col++;
                                    }

                                }
                                $datos.="</tr>\n";  
                                if ($num_filas_recorridas <count($data_aux)){
                                    $datos.="<tr onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'  class=\"DatosGrilla\">";  
                                }
                            }
                            if (count($data_aux) === 0){
                                
                                $k = 8;                                
                                $band = 0;
                                foreach ($this->parametros as $value) {     
                                    if (($value[dependencia]=='2')&&($band == 0)){
                                        $band = 1;
                                        $k=$k+2;
                                        if (in_array($k, $array_columns)) 
                                        {                
                                            $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                        }                                                                               
                                        $k++;
                                    }
                                    if ($value[dependencia]=='2')
                                    {
                                    switch ($value[tipo]) {                                                   
                                        case '3':
                                            if (($value[dependencia]=='2')&&($value[indicador]=='S')){//ESTO PARA LAS $ COLUMNAS DEL INDICADOR
                                                //$parametros['mostrar-col'] .= "-$k";  // Fecha 1                                                  
                                                if (in_array($k, $array_columns)) 
                                                {                                                
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                $k++;     
                                                if (in_array($k, $array_columns)) 
                                                {                                                
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                //$parametros['mostrar-col'] .= "-$k";  // Fecha 2                                                
                                                $k++;
                                                if (in_array($k, $array_columns)) 
                                                {                
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                //$parametros['mostrar-col'] .= "-$k";  // Estado                                                
                                                $k=$k+2;//Para NO Mostrar la columna de Dias 
                                                if (in_array($k, $array_columns)) 
                                                {                
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }
                                                //$parametros['mostrar-col'] .= "-$k";  // Responsable                                                                                                
                                            }else{
                                                if (in_array($k, $array_columns)) 
                                                {                
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                                }                                                  
                                            }
                                            break;
                                        default:
                                            if (in_array($k, $array_columns)) 
                                            {                                            
                                                $datos.="<td $atributos align='" . $this->aligns[$col] . "'>&nbsp;</td>\n";
                                            }                                
                                            break;
                                    }
                                    }
                                    $k++;
                                }            
                                                               
                                $datos.="</tr>\n";
                            }
                            //echo $sql;
                                                        
                            $reg++;                
                        }
                         
                    }
                    
                }else{
                    $datos.="<tr> <td  colspan=\"200\" align=\"center\">";
                    $datos.="NO EXISTEN REGISTROS";
                    $datos.=" </td></tr>\n";
                }
                
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
                $grid->setPagina($parametros['pag']);
                //$grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                //$out['tabla']= $grid->armarTabla();
                $out['tabla'] = '<table id="tblAccionesCorrectivas" border="1" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">' . $titulosColumna . $datos.'</table>';

            return $out['tabla'];
        }
 
 
            public function indexAccionesCorrectivas($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="p1";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1"; 
                
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 8;
                $contenido[PARAMETROS_OTROS] = "";
                $band = 0;
                $columna_estado_final = 0;
                foreach ($this->parametros as $value) {     
                    if (($value[dependencia]=='2')&&($band == 0)){
                        $band = 1;
                        $columna_estado_final = $k;
                        $contenido[PARAMETROS_OTROS] .= '{SEMAFORO_FINAL}';
                        //$parametros['mostrar-col'] .= "-";
                        $k++;
                        $k=$k+2;
                        
                        
                        //$parametros['mostrar-col'] .= "-$k";   
                        $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                  <label for="SelectAcc" class="col-md-9 control-label">Evidenvia</label>
                                  <div class="col-md-3">      
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      </label>
                                  </div>
                            </div>';
                        $k++;
                    }
                    if ($value[dependencia]=='2'){
                        switch ($value[tipo]) {                                                   
                            case '3':
                                if (($value[dependencia]=='2')&&($value[indicador]=='S')){//ESTO PARA LAS $ COLUMNAS DEL INDICADOR
                                    //$parametros['mostrar-col'] .= "-$k";  // Fecha 1  
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[fecha_nom1] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    $k++;                                
                                    //$parametros['mostrar-col'] .= "-$k";  // Fecha 2
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[fecha_nom2] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    $k++;
                                    //$parametros['mostrar-col'] .= "-$k";  // Estado
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[fecha_sem] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    $k=$k+2;//Para NO Mostrar la columna de Dias 
                                    //$parametros['mostrar-col'] .= "-$k";  // Responsable
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[datos] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    //echo 1;
                                }else{
                                    //$parametros['mostrar-col'] .= "-$k";    
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                      <label for="SelectAcc" class="col-md-9 control-label">' . $value[nombre] . '</label>
                                      <div class="col-md-3">      
                                          <label class="checkbox-inline">
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                          </label>
                                      </div>
                                </div>';
                                }

                                break;

                            default:
                                //$parametros['mostrar-col'] .= "-$k";
                                $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                      <label for="SelectAcc" class="col-md-9 control-label">' . $value[nombre] . '</label>
                                      <div class="col-md-3">      
                                          <label class="checkbox-inline">
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                          </label>
                                      </div>
                                </div>';
                                break;
                        }
                    }  else {
                        switch ($value[tipo]) {                                                   
                            case '3':
                                if (($value[dependencia]=='2')&&($value[indicador]=='S')){//ESTO PARA LAS $ COLUMNAS DEL INDICADOR
                                    $parametros['mostrar-col'] .= "-$k";  // Fecha 1  
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[fecha_nom1] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    $k++;                                
                                    $parametros['mostrar-col'] .= "-$k";  // Fecha 2
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[fecha_nom2] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    $k++;
                                    $parametros['mostrar-col'] .= "-$k";  // Estado
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[fecha_sem] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    $k=$k+2;//Para NO Mostrar la columna de Dias 
                                    $parametros['mostrar-col'] .= "-$k";  // Responsable
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                        <label for="SelectAcc" class="col-md-9 control-label">' . $value[datos] . '</label>
                                        <div class="col-md-3">      
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                            </label>
                                        </div>
                                    </div>';
                                    //echo 1;
                                }else{
                                    $parametros['mostrar-col'] .= "-$k";    
                                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                      <label for="SelectAcc" class="col-md-9 control-label">' . $value[nombre] . '</label>
                                      <div class="col-md-3">      
                                          <label class="checkbox-inline">
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                          </label>
                                      </div>
                                </div>';
                                }

                                break;

                            default:
                                $parametros['mostrar-col'] .= "-$k";
                                $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                      <label for="SelectAcc" class="col-md-9 control-label">' . $value[nombre] . '</label>
                                      <div class="col-md-3">      
                                          <label class="checkbox-inline">
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                          </label>
                                      </div>
                                </div>';
                                break;
                        }
                    }
                    $k++;
                }     
                $Consulta="Select * from mos_matrices_parametro_general where agrupacion='3' and cod_categoria='8' and cod_param in (1,2,4)";
                $data_otro_din = $this->dbl->query($Consulta);
                foreach ($data_otro_din as $value) {
                    switch ($value[cod_param]) {
                        case '1':
                            if ($value[activo] == 'S'){
                                $parametros['mostrar-col'] .= "-5"; 
                            }
                            break;
                        case '2':
                            if ($value[activo] == 'S'){
                                $parametros['mostrar-col'] .= "-6"; 
                            } 
                            break;
                        default:
                            if ($value[activo] == 'S'){
                                $parametros['mostrar-col'] .= "-$columna_estado_final"; 
                                $html_aux = '<div class="form-group">
                                  <label for="SelectAcc" class="col-md-9 control-label">'. $value[descripcion] . '</label>
                                  <div class="col-md-3">      
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $columna_estado_final . '" class="checkbox-mos-col"  checked="checked">   &nbsp;
                                      </label>
                                  </div>
                            </div>';
                                $contenido[PARAMETROS_OTROS] = str_replace("{SEMAFORO_FINAL}", $html_aux, $contenido[PARAMETROS_OTROS]);  
                            } 
                            break;
                    }                   
                }
                $ut_tool = new ut_Tool();
                $js = '';
                foreach ($this->parametros as $value) { 
                    
                    if (($value[dependencia]=='2')){

                    }
                    else{
                        $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[nombre] . '</label>';
                        switch ($value[tipo]) {
                            case '2'://texto
                                
                                if ($value[id_cmb_acap] == '0'){
                                    
                                    $html .= '<div class="col-md-3">';
                                    $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value=""  name="campo_' . $value[id_cmb_acap] . '" id="campo_b-' . $value[id_cmb_acap] . '" style="width:100px;">';
                                    $html .= '</div>';                               
                                }
                                else
                                {
                                    $html .= '<div class="col-md-4">';
                                    $html .= '<textarea data-validation="required" class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_b-' . $value[id_cmb_acap] . '"></textarea>';
                                    $html .= '</div>';
                                }
                                
                                //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                break;
                            case '3'://fecha
//                                if (($value[dependencia]=='2')&&($value[indicador]=='S')){//fecha_nom1,fecha_nom2,fecha_sem,datos
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom1]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom2]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_sem]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Dias"));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[datos]), "p$k", $parametros)));
//
//                                }else
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $html .= '<div class="col-md-2"> Desde: ';
                                $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="dd/mm/yyyy"  name="campo_' . $value[id_cmb_acap] . '_desde" id="campo_b-' . $value[id_cmb_acap] . '_desde"/>';
                                $html .= '</div>';
                                $html .= '<div class="col-md-2"> Hasta: ';
                                $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="dd/mm/yyyy"  name="campo_' . $value[id_cmb_acap] . '_hasta" id="campo_b-' . $value[id_cmb_acap] . '_hasta"/>';
                                $html .= '</div>';
                                $js .= "$('#campo_b-$value[id_cmb_acap]_desde').datepicker();";
                                $js .= "$('#campo_b-$value[id_cmb_acap]_hasta').datepicker();";
                                break;
                            case '1'://combo
                                //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $cadenas = split("<br />", $value[valores]) ;
                                $html .= '<div class="col-md-4">                                              
                                                          <select class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_b-' . $value[id_cmb_acap] . '" data-validation="required">
                                                    <option selected="" value="">-- Seleccione --</option>
                                                    <option  ' .($valor[1] == '1'? "selected='selected'" :'') .  'value="Acc">Accidentes ley</option>'
                                        . '         <option  ' .($valor[1] == '1'? "selected='selected'" :'') .  'value="Inc">Incidentes</option>';
                                
                                $html .= $ut_tool->OptionsCombo("Select id_item,nombre from mos_matrices_parametros_detalle where id_cmb_acap='".$value['id_cmb_acap']."' and cod_categoria='8' order by nombre"
                                                                    , 'id_item'
                                                                    , 'nombre', $value[valor]);
                                $html .= '</select></div>';
                                break;
                            case '4'://personal
                                $html .= '<div class="col-md-4">                                              
                                                      <select name="campo_' . $value[id_cmb_acap] . '" id="campo_b-' . $value[id_cmb_acap] . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_b-' . $value[id_cmb_acap] . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                                //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));                                    break;
                            case '5'://semaforo
                                //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ('1' == '2'? 'checked' : '') .' type="radio" value="1" name="campo_' . $value[id_cmb_acap] . '" id="campo_b-' . $value[id_cmb_acap] . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ($valor[1] == '1'? 'checked' : '') .' type="radio" value="2" name="campo_' . $value[id_cmb_acap] . '" id="campo_b-' . $value[id_cmb_acap] . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                                break;
                            default:
                                break;
                        }
                        $html .= '</div>';
                    }
//                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
//                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
//                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
//                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    
                    $i++;
                    $k++;
                }
                $contenido[CAMPOS_DINAMICOS] = $html;
                //echo $parametros['mostrar-col'];
                $grid = $this->verListaAccionesCorrectivas($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_AccionesCorrectivas();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;AccionesCorrectivas';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correc/';
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correc/';

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
                $objResponse->addAssign('modulo_actual',"value","acciones_correc");
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_correc/acciones_correc.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript($js);
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
                
                //$campos_din = $this->verCamposDinamicos();
                $html = $html_p2 = '';
                $js='';
                $i = 1;
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                foreach ($this->parametros as $value) { 
                    
                    if (($value[dependencia]=='2')){

                    }
                    else{
                        $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[nombre] . '</label>';
                        switch ($value[tipo]) {
                            case '2'://texto
                                
                                if ($value[id_cmb_acap] == '0'){
                                    
                                    $html .= '<div class="col-md-3">';
                                    $html .= '<input type="text" readonly="readonly" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $this->codigo_siguiente_id() .'"  name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '" style="width:100px;">';
                                    $html .= '</div>';                               
                                }
                                else
                                {
                                    $html .= '<div class="col-md-4">';
                                    $html .= '<textarea data-validation="required" class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"></textarea>';
                                    $html .= '</div>';
                                }
                                
                                //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                break;
                            case '3'://fecha
//                                if (($value[dependencia]=='2')&&($value[indicador]=='S')){//fecha_nom1,fecha_nom2,fecha_sem,datos
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom1]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom2]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_sem]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Dias"));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[datos]), "p$k", $parametros)));
//
//                                }else
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $html .= '<div class="col-md-3">';
                                $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="'. $value[nombre] .'"  name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"/>';
                                $html .= '</div>';
                                $js .= "$('#campo_$value[id_cmb_acap]').datepicker();";
                                break;
                            case '1'://combo
                                //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $cadenas = split("<br />", $value[valores]) ;
                                $html .= '<div class="col-md-4">                                              
                                                          <select class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '" data-validation="required">
                                                    <option selected="" value="">-- Seleccione --</option>
                                                    <option  ' .($valor[1] == '1'? "selected='selected'" :'') .  'value="Acc">Accidentes ley</option>'
                                        . '         <option  ' .($valor[1] == '1'? "selected='selected'" :'') .  'value="Inc">Incidentes</option>';
                                
                                $html .= $ut_tool->OptionsCombo("Select id_item,nombre from mos_matrices_parametros_detalle where id_cmb_acap='".$value['id_cmb_acap']."' and cod_categoria='8' order by nombre"
                                                                    , 'id_item'
                                                                    , 'nombre', $value[valor]);
                                $html .= '</select></div>';
                                break;
                            case '4'://personal
                                $html .= '<div class="col-md-4">                                              
                                                      <select name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_' . $value[id_cmb_acap] . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                                //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));                                    break;
                            case '5'://semaforo
                                //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ('1' == '1'? 'checked' : '') .' type="radio" value="1" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ($valor[1] == '1'? 'checked' : '') .' type="radio" value="2" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                                break;
                            default:
                                break;
                        }
                        $html .= '</div>';
                    }
//                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
//                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
//                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
//                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    
                    $i++;
                    $k++;
                }
                $Consulta="Select * from mos_matrices_parametro_general where agrupacion='3' and cod_categoria='8' and cod_param in (1,2)";
                $data_otro_din = $this->dbl->query($Consulta);
                foreach ($data_otro_din as $value) {
                    if ($value[cod_param] == '1'){
                        if ($value[activo] == 'S'){
                            $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[descripcion] . '</label>';
                            $html .= '<div class="col-md-8" style="color:white;">  
                                        <a href="#" data-toggle="modal" style="color:white;" data-target="#myModal-Filtrar-Arbol">[Seleccionar]</a> 
                                        <span id="desc-arbol"></span>                                        
                                        <input type="hidden" value="" id="nivel" name="nivel" data-validation="required"/>                                    
                                    </div>';
                            $html .= '</div>';
                        }
                    }
                    else{
                        if ($value[activo] == 'S'){
                            $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[descripcion] . '</label>';
                            $html .= '<div class="col-md-8" style="color:white;">  
                                        <a href="#" data-toggle="modal" style="color:white;" data-target="#myModal-Filtrar-Proceso">[Seleccionar]</a> 
                                        <span id="desc-proceso"></span>                                        
                                        <input type="hidden" value="" id="proceso" name="proceso" data-validation="required"/>                                    
                                    </div>';
                            $html .= '</div>';
                        }
                    }
                }
                $campos_din = array();
                foreach ($campos_din as $value) {//Nombre,tipo,valores
                    $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[Nombre] . '</label>';
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br/>", $value[valor]) ;
                            foreach ($cadenas as $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $valores . '" name="campo_' . $i . '" id="campo_' . $i . '"> '. $valores . ' 
                                          </label>';
                             }
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br />", $value[valor]) ;
                            $j = 1;
                            foreach ($cadenas as $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline" style="color:white;">
                                            <input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '"> '. $valores . ' 
                                          </label>';
                                $j++;
                            }
                            $html .= '<input id="num_campo_' . $i . '" type="hidden" value="' . ($j - 1) . '" name="num_campo_' . $i . '">';
                            break;
                        case 'Combo':
                        case '9':
                            $cadenas = split("<br />", $value[valores]) ;
                            $html .= '<div class="col-md-4">                                              
                                                      <select class="form-control" name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select></div>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '<div class="col-md-4">';
                                $html .= '<input type="text" data-validation="required" class="form-control" value="'. $value[valor] .'" name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '<div class="col-md-3">';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '<div class="col-md-3">';
                                $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker();";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '<div class="col-md-3">';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);"  data-validation="required rut" style="width: 140px;" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';                                
                            break;
                        case 'Persona':
                        case '6':
                                $html .= '<div class="col-md-4">                                              
                                                      <select name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                        default:
                            break;
                    }
                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    $html .= '</div>';
                    $i++;
                }
                //$html .= '</table>';
                $contenido_1[CAMPOS_DINAMICOS_PES_1] = $html;
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correc/';
                $template->setTemplate("formulario_1");
                //$template->setVars($contenido_1);
                //$contenido_1['CAMPOS'] = $template->show();

//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
                $contenido_1['TITULO_FORMULARIO'] = "Crear&nbsp;AccionesCorrectivas";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesCorrectivas";
                $contenido_1['PAGINA_VOLVER'] = "listarAccionesCorrectivas.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "new";
                $contenido_1['ID'] = "-1";

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");   
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show');");
                $objResponse->addScript($js);
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
                    $parametros[id_proceso] = $parametros['b-id_proceso_aux'];
                    $parametros[id_organizacion] = $parametros['b-id_organizacion_aux'];
                    $respuesta = $this->ingresarAccionesCorrectivas($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        if (count($this->parametros) <= 0){
                            $this->cargar_parametros();
                        }
                        //print_r($this->parametros);
                        foreach ($this->parametros as $value) { 
                            $params[cod_categoria] = 8;
                            $params[id_acap] = $respuesta;
                            
                            if (($value[dependencia]=='1')){
                                //$value[id_cmb_acap]//cod_categoria,id_acap,id_cmb_acap,id_item,vigencia,descripcion
                                switch ($value[tipo]) {
                                    case '1':
                                    case '4':
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = $parametros['campo_'.$value[id_cmb_acap]];//'Txt';
                                        $params[vigencia] = 'S';
                                        $params[descripcion] = $parametros['campo_ref_'.$value[id_cmb_acap]];
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);

                                        break;
                                    case '3':
                                    case '2':
                                        if ($value[id_cmb_acap] == '0'){
                                            
                                            $params[descripcion] = $this->codigo_siguiente_id();
                                            $msj = 'La acción correctiva "'.$params[descripcion].'" ha sido ingresada con exito';
                                        }
                                        else
                                           $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap]]; 
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = 'Txt';
                                        $params[vigencia] = 'S';
                                        
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);
                                        break;
                                    case '5':
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = 'Chk';
                                        $params[vigencia] = 'S';
                                        $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap]];
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);
                                        break;

                                    default:
                                        break;
                                }                                
                                

                            }
                        }
                        
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$msj);
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
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verAccionesCorrectivas($parametros[id]); 

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
                $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
                $contenido_1['ID_ACAP'] = $val["id_acap"];
                $contenido_1['COD_CARGO'] = $val["cod_cargo"];
                $contenido_1['COD_EMP'] = $val["cod_emp"];
                $contenido_1['BLOQUEO'] = ($val["bloqueo"]);
                $contenido_1['ID_PROCESO'] = $val["id_proceso"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];

                
                $html = $html_p2 = '';
                $js='';
                $i = 1;
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $campos_din = $this->cargar_valores_parametros($parametros[id]);
                foreach ($campos_din as $value) { 
                    
                    if (($value[dependencia]=='2')){

                    }
                    else{
                        $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[nombre] . '</label>';
                        switch ($value[tipo]) {
                            case '2'://texto
                                
                                if ($value[id_cmb_acap] == '0'){
                                    
                                    $html .= '<div class="col-md-3">';
                                    $html .= '<input type="text" readonly="readonly" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[descripcion] .'"  name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '" style="width:100px;">';
                                    $html .= '</div>';                               
                                }
                                else
                                {
                                    $html .= '<div class="col-md-4">';
                                    $html .= '<textarea data-validation="required" class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '">'. $value[descripcion] .'</textarea>';
                                    $html .= '</div>';
                                }
                                
                                //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                break;
                            case '3'://fecha
//                                if (($value[dependencia]=='2')&&($value[indicador]=='S')){//fecha_nom1,fecha_nom2,fecha_sem,datos
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom1]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom2]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_sem]), "p$k", $parametros)));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Dias"));
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[datos]), "p$k", $parametros)));
//
//                                }else
//                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $html .= '<div class="col-md-3">';
                                $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="'. $value[nombre] .'" value="'. $value[descripcion] .'" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"/>';
                                $html .= '</div>';
                                $js .= "$('#campo_$value[id_cmb_acap]').datepicker();";
                                break;
                            case '1'://combo
                                //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $cadenas = split("<br />", $value[valores]) ;
                                $html .= '<div class="col-md-4">                                              
                                                          <select class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '" data-validation="required">
                                                    <option selected="" value="">-- Seleccione --</option>
                                                    <option  ' .($value[id_item] == 'Acc'? "selected='selected'" :'') .  'value="Acc">Accidentes ley</option>'
                                        . '         <option  ' .($value[id_item] == 'Inc'? "selected='selected'" :'') .  'value="Inc">Incidentes</option>';
                                
                                $html .= $ut_tool->OptionsCombo("Select id_item,nombre from mos_matrices_parametros_detalle where id_cmb_acap='".$value['id_cmb_acap']."' and cod_categoria='8' order by nombre"
                                                                    , 'id_item'
                                                                    , 'nombre', $value[id_item]);
                                $html .= '</select></div>';
                                break;
                            case '4'://personal
                                $html .= '<div class="col-md-4">                                              
                                                      <select name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[id_item]);
                                $js .= '$( "#campo_' . $value[id_cmb_acap] . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                                //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));                                    break;
                            case '5'://semaforo
                                //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ($value[descripcion] == '1'? 'checked' : '') .' type="radio" value="1" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ($value[descripcion] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                                break;
                            default:
                                break;
                        }
                        $html .= '</div>';
                    }
//                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
//                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
//                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
//                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    
                    $i++;
                    $k++;
                }
                $Consulta="Select * from mos_matrices_parametro_general where agrupacion='3' and cod_categoria='8' and cod_param in (1,2)";
                $data_otro_din = $this->dbl->query($Consulta);
                foreach ($data_otro_din as $value) {
                    if ($value[cod_param] == '1'){
                        if ($value[activo] == 'S'){
                            $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[descripcion] . '</label>';
                            $html .= '<div class="col-md-8" style="color:white;">  
                                        <a href="#" data-toggle="modal" style="color:white;" data-target="#myModal-Filtrar-Arbol">[Seleccionar]</a> 
                                        <span id="desc-arbol">' . $this->BuscaOrganizacional($val) . '</span>                                        
                                        <input type="hidden" value="'.$val["id_organizacion"].'" id="nivel" name="nivel" data-validation="required"/>                                    
                                    </div>';
                            $html .= '</div>';
                        }
                    }
                    else{
                        if ($value[activo] == 'S'){
                            $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-2 control-label">' . $value[descripcion] . '</label>';
                            $html .= '<div class="col-md-8" style="color:white;">  
                                        <a href="#" data-toggle="modal" style="color:white;" data-target="#myModal-Filtrar-Proceso">[Seleccionar]</a> 
                                        <span id="desc-proceso">' . $this->BuscaProceso($val) . '</span>                                        
                                        <input type="hidden" value="'.$val["id_proceso"].'" id="proceso" name="proceso" data-validation="required"/>                                    
                                    </div>';
                            $html .= '</div>';
                        }
                    }
                }
                
                $contenido_1[CAMPOS_DINAMICOS_PES_1] = $html;
                
                
                
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correc/';
                $template->setTemplate("formulario_1");
                //$template->setVars($contenido_1);

                //$contenido['CAMPOS'] = $template->show();

                //$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario_1");

                $contenido_1['TITULO_FORMULARIO'] = "Editar&nbsp;AccionesCorrectivas";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesCorrectivas";
                $contenido_1['PAGINA_VOLVER'] = "listarAccionesCorrectivas.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "upd";
                $contenido_1['ID'] = $val["id_acap"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  
                $objResponse->addScript($js);
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
                    $parametros[id_proceso] = $parametros['b-id_proceso_aux'];
                    $parametros[id_organizacion] = $parametros['b-id_organizacion_aux'];
                    $respuesta = $this->modificarAccionesCorrectivas($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        if (count($this->parametros) <= 0){
                            $this->cargar_parametros();
                        }
                        $sql = "DELETE FROM mos_matrices_detalle WHERE cod_categoria = 8 AND id_acap = $parametros[id]";                            
                        $this->dbl->query($sql);
                        //print_r($this->parametros);
                        foreach ($this->parametros as $value) { 
                            $params[cod_categoria] = 8;
                            $params[id_acap] = $parametros[id];
                            
                            if (($value[dependencia]=='1')){
                                //$value[id_cmb_acap]//cod_categoria,id_acap,id_cmb_acap,id_item,vigencia,descripcion
                                switch ($value[tipo]) {
                                    case '1':
                                    case '4':
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = $parametros['campo_'.$value[id_cmb_acap]];//'Txt';
                                        $params[vigencia] = 'S';
                                        $params[descripcion] = $parametros['campo_ref_'.$value[id_cmb_acap]];
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);

                                        break;
                                    case '3':
                                    case '2':
                                        if ($value[id_cmb_acap] == '0'){
                                            
                                            $params[descripcion] = $this->codigo_siguiente_id();
                                            $msj = 'La acción correctiva "'.$params[descripcion].'" ha sido ingresada con exito';
                                        }
                                        else
                                           $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap]]; 
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = 'Txt';
                                        $params[vigencia] = 'S';
                                        
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);
                                        break;
                                    case '5':
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = 'Chk';
                                        $params[vigencia] = 'S';
                                        $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap]];
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);
                                        break;

                                    default:
                                        break;
                                }                                
                                

                            }
                        }
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
                $val = $this->verAccionesCorrectivas($parametros[id]);
                $respuesta = $this->eliminarAccionesCorrectivas($parametros);
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
                $grid = $this->verListaAccionesCorrectivas($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verAccionesCorrectivas($parametros[id]);

                            $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
            $contenido_1['ID_ACAP'] = $val["id_acap"];
            $contenido_1['COD_CARGO'] = $val["cod_cargo"];
            $contenido_1['COD_EMP'] = $val["cod_emp"];
            $contenido_1['BLOQUEO'] = ($val["bloqueo"]);
            $contenido_1['ID_PROCESO'] = $val["id_proceso"];
            $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correc/';
                $template->setTemplate("verAccionesCorrectivas");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la AccionesCorrectivas";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>