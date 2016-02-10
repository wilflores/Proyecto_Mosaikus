<?php
/*Modificaciones : 
En el metodo setVars se pasa el array por el metodo acentos_array()
*/
class Template{
	//::::: Atributos :::::
	private $htmlTemplate;//contenido de la plantilla
	private $fileRead;//contenido original del archivo
	private $dataRead;//indica si se a asignado el codigo de una plantilla
	private $extension = 'tpl';
  public  $PATH = 'templates/';  
	//:::::Metodos :::::
  /************************************************************************************************/
	/**
	* Constructor de la clase
	* toma la direccion de las plantillas  del archivo config.php
	* si no se incluye el archivo se debera de definir la constante
    * antes de instanciarlo
    *
	* @return null
	*/
	public function Template(){
                if(!class_exists('Utils')){
                  import('clases.utilidades.Utils');
                }
	 	$this->fileRead = false;
		$this->dataRead = false;
                $this->PATH = PATH_TO_TEMPLATES;
	}

        public function setExtension($ext){
                $this->extension = $ext;
        }
  /************************************************************************************************/
	/**
	* Carga el contenido del archivo indicado
	* en htmlTemplate
	*
    * @param $templateFile Archivo a cargar para la plantilla
	* @return boolean
	*/
	public function setTemplate($templateFile){
		$file =	$this->PATH.$templateFile.".".$this->extension;
		$return = false;
		if(file_exists($file)){
			$tmp    =   $this->fileRead	= @file_get_contents($file);
			if($tmp){
			    if (ord($tmp) == 239)
				$tmp = substr($tmp,3);
                            $this->htmlTemplate	=	str_replace ("'", "\'", $tmp);
                            $this->htmlTemplate	=	str_replace ("{PAHT_TO_IMG}", PATH_TO_IMG, $this->htmlTemplate);                            
                            $this->htmlTemplate	=	str_replace ("{APPLICATION_NOMBRE_EMPRESA}", APPLICATION_NOMBRE_EMPRESA, $this->htmlTemplate);
                            $this->htmlTemplate	=	str_replace ("{APPLICATION_NOMBRE_FIRMA_MSJ}", APPLICATION_NOMBRE_FIRMA_MSJ, $this->htmlTemplate);
                            $this->htmlTemplate	=	str_replace ("{APPLICATION_CARGO_FIRMA_MSJ}", APPLICATION_CARGO_FIRMA_MSJ, $this->htmlTemplate);
                            $this->htmlTemplate	=	str_replace ("{FIRMA_MSJ_CORREO}", FIRMA_MSJ_CORREO, $this->htmlTemplate);
                            $return = true;
                        }
			unset($this->htmlText);
		}
		return $return;
	}
  /************************************************************************************************/
	/**
	* Asigna el contenido de $data como 
	* la plantilla con la que se formateara
	*
    * @param $data Cadena que sera la plantilla a usar
	* @return boolean
	*/
	public function setData($data){
		$this->dataRead = true;
		$this->htmlTemplate = str_replace ("'", "\'", $data);
		unset($this->htmlText);
		return true;
	}
  /************************************************************************************************/
	/**
	* Busca las plantillas en un archivo
	* y las retorna en un array
	*
    * @param $Source codigo o direccion del archivo que contiene las plantillas
    * @param $isData   indica si $Source es codigo o una direccion
	* @return array
	*/
	public function getTemplates($Source = '',$isData = false){
    if(!$isData){
			$file	= $this->PATH.$Source.".".$this->extension;
      if(file_exists($file)){
        $Content = @file_get_contents($file);
      }
		}
		else{
			$Content	=	$Source;
		}
    $Templates 	= array( );
		$RegExp		= 	"<\!-- BEGIN (.*?) -->(.*?)<\!-- END \\1 -->";
		preg_match_all("/" . $RegExp . "/s", $Content , $Matches);
		if(is_array($Matches[1])){
			foreach ($Matches[1] as $key => $TemplateName){
				$Aux = $Matches[2][$key];
				$Templates[$TemplateName]	=	$this->getTemplates($Matches[2][$key],true);
				if(empty($Templates[$TemplateName])){
					$Templates[$TemplateName]	= $Aux;
				}
			}
		}
		return $Templates;
	}
	/************************************************************************************************/
	/**
	* Realiza el cruze entre la plantilla
	* y un array asociativo de valores
	*
    * @param $vars array asociativo con los valores que seran formateados con la plantilla
	* @return boolean
	*/
  public function setVars($vars = null,$tabs = false){
    $return = false;
    /**************************************************************************/
    $vars = Utils::acentos_array($vars);
    /**************************************************************************/    
    if($tabs){
      $variables = $this->getVars();
      $tabs_K = array();
      $tabs_N = array();
      $ind = 0;
      foreach($variables as $tab){
        if(strtolower(substr($tab,0,3))=='tab'){
          $tabs_K[$ind] = $tab;
          $tabs_N[$ind++] = str_replace('_',' ',substr($tab,4));
        }
      }
      if(($Ctabs=count($tabs_K))>0){
        if(!is_array($vars))
          $vars = array();
        $tmp = "\n    <div class=\"tabs\">\n     <ul>";         
        $tmp .= "      <li id=\"".$tabs_K[0]."_tab\" class=\"current\"><span><a href=\"javascript:mcTabs.displayTab('".$tabs_K[0]."_tab','".$tabs_K[0]."_panel');\">".$vars[$tabs_N[0]]."</a></span></li>\n";
        for($ind = 1;$ind < $Ctabs;$ind++){
          $tab = substr($tabs[$ind],4);
          $tmp .= "      <li id=\"".$tabs_K[$ind]."_tab\"><span><a href=\"javascript:mcTabs.displayTab('".$tabs_K[$ind]."_tab','".$tabs_K[$ind]."_panel');\">".$vars[$tabs_N[$ind]]."</a></span></li>\n";
          $vars[$tabs_K[$ind]] = "     <div id=\"".$tabs_K[$ind]."_panel\" class=\"panel\">\n";
        }
        $tmp .= "    </div>\n     </ul>\n    <div class=\"panel_wrapper\">\n";
        $vars[$tabs_K[0]] = $tmp."     <div id=\"".$tabs_K[0]."_panel\" class=\"current\">\n";
        $vars['endtab']="     </div>\n";
        $vars['endendtab']="     </div>\n";
      }
    }
    /**************************************************************************/
    if(($this->fileRead or $this->dataRead) and (is_array($vars))){
      $this->vars = $vars;
      $this->htmlText = $this->htmlTemplate;
      $this->htmlText = preg_replace('#\{([a-z0-9\-_]*?)\}#is', "' . $\\1 . '", $this->htmlText);
      reset ($this->vars);
      while(list($key,$val) = each($this->vars)){
          $$key = $val;
      }
      eval("\$this->htmlText = '$this->htmlText';");
      reset ($this->vars);
      while(list($key,$val) = each($this->vars)){
          unset($$key);
      }
      $this->htmlText = str_replace ("\'", "'", $this->htmlText);
      $return = true;
    }
    return $return;
  }
	/************************************************************************************************/
	/**
	*  Obtiene las variables dentro de la plantilla
	*
	* @return boolean o array con las variables 
	*/
  public function getVars(){
    $return = false;
    if($this->fileRead or $this->dataRead){
      $Vars = array();
      preg_match_all('/\{([\w-].*?)\}/s', $this->htmlTemplate,$Vars);
      $return = $Vars[1];
    }
    return $return;
	}
  /************************************************************************************************/
	/**
	* Retorna cadena con la data ya formateada
	* con la plantilla seleccionada
	*
	* @return string
	*/
	public function show(){
		$return = "";
                if($this->fileRead or $this->dataRead){
                        $return = isset($this->htmlText) ? $this->htmlText : $this->htmlTemplate ;
                }
                else{
                  trigger_error("No existe ninguna plantilla");
                }
                return $return;
	}
}
?>
