<?php
 import("clases.interfaz.Pagina");        
        class AccionesCorrectivas extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        public $nombres_columnas;
        private $placeholder;
            
            public function AccionesCorrectivas(){
                parent::__construct();
                $this->asigna_script('acciones_correctivas/acciones_correctivas.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->nombres_columnas_ac = $this->placeholder = array();
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            public function cargar_parametros(){
                $sql = "SELECT cod_parametro, espanol, tipo FROM mos_parametro WHERE cod_categoria = '8' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            public function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 15";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            public function cargar_nombres_columnas_acciones(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 16";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas_ac[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 15";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verAccionesCorrectivas($id){
                $atr=array(id=>$id);
                $atr = $this->dbl->corregir_parametros($atr);
                $sql = "SELECT ac.id
                            ,origen_hallazgo
                            ,DATE_FORMAT(fecha_generacion, '%d/%m/%Y') fecha_generacion
                            ,ac.descripcion
                            ,analisis_causal
                            ,responsable_analisis
                            ,ac.id_organizacion
                            ,id_proceso
                            ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada
                            ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada
                            ,id_responsable_segui
                            ,alto_potencial
                            ,o.descripcion origen
                            ,concat(initcap(p.nombres), ' ', initcap(p.apellido_paterno), ' ' , initcap(p.apellido_materno)) responsable_ana
                            ,concat(initcap(per.nombres), ' ', initcap(per.apellido_paterno), ' ' , initcap(per.apellido_materno)) responsable_segui
                            ,CASE WHEN NOT ac.fecha_acordada IS NULL THEN 
                                            CASE WHEN NOT ac.fecha_realizada IS NULL THEN
                                                CASE WHEN ac.fecha_realizada <= ac.fecha_acordada 
                                                    THEN 'Realizado'
                                                    ElSE 'Realizado con atraso'
                                                END
                                                WHEN CURRENT_DATE() > ac.fecha_acordada THEN 
                                                    'Plazo vencido'
                                                    ELSE 'En el plazo'
                                                END 
                                            ELSE ''
                                        END sema_evi
                         FROM mos_acciones_correctivas ac
                         INNER JOIN mos_origen_ac o ON o.id = origen_hallazgo
                         INNER JOIN mos_personal p ON p.cod_emp = responsable_analisis
                         LEFT JOIN mos_personal per ON per.cod_emp = id_responsable_segui
                         WHERE ac.id = $atr[id] "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarAccionesCorrectivas($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    if (strlen($atr[id_proceso]) == 0){
                        $atr[id_proceso] = 'NULL';
                    }
                    if (strlen($atr[id_organizacion]) == 0){
                        $atr[id_organizacion] = 'NULL';
                    }
                    if (strlen($atr[fecha_acordada]) == 0){
                        $atr[fecha_acordada] = 'NULL';
                    }
                    else{
                        $atr[fecha_acordada] = "'$atr[fecha_acordada]'";
                    }
                    if (strlen($atr[fecha_realizada]) == 0){
                        $atr[fecha_realizada] = 'NULL';
                    }
                    else{
                        $atr[fecha_realizada] = "'$atr[fecha_realizada]'";
                    }
                    if (strlen($atr[id_responsable_segui]) == 0){
                        $atr[id_responsable_segui] = 'NULL';
                    }
                    $sql = "INSERT INTO mos_acciones_correctivas(origen_hallazgo,fecha_generacion,descripcion,analisis_causal,responsable_analisis,id_organizacion,id_proceso,fecha_acordada,fecha_realizada,id_responsable_segui,alto_potencial)
                            VALUES(
                                $atr[origen_hallazgo],'$atr[fecha_generacion]','$atr[descripcion]','$atr[analisis_causal]',$atr[responsable_analisis],$atr[id_organizacion],$atr[id_proceso],$atr[fecha_acordada],$atr[fecha_realizada],$atr[id_responsable_segui], '$atr[alto_potencial]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_acciones_correctivas ' . $atr[descripcion_ano], 'mos_acciones_correctivas');
                      */
                    if ($atr[fecha_acordada] != 'NULL'){
                        $atr[fecha_acordada] = "\\" . substr($atr[fecha_acordada], 0, strlen($atr[fecha_acordada])-1) . "\\";
                    }
                    if ($atr[fecha_realizada] != 'NULL'){
                        $atr[fecha_realizada] = "\\" . substr($atr[fecha_realizada], 0, strlen($atr[fecha_realizada])-1)  . "\'";
                    }
                    $nuevo = "Origen Hallazgo: \'$atr[origen_hallazgo]\', Fecha Generacion: \'$atr[fecha_generacion]\', Descripcion: \'$atr[descripcion]\', Analisis Causal: \'$atr[analisis_causal]\', Responsable Analisis: \'$atr[responsable_analisis]\', Id Organizacion: \'$atr[id_organizacion]\', Id Proceso: \'$atr[id_proceso]\', Fecha Acordada: $atr[fecha_acordada], Fecha Realizada: $atr[fecha_realizada], Id Responsable Segui: \'$atr[id_responsable_segui]\', ";
                    $this->registraTransaccionLog(60,$nuevo,'', '');
                    $sql = "SELECT MAX(id) ultimo FROM mos_acciones_correctivas"; 
                    $this->operacion($sql, $atr);
                    return $this->dbl->data[0][0];
                    return "El mos_acciones_correctivas '$atr[descripcion_ano]' ha sido ingresado con exito";
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
                    if (strlen($atr[id_proceso]) == 0){
                        $atr[id_proceso] = 'NULL';
                    }
                    if (strlen($atr[id_organizacion]) == 0){
                        $atr[id_organizacion] = 'NULL';
                    }
                    if (strlen($atr[fecha_acordada]) == 0){
                        $atr[fecha_acordada] = 'NULL';
                    }
                    else{
                        $atr[fecha_acordada] = "'$atr[fecha_acordada]'";
                    }
                    if (strlen($atr[fecha_realizada]) == 0){
                        $atr[fecha_realizada] = 'NULL';
                    }
                    else{
                        $atr[fecha_realizada] = "'$atr[fecha_realizada]'";
                    }
                    if (strlen($atr[id_responsable_segui]) == 0){
                        $atr[id_responsable_segui] = 'NULL';
                    }
                    $sql = "UPDATE mos_acciones_correctivas SET                            
                                    origen_hallazgo = '$atr[origen_hallazgo]',fecha_generacion = '$atr[fecha_generacion]',descripcion = '$atr[descripcion]',analisis_causal = '$atr[analisis_causal]',responsable_analisis = $atr[responsable_analisis],id_organizacion = $atr[id_organizacion],id_proceso = $atr[id_proceso],fecha_acordada = $atr[fecha_acordada],fecha_realizada = $atr[fecha_realizada],id_responsable_segui = $atr[id_responsable_segui]
                                        , alto_potencial = '$atr[alto_potencial]'
                            WHERE  id = $atr[id]";      
                    if ($atr[fecha_acordada] != 'NULL'){
                        $atr[fecha_acordada] = "\\" . substr($atr[fecha_acordada], 0, strlen($atr[fecha_acordada])-1) . "\\";
                    }
                    if ($atr[fecha_realizada] != 'NULL'){
                        $atr[fecha_realizada] = "\\" . substr($atr[fecha_realizada], 0, strlen($atr[fecha_realizada])-1)  . "\'";
                    }
                    $val = $this->verAccionesCorrectivas($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Origen Hallazgo: \'$atr[origen_hallazgo]\', Fecha Generacion: \'$atr[fecha_generacion]\', Descripcion: \'$atr[descripcion]\', Analisis Causal: \'$atr[analisis_causal]\', Responsable Analisis: \'$atr[responsable_analisis]\', Id Organizacion: \'$atr[id_organizacion]\', Id Proceso: \'$atr[id_proceso]\', Fecha Acordada: $atr[fecha_acordada], Fecha Realizada: $atr[fecha_realizada], Id Responsable Segui: \'$atr[id_responsable_segui]\', ";
                    $anterior = "Origen Hallazgo: \'$val[origen_hallazgo]\', Fecha Generacion: \'$val[fecha_generacion]\', Descripcion: \'$val[descripcion]\', Analisis Causal: \'$val[analisis_causal]\', Responsable Analisis: \'$val[responsable_analisis]\', Id Organizacion: \'$val[id_organizacion]\', Id Proceso: \'$val[id_proceso]\', Fecha Acordada: \'$val[fecha_acordada]\', Fecha Realizada: \'$val[fecha_realizada]\', Id Responsable Segui: \'$val[id_responsable_segui]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el AccionesCorrectivas ' . $atr[descripcion_ano], 'mos_acciones_correctivas');
                    */
                    return "La acción correctiva '$atr[descripcion]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarAccionesCorrectivas($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 8;                    
                    foreach ($this->parametros as $value) {
                        //$sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                        //        inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                        //where t1.cod_categoria='3' and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = p.cod_emp "; 
                        //$sql_col_left .= ",p$k.nom_detalle p$k ";
                            switch ($value[tipo]) {
                                case '2':
                                
                                    $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = 8 AND ac.id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                                    $sql_col_left .= ",p$k.descripcion p$k ";
                                    break;
                                case '3':
                                    $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = 8 AND ac.id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                                    $sql_col_left .= ",p$k.descripcion p$k ";
                                    if ($atr[corder] == "p$k"){
                                        $atr[corder] = "STR_TO_DATE(p$k.descripcion, '%d/%m/%Y')";
                                    }
                                    break;
                                case '1'://Combo
                                    $sql_col_left .= ",p$k.nom_detalle p$k ";
                                    $sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                                            inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                                        where t1.cod_categoria=13 and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = ac.id "; 
                                    break;
                                case '4':
                                    $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = 13 AND ac.id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro] "
                                        . " left join mos_personal per$k on per$k.cod_emp = p$k.cod_parametro_det "; 
                                    $sql_col_left .= ",CONCAT(CONCAT(UPPER(LEFT(per$k.nombres, 1)), LOWER(SUBSTRING(per$k.nombres, 2))),' ', CONCAT(UPPER(LEFT(per$k.apellido_paterno, 1)), LOWER(SUBSTRING(per$k.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(per$k.apellido_materno, 1)), LOWER(SUBSTRING(per$k.apellido_materno, 2)))) as p$k ";
                                    break;
                                case '5':
                                    $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = 8 AND ac.id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]";                                     
                                    $sql_col_left .= ",CASE WHEN p$k.descripcion = '1' "
                                            . "THEN 'Bueno' "
                                            . "ELSE 'Malo' END p$k ";
                                    if ($registros_x_pagina == 100000)
                                        $this->funciones["p$k"] = 'estado_columna_excel';
                                    else
                                        $this->funciones["p$k"] = 'estado_columna'; 
                                    break;
                                case '6':
                                    $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = 8 AND ac.id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                                    $sql_col_left .= ",p$k.descripcion p$k ";
                                    break;
                                case '7':
                                    $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = 8 AND ac.id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                                    $sql_col_left .= ",p$k.descripcion p$k ";
                                    break;
                                default:
                                    break;
                            }

                        $k++;
                    }
                    $id_org ='';
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){                             
                        $id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);                        
                    }
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_acciones_correctivas 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                if (strlen($atr["b-origen_hallazgo"])>0)
                        $sql .= " AND upper(origen_hallazgo) like '%" . strtoupper($atr["b-origen_hallazgo"]) . "%'";
                    if (strlen($atr['b-fecha_generacion-desde'])>0)                        
                    {
                        $atr['b-fecha_generacion-desde'] = formatear_fecha($atr['b-fecha_generacion-desde']);                        
                        $sql .= " AND fecha_generacion >= '" . ($atr['b-fecha_generacion-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_generacion-hasta'])>0)                        
                    {
                        $atr['b-fecha_generacion-hasta'] = formatear_fecha($atr['b-fecha_generacion-hasta']);                        
                        $sql .= " AND fecha_generacion <= '" . ($atr['b-fecha_generacion-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-analisis_causal"])>0)
                        $sql .= " AND upper(analisis_causal) like '%" . strtoupper($atr["b-analisis_causal"]) . "%'";
                    if (strlen($atr["b-responsable_analisis"])>0)
                        $sql .= " AND responsable_analisis = '". $atr["b-responsable_analisis"] . "'";
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2"))
                        $sql .= " AND ac.id_organizacion IN (". $id_org . ")";
                    if (strlen($atr["b-id_proceso"])>0)
                        $sql .= " AND id_proceso = '". $atr["b-id_proceso"] . "'";
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
                    if (strlen($atr["b-id_responsable_segui"])>0)
                        $sql .= " AND id_responsable_segui = '". $atr["b-id_responsable_segui"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT ac.id
                                    ,0 estado
                                    ,ac.id as id_2
                                    ,alto_potencial
                                    ,oac.descripcion origen_hallazgo
                                    ,DATE_FORMAT(fecha_generacion, '%d/%m/%Y') fecha_generacion_a
                                    ,ac.descripcion
                                    ,analisis_causal
                                    ,concat(initcap(per.nombres), ' ', initcap(per.apellido_paterno), ' ' , initcap(per.apellido_materno)) as responsable_analisis
                                    $sql_col_left
                                    ,ac.id_organizacion
                                    ,ac.id_proceso
                                    -- ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada
                                    -- ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada
                                    -- ,id_responsable_segui

                                     
                            FROM mos_acciones_correctivas ac
                            INNER JOIN mos_origen_ac oac ON oac.id = ac.origen_hallazgo 
                            LEFT JOIN mos_personal per ON per.cod_emp = ac.responsable_analisis
                            $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                if (strlen($atr["b-origen_hallazgo"])>0)
                        $sql .= " AND upper(origen_hallazgo) like '%" . strtoupper($atr["b-origen_hallazgo"]) . "%'";
             if (strlen($atr['b-fecha_generacion-desde'])>0)                        
                    {
                        $atr['b-fecha_generacion-desde'] = formatear_fecha($atr['b-fecha_generacion-desde']);                        
                        $sql .= " AND fecha_generacion >= '" . ($atr['b-fecha_generacion-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_generacion-hasta'])>0)                        
                    {
                        $atr['b-fecha_generacion-hasta'] = formatear_fecha($atr['b-fecha_generacion-hasta']);                        
                        $sql .= " AND fecha_generacion <= '" . ($atr['b-fecha_generacion-hasta']) . "'";                        
                    }
            if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
            if (strlen($atr["b-analisis_causal"])>0)
                        $sql .= " AND upper(analisis_causal) like '%" . strtoupper($atr["b-analisis_causal"]) . "%'";
             if (strlen($atr["b-responsable_analisis"])>0)
                        $sql .= " AND responsable_analisis = '". $atr["b-responsable_analisis"] . "'";
             if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2"))
                        $sql .= " AND ac.id_organizacion IN (". $id_org . ")";
             if (strlen($atr["b-id_proceso"])>0)
                        $sql .= " AND id_proceso = '". $atr["b-id_proceso"] . "'";
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
             if (strlen($atr["b-id_responsable_segui"])>0)
                        $sql .= " AND id_responsable_segui = '". $atr["b-id_responsable_segui"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarAccionesCorrectivas($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                            FROM mos_acciones_ac_co 
                                            WHERE id_ac = " . $atr[id];                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene acciones asociadas.";
                        }
                        $val = $this->verAccionesCorrectivas($atr[id]);
                                                                
                        $respuesta = $this->dbl->delete("mos_acciones_correctivas", "id = " . $atr[id]);                        
                        $nuevo = "Origen Hallazgo: \'$val[origen_hallazgo]\', Fecha Generacion: \'$val[fecha_generacion]\', Descripcion: \'$val[descripcion]\', Analisis Causal: \'$val[analisis_causal]\', Responsable Analisis: \'$val[responsable_analisis]\', Id Organizacion: \'$val[id_organizacion]\', Id Proceso: \'$val[id_proceso]\', Fecha Acordada: \'$val[fecha_acordada]\', Fecha Realizada: \'$val[fecha_realizada]\', Id Responsable Segui: \'$val[id_responsable_segui]\', ";
                        $this->registraTransaccionLog(64,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
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
             
     private function semaforo_estado($tupla, $key){
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
        
        private function semaforo_estado_excel($tupla, $key){
                //,cantidad_evidencia    
            switch ($tupla[$key]) {
                    case 'Realizado':
                        $html = '<img src="diseno/images/realizo.png" title="Realizado"/>';
                        $html = 4;
                        break;
                    case 'Realizado con atraso':
                        $html = '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>';
                        $html = 3;
                        break;
                    case 'Plazo vencido':
                        $html = '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>';
                        $html = 1;
                        break;
                    case 'En el plazo':
                        $html = '<img src="diseno/images/SemPlazo.png" title="En el plazo"/>';
                        $html = 2;
                        break;
                    default:
                        return '';
                        break;
                }
                return $html;
              $html ='';
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
        
        private function semaforo_estado_ver($tupla, $key){
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
                    $html .= '<font color="#006600">'." ".str_pad(abs($tupla["dias_evi"]) ,4,0,STR_PAD_LEFT).'</font>';                       
                }
                else{
                    $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias_evi"]) ,4,0,STR_PAD_LEFT).'</font>';
                }
            }
            else{
                $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias_evi"]) ,4,0,STR_PAD_LEFT).'</font>';
            }
            return $html;
        }
        
        private function semaforo_estado_ver_excel($tupla, $key){
                //,cantidad_evidencia               
                switch ($tupla[$key]) {
                    case 'Realizado':
                        $html = '<img src="diseno/images/realizo.png" title="Realizado"/>';
                        $html = 4;
                        break;
                    case 'Realizado con atraso':
                        $html = '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>';
                        $html = 3;
                        break;
                    case 'Plazo vencido':
                        $html = '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>';
                        $html = 1;
                        break;
                    case 'En el plazo':
                        $html = '<img src="diseno/images/SemPlazo.png" title="En el plazo"/>';
                        $html = 2;
                        break;
                    default:
                        return '';
                        break;
                }
                return $html;
            $html = '';
            if (strpos($tupla[$key],"vencido") === false){
                if (strpos($tupla[$key],"atraso") === false){
                    $html .= '<font color="#006600">'." ".str_pad(abs($tupla["dias_evi"]) ,4,0,STR_PAD_LEFT).'</font>';                       
                }
                else{
                    $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias_evi"]) ,4,0,STR_PAD_LEFT).'</font>';
                }
            }
            else{
                $html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias_evi"]) ,4,0,STR_PAD_LEFT).'</font>';
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
            $html = '';
            if (strlen($tupla[id])>0)
                $html = str_pad($tupla[$key],3,0,STR_PAD_LEFT) . ' <a onclick="EvidenciasMuestra('. $tupla[id].')" href="#"><i style="cursor:pointer"  class="icon icon-view-document"  title="Evidencias"></i> </a>';
            return $html;
        }
        
        private function cantidad_evidencia_ver($tupla, $key){
                //,cantidad_evidencia    
            $html = str_pad($tupla[$key],3,0,STR_PAD_LEFT) . ' <a onclick="adminEvidenciasVer('.$tupla[id_ac].')" href="#"><i style="cursor:pointer"  class="icon icon-view-document"  title="Evidencias"></i> </a>';
            return $html;
        }
        
        private function formatear_descripcion($tupla,$key){
            if (strlen($tupla[descripcion])>200)
                return substr($tupla[descripcion], 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[id]. '-des" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[id]. '-des" value="'.$tupla[descripcion].'"/>
                    </a>';
            return $tupla[descripcion];
        }
        
        private function formatear_analisis_causal($tupla,$key){
            if (strlen($tupla[analisis_causal])>200)
                return substr($tupla[analisis_causal], 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[id]. '-ac" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[id]. '-ac" value="'.$tupla[analisis_causal].'"/>
                    </a>';
            return $tupla[analisis_causal];
        }
        
        private function formatear_accion($tupla,$key){
            if (strlen($tupla[accion])>200)
                return substr($tupla[accion], 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[id]. '-acc" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[id]. '-acc" value="'.$tupla[accion].'"/>
                    </a>';
            return $tupla[accion];
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
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "id", $parametros)),  
                    array( "width"=>"5%","ValorEtiqueta"=>"Estado"), 
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "id_2", $parametros)),     
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[alto_potencial], "alto_potencial", $parametros)),     
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[origen_hallazgo], "origen_hallazgo", $parametros)),
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_generacion], "fecha_generacion", $parametros)),
                    array( "width"=>"25%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros,250)),
                    array( "width"=>"25%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[analisis_causal], "analisis_causal", $parametros,250)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[responsable_analisis], "responsable_analisis", $parametros)));;
                    
              if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
              $k = 9;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }
                
                
               
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_proceso], "id_proceso", $parametros)));
               
               if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Id Accion"));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[trazabilidad], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[tipo], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>'<div style="width:250px;height: 20px;">'.htmlentities($this->nombres_columnas_ac[accion], ENT_QUOTES, "UTF-8").'</div>'));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_acordada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_realizada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[id_responsable], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[estado_seguimiento], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
                
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[trazabilidad])));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_acordada], "fecha_acordada", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_realizada], "fecha_realizada", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable_segui], "id_responsable_segui", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estado_seguimiento], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
                
                

                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 12;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesCorrectivas'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar AccionesCorrectivas'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar AccionesCorrectivas'>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:80px'>&nbsp;</div>"));
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
                $this->hidden = $grid->hidden;                
                //$grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                //$grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                //$out['tabla']= $grid->armarTabla();
                
                $titulosColumna="<thead><tr height=\"30px\">";
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
                $this->funciones["descripcion"] = "formatear_descripcion";
                $this->funciones["analisis_causal"] = "formatear_analisis_causal";
                //BuscaProceso
                $colbotones = $columna_funcion;
                $funciones = array();
                $datos = '';
                if ((is_array($data)) && (count($data)>0)) {
                    foreach($data as $fila ){               
                        if($fila[0]!=-1){
                            $col=0;                                                    
                             $sql = "SELECT                                        
                                        aacco.id
                                        ,(select count(id) from mos_acciones_evidencia where id_accion=aacco.id) as cantidad 
                                        ,tac.descripcion tipo
                                        ,aacco.accion
                                        ,DATE_FORMAT(aacco.fecha_acordada, '%d/%m/%Y') fecha_a
                                        ,DATE_FORMAT(aacco.fecha_realizada, '%d/%m/%Y') fecha_r
                                        ,CONCAT(CONCAT(UPPER(LEFT(per.nombres, 1)), LOWER(SUBSTRING(per.nombres, 2))),' ', CONCAT(UPPER(LEFT(per.apellido_paterno, 1)), LOWER(SUBSTRING(per.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(per.apellido_materno, 1)), LOWER(SUBSTRING(per.apellido_materno, 2)))) as responsable
                                        ,CASE WHEN NOT aacco.fecha_acordada IS NULL THEN 
                                                    CASE WHEN NOT aacco.fecha_realizada IS NULL THEN
                                                                   CASE WHEN aacco.fecha_realizada <= aacco.fecha_acordada 
                                                                                   THEN 'Realizado'
                                                                                   ElSE 'Realizado con atraso'
                                                                   END
                                                           WHEN CURRENT_DATE() > aacco.fecha_acordada THEN 
                                                                           'Plazo vencido'
                                                           ELSE 'En el plazo'
                                                   END 
                                           ELSE ''
                                        END sema
                                        ,CASE WHEN NOT aacco.fecha_acordada IS NULL THEN 
                                                CASE WHEN NOT aacco.fecha_realizada IS NULL THEN
                                                        CASE WHEN aacco.fecha_realizada <= aacco.fecha_acordada
                                                                THEN 0
                                                            ElSE DATEDIFF(aacco.fecha_realizada,aacco.fecha_acordada )
                                                        END
                                                    WHEN CURRENT_DATE() > aacco.fecha_acordada THEN 
                                                        DATEDIFF(CURRENT_DATE(),aacco.fecha_acordada)
                                                    ELSE DATEDIFF(aacco.fecha_acordada,CURRENT_DATE())
                                                END 
                                            ELSE NULL 
                                        END dias			  
                                        ,(select count(id) from mos_acciones_evidencia where id_accion_correctiva=ac.id) as cantidad_evi 
                                        ,DATE_FORMAT(ac.fecha_acordada, '%d/%m/%Y') fecha_a_evi
                                        ,DATE_FORMAT(ac.fecha_realizada, '%d/%m/%Y') fecha_r_evi
                                        ,CONCAT(CONCAT(UPPER(LEFT(per_evi.nombres, 1)), LOWER(SUBSTRING(per_evi.nombres, 2))),' ', CONCAT(UPPER(LEFT(per_evi.apellido_paterno, 1)), LOWER(SUBSTRING(per_evi.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(per_evi.apellido_materno, 1)), LOWER(SUBSTRING(per_evi.apellido_materno, 2)))) as responsable_seg
                                        ,CASE WHEN NOT ac.fecha_acordada IS NULL THEN 
                                            CASE WHEN NOT ac.fecha_realizada IS NULL THEN
                                                CASE WHEN ac.fecha_realizada <= ac.fecha_acordada 
                                                    THEN 'Realizado'
                                                    ElSE 'Realizado con atraso'
                                                END
                                                WHEN CURRENT_DATE() > ac.fecha_acordada THEN 
                                                    'Plazo vencido'
                                                    ELSE 'En el plazo'
                                                END 
                                            ELSE ''
                                        END sema_evi
                                        ,CASE WHEN NOT ac.fecha_acordada IS NULL THEN 
                                            CASE WHEN NOT ac.fecha_realizada IS NULL THEN
                                                CASE WHEN ac.fecha_realizada <= ac.fecha_acordada
                                                    THEN 0
                                                    ElSE DATEDIFF(ac.fecha_realizada,ac.fecha_acordada )
                                                END
                                                WHEN CURRENT_DATE() > ac.fecha_acordada THEN 
                                                    DATEDIFF(CURRENT_DATE(),ac.fecha_acordada)
                                                ELSE DATEDIFF(ac.fecha_acordada,CURRENT_DATE())
                                            END 
                                            ELSE NULL 
                                        END dias_evi	
                                        ,ac.id id_ac
                                    FROM mos_acciones_correctivas ac                                     
                                    LEFT JOIN mos_acciones_ac_co aacco on ac.id = aacco.id_ac
                                    LEFT JOIN mos_tipo_ac tac ON tac.id = aacco.tipo
                                    left join mos_personal per on per.cod_emp = aacco.id_responsable
                                    left join mos_personal per_evi on per_evi.cod_emp = ac.id_responsable_segui
                                    WHERE ac.id =  $fila[id]";
                            //echo $sql;
                            //semaforo_estado,cantidad_evidencia
                            $this->funciones["cantidad"] = "cantidad_evidencia";
                            $this->funciones["cantidad_evi"] = "cantidad_evidencia_ver";
                            $this->funciones["sema_evi"] = "semaforo_estado_ver";
                            $this->funciones["sema"] = "semaforo_estado";
                            $this->funciones["accion"] = "formatear_accion";
                            
                            $data_aux = $this->dbl->query($sql, array());
                            //print_r($data_aux);
                            //print_r($data_aux);
                            $num_filas_recorridas_int = 0;                          
                            $total_semaforo_final = 0;
                            $plazo_vencido = 0;
                            $plazo_atrasado = 0;
                            $plazo_plazo = 0;
                            $num_factor_sema = 0; //para el calculo del semaforo final numero de factores
                            $sum_factor_sema = 0; //para el calculo del semaforo final numero de acciones completadas
                            foreach ($data_aux as $fila_aux) {
                                
                                
                                foreach($fila_aux as $key=>$value){
                                    switch ($value) {
                                        case 'Realizado':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            break;
                                        case 'Realizado con atraso':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            $plazo_atrasado = 1;
                                            break;
                                        case 'Plazo vencido':
                                            $num_factor_sema++;
                                            $plazo_vencido = 1;
                                            break;
                                        case 'En el plazo':
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
                                $valor = '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>';
                            }
                            else if ($plazo_plazo >= 1){
                                $valor = '<img src="diseno/images/SemPlazo.png" title="En el plazo"/>';
                            }
                            else if ($plazo_atrasado >= 1){
                                $valor = '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>';
                            }
                            else if (strlen($data_aux[0][id])<=0){
                                $valor = '<img src="diseno/images/atrasado.png" title="Sin Acciones Cargadas"/>';
                            }else{
                               
                                $valor = '<img src="diseno/images/realizo.png" title="Realizado"/>';
                            }                      
                            $fila[estado] = $valor;
                                                       
                            $num_acc = count($data_aux);
                            $datos.="<tr onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'  class=\"DatosGrilla\">";                            
                                $datos.="	<td rowspan=\"$num_acc\" align=\"center\" $atributos>";
                                
                                if($_SESSION[CookM] == 'S'){
                                    $datos .= "<a onclick=\"javascript:editarAccionesCorrectivas('". $fila[id] . "');\">
                                                <i style='cursor:pointer'  class=\"icon icon-edit\" title=\"Modificar Acción Correctiva ". $fila['p1']."\"></i>
                                            </a>";
                                }
                                if($_SESSION[CookE] == 'S'){
                                    $datos .= '<a onclick="javascript:eliminarAccionesCorrectivas(\''. $fila[id] . '\');">
                                            <i style="cursor:pointer"  class="icon icon-remove" title="Eliminar Acción Correctiva '.$fila[id].'"></i>
                                        </a>'; 
                                }
                                if(($_SESSION[CookN] == 'S')||($_SESSION[CookM] == 'S')){
                                    $datos .= "<a onclick=\"javascript:verAcciones('". $fila[id] . "');\">
                                                <i style='cursor:pointer'  class=\"icon icon-more\" title=\"Administrar Acciones Correctivas ". $fila['id']."\"></i>
                                            </a>";
                                }
                                {
                                    $datos .= "<a tok=\"". $fila[id] . "\" class=\"ver-reporte\">
                                                <i style='cursor:pointer'  class=\"icon icon-view-document\" title=\"Ver Reporte ". $fila['id']."\"></i>
                                            </a>";
                                }
                                
                                
                                $datos.="	</td>\n";
                            
                               // $datos.="<td rowspan=\"$fila[num_acc]\" align='center'>". ($valor)."</td>\n";     
                            foreach($fila as $key=>$value){
                                
                                if ($col == 0) $col_id = $key;                       
                                if (!is_integer($key))
                                {                       
                                    //echo $key . ' - ';
                                    if($this->hidden[$col]==true){
                                        //echo $col . ' ';
                                    }
                                    elseif ($col==$this->hide)
                                        $datos.="<td rowspan=\"$num_acc\"  $atributos style=\"display:none\" > $fila[$col] &nbsp;</td>\n";
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
                                            $datos.="<td rowspan=\"$num_acc\" $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                    }
                                    $col++;
                                }

                            }
                            $col_aux = $col;
                            $num_filas_recorridas_int = 0;  
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
                                            switch ($key) {
                                                case 'cantidad_evi':
                                                case 'fecha_a_evi':
                                                case 'fecha_r_evi':
                                                case 'responsable_seg':
                                                case 'sema_evi':
                                                    if ($num_filas_recorridas_int <= 1)
                                                        $datos.="<td rowspan=\"$num_acc\" $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                                    break;

                                                default:
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                                    break;
                                            }
                                            
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
                
                $grid->setPagina($parametros['pag']);
                $out['tabla'] = '<table id="tblAccionesCorrectivas" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">' . $titulosColumna . $datos.'</table>';
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


//            $grid= new DataGrid();
//            $this->listarAccionesCorrectivas($parametros, 1, 100000);
//            $data=$this->dbl->data;
//
//            if (count($this->nombres_columnas) <= 0){
//                        $this->cargar_nombres_columnas();
//                }
//             $grid->SetConfiguracion("tblAccionesCorrectivas", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
//                $config_col=array(
//                 
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[origen_hallazgo], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_generacion], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[analisis_causal], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[responsable_analisis], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_proceso], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_acordada], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_realizada], ENT_QUOTES, "UTF-8")),
//         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_responsable_segui], ENT_QUOTES, "UTF-8"))
//              );
//                $columna_funcion =10;
//           /* $grid->hidden = array(0 => true);
//           if (count($this->parametros) <= 0){
//                        $this->cargar_parametros();
//                }*/
//                $k = 1;
//                foreach ($this->parametros as $value) {                    
//                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  utf8_decode($value[espanol])));
//                    $k++;
//                }
//                $columna_funcion =10;
//                $config = array();            
//                $array_columns =  explode('-', $parametros['mostrar-col']);            
//                for($i=0;$i<count($config_col);$i++){
//                    switch ($i) {                                             
//                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
//                            array_push($config,$config_col[$i]);
//                            break;
//                        default:                            
//                            if (in_array($i, $array_columns)) {
//                                array_push($config,$config_col[$i]);
//                            }
//                            else                                
//                                $grid->hidden[$i] = true;                            
//                            break;
//                    }
//                }
                
            $grid= "";
                $grid= new DataGrid();
//                if ($parametros['pag'] == null) 
//                    $parametros['pag'] = 1;
//                $reg_por_pagina = getenv("PAGINACION");
//                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarAccionesCorrectivas($parametros, 1, 100000);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesCorrectivas", "");
                $config_col=array(
                    array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),  
                    array( "width"=>"5%","ValorEtiqueta"=>"Estado"), 
                    array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id], ENT_QUOTES, "UTF-8")),     
                    array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[alto_potencial], ENT_QUOTES, "UTF-8")),     
                    array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[origen_hallazgo], ENT_QUOTES, "UTF-8")),
                    array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_generacion], ENT_QUOTES, "UTF-8")),
                    array( "width"=>"25%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8")),
                    array( "width"=>"25%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[analisis_causal], ENT_QUOTES, "UTF-8")),
                    array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[responsable_analisis], ENT_QUOTES, "UTF-8")));;
                    
              if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
              $k = 9;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>htmlentities(($value[espanol]), ENT_QUOTES, "UTF-8")));
                    $k++;
                }
                
                
               
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_proceso], ENT_QUOTES, "UTF-8")));
               
               if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Id Accion"));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[trazabilidad], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[tipo], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>'<div style="width:250px;height: 20px;">'.htmlentities($this->nombres_columnas_ac[accion], ENT_QUOTES, "UTF-8").'</div>'));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_acordada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_realizada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[id_responsable], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[estado_seguimiento], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
                
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[trazabilidad])));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>  htmlentities($this->nombres_columnas[fecha_acordada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_realizada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_responsable_segui], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estado_seguimiento], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
                
                

                $func= array();

                $columna_funcion = 0;
                $config = array();
                //$config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:80px'>&nbsp;</div>"));
                //$grid->setPaginado($reg_por_pagina, $this->total_registros);
                //echo $parametros['mostrar-col'];
                $array_columns =  explode('-', $parametros['mostrar-col']);
                //print_r($array_columns);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 0:
                            $grid->hidden[$i] = true;
                            break;
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:                        
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
                $this->hidden = $grid->hidden;                
                //$grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                //$grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                //$out['tabla']= $grid->armarTabla();
                
                $titulosColumna="<thead><tr height=\"30px\">";
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
                //$this->funciones["descripcion"] = "formatear_descripcion";
                //$this->funciones["analisis_causal"] = "formatear_analisis_causal";
                //BuscaProceso
                $colbotones = $columna_funcion;
                $funciones = array();
                $datos = '';
                if ((is_array($data)) && (count($data)>0)) {
                    foreach($data as $fila ){               
                        if($fila[0]!=-1){
                            $col=0;                                                    
                                                        $sql = "SELECT                                        
                                        aacco.id
                                        ,(select count(id) from mos_acciones_evidencia where id_accion=aacco.id) as cantidad 
                                        ,tac.descripcion tipo
                                        ,aacco.accion
                                        ,DATE_FORMAT(aacco.fecha_acordada, '%d/%m/%Y') fecha_a
                                        ,DATE_FORMAT(aacco.fecha_realizada, '%d/%m/%Y') fecha_r
                                        ,CONCAT(CONCAT(UPPER(LEFT(per.nombres, 1)), LOWER(SUBSTRING(per.nombres, 2))),' ', CONCAT(UPPER(LEFT(per.apellido_paterno, 1)), LOWER(SUBSTRING(per.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(per.apellido_materno, 1)), LOWER(SUBSTRING(per.apellido_materno, 2)))) as responsable
                                        ,CASE WHEN NOT aacco.fecha_acordada IS NULL THEN 
                                                    CASE WHEN NOT aacco.fecha_realizada IS NULL THEN
                                                                   CASE WHEN aacco.fecha_realizada <= aacco.fecha_acordada 
                                                                                   THEN 'Realizado'
                                                                                   ElSE 'Realizado con atraso'
                                                                   END
                                                           WHEN CURRENT_DATE() > aacco.fecha_acordada THEN 
                                                                           'Plazo vencido'
                                                           ELSE 'En el plazo'
                                                   END 
                                           ELSE ''
                                        END sema
                                        ,CASE WHEN NOT aacco.fecha_acordada IS NULL THEN 
                                                CASE WHEN NOT aacco.fecha_realizada IS NULL THEN
                                                        CASE WHEN aacco.fecha_realizada <= aacco.fecha_acordada
                                                                THEN 0
                                                            ElSE DATEDIFF(aacco.fecha_realizada,aacco.fecha_acordada )
                                                        END
                                                    WHEN CURRENT_DATE() > aacco.fecha_acordada THEN 
                                                        DATEDIFF(CURRENT_DATE(),aacco.fecha_acordada)
                                                    ELSE DATEDIFF(aacco.fecha_acordada,CURRENT_DATE())
                                                END 
                                            ELSE NULL 
                                        END dias			  
                                        ,(select count(id) from mos_acciones_evidencia where id_accion_correctiva=ac.id) as cantidad_evi 
                                        ,DATE_FORMAT(ac.fecha_acordada, '%d/%m/%Y') fecha_a_evi
                                        ,DATE_FORMAT(ac.fecha_realizada, '%d/%m/%Y') fecha_r_evi
                                        ,CONCAT(CONCAT(UPPER(LEFT(per_evi.nombres, 1)), LOWER(SUBSTRING(per_evi.nombres, 2))),' ', CONCAT(UPPER(LEFT(per_evi.apellido_paterno, 1)), LOWER(SUBSTRING(per_evi.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(per_evi.apellido_materno, 1)), LOWER(SUBSTRING(per_evi.apellido_materno, 2)))) as responsable_seg
                                        ,CASE WHEN NOT ac.fecha_acordada IS NULL THEN 
                                            CASE WHEN NOT ac.fecha_realizada IS NULL THEN
                                                CASE WHEN ac.fecha_realizada <= ac.fecha_acordada 
                                                    THEN 'Realizado'
                                                    ElSE 'Realizado con atraso'
                                                END
                                                WHEN CURRENT_DATE() > ac.fecha_acordada THEN 
                                                    'Plazo vencido'
                                                    ELSE 'En el plazo'
                                                END 
                                            ELSE ''
                                        END sema_evi
                                        ,CASE WHEN NOT ac.fecha_acordada IS NULL THEN 
                                            CASE WHEN NOT ac.fecha_realizada IS NULL THEN
                                                CASE WHEN ac.fecha_realizada <= ac.fecha_acordada
                                                    THEN 0
                                                    ElSE DATEDIFF(ac.fecha_realizada,ac.fecha_acordada )
                                                END
                                                WHEN CURRENT_DATE() > ac.fecha_acordada THEN 
                                                    DATEDIFF(CURRENT_DATE(),ac.fecha_acordada)
                                                ELSE DATEDIFF(ac.fecha_acordada,CURRENT_DATE())
                                            END 
                                            ELSE NULL 
                                        END dias_evi	
                                        ,ac.id id_ac
                                    FROM mos_acciones_correctivas ac                                     
                                    LEFT JOIN mos_acciones_ac_co aacco on ac.id = aacco.id_ac
                                    LEFT JOIN mos_tipo_ac tac ON tac.id = aacco.tipo
                                    left join mos_personal per on per.cod_emp = aacco.id_responsable
                                    left join mos_personal per_evi on per_evi.cod_emp = ac.id_responsable_segui
                                    WHERE ac.id =  $fila[id]";
                            //echo $sql;
                            //semaforo_estado,cantidad_evidencia
                            $this->funciones["cantidad"] = "cantidad_evidencia";
                            $this->funciones["cantidad_evi"] = "cantidad_evidencia_ver";
                            $this->funciones["sema_evi"] = "semaforo_estado_ver_excel";
                            $this->funciones["sema"] = "semaforo_estado_excel";
                            //$this->funciones["accion"] = "formatear_accion";
                            
                            $data_aux = $this->dbl->query($sql, array());
                            //print_r($data_aux);
                            //print_r($data_aux);
                            $num_filas_recorridas_int = 0;                          
                            $total_semaforo_final = 0;
                            $plazo_vencido = 0;
                            $plazo_atrasado = 0;
                            $plazo_plazo = 0;
                            $num_factor_sema = 0; //para el calculo del semaforo final numero de factores
                            $sum_factor_sema = 0; //para el calculo del semaforo final numero de acciones completadas
                            foreach ($data_aux as $fila_aux) {
                                
                                
                                foreach($fila_aux as $key=>$value){
                                    switch ($value) {
                                        case 'Realizado':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            break;
                                        case 'Realizado con atraso':
                                            $num_factor_sema++;
                                            $sum_factor_sema++;
                                            $plazo_atrasado = 1;
                                            break;
                                        case 'Plazo vencido':
                                            $num_factor_sema++;
                                            $plazo_vencido = 1;
                                            break;
                                        case 'En el plazo':
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
                                $valor = '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>';
                                $valor = 1;
                            }
                            else if ($plazo_plazo >= 1){
                                $valor = '<img src="diseno/images/SemPlazo.png" title="En el plazo"/>';
                                $valor = "2";
                            }
                            else if ($plazo_atrasado >= 1){
                                $valor = '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>';
                                $valor = 3;
                            }
                            else if (strlen($data_aux[0][id])<=0){
                                $valor = '<img src="diseno/images/atrasado.png" title="Sin Acciones Cargadas"/>';
                                $valor = 1;
                            }else{
                               
                                $valor = '<img src="diseno/images/realizo.png" title="Realizado"/>';
                                $valor = 4;
                            }                      
                            $fila[estado] = $valor;
                                                       
                            $num_acc = count($data_aux);
                            $datos.="<tr onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'  class=\"DatosGrilla\">";                            
                               
                            
                               // $datos.="<td rowspan=\"$fila[num_acc]\" align='center'>". ($valor)."</td>\n";     
                            foreach($fila as $key=>$value){
                                
                                if ($col == 0) $col_id = $key;                       
                                if (!is_integer($key))
                                {                       
                                    //echo $key . ' - ';
                                    if($this->hidden[$col]==true){
                                        //echo $col . ' ';
                                    }
                                    elseif ($col==$this->hide)
                                        $datos.="<td rowspan=\"$num_acc\"  $atributos style=\"display:none\" > $fila[$col] &nbsp;</td>\n";
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
                                            $datos.="<td rowspan=\"$num_acc\" $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                    }
                                    $col++;
                                }

                            }
                            $col_aux = $col;
                            $num_filas_recorridas_int = 0;  
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
                                            switch ($key) {
                                                case 'cantidad_evi':
                                                case 'fecha_a_evi':
                                                case 'fecha_r_evi':
                                                case 'responsable_seg':
                                                case 'sema_evi':
                                                    if ($num_filas_recorridas_int <= 1)
                                                        $datos.="<td rowspan=\"$num_acc\" $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                                    break;

                                                default:
                                                    $datos.="<td $atributos align='" . $this->aligns[$col] . "'>". ($valor)."</td>\n";
                                                    break;
                                            }
                                            
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
                
                $grid->setPagina($parametros['pag']);
                $out['tabla'] = '<table id="tblAccionesCorrectivas" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">' . $titulosColumna . $datos.'</table>';
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
//                {
//                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
//                }
                return $out['tabla'];
                return $out;
                
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexAccionesCorrectivas($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id_2";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-8"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 9;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    $parametros['mostrar-col'] .= "-$k"; //checked="checked"
                    $contenido[PARAMETROS_OTROS] .= '
                                  <div class="checkbox">      
                                      <label>
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $value[espanol] . '</label>
                                  </div>
                            ';
                    $k++;
                }
                
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                } 
                $contenido[PARAMETROS_OTROS_AE_AO] = '';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($key == 'id_organizacion'){                            
                            $parametros['mostrar-col'] .= "-$k"; //checked="checked"
                            $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                    <div class="checkbox">      
                                        <label >
                                            <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                        ' . $this->nombres_columnas[id_organizacion] . '</label>
                                    </div>
                              ';
                        }                    
                        else{                                                
                            //$parametros['mostrar-col'] .= "-$k"; checked="checked"
                            $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas[id_proceso] . '</label>
                                  </div>
                            ';
                        }
                    }   
                    $k++;
                } 
                //$total = $k + 7;
                //for(;$k<=$total;$k++){
                
                /*
                 array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[trazabilidad], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[tipo], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[accion], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_acordada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_realizada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[id_responsable], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[estado_seguimiento], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
                
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[trazabilidad])));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_acordada], "fecha_acordada", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_realizada], "fecha_realizada", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable_segui], "id_responsable_segui", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estado_seguimiento], ENT_QUOTES, "UTF-8")));

                 */
                if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
                $k++;                
                $parametros['mostrar-col'] .= "-". ($k); //Columna Trazabilidad checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[trazabilidad] . '</label>
                                  </div>
                           ';
                $k++;                
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Tipo checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[tipo] . '</label>
                                  </div>
                           ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Accion checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[accion] . '</label>
                                  </div>
                           ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Acordada checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[fecha_acordada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Realizada checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[fecha_realizada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Responsable checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[id_responsable] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Semaforo checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas_ac[estado_seguimiento] . '</label>
                                  </div>
                            ';
                $k = $k + 2;                
                $parametros['mostrar-col'] .= "-". ($k); //Columna Trazabilidad EVI
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked" >   &nbsp;
                                      ' . $this->nombres_columnas[trazabilidad] . '</label>
                                  </div>
                            ';
                $k++;                
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Acordada EVI checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas[fecha_acordada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Realizada EVI checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas[fecha_realizada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Responsable EVI checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" checked="checked" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas[id_responsable_segui] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Semaforo EVI
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                      ' . $this->nombres_columnas[estado_seguimiento] . '</label>
                                  </div>
                            ';
                //}
                
                $ut_tool = new ut_Tool();
                $contenido[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                $contenido[ORIGENES] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_origen_ac ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', $value[valor]);
                
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

                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                

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
                $objResponse->addAssign('modulo_actual',"value","acciones_correctivas");
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_correctivas/acciones_correctivas.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                $objResponse->addScript('setTimeout(function(){ init_tabla(); }, 500);');
                return $objResponse;
            }
            
            public function indexAccionesCorrectivasReporte($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id_2";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-8"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 9;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    $parametros['mostrar-col'] .= "-$k"; //checked="checked"
                    $contenido[PARAMETROS_OTROS] .= '
                                  <div class="checkbox">      
                                      <label>
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $value[espanol] . '</label>
                                  </div>
                            ';
                    $k++;
                }
                
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                } 
                $contenido[PARAMETROS_OTROS_AE_AO] = '';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($key == 'id_organizacion'){                            
                            $parametros['mostrar-col'] .= "-$k"; //checked="checked"
                            $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                    <div class="checkbox">      
                                        <label >
                                            <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                        ' . $this->nombres_columnas[id_organizacion] . '</label>
                                    </div>
                              ';
                        }                    
                        else{                                                
                            //$parametros['mostrar-col'] .= "-$k"; checked="checked"
                            $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas[id_proceso] . '</label>
                                  </div>
                            ';
                        }
                    }   
                    $k++;
                } 

                if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
                $k++;                
                $parametros['mostrar-col'] .= "-". ($k); //Columna Trazabilidad checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[trazabilidad] . '</label>
                                  </div>
                           ';
                $k++;                
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Tipo checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[tipo] . '</label>
                                  </div>
                           ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Accion checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[accion] . '</label>
                                  </div>
                           ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Acordada checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[fecha_acordada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Realizada checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[fecha_realizada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Responsable checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[id_responsable] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Semaforo checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas_ac[estado_seguimiento] . '</label>
                                  </div>
                            ';
                $k = $k + 2;                
                $parametros['mostrar-col'] .= "-". ($k); //Columna Trazabilidad EVI
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked" >   &nbsp;
                                      ' . $this->nombres_columnas[trazabilidad] . '</label>
                                  </div>
                            ';
                $k++;                
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Acordada EVI checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas[fecha_acordada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Realizada EVI checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas[fecha_realizada] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Responsable EVI checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" checked="checked" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas[id_responsable_segui] . '</label>
                                  </div>
                            ';
                $k++;
                $parametros['mostrar-col'] .= "-". ($k); //Columna Semaforo EVI
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                      ' . $this->nombres_columnas[estado_seguimiento] . '</label>
                                  </div>
                            ';
                //}
                
                $ut_tool = new ut_Tool();
                $contenido[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                $contenido[ORIGENES] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_origen_ac ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', $value[valor]);
                
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

                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                
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
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                
                 $template->setTemplate("busqueda_reporte");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR_FILTRO'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                

                $template->setTemplate("dashboard_1");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","acciones_correctivas");
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_correctivas/acciones_correctivas_report.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                $objResponse->addScript('setTimeout(function(){ init_tabla(); }, 500);');
                $objResponse->addScript('setTimeout(function(){ init_graficos(); }, 500);');
                return $objResponse;
            }
         
            public function cargar_campos_activos(){
                $sql = "SELECT campo, activo, orden FROM mos_campos_activos WHERE modulo = 15";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->campos_activos[$value[campo]] = array($value[activo],$value[orden]);
                }
                
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
                $contenido_1[RESPONSABLE_SEGUI] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                $contenido_1[ORIGENES] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_origen_ac ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', $value[valor]);
                $contenido_1[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                } 
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($key == 'id_organizacion'){
                            $contenido_1[ID_ORGANIZACIONES] = '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $this->nombres_columnas[id_organizacion] . '</label>';
                            $contenido_1[ID_ORGANIZACIONES] .= '<div class="col-md-10" style="">  
                                        <a href="#" data-toggle="modal" style="" data-target="#myModal-Filtrar-Arbol">[Seleccionar]</a> 
                                        <span id="desc-arbol"></span>                                        
                                        <input type="hidden" value="" id="nivel" name="nivel" data-validation="required"/>                                    
                                    </div>';
                            $contenido_1[ID_ORGANIZACIONES] .= '</div>';
                        }
                    
                        else{
                    
                            $contenido_1[ID_PROCESOS] = '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $this->nombres_columnas[id_proceso] . '</label>';
                            $contenido_1[ID_PROCESOS] .= '<div class="col-md-10" style="">  
                                        <a href="#" data-toggle="modal" style="" data-target="#myModal-Filtrar-Proceso">[Seleccionar]</a> 
                                        <span id="desc-proceso"></span>                                        
                                        <input type="hidden" value="" id="proceso" name="proceso" />                                    
                                    </div>';
                            $contenido_1[ID_PROCESOS] .= '</div>';
                        }
                    }   
                    
                } 
                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                $array = $campos_dinamicos->crear_campos_dinamicos(8);
                $contenido_1[CAMPOS_DINAMICOS] = $array[html];
                $js = $array[js];
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesCorrectivas";
                $contenido['PAGINA_VOLVER'] = "listarAccionesCorrectivas.php";
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
                          });");
                $objResponse->addScript($array[js]);
                $objResponse->addScript("$('#fecha_generacion').datepicker();");
                $objResponse->addScript("$('#fecha_acordada').datepicker();");
                $objResponse->addScript("$('#fecha_realizada').datepicker();");
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
                    $parametros["fecha_generacion"] = formatear_fecha($parametros["fecha_generacion"]);
                    if (strlen($parametros["fecha_acordada"])>0)
                        $parametros["fecha_acordada"] = formatear_fecha($parametros["fecha_acordada"]);
                    if (strlen($parametros["fecha_realizada"])>0)
                        $parametros["fecha_realizada"] = formatear_fecha($parametros["fecha_realizada"]);
                    $parametros[id_proceso] = $parametros['b-id_proceso_aux'];
                    $parametros[id_organizacion] = $parametros['b-id_organizacion_aux'];
                    if (!isset($parametros[alto_potencial])) $parametros[alto_potencial] = 'N';
                    $respuesta = $this->ingresarAccionesCorrectivas($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        $parametros[id] = $respuesta;
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($parametros, 8);
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',"La Acción Correctiva '$parametros[descripcion]' ha sido ingresado con exito");
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
     
        public function BuscaOrganizacional($tupla)
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
        
        public function BuscaProceso($tupla)
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
                $contenido_1['ORIGEN_HALLAZGO'] = ($val["origen_hallazgo"]);
                $contenido_1['FECHA_GENERACION'] = ($val["fecha_generacion"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['ANALISIS_CAUSAL'] = ($val["analisis_causal"]);
                $contenido_1['RESPONSABLE_ANALISIS'] = $val["responsable_analisis"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                $contenido_1['ID_PROCESO'] = $val["id_proceso"];
                $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
                $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
                $contenido_1['ID_RESPONSABLE_SEGUI'] = $val["id_responsable_segui"];
                $contenido_1[CHECKED_ALTO_POTENCIAL] = $val["alto_potencial"] == 'S' ? 'checked="checked"' : '';
                
                $contenido_1[RESPONSABLE_SEGUI] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_responsable_segui"]);
                $contenido_1[ORIGENES] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_origen_ac ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', $val["origen_hallazgo"]);
                $contenido_1[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["responsable_analisis"]);
                
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                } 
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($key == 'id_organizacion'){
                            $contenido_1[ID_ORGANIZACIONES] = '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $this->nombres_columnas[id_organizacion] . '</label>';
                            $contenido_1[ID_ORGANIZACIONES] .= '<div class="col-md-10" style="">  
                                        <a href="#" data-toggle="modal" style="" data-target="#myModal-Filtrar-Arbol">[Seleccionar]</a> 
                                        <span id="desc-arbol">' . $this->BuscaOrganizacional($val) . '</span>                                        
                                        <input type="hidden" value="'.$val["id_organizacion"].'"  id="nivel" name="nivel" data-validation="required"/>                                    
                                    </div>';
                            $contenido_1[ID_ORGANIZACIONES] .= '</div>';
                        }
                    
                        else{
                    
                            $contenido_1[ID_PROCESOS] = '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $this->nombres_columnas[id_proceso] . '</label>';
                            $contenido_1[ID_PROCESOS] .= '<div class="col-md-10" style="">  
                                        <a href="#" data-toggle="modal" style="" data-target="#myModal-Filtrar-Proceso">[Seleccionar]</a> 
                                        <span id="desc-proceso">' . $this->BuscaProceso($val) . '</span>                                        
                                        <input type="hidden" value="'.$val["id_proceso"].'" id="proceso" name="proceso" />                                    
                                    </div>';
                            $contenido_1[ID_PROCESOS] .= '</div>';
                        }
                    }   
                    
                } 
                
                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                $array = $campos_dinamicos->crear_campos_dinamicos(8,$val["id"]);
                $contenido_1[CAMPOS_DINAMICOS] = $array[html];

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesCorrectivas";
                $contenido['PAGINA_VOLVER'] = "listarAccionesCorrectivas.php";
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
                          });");
                $objResponse->addScript($array[js]);
                $objResponse->addScript("$('#fecha_generacion').datepicker();");
                $objResponse->addScript("$('#fecha_acordada').datepicker();");
                $objResponse->addScript("$('#fecha_realizada').datepicker();");
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
                    $parametros["fecha_generacion"] = formatear_fecha($parametros["fecha_generacion"]);
                    if (strlen($parametros["fecha_acordada"])>0)
                        $parametros["fecha_acordada"] = formatear_fecha($parametros["fecha_acordada"]);
                    if (strlen($parametros["fecha_realizada"])>0)
                        $parametros["fecha_realizada"] = formatear_fecha($parametros["fecha_realizada"]);
                    $parametros[id_proceso] = $parametros['b-id_proceso_aux'];
                    $parametros[id_organizacion] = $parametros['b-id_organizacion_aux'];
                    if (!isset($parametros[alto_potencial])) $parametros[alto_potencial] = 'N';

                    $respuesta = $this->modificarAccionesCorrectivas($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($parametros, 8);
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
                $objResponse->addScript("init_tabla();");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verAccionesCorrectivas($parametros[id]);

                $contenido_1['ORIGEN_HALLAZGO'] = ($val["origen_hallazgo"]);
                $contenido_1['FECHA_GENERACION'] = ($val["fecha_generacion"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['ANALISIS_CAUSAL'] = ($val["analisis_causal"]);
                $contenido_1['RESPONSABLE_ANALISIS'] = $val["responsable_analisis"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                $contenido_1['ID_PROCESO'] = $val["id_proceso"];
                $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
                $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
                $contenido_1['ID_RESPONSABLE_SEGUI'] = $val["id_responsable_segui"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
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