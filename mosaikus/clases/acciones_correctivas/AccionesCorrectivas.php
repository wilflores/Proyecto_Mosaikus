<?php
 import("clases.interfaz.Pagina");        
        class AccionesCorrectivas extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        public $nombres_columnas;
        private $placeholder;
        private $cod_categoria; //Guarda el codigo para los parametros dinamicos
        private $restricciones;
            
            public function AccionesCorrectivas(){
                parent::__construct();
                $this->asigna_script('acciones_correctivas/acciones_correctivas.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->nombres_columnas_ac = $this->placeholder = array();
                $this->contenido = array();
                $this->cod_categoria = 8;
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and modulo in (15,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            public function cargar_nombres_columnas_acciones(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and modulo = 16";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas_ac[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and modulo = 15";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }
            
            /**
         * Busca las areas relacionada al Documento de lista de distribucion
         * @param type $tupla
         * @return string
         */
        public function BuscaOrganizacional($tupla,$key='')
        {
            $Nivls = "";
                                                       
 
            $Resp3 = explode(',', $tupla[id_area]);//, $pieces)
            foreach ($Resp3 as $Fila3) 
            {                        
                $Nivls .= $this->arbol->BuscaOrganizacional(array('id_organizacion' => $tupla[id_organizacion]));
            }
            if($Nivls!='')
                    $Nivls=$Nivls;//;substr($Nivls,0,strlen($Nivls)-6);
            else
                    $Nivls='-- Sin información --';
            return $Nivls;
                        
            

        }
            
             public function colum_admin_arbol($tupla)
            {       
                 $html = "<a tok=\"". $tupla[id] . "\" class=\"ver-reporte\">
                                                <i style='cursor:pointer'  class=\"icon icon-view-document\" title=\"Ver Reporte ". $fila['id']."\"></i>
                                            </a>";
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                {   
                    switch ($tupla[estatus]) {
                        case 'en_buzon':
                        case 'sin_responsable_analisis':
                        case 'sin_plan_accion':
                        case 'en_elaboracion':
                        case 'implementacion_acciones':
                            $bandera = 0;
                            //SI EL USUARIO CONECTADO ES QUIEN REPORTO EL DESVIO
                            if ($tupla[reportado_por] == $_SESSION[CookCodEmp])
                                $bandera = 1;
                            //SI EL USUARIO CONECTADO ES RESPONSABLE DEL DESVIO
                            if ($tupla[responsable_desvio] == $_SESSION[CookCodEmp])
                                $bandera = 1;
                            //SI EL USUARIO CONECTADO ES RESPONSABLE DEL ANALISIS
                            if ($tupla[responsable_analisis] == $_SESSION[CookCodEmp])
                                $bandera = 1;
                            if ($bandera == 1)
                                $html .= "<a href=\"#\" onclick=\"javascript:editarAccionesCorrectivas('". $tupla[id] . "');\"  title=\"Editar Ocurrencia\">                            
                                    <i class=\"icon icon-edit\"></i>
                                </a>";

                            break;

                        case 'verificacion_eficacia':
                            $bandera = 0;
                            //SI EL USUARIO CONECTADO ES RESPONSABLE DEL DESVIO
                            if ($tupla[responsable_desvio] == $_SESSION[CookCodEmp])
                                $bandera = 1;
                            //SI EL USUARIO CONECTADO ES RESPONSABLE DEL VERIFICACION DE EFICACIA
                            if ($tupla[id_responsable_segui] == $_SESSION[CookCodEmp])
                                $bandera = 1;
                            if ($bandera == 1)
                                $html .= "<a href=\"#\" onclick=\"javascript:verificarAccionesCorrectivas('". $tupla[id] . "');\"  title=\"Editar Ocurrencia\">                            
                                    <i class=\"icon icon-edit\"></i>
                                </a>";
                            break;
                    }
                    
                
                    
                }
                if ($this->restricciones->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                {
                    switch ($tupla[estatus]) {
                        case 'en_buzon':
                        case 'sin_responsable_analisis':
                        case 'sin_plan_accion':
                        case 'en_elaboracion':
                        case 'implementacion_acciones':
                            $bandera = 0;
                            if ($tupla[reportado_por] == $_SESSION[CookCodEmp])
                                $bandera = 1;
                            if ($bandera == 1)
                                $html .= "<a href=\"#\" onclick=\"javascript:eliminarAccionesCorrectivas('". $tupla[id] . "');\" title=\"Eliminar Familias\">
                                    <i class=\"icon icon-remove\"></i>

                                </a>"; 

                            break;

                        default:
                            
                            break;
                    }
                    
                }
                
                return $html;
            }


     

             public function verAccionesCorrectivas($id){
                $atr=array(id=>$id);
                $atr = $this->dbl->corregir_parametros($atr);
                $sql = "SELECT ac.id
                            ,origen_hallazgo
                            ,DATE_FORMAT(fecha_generacion, '%d/%m/%Y') fecha_generacion
                            ,ac.descripcion
                            ,ac.descripcion_val
                            ,analisis_causal
                            ,responsable_analisis
                            ,ac.id_organizacion
                            ,id_proceso
                            ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada
                            ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada
                            ,DATE_FORMAT(fecha_realizada_temp, '%d/%m/%Y') fecha_realizada_temp
                            ,id_responsable_segui
                            ,alto_potencial
                            ,alto_potencial_val
                            ,o.descripcion origen
                            -- ,concat(initcap(p.nombres), ' ', initcap(p.apellido_paterno), ' ' , initcap(p.apellido_materno)) responsable_ana
                            ,CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno)) responsable_ana
                            -- ,concat(initcap(per.nombres), ' ', initcap(per.apellido_paterno), ' ' , initcap(per.apellido_materno)) responsable_segui
                            ,CONCAT(initcap(SUBSTR(per.nombres,1,IF(LOCATE(' ' ,per.nombres,1)=0,LENGTH(per.nombres),LOCATE(' ' ,per.nombres,1)-1))),' ',initcap(per.apellido_paterno)) responsable_segui
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
                            ,responsable_desvio
                            ,reportado_por
                            ,estatus
                            ,estado
                            ,CONCAT(initcap(SUBSTR(rp.nombres,1,IF(LOCATE(' ' ,rp.nombres,1)=0,LENGTH(rp.nombres),LOCATE(' ' ,rp.nombres,1)-1))),' ',initcap(rp.apellido_paterno))  reportado_por_aux                            
                            ,CONCAT(initcap(SUBSTR(rd.nombres,1,IF(LOCATE(' ' ,rd.nombres,1)=0,LENGTH(rd.nombres),LOCATE(' ' ,rd.nombres,1)-1))),' ',initcap(rd.apellido_paterno))  responsable_desvio_aux
                            ,(SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE mos_nombres_campos.nombre_campo = ac.estatus AND mos_nombres_campos.modulo = 15) as estatus_a
                            ,desc_verificacion
                         FROM mos_acciones_correctivas ac
                         INNER JOIN mos_origen_ac o ON o.id = origen_hallazgo
                         left JOIN mos_personal p ON p.cod_emp = responsable_analisis
                         LEFT JOIN mos_personal per ON per.cod_emp = id_responsable_segui
                         LEFT JOIN mos_personal rp ON rp.cod_emp = ac.reportado_por
			 LEFT JOIN mos_personal rd ON rd.cod_emp = ac.responsable_desvio
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
                    if (strlen($atr[responsable_analisis]) == 0){
                        $atr[responsable_analisis] = 'NULL';
                    }
                    if (strlen($atr[responsable_desvio]) == 0){
                        $atr[responsable_desvio] = 'NULL';
                    }
                    if (strlen($atr[estatus]) == ''){
                        $atr[estatus] = 'NULL';
                    }
                    else{
                        $atr[estatus] = "'$atr[estatus]'";
                    }
                    
                    
                    $sql = "INSERT INTO mos_acciones_correctivas(origen_hallazgo,fecha_generacion,descripcion,analisis_causal,responsable_analisis,id_organizacion,id_proceso,fecha_acordada,fecha_realizada,id_responsable_segui,alto_potencial
                        ,responsable_desvio,estatus,reportado_por,id_usuario)
                            VALUES(
                                $atr[origen_hallazgo],'$atr[fecha_generacion]','$atr[descripcion]','$atr[analisis_causal]',$atr[responsable_analisis],$atr[id_organizacion],$atr[id_proceso],$atr[fecha_acordada],$atr[fecha_realizada],$atr[id_responsable_segui], '$atr[alto_potencial]',$atr[responsable_desvio]
                                ,$atr[estatus],$atr[reportado_por], $atr[id_usuario]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*Obtenemos el id del nuevo registro*/
                    $sql = "SELECT MAX(id) ultimo FROM mos_acciones_correctivas"; 
                    $this->operacion($sql, $atr);
                    $id_new =  $this->dbl->data[0][0];
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
                    $this->registraTransaccionLog(60,$nuevo,'', $id_new);
                    return $id_new;
                    
                    
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
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip, id_registro) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]', $id)";            
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarAccionesCorrectivas($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $val = $this->verAccionesCorrectivas($atr[id]);
                    $sql_set = '';
                    //BANDERA PARA ACTUALIZAR PARAMETROS
                    $actualizar_parametros = 0;
                    $actualizar_evidencias = 0;
                    $actualizar_acciones = 0;
                    switch ($val[estatus]) {
                    case 'en_elaboracion':
                    case 'en_buzon':
                        if (($atr['notificar'] == 'si')) 
                        {
                            //SE VALIDA SI TIENE AL MENOS UNA ACCION DEFINIDA
                            $bandera = 0;
                            for($i=1;$i <= $atr[num_items_esp] * 1; $i++){                              
                                if (isset($atr["tipo_$i"])){                                                                
                                    $bandera = 1;
                                    break;
                                }
                            }
                            /*SI TIENE UNA ACCION DEFINIDA EL ESTATUS ES IMPLEMENTACION DE ACCIONES*/
                            if ($bandera == 1) $atr[estatus] = "'implementacion_acciones'";
                            else $atr[estatus] = 'NULL';
                        }
                        ELSE $atr[estatus] = 'estatus';
                        /*VALIDAMOS LOS DATOS*/
                        $atr[id_proceso] = (strlen($atr[id_proceso]) == 0) ? 'NULL' : $atr[id_proceso];
                        $atr[id_organizacion] = (strlen($atr[id_organizacion]) == 0) ? 'NULL' : $atr[id_organizacion];
                        $atr[fecha_acordada] = (strlen($atr[fecha_acordada]) == 0) ? 'NULL' : "'$atr[fecha_acordada]'";                                        
                        $atr[fecha_realizada] = (strlen($atr[fecha_realizada]) == 0) ? 'NULL' : "'$atr[fecha_realizada]'";                                        
                        $atr[id_responsable_segui] = (strlen($atr[id_responsable_segui]) == 0) ? 'NULL' : $atr[id_responsable_segui];                                        
                        $atr[responsable_analisis] = (strlen($atr[responsable_analisis]) == 0) ? 'NULL' : $atr[responsable_analisis];                    
                        $atr[responsable_desvio] = (strlen($atr[responsable_desvio]) == 0) ? 'NULL' : $atr[responsable_desvio];                                        
                        $atr[estatus] = (strlen($atr[estatus]) == '') ? 'NULL' : $atr[estatus];
                        //
                        $sql_set = "origen_hallazgo = '$atr[origen_hallazgo]',fecha_generacion = '$atr[fecha_generacion]',descripcion = '$atr[descripcion]'"
                                . ",analisis_causal = '$atr[analisis_causal]'"
                                . ",responsable_desvio = $atr[responsable_desvio],responsable_analisis = $atr[responsable_analisis],id_organizacion = $atr[id_organizacion]"
                                . ",id_proceso = $atr[id_proceso],fecha_acordada = $atr[fecha_acordada],fecha_realizada = $atr[fecha_realizada]"
                                . ",id_responsable_segui = $atr[id_responsable_segui]
                                        , alto_potencial = '$atr[alto_potencial]', alto_potencial_val = '$atr[alto_potencial_val]',descripcion_val = '$atr[descripcion_val]', id_usuario =  $atr[id_usuario]";
                        $sql_set .= ",estatus = $atr[estatus]";
                        $actualizar_parametros = 1;
                        $actualizar_acciones = 1;
                        $actualizar_evidencias = 1;
                        $nuevo = "Origen Hallazgo: \'$atr[origen_hallazgo]\', Fecha Generacion: \'$atr[fecha_generacion]\', Descripcion: \'$atr[descripcion]\', Analisis Causal: \'$atr[analisis_causal]\', Responsable Analisis: \'$atr[responsable_analisis]\', Id Organizacion: \'$atr[id_organizacion]\', Id Proceso: \'$atr[id_proceso]\',Id Responsable Segui: \'$atr[id_responsable_segui]\',Alto Potencial: \'$atr[alto_potencial]\',Responsable Desvio: \'$atr[id_responsable_desvio]\' ";
                        $anterior = "Origen Hallazgo: \'$val[origen_hallazgo]\', Fecha Generacion: \'$val[fecha_generacion]\', Descripcion: \'$val[descripcion]\', Analisis Causal: \'$val[analisis_causal]\', Responsable Analisis: \'$val[responsable_analisis]\', Id Organizacion: \'$val[id_organizacion]\', Id Proceso: \'$val[id_proceso]\', Id Responsable Segui: \'$val[id_responsable_segui]\',Alto Potencial: \'$atr[alto_potencial]\',Responsable Desvio: \'$atr[id_responsable_desvio]\' ";
                        break;
                                            
                    case 'sin_responsable_analisis':                        
                        $sql_set = "id_usuario =  $atr[id_usuario]";
                        //SI EL USUARIO CONECTADO ES EL MISMO QUE REPORTO PUEDE EDITAR CAMPOS
                        if (($_SESSION[CookCodEmp] == $val[reportado_por])){
                             $atr[id_organizacion] = (strlen($atr[id_organizacion]) == 0) ? 'NULL' : $atr[id_organizacion];
                             $atr[responsable_desvio] = (strlen($atr[responsable_desvio]) == 0) ? 'NULL' : $atr[responsable_desvio];   
                             $sql_set .= ",origen_hallazgo = '$atr[origen_hallazgo]',fecha_generacion = '$atr[fecha_generacion]',descripcion = '$atr[descripcion]'"                                
                                . ",responsable_desvio = $atr[responsable_desvio],id_organizacion = $atr[id_organizacion]"
                                . ",alto_potencial = '$atr[alto_potencial]'";                           
                             $nuevo = "Origen Hallazgo: \'$atr[origen_hallazgo]\', Fecha Generacion: \'$atr[fecha_generacion]\', Descripcion: \'$atr[descripcion]\', Analisis Causal: \'$atr[analisis_causal]\', Id Organizacion: \'$atr[id_organizacion]\' ,Alto Potencial: \'$atr[alto_potencial]\',Responsable Desvio: \'$atr[id_responsable_desvio]\' ";
                             $anterior = "Origen Hallazgo: \'$val[origen_hallazgo]\', Fecha Generacion: \'$val[fecha_generacion]\', Descripcion: \'$val[descripcion]\', Analisis Causal: \'$val[analisis_causal]\', Id Organizacion: \'$val[id_organizacion]\', Alto Potencial: \'$atr[alto_potencial]\',Responsable Desvio: \'$atr[id_responsable_desvio]\' ";
                             //$actualizar_parametros = 1;
                             $actualizar_evidencias = 1;

                        }
                        //SI EL USUARIO CONECTADO ES EL MISMO RESPONSABLE DEL DESVIO PUEDE EDITAR EL RESPONSABLE DE ANALISIS
                        if (
                                ((strlen($atr[responsable_desvio])== 0) && ($_SESSION[CookCodEmp] == $val[responsable_desvio]) && (strlen($val[responsable_desvio])>0))
                                || (($_SESSION[CookCodEmp] == $atr[responsable_desvio]) && strlen($atr[responsable_desvio])>0))
                        {
                            $atr[responsable_analisis] = (strlen($atr[responsable_analisis]) == 0) ? 'NULL' : $atr[responsable_analisis];  
                            $atr[id_responsable_segui] = (strlen($atr[id_responsable_segui]) == 0) ? 'NULL' : $atr[id_responsable_segui];   
                            $sql_set .= ",responsable_analisis = $atr[responsable_analisis], id_responsable_segui = $atr[id_responsable_segui]";
                            $nuevo = "Responsable Analisis: \'$atr[responsable_analisis]\',Id Responsable Segui: \'$atr[id_responsable_segui]\'";
                            $anterior = "Responsable Analisis: \'$val[responsable_analisis]\',Id Responsable Segui: \'$val[id_responsable_segui]\'";

                        }
                        //SI EL USUARIO CONECTADO ES EL MISMO RESPONSABLE DE ANALISIS PUEDE EDITAR LOS PARAMETROS, PROCESO, ANALISIS DE CAUSAS
                        if (
                                ((strlen($atr[responsable_analisis])== 0) && ($_SESSION[CookCodEmp] == $val[responsable_analisis]) && (strlen($val[responsable_analisis])>0))
                                || (($_SESSION[CookCodEmp] == $atr[responsable_analisis]) && strlen($atr[responsable_analisis])>0))
                        {
                            $atr[id_proceso] = (strlen($atr[id_proceso]) == 0) ? 'NULL' : $atr[id_proceso];
                            $sql_set .= ",analisis_causal = '$atr[analisis_causal]'"                                
                                      . ",id_proceso = $atr[id_proceso], alto_potencial_val = '$atr[alto_potencial_val]',descripcion_val = '$atr[descripcion_val]'";
                            $nuevo = "Analisis Causal: \'$atr[analisis_causal]\',  Id Proceso: \'$atr[id_proceso]\', Alto Potencial Val: \'$atr[alto_potencial_val]\', descripcion_val: \'$atr[descripcion_val]\'";
                            $anterior = "Analisis Causal: \'$val[analisis_causal]\',Id Proceso: \'$val[id_proceso]\', Alto Potencial Val: \'$atr[alto_potencial_val]\', descripcion_val: \'$atr[descripcion_val]\'";

                            $actualizar_parametros = 1;
                            $actualizar_acciones = 1;
                        }
                        if (($atr['notificar'] == 'si')) 
                        {
                            //SE VALIDA SI TIENE AL MENOS UNA ACCION DEFINIDA
                            $bandera = 0;
                            for($i=1;$i <= $atr[num_items_esp] * 1; $i++){                              
                                if (isset($atr["tipo_$i"])){                                                                
                                    $bandera = 1;
                                    break;
                                }
                            }
                            /*SI TIENE UNA ACCION DEFINIDA EL ESTATUS ES IMPLEMENTACION DE ACCIONES*/
                            if ($bandera == 1) $atr[estatus] = "'implementacion_acciones'";
                            else $atr[estatus] = 'NULL';
                            $sql_set .= ",estatus = $atr[estatus]";
                        }
                        break;
                    case 'sin_plan_accion':
                        $sql_set = "id_usuario =  $atr[id_usuario]";
                        //SI EL USUARIO CONECTADO ES EL MISMO RESPONSABLE DEL DESVIO PUEDE EDITAR EL RESPONSABLE DE ANALISIS
                        if (
                                ((strlen($atr[responsable_desvio])== 0) && ($_SESSION[CookCodEmp] == $val[responsable_desvio]) && (strlen($val[responsable_desvio])>0))
                                || (($_SESSION[CookCodEmp] == $atr[responsable_desvio]) && strlen($atr[responsable_desvio])>0))
                        {
                            $atr[responsable_analisis] = (strlen($atr[responsable_analisis]) == 0) ? 'NULL' : $atr[responsable_analisis];  
                            $atr[id_responsable_segui] = (strlen($atr[id_responsable_segui]) == 0) ? 'NULL' : $atr[id_responsable_segui];   
                            $sql_set .= ",responsable_analisis = $atr[responsable_analisis], id_responsable_segui = $atr[id_responsable_segui]";
                            $nuevo = "Responsable Analisis: \'$atr[responsable_analisis]\',Id Responsable Segui: \'$atr[id_responsable_segui]\'";
                            $anterior = "Responsable Analisis: \'$val[responsable_analisis]\',Id Responsable Segui: \'$val[id_responsable_segui]\'";
                        }
                        //SI EL USUARIO CONECTADO ES EL MISMO RESPONSABLE DE ANALISIS PUEDE EDITAR LOS PARAMETROS, PROCESO, ANALISIS DE CAUSAS
                        if (
                                ((strlen($atr[responsable_analisis])== 0) && ($_SESSION[CookCodEmp] == $val[responsable_analisis]) && (strlen($val[responsable_analisis])>0))
                                || (($_SESSION[CookCodEmp] == $atr[responsable_analisis]) && strlen($atr[responsable_analisis])>0))
                        {
                            $atr[id_proceso] = (strlen($atr[id_proceso]) == 0) ? 'NULL' : $atr[id_proceso];
                            $sql_set .= ",analisis_causal = '$atr[analisis_causal]'"                                
                                      . ",id_proceso = $atr[id_proceso], alto_potencial_val = '$atr[alto_potencial_val]',descripcion_val = '$atr[descripcion_val]'";
                            $nuevo = "Analisis Causal: \'$atr[analisis_causal]\',  Id Proceso: \'$atr[id_proceso]\', Alto Potencial Val: \'$atr[alto_potencial_val]\', descripcion_val: \'$atr[descripcion_val]\'";
                            $anterior = "Analisis Causal: \'$val[analisis_causal]\',Id Proceso: \'$val[id_proceso]\', Alto Potencial Val: \'$atr[alto_potencial_val]\', descripcion_val: \'$atr[descripcion_val]\'";

                            $actualizar_parametros = 1;
                            $actualizar_acciones = 1;
                        }
                        if (($atr['notificar'] == 'si')) 
                        {
                            //SE VALIDA SI TIENE AL MENOS UNA ACCION DEFINIDA
                            $bandera = 0;
                            for($i=1;$i <= $atr[num_items_esp] * 1; $i++){                              
                                if (isset($atr["tipo_$i"])){                                                                
                                    $bandera = 1;
                                    break;
                                }
                            }
                            /*SI TIENE UNA ACCION DEFINIDA EL ESTATUS ES IMPLEMENTACION DE ACCIONES*/
                            if ($bandera == 1) $atr[estatus] = "'implementacion_acciones'";
                            else return  '- Debe ingresar al menos una acción.';
                            $sql_set .= ",estatus = $atr[estatus]";
                        }
                        break;
                    case 'implementacion_acciones':
                        $sql_set = "id_usuario =  $atr[id_usuario]";
                        //SI EL USUARIO CONECTADO ES EL MISMO RESPONSABLE DEL DESVIO PUEDE EDITAR EL RESPONSABLE DE ANALISIS
                        if (
                                ((strlen($atr[responsable_desvio])== 0) && ($_SESSION[CookCodEmp] == $val[responsable_desvio]) && (strlen($val[responsable_desvio])>0))
                                || (($_SESSION[CookCodEmp] == $atr[responsable_desvio]) && strlen($atr[responsable_desvio])>0))
                        {
                            
                            $atr[responsable_analisis] = (strlen($atr[responsable_analisis]) == 0) ? 'NULL' : $atr[responsable_analisis];  
                            $atr[id_responsable_segui] = (strlen($atr[id_responsable_segui]) == 0) ? 'NULL' : $atr[id_responsable_segui];   
                            $sql_set .= ",responsable_analisis = $atr[responsable_analisis], id_responsable_segui = $atr[id_responsable_segui]";
                            $nuevo = "Responsable Analisis: \'$atr[responsable_analisis]\',Id Responsable Segui: \'$atr[id_responsable_segui]\'";
                            $anterior = "Responsable Analisis: \'$val[responsable_analisis]\',Id Responsable Segui: \'$val[id_responsable_segui]\'";
                        }
                        //SI EL USUARIO CONECTADO ES EL MISMO RESPONSABLE DE ANALISIS PUEDE EDITAR LOS PARAMETROS, PROCESO, ANALISIS DE CAUSAS
                        if (
                                ((strlen($atr[responsable_analisis])== 0) && ($_SESSION[CookCodEmp] == $val[responsable_analisis]) && (strlen($val[responsable_analisis])>0))
                                || (($_SESSION[CookCodEmp] == $atr[responsable_analisis]) && strlen($atr[responsable_analisis])>0))
                        {
                            $atr[id_proceso] = (strlen($atr[id_proceso]) == 0) ? 'NULL' : $atr[id_proceso];
                            $sql_set .= ",analisis_causal = '$atr[analisis_causal]'"                                
                                      . ",id_proceso = $atr[id_proceso], alto_potencial_val = '$atr[alto_potencial_val]',descripcion_val = '$atr[descripcion_val]'";
                            $nuevo = "Analisis Causal: \'$atr[analisis_causal]\',  Id Proceso: \'$atr[id_proceso]\', Alto Potencial Val: \'$atr[alto_potencial_val]\', descripcion_val: \'$atr[descripcion_val]\'";
                            $anterior = "Analisis Causal: \'$val[analisis_causal]\',Id Proceso: \'$val[id_proceso]\', Alto Potencial Val: \'$atr[alto_potencial_val]\', descripcion_val: \'$atr[descripcion_val]\'";

                            $actualizar_parametros = 1;
                            $actualizar_acciones = 1;
                        }
                        if (($atr['notificar'] == 'si')) 
                        {
                            //SE VALIDA SI TIENE AL MENOS UNA ACCION DEFINIDA
                            $bandera = 0;
                            for($i=1;$i <= $atr[num_items_esp] * 1; $i++){                              
                                if (isset($atr["tipo_$i"])){                                                                
                                    $bandera = 1;
                                    break;
                                }
                            }
                            /*SI TIENE UNA ACCION DEFINIDA EL ESTATUS ES IMPLEMENTACION DE ACCIONES*/
                            if ($bandera == 1) $atr[estatus] = "'implementacion_acciones'";
                            else return  '- Debe ingresar al menos una acción.';
                            //$sql_set .= ",estatus = $atr[estatus]";
                        }
                        break;
                    default:
                        break;
                }
                    
                    $sql = "UPDATE mos_acciones_correctivas SET                            
                                    $sql_set
                            WHERE  id = $atr[id]"; 
                    //echo $sql;
                    if ($actualizar_parametros == 1){
                        /*PARAMETROS DINAMICOS*/
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($atr, 8);
                    }
                    if ($actualizar_evidencias == 1){
                        /* EVIDENCIAS ADJUNTADAS*/
                        if(!class_exists('ArchivosAdjuntos')){
                            import("clases.utilidades.ArchivosAdjuntos");
                        }
                        $adjuntos = new ArchivosAdjuntos();
                        $atr[tabla] = 'mos_acciones_evidencia';
                        $atr[clave_foranea] = 'fk_id_accion_c';
                        $atr[valor_clave_foranea] = $atr[id];
                        $adjuntos->guardar($atr);
                        /*FIN EVIDENNCIAS*/
                    }
                    
                    if ($actualizar_acciones == 1){    
                        $params = array();
                        $params[id_ac] = $respuesta;
                        //IMPORTAMOS CLASE de ACCIONES
                        import('clases.acciones_ac.AccionesAC');
                        $acciones_ac = new AccionesAC();
                        if (strlen($atr[id_unico_del])>0){
                            $atr[id_unico_del] = substr($atr[id_unico_del], 0, strlen($atr[id_unico_del]) - 1);
                            $sql = "DELETE FROM mos_acciones_ac_co WHERE id IN ($atr[id_unico_del]) and id_ac = $atr[id]"
                                . " AND NOT id IN (SELECT id_accion FROM mos_acciones_trazabilidad where id_accion IN ($atr[id_unico_del])) ";      
                            //echo $sql;
                            $this->dbl->insert_update($sql);
                        }
                        for($i=1;$i <= $atr[num_items_esp] * 1; $i++){                              
                            //GUARDAMOS LAS ACCIONES VALIDAS
                            if (!isset($atr["id_unico_din_$i"])){ 
                                if (isset($atr["tipo_$i"])){
                                    $params[tipo] = $atr["tipo_$i"];
                                    $params[accion] = $atr["accion_$i"];                                        
                                    $params[id_responsable] = $atr["responsable_acc_$i"];                                
                                    $params[fecha_acordada] = formatear_fecha($atr["fecha_acordada_$i"]);
                                    $params[orden] = $atr["orden_din_$i"];     
                                    $params[id_ac] = $atr[id];
                                    $params[id_validador] = $atr["validador_acc_$i"]; 
                                    $acciones_ac->ingresarAccionesAC($params);                                                               
                                }
                            }else{
                                if (isset($atr["tipo_$i"])){
                                    $params[tipo] = $atr["tipo_$i"];
                                    $params[accion] = $atr["accion_$i"];                                        
                                    $params[id_responsable] = $atr["responsable_acc_$i"];                                
                                    $params[fecha_acordada] = formatear_fecha($atr["fecha_acordada_$i"]);
                                    $params[orden] = $atr["orden_din_$i"];    
                                    $params[id] = $atr["id_unico_din_$i"]; 
                                    $params[id_validador] = $atr["validador_acc_$i"]; 
                                    $acciones_ac->modificarAccionesAC($params);
                                }
                            }
                        }
                    }
                    
                    $this->dbl->insert_update($sql);
                    //$nuevo = "Origen Hallazgo: \'$atr[origen_hallazgo]\', Fecha Generacion: \'$atr[fecha_generacion]\', Descripcion: \'$atr[descripcion]\', Analisis Causal: \'$atr[analisis_causal]\', Responsable Analisis: \'$atr[responsable_analisis]\', Id Organizacion: \'$atr[id_organizacion]\', Id Proceso: \'$atr[id_proceso]\'Id Responsable Segui: \'$atr[id_responsable_segui]\', ";
                    //$anterior = "Origen Hallazgo: \'$val[origen_hallazgo]\', Fecha Generacion: \'$val[fecha_generacion]\', Descripcion: \'$val[descripcion]\', Analisis Causal: \'$val[analisis_causal]\', Responsable Analisis: \'$val[responsable_analisis]\', Id Organizacion: \'$val[id_organizacion]\', Id Proceso: \'$val[id_proceso]\', Id Responsable Segui: \'$val[id_responsable_segui]\', ";
                    $this->registraTransaccionLog(61,$nuevo,$anterior, $atr[id]);
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
            
            public function modificarVerificacionAccionesCorrectivas($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $val = $this->verAccionesCorrectivas($atr[id]);
                    $sql_set = '';
                    //BANDERA PARA ACTUALIZAR PARAMETROS
                    $actualizar_parametros = 0;
                    $actualizar_evidencias = 0;
                    $actualizar_acciones = 0;
                    switch ($val[estatus]) {
                    case 'verificacion_eficacia':  
                        $sql_set = "id_usuario =  $atr[id_usuario]";
                        if (($_SESSION[CookCodEmp] == $val[id_responsable_segui]))
                        {   
                            
                            if (($atr['notificar'] == 'si'))                             
                            {                                
                                $atr[fecha_realizada] = (strlen($atr[fecha_realizada]) == 0) ? 'NULL' : "'$atr[fecha_realizada]'";  
                            }
                            ELSE{ 
                                $atr[fecha_realizada] = 'NULL';
                                //SI TIENE FECHA REALIZADA
                                if (strlen($atr[fecha_realizada_temp])>0)
                                    $sql_set .= ",fecha_realizada_temp = '$atr[fecha_realizada_temp]'";
                            }
                            $atr[fecha_acordada] = (strlen($atr[fecha_acordada]) == 0) ? 'NULL' : "'$atr[fecha_acordada]'";                                        
                            //$atr[fecha_realizada] = (strlen($atr[fecha_realizada]) == 0) ? 'NULL' : "'$atr[fecha_realizada]'";
                            $sql_set .= ",fecha_acordada = $atr[fecha_acordada],fecha_realizada = $atr[fecha_realizada],desc_verificacion = '$atr[desc_verificacion]'";
                            if ($atr[fecha_acordada] != 'NULL'){
                                $atr[fecha_acordada] = "\\" . substr($atr[fecha_acordada], 0, strlen($atr[fecha_acordada])-1) . "\\";
                            }
                            if ($atr[fecha_realizada] != 'NULL'){
                                $atr[fecha_realizada] = "\\" . substr($atr[fecha_realizada], 0, strlen($atr[fecha_realizada])-1)  . "\'";
                            }
                            $nuevo = "Fecha Acordada: $atr[fecha_acordada], Fecha Realizada: $atr[fecha_realizada], Desc Verificacion: \'$atr[desc_verificacion]\', ";                           
                            $anterior = "Fecha Acordada: $val[fecha_acordada], Fecha Realizada: $val[fecha_realizada], Desc Verificacion: \'$val[desc_verificacion]\', ";
                            $actualizar_evidencias = 1;
                        }
                        
                        if (($_SESSION[CookCodEmp] == $val[responsable_desvio])){
                            $sql_set .= ",id_responsable_segui = $atr[id_responsable_segui]";
                            $nuevo = "Responsable Segui: \'$atr[id_responsable_segui]\'";
                            $anterior = "Responsable Segui: \'$val[id_responsable_segui]\'";

                        }
                        break;
                    default:
                        break;
                }
                    
                    $sql = "UPDATE mos_acciones_correctivas SET                            
                                    $sql_set
                            WHERE  id = $atr[id]"; 
                    
                    if ($actualizar_evidencias == 1){
                        /* EVIDENCIAS ADJUNTADAS*/
                        if(!class_exists('ArchivosAdjuntos')){
                            import("clases.utilidades.ArchivosAdjuntos");
                        }
                        $adjuntos = new ArchivosAdjuntos();
                        $atr[tabla] = 'mos_acciones_evidencia';
                        $atr[clave_foranea] = 'fk_id_accion_c_ver';
                        $atr[valor_clave_foranea] = $atr[id];
                        $adjuntos->guardar($atr);
                        /*FIN EVIDENNCIAS*/
                    }
                    
                    
                    
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $this->registraTransaccionLog(61,$nuevo,$anterior, $atr[id]);
                                        
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
                    
                    /*PARAMETROS DINAMICOS*/
                    if(!class_exists('Parametros')){
                        import("clases.parametros.Parametros");
                    } 
                    if (count($this->parametros) <= 0){
                        $this->cargar_parametros($this->cod_categoria);
                    }                                       

                    $k = 1;
                    $campos_dinamicos = new Parametros();
                    $valores = $campos_dinamicos->ArmaSqlParamsDinamicos($this->cod_categoria,$k,$this->parametros,'ac.id');
                    $sql_left = $valores[sql_left];
                    $sql_col_left = $valores[sql_col_left];
                    $k = $valores[k];
                    $id_org ='';
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){                             
                        $id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);                        
                    }
                    
                    /*FILTRO SQL*/
                    $sql_where = '';
                    if (strlen($atr[valor])>0)
                        $sql_where .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                    if (strlen($atr["b-origen_hallazgo"])>0)
                        $sql_where .= " AND upper(origen_hallazgo) like '%" . strtoupper($atr["b-origen_hallazgo"]) . "%'";
                    /*PARAMETROS DEL REPORTE DE AC*/
                    $sql_where_pac = '';
                    if (isset($atr["b-filtro-fecha"])){
                        if ($atr["b-filtro-fecha"] == '2')
                        {            
                            $atr['b-fecha_generacion-desde'] = $atr["b-f-desde"];
                        }
                        else{
                            switch ($atr[tipo_data]) {
                                case 'YTD':
                                case 'MES':
                                case 'QUARTIL':
                                case 'SEM':
                                case 'SEMANAL':
                                    $atr['b-fecha_generacion-desde'] = "01/01/".date('Y');   
                                    
                                    //echo $nuevafecha;
                                    break;
                                default:
                                    $nuevafecha = strtotime ( '-1 year' , strtotime ( date('Y-m-j') ) ) ;
                                    $atr['b-fecha_generacion-desde'] = date ( 'd/m/Y' , $nuevafecha );                                    
                                    break;
                            }
                        } 
                        $sql_where_pac .= " OR fecha_realizada is null";
                    }
                    /*FIN PARAMETROS REPORTE AC*/
                    if (strlen($atr['b-fecha_generacion-desde'])>0)                        
                    {
                        $atr['b-fecha_generacion-desde'] = formatear_fecha($atr['b-fecha_generacion-desde']);                        
                        $sql_where .= " AND (fecha_generacion >= '" . ($atr['b-fecha_generacion-desde']) . "' $sql_where_pac)";    
                        
                    }
                    if (strlen($atr['b-fecha_generacion-hasta'])>0)                        
                    {
                        $atr['b-fecha_generacion-hasta'] = formatear_fecha($atr['b-fecha_generacion-hasta']);                        
                        $sql_where .= " AND fecha_generacion <= '" . ($atr['b-fecha_generacion-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-descripcion"])>0)
                        $sql_where .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-analisis_causal"])>0)
                        $sql_where .= " AND upper(analisis_causal) like '%" . strtoupper($atr["b-analisis_causal"]) . "%'";
                    if (strlen($atr["b-responsable_analisis"])>0)
                        $sql_where .= " AND responsable_analisis = '". $atr["b-responsable_analisis"] . "'";
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2"))
                        $sql_where .= " AND ac.id_organizacion IN (". $id_org . ")";
                    if (strlen($atr["b-id_proceso"])>0)
                        $sql_where .= " AND id_proceso = '". $atr["b-id_proceso"] . "'";
                    if (strlen($atr["b-alto_potencial"])>0)
                        $sql_where .= " AND alto_potencial = '". $atr["b-alto_potencial"] . "'";
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
                    if (strlen($atr["b-id_responsable_segui"])>0)
                        $sql_where .= " AND id_responsable_segui = '". $atr["b-id_responsable_segui"] . "'";
                    /*FIN FILTRO SQL*/
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_acciones_correctivas 
                         WHERE 1 = 1 $sql_where ";
                    

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT ac.id
                                    ,estado
                                    ,(SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE id_idioma=$_SESSION[CookIdIdioma] and mos_nombres_campos.nombre_campo = ac.estatus AND mos_nombres_campos.modulo = 15) as estatus_a
                                    ,ac.id as id_2
                                    ,alto_potencial
                                    ,oac.descripcion origen_hallazgo
                                    ,DATE_FORMAT(fecha_generacion, '%d/%m/%Y') fecha_generacion_a
                                    ,ac.descripcion
                                    ,ac.reportado_por
				    ,CONCAT(initcap(SUBSTR(rp.nombres,1,IF(LOCATE(' ' ,rp.nombres,1)=0,LENGTH(rp.nombres),LOCATE(' ' ,rp.nombres,1)-1))),' ',initcap(rp.apellido_paterno))  reportado_por_aux
                                    ,ac.responsable_desvio
                                    ,CONCAT(initcap(SUBSTR(rd.nombres,1,IF(LOCATE(' ' ,rd.nombres,1)=0,LENGTH(rd.nombres),LOCATE(' ' ,rd.nombres,1)-1))),' ',initcap(rd.apellido_paterno))  responsable_desvio_aux
                                    ,ac.responsable_analisis
				    ,CONCAT(initcap(SUBSTR(per.nombres,1,IF(LOCATE(' ' ,per.nombres,1)=0,LENGTH(per.nombres),LOCATE(' ' ,per.nombres,1)-1))),' ',initcap(per.apellido_paterno))  responsable_analisis_aux
                                    ,analisis_causal
                                    
                                    $sql_col_left
                                    ,ac.id_organizacion
                                    ,ac.id_proceso
                                    ,DATE_FORMAT(fecha_acordada, '%d/%m/%Y') fecha_acordada_a
                                    ,DATE_FORMAT(fecha_realizada, '%d/%m/%Y') fecha_realizada_a
                                    ,id_responsable_segui
                                    ,CONCAT(initcap(SUBSTR(rs.nombres,1,IF(LOCATE(' ' ,rs.nombres,1)=0,LENGTH(rs.nombres),LOCATE(' ' ,rs.nombres,1)-1))),' ',initcap(rs.apellido_paterno))  id_responsable_segui_aux
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
                                        ,estatus
                            FROM mos_acciones_correctivas ac
                            INNER JOIN mos_origen_ac oac ON oac.id = ac.origen_hallazgo 
                            
                            LEFT JOIN mos_personal rp ON rp.cod_emp = ac.reportado_por
			    LEFT JOIN mos_personal rd ON rd.cod_emp = ac.responsable_desvio
                            LEFT JOIN mos_personal per ON per.cod_emp = ac.responsable_analisis
                            LEFT JOIN mos_personal rs ON rs.cod_emp = ac.id_responsable_segui
                            $sql_left
                            WHERE 1 = 1 $sql_where ";
                    /*
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                if (strlen($atr["b-origen_hallazgo"])>0)
                        $sql .= " AND upper(origen_hallazgo) like '%" . strtoupper($atr["b-origen_hallazgo"]) . "%'";
                    
                    if (strlen($atr['b-fecha_generacion-desde'])>0)                        
                    {
                        //$atr['b-fecha_generacion-desde'] = formatear_fecha($atr['b-fecha_generacion-desde']);                        
                        $sql .= " AND (fecha_generacion >= '" . ($atr['b-fecha_generacion-desde']) . "' $sql_where_pac )";                        
                    }
                    if (strlen($atr['b-fecha_generacion-hasta'])>0)                        
                    {
                        //$atr['b-fecha_generacion-hasta'] = formatear_fecha($atr['b-fecha_generacion-hasta']);                        
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
                    if (strlen($atr["b-alto_potencial"])>0)
                        $sql .= " AND alto_potencial = '". $atr["b-alto_potencial"] . "'";
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
                        */
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
             
     public function semaforo_estado($tupla, $key){
                //,cantidad_evidencia    
         /*,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada*/
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
                    //$html .= '<font color="#006600">'." ".str_pad(abs($tupla["dias"]) ,4,0,STR_PAD_LEFT).'</font>';  
                    $html .= '<font color="#006600">'." ".($tupla["estatus_a"]).'</font>';
                }
                else{
                    //$html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias"]) ,4,0,STR_PAD_LEFT).'</font>';
                    $html .=  '<font color="#FF0000">'." ".($tupla["estatus_a"]).'</font>';
                }
            }
            else{
                //$html .=  '<font color="#FF0000">'." ".str_pad(abs($tupla["dias"]) ,4,0,STR_PAD_LEFT).'</font>';
                $html .=  '<font color="#FF0000">'." ".($tupla["estatus_a"]).'</font>';
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
        
        public function semaforo_estado_ver($tupla, $key){
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
        
        public function formatear_descripcion($tupla,$key){
            if (strlen($tupla[descripcion])>200)
                return substr($tupla[descripcion], 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[id]. '-des" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[id]. '-des" value="'.$tupla[descripcion].'"/>
                    </a>';
            return $tupla[descripcion];
        }
        
        public function formatear_analisis_causal($tupla,$key){
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
                //print_r($data);
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblAccionesCorrectivas", "");
                $config_col=array(
                    array( "width"=>"5%","ValorEtiqueta"=>"<div style='width:75px'>&nbsp;</div>"),  
                    
                    array( "width"=>"6%","ValorEtiqueta"=>"Estado"), 
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estatus], "estatus", $parametros)),  
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id], "id_2", $parametros)),     
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[alto_potencial], "alto_potencial", $parametros)),     
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[origen_hallazgo], "origen_hallazgo", $parametros)),
                    array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_generacion], "fecha_generacion", $parametros)),
                    array( "width"=>"25%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros,250)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[reportado_por], "reportado_por", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[reportado_por], "reportado_por", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[responsable_desvio], "responsable_desvio", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[responsable_desvio], "responsable_desvio", $parametros)),
                    
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[responsable_analisis], "responsable_analisis", $parametros)),
                    array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[responsable_analisis], "responsable_analisis", $parametros)),
                    array( "width"=>"25%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[analisis_causal], "analisis_causal", $parametros,250)),
                    
                    
                    );;
                    
              if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
              $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }
                
                
               
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros,250)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_proceso], "id_proceso", $parametros)));
               
               /*if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
                 ANTERIOR COMENTADO 
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Id Accion"));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[trazabilidad], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[tipo], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>'<div style="width:250px;height: 20px;">'.htmlentities($this->nombres_columnas_ac[accion], ENT_QUOTES, "UTF-8").'</div>'));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_acordada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[fecha_realizada], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[id_responsable], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas_ac[estado_seguimiento], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
               /*FIN */
                
               //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>($this->nombres_columnas[trazabilidad])));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_acordada], "fecha_acordada", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_realizada], "fecha_realizada", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable_segui], "id_responsable_segui", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_responsable_segui], "id_responsable_segui", $parametros)));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[estado_seguimiento], ENT_QUOTES, "UTF-8")));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
               array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Estatus"));
               
               //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>"Dias"));
                
                

                $func= array();

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 12;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver AccionesCorrectivas'>"));
                
                
                    if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                        array_push($func,array('nombre'=> 'editarAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar AccionesCorrectivas'>"));
                    if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                        array_push($func,array('nombre'=> 'eliminarAccionesCorrectivas','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar AccionesCorrectivas'>"));
                */
                /*VALIDAMOS QUE NO SEA REPORTE SALIDA DE ACCION CORRECTIVA*/
                if (!isset($parametros['reporte_ac'])){
                    $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:80px'>&nbsp;</div>"));
                }
                ELSE
                    $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:60px'>&nbsp;</div>"));
                $config=array();
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                //echo $parametros['mostrar-col'];
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
                //print_r($array_columns);
                //print_r($grid->hidden);
                //$this->hidden = $grid->hidden;   
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id", "colum_admin_arbol");
                $grid->setFuncion("descripcion", "formatear_descripcion");
                $grid->setFuncion("analisis_causal", "formatear_analisis_causal");
                $grid->setFuncion("estado", "semaforo_estado");
                $grid->setFuncion("id_organizacion", "BuscaOrganizacional");
                $grid->setFuncion("sema_evi", "semaforo_estado_ver");
                //$this->funciones["sema_evi"] = "semaforo_estado_ver";
                //$this->funciones["descripcion"] = "formatear_descripcion";
                //$this->funciones["analisis_causal"] = "formatear_analisis_causal";
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
                
                /* INICIO DE COMO ESTABA ANTERIORMENTE */
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
                                
                                if (!isset($parametros['reporte_ac'])){
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
                                }
                                else{
                                    $datos .= "<a onclick=\"javascript:verAccionesRep('". $fila[id] . "');\">
                                                    <i style='cursor:pointer'  class=\"icon icon-more\" title=\"Ver Acciones Correctivas ". $fila['id']."\"></i>
                                                </a>&nbsp;&nbsp;&nbsp;";
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
                                                                        ELSE '<img src=\"diseno/images/SemPlazo.png\" title=\"En el plazo\"/>'
                                */
                                
                                
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
                
                //$grid->setPagina($parametros['pag']);
                //$out['tabla'] = '<table id="tblAccionesCorrectivas" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">' . $titulosColumna . $datos.'</table>';
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
                /*PERMISOS*/
                import('clases.utilidades.NivelAcceso');
                $this->restricciones = new NivelAcceso();
                $this->restricciones->cargar_acceso_nodos_explicito($parametros);
                $this->restricciones->cargar_permisos($parametros);
                /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $this->arbol->jstree_ao(0,$parametros);
                /*FIN ARBOL ORGANIZACIONAL*/
                
                if ($parametros['corder'] == null) $parametros['corder']="id_2";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-1-3-4-5-6-7-14"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 15;
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
                /*
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
                 * 
                 
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
                $k++;         */       
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
                $k++;$k++;
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
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];     
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
                //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                //$objResponse->addScript('setTimeout(function(){ init_tabla(); }, 500);');
                
                /*JS init_Filtrar*/
                $objResponse->addScript('$( "#b-responsable_analisis" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); 
                                            $( "#b-id_responsable_segui" ).select2({
                                                                                placeholder: "Selecione el revisor",
                                                                                allowClear: true
                                                                              }); 
                                            $("#b-fecha_generacion-desde").datetimepicker();
                                            $("#b-fecha_acordada-desde").datetimepicker();;
                                            $("#b-fecha_realizada-desde").datetimepicker();
                                            $("#b-fecha_generacion-hasta").datetimepicker();
                                            $("#b-fecha_acordada-hasta").datetimepicker();
                                            $("#b-fecha_realizada-hasta").datetimepicker();

                                            PanelOperator.initPanels("");
                                            ScrollBar.initScroll();
                                            init_filtro_rapido();
                                            init_filtro_ao_simple();');
                /*JS init_tabla*/
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                        event.preventDefault();
                                        var id = $(this).attr('tok');
                                        $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                        $('#myModal-Ventana-Titulo').html('');
                                        $('#myModal-Ventana').modal('show');
                                    });

                                    $('.ver-reporte').on('click', function (event) {
                                        event.preventDefault();
                                        var id = $(this).attr('tok');
                                        verAccionesCorrectivas(id);
                                        /*window.open('pages/acciones_correctivas/reporte_ac_pdf.php?id='+id,'_blank');*/
                                    });");
                
                return $objResponse;
            }
            
            /**
             * Calcula los datos para la generacion del grafico de linea por arbol organizacional
             * @param array $parametros 
             * @return array
             */    
            PUBLIC function dataGraficoLinea($parametros){
                if(!class_exists('ArbolOrganizacional')){
                    import('clases.organizacion.ArbolOrganizacional');
                }
                $parametros = $this->dbl->corregir_parametros($parametros);
                
                /*PARAMETROS FORMULARIO*/
                $sql_parametros = '';
                if (strlen($parametros["b-origen_hallazgo"])>0)
                    $sql_parametros .= " AND origen_hallazgo = " . ($parametros["b-origen_hallazgo"]) . "";
                if (strlen($parametros["b-responsable_analisis"])>0)
                    $sql_parametros .= " AND responsable_analisis = ". $parametros["b-responsable_analisis"] . "";
                if (strlen($parametros["b-alto_potencial"])>0)
                    $sql_parametros .= " AND alto_potencial = '". $parametros["b-alto_potencial"] . "'";
                //echo $sql_parametros;
                if ((strlen($parametros["b-id_organizacion"])>0) && ($parametros["b-id_organizacion"] != "2")){                             
                        //$id_org_padre = $ao->BuscaOrgNivelHijos($parametros["b-id_organizacion"]);
                        $id_org_padre = $parametros["b-id_organizacion"];
                }    
                else{
                    $id_org_padre = 2;
                }
                $ao = new ArbolOrganizacional();
                switch ($parametros[tipo_data]) {
                    case 'SEMANAL':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        foreach ($data as $value) {
                            $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                            /*SE CONSULTAN LOS VALORES DE LOS HIJOS */
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_sem
                                where id_org in ($ids_hija) $sql_parametros 
                                and (sema >= ".date('Y')."01 AND sema < ".(date('YW')) ." )
                                group by sema
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            /*Se inicializan valores en cero*/
                            for($i=1;$i<=date('W')*1;$i++)
                            //for($i=1;$i<=40;$i++)
                            {
                                
                                $data_mes[date('Y').str_pad($i,2,'0',STR_PAD_LEFT)] = array('y'=>0,'valor'=> '0/0', 'total'=>0);
                            }                            
                            
                            /*SE ASIGNAN VALORES REALES*/
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[sema]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[sema]][valor] = $value_org[atrasada] . '/'. $value_org[total];
//                                    $data_mes[$value_org[mes]][total] = $value_org[total] ;
//                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                }
                                else
                                {
                                    $data_mes[$value_org[sema]][y] = 0;
                                    $data_mes[$value_org[sema]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            /*OBTENEMOS SEMANA ACTUAL*/
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,YEARWEEK(now()) sema                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros 
                                and (fecha_generacion >= STR_TO_DATE('".date('YW')." Monday', '%X%V %W') or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[sema]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[sema]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    //$data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                    //$data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[sema]][y] = 0;
                                    $data_mes[$value_org[sema]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $serie .= "{name: '" . $value[title] . "',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=date('W')*1;$i++)
                        //for($i=1;$i<=52*1;$i++)
                        {
                            $nombre_x .= "'" . $i . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'Semanal - ' . date('Y');
                        break;
                    case 'YTD':
                    case 'MES':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        foreach ($data as $value) {
                            $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                            /*SE CONSULTAN LOS VALORES DE LOS HIJOS */
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes
                                where id_org in ($ids_hija) and tipo = 1 $sql_parametros 
                                and (anio >= ".date('Y')." AND mes < ".(date('m')*1) ." )
                                group by mes, anio
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            /*Se inicializan valores en cero*/
                            for($i=1;$i<=date('m')*1;$i++)
                            {
                                $data_mes[$i] = array('y'=>0,'valor'=> '0', 'total'=>0);
                            }
                            /*SE ASIGNAN VALORES REALES*/
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
//                                    $data_mes[$value_org[mes]][total] = $value_org[total] ;
//                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,".(date('m')*1) ." mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '".date('Y')."-".(date('m')*1) ."-01' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    //$data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                    //$data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $serie .= "{name: '" . $value[title] . "',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=date('m')*1;$i++)
                        {
                            $nombre_x .= "'" . descripcion_mes_corto($i) . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'YTD - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;

                    case 'M12':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        foreach ($data as $value) {
                            $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes
                                where id_org in ($ids_hija) and tipo = 1 $sql_parametros
                                and (anio >= YEAR(DATE_SUB(now(),INTERVAL 1 YEAR)) and mes <= MONTH(DATE_SUB('2016-12-16',INTERVAL 1 YEAR)) and anio >= ".date('Y')." AND mes < ".(date('m')*1) ." )
                                group by mes, anio
                                order by anio asc, mes asc
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            /* Se setean todos los valores a cero */
                            $fecha = date('Y-m-j');
                            $nuevafecha = strtotime ( '-11 month' , strtotime ( $fecha ) ) ;
                            for($i=1;$i<=12*1;$i++)
                            {
                                $data_mes[date ( 'm' , $nuevafecha )*1] = array('y'=>0,'valor'=> '0/0');
//                                $nombre_x .= "'" . descripcion_mes_corto(date ( 'm' , $nuevafecha )*1) . "',";
                                $nuevafecha = strtotime ( '+1 month' , strtotime ( date ( 'Y-m-j' , $nuevafecha ) ) ) ;

                            }
                            /* Se asignan valores reales */ 
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
//                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
//                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = '0/0';
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,".(date('m')*1) ." mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '".date('Y')."-".(date('m')*1) ."-01' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                }
                            }
                            $serie .= "{name: '" . $value[title] . "',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        /*Nombre de los meses*/
                        $fecha = date('Y-m-j');
                        $nuevafecha = strtotime ( '-11 month' , strtotime ( $fecha ) ) ;
                        for($i=1;$i<=12*1;$i++)
                        {
                            $nombre_x .= "'" . descripcion_mes_corto(date ( 'm' , $nuevafecha )*1) . "',";
                            $nuevafecha = strtotime ( '+1 month' , strtotime ( date ( 'Y-m-j' , $nuevafecha ) ) ) ;
                            
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'M12 - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;
                    case 'QUARTIL':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        $fecha_actual = new DateTime("now");
                        $primer_cuartil = new DateTime(Date('Y'.'-03-31'));
                        $segundo_cuartil = new DateTime(Date('Y'.'-06-30'));
                        $tercer_cuartil = new DateTime(Date('Y'.'-09-30'));
                        $cuartil = 0;
                        if ($fecha_actual > $primer_cuartil){
                            if ($fecha_actual > $segundo_cuartil){
                                if ($fecha_actual > $tercer_cuartil){
                                    $cuartil = 4;
                                    $fecha_ini_cuartil = Date('Y').'-10-01';
                                }
                                else
                                {
                                    $cuartil = 3;
                                    $fecha_ini_cuartil = Date('Y').'-07-01';
                                }
                            }
                            else
                            {
                                $cuartil = 2;
                                $fecha_ini_cuartil = Date('Y').'-04-01';                                
                            }
                        }
                        else{
                            $cuartil = 1;
                            $fecha_ini_cuartil = Date('Y').'-01-01';
                        }
                        
                        
                        foreach ($data as $value) {
                            $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                            /*SE CONSULTAN LOS VALORES DE LOS HIJOS */
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes  
                                where id_org in ($ids_hija) and tipo = 2 $sql_parametros
                                and (anio >= ".date('Y')." AND mes < $cuartil )
                                group by mes, anio
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            /*Se inicializan valores en cero*/
                            for($i=1;$i<=$cuartil;$i++)
                            {
                                $data_mes[$i] = array('y'=>0,'valor'=> '0', 'total'=>0);
                            }
                            /*SE ASIGNAN VALORES REALES*/
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
//                                    $data_mes[$value_org[mes]][total] = $value_org[total] ;
//                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,$cuartil mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '$fecha_ini_cuartil' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    //$data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                    //$data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $serie .= "{name: '" . $value[title] . "',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=$cuartil;$i++)
                        {
                            $nombre_x .= "'Q" . $i . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'Cuartiles - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;    
                    case 'SEM':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        $fecha_actual = new DateTime("now");
                        $primer_semestre = new DateTime(Date('Y'.'-03-31'));                        
                        $semestre = 0;
                        if ($fecha_actual > $primer_semestre){                            
                            $semestre = 2;
                            $fecha_ini_semestre = Date('Y').'-07-01';
                                
                        }
                        else{
                            $semestre = 1;
                            $fecha_ini_semestre = Date('Y').'-01-01';
                        }
                        
                        
                        foreach ($data as $value) {
                            $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                            /*SE CONSULTAN LOS VALORES DE LOS HIJOS */
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes
                                where id_org in ($ids_hija) and tipo = 2 $sql_parametros
                                and (anio >= ".date('Y')." AND mes < $semestre )
                                group by mes, anio
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            /*Se inicializan valores en cero*/
                            for($i=1;$i<=$semestre;$i++)
                            {
                                $data_mes[$i] = array('y'=>0,'valor'=> '0', 'total'=>0);
                            }
                            /*SE ASIGNAN VALORES REALES*/
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
//                                    $data_mes[$value_org[mes]][total] = $value_org[total] ;
//                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        $value[id] id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,$semestre mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '$fecha_ini_semestre' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    //$data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                    //$data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $serie .= "{name: '" . $value[title] . "',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=$semestre;$i++)
                        {
                            $nombre_x .= "'Semestre " . $i . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'Semestres - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;    
                    default:
                        break;
                }
                switch ($parametros[tipo_data]) {
                    case 'MES':
                        $return[subtitle] = 'Mensual - ' . date('Y');

                        break;

                    default:
                        break;
                }
                
                return $return;
            }
            
            /**
             * Calcula los datos para la generacion del grafico de linea curva S atrasadas vs Total
             * @param array $parametros 
             * @return array
             */            
            PUBLIC function dataGraficoLineaUnificada($parametros){
                if(!class_exists('ArbolOrganizacional')){
                    import('clases.organizacion.ArbolOrganizacional');
                }
                $parametros = $this->dbl->corregir_parametros($parametros);
                
                $ao = new ArbolOrganizacional();
                /*PARAMETROS FORMULARIO*/
                $sql_parametros = '';
                if (strlen($parametros["b-origen_hallazgo"])>0)
                    $sql_parametros .= " AND origen_hallazgo = " . ($parametros["b-origen_hallazgo"]) . "";
                if (strlen($parametros["b-responsable_analisis"])>0)
                    $sql_parametros .= " AND responsable_analisis = ". $parametros["b-responsable_analisis"] . "";
                if (strlen($parametros["b-alto_potencial"])>0)
                    $sql_parametros .= " AND alto_potencial = '". $parametros["b-alto_potencial"] . "'";
                //echo $sql_parametros;
                if ((strlen($parametros["b-id_organizacion"])>0) && ($parametros["b-id_organizacion"] != "2")){                             
                        //$id_org_padre = $ao->BuscaOrgNivelHijos($parametros["b-id_organizacion"]);
                        $id_org_padre = $parametros["b-id_organizacion"];
                }    
                else{
                    $id_org_padre = 2;
                }
                switch ($parametros[tipo_data]) {
                    case 'YTD':
                    case 'MES':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        //foreach ($data as $value) 
                        {
                            $ids_hija = $ao->BuscaOrgNivelHijos($id_org_padre);
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes
                                where tipo = 1 and id_org in ($ids_hija) $sql_parametros
                                and (anio >= ".date('Y')." AND mes < ".(date('m')*1) ." )
                                group by mes, anio
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            for($i=1;$i<=date('m')*1;$i++)
                            {
                                $data_mes[$i] = array('y'=>0,'valor'=> '0/0','total'=> '0/0');
                            }
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; Porcentual desactivado
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,".(date('m')*1) ." mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros 
                                and (fecha_generacion >= '".date('Y')."-".(date('m')*1) ."-01' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; atrasada porcentualmente
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            /* SERIE PARA LAS ATRASADAS */
                            $serie .= "{name: 'Atrasadas',color: '#ff7272',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                                       
                            /* SERIE PARA EL TOTAL */
                            $serie .= "{name: 'Total',color: '#00950e',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[total],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=date('m')*1;$i++)
                        {
                            $nombre_x .= "'" . descripcion_mes_corto($i) . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'YTD - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;
                    case 'QUARTIL':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        //foreach ($data as $value) 
                        $fecha_actual = new DateTime("now");
                        $primer_cuartil = new DateTime(Date('Y'.'-03-31'));
                        $segundo_cuartil = new DateTime(Date('Y'.'-06-30'));
                        $tercer_cuartil = new DateTime(Date('Y'.'-09-30'));
                        $cuartil = 0;
                        if ($fecha_actual > $primer_cuartil){
                            if ($fecha_actual > $segundo_cuartil){
                                if ($fecha_actual > $tercer_cuartil){
                                    $cuartil = 4;
                                    $fecha_ini_cuartil = Date('Y').'-10-01';
                                }
                                else
                                {
                                    $cuartil = 3;
                                    $fecha_ini_cuartil = Date('Y').'-07-01';
                                }
                            }
                            else
                            {
                                $cuartil = 2;
                                $fecha_ini_cuartil = Date('Y').'-04-01';                                
                            }
                        }
                        else{
                            $cuartil = 1;
                            $fecha_ini_cuartil = Date('Y').'-01-01';
                        }
                        {
                            $ids_hija = $ao->BuscaOrgNivelHijos($id_org_padre);
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes 
                                where tipo = 2 and id_org in ($ids_hija) $sql_parametros
                                and (anio >= ".date('Y')." AND mes < $cuartil )
                                group by mes, anio
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            for($i=1;$i<=$cuartil;$i++)
                            {
                                $data_mes[$i] = array('y'=>0,'valor'=> '0/0','total'=> '0/0');
                            }
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; Porcentual desactivado
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,$cuartil mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '".date('Y')."-".(date('m')*1) ."-01' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; atrasada porcentualmente
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            /* SERIE PARA LAS ATRASADAS */
                            $serie .= "{name: 'Atrasadas',color: '#ff7272',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                                       
                            /* SERIE PARA EL TOTAL */
                            $serie .= "{name: 'Total',color: '#00950e',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[total],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=$cuartil;$i++)
                        {
                            $nombre_x .= "'Q" . ($i) . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'Cuartiles - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;
                    case 'QUARTIL':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        //foreach ($data as $value) 
                        $fecha_actual = new DateTime("now");
                        $primer_cuartil = new DateTime(Date('Y'.'-03-31'));
                        $segundo_cuartil = new DateTime(Date('Y'.'-06-30'));
                        $tercer_cuartil = new DateTime(Date('Y'.'-09-30'));
                        $cuartil = 0;
                        if ($fecha_actual > $primer_cuartil){
                            if ($fecha_actual > $segundo_cuartil){
                                if ($fecha_actual > $tercer_cuartil){
                                    $cuartil = 4;
                                    $fecha_ini_cuartil = Date('Y').'-10-01';
                                }
                                else
                                {
                                    $cuartil = 3;
                                    $fecha_ini_cuartil = Date('Y').'-07-01';
                                }
                            }
                            else
                            {
                                $cuartil = 2;
                                $fecha_ini_cuartil = Date('Y').'-04-01';                                
                            }
                        }
                        else{
                            $cuartil = 1;
                            $fecha_ini_cuartil = Date('Y').'-01-01';
                        }
                        {
                            $ids_hija = $ao->BuscaOrgNivelHijos($id_org_padre);
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes
                                where tipo = 2 and id_org in ($ids_hija) $sql_parametros 
                                and (anio >= ".date('Y')." AND mes < $cuartil )
                                group by mes, anio
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            for($i=1;$i<=$cuartil;$i++)
                            {
                                $data_mes[$i] = array('y'=>0,'valor'=> '0/0','total'=> '0/0');
                            }
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; Porcentual desactivado
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,$cuartil mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '".date('Y')."-".(date('m')*1) ."-01' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; atrasada porcentualmente
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            /* SERIE PARA LAS ATRASADAS */
                            $serie .= "{name: 'Atrasadas',color: '#ff7272',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                                       
                            /* SERIE PARA EL TOTAL */
                            $serie .= "{name: 'Total',color: '#00950e',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[total],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=$cuartil;$i++)
                        {
                            $nombre_x .= "'Q" . ($i) . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'Cuartiles - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;
                    case 'SEM':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        //foreach ($data as $value) 
                        $fecha_actual = new DateTime("now");
                        $primer_semestre = new DateTime(Date('Y'.'-06-30'));                        
                        $semestre = 0;
                        if ($fecha_actual > $primer_semestre){                            
                            $semestre = 2;
                            $fecha_ini_semestre = Date('Y').'-01-01';                                
                        }
                        else{
                            $semestre = 1;
                            $fecha_ini_semestre = Date('Y').'-07-01';
                        }
                        {
                            $ids_hija = $ao->BuscaOrgNivelHijos($id_org_padre);
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes
                                where tipo = 3 and id_org in ($ids_hija) $sql_parametros 
                                and (anio >= ".date('Y')." AND mes < $semestre )
                                group by mes, anio
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            for($i=1;$i<=$semestre;$i++)
                            {
                                $data_mes[$i] = array('y'=>0,'valor'=> '0/0','total'=> '0/0');
                            }
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; Porcentual desactivado
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,$semestre mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '$fecha_ini_semestre' or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100; atrasada porcentualmente
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            /* SERIE PARA LAS ATRASADAS */
                            $serie .= "{name: 'Atrasadas',color: '#ff7272',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                                       
                            /* SERIE PARA EL TOTAL */
                            $serie .= "{name: 'Total',color: '#00950e',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[total],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=$semestre;$i++)
                        {
                            $nombre_x .= "'Semestre " . ($i) . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'Semestres - ' . date('Y');
                        //echo $return[nombre_x];
                        //return $return;
                        break;
                    case 'SEMANAL':
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        //foreach ($data as $value) 
                        {
                            $ids_hija = $ao->BuscaOrgNivelHijos($id_org_padre);
                            /*SE CONSULTAN LOS VALORES DE LOS HIJOS */
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_sem
                                where id_org in ($ids_hija) $sql_parametros 
                                and (sema >= ".date('Y')."01 AND sema < ".(date('YW')) ." )
                                group by sema
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();
                            /*Se inicializan valores en cero*/
                            for($i=1;$i<=date('W')*1;$i++)
                            //for($i=1;$i<=40;$i++)
                            {
                                
                                $data_mes[date('Y').str_pad($i,2,'0',STR_PAD_LEFT)] = array('y'=>0,'valor'=> '0/0', 'total'=>0);
                            }                            
                            
                            /*SE ASIGNAN VALORES REALES*/
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[sema]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[sema]][valor] = $value_org[atrasada] . '/'. $value_org[total];
//                                    $data_mes[$value_org[mes]][total] = $value_org[total] ;
//                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                }
                                else
                                {
                                    $data_mes[$value_org[sema]][y] = 0;
                                    $data_mes[$value_org[sema]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            /*OBTENEMOS SEMANA ACTUAL*/
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,YEARWEEK(now()) sema                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros 
                                and (fecha_generacion >= STR_TO_DATE('".date('YW')." Monday', '%X%V %W') or fecha_realizada is null)                               
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    $data_mes[$value_org[sema]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[sema]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    //$data_mes[$value_org[mes]][valor] = $value_org[atrasada] ;
                                    //$data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[sema]][y] = 0;
                                    $data_mes[$value_org[sema]][valor] = '0/0';
                                    //$data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                                                        
                                                          
                            /* SERIE PARA LAS ATRASADAS */
                            $serie .= "{name: 'Atrasadas',color: '#ff7272',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                                       
                            /* SERIE PARA EL TOTAL */
                            $serie .= "{name: 'Total',color: '#00950e',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[total],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        for($i=1;$i<=date('W')*1;$i++)
                        //for($i=1;$i<=52*1;$i++)
                        {
                            $nombre_x .= "'" . $i . "',";
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        $return[subtitle] = 'Semanal - ' . date('Y');
                        break;
                    case 'M12':
                        
                        $sql="Select * from mos_organizacion
				Where parent_id IN ($id_org_padre)";
                
                        $data = $this->dbl->query($sql, $atr);
                        $sql ='';
                        $serie = '';
                        //foreach ($data as $value) 
                        {
                            $ids_hija = $ao->BuscaOrgNivelHijos($id_org_padre);
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,IFNULL(sum(total),0) total 
                                        ,IFNULL(sum(atrasada),0)  atrasada
                                        ,IFNULL(sum(en_plazo),0)  en_plazo
                                        ,IFNULL(sum(realizada),0)  realizada
                                        ,IFNULL(sum(realizada_atraso),0)  realizada_atraso
                                        ,mes	
                                        ,anio                                
                                FROM
                                mos_acciones_correctivas_foto_mes
                                where id_org in ($ids_hija) and tipo = 1 $sql_parametros 
                                and (anio >= YEAR(DATE_SUB(now(),INTERVAL 1 YEAR)) and mes <= MONTH(DATE_SUB('2016-12-16',INTERVAL 1 YEAR)) and anio >= ".date('Y')." AND mes < ".(date('m')*1) ." )
                                group by mes, anio
                                order by anio asc, mes asc
                               ";       
                            
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            $data_mes = array();                            
//                            for($i=1;$i<=12*1;$i++)
//                            {
//                                $data_mes[$i] = array('y'=>0,'valor'=> '0/0');
//                            }
                            $fecha = date('Y-m-j');
                            $nuevafecha = strtotime ( '-11 month' , strtotime ( $fecha ) ) ;
                            for($i=1;$i<=12*1;$i++)
                            {
                                $data_mes[date ( 'm' , $nuevafecha )*1] = array('y'=>0,'valor'=> '0/0', 'total'=>0);
//                                $nombre_x .= "'" . descripcion_mes_corto(date ( 'm' , $nuevafecha )*1) . "',";
                                $nuevafecha = strtotime ( '+1 month' , strtotime ( date ( 'Y-m-j' , $nuevafecha ) ) ) ;

                            }
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
                            $sql = "SELECT
                                        2 id_organizacion                                   
                                        ,count(id) total
                                        ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasada
                                        ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                        ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                        ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada
                                        ,".(date('m')*1) ." mes                              
                                FROM
                                mos_acciones_correctivas
                                where id_organizacion in ($ids_hija) $sql_parametros
                                and (fecha_generacion >= '".date('Y')."-".(date('m')*1) ."-01' or fecha_realizada is null)  
                                order by anio asc, mes asc
                               ";       
                            //echo $sql;
                            
                            $data_org = $this->dbl->query($sql, $atr);
                            foreach ($data_org as $value_org) {
                                if ($value_org[total] != '0'){
                                    //$data_mes[$value_org[mes]][y] = $value_org[atrasada] / $value_org[total] * 100;
                                    $data_mes[$value_org[mes]][y] = $value_org[atrasada];
                                    $data_mes[$value_org[mes]][valor] = $value_org[atrasada] . '/'. $value_org[total];
                                    $data_mes[$value_org[mes]][total] = $value_org[total];
                                }
                                else
                                {
                                    $data_mes[$value_org[mes]][y] = 0;
                                    $data_mes[$value_org[mes]][valor] = 0;
                                    $data_mes[$value_org[mes]][total] = 0;
                                }
                            }
//                            $serie .= "{name: 'Porcentaje de Atraso',data:[ ";
//                            foreach ($data_mes as $value_mes) {
//                                //$serie .= json_encode($value_mes) .",";
//                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
//                            }
//                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                            /* SERIE PARA LAS ATRASADAS */
                            $serie .= "{name: 'Atrasadas',color: '#ff7272',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[y],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                                       
                            /* SERIE PARA EL TOTAL */
                            $serie .= "{name: 'Total',color: '#00950e',data:[ ";
                            foreach ($data_mes as $value_mes) {
                                //$serie .= json_encode($value_mes) .",";
                                $serie .= "{y:$value_mes[total],valor:'$value_mes[valor]'},";
                            }
                            $serie = substr($serie, 0, strlen($serie) - 1) . " ] },"; 
                                
                            //print_r($data_mes);
                        }
                        $nombre_x = '';
                        /*Nombre de los meses*/
                        $fecha = date('Y-m-j');
                        $nuevafecha = strtotime ( '-11 month' , strtotime ( $fecha ) ) ;
                        for($i=1;$i<=12*1;$i++)
                        {
                            $nombre_x .= "'" . descripcion_mes_corto(date ( 'm' , $nuevafecha )*1) . "',";
                            $nuevafecha = strtotime ( '+1 month' , strtotime ( date ( 'Y-m-j' , $nuevafecha ) ) ) ;
                            
                        }
                        $serie = substr($serie, 0, strlen($serie) - 1);
                        //echo $serie;
                        
                        $return = array();
                        $return[serie] = str_replace('"', '', $serie);
                        
                        $return[nombre_x] = $serie = substr($nombre_x, 0, strlen($nombre_x) - 1);
                        
                        $return[subtitle] = 'M12 - ' . date('Y');
                        //return $return;
                        break;
                    default:
                        break;
                }
                switch ($parametros[tipo_data]) {
                    case 'MES':
                        $return[subtitle] = 'Mensual - ' . date('Y');

                        break;

                    default:
                        break;
                }
                return $return;
            }
            
            /**
             * Calcula los datos para la generacion del grafico de barra
             * @param array $parametros 
             * @return array
             */            
            PUBLIC function dataGraficoBarra($parametros){
                if(!class_exists('ArbolOrganizacional')){
                    import('clases.organizacion.ArbolOrganizacional');
                }
                $parametros = $this->dbl->corregir_parametros($parametros);
                $ao = new ArbolOrganizacional();
                /*PARAMETROS FORMULARIO*/
                $sql_parametros = '';
                if (strlen($parametros["b-origen_hallazgo"])>0)
                    $sql_parametros .= " AND origen_hallazgo = " . ($parametros["b-origen_hallazgo"]) . "";
                if (strlen($parametros["b-responsable_analisis"])>0)
                    $sql_parametros .= " AND responsable_analisis = ". $parametros["b-responsable_analisis"] . "";
                if (strlen($parametros["b-alto_potencial"])>0)
                    $sql_parametros .= " AND alto_potencial = '". $parametros["b-alto_potencial"] . "'";
                //echo $sql_parametros;
                if ((strlen($parametros["b-id_organizacion"])>0) && ($parametros["b-id_organizacion"] != "2")){                             
                        //$id_org_padre = $ao->BuscaOrgNivelHijos($parametros["b-id_organizacion"]);
                        $id_org_padre = $parametros["b-id_organizacion"];
                }    
                else{
                    $id_org_padre = 2;
                }
                if ($parametros["b-filtro-fecha"] == '2')
                {                    
                    $dias	= (strtotime(date(formatear_fecha($parametros["b-f-desde"])))-strtotime(date(formatear_fecha($parametros["b-f-hasta"]))))/86400;
                    $dias 	= abs($dias); 
                    $dias = floor($dias);	
                    $sql="Select * from mos_organizacion
                                    Where parent_id IN ($id_org_padre)";

                    $data = $this->dbl->query($sql, $atr);
                    $sql ='';
                    foreach ($data as $value) {
                        $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                        $sql .= "SELECT
                                    $value[id] id_organizacion                                   
                                    ,count(id) total
                                    ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasadas
                                    ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                    ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                    ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada                                  
                            FROM
                            mos_acciones_correctivas
                            where id_organizacion in ($ids_hija) $sql_parametros 
                            and (fecha_generacion >= '".formatear_fecha($parametros["b-f-desde"])."' or fecha_realizada is null) -- or fecha_realizada >= '2016-01-01'
                            -- group by id_organizacion
                            UNION ALL ";                                                                                    
                    }
                    $sql = substr($sql, 0, strlen($sql) - 10);
                    $sql = "SELECT temp.*,IFNULL(atrasadas/total*100,0) por_atrasadas,IFNULL(en_plazo/total*100,0) por_en_plazo,IFNULL(realizada_atraso/total*100,0) por_realizada_atraso,IFNULL(realizada/total*100,0) por_realizada, title from ( $sql )"
                            . " as temp
                                inner JOIN mos_organizacion on id = id_organizacion
                                 ORDER BY por_atrasadas,total";
                    //echo $sql;
                    $data = $this->dbl->query($sql, $atr);
                    $return = array();
                    foreach ($data as $value) {
                        $return[gerencia] .= "'".$value[title]."',";
//                            $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]/$value[total]'},";
//                            $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]/$value[total]'},";
//                            $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]/$value[total]'},";
//                            $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]/$value[total]'},";

                        $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]',total_ac:'$value[total]'},";
                        $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]',total_ac:'$value[total]'},";
                        $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]',total_ac:'$value[total]'},";
                        $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]',total_ac:'$value[total]'},";
                    }
                    $return[gerencia] = substr($return[gerencia], 0, strlen($return[gerencia]) - 1);
                    $return[atrasada] = substr($return[atrasada], 0, strlen($return[atrasada]) - 1);
                    $return[plazo] = substr($return[plazo], 0, strlen($return[plazo]) - 1);
                    $return[realizada_atraso] = substr($return[realizada_atraso], 0, strlen($return[realizada_atraso]) - 1);
                    $return[realizada] = substr($return[realizada], 0, strlen($return[realizada]) - 1);
                    $return[subtitle] = 'Desde "' . $parametros["b-f-desde"] . '" Hasta "' . $parametros["b-f-hasta"] . '"';                    
                    //echo $dias;
                    
                }
                else{                                    
                    switch ($parametros[tipo_data]) {
                        case 'YTD':
                        case 'MES':
                        case 'QUARTIL':
                        case 'SEM':
                        case 'SEMANAL':
                            $sql="Select * from mos_organizacion
                                    Where  parent_id IN ( $id_org_padre)";

                            $data = $this->dbl->query($sql, $atr);
                            //echo $sql;
                            $sql ='';
                            foreach ($data as $value) {
                                $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                                $sql .= "SELECT
                                            $value[id] id_organizacion                                   
                                            ,count(id) total
                                            ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasadas
                                            ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                            ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                            ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada                                  
                                    FROM
                                    mos_acciones_correctivas
                                    where id_organizacion in ($ids_hija) $sql_parametros 
                                    and (fecha_generacion >= '".date('Y')."-01-01' or fecha_realizada is null) -- or fecha_realizada >= '2016-01-01'
                                    -- group by id_organizacion
                                    UNION ALL ";                                                                                    
                            }
                            $sql = substr($sql, 0, strlen($sql) - 10);
                            $sql = "SELECT temp.*,IFNULL(atrasadas/total*100,0) por_atrasadas,IFNULL(en_plazo/total*100,0) por_en_plazo,IFNULL(realizada_atraso/total*100,0) por_realizada_atraso,IFNULL(realizada/total*100,0) por_realizada, title from ( $sql )"
                                    . " as temp
                                        inner JOIN mos_organizacion on id = id_organizacion
                                         ORDER BY por_atrasadas,total";
                            //echo $sql;
                            $data = $this->dbl->query($sql, $atr);
                            $return = array();
                            foreach ($data as $value) {
                                $return[gerencia] .= "'".$value[title]."',";
    //                            $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]/$value[total]'},";
    //                            $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]/$value[total]'},";
    //                            $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]/$value[total]'},";
    //                            $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]/$value[total]'},";

                                $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]',total_ac:'$value[total]'},";
                                $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]',total_ac:'$value[total]'},";
                                $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]',total_ac:'$value[total]'},";
                                $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]',total_ac:'$value[total]'},";
                            }
                            $return[gerencia] = substr($return[gerencia], 0, strlen($return[gerencia]) - 1);
                            $return[atrasada] = substr($return[atrasada], 0, strlen($return[atrasada]) - 1);
                            $return[plazo] = substr($return[plazo], 0, strlen($return[plazo]) - 1);
                            $return[realizada_atraso] = substr($return[realizada_atraso], 0, strlen($return[realizada_atraso]) - 1);
                            $return[realizada] = substr($return[realizada], 0, strlen($return[realizada]) - 1);
                            $return[subtitle] = 'YTD - ' . date('Y');
                            //return $return;
                            break;

                        case 'M12':        
                            $sql="Select * from mos_organizacion
                                    Where parent_id IN ($id_org_padre)";

                            $data = $this->dbl->query($sql, $atr);
                            $sql ='';
                            foreach ($data as $value) {
                                $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                                $sql .= "SELECT
                                            $value[id] id_organizacion                                   
                                            ,count(id) total
                                            ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasadas
                                            ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                            ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                            ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada                                  
                                    FROM
                                    mos_acciones_correctivas
                                    where id_organizacion in ($ids_hija) $sql_parametros
                                    and (fecha_generacion >= DATE_ADD(DATE_SUB(NOW(),INTERVAL 1 YEAR),INTERVAL 1 DAY)  or fecha_realizada is null) -- or fecha_realizada >= '2016-01-01'
                                    -- group by id_organizacion
                                    UNION ALL ";                                                                                    
                            }
                            $sql = substr($sql, 0, strlen($sql) - 10);
                            $sql = "SELECT temp.*,IFNULL(atrasadas/total*100,0) por_atrasadas,IFNULL(en_plazo/total*100,0) por_en_plazo,IFNULL(realizada_atraso/total*100,0) por_realizada_atraso,IFNULL(realizada/total*100,0) por_realizada, title from ( $sql )"
                                    . " as temp
                                        inner JOIN mos_organizacion on id = id_organizacion
                                         ORDER BY por_atrasadas,total";
                            //echo $sql;
                            $data = $this->dbl->query($sql, $atr);
                            $return = array();
                            foreach ($data as $value) {
                                $return[gerencia] .= "'".$value[title]."',";
    //                            $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]/$value[total]'},";
    //                            $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]/$value[total]'},";
    //                            $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]/$value[total]'},";
    //                            $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]/$value[total]'},";

                                $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]',total_ac:'$value[total]'},";
                                $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]',total_ac:'$value[total]'},";
                                $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]',total_ac:'$value[total]'},";
                                $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]',total_ac:'$value[total]'},";
                            }
                            $return[gerencia] = substr($return[gerencia], 0, strlen($return[gerencia]) - 1);
                            $return[atrasada] = substr($return[atrasada], 0, strlen($return[atrasada]) - 1);
                            $return[plazo] = substr($return[plazo], 0, strlen($return[plazo]) - 1);
                            $return[realizada_atraso] = substr($return[realizada_atraso], 0, strlen($return[realizada_atraso]) - 1);
                            $return[realizada] = substr($return[realizada], 0, strlen($return[realizada]) - 1);
                            $return[subtitle] = 'M12 - ' . date('Y');
                            return $return;
                        default:
                            break;
                    }
                    switch ($parametros[tipo_data]) {
                        case 'MES':
                            $return[subtitle] = 'Mensual - ' . date('Y');
                            break;
                        case 'QUARTIL':
                            $return[subtitle] = 'Cuartil - ' . date('Y');
                            break;
                        case 'SEM':
                            $return[subtitle] = 'Semestre - ' . date('Y');
                            break;
                        case 'SEMANAL':
                            $return[subtitle] = 'Semanal - ' . date('Y');
                            break;

                        default:
                            break;
                    }
                }
                return $return;
            }
            
            
            /**
             * Calcula los datos para la generacion del grafico de barra verificacion atrasada
             * @param array $parametros 
             * @return array
             */            
            PUBLIC function dataGraficoBarraVA($parametros){
                if(!class_exists('ArbolOrganizacional')){
                    import('clases.organizacion.ArbolOrganizacional');
                }
                $ao = new ArbolOrganizacional();      
                /*PARAMETROS FORMULARIO*/
                $sql_parametros = '';
                if (strlen($parametros["b-origen_hallazgo"])>0)
                    $sql_parametros .= " AND origen_hallazgo = " . ($parametros["b-origen_hallazgo"]) . "";
                if (strlen($parametros["b-responsable_analisis"])>0)
                    $sql_parametros .= " AND responsable_analisis = ". $parametros["b-responsable_analisis"] . "";
                if (strlen($parametros["b-alto_potencial"])>0)
                    $sql_parametros .= " AND alto_potencial = '". $parametros["b-alto_potencial"] . "'";
                //echo $sql_parametros;
                if ((strlen($parametros["b-id_organizacion"])>0) && ($parametros["b-id_organizacion"] != "2")){                             
                        //$id_org_padre = $ao->BuscaOrgNivelHijos($parametros["b-id_organizacion"]);
                        $id_org_padre = $parametros["b-id_organizacion"];
                }    
                else{
                    $id_org_padre = 2;
                }
                $sql="Select * from mos_organizacion
                        Where parent_id IN ($id_org_padre)";

                $data = $this->dbl->query($sql, $atr);
                $sql ='';
                foreach ($data as $value) {
                    $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                    $sql .= "SELECT
                                $value[id] id_organizacion                                   
                                ,count(id) total
                                ,IFNULL(sum(case when estado=1 then 1 else 0 end),0) as atrasadas
                                ,IFNULL(sum(case when estado=2 then 1 else 0 end),0) as en_plazo
                                ,IFNULL(sum(case when estado=3 then 1 else 0 end),0) as realizada_atraso
                                ,IFNULL(sum(case when estado=4 then 1 else 0 end),0) as realizada                                  
                        FROM
                        mos_acciones_correctivas
                        where id_organizacion in ($ids_hija) $sql_parametros
                        and (not fecha_acordada is null and estado = 1) -- or fecha_realizada >= '2016-01-01'
                        -- group by id_organizacion
                        UNION ALL ";                                                                                    
                }
                $sql = substr($sql, 0, strlen($sql) - 10);
                $sql = "SELECT temp.*,IFNULL(atrasadas/total*100,0) por_atrasadas,IFNULL(en_plazo/total*100,0) por_en_plazo,IFNULL(realizada_atraso/total*100,0) por_realizada_atraso,IFNULL(realizada/total*100,0) por_realizada, title from ( $sql )"
                        . " as temp
                            inner JOIN mos_organizacion on id = id_organizacion
                             ORDER BY atrasadas,total";
                //echo $sql;
                $data = $this->dbl->query($sql, $atr);
                $return = array();
                foreach ($data as $value) {
                    $return[gerencia] .= "'".$value[title]."',";
//                            $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]/$value[total]'},";
//                            $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]/$value[total]'},";
//                            $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]/$value[total]'},";
//                            $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]/$value[total]'},";

                    $return[atrasada] .= "{y:". $value[atrasadas].",valor:'$value[atrasadas]',total_ac:'$value[total]'},";
//                    $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]',total_ac:'$value[total]'},";
//                    $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]',total_ac:'$value[total]'},";
//                    $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]',total_ac:'$value[total]'},";
                }
                $return[gerencia] = substr($return[gerencia], 0, strlen($return[gerencia]) - 1);
                $return[atrasada] = substr($return[atrasada], 0, strlen($return[atrasada]) - 1);
//                $return[plazo] = substr($return[plazo], 0, strlen($return[plazo]) - 1);
//                $return[realizada_atraso] = substr($return[realizada_atraso], 0, strlen($return[realizada_atraso]) - 1);
//                $return[realizada] = substr($return[realizada], 0, strlen($return[realizada]) - 1);
                $return[subtitle] = 'Al ' . date('d/m/Y');
                //return $return;
                        
                return $return;
            }
            
            /**
             * Calcula los datos para la generacion del grafico de barra acciones atrasadas
             * @param array $parametros 
             * @return array
             */            
            PUBLIC function dataGraficoBarraAA($parametros){
                if(!class_exists('ArbolOrganizacional')){
                    import('clases.organizacion.ArbolOrganizacional');
                }
                $ao = new ArbolOrganizacional();    
                /*PARAMETROS FORMULARIO*/
                $sql_parametros = '';
                if (strlen($parametros["b-origen_hallazgo"])>0)
                    $sql_parametros .= " AND origen_hallazgo = " . ($parametros["b-origen_hallazgo"]) . "";
                if (strlen($parametros["b-responsable_analisis"])>0)
                    $sql_parametros .= " AND responsable_analisis = ". $parametros["b-responsable_analisis"] . "";
                if (strlen($parametros["b-alto_potencial"])>0)
                    $sql_parametros .= " AND alto_potencial = '". $parametros["b-alto_potencial"] . "'";
                //echo $sql_parametros;
                if ((strlen($parametros["b-id_organizacion"])>0) && ($parametros["b-id_organizacion"] != "2")){                             
                        //$id_org_padre = $ao->BuscaOrgNivelHijos($parametros["b-id_organizacion"]);
                        $id_org_padre = $parametros["b-id_organizacion"];
                }    
                else{
                    $id_org_padre = 2;
                }
                $sql="Select * from mos_organizacion
                        Where parent_id IN ($id_org_padre)";

                $data = $this->dbl->query($sql, $atr);
                $sql ='';
                foreach ($data as $value) {
                    $ids_hija = $ao->BuscaOrgNivelHijos($value[id]);
                    $sql .= "SELECT
                                $value[id] id_organizacion                                   
                                ,count(a.id) total
                                ,IFNULL(sum(case when a.estado=1 then 1 else 0 end),0) as atrasadas
                                ,IFNULL(sum(case when a.estado=2 then 1 else 0 end),0) as en_plazo
                                ,IFNULL(sum(case when a.estado=3 then 1 else 0 end),0) as realizada_atraso
                                ,IFNULL(sum(case when a.estado=4 then 1 else 0 end),0) as realizada                                  
                        FROM
                        mos_acciones_correctivas ac
                        INNER JOIN mos_acciones_ac_co a ON a.id_ac = ac.id
                        where id_organizacion in ($ids_hija) $sql_parametros
                        -- and (not fecha_acordada is null and estado = 1) -- or fecha_realizada >= '2016-01-01'
                        -- group by id_organizacion
                        UNION ALL ";                                                                                    
                }
                $sql = substr($sql, 0, strlen($sql) - 10);
                $sql = "SELECT temp.*,IFNULL(atrasadas/total*100,0) por_atrasadas,IFNULL(en_plazo/total*100,0) por_en_plazo,IFNULL(realizada_atraso/total*100,0) por_realizada_atraso,IFNULL(realizada/total*100,0) por_realizada, title from ( $sql )"
                        . " as temp
                            inner JOIN mos_organizacion on id = id_organizacion
                             ORDER BY atrasadas,total";
                //echo $sql;
                $data = $this->dbl->query($sql, $atr);
                $return = array();
                foreach ($data as $value) {
                    $return[gerencia] .= "'".$value[title]."',";
//                            $return[atrasada] .= "{y:". $value[por_atrasadas].",valor:'$value[atrasadas]/$value[total]'},";
//                            $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]/$value[total]'},";
//                            $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]/$value[total]'},";
//                            $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]/$value[total]'},";

                    $return[atrasada] .= "{y:". $value[atrasadas].",valor:'$value[atrasadas]',total_ac:'$value[total]'},";
//                    $return[plazo] .= "{y:". $value[por_en_plazo].",valor:'$value[en_plazo]',total_ac:'$value[total]'},";
//                    $return[realizada_atraso] .= "{y:". $value[por_realizada_atraso].",valor:'$value[realizada_atraso]',total_ac:'$value[total]'},";
//                    $return[realizada] .= "{y:". $value[por_realizada].",valor:'$value[realizada]',total_ac:'$value[total]'},";
                }
                $return[gerencia] = substr($return[gerencia], 0, strlen($return[gerencia]) - 1);
                $return[atrasada] = substr($return[atrasada], 0, strlen($return[atrasada]) - 1);
//                $return[plazo] = substr($return[plazo], 0, strlen($return[plazo]) - 1);
//                $return[realizada_atraso] = substr($return[realizada_atraso], 0, strlen($return[realizada_atraso]) - 1);
//                $return[realizada] = substr($return[realizada], 0, strlen($return[realizada]) - 1);
                $return[subtitle] = 'Al ' . date('d/m/Y');
                //return $return;
                        
                return $return;
            }
            
            
            public function indexAccionesCorrectivasReporte($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="estado";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6";//-7-8"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 9;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    //$parametros['mostrar-col'] .= "-$k"; //checked="checked"
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
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Trazabilidad checked="checked"
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
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Accion checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[accion] . '</label>
                                  </div>
                           ';
                $k++;
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Acordada checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[fecha_acordada] . '</label>
                                  </div>
                            ';
                $k++;
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Realizada checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[fecha_realizada] . '</label>
                                  </div>
                            ';
                $k++;
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Responsable checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $this->nombres_columnas_ac[id_responsable] . '</label>
                                  </div>
                            ';
                $k++;
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Semaforo checked="checked"
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
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Acordada EVI checked="checked"
                $contenido[PARAMETROS_OTROS_AE_AO] .= '
                                  <div class="checkbox">      
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" >   &nbsp;
                                      ' . $this->nombres_columnas[fecha_acordada] . '</label>
                                  </div>
                            ';
                $k++;
                //$parametros['mostrar-col'] .= "-". ($k); //Columna Fecha Realizada EVI checked="checked"
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
                /*PARAMETROS DE REPORTES AC*/
                $parametros['reporte_ac'] ='S';
                $parametros[tipo_data] = 'YTD';
                $parametros['b-filtro-fecha'] = 1;
                /*FIN REPORTE PARAMETRO AC*/
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
                //$contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['PERMISO_INGRESAR'] = 'display:none;';
                
                if(!class_exists('ArbolOrganizacional')){
                    import('clases.organizacion.ArbolOrganizacional');
                }
                
                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(3,$parametros);
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
                $template->setTemplate("busqueda_reporte_listado");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                
                
                $contenido[B_F_HASTA] = date('d/m/Y');
                $template->setTemplate("busqueda_reporte");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR_FILTRO'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                
                
                /*GRAFICO BARRA*/
                $result = $this->dataGraficoBarra($parametros);                
                $contenido[DATA_GRAFICO_BAR] = $result[atrasada].";".$result[plazo].";".$result[realizada_atraso].";".$result[realizada];
                $contenido[NOMBRE_COLUM_GRAFICO_BAR] = $result[gerencia];
                $contenido[SUBTITLE_GRAFICO_BAR] = $result[subtitle];//'YTD - ' . date('Y');
                /*GRAFICO LINEA*/
                $result = $this->dataGraficoLinea($parametros);
                $contenido[DATA_GRAFICO_LINEA] = $result[serie];
                $contenido[NOMBRE_COLUM_GRAFICO_LINEA] = $result[nombre_x];
                $contenido[SUBTITLE_GRAFICO_LINEA] = $result[subtitle];//'YTD - ' . date('Y');
                /*GRAFICO LINEA UNIFICADA*/
                $result = $this->dataGraficoLineaUnificada($parametros);
                $contenido[DATA_GRAFICO_LINEA_UNI] = $result[serie];
                $contenido[NOMBRE_COLUM_GRAFICO_LINEA_UNI] = $result[nombre_x];
                $contenido[SUBTITLE_GRAFICO_LINEA_UNI] = $result[subtitle];
                /*GRAFICO BARRA VERIFICACIONES ATRASADAS*/
                $result = $this->dataGraficoBarraVA($parametros);                
                $contenido[DATA_GRAFICO_BAR_2] = $result[atrasada].";".$result[plazo].";".$result[realizada_atraso].";".$result[realizada];
                $contenido[NOMBRE_COLUM_GRAFICO_BAR_2] = $result[gerencia];
                $contenido[SUBTITLE_GRAFICO_BAR_2] = $result[subtitle];//'YTD - ' . date('Y');
                /*GRAFICO BARRA ACCIONES ATRASADAS*/
                $result = $this->dataGraficoBarraAA($parametros);                
                $contenido[DATA_GRAFICO_BAR_3] = $result[atrasada].";".$result[plazo].";".$result[realizada_atraso].";".$result[realizada];
                $contenido[NOMBRE_COLUM_GRAFICO_BAR_3] = $result[gerencia];
                $contenido[SUBTITLE_GRAFICO_BAR_3] = $result[subtitle];//'YTD - ' . date('Y');
                //echo $result[serie];
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
                //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                //$objResponse->addScript('setTimeout(function(){ init_tabla(); }, 500);');
                $objResponse->addScript('setTimeout(function(){ init_graficos(); }, 1550);');
                $objResponse->addScript('setTimeout(function(){ init_graficos_atrasos(); }, 1550);');
                $objResponse->addScript('setTimeout(function(){ PanelOperator.resize() }, 550);');
                
                /*JS init_filtrar*/
                $objResponse->addScript('$("#b-f-desde").datetimepicker();
                    $("input[name=b-filtro-fecha]:radio").change(function () {
                        if ($("#b-filtro-fecha").val()=="1") {
                            $("#b-f-desde").removeAttr("disabled");
                        }
                        else {
                            $("#b-f-desde").attr("disabled", true);
                        }
                    });

                    $( "#b-f-responsable_analisis" ).select2({
                                                        placeholder: "Selecione el revisor",
                                                        allowClear: true
                                                      }); 

                    $( "#b-responsable_analisis" ).select2({
                                                        placeholder: "Selecione el revisor",
                                                        allowClear: true
                                                      }); 
                    $( "#b-id_responsable_segui" ).select2({
                                                        placeholder: "Selecione el revisor",
                                                        allowClear: true
                                                      }); 
                    $("#b-fecha_generacion-desde").datetimepicker();
                    $("#b-fecha_acordada-desde").datetimepicker();;
                    $("#b-fecha_realizada-desde").datetimepicker();
                    $("#b-fecha_generacion-hasta").datetimepicker();
                    $("#b-fecha_acordada-hasta").datetimepicker();
                    $("#b-fecha_realizada-hasta").datetimepicker();

                    $("#tabs-hv").tab();
                    $("#tabs-hv a:first").tab("show"); 

                    PanelOperator.initPanels("");

                    ScrollBar.initScroll();
                    init_filtro_rapido();
                    init_filtro_ao_simple();');
                
                /* JS init_tabla*/
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                                    event.preventDefault();
                                                    var id = $(this).attr('tok');
                                                    $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                                    $('#myModal-Ventana-Titulo').html('');
                                                    $('#myModal-Ventana').modal('show');
                                                });

                                                $('.ver-reporte').on('click', function (event) {
                                                    event.preventDefault();
                                                    var id = $(this).attr('tok');
                                                    window.open('pages/acciones_correctivas/reporte_ac_pdf.php?id='+id,'_blank');
                                                });");
                /* JS init_graficos*/                
                $objResponse->addScript('');
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
                
                /*NOMBRE CAMPOS ACCIONES*/
                if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
                $contenido_1["N_ACCION"] = $this->nombres_columnas_ac[accion];
                $contenido_1["N_FECHA_ACORDADA"] = $this->nombres_columnas_ac[fecha_acordada];
                $contenido_1["N_TIPO"] = $this->nombres_columnas_ac[tipo];
                $contenido_1["N_ID_RESPONSABLE"] = $this->nombres_columnas_ac[id_responsable];
                $contenido_1["N_VALIDADOR_ACCION"] = $this->nombres_columnas_ac[validador_accion];
                $contenido_1[TIPOS] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_tipo_ac where id = 1 ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', null);
                $contenido_1[RESPONSABLE_ACCIONES] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres_a
                                                                            FROM mos_personal p WHERE interno = 1 AND workflow = 'S' ORDER BY nombres, apellido_paterno"
                                                                    , 'cod_emp'
                                                                    , 'nombres_a', null);
                $contenido_1[RESPONSABLE_SEGUI] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres_a
                                                                            FROM mos_personal p WHERE interno = 1  AND workflow = 'S'  and vigencia = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres_a', null);
                $ids = array('', 'S', 'N'); 
                $desc = array('-- Seleccione --', 'Si', 'No');
                $contenido_1['ALTO_POTENCIAL'] = $ut_tool->combo_array("alto_potencial", $desc, $ids, 'data-validation="required"', $val["alto_potencial"]);
                $contenido_1['ALTO_POTENCIAL_VAL'] = $ut_tool->combo_array("alto_potencial_val", $desc, $ids, '', $val["alto_potencial_val"]);
                $contenido_1[NUM_ITEMS_ESP] = 0;
                
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido_1["P_" . strtoupper($key)] =  $value;
                }     
                $contenido_1[RESPONSABLE_DESVIO] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                                                            FROM mos_personal p WHERE interno = 1 AND workflow = 'S'
                                                                            ORDER BY apellido_paterno, apellido_materno, nombres"
                                                                    , 'cod_emp'
                                                                    , 'nombres', null);
                $contenido_1[ORIGENES] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_origen_ac ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', null);
                $contenido_1[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                                                            FROM mos_personal p WHERE interno = 1 AND workflow = 'S'
                                                                            ORDER BY apellido_paterno, apellido_materno, nombres"
                                                                    , 'cod_emp'
                                                                    , 'nombres', null);
                
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
                //echo $sql;
                $contenido_1[REPORTADO_POR] .= $ut_tool->OptionsCombo($sql
                                                                    , 'cod_emp'
                                                                    , 'nombres', $_SESSION[CookCodEmp]);
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
                /*ARBOL ORGANIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();
                $parametros[opcion] = 'simple';
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $this->arbol->jstree_ao(0,$parametros);
                /*FIN ARBOL ORGANIZACIONAL*/
                /*CAMPOS DINAMICOS*/
                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                $array = $campos_dinamicos->crear_campos_dinamicos(8);
                $contenido_1[CAMPOS_DINAMICOS] = $array[html];
                $contenido_1[NOMBRE_CAMPOS_DIN] = $array[nombre_campos];
                $js = $array[js];
                
                /* EVIDENCIAS ADJUNTADAS*/
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();
                $array_nuevo = $adjuntos->crear_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_accion_c');
                $contenido_1[ARCHIVOS_ADJUNTOS] = $array_nuevo[html];
                $js .= $array_nuevo[js];
                
                /*FIN EVIDENNCIAS*/
                
                
                $contenido_1[NUM_ITEMS] = 0;
                
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
                $contenido['DESC_OPERACION'] = "Solo Guardar";
                $contenido[JS_PREVIO_GUARDAR] = "$('#notificar').val('');";
                $contenido['OTRO_BOTON_PRINCIPAL'] = '<button type="button" class="btn btn-primary" onClick="$(\'#notificar\').val(\'si\');validar(document);" id="btn-guardar-not">Guardar y Enviar</button>';
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";

                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                } 
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
                $objResponse->addScript("$('#fecha_generacion').datetimepicker();");
                $objResponse->addScript("$('#fecha_acordada').datetimepicker();");
                $objResponse->addScript("$('#fecha_realizada').datetimepicker();");
                $objResponse->addScript($js);
                $objResponse->addScript('ao_simple();');
                $objResponse->addScript("$('#tabs-hv-2').tab();$('#tabs-hv-2 a:first').tab('show');");
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
                    $parametros["fecha_generacion"] = formatear_fecha($parametros["fecha_generacion"]);
                    if (strlen($parametros["fecha_acordada"])>0)
                        $parametros["fecha_acordada"] = formatear_fecha($parametros["fecha_acordada"]);
                    if (strlen($parametros["fecha_realizada"])>0)
                        $parametros["fecha_realizada"] = formatear_fecha($parametros["fecha_realizada"]);
                    $parametros[id_proceso] = $parametros['b-id_proceso_aux'];
                    $parametros[id_organizacion] = $parametros['b-id_organizacion_aux'];
                    if (!isset($parametros[alto_potencial])) $parametros[alto_potencial] = 'N';
                    /*SE VERIFICA SI ESTA EN ELABORACION*/
                    if (!($parametros['notificar'] == 'si')) 
                        $parametros[estatus] = 'en_elaboracion';
                    else{
                        //SE VALIDA SI TIENE AL MENOS UNA ACCION DEFINIDA
                        $bandera = 0;
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            if (isset($parametros["tipo_$i"])){                                                                
                                $bandera = 1;
                                break;
                            }
                        }
                        /*SI TIENE UNA ACCION DEFINIDA EL ESTATUS ES IMPLEMENTACION DE ACCIONES*/
                        if ($bandera == 1) $parametros[estatus] = 'implementacion_acciones';
                    }
                    $respuesta = $this->ingresarAccionesCorrectivas($parametros);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        $parametros[id] = $respuesta;
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($parametros, 8);
                        /* EVIDENCIAS ADJUNTADAS*/
                        if(!class_exists('ArchivosAdjuntos')){
                            import("clases.utilidades.ArchivosAdjuntos");
                        }
                        $adjuntos = new ArchivosAdjuntos();
                        $parametros[tabla] = 'mos_acciones_evidencia';
                        $parametros[clave_foranea] = 'fk_id_accion_c';
                        $parametros[valor_clave_foranea] = $respuesta;
                        $adjuntos->guardar($parametros);
                        /*FIN EVIDENNCIAS*/
                        
                        $params = array();
                        $params[id_ac] = $respuesta;
                        //IMPORTAMOS CLASE de ACCIONES
                        import('clases.acciones_ac.AccionesAC');
                        $acciones_ac = new AccionesAC();
                        
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //GUARDAMOS LAS ACCIONES VALIDAS
                            if (isset($parametros["tipo_$i"])){                                                                
                                $params[tipo] = $parametros["tipo_$i"];
                                $params[accion] = $parametros["accion_$i"];                                        
                                $params[id_responsable] = $parametros["responsable_acc_$i"];     
                                $params[id_validador] = $parametros["validador_acc_$i"];     
                                
                                $params[fecha_acordada] = formatear_fecha($parametros["fecha_acordada_$i"]);
                                $params[orden] = $parametros["orden_din_$i"];                                  
                                $acciones_ac->ingresarAccionesAC($params);                                                               
                            }
                        }
                        
                        
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
                $contenido_1['ESTATUS'] = ($val["estatus"]);
                $contenido_1['ORIGEN_HALLAZGO'] = ($val["origen_hallazgo"]);
                $contenido_1['FECHA_GENERACION'] = ($val["fecha_generacion"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['DESCRIPCION_VAL'] = ($val["descripcion_val"]);
                $contenido_1['ANALISIS_CAUSAL'] = ($val["analisis_causal"]);
                $contenido_1['RESPONSABLE_ANALISIS'] = $val["responsable_analisis"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                $contenido_1['ID_PROCESO'] = $val["id_proceso"];
                $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
                $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
                $contenido_1['ID_RESPONSABLE_SEGUI'] = $val["id_responsable_segui"];
                $contenido_1[CHECKED_ALTO_POTENCIAL] = $val["alto_potencial"] == 'S' ? 'checked="checked"' : '';
                $ids = array('', 'S', 'N'); 
                $desc = array('Seleccione', 'Si', 'No');
                $contenido_1['ALTO_POTENCIAL'] = $ut_tool->combo_array("alto_potencial", $desc, $ids, false, $val["alto_potencial"]);
                $ids = array('', 'S', 'N'); 
                $desc = array('Seleccione', 'Si', 'No');
                $contenido_1['ALTO_POTENCIAL_VAL'] = $ut_tool->combo_array("alto_potencial_val", $desc, $ids, false, $val["alto_potencial_val"]);
                $js = '';
                $bloqueo_evidencia = 0;
                switch ($val[estatus]) {
                    case 'en_elaboracion':
                        //$js = 'formulario';

                        break;
                    case 'en_buzon':
                        //$js = 'formulario';
                        break;
                    case 'sin_responsable_analisis':
                        //$js = 'formulario';
                        //echo $_SESSION[CookCodEmp] . '=' .$val[reportado_por];
                        if (($_SESSION[CookCodEmp] != $val[reportado_por])){//&&($_SESSION[SuperUser]!='S')){
                            $js .= "$('#origen_hallazgo').attr('disabled','true');";
                            $js .= "$('#fecha_generacion').attr('readonly','true');";
                            $js .= "$('#descripcion').attr('readonly','true');";
                            $js .= "$('#responsable_desvio').attr('disabled','true');";
                            $js .= "$('#reportado_por').attr('disabled','true');";
                            $bloqueo_evidencia = 1;
                        }
                        break;
                    case 'sin_plan_accion':
                        if (($_SESSION[CookCodEmp] == $val[responsable_analisis])){
                            //SI NO ES ES RESPONSABLE DEL DESVIO BLOQUEAMOS QUE NO PUEDA CAMBAIR EL RESPONSABLE DE ANALISIS
                            if (($_SESSION[CookCodEmp] != $val[responsable_desvio])){
                                $js .= "$('#responsable_analisis').attr('disabled','true');";
                            }                                                       
                        }else if (($_SESSION[CookCodEmp] != $val[responsable_desvio])){
                            $js .= "$('#responsable_analisis').attr('disabled','true');";
                        }
                        if (($_SESSION[CookCodEmp] != $val[reportado_por])){//&&($_SESSION[SuperUser]!='S')){
                                $js .= "$('#origen_hallazgo').attr('disabled','true');";
                                $js .= "$('#fecha_generacion').attr('readonly','true');";
                                $js .= "$('#descripcion').attr('readonly','true');";
                                $js .= "$('#responsable_desvio').attr('disabled','true');";
                                $js .= "$('#reportado_por').attr('disabled','true');";
                                $bloqueo_evidencia = 1;
                        }
                        break;
                    case 'implementacion_acciones':
//                        if (($_SESSION[CookCodEmp] == $val[responsable_analisis])){
//                            //SI NO ES ES RESPONSABLE DEL DESVIO BLOQUEAMOS QUE NO PUEDA CAMBAIR EL RESPONSABLE DE ANALISIS
//                            if (($_SESSION[CookCodEmp] != $val[responsable_desvio])){
//                                $js .= "$('#responsable_analisis').attr('disabled','true');";
//                            }                                                       
//                        }else 
                        if (($_SESSION[CookCodEmp] != $val[responsable_desvio])){
                            $js .= "$('#responsable_analisis').attr('disabled','true');";
                        }
                        //if (($_SESSION[CookCodEmp] != $val[responsable_analisis]))
                        {//&&($_SESSION[SuperUser]!='S')){
                                $js .= "$('#origen_hallazgo').attr('disabled','true');";
                                $js .= "$('#fecha_generacion').attr('readonly','true');";
                                $js .= "$('#descripcion').attr('readonly','true');";
                                $js .= "$('#responsable_desvio').attr('disabled','true');";
                                $js .= "$('#reportado_por').attr('disabled','true');";
                                $bloqueo_evidencia = 1;
                        }
                        break;
                    default:
                        break;
                }
                
                
                if($_SESSION[SuperUser]=='S'){
                    $sql = "SELECT cod_emp, 
                            CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                FROM mos_personal p WHERE interno = 1 AND workflow = 'S' and vigencia = 'S'
                                ORDER BY nombres";
                }
                else
                {
                    $sql = "SELECT cod_emp, 
                            CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno))  nombres
                                FROM mos_personal p WHERE interno = 1 AND workflow = 'S' AND p.cod_emp = $val[reportado_por]  and vigencia = 'S'
                                ORDER BY nombres";
                }
                $contenido_1[USER_TOK] = $_SESSION[CookCodEmp];
                //echo $sql;
                $contenido_1[REPORTADO_POR] .= $ut_tool->OptionsCombo($sql
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["reportado_por"]);
                
                $contenido_1[RESPONSABLE_SEGUI] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1  AND workflow = 'S'  and vigencia = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_responsable_segui"]);
                $contenido_1[RESPONSABLE_DESVIO] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(p.apellido_paterno), ' ',initcap(p.apellido_materno), ' ', initcap(p.nombres))  nombres
                                                                            FROM mos_personal p WHERE interno = 1 AND workflow = 'S'  and vigencia = 'S'
                                                                            ORDER BY apellido_paterno, apellido_materno, nombres"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val[responsable_desvio]);
                $contenido_1[ORIGENES] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_origen_ac ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', $val["origen_hallazgo"]);
                $contenido_1[RESPONSABLE_ANALISIS] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1  AND workflow = 'S'  and vigencia = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["responsable_analisis"]);
                
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                } 
                //IMPORTAMOS LA CLASE DEL ARBOL ORGANIZACIONAL
                import("clases.organizacion.ArbolOrganizacional");
                $arbol = new ArbolOrganizacional();
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($key == 'id_organizacion'){
                            $contenido_1[ID_ORGANIZACIONES] = '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $this->nombres_columnas[id_organizacion] . '</label>';
                            if ($bloqueo_evidencia == 1){
                            $contenido_1[ID_ORGANIZACIONES] .= '<div class="col-md-10" style="">                                          
                                        <span id="desc-arbol">' . $arbol->BuscaOrganizacional($val) . '</span>                                        
                                        <input type="hidden" value="'.$val["id_organizacion"].'"  id="nivel" name="nivel" data-validation="required"/>                                    
                                    </div>';
                            }
                            else{
                                $contenido_1[ID_ORGANIZACIONES] .= '<div class="col-md-10" style="">  
                                        <a href="#" data-toggle="modal" style="" data-target="#myModal-Filtrar-Arbol">[Seleccionar]</a> 
                                        <span id="desc-arbol">' . $arbol->BuscaOrganizacional($val) . '</span>                                        
                                        <input type="hidden" value="'.$val["id_organizacion"].'"  id="nivel" name="nivel" data-validation="required"/>                                    
                                    </div>';
                            }
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
                $contenido_1[NOMBRE_CAMPOS_DIN] = $array[nombre_campos];
                $js .= $array[js];
                
                /* EVIDENCIAS ADJUNTADAS*/
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();       
                if ($bloqueo_evidencia == 1){
                    $array_nuevo = $adjuntos->visualizar_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_accion_c',$val["id"]);
                    $array_nuevo[html] = '<div class="col-md-19">' . $array_nuevo[html] . '</div>';
                }
                else{
                    $array_nuevo = $adjuntos->crear_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_accion_c',$val["id"]);
                }
                $contenido_1[ARCHIVOS_ADJUNTOS] = $array_nuevo[html];
                $js .= $array_nuevo[js];
                
                /*FIN EVIDENNCIAS*/
                
                
                /* Acciones */ 
                /*NOMBRE CAMPOS ACCIONES*/
                if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
                $contenido_1["N_ACCION"] = $this->nombres_columnas_ac[accion];
                $contenido_1["N_FECHA_ACORDADA"] = $this->nombres_columnas_ac[fecha_acordada];
                $contenido_1["N_TIPO"] = $this->nombres_columnas_ac[tipo];
                $contenido_1["N_ID_RESPONSABLE"] = $this->nombres_columnas_ac[id_responsable];
                $contenido_1["N_VALIDADOR_ACCION"] = $this->nombres_columnas_ac[validador_accion];
                $contenido_1[TIPOS] .= $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_tipo_ac where id = 1 ORDER BY descripcion"
                                                                    , 'id'
                                                                    , 'descripcion', null);
                $contenido_1[RESPONSABLE_ACCIONES] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres_a
                                                                            FROM mos_personal p WHERE interno = 1 AND workflow = 'S' ORDER BY nombres, apellido_paterno"
                                                                    , 'cod_emp'
                                                                    , 'nombres_a', null);
                import('clases.acciones_ac.AccionesAC');
                $ac = new AccionesAC();
                $parametros['b-id_ac'] = $val[id];
                $parametros[corder] = 'orden';
                //$parametros[corder] = 'orden';
                $ac->listarAccionesACSinPaginacion($parametros);
                $data=$ac->dbl->data;
                //print_r($data);
                $item = "";
                //$js = "";
                $i = 0;
                $contenido_1['TOK_NEW'] = time();                                
                //$ids = array('7','8','9','1','2','3','5','6','10');
                //$desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo');
                foreach ($data as $value) {                          
                    $i++;                    
                    $item = $item. '<tr id="tr-esp-' . $i . '">';                      
                    

                    {
                        
                                                                    
                        $item = $item. '<td align="center">'.
                                            ' <a href="' . $i . '"  title="Eliminar " id="eliminar_esp_' . $i . '"> ' . 
                                            //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' . 
                                             '<i class="icon icon-remove" style="width: 18px;"></i>'.
                                             '</a>' .
                                             '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
                                              <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'.
                                              
                                              '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id].'" type="hidden" >'.                                              
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.($value[orden] == '' ? $i : $value[orden]).'" type="hidden" >'.
                                       '  </td>';
                         $item = $item. '<td class="td-table-data" style="display:none;">'.
                                             '  <select id="tipo_' .$i. '" name="tipo_'.$i. '" class="form-control">' .
                                                    $ut_tool->OptionsCombo("SELECT id, 
                                                                        descripcion
                                                                            FROM mos_tipo_ac where id = 1 ORDER BY descripcion "
                                                                    , 'id'
                                                                    , 'descripcion', $value[tipo]).

                                                '</select>' .
                                        '</td>';
//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';
                         $item = $item.  '<td>' .
                                            ' <textarea id="accion_'. $i . '" name="accion_'. $i . '" class="form-control" data-validation="required">'. $value[accion] .'</textarea>'.
                                         '</td>';
                         $item = $item . '<td class="td-table-data">'.
                                            '  <select id="responsable_acc_'. $i .  '" name="responsable_acc_'. $i .  '" class="form-control" data-validation="required" data-live-search="true">'.
                                            '<option value="">-- Seleccione --</option>' . 
                                                //$('#option_responsables').val() .
                                                $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres_a
                                                                            FROM mos_personal p WHERE interno = 1 AND workflow = 'S' ORDER BY nombres, apellido_paterno"
                                                                    , 'cod_emp'
                                                                    , 'nombres_a', $value[id_responsable]) . 
                                            '</select>' .
                                       '</td>';
                         $item = $item . '<td class="td-table-data">'.
                                            '  <select id="validador_acc_'. $i .  '" name="validador_acc_'. $i .  '" class="form-control" data-validation="required" data-live-search="true">'.
                                            '<option value="">-- Seleccione --</option>' . 
                                                //$('#option_responsables').val() .
                                                $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres_a
                                                                            FROM mos_personal p WHERE interno = 1 AND workflow = 'S' ORDER BY nombres, apellido_paterno"
                                                                    , 'cod_emp'
                                                                    , 'nombres_a', $value[id_validador]) . 
                                            '</select>' .
                                       '</td>';
                         $item = $item . '<td class="td-table-data"><div class="col-sm-24" style="padding-left: 0px;padding-right: 0px;">'.
                                            '<input id="fecha_acordada_'. $i .  '"  data-date-format="DD/MM/YYYY" class="form-control" type="text" data-validation="required" value="'.$value[fecha_acordada_a].'" name="fecha_acordada_'. $i .  '" >'.
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
                        
                        
                        
                    }
                }               
                //echo $item;
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;
                
                
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
                $contenido['DESC_OPERACION'] = "Solo Guardar";
                $contenido[JS_PREVIO_GUARDAR] = "$('#notificar').val('');";
                $contenido['OTRO_BOTON_PRINCIPAL'] = '<button type="button" class="btn btn-primary" onClick="$(\'#notificar\').val(\'si\');validar(document);" id="btn-guardar-not">Guardar y Enviar</button>';

                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }
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
                $objResponse->addScript($js);
                $objResponse->addScript("$('#fecha_generacion').datetimepicker();");
                $objResponse->addScript("$('#fecha_acordada').datetimepicker();");
                $objResponse->addScript("$('#fecha_realizada').datetimepicker();");
                return $objResponse;
            }
     
 
            public function verificar($parametros)
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
                $contenido_1['ESTATUS'] = ($val["estatus"]);
                $contenido_1['ORIGEN'] = ($val["origen"]);
                $contenido_1['FECHA_GENERACION'] = ($val["fecha_generacion"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['DESC_VERIFICACION'] = ($val["desc_verificacion"]);
                $contenido_1['ANALISIS_CAUSAL'] = ($val["analisis_causal"]);
                $contenido_1['RESPONSABLE_ANALISIS'] = $val["responsable_ana"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                $contenido_1['ID_PROCESO'] = $val["id_proceso"];
                $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
                $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada_temp"]);
                $contenido_1['ID_RESPONSABLE_SEGUI'] = $val["id_responsable_segui"];
                $contenido_1[ALTO_POTENCIAL] = $val["alto_potencial"] == 'S' ? 'Si' : 'No';
                $js = '';
                $bloqueo_evidencia = 0;
                
                if (($_SESSION[CookCodEmp] != $val[responsable_desvio])){
                    $js .= "$('#id_responsable_segui').attr('disabled','true');";
                }
                if (($_SESSION[CookCodEmp] != $val[id_responsable_segui])){//&&($_SESSION[SuperUser]!='S')){

                        $js .= "$('#desc_verificacion').attr('readonly','true');";
                        $js .= "$('#fecha_realizada').attr('readonly','true');";
                        $js .= "$('#fecha_acordada').attr('disabled','true');";

                        $bloqueo_evidencia = 1;
                }
                        
                
                
                
                $contenido_1[USER_TOK] = $_SESSION[CookCodEmp];
                //echo $sql;
                $contenido_1[REPORTADO_POR] = $val["reportado_por_aux"];
                
                $contenido_1[RESPONSABLE_SEGUI] .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1  AND workflow = 'S'  and vigencia = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val["id_responsable_segui"]);
                $contenido_1[RESPONSABLE_DESVIO] .= $val[responsable_desvio_aux];                                
                
                if (count($this->campos_activos) <= 0){
                        $this->cargar_campos_activos();
                } 
                //IMPORTAMOS LA CLASE DEL ARBOL ORGANIZACIONAL
                import("clases.organizacion.ArbolOrganizacional");
                $arbol = new ArbolOrganizacional();
                foreach ($this->campos_activos as $key => $value) {
                    if ($value[0] == '1'){                        
                        if ($key == 'id_organizacion'){
                            $contenido_1[ID_ORGANIZACIONES] = '<div class="row"><div class="form-group col-md-24">
                                        <label ><b>' . $this->nombres_columnas[id_organizacion] . '</b></label><br>';
                            
                            $contenido_1[ID_ORGANIZACIONES] .= $arbol->BuscaOrganizacional($val) . '</div>';
                            
                            $contenido_1[ID_ORGANIZACIONES] .= '</div>';
                        }
                    
                        else{
                    
                            $contenido_1[ID_PROCESOS] = '<div class="row"><div class="form-group col-md-24">
                                        <label><b>' . $this->nombres_columnas[id_proceso] . '</b></label><br>';
                            $contenido_1[ID_PROCESOS] .= $this->BuscaProceso($val) . '                                   
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
                $contenido_1[NOMBRE_CAMPOS_DIN] = $array[nombre_campos];
                $js .= $array[js];
                
                /* EVIDENCIAS ADJUNTADAS*/
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();       
                //if ($bloqueo_evidencia == 1){
                    $array_nuevo = $adjuntos->visualizar_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_accion_c',$val["id"]);
//                }
//                else{
//                    $array_nuevo = $adjuntos->crear_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_accion_c',$val["id"]);
//                }
                $contenido_1[ARCHIVOS_ADJUNTOS] = $array_nuevo[html];
                $js .= $array_nuevo[js];
                
                if ($bloqueo_evidencia == 1){
                    $array_nuevo = $adjuntos->visualizar_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_accion_c_ver',$val["id"],24);
                }
                else
                    {
                    $array_nuevo = $adjuntos->crear_archivos_adjuntos('mos_acciones_evidencia', 'fk_id_accion_c_ver',$val["id"],24);
                }
                $contenido_1[ARCHIVOS_ADJUNTOS_VER] = $array_nuevo[html];
                $js .= $array_nuevo[js];
                
                /*FIN EVIDENNCIAS*/
                
                
                /* Acciones */ 
                /*NOMBRE CAMPOS ACCIONES*/
                if (count($this->nombres_columnas_ac) <= 0){
                        $this->cargar_nombres_columnas_acciones();
                }
                $contenido_1["N_ACCION"] = $this->nombres_columnas_ac[accion];
                $contenido_1["N_FECHA_ACORDADA"] = $this->nombres_columnas_ac[fecha_acordada];
                $contenido_1["N_FECHA_REALIZADA"] = $this->nombres_columnas_ac[fecha_realizada];
                $contenido_1["N_TIPO"] = $this->nombres_columnas_ac[tipo];
                $contenido_1["N_ID_RESPONSABLE"] = $this->nombres_columnas_ac[id_responsable];
                $contenido_1["N_VALIDADOR_ACCION"] = $this->nombres_columnas_ac[validador_accion];
                $contenido_1["N_ESTADO_SEGUIMIENTO"] = $this->nombres_columnas_ac[estado_seguimiento];
                
                
                import('clases.acciones_ac.AccionesAC');
                $ac = new AccionesAC();
                $parametros['b-id_ac'] = $val[id];
                $parametros[corder] = 'orden';
                //$parametros[corder] = 'orden';
                $ac->listarAccionesACSinPaginacion($parametros);
                $data=$ac->dbl->data;
                //print_r($data);
                $item = "";
                //$js = "";
                $i = 0;
                $contenido_1['TOK_NEW'] = time();                                
                //$ids = array('7','8','9','1','2','3','5','6','10');
                //$desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo');
                foreach ($data as $value) {                          
                    $i++;                    
                    $item = $item. '<tr id="tr-esp-' . $i . '">';                      
                    

                    {
                        
                                                                    
                        $item.= '<td style="vertical-align: middle;" align="center">'.$ac->semaforo_estado($value, 'estado') .'&nbsp;</td>';
                         $item = $item. '<td class="td-table-data" style="display:none;">'.
                                             $value[tipo] .
                                        '</td>';
//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';
                         $item = $item.  '<td>' .
                                             $value[accion] .
                                         '</td>';
                         $item = $item . '<td class="td-table-data">'.
                                            $value[responsable] . 
                                            
                                       '</td>';
                         $item = $item . '<td class="td-table-data">'.
                                            $value[id_responsable] . 
                                            
                                       '</td>';
                         $item = $item . '<td class="td-table-data">'.$value[fecha_acordada_a].''.
                                       '</td>';
                        $item = $item . '<td class="td-table-data">'.$value[fecha_realizada_a].''.
                                       '</td>';
                        
                        $item = $item. '</tr>' ;                    
                        
                        
                        
                        
                    }
                }               
                //echo $item;
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;
                
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                $template->setTemplate("formulario_verificacion");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario_h");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;AccionesCorrectivas";
                $contenido['PAGINA_VOLVER'] = "listarAccionesCorrectivas.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['DESC_OPERACION'] = "Solo Guardar";
                $contenido[JS_PREVIO_GUARDAR] = "$('#notificar').val('');";
                $contenido['OTRO_BOTON_PRINCIPAL'] = '<button type="button" class="btn btn-primary" onClick="$(\'#notificar\').val(\'si\');validarVer(document);" id="btn-guardar-not">Guardar y Cerrar</button>';
                if (($_SESSION[CookCodEmp] == $val[responsable_desvio])){
                    $contenido['DESC_OPERACION'] = "Guardar";
                    $contenido[JS_PREVIO_GUARDAR] = "$('#notificar').val('');";
                    $contenido['OTRO_BOTON_PRINCIPAL'] = '';
                }
                else { 
                    //echo $_SESSION[CookCodEmp] ."==". $val[responsable_desvio];
                }
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",  str_replace('validar(document);', 'validarVer(document);', $template->show()));
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                //$objResponse->addScriptCall("cargar_autocompletado");
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript($js);
                $objResponse->addScript("$('#fecha_generacion').datetimepicker();");
                $objResponse->addScript("$('#fecha_acordada').datetimepicker();");
                $objResponse->addScript("$('#fecha_realizada').datetimepicker();");
                $objResponse->addScript("$('#collapseTres').collapse('show')");
                return $objResponse;
            }
     
            
            public function actualizar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
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
                    $parametros["fecha_generacion"] = formatear_fecha($parametros["fecha_generacion"]);
                    if (strlen($parametros["fecha_acordada"])>0)
                        $parametros["fecha_acordada"] = formatear_fecha($parametros["fecha_acordada"]);
                    if (strlen($parametros["fecha_realizada"])>0)
                        $parametros["fecha_realizada"] = formatear_fecha($parametros["fecha_realizada"]);
                    $parametros[id_proceso] = $parametros['b-id_proceso_aux'];
                    $parametros[id_organizacion] = $parametros['b-id_organizacion_aux'];
                    if (!isset($parametros[alto_potencial])) $parametros[alto_potencial] = 'N';
                    /*SE VERIFICA SI SE TIENE QUE NOTIFICAR*/
                    if (!($parametros['notificar'] == 'si')) 
                        $parametros[estatus] = 'en_elaboracion';
                    else{
                        //SE VALIDA SI TIENE AL MENOS UNA ACCION DEFINIDA
                        $bandera = 0;
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            if (isset($parametros["tipo_$i"])){                                                                
                                $bandera = 1;
                                break;
                            }
                        }
                        /*SI TIENE UNA ACCION DEFINIDA EL ESTATUS ES IMPLEMENTACION DE ACCIONES*/
                        if ($bandera == 1) $parametros[estatus] = 'implementacion_acciones';
                    }
                    $respuesta = $this->modificarAccionesCorrectivas($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {

                        $parametros = $this->dbl->corregir_parametros($parametros);
                        
                        
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
            
            public function actualizar_verificacion($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
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
                    
                    if (strlen($parametros["fecha_acordada"])>0)
                        $parametros["fecha_acordada"] = formatear_fecha($parametros["fecha_acordada"]);
                    if (strlen($parametros["fecha_realizada"])>0){
                        $parametros["fecha_realizada"] = formatear_fecha($parametros["fecha_realizada"]);                    
                        $parametros[fecha_realizada_temp] = $parametros["fecha_realizada"];
                    }
                    /*SE VERIFICA SI SE TIENE QUE NOTIFICAR*/
                    if (!($parametros['notificar'] == 'si')){                         
                        $parametros[fecha_realizada_temp] = $parametros["fecha_realizada"];
                    }
                    
                    $respuesta = $this->modificarVerificacionAccionesCorrectivas($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {

                        $parametros = $this->dbl->corregir_parametros($parametros);
                        
                        
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
                /*PERMISOS*/
                import('clases.utilidades.NivelAcceso');                
                $this->restricciones = new NivelAcceso();
                $this->restricciones->cargar_acceso_nodos_explicito($parametros);
                $this->restricciones->cargar_permisos($parametros);
                /*ARBOL*/
                import('clases.organizacion.ArbolOrganizacional');
                $this->arbol = new ArbolOrganizacional();
                $grid = $this->verListaAccionesCorrectivas($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                $objResponse->addScript("init_tabla();");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
            
            /**
             * Busqueda de acciones correctivas desde los Reportes de AC
             * @param array $parametros
             * @return \xajaxResponse
             */
            public function buscarReporteListado($parametros)
            {
                $parametros['reporte_ac'] ='S';
                $grid = $this->verListaAccionesCorrectivas($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                $objResponse->addScript("init_tabla();");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
            
            
            /**
             * Actualiza la informacion del dashboard de las acciones correctivas
             * @param array $parametros
             * @return \xajaxResponse
             */
            public function buscarReporte($parametros)
            {
                $objResponse = new xajaxResponse();
                
                $result = $this->dataGraficoBarra($parametros);         
                $objResponse->addAssign('data-grafico-bar',"value",$result[atrasada].";".$result[plazo].";".$result[realizada_atraso].";".$result[realizada]);
                $objResponse->addAssign('nombre-colum-grafico-bar',"value",$result[gerencia]);
                $objResponse->addAssign('subtitle-grafico-bar',"value",$result[subtitle]);
                //print_r($result);
//                $contenido[DATA_GRAFICO_BAR] = $result[atrasada].";".$result[plazo].";".$result[realizada_atraso].";".$result[realizada];
//                $contenido[NOMBRE_COLUM_GRAFICO_BAR] = $result[gerencia];
//                $contenido[SUBTITLE_GRAFICO_BAR] = $result[subtitle];//'YTD - ' . date('Y');
                
                $result = $this->dataGraficoLinea($parametros);
                //print_r($result);
                $objResponse->addAssign('data-grafico-linea',"value",$result[serie]);
                $objResponse->addAssign('nombre-colum-grafico-linea',"value",$result[nombre_x]);
                $objResponse->addAssign('subtitle-grafico-linea',"value",$result[subtitle]);
                //
                $result = $this->dataGraficoLineaUnificada($parametros);
                //print_r($result);
                $objResponse->addAssign('data-grafico-linea-uni',"value",$result[serie]);
                $objResponse->addAssign('nombre-colum-grafico-linea-uni',"value",$result[nombre_x]);
                $objResponse->addAssign('subtitle-grafico-linea-uni',"value",$result[subtitle]);
                
                $result = $this->dataGraficoBarraVA($parametros);         
                $objResponse->addAssign('data-grafico-bar-2',"value",$result[atrasada].";".$result[plazo].";".$result[realizada_atraso].";".$result[realizada]);
                $objResponse->addAssign('nombre-colum-grafico-bar-2',"value",$result[gerencia]);
                $objResponse->addAssign('subtitle-grafico-bar-2',"value",$result[subtitle]);
                
                $result = $this->dataGraficoBarraAA($parametros);         
                $objResponse->addAssign('data-grafico-bar-3',"value",$result[atrasada].";".$result[plazo].";".$result[realizada_atraso].";".$result[realizada]);
                $objResponse->addAssign('nombre-colum-grafico-bar-3',"value",$result[gerencia]);
                $objResponse->addAssign('subtitle-grafico-bar-3',"value",$result[subtitle]);
                
                //$grid = $this->verListaAccionesCorrectivas($parametros);                
                
                
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                $objResponse->addScript("init_graficos();");
                $objResponse->addScript("init_graficos_atrasos();");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido_1["N_" . strtoupper($key)] =  $value;
                }  

                $val = $this->verAccionesCorrectivas($parametros[id]);
                

                $contenido_1['ORIGEN_HALLAZGO'] = ($val["origen"]);
                $contenido_1['FECHA_GENERACION'] = ($val["fecha_generacion"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1[DESC_VERIFICACION] = $val[desc_verificacion];
                $contenido_1['ANALISIS_CAUSAL'] = ($val["analisis_causal"]);
                /*ARBOL ORgaNIZACIONAL*/
                import('clases.organizacion.ArbolOrganizacional');
                $arbol = new ArbolOrganizacional();                    
                $contenido_1['ID_ORGANIZACION'] = $arbol->BuscaOrganizacional($val);
                
                $contenido_1['RESPONSABLE_ANALISIS'] = $val["responsable_ana"];
                
                $contenido_1['ID_PROCESO'] = $val["id_proceso"];
                $contenido_1['FECHA_ACORDADA'] = ($val["fecha_acordada"]);
                $contenido_1['FECHA_REALIZADA'] = ($val["fecha_realizada"]);
                $contenido_1['ID_RESPONSABLE_SEGUI'] = $val["responsable_segui"];
                $contenido_1['RESPONSABLE_DESVIO'] = $val["responsable_desvio_aux"];
                $contenido_1['REPORTADO_POR'] = $val["reportado_por_aux"];
                $contenido_1['ID'] = $val["id"];
                $contenido_1[ALTO_POTENCIAL] = $val["alto_potencial"] == 'S' ? 'Si' : 'No';
                
                $contenido_1['ESTADO'] = $this->semaforo_estado($val, "estado") ;
;   
                /*PARAMETROS*/
                

                /*ACCIONES*/
                if (count($this->nombres_columnas_ac) <= 0){
                    $this->cargar_nombres_columnas_acciones();
                }
        
                foreach ( $this->nombres_columnas_ac as $key => $value) {
                    $contenido_1["NA_" . strtoupper($key)] =  $value;
                }
                import('clases.acciones_ac.AccionesAC');
                $acciones_ac = new AccionesAC();

                import('clases.acciones_evidencia.AccionesEvidencia');

                $evidencia = new AccionesEvidencia();        

                $acciones_ac->listarAccionesAC(array('b-id_ac'=>$val[id],'corder'=>'dias','sorder'=>'asc'), 1, 100);
                $data=$acciones_ac->dbl->data;
                $contenido_1[TABLA_ACCIONES] = '';
                foreach ($data as $value) {
                    $contenido_1[TABLA_ACCIONES] .= '<tr>';
                    //$contenido_1[TABLA_ACCIONES] .= '<td>'.$value[tipo].'&nbsp;</td>';
                    $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[accion].'&nbsp;</td>';
                    $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[fecha_acordada_a].'&nbsp;</td>';
                    $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[fecha_realizada_a].'&nbsp;</td>';
                    $contenido_1[TABLA_ACCIONES] .= '<td>'.$value[responsable].'&nbsp;</td>';
                    $contenido_1[TABLA_ACCIONES] .= '<td style="vertical-align: middle;" align="center">'.$acciones_ac->semaforo_estado($value, 'estado') .'&nbsp;</td>';
                    $contenido_1[TABLA_ACCIONES] .= '</tr>';
                    $contenido_1[TABLA_ACCIONES] .= '';
                }
                /*TRAZAVILIDAD DE ACCIONES*/
                $contenido_1[TABLA_TRAZA] = '';
                foreach ($data as $value) {
                    $contenido_1[TABLA_TRAZA] .= '<tr>';
                    //$contenido_1[TABLA_TRAZA] .= '<td>'.$value[tipo].'&nbsp;</td>';
                    $contenido_1[TABLA_TRAZA] .= '<td>'.$value[accion].'&nbsp;</td>';
                    $evidencia->listarAccionesEvidencia(array('b-id_accion'=>$value[id],'corder'=>'fecha_evi', 'sorder'=>'desc'), 1, 1000);
                    $data_evidencias = $evidencia->dbl->data;
                    $html = "";
                    foreach ($data_evidencias as $value_Evi) {
                        $html .= "$value_Evi[observacion].<br/>"    ;
                        $html .= $evidencia->archivo_descarga_pdf($value_Evi, 'nomb_archivo');
                        $html .= "$value_Evi[id_persona], $value_Evi[fecha_evi_a]<br/><br/>"    ;
                    }                                               
                    $contenido_1[TABLA_TRAZA] .= '<td>'.$html.'&nbsp;</td>';
                    $contenido_1[TABLA_TRAZA] .= '</tr>';
                    $contenido_1[TABLA_TRAZA] .= '';
                }
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'acciones_correctivas/';
                $template->setTemplate("verAccionesCorrectivas");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Acción Correctiva - Reporte Individual";
                /*OPCION GENERAR PDF*/
                $contenido[OPCIONES] = " <li> <a id=\"a-imprimir-reporte\" title=\"Generar PDF\" tok=\"$val[id]\"  href=\"#\">
                            
                            <i class=\"icon icon-alert-print\"></i>
                        </a>  </li>";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                $html =  $template->show();
                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('detail-content',"innerHTML",$html);                
                $objResponse->addScript("$('.close-detail').click(function (event) {
                        event.preventDefault();
                        PanelOperator.hideDetail('');
                    })

                    $('.detail-show').click(function (event) {
                        event.preventDefault();
                        PanelOperator.showDetail('');
                        PanelOperator.hideSearch('');
                    });");
                $objResponse->addScript("PanelOperator.showDetail('');");  
                $objResponse->addScript("PanelOperator.resize();");
                //$objResponse->addScript("init_ver_registros();");
                $objResponse->addScript("

                                    $('#a-imprimir-reporte').on('click', function (event) {
                                        event.preventDefault();
                                        var id = $(this).attr('tok');
                                        
                                        window.open('pages/acciones_correctivas/reporte_ac_pdf.php?id='+id,'_blank');
                                    });");                
                return $objResponse;
                //return $template->show();
            }
     
 }?>