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
            $sql = 'SELECT d.* FROM mos_link d WHERE d.cod_link ='.$this->cite['dependencia'];
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
            
            return $param;
        }

        public function obtenerNodosMenu($usuario, $filial, $modo){
            $atr = array();
            if($modo == 'Especialista')
                $sql = "select t3.*
                    from mos_usuario_filial t1 inner join mos_link_por_perfil t2 on t1.cod_perfil=t2.cod_perfil inner join mos_link t3 on t2.cod_link=t3.cod_link 
                    where t1.id_usuario='$usuario' and t1.id_filial='$filial' 	
                    group by t3.cod_link 
                    order by t3.dependencia,t3.orden asc";
            else
                $sql="select t3.*
                    from mos_usuario_filial t1 inner join mos_link_por_perfil_portal t2 on t1.cod_perfil_portal=t2.cod_perfil  inner join mos_link_portal t3 on t2.cod_link=t3.cod_link
                    where t1.id_usuario='$usuario' and  t1.id_filial='$filial'
                    group by t3.cod_link 
                    order by t3.dependencia,t3.orden asc
                     ";    
            
            $this->operacion($sql, $atr);
            
            global $arbol;
            $arbol = $this->dbl->data;
                    
            $sql = "select distinct t3.dependencia
                from mos_usuario_filial t1 inner join mos_link_por_perfil t2 on t1.cod_perfil=t2.cod_perfil inner join mos_link t3 on t2.cod_link=t3.cod_link 
                where t1.id_usuario='$usuario' and t1.id_filial='$filial' 	
                group by t3.cod_link 
                order by t3.dependencia,t3.orden asc";
            $this->operacion($sql, $atr);
            $padres = $this->dbl->data;             
          
            foreach ($padres as $arrN) {                
                $this->recursiveGenerateCite($arrN);
            }
            asort($arbol);
            foreach ($arbol as $k1 => $v1) {
                foreach ($arbol as $k2 => $v2) {
                    if ($k1 != $k2) {
                        if ($v1 == $v2) {
                            unset($arbol[$k1]);
                        }
                    }
                }
            }
            return $arbol;
        }
    }
?>


