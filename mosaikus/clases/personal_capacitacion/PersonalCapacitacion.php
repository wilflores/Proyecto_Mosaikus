<?php
        function formatear_horas_capa($FilasCap){
            $Duracion=$FilasCap[hora]*60;
            $Duracion=$Duracion+$FilasCap[min];
            $Duracion=$Duracion/60;
            return number_format($Duracion,2,',','');
        }
        function formatear_hh($FilasCap){
            $Duracion=$FilasCap[hora]*60;
            $Duracion=$Duracion+$FilasCap[min];
            $Duracion=$Duracion/60*$FilasCap[cant_pe];
            
            return number_format($Duracion,2,',','');
        }
        function archivo($tupla)
        {
            if (strlen($tupla[nom_archivo])>0){
                //<img class=\"SinBorde\" title=\"Ver Documento Fuente $tupla[nom_archivo]\" src=\"diseno/images/pdf.png\">
                $html = "<a title=\"Ver $tupla[nom_archivo]\" target=\"_blank\" href=\"pages/personal_capacitacion/descargar_archivo.php?id=$tupla[cod_capacitacion]&token=" . md5($tupla[cod_capacitacion]) ."\">
                            
                            <i class=\"icon icon-view-document\"></i>
                        </a>";
                if($_SESSION[CookM] == 'S'){
                    //<img title="Eliminar '.$tupla[nom_archivo].'" src="diseno/images/ico_eliminar.png" style="cursor:pointer">
                    $html .= '<a onclick="javascript:eliminarArchivoCapacitacion(\''. $tupla[cod_capacitacion] . '\');">
                            
                            <i class="icon icon-remove" title="Eliminar '.$tupla[nom_archivo].'" style="cursor:pointer"></i>    
                        </a>'; 
                }
                return $html;
            }
            return "&nbsp;";
        }
        
        function cantidad_aprobados($tupla)
        {
            
            return "$tupla[cantidad_ap] de $tupla[cant_pe]";
        }

?>
<?php
 import("clases.interfaz.Pagina");        
        class PersonalCapacitacion extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        private $campos_activos;
            
            public function PersonalCapacitacion(){
                parent::__construct();
                $this->asigna_script('personal_capacitacion/personal_capacitacion.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = $this->campos_activos = array();                
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros(){
                $sql = "SELECT cod_parametro, espanol FROM mos_parametro WHERE cod_categoria = '10' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }

            private function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 5";
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
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 5";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }
     

             public function verPersonalCapacitacion($id){
                $atr=array();
                $sql = "SELECT cod_capacitacion
                            ,pc.cod_curso
                            ,cod_emp_relator
                            ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha
                            ,id_organizacion
                            ,nom_archivo
                            ,1 archivo
                            ,contenttype
                            ,observacion
                            ,DATE_FORMAT(fecha_termino, '%d/%m/%Y') fecha_termino
                            ,hora
                            ,min
                            ,hh
                            ,c.aplica_evaluacion
                         FROM mos_personal_capacitacion pc
                         INNER JOIN mos_cursos c ON c.cod_curso = pc.cod_curso
                         WHERE cod_capacitacion = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verPersonalCapacitacionArchivo($id){
                $atr=array();
                $sql = "SELECT                             
                            archivo
                            ,nom_archivo
                         FROM mos_personal_capacitacion 
                         WHERE cod_capacitacion = $id "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_capacitacion) total_registros
                         FROM mos_personal_capacitacion";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarPersonalCapacitacion($atr,$archivo){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[archivo] = $archivo;
                    $atr[cod_capacitacion] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_personal_capacitacion(cod_capacitacion,cod_curso,cod_emp_relator,fecha,id_organizacion,nom_archivo,archivo,contenttype,observacion,fecha_termino,hora,min,hh)
                            VALUES(
                                $atr[cod_capacitacion],$atr[cod_curso],$atr[cod_emp_relator],'$atr[fecha]',NULL,'$atr[nom_archivo]','$atr[archivo]','$atr[contenttype]','$atr[observacion]','$atr[fecha_termino]',$atr[hora],$atr[min],$atr[hh_min]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_personal_capacitacion ' . $atr[descripcion_ano], 'mos_personal_capacitacion');
                      */
                    $nuevo = "Cod Capacitacion: \'$atr[cod_capacitacion]\', Cod Curso: \'$atr[cod_curso]\', Cod Emp Relator: \'$atr[cod_emp_relator]\', Fecha: \'$atr[fecha]\', Id Organizacion: \'$atr[id_organizacion]\', Nom Archivo: \'$atr[nom_archivo]\', Contenttype: \'$atr[contenttype]\', Observacion: \'$atr[observacion]\', Fecha Termino: \'$atr[fecha_termino]\', Hora: \'$atr[hora]\', Min: \'$atr[min]\', Hh: \'$atr[hh_min]\', ";
                    $this->registraTransaccionLog(58,$nuevo,'', '');
                    return $atr[cod_capacitacion];
                    return "El mos_personal_capacitacion '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarPersonalCapacitacionDetalle($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                                        
                    $sql = "INSERT INTO mos_personal_capacitacion_detalle(cod_capacitacion,cod_emp,asistencia,aprobacion,nota_evaluacion,observacion)
                            VALUES(
                                $atr[cod_capacitacion],$atr[cod_emp],'$atr[asistencia]','$atr[aprobacion]','$atr[nota_evaluacion]','$atr[observacion]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_personal_capacitacion ' . $atr[descripcion_ano], 'mos_personal_capacitacion');
                      */
                    $nuevo = "Cod Capacitacion: \'$atr[cod_capacitacion]\', Cod emp: \'$atr[cod_emp]\', asistencia: \'$atr[asistencia]\', aprobacion: \'$atr[aprobacion]\', nota_evaluacion: \'$atr[nota_evaluacion]\', observacion: \'$atr[observacion]\'";
                    $this->registraTransaccionLog(56,$nuevo,'', '');
                    //return $atr[cod_emp];
                    return "El mos_personal_capacitacion '$atr[descripcion_ano]' ha sido ingresado con exito";
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
                //echo $sql;
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarPersonalCapacitacion($atr,$archivo){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[archivo] = $archivo;
                    if ($atr[id_registro] == '')
                        $atr[id_registro] = 'NULL';
                    if (strlen($atr[archivo])== 0){
                        $atr[archivo] = "archivo";
                        $atr[contenttype] = "contenttype";
                        $atr[nom_archivo] = "nom_archivo";                        
                    }
                    else
                    {
                        $atr[archivo] = "'$atr[archivo]'";
                        $atr[contenttype_aux] = $atr[contenttype];
                        $atr[contenttype] = "'$atr[contenttype]'";                        
                        $atr[nom_archivo_aux] = "$atr[nom_archivo]";  
                        $atr[nom_archivo] = "'$atr[nom_archivo]'";   
                        
                    }
                    $sql = "UPDATE mos_personal_capacitacion SET                            
                                    cod_curso = $atr[cod_curso],cod_emp_relator = $atr[cod_emp_relator],fecha = '$atr[fecha]',nom_archivo = $atr[nom_archivo],archivo = $atr[archivo],contenttype = $atr[contenttype],observacion = '$atr[observacion]',fecha_termino = '$atr[fecha_termino]',hora = $atr[hora],min = $atr[min],hh = $atr[hh_min]
                            WHERE  cod_capacitacion = $atr[id]";      
                    //echo $sql;
                    $val = $this->verPersonalCapacitacion($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Capacitacion: \'$atr[cod_capacitacion]\', Cod Curso: \'$atr[cod_curso]\', Cod Emp Relator: \'$atr[cod_emp_relator]\', Fecha: \'$atr[fecha]\', Nom Archivo: \'$atr[nom_archivo_aux]\', Contenttype: \'$atr[contenttype_aux]\', Observacion: \'$atr[observacion]\', Fecha Termino: \'$atr[fecha_termino]\', Hora: \'$atr[hora]\', Min: \'$atr[min]\', Hh: \'$atr[hh]\', ";
                    $anterior = "Cod Capacitacion: \'$val[cod_capacitacion]\', Cod Curso: \'$val[cod_curso]\', Cod Emp Relator: \'$val[cod_emp_relator]\', Fecha: \'$val[fecha]\', Nom Archivo: $val[nom_archivo], Contenttype: $val[contenttype], Observacion: \'$val[observacion]\', Fecha Termino: \'$val[fecha_termino]\', Hora: \'$val[hora]\', Min: \'$val[min]\', Hh: \'$val[hh]\', ";
                    $this->registraTransaccionLog(59,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el PersonalCapacitacion ' . $atr[descripcion_ano], 'mos_personal_capacitacion');
                    */
                    return "La capacitaci&oacute;n '$atr[nombre_curso]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function eliminarArchivoCapacitacion($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    
                    $sql = "UPDATE mos_personal_capacitacion SET                            
                                    nom_archivo = NULL,archivo = NULL,contenttype = NULL
                            WHERE  cod_capacitacion = $atr[id]";      
                    //echo $sql;
                    //$val = $this->verPersonalCapacitacion($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "cod_capacitacion = $atr[id]";
                    $anterior = "";
                    $this->registraTransaccionLog(57,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el PersonalCapacitacion ' . $atr[descripcion_ano], 'mos_personal_capacitacion');
                    */
                    return "ha sido eliminado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function listarPersonalCapacitacionDetalle($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT 
                                cod_capacitacion
                                ,pcd.cod_emp
                                ,asistencia
                                ,aprobacion
                                ,nota_evaluacion
                                ,observacion                             
                                ,id_personal
                                ,CONCAT(UPPER(LEFT(nombres, 1)), LOWER(SUBSTRING(nombres, 2))) nombres
                                ,CONCAT(UPPER(LEFT(apellido_paterno, 1)), LOWER(SUBSTRING(apellido_paterno, 2))) apellido_paterno
                                ,CONCAT(UPPER(LEFT(apellido_materno, 1)), LOWER(SUBSTRING(apellido_materno, 2))) apellido_materno
                            FROM mos_personal_capacitacion_detalle   pcd
                            INNER JOIN mos_personal p ON p.cod_emp = pcd.cod_emp
                            WHERE cod_capacitacion = $atr[id] "; 
                            
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function listarPersonasSinFiltro($atr){
                    $atr = $this->dbl->corregir_parametros($atr);
                    
            //,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))))  cod_emp_relator
                    $sql = "SELECT cod_emp
                                    ,id_personal
                                    ,CONCAT(UPPER(LEFT(nombres, 1)), LOWER(SUBSTRING(nombres, 2))) nombres
                                    ,CONCAT(UPPER(LEFT(apellido_paterno, 1)), LOWER(SUBSTRING(apellido_paterno, 2))) apellido_paterno
                                    ,CONCAT(UPPER(LEFT(apellido_materno, 1)), LOWER(SUBSTRING(apellido_materno, 2))) apellido_materno
                                    ,id_organizacion
                                    ,DATE_FORMAT(fecha_nacimiento, '%d/%m/%Y') fecha_nacimiento
                                    ,CASE genero WHEN 1 THEN 'Masculino' ELSE 'Femenino' END genero
                                    ,c.descripcion cod_cargo
                                                                 
                            FROM mos_personal p
                            LEFT JOIN mos_cargo c ON c.cod_cargo = p.cod_cargo 
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-cod_emp"])>0)
                        $sql .= " AND cod_emp IN (". $atr['b-cod_emp'] . ")";
                    if (strlen($atr["b-no_cod_emp"])>0)
                        $sql .= " AND NOT cod_emp IN (". $atr['b-no_cod_emp'] . ")";
                    if (strlen($atr["b-id_personal"])>0)
                                $sql .= " AND upper(id_personal) like '%" . strtoupper($atr["b-id_personal"]) . "%'";
                    if (strlen($atr["b-nombres"])>0)
                                $sql .= " AND upper(nombres) like '%" . strtoupper($atr["b-nombres"]) . "%'";
                    if (strlen($atr["b-apellido_paterno"])>0)
                                $sql .= " AND upper(apellido_paterno) like '%" . strtoupper($atr["b-apellido_paterno"]) . "%'";
                    if (strlen($atr["b-apellido_materno"])>0)
                                $sql .= " AND upper(apellido_materno) like '%" . strtoupper($atr["b-apellido_materno"]) . "%'";
                    if (strlen($atr["b-genero"])>0)
                                $sql .= " AND upper(genero) like '%" . strtoupper($atr["b-genero"]) . "%'";
                    if (strlen($atr['b-fecha_nacimiento-desde'])>0)                        
                    {
                        //$atr['b-fecha_nacimiento-desde'] = formatear_fecha($atr['b-fecha_nacimiento-desde']);                        
                        $sql .= " AND fecha_nacimiento >= '" . ($atr['b-fecha_nacimiento-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_nacimiento-hasta'])>0)                        
                    {
                        //$atr['b-fecha_nacimiento-hasta'] = formatear_fecha($atr['b-fecha_nacimiento-hasta']);                        
                        $sql .= " AND fecha_nacimiento >= '" . ($atr['b-fecha_nacimiento-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(p.vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-interno"])>0)
                        $sql .= " AND upper(p.interno) like '%" . strtoupper($atr["b-interno"]) . "%'";
                    if (strlen($atr["b-id_filial"])>0)
                               $sql .= " AND id_filial = '". $atr[b-id_filial] . "'";
                    //if (strlen($atr["b-id_organizacion"])>0)
                    //           $sql .= " AND id_organizacion = '". $atr[b-id_organizacion] . "'";
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){                             
                        //$id_org = $this->BuscaOrgNivelHijos($atr[b-id_organizacion]);
                        $sql .= " AND id_organizacion IN (". $id_org . ")";
                    }
                    if (strlen($atr["b-cod_cargo"])>0)
                               $sql .= " AND c.descripcion = '". $atr[b-cod_cargo] . "'";
                    if (strlen($atr["b-workflow"])>0)
                                $sql .= " AND upper(workflow) like '%" . strtoupper($atr["b-workflow"]) . "%'";
                    if (strlen($atr["b-email"])>0)
                                $sql .= " AND upper(email) like '%" . strtoupper($atr["b-email"]) . "%'";
                    if (strlen($atr["b-relator"])>0)
                                $sql .= " AND upper(relator) like '%" . strtoupper($atr["b-relator"]) . "%'";
                    if (strlen($atr["b-reviso"])>0)
                                $sql .= " AND upper(reviso) like '%" . strtoupper($atr["b-reviso"]) . "%'";
                    if (strlen($atr["b-elaboro"])>0)
                                $sql .= " AND upper(elaboro) like '%" . strtoupper($atr["b-elaboro"]) . "%'";
                    if (strlen($atr["b-aprobo"])>0)
                                $sql .= " AND upper(aprobo) like '%" . strtoupper($atr["b-aprobo"]) . "%'";
                    if (strlen($atr["b-extranjero"])>0)
                                $sql .= " AND upper(extranjero) like '%" . strtoupper($atr["b-extranjero"]) . "%'";

                    $sql .= " order by apellido_paterno asc ";                    
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function listarPersonalCapacitacion($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                     if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 1;                    
                    foreach ($this->parametros as $value) {
                        $sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                                inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                        where t1.cod_categoria='10' and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = p.cod_emp "; 
                        $sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_personal_capacitacion pc
                            INNER JOIN mos_cursos c ON c.cod_curso = pc.cod_curso  
                            left join mos_personal p on pc.cod_emp_relator=p.cod_emp
                            -- LEFT JOIN (select count(cod_capacitacion) as cantidad_ap, cod_capacitacion from mos_personal_capacitacion_detalle where aprobacion='S' GROUP BY cod_capacitacion) as ap ON ap.cod_capacitacion = pc.cod_capacitacion

                         WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(p.nombres) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(c.identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";                        
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_capacitacion"])>0)
                        $sql .= " AND cod_capacitacion = '". $atr["b-cod_capacitacion"] . "'";
//                    if (strlen($atr["b-cod_curso"])>0)
//                        $sql .= " AND cod_curso = '". $atr["b-cod_curso"] . "'";
                    if (strlen($atr["b-cod_emp_relator"])>0){
                        //$sql .= " AND cod_emp_relator = '". $atr["b-cod_emp_relator"] . "'";
                        $nombre_supervisor = explode(' ', $atr["b-cod_emp_relator"]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql .= " AND ((upper(p.nombres) like '%" . strtoupper($supervisor_aux) . "%') OR (upper(p.apellido_paterno) like '%" . strtoupper($supervisor_aux) . "%') OR (upper(p.apellido_materno) like '%" . strtoupper($supervisor_aux) . "%'))";
                        }  
                    }
                    if (strlen($atr['b-fecha-desde'])>0)                        
                    {
                        $atr['b-fecha-desde'] = formatear_fecha($atr['b-fecha-desde']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha-hasta'])>0)                        
                    {
                        $atr['b-fecha-hasta'] = formatear_fecha($atr['b-fecha-hasta']);                        
                        $sql .= " AND fecha <= '" . ($atr['b-fecha-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND id_organizacion = '". $atr["b-id_organizacion"] . "'";
                    if (strlen($atr["b-cod_curso"])>0)
                        $sql .= " AND upper(c.identificacion) like '%" . strtoupper($atr["b-cod_curso"]) . "%'";
                    if (strlen($atr["b-archivo"])>0)
                        $sql .= " AND archivo = '". $atr["b-archivo"] . "'";
                    if (strlen($atr["b-contenttype"])>0)
                        $sql .= " AND upper(contenttype) like '%" . strtoupper($atr["b-contenttype"]) . "%'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND observacion = '". $atr["b-observacion"] . "'";
                    if (strlen($atr['b-fecha_termino-desde'])>0)                        
                    {
                        $atr['b-fecha_termino-desde'] = formatear_fecha($atr['b-fecha_termino-desde']);                        
                        $sql .= " AND fecha_termino >= '" . ($atr['b-fecha_termino-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_termino-hasta'])>0)                        
                    {
                        $atr['b-fecha_termino-hasta'] = formatear_fecha($atr['b-fecha_termino-hasta']);                        
                        $sql .= " AND fecha_termino <= '" . ($atr['b-fecha_termino-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-hora"])>0)
                        $sql .= " AND hora = ". $atr["b-hora"] . "";
                    if (strlen($atr["b-min"])>0)
                        $sql .= " AND min = ". $atr["b-min"] . "";
                    if (strlen($atr["b-hh"])>0)
                        $sql .= " AND hh = '". $atr["b-hh"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT pc.cod_capacitacion
                                    ,c.identificacion cod_curso                                    
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha_inicio
                                    ,DATE_FORMAT(fecha_termino, '%d/%m/%Y') fecha_termino
                                    ,hora
                                    ,min                                    
                                    ,(select count(*) from mos_personal_capacitacion_detalle where mos_personal_capacitacion_detalle.cod_capacitacion = pc.cod_capacitacion) as cant_pe
                                    ,hh
                                    ,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))))  cod_emp_relator
                                    ,pc.id_organizacion
                                    ,nom_archivo
                                    ,1 archivo
                                    ,contenttype
                                    ,observacion
                                    ,ap.cantidad_ap
                                    

                                     $sql_col_left
                            FROM mos_personal_capacitacion pc
                            INNER JOIN mos_cursos c ON c.cod_curso = pc.cod_curso  
                            left join mos_personal p on pc.cod_emp_relator=p.cod_emp
                            LEFT JOIN (select count(cod_capacitacion) as cantidad_ap, cod_capacitacion from mos_personal_capacitacion_detalle where aprobacion='S' GROUP BY cod_capacitacion) as ap ON ap.cod_capacitacion = pc.cod_capacitacion
                            $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(p.nombres) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(c.identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";                        
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_capacitacion"])>0)
                        $sql .= " AND cod_capacitacion = '". $atr["b-cod_capacitacion"] . "'";
//                    if (strlen($atr["b-cod_curso"])>0)
//                        $sql .= " AND cod_curso = '". $atr["b-cod_curso"] . "'";
                    if (strlen($atr["b-cod_emp_relator"])>0){
                        //$sql .= " AND cod_emp_relator = '". $atr["b-cod_emp_relator"] . "'";
                        $nombre_supervisor = explode(' ', $atr["b-cod_emp_relator"]);                                                  
                        foreach ($nombre_supervisor as $supervisor_aux) {
                           $sql .= " AND ((upper(p.nombres) like '%" . strtoupper($supervisor_aux) . "%') OR (upper(p.apellido_paterno) like '%" . strtoupper($supervisor_aux) . "%') OR (upper(p.apellido_materno) like '%" . strtoupper($supervisor_aux) . "%'))";
                        }  
                    }
                    if (strlen($atr['b-fecha-desde'])>0)                        
                    {
                        $atr['b-fecha-desde'] = formatear_fecha($atr['b-fecha-desde']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha-hasta'])>0)                        
                    {
                        $atr['b-fecha-hasta'] = formatear_fecha($atr['b-fecha-hasta']);                        
                        $sql .= " AND fecha <= '" . ($atr['b-fecha-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND id_organizacion = '". $atr["b-id_organizacion"] . "'";
                    if (strlen($atr["b-cod_curso"])>0)
                        $sql .= " AND upper(c.identificacion) like '%" . strtoupper($atr["b-cod_curso"]) . "%'";
                    if (strlen($atr["b-archivo"])>0)
                        $sql .= " AND archivo = '". $atr["b-archivo"] . "'";
                    if (strlen($atr["b-contenttype"])>0)
                        $sql .= " AND upper(contenttype) like '%" . strtoupper($atr["b-contenttype"]) . "%'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND observacion = '". $atr["b-observacion"] . "'";
                    if (strlen($atr['b-fecha_termino-desde'])>0)                        
                    {
                        $atr['b-fecha_termino-desde'] = formatear_fecha($atr['b-fecha_termino-desde']);                        
                        $sql .= " AND fecha_termino >= '" . ($atr['b-fecha_termino-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_termino-hasta'])>0)                        
                    {
                        $atr['b-fecha_termino-hasta'] = formatear_fecha($atr['b-fecha_termino-hasta']);                        
                        $sql .= " AND fecha_termino <= '" . ($atr['b-fecha_termino-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-hora"])>0)
                        $sql .= " AND hora = ". $atr["b-hora"] . "";
                    if (strlen($atr["b-min"])>0)
                        $sql .= " AND min = ". $atr["b-min"] . "";
                    if (strlen($atr["b-hh"])>0)
                        $sql .= " AND hh = '". $atr["b-hh"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function eliminarPersonalCapacitacion($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_personal_capacitacion", "cod_capacitacion = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_personal_capacitacion_detalle", "cod_capacitacion = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaPersonalCapacitacion($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarPersonalCapacitacion($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblPersonalCapacitacion", "");
                $config_col=array(
                     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_capacitacion], "cod_capacitacion", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_curso], "cod_curso", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha], "fecha", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_termino], "fecha_termino", $parametros)),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[hora], "hora", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Min", "min", $parametros)),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos("Cant. Personas", "cant_pe", $parametros)),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[hh], "hh", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_emp_relator], "cod_emp_relator", $parametros)),               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Id Organizacion", "id_organizacion", $parametros)),               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nom_archivo], "nom_archivo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Archivo", "archivo", $parametros)),
               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Contenttype", "contenttype", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[observacion], "observacion", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>"Evaluaci&oacute;n Aprobados")
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(utf8_decode($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 14;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verPersonalCapacitacion','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver PersonalCapacitacion'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarPersonalCapacitacion','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\" title='Editar PersonalCapacitacion'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarPersonalCapacitacion','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar PersonalCapacitacion'></i>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:50px'>&nbsp;</div>"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
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
                $grid->setFuncion("hora", "formatear_horas_capa");
                $grid->setFuncion("hh", "formatear_hh");
                $grid->setFuncion("cantidad_ap", "cantidad_aprobados");
                $grid->setFuncion("nom_archivo", "archivo");
                
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
            $this->listarPersonalCapacitacion($parametros, 1, 100000);
            $data=$this->dbl->data;
            
            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

             $grid->SetConfiguracion("tblPersonalCapacitacion", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_capacitacion], ENT_QUOTES, "UTF-8")),
               array( "width"=>"20%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_curso], ENT_QUOTES, "UTF-8")),
               array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha], ENT_QUOTES, "UTF-8")),
               array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_termino], ENT_QUOTES, "UTF-8")),
               array( "width"=>"3%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[hora], ENT_QUOTES, "UTF-8")),     
               array( "width"=>"10%","ValorEtiqueta"=>"Min"),
               array( "width"=>"3%","ValorEtiqueta"=>"Cant. Personas"),
               array( "width"=>"3%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[hh], ENT_QUOTES, "UTF-8")),     
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_emp_relator], ENT_QUOTES, "UTF-8")),               
               array( "width"=>"10%","ValorEtiqueta"=>"Id Organizacion"),               
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nom_archivo], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>"Archivo"),
               
               array( "width"=>"10%","ValorEtiqueta"=>"Contenttype"),
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[observacion], ENT_QUOTES, "UTF-8")),
                    array( "width"=>"10%","ValorEtiqueta"=>"Evaluaci&oacute;n Aprobados")
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
//                        case 2:
//                        case 3:
//                        case 4:
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
 
 
            public function indexPersonalCapacitacion($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="fecha";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-6-7-8-10-14"; 
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
                $grid = $this->verListaPersonalCapacitacion($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_PersonalCapacitacion();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;PersonalCapacitacion';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $ut_tool = new ut_Tool();
                $ids = array(''); 
                $desc = array('00');
                for($i=1;$i<=2000;$i++){
                    if ($i<10){
                        $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }
                    else
                    {
                       $desc[] = $i; 
                    }
                    $ids[] = $i;
                }
                $contenido['HORAS'] = $ut_tool->combo_array("b-hora", $desc, $ids,false,false,false,false,false,false,'display:inline;width:70px');
                $ids = array(''); 
                $desc = array('00');
                for($i=1;$i<=60;$i++){
                    if ($i<10){
                        $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }
                    else
                    {
                       $desc[] = $i; 
                    }
                    $ids[] = $i;
                }
                $contenido['MINUTOS'] = $ut_tool->combo_array("b-min", $desc, $ids,false,false,false,false,false,false,'display:inline;width:70px');


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personas/';
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
                $template->PATH = PATH_TO_TEMPLATES.'personal_capacitacion/';

                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personal_capacitacion/';

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
                $objResponse->addAssign('modulo_actual',"value","personal_capacitacion");
                $objResponse->addIncludeScript(PATH_TO_JS . 'personal_capacitacion/personal_capacitacion.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
//                $objResponse->addScript("var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
//
//                        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
//
//                        \$confModal.on('hidden', function() {
//                            $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
//                        });
//
//                        \$confModal.modal({ backdrop : false });");
//                $objResponse->addScript("$('#b-fecha-desde').datepicker({
//                                            changeMonth: true,
//                                            yearRange: '-100:+0',
//                                            changeYear: true
//                                          });");
//                $objResponse->addScript("$('#b-fecha-hasta').datepicker({
//                                            changeMonth: true,
//                                            yearRange: '-100:+0',
//                                            changeYear: true
//                                          });");
//                $objResponse->addScript("$('#b-fecha_termino-desde').datepicker({
//                                            changeMonth: true,
//                                            yearRange: '-100:+0',
//                                            changeYear: true
//                                          });");
//                $objResponse->addScript("$('#b-fecha_termino-hasta').datepicker({
//                                            changeMonth: true,
//                                            yearRange: '-100:+0',
//                                            changeYear: true
//                                          });");
//                $objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
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
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personal_capacitacion/';
                $template->setTemplate("formulario");
                $contenido_1[NUM_ITEMS_ESP] = 0;
                
                $contenido_1['CURSOS'] = $ut_tool->OptionsCombo("SELECT cod_curso, identificacion 
                                                                            FROM mos_cursos "
                                                                    , 'cod_curso'
                                                                    , 'identificacion', $val['cod_curso']);
                $contenido_1['RELATORES'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE relator = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=2000;$i++){
                    if ($i<10){
                        $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }
                    else
                    {
                       $desc[] = $i; 
                    }
                    $ids[] = $i;
                }
                $contenido_1['HORAS'] = $ut_tool->combo_array("hora", $desc, $ids,false,false,false,false,false,false,'display:inline;width:70px');
                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=60;$i++){
                    if ($i<10){
                        $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }
                    else
                    {
                       $desc[] = $i; 
                    }
                    $ids[] = $i;
                }
                $contenido_1['MINUTOS'] = $ut_tool->combo_array("min", $desc, $ids,false,false,false,false,false,false,'display:inline;width:70px');
                
                $template->setVars($contenido_1);
               //$contenido['CAMPOS'] = $template->show();
                $html = '';

                $this->listarPersonasSinFiltro($parametros);
                $data=$this->dbl->data;                
                foreach ($data as $value) {
                    $html .= '<option value="'.$value[cod_emp].'" rut="'.$value[id_personal].'"';
                    $html .= ' nom="' .$value[nombres].'"' ;
                    $html .= ' ap_p="' .$value[apellido_paterno].'"' ;
                    $html .= ' ap_m="' .$value[apellido_materno].'"' ;
                    $html .= ' arb="' . $value[id_organizacion] . '"';
                    $html .= '>' /*. completar_espacios($i,1).' - '*/. str_pad($value[id_personal], 9, '0',STR_PAD_LEFT).' - '.$value[apellido_paterno].' '.$value[apellido_materno].' '.$value[nombres].'</option>';
                    $i++;
                }
                $contenido_1[TOTAl_PER_SEL] = 0;
                $contenido_1[TOTAl_PER] = count($data);
                //$html .= '</select>';
                $contenido_1[ORIGEN] = $html;

                //$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->setTemplate("formulario");
                $contenido_1['TITULO_FORMULARIO'] = "Crear&nbsp;";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;PersonalCapacitacion";
                $contenido_1['PAGINA_VOLVER'] = "listarPersonalCapacitacion.php";
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
                $objResponse->addScript("$('#fecha').datepicker();");
                $objResponse->addScript("$('#fecha_termino').datepicker();");
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show');");
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
                    $parametros["fecha"] = formatear_fecha($parametros["fecha"]);
                    $parametros["fecha_termino"] = formatear_fecha($parametros["fecha_termino"]);

                    $archivo = '';
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            
                            $parametros[contenttype] = 'application/pdf';                                    
                            $parametros[nom_archivo] = $parametros['filename'];
                           
                    }
                    
                    $respuesta = $this->ingresarPersonalCapacitacion($parametros,$archivo);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        //$atr[cod_capacitacion],$atr[cod_emp],'$atr[asistencia]','$atr[aprobacion]','$atr[nota_evaluacion]','$atr[observacion]'
                        $params[cod_capacitacion] = $respuesta;
                        //$params['id_usuario']= $_SESSION['USERID'];
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["id_pers_$i"])){                                
                                //echo $parametros["nro_pts_$i"];
                                $params[cod_emp] = $parametros["id_pers_$i"];
                                $params[asistencia] = $parametros["asis_$i"];                                
                                $params[aprobacion] = $parametros["aprobo_$i"]; 
                                $params[nota_evaluacion] = ($parametros[tipo_curso] == 'N') ? 'No Apl' : $parametros["nota_$i"]; 
                                $params[observacion] = $parametros["obs_$i"]; 
                                //echo $parametros["cuerpo_$i"];
                                $this->ingresarPersonalCapacitacionDetalle($params);
                            }
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',"La capacitaci&oacute;n '$parametros[nombre_curso]' ha sido ingresado con exito");
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
                $val = $this->verPersonalCapacitacion($parametros[id]); 

                $contenido_1['COD_CAPACITACION'] = $val["cod_capacitacion"];
                $contenido_1['COD_CURSO'] = $val["cod_curso"];
                $contenido_1['COD_EMP_RELATOR'] = $val["cod_emp_relator"];
                $contenido_1['FECHA'] = ($val["fecha"]);
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                $contenido_1['NOM_ARCHIVO'] = ($val["nom_archivo"]);
                $contenido_1['ARCHIVO'] = $val["archivo"];
                $contenido_1['CONTENTTYPE'] = ($val["contenttype"]);
                $contenido_1['OBSERVACION'] = $val["observacion"];
                $contenido_1['FECHA_TERMINO'] = ($val["fecha_termino"]);
                $contenido_1['HORA'] = $val["hora"];
                $contenido_1['MIN'] = $val["min"];
                $contenido_1['HH'] = $val["hh"];
                $contenido_1['CURSOS'] = $ut_tool->OptionsCombo("SELECT cod_curso, identificacion 
                                                                            FROM mos_cursos "
                                                                    , 'cod_curso'
                                                                    , 'identificacion', $val['cod_curso']);
                $contenido_1['RELATORES'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE relator = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=2000;$i++){
                    if ($i<10){
                        $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }
                    else
                    {
                       $desc[] = $i; 
                    }
                    $ids[] = $i;
                }
                $contenido_1['HORAS'] = $ut_tool->combo_array("hora", $desc, $ids,false,$val["hora"],false,false,false,false,'display:inline;width:70px');
                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=60;$i++){
                    if ($i<10){
                        $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);
                    }
                    else
                    {
                       $desc[] = $i; 
                    }
                    $ids[] = $i;
                }
                $contenido_1['MINUTOS'] = $ut_tool->combo_array("min", $desc, $ids,false,$val["min"],false,false,false,false,'display:inline;width:70px');

                $this->listarPersonalCapacitacionDetalle($parametros);
                $data=$this->dbl->data;
                //print_r($data);
                $item = "";
                $js = "";
                $i = 0;
                $ids_per = '0';
               
                $contenido_1[TIPO_CURSO] = $val[aplica_evaluacion];
                foreach ($data as $value) {                          
                    $i++;
                    $ids_per .= ','.$value[cod_emp];
                    //echo $i;
                    
                    $item = $item. '<tr id="tr-esp-' . $i . '" class="DatosGrilla" onmouseout="TRMarkOut(this);" onmouseover="TRMarkOver(this);">'; 
                    $item = $item. '<td align="center">';
                    $item = $item. '<a href="' . $i . '" id="eliminar_esp_' . $i . '"><i class="icon icon-remove"></i></a>';  
                    $item = $item. '<input type="hidden" id="id_pers_' . $i . '" name="id_pers_' . $i . '" value="' .$value[cod_emp]. '">'; 
                    $item = $item. '</td>' ; 
                    $item = $item. '<td>' . formatear_rut(array('id_personal'=> $value[id_personal])). '</td>';
                    $item = $item. '<td>' .$value[nombres]. ' ' . $value[apellido_paterno]. ' ' .$value[apellido_materno]. ' </td>';
                    $ap_si = $ap_no = '';
                    if ($value[aprobacion] == 'S'){
                        $ap_si = 'checked="checked"';
                    }
                    else $ap_no = 'checked="checked"';
                    $item = $item. '<td><span class="">'.
                                                    '    Si '.
                                                    '    <input id="aprobo_' . $i . '" value="S" class="CajaTexto" type="radio" '. $ap_si . ' name="aprobo_' . $i . '">'.
                                                        ' No '.
                                                        '<input id="aprobo_' . $i . '" value="N" class="CajaTexto" type="radio" '. $ap_no . ' name="aprobo_' . $i . '">'.
                                                    '</span></td>';
                    if ($val[aplica_evaluacion] =='S'){
                        $item = $item. '<td><input type="text" id="nota_' . $i . '" name="nota_' . $i . '" maxlength="4" size="6" class="form-box" value="' .$value[nota_evaluacion]. '" data-validation="number"></td>';         
                    }
                    else{
                        $item = $item. '<td><input type="text" value="No Aplica" readonly="readonly" maxlength="4" size="6"></td>';         
                    }
                    $item = $item. '<td><input type="text" id="asis_' . $i . '" name="asis_' . $i . '" maxlength="4" size="6" class="form-box" value="' .$value[asistencia]. '" data-validation="number" data-validation-allowing="range[1;100]"> %</td>';         
                    $item = $item. '<td><textarea id="obs_' . $i . '" class="CajaTexto" name="obs_' . $i . '" cols="30">' .$value[observacion]. '</textarea>' . '</td>';
                    $item = $item. '</tr>' ;                                                                                                           
                    $js .= '$("#eliminar_esp_' . $i . '").click(function(e){ 
                        e.preventDefault();
                        var id = $(this).attr("href");
                        $("#destino option:selected").prop("selected", false);
                        $("#destino option[value=\'' .$value[cod_emp]. '\']").prop("selected", true);

                        !$("#destino option:selected").remove().appendTo("#origen");

                        $("tr-esp-' . $i . '").remove();
                        var parent = $(this).parents().parents().get(0);
                            $(parent).remove();
                            total_per_sel();
                    });';
                    
                }     
                //echo $item;
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;
                $html = '';
                $parametros['b-no_cod_emp'] = $ids_per;
                $this->listarPersonasSinFiltro($parametros);
                $data=$this->dbl->data;                
                foreach ($data as $value) {
                    $html .= '<option value="'.$value[cod_emp].'" rut="'.$value[id_personal].'"';
                    $html .= ' nom="' .$value[nombres].'"' ;
                    $html .= ' ap_p="' .$value[apellido_paterno].'"' ;
                    $html .= ' ap_m="' .$value[apellido_materno].'"' ;
                    $html .= ' arb="' . $value[id_organizacion] . '"';
                    $html .= '>' /*. completar_espacios($i,1).' - '*/. str_pad($value[id_personal], 9, '0',STR_PAD_LEFT).' - '.$value[apellido_paterno].' '.$value[apellido_materno].' '.$value[nombres].'</option>';
                    $i++;
                }
                //$html .= '</select>';
                $contenido_1[ORIGEN] = $html;
                $contenido_1[TOTAl_PER_SEL] = 0;
                $contenido_1[TOTAl_PER] = count($data);
                $html = '';
                $parametros['b-no_cod_emp'] = '';
                $parametros['b-cod_emp'] = $ids_per;
                $this->listarPersonasSinFiltro($parametros);
                $data=$this->dbl->data;        
                //print_r($data);
                foreach ($data as $value) {
                    $html .= '<option value="'.$value[cod_emp].'" rut="'.$value[id_personal].'"';
                    $html .= ' nom="' .$value[nombres].'"' ;
                    $html .= ' ap_p="' .$value[apellido_paterno].'"' ;
                    $html .= ' ap_m="' .$value[apellido_materno].'"' ;
                    $html .= ' arb="' . $value[id_organizacion] . '"';
                    $html .= '>' /*. completar_espacios($i,1).' - '*/. str_pad($value[id_personal], 9, '0',STR_PAD_LEFT).' - '.$value[apellido_paterno].' '.$value[apellido_materno].' '.$value[nombres].'</option>';
                    $i++;
                }
                //$html .= '</select>';
                $contenido_1[DESTINO] = $html;
                $contenido_1[TOTAl_PER_SEL] = count($data);
                $contenido_1[TOTAl_PER] = $contenido_1[TOTAl_PER] + count($data);
                //b-cod_emp
//                $objResponse = new xajaxResponse();
//                $objResponse->addAssign('div-origen',"innerHTML",$html);
                          
//                $objResponse->addScript("$('#MustraCargando').hide();");
//                $objResponse->addScript("$('#origen').on('dblclick', 'option', function() {
//                                                !$('#origen option:selected').remove().appendTo('#destino');                
//                                        }); "); 
//                $objResponse->addScript("$('#destino').on('dblclick', 'option', function() {
//                                                !$('#destino option:selected').remove().appendTo('#origen');         
//                                        }); "); 
//                return $objResponse;
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personal_capacitacion/';
                $template->setTemplate("formulario");
                //$template->setVars($contenido_1);

                //$contenido['CAMPOS'] = $template->show();

                //$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->setTemplate("formulario");

                $contenido_1['TITULO_FORMULARIO'] = "Editar&nbsp;";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;PersonalCapacitacion";
                $contenido_1['PAGINA_VOLVER'] = "listarPersonalCapacitacion.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "upd";
                $contenido_1['ID'] = $val["cod_capacitacion"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('#fecha').datepicker();");
                $objResponse->addScript("$('#fecha_termino').datepicker();");
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show');");
                $objResponse->addScript("$js");
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
                    $parametros["fecha"] = formatear_fecha($parametros["fecha"]);
                    $parametros["fecha_termino"] = formatear_fecha($parametros["fecha_termino"]);
                    
                    $archivo = '';
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            
                            $parametros[contenttype] = 'application/pdf';                                    
                            $parametros[nom_archivo] = $parametros['filename'];
                           
                    }
                    
                    $respuesta = $this->modificarPersonalCapacitacion($parametros, $archivo);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        
                        $sql = "DELETE FROM mos_personal_capacitacion_detalle WHERE cod_capacitacion = $parametros[id]";                               
                        $this->dbl->insert_update($sql);
                        $params[cod_capacitacion] = $parametros[id];
                        //$params['id_usuario']= $_SESSION['USERID'];
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["id_pers_$i"])){                                
                                //echo $parametros["nro_pts_$i"];
                                $params[cod_emp] = $parametros["id_pers_$i"];
                                $params[asistencia] = $parametros["asis_$i"];                                
                                $params[aprobacion] = $parametros["aprobo_$i"]; 
                                $params[nota_evaluacion] = ($parametros[tipo_curso] == 'N') ? 'No Apl' : $parametros["nota_$i"]; 
                                $params[observacion] = $parametros["obs_$i"]; 
                                //echo $parametros["cuerpo_$i"];
                                $this->ingresarPersonalCapacitacionDetalle($params);
                            }
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
                $val = $this->verPersonalCapacitacion($parametros[id]);
                $respuesta = $this->eliminarPersonalCapacitacion($parametros);
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
            
            public function eliminar_archivo($parametros)
            {
                //$val = $this->verPersonalCapacitacion($parametros[id]);
                $respuesta = $this->eliminarArchivoCapacitacion($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminado con exito/",$respuesta ) == true) {
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
                $grid = $this->verListaPersonalCapacitacion($parametros);                
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

                $val = $this->verPersonalCapacitacion($parametros[id]);

                            $contenido_1['COD_CAPACITACION'] = $val["cod_capacitacion"];
            $contenido_1['COD_CURSO'] = $val["cod_curso"];
            $contenido_1['COD_EMP_RELATOR'] = $val["cod_emp_relator"];
            $contenido_1['FECHA'] = ($val["fecha"]);
            $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
            $contenido_1['NOM_ARCHIVO'] = ($val["nom_archivo"]);
            $contenido_1['ARCHIVO'] = $val["archivo"];
            $contenido_1['CONTENTTYPE'] = ($val["contenttype"]);
            $contenido_1['OBSERVACION'] = $val["observacion"];
            $contenido_1['FECHA_TERMINO'] = ($val["fecha_termino"]);
            $contenido_1['HORA'] = $val["hora"];
            $contenido_1['MIN'] = $val["min"];
            $contenido_1['HH'] = $val["hh"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personal_capacitacion/';
                $template->setTemplate("verPersonalCapacitacion");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la PersonalCapacitacion";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>