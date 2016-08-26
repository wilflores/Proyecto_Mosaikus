<?php
/*file:Pagina
 Cambie el nombre de algunas variables
*/
//session_start();
class Pagina{
  private $contenido;//contenido a mostrar dentro de la pagina
  private $xajax = null;//varible usada para manejar el xajax
  public  $usuario;//datos del usuario actual
  public  $PATH;//ruta de las plantillas
  public  $version_jquery = '1.2';
  public  $encryt; 
  

  /************************************************************************************************/
  /**
    * Constructor de la clase
    *
    * @return null
    */  
  public function Pagina(){
     
    $this->xajax();
    $this->contenido = array();
    $this->contenido['JAVASCRIPT'] = "";
    $this->contenido['CSS'] = "";
    $this->contenido['CSSIE6'] = "";
    $this->encryt = new EnDecryptText();
    $this->asigna_script("funciones.js?".  rand());
    
  }

  public function activar_version_jquery($version){
      $this->version_jquery = $version;
  }
  /************************************************************************************************/
  /**
    * Realiza en cruce de una plantilla con el 
    * array pasado por paramtro, para usar este metodo
    * hay que llamar primero a setTemplates para setear la
    * ruta en donde se van a ubicar las plantillas
    *
    * @param $Nombre nombre de la plantilla a utilizar
    * @param $Vars array de valores a cruzar con la plantilla
    * @return string, resultado del cruce de la plantilla con el array
    */  
  final public function formatear($Nombre,$Vars){
    $Template = new Template();
    $Template->PATH = $this->PATH;
    $Template->setTemplate($Nombre);
    if((!isset($Vars))||(!is_array($Vars))){
         $Vars = array();
    }
    $Template->setVars($Vars);
    return $Template->show();
  }

  final public function cargar_script($ruta){

        $file = HOME .'/'.'js/' . $ruta . '.js';        
        if(file_exists($file)){            
            $tmp    = @file_get_contents($file);
            if($tmp){
                    $this->contenido['SCRIPT_LOAD'] = $tmp;
            }
	}       
  }
  /************************************************************************************************/
  /**
    * Le indica a la pagina que va a usar xajax
    * y se realiza la importacion de los archivos necesarios
    *
    * @return null
    */
  final private function xajax(){
    if(!class_exists('xajax')){
      import('clases.xajax.xajax');
    }
    $this->xajax = new xajax();
  }
  /************************************************************************************************/
  /**
    * Registra una funcion para el uso de xajax
    *
    * @param $Nombre de la funcion a registrar
    * @return null
    */
  final public function registrar($Nombre){
    if($this->xajax != null){
      $this->xajax->registerFunction($Nombre);
    }
  }
  /************************************************************************************************/
  /**
    * Registra una funcion para el uso de xajax
    *
    * @param $Nombre de la funcion a registrar
    * @return null
    */
  final private function finXajax(){
    if($this->xajax != null){
      $this->xajax->processRequests();
      $this->contenido['JAVASCRIPT'] .= $this->xajax->printJavascript(PATH_TO_JS);
    }
  }
  final public function asigna_permiso($permiso){
    $this->contenido["PERMISO"] = $permiso;
  }

  final public function modulo_actual($modulo){
    $this->contenido["MODULO_ACTUAL"] = $modulo;
  }
  /************************************************************************************************/
  final public function asigna_script($script){
    $this->contenido["JAVASCRIPT"] .= "\n  <script type=\"text/javascript\" src=\"".PATH_TO_JS."$script\" charset=\"ISO-8859-15\"></script>";
  }
  
  /************************************************************************************************/
  final public function asigna_script_vendor($script){
    $this->contenido["JAVASCRIPT"] .= "\n  <script type=\"text/javascript\" src=\"".PATH_TO_JS_V."$script\"></script>";
  }
  /************************************************************************************************/
  final public function asigna_css($css){    
    $this->contenido["CSS"] .= "\n  <link type=\"text/css\" href=\"".PATH_TO_CSS."$css\" rel=\"stylesheet\" />";
  }
  /************************************************************************************************/
  final public function asigna_css_vendor($css){    
    $this->contenido["CSS"] .= "\n  <link type=\"text/css\" href=\"".PATH_TO_JS_V."$css\" rel=\"stylesheet\" />";
  }
  /************************************************************************************************/
  final public function asigna_css_media_print($css){    
    $this->contenido["CSS"] .= "\n  <link type=\"text/css\" href=\"".PATH_TO_CSS."$css\" rel=\"stylesheet\" media=\"print\" />";
  }  
  /************************************************************************************************/
  final public function asigna_css_ie6($css){    
    $this->contenido["CSSIE6"] .= "\n  <link type=\"text/css\" href=\"".PATH_TO_CSS."$css\" rel=\"stylesheet\" />";
  }
  /************************************************************************************************/
  final public function activa_ckeditor(){
    $this->contenido["JAVASCRIPT"] .= "\n  <script type=\"text/javascript\" src=\"".APPLICATION_ROOT . "lib/ckeditor/ckeditor.js\" charset=\"ISO-8859-15\"></script>";
  }
  /************************************************************************************************/
  final public function asigna_contenido($contenido){    
     $this->contenido = array_merge($this->contenido,$contenido);
  }

  /************************************************************************************************/
  final public function asigna_titulo($titulo){    
     $this->contenido['TITLE'] = $titulo;
  }
    
  /************************************************************************************************/
  final public function activar_menu($menu){    
     $this->contenido['MENU_'.$menu] = 'active';
  }
  /************************************************************************************************/

  final public function activar_jquery(){      
      
      
      $this->asigna_css("jquery-ui-1.10.3.custom.css");
      $this->asigna_css("theme-default-form-validator.css");            
      $this->asigna_css("select2.css");
      
      
      //$this->asigna_css("jquery.fancybox-1.3.1.css");      temporal
      //$this->asigna_css("jquery.multiselect.css"); temporal
       //$this->asigna_css("bootstrap.min.css"); 
      
//      if ($this->version_jquery == '1.2'){
//        //$this->asigna_script("jquery/jquery.js");
//      }
//      else
      {
        //$this->asigna_script('jquery/jquery.min.js');        
        $this->asigna_script_vendor('jquery/dist/jquery.min.js');     
        $this->asigna_script_vendor('moment/min/moment.min.js');    
        $this->asigna_script_vendor('bootstrap-sass/assets/javascripts/bootstrap.min.js'); 
        $this->asigna_script_vendor('eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js');
        $this->asigna_script_vendor('jstree/dist/jstree.min.js');  
        $this->asigna_script_vendor('perfect-scrollbar/js/perfect-scrollbar.jquery.js');  
        $this->asigna_script_vendor('alertifyjs/alertify.min.js');  //para alertas
        $this->asigna_script_vendor('Gallery/js/jquery.blueimp-gallery.min.js');  
        $this->asigna_script_vendor('Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js');
        $this->asigna_script_vendor('bootstrap-select/dist/js/bootstrap-select.min.js');
        $this->asigna_script_vendor('Bootstrap-3-Typeahead/bootstrap3-typeahead.min.js');
        
        
      
        //$this->asigna_script('jquery/jquery-ui-1.10.3.custom.js');
        //$this->asigna_script('jquery/jquery-ui-timepicker-addon.js');
        $this->asigna_script('jquery/jquery.form-validator.min.js'); 
        //$this->asigna_script('jquery/jquery.Rut.js'); 
        $this->asigna_script('jquery/select2.min.js');
        $this->asigna_script('highcharts/highcharts.js');
        //$this->asigna_script('highcharts/exporting.js');
        $this->asigna_script("jquery/bootbox.min.js");
      
        //$this->asigna_script('jquery/jquery.multiselect.js');        temporal
        //$this->asigna_script('jquery/jquery.multiselect.filter.js');  temporal
        
        
        
        //$this->asigna_script('jquery/jquery.tooltipster.min.js');  
        
        
        
      }      
      
      
  }

  public function descativar_style_body(){
      $this->contenido['STYLE_CSS'] = 'body {
    background: white;/*url("../images/lateral_bar.png") repeat-x scroll center top #931314*/;
    min-width: 60px;
  }';
  }

  final public function activar_calendario(){
      $this->asigna_css("calendario.css");
      $this->asigna_script("calendario_js/calendar.js");
      $this->asigna_script("calendario_js/calendar-es.js");
      $this->asigna_script("calendario_js/calendar-setup.js");
  }

  final public function show(){
    if(!class_exists('Template')){
      import("clases.interfaz.Template");
    }
		
        $this->asigna_css("mensajes.css");
        $this->asigna_css("/third/reset-min.css");
	$this->asigna_css("/third/fonts-min.css");
	$this->asigna_css("/third/grid.css");
	$this->asigna_css("core.css");
//	$this->asigna_css("menu.css");
//	$this->asigna_css("modules.css");  
        $this->asigna_script("utilidades/array_xml.js");         
        $this->activar_version_jquery("1.4");
        $this->activar_jquery();        
//        $this->asigna_script("jquery/slide.js");
        $this->asigna_script("jquery/jquery.cropzoom.js");
        $this->finXajax();
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        $template->setTemplate("principal");
        $template->setVars($this->contenido);
	
        echo $template->show();
	
  }
  
  final public function showBlank(){
    if(!class_exists('Template')){
      import("clases.interfaz.Template");
    }		                   
        $this->finXajax();
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        $template->setTemplate("principal");
        $template->setVars($this->contenido);
	
        echo $template->show();
	
  }

    final public function showIndex($parametros){
        
        if(!class_exists('Template')){
          import("clases.interfaz.Template");
        }
        //include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/PostgreSQL.php');
        if(!class_exists('Menu')){
          import("clases.menu.Menu");
        }
        $this->activar_version_jquery("1.4");
        $this->activar_jquery();
        //$this->asigna_script("jquery/jquery.cycle.all.js"); temporal
        //$this->asigna_script("jquery/jquery.fancybox-1.3.1.js");    temporal  
        
        $this->asigna_css("mensajes.css");                
	$this->asigna_css("third/reset-min.css");
	$this->asigna_css("third/fonts-min.css");
	//$this->asigna_css("third/grid.css");
	$this->asigna_css("core.css");
	$this->asigna_css("menu.css");
	$this->asigna_css("modules.css");                    
	$this->asigna_css("estilo_boton.css");
        $this->asigna_css("tooltipster.css");
	//$this->asigna_css_media_print("print.css");        
        
        //$this->asigna_script("menu/ocultar_menu.js");        
        //$this->asigna_script("jquery/slide.js");	
        //$this->asigna_script("mensajes/mensajes.js");        
        
        $this->asigna_script("menu/menu.js");
        $this->asigna_script("utilidades/array_xml.js");
        $this->activa_ckeditor();
        $this->finXajax();
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        
        session_name($GLOBALS[SESSION]);
        session_start();
        
        $sql = "select e.nombre,ue.* from tbl_empresa e
                inner join tbl_usuarios_empresa ue on ue.id_empresa = e.id
                where id_usuario =  $_SESSION[USERID]";        
        $param=array();
        $this->dbl = new Mysql();  
        $this->dbl->data = $this->dbl->query($sql, $param);        
        $ids = array(); 
        $desc = array();         
        
        $_SESSION['ID_ROL'] = $this->dbl->data[0][id_rol];
        $_SESSION['ID_EMPRESA'] = $this->dbl->data[0][id_empresa];
        //print_r($this->dbl->data);
        //echo $parametros[id_empresa_actual];
        foreach ($this->dbl->data as $value) {
            if (isset($parametros[id_empresa_actual]) && ($value[id_empresa] == $parametros[id_empresa_actual])){
                $_SESSION['ID_ROL'] = $value[id_rol];
                $_SESSION['ID_EMPRESA'] = $value[id_empresa];
                $this->contenido[PLANTA] = $value[nombre];
            }
            else
            if (!isset($parametros[id_empresa_actual]) && ($value[predeterminado] == "1")){
                $_SESSION['ID_ROL'] = $value[id_rol];
                $_SESSION['ID_EMPRESA'] = $value[id_empresa];
                $this->contenido[PLANTA] = $value[nombre];
            }
            $ids[]=$value[id_empresa];
            $desc[]=$value[nombre];
        }
        //print_r($ids);
        //echo $_SESSION['ID_ROL'];
        $ut_tool = new ut_Tool();
        $contenido = array();
        $sql = "SELECT valor
                         FROM tb_configuraciones 
                         WHERE nombre = 'MSJ_INICIAL'";
        //$dbl = new Mysql(); 
        $total_registros = $this->dbl->query($sql, $atr);
        $contenido[MSJ_INICIAL] = $total_registros[0][valor];
        if ($_SESSION['ID_EMPRESA'] == 4){
            $contenido[JEFE_FORESTAL] = "| Rodrigo Vicencio";
            $contenido[CARGO_JEFE_FORESTAL] = "| Gerente de Operaciones Forestal";
        }
        $template->setTemplate("presentacion");
        $template->setVars($contenido);
        
        $this->contenido['CONTENIDO'] = $template->show();
        $this->contenido['EMPRESAS'] = $ut_tool->combo_array("id_empresa_actual", $desc, $ids, false, $_SESSION['ID_EMPRESA'], false, false, false, false, 'margin-right:10px;');
        $this->contenido['ANIO_SESSION'] = $_SESSION['ID_EMPRESA'];
        
        
        //Nuevo Mensajes al inicio
        $html_msj = "";
        $html_msj = $this->mensajes();
        
        if (strlen($html_msj)>0){
            $this->contenido[SCRIPT_LOAD] = '$( "#dialog-message" ).dialog({
                    modal: true,                    
                    width: 590,
                    buttons: {
                      Ok: function() {
                        $( this ).dialog( "close" );
                      }
                    }
                  });';
            $this->contenido[MSJ_INICIAL] = $html_msj;
        }
        //Fin Mensaje inicio
//        foreach($anos as $variable) {
//            if ($variable[en_curso] == 't') {
//                $_SESSION['ID_EN_CURSO'] =  $variable[id_ano];
//                $_SESSION['DESC_EN_CURSO'] = $variable[descripcion_ano];
//            }
//            if ($variable[en_proceso_inscripcion] == 't') {
//                $_SESSION['ID_EN_PROCESO'] =  $variable[id_ano];
//                $_SESSION['DESC_EN_CURSO'] = $variable[descripcion_ano];
//            }
//            $ids[]=$variable[id_ano];
//            $desc[]=$variable[descripcion_ano];
//        }
//                        //echo $parametros['id_bandeja'];
//        $combo = new ut_Tool();
//        $this->contenido['COMBO_ANIOS'] = $combo->combo_array('id_ano_activo',$desc,$ids, null, $_SESSION['ID_EN_CURSO']);
//        $this->contenido['ANIO_SESSION'] = $_SESSION['ID_EN_CURSO'];
                
        $menu = new Menu();
        
        $this->contenido['LLAMAR_FUNCION'] = 'ChequearMensajes();AvisaMensajesNotificados();';
        $this->contenido['MENU_VERTICAL'] = $menu->indexMenuVertical(null);
        //echo 'aaaaeeeiiiooo';
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        $template->setTemplate("index");
        $this->contenido['TITLE'] = '.:: D&Z DEVELOPS&TRAINS ::.';
        $this->contenido['USUARIO'] = strtoupper(($_SESSION['NOMBREUSER']));
        $template->setVars($this->contenido);

        echo $template->show();
    }
    
          public function registraTransaccion($accion,$descr, $tabla, $id = ''){
              session_name("mosaikus");
              session_start();
            //$dbl = new PostgreSQL();
            //$dbl->conectar();
            $sql = "INSERT INTO log_transaccions(user_id, ip_conectado, operacion, accion, nombre, clave) VALUES ($_SESSION[USERID], '$_SERVER[REMOTE_ADDR]', '$descr', '$accion', '$tabla', '$id')";
            //$sql = "INSERT INTO mos_log(user_id, ip_conectado, operacion, accion, nombre, clave) VALUES ($_SESSION[USERID], '$_SERVER[REMOTE_ADDR]', '$descr', '$accion', '$tabla', '$id')";
            //insert into mos_log values('".$CodAcci."','".date('Y-m-d G:h:s')."','".$Accion."','".$Accion2."','".$CookIdUsuario."')
//            $atr=array();
//            $atr['accion']=$accion;
//            $atr['descripcion']=$descr;
//            $atr['id_usuario']=$_SESSION['USERID'];
//            $atr['ip_equipo']=$_SERVER["REMOTE_ADDR"];
            //$this->operacion2($sql, $atr, $dbl);
            return $dbl->data[0][0];
        }
         private function operacion2($sp, $atr,&$dbl){
            $param=array();
//            if(is_array($atr)){
//                foreach ($atr as $key => $value) {
//                    $param["@".$key]=$value;
//                }
//            }
            $dbl->exe($sp, $param);
        }
        
        public function mensajes(){
            $html_msj = "";
            $this->dbl = new Mysql();  
            $sql = "select count(*) total 
                from tbl_reporte
                where leido = 0 and NOT fecha_create is null
                and id_transformador = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <img src="diseno/images/semaforo_amarillo.png" title="">
                    Usted tiene <b>'.$this->dbl->data[0][total] .'</b> Avisos SMS en plazo que no ha transformado en Reporte SMS.
                  </p>';
            }
            $sql = "select count(*) total 
                    from tbl_reporte
                    where leido = 1 and NOT fecha_create is null
                    and id_transformador = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene <b>'.$this->dbl->data[0][total] .'</b> Avisos SMS fuera de plazo que no ha transformado en Reporte SMS.
                  </p>';
            }

            $sql = "select count(*) total 
                    from tbl_reporte
                    where leido = 1 and NOT fecha_create is null
                    and id_responsable_area = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene en su &aacute;rea <b>'.$this->dbl->data[0][total] .'</b> Avisos SMS fuera de plazo que no han sido transformado en Reporte SMS.
                  </p>';
            }
            $sql = "select count(*) total 
                    from tbl_reporte
                    where leido = 1 and NOT fecha_create is null
                    and id_sso = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene en sus &aacute;reas <b>'.$this->dbl->data[0][total] .'</b> Avisos SMS fuera de plazo que no han sido transformado en Reporte SMS.
                  </p>';
            }

            $sql = "SELECT
                        count(*) total
                FROM tbl_reporte r
                where 
                 id_empresa = $_SESSION[ID_EMPRESA] 
                and  CURRENT_DATE() <= fecha_cierre  and DATEDIFF(fecha_cierre,CURRENT_DATE()) < DATEDIFF(fecha_cierre,fecha) * 75 / 100
                -- and auditor = ''
                and estatus = 'Abierto'
                and (r.responsable_seguimiento like '%$_SESSION[CORREO]%' OR r.responsable_seguimiento like '%$_SESSION[NOMBREUSER]%')
                "; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <a href="#" onclick="abrir_notificaciones(5);"><img src="diseno/images/semaforo_amarillo.png" title="">
                    Usted tiene <b>'.$this->dbl->data[0][total] .'</b> Reportes SMS donde es responsable de seguimiento, con 5 dias para llegar la fecha estimada de cierre.
                  </a></p>';
            }

            $sql = "SELECT
                        count(*) total
                FROM tbl_reporte r
                where 
                 id_empresa = $_SESSION[ID_EMPRESA] 
                and  CURRENT_DATE() > fecha_cierre 
                and estatus = 'Abierto'
                -- and auditor = ''
                and (r.responsable_seguimiento like '%$_SESSION[CORREO]%' OR r.responsable_seguimiento like '%$_SESSION[NOMBREUSER]%')
                "; 
            $this->dbl->data = $this->dbl->query($sql, $param);//<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>                                
                    <a href="#" onclick="abrir_notificaciones(6);"><img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene <b>'.$this->dbl->data[0][total] .'</b> Reportes SMS donde es responsable de seguimiento, con cierre pendiente.
                    </a></p>';
            }

            $sql = "SELECT
                        count(*) total
                FROM tbl_reporte r
                where 
                 id_empresa = $_SESSION[ID_EMPRESA] 
                and  CURRENT_DATE() <= fecha_cierre  and DATEDIFF(fecha_cierre,CURRENT_DATE()) < DATEDIFF(fecha_cierre,fecha) * 75 / 100
                -- and auditor = ''
                and estatus = 'Abierto'
                and id_sso = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene en sus &aacute;reas <b>'.$this->dbl->data[0][total] .'</b> Reportes SMS, con 5 dias para llegar la fecha estimada de cierre.
                  </p>';
            }

            $sql = "SELECT
                        count(*) total
                FROM tbl_reporte r
                where 
                 id_empresa = $_SESSION[ID_EMPRESA] 
                and  CURRENT_DATE() > fecha_cierre 
                -- and auditor = ''
                and estatus = 'Abierto'
                and id_sso = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]";
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene en sus &aacute;reas <b>'.$this->dbl->data[0][total] .'</b> Reportes SMS con cierre pendiente.
                  </p>';
            }
            
            $sql = "SELECT
                        count(*) total
                FROM tbl_reporte r
                where 
                 id_empresa = $_SESSION[ID_EMPRESA] 
                -- and  CURRENT_DATE() > fecha_cierre 
                -- and auditor = ''
                and estatus = 'Cerrado por Verifica'
                and id_sso = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]";
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene en sus &aacute;reas <b>'.$this->dbl->data[0][total] .'</b> Reportes SMS cerrados que no ha verificado.
                  </p>';
            }
            
            /* seccion nueva para alertas sms */
            
            $sql = "select count(*) total 
                from tbl_reporte
                where leido = -1 and NOT fecha_create is null
                and id_transformador = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <a href="#" onclick="abrir_notificaciones(9);"><img src="diseno/images/semaforo_amarillo.png" title="">
                    Usted tiene <b>'.$this->dbl->data[0][total] .'</b> Alertas SMS en plazo que no ha transformado en Reporte SMS.
                  </a></p>';
            }
            //echo $sql;
            $sql = "select count(*) total 
                    from tbl_reporte
                    where leido = -2 and NOT fecha_create is null
                    and id_transformador = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <a href="#" onclick="abrir_notificaciones(10);"><img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene <b>'.$this->dbl->data[0][total] .'</b> Alertas SMS fuera de plazo que no ha transformado en Reporte SMS.
                  </a></p>';
            }

            $sql = "select count(*) total 
                    from tbl_reporte
                    where leido = -2 and NOT fecha_create is null
                    and id_responsable_area = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <a href="#" onclick="abrir_notificaciones(10);"><img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene en su &aacute;rea <b>'.$this->dbl->data[0][total] .'</b> Alertas SMS fuera de plazo que no han sido transformado en Reporte SMS.
                  </a></p>';
            }
            $sql = "select count(*) total 
                    from tbl_reporte
                    where leido = -2 and NOT fecha_create is null
                    and id_sso = $_SESSION[USERID] AND id_empresa = $_SESSION[ID_EMPRESA]"; 
            $this->dbl->data = $this->dbl->query($sql, $param);
            if ($this->dbl->data[0][total]>0){
                $html_msj .= '<p>
                    <a href="#" onclick="abrir_notificaciones(10);"><img src="diseno/images/semaforo_rojo.png" title="">
                    Usted tiene en sus &aacute;reas <b>'.$this->dbl->data[0][total] .'</b> Alertas SMS fuera de plazo que no han sido transformado en Reporte SMS.
                  </a></p>';
            }
            
            /* fin seccnion alertas sms */
            
            return $html_msj;
        }
        
        final public function showIndexMosaikus($parametros){
        
        if(!class_exists('Template')){
          import("clases.interfaz.Template");
        }
        //include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/PostgreSQL.php');
        if(!class_exists('Menu')){
          import("clases.menu.Menu");
        }
    
        $this->asigna_css_vendor('alertifyjs/css/alertify.min.css');  //para alertas
        $this->asigna_css_vendor('alertifyjs/css/themes/bootstrap.min.css');  //para alertas
        
        $this->asigna_css_vendor('Gallery/css/blueimp-gallery.min.css');
        $this->asigna_css_vendor('Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css');//para gallery
        
        
        $this->asigna_css("jstree-mosaikus/style.css");
        $this->asigna_css("styles.css");
        $this->asigna_css_vendor('eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css');
        $this->asigna_css_vendor('bootstrap-select/dist/css/bootstrap-select.min.css');//para select multiple
        $this->activar_jquery();
          
        //$this->asigna_script("jquery/jquery.fancybox-1.3.1.js");      
        //$this->asigna_css("mos_style.css");
        
        
        
        $this->asigna_script("ui.js");
        $this->asigna_script("menu/menu.js");
        $this->asigna_script("utilidades/array_xml.js");
//        $this->activa_ckeditor();
        $this->finXajax();
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        
        session_name($GLOBALS[SESSION]);
        session_start();
        
        
        $ut_tool = new ut_Tool();
        
        //$template->setTemplate("presentacion");
        //$template->setVars($contenido);
        
        //$this->contenido['CONTENIDO'] = $template->show();
        
        
        
        $menu = new Menu();
        
        //$this->contenido['LLAMAR_FUNCION'] = 'ChequearMensajes();AvisaMensajesNotificados();';
        $this->contenido['MENU_PRINCIPAL'] = $menu->indexMenuMosaikus();
        $sql = "UPDATE mos_usuario_filial SET ultimo_acceso = 1 WHERE id_usuario='".$_SESSION[CookIdUsuario]."' and  id_filial='".$_SESSION[CookFilial]."'";
        $menu->dbl->insert_update($sql);
        $sql = "SELECT COUNT(*) total_registros
                        FROM mos_usuario_filial 
                WHERE id_usuario='".$_SESSION[CookIdUsuario]."' and  id_filial='".$_SESSION[CookFilial]."' AND cod_perfil_portal > 0";
        
        $total_registros = $menu->dbl->query($sql, $atr);
        $total = $total_registros[0][total_registros];  
        if ($total > 0){
            $this->contenido['MODO_PORTAL'] = '<a title="Cambiar a Modo Portal" href="portal.php">
                    <i class="icon-app-mode icon-app-mode-portal"></i>
                </a>';
        }
        //echo 'aaaaeeeiiiooo';
        
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        $template->setTemplate("index");
        //CONSULTAMOS LA INFORMACION DEL USUARIO
        $Consulta="select id_usuario,super_usuario,usuario.email "
                                //. ",CONCAT(UPPER(LEFT(nombres, 1)), LOWER(SUBSTRING(nombres, 2))) nombres"
                                . ",initcap(initcap(SUBSTR(usuario.nombres,1,IF(LOCATE(' ' ,usuario.nombres,1)=0,LENGTH(usuario.nombres),LOCATE(' ' ,usuario.nombres,1))))) nombres"
                                . ",CONCAT(UPPER(LEFT(usuario.apellido_paterno, 1)), LOWER(SUBSTRING(usuario.apellido_paterno, 2))) apellido_paterno "
                                . ",CONCAT(UPPER(LEFT(usuario.apellido_materno, 1)), LOWER(SUBSTRING(usuario.apellido_materno, 2))) apellido_materno "                                
                                . ",c.descripcion cargo "
                                . " from mos_usuario usuario LEFT JOIN mos_personal persona "
                                . " on usuario.email = persona.email "
                                . " LEFT JOIN mos_cargo c ON c.cod_cargo = persona.cod_cargo "
                                . " where usuario.id_usuario=$_SESSION[CookIdUsuario]";
        $data_usuario = $menu->dbl->query($Consulta, $atr);
        //$this->contenido['TITLE'] = '.:: D&Z DEVELOPS&TRAINS ::.';
        $this->contenido['NOMBREMPRESA'] = strlen($data_usuario[0][cargo]) > 0 ? $data_usuario[0][cargo] : "Consultor Externo";//(($_SESSION['CookNomEmpresa']));
        $this->contenido['LOGO_EMPRESA'] = (($_SESSION['CookIdEmpresa']));
        $this->contenido['USUARIO'] = $data_usuario[0]["nombres"]." ".$data_usuario[0]["apellido_paterno"];//(($_SESSION['CookNamUsuario']));
        $template->setVars($this->contenido);
        
        echo $template->show();
    }
    
    final public function showIndexMosaikusPortal($parametros){
        
        if(!class_exists('Template')){
          import("clases.interfaz.Template");
        }
        //include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/PostgreSQL.php');
        if(!class_exists('Menu')){
          import("clases.menu.Menu");
        }
        $this->asigna_css_vendor('alertifyjs/css/alertify.min.css');  //para alertas
        $this->asigna_css_vendor('alertifyjs/css/themes/bootstrap.min.css');  //para alertas
         $this->asigna_css_vendor('Gallery/css/blueimp-gallery.min.css');
        $this->asigna_css_vendor('Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css');//para gallery
        $this->asigna_css_vendor('bootstrap-select/dist/css/bootstrap-select.min.css');//para select multiple
        
        $this->asigna_css("jstree-mosaikus/style.css");
        $this->asigna_css("styles.css");
        
        $this->activar_jquery();
        
        //$this->asigna_script("jquery/jquery.fancybox-1.3.1.js");      
        //$this->asigna_css("mos_style.css");
        
        
        
        $this->asigna_script("ui.js");
        $this->asigna_script("menu/menu.js");
        $this->asigna_script("utilidades/array_xml.js");
//        $this->activa_ckeditor();
        $this->finXajax();
        $template = new Template();
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        
        session_name($GLOBALS[SESSION]);
        session_start();
        
        
        $ut_tool = new ut_Tool();
        
        //$template->setTemplate("presentacion");
        //$template->setVars($contenido);
        
        //$this->contenido['CONTENIDO'] = $template->show();
        
        
        
        $menu = new Menu();
        
        //$this->contenido['LLAMAR_FUNCION'] = 'ChequearMensajes();AvisaMensajesNotificados();';
        $this->contenido['MENU_PRINCIPAL'] = $menu->indexMenuMosaikusPortal();
        //echo 'aaaaeeeiiiooo';
        
        $sql = "UPDATE mos_usuario_filial SET ultimo_acceso = 2 WHERE id_usuario='".$_SESSION[CookIdUsuario]."' and  id_filial='".$_SESSION[CookFilial]."'";
        $menu->dbl->insert_update($sql);
        $sql = "SELECT COUNT(*) total_registros
                        FROM mos_usuario_filial 
                WHERE id_usuario='".$_SESSION[CookIdUsuario]."' and  id_filial='".$_SESSION[CookFilial]."' AND cod_perfil > 0";
        $total_registros = $menu->dbl->query($sql, $atr);
        $total = $total_registros[0][total_registros];  
        if ($total > 0){
            $this->contenido['MODO_ESPECIALISTA'] = '<a title="Cambiar a Modo Especialista" class="dropdown-toggle" href="index.php">
                    <i class="icon-app-mode icon-app-mode-specialist"></i>
                </a>';
        }
        
        $template->PATH = PATH_TO_TEMPLATES.'index/';
        $template->setTemplate("index_portal");
        //CONSULTAMOS LA INFORMACION DEL USUARIO
        $Consulta="select id_usuario,super_usuario,usuario.email "
                                //. ",CONCAT(UPPER(LEFT(nombres, 1)), LOWER(SUBSTRING(nombres, 2))) nombres"
                                . ",initcap(initcap(SUBSTR(usuario.nombres,1,IF(LOCATE(' ' ,usuario.nombres,1)=0,LENGTH(usuario.nombres),LOCATE(' ' ,usuario.nombres,1))))) nombres"
                                . ",CONCAT(UPPER(LEFT(usuario.apellido_paterno, 1)), LOWER(SUBSTRING(usuario.apellido_paterno, 2))) apellido_paterno "
                                . ",CONCAT(UPPER(LEFT(usuario.apellido_materno, 1)), LOWER(SUBSTRING(usuario.apellido_materno, 2))) apellido_materno "                                
                                . ",c.descripcion cargo "
                                . " from mos_usuario usuario LEFT JOIN mos_personal persona "
                                . " on usuario.email = persona.email "
                                . " LEFT JOIN mos_cargo c ON c.cod_cargo = persona.cod_cargo "
                                . " where usuario.id_usuario=$_SESSION[CookIdUsuario]";
        $data_usuario = $menu->dbl->query($Consulta, $atr);
        //$this->contenido['TITLE'] = '.:: D&Z DEVELOPS&TRAINS ::.';
        $this->contenido['NOMBREMPRESA'] = strlen($data_usuario[0][cargo]) > 0 ? $data_usuario[0][cargo] : "Consultor Externo";//(($_SESSION['CookNomEmpresa']));
        $this->contenido['LOGO_EMPRESA'] = (($_SESSION['CookIdEmpresa']));
        $this->contenido['USUARIO'] = $data_usuario[0]["nombres"]." ".$data_usuario[0]["apellido_paterno"];//(($_SESSION['CookNamUsuario']));
        $template->setVars($this->contenido);
        
        echo $template->show();
    }
    
    function formatear_rut($tupla){
            if ($tupla[extranjero]=='NO'){
                $cadena = str_pad($tupla[id_personal],8,0,STR_PAD_LEFT);
                $largo_cadena = strlen($cadena);
                $cadena_izquierda = substr($cadena, 0, $largo_cadena-1);
                $cadena_derecha = substr($cadena, $largo_cadena-1, 1);

                $final = number_format($cadena_izquierda,0,"",".")."-".$cadena_derecha;
                return $final;
            }
            return $tupla[id_personal];

        } 
        
        public function obtenerNodosArbolNivel($coleccion, $padre){
            $param=$orden=array();
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
}
?>
