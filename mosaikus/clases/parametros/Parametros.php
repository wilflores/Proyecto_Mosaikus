<?php
 import("clases.interfaz.Pagina");        
        class Parametros extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function Parametros(){
                parent::__construct();
                $this->asigna_script('parametros/parametros.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros($modulo){
                $sql = "SELECT cod_parametro, espanol,tipo FROM mos_parametro WHERE cod_categoria = '$modulo' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            private function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 7";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 7";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }

            public function ArmaSqlParamsDinamicos($cod_categoria,$k,$parametros,$id ){
                $sql_left = $sql_col_left = "";
               // print_r($parametros);
                foreach ($parametros as $value) {
                    switch ($value[tipo]) {
                        case '2':
                            $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = $cod_categoria AND $id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                            $sql_col_left .= ",p$k.descripcion p$k ";
                            break;
                        case '3':
                            $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = $cod_categoria AND $id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                            $sql_col_left .= ",p$k.descripcion p$k ";
                            break;
                        case '1'://Combo
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                            $sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                                    inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                                where t1.cod_categoria=$cod_categoria and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = $id "; 
                            break;
                        case '4':
                            $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = $cod_categoria AND $id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro] "
                                . " left join mos_personal per$k on per$k.cod_emp = p$k.cod_parametro_det "; 
                            $sql_col_left .= ",CONCAT(CONCAT(UPPER(LEFT(per$k.nombres, 1)), LOWER(SUBSTRING(per$k.nombres, 2))),' ', CONCAT(UPPER(LEFT(per$k.apellido_paterno, 1)), LOWER(SUBSTRING(per$k.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(per$k.apellido_materno, 1)), LOWER(SUBSTRING(per$k.apellido_materno, 2)))) as p$k ";
                            break;
                        case '5':
                            $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = $cod_categoria AND $id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]";                                     
                            $sql_col_left .= ",CASE WHEN p$k.descripcion = '1' "
                                    . "THEN 'Bueno' "
                                    . "ELSE 'Malo' END p$k ";
                            break;
                        case '6':
                            $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = $cod_categoria AND $id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                            $sql_col_left .= ",p$k.descripcion p$k ";
                            break;
                        case '7':
                            $sql_left .= " LEFT JOIN mos_parametro_modulos p$k on p$k.cod_categoria = $cod_categoria AND $id=p$k.id_registro and p$k.cod_parametro=$value[cod_parametro]"; 
                            $sql_col_left .= ",p$k.descripcion p$k ";
                            break;
                        default:
                            break;
                    }

                $k++;
                } 
                $retorno = array();
                $retorno[sql_left]=$sql_left;
                $retorno[sql_col_left]=$sql_col_left;
                $retorno[k]=$k;
               // print_r($retorno);
                return $retorno;
            }


            public function verParametros($id){
                $atr=array();
                $sql = "SELECT cod_categoria
                            ,cod_parametro
                            
                            ,espanol
                            ,ingles
                            ,vigencia
                            ,tipo

                         FROM mos_parametro 
                         WHERE cod_parametro = $id AND cod_categoria = $_SESSION[cod_categoria]"; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(cod_parametro) total_registros
                         FROM mos_parametro";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarParametros($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[cod_parametro] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_parametro(cod_categoria,cod_parametro,espanol,ingles,vigencia,tipo)
                            VALUES(
                                $_SESSION[cod_categoria],$atr[cod_parametro],'$atr[espanol]','$atr[ingles]','$atr[vigencia]','$atr[tipo]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_parametro ' . $atr[descripcion_ano], 'mos_parametro');
                      */
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Cod Parametro: \'$atr[cod_parametro]\', Espanol: \'$atr[espanol]\', Ingles: \'$atr[ingles]\', Vigencia: \'$atr[vigencia]\', Tipo: \'$atr[tipo]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', '');
                    return "El Parametro '$atr[espanol]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarParametroDinamico($atr,$modulo){
                try {                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    if ($atr[cod_parametro_det] == '') 
                        $atr[cod_parametro_det] = 0;
                    $sql = "INSERT INTO mos_parametro_modulos(cod_categoria,cod_parametro,cod_parametro_det,id_registro,cod_categoria_aux,descripcion)
                            VALUES(
                                $modulo,$atr[cod_parametro],$atr[cod_parametro_det],$atr[id_registro],$modulo,'$atr[descripcion]'
                                )";
                    $this->dbl->insert_update($sql);
                    return "El mos_personal '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function eliminarParametrosDinamicos($atr,$modulo){
                    try {
                        $respuesta = $this->dbl->delete("mos_parametro_modulos", "cod_categoria = $modulo AND id_registro = $atr[id_registro]");
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
            
            public function guardar_parametros_dinamicos($parametros,$modulo){
                
                    if (count($this->parametros) <= 0){
                        $this->cargar_parametros($modulo);
                    }                

                    $params[id_registro] = $parametros[id];
                    $this->eliminarParametrosDinamicos($params,$modulo);
                    foreach ($this->parametros as $value) {  
                        switch ($value[tipo]) {
                            case '1':
                            case '4':
                                $params[cod_parametro_det] = $parametros["campo_".$value[cod_parametro]];
                                $params[cod_parametro] = $value[cod_parametro];
                                $params[descripcion] = '';
                                if (strlen($params[cod_parametro_det])>0)
                                    $this->ingresarParametroDinamico($params,$modulo);
                                break;
                            default :
                                $params[descripcion] = $parametros["campo_".$value[cod_parametro]];
                                $params[cod_parametro] = $value[cod_parametro];
                                $params[cod_parametro_det] = '0';
                                if (strlen($params[cod_parametro_det])>0)
                                    $this->ingresarParametroDinamico($params,$modulo);
                                break;
                        }
                    }
            }

            public function registraTransaccionLog($accion,$descr, $tabla, $id = ''){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]')";            
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarParametros($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_parametro SET                            
                                    espanol = '$atr[espanol]',ingles = '$atr[ingles]',vigencia = '$atr[vigencia]',tipo = '$atr[tipo]'
                            WHERE  cod_parametro = $atr[id] AND cod_categoria = $_SESSION[cod_categoria]";      
                    $val = $this->verParametros($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Cod Parametro: \'$atr[cod_parametro]\', Espanol: \'$atr[espanol]\', Ingles: \'$atr[ingles]\', Vigencia: \'$atr[vigencia]\', Tipo: \'$atr[tipo]\', ";
                    $anterior = "Cod Categoria: \'$val[cod_categoria]\', Cod Parametro: \'$val[cod_parametro]\', Espanol: \'$val[espanol]\', Ingles: \'$val[ingles]\', Vigencia: \'$val[vigencia]\', Tipo: \'$val[tipo]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Parametros ' . $atr[descripcion_ano], 'mos_parametro');
                    */
                    //return "actualizado con exito";
                    return "El Parametro '$atr[espanol]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
             public function listarParametros($atr, $pag, $registros_x_pagina){
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
                         FROM mos_parametro 
                         WHERE cod_categoria = $_SESSION[cod_categoria] ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(espanol) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(ingles) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";                        
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-cod_parametro"])>0)
                        $sql .= " AND cod_parametro = ". $atr["b-cod_parametro"] . "";
            if (strlen($atr["b-espanol"])>0)
                        $sql .= " AND upper(espanol) like '%" . strtoupper($atr["b-espanol"]) . "%'";
            if (strlen($atr["b-ingles"])>0)
                        $sql .= " AND upper(ingles) like '%" . strtoupper($atr["b-ingles"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
            if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
                              
                    $sql = "SELECT 
                        cod_parametro
                                ,cod_categoria
                                ,(select count(cod_parametro) from mos_parametro_det where cod_categoria = $_SESSION[cod_categoria] AND  cod_parametro=p.cod_parametro) as cantidad
                                ,espanol
                                ,ingles
                                ,vigencia
                                ,CASE tipo 
                                    WHEN 1 THEN 'Combo'
                                    WHEN 2 THEN 'Texto Largo'
                                    WHEN 3 THEN 'Fecha'
                                    WHEN 4 THEN 'Personal'
                                    WHEN 5 THEN 'Semaforo'
                                    WHEN 6 THEN 'Numero'
                                    ELSE 'Texto Corto'
                                 END tipo
                                     $sql_col_left
                            FROM mos_parametro p $sql_left
                            WHERE cod_categoria = $_SESSION[cod_categoria] ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(espanol) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                        $sql .= " OR (upper(ingles) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";                        
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
                        if (strlen($atr["b-cod_parametro"])>0)
                        $sql .= " AND cod_parametro = ". $atr["b-cod_parametro"] . "";
            if (strlen($atr["b-espanol"])>0)
                        $sql .= " AND upper(espanol) like '%" . strtoupper($atr["b-espanol"]) . "%'";
            if (strlen($atr["b-ingles"])>0)
                        $sql .= " AND upper(ingles) like '%" . strtoupper($atr["b-ingles"]) . "%'";
            if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
            if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarParametros($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_parametro_det 
                                        WHERE cod_parametro = " . $atr[id] . " AND cod_categoria = $_SESSION[cod_categoria]";                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];  
                        if ($total > 0){
                            return "- No se puede eliminar el parametros, existen items asignados.";
                        }
                        
                         $sql = "SELECT COUNT(*) total_registros
                                    FROM mos_parametro_modulos 
                                    WHERE cod_parametro = " . $atr[id] . " AND cod_categoria = $_SESSION[cod_categoria]";                                  
                        $total_registros = $this->dbl->query($sql, array());
                        $total = $total_registros[0][total_registros]; 
                        if ($total > 0){
                            return "- No se puede eliminar el parametros, existen datos asignados.";
                        }
                        $respuesta = $this->dbl->delete("mos_parametro", "cod_parametro = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaParametros($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarParametros($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblParametros", "");
                $config_col=array(
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_parametro], "cod_parametro", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_categoria], "cod_categoria", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("# Items", "cantidad", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[espanol], "espanol", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[ingles], "ingles", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[tipo], "tipo", $parametros))
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

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 7;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verParametros','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Parametros'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarParametros','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\" title='Editar Parametros'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarParametros','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\" title='Eliminar Parametros'></i>"));
                array_push($func,array('condicion'=>array('columna'=>'tipo', 'valor'=> "=='Combo'"), 'nombre'=> 'verItemsParametros','imagen'=> "<i style='cursor:pointer' class=\"icon icon-more\" title='Ver Items de Parametros'></i>"));
               
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
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarParametros($parametros, 1, 100000);
            $data=$this->dbl->data;
            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblParametros", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_parametro], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_categoria], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>("# Items")),               
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[espanol], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[ingles], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[tipo], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexParametros($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                if ($parametros['corder'] == null) $parametros['corder']="espanol";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-4-5-6"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
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
               $parametros['b-cod_categoria'] = $_SESSION[cod_categoria] = 1; 
               if($_SESSION[ParamAdic]=='formulario'){
                    $parametros['b-cod_categoria'] = $_SESSION[cod_categoria] = 15;
               }
                //print_r($parametros);
                $grid = $this->verListaParametros($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Parametros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Parametros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';

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
                $objResponse->addAssign('modulo_actual',"value","parametros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'parametros/parametros.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");');
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                return $objResponse;
            }
            
            public function indexParametrosPersonas($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                if ($parametros['corder'] == null) $parametros['corder']="espanol";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-4-5-6"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
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
                $parametros['b-cod_categoria'] = $_SESSION[cod_categoria] = 3;
                //print_r($parametros);
                $grid = $this->verListaParametros($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Parametros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Parametros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';

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
                $objResponse->addAssign('modulo_actual',"value","parametros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'parametros/parametros.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");');
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                return $objResponse;
            }
            
            public function indexParametrosCorrecciones($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                if ($parametros['corder'] == null) $parametros['corder']="espanol";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-4-5-6"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
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
                $parametros['b-cod_categoria'] = $_SESSION[cod_categoria] = 13;
                //print_r($parametros);
                $grid = $this->verListaParametros($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Parametros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Parametros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';

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
                $objResponse->addAssign('modulo_actual',"value","parametros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'parametros/parametros.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");');
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                return $objResponse;
            }
            
            public function indexParametrosAC($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                if ($parametros['corder'] == null) $parametros['corder']="espanol";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-4-5-6"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
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
                $parametros['b-cod_categoria'] = $_SESSION[cod_categoria] = 8;
                //print_r($parametros);
                $grid = $this->verListaParametros($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Parametros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Parametros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';

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
                $objResponse->addAssign('modulo_actual',"value","parametros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'parametros/parametros.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");');
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                return $objResponse;
            }
            
            public function indexParametrosInspecciones($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                if ($parametros['corder'] == null) $parametros['corder']="espanol";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-4-5-6"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
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
                $parametros['b-cod_categoria'] = $_SESSION[cod_categoria] = 14;
                //print_r($parametros);
                $grid = $this->verListaParametros($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Parametros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Parametros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';

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
                $objResponse->addAssign('modulo_actual',"value","parametros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'parametros/parametros.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");');
                $objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                return $objResponse;
            }
            
            
            public function crear_campos_dinamicos($modulo,$id_registro=null,$col_lab=4,$col_cam=10){
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $html = '';
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros($modulo);
                }  
                
                $desc_valores_params = $valores_params = array();
                if ($id_registro!= null){
                    $sql = "SELECT cod_parametro, cod_parametro_det,descripcion FROM mos_parametro_modulos WHERE cod_categoria = $modulo and id_registro = $id_registro";

                    $data_params = $this->dbl->query($sql, array());


                    foreach ($data_params as $value_data_params) {
                        $valores_params[$value_data_params[cod_parametro]]       = $value_data_params[cod_parametro_det];
                        $desc_valores_params[$value_data_params[cod_parametro]]  = $value_data_params[descripcion];
                    }                
                }
                
                $js = $html = "";
                foreach ($this->parametros as $value) {    
                    switch ($value[tipo]) {
                        case '1':
                            $sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria='$modulo' and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
                            $data = $this->dbl->query($sql, array());
                            $ids = array(''); 
                            $desc = array('-- Seleccione --');
                            foreach ($data as $value_combos) {
                                $ids[] = $value_combos[cod_parametro_det]; 
                                $desc[] = $value_combos[descripcion];                                                
                            }
                            $combo_dinamico = $ut_tool->combo_array("campo_".$value[cod_parametro], $desc, $ids, 'data-validation="required"',$valores_params[$value[cod_parametro]]);
                            $html .= '<div class="form-group">
                                          <label for="campo_'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>
                                          <div class="col-md-'.$col_cam.'">      
                                              '.$combo_dinamico.' 
                                          </div>
                                    </div>';

                            break;
                        case '2':
                            $html .= '<div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<div class="col-md-'.$col_cam.'">';
                            $html .= '<textarea data-validation="required" class="form-control" name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '">'.$desc_valores_params[$value[cod_parametro]].'</textarea>';
                            $html .= '</div>';
                            $html .= '</div>';
                            break;
                        case '3':
                            $html .= '<div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<div class="col-md-'.$col_cam.'">';
                            $html .= '<input type="text" style="width: 140px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="dd/mm/yyyy"  name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" value="'.$desc_valores_params[$value[cod_parametro]].'"/>';
                            $html .= '</div>';
                            $js .= "$('#campo_$value[cod_parametro]').datepicker();";
                            $html .= '</div>';
                            break;
                        case '4':
                            $html .= '<div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<div class="col-md-'.$col_cam.'">                                              
                                                      <select name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                    CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                        FROM mos_personal p WHERE interno = 1"
                                                                , 'cod_emp'
                                                                , 'nombres', $valores_params[$value[cod_parametro]]);
                            $js .= '$( "#campo_' . $value[cod_parametro] . '" ).select2({
                                        placeholder: "Selecione",
                                        allowClear: true
                                      }); ';
                            $html .= '</select></div>';
                            $html .= '</div>';
                            break;
                        case '5':
                            $html .= '<div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. (((count($desc_valores_params)== 0) || ($desc_valores_params[$value[cod_parametro]] == '1'))? 'checked' : '') .' type="radio" value="1" name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ($desc_valores_params[$value[cod_parametro]] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                            $html .= '</div>';
                            break;
                        case '6':
                            $html .= '<div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<div class="col-md-'.$col_cam.'">';
                            $html .= '<input type="text" style="width: 200px;"  data-validation="number" class="form-control" placeholder="' . $value[espanol] . '"  name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" value="'.$desc_valores_params[$value[cod_parametro]].'"/>';
                            $html .= '</div>';
                            $html .= '</div>';
                            break;
                        case '7':
                            $html .= '<div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<div class="col-md-'.$col_cam.'">';
                            $html .= '<input type="text" data-validation="required" class="form-control" placeholder="' . $value[espanol] . '"  name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" value="'.$desc_valores_params[$value[cod_parametro]].'"/>';
                            $html .= '</div>';
                            $html .= '</div>';
                            break;
                        default:
                            break;
                    }
                    
                    
                }
                $array[html] = $html;
                $array[js] = $js;
                return $array;
            }
            
            /**
             * Funcion para generar los paramtros en formato fila de tablas para los reportes PDF
             * @param int $modulo identificador del modulo
             * @param int $id_registro  identificador de registro           
             * @param int $par para agregar estilo sombra a las filas
             * @return string
             */
            public function crear_campos_dinamicos_td($modulo,$id_registro=null,$par=1){
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $html = '';
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros($modulo);
                }  
                
                $desc_valores_params = $valores_params = array();
                if ($id_registro!= null){
                    $sql = "SELECT cod_parametro, cod_parametro_det,descripcion FROM mos_parametro_modulos WHERE cod_categoria = $modulo and id_registro = $id_registro";

                    $data_params = $this->dbl->query($sql, array());


                    foreach ($data_params as $value_data_params) {
                        $valores_params[$value_data_params[cod_parametro]]       = $value_data_params[cod_parametro_det];
                        $desc_valores_params[$value_data_params[cod_parametro]]  = $value_data_params[descripcion];
                    }                
                }
                
                $k = $par;
                $js = $html = "";
                foreach ($this->parametros as $value) {    
                    if ($k % 2 == 0)
                        $clases = '';
                    else
                        $clases = 'even gradeA';
                    $k++;
                    switch ($value[tipo]) {
                        case '1':
                            $sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria='$modulo' and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
                            $data = $this->dbl->query($sql, array());
                            //$ids = array(''); 
                            $desc = array('-- Seleccione --');
                            $desc = array();
                            foreach ($data as $value_combos) {
                                //$ids[] = $value_combos[cod_parametro_det]; 
                                $desc[$value_combos[cod_parametro_det]] = $value_combos[descripcion];                                                
                            }
                            //$combo_dinamico = $ut_tool->combo_array("campo_".$value[cod_parametro], $desc, $ids, 'data-validation="required"',$valores_params[$value[cod_parametro]]);
                            $html .= '<tr class="'. $clases .'">
                                          <td>' . $value[espanol] . '</td>
                                          <td>      
                                              '.$desc[$value[cod_parametro]].' 
                                          </td>
                                    </tr>';

                            break;
                        case '2':
                            $html .= '<tr  class="'. $clases .'">'
                                . '<td>' . $value[espanol] . '</td>'; 
                            
                            $html .= '<td>'.$desc_valores_params[$value[cod_parametro]].'</td>';                            
                            $html .= '</tr>';
                            break;
                        case '3':
                            $html .= '<tr class="'. $clases .'">'
                                . '<td>' . $value[espanol] . '</td>'; 
                            $html .= '<td>';
                            $html .= ' '.$desc_valores_params[$value[cod_parametro]].'';
                            $html .= '</td>';
                            //$js .= "$('#campo_$value[cod_parametro]').datepicker();";
                            $html .= '</tr>';
                            break;
                        case '4':
                            $html .= '<tr class="'. $clases .'">'
                                . '<td>' . $value[espanol] . '</td>'; 
                            $html .= '<td>';
                            $sql = "SELECT cod_emp, 
                                                                    CONCAT( LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', initcap(p.nombres))  nombres
                                                                        FROM mos_personal p WHERE interno = 1 AND cod_emp = " . $valores_params[$value[cod_parametro]] .""
                                                                ;
                            $data_aux = $this->dbl->query($sql, array());
                            $html .= $data_aux[0][nombres];
//                            $js .= '$( "#campo_' . $value[cod_parametro] . '" ).select2({
//                                        placeholder: "Selecione",
//                                        allowClear: true
//                                      }); ';
                            $html .= '</td>';
                            $html .= '</tr>';
                            break;
                        case '5':
                            $html .= '<tr class="'. $clases .'">'
                                . '<td>' . $value[espanol] . '</td>'; 
                            $html .= '<td>';
                            if ((count($desc_valores_params)== 0) || ($desc_valores_params[$value[cod_parametro]] == '1')){
                                $html .= '<img src="diseno/images/verde.png" />';
                            }
                            if ((count($desc_valores_params)== 0) || ($desc_valores_params[$value[cod_parametro]] == '2')){
                                $html .= '<img src="diseno/images/atrasado.png" />';
                            }                                                                                           
                            $html .= '</td>';
                            $html .= '</tr>';
                            break;
                        case '6':
                            $html .= '<tr class="'. $clases .'">'
                                . '<td>' . $value[espanol] . '</td>'; 
                            $html .= '<td>';
                            $html .= $desc_valores_params[$value[cod_parametro]];
                            $html .= '</td>';
                            $html .= '</tr>';
                            break;
                        case '7':
                            $html .= '<tr class="'. $clases .'">'
                                . '<td>' . $value[espanol] . '</td>'; 
                            $html .= '<td>';
                            $html .= $desc_valores_params[$value[cod_parametro]];
                            $html .= '</td>';
                            $html .= '</tr>';
                            break;
                        default:
                            break;
                    }
                    
                    
                }
                $array[contador] = count($this->parametros);
                $array[html] = $html;
                $array[js] = $js;
                return $array;
            }
            
            public function crear_campos_dinamicos_form_h($modulo,$id_registro=null,$col_lab=24){
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $html = '';
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros($modulo);
                }  
                
                $desc_valores_params = $valores_params = array();
                if ($id_registro!= null){
                    $sql = "SELECT cod_parametro, cod_parametro_det,descripcion FROM mos_parametro_modulos WHERE cod_categoria = $modulo and id_registro = $id_registro";

                    $data_params = $this->dbl->query($sql, array());


                    foreach ($data_params as $value_data_params) {
                        $valores_params[$value_data_params[cod_parametro]]       = $value_data_params[cod_parametro_det];
                        $desc_valores_params[$value_data_params[cod_parametro]]  = $value_data_params[descripcion];
                    }                
                }
                
                $js = $html = "";

                foreach ($this->parametros as $value) {    
                    switch ($value[tipo]) {
                        case '1':
                            $sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria='$modulo' and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
                            $data = $this->dbl->query($sql, array());
                            $ids = array(''); 
                            $desc = array('-- Seleccione --');
                            foreach ($data as $value_combos) {
                                $ids[] = $value_combos[cod_parametro_det]; 
                                $desc[] = $value_combos[descripcion];                                                
                            }
                            $combo_dinamico = $ut_tool->combo_array("campo_".$value[cod_parametro], $desc, $ids, 'data-validation="required"',$valores_params[$value[cod_parametro]]);
                            $html .= '<div class="form-group col-xs-'.$col_lab.'">
                                          <label for="campo_'.$value[cod_parametro].'">' . $value[espanol] . '</label>
                                                
                                              '.$combo_dinamico.'                                           
                                    </div>';

                            break;
                        case '2':
                            $html .= '<div class="form-group col-xs-'.$col_lab.'">'
                                . '<label for="campo-'.$value[cod_parametro].'">' . $value[espanol] . '</label>'; 
                            //$html .= '<div class="col-md-4">';
                            $html .= '<textarea data-validation="required" class="form-control" name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '">'.$desc_valores_params[$value[cod_parametro]].'</textarea>';
                            //$html .= '</div>';
                            $html .= '</div>';
                            break;
                        case '3':
                            $html .= '<div class="form-group col-xs-'.$col_lab.'">'
                                . '<label for="campo-'.$value[cod_parametro].'">' . $value[espanol] . '</label>'; 
                            //$html .= '<div class="col-md-4">';
                            $html .= '<input type="text" style="width: 100px;"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" placeholder="dd/mm/yyyy"  name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" value="'.$desc_valores_params[$value[cod_parametro]].'"/>';
                            //$html .= '</div>';
                            $js .= "$('#campo_$value[cod_parametro]').datepicker();";
                            $html .= '</div>';
                            break;
                        case '4':
                            $html .= '<div class="form-group col-xs-'.$col_lab.'">'
                                . '<label for="campo-'.$value[cod_parametro].'">' . $value[espanol] . '</label>'; 
                            //<div class="col-md-4">  
                            $html .= '                                            
                                                      <select name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                    CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                        FROM mos_personal p WHERE interno = 1"
                                                                , 'cod_emp'
                                                                , 'nombres', $valores_params[$value[cod_parametro]]);
                            $js .= '$( "#campo_' . $value[cod_parametro] . '" ).select2({
                                        placeholder: "Selecione",
                                        allowClear: true
                                      }); ';
                            $html .= '</select>';//</div>
                            $html .= '</div>';
                            break;
                        case '5':
                            $html .= '<div class="form-group col-xs-'.$col_lab.'">'
                                . '<label for="campo-'.$value[cod_parametro].'" >' . $value[espanol] . '</label>'; 
                            $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. (((count($desc_valores_params)== 0) || ($desc_valores_params[$value[cod_parametro]] == '1'))? 'checked' : '') .' type="radio" value="1" name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline" style="color:white;">
                                            <input '. ($desc_valores_params[$value[cod_parametro]] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                            $html .= '</div>';
                            break;
                        case '6':
                            $html .= '<div class="form-group col-xs-'.$col_lab.'">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="">' . $value[espanol] . '</label>'; 
                            //$html .= '<div class="col-md-4">';
                            $html .= '<input type="text" style=""  data-validation="number" class="form-control" placeholder="' . $value[espanol] . '"  name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" value="'.$desc_valores_params[$value[cod_parametro]].'"/>';
                            //$html .= '</div>';
                            $html .= '</div>';
                            break;
                        case '7':
                            $html .= '<div class="form-group col-xs-'.$col_lab.'">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="">' . $value[espanol] . '</label>'; 
                            //$html .= '<div class="col-md-4">';
                            $html .= '<input type="text" data-validation="required" class="form-control" placeholder="' . $value[espanol] . '"  name="campo_' . $value[cod_parametro] . '" id="campo_' . $value[cod_parametro] . '" value="'.$desc_valores_params[$value[cod_parametro]].'"/>';
                            //$html .= '</div>';
                            $html .= '</div>';
                            break;
                        default:
                            break;
                    }
                    
                    
                }
                $array[html] = $html;
                $array[js] = $js;
                return $array;
            }


            
            public function crear($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                /*
                 ,CASE tipo 
                                    WHEN 1 THEN 'Combo'
                                    WHEN 2 THEN 'Texto Largo'
                                    WHEN 3 THEN 'Fecha'
                                    WHEN 4 THEN 'Personal'
                                    WHEN 5 THEN 'Semaforo'
                                    WHEN 6 THEN 'Numero'
                                    ELSE 'Texto Corto'
                                 END tipo
                 */
                $ids = array('1', '2','3', '4', '5', '6', '7'); 
                $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');
                //join mos_matrices_parametro_general mpg on mpg.cod_param=mp.dependencia and mp.cod_categoria=mpg.cod_categoria and agrupacion='1'
                $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,56);
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
                $contenido_1[CHECKED_VIGENCIA] = 'checked="checked"';
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;Parametros";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Parametros";
                $contenido['PAGINA_VOLVER'] = "listarParametros.php";
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
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->ingresarParametros($parametros);

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
                session_name("$GLOBALS[SESSION]");
                session_start();
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verParametros($parametros[id]); 

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
                $contenido_1['COD_PARAMETRO'] = $val["cod_parametro"];
                $contenido_1['ESPANOL'] = ($val["espanol"]);
                $contenido_1['INGLES'] = ($val["ingles"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1[CHECKED_VIGENCIA] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
                $contenido_1['TIPO'] = ($val["tipo"]);
                
                $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_parametro_det 
                                        WHERE cod_parametro = " . $parametros[id] . " AND cod_categoria = $_SESSION[cod_categoria]";                    
                $total_registros = $this->dbl->query($sql, array());
                $total_n_combo = $total_registros[0][total_registros];  
                $sql = "SELECT COUNT(*) total_registros
                            FROM mos_parametro_modulos 
                            WHERE cod_categoria = " . $_SESSION[cod_categoria] . " AND cod_parametro = $parametros[id]";                                    
                $total_registros = $this->dbl->query($sql, array());
                $total_datos = $total_registros[0][total_registros]; 
                
                switch ($val["tipo"]) {
                    case '1':
                        if ($total_n_combo > 0){
                            $contenido_1['TIPOS'] = '<input type="hidden" class="form-control" value="1" id="tipo" name="tipo" data-validation="required"/>';
                            $contenido_1['TIPOS'] .= '<input type="text" class="form-control" value="Combo" readonly="readonly"/>';
                        }
                        else{
                            $ids = array('1', '2','3', '4', '5', '6', '7'); 
                            $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        break;
                    case '2':
                        if ($total_datos > 0){
                            $ids = array('2','7'); 
                            $desc = array('Texto Largo', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        else{
                            $ids = array('1', '2','3', '4', '5', '6', '7'); 
                            $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        break;
                    case '3':
                        if ($total_datos > 0){
                            $contenido_1['TIPOS'] = '<input type="hidden" class="form-control" value="3" id="tipo" name="tipo" data-validation="required"/>';
                            $contenido_1['TIPOS'] .= '<input type="text" class="form-control" value="Fecha" readonly="readonly"/>';
                        }
                        else{
                            $ids = array('1', '2','3', '4', '5', '6', '7'); 
                            $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        break;
                    case '4':
                        if ($total_datos > 0){
                            $contenido_1['TIPOS'] = '<input type="hidden" class="form-control" value="4" id="tipo" name="tipo" data-validation="required"/>';
                            $contenido_1['TIPOS'] .= '<input type="text" class="form-control" value="Personal" readonly="readonly"/>';
                        }
                        else{
                            $ids = array('1', '2','3', '4', '5', '6', '7'); 
                            $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        break;
                    case '5':
                        if ($total_datos > 0){
                            $contenido_1['TIPOS'] = '<input type="hidden" class="form-control" value="5" id="tipo" name="tipo" data-validation="required"/>';
                            $contenido_1['TIPOS'] .= '<input type="text" class="form-control" value="Semaforo" readonly="readonly"/>';
                        }
                        else{
                            $ids = array('1', '2','3', '4', '5', '6', '7'); 
                            $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        break;
                    case '6':
                        if ($total_datos > 0){
                            $contenido_1['TIPOS'] = '<input type="hidden" class="form-control" value="6" id="tipo" name="tipo" data-validation="required"/>';
                            $contenido_1['TIPOS'] .= '<input type="text" class="form-control" value="Numero" readonly="readonly"/>';
                        }
                        else{
                            $ids = array('1', '2','3', '4', '5', '6', '7'); 
                            $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        break;
                    case '7':
                        if ($total_datos > 0){
                            $ids = array('2','7'); 
                            $desc = array('Texto Largo', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        else{
                            $ids = array('1', '2','3', '4', '5', '6', '7'); 
                            $desc = array('Combo', 'Texto Largo', 'Fecha', 'Personal', 'Semaforo', 'Numero', 'Texto Corto');                
                            $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                        }
                        break;
                    default:
                        break;
                }               

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;Parametros";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Parametros";
                $contenido['PAGINA_VOLVER'] = "listarParametros.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["cod_parametro"];

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
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    $respuesta = $this->modificarParametros($parametros);

                    //if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                    if (preg_match("/actualizado con exito/",$respuesta ) == true) {
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
                $val = $this->verParametros($parametros[id]);
                $respuesta = $this->eliminarParametros($parametros);
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
                 $parametros['b-cod_categoria'] = $_SESSION[cod_categoria];
                $grid = $this->verListaParametros($parametros);                
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

                $val = $this->verParametros($parametros[id]);

                            $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
            $contenido_1['COD_PARAMETRO'] = $val["cod_parametro"];
            $contenido_1['ESPANOL'] = ($val["espanol"]);
            $contenido_1['INGLES'] = ($val["ingles"]);
            $contenido_1['VIGENCIA'] = ($val["vigencia"]);
            $contenido_1['TIPO'] = ($val["tipo"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'parametros/';
                $template->setTemplate("verParametros");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Parametros";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>