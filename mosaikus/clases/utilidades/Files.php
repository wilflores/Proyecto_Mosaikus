<?php 
include(dirname(dirname(dirname(__FILE__))). "/config/config.php");
class Files{	
	private $links;	
	private $separador;
	private $extenciones;	
	/************************************************************************************************/
	public function __construct(){
		if (strrpos(HOME ,'\\') === false)
			$this->separador = '/';
		else
			$this->separador = '\\';
		$tmp = scandir(HOME."/temas/constante/icons/normal/");
		foreach($tmp as $valor)
			if(strrpos($valor,'.png')!= false)
				$this->extenciones[] = substr($valor,0,-4);
	}	
	/************************************************************************************************/
	public function archivos($files){
		$this->links = '<div>';
		$this->links .='<legend>{USUARIO_REGISTRO_ACTUALIZARSE} {nombre}</legend>';
		if(is_array($files[0]))
			foreach($files as $file)
				$this->links .= $this->make($file);
		else
			$this->links .= $this->make($files);
		$this->links .= '</div>';
		return $this->links;
	}
	/************************************************************************************************/
	private function make($file){
		return  "\t\t<a href=\"$file[0]\"><img src=\"".APPLICATION_ROOT."temas/constante/icons/normal/".$this->extension($file[0]).".png\" border=\"0\" onmouseover=\"this.src='".APPLICATION_ROOT."temas/constante/icons/encima/".$this->extension($file[0]).".png'\" onmouseout=\"this.src='".APPLICATION_ROOT."temas/constante/icons/normal/".$this->extension($file[0]).".png'\" title = \"$file[1]\" /></a>\n";
	}
	/************************************************************************************************/
	private function extension($file){
		$tmp = pathinfo($file,PATHINFO_EXTENSION);
		if(in_array($tmp,$this->extenciones))
			return $tmp;
		return 	"des";
	}
	/************************************************************************************************/
	public function grupos($files){
		foreach($this->extenciones as $extencion)
			$tmp[$extencion] = '';
		$this->links = '<div>';
		if(is_array($files[0])){
			foreach($files as $file)
				$tmp[$this->extension($file[0])].= $this->make($file);			
			foreach($tmp as $key => $value)
				if($value != ''){				
					$this->links .="<fieldset>\n";
					$this->links .="	<legend>$key</legend>\n\n";
					$this->links .=$value."\n";
					$this->links .="</fieldset>\n<br />";
				}			
		}
		$this->links .= '</div>';
		return $this->links;	
	}
	/************************************************************************************************/
}
?>