<?php
 import("clases.interfaz.Pagina");        
        class ArbolProcesos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
            
            public function ArbolProcesos(){
                parent::__construct();
                $this->asigna_script('arbol_procesos/arbol_procesos.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = array();
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


     

             public function verArbolProcesos($id){
                $atr=array();
                $sql = "SELECT id
,parent_id
,position
,left
,right
,level
,title
,type

                         FROM mos_arbol_procesos 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarArbolProcesos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "INSERT INTO mos_arbol_procesos(id,parent_id,position,left,right,level,title,type)
                            VALUES(
                                $atr[id],$atr[parent_id],$atr[position],$atr[left],$atr[right],$atr[level],'$atr[title]','$atr[type]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_arbol_procesos ' . $atr[descripcion_ano], 'mos_arbol_procesos');
                      */
                    $nuevo = "Parent Id: \'$atr[parent_id]\', Position: \'$atr[position]\', Left: \'$atr[left]\', Right: \'$atr[right]\', Level: \'$atr[level]\', Title: \'$atr[title]\', Type: \'$atr[type]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', '');
                    return "El mos_arbol_procesos '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarArbolProcesos($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_arbol_procesos SET                            
                                    id = $atr[id],parent_id = $atr[parent_id],position = $atr[position],left = $atr[left],right = $atr[right],level = $atr[level],title = '$atr[title]',type = '$atr[type]'
                            WHERE  id = $atr[id]";      
                    $val = $this->verArbolProcesos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Parent Id: \'$atr[parent_id]\', Position: \'$atr[position]\', Left: \'$atr[left]\', Right: \'$atr[right]\', Level: \'$atr[level]\', Title: \'$atr[title]\', Type: \'$atr[type]\', ";
                    $anterior = "Parent Id: \'$val[parent_id]\', Position: \'$val[position]\', Left: \'$val[left]\', Right: \'$val[right]\', Level: \'$val[level]\', Title: \'$val[title]\', Type: \'$val[type]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el ArbolProcesos ' . $atr[descripcion_ano], 'mos_arbol_procesos');
                    */
                    return "El mos_arbol_procesos '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            /**
             * Devuelve el numero de niveles del arbol
             * 
             */
            public function numeroNivelesHijos() {
                //$ids = implode(",", $param);
                $sql = "select max(level) level from mos_arbol_procesos ";
                $data = $this->dbl->query($sql);
                
                if (count($data)>0){
                    $num = $data[0][level] - 1;
                }
                return $num;                                
            }
            
             public function listarArbolProcesosReporte($atr, $pag, $registros_x_pagina){
                    /*$atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                     $sql = "select g.id g_id, g.title g,a.id a_id, a.title a,sa1.id sa1_id, sa1.title sa1,sa2.id sa2_id,sa2.title sa2 
                        from mos_arbol_procesos g
                                LEFT JOIN mos_arbol_procesos a on a.parent_id = g.id
                                LEFT JOIN mos_arbol_procesos sa1 on sa1.parent_id =a.id
                                LEFT JOIN mos_arbol_procesos sa2 on sa2.parent_id = sa1.id
                                where g.parent_id = 2";
//                    $sql .= " order by $atr[corder] $atr[sorder] ";
//                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";*/
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = $sql_order = "";
                    $k = 1;                    
                    for($k=2;$k<=$atr[niveles]+1;$k++) {
                        if ($k==2){
                            $sql_left .= "mos_arbol_procesos a$k ";  
                            $sql_order = "a$k.id_organizacion,a$k.title";
                            $sql_col_left .= ",a$k.id_organizacion id_1,a$k.id id_$k, a$k.title nombre_$k";    
                        }
                        else{
                            $sql_left.= " LEFT JOIN mos_arbol_procesos a$k on a$k.parent_id = a".($k-1).".id ";
                            $sql_order .= ",a$k.title";
                            $sql_col_left .= ",a$k.id id_$k, a$k.title nombre_$k";  
                        }
                                              
                    }
                    
                    $sql = "select 1 $sql_col_left
                        from $sql_left
                                where NOT a2.id_organizacion IS NULL AND a2.parent_id = ". $atr["b-id_organizacion"] . ""
                            . " ORDER BY $sql_order";
                   //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function listarArbolProcesos($atr, $pag, $registros_x_pagina){
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
                         FROM mos_arbol_procesos 
                         WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-parent_id"])>0)
                        $sql .= " AND parent_id = '". $atr[b-parent_id] . "'";
                    if (strlen($atr["b-position"])>0)
                        $sql .= " AND position = '". $atr[b-position] . "'";
                    if (strlen($atr["b-left"])>0)
                        $sql .= " AND left = '". $atr[b-left] . "'";
                    if (strlen($atr["b-right"])>0)
                        $sql .= " AND right = '". $atr[b-right] . "'";
                    if (strlen($atr["b-level"])>0)
                        $sql .= " AND level = '". $atr[b-level] . "'";
                    if (strlen($atr["b-title"])>0)
                        $sql .= " AND upper(title) like '%" . strtoupper($atr["b-title"]) . "%'";
                    if (strlen($atr["b-type"])>0)
                        $sql .= " AND upper(type) like '%" . strtoupper($atr["b-type"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                ,parent_id
                                ,position
                                ,left
                                ,right
                                ,level
                                ,title
                                ,type
                                     $sql_col_left
                            FROM mos_arbol_procesos $sql_left
                            WHERE 1 = 1 ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-parent_id"])>0)
                        $sql .= " AND parent_id = '". $atr[b-parent_id] . "'";
                    if (strlen($atr["b-position"])>0)
                        $sql .= " AND position = '". $atr[b-position] . "'";
                    if (strlen($atr["b-left"])>0)
                        $sql .= " AND left = '". $atr[b-left] . "'";
                    if (strlen($atr["b-right"])>0)
                        $sql .= " AND right = '". $atr[b-right] . "'";
                    if (strlen($atr["b-level"])>0)
                        $sql .= " AND level = '". $atr[b-level] . "'";
                    if (strlen($atr["b-title"])>0)
                        $sql .= " AND upper(title) like '%" . strtoupper($atr["b-title"]) . "%'";
                    if (strlen($atr["b-type"])>0)
                        $sql .= " AND upper(type) like '%" . strtoupper($atr["b-type"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             
             public function eliminarArbolProcesos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_arbol_procesos", "id = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaArbolProcesos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarArbolProcesos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;

                $grid->SetConfiguracionMSKS("tblArbolProcesos", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Parent Id", "parent_id", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Position", "position", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Left", "left", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Right", "right", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Level", "level", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Title", "title", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos("Type", "type", $parametros))
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
                    
                    $columna_funcion = 9;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verArbolProcesos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver ArbolProcesos'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarArbolProcesos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar ArbolProcesos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarArbolProcesos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar ArbolProcesos'>"));
               
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>"&nbsp;"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
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
            
            public function verListaArbolProcesosReporte($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                
                /*CALCULA EL NUMERO DE NIVELES DEL ARBOL*/
                $out[niveles] = $niveles = $parametros[niveles] = $this->numeroNivelesHijos();
                $out[titulo] = "";
                if ($niveles == 2){
                    $ancho_area = 50;
                }
                else $ancho_area = 40;
                for($i=1;$i<=$niveles;$i++){
                    if ($i==1){
                        $out[titulo] .= "<th style=\"width: ".$ancho_area  . "%\" >&Aacute;rea</th>" ;                        
                        $out[titulo] .= "<th style=\"width: ". (100  - $ancho_area) / $niveles  . "%\" >Proceso</th>" ;                        
                    }
                    else if ($i==$niveles){
                        $out[titulo] .= "<th style=\"width: ". (100  - $ancho_area) / $niveles  . "%\" >Actividad</th>" ;   
                    }
                    else 
                      $out[titulo] .= "<th style=\"width: ". (100  - $ancho_area) / $niveles  . "%\" >SubProceso $i</th>" ;
                    
                }
                
                $this->listarArbolProcesosReporte($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;                                
                $out[filas] = count($data);
                //print_r($data);
                $html = "";
                
                $id_aux = $ids = $con_g = array();
                for($i=1;$i<=$niveles+1;$i++){                                           
                    $id_aux[$i] = $data[0]["id_$i"];
                    $con_g[$i] = 0;
                    
                }  
                /*Suma cuantas veces se repite un nodo*/
                foreach ($data as $value) {
                    for($i=1;$i<=$niveles+1;$i++){
                        if ($value["id_$i"] != $id_aux[$i]){
                            $ids[$id_aux[$i]] = $con_g[$i];
                            $id_aux[$i] = $value["id_$i"];
                            $con_g[$i] = 1;
                        }
                        else
                            $con_g[$i]++;
                    }                    
                }
                for($i=1;$i<=$niveles+1;$i++){                    
                    $ids[$id_aux[$i]] = $con_g[$i];                        
                }  
                /*
                $g_id = $a_id = $sa1_id = $sa2_id = array();
                $g_id_aux = $data[0][g_id];
                $a_id_aux = $data[0][a_id];
                $sa1_id_aux = $data[0][sa1_id];
                $sa2_id_aux = $data[0][sa2_id];
                $con_g = $con_a = $con_sa1 = $con_sa2 = 0;
                foreach ($data as $value) {
                    if ($value[g_id] != $g_id_aux){
                        $g_id[$g_id_aux] = $con_g;
                        $g_id_aux = $value[g_id];
                        $con_g = 1;
                    }
                    else
                        $con_g++;
                    if ($value[a_id] != $a_id_aux){
                        $a_id[$a_id_aux] = $con_a;
                        $a_id_aux = $value[a_id];
                        $con_a = 1;
                    }
                    else
                        $con_a++;
                    if ($value[sa1_id] != $sa1_id_aux){
                        $sa1_id[$sa1_id_aux] = $con_sa1;
                        $sa1_id_aux = $value[sa1_id];
                        $con_sa1 = 1;
                    }
                    else
                        $con_sa1++;
                    if ($value[sa2_id] != $sa2_id_aux){
                        $sa2_id[$sa2_id_aux] = $con_sa2;
                        $sa2_id_aux = $value[sa2_id];
                        $con_sa2 = 1;
                    }
                    else
                        $con_sa2++;
                }
                */
                /*
                $g_id[$g_id_aux] = $con_g;
                $a_id[$a_id_aux] = $con_a;
                $sa1_id[$sa1_id_aux] = $con_sa1;
                $sa1_id[$sa2_id_aux] = $con_sa2;
                
                $g_id_aux = $a_id_aux = $sa1_id_aux = $sa2_id_aux = '';
                foreach ($data as $value) {
                    $html .= '<tr class="odd gradeX">';
                    if ($value[g_id] != $g_id_aux){
                          $html .= '<td rowspan="'. $g_id[$value[g_id]] .'">'.$value[g].'</td>';
                          $g_id_aux = $value[g_id];
                    }
                    if (($value[a_id] != '')&&($value[a_id] != $a_id_aux)){
                          $html .= '<td rowspan="'. $a_id[$value[a_id]] .'">'.$value[a].'</td>';
                          $a_id_aux = $value[a_id];
                    }
                    else
                    if ($value[a_id] == ''){
                         $html .= '<td>&nbsp;</td>';
                    }
                    if (($value[sa1_id] != '')&&($value[sa1_id] != $sa1_id_aux)){
                          $html .= '<td rowspan="'. $sa1_id[$value[sa1_id]] .'">'.$value[sa1].'</td>';
                          $sa1_id_aux = $value[sa1_id];
                    }
                    else
                    if ($value[sa1_id] == ''){
                         $html .= '<td>&nbsp;</td>';
                    }
                    $html .= '<td>'.$value[sa2].'&nbsp;</td>';


                     $html .= '</tr>';
                }
                */
                
                //print_r($data);
                $id_aux = array();
                if (!class_exists('ArbolOrganizacional')){
                    import('clases.organizacion.ArbolOrganizacional');
                }
                $ao = new ArbolOrganizacional();
                foreach ($data as $value) {
                    
                    $html .= '<tr class="odd gradeX">';
                    for($i=1;$i<=$niveles+1;$i++){
                        if ($value["id_$i"] != ''){
                            if ($value["id_$i"] != $id_aux[$i]){
                                if ($i == 1){
                                    $nombre_area = $ao->BuscaOrganizacional(array('id_organizacion'=>$value[$i]));
                                    $html .= '<td rowspan="'. $ids[$value["id_$i"]] .'">'.$nombre_area.'</td>';
                                }                                    
                                else                                        
                                    $html .= '<td rowspan="'. $ids[$value["id_$i"]] .'">'.$value["nombre_$i"].'</td>';
                                $id_aux[$i] = $value["id_$i"];;
                            }
                        }
                        else
                            $html .= '<td>&nbsp;</td>';
                    }
                                        


                     $html .= '</tr>';
                }
                $out[tabla] = $html;
                //$out['tabla']= $grid->armarTabla();
//                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
//                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
//                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarArbolProcesos($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblArbolProcesos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>"Parent Id"),
         array( "width"=>"10%","ValorEtiqueta"=>"Position"),
         array( "width"=>"10%","ValorEtiqueta"=>"Left"),
         array( "width"=>"10%","ValorEtiqueta"=>"Right"),
         array( "width"=>"10%","ValorEtiqueta"=>"Level"),
         array( "width"=>"10%","ValorEtiqueta"=>"Title"),
         array( "width"=>"10%","ValorEtiqueta"=>"Type")
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
 
 
            public function indexArbolProcesos($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_ano";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-8-"; 
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
                $grid = $this->verListaArbolProcesos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_ArbolProcesos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;ArbolProcesos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] = str_replace('Árbol Organizacional', 'Árbol Organizacional &nbsp;&nbsp;<input type="text" value="" style="box-shadow:inset 0 0 4px #eee; width:220px; margin:0; padding:6px 12px; border-radius:4px; border:1px solid silver; font-size:1.1em;" id="demo_q_ao" placeholder="Buscar">', $contenido[DIV_ARBOL_ORGANIZACIONAL]);
                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';

                $template->setTemplate("busqueda");
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';

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
                $objResponse->addAssign('modulo_actual',"value","arbol_procesos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'arbol_procesos/arbol_procesos.js?'.  rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("init_filtro_ao_multiple();");
                $objResponse->addScript("var to = false;
                    $('#demo_q_ao').keyup(function () {                    
                                if(to) { clearTimeout(to); }
                                to = setTimeout(function () {
                                        var v = $('#demo_q_ao').val();
                                        $('#div-ao').jstree(true).search(v);
                                }, 250);
                        });");
                return $objResponse;
            }
            
            public function indexArbolProcesosReporte($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="descripcion_ano";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-8-"; 
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
                $parametros["b-id_organizacion"] = 2;
                $grid = $this->verListaArbolProcesosReporte($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido[TITULO_TABLA] = $grid['titulo'];
                
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_ArbolProcesos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;ArbolProcesos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';

                $template->setTemplate("busqueda");
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';
                $contenido[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
                $template->setTemplate("listar_reporte");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","arbol_procesos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'arbol_procesos/arbol_procesos.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
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
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;ArbolProcesos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ArbolProcesos";
                $contenido['PAGINA_VOLVER'] = "listarArbolProcesos.php";
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
                    
                    $respuesta = $this->ingresarArbolProcesos($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verArbolProcesos($parametros[id]); 

                            $contenido_1['PARENT_ID'] = $val["parent_id"];
            $contenido_1['POSITION'] = $val["position"];
            $contenido_1['LEFT'] = $val["left"];
            $contenido_1['RIGHT'] = $val["right"];
            $contenido_1['LEVEL'] = $val["level"];
            $contenido_1['TITLE'] = ($val["title"]);
            $contenido_1['TYPE'] = ($val["type"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;ArbolProcesos";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ArbolProcesos";
                $contenido['PAGINA_VOLVER'] = "listarArbolProcesos.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id"];

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
                    
                    $respuesta = $this->modificarArbolProcesos($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verArbolProcesos($parametros[id]);
                $respuesta = $this->eliminarArbolProcesos($parametros);
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
                $grid = $this->verListaArbolProcesos($parametros);                
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

                $val = $this->verArbolProcesos($parametros[id]);

                            $contenido_1['PARENT_ID'] = $val["parent_id"];
            $contenido_1['POSITION'] = $val["position"];
            $contenido_1['LEFT'] = $val["left"];
            $contenido_1['RIGHT'] = $val["right"];
            $contenido_1['LEVEL'] = $val["level"];
            $contenido_1['TITLE'] = ($val["title"]);
            $contenido_1['TYPE'] = ($val["type"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';
                $template->setTemplate("verArbolProcesos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la ArbolProcesos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
         /**
         *  Devuelve la estructura HTML para el arbol jtree 
         * 
         * @param int $contar Plus para informacion adicional 1=> Documentos, 2 => Registro, 3 => Acciones Correctivas
         */           
        public function jstree_ap($contar=0,$parametros=array()){
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }
            $contenido_1[AP] = $this->MuestraPadreP($contar,$parametros);
            $template = new Template();
            $template->PATH = PATH_TO_TEMPLATES.'arbol_procesos/';
            if($parametros['opcion']=='reg')
                $template->setTemplate("jstree_ap_reg");
            else
                $template->setTemplate("jstree_ap");
            $template->setVars($contenido_1);            

            return $template->show();
        }

        /**
         * Devuelve la estructura HTML del primer nivel para el jtree 
         */
        public function MuestraPadreP($contar,$parametros=array()){
		$sql="Select * from mos_arbol_procesos
				Where parent_id = 2";
                
                $data = $this->dbl->query($sql, $atr);

		$padre_final = "";
                
		//while($arrP=mysql_fetch_assoc($resp)){
                foreach ($data as $arrP) {//data-jstree='{ \"type\" : \"verde\" }'
                        
                        $data_hijo = $this->MuestraHijosP($arrP[id],$contar,$parametros);
                        $cuerpo .= "<li  id=\"phtml_".$arrP[id]."\">";
                        switch ($contar) {
                            case 1:
                                $sql = "SELECT COUNT(DISTINCT(eao.IDDoc)) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE eao.id_organizacion_proceso IN (". $this->BuscaOrgNivelHijos($arrP[id]) . ")  AND tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S' "
                                    . " ";                                
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador). ")</a>";
                                break;
                            case 2:
                                $sql = "SELECT COUNT(DISTINCT(eao.IDDoc)) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE eao.id_organizacion_proceso IN (". $this->BuscaOrgNivelHijos($arrP[id]) . ")  AND tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S' AND formulario = 'S' "
                                    . " ";                                
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador). ")</a>";
                                break;
                            case 3:
                                switch ($parametros[tipo_data]) {
                                    case 'YTD':
                                        $sql = "SELECT                                            
                                            count(id_organizacion) total,
                                            sum(case when estado=1 then 1 else 0 end) as atrasadas,
                                            sum(case when estado=2 then 1 else 0 end) as en_plazo,
                                            sum(case when estado=3 then 1 else 0 end) as realizada_atraso,
                                            sum(case when estado=4 then 1 else 0 end) as realizada
                                        FROM
                                        mos_acciones_correctivas
                                        where id_organizacion in ($arrP[id]) and (fecha_generacion >= '".date('Y')."-01-01' or fecha_realizada is null)";                                
                                        $data_aux = $this->dbl->query($sql, $atr);

                                        break;

                                    default:
                                        break;
                                }                                
                                $return[total] = $data_aux[0][total] + (isset($data_hijo[total])?$data_hijo[total]:0) ;
                                $return[atrasadas] = $data_aux[0][atrasadas] + (isset($data_hijo[atrasadas])?$data_hijo[atrasadas]:0) ;
                                $return[en_plazo] = $data_aux[0][en_plazo] + (isset($data_hijo[en_plazo])?$data_hijo[en_plazo]:0) ;
                                $return[realizada_atraso] = $data_aux[0][realizada_atraso] + (isset($data_hijo[realizada_atraso])?$data_hijo[realizada_atraso]:0) ;
                                $return[realizada] = $data_aux[0][realizada] + (isset($data_hijo[realizada])?$data_hijo[realizada]:0) ;

                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $cuerpo .= "<a href=\"#\">".($arrP[title])." ($return[total];$return[atrasadas];$return[en_plazo];$return[realizada_atraso];$return[realizada] )</a>";
                                break;
                            //cuenta los registros de un documento
                            case 4:
                                $sql = "SELECT
                                        IFNULL(count(mos_registro_item.idRegistro),0) cant
                                        FROM
                                        mos_registro_item
                                        WHERE
                                        IDDoc= ".$_SESSION[IDDoc]." and 
                                        valor in (".$this->BuscaOrgNivelHijos($arrP[id]).")
                                        and tipo = 12;";
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador='';
                                if($data_aux[0][cant]>0) $contador=$data_aux[0][cant];
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador). ")</a>";
                                break;

                            default:
                                $cuerpo .= "<a href=\"#\">".($arrP[title])." </a>";
                                break;
                        }
                        $cuerpo .= "$data_hijo[html]";
                        $cuerpo .= "</li>";
		}
		//$pie_padre = "</ul>";
                return $cuerpo;
		return $cabecera_padre.$cuerpo.$pie_padre;
	}

        /**
         *  Devuelve el HTML para el jtree incluyendo los hijos 
         * 
         * @param int $id Id del arbol organizacional
         * 
         * @param int $contar Plus para contar el numero de registros hijos en el arbol
         */
	public function MuestraHijosP($id,$contar,$parametros=array()){
		$sql="select * from mos_arbol_procesos
				Where parent_id = $id";
                //echo $sql;
		//$resp = mysql_query($sql);
                $data = $this->dbl->query($sql, $atr);
                //print_r($data);
                $contador = 0;
                $data_hijo= array();
		$cabecera = "<ul>";
                foreach ($data as $arr) {//data-jstree='{ \"type\" : \"rojo\" }' 
                    $contador = array();
                    $data_hijo = $this->MuestraHijosP($arr[id],$contar,$parametros);
                    $extra .= "<li id=\"phtml_".$arr[id]."\">";
                    switch ($contar) {
                            case 1:
                                $sql = "SELECT COUNT(*) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE eao.id_organizacion_proceso IN (". $this->BuscaOrgNivelHijos($arr[id]) . ")   AND tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S'";
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                $extra .= "<a href=\"#\">".($arr[title])." (". ($contador) .")</a>";
                                break;
                            case 2:
                                $sql = "SELECT COUNT(DISTINCT(eao.IDDoc)) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE eao.id_organizacion_proceso IN (". $this->BuscaOrgNivelHijos($arr[id]) . ")  AND tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S' AND formulario = 'S' "
                                    . " ";                                
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $extra .= "<a href=\"#\">".($arr[title])." (". ($contador). ")</a>";
                                break;
                            case 3:
                                //print_r($arr);
                                switch ($parametros[tipo_data]) {
                                    case 'YTD':
                                        $sql = "SELECT                                            
                                            count(id_organizacion) total,
                                            sum(case when estado=1 then 1 else 0 end) as atrasadas,
                                            sum(case when estado=2 then 1 else 0 end) as en_plazo,
                                            sum(case when estado=3 then 1 else 0 end) as realizada_atraso,
                                            sum(case when estado=4 then 1 else 0 end) as realizada
                                        FROM
                                        mos_acciones_correctivas
                                        where id_organizacion in ($arr[id]) and (fecha_generacion >= '".date('Y')."-01-01' or fecha_realizada is null)";                                
                                        $data_aux = $this->dbl->query($sql, $atr);

                                        break;

                                    default:
                                        break;
                                }
                                
                                $contador[total] = $data_aux[0][total] + (isset($data_hijo[total])?$data_hijo[total]:0) ;
                                $contador[atrasadas] = $data_aux[0][atrasadas] + (isset($data_hijo[atrasadas])?$data_hijo[atrasadas]:0) ;
                                $contador[en_plazo] = $data_aux[0][en_plazo] + (isset($data_hijo[en_plazo])?$data_hijo[en_plazo]:0) ;
                                $contador[realizada_atraso] = $data_aux[0][realizada_atraso] + (isset($data_hijo[realizada_atraso])?$data_hijo[realizada_atraso]:0) ;
                                $contador[realizada] = $data_aux[0][realizada] + (isset($data_hijo[realizada])?$data_hijo[realizada]:0) ;

                                        
                                $return[total] += $data_aux[0][total] + (isset($data_hijo[total])?$data_hijo[total]:0) ;
                                $return[atrasadas] += $data_aux[0][atrasadas] + (isset($data_hijo[atrasadas])?$data_hijo[atrasadas]:0) ;
                                $return[en_plazo] += $data_aux[0][en_plazo] + (isset($data_hijo[en_plazo])?$data_hijo[en_plazo]:0) ;
                                $return[realizada_atraso] += $data_aux[0][realizada_atraso] + (isset($data_hijo[realizada_atraso])?$data_hijo[realizada_atraso]:0) ;
                                $return[realizada] += $data_aux[0][realizada] + (isset($data_hijo[realizada])?$data_hijo[realizada]:0) ;
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $extra .= "<a href=\"#\">".($arr[title])." ($contador[total];$contador[atrasadas];$contador[en_plazo];$contador[realizada_atraso];$contador[realizada] )</a>";
                                break;
                            case 4:
                                $sql="SELECT
                                    IFNULL(count(mos_registro_item.idRegistro),0) cant
                                    FROM
                                    mos_registro_item
                                    WHERE
                                    IDDoc= ".$_SESSION[IDDoc]." and
                                    valor in (".$this->BuscaOrgNivelHijos($arr[id]).")
                                    and tipo = 12;";  

                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador='';
                                if($data_aux[0][cant]>0) $contador=$data_aux[0][cant];
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $extra .= "<a href=\"#\">".($arr[title])." (". ($contador). ")</a>";
                                break;
                            
                            default:
                                
                                $extra .= "<a href=\"#\">".($arr[title])."</a>";
                                break;
                        }
			$extra .= $data_hijo[html]."
						</li>";		
                        
                }
		$pie = "</ul>";
                //$return[contador] = $contador+$data_hijo[contador];
                $return[html] = $cabecera.$extra.$pie;
		return $return;
	}
            
            
            public function MuestraPadre(){
		$sql="Select * from mos_arbol_procesos
				Where parent_id = 2";
                
                $data = $this->dbl->query($sql, $atr);
                
		//$resp = mysql_query($sql);
		$cabecera_padre = "<ul>";
		$padre_final = "";
		//while($arrP=mysql_fetch_assoc($resp)){
                foreach ($data as $arrP) {
                        
                    
			$cuerpo .= "<li id=\"phtml_".$arrP[id]."\">
							<a href=\"#\">".($arrP[title])."</a>
							".$this->MuestraHijos($arrP[id])."
						</li>";
		}
		$pie_padre = "</ul>";
		return $cabecera_padre.$cuerpo.$pie_padre;
	}
 

	public function MuestraHijos($id){
		$sql="select * from mos_arbol_procesos
				Where parent_id = $id";
		//$resp = mysql_query($sql);
                $data = $this->dbl->query($sql, $atr);
		$cabecera = "<ul>";
		//while($arr=mysql_fetch_assoc($resp)){
                foreach ($data as $arr) {
			$extra .= "<li id=\"phtml_".$arr[id]."\">
							<a href=\"#\">".($arr[title])."</a>
							".$this->MuestraHijos($arr[id])."
						</li>";		}
		$pie = "</ul>";
		return $cabecera.$extra.$pie;
	}
        
           public function MuestraPadreReg(){
		$sql="Select * from mos_arbol_procesos
				Where parent_id = 2";
                
                $data = $this->dbl->query($sql, $atr);
                
		//$resp = mysql_query($sql);
		$cabecera_padre = "<ul>";
		$padre_final = "";
		//while($arrP=mysql_fetch_assoc($resp)){
                foreach ($data as $arrP) {
                         $id_org = $this->BuscaOrgNivelHijos($arrP[id]);
                         //echo 'Idorg='.$id_org;
                        $sql="SELECT
                            IFNULL(count(mos_registro_item.idRegistro),0) cant
                            FROM
                            mos_registro_item
                            WHERE
                            valor in (".$id_org.")
                            and tipo = 12;";  
                        $cuenta = $this->dbl->query($sql, $atr);
                        $registros='';
                        if($cuenta[0][cant]>0) $registros='('.$cuenta[0][cant].')';
			$cuerpo .= "<li id=\"phtml_".$arrP[id]."\">
							<a href=\"#\">".($arrP[title]).$registros."</a>
							".$this->MuestraHijosReg($arrP[id])."
						</li>";
		}
		$pie_padre = "</ul>";
		return $cabecera_padre.$cuerpo.$pie_padre;
	}        
	public function MuestraHijosReg($id){
		$sql="select * from mos_arbol_procesos
				Where parent_id = $id";
		//$resp = mysql_query($sql);
                $data = $this->dbl->query($sql, $atr);
		$cabecera = "<ul>";
		//while($arr=mysql_fetch_assoc($resp)){
                foreach ($data as $arr) {
                         $id_org = $this->BuscaOrgNivelHijos($arr[id]);
                         //echo 'Idorg='.$id_org;
                        $sql="SELECT
                            IFNULL(count(mos_registro_item.idRegistro),0) cant
                            FROM
                            mos_registro_item
                            WHERE
                            valor in (".$id_org.")
                            and tipo = 12;";  
                        $cuenta = $this->dbl->query($sql, $atr);
                        $registros='';
                        if($cuenta[0][cant]>0) $registros='('.$cuenta[0][cant].')';
                    
			$extra .= "<li id=\"phtml_".$arr[id]."\">
							<a href=\"#\">".($arr[title]).$registros."</a>
							".$this->MuestraHijos($arr[id])."
						</li>";		}
		$pie = "</ul>";
		return $cabecera.$extra.$pie;
	}
        
        public function buscar_hijos($parametros)
            {
                $hijos = $this->BuscaOrgNivelHijos($parametros['b-id_proceso']);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('proceso',"value",$hijos);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                $nombre = BuscaProceso(array('id_organizacion' => $parametros['b-id_proceso']));
                $objResponse->addAssign('desc-proceso',"innerHTML",$nombre);
                //$objResponse->addScript("procesar_filtrar_arbol();");
                $objResponse->addScript("$('#myModal-Filtrar-Proceso').modal('hide');");
                return $objResponse;
            }
            
        public function BuscaOrgNivelHijos($IDORG)
        {
            $OrgNom = $IDORG;            
            $Consulta3="select id as id_organizacion, parent_id as organizacion_padre, title as identificacion from mos_arbol_procesos where parent_id='".$IDORG."' order by id";            
            $data = $this->dbl->query($Consulta3,array());
            foreach( $data as $Fila3)
            {                    
                    $OrgNom .= ",".$this->BuscaOrgNivelHijos($Fila3[id_organizacion]);
            }
            return $OrgNom;
        }
        
        /**
         *  Devuelve la array para administrar el arbol 
         *          
         */
        public function admin_jstree_ap($parametros){
            $atr = $this->dbl->corregir_parametros($parametros);
            $sql = "SELECT * FROM mos_arbol_procesos where id_organizacion in ($atr[id_ao]) AND level = 2 ";
            //echo $sql;
            $data = $this->dbl->query($sql);
            if (count($data)==0) return array();
            $data_hijo = '';
            foreach ($data as $value) {
                $result = $this->AdminMuestraHijos($value[id]); 
                if (is_array($data_hijo)){
                    $data_hijo = array_merge($data_hijo,$result);
                }
                else 
                    $data_hijo = $result;
            }
                               

            return $data_hijo;
        }
        
        /**
         *  Devuelve el HTML para el jtree incluyendo los hijos 
         * 
         * @param int $id Id del arbol de proceso
         * 
         * @param int $contar Plus para contar el numero de registros hijos en el arbol
         */
	public function AdminMuestraHijos($id){
		
                $items = array();
                $sql="select * from mos_arbol_procesos
				Where id = $id";
                $data = $this->dbl->query($sql, $atr);
                $items[0] = array(id=>$data[0][id], text=>$data[0][title], "state" => array("opened" => true ));
                
                $sql="select * from mos_arbol_procesos
				Where parent_id = $id";
                //echo $sql;
		//$resp = mysql_query($sql);
                $data = $this->dbl->query($sql, $atr);
                //print_r($data);
                $contador = 0;
                $data_hijo= '';
		$cabecera = "<ul>";
                foreach ($data as $arr) {//data-jstree='{ \"type\" : \"rojo\" }'                    
                    $result = $this->AdminMuestraHijos($arr[id]);                    	
                    if (is_array($data_hijo)){
                        $data_hijo = array_merge($data_hijo,$result);
                    }
                    else 
                        $data_hijo = $result;
                }
		$items[0][children] = $data_hijo;
                //$return[contador] = $contador+$data_hijo[contador];                
		return $items;
	}
     
 }?>