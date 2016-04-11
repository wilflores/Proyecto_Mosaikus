<?php
 import("clases.interfaz.Pagina");        
        class HojadeVida extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $per_crear;
        private $per_editar;
        private $per_eliminar;
            
            public function HojadeVida(){
                parent::__construct();
                $this->asigna_script('Hoja_de_Vida/Hoja_de_Vida.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = array();
                $this->contenido = array();
                $this->per_crear = $this->per_editar = $this->per_eliminar = 'N';
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 2";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 2";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }
            
            /**
             * Busca los permisos que tiene el usuario en el modulo
             */
            private function cargar_permisos($parametros){
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();
                    $data_permisos = $acceso->obtenerPermisosModulo($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros['b-id_organizacion']);                    
                    foreach ($data_permisos as $value) {
                        if ($value[nuevo] == 'S'){
                            $this->per_crear =  'S';
                            break;
                        }
                    }                                               
                    foreach ($data_permisos as $value) {
                        if ($value[modificar] == 'S'){
                            $this->per_editar =  'S';
                            break;
                        }
                    } 
                    foreach ($data_permisos as $value) {
                        if ($value[eliminar] == 'S'){
                            $this->per_eliminar =  'S';
                            break;
                        }
                    } 
                }
            }
                        
            
        public function archivo($tupla)
        {
            if (strlen($tupla[nom_archivo])>0){
                //<img class=\"SinBorde\"  src=\"diseno/images/pdf.png\">
                if ($tupla[tipo]=='Cap'){
                    $html = "<a title=\"Ver $tupla[nom_archivo]\" target=\"_blank\" href=\"pages/personal_capacitacion/descargar_archivo.php?id=$tupla[id_registro]&token=" . md5($tupla[id_registro]) ."\">
                            
                            <i class=\"icon icon-view-document\"></i>
                        </a>";
                }
                else{
                    $html = "<a target=\"_blank\" title=\"Ver $tupla[nom_archivo]\" href=\"pages/Hoja_de_Vida/descargar_archivo.php?id=$tupla[cod_hoja_vida]&token=" . md5($tupla[cod_hoja_vida]) ."\">                            
                            <i class=\"icon icon-view-document\"></i>
                        </a>";
                }
                return $html;
            }
            return "No aplica";
        }
        
        public function columna_accion($tupla)
        {
            $html = "&nbsp;";
            if (strlen($tupla[id_registro])<=0){
                if($this->per_editar == 'S'){
                    $html .= '<a onclick="javascript:editarHojadeVida(\''.$tupla[cod_hoja_vida].'\' );">
                                <i style="cursor:pointer" class="icon icon-edit"  title="Editar Anotaci&oacute;n" style="cursor:pointer"></i>
                            </a>';
                }                
                if($this->per_eliminar == 'S'){
                    $html .= '<a onclick="javascript:eliminarHojadeVida(\''.$tupla[cod_hoja_vida].'\');;">
                                <i style="cursor:pointer" class="icon icon-remove" title="Eliminar Anotaci&oacute;n" style="cursor:pointer"></i>
                            </a>';
                }
            }
            return $html;
        }
        
        public function formato_codigo($tupla){
            //return str_pad($tupla[cod_hoja_vida], 6, "0", STR_PAD_LEFT);
            $cod = $tupla[cod_hoja_vida];
            //echo '0'.str_pad($cod, 5, '0', STR_PAD_LEFT);
            return '&nbsp;'.str_pad($cod, 6, '0', STR_PAD_LEFT);
        }

             public function verHojadeVida($id){
                $atr=array();
                $sql = "SELECT cod_hoja_vida
                            ,cod_emp
                            ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha
                            ,anotacion
                            ,id_registro
                            ,1 archivo
                            ,contenttype
                            ,nom_archivo
                            ,tipo

                         FROM mos_personal_hoja_vida 
                         WHERE cod_hoja_vida = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verHojadeVidaArchivo($id){
                $atr=array();
                $sql = "SELECT                             
                            archivo
                            ,nom_archivo
                         FROM mos_personal_hoja_vida 
                         WHERE cod_hoja_vida = $id "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_hoja_vida) total_registros
                         FROM mos_personal_hoja_vida";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarHojadeVida($atr,$archivo){
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);   
                    $atr[archivo] = $archivo;
                    $atr[cod_hoja_vida] = $this->codigo_siguiente();
                    if ($atr[id_registro] == '')
                        $atr[id_registro] = 'NULL';
                    $sql = "INSERT INTO mos_personal_hoja_vida(cod_hoja_vida,cod_emp,fecha,anotacion,id_registro,archivo,contenttype,nom_archivo,tipo)
                            VALUES(
                                $atr[cod_hoja_vida],$atr[cod_emp],'$atr[fecha]','$atr[anotacion]',$atr[id_registro],'$atr[archivo]','$atr[contenttype]','$atr[nom_archivo]','$atr[tipo]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                   
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_personal_hoja_vida ' . $atr[descripcion_ano], 'mos_personal_hoja_vida');
                      */
                    $nuevo = "Cod Hoja Vida: \'$atr[cod_hoja_vida]\', Cod Emp: \'$atr[cod_emp]\', Fecha: \'$atr[fecha]\', Anotacion: \'$atr[anotacion]\', Id Registro: \'$atr[id_registro]\', Tipo: \'$atr[tipo]\' ";
                    $this->registraTransaccionLog(28,$nuevo,'', '');
                    return "El registro ha sido ingresado con exito";
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

            public function modificarHojadeVida($atr,$archivo){
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
                        $atr[contenttype] = "'$atr[contenttype]'";
                        $atr[nom_archivo] = "'$atr[nom_archivo]'";      
                    }
                    $sql = "UPDATE mos_personal_hoja_vida SET                            
                                    cod_emp = $atr[cod_emp],fecha = '$atr[fecha]',anotacion = '$atr[anotacion]',id_registro = $atr[id_registro],archivo = $atr[archivo],contenttype = $atr[contenttype],nom_archivo = $atr[nom_archivo]
                            WHERE  cod_hoja_vida = $atr[id]";         
                    $val = $this->verHojadeVida($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Hoja Vida: \'$atr[cod_hoja_vida]\', Cod Emp: \'$atr[cod_emp]\', Fecha: \'$atr[fecha]\', Anotacion: \'$atr[anotacion]\'";
                    $anterior = "Cod Hoja Vida: \'$val[cod_hoja_vida]\', Cod Emp: \'$val[cod_emp]\', Fecha: \'$val[fecha]\', Anotacion: \'$val[anotacion]\'";
                    $this->registraTransaccionLog(29,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el HojadeVida ' . $atr[descripcion_ano], 'mos_personal_hoja_vida');
                    */
                    return "Anotaci&oacute;n ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarHojadeVida($atr, $pag, $registros_x_pagina){
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
                         FROM mos_personal_hoja_vida 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_hoja_vida"])>0)
                        $sql .= " AND cod_hoja_vida = '". $atr[b-cod_hoja_vida] . "'";
                    if (strlen($atr["b-cod_emp"])>0)
                               $sql .= " AND cod_emp = '". $atr[b-cod_emp] . "'";
                    if (strlen($atr['b-fecha-desde'])>0)                        
                    {
                        $atr['b-fecha-desde'] = formatear_fecha($atr['b-fecha-desde']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha-hasta'])>0)                        
                    {
                        $atr['b-fecha-hasta'] = formatear_fecha($atr['b-fecha-hasta']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-anotacion"])>0)
                               $sql .= " AND anotacion = '". $atr[b-anotacion] . "'";
                    if (strlen($atr["b-id_registro"])>0)
                               $sql .= " AND id_registro = '". $atr[b-id_registro] . "'";
                    if (strlen($atr["b-archivo"])>0)
                               $sql .= " AND archivo = '". $atr[b-archivo] . "'";
                    if (strlen($atr["b-contenttype"])>0)
                        $sql .= " AND contenttype = '". $atr[b-contenttype] . "'";
                    if (strlen($atr["b-nom_archivo"])>0)
                        $sql .= " AND upper(nom_archivo) like '%" . strtoupper($atr["b-nom_archivo"]) . "%'";
                    if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
                    
                    $sql_where  = '';
                    if (strlen($atr["b-cod_emp"])>0)
                               $sql_where .= " AND hv.cod_emp = ". $atr['b-cod_emp'] . "";
                    $sql = "SELECT cod_hoja_vida
                                    ,cod_emp
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha
                                    ,anotacion
                                    ,id_registro
                                    ,1 archivo
                                    ,contenttype
                                    ,nom_archivo
                                    ,tipo

                                     $sql_col_left
                            FROM mos_personal_hoja_vida $sql_left
                            WHERE 1 = 1 ";
                    $sql = "SELECT cod_hoja_vida
                                    ,cod_emp
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha
                                    ,anotacion
                                    ,id_registro
                                    ,1 archivo
                                    ,contenttype
                                    ,nom_archivo
                                    ,tipo                                    
                            FROM mos_personal_hoja_vida hv				                            
                            WHERE 1 = 1 $sql_where and (tipo is null or tipo = '') 
                             UNION ALL
                             SELECT cod_hoja_vida
                                    ,cod_emp
                                    ,DATE_FORMAT(mi.fecha, '%d/%m/%Y') fecha
                                    ,mi.descripcion anotacion
                                    ,id_registro
                                    ,1 archivo
                                    ,contenttype
                                    ,nom_archivo
                                    ,tipo                                    
                            FROM mos_personal_hoja_vida hv
                            INNER JOIN mos_incidentes mi ON hv.tipo = 'Inc' and hv.id_registro = mi.cod_incidente
                            WHERE 1 = 1 $sql_where and (tipo is null or tipo = 'Inc')
                           UNION ALL
                           SELECT cod_hoja_vida
                                    ,cod_emp
                                    ,DATE_FORMAT(ca.fecha, '%d/%m/%Y') fecha
                                    ,concat('Curso: ',cu.identificacion, '. ',ca.observacion) anotacion
                                    ,id_registro
                                    ,1 archivo
                                    ,hv.contenttype
                                    ,ca.nom_archivo
                                    ,tipo                                    
                            FROM mos_personal_hoja_vida hv
                            INNER JOIN mos_personal_capacitacion ca ON hv.tipo = 'Cap' and hv.id_registro = ca.cod_capacitacion
                            INNER JOIN mos_cursos cu ON cu.cod_curso = ca.cod_capacitacion
                            WHERE 1 = 1 $sql_where and (tipo is null or tipo = 'Cap')
                            UNION ALL
                            SELECT cod_hoja_vida
                                    ,cod_emp
                                    ,DATE_FORMAT(al.fechaAccidente, '%d/%m/%Y') fecha
                                    ,al.circunstancia anotacion
                                    ,id_registro
                                    ,1 archivo
                                    ,hv.contenttype
                                    ,hv.nom_archivo
                                    ,tipo                                    
                            FROM mos_personal_hoja_vida hv
                            INNER JOIN mos_accidentes_ley al ON hv.tipo = 'Acc' and hv.id_registro = al.id_accidente_ley
                        WHERE 1 = 1 $sql_where and (tipo is null or tipo = 'Acc')";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_hoja_vida"])>0)
                        $sql .= " AND cod_hoja_vida = ". $atr['b-cod_hoja_vida'] . "";
                    
                    if (strlen($atr['b-fecha-desde'])>0)                        
                    {
                        $atr['b-fecha-desde'] = formatear_fecha($atr['b-fecha-desde']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha-hasta'])>0)                        
                    {
                        $atr['b-fecha-hasta'] = formatear_fecha($atr['b-fecha-hasta']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-anotacion"])>0)
                               $sql .= " AND anotacion = '". $atr[b-anotacion] . "'";
                    if (strlen($atr["b-id_registro"])>0)
                               $sql .= " AND id_registro = '". $atr[b-id_registro] . "'";
                    if (strlen($atr["b-archivo"])>0)
                        $sql .= " AND archivo = '". $atr[b-archivo] . "'";
                    if (strlen($atr["b-contenttype"])>0)
                        $sql .= " AND contenttype = '". $atr[b-contenttype] . "'";
                    if (strlen($atr["b-nom_archivo"])>0)
                        $sql .= " AND upper(nom_archivo) like '%" . strtoupper($atr["b-nom_archivo"]) . "%'";
                    if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function eliminarHojadeVida($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_personal_hoja_vida", "cod_hoja_vida = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaHojadeVida($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
//                $this->listarHojadeVida($parametros, $parametros['pag'], $reg_por_pagina);
                $this->listarHojadeVida($parametros, $parametros['pag'], 100);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblHojadeVida", "");
                $config_col=array(
                    
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["cod_hoja_vida"], "cod_hoja_vida", $parametros, 'link_titulos_hv')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["cod_emp"], "cod_emp", $parametros, 'link_titulos_hv')),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["fecha"], "fecha", $parametros, 'link_titulos_hv')),
               array( "width"=>"30%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["anotacion"], "anotacion", $parametros, 'link_titulos_hv')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["id_registro"], "id_registro", $parametros, 'link_titulos_hv')),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["archivo"], "archivo", $parametros, 'link_titulos_hv')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["contenttype"], "contenttype", $parametros, 'link_titulos_hv')),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["nom_archivo"], "nom_archivo", $parametros, 'link_titulos_hv')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas["tipo"], "tipo", $parametros, 'link_titulos_hv'))
                );
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(utf8_decode($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array('funcion'=> 'columna_accion');
                //$func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 10;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verHojadeVida','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver HojadeVida'>"));
                */
                //if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                //    array_push($func,array('nombre'=> 'editarHojadeVida','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar HojadeVida'>"));
                //if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                //    array_push($func,array('nombre'=> 'eliminarHojadeVida','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar HojadeVida'>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        //case 0:
                        case 2:
//                        /case 3:
                        //case 4:
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
                $grid->setParent($this);
                $grid->setFuncion("archivo", "archivo");
//                $grid->setFuncion("cod_hoja_vida", "formato_codigo");
                
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
            $this->listarHojadeVida($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblHojadeVida", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["cod_hoja_vida"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["cod_emp"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["fecha"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["anotacion"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["id_registro"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["archivo"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["contenttype"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["nom_archivo"], ENT_QUOTES, "UTF-8")),
                array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas["tipo"], ENT_QUOTES, "UTF-8")),
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
 
 
            public function indexHojadeVida($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="fecha";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-5"; 
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
                
                if(!class_exists('Personas')){
                    import("clases.personas.Personas");
                }
                $persona = new Personas();
                $ver_persona = $persona->verPersonas($parametros[id]);
                $parametros['b-id_organizacion'] = $ver_persona[id_organizacion];
                $parametros['b-cod_emp'] = $parametros[id];
                $contenido['COD_EMP'] = $parametros[id];
                $contenido['NOMBRES'] = $ver_persona['nombres'] . ' ' . $ver_persona['apellido_paterno'] . ' ' . $ver_persona['apellido_materno'];
                $contenido[RUT] = formatear_rut($ver_persona);
                $contenido[FECHA_NACIMIENTO] = $ver_persona[fecha_nacimiento];
                $contenido[CARGO] = $ver_persona[cargo];
                $this->cargar_permisos($parametros);
                $grid = $this->verListaHojadeVida($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_HojadeVida();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;HojadeVida';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['OPC'] = "new";
                $contenido['ID'] = "-1";

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'Hoja_de_Vida/';

                $template->setTemplate("busqueda");
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
                    $contenido_1["P_" . strtoupper($key)] =  $value;
                } 
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'personas/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'Hoja_de_Vida/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                //$objResponse->addAssign('modulo_actual',"value","Hoja_de_Vida");
                $objResponse->addIncludeScript(PATH_TO_JS . 'Hoja_de_Vida/Hoja_de_Vida.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$('#myModal-Ventana').modal('show');");
                $objResponse->addScript("$('#myModal-Ventana-Titulo').html('Hoja de Vida');");
                
                $objResponse->addScript("$('#hv-fecha').datepicker();");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show');"
                        . "ScrollBar.initScroll();");
                if ($this->per_crear == 'N'){                    
                    $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");
                }
                
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'Hoja_de_Vida/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;HojadeVida";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;HojadeVida";
                $contenido['PAGINA_VOLVER'] = "listarHojadeVida.php";
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
                          });");$objResponse->addScript("$('#fecha').datepicker();");
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
                    
                    $respuesta = $this->ingresarHojadeVida($parametros, $archivo);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        //$objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
                        if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                        {
                            unlink(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                        }
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
                $objResponse = new xajaxResponse();
                $val = $this->verHojadeVida($parametros[id]); 

                $objResponse->addScript("$('#hv-fecha').val('$val[fecha]');");
                $objResponse->addScript("$('#hv-anotacion').html('$val[anotacion]');");
                $objResponse->addScript("$('#id-hv').val('$parametros[id]');");
                $objResponse->addScript("$('#opc-hv').val('upd');");
                if (strlen($val[nom_archivo])>0){
                       $objResponse->addScript("$('#tabla_fileUpload').hide();");
                       $objResponse->addScript(" $('#info_nombre').html('$val[nom_archivo]');");
                       //$objResponse->addScript(" $('#filename').val(respuesta[0].filename);");
                       //$objResponse->addScript(" $('#tamano').val(respuesta[0].tamano);");
                       //$objResponse->addScript(" $('#tipo_doc').val(respuesta[0].tipo);");
                       //$objResponse->addScript(" $('#estado_actual').val(respuesta[0].estado_actual);");
                       $objResponse->addScript(" $('#info_archivo_adjunto').show();");
                }
                //$contenido_1['COD_HOJA_VIDA'] = $val["cod_hoja_vida"];
                //$contenido_1['COD_EMP'] = $val["cod_emp"];
                //$contenido_1['FECHA'] = ($val["fecha"]);
                //$contenido_1['ANOTACION'] = $val["anotacion"];
                //$contenido_1['ID_REGISTRO'] = $val["id_registro"];
                //$contenido_1['ARCHIVO'] = $val["archivo"];
                //$contenido_1['CONTENTTYPE'] = $val["contenttype"];
                //$contenido_1['NOM_ARCHIVO'] = ($val["nom_archivo"]);
                //$contenido_1['TIPO'] = ($val["tipo"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'Hoja_de_Vida/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;HojadeVida";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;HojadeVida";
                $contenido['PAGINA_VOLVER'] = "listarHojadeVida.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

                $template->setVars($contenido);
                
                //$objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                //$objResponse->addScriptCall("calcHeight");
                //$objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                //$objResponse->addScript("$('#tabs-hv a:first').tab('show');");
                $objResponse->addScript("$('.nav-tabs a[href=\"#hv-red\"]').tab('show');");
                $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').show();");
                //$objResponse->addScript("$('#fecha').datepicker();");
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
                    $respuesta = $this->modificarHojadeVida($parametros, $archivo);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                        {
                            unlink(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                        }
                        //$objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                        $objResponse->addScript("reset_formulario();");
                        $objResponse->addScript("verPagina_hv(1,1);");
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
                $val = $this->verHojadeVida($parametros[id]);
                $respuesta = $this->eliminarHojadeVida($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    //$objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    $objResponse->addScript("reset_formulario();");
                    $objResponse->addScript("verPagina_hv(1,1);");
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
            public function buscar($parametros)
            {
                if(!class_exists('Personas')){
                    import("clases.personas.Personas");
                }
                $persona = new Personas();
                $ver_persona = $persona->verPersonas($parametros['b-cod_emp']);
                $parametros['b-id_organizacion'] = $ver_persona[id_organizacion];                
                $this->cargar_permisos($parametros);
                $grid = $this->verListaHojadeVida($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid-hv',"innerHTML",$grid[tabla]);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                if ($this->per_crear == 'N'){                    
                    $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");
                }
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verHojadeVida($parametros[id]);

                            $contenido_1['COD_HOJA_VIDA'] = $val["cod_hoja_vida"];
            $contenido_1['COD_EMP'] = $val["cod_emp"];
            $contenido_1['FECHA'] = ($val["fecha"]);
            $contenido_1['ANOTACION'] = $val["anotacion"];
            $contenido_1['ID_REGISTRO'] = $val["id_registro"];
            $contenido_1['ARCHIVO'] = $val["archivo"];
            $contenido_1['CONTENTTYPE'] = $val["contenttype"];
            $contenido_1['NOM_ARCHIVO'] = ($val["nom_archivo"]);
            $contenido_1['TIPO'] = ($val["tipo"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'Hoja_de_Vida/';
                $template->setTemplate("verHojadeVida");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la HojadeVida";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>