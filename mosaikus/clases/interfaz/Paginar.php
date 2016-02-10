<?php
/*file:Pagina
 Cambie el nombre de algunas variables
*/
//import("clases.interfaz.Template");

class Paginar{
  private  $pagina_actual;//contenido a mostrar dentro de la pagina
  private  $num_por_pagina;//datos del usuario actual
  private  $num_paginas;
  private $parametros;//parametros que se pueden pasar al datagrig a la hora de paginar  
  private  $PATH;//ruta de las plantillas



  /************************************************************************************************/
  /**
    * Constructor de la clase
    *
    * @return null
    */    
  public function Paginar($total, $paginas){
     
    $this->num_por_pagina = $total;
    $this->num_paginas = $paginas;
    $this->pagina_actual = 1; 
	$this->PATH =  PATH_TO_TEMPLATES.'interfaz/';	
    $this->parametros = array();	
  }

	
  /************************************************************************************************/
  /**
    * Agrega un parametro al datagrid
    * estoy es para enviar parametros
    * cuando se realiza el paginado
    *
    *  @return null
    */
  public function agregarParametro($Nombre,$Valor){
    $this->parametros[$Nombre] = $Valor;
  }	  
  
  /************************************************************************************************/
  final public function asigna_pagina_actual($pagina){
    $this->pagina_actual = $pagina;
  }

  /************************************************************************************************/
  final public function show(){
    $template = new Template();
    $template->PATH = $this->PATH;
    $template->setTemplate("paginar");
	$this->contenido = array();
	$this->contenido['PAG_ACTUAL'] = $this->pagina_actual;
	$this->contenido['TOTAL_PAGINAS'] = $this->num_paginas;
	$this->contenido['PAGINAS'] = "";
	if ($this->pagina_actual == 1)
	   	$this->contenido['PAGINAS'] .= "<span><strong>Anterior</strong></span>";
	else
		$this->contenido['PAGINAS'] .= '<a href="#" onclick="Paginar('. ($this->pagina_actual - 1) .'); return false;"><strong>Anterior</strong></a>';
	for($i=1; $i <= $this->num_paginas; $i++)
	{
	    if ($this->pagina_actual == $i)
			$this->contenido['PAGINAS'] .= "<span>" . $i . "</span>";
		else
			$this->contenido['PAGINAS'] .=   '<a href="#" onclick="Paginar('. $i .'); return false;">' . $i . '</a>';
	}
	if ($this->pagina_actual == $this->num_paginas)
	   	$this->contenido['PAGINAS'] .= "<span><strong>Siguiente</strong></span>";	
	else
		$this->contenido['PAGINAS'] .= '<a href="#" onclick="Paginar('. ($this->pagina_actual + 1) .'); return false;" ><strong>Siguiente</strong></a>';		
    if(count($this->parametros)>0){
      $this->contenido['PARAMETROS'] = '';
      foreach($this->parametros as $key => $parametro){
        $this->contenido['PARAMETROS'] .= "&$key=$parametro";
      }
    }
		
    $template->setVars($this->contenido);
	
    return $template->show();
	
  }
}
?>
