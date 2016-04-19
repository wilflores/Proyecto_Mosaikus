<?php 
            include_once('configuracion/configuracion.php');
            //ini_set('session.cache_expire',60);
            //ini_set("session.cookie_lifetime",0);
            
            //echo session_set_cookie_params(60);
            session_name('mosaikus');
            //ini_set("session.gc_maxlifetime", 60);
            session_start();
            //$_SESSION['USERID'] = 1;
            
            putenv("PAGINACION=10");// NUMERO UTILIZADO PARA LA PAGINACION
            include('clases/clases.php');
            include_once('configuracion/import.php');            
            if(isset($_POST['usuario_id']))
            {
                 $_SESSION['USERID'] = $_POST['usuario_id'];
                 $_SESSION['ID_ROL'] = $_POST['id_rol'];
                 $_SESSION['NOMBREUSER'] = $_POST['nombre'];
            }

            function isAjax()
            {					
                    if(isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')				
                    {return 1;}
                    else
                    { 
                    return 2;}
            }
            //print_r($_SESSION);
            function Loading($parametros){    
                if($_SESSION['CookIdUsuario']=='')// && $_SESSION['ID_ROL']=='')	 	
                    {
                            $objResponse = new xajaxResponse();
                            //$objResponse->addScript("alert('Su sesion ha expirado')");
                            $objResponse->addScript("location.replace('mos_logout.php')");
                            return $objResponse;
                    }
                if(isset($parametros['id_menu_opcion'])){  
                    
                    $encryt = new EnDecryptText();                                
                    $pagina = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
                    $sql = 'Select descripcion,nombre_link,cod_link FROM mos_link_portal WHERE cod_link = ' . $parametros['id_menu_opcion'];
                    //$pagina->conectar();
                    //$params['p_id']= $parametros['id_menu_opcion'];
                    //$val = $pagina->exe("util_sp_menu_con_id", $params);                    
                    $val = $pagina->query($sql, $params);
                    //$pagina->data;
                    //echo 1;
                    //print_r($val);
                    foreach ($val as $datos) {
                        $datos_clase = explode('-', $datos[descripcion]);                        
                        $parametros['import'] = $datos_clase[2];
                        $parametros['objeto'] = $datos_clase[0];
                        $parametros['metodo'] = $datos_clase[1];
                        $parametros['nombre_modulo'] = $datos[nombre_link];                        
                        $parametros['cod_link'] = $datos[cod_link]; 
                        $parametros['modo'] = 'Portal';
                    }
                }
                //print_r($val);
                //print_r($parametros);		
                        
                        if(isset($parametros['import'])){
                     import($parametros['import']);
            }
                    include_once('configuracion/configuracion.php');
                eval('$Obj = new '.$parametros['objeto'].'();');
                eval('$objResponse = $Obj->'.$parametros['metodo'].'($parametros);');
                return $objResponse;
            }
            
            if (isAjax() != 1)
            {	
                    validar_perfil('mos_logout.php');
            }											
				
            //chdir('..');
            //chdir('..');
            //chdir("../..");
            include_once('clases/clases.php');
            include_once('configuracion/import.php');
            include_once('configuracion/configuracion.php');
            import('clases.interfaz.Pagina');            

            //print_r($_SESSION);
            $pagina = new Pagina();
            $pagina->registrar("Loading");
            $pagina->asigna_permiso($_POST['permiso']);
            //$pagina->ind(array('permiso' => $_POST['permiso']));
            $pagina->showIndexMosaikusPortal($_GET);
?>