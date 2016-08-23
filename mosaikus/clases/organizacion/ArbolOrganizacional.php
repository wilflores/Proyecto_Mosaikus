<?php
 import("clases.interfaz.Pagina");        
        class ArbolOrganizacional extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $id_org_acceso;
        private $id_org_acceso_explicito;
            
            public function ArbolOrganizacional(){
                parent::__construct();
                $this->asigna_script('organizacion/organizacion.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = array();
                $this->contenido = $this->id_org_acceso = $this->id_org_acceso_explicito = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros(){
                $sql = "SELECT cod_parametro, espanol FROM mos_parametro WHERE cod_categoria = '3' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }

            public function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (33,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }                
            }
             private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE id_idioma=$_SESSION[CookIdIdioma] and  modulo in (33,100)";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }

             }

             public function verArbolOrganizacional($id){
                $atr=array();
                $sql = "SELECT id
                        ,parent_id
                        ,position
                        ,left
                        ,right
                        ,level
                        ,title
                        ,type

                         FROM mos_organizacion 
                         WHERE id = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            public function ingresarArbolOrganizacional($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "INSERT INTO mos_organizacion(id,parent_id,position,left,right,level,title,type)
                            VALUES(
                                $atr[id],$atr[parent_id],$atr[position],$atr[left],$atr[right],$atr[level],'$atr[title]','$atr[type]'
                                )";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_organizacion ' . $atr[descripcion_ano], 'mos_organizacion');
                      */
                    $nuevo = "Parent Id: \'$atr[parent_id]\', Position: \'$atr[position]\', Left: \'$atr[left]\', Right: \'$atr[right]\', Level: \'$atr[level]\', Title: \'$atr[title]\', Type: \'$atr[type]\', ";
                    $this->registraTransaccionLog(18,$nuevo,'', '');
                    return "El mos_organizacion '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarArbolOrganizacional($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_organizacion SET                            
                                    id = $atr[id],parent_id = $atr[parent_id],position = $atr[position],left = $atr[left],right = $atr[right],level = $atr[level],title = '$atr[title]',type = '$atr[type]'
                            WHERE  id = $atr[id]";      
                    $val = $this->verArbolOrganizacional($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "Parent Id: \'$atr[parent_id]\', Position: \'$atr[position]\', Left: \'$atr[left]\', Right: \'$atr[right]\', Level: \'$atr[level]\', Title: \'$atr[title]\', Type: \'$atr[type]\', ";
                    $anterior = "Parent Id: \'$val[parent_id]\', Position: \'$val[position]\', Left: \'$val[left]\', Right: \'$val[right]\', Level: \'$val[level]\', Title: \'$val[title]\', Type: \'$val[type]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el ArbolOrganizacional ' . $atr[descripcion_ano], 'mos_organizacion');
                    */
                    return "El mos_organizacion '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function modificarAreaEspejo($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_organizacion_nombres SET                            
                                    area_espejo = $atr[area_espejo]
                            WHERE  id = $atr[id]";      
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $nuevo = "Area Espejo: \'$atr[area_espejo]\'";                    
                    $this->registraTransaccionLog(88,$nuevo,'', $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el ArbolOrganizacional ' . $atr[descripcion_ano], 'mos_organizacion');
                    */
                    return " ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function listarArbolOrganizacional($atr, $pag, $registros_x_pagina){
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
                         FROM mos_organizacion 
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
                            FROM mos_organizacion $sql_left
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
             
             public function listarArbolOrganizacionalReporte($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $this->cargar_acceso_nodos($atr);
                    $sql_left = $sql_col_left = $sql_where = "";
                    $k = 1;                    
                    for($k=1;$k<=$atr[niveles];$k++) {
                        if ($k==1){
                            $sql_left .= "mos_organizacion a$k ";       
                            $sql_where .= " AND a$k.id IN (". implode(',', array_keys($this->id_org_acceso)) . ") ";
                        }
                        else{
                            $sql_left.= " LEFT JOIN mos_organizacion a$k on a$k.parent_id = a".($k-1).".id  AND a$k.id IN (". implode(',', array_keys($this->id_org_acceso)) . ") ";
                        }
                        $sql_col_left .= ",a$k.id id_$k, a$k.title nombre_$k";   
                        
                    }
                    
                    $sql = "select 1 $sql_col_left
                        from $sql_left
                                where a1.parent_id IN ( ". $atr["b-id_organizacion"] . ") $sql_where";
                        //echo $sql;
                    
                    //$sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= " LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    //exit();
                    $this->operacion($sql, $atr);
             }
             
             public function ContarArbolOrganizacionalReporte($atr){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $this->cargar_acceso_nodos($atr);
                    $sql_left = $sql_col_left = $sql_where = "";
                    $k = 1;                    
                    for($k=1;$k<=$atr[niveles];$k++) {
                        if ($k==1){
                            $sql_left .= "mos_organizacion a$k ";       
                            $sql_where .= " AND a$k.id IN (". implode(',', array_keys($this->id_org_acceso)) . ") ";
                        }
                        else{
                            $sql_left.= " LEFT JOIN mos_organizacion a$k on a$k.parent_id = a".($k-1).".id  AND a$k.id IN (". implode(',', array_keys($this->id_org_acceso)) . ") ";
                        }
                        $sql_col_left .= ",a$k.id id_$k, a$k.title nombre_$k";   
                        
                    }
                    
                    $sql = "select COUNT(*) total_registros
                        from $sql_left
                                where a1.parent_id IN ( ". $atr["b-id_organizacion"] . ") $sql_where";
                        //echo $sql;
                    
                    //$sql .= " order by $atr[corder] $atr[sorder] ";
                    $total_registros = $this->dbl->query($sql, $atr);
                    return $total_registros[0][total_registros];  
             }
             
             public function eliminarArbolOrganizacional($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_organizacion", "id = " . $atr[id]);
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaArbolOrganizacional($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarArbolOrganizacional($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;

                $grid->SetConfiguracionMSKS("tblArbolOrganizacional", "");
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
                    array_push($func,array('nombre'=> 'verArbolOrganizacional','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver ArbolOrganizacional'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarArbolOrganizacional','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar ArbolOrganizacional'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarArbolOrganizacional','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar ArbolOrganizacional'>"));
               
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
            
            public function verListaArbolOrganizacionalReporte($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                
                /*CALCULA EL NUMERO DE NIVELES DEL ARBOL*/
                $out[niveles] = $niveles = $parametros[niveles];// = $this->numeroNivelesHijos(array($parametros["b-id_organizacion"]));
                $out[titulo] = "";
                for($i=1;$i<=$niveles;$i++){
                    $out[titulo] .= "<th style=\"width: ". 100 / $niveles  . "%\" >Nivel $i</th>" ;
                }
                $this->listarArbolOrganizacionalReporte($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                $out[filas] = count($data);
                //print_r($data);
                $html = "";
                
                $id_aux = $ids = $con_g = array();
                for($i=1;$i<=$niveles;$i++){                                           
                    $id_aux[$i] = $data[0]["id_$i"];
                    $con_g[$i] = 0;
                    
                }  
                foreach ($data as $value) {
                    for($i=1;$i<=$niveles;$i++){
                        if ($value["id_$i"] != $id_aux[$i]){
                            $ids[$id_aux[$i]] = $con_g[$i];
                            $id_aux[$i] = $value["id_$i"];
                            $con_g[$i] = 1;
                        }
                        else
                            $con_g[$i]++;
                    }                    
                }
                for($i=1;$i<=$niveles;$i++){                    
                    $ids[$id_aux[$i]] = $con_g[$i];                        
                }  
                //print_r($ids);
                
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
                
                
                $g_id[$g_id_aux] = $con_g;
                $a_id[$a_id_aux] = $con_a;
                $sa1_id[$sa1_id_aux] = $con_sa1;
                $sa1_id[$sa2_id_aux] = $con_sa2;
                
                $g_id_aux = $a_id_aux = $sa1_id_aux = $sa2_id_aux = '';*/
                $id_aux = array();
                foreach ($data as $value) {
                    
                    $html .= '<tr class="odd gradeX">';
                    for($i=1;$i<=$niveles;$i++){
                        if ($value["id_$i"] != ''){
                            if ($value["id_$i"] != $id_aux[$i]){
                                $html .= '<td rowspan="'. $ids[$value["id_$i"]] .'">'.$value["nombre_$i"].'</td>';
                                $id_aux[$i] = $value["id_$i"];;
                            }
                        }
                        else
                            $html .= '<td>&nbsp;</td>';
                    }
                                        


                     $html .= '</tr>';
                }
                /*
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
                }*/
                
                //echo $html;
                //print_r($sa2_id);
                        
                
                
                //$out['tabla']= $grid->armarTabla();
                $out[tabla] = $html;
//                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
//                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
//                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarArbolOrganizacional($parametros, 1, 100000);
            $data=$this->dbl->data;

             $grid->SetConfiguracion("tblArbolOrganizacional", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
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
 
 
            public function indexArbolOrganizacional($parametros)
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
                $grid = $this->verListaArbolOrganizacional($parametros);
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_ArbolOrganizacional();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;ArbolOrganizacional';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';

                $template->setTemplate("busqueda");
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';

                $template->setTemplate("listar");
                
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
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","organizacion");
                $objResponse->addIncludeScript(PATH_TO_JS . 'organizacion/organizacion.js?'.  rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("admin_ao();");
                return $objResponse;
            }
            
            /**
             * Devuelve el numero de niveles del arbol
             * 
             */
            public function numeroNivelesHijos($param=array(),$num=0) {
                $ids = implode(",", $param);
                $sql = "select id from mos_organizacion where parent_id in ($ids)";
                $data = $this->dbl->query($sql);
                if (count($data)>0){
                    $param = array();
                    foreach ($data as $value) {
                        $param[] = $value[id];
                    }
                    $num++;
                    return $this->numeroNivelesHijos($param,$num);
                }
                return $num;                                
            }
            
            public function indexArbolOrganizacionalReporte($parametros)
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
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($parametros);
                }
                //print_r($parametros);
                //print_r($this->id_org_acceso_explicito);
                $parametros[reg_por_pagina] = 5000;
                $parametros[niveles] = $this->numeroNivelesHijos(array($parametros["b-id_organizacion"]));
                $grid = $this->verListaArbolOrganizacionalReporte($parametros);
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido[TITULO_TABLA] = $grid['titulo'];
                
                
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['FECHA'] = date('d/m/Y');
                //$contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                //$contenido['JS_NUEVO'] = 'nuevo_ArbolOrganizacional();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;ArbolOrganizacional';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';

                $template->setTemplate("busqueda");
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';
                
                $contenido[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $this->jstree_ao(0,$parametros);                
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
                $objResponse->addAssign('modulo_actual',"value","organizacion");
                $objResponse->addIncludeScript(PATH_TO_JS . 'organizacion/organizacion.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('init_filtro_ao_simple();
                                        PanelOperator.initPanels("");
                                        ScrollBar.initScroll();
                                        PanelOperator.resize();
                                        ');
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
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;ArbolOrganizacional";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ArbolOrganizacional";
                $contenido['PAGINA_VOLVER'] = "listarArbolOrganizacional.php";
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
                    
                    $respuesta = $this->ingresarArbolOrganizacional($parametros);

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
            
            public function guardar_area_espejo($parametros)
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
                    
                    $respuesta = $this->modificarAreaEspejo($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        //$objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();");                 
                $objResponse->addScript("$('#tree').jstree(true).refresh()");
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verArbolOrganizacional($parametros[id]); 

                            $contenido_1['PARENT_ID'] = $val["parent_id"];
            $contenido_1['POSITION'] = $val["position"];
            $contenido_1['LEFT'] = $val["left"];
            $contenido_1['RIGHT'] = $val["right"];
            $contenido_1['LEVEL'] = $val["level"];
            $contenido_1['TITLE'] = ($val["title"]);
            $contenido_1['TYPE'] = ($val["type"]);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;ArbolOrganizacional";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;ArbolOrganizacional";
                $contenido['PAGINA_VOLVER'] = "listarArbolOrganizacional.php";
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
                    
                    $respuesta = $this->modificarArbolOrganizacional($parametros);

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
                $val = $this->verArbolOrganizacional($parametros[id]);
                $respuesta = $this->eliminarArbolOrganizacional($parametros);
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
                if (strlen($parametros['b-id_organizacion'])== 0)
                    $parametros['b-id_organizacion'] = 2;     
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($parametros);
                }
                $parametros[reg_por_pagina] = 5000;
                $parametros[niveles] = $this->numeroNivelesHijos(array($parametros["b-id_organizacion"]));
                $grid = $this->verListaArbolOrganizacionalReporte($parametros);                
                $objResponse = new xajaxResponse();
                
                $html = '<table class="table table-report  ">
                      <thead>
                      <tr>'.$grid[titulo].'</tr>
                      </thead>
                      <tbody>'.$grid[tabla].'</tbody>
                    </table>';
                $objResponse->addAssign('grid',"innerHTML",$html);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verArbolOrganizacional($parametros[id]);

                            $contenido_1['PARENT_ID'] = $val["parent_id"];
            $contenido_1['POSITION'] = $val["position"];
            $contenido_1['LEFT'] = $val["left"];
            $contenido_1['RIGHT'] = $val["right"];
            $contenido_1['LEVEL'] = $val["level"];
            $contenido_1['TITLE'] = ($val["title"]);
            $contenido_1['TYPE'] = ($val["type"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'organizacion/';
                $template->setTemplate("verArbolOrganizacional");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la ArbolOrganizacional";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
            
            public function buscar_hijos($parametros)
            {
                $parametros = $this->dbl->corregir_parametros($parametros);
                $hijos = $this->BuscaOrgNivelHijos($parametros['b-id_organizacion']);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('nivel',"value",$hijos);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                $nombre = BuscaOrganizacional(array('id_organizacion' => $parametros['b-id_organizacion']));
                $objResponse->addAssign('desc-arbol',"innerHTML",$nombre);
                //$objResponse->addScript("procesar_filtrar_arbol();");
                $objResponse->addScript("$('#myModal-Filtrar-Arbol').modal('hide');");
                if (isset($parametros[respon]) && $parametros[respon] == '1'){
                    $sql = "select cod_emp from mos_responsable_area where id_organizacion = " . $parametros['b-id_organizacion'];
                    $data = $this->dbl->query($sql);
                    if (count($data)> 0){
                        $objResponse->addScript("$('#responsable_desvio').val('".$data[0][cod_emp]."');");
                        $objResponse->addScript('$( "#responsable_desvio" ).select2({
                                                    placeholder: "Selecione el elaborador",
                                                    allowClear: true
                                                  });actualizar_cambio_respon_desvio();');
                    }
                }
                return $objResponse;
            }
            
        /**
         *  Devuelve los ID de los Hijos
        */            
        public function BuscaOrgNivelHijos($IDORG)
        {
            $OrgNom = $IDORG;            
            $Consulta3="select id as id_organizacion, parent_id as organizacion_padre, title as identificacion from mos_organizacion where parent_id='".$IDORG."' order by id";            
            $data = $this->dbl->query($Consulta3,array());
            foreach( $data as $Fila3)
            {                    
                    $OrgNom .= ",".$this->BuscaOrgNivelHijos($Fila3[id_organizacion]);
            }
            return $OrgNom;
        }
        
        /**
         *  Devuelve los ID de los Hijos
        */            
        public function BuscaOrgNivelPadres($IDORG)
        {
            $OrgNom = $IDORG;            
            $Consulta3="select id as id_organizacion, parent_id as organizacion_padre, title as identificacion from mos_organizacion where id='".$IDORG."' order by id";            
            $data = $this->dbl->query($Consulta3,array());
            foreach( $data as $Fila3)
            {                    
                if(($Fila3[organizacion_padre]==2)||($Fila3[organizacion_padre]==1))
                {
                    $OrgNom.=($Fila3[organizacion_padre]);
                    return($OrgNom);                                        
                }
                else{
                    $OrgNom .= ",".$this->BuscaOrgNivelPadres($Fila3[organizacion_padre]);
                }
            }
            return $OrgNom;
        }
        
        /**
         *  Devuelve la estructura HTML para el arbol jtree 
         * 
         * @param int $contar Plus para informacion adicional 1=> Documentos, 2 => Registro, 3 => Acciones Correctivas
         */
        public function jstree_ao($contar=0,$parametros=array()){
            //print_r($parametros);
            if(!class_exists('Template')){
                import("clases.interfaz.Template");
            }            
            $contenido_1[AO] = $this->MuestraPadre($contar,$parametros);
            $template = new Template();
            $template->PATH = PATH_TO_TEMPLATES.'organizacion/';
            if($parametros['opcion']=='reg')
                $template->setTemplate("jstree_ao_reg");
            else
                if ($parametros['opcion']=='simple')
                    $template->setTemplate("jstree_ao_simple");
                else {
                    $template->setTemplate("jstree_ao");
                }
            if($parametros[param_cambio_nombre]!=''){
                $contenido_1[IDNAMEBUSCAR]=$parametros[id_nombre_arbol_buscar];
                $contenido_1[NOMBREARBOL]=$parametros[param_cambio_nombre];
            }   
            else{
                $contenido_1[NOMBREARBOL]='Árbol Organizacional';
                $contenido_1[IDNAMEBUSCAR]='demo_q_ao';
            }
            $template->setVars($contenido_1);            

            return $template->show();
        }
        
        /**
         *  Devuelve la array para administrar el arbol 
         *          
         */
        public function admin_jstree_ao($parametros){
            $parametros = $this->dbl->corregir_parametros($parametros);
            $this->cargar_acceso_nodos($parametros);
            if (strlen($parametros[cod_link])>0){
               if(!class_exists('mos_acceso')){
                   import("clases.mos_acceso.mos_acceso");
               }
               $acceso = new mos_acceso();
               $data_ids_acceso = $acceso->obtenerNodosArbol($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);
               //print_r($data_ids_acceso);
               foreach ($data_ids_acceso as $value) {
                   $this->id_org_acceso_exclusivo[$value[id]] = $value;
               }                                            
           }
           $sql = "Select min(level) level FROM mos_organizacion WHERE id IN (". implode(',', array_keys($this->id_org_acceso_exclusivo)) . ")";
           
           $nivel_acceso = $this->dbl->query($sql);
           $nivel_acceso = $nivel_acceso[0][level] < 1 ? 1 : $nivel_acceso[0][level];
           $sql = "Select id FROM mos_organizacion WHERE id IN (". implode(',', array_keys($this->id_org_acceso)) . ") AND level = $nivel_acceso";
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
           
           //$result = $this->AdminMuestraHijos(2);                    

           return $result;
        }
        
        /**
         *  Devuelve el HTML para el jtree incluyendo los hijos 
         * 
         * @param int $id Id del arbol organizacional
         * 
         * @param int $contar Plus para contar el numero de registros hijos en el arbol
         */
	public function AdminMuestraHijos($id){
		
                $items = array();
                $sql="select * from mos_organizacion
				Where id = $id";
                $data = $this->dbl->query($sql, $atr);
                $items[0] = array(id=>$data[0][id], text=>$data[0][title], "state" => array("opened" => true ), "type"=> strlen($data[0][area_espejo])>0?'area_espejo':'default');
                
                $sql="select * from mos_organizacion
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
        
        /**
        * Activa los nodos donde se tiene acceso
        */
       public function cargar_acceso_nodos($parametros){
           if (strlen($parametros[cod_link])>0){
               if(!class_exists('mos_acceso')){
                   import("clases.mos_acceso.mos_acceso");
               }
               $acceso = new mos_acceso();
               $data_ids_acceso = $acceso->obtenerArbolEstructura($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);
               //print_r($data_ids_acceso);
               foreach ($data_ids_acceso as $value) {
                   //if (is_array($value))
                   $this->id_org_acceso[$value[id]] = $value;
               }                   
           }
       }
       
       /**
        * Activa los nodos donde se tiene acceso
        */
       public function cargar_acceso_nodos_explicito($parametros){
           if (strlen($parametros[cod_link])>0){
               if(!class_exists('mos_acceso')){
                   import("clases.mos_acceso.mos_acceso");
               }
               $acceso = new mos_acceso();
               $data_ids_acceso = $acceso->obtenerNodosArbol($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);
               //print_r($data_ids_acceso);
               foreach ($data_ids_acceso as $value) {
                   $this->id_org_acceso_explicito[$value[id]] = $value;
               }                                            
           }
       }
        
        /**
         * Devuelve la estructura HTML del primer nivel para el jtree 
         */
        public function MuestraPadre($contar,$parametros=array()){
            
            $this->cargar_acceso_nodos($parametros);
		$sql="Select * from mos_organizacion
				Where parent_id = 1";
                
                //
                if (count($this->id_org_acceso)>0){
                    $data = $this->obtenerNodosArbolNivel($this->id_org_acceso,1);
                }
                else{
                    $data = $this->dbl->query($sql, $atr);
                }
                //print_r($data);

		$padre_final = "";
                
		//while($arrP=mysql_fetch_assoc($resp)){
                foreach ($data as $arrP) {//data-jstree='{ \"type\" : \"verde\" }'
                       // $arrP[id];
                        $data_hijo = $this->MuestraHijos($arrP[id],$contar,$parametros);
                        if ((is_array($parametros[nodos_seleccionados]))&&(in_array($arrP[id], $parametros[nodos_seleccionados]))){
                            $select_aux = 'jstree-clicked';
                            //$select_aux = '';
                        }
                        else $select_aux = '';
                        $cuerpo .= "<li  id=\"phtml_".$arrP[id]."\" class=\"jstree-open\">";
                        switch ($contar) {
                            case 1:
                                $sql = "SELECT COUNT(DISTINCT(eao.IDDoc)) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S'  AND formulario = 'N' and d.etapa_workflow = 'estado_aprobado'"
                                    . " ";   
                                $sql .= " AND (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                if ($parametros["b-publico"] == 'S')
                                    $sql .= " OR (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso)).") AND d.publico = 'S' )";
                                $sql .= ")";
                                //echo $sql . ' <br>';
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $cuerpo .= "<a href=\"#\" class=\"$select_aux\">".($arrP[title])." (". ($contador). ")</a>";
                                break;
                            case 2:
                                $sql = "SELECT COUNT(DISTINCT(eao.IDDoc)) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S' AND formulario = 'S'  and d.etapa_workflow = 'estado_aprobado'"
                                    . " "; 
                                $sql .= " AND (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                if ($parametros["b-publico"] == 'S')
                                    $sql .= " OR (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso)).") AND d.publico = 'S' )";
                                $sql .= ")";
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $cuerpo .= "<a href=\"#\" class=\"$select_aux\">".($arrP[title])." (". ($contador). ")</a>";
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
                                $cuerpo .= "<a href=\"#\" class=\"$select_aux\">".($arrP[title])." ($return[total];$return[atrasadas];$return[en_plazo];$return[realizada_atraso];$return[realizada] )</a>";
                                break;
                            //cuenta los registros de un documento
                            case 4:
                                $sql = "SELECT
                                        IFNULL(count(mos_registro_item.idRegistro),0) cant  
                                        FROM
                                        mos_registro_item inner join mos_registro reg 
                                        on reg.idRegistro = mos_registro_item.idRegistro
                                        WHERE
                                        reg.vigencia = 'S' and
                                        mos_registro_item.IDDoc= ".$_SESSION[IDDoc]." and 
                                        valor in (".$this->BuscaOrgNivelHijos($arrP[id]).")
                                        and tipo = 11 ";
                                    if (count($this->id_org_acceso_explicito)>0)
                                       $sql .= " and valor in (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                   // echo $sql;
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador='';
                                $contador=$data_aux[0][cant];
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $cuerpo .= "<a href=\"#\" class=\"$select_aux\">".($arrP[title])." (". ($contador). ")</a>";
                                break;
                            case 6:
                                //FALTA AQUIIIIIIIIIII
                                //// los registros de los empleados de las areas donde tiene permisos
                                $sql = "SELECT 
                                        count(reg.idRegistro) cant
                                        FROM
                                        mos_registro_formulario reg inner join mos_personal per on
                                        reg.Nombre = per.cod_emp inner join mos_registro reg_1 
                                        on reg.idRegistro = reg_1.idRegistro
                                        WHERE
                                        reg_1.vigencia = 'S' and
                                        reg.IDDoc = ".$_SESSION[IDDoc]." and tipo=6
                                        and per.id_organizacion in (".$this->BuscaOrgNivelHijos($arrP[id]).")";
                                    if (count($this->id_org_acceso_explicito)>0)
                                       $sql .= " and per.id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                
                                //echo $sql;
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador='';
                                $contador=$data_aux[0][cant];
                                $sql = "SELECT 
                                        count(idRegistro) cant
                                        FROM
                                        mos_registro_formulario reg inner join mos_personal per on
                                        reg.Nombre = per.cod_emp
                                        WHERE
                                        reg.IDDoc = ".$_SESSION[IDDoc]." and tipo=6
                                        and id_organizacion in (".$arrP[id].")";
                                    if (count($this->id_org_acceso_explicito)>0)
                                       $sql .= " and id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                //echo $sql;
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador_uni='';
                                $contador_uni=$data_aux[0][cant];
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                               // if($contador_uni==0 && $contador==0)
                                 //   $cuerpo .= "<a href=\"#\">".($arrP[title])." ()</a>";
                                //else
                                    $cuerpo .= "<a href=\"#\" class=\"$select_aux\">".($arrP[title])." (". ($contador_uni.';'.$contador). ")</a>";
                                break;

                            default:
                                $cuerpo .= "<a href=\"#\" class=\"$select_aux\">".($arrP[title])." </a>";
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
	public function MuestraHijos($id,$contar,$parametros=array()){
		$sql="select * from mos_organizacion
				Where parent_id = $id";
                //echo $sql;
		//$resp = mysql_query($sql);
                //
                if (count($this->id_org_acceso)>0)
                    $data = $this->obtenerNodosArbolNivel($this->id_org_acceso,$id);
                else $data = $this->dbl->query($sql, $atr);
                //print_r($data);
                $contador = 0;
                $data_hijo= array();
		$cabecera = "<ul>";
                foreach ($data as $arr) {//data-jstree='{ \"type\" : \"rojo\" }' 
                    $contador = array();
                    $data_hijo = $this->MuestraHijos($arr[id],$contar,$parametros);
                    if ((is_array($parametros[nodos_seleccionados]))&&(in_array($arr[id], $parametros[nodos_seleccionados]))){
                        $select_aux = 'jstree-clicked';
                        //$select_aux = '';
                    }
                    else $select_aux = '';
                    $extra .= "<li id=\"phtml_".$arr[id]."\" >";
                    switch ($contar) {
                            case 1:
                                $sql = "SELECT COUNT(*) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE eao.id_organizacion_proceso IN (". $this->BuscaOrgNivelHijos($arr[id]) . ")   AND tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S'  AND formulario = 'N'  and d.etapa_workflow = 'estado_aprobado'";
                                $sql .= " AND (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                if ($parametros["b-publico"] == 'S')
                                    $sql .= " OR (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso)).") AND d.publico = 'S' )";
                                $sql .= ")";
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                $extra .= "<a href=\"#\" class=\"$select_aux\">".($arr[title])." (". ($contador) .")</a>";
                                break;
                            case 2:
                                $sql = "SELECT COUNT(DISTINCT(eao.IDDoc)) total "
                                    . "FROM mos_documentos_estrorg_arbolproc eao "
                                    . "INNER JOIN mos_documentos d ON d.IDDoc = eao.IDDoc "
                                    . "WHERE eao.id_organizacion_proceso IN (". $this->BuscaOrgNivelHijos($arr[id]) . ")  AND tipo = 'EO' AND d.vigencia = 'S' AND muestra_doc = 'S' AND formulario = 'S'  and d.etapa_workflow = 'estado_aprobado'"
                                    . " ";     
                                $sql .= " AND (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                if ($parametros["b-publico"] == 'S')
                                    $sql .= " OR (eao.id_organizacion_proceso IN (".implode(',', array_keys($this->id_org_acceso)).") AND d.publico = 'S' )";
                                $sql .= ")";
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador = $data_aux[0][total] + 0;
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $extra .= "<a href=\"#\" class=\"$select_aux\">".($arr[title])." (". ($contador). ")</a>";
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
                                $extra .= "<a href=\"#\" class=\"$select_aux\">".($arr[title])." ($contador[total];$contador[atrasadas];$contador[en_plazo];$contador[realizada_atraso];$contador[realizada] )</a>";
                                break;
                            case 4:
                                $sql="SELECT
                                    IFNULL(count(mos_registro_item.idRegistro),0) cant
                                    FROM
                                    mos_registro_item inner join mos_registro reg 
                                        on reg.idRegistro = mos_registro_item.idRegistro
                                    WHERE
                                    reg.vigencia = 'S' and
                                    mos_registro_item.IDDoc= ".$_SESSION[IDDoc]." and
                                    valor in (".$this->BuscaOrgNivelHijos($arr[id]).")
                                    and tipo = 11;";  

                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador='';
                                $contador=$data_aux[0][cant];
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                $extra .= "<a href=\"#\" class=\"$select_aux\">".($arr[title])." (". ($contador). ")</a>";
                                break;
                            case 6:
                                $sql = "SELECT 
                                        count(reg.idRegistro) cant
                                        FROM
                                        mos_registro_formulario reg inner join mos_personal per on
                                        reg.Nombre = per.cod_emp inner join mos_registro reg_1 
                                        on reg.idRegistro = reg_1.idRegistro
                                        WHERE
                                        reg_1.vigencia = 'S' and
                                        reg.IDDoc = ".$_SESSION[IDDoc]." and tipo=6
                                        and per.id_organizacion in (".$this->BuscaOrgNivelHijos($arr[id]).")";
                                    //if (count($this->id_org_acceso_explicito)>0)
                                       $sql .= " and per.id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).")";

                                
                                $data_aux = $this->dbl->query($sql, $atr);
                                $contador='';
                                $contador=$data_aux[0][cant];                                                                
                                $sql = "SELECT 
                                        count(reg.idRegistro) cant
                                        FROM
                                        mos_registro_formulario reg inner join mos_personal per on
                                        reg.Nombre = per.cod_emp inner join mos_registro reg_1 
                                        on reg.idRegistro = reg_1.idRegistro
                                        WHERE
                                        reg_1.vigencia = 'S' and
                                        reg.IDDoc = ".$_SESSION[IDDoc]." and tipo=6
                                        and per.id_organizacion in (".$arr[id].")";
                                    //if (count($this->id_org_acceso_explicito)>0)
                                       $sql .= " and per.id_organizacion in (".implode(',', array_keys($this->id_org_acceso_explicito)).")";
                                //echo $sql;
                                $data_aux = $this->dbl->query($sql, $atr);                                
                                
                                $contador_uni='';
                                 $contador_uni=$data_aux[0][cant];
                                //$cuerpo .= "<a href=\"#\">".($arrP[title])." (". ($contador + $data_hijo[contador]) .")</a>";
                                 //if($contador_uni==0 && $contador==0)
                                  //   $extra .= "<a href=\"#\">".($arr[title])." ()</a>";
                                 //else
                                     $extra .= "<a href=\"#\" class=\"$select_aux\">".($arr[title])." (". ($contador_uni.';'.$contador). ")</a>";
                                break;
                            
                            default:
                                //
                                $extra .= "<a href=\"#\" class=\"$select_aux\">".($arr[title])."</a>";
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
        
        /**
         * Devuelve los nombres de los padres de un nodo del arbol
         * @param array $tupla 
         * @return string
         */
        function BuscaOrganizacional($tupla)
        {
                $OrgNom = "";
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                //if($Fila3[organizacion_padre]==2)
                                if(($Fila3[organizacion_padre]==2)||($Fila3[organizacion_padre]==1))
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= $this->BuscaOrganizacional(array('id_organizacion' => $Fila3[organizacion_padre])) . ' &#8594; ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= $_SESSION[CookNomEmpresa];
                return $OrgNom;

        }
        
        public function marcar_area_espejo($parametros) {
            
            $contenido_1   = array();
            $parametros[opcion] = 'simple';
            $sql = "SELECT area_espejo FROM mos_organizacion_nombres WHERE id = $parametros[id]";
            $data = $this->dbl->query($sql);
            $parametros[nodos_seleccionados] = array();
            if (strlen($data[0][area_espejo])>0)
                $parametros[nodos_seleccionados] = array($data[0][area_espejo]);
            $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $this->jstree_ao(0,$parametros);
            $objResponse = new xajaxResponse();
            //$objResponse->addAssign('contenido',"innerHTML",$template->show());
            //$objResponse->addAssign('myModal-Ventana-Cuerpo',"innerHTML",$template->show());
            $objResponse->addAssign('modal-body-area-espejo',"innerHTML",$contenido_1[DIV_ARBOL_ORGANIZACIONAL]);
            $objResponse->addAssign('origen-id-area',"value",$parametros[id]);
            
            //$objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
            //$objResponse->addAssign('modulo_actual',"value","items_formulario");
            $objResponse->addIncludeScript(PATH_TO_JS . 'items_formulario/items_formulario.js');
            $objResponse->addScript("$('#MustraCargando').hide();");
            //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
            $objResponse->addScript("$('#myModal-Area-Espejo').modal('show');");
            $objResponse->addScript("$('#myModal-Area-Espejo-Titulo').html('Área Vinculada');"); 
            $objResponse->addScript('ao_simple();');
            return $objResponse;
            
        }
        
        
     
 }?>