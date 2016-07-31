<?php
    import("clases.interfaz.Pagina");
    class mos_acceso extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        public $arbol = array();
        
        public function mos_acceso(){
            parent::__construct();
            $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $this->parametros = $this->nombres_columnas = $this->placeholder = array();
            $this->contenido = array();
            //$this->arbol = array();
        }

        private function operacion($sp, $atr){
            $param=array();
            $this->dbl->data = $this->dbl->query($sp, $param);
        }

        public function recursiveGenerateCite($cite){
            $this->cite = $cite;   
            $sql = 'SELECT t3.cod_link,t3.descripcion,t3.dependencia,t3.tipo,t3.link,t3.orden,t3.imagen, t4.nombre_link FROM mos_link t3 inner join mos_nombres_link_idiomas t4 on t3.cod_link = t4.cod_link WHERE  t4.id_idioma='.$_SESSION[CookIdIdioma].' and t3.cod_link ='.$this->cite['dependencia'];
            $this->operacion($sql, $atr);
            $repo = $this->dbl->data;
            
            global $arbol;            
            array_push($arbol,$repo[0]);
            
            $this->cite['dependencia'] = $repo[0]['dependencia'];            
            if($repo[0]['dependencia'] > 0)
                $this->recursiveGenerateCite($this->cite);
            else 
                return $arbol;
        } 

        public function recursiveNodosArbol($nodoArbol){
            $atr = array();
            $this->nodoArbol = $nodoArbol;
            $sql = 'SELECT o.* FROM mos_organizacion o WHERE o.id ='.$this->nodoArbol['parent_id'];            
            $this->operacion($sql, $atr);
            $repo = $this->dbl->data;
            
            global $arbol;            
            array_push($arbol,$repo[0]);
            
            $this->nodoArbol['parent_id'] = $repo[0]['parent_id'];            
            if($repo[0]['parent_id'] > 1)
                $this->recursiveNodosArbol($this->nodoArbol);
            else 
                return $arbol;
            
            
        }
        public function obtenerHijosMenu($coleccion, $padre){
            $param=array();
            foreach ($coleccion as $arrC){               
                if($arrC['dependencia'] == $padre && $arrC['nombre_link'] != '')
                    array_push($param, $arrC);
            }  
            
            foreach ($param as $clave => $fila) {
                $orden[$clave] = $fila['orden'];                    
            }
            array_multisort($orden, SORT_ASC, $param); 
           // print_r($param);
            return $param;
        }
        
        public function obtenerNodosArbolNivel($coleccion, $padre){
            $param=array();
            foreach ($coleccion as $arrC){               
                if($arrC['parent_id'] == $padre && $arrC['title'] != '')
                    array_push($param, $arrC);
            }  
            
            foreach ($param as $clave => $fila) {
                $orden[$clave] = $fila['position'];                    
            }
            array_multisort($orden, SORT_ASC, $param); 
            
            return $param;
        }
        
        public function obtenerArbolEstructura($usuario, $modulo, $modo){
            $atr = array();
            if($modo == 'Especialista')
                $sql = "SELECT
                        mos_o.*
                FROM
                        mos_usuario_estructura AS mos_ue 
                        INNER JOIN mos_perfil AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                        INNER JOIN mos_link_por_perfil AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                        INNER JOIN mos_link AS mos_l On mos_lp.cod_link = mos_l.cod_link
                        INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                WHERE 
                        mos_ue.portal = 'N'
                        AND mos_ue.id_usuario =$usuario
                        AND mos_l.cod_link = $modulo
                    ";
            else
                $sql = "SELECT
                        mos_o.*
                FROM
                    mos_usuario_estructura AS mos_ue 
                    INNER JOIN mos_perfil_portal AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                    INNER JOIN mos_link_por_perfil_portal AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                    INNER JOIN mos_link_portal AS mos_l On mos_lp.cod_link = mos_l.cod_link
                    INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                WHERE 
                        mos_ue.portal = 'S'
                        AND mos_ue.id_usuario =$usuario
                        AND mos_l.cod_link = $modulo
                    ";
            $this->operacion($sql, $atr);
            global $arbol;
            $arbol = $this->dbl->data;
            if($modo == 'Especialista')
                $sql = "SELECT
                        distinct mos_o.parent_id
                FROM
                        mos_usuario_estructura AS mos_ue 
                        INNER JOIN mos_perfil AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                        INNER JOIN mos_link_por_perfil AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                        INNER JOIN mos_link AS mos_l On mos_lp.cod_link = mos_l.cod_link
                        INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                WHERE 
                        mos_ue.portal = 'N'
                        AND mos_ue.id_usuario =$usuario
                        AND mos_l.cod_link = $modulo
                    ";
            else
                $sql = "SELECT
                        distinct mos_o.parent_id
                FROM
                    mos_usuario_estructura AS mos_ue 
                    INNER JOIN mos_perfil_portal AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                    INNER JOIN mos_link_por_perfil_portal AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                    INNER JOIN mos_link_portal AS mos_l On mos_lp.cod_link = mos_l.cod_link
                    INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                WHERE 
                        mos_ue.portal = 'S'
                        AND mos_ue.id_usuario =$usuario
                        AND mos_l.cod_link = $modulo
                    ";
                
            $this->operacion($sql, $atr);
            $padres = $this->dbl->data;  
            foreach($padres as $arrN){
                $this->recursiveNodosArbol($arrN);
            }

            //ordenar el arbol obtenido
            asort($arbol);
            

            //recorro 
            foreach ($arbol as $k1 => $v1) {
                foreach ($arbol as $k2 => $v2) {
                    if ($k1 != $k2) {
                        if ($v1 == $v2) {
                            unset($arbol[$k1]);
                        }
                    }
                }
            }
            //return $arbol;
            $arbol_aux = array();
            foreach ($arbol as $key => $value) {                
                if (is_array($value))
                    $arbol_aux[$key] = $value;
            }
            
            return $arbol_aux;
        }
        public function obtenerNodosArbol($usuario, $modulo, $modo,$visualiza_tercero=''){
            $atr = array();
            if($modo == 'Especialista'){
                $sql = "SELECT
                        mos_l.cod_link
                        ,t4.nombre_link
                        ,mos_o.id
                        ,mos_o.title
                        ,mos_p.nuevo
                        ,mos_p.modificar
                        ,mos_p.eliminar
                        ,mos_p.recordatorio
                        ,mos_p.modificar_terceros
                        ,mos_p.visualizar_terceros
                        ,mos_o.parent_id
                FROM
                        mos_usuario_estructura AS mos_ue 
                        INNER JOIN mos_perfil AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                        INNER JOIN mos_link_por_perfil AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                        INNER JOIN mos_link AS mos_l On mos_lp.cod_link = mos_l.cod_link
                        INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                        inner join mos_nombres_link_idiomas t4 on mos_l.cod_link = t4.cod_link
                WHERE 
                        mos_ue.portal = 'N'
                        AND mos_ue.id_usuario =$usuario
                        AND mos_l.cod_link = $modulo
                        and t4.id_idioma=$_SESSION[CookIdIdioma]    
                    ";
                if (strlen($visualiza_tercero) > 0){
                    $sql .= " AND mos_p.visualizar_terceros = 'S' ";
                }
            }
            else
                $sql = "SELECT
                        mos_l.cod_link
                        ,t4.nombre_link
                        ,mos_o.id
                        ,mos_o.title
                        ,mos_p.nuevo
                        ,mos_p.modificar
                        ,mos_p.eliminar
                        ,mos_p.recordatorio
                        ,mos_p.modificar_terceros
                        ,mos_p.visualizar_terceros
                        ,mos_o.parent_id
                FROM
                    mos_usuario_estructura AS mos_ue 
                    INNER JOIN mos_perfil_portal AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                    INNER JOIN mos_link_por_perfil_portal AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                    INNER JOIN mos_link_portal AS mos_l On mos_lp.cod_link = mos_l.cod_link
                    INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                    inner join mos_nombres_link_idiomas t4 on mos_l.cod_link = t4.cod_link
                WHERE 
                        mos_ue.portal = 'S'
                        AND mos_ue.id_usuario =$usuario
                        and t4.id_idioma=$_SESSION[CookIdIdioma]    
                        AND mos_l.cod_link = $modulo
                    ";
            $this->operacion($sql, $atr);
            global $arbol;
            $arbol = $this->dbl->data;
            return $arbol;                        
        }


/**
         * Devuelve el array con los permisos del usuario sobre un modulo
         * @param int $usuario id del usuario
         * @param int $modulo id del modulo
         * @param int $id_area id del area
         * @return array
         */
        public function obtenerPermisosModulo($usuario, $modulo,$id_area = null){
            $permisos = array();
            if ($id_area == NULL){
                $sql = "SELECT
                            mos_l.cod_link
                            ,t4.nombre_link
                            -- ,mos_o.id
                            -- ,mos_o.title
                            ,mos_p.nuevo
                            ,mos_p.modificar
                            ,mos_p.eliminar
                            ,mos_p.recordatorio
                            ,mos_p.modificar_terceros
                            ,mos_p.visualizar_terceros
                    FROM
                            mos_usuario_filial AS mos_ue 
                            INNER JOIN mos_perfil AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                            INNER JOIN mos_link_por_perfil AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                            INNER JOIN mos_link AS mos_l On mos_lp.cod_link = mos_l.cod_link
                            inner join mos_nombres_link_idiomas t4 on mos_l.cod_link = t4.cod_link
                            -- INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                    WHERE 
                            -- mos_ue.portal = 'N'
                           -- AND
                            mos_ue.id_usuario =$usuario
                            and t4.id_idioma=$_SESSION[CookIdIdioma]    
                            AND mos_l.cod_link = $modulo";
                            
            }
            else{
                $sql = "SELECT
                            mos_l.cod_link
                            ,t4.nombre_link
                            -- ,mos_o.id
                            -- ,mos_o.title
                            ,mos_p.nuevo
                            ,mos_p.modificar
                            ,mos_p.eliminar
                            ,mos_p.recordatorio
                            ,mos_p.modificar_terceros
                            ,mos_p.visualizar_terceros
                    FROM
                            mos_usuario_estructura AS mos_ue 
                            INNER JOIN mos_perfil AS mos_p ON mos_ue.cod_perfil = mos_p.cod_perfil
                            INNER JOIN mos_link_por_perfil AS mos_lp ON mos_ue.cod_perfil = mos_lp.cod_perfil
                            INNER JOIN mos_link AS mos_l On mos_lp.cod_link = mos_l.cod_link
                            inner join mos_nombres_link_idiomas t4 on mos_l.cod_link = t4.cod_link
                            -- INNER JOIN mos_organizacion AS mos_o ON mos_ue.id_estructura = mos_o.id
                    WHERE 
                            mos_ue.id_estructura = $id_area
                           -- AND
                            and t4.id_idioma=$_SESSION[CookIdIdioma]
                            and mos_ue.id_usuario =$usuario
                            AND mos_l.cod_link = $modulo";
            }
            //echo $sql;
            $permisos = $this->dbl->query($sql);
            return $permisos;
        }
        
        public function obtenerNodosMenu($usuario, $filial, $modo){
            $atr = array();
            if($modo == 'Especialista')
                $sql = "select t3.cod_link,t3.descripcion,t3.dependencia,t3.tipo,t3.link,t3.orden,t3.imagen, t4.nombre_link
                    from mos_usuario_filial t1 inner join mos_link_por_perfil t2 on t1.cod_perfil=t2.cod_perfil inner join mos_link t3 on t2.cod_link=t3.cod_link 
                    inner join mos_nombres_link_idiomas t4 on t3.cod_link = t4.cod_link
                    where t1.id_usuario='$usuario' and t1.id_filial='$filial' and t4.id_idioma=$_SESSION[CookIdIdioma]	
                    group by t3.cod_link 
                    order by t3.dependencia,t3.orden asc";
            else
                $sql="select t3.cod_link,t3.descripcion,t3.dependencia,t3.tipo,t3.link,t3.orden,t3.imagen
                    from mos_usuario_filial t1 inner join mos_link_por_perfil_portal t2 on t1.cod_perfil_portal=t2.cod_perfil  inner join mos_link_portal t3 on t2.cod_link=t3.cod_link
                    where t1.id_usuario='$usuario' and  t1.id_filial='$filial'
                    group by t3.cod_link 
                    order by t3.dependencia,t3.orden asc
                     ";    
            //echo $sql;
            $this->operacion($sql, $atr);
            
            global $arbol;
            $arbol = $this->dbl->data;
            if($modo == 'Especialista')        
                $sql = "select distinct t3.dependencia
                    from mos_usuario_filial t1 inner join mos_link_por_perfil t2 on t1.cod_perfil=t2.cod_perfil inner join mos_link t3 on t2.cod_link=t3.cod_link 
                    where t1.id_usuario='$usuario' and t1.id_filial='$filial'   	
                    group by t3.cod_link 
                    order by t3.dependencia,t3.orden asc";
            else
                $sql="select distinct t3.dependencia
                    from mos_usuario_filial t1 inner join mos_link_por_perfil_portal t2 on t1.cod_perfil_portal=t2.cod_perfil  inner join mos_link_portal t3 on t2.cod_link=t3.cod_link
                    where t1.id_usuario='$usuario' and  t1.id_filial='$filial'
                    group by t3.cod_link 
                    order by t3.dependencia,t3.orden asc
                     ";    
                
            $this->operacion($sql, $atr);
            $padres = $this->dbl->data;             
          
            foreach ($padres as $arrN) {                
                $this->recursiveGenerateCite($arrN);
            }
            //ordenar el arbol obtenido
            asort($arbol);
            
            //recorro 
            foreach ($arbol as $k1 => $v1) {
                foreach ($arbol as $k2 => $v2) {
                    if ($k1 != $k2) {
                        if ($v1 == $v2) {
                            unset($arbol[$k1]);
                        }
                    }
                }
            }
            //print_r($arbol); die();
            return $arbol;
        }
    }
?>