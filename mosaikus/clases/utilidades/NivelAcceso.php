<?php

import("clases.interfaz.Pagina");        
        class NivelAcceso extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        public $id_org_acceso;
        public $id_org_acceso_explicito;
        public $id_org_acceso_viz_terceros;
        public $id_org_acceso_mod_terceros;
        public $per_crear;
        public $per_editar;
        public $per_eliminar;
        public $per_viz_terceros;
        public $per_mod_terceros;
        public $arbol;
        
            
            public function NivelAcceso(){
                parent::__construct();
                $this->asigna_script('lista_distribucion_doc/lista_distribucion_doc.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->id_org_acceso = $this->id_org_acceso_explicito = $this->id_org_acceso_viz_terceros = $this->id_org_acceso_mod_terceros = array();
                $this->per_crear = $this->per_editar = $this->per_eliminar = $this->per_viz_terceros = $this->per_mod_terceros = 'N';
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
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 27";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 27";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
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
                   foreach ($data_ids_acceso as $value) {
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
                   foreach ($data_ids_acceso as $value) {
                       $this->id_org_acceso_explicito[$value[id]] = $value;
                   }                                            
               }
           }
           
           /**
            * Activa los nodos donde se tiene acceso a informacion de terceros
            */
           public function cargar_acceso_nodos_visualiza_terceros($parametros){
               
                if (count($this->id_org_acceso_explicito) <= 0){
                     $this->cargar_acceso_nodos_explicito($parametros);
                }                     
                foreach ($this->id_org_acceso_explicito as $value) {
                    if ($value['visualizar_terceros'])
                        $this->id_org_acceso_viz_terceros[$value[id]] = $value;
                }                                            
               
           }
           
           /**
            * Activa los nodos donde se tiene acceso a informacion de terceros
            */
           public function cargar_acceso_nodos_modificar_terceros($parametros){
              // print_r($parametros);
               if (count($this->id_org_acceso_explicito) <= 0){
                     $this->cargar_acceso_nodos_explicito($parametros);
                }                     
                foreach ($this->id_org_acceso_explicito as $value) {
                    if ($value['modificar_terceros'])
                        $this->id_org_acceso_mod_terceros[$value[id]] = $value;
                } 
           }
           
            /**
             * Busca los permisos que tiene el usuario en el modulo
             */
            public function cargar_permisos($parametros){
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
                    foreach ($data_permisos as $value) {
                        if ($value[visualizar_terceros] == 'S'){
                            $this->per_viz_terceros =  'S';
                            break;
                        }
                    } 
                    foreach ($data_permisos as $value) {
                        if ($value[modificar_terceros] == 'S'){
                            $this->per_mod_terceros =  'S';
                            break;
                        }
                    } 
                }
            }
            
            public function colum_admin($tupla)
            {
                $html = "&nbsp;";
                if (strlen($tupla[id_registro])<=0){
                    if($this->per_editar == 'S'){
                        $html .= '<a onclick="javascript:editarListaDistribucionDoc(\''.$tupla[id].'\' );">
                                    <i style="cursor:pointer" class="icon icon-edit"  title="Editar ListaDistribucionDoc" style="cursor:pointer"></i>
                                </a>';
                    }                
                    if($this->per_eliminar == 'S'){
                        $html .= '<a onclick="javascript:eliminarListaDistribucionDoc(\''.$tupla[id].'\');;">
                                    <i style="cursor:pointer" class="icon icon-remove" title="Eliminar ListaDistribucionDoc" style="cursor:pointer"></i>
                                </a>';
                    }
                }
                return $html;
            }
            
            public function colum_admin_arbol($tupla)
            {    
                $html .= "<a href=\"#\" onclick=\"javascript:verListaDistribucionDoc('". $tupla[id] . "');\"  title=\"Ver Lista de Distribucion\">                            
                                    <i class=\"icon icon-view-document\" style=\"margin-left: 1px;margin-right: 1px;\"></i>
                                </a>";
                if ($tupla[id_responsable] == $_SESSION['CookCodEmp']){
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($tupla[id_area],',')){    
                        $organizacion = explode(",", $tupla[id_area]);
                    }
                    else{
                        $organizacion[] = $tupla[id_area];                                 
                    }
                    //echo $tupla[id_responsable];
                    //print_r($organizacion);
                    //print_r($tupla);
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if ((isset($this->id_org_acceso_explicito[$value_2]))&& ($this->id_org_acceso_explicito[$value_2][modificar]=='S')){
                            //if()
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                    if (($editar == true)||($_SESSION[SuperUser] == 'S'))
                    //if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                    {                    
                        $html .= "<a href=\"#\" onclick=\"javascript:editarListaDistribucionDoc('". $tupla[id] . "');\"  title=\"Editar  Lista de Distribucion\">                            
                                    <i class=\"icon icon-edit\" style=\"margin-left: 1px;margin-right: 1px;\"></i>
                                </a>";
                    }
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($tupla[id_area],',')){    
                        $organizacion = explode(",", $tupla[id_area]);
                    }
                    else{
                        $organizacion[] = $tupla[id_area];                                 
                    }
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if ((isset($this->id_org_acceso_explicito[$value_2]))&&($this->id_org_acceso_explicito[$value_2][eliminar]=='S')){
                            //if(($this->id_org_acceso[$value_2][eliminar]=='S'))
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                    if (($editar == true)||($_SESSION[SuperUser] == 'S'))                  
                    //if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                    {
                        $html .= "<a href=\"#\" onclick=\"javascript:eliminarListaDistribucionDoc('". $tupla[id] . "');\" title=\"Eliminar  Lista de Distribucion\">
                                <i class=\"icon icon-remove\" style=\"margin-left: 1px;margin-right: 1px;\"></i>

                            </a>"; 
                    }
                }
                else{
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($tupla[id_area],',')){    
                        $organizacion = explode(",", $tupla[id_area]);
                    }
                    else{
                        $organizacion[] = $tupla[id_area];                                 
                    }
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if ((isset($this->id_org_acceso_explicito[$value_2]))&& ($this->id_org_acceso_explicito[$value_2][modificar_terceros] == 'S')){
                            //if(($this->id_org_acceso[$value_2][modificar]=='S'))
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                    if (($editar == true)||($_SESSION[SuperUser] == 'S'))
                    //if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][modificar] == 'S')
                    {                    
                        $html .= "<a href=\"#\" onclick=\"javascript:editarListaDistribucionDoc('". $tupla[id] . "');\"  title=\"Editar  Lista de Distribucion\">                            
                                    <i class=\"icon icon-edit\" style=\"margin-left: 1px;margin-right: 1px;\"></i>
                                </a>";
                    }                    
                    if (($_SESSION[SuperUser] == 'S'))                  
                    //if ($this->id_org_acceso_explicito[$tupla[id_organizacion]][eliminar] == 'S')
                    {
                        $html .= "<a href=\"#\" onclick=\"javascript:eliminarListaDistribucionDoc('". $tupla[id] . "');\" title=\"Eliminar  Lista de Distribucion\">
                                <i class=\"icon icon-remove\" style=\"margin-left: 1px;margin-right: 1px;\"></i>

                            </a>"; 
                    }
                }
                return $html;
            }
        }
?>