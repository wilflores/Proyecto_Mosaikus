<?php
 import("clases.interfaz.Pagina");        
        class MatricesParametros extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
            
            public function MatricesParametros(){
                parent::__construct();
                $this->asigna_script('matrices_parametros/matrices_parametros.js');                                             
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 13";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 13";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


     

             public function verMatricesParametros($id){
                $atr=array();
                $sql = "SELECT cod_categoria
                                ,id_cmb_acap
                                ,nombre
                                ,dependencia
                                ,texto
                                ,formula
                                ,calculo_formula
                                ,muestra
                                ,muestrarpt
                                ,tipo
                                ,indicador
                                ,orden
                                ,fecha_nom1
                                ,fecha_nom2
                                ,fecha_sem
                                ,datos
                                ,tip_familia_requisito

                         FROM mos_matrices_parametros 
                         WHERE id_cmb_acap = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(id_cmb_acap) total_registros
                         FROM mos_matrices_parametros";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            
            public function ingresarMatricesParametros($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[id_cmb_acap] = $this->codigo_siguiente();
                    $sql = "INSERT INTO mos_matrices_parametros(cod_categoria,id_cmb_acap,nombre,dependencia,texto,formula,calculo_formula,muestra,muestrarpt,tipo,indicador,orden,fecha_nom1,fecha_nom2,fecha_sem,datos,tip_familia_requisito)
                            VALUES(
                                8,$atr[id_cmb_acap],'$atr[nombre]','$atr[dependencia]','$atr[texto]','$atr[formula]','$atr[calculo_formula]','$atr[muestra]','$atr[muestrarpt]','$atr[tipo]','$atr[indicador]',$atr[orden],'$atr[fecha_nom1]','$atr[fecha_nom2]','$atr[fecha_sem]','$atr[datos]','$atr[tip_familia_requisito]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_matrices_parametros ' . $atr[descripcion_ano], 'mos_matrices_parametros');
                      */
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Cmb Acap: \'$atr[id_cmb_acap]\', Nombre: \'$atr[nombre]\', Dependencia: \'$atr[dependencia]\', Texto: \'$atr[texto]\', Formula: \'$atr[formula]\', Calculo Formula: \'$atr[calculo_formula]\', Muestra: \'$atr[muestra]\', Muestrarpt: \'$atr[muestrarpt]\', Tipo: \'$atr[tipo]\', Indicador: \'$atr[indicador]\', Orden: \'$atr[orden]\', Fecha Nom1: \'$atr[fecha_nom1]\', Fecha Nom2: \'$atr[fecha_nom2]\', Fecha Sem: \'$atr[fecha_sem]\', Datos: \'$atr[datos]\', Tip Familia Requisito: \'$atr[tip_familia_requisito]\', ";
                    $this->registraTransaccionLog(68,$nuevo,'', '');
                    return "El mos_matrices_parametros '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarMatricesParametros($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_matrices_parametros SET                            
                                    nombre = '$atr[nombre]',dependencia = '$atr[dependencia]',texto = '$atr[texto]',formula = '$atr[formula]',calculo_formula = '$atr[calculo_formula]',muestra = '$atr[muestra]',muestrarpt = '$atr[muestrarpt]',tipo = '$atr[tipo]',indicador = '$atr[indicador]',orden = $atr[orden],fecha_nom1 = '$atr[fecha_nom1]',fecha_nom2 = '$atr[fecha_nom2]',fecha_sem = '$atr[fecha_sem]',datos = '$atr[datos]',tip_familia_requisito = '$atr[tip_familia_requisito]'
                            WHERE  id_cmb_acap = $atr[id]";      
                    $val = $this->verMatricesParametros($atr[id]);
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $nuevo = "Cod Categoria: \'$atr[cod_categoria]\', Id Cmb Acap: \'$atr[id_cmb_acap]\', Nombre: \'$atr[nombre]\', Dependencia: \'$atr[dependencia]\', Texto: \'$atr[texto]\', Formula: \'$atr[formula]\', Calculo Formula: \'$atr[calculo_formula]\', Muestra: \'$atr[muestra]\', Muestrarpt: \'$atr[muestrarpt]\', Tipo: \'$atr[tipo]\', Indicador: \'$atr[indicador]\', Orden: \'$atr[orden]\', Fecha Nom1: \'$atr[fecha_nom1]\', Fecha Nom2: \'$atr[fecha_nom2]\', Fecha Sem: \'$atr[fecha_sem]\', Datos: \'$atr[datos]\', Tip Familia Requisito: \'$atr[tip_familia_requisito]\', ";
                    $anterior = "Cod Categoria: \'$val[cod_categoria]\', Id Cmb Acap: \'$val[id_cmb_acap]\', Nombre: \'$val[nombre]\', Dependencia: \'$val[dependencia]\', Texto: \'$val[texto]\', Formula: \'$val[formula]\', Calculo Formula: \'$val[calculo_formula]\', Muestra: \'$val[muestra]\', Muestrarpt: \'$val[muestrarpt]\', Tipo: \'$val[tipo]\', Indicador: \'$val[indicador]\', Orden: \'$val[orden]\', Fecha Nom1: \'$val[fecha_nom1]\', Fecha Nom2: \'$val[fecha_nom2]\', Fecha Sem: \'$val[fecha_sem]\', Datos: \'$val[datos]\', Tip Familia Requisito: \'$val[tip_familia_requisito]\', ";
                    $this->registraTransaccionLog(69,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el MatricesParametros ' . $atr[descripcion_ano], 'mos_matrices_parametros');
                    */
                    return "El mos_matrices_parametros '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function modificarMatricesParametrosGeneral($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_matrices_parametro_general SET                            
                                    descripcion = '$atr[descripcion]',activo = '$atr[activo]'
                            WHERE  cod_param = $atr[cod_param] AND cod_categoria = $atr[cod_categoria] AND agrupacion = $atr[agrupacion]";      
                    //$val = $this->verMatricesParametros($atr[id]);
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $nuevo = "Descripcion: \'$atr[descripcion]\', Activo: \'$atr[activo]\', Cod Categoria: \'8\', Agrupacion: \'$atr[agrupacion]\' ";
                    //$anterior = "Cod Categoria: \'$val[cod_categoria]\', Id Cmb Acap: \'$val[id_cmb_acap]\', Nombre: \'$val[nombre]\', Dependencia: \'$val[dependencia]\', Texto: \'$val[texto]\', Formula: \'$val[formula]\', Calculo Formula: \'$val[calculo_formula]\', Muestra: \'$val[muestra]\', Muestrarpt: \'$val[muestrarpt]\', Tipo: \'$val[tipo]\', Indicador: \'$val[indicador]\', Orden: \'$val[orden]\', Fecha Nom1: \'$val[fecha_nom1]\', Fecha Nom2: \'$val[fecha_nom2]\', Fecha Sem: \'$val[fecha_sem]\', Datos: \'$val[datos]\', Tip Familia Requisito: \'$val[tip_familia_requisito]\', ";
                    $this->registraTransaccionLog(74,$nuevo,'', '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el MatricesParametros ' . $atr[descripcion_ano], 'mos_matrices_parametros');
                    */
                    return "Parametros Generales ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function modificarMatricesParametrosGeneralSemaforo($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_matrices_sem_final SET                            
                                    valor = $atr[valor],valor2 = $atr[valor2]
                            WHERE  semaforo = $atr[semaforo] AND cod_categoria = $atr[cod_categoria]";      
                    //$val = $this->verMatricesParametros($atr[id]);
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    //$nuevo = "Descripcion: \'$atr[descripcion]\', Activo: \'$atr[activo]\', Cod Categoria: \'8\', Agrupacion: \'$atr[agrupacion]\' ";
                    //$anterior = "Cod Categoria: \'$val[cod_categoria]\', Id Cmb Acap: \'$val[id_cmb_acap]\', Nombre: \'$val[nombre]\', Dependencia: \'$val[dependencia]\', Texto: \'$val[texto]\', Formula: \'$val[formula]\', Calculo Formula: \'$val[calculo_formula]\', Muestra: \'$val[muestra]\', Muestrarpt: \'$val[muestrarpt]\', Tipo: \'$val[tipo]\', Indicador: \'$val[indicador]\', Orden: \'$val[orden]\', Fecha Nom1: \'$val[fecha_nom1]\', Fecha Nom2: \'$val[fecha_nom2]\', Fecha Sem: \'$val[fecha_sem]\', Datos: \'$val[datos]\', Tip Familia Requisito: \'$val[tip_familia_requisito]\', ";
                    //$this->registraTransaccionLog(74,$nuevo,'', '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el MatricesParametros ' . $atr[descripcion_ano], 'mos_matrices_parametros');
                    */
                    return "Parametros Generales ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
             public function listarMatricesParametros($atr, $pag, $registros_x_pagina){
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
                         FROM mos_matrices_parametros mp 
                                left join mos_matrices_parametro_general mpg on mpg.cod_param=mp.dependencia and mp.cod_categoria=mpg.cod_categoria and agrupacion='1'
                            WHERE mp.cod_categoria = 8 AND dependencia in (1,2) ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-id_cmb_acap"])>0)
                        $sql .= " AND id_cmb_acap = '". $atr["b-id_cmb_acap"] . "'";
            if (strlen($atr["b-nombre"])>0)
                        $sql .= " AND upper(nombre) like '%" . strtoupper($atr["b-nombre"]) . "%'";
            if (strlen($atr["b-dependencia"])>0)
                        $sql .= " AND upper(dependencia) like '%" . strtoupper($atr["b-dependencia"]) . "%'";
            if (strlen($atr["b-texto"])>0)
                        $sql .= " AND upper(texto) like '%" . strtoupper($atr["b-texto"]) . "%'";
            if (strlen($atr["b-formula"])>0)
                        $sql .= " AND upper(formula) like '%" . strtoupper($atr["b-formula"]) . "%'";
             if (strlen($atr["b-calculo_formula"])>0)
                        $sql .= " AND calculo_formula = '". $atr["b-calculo_formula"] . "'";
            if (strlen($atr["b-muestra"])>0)
                        $sql .= " AND upper(muestra) like '%" . strtoupper($atr["b-muestra"]) . "%'";
            if (strlen($atr["b-muestrarpt"])>0)
                        $sql .= " AND upper(muestrarpt) like '%" . strtoupper($atr["b-muestrarpt"]) . "%'";
            if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";
            if (strlen($atr["b-indicador"])>0)
                        $sql .= " AND upper(indicador) like '%" . strtoupper($atr["b-indicador"]) . "%'";
             if (strlen($atr["b-orden"])>0)
                        $sql .= " AND orden = '". $atr["b-orden"] . "'";
            if (strlen($atr["b-fecha_nom1"])>0)
                        $sql .= " AND upper(fecha_nom1) like '%" . strtoupper($atr["b-fecha_nom1"]) . "%'";
            if (strlen($atr["b-fecha_nom2"])>0)
                        $sql .= " AND upper(fecha_nom2) like '%" . strtoupper($atr["b-fecha_nom2"]) . "%'";
            if (strlen($atr["b-fecha_sem"])>0)
                        $sql .= " AND upper(fecha_sem) like '%" . strtoupper($atr["b-fecha_sem"]) . "%'";
             if (strlen($atr["b-datos"])>0)
                        $sql .= " AND datos = '". $atr["b-datos"] . "'";
            if (strlen($atr["b-tip_familia_requisito"])>0)
                        $sql .= " AND upper(tip_familia_requisito) like '%" . strtoupper($atr["b-tip_familia_requisito"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT mp.id_cmb_acap
                                ,mp.cod_categoria
                                ,mp.nombre
                                ,mpg.descripcion dependencia
                                ,texto
                                ,formula
                                ,calculo_formula
                                ,muestra
                                ,muestrarpt
                                ,CASE tipo WHEN '1' THEN 'ComboBox'
                                           WHEN '2' THEN 'Texto'
                                           WHEN '3' THEN 'Fecha'
                                           WHEN '4' THEN 'Personal'
                                           ELSE 'Semaforo'
                                 END tipo
                                ,indicador
                                ,mp.orden
                                ,fecha_nom1
                                ,fecha_nom2
                                ,fecha_sem
                                ,datos
                                ,tip_familia_requisito

                                     $sql_col_left
                            FROM mos_matrices_parametros mp $sql_left
                                left join mos_matrices_parametro_general mpg on mpg.cod_param=mp.dependencia and mp.cod_categoria=mpg.cod_categoria and agrupacion='1'
                            WHERE mp.cod_categoria = 8 AND dependencia in (1,2) ";
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-cod_categoria"])>0)
                        $sql .= " AND cod_categoria = '". $atr["b-cod_categoria"] . "'";
             if (strlen($atr["b-id_cmb_acap"])>0)
                        $sql .= " AND id_cmb_acap = '". $atr["b-id_cmb_acap"] . "'";
            if (strlen($atr["b-nombre"])>0)
                        $sql .= " AND upper(nombre) like '%" . strtoupper($atr["b-nombre"]) . "%'";
            if (strlen($atr["b-dependencia"])>0)
                        $sql .= " AND upper(dependencia) like '%" . strtoupper($atr["b-dependencia"]) . "%'";
            if (strlen($atr["b-texto"])>0)
                        $sql .= " AND upper(texto) like '%" . strtoupper($atr["b-texto"]) . "%'";
            if (strlen($atr["b-formula"])>0)
                        $sql .= " AND upper(formula) like '%" . strtoupper($atr["b-formula"]) . "%'";
             if (strlen($atr["b-calculo_formula"])>0)
                        $sql .= " AND calculo_formula = '". $atr["b-calculo_formula"] . "'";
            if (strlen($atr["b-muestra"])>0)
                        $sql .= " AND upper(muestra) like '%" . strtoupper($atr["b-muestra"]) . "%'";
            if (strlen($atr["b-muestrarpt"])>0)
                        $sql .= " AND upper(muestrarpt) like '%" . strtoupper($atr["b-muestrarpt"]) . "%'";
            if (strlen($atr["b-tipo"])>0)
                        $sql .= " AND upper(tipo) like '%" . strtoupper($atr["b-tipo"]) . "%'";
            if (strlen($atr["b-indicador"])>0)
                        $sql .= " AND upper(indicador) like '%" . strtoupper($atr["b-indicador"]) . "%'";
             if (strlen($atr["b-orden"])>0)
                        $sql .= " AND orden = '". $atr["b-orden"] . "'";
            if (strlen($atr["b-fecha_nom1"])>0)
                        $sql .= " AND upper(fecha_nom1) like '%" . strtoupper($atr["b-fecha_nom1"]) . "%'";
            if (strlen($atr["b-fecha_nom2"])>0)
                        $sql .= " AND upper(fecha_nom2) like '%" . strtoupper($atr["b-fecha_nom2"]) . "%'";
            if (strlen($atr["b-fecha_sem"])>0)
                        $sql .= " AND upper(fecha_sem) like '%" . strtoupper($atr["b-fecha_sem"]) . "%'";
             if (strlen($atr["b-datos"])>0)
                        $sql .= " AND datos = '". $atr["b-datos"] . "'";
            if (strlen($atr["b-tip_familia_requisito"])>0)
                        $sql .= " AND upper(tip_familia_requisito) like '%" . strtoupper($atr["b-tip_familia_requisito"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    $this->operacion($sql, $atr);
             }
             public function eliminarMatricesParametros($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $sql = "SELECT COUNT(*) total_registros
                                    FROM mos_matrices_detalle 
                                WHERE cod_categoria = 8 AND id_cmb_acap = $atr[id]";                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene accion(es) correctiva(s) asociada(s).";
                        }
                        
                        $sql = "SELECT COUNT(*) total_registros
                                    FROM mos_matrices_control_detalle 
                                WHERE cod_categoria = 8 AND id_cmb_acap = $atr[id]";                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];

                        if ($total+0 > 0){
                            //echo $total; 
                            return "- No se puede eliminar, tiene accion(es) asociada(s).";
                        }
                        $val = $this->verMatricesParametros($atr[id]);
                        $respuesta = $this->dbl->delete("mos_matrices_parametros", " cod_categoria = 8 AND id_cmb_acap = $atr[id]");
                        $nuevo = "Cod Categoria: \'$val[cod_categoria]\', Id Cmb Acap: \'$val[id_cmb_acap]\', Nombre: \'$val[nombre]\', Dependencia: \'$val[dependencia]\', Texto: \'$val[texto]\', Formula: \'$val[formula]\', Calculo Formula: \'$val[calculo_formula]\', Muestra: \'$val[muestra]\', Muestrarpt: \'$val[muestrarpt]\', Tipo: \'$val[tipo]\', Indicador: \'$val[indicador]\', Orden: \'$val[orden]\', Fecha Nom1: \'$val[fecha_nom1]\', Fecha Nom2: \'$val[fecha_nom2]\', Fecha Sem: \'$val[fecha_sem]\', Datos: \'$val[datos]\', Tip Familia Requisito: \'$val[tip_familia_requisito]\', ";
                        $this->registraTransaccionLog(70,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaMatricesParametros($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarMatricesParametros($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblMatricesParametros", "");
                $config_col=array(
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_cmb_acap], "id_cmb_acap", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[cod_categoria], "cod_categoria", $parametros)),               
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombre], "nombre", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[dependencia], "dependencia", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[texto], "texto", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[formula], "formula", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[calculo_formula], "calculo_formula", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[muestra], "muestra", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[muestrarpt], "muestrarpt", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[tipo], "tipo", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[indicador], "indicador", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[orden], "orden", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_nom1], "fecha_nom1", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_nom2], "fecha_nom2", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_sem], "fecha_sem", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[datos], "datos", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[tip_familia_requisito], "tip_familia_requisito", $parametros))
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
                    
                    $columna_funcion = 18;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verMatricesParametros','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver MatricesParametros'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarMatricesParametros','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar MatricesParametros'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('condicion'=>array('columna'=>'id_cmb_acap', 'valor'=> '>3'), 'nombre'=> 'eliminarMatricesParametros','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar MatricesParametros'>"));
               if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('condicion'=>array('columna'=>'tipo', 'valor'=> "=='ComboBox'"), 'segundo' => array('nombre'), 'nombre'=> 'administrarItem','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_explorer.png' title='Item'>"));
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"&nbsp;"));
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
                if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina)){
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarMatricesParametros($parametros, 1, 100000);
            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                
             $grid->SetConfiguracion("tblMatricesParametros", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_cmb_acap], ENT_QUOTES, "UTF-8")),        
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[cod_categoria], ENT_QUOTES, "UTF-8")),
         
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nombre], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[dependencia], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[texto], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[formula], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[calculo_formula], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[muestra], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[muestrarpt], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[tipo], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[indicador], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[orden], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_nom1], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_nom2], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha_sem], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[datos], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[tip_familia_requisito], ENT_QUOTES, "UTF-8"))
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
 
 
            public function indexMatricesParametros($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id_cmb_acap";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="0-2-3-9-11"; 
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
                $grid = $this->verListaMatricesParametros($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_MatricesParametros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;MatricesParametros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros/';
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
                $ut_tool = new ut_Tool();
                $ids = array('','1', '2','3', '4', '5'); 
                $desc = array('-- Todos --', 'ComboBox', 'Texto', 'Fecha', 'Personal', 'Semaforo');
                //join mos_matrices_parametro_general mpg on mpg.cod_param=mp.dependencia and mp.cod_categoria=mpg.cod_categoria and agrupacion='1'
                $contenido[CHECKED_IND_SI] = 'checked="checked"';
                $contenido['TIPOS'] = $ut_tool->combo_array("b-tipo", $desc, $ids,false,56);
                $contenido['DEPENDENCIA'] = $ut_tool->OptionsCombo("SELECT cod_param, 
                                                                        descripcion
                                                                            FROM mos_matrices_parametro_general WHERE cod_param IN (1,2) and cod_categoria=8 and agrupacion='1'"
                                                                    , 'cod_param'
                                                                    , 'descripcion', $val['dependencia']);
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros/';

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
                $objResponse->addAssign('modulo_actual',"value","matrices_parametros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'matrices_parametros/matrices_parametros.js');
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
                $ids = array('1', '2','3', '4', '5'); 
                $desc = array('ComboBox', 'Texto', 'Fecha', 'Personal', 'Semaforo');
                //join mos_matrices_parametro_general mpg on mpg.cod_param=mp.dependencia and mp.cod_categoria=mpg.cod_categoria and agrupacion='1'
                $contenido_1[CHECKED_IND_SI] = 'checked="checked"';
                $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,56);
                $contenido_1['DEPENDENCIA'] = $ut_tool->OptionsCombo("SELECT cod_param, 
                                                                        descripcion
                                                                            FROM mos_matrices_parametro_general WHERE cod_param IN (1,2) and cod_categoria=8 and agrupacion='1'"
                                                                    , 'cod_param'
                                                                    , 'descripcion', $val['dependencia']);

                
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);
                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");
                $contenido['TITULO_FORMULARIO'] = "Crear&nbsp;MatricesParametros";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;MatricesParametros";
                $contenido['PAGINA_VOLVER'] = "listarMatricesParametros.php";
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
                $objResponse->addScriptCall('mostra_ocultar_indicador');
                $objResponse->addScriptCall('cargar_autocompletado');
                
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
                    
                    $respuesta = $this->ingresarMatricesParametros($parametros);

                    if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).val('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verMatricesParametros($parametros[id]); 

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
                $contenido_1['ID_CMB_ACAP'] = $val["id_cmb_acap"];
                $contenido_1['NOMBRE'] = ($val["nombre"]);
                $contenido_1['DEPENDENCIA'] = ($val["dependencia"]);
                $contenido_1['TEXTO'] = ($val["texto"]);
                $contenido_1['FORMULA'] = ($val["formula"]);
                $contenido_1['CALCULO_FORMULA'] = $val["calculo_formula"];
                $contenido_1['MUESTRA'] = ($val["muestra"]);
                $contenido_1['MUESTRARPT'] = ($val["muestrarpt"]);
                $contenido_1['TIPO'] = ($val["tipo"]);
                $contenido_1['INDICADOR'] = ($val["indicador"]);
                $contenido_1['ORDEN'] = $val["orden"];
                $contenido_1['FECHA_NOM1'] = ($val["fecha_nom1"]);
                $contenido_1['FECHA_NOM2'] = ($val["fecha_nom2"]);
                $contenido_1['FECHA_SEM'] = ($val["fecha_sem"]);
                $contenido_1['DATOS'] = $val["datos"];
                $contenido_1['TIP_FAMILIA_REQUISITO'] = ($val["tip_familia_requisito"]);
                
                $contenido_1[CHECKED_IND_NO] = $val["indicador"] == 'N' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_IND_SI] = $val["indicador"] == 'S' ? 'checked="checked"' : '';
                
                $ids = array('1', '2','3', '4', '5'); 
                $desc = array('ComboBox', 'Texto', 'Fecha', 'Personal', 'Semaforo');
                $contenido_1['TIPOS'] = $ut_tool->combo_array("tipo", $desc, $ids,false,$val["tipo"]);
                $contenido_1['DEPENDENCIA'] = $ut_tool->OptionsCombo("SELECT cod_param, 
                                                                        descripcion
                                                                            FROM mos_matrices_parametro_general WHERE cod_param IN (1,2) and cod_categoria=8 and agrupacion='1'"
                                                                    , 'cod_param'
                                                                    , 'descripcion', $val['dependencia']);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros/';
                $template->setTemplate("formulario");
                $template->setVars($contenido_1);

                $contenido['CAMPOS'] = $template->show();

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("formulario");

                $contenido['TITULO_FORMULARIO'] = "Editar&nbsp;MatricesParametros";
                $contenido['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;MatricesParametros";
                $contenido['PAGINA_VOLVER'] = "listarMatricesParametros.php";
                $contenido['DESC_OPERACION'] = "Guardar";
                $contenido['OPC'] = "upd";
                $contenido['ID'] = $val["id_cmb_acap"];

                $template->setVars($contenido);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScriptCall('mostra_ocultar_indicador');
                return $objResponse;
            }
            
            public function configuracion($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                
                $sql = "Select * from mos_matrices_parametro_general where agrupacion in ('3', '1') and cod_categoria='8'";
                $data = $this->dbl->query($sql,array());
                foreach ($data as $value) {
                    switch ($value[agrupacion]) {
                        case '1':
                            $contenido_1["NOMBRE_$value[agrupacion]$value[cod_param]"] = $value[descripcion];

                            break;

                        default:
                            $contenido_1["NOMBRE_$value[agrupacion]$value[cod_param]"] = $value[descripcion];
                            $contenido_1["CHECKED_IND$value[agrupacion]$value[cod_param]_NO"] = $value["activo"] == 'N' ? 'checked="checked"' : '';
                            $contenido_1["CHECKED_IND$value[agrupacion]$value[cod_param]_SI"] = $value["activo"] == 'S' ? 'checked="checked"' : '';
                            break;
                    }
                }
                
                $sql = "select * from mos_matrices_sem_final where cod_categoria=8";
                $data = $this->dbl->query($sql,array());
                foreach ($data as $value) {
                    switch ($value[semaforo]) {
                        case '2':
                            $contenido_1["SEM_21"] = $value[valor];
                            $contenido_1["SEM_22"] = $value[valor2];
                            break;

                        default:
                            $contenido_1["SEM_$value[semaforo]"] = $value[valor];
                            break;
                    }
                }
                
                
                

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros/';
                $template->setTemplate("formulario_config");
//                $template->setVars($contenido_1);
//
//                $contenido['CAMPOS'] = $template->show();
//
//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");

                $contenido_1['TITULO_FORMULARIO'] = "Editar&nbsp;MatricesParametros";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;MatricesParametros";
                $contenido_1['PAGINA_VOLVER'] = "listarMatricesParametros.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "upd";
                $contenido_1['ID'] = $val["id_cmb_acap"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  
                $objResponse->addScriptCall('mostra_ocultar_indicador_config');
                $objResponse->addScriptCall('cargar_autocompletado_config');
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
                    
                    
                    
                    $respuesta = $this->modificarMatricesParametros($parametros);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).val('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
     
            public function actualizar_config($parametros)
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
                    /*descripcion = '$atr[descripcion]',activo = '$atr[activo]'
                            WHERE  cod_param = $atr[cod_param] AND cod_categoria = 8 AND agrupacion = $atr[agrupacion]*/
                    
                    $sql = "Select * from mos_matrices_parametro_general where agrupacion in ('3', '1') and cod_categoria='8'";
                    $data = $this->dbl->query($sql,array());
                    foreach ($data as $value) {
                        $params[cod_param] = $value[cod_param];
                        $params[cod_categoria] = $value[cod_categoria];
                        $params[agrupacion] = $value[agrupacion];
                        switch ($value[agrupacion]) {
                            case '1':
                                
                                $params[descripcion] = $parametros["nombre_$value[agrupacion]$value[cod_param]"];
                                $params[activo] = 'S';
                                break;

                            default:
                                $params[descripcion] = $parametros["nombre_$value[agrupacion]$value[cod_param]"];
                                $params[activo] = $parametros["indicador_$value[agrupacion]$value[cod_param]"];
                                break;
                        } 
                        $respuesta = $this->modificarMatricesParametrosGeneral($params);
                    }
                    $sql = "select * from mos_matrices_sem_final where cod_categoria=8";
                    $data = $this->dbl->query($sql,array());
                    foreach ($data as $value) {
                        $params[cod_categoria] = $value[cod_categoria];
                        $params[semaforo] = $value[semaforo];
                        switch ($value[semaforo]) {
                            case '2':
                                $params[valor]  = $parametros["sem_21"];
                                $params[valor2] = $parametros["sem_22"];
                                break;

                            default:                                
                                $params[valor] = $parametros["sem_$value[semaforo]"];
                                $params[valor2] = 'NULL';
                                break;
                        }
                        $respuesta = $this->modificarMatricesParametrosGeneralSemaforo($params);
                    }
                    
                    /*valor = $atr[valor],valor2 = $atr[valor2]
                            WHERE  semaforo = $atr[semaforo]*/
                   

                    //if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    //}
                    //else
                    //    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).val('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );"
                        );
                return $objResponse;
            }
 
            public function eliminar($parametros)
            {
                $val = $this->verMatricesParametros($parametros[id]);
                $respuesta = $this->eliminarMatricesParametros($parametros);
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
                $grid = $this->verListaMatricesParametros($parametros);                
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

                $val = $this->verMatricesParametros($parametros[id]);

                            $contenido_1['COD_CATEGORIA'] = $val["cod_categoria"];
            $contenido_1['ID_CMB_ACAP'] = $val["id_cmb_acap"];
            $contenido_1['NOMBRE'] = ($val["nombre"]);
            $contenido_1['DEPENDENCIA'] = ($val["dependencia"]);
            $contenido_1['TEXTO'] = ($val["texto"]);
            $contenido_1['FORMULA'] = ($val["formula"]);
            $contenido_1['CALCULO_FORMULA'] = $val["calculo_formula"];
            $contenido_1['MUESTRA'] = ($val["muestra"]);
            $contenido_1['MUESTRARPT'] = ($val["muestrarpt"]);
            $contenido_1['TIPO'] = ($val["tipo"]);
            $contenido_1['INDICADOR'] = ($val["indicador"]);
            $contenido_1['ORDEN'] = $val["orden"];
            $contenido_1['FECHA_NOM1'] = ($val["fecha_nom1"]);
            $contenido_1['FECHA_NOM2'] = ($val["fecha_nom2"]);
            $contenido_1['FECHA_SEM'] = ($val["fecha_sem"]);
            $contenido_1['DATOS'] = $val["datos"];
            $contenido_1['TIP_FAMILIA_REQUISITO'] = ($val["tip_familia_requisito"]);
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'matrices_parametros/';
                $template->setTemplate("verMatricesParametros");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la MatricesParametros";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>