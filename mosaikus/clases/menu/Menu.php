<?php
 import("clases.interfaz.Pagina");
        include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/SQLServer.php');
        class Menu extends Pagina{
        private $templates;
        private $bd;

            public function Menu(){
                parent::__construct();
                $this->asigna_script('menu/menu.js');
                $encryt = new EnDecryptText();                                
                $this->dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
                //$this->dbl->conectar();
                $this->contenido = array();
            }

            private function operacion($sp, $atr, $parametros){
                $atr = array_intersect_key($atr, $parametros);
                $param=array();
                if(is_array($atr)){
                    foreach ($atr as $key => $value) {
                        $param["@p_".$key]=$parametros[$key] . $value . $parametros[$key];
                    }
                }
                //$result =
                return $this->dbl->exe($sp, $param);;
            }
            
            private function operacion_2($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }

     

             public function verMenu($id){
                $atr=array();
                $atr['id']=$id;
                $this->operacion("sp_tb_menu_con_id", $atr);
                return $this->dbl->data[0];
            }
            public function ingresarMenu($atr){
                $this->operacion("sp_tb_menu_ins", $atr);
                $this->registraTransaccion('Insertar','Ingreso el Menu ');
                return utf8_encode($this->dbl->data[0][0]);
            }
            public function modificarMenu($atr){
                $this->operacion("sp_tb_menu_act", $atr);
                $this->registraTransaccion('Modificar','Modifico el Menu ');
                 return utf8_encode($this->dbl->data[0][0]);
            }
            public function CargaMenu($atr){
                //echo 'entro carga menu';
                  $parametros = array('id_rol' => '', 'tipo_menu' => "'", 'parent_id' => "");
                  $respuesta = $this->operacion("util_sp_menu_con", $atr, $parametros);
                  //echo 'entrooooooo';
                 // print_r($respuesta);
                  return $respuesta;
             }
             public function eliminarMenu($atr){
                $this->operacion("sp_tb_menu_eli", $atr);
                return utf8_encode($this->dbl->data[0][0]);
             }
     
 
     public function verListaMenuVertical($parametros){
                //echo 'entro menu vertical';
                $data=$this->CargaMenu($parametros);
                //$data=$this->dbl->data;
                
                $str_menu = "<div class='vertical' id='admin-toolbar'>\n";
                $str_menu .= " <span class='admin-toggle toggle-bottom'  status='show'>Admin</span>\n";
                $str_menu .= "<div class='modules'>\n";
                $str_menu .= "<div class='item-tabs'>\n";
                $i=0;
                //echo "<tr ><td>&nbsp;</td></tr>\n";
                //echo "<li><a class='current' href='#'><span onClick=\"MM_goToURL(this);callmenuh(".$menu[$i][0].",'".$menu[$i][0]."');\">".str_replace(' ', '&nbsp;',$menu[$i][1]) ."</span></a></ul>\n";
               // $str_menu .= "<li><a href='" . APPLICATION_ROOT.  "' class='current'><span>Home</span></a></li>";
                $i++;

                foreach ($data as $menu)
                {   $str_menu .= "<div  id='".$menu["id"]."' class='menu-tab' href='".$menu["link"]."'>\n";
                    $str_menu .= "<span>".str_replace(' ', '&nbsp;',($menu["descripcion"])) ."</span>\n";
                    $str_menu .= "</div>\n";
                    //$str_menu .= "  <a onClick=\"\" id='link".$menu["id"]."' name='link".$menu["id"]."'><span onClick=\"CallMenuTab(".$menu["id"].");\">".str_replace(' ', '&nbsp;',utf8_encode($menu["descripcion"])) ."</span></a>\n";
                    //$str_menu .= "</li>";
                    //echo "</div>\n";
                        $i++;

                }

                $str_menu .= "</div>";
                $str_menu .= "<div  id='active-module'>";
                $str_menu .= "<ul id='nivel-second' class='second-level menu active-menu'>";
                $str_menu .= "</ul>";
                $str_menu .= "</div>";
                $str_menu .= "</div></div>";

                return $str_menu;
            }
       public function verListaMenuTab($parametros){
                $data=$this->CargaMenu($parametros);
                //$data=$this->dbl->data;
                $tsel = $parametros["tab_sel"] = isset($parametros["tab_sel"]) ? $parametros["tab_sel"] : $data[0]["id"];
               foreach ($data as $menu)
                {
                   $str_menu .= " <li ref='".$menu["id"]."' class='leaf'>  <a ref='".$menu["id"]."' class='opcion_menu_tab'>".($menu["descripcion"])."</a> \n";
                   $str_menu .= "<ul id='ul-".$menu["id"]."' class='third-level third-active'> \n";
                   $str_menu .= "</ul> \n";
                   $str_menu .= "</li> \n";
                        //$str_menu .= "  <td style='cursor:pointer' class='td-tab-off' id='tab".$menu["id"]."' onClick=\"ClickTabs(" . $parametros["indice_modulo"] . "," . $menu["id"].");\">".utf8_encode($menu["descripcion"])."</td>\n";
                }
                return $str_menu;
       }
       public function verListaMenuOpciones($parametros){
                $data=$this->CargaMenu($parametros);
                //$data=$this->dbl->data;
               foreach ($data as $menu)
                {
                   $str_menu .= " <li class='leaf'><a id=\"ul-li-a-$menu[id]\" onclick='javascript:ClickOpciones(\"".rtrim($menu["id"])."\",this,\"".rtrim($menu[3])."\")'>".($menu[1])."</a></li>";

                   //$str_menu .=  "<a style='cursor:pointer' name='opcion".$j."' id='opcion".$j."' onclick='javascript:ClickOpciones(\"".rtrim($menu["id"])."\",this,\"".rtrim($menu[3])."\")'  class='" . $class_opcion .  "'>".utf8_encode($menu[1]);
                }
                return $str_menu;
       }
       
 
 
            public function indexMenuVertical($parametros)
            {
                //echo 'entro en el index';
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $parametros['id_rol']=$_SESSION['ID_ROL'];
                $parametros['tipo_menu']='modulo';
                $parametros['parent_id']=0;
                return $this->verListaMenuVertical($parametros);
            }

            public function indexMenuTab($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $parametros['id_rol']=$_SESSION['ID_ROL'];
                $parametros['tipo_menu']='tab';
                $parametros['parent_id']=$parametros['indice_modulo'];

                //return $this->verListaMenuTab($parametros);

                $objResponse = new xajaxResponse();
                $objResponse->addAssign('nivel-second',"innerHTML",$this->verListaMenuTab($parametros));
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("InitMenu");
                $objResponse->addScriptCall("HabilitaOpciones()");
                return $objResponse;
            }
             public function indexMenuOpciones($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $parametros['id_rol']=$_SESSION['ID_ROL'];
                $parametros['tipo_menu']='opcion';
                $parametros['parent_id']=$parametros['indice_tab'];

                //return $this->verListaMenuTab($parametros);

                $objResponse = new xajaxResponse();
                $objResponse->addAssign('ul-'.$parametros['indice_tab'],"innerHTML",$this->verListaMenuOpciones($parametros));
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("InitMenu");


                return $objResponse;
            }
            
            public function indexMenuMosaikus()
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $Consulta="select t3.* from mos_usuario_filial t1 inner join mos_link_por_perfil t2 on t1.cod_perfil=t2.cod_perfil  inner join mos_link t3 ";
                $Consulta.=" on t2.cod_link=t3.cod_link   ";
                //if($SuperUser!='S')
                $Consulta.=" where t1.id_usuario='".$_SESSION[CookIdUsuario]."' and  t1.id_filial='".$_SESSION[CookFilial]."' AND t3.dependencia = 0";
                $Consulta.=" group by t3.cod_link order by t3.dependencia,t3.orden asc";
                //print_r($_SESSION);
                //echo $Consulta;
                $this->operacion_2($Consulta, $parametros);
                
                //print_r($this->dbl->data);
                $html = '';
                foreach ($this->dbl->data as $value) {
                    $html .= $this->buscar_hijo($value,1);
                }
//                $sql = "UPDATE mos_usuario_filial SET ultimo_acceso = 1 WHERE id_usuario='".$_SESSION[CookIdUsuario]."' and  id_filial='".$_SESSION[CookFilial]."'";
//                $this->dbl->insert_update($sql);
                return $html;
            }
            
            public function indexMenuMosaikusPortal()
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $Consulta="select t3.* from mos_usuario_filial t1 inner join mos_link_por_perfil_portal t2 on t1.cod_perfil_portal=t2.cod_perfil  inner join mos_link_portal t3 ";
                $Consulta.=" on t2.cod_link=t3.cod_link   ";
                //if($SuperUser!='S')
                $Consulta.=" where t1.id_usuario='".$_SESSION[CookIdUsuario]."' and  t1.id_filial='".$_SESSION[CookFilial]."' AND t3.dependencia = 0";
                $Consulta.=" group by t3.cod_link order by t3.dependencia,t3.orden asc";
                //print_r($_SESSION);
                //echo $Consulta;
                $this->operacion_2($Consulta, $parametros);
                
                //print_r($this->dbl->data);
                $html = '';
                foreach ($this->dbl->data as $value) {
                    $html .= $this->buscar_hijo_portal($value,1);
                }
//                $sql = "UPDATE mos_usuario_filial SET ultimo_acceso = 2 WHERE id_usuario='".$_SESSION[CookIdUsuario]."' and  id_filial='".$_SESSION[CookFilial]."'";
//                $this->dbl->insert_update($sql);
                return $html;
            }
            
            private function buscar_hijo($value,$nivel){
                $html = '';
                $Consulta="select t3.* from mos_usuario_filial t1 inner join mos_link_por_perfil t2 on t1.cod_perfil=t2.cod_perfil  inner join mos_link t3 ";
                $Consulta.=" on t2.cod_link=t3.cod_link   ";
                //if($SuperUser!='S')
                $Consulta.=" where t1.id_usuario='".$_SESSION[CookIdUsuario]."' and  t1.id_filial='".$_SESSION[CookFilial]."' AND t3.dependencia = $value[cod_link]";
                $Consulta.=" group by t3.cod_link order by t3.dependencia,t3.orden asc";
                //print_r($_SESSION);
                //echo $Consulta;
                $data_aux = $this->dbl->query($Consulta, $param);
                if (count($data_aux)>0){
                    /*
                    $html = '<li class="dropdown-submenu">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $value[nombre_link] . '</a>
                                                    <ul class="dropdown-menu">';
                     * 
                     */
                    switch ($nivel) {
                        case 1:
                            $html .= '<li class="li-parent">
                                           <a href="#submenu'. $value[cod_link] . '"  >
                                              <i class="icon-mod icon-mod-'. $value[imagen] . '"></i>' . $value[nombre_link] . ''
                                . '<i class="glyphicon glyphicon-triangle-right active-vin"></i></a>';
                            $html .=                '<ul id="submenu'. $value[cod_link] . '" class="submenu nav-pills nav-stacked collapse">';
                            foreach ($data_aux as $value_aux) {
                                $html .= $this->buscar_hijo($value_aux,$nivel + 1,$value[cod_link]);
                            } 
                            $html .= '</ul></li>';
                            break;
                        default:
                            
                            $html .= '<li>
                                          <a href="#submenu'. $value[cod_link] . '" data-toggle="collapse">
                                            ' . $value[nombre_link] . '
                                          </a>
                                          <ul id="submenu'. $value[cod_link] . '" class="nav-pills nav-stacked collapse">';
                            foreach ($data_aux as $value_aux) {
                                $html .= $this->buscar_hijo($value_aux,$nivel + 1,$value[cod_link]);
                            }
                            $html .= '</ul></li>';                        
                    }
                                        
                    //$html .= '</ul></li>';
                }
                else
                {
                     switch ($nivel) {
                         case 1:
                             $html .= '<li class="">
                                            <a href="#" onclick="ClickOpcionesMosaikus('. $value[cod_link] . ', \'' .$value[nombre_link] . '\',\''. $value[imagen] . '\')">
                                              <i class="icon-mod icon-mod-'. $value[imagen] . '"></i>
                                              ' . $value[nombre_link] . '
                                            <i class="glyphicon glyphicon-triangle-right active-vin"></i></a>
                                          </li>';
                             break;
                         default:
                             $html .= '<li class="">
                                            <a href="#" onclick="ClickOpcionesMosaikus('. $value[cod_link] . ', \'' .$value[nombre_link] . '\',\''. $value[imagen] . '\')">                                             
                                              ' . $value[nombre_link] . '
                                            </a>
                                          </li>';
                             break;
                     }
                    //$html = '<li><a href="#">' . $value[nombre_link] . '</a></li>';
                }
                return $html;
            }
            
            private function buscar_hijo_portal($value,$nivel){
                $html = '';
                $Consulta="select t3.* from mos_usuario_filial t1 inner join mos_link_por_perfil_portal t2 on t1.cod_perfil_portal=t2.cod_perfil  inner join mos_link_portal t3 ";
                $Consulta.=" on t2.cod_link=t3.cod_link   ";
                //if($SuperUser!='S')
                $Consulta.=" where t1.id_usuario='".$_SESSION[CookIdUsuario]."' and  t1.id_filial='".$_SESSION[CookFilial]."' AND t3.dependencia = $value[cod_link]";
                $Consulta.=" group by t3.cod_link order by t3.dependencia,t3.orden asc";
                //print_r($_SESSION);
                //echo $Consulta;
                $data_aux = $this->dbl->query($Consulta, $param);
                if (count($data_aux)>0){
                    /*
                    $html = '<li class="dropdown-submenu">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $value[nombre_link] . '</a>
                                                    <ul class="dropdown-menu">';
                     * 
                     */
                    switch ($nivel) {
                        case 1:
                            $html .= '<li class="li-parent">
                                           <a href="#submenu'. $value[cod_link] . '"  >
                                              <i class="icon-mod icon-mod-'. $value[imagen] . '"></i>' . $value[nombre_link] . ''
                                . '<i class="glyphicon glyphicon-triangle-right active-vin"></i></a>';
                            $html .=                '<ul id="submenu'. $value[cod_link] . '" class="submenu nav-pills nav-stacked collapse">';
                            foreach ($data_aux as $value_aux) {
                                $html .= $this->buscar_hijo_portal($value_aux,$nivel + 1,$value[cod_link]);
                            } 
                            $html .= '</ul></li>';
                            break;
                        default:
                            
                            $html .= '<li>
                                          <a href="#submenu'. $value[cod_link] . '" data-toggle="collapse">
                                            ' . $value[nombre_link] . '
                                          </a>
                                          <ul id="submenu'. $value[cod_link] . '" class="nav-pills nav-stacked collapse">';
                            foreach ($data_aux as $value_aux) {
                                $html .= $this->buscar_hijo_portal($value_aux,$nivel + 1,$value[cod_link]);
                            }
                            $html .= '</ul></li>';                        
                    }
                                        
                    //$html .= '</ul></li>';
                }
                else
                {
                     switch ($nivel) {
                         case 1:
                             $html .= '<li class="">
                                            <a href="#" onclick="ClickOpcionesMosaikus('. $value[cod_link] . ', \'' .$value[nombre_link] . '\',\''. $value[imagen] . '\')">
                                              <i class="icon-mod icon-mod-'. $value[imagen] . '"></i>
                                              ' . $value[nombre_link] . '
                                            <i class="glyphicon glyphicon-triangle-right active-vin"></i></a>
                                          </li>';
                             break;
                         default:
                             $html .= '<li class="">
                                            <a href="#" onclick="ClickOpcionesMosaikus('. $value[cod_link] . ', \'' .$value[nombre_link] . '\',\''. $value[imagen] . '\')">                                             
                                              ' . $value[nombre_link] . '
                                            </a>
                                          </li>';
                             break;
                     }
                    //$html = '<li><a href="#">' . $value[nombre_link] . '</a></li>';
                }
                return $html;
            }

     
 }?>