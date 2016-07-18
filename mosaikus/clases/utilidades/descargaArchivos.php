<?php
class visualizador_documentos {
	public $nombre_carpeta;
	public $path_archivo;
	public $nombre_archivo;
	public $NombreDoc;
	public $Codigo;
	public $version;
	public $Ext2;
	public $contenido2;


	function __construct($BD,$NombreDoc,$Codigo,$version,$Ext2,$contenido2){
		$this->nombre_carpeta = $BD; //EL NOMBRE DE LA CARPETA CORRESPONDE AL NOMBRE DE LA BD
		$this->NombreDoc = $NombreDoc;
		$this->Codigo = $Codigo;
		$this->version = $version;
		$this->Ext2 = $Ext2;
		$this->contenido2 = $contenido2;

		$this->path_archivo = APPLICATION_DOWNLOADS . "tmp_doc/".$this->nombre_carpeta;
                //echo $this->path_archivo;
	}

	public function VisualizaDocumento(){
		$this->CreaCarpetaPermisosArchivo();
		$this->QuitaAcentos();
		$this->CreaArchivoEnCarpeta();

		//echo $this->nombre_archivo;
		//file_put_contents( $this->path_archivo."/".$this->nombre_archivo, $this->contenido2 );&chrome=true
                //echo " http://docs.google.com/gview?url=" . APPLICATION_ROOT . "downloads/tmp_doc/".$this->nombre_carpeta."/".  str_replace (' ', '%20', $this->nombre_archivo)."";
		Header("Location: https://docs.google.com/gview?url=" . APPLICATION_ROOT . "downloads/tmp_doc/".$this->nombre_carpeta."/".$this->nombre_archivo."");
		//*************ENTERMEDIABIT*****************//
	}
        
        public function ActivarDocumento(){
		$this->CreaCarpetaPermisosArchivo();
		$this->QuitaAcentos();
		$this->CreaArchivoEnCarpeta();
                return ""  . APPLICATION_ROOT . "downloads/tmp_doc/".$this->nombre_carpeta."/".$this->nombre_archivo."";
		//echo $this->nombre_archivo;
		//file_put_contents( $this->path_archivo."/".$this->nombre_archivo, $this->contenido2 );&chrome=true
		Header("Location: http://docs.google.com/gview?url=" . APPLICATION_ROOT . "downloads/tmp_doc/".$this->nombre_carpeta."/".$this->nombre_archivo."");
		//*************ENTERMEDIABIT*****************//
	}
        
        public function DescargarDocumento(){
		$this->CreaCarpetaPermisosArchivo();
		$this->QuitaAcentos();
		$this->CreaArchivoEnCarpeta();

		//echo $this->nombre_archivo;
		//file_put_contents( $this->path_archivo."/".$this->nombre_archivo, $this->contenido2 );
		Header("Location: " .APPLICATION_ROOT . "downloads/tmp_doc/".$this->nombre_carpeta."/".$this->nombre_archivo);
		//*************ENTERMEDIABIT*****************//
	}


	public function CreaCarpetaPermisosArchivo(){
		if (!file_exists(APPLICATION_DOWNLOADS . "tmp_doc")) {
                    //echo APPLICATION_DOWNLOADS . "tmp_doc";
                        mkdir(APPLICATION_DOWNLOADS . "tmp_doc", 0777);
                        
			chmod(APPLICATION_DOWNLOADS . "tmp_doc", 0777);
		}

		if (!file_exists($this->path_archivo)) {
	    	mkdir($this->path_archivo, 0777);
			chmod($this->path_archivo, 0777);
		}
	}

	public function QuitaAcentos(){
                //echo $this->version . ' ' . $this->NombreDoc . ' ' . 2;
                switch ($this->version) {
                    case "evidencia":
                        $this->nombre_archivo = $this->NombreDoc.".".$this->Ext2;
                        break;
                    case "NO_APLICA":
                        $this->nombre_archivo = $this->Codigo."-".$this->NombreDoc.".".$this->Ext2;
                        break;                    
                    case "HOJA_VIDA":
                        $this->nombre_archivo = (CambiaSinAcento($this->NombreDoc));
                        //echo $this->nombre_archivo;
                        break;
                    default:
                        $this->nombre_archivo = $this->Codigo."-".($this->NombreDoc)."-V".str_pad($this->version,2,0,STR_PAD_LEFT).".".$this->Ext2;
                        break;
                }
/*		if ($this->version=="evidencia")
			$this->nombre_archivo = $this->NombreDoc.".".$this->Ext2;
		else if ($this->version=="NO_APLICA")
			$this->nombre_archivo = $this->Codigo."-".$this->NombreDoc.".".$this->Ext2;
		else
			$this->nombre_archivo = $this->Codigo."-".$this->NombreDoc."-V".str_pad($this->version,2,0,STR_PAD_LEFT).".".$this->Ext2;
*/
	}

        public function getNombreArchivo(){
            $pos = strrpos($this->nombre_archivo,'.');
            if ($pos >=0)
                return (substr ($this->nombre_archivo, 0,$pos));
            return  $this->nombre_archivo;
        }

        public function CreaArchivoEnCarpeta(){
            //$this->nombre_archivo = str_replace(' ', '_', $this->nombre_archivo);
                if (file_exists($this->path_archivo."/".$this->nombre_archivo)) {
                    unlink($this->path_archivo."/".$this->nombre_archivo);
                }
		if (!file_exists($this->path_archivo."/".$this->nombre_archivo)) {
			$file = fopen($this->path_archivo."/".$this->nombre_archivo,"x");
			file_put_contents( $this->path_archivo."/".$this->nombre_archivo, $this->contenido2 );
		}
	}

	public function quitar_tildes($cadena) {
		$no_permitidas= array ("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","Ù","� ","è","ì","ò","ù","�","�","â","�","î","ô","û","Â","Ê","Î","Ô","Û","�","ö","Ö","ï","ä","�","�","Ï","Ä","Ë");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}

}
?>