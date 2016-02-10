<?php
        function archivo_reg($tupla)
        {
            $html = "<a target=\"_blank\" title=\"Ver\" href=\"pages/registros/descargar_archivo.php?id=$tupla[idRegistro]&token=" . md5($tupla[idRegistro]) ."\">
               
                    <i class=\"icon icon-view-document\"></i>
                            
                        </a>";            
            //<img class=\"SinBorde\"  src=\"diseno/images/pdf.png\">
            return $html;
        }
        
        
?>
<?php
 import("clases.interfaz.Pagina");        
        class Registros extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function Registros(){
                parent::__construct();
                $this->asigna_script('registros/registros.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = $this->funciones = array();
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros(){
                session_name("$GLOBALS[SESSION]");
                session_start();   
                $sql = "SELECT 
                                id_unico
                                ,IDDoc
                                ,Nombre
                                ,tipo
                                ,valores
                         FROM mos_documentos_datos_formulario 
                         WHERE IDDoc = $_SESSION[IDDoc] ORDER BY orden";
                //echo $sql;
                $this->parametros = $this->dbl->query($sql, array());
                //print_r($this->parametros);
            }
            
            private function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 9";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
        public function archivo_reg($tupla)
        {
            $html = "<a target=\"_blank\" title=\"Ver\" href=\"pages/registros/descargar_archivo.php?id=$tupla[idRegistro]&token=" . md5($tupla[idRegistro]) ."\">
               
                    <i class=\"icon icon-view-document\"></i>
                            
                        </a>";            
            //<img class=\"SinBorde\"  src=\"diseno/images/pdf.png\">
            return $html;
        }
            
            public function estado_columna($tupla,$key){            
            if ($tupla[$key]== '1'){
                return "<img src=\"diseno/images/verde.png\"/>";
            }
            if ($tupla[$key]== '2'){
                return "<img src=\"diseno/images/amarillo.png\" /> ";
            }
            if ($tupla[$key]== '3'){
                return "<img src=\"diseno/images/rojo.png\"/ >";
            }
            return "";
        }
        
        public function estado_columna_excel($tupla,$key){            
            if ($tupla[$key]== 'Bueno'){
                return "<img src=\"".PATH_TO_IMG."verde.png\"/ title=\"Verde\">";
            }
            return "<img src=\"".PATH_TO_IMG."rojo.png\"/ title=\"Rojo\">";
        }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 9";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


            public function verDocumentoPDF($id){
                $atr=array();
                $sql = "SELECT                             
                            identificacion
                            ,doc_fisico
                            ,contentType    
                            ,idRegistro
                            ,descripcion
                         FROM mos_registro 
                         WHERE idRegistro = $id ";  
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }

             public function verRegistros($id){
                $atr=array();
                $sql = "SELECT idRegistro
                            ,IDDoc
                            ,identificacion
                            ,version
                            ,correlativo
                            ,id_usuario
                            ,descripcion
                            ,1 doc_fisico
                            ,contentType
                            ,id_procesos
                            ,id_organizacion
                         FROM mos_registro 
                         WHERE idRegistro = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verCamposDinamicos(){
                $atr=array();
                $sql = "SELECT 
                                id_unico
                                ,IDDoc
                                ,Nombre
                                ,tipo
                                ,valores
                         FROM mos_documentos_datos_formulario 
                         WHERE IDDoc = $_SESSION[IDDoc] ORDER BY orden"; 
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            public function verValoresCamposDinamicos($id){
                $atr=array();
                $sql = "SELECT 
                                df.id_unico
                                ,df.IDDoc
                                ,df.Nombre
                                ,df.tipo
                                ,valores
				,rf.Nombre valor
				,rf.idRegistro
                         FROM mos_documentos_datos_formulario df
                            inner join mos_registro_formulario rf on rf.id_unico = df.id_unico
                         WHERE df.IDDoc = $_SESSION[IDDoc] AND rf.idRegistro = $id ORDER BY df.orden "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            public function verDocumentos($id){
                $atr=array();
                $sql = "SELECT IDDoc
                                ,Codigo_doc
                                ,nombre_doc
                                ,version
                                
                         FROM mos_documentos 
                         WHERE IDDoc = $id ";                 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function ingresarRegistros($atr,$archivo){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion
                    $atr[doc_fisico] = $archivo;
                    $sql = "INSERT INTO mos_registro(IDDoc,identificacion,id_usuario,descripcion,doc_fisico,contentType)
                            VALUES(
                                $_SESSION[IDDoc],'$atr[identificacion]',$atr[id_usuario],'$atr[descripcion]','$atr[doc_fisico]','$atr[contentType]'
                                )";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_registro ' . $atr[descripcion_ano], 'mos_registro');
                      */
                    
                    $sql = "SELECT MAX(idRegistro) ultimo FROM mos_registro"; 
                    $this->operacion($sql, $atr);
                    $nuevo = "IdRegistro: \'".$this->dbl->data[0][0]."\', IDDoc: \'$_SESSION[IDDoc]\', Identificacion: \'$atr[identificacion]\',  Id Usuario: \'$atr[id_usuario]\', Descripcion: \'$atr[descripcion]\', ContentType: \'$atr[contentType]\', Id Procesos: \'$atr[id_procesos]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                    $this->registraTransaccionLog(7,$nuevo,'', '');
                    return $this->dbl->data[0][0];
                    return "El mos_registro '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarRegistrosCampoDinamico($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion                    
                    $sql = "INSERT INTO mos_registro_formulario(IDDoc,idRegistro,Nombre,tipo,id_unico)
                            VALUES(
                                $_SESSION[IDDoc],$atr[idRegistro],'$atr[Nombre]','$atr[tipo]',$atr[id_unico]
                                )";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                    //echo $sql;
                    $this->dbl->insert_update($sql);

                    return "El mos_registro '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
             public function modificarRegistrosCampoDinamico($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion                    
                    $sql = "UPDATE mos_registro_formulario SET Nombre = '$atr[Nombre]'
                            WHERE idRegistro = $atr[idRegistro] AND id_unico = $atr[id_unico]
                            ";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                    //echo $sql;
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

            public function modificarRegistros($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_registro SET                            
                                    idRegistro = $atr[idRegistro],IDDoc = $atr[IDDoc],identificacion = '$atr[identificacion]',version = $atr[version],correlativo = $atr[correlativo],id_usuario = $atr[id_usuario],descripcion = '$atr[descripcion]',doc_fisico = $atr[doc_fisico],contentType = '$atr[contentType]',id_procesos = $atr[id_procesos],id_organizacion = $atr[id_organizacion]
                            WHERE  id = $atr[id]";      
                    $val = $this->verRegistros($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "IdRegistro: \'$atr[idRegistro]\', IDDoc: \'$atr[IDDoc]\', Identificacion: \'$atr[identificacion]\', Version: \'$atr[version]\', Correlativo: \'$atr[correlativo]\', Id Usuario: \'$atr[id_usuario]\', Descripcion: \'$atr[descripcion]\', Doc Fisico: \'$atr[doc_fisico]\', ContentType: \'$atr[contentType]\', Id Procesos: \'$atr[id_procesos]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                    $anterior = "IdRegistro: \'$val[idRegistro]\', IDDoc: \'$val[IDDoc]\', Identificacion: \'$val[identificacion]\', Version: \'$val[version]\', Correlativo: \'$val[correlativo]\', Id Usuario: \'$val[id_usuario]\', Descripcion: \'$val[descripcion]\', Doc Fisico: \'$val[doc_fisico]\', ContentType: \'$val[contentType]\', Id Procesos: \'$val[id_procesos]\', Id Organizacion: \'$val[id_organizacion]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Registros ' . $atr[descripcion_ano], 'mos_registro');
                    */
                    return "El mos_registro '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarRegistros($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                     if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 1;   
                    //id_unico,IDDoc,Nombre,tipo,valores
                    if(!class_exists('Personas')){
                        import("clases.personas.Personas");
                    }
                    $personal = new Personas();
                    foreach ($this->parametros as $value) {
                        //,CONCAT(CONCAT(UPPER(LEFT(ap.nombres, 1)), LOWER(SUBSTRING(ap.nombres, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_paterno, 1)), LOWER(SUBSTRING(ap.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_materno, 1)), LOWER(SUBSTRING(ap.apellido_materno, 2)))) aprobo
                        if ($value[tipo]== '6'){//-- , t1.Nombre as nom_detalle                             
                            if ($registros_x_pagina == 100000){
                                if (count($personal->campos_activos) <= 0){
                                    $personal->cargar_campos_activos();
                                }

                                $sql_left .= " LEFT JOIN(select t1.idRegistro
                                , t1.Nombre as nom_detalle_aux
                                ,p.id_organizacion
                                ,p.id_personal,c.descripcion cargo
                                
                                -- ,DATE_FORMAT(p.fecha_nacimiento, '%d/%m/%Y') fecha_nacimiento
                                -- ,CASE p.genero WHEN 1 THEN 'Masculino' ELSE 'Femenino' END genero
                                -- ,CASE p.workflow  when 'S' then 'Si' Else 'No' END workflow
                                -- ,p.vigencia
                                -- ,CASE p.interno   when 1 then 'Si' Else 'No' END interno                                                                                                                                                                             
                                -- ,p.email
                                -- ,CASE p.relator  when 'S' then 'Si' Else 'No' END relator
                                -- ,CASE p.reviso when 'S' then 'Si' Else 'No' END reviso
                                -- ,CASE p.elaboro when 'S' then 'Si' Else 'No' END elaboro
                                -- ,CASE p.aprobo  when 'S' then 'Si' Else 'No' END aprobo
                                -- ,p.extranjero
                                -- ,DATE_FORMAT(p.fecha_ingreso, '%d/%m/%Y') fecha_ingreso
                                -- ,DATE_FORMAT(p.fecha_egreso, '%d/%m/%Y') fecha_egreso
                                ,CONCAT(initcap(p.nombres), ' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as nom_detalle
                                -- ,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as nom_detalle 
                                from mos_registro_formulario t1
                                inner join mos_personal p on p.cod_emp = CAST(t1.Nombre AS UNSIGNED)
                                LEFT JOIN mos_cargo c ON c.cod_cargo = p.cod_cargo
                                where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro"; 
                                $sql_col_left .= ",p$k.nom_detalle p$k"
                                        . ",p$k.id_personal"
                                        . ",p$k.id_organizacion"
                                        . ",p$k.cargo";
//                                if ($personal->campos_activos[fecha_nacimiento][0] == '1')
//                                        $sql_col_left .= ",p$k.fecha_nacimiento";
//                                if ($personal->campos_activos[genero][0] == '1')
//                                    $sql_col_left .= ",p$k.genero";
//                                $sql_col_left .= ",p$k.workflow
//                                ,p$k.vigencia
//                                ,p$k.email
//                                ,p$k.relator
//                                ,p$k.reviso
//                                ,p$k.elaboro
//                                ,p$k.aprobo
//                                ,p$k.extranjero";
//                                if ($personal->campos_activos[fecha_ingreso][0] == '1')
//                                    $sql_col_left .= ",p$k.fecha_ingreso";
//                                if ($personal->campos_activos[fecha_egreso][0] == '1')
//                                    $sql_col_left .= ",p$k.fecha_egreso ";
                            }   
                            else{
                                $sql_left .= " LEFT JOIN(select t1.idRegistro
                                , t1.Nombre as nom_detalle_aux
                                ,CONCAT(initcap(p.nombres), ' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as nom_detalle
                                -- ,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as nom_detalle 
                                from mos_registro_formulario t1
                                inner join mos_personal p on p.cod_emp = CAST(t1.Nombre AS UNSIGNED)
                                where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro"; 
                                $sql_col_left .= ",p$k.nom_detalle p$k ";
                            }
                            
                        }
                        else if ($value[tipo]== '10'){
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                            where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            if ($registros_x_pagina != 100000)
                                $this->funciones["p$k"] = 'estado_columna';  
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                        }
                        else if ($value[tipo]== '8'){
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, replace(t1.Nombre,'<br/>',' ; ') as nom_detalle from mos_registro_formulario t1
                            where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                        }
                        else{
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                            where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                        }
                        
                        $k++;
                    }
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_registro r $sql_left
                         WHERE 1 = 1 ";
                    if (strlen($_SESSION[IDDoc])>0){
                        $sql .= " AND IDDoc = $_SESSION[IDDoc] ";
                    }
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%' ";
                        $k = 1; 
                        foreach ($this->parametros as $value) {
                            switch ($value[tipo]) {
                                                                
                                case '1':
                                case '5':
                                    {
                                        $sql .= " OR upper(p$k.nom_detalle) LIKE '%". strtoupper($atr["b-filtro-sencillo"]) . "%'";
                                    }                                
                                    break;
                                default:
                                    break;
                            }
                            
                            $k++;
                        }
                        $sql .= " )";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-idRegistro"])>0)
                        $sql .= " AND idRegistro = '". $atr["b-idRegistro"] . "'";
                    if (strlen($atr["b-IDDoc"])>0)
                        $sql .= " AND IDDoc = '". $atr["b-IDDoc"] . "'";
                    if (strlen($atr["b-identificacion"])>0)
                        $sql .= " AND upper(identificacion) like '%" . strtoupper($atr["b-identificacion"]) . "%'";
                    if (strlen($atr["b-version"])>0)
                        $sql .= " AND version = '". $atr["b-version"] . "'";
                    if (strlen($atr["b-correlativo"])>0)
                        $sql .= " AND correlativo = '". $atr["b-correlativo"] . "'";
                    if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-doc_fisico"])>0)
                        $sql .= " AND doc_fisico = '". $atr["b-doc_fisico"] . "'";
                    if (strlen($atr["b-contentType"])>0)
                        $sql .= " AND upper(contentType) like '%" . strtoupper($atr["b-contentType"]) . "%'";
                    if (strlen($atr["b-id_procesos"])>0)
                        $sql .= " AND id_procesos = '". $atr["b-id_procesos"] . "'";
                    if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND id_organizacion = '". $atr["b-id_organizacion"] . "'";
                    $k = 1;   
                    //id_unico,IDDoc,Nombre,tipo,valores
                    /*
                      $ids = array('7','8','9','1','2','3','5','6');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona');
                     */
                    $k = 1;   //7=>Seleccion Simple, 8=>Seleccion Multiple,9=>Combo, 1=>Texto, 2=>Numerico, 3=> Fecha, 5=> Rut, 6=> Persona
                    foreach ($this->parametros as $value) {
                        switch ($value[tipo]) {
                            //case '8':
                            case '10':    
                                if (is_array($atr["p$k"])){
                                    for($i=0;$i<count($atr["p$k"]); $i++)
                                        $atr["p$k"][$i] = "'".$atr["p$k"][$i]."'";
                                    $in_values = implode(",", $atr["p$k"]);
                                    //
                                    {
                                        $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //echo " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
//                                if (is_array($atr["p$k"])){
//                                $in_values = implode("','", $atr["p$k"]);
//                                    //for($i=0;$i<count($atr["p$k"]); $i++)
//                                    {
//                                        $sql .= " AND p$k.nom_detalle IN ($in_values)";
//                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
//                                    }
////                                    
////                                    for($i=0;$i<count($atr["p$k"]); $i++){
////                                        $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"][$i] . "%'";
////                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
////                                    }
//                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                               case '8':
                            //case '10':                                    
                                if (is_array($atr["p$k"])){
                                //$in_values = implode("','", $atr["p$k"]);
                                    //for($i=0;$i<count($atr["p$k"]); $i++)
                                   // {
                                   //     $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                   // }
//                                    
                                    for($i=0;$i<count($atr["p$k"]); $i++){
                                        $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"][$i] . "%'";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                            
                            case '7':
                            case '9':
                            case '2':
                            case '3':
                            //case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle = '". $atr["p$k"] . "'";
                                }                                
                                break;
                            case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle_aux = '". $atr["p$k"] . "'";
                                } 
                                break;
                            case '1':
                            case '5':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
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

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT r.idRegistro
                                    ,d.Codigo_doc
                                    ,r.identificacion
                                    -- ,version
                                    -- ,correlativo
                                    -- ,id_usuario
                                    -- ,descripcion
                                    ,1 doc_fisico
                                    ,r.contentType
                                    -- ,r.id_procesos
                                    -- ,r.id_organizacion

                                     $sql_col_left
                            FROM mos_registro r
                            INNER JOIN mos_documentos d ON d.IDDoc = r.IDDoc
                            $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($_SESSION[IDDoc])>0){
                        $sql .= " AND r.IDDoc = $_SESSION[IDDoc] ";
                    }
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $k = 1; 
                        $sql .= " AND (upper(identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%' ";
                        foreach ($this->parametros as $value) {
                            switch ($value[tipo]) {
                                                                
                                case '1':
                                case '5':
                                    {
                                        $sql .= " OR upper(p$k.nom_detalle) LIKE '%". strtoupper($atr["b-filtro-sencillo"]) . "%'";
                                    }                                
                                    break;
                                default:
                                    break;
                            }
                            
                            $k++;
                        }
                        $sql .= " )";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-idRegistro"])>0)
                        $sql .= " AND idRegistro = '". $atr["b-idRegistro"] . "'";
                    if (strlen($atr["b-IDDoc"])>0)
                        $sql .= " AND IDDoc = '". $atr["b-IDDoc"] . "'";
                    if (strlen($atr["b-identificacion"])>0)
                        $sql .= " AND upper(identificacion) like '%" . strtoupper($atr["b-identificacion"]) . "%'";
                    if (strlen($atr["b-version"])>0)
                        $sql .= " AND version = '". $atr["b-version"] . "'";
                    if (strlen($atr["b-correlativo"])>0)
                        $sql .= " AND correlativo = '". $atr["b-correlativo"] . "'";
                    if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-doc_fisico"])>0)
                        $sql .= " AND doc_fisico = '". $atr["b-doc_fisico"] . "'";
                    if (strlen($atr["b-contentType"])>0)
                        $sql .= " AND upper(contentType) like '%" . strtoupper($atr["b-contentType"]) . "%'";
                    if (strlen($atr["b-id_procesos"])>0)
                        $sql .= " AND id_procesos = '". $atr["b-id_procesos"] . "'";
                    if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND id_organizacion = '". $atr["b-id_organizacion"] . "'";
                    $k = 1;   
                    //id_unico,IDDoc,Nombre,tipo,valores
                    /*
                      $ids = array('7','8','9','1','2','3','5','6');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona');
                     */
                    $k = 1;   
                    foreach ($this->parametros as $value) {
                        switch ($value[tipo]) {
                            //case '8':
                            case '10':    
                                if (is_array($atr["p$k"])){
//                                    for($i=0;$i<count($atr["p$k"]); $i++)
//                                        $atr["p$k"][$i] = "'".$atr["p$k"][$i]."'";
                                    $in_values = implode(",", $atr["p$k"]);
                                    //
                                    {
                                        $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //echo " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                            case '8':
                            //case '10':                                    
                                if (is_array($atr["p$k"])){
                                //$in_values = implode("','", $atr["p$k"]);
                                    //for($i=0;$i<count($atr["p$k"]); $i++)
                                   // {
                                   //     $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                   // }
//                                    
                                    for($i=0;$i<count($atr["p$k"]); $i++){
                                        $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"][$i] . "%'";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                            case '7':
                            case '9':
                            case '2':
                            case '3':
                            //case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle = '". $atr["p$k"] . "'";
                                }                                
                                break;
                            case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle_aux = '". $atr["p$k"] . "'";
                                } 
                                break;
                            case '1':
                            case '5':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
                                }                                
                                break;
                            default:
                                break;
                        }
//                        $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
//                                
//                        where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
//                        $sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }
                    

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarRegistros($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_registro", "idRegistro = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_registro_formulario", "idRegistro = " . $atr[id]);
                        $nuevo = "idRegistro: \'$atr[id]\'";
                        $this->registraTransaccionLog(9,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaRegistros($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarRegistros($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblRegistros", "");
                $config_col=array(
                   
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[idRegistro], "idRegistro", $parametros,'r_link_titulos')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[IDDoc], "IDDoc", $parametros,'r_link_titulos')),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[identificacion], "identificacion", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[correlativo], "correlativo", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros,'r_link_titulos')),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[contentType], "contentType", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_procesos], "id_procesos", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros))
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                foreach ($this->parametros as $value) {   
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $ancho = 5;
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $ancho = 5;                            
                            break;
                        case 'Combo':
                            $ancho = 7;
                        case '9':
                            $ancho = 7;
                            break;
                        case 'Texto':
                        case '1':
                             $ancho = 10;
                            break;
                        case 'Numerico':
                        case '2':
                             $ancho = 3;
                            break;
                        case '3':
                        case 'Fecha':
                              $ancho = 2;
                            break;
                        case '5':
                        case 'Rut':
                              $ancho = 5;                           
                            break;
                        case 'Persona':
                        case '6':
                                $ancho = 15;
                            break;
                        case '10':
                                $ancho = 2;
                                break;
                        default:
                            break;
                    }
                    array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                    $k++;
                }

                $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 12;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verRegistros','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Registros'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarRegistros','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar Registros'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarRegistros','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar Registros'></i>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:50px;'>&nbsp;</div>"));
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
                $grid->setFuncion("contentType", "archivo_reg");
                $grid->setAligns(4,"center");
                foreach ($this->funciones as $key => $value) {
                    $grid->setFuncion($key, $value);
                }
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina_aux", "document", "r-pag_actual","r-reg_por_pag");
                }
                return $out;
            }
            
            public function verListaRegistrosReporte($parametros){
                $grid= "";
                $grid= new DataGrid();
                
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarRegistros($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblRegistros", "");
                $config_col=array(
                   
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[idRegistro], "idRegistro", $parametros,'r_link_titulos')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[IDDoc], "IDDoc", $parametros,'r_link_titulos')),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[identificacion], "identificacion", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[correlativo], "correlativo", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros,'r_link_titulos')),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[contentType], "contentType", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_procesos], "id_procesos", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros))
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                foreach ($this->parametros as $value) {   
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $ancho = 5;
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $ancho = 5;                            
                            break;
                        case 'Combo':
                            $ancho = 7;
                        case '9':
                            $ancho = 7;
                            break;
                        case 'Texto':
                        case '1':
                             $ancho = 10;
                            break;
                        case 'Numerico':
                        case '2':
                             $ancho = 3;
                            break;
                        case '3':
                        case 'Fecha':
                              $ancho = 2;
                            break;
                        case '5':
                        case 'Rut':
                              $ancho = 5;                           
                            break;
                        case 'Persona':
                        case '6':
                                $ancho = 15;
                            break;
                        case '10':
                                $ancho = 2;
                                break;
                        default:
                            break;
                    }
                    array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                    $k++;
                }

                $func= array();

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 12;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verRegistros','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Registros'>"));
                */
                //if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                //    array_push($func,array('nombre'=> 'editarRegistros','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar Registros'></i>"));
                //if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                //    array_push($func,array('nombre'=> 'eliminarRegistros','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar Registros'></i>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"&nbsp;"));
                $config=array();
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
                $grid->setFuncion("contentType", "archivo_reg");
                foreach ($this->funciones as $key => $value) {
                    $grid->setFuncion($key, $value);
                }
                //$grid->setAligns(5,"center");
                //$grid->hidden = array(0 => true);                
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina_aux", "document", "r-pag_actual","r-reg_por_pag");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarRegistros($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblRegistros", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[idRegistro], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[IDDoc], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[identificacion], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[version], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[correlativo], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_usuario], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[doc_fisico], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[contentType], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_procesos], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8"))
              );
                $columna_funcion =10;
           /* $grid->hidden = array(0 => true);*/
           if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                if(!class_exists('Personas')){
                    import("clases.personas.Personas");
                }
                $personal = new Personas();
                foreach ($this->parametros as $value) {          
                    switch ($value[tipo]){
                        case '6':
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  htmlentities($value[Nombre], ENT_QUOTES, "UTF-8")));
                            if (count($personal->nombres_columnas) <= 0){
                                    $personal->cargar_nombres_columnas();
                            }
                            if (count($personal->campos_activos) <= 0){
                                    $personal->cargar_campos_activos();
                            }
                            
                            /*. ",p$k.id_personal"
                                        . ",p$k.cargo"
                                        . ",p$k.id_organizacion"
                                        . ",p$k.fecha_nacimiento
                                ,p$k.genero
                                ,p$k.workflow
                                ,p$k.vigencia
                                ,p$k.email
                                ,p$k.relator
                                ,p$k.reviso
                                ,p$k.elaboro
                                ,p$k.aprobo
                                ,p$k.extranjero
                                ,p$k.fecha_ingreso
                                ,p$k.fecha_egreso ";*/
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_personal], ENT_QUOTES, "UTF-8"))));   
                            array_push($config_col,array( "width"=>"15%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")))); 
                            array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[cod_cargo], ENT_QUOTES, "UTF-8"))));                                
//                            if ($personal->campos_activos[fecha_nacimiento][0] == '1')
//                                array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[fecha_nacimiento], ENT_QUOTES, "UTF-8"))));                   
//                             if ($personal->campos_activos[genero][0] == '1')
//                                 array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[genero], ENT_QUOTES, "UTF-8"))));           
//                            
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[workflow], ENT_QUOTES, "UTF-8"))));
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8"))));
//                            //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[interno], ENT_QUOTES, "UTF-8"))));                   
//                            //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_filial], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[email], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[relator], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[reviso], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[elaboro], ENT_QUOTES, "UTF-8"))));
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[aprobo], ENT_QUOTES, "UTF-8"))));
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[extranjero], ENT_QUOTES, "UTF-8"))));
//                            if ($personal->campos_activos[fecha_ingreso][0] == '1')
//                                array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[fecha_ingreso], ENT_QUOTES, "UTF-8"))));
//                            if ($personal->campos_activos[fecha_egreso][0] == '1')
//                                array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[fecha_egreso], ENT_QUOTES, "UTF-8"))));
                            $grid->setFuncion("id_organizacion", "BuscaOrganizacional");
                            break;
                        default :
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  htmlentities($value[Nombre], ENT_QUOTES, "UTF-8")));
                    }
                    //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  htmlentities($value[Nombre], ENT_QUOTES, "UTF-8")));
                    $k++;
                }
                $columna_funcion =10;
                $config = array();            
                $array_columns =  explode('-', $parametros['mostrar-col']);            
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        //case 1:
                        case 2:
//                        case 3:
//                        case 4:
                            array_push($config,$config_col[$i]);
                            break;
                        case 0:
                        case 3:
                        case 4:
                            $grid->hidden[$i] = true; 
                            break;
                        default:                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else      
                                array_push($config,$config_col[$i]);
                                //$grid->hidden[$i] = true;                            
                            break;
                    }
                }
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexRegistros($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="idRegistro";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-4"; 
                
                
                session_name("$GLOBALS[SESSION]");
                session_start();                                                
                
                $_SESSION[IDDoc] = $parametros[id];                
                
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } 
                $k = 5;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    $parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                    $k++;
                }
                
                $campos_din = $this->verCamposDinamicos();
                $ut_tool = new ut_Tool();
                $html = '';
                $js='';
                $i = 1;
                foreach ($campos_din as $value) {//Nombre,tipo,valores col-md-24
                    $html .= '<div class="form-group"><label>' . $value[Nombre] . '</label>';                                       
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br/>", $value[valor]) ;
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">
                                        <label class="checkbox-inline" style="padding-top: 0px; padding-left: 0px;">';
                            foreach ($cadenas as $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
                                            <input '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $valores . '" name="p' . $i . '" id="p' . $i . '"> '. $valores . ' 
                                          ';
                                
                                //$html .= '<input type="radio" class="form-box" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' value="' . $valores . '" size="20" name="campo_' . $i . '" id="campo_' . $i . '">'. $valores;
                            }
                            $html .= '</label>';
                            break;
                        case '10':
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input  type="checkbox" value="1" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/verde.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input  type="checkbox" value="2" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/amarillo.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input type="checkbox" value="3" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/atrasado.png" /> 
                                          </label>';
                            
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br />", $value[valor]) ;
                            $j = 1;
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">
                                        <label class="checkbox-inline" style="padding-top: 0px; padding-left: 0px;">';
                            foreach ($cadenas as $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="p' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="p' . $i . '[]"> '. $valores . ' 
                                          </label>';
                                //$html .= '<input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '">'. $valores;
                                
                                $j++;
                            }
                            $html .= '</label>';
                            break;
                        case 'Combo':
                        case '9':
                            $cadenas = split("<br />", $value[valores]) ;
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '                                             
                                                      <select class="form-control" name="p' . $i . '" id="p' . $i . '" >
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '';
                                $html .= '<input type="text"  class="col-xs-24 form-control" value="'. $value[valor] .'" name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case 'Numerico': 
                        case '2':
                                $html .= '';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '';
                                $html .= '<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                                $js .= "$('#p$i').datepicker();";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';                                
                            break;
                        case 'Persona':
                        case '6':
                                $html .= '                                             
                                                      <select name="p' . $i . '" id="p' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#p' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select>';
                        default:
                            break;
                    }
//                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
//                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
//                    //$html .= '<input id="validacion_' . $i . '" type="hidden" value="' . $value[validacion] . '" name="validacion_' . $i . '">';
//                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
//                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    $html .= '</div>';
                    $i++;
                }
                $html .= '</table>';
                $contenido[CAMPOS_DINAMICOS] = $html;
                $grid = $this->verListaRegistros($parametros);
                $val = $this->verDocumentos($parametros[id]);
                $contenido[DOCUMENTO] = $val[Codigo_doc] . ' - ' .$val[nombre_doc];
                $contenido[TITULO_MODULO] = 'Documento [ '.$val[Codigo_doc] . ' - ' .$val[nombre_doc].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">
                            Lista de Registros';
                $contenido[TITULO] = $parametros[titulo];
                $_SESSION[Codigo_doc] = $val[Codigo_doc];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Registros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Registros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'registros/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                $template->setTemplate("listar_volver");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('desc-mod-act',"innerHTML","Registros - Documento [$val[Codigo_doc] - $val[nombre_doc]]");
                $objResponse->addAssign('contenido-aux',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);                
                //$objResponse->addAssign('modulo_actual',"value","registros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'registros/registros.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript($js);
                //$objResponse->addScript('r_init_filtrar();');
                $objResponse->addScript('setTimeout(function(){ r_init_filtrar(); }, 500);');
                //$objResponse->addScriptCall("MostrarContenidoAux"); 
                return $objResponse;
            }
            
            public function indexRegistrosListado($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="idRegistro";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2"; 
                
                
                session_name("$GLOBALS[SESSION]");
                session_start();                                                
                
                $_SESSION[IDDoc] = $parametros[id];                
                
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } 
                $k = 5;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    $parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                    $k++;
                }
                
                $campos_din = $this->verCamposDinamicos();
                $ut_tool = new ut_Tool();
                $html = '';
                $js='';
                $i = 1;
                foreach ($campos_din as $value) {//Nombre,tipo,valores col-md-24
                    $html .= '<div class="form-group"><label>' . $value[Nombre] . '</label>';                                       
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br/>", $value[valor]) ;
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">
                                        <label class="checkbox-inline" style="padding-top: 0px; padding-left: 0px;">';
                            foreach ($cadenas as $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
                                            <input '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $valores . '" name="p' . $i . '" id="p' . $i . '"> '. $valores . ' 
                                          ';
                                
                                //$html .= '<input type="radio" class="form-box" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' value="' . $valores . '" size="20" name="campo_' . $i . '" id="campo_' . $i . '">'. $valores;
                            }
                            $html .= '</label>';
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br />", $value[valor]) ;
                            $j = 1;
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">
                                        <label class="checkbox-inline" style="padding-top: 0px; padding-left: 0px;">';
                            foreach ($cadenas as $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="p' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="p' . $i . '[]"> '. $valores . ' 
                                          </label>';
                                //$html .= '<input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '">'. $valores;
                                
                                $j++;
                            }
                            $html .= '</label>';
                            break;
                        case 'Combo':
                        case '9':
                            $cadenas = split("<br />", $value[valores]) ;
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '                                             
                                                      <select class="form-control" name="p' . $i . '" id="p' . $i . '" >
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '';
                                $html .= '<input type="text"  class="col-xs-24 form-control" value="'. $value[valor] .'" name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '';
                                $html .= '<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                                $js .= "$('#p$i').datepicker();";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';                                
                            break;
                        case 'Persona':
                        case '6':
                                $html .= '                                             
                                                      <select name="p' . $i . '" id="p' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#p' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select>';
                        default:
                            break;
                    }
//                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
//                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
//                    //$html .= '<input id="validacion_' . $i . '" type="hidden" value="' . $value[validacion] . '" name="validacion_' . $i . '">';
//                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
//                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    $html .= '</div>';
                    $i++;
                }
                $html .= '</table>';
                $contenido[CAMPOS_DINAMICOS] = $html;
                $grid = $this->verListaRegistrosReporte($parametros);
                $val = $this->verDocumentos($parametros[id]);
                $contenido[DOCUMENTO] = $val[Codigo_doc] . ' - ' .$val[nombre_doc];
                $contenido[TITULO_MODULO] = 'Documento [ '.$val[Codigo_doc] . ' - ' .$val[nombre_doc].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">
                            Lista de Registros';
                $contenido[TITULO] = $parametros[titulo];
                $_SESSION[Codigo_doc] = $val[Codigo_doc];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Registros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Registros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['PERMISO_INGRESAR'] = 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'registros/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                $template->setTemplate("listar_volver");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addIncludeScript(PATH_TO_JS . 'registros/registros_reporte.js');
                $objResponse->addAssign('desc-mod-act',"innerHTML","Registros - Documento [$val[Codigo_doc] - $val[nombre_doc]]");
                $objResponse->addAssign('contenido-aux',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                
                //$objResponse->addAssign('modulo_actual',"value","registros");
                
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript($js);
                
                $objResponse->addScript('setTimeout(function(){ r_init_filtrar_reporte(); }, 500);');
                //$objResponse->addScriptCall("MostrarContenidoAux"); 
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
                $contenido_1[CODIGO_DOC] = $_SESSION[Codigo_doc];
                //$val = $this->verDocumentosNumero($val["numero"]);
                //echo count($val);
                $campos_din = $this->verCamposDinamicos();
                $html = '';
                $js='';
                $i = 1;
                foreach ($campos_din as $value) {//Nombre,tipo,valores
                    $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $value[Nombre] . '</label>';
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br/>", $value[valor]) ;
                            foreach ($cadenas as $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
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
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '"> '. $valores . ' 
                                          </label>';
                                $j++;
                            }
                            $html .= '<input id="num_campo_' . $i . '" type="hidden" value="' . ($j - 1) . '" name="num_campo_' . $i . '">';
                            break;
                        case 'Combo':
                        case '9':
                            $cadenas = split("<br />", $value[valores]) ;
                            $html .= '<div class="col-md-10">                                              
                                                      <select class="form-control" name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select></div>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '<div class="col-md-10">';
                                $html .= '<input type="text" data-validation="required" class="form-control" value="'. $value[valor] .'" name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy" data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      });";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);"  data-validation="required rut" style="width: 160px;" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';                                
                            break;
                        case 'Persona':
                        case '6':
                                $html .= '<div class="col-md-10">                                              
                                                      <select name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(id_personal, ' - ',CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                        case '10':
                            $html .= '<div class="col-md-10">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. (((count($desc_valores_params)== 0) || ($desc_valores_params[$value[cod_parametro]] == '1'))? 'checked' : '') .' type="radio" value="1" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($desc_valores_params[$value[cod_parametro]] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/amarillo.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($desc_valores_params[$value[cod_parametro]] == '3'? 'checked' : '') .' type="radio" value="3" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                            $html .= '</div>';
                            break;
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
                $html .= '</table>';
                $contenido_1[CAMPOS_DINAMICOS] = $html;
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
                $template->setTemplate("formulario_1");
                //$template->setVars($contenido_1);
//                $contenido['CAMPOS'] = $template->show();
//
//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
                $contenido_1['TITULO_FORMULARIO'] = "Crear&nbsp;Registros";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Registros";
                $contenido_1['PAGINA_VOLVER'] = "listarRegistros.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "new";
                $contenido_1['ID'] = "-1";

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form-aux',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2Aux"); 
                $objResponse->addScriptCall("r_cargar_autocompletado");
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");   
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
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $archivo = '';
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            //identificacion,id_usuario,descripcion,doc_fisico,
                            $parametros[identificacion] = $parametros[nombre_doc];
                            $parametros[contentType] = $parametros[tipo_doc];//'application/pdf';                                    
                            $parametros[descripcion] = $parametros['filename'];
                           
                    }
                    $respuesta = $this->ingresarRegistros($parametros,$archivo);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        for ($i = 1; $i <= 20; $i++) {
                            if (isset($parametros['nombre_' . $i]) == true){
                                if ($parametros["tipo_dato_$i"] == '8'){
                                    $valor_actual_aux = '';
                                    for($j = 1; $j <= $parametros["num_campo_$i"]; $j++){
                                        if (isset($parametros["campo_" . $i . "_" . $j]) == true){
                                            $valor_actual_aux .= $parametros["campo_" . $i . "_" . $j] . '<br/>';
                                        }
                                    }
                                    $valor_actual_aux = substr($valor_actual_aux, 0, strlen($valor_actual_aux) - 5);                                    
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    //$params[validacion] = $parametros["validacion_$i"];
                                    //$params[valores] = $parametros["valores_$i"];
                                    $params[Nombre] = $valor_actual_aux;
                                    //$params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $respuesta;
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    $this->ingresarRegistrosCampoDinamico($params);
                                    
                                }
                                else
                                {
                                    //$params[id_documento] = $id_documento;
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    //$params[validacion] = $parametros["validacion_$i"];
                                    //$params[valores] = $parametros["valores_$i"];                                    
                                    $params[Nombre] = $parametros["campo_". $i];
                                    $params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $respuesta;
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    $this->ingresarRegistrosCampoDinamico($params);
                                }
                            }
                        }
                        
                        try{
                            unlink(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                        } catch (Exception $ex) {

                        }
                        $objResponse->addScriptCall("MostrarContenidoAux");
                        $objResponse->addScriptCall('VerMensaje','exito',"El Registro '$parametros[nombre_doc]' ha sido ingresado con exito");
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
                $val = $this->verRegistros($parametros[id]); 

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
                $contenido_1[NOMBRE_DOC] = $val["descripcion"];
                $contenido_1['IDREGISTRO'] = $val["idRegistro"];
                $contenido_1['IDDOC'] = $val["IDDoc"];
                $contenido_1['IDENTIFICACION'] = ($val["identificacion"]);
                $contenido_1['VERSION'] = $val["version"];
                $contenido_1['CORRELATIVO'] = $val["correlativo"];
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
                $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
                $contenido_1['ID_PROCESOS'] = $val["id_procesos"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                
                $campos_din = $this->verValoresCamposDinamicos($val["idRegistro"]);
                $html = '';
                $js='';
                $i = 1;
                foreach ($campos_din as $value) {//Nombre,tipo,valores
                    $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $value[Nombre] . '</label>';
                    //$html .= '<td style="width: 141px;" class="title">' . $value[Nombre] . ':</td><td>';
                    /*
                      $ids = array('7','8','9','1','2','3','5','6');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona');
                     */
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br/>", $value[valor]) ;
                            foreach ($cadenas as $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
                                            <input '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $valores . '" name="campo_' . $i . '" id="campo_' . $i . '"> '. $valores . ' 
                                          </label>';
                                
                                //$html .= '<input type="radio" class="form-box" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' value="' . $valores . '" size="20" name="campo_' . $i . '" id="campo_' . $i . '">'. $valores;
                            }
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br/>", $value[valor]) ;
                            $j = 1;
                            foreach ($cadenas as $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '"> '. $valores . ' 
                                          </label>';
                                //$html .= '<input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '">'. $valores;
                                
                                $j++;
                            }
                            $html .= '<input id="num_campo_' . $i . '" type="hidden" value="' . ($j - 1) . '" name="num_campo_' . $i . '">';
                            break;
                        case 'Combo':
                        case '9':
                            $cadenas = split("<br />", $value[valores]) ;
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '<div class="col-md-10">                                              
                                                      <select class="form-control" name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select></div>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '<div class="col-md-10">';
                                $html .= '<input type="text" data-validation="required" class="form-control" value="'. $value[valor] .'" name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      }); ";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);"  data-validation="required rut" style="width: 160px;" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';                                
                            break;
                        case 'Persona':
                        case '6':
                                $html .= '<div class="col-md-10">                                              
                                                      <select name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(id_personal, ' - ',CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                        case '10':
                            $html .= '<div class="col-md-10">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. (($value[valor] == '1')? 'checked' : '') .' type="radio" value="1" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($value[valor] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/amarillo.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($value[valor] == '3'? 'checked' : '') .' type="radio" value="3" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                            $html .= '</div>';
                            break;
                        default:
                            break;
                    }
                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
                    //$html .= '<input id="validacion_' . $i . '" type="hidden" value="' . $value[validacion] . '" name="validacion_' . $i . '">';
                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    $html .= '</div>';
                    $i++;
                }
                $html .= '</table>';
                $contenido_1[CAMPOS_DINAMICOS] = $html;

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
                $template->setTemplate("formulario_editar");
//                $template->setVars($contenido_1);
//
//                $contenido['CAMPOS'] = $template->show();

//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");

                $contenido_1['TITULO_FORMULARIO'] = "Editar&nbsp;Registros";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Registros";
                $contenido_1['PAGINA_VOLVER'] = "listarRegistros.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "upd";
                //echo $val["idRegistro"];
                $contenido_1['ID'] = $val["idRegistro"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form-aux',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2Aux");   
                $objResponse->addScript($js);
                $objResponse->addScriptCall("r_cargar_autocompletado");
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
                    
                    //$respuesta = $this->modificarRegistros($parametros);

                    //if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) 
                    if (1 == 1)
                    {
                        $nuevo = '';
                        for ($i = 1; $i <= 20; $i++) {
                            if (isset($parametros['nombre_' . $i]) == true){
                                if ($parametros["tipo_dato_$i"] == '8'){
                                    $valor_actual_aux = '';
                                    for($j = 1; $j <= $parametros["num_campo_$i"]; $j++){
                                        if (isset($parametros["campo_" . $i . "_" . $j]) == true){
                                            $valor_actual_aux .= $parametros["campo_" . $i . "_" . $j] . '<br/>';
                                        }
                                    }
                                    $valor_actual_aux = substr($valor_actual_aux, 0, strlen($valor_actual_aux) - 5);                                    
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    //$params[validacion] = $parametros["validacion_$i"];
                                    //$params[valores] = $parametros["valores_$i"];
                                    $params[Nombre] = $valor_actual_aux;
                                    //$params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $parametros[id];
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    $this->modificarRegistrosCampoDinamico($params);
                                    
                                }
                                else
                                {
                                    //$params[id_documento] = $id_documento;
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    //$params[validacion] = $parametros["validacion_$i"];
                                    //$params[valores] = $parametros["valores_$i"];                                    
                                    $params[Nombre] = $parametros["campo_". $i];
                                    $params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $parametros[id];
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    $this->modificarRegistrosCampoDinamico($params);
                                    
                                }
                                $nuevo .= " " . $parametros["nombre_$i"] . ": \'" . $params[Nombre] . "\'";
                            }
                        }
                        //$nuevo = "IdRegistro: \'".$this->dbl->data[0][0]."\', IDDoc: \'$_SESSION[IDDoc]\', Identificacion: \'$atr[identificacion]\',  Id Usuario: \'$atr[id_usuario]\', Descripcion: \'$atr[descripcion]\', ContentType: \'$atr[contentType]\', Id Procesos: \'$atr[id_procesos]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                        $this->registraTransaccionLog(8,$nuevo,'', '');
                        $objResponse->addScriptCall("MostrarContenidoAux");
                        $objResponse->addScriptCall('VerMensaje','exito',"Registro actualizado con exito");
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
                $val = $this->verRegistros($parametros[id]);
                $respuesta = $this->eliminarRegistros($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    $objResponse->addScriptCall("MostrarContenidoAux");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
            public function buscar($parametros)
            {
                $grid = $this->verListaRegistros($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('r-grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('r-grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
            
             public function buscar_reporte($parametros)
            {
                $grid = $this->verListaRegistrosReporte($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('r-grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('r-grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("init_tabla_reporte_reg();");                
                //$objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;                
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verRegistros($parametros[id]);

                            $contenido_1['IDREGISTRO'] = $val["idRegistro"];
            $contenido_1['IDDOC'] = $val["IDDoc"];
            $contenido_1['IDENTIFICACION'] = ($val["identificacion"]);
            $contenido_1['VERSION'] = $val["version"];
            $contenido_1['CORRELATIVO'] = $val["correlativo"];
            $contenido_1['ID_USUARIO'] = $val["id_usuario"];
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
            $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
            $contenido_1['ID_PROCESOS'] = $val["id_procesos"];
            $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
                $template->setTemplate("verRegistros");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Registros";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
            
       public function ver_visualiza($parametros)
            {
                $objResponse = new xajaxResponse();
                $archivo_aux = $this->verDocumentoPDF($parametros[id]);
                $contenido2 = $archivo_aux[doc_fisico];
                if (strlen($contenido2)>0){
                    
                    $html = "<a target=\"_blank\" title=\"Ver Documento PDF\"  href=\"pages/registros/descargar_archivo.php?id=$archivo_aux[idRegistro]&token=" . md5($archivo_aux[idRegistro]) ."&des=1\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";                
                                                                                  
                    $sql = "SELECT extension FROM mos_extensiones WHERE extension = '$archivo_aux[contentType]' OR contentType = '$archivo_aux[contentType]'";
                    $total_registros = $this->dbl->query($sql, $atr);
                    $Ext2 = $total_registros[0][extension];
                    $NombreDoc = $archivo_aux[identificacion].'.'.$Ext2;  
                    $NombreDoc = $archivo_aux['descripcion'];
                    $version = "HOJA_VIDA";
                    $Codigo = $Ext2 = "";
                    $carpeta =  $this->encryt->Decrypt_Text($_SESSION[BaseDato]);
                    $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);

                    $ruta_doc = $documento->ActivarDocumento();
                    $titulo_doc = $documento->getNombreArchivo();
                }
                
                $http = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
                $html = '<div class="content-panel panel">
                <div class="content">
                    <div class="info-container" style="height:90%;">
                        <a class="close-detail" href="#detail-content">
                            <i class="glyphicon glyphicon-remove"></i>
                        </a>
                        <div class="panel-heading">
                        
                            <div class="row">
                                <div id="div-titulo-mod" class="panel-title col-xs-16">
                                    '. $titulo_doc .'
                                </div>

                                <div class="panel-actions col-xs-8">
                                    <ul class="navbar">                                
                                        <li>'.'  
                                            '. $html .'
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        
                        
                        </div>
                    
                  
                        <div class="row"  style="height:100%;">
                            <!--<iframe src="'.$http.'://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;" frameborder="0"></iframe>-->
                            <iframe src="'.$ruta_doc.'" style="height:90%;width:100%;" frameborder="0"></iframe>
                        </div>
                        
                    </div>
              </div></div>';
                $objResponse->addAssign('detail-content-aux',"innerHTML",$html);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                //$objResponse->addScript("PanelOperator.initPanels('');");
                $objResponse->addScript("$('.close-detail').click(function (event) {
                        event.preventDefault();
                        PanelOperator.hideDetail('-aux');
                    })

                    $('.detail-show').click(function (event) {
                        event.preventDefault();
                        PanelOperator.showDetail('-aux');
                        PanelOperator.hideSearch('-aux');
                    });");
                $objResponse->addScript("PanelOperator.showDetail('-aux');");  
                $objResponse->addScript("PanelOperator.resize();");
                //$objResponse->addScript("init_ver_registros();");
                
                return $objResponse;
            }
     
 }?>