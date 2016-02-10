<?php
 import("clases.interfaz.Pagina");        
        class AccionesCorectivasDetalle extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function AccionesCorectivasDetalle(){
                parent::__construct();
                $this->asigna_script('accion_detalle/accion_detalle.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->contenido = array();
            }
            
           public function semaforo_estado($tupla, $key){
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

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_valores_parametros($id){
                $sql = "Select mp.cod_categoria
                                ,mp.id_cmb_acap
                                ,md.id_cmb_acap as id_2
                                ,mp.nombre
                                ,mp.tipo
                                ,mp.dependencia 
                                ,md.descripcion
                                ,md.id_item
                                ,md.cod_workflow
                         from mos_matrices_parametros mp
                            left JOIN mos_matrices_control_detalle md on 
                                (md.id_cmb_acap = mp.id_cmb_acap OR (mp.indicador = 'S' AND (md.id_cmb_acap = CONCAT('1',mp.id_cmb_acap) OR md.id_cmb_acap = CONCAT('2',mp.id_cmb_acap))))
                                 and md.cod_categoria = mp.cod_categoria and md.id_acap = $_SESSION[id_acap] and md.id_control_detalle = $id
                                 
                         where  mp.dependencia in ('2') and mp.cod_categoria=8                        
                         order by mp.dependencia, mp.orden";
                //echo $sql;
                return $this->dbl->query($sql, array());
            }
            
            private function cargar_parametros(){
                //$sql = "SELECT cod_parametro, espanol FROM mos_parametro WHERE cod_categoria = '3' AND vigencia = 'S' ORDER BY cod_parametro";
                $sql = "Select cod_categoria"
                        . ",id_cmb_acap"
                        . ",nombre"
                        . ",tipo"
                        . ",dependencia "
                        . ",indicador,fecha_nom1,fecha_nom2,fecha_sem,datos"
                        . " from mos_matrices_parametros "
                        . " where dependencia in ('2') and cod_categoria=8 "                        
                        . " order by dependencia, orden";
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            private function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 11";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 11";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


            private function codigo_siguiente(){
                $sql = "SELECT max(id_control_detalle) total_registros
                         FROM mos_matrices_control_detalle_cabecera";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }

             public function verAccionesCorectivasDetalle($id){
                $atr=array();
                $sql = "SELECT cod_categoria
                            ,id_acap
                            ,id_control_detalle
                            ,peso_especifico

                         FROM mos_matrices_control_detalle_cabecera 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function ingresarCampoDinamico($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion     
                    if (strlen($atr[cod_workflow]) == 0){
                        $atr[cod_workflow] = 'NULL';
                    }
                    $sql = "INSERT INTO mos_matrices_control_detalle(cod_categoria,id_acap,id_cmb_acap,id_item,vigencia,descripcion,id_control_detalle,cod_workflow)
                            VALUES(
                                $atr[cod_categoria],$atr[id_acap],$atr[id_cmb_acap],'$atr[id_item]','$atr[vigencia]','$atr[descripcion]',$atr[id_control_detalle],$atr[cod_workflow]
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
            
            public function ingresarAccionesCorectivasDetalle($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[cod_categoria] = 8;
                    $atr[id_acap] = $_SESSION[id_acap];
                    $atr[id_control_detalle] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_matrices_control_detalle_cabecera(cod_categoria,id_acap,id_control_detalle,peso_especifico)
                            VALUES(
                                $atr[cod_categoria],$atr[id_acap],$atr[id_control_detalle],100
                                )";
                    $this->dbl->insert_update($sql);
                    $Consulta="select count(id_acap) as cant from mos_matrices_control_detalle_cabecera where cod_categoria=8 and id_acap=$_SESSION[id_acap]";
                    $total_registros = $this->dbl->query($Consulta, ARRAY());
                    $num_viaje = $total_registros[0][cant] + 0;  
                    
                    $PesoDivide=100/$num_viaje;
                    $Actualiza="update mos_matrices_control_detalle_cabecera set peso_especifico=".$PesoDivide." where cod_categoria=8 and id_acap=$_SESSION[id_acap]";
                    $this->dbl->insert_update($Actualiza);
                    
                    
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_matrices_control_detalle_cabecera ' . $atr[descripcion_ano], 'mos_matrices_control_detalle_cabecera');
                      */
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Acap: \'$atr[id_acap]\', Id Control Detalle: \'$atr[id_control_detalle]\', Peso Especifico: \'$atr[peso_especifico]\', ";
                    $this->registraTransaccionLog(62,$nuevo,'', '');
                    return $atr[id_control_detalle];
                    return "El mos_matrices_control_detalle_cabecera '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarAccionesCorectivasDetalle($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_matrices_control_detalle_cabecera SET                            
                                    cod_categoria = $atr[cod_categoria],id_acap = $atr[id_acap],id_control_detalle = $atr[id_control_detalle],peso_especifico = $atr[peso_especifico]
                            WHERE  id = $atr[id]";      
                    $val = $this->verAccionesCorectivasDetalle($atr[id]);
                    //$this->dbl->insert_update($sql);
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Acap: \'$atr[id_acap]\', Id Control Detalle: \'$atr[id_control_detalle]\', Peso Especifico: \'$atr[peso_especifico]\', ";
                    $anterior = "Cod Categoria: \'$val[cod_categoria]\', Id Acap: \'$val[id_acap]\', Id Control Detalle: \'$val[id_control_detalle]\', Peso Especifico: \'$val[peso_especifico]\', ";
                    $this->registraTransaccionLog(63,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el AccionesCorectivasDetalle ' . $atr[descripcion_ano], 'mos_matrices_control_detalle_cabecera');
                    */
                    return "La acci&oacute;n '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarAccionesCorectivasDetalle($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 4;
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
                                                                        ElSE '<img src=\"diseno/images/realizo.png\" title=\"Realizado con atraso\"/>'
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
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_matrices_control_detalle_cabecera 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
                    if (strlen($atr["b-id_acap"])>0)
                               $sql .= " AND id_acap = '". $atr["b-id_acap"] . "'";
                    if (strlen($atr["b-id_control_detalle"])>0)
                               $sql .= " AND id_control_detalle = '". $atr["b-id_control_detalle"] . "'";
                    if (strlen($atr["b-peso_especifico"])>0)
                        $sql .= " AND peso_especifico = '". $atr["b-peso_especifico"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT 
                                cdc.id_control_detalle
                                ,cdc.cod_categoria
                                ,cdc.id_acap                                
                                ,cdc.peso_especifico

                                     $sql_col_left
                            FROM mos_matrices_control_detalle_cabecera cdc $sql_left
                            WHERE cdc.id_acap = $_SESSION[id_acap] and cdc.cod_categoria = 8 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-id_acap"])>0)
                        $sql .= " AND id_acap = '". $atr["b-id_acap"] . "'";
             if (strlen($atr["b-id_control_detalle"])>0)
                        $sql .= " AND id_control_detalle = '". $atr["b-id_control_detalle"] . "'";
             if (strlen($atr["b-peso_especifico"])>0)
                        $sql .= " AND peso_especifico = '". $atr["b-peso_especifico"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarAccionesCorectivasDetalle($atr){
                    try {
                        session_name("$GLOBALS[SESSION]");
                        session_start();
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                            FROM mos_matrices_evidencia_detalle 
                                            WHERE cod_categoria = 8 AND id_acap = $_SESSION[id_acap] AND id_control_detalle = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene evidencia(s) asociada(s).";
                        }
                        $respuesta = $this->dbl->delete("mos_matrices_control_detalle_cabecera", "cod_categoria = 8 AND id_acap = $_SESSION[id_acap] AND id_control_detalle = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_matrices_control_detalle", "cod_categoria = 8 AND id_acap = $_SESSION[id_acap] AND id_control_detalle = " . $atr[id]);
                        $nuevo = "cod_categoria: \'8\', id_acap:\'$_SESSION[id_acap]\', id_control_detalle:\'$atr[id]\'";
                        $this->registraTransaccionLog(65,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaAccionesCorectivasDetalle($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarAccionesCorectivasDetalle($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesCorectivasDetalle", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_categoria], "cod_categoria", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_acap], "id_acap", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_control_detalle], "id_control_detalle", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[peso_especifico], "peso_especifico", $parametros))
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 4;
                foreach ($this->parametros as $value) {                      
                    switch ($value[tipo]) {
                        case '2':
                            if ($value[id_cmb_acap]=='0'){
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            }
                            else array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                            break;
                        case '3':
                            if (($value[dependencia]=='2')&&($value[indicador]=='S')){//fecha_nom1,fecha_nom2,fecha_sem,datos
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom1]), "p$k", $parametros)));
                                
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom2]), "p$k", $parametros)));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_sem]), "p$k", $parametros)));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Dias"));
                                array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[datos]), "p$k", $parametros)));
                                $grid->setFuncion("sem$k", "semaforo_estado");
                                $grid->setParent($this);
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

                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 5;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verAccionesCorectivasDetalle','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesCorectivasDetalle'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarAccionesCorectivasDetalle','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar AccionesCorectivasDetalle'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarAccionesCorectivasDetalle','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar AccionesCorectivasDetalle'>"));
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'adminEvidencias','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_evidencia.png' title='Ver Evidencias'>"));

                $config=array(array("width"=>"8%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                //echo $parametros['mostrar-col'];
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
//                            array_push($config,$config_col[$i]);
                            $grid->hidden[$i] = true;
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
                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarAccionesCorectivasDetalle($parametros, 1, 100000);
            $data=$this->dbl->data;
            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

             $grid->SetConfiguracion("tblAccionesCorectivasDetalle", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_categoria], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_acap], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_control_detalle], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[peso_especifico], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexAccionesCorectivasDetalle($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $_SESSION[id_acap] = $parametros[id];
                if (isset($parametros[id_accion]))
                    $_SESSION[id_accion] = $parametros[id_accion];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id_control_detalle";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 4;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                                        
                    switch ($value[tipo]) {                                                   
                        case '3':
                            if (($value[dependencia]=='2')&&($value[indicador]=='S')){//ESTO PARA LAS $ COLUMNAS DEL INDICADOR
                                $parametros['mostrar-col'] .= "-$k";  // Fecha 1                                  
                                $k++;                                
                                $parametros['mostrar-col'] .= "-$k";  // Fecha 2                                
                                $k++;
                                $parametros['mostrar-col'] .= "-$k";  // Estado                                
                                $k=$k+2;//Para NO Mostrar la columna de Dias 
                                $parametros['mostrar-col'] .= "-$k";  // Responsable
                                
                                //echo 1;
                            }else{
                                $parametros['mostrar-col'] .= "-$k";                                    
                            }
                            
                            break;
                        
                        default:
                            $parametros['mostrar-col'] .= "-$k";                            
                            break;
                    }
                    $k++;                
                }
                $grid = $this->verListaAccionesCorectivasDetalle($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_AccionesCorectivasDetalle();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;AccionesCorectivasDetalle';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['OPC'] = "new";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'accion_detalle/';
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
                
                $Consulta="Select cod_param,descripcion from mos_matrices_parametro_general where agrupacion='1' and cod_categoria='8'";
                $data_pest = $this->dbl->query($Consulta);
                foreach ($data_pest as $value) {
                    $contenido["NOMBRE_PEST_" . $value[cod_param]] = $value[descripcion];
                }
                
                $html = $html_p2 = '';
                $js='';
                $i = 1;
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $ut_tool = new ut_Tool();
                foreach ($this->parametros as $value) { 
                    
                    {
                        
                        switch ($value[tipo]) {
                            case '2'://texto
                                
                                $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[nombre] . '</label>';
                                {
                                    $html .= '<div class="col-md-5">';
                                    $html .= '<textarea data-validation="required" class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"></textarea>';
                                    $html .= '</div>';
                                }
                                
                                //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                break;
                            case '3'://fecha
                                if (($value[dependencia]=='2')&&($value[indicador]=='S')){
                                    $html .= '<div class="form-group">
                                            <label for="idRegistro" class="col-md-4 control-label" style="color:blue;text-align:left;">' . $value[nombre] . '</label>';
                                    $html .= '</div>';

    //                                if (($value[dependencia]=='2')&&($value[indicador]=='S')){//fecha_nom1,fecha_nom2,fecha_sem,datos
    //                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom1]), "p$k", $parametros)));
    //                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_nom2]), "p$k", $parametros)));
    //                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[fecha_sem]), "p$k", $parametros)));
    //                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>"Dias"));
    //                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[datos]), "p$k", $parametros)));
    //
    //                                }else
    //                                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                    $html .= '<div class="form-group">
                                                <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[fecha_nom1] . '</label>';
                                    $html .= '<div class="col-md-4">';
                                    $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="'. $value[fecha_nom1] .'"  name="campo_' . $value[id_cmb_acap] . '_1" id="campo_' . $value[id_cmb_acap] . '_1"/>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    $js .= "$('#campo_$value[id_cmb_acap]_1').datepicker();";
                                    $html .= '<div class="form-group">
                                                <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[fecha_nom2] . '</label>';
                                    $html .= '<div class="col-md-4">';
                                    $html .= '<input type="text" style="width: 100px;"  data-validation-format="dd/mm/yyyy" class="form-control" placeholder="'. $value[fecha_nom2] .'"  name="campo_' . $value[id_cmb_acap] . '_2" id="campo_' . $value[id_cmb_acap] . '_2"/>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    $js .= "$('#campo_$value[id_cmb_acap]_2').datepicker();";
                                    $html .= '<div class="form-group">
                                            <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[datos] . '</label>';
                                    $html .= '<div class="col-md-5">                                              
                                                          <select name="campo_' . $value[id_cmb_acap] . '_3" id="campo_' . $value[id_cmb_acap] . '_3" data-validation="required">
                                                            <option selected="" value="">-- Seleccione --</option>';
                                    $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                            CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                                FROM mos_personal p WHERE interno = 1"
                                                                        , 'cod_emp'
                                                                        , 'nombres', $value[valor]);
                                    $js .= '$( "#campo_' . $value[id_cmb_acap] . '_3" ).select2({
                                                placeholder: "Selecione",
                                                allowClear: true
                                              }); ';
                                    $html .= '</select></div>';
                                }
                                else{
                                    $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[nombre] . '</label>';

                                    $html .= '<div class="col-md-3">';
                                    $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="'. $value[nombre] .'"  name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '"/>';
                                    $html .= '</div>';
                                    $js .= "$('#campo_$value[id_cmb_acap]').datepicker();";
                                }
                                break;
                            case '1'://combo
                                //array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));
                                //$cadenas = split("<br />", $value[valores]) ;
                                $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[nombre] . '</label>';
                                $html .= '<div class="col-md-5">                                              
                                                          <select class="form-control" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '" data-validation="required">
                                                    <option selected="" value="">-- Seleccione --</option>';
                                
                                $html .= $ut_tool->OptionsCombo("Select id_item,nombre from mos_matrices_parametros_detalle where id_cmb_acap='".$value['id_cmb_acap']."' and cod_categoria='8' order by nombre"
                                                                    , 'id_item'
                                                                    , 'nombre', $value[valor]);
                                $html .= '</select></div>';
                                break;
                            case '4'://personal
                                $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[nombre] . '</label>';
                                $html .= '<div class="col-md-5">                                              
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
                                $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-3 control-label" style="color:black">' . $value[nombre] . '</label>';
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ('1' == '1'? 'checked' : '') .' type="radio" value="1" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '_1"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ($valor[1] == '1'? 'checked' : '') .' type="radio" value="2" name="campo_' . $value[id_cmb_acap] . '" id="campo_' . $value[id_cmb_acap] . '_2"> <img src="diseno/images/atrasado.png" /> 
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
                $contenido[CAMPOS_DINAMICOS_PES_2] = $html;
                
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'accion_detalle/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'accion_detalle/';
                

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
                //$objResponse->addAssign('modulo_actual',"value","accion_detalle");
                $objResponse->addIncludeScript(PATH_TO_JS . 'accion_detalle/accion_detalle.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Acciones ID: $_SESSION[id_accion]');");
                
                
                //$objResponse->addScript("$('#hv-fecha').datepicker();");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show'); ");
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
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'accion_detalle/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;AccionesCorectivasDetalle";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesCorectivasDetalle";
                $contenido['PAGINA_VOLVER'] = "listarAccionesCorectivasDetalle.php";
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
                    
                    $respuesta = $this->ingresarAccionesCorectivasDetalle($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {    
                        $objResponse->addScriptCall("MostrarContenido");
                        if (count($this->parametros) <= 0){
                            $this->cargar_parametros();
                        }
                        //print_r($this->parametros);
                        foreach ($this->parametros as $value) { 
                            $params = array();
                            $params[cod_categoria] = 8;
                            $params[id_acap] = $_SESSION[id_acap];
                            $params[id_control_detalle] = $respuesta;
                            if (($value[dependencia]=='2')){
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
                                        if (($value[dependencia]=='2')&&($value[indicador]=='S')){
                                            $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap].'_1']; 
                                            $params[id_cmb_acap] = '1'.$value[id_cmb_acap];
                                            $params[id_item] = 'Fec';
                                            $params[vigencia] = 'S';                                            
                                            $this->ingresarCampoDinamico($params);
                                            $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap].'_2']; 
                                            $params[id_cmb_acap] = '2'.$value[id_cmb_acap];
                                            $params[id_item] = 'Fec';
                                            $params[vigencia] = 'S';                                            
                                            $this->ingresarCampoDinamico($params);
                                            $params[descripcion] = 'S';
                                            $params[id_cmb_acap] = $value[id_cmb_acap];
                                            $params[id_item] = 'Wor';
                                            $params[cod_workflow] = $parametros['campo_'.$value[id_cmb_acap].'_3']; 
                                            $params[vigencia] = 'S';                                            
                                            $this->ingresarCampoDinamico($params);
                                            $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_3").select2("val", "")');  
                                        }
                                        else{
                                            $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap]]; 
                                            $params[id_cmb_acap] = $value[id_cmb_acap];
                                            $params[id_item] = 'Fec';
                                            $params[vigencia] = 'S';

                                            //print_r($params);
                                            $this->ingresarCampoDinamico($params);
                                        }
                                        break;
                                    case '2':
                                        if ($value[id_cmb_acap] == '0'){
                                            
                                            $params[descripcion] = $this->codigo_siguiente_id();
                                            
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
                        $msj = 'La acción ha sido ingresada con exito';
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$msj);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar-hv' ).val('Guardar');
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
                $val = $this->verAccionesCorectivasDetalle($parametros[id]); 

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
                $contenido_1['ID_CONTROL_DETALLE'] = $val["id_control_detalle"];
                $contenido_1['PESO_ESPECIFICO'] = $val["peso_especifico"];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'accion_detalle/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;AccionesCorectivasDetalle";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesCorectivasDetalle";
                $contenido['PAGINA_VOLVER'] = "listarAccionesCorectivasDetalle.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                
                $campos_din = $this->cargar_valores_parametros($parametros[id]);
                foreach ($campos_din as $value) { 
                    
                    {
                        
                        switch ($value[tipo]) {
                            case '2'://texto
                                $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '").html("'. $value[descripcion] . '")');
                                break;
                            case '3'://fecha
                                if (($value[id_cmb_acap]!=$value[id_2])){
                                    if ('1'.$value[id_cmb_acap]==$value[id_2]){
                                        $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_1").val("'. $value[descripcion] . '")');
                                    }  
                                    
                                    if ('2'.$value[id_cmb_acap]==$value[id_2]){
                                        $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_2").val("'. $value[descripcion] . '")');
                                    }  
                                }
                                else{
                                    if ($value[id_item] == 'Wor'){
                                        $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_3").select2("val", "'.$value[cod_workflow].'")'); 
                                    }else
                                        $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '").val("'. $value[id_item] . '")');
                                }
                                break;
                            case '1'://combo
                                $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '").val("'. $value[id_item] . '")');
                                break;
                            case '4'://personal
                                $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '").select2("val", "'.$value[id_item].'")');                                
                                break;
                                //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[nombre]), "p$k", $parametros)));                                    break;
                            case '5'://semaforo
                                if ($value[descripcion] == '1'){
                                    $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_1").prop("checked", true); ');
                                    $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_2").prop("checked", true); ');                                    
                                }
                                else{
                                    $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_2").prop("checked", true); ');
                                    $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_1").prop("checked", true); ');    
                                }
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
                
                //$objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                //$objResponse->addScriptCall("calcHeight");
                //$objResponse->addScriptCall("MostrarContenido2");          
                //$objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  
                $objResponse->addScript("$('#id-hv').val('$parametros[id]');");
                $objResponse->addScript("$('#opc-hv').val('upd');");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('.nav-tabs a[href=\"#hv-red\"]').tab('show');");
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
                    
                    $respuesta = $this->modificarAccionesCorectivasDetalle($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        
                        $sql = "DELETE FROM mos_matrices_control_detalle WHERE cod_categoria = 8 AND id_acap = $_SESSION[id_acap] AND id_control_detalle = $parametros[id]";                            
                        $this->dbl->query($sql);
                        if (count($this->parametros) <= 0){
                            $this->cargar_parametros();
                        }
                        //print_r($this->parametros);
                        foreach ($this->parametros as $value) { 
                            $params = array();
                            $params[cod_categoria] = 8;
                            $params[id_acap] = $_SESSION[id_acap];
                            $params[id_control_detalle] = $parametros[id];
                            if (($value[dependencia]=='2')){
                                //$value[id_cmb_acap]//cod_categoria,id_acap,id_cmb_acap,id_item,vigencia,descripcion
                                switch ($value[tipo]) {
                                    case '1':
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = $parametros['campo_'.$value[id_cmb_acap]];//'Txt';
                                        $params[vigencia] = 'S';
                                        $params[descripcion] = $parametros['campo_ref_'.$value[id_cmb_acap]];
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);

                                        break;
                                    case '4':
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = $parametros['campo_'.$value[id_cmb_acap]];//'Txt';
                                        $params[vigencia] = 'S';
                                        $params[descripcion] = $parametros['campo_ref_'.$value[id_cmb_acap]];
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);
                                        $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '").select2("val", "")');  

                                        break;
                                    case '3':
                                        if (($value[dependencia]=='2')&&($value[indicador]=='S')){
                                            $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap].'_1']; 
                                            $params[id_cmb_acap] = '1'.$value[id_cmb_acap];
                                            $params[id_item] = 'Fec';
                                            $params[vigencia] = 'S';                                            
                                            $this->ingresarCampoDinamico($params);
                                            $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap].'_2']; 
                                            $params[id_cmb_acap] = '2'.$value[id_cmb_acap];
                                            $params[id_item] = 'Fec';
                                            $params[vigencia] = 'S';                                            
                                            $this->ingresarCampoDinamico($params);
                                            $params[descripcion] = 'S';
                                            $params[id_cmb_acap] = $value[id_cmb_acap];
                                            $params[id_item] = 'Wor';
                                            $params[cod_workflow] = $parametros['campo_'.$value[id_cmb_acap].'_3']; 
                                            $params[vigencia] = 'S';                                            
                                            $this->ingresarCampoDinamico($params);
                                            $objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '_3").select2("val", "")');  
                                            
                                        }
                                        else{
                                            $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap]]; 
                                            $params[id_cmb_acap] = $value[id_cmb_acap];
                                            $params[id_item] = 'Fec';
                                            $params[vigencia] = 'S';

                                            //print_r($params);
                                            $this->ingresarCampoDinamico($params);
                                        }
                                        break;
                                    case '2':
                                        if ($value[id_cmb_acap] == '0'){
                                            
                                            $params[descripcion] = $this->codigo_siguiente_id();
                                            
                                        }
                                        else
                                           $params[descripcion] = $parametros['campo_'.$value[id_cmb_acap]]; 
                                        $params[id_cmb_acap] = $value[id_cmb_acap];
                                        $params[id_item] = 'Txt';
                                        $params[vigencia] = 'S';
                                        
                                        //print_r($params);
                                        $this->ingresarCampoDinamico($params);
                                        //$objResponse->addScript('$("#campo_' . $value[id_cmb_acap] . '").html("")');
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
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar-hv' ).val('Guardar');
                                        $( '#btn-guardar-hv' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verAccionesCorectivasDetalle($parametros[id]);
                $respuesta = $this->eliminarAccionesCorectivasDetalle($parametros);
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
                $grid = $this->verListaAccionesCorectivasDetalle($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid-hv',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado-hv',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verAccionesCorectivasDetalle($parametros[id]);

                            $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
            $contenido_1['ID_ACAP'] = $val["id_acap"];
            $contenido_1['ID_CONTROL_DETALLE'] = $val["id_control_detalle"];
            $contenido_1['PESO_ESPECIFICO'] = $val["peso_especifico"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'accion_detalle/';
                $template->setTemplate("verAccionesCorectivasDetalle");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la AccionesCorectivasDetalle";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>