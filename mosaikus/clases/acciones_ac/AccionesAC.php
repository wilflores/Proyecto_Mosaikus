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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo in (16,100)  AND id_idioma = $_SESSION[CookIdIdioma]";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            public function cargar_nombres_columnas_acciones(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 15  AND id_idioma = $_SESSION[CookIdIdioma]";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas_ac[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 16  AND id_idioma = $_SESSION[CookIdIdioma]";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verAccionesAC($id){
                $atr=array('id' =>$id);
                $atr = $this->dbl->corregir_parametros($atr);
                $id = $atr[id];
                $sql = "SELECT acco.id
                            ,tipo
                            ,ta.descripcion tipo_desc
                            ,accion
                            ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada
                            ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada
                            ,id_responsable
                            ,id_ac
                            ,id_correcion
                            ,estado
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
                            ,CONCAT(initcap(SUBSTR(per.nombres,1,IF(LOCATE(' ' ,per.nombres,1)=0,LENGTH(per.nombres),LOCATE(' ' ,per.nombres,1)-1))),' ',initcap(per.apellido_paterno)) as responsable
                            ,(SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE mos_nombres_campos.nombre_campo = acco.estatus_wf AND mos_nombres_campos.modulo = 16  AND id_idioma = $_SESSION[CookIdIdioma]) as estatus_wf
                         FROM mos_acciones_ac_co acco
                         INNER JOIN mos_tipo_ac ta ON ta.id = tipo
                         INNER JOIN mos_personal per on per.cod_emp = acco.id_responsable
                         WHERE acco.id = $id "; 
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
                    else $atr[id_ac] = strlen($atr[id_ac]) > 0 ? $atr[id_ac] : 'NULL';
                    if (isset($_SESSION[id_correccion])){
                        $atr[id_correcion] = $_SESSION[id_correccion];                        
                    }
                    else $atr[id_correcion] = strlen($atr[id_correcion]) > 0 ? $atr[id_correcion] : 'NULL';
                    if (strlen($atr[fecha_realizada]) == 0){
                        $atr[fecha_realizada] = 'NULL';
                    }
                    else{
                        $atr[fecha_realizada] = "'$atr[fecha_realizada]'";
                    }
                    $sql = "INSERT INTO mos_acciones_ac_co(tipo,accion,fecha_acordada,fecha_realizada,id_responsable,id_ac,id_correcion,orden
                        ,id_validador)
                            VALUES(
                                $atr[tipo],'$atr[accion]','$atr[fecha_acordada]',$atr[fecha_realizada],$atr[id_responsable],$atr[id_ac],$atr[id_correcion],$atr[orden]
                                ,$atr[id_validador])";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    if ($atr[fecha_realizada] != 'NULL'){
                        $atr[fecha_realizada] = "\\" . substr($atr[fecha_realizada], 0, strlen($atr[fecha_realizada])-1)  . "\'";
                    }
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_acciones_ac_co ' . $atr[descripcion_ano], 'mos_acciones_ac_co');
                      */
                    $nuevo = "Tipo: \'$atr[tipo]\', Accion: \'$atr[accion]\', Fecha Acordada: \'$atr[fecha_acordada]\', Fecha Realizada: $atr[fecha_realizada], Id Responsable: \'$atr[id_responsable]\', Id Ac: \'$atr[id_ac]\', Id Correcion: \'$atr[id_correcion]\', ";
                    /*Obtenemos el id del nuevo registro*/
                    $sql = "SELECT MAX(id) ultimo FROM mos_acciones_ac_co"; 
                    $this->operacion($sql, $atr);
                    $id_new =  $this->dbl->data[0][0];
                    
                    $this->registraTransaccionLog(62,$nuevo,'', $id_new);
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
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip, id_registro) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]', $id)";            
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
                                    tipo = $atr[tipo],accion = '$atr[accion]',fecha_acordada = '$atr[fecha_acordada]',fecha_realizada = $atr[fecha_realizada],id_responsable = $atr[id_responsable], orden = $atr[orden]
                                    ,id_validador = $atr[id_validador]
                            WHERE  id = $atr[id]";
                    //echo $sql;
                    $val = $this->verAccionesAC($atr[id]);
                    $this->dbl->insert_update($sql);
                    if ($atr[fecha_realizada] != 'NULL'){
                        $atr[fecha_realizada] = "\\" . substr($atr[fecha_realizada], 0, strlen($atr[fecha_realizada])-1)  . "\'";
                    }
                    $nuevo = "Tipo: \'$atr[tipo]\', Accion: \'$atr[accion]\', Fecha Acordada: \'$atr[fecha_acordada]\', Fecha Realizada: $atr[fecha_realizada], Id Responsable: \'$atr[id_responsable]\' ";
                    $anterior = "Tipo: \'$val[tipo]\', Accion: \'$val[accion]\', Fecha Acordada: \'$val[fecha_acordada]\', Fecha Realizada: \'$val[fecha_realizada]\', Id Responsable: \'$val[id_responsable]\'";
                    $this->registraTransaccionLog(63,$nuevo,$anterior, $atr[id]);
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
            
            public function cambiarestadowf($atr){
                    try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_acciones_ac_co 
                        SET                            
                        estatus_wf = '$atr[estado]',                            
                            observacion_rechazo = '$atr[observacion_rechazo]',    
                            fecha_estado_wf = '$atr[fecha_estado_wf]',id_usuario_wf = $atr[id_usuario]
                            WHERE  id = $atr[id]";     
                    //echo $sql;
                    //$val = $this->verDocumentos($atr[id]);
                    $this->dbl->insert_update($sql);
                    
                    $nuevo = "$atr[estado]";
                    $anterior = "$atr[observacion_rechazo]";
                    $this->registraTransaccionLog(22,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el WorkflowDocumentos ' . $atr[descripcion_ano], 'mos_workflow_documentos');
                    */
                    return "El flujo de trabajo de documentos ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }

             }
            
            public function listarAccionesACSinPaginacion($atr){
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
                    if (strlen($atr[valor])>0)
                        $sql_where .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-tipo"])>0)
                        $sql_where .= " AND tipo = '". $atr["b-tipo"] . "'";
                    if (strlen($atr["b-accion"])>0)
                        $sql_where .= " AND upper(accion) like '%" . strtoupper($atr["b-accion"]) . "%'";
                    if (strlen($atr['b-fecha_acordada-desde'])>0)                        
                    {
                        $atr['b-fecha_acordada-desde'] = formatear_fecha($atr['b-fecha_acordada-desde']);                        
                        $sql_where .= " AND fecha_acordada >= '" . ($atr['b-fecha_acordada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_acordada-hasta'])>0)                        
                    {
                        $atr['b-fecha_acordada-hasta'] = formatear_fecha($atr['b-fecha_acordada-hasta']);                        
                        $sql_where .= " AND fecha_acordada <= '" . ($atr['b-fecha_acordada-hasta']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_realizada-desde'])>0)                        
                    {
                        $atr['b-fecha_realizada-desde'] = formatear_fecha($atr['b-fecha_realizada-desde']);                        
                        $sql_where .= " AND fecha_realizada >= '" . ($atr['b-fecha_realizada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_realizada-hasta'])>0)                        
                    {
                        $atr['b-fecha_realizada-hasta'] = formatear_fecha($atr['b-fecha_realizada-hasta']);                        
                        $sql_where .= " AND fecha_realizada <= '" . ($atr['b-fecha_realizada-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-id_responsable"])>0)
                        $sql_where .= " AND id_responsable = '". $atr["b-id_responsable"] . "'";
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
                    if (strlen($atr["b-id_ac"])>0)
                        $sql_where .= " AND id_ac = '". $atr["b-id_ac"] . "'";
                    if (strlen($atr["b-id_correcion"])>0)
                        $sql_where .= " AND id_correcion = ". $atr["b-id_correcion"] . "";
                    if (strlen($_SESSION[id_ac])>0)
                        $sql_where .= " AND id_ac = ". $_SESSION[id_ac] . "";
                    if (strlen($_SESSION[id_correccion])>0)
                        $sql_where .= " AND id_correcion = '". $_SESSION[id_correccion] . "'";

                                                            
                    $sql = "SELECT acco.id
                                ,tipo
                                ,accion
                                ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada_a
                                ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada_a
                                ,id_responsable                                                                
                                ,id_ac
                                ,id_correcion
                                ,CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno)) responsable
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
                                ,estado
                                ,id_validador
                                $sql_col_left
                            FROM mos_acciones_ac_co acco   
                            INNER JOIN mos_personal p ON p.cod_emp = id_responsable
                            $sql_left
                            WHERE 1 = 1 $sql_where";
                    
                    $sql .= " order by $atr[corder] $atr[sorder] ";                    
                    //echo $sql;
                    $this->operacion($sql, $atr);
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
                    
                    $sql_where = '';
                    if (strlen($atr[valor])>0)
                        $sql_where .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-tipo"])>0)
                        $sql_where .= " AND tipo = '". $atr["b-tipo"] . "'";
                    if (strlen($atr["b-accion"])>0)
                        $sql_where .= " AND upper(accion) like '%" . strtoupper($atr["b-accion"]) . "%'";
                    if (strlen($atr['b-fecha_acordada-desde'])>0)                        
                    {
                        $atr['b-fecha_acordada-desde'] = formatear_fecha($atr['b-fecha_acordada-desde']);                        
                        $sql_where .= " AND fecha_acordada >= '" . ($atr['b-fecha_acordada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_acordada-hasta'])>0)                        
                    {
                        $atr['b-fecha_acordada-hasta'] = formatear_fecha($atr['b-fecha_acordada-hasta']);                        
                        $sql_where .= " AND fecha_acordada <= '" . ($atr['b-fecha_acordada-hasta']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_realizada-desde'])>0)                        
                    {
                        $atr['b-fecha_realizada-desde'] = formatear_fecha($atr['b-fecha_realizada-desde']);                        
                        $sql_where .= " AND fecha_realizada >= '" . ($atr['b-fecha_realizada-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_realizada-hasta'])>0)                        
                    {
                        $atr['b-fecha_realizada-hasta'] = formatear_fecha($atr['b-fecha_realizada-hasta']);                        
                        $sql_where .= " AND fecha_realizada <= '" . ($atr['b-fecha_realizada-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-id_responsable"])>0)
                        $sql_where .= " AND id_responsable = '". $atr["b-id_responsable"] . "'";
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
                    if (strlen($atr["b-id_ac"])>0)
                        $sql_where .= " AND id_ac = '". $atr["b-id_ac"] . "'";
                    if (strlen($atr["b-id_correcion"])>0)
                        $sql_where .= " AND id_correcion = '". $atr["b-id_correcion"] . "'";
//                    if (strlen($_SESSION[id_ac])>0)
//                        $sql_where .= " AND id_ac = '". $_SESSION[id_ac] . "'";
//                    if (strlen($_SESSION[id_correccion])>0)
//                        $sql_where .= " AND id_correcion = '". $_SESSION[id_correccion] . "'";

                    
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_acciones_ac_co 
                         WHERE 1 = 1 $sql_where";
                    

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT acco.id
                                ,acco.estado
                                ,ac.id id_ac
                                ,DATE_FORMAT(fecha_generacion, '%d/%m/%Y') fecha_generacion_a
                                ,ac.descripcion descripcion
                                ,ta.descripcion tipo                                
                                ,accion
                                ,DATE_FORMAT(acco.fecha_acordada, '%d/%m/%Y') fecha_acordada_a
                                ,DATE_FORMAT(acco.fecha_realizada, '%d/%m/%Y') fecha_realizada_a
                                ,CONCAT(initcap(SUBSTR(per.nombres,1,IF(LOCATE(' ' ,per.nombres,1)=0,LENGTH(per.nombres),LOCATE(' ' ,per.nombres,1)-1))),' ',initcap(per.apellido_paterno)) as responsable
                                
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
                                -- ,(select count(id) from mos_acciones_evidencia where id_accion=acco.id) as cantidad 
                                -- ,id_ac
                                ,id_correcion
                                ,(SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE mos_nombres_campos.nombre_campo = acco.estatus_wf AND mos_nombres_campos.modulo = 16 AND id_idioma = $_SESSION[CookIdIdioma]) as estatus_wf_a
                                ,id_responsable
                                ,id_validador
                                ,estatus_wf
                                     $sql_col_left
                            FROM mos_acciones_ac_co acco 
                            INNER JOIN mos_acciones_correctivas ac ON ac.id = acco.id_ac
                            INNER JOIN mos_tipo_ac ta ON ta.id = tipo
                            INNER JOIN mos_personal per on per.cod_emp = acco.id_responsable
                            $sql_left
                            WHERE 1 = 1 $sql_where";
                    
                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
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
             
             public function colum_admin_arbol($tupla)
            {       
                 $html = "<a tok=\"". $tupla[id] . "\" class=\"ver-reporte\">
                                                <i style='cursor:pointer'  class=\"icon icon-view-document\" title=\"Ver Trazabilidad Acción\"></i>
                                            </a>";
                //if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                 //
                if ((($tupla[estatus_wf] == 'en_ejecucion')  || ($tupla[estatus_wf] == 'rechazado')) && ($tupla[id_responsable] == $_SESSION['CookCodEmp']))
                {                    
                    //print_r($tupla);
                    $html .= "<a href=\"#\" onclick=\"javascript:editarAccionesAC('". $tupla[id] . "');\"  title=\"Gestionar Trazabilidad Acción\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                }
                /*
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    $html .= "<a href=\"#\" onclick=\"javascript:eliminarAccionesCorrectivas('". $tupla[id] . "');\" title=\"Eliminar Familias\">
                            <i class=\"icon icon-remove\"></i>

                        </a>"; 
                }*/
                if (($tupla[id_validador] == $_SESSION['CookCodEmp']) && ($tupla[estatus_wf] == 'cerrada_verificar'))
                    $html .= '<a href="#" onclick="javascript:WFAccionesAC(\''. $tupla[id] . '\');" title="Ver Flujo de Trabajo '.$tupla[nombre_doc].'" >                        
                            <i class="icon icon-user-document"></i>
                    </a>'; 
                
                return $html;
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
                $this->cargar_nombres_columnas_acciones();

                $grid->SetConfiguracionMSKS("tblAccionesAC", "");
                $config_col=array(
                    array( "width"=>"2%","ValorEtiqueta"=>"<div style='width:60px'>&nbsp;</div>"),
                    array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estado_seguimiento], ENT_QUOTES, "UTF-8")),
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas_ac[id], "id_ac", $parametros,30)), 
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas_ac[fecha_generacion], "ac.fecha_generacion", $parametros,80,40)), 
                    array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas_ac[descripcion], "ac.descripcion", $parametros)), 
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[tipo], "tipo", $parametros,"link_titulos")),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[accion], "accion", $parametros,"link_titulos")),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[fecha_acordada], "acco.fecha_acordada", $parametros,"link_titulos")),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[fecha_realizada], "acco.fecha_realizada", $parametros,"link_titulos")),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[id_responsable], "id_responsable", $parametros,"link_titulos")),
               
               array( "width"=>"10%","ValorEtiqueta"=>"dias"),
               array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[trazabilidad], ENT_QUOTES, "UTF-8")),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_ac], "id_ac", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estatus_wf], "estatus_wf", $parametros)),
                    //
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estatus_wf], "estatus_wf", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estatus_wf], "estatus_wf", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estatus_wf], "estatus_wf", $parametros)),
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
                    
                    $columna_funcion = 9;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verAccionesAC','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesAC'>"));
                */
                if (isset($parametros[reporte_ac])){
                    $config=array(array("width"=>"1%", "ValorEtiqueta"=>"&nbsp;"));
                }
                else{
                    
                /*
                    if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                        array_push($func,array('nombre'=> 'editarAccionesAC','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar AccionesAC'></i>"));
                    if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                        array_push($func,array('nombre'=> 'eliminarAccionesAC','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar AccionesAC'></i>"));
*/
                    $config=array();
                }
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                $grid->setParent($this);
                //echo $parametros['mostrar-col'];
                //print_r($array_columns);
                //echo count($config_col);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
                        case 10:
                        case 11:
                        //case 12:
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
                
                if (isset($parametros[reporte_ac])){
                    $grid->setFuncion("cantidad", "cantidad_evidencia_ver");
                }  else {
                    $grid->setFuncion("cantidad", "cantidad_evidencia");
                }
                
                $grid->setFuncion("estado", "semaforo_estado");
                $grid->setFuncion("id", "colum_admin_arbol");
                $grid->setFuncion("descripcion", "formatear_descripcion");
                $grid->setParent($this);
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
     
       public function formatear_descripcion($tupla,$key){
            if (strlen($tupla[descripcion])>200)
                return substr($tupla[descripcion], 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[id]. '-des" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[id]. '-des" value="'.$tupla[descripcion].'"/>
                    </a>';
            return $tupla[descripcion];
        }
        
        public function cantidad_evidencia($tupla, $key){
                //,cantidad_evidencia    
            //return $tupla[$key];
            $html = str_pad($tupla[$key],3,0,STR_PAD_LEFT) . ' <a onclick="adminEvidencias('.$tupla[id].')" href="#"><i style="cursor:pointer"  class="icon icon-view-document" title="Evidencias"></i> </a>';
            return $html;
        }
        
        public function cantidad_evidencia_ver($tupla, $key){
                //,cantidad_evidencia    
            //return $tupla[$key];
            $html = str_pad($tupla[$key],3,0,STR_PAD_LEFT) . ' <a onclick="EvidenciasVerReporte('.$tupla[id].')" href="#"><i style="cursor:pointer"  class="icon icon-view-document" title="Evidencias"></i> </a>';
            return $html;
        }
        
        public function semaforo_estado($tupla, $key){
                //,cantidad_evidencia    
            switch ($tupla[$key]) {
                    case 'Realizado':
                    case 4:
                        $html = '<img src="diseno/images/realizo.png" title="Realizado"/>';
                        break;
                    case 'Realizado con atraso':
                    case 3:
                        $html = '<img src="diseno/images/SemPlazoAtrasado.png" title="Realizado con atraso"/>';
                        break;
                    case 'Plazo vencido':
                    case 1:
                        $html = '<img src="diseno/images/atrasado.png" title="Plazo vencido"/>';
                        break;
                    case 'En el plazo':
                    case 2:
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
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
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
                if ($parametros['corder'] == null) $parametros['corder']="acco.fecha_acordada";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-2-3-4-6-7-8-9-12"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 14;
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
                //$contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['PERMISO_INGRESAR'] = 'display:none;';
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
                                                                    , 'descripcion');
                $contenido[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres');
                
                if (isset($parametros[reporte_ac]))
                    $contenido[DISPLAY_AM] = 'display:none;';
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';

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
                /*$objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","acciones_ac");*/
                $objResponse->addIncludeScript(PATH_TO_JS . 'acciones_ac/acciones_ac.js');
                /*$objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Acciones ID: " . (isset($_SESSION[id_ac]) ? $_SESSION[id_ac] : $_SESSION[id_correccion]) . "');");
                $objResponse->addScript("$('#myModal-Ventana').on('hidden.bs.modal', function () {                                                 
                                                 $('body').css({'padding-right':'0px'});
                                            })");
                
                //$objResponse->addScript("$('#hv-fecha').datetimepicker();");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show'); ");*/
                $objResponse->addScript("$('#hv-fecha_acordada').datetimepicker();");
                $objResponse->addScript("$('#hv-fecha_realizada').datetimepicker();");
                $objResponse->addScript('$( "#hv-id_responsable" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); ');
                /*JS init_tabla*/
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                        event.preventDefault();
                                        var id = $(this).attr('tok');
                                        $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                        $('#myModal-Ventana-Titulo').html('');
                                        $('#myModal-Ventana').modal('show');
                                    });
                                    PanelOperator.initPanels('');
                                        ScrollBar.initScroll();
/*
                                    $('.ver-reporte').on('click', function (event) {
                                        event.preventDefault();
                                        var id = $(this).attr('tok');
                                        verAccionesCorrectivas(id);*/
                                        /*window.open('pages/acciones_correctivas/reporte_ac_pdf.php?id='+id,'_blank');*/
                                    /*});*/");
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
                $objResponse->addScript("$('#fecha_acordada').datetimepicker();");
                $objResponse->addScript("$('#fecha_realizada').datetimepicker();");
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
                $contenido_1['TIPO'] = $val["tipo_desc"];
                /*
                $objResponse->addScript("$('#hv-tipo').val('$val[tipo]');");
                $objResponse->addScript("$('#hv-accion').html('$val[accion]');");
                $objResponse->addScript("$('#hv-fecha_acordada').val('$val[fecha_acordada]');");
                $objResponse->addScript("$('#hv-fecha_realizada').val('$val[fecha_realizada]');");
                $objResponse->addScript('$("#hv-id_responsable").select2("val", "'.$val["id_responsable"].'")'); */
                if($_SESSION[SuperUser]=='S'){
                    $sql = "SELECT cod_emp, 
                            CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                FROM mos_personal p WHERE interno = 1 AND workflow = 'S'
                                ORDER BY nombres";
                }
                else
                {
                    $sql = "SELECT cod_emp, 
                            CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                FROM mos_personal p WHERE interno = 1 AND workflow = 'S' AND p.cod_emp = $_SESSION[CookCodEmp]
                                ORDER BY nombres";
                }
                $ids = array('', 'Avance', 'Cierre'); 
                $desc = array('-- Seleccione --', 'Avance', 'Cierre');
                $contenido_1['TIPOS'] = $ut_tool->OptionsComboArrayMultiple($ids, $desc);
                $contenido_1[RESPONSABLE_ACCIONES] .= $ut_tool->OptionsCombo($sql
                                                                    , 'cod_emp'
                                                                    , 'nombres', strlen($_SESSION[CookCodEmp])>0?$_SESSION[CookCodEmp]:null);
                $contenido_1['ACCION'] = ($val["accion"]);
                $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
                $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
                $contenido_1['ID_RESPONSABLE'] = $val["responsable"];
                $contenido_1['ID_AC'] = $val["id_ac"];
                $contenido_1['ID_CORRECION'] = $val["id_correcion"];
                $contenido_1['ESTATUS_WF'] = $val["estatus_wf"];
                $contenido_1['ESTADO'] = $this->semaforo_estado($val,'estado');

                /*LISTADO DE TRAZABILIDAD*/
                import('clases.acciones_trazabildiad.AccionesTrazavilidad');
                $ac = new AccionesTrazavilidad();
                $parametros['b-id_accion'] = $val[id];
                $parametros[corder] = 'fecha_evi';
                $parametros[sorder] = '';
                //$parametros[corder] = 'orden';
                $ac->listarAccionesTrazavilidad($parametros,1,10000);
                $data=$ac->dbl->data;
                //print_r($data);
                $item = "";
                //$js = "";
                $i = 0;
                $contenido_1['TOK_NEW'] = time();       
                /* EVIDENCIAS ADJUNTADAS*/
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();       
                
                //$ids = array('7','8','9','1','2','3','5','6','10');
                //$desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo');
                foreach ($data as $value) {                          
                    $i++;                    
                    $item = $item. '<tr id="tr-esp-' . $i . '">';                      
                    

                    {
                        $adjuntos->ingresar_archivos_adjuntos_temp('mos_acciones_evidencia', 'fk_id_trazabilidad',$value["id"],$contenido_1['TOK_NEW']*1-$i);
                        
                                                          //paperclip          
                        $item = $item. '<td align="center">'.
                                            ' <a href="' . $i . '"  title="Eliminar " id="eliminar_esp_' . $i . '"> ' . 
                                            //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' . 
                                             '<i class="icon icon-remove" style="width: 18px;"></i>'.
                                             '</a>' .
//                                             '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
//                                              <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'.
                        '<i class="bajar glyphicon glyphicon-paperclip cursor-pointer" id="ico_cmb_din_'. $i . '" tok="'. $i .'" title="Administrar Anexos"></i>'.
                                              
                                              '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id].'" type="hidden" >'.                                              
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.($value[orden] == '' ? $i : $value[orden]).'" type="hidden" >'.
                                       '  </td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '  <select id="tipo_' .$i. '" name="tipo_'.$i. '" class="form-control">' .
                                                    $ut_tool->OptionsComboArrayMultiple($ids, $desc, array($value[tipo])).

                                                '</select>' .
                                        '</td>';
//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';
                         $item = $item.  '<td>' .
                                            ' <textarea id="accion_'. $i . '" name="accion_'. $i . '" class="form-control" data-validation="required">'. $value[observacion] .'</textarea>'.
                                         '</td>';
                         /*SI ES SUPER USUARIO PUEDE EDITAR CUALQUIERA*/
                         if($_SESSION[SuperUser]=='S'){
                             $sql= "SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres_a
                                                                            FROM mos_personal p 
                                                                            WHERE (interno = 1 AND workflow = 'S' ) OR cod_emp = $value[id_persona]
                                                                            ORDER BY nombres, apellido_paterno";
                         }
                         else// if ($this->restricciones->per_ == 'S') 
                         {
                            //$_SESSION[CookCodEmp]
                             $sql= "SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres_a
                                                                            FROM mos_personal p 
                                                                            WHERE cod_emp = $value[id_persona]
                                                                            ORDER BY nombres, apellido_paterno";
                         }
                         
                         
                         $item = $item . '<td class="td-table-data">'.
                                            '  <select id="responsable_acc_'. $i .  '" name="responsable_acc_'. $i .  '" class="form-control" data-validation="required" data-live-search="true">'.
                                            '<option value="">-- Seleccione --</option>' . 
                                                //$('#option_responsables').val() .
                                                $ut_tool->OptionsCombo($sql
                                                                    , 'cod_emp'
                                                                    , 'nombres_a', $value[id_persona]) . 
                                            '</select>' .
                                       '</td>';
                         $item = $item . '<td class="td-table-data"><div class="col-sm-24" style="padding-left: 0px;padding-right: 0px;">'.
                                            '<input id="fecha_acordada_'. $i .  '" data-date-format="DD/MM/YYYY HH:mm"   class="form-control" type="text" data-validation="required" value="'.$value[fecha_evi_a].'" name="fecha_acordada_'. $i .  '" >'.
                                       '</div></td>';
                        
                        
                        $item = $item. '</tr>' ;                    
                        $js .= '$("#eliminar_esp_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("href");  
                                    $("#id_unico_del").val($("#id_unico_del").val() + $("#id_unico_din_"+id).val() + ",");
                                    $("tr-esp-'. $i .'").remove();
                                    var parent = $(this).parents().parents().get(0);
                                        $(parent).remove();
                            });';
                        $js .= "$('#fecha_acordada_$i').datetimepicker();
                                $('#responsable_acc_$i').selectpicker({
                                                                    style: 'btn-combo'
                                });";
                        
                        $js .= '$("#ico_cmb_din_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("tok");            
                                    array = new XArray();
                                    array.setObjeto("ArchivosAdjuntos","indexItemsFormulario");
                                    array.addParametro("i",id);
                                    array.addParametro("fk_id",$("#id_unico_din_"+id).val());
                                    array.addParametro("titulo",$("#nombre_din_"+id).val());
                                    array.addParametro("token", $("#tok_new_edit").val());
                                    array.addParametro("import","clases.utilidades.ArchivosAdjuntos");
                                    array.addParametro("tipo",1);
                                    xajax_Loading(array.getArray());
                                }); ';
                        
                    }
                }               
                //echo $item;
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;
                
                /*Historico Flujo de Trabajo*/
                $sql = "SELECT fecha_hora f1,DATE_FORMAT(fecha_hora, '%d/%m/%Y %H:%i')fecha_registro"
                        . ", (SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE mos_nombres_campos.nombre_campo = accion AND mos_nombres_campos.modulo = 16 AND id_idioma = $_SESSION[CookIdIdioma]) as accion"
                        . ", anterior"
                        . ",CONCAT(initcap(SUBSTR(user.nombres,1,IF(LOCATE(' ' ,user.nombres,1)=0,LENGTH(user.nombres),LOCATE(' ' ,user.nombres,1)-1))),' ',initcap(user.apellido_paterno)) usuario "
                        . "FROM mos_log wf inner join mos_usuario user "
                        . " on wf.realizo= user.id_usuario "
                        . " WHERE codigo_accion = 22 AND wf.id_registro = $parametros[id] order by f1 desc";
                //echo $sql;
                $historia = $this->dbl->query($sql, array());
                foreach ($historia as $value) {
                    $item_histo .="<tr>";
                    $item_histo .="<td>".$value[fecha_registro]."</td>";
                    $item_histo .="<td>".$value[accion].". $value[anterior]</td>";
                    $item_histo .="<td>".$value[usuario]."</td>";
                    $item_histo .="</tr>";
                }
                $contenido_1['ITEMS_HISTO'] = $item_histo;
                /*FIN HISTORICO*/
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';
                $template->setTemplate("formulario_h");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario_h");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesAC";
                $contenido['PAGINA_VOLVER'] = "listarAccionesAC.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }  
                
                $contenido['DESC_OPERACION'] = "Solo Guardar";
                $contenido[JS_PREVIO_GUARDAR] = "$('#notificar').val('');";
                $contenido['OTRO_BOTON_PRINCIPAL'] = '<button type="button" class="btn btn-primary" onClick="$(\'#notificar\').val(\'si\');validar(document);" id="btn-guardar-not">Guardar y Notificar</button>';


                $template->setVars($contenido);
                
                /*
                $objResponse->addScript("$('#id-hv').val('$parametros[id]');");
                $objResponse->addScript("$('#opc-hv').val('upd');");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('.nav-tabs a[href=\"#hv-red\"]').tab('show');");*/
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript($js);
//                $objResponse->addScript("$('#fecha_acordada').datetimepicker();");
//                $objResponse->addScript("$('#fecha_realizada').datetimepicker();");
                return $objResponse;
            }
     
            public function WFAccionesAC($parametros)
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
                $contenido_1['TIPO'] = $val["tipo_desc"];
                /*
                $objResponse->addScript("$('#hv-tipo').val('$val[tipo]');");
                $objResponse->addScript("$('#hv-accion').html('$val[accion]');");
                $objResponse->addScript("$('#hv-fecha_acordada').val('$val[fecha_acordada]');");
                $objResponse->addScript("$('#hv-fecha_realizada').val('$val[fecha_realizada]');");
                $objResponse->addScript('$("#hv-id_responsable").select2("val", "'.$val["id_responsable"].'")'); */
                if($_SESSION[SuperUser]=='S'){
                    $sql = "SELECT cod_emp, 
                            CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                FROM mos_personal p WHERE interno = 1 AND workflow = 'S'
                                ORDER BY nombres";
                }
                else
                {
                    $sql = "SELECT cod_emp, 
                            CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                FROM mos_personal p WHERE interno = 1 AND workflow = 'S' AND p.cod_emp = $_SESSION[CookCodEmp]
                                ORDER BY nombres";
                }
                $ids = array('', 'Avance', 'Cierre'); 
                $desc = array('-- Seleccione --', 'Avance', 'Cierre');
                $contenido_1['TIPOS'] = $ut_tool->OptionsComboArrayMultiple($ids, $desc);
                $contenido_1[RESPONSABLE_ACCIONES] .= $ut_tool->OptionsCombo($sql
                                                                    , 'cod_emp'
                                                                    , 'nombres', strlen($_SESSION[CookCodEmp])>0?$_SESSION[CookCodEmp]:null);
                $contenido_1['ACCION'] = ($val["accion"]);
                $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
                $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
                $contenido_1['ID_RESPONSABLE'] = $val["responsable"];
                $contenido_1['ID_AC'] = $val["id_ac"];
                $contenido_1['ID_CORRECION'] = $val["id_correcion"];
                $contenido_1['ESTATUS_WF'] = $val["estatus_wf"];
                $contenido_1['ESTADO'] = $this->semaforo_estado($val,'estado');

                /*LISTADO DE TRAZABILIDAD*/
                import('clases.acciones_trazabildiad.AccionesTrazavilidad');
                $ac = new AccionesTrazavilidad();
                $parametros['b-id_accion'] = $val[id];
                $parametros[corder] = 'fecha_evi';
                $parametros[sorder] = '';
                //$parametros[corder] = 'orden';
                $ac->listarAccionesTrazavilidad($parametros,1,10000);
                $data=$ac->dbl->data;
                //print_r($data);
                $item = "";
                //$js = "";
                $i = 0;
                $contenido_1['TOK_NEW'] = time();       
                /* EVIDENCIAS ADJUNTADAS*/
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();       
                
                //$ids = array('7','8','9','1','2','3','5','6','10');
                //$desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo');
                foreach ($data as $value) {                          
                    $i++;                    
                    $item = $item. '<tr id="tr-esp-' . $i . '">';                      
                    

                    {
                        //FOTOS ANEXOS
                        $anexos = $adjuntos->visualizar_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_trazabilidad',$value["id"],24,'div',$i*100);
                                //print_r ($anexos);
                        //$js .= $anexos[js];
                        //$adjuntos->ingresar_archivos_adjuntos_temp('mos_acciones_evidencia', 'fk_id_trazabilidad',$value["id"],$contenido_1['TOK_NEW']*1-$i);
                        
                                                          //paperclip          
                        /*$item = $item. '<td align="center">'.
                                            
//                                             '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
//                                              <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'.
                        '<i class="bajar glyphicon glyphicon-paperclip cursor-pointer" id="ico_cmb_din_'. $i . '" tok="'. $i .'" title="Administrar Anexos"></i>'.
                                              
                                              '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id].'" type="hidden" >'.                                              
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.($value[orden] == '' ? $i : $value[orden]).'" type="hidden" >'.
                                       '  </td>';*/
                         $item = $item. '<td class="td-table-data">'.$value[tipo].
                                             
                                        '</td>';
//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';
                         $item = $item.  '<td>' . $value[observacion] .
                                            $anexos[html] .
                                         '</td>';
                         
                         
                         $item = $item . '<td class="td-table-data">'.$value[persona]
                                             .
                                       '</td>';
                         $item = $item . '<td class="td-table-data">'.$value[fecha_evi_a].'</td>';
                        
                        
                        $item = $item. '</tr>' ;                    
                        
                        
                        
                    }
                }               
                //echo $item;
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;
                $contenido_1['ID'] = $val["id"];
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_ac/';
                $template->setTemplate("formulario_h_wf");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario_h");

                $contenido['TITULO_FORMULARIO'] = "Flujo de Trabajo&nbsp;";
                $contenido['TITULO_FORMULARIO'] .= '<br>
                    <button  {MOSTRARCAMBIAR} type="button" class="btn btn-primary" onclick="CambiarEstadoWF(\'cerrada_verificada\','.$val[id].');"><i class="glyphicon glyphicon-ok"></i> &nbsp;Aprobar</button>
                    <button {MOSTRARRECHAZAR} type="button" class="btn btn-default" onclick="$(\'#myModal-observacion-rechazo\').modal(\'show\');"><i class="glyphicon glyphicon-remove"></i> &nbsp;Rechazar</button>';

                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesAC";
                $contenido['PAGINA_VOLVER'] = "listarAccionesAC.php";
                $contenido['DESC_OPERACION'] = '<i class="glyphicon glyphicon-ok"></i> &nbsp;Aprobar';
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];
                                
                $contenido['OTRO_BOTON_PRINCIPAL'] = '<button type="button" class="btn btn-default" onClick="$(\'#myModal-observacion-rechazo\').modal(\'show\');" id="btn-guardar-not"><i class="glyphicon glyphicon-remove"></i> Rechazar</button>';


                $template->setVars($contenido);
                
                /*
                $objResponse->addScript("$('#id-hv').val('$parametros[id]');");
                $objResponse->addScript("$('#opc-hv').val('upd');");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('.nav-tabs a[href=\"#hv-red\"]').tab('show');");*/
                $objResponse->addAssign('contenido-form',"innerHTML",  str_replace('validar(document);', 'CambiarEstadoWF(\'cerrada_verificada\','.$val[id].');', $template->show()) );
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript($js);
//                $objResponse->addScript("$('#fecha_acordada').datetimepicker();");
//                $objResponse->addScript("$('#fecha_realizada').datetimepicker();");
                return $objResponse;
            }
            
            public function actualizar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                $parametros['id_usuario']= $_SESSION['USERID'];
                $bandera = 0;
                if ($parametros[notificar] == 'si'){
                    $bandera = 0;
                    for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                        //GUARDAMOS LAS ACCIONES VALIDAS
                        //if (!isset($parametros["id_unico_din_$i"]))
                        { 
                            if ((isset($parametros["tipo_$i"])) && ($parametros["tipo_$i"] == 'Cierre')){
                                $bandera++;
                            }
                        }
                    }
                    if ($bandera < 1){
                        $objResponse->addScriptCall('VerMensaje','error',"Debes ingresar al menos una trazabilidad tipo Cierre");
                        return $objResponse;
                    }
                    if ($bandera > 1){
                        $objResponse->addScriptCall('VerMensaje','error',"Existe mas de una trazabilidad tipo Cierre");
                        return $objResponse;
                    }
                }
                
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

                    /*GUARDAMOS LA TRAZABILIDAD DE UNA ACCION*/
                    $params = array();
                    $params[id_ac] = $respuesta;
                    //IMPORTAMOS CLASE de ACCIONES
                    import('clases.acciones_trazabildiad.AccionesTrazavilidad');
                    $trazabilidad = new AccionesTrazavilidad();                    
                    if (strlen($parametros[id_unico_del])>0){
                        $parametros[id_unico_del] = substr($parametros[id_unico_del], 0, strlen($parametros[id_unico_del]) - 1);
                        $sql = "DELETE FROM mos_acciones_trazabilidad WHERE id IN ($parametros[id_unico_del]) and id_accion = $parametros[id]"
                            . " -- AND NOT id IN (SELECT id_accion FROM mos_acciones_trazabilidad id_accion IN ($parametros[id_unico_del])) ";                               
                        $this->dbl->insert_update($sql);
                    }     
                    //print_r($parametros);
                    //echo $parametros[num_items_esp];
                    /* EVIDENCIAS ADJUNTADAS*/
                    if(!class_exists('ArchivosAdjuntos')){
                        import("clases.utilidades.ArchivosAdjuntos");
                    }
                    $adjuntos = new ArchivosAdjuntos();
                    
                    /*FIN EVIDENNCIAS*/
                    
                    for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                        //GUARDAMOS LAS ACCIONES VALIDAS
                        if (!isset($parametros["id_unico_din_$i"])){ 
                            if (isset($parametros["tipo_$i"])){
                                $params[tipo] = $parametros["tipo_$i"];
                                $params[observacion] = $parametros["accion_$i"];                                        
                                $params[id_persona] = $parametros["responsable_acc_$i"];                                
                                $params[fecha_evi] = formatear_fecha_hora($parametros["fecha_acordada_$i"]);
                                //$params[orden] = $parametros["orden_din_$i"];     
                                $params[id_accion] = $parametros[id];
                                $id_traza = $trazabilidad->ingresarAccionesTrazavilidad($params);   
                                //SE GUARDAN LAS EVIDENCIAS
                                $atr[tabla] = 'mos_acciones_evidencia';
                                $atr[clave_foranea] = 'fk_id_trazabilidad';
                                $atr[valor_clave_foranea] = $id_traza;
                                $atr[tok_new_edit] = $parametros[tok_new_edit]*1+$i;
                                $adjuntos->guardar($atr);
                            }
                        }else{
                            if (isset($parametros["tipo_$i"])){
                                $params[tipo] = $parametros["tipo_$i"];
                                $params[observacion] = $parametros["accion_$i"];                                        
                                $params[id_persona] = $parametros["responsable_acc_$i"];                                
                                $params[fecha_evi] = formatear_fecha_hora($parametros["fecha_acordada_$i"]);
                                //$params[orden] = $parametros["orden_din_$i"];     
                                $params[id_accion] = $parametros[id];   
                                $params[id] = $parametros["id_unico_din_$i"]; 
                                $trazabilidad->modificarAccionesTrazavilidad($params);
                                //SE GUARDAN LAS EVIDENCIAS
                                $atr[tabla] = 'mos_acciones_evidencia';
                                $atr[clave_foranea] = 'fk_id_trazabilidad';
                                $atr[valor_clave_foranea] = $parametros["id_unico_din_$i"];
                                $atr[tok_new_edit] = $parametros[tok_new_edit]*1-$i;
                                $adjuntos->guardar($atr);
                            }
                        }
                    }
                    
                    /*SI GUARDA Y NOTIFICA SE ACTIVA EL WF PARA LA VERIFICACION*/
                    if ($bandera == 1){
                        $parametros= $this->dbl->corregir_parametros($parametros);
                        $sql = "update mos_acciones_ac_co set estatus_wf = 'cerrada_verificar' where id = $parametros[id]";
                        $this->dbl->insert_update($sql);
                    }
                    //$respuesta = $this->modificarAccionesAC($parametros);

                    //if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) 
                    {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    //else
                    //    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar-hv' ).html('Guardar');
                                        $( '#btn-guardar-hv' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
            public function verWFemail($id){
                $atr=array();
                $sql = "select 
                                mos_personal.email
                                ,CONCAT(initcap(SUBSTR(mos_personal.nombres,1,IF(LOCATE(' ' ,mos_personal.nombres,1)=0,LENGTH(mos_personal.nombres),LOCATE(' ' ,mos_personal.nombres,1)-1))),' ',initcap(mos_personal.apellido_paterno)) nombres 
                                ,u.recibe_notificaciones
                                ,pv.email email_validador
                                ,CONCAT(initcap(SUBSTR(pv.nombres,1,IF(LOCATE(' ' ,pv.nombres,1)=0,LENGTH(pv.nombres),LOCATE(' ' ,pv.nombres,1)-1))),' ',initcap(pv.apellido_paterno)) nombres_validador 
                                ,uv.recibe_notificaciones
                                ,ac.accion
                                ,ac.estatus_wf
                            from mos_acciones_ac_co ac
                            inner JOIN mos_personal on mos_personal.cod_emp = ac.id_responsable
                            LEFT JOIN mos_usuario u on u.email = mos_personal.email
                            left JOIN mos_personal pv on pv.cod_emp = ac.id_validador
                            LEFT JOIN mos_usuario uv on uv.email = pv.email
                            where ac.id = $id
                        ;"; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            
            
            public function cambiar_estado($parametros)
            {   //print_r($parametros);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];
                $parametros[fecha_estado_wf] = formatear_fecha_hora($parametros[fecha_estado_wf]);
                $respuesta = $this->cambiarestadowf($parametros);
                //$val = $this->verDocumentos($parametros[id]);
                $objResponse = new xajaxResponse();
               // echo $respuesta;
               // print_r($parametros);
                if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $cc=array();
                        $from=array();
                        $ut_tool = new ut_Tool();
                        $correowf = $this->verWFemail($parametros[id]);
                        switch ($correowf[estatus_wf]) {
                            case 'cerrada_verificada':
                                

                                break;

                            default:
                                break;
                        }
                        
                        //echo $correowf[email];
                        /*$this->cargar_nombres_columnas();
                        $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                        //echo $correowf[email];
                            
                            $cuerpo = 'Sr(a). ' .$correowf[nombres] . '<br><br> Usted tiene una notificación de un documento "'.$etapa.'"<br><br>';
                            $asunto = 'Documento '. $etapa . ': ' . $val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                           // $correowf[email] = 'azambrano75@gmail.com';
                            $nombres = $correowf[nombres];
                            //echo $cuerpo;
                           // print_r($correowf);
                        if($correowf[etapa_workflow]=='estado_pendiente_aprobacion' && $correowf[estado_workflow]=='OK') {                            
                            if($correowf[recibe_notificaciones]=='S'){
                                $from = array(array('correo' => $correowf[email], 'nombres'=>$nombres));                                
                            }                            
                            if($correowf[recibe_notificaciones_responsable]=='S'){
                                $cc = array(array('correo' => $correowf[email_responsable], 'nombres'=>$correowf[nombre_responsable]));
                               // $cc = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_responsable]));
                            }
                            if(sizeof($from)>0 || sizeof($cc)>0){
                                   $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_responsable].' &rarr;';
                                   $anexo_adicional .='<strong>'. $this->nombres_columnas['reviso'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_revisa].'.<br>';
                                   $cuerpo .= $anexo_adicional.'<br>'.APPLICATION_ROOT;
                                   $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $asunto, $cuerpo, array(),$cc); 
                            }
                        }
                        
                        else if($correowf[estado_workflow]=='RECHAZADO'){
                            $cuerpo = 'Rechazado por:&nbsp;'.$correowf[nombres];
                            $cuerpo .= '<br><br>Motivo del Rechazo:<br><span style="color:red">'.$correowf[observacion_rechazo].'</span>';                            
                            $asunto = 'Documento RECHAZADO: ' . $val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                            //echo $_SESSION[CookEmail].' '.$correowf[email_aprueba].' '.$correowf[recibe_notificaciones_revisa].' '.$correowf[recibe_notificaciones_responsable];
                            if($_SESSION[CookEmail]==$correowf[email_aprueba]){
                                if($correowf[recibe_notificaciones_revisa]=='S'){
                                    $cc = array(array('correo' => $correowf[email_revisa], 'nombres'=>$correowf[nombre_revisa]));
                                    //$cc = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_revisa]));
                                }
                                if($correowf[recibe_notificaciones_responsable]=='S'){
                                    $from = array(array('correo' => $correowf[email_responsable], 'nombres'=>$correowf[nombre_responsable]));
                                    //$from = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_responsable]));
                                }
                                if(sizeof($from)>0 || sizeof($cc)>0){
                                    $cuerpo .= '<br><br>'.APPLICATION_ROOT;
                                    //echo $cuerpo;
                                    //$correowf[email] = 'azambrano75@gmail.com';
                                    $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $asunto, $cuerpo, array(),$cc);                                
                                }
                            }            
                        }
                        

                        //SE CARGA LA NOTIFICACION
                            //echo 'etapa:'.$correowf[etapa_workflow];
                            //echo 'edo:'.$correowf[estado_workflow];
                            import('clases.notificaciones.Notificaciones');
                            $noti = new Notificaciones();
                            $atr[cuerpo] .=$val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT).'<br>';
                            if($correowf[etapa_workflow]=='estado_pendiente_revision' && $correowf[estado_workflow]=='OK') {
                                //$atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su revision<br>';
                                $atr[asunto]='Tiene un documento '.$etapa.'';
                            }
                            else 
                                if($correowf[etapa_workflow]=='estado_pendiente_aprobacion' && $correowf[estado_workflow]=='OK') {
                                    //$atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su aprobacion<br>';
                                    $atr[asunto]='Tiene un documento '.$etapa.'';
                                }
                                else
                                    if($correowf[estado_workflow]=='RECHAZADO') {
                                        //$atr[cuerpo] .='Tiene un documento Rechazado<br>';
                                        $atr[asunto]='Tiene un documento Rechazado';
                                    } else
                                    if($correowf[etapa_workflow]=='estado_aprobado') {
                                        //$atr[cuerpo] .='Tiene un documento Aprobado<br>';
                                        //ESTE BLOQUE APLICA SI EL DOCUMENTO TIENE LISTA DE DISTR ACTIVA
                                        //print_r($val);
                                        if($val[requiere_lista_distribucion]=='S'){
                                            //echo 'paso';
                                            $this->NotificacionListaDistribucion($val);
                                        }
                                        
                                        if(!class_exists('Template')){
                                            import("clases.interfaz.Template");
                                        }
                                        $contenido   = array();
                                        $contenido['ELABORADOR']=$correowf[nombre_responsable];
                                        if($val["version"]>1){
                                            $contenido['MENSAJE']='Se ha actualizado el documento';
                                            $contenido['DOCUMENTO']=$val[Codigo_doc].'-'.$val[nombre_doc].' a la versi&oacute;n '.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                                        }
                                        else {
                                            $contenido['MENSAJE']='Se ha publicado el documento';
                                            $contenido['DOCUMENTO']=$val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                                        }
                                        $asunto = 'Documento Publicado: ' . $val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                                        
                                        $template = new Template();
                                        $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                                        $template->setTemplate("cuerpo_notificacion");
                                        $template->setVars($contenido);
                                        $cuerpo = $template->show();

                                        //echo $cuerpo;
                                        if($correowf[recibe_notificaciones_revisa]=='S'){
                                            $cc = array(array('correo' => $correowf[email_revisa], 'nombres'=>$correowf[nombre_revisa]));
                                           // $cc = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_revisa]));
                                        }
                                        if($correowf[recibe_notificaciones_responsable]=='S'){
                                            $from = array(array('correo' => $correowf[email_responsable], 'nombres'=>$correowf[nombre_responsable]));
                                           // $from = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_responsable]));
                                        }
                                        if(sizeof($from)>0 || sizeof($cc)>0){
                                            $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                            $anexo_adicional .=$correowf[nombre_responsable].' &rarr;';
                                            $anexo_adicional .='<strong>'. $this->nombres_columnas['reviso'].'</strong>&nbsp;';
                                            $anexo_adicional .=$correowf[nombre_revisa].' &rarr;';
                                            $anexo_adicional .='<strong>'. $this->nombres_columnas['aprobo'].'</strong>&nbsp;';
                                            $anexo_adicional .=$correowf[nombre_aprueba].'.<br>';
                                            $cuerpo .= $anexo_adicional.'<br>'.APPLICATION_ROOT;
                                            $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $asunto, $cuerpo, array(),$cc);                                
                                        }
                                        $atr[asunto]='Tiene un documento Aprobado';
                                    }
                            
                            $atr[modulo]='DOCUMENTOS';
                            $atr[funcion] = "verWorkFlowPopup(".$val[IDDoc].");";
                            $atr[email]=$correowf[email];
                            $atr[id_entidad]=$val[IDDoc];
                            $mensaje=$noti->ingresarNotificaciones($atr);
                        //die;
                         * */
                         
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                $objResponse->addScript("$('#MustraCargando').hide();");
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