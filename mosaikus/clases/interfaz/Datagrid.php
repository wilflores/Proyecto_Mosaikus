<?php
/**
Compa�ia: Corporacion NGS
Autor(es):  Juziel Indriago
Prop�sito:  Crea un grid en html
Version:  0.0
Fecha: 09 jun 2007
Actualizada: 11 jul 2007
Requirimientos:
PHP Version >= 5
Descripcion: La clase tiene como funcion dibujar un DataGrid a partir de un conjunto de datos de una consulta(result set)
Considerando la implementacion de paginado del la tabla de de datos o grid.
*/

class DataGrid{

    //////////////////////////Atributos//////////////////////////////////
    private $tituloTabla;    //Titulo de la Tabla
    private $funciones;//array de nombres de funciones por al cuan se pasara los valores
    private $configuracion;  //String con atributos de la Tabla como width='100%' align ='center' border='0'
    private $titulosColumna; // String que contiene titulos de las Columnas
    private $totales;        //String que contiene la fila de total
    private $totalposicion;        //indica si el total esta al final 1 o al comienzo de la tabla 0
    private $datos;			 // Datos que muestra la tabla
    private $tabla; 		 // String que contiene el codigo html de la tabla
    private $regPagina;      // Numero de registros por pagina;
    private $nroPag;         // Numero de paginas
    private $color;          // Color del tr seleccionado
    private $funResalatdo;
    private $filaResaltado;
    private $colorArray;     // Registros seleecionasdoe n el orden de la tabla
    private $pagActual;		 // Nupmero de Pagina en la que se encuantra el grid posicionado
    private $hide;           // Oculta alguna columna
    public  $hidden;//array con los datos que se van a ocultar
    private $diffAction;     // asiga una funcion diferente a la columna de acciones cuando tiene un color distinto
    public  $aligns;//array en donde se indica a que columnas se le va a aplicar la alineacion
    private $total_reg;
    private $Parent;
    ////////////////////Metodos///////////////////////////////////////

   /**
    * Inicializa las variables principales del grid
    *
    */
    public function __construct(/*$funcion=null, $fila=null*/){
        $this->tituloTabla=null;
        $this->regPagina=null;
        $this->color=null;
        $this->funResalatdo;
        $this->filaResaltado;
        $this->hide=-20;
        $this->colorArray=array();
        $this->diffAction=false;
        $this->hidden=array();
        $this->funciones = array();
        $this->aligns = array();
        $this->total_reg = 0;
        $this->Parent = null;
    }
    
    /************************************************************************************************/
  /**
    * Retorna el valor de la columna se verifica si se tiene
    * que pasar o no por una funcion y se realiza el cambio
    * de los caracteres especiales
    *
    * @param $Columna nombre original de la columna
    * @param $Valores array que representa tupla o registro de la data
    * @return string valor de la columna ya formateado
    */
  private function valorColumna($Columna, $Valores){      
    if(isset($this->funciones[$Columna])){
      $function  =  $this->funciones[$Columna];
      if ($this->Parent == null)
        @eval(" \$valor = \$function (\$Valores);");
      else
        @eval(" \$valor = \$this->Parent->$function (\$Valores,\$Columna);");
    }
    else{
      $valor = $Valores[$Columna];
    }
    return $valor;
  }
  
   /************************************************************************************************/
  /**
    * Retorna el valor de la columna se verifica si se tiene
    * que pasar o no por una funcion y se realiza el cambio
    * de los caracteres especiales
    *
    * @param $Columna nombre original de la columna
    * @param $Valores array que representa tupla o registro de la data
    * @return string valor de la columna ya formateado
    */
  private function valorColumnaFilaActual($Columna, $Valores, $fila){      
    if(isset($this->funciones[$Columna])){
      $function  =  $this->funciones[$Columna];
      if ($this->Parent == null)
        @eval(" \$valor = \$function (\$Valores, \$fila);");
      else
        @eval(" \$valor = \$this->Parent->$function (\$Valores, \$fila);");
    }
    else{
      $valor = $Valores[$Columna];
    }
    return $valor;
  }
  /************************************************************************************************/
  /**
    * Asigna la funcion que sera llamada cada vez
    * que se va a imprimir la columna '$Nombre' el valor
    * que se imprimiria seria el devuelto por $NombreFuncion
    *
    * @param $Nombre nombre original de la columna
    * @param $NombreFuncion funcion a ser llamada
    * @return null
    */
    public function setFuncion($Nombre,$NombreFuncion){        
        $this->funciones[$Nombre] = $NombreFuncion;
    }

    public function setAligns($Nombre,$alings){
        $this->aligns[$Nombre] = $alings;
    }
    public function setParent($parent){
        $this->Parent = $parent;
    }

    /**
     * define la columna de accion para la columnas coloreadas que se  deben comportar diferente al hacer click
     * @param arreglo de la siguente manerea
     * array(
     *      nombre=> 'nombre de la funcion java scrip qur recibe por defecto el id del registro'
     *      imagen=> 'comtenido o imagen HTML de boton a presionar'
     *  )
     */
    public function setTrueDiffActio($btnaccion){
        $this->diffAction=$btnaccion;
    }
   /**
    * Difine si se va a utilizar el paginado
    *
    * @param Int $registros
    * 		Numero de Registros por pagina.
    */
    
    public function setPaginado($registros, $total){
        $this->regPagina=$registros;
        $this->total_reg = $total;
	$this->nroPag=((int)($total/$this->regPagina) + (($total % $this->regPagina) > 0 ? 1 : 0 ));
    }
    
    public function setPaginado_new($registros){
        $this->regPagina=$registros;
    }

    
    

    /**
     * Define en resaltado de las filas en el grid.
     *
     * @param unknown_type $col
     * 		Color en Hexadecimal de la celda a resaltar.
     * @param array() $array
     * 	Arreglo que indica las filas a Resaltar, de la forma {1,0,0,1} donde el numero de elementos corresponde
     *         al numero de filas y el valor 1 es para resaltar con 0 se ignora.
     */
    public function setColor($col,$array){
        $this->color=$col;
        if(is_array($array))
        $this->colorArray=$array;
    }


    /**
     * Obtiene el numero de paginas de los registros.
     */
    public function getNumeroPag(){
        if($this->regPagina!=null){
            return $this->nroPag;
        }else {
            return -1;
        }
    }


    /**
     * Define la configuracion general de la tabla.
     *
     * @param unknown_type $id
     * 		identificador de la tabla "id"
     * @param String  $atributo
     * 		Atributos de la tabla, en al forma de HTML atributo="valor"

     */
    public function SetConfiguracion($id, $atributo){
        $this->configuracion= "	<table class='data-table' id=\"$id\" $atributo >\n";
        //$this->configuracion= "	<table class='borde_table' id=\"$id\" ".$atributo;
        //$this->configuracion.="  >\n";
    }
    
    /**
     * Define la configuracion general de la tabla.
     *
     * @param unknown_type $id
     * 		identificador de la tabla "id"
     * @param String  $atributo
     * 		Atributos de la tabla, en al forma de HTML atributo="valor"

     */
    public function SetConfiguracionMSKS($id, $atributo){
        $this->configuracion= "	<table width=\"100%\" class='table table-striped table-condensed' id=\"$id\" $atributo >\n";
        //$this->configuracion= "	<table class='borde_table' id=\"$id\" ".$atributo;
        //$this->configuracion.="  >\n";
    }

    /**
     * Define la cabecera o titulos de la tabla.
     *
     * @param String $cssclass
     * 			Nombre del estilo de la cabecera.
     * @param array() $config
     * 		Arreglo con los titulos de las etiquetas, de la forma.
     *			array(
     *				  array("atributo"=>valor,
     *				        "atributo"=>valor,
     *				        "atributo"=>valor,
     *				       )
     *				   array("atributo"=>valor,
     *				        "atributo"=>valor,
     *				        "atributo"=>valor,
     *				       )
     *				  )
     *	 Cada arreglo interno corresponde a un titulo y el atributo es un alguno HTML y su respectivo valor.
     *	 El titulo que aparecere enla tabla de define bajo el atributo 'ValorEtiqueta'=>"mi Titulo".
     *
     */
    public function SetTitulosTabla($cssclass, $config){
//        $this->titulosColumna="	<tr>\n";
//        foreach($config as $detalle){
//            $this->titulosColumna.="	<th  class= \"$cssclass\" ";
//            foreach($detalle as $key=>$value){
//                if ($key!='ValorEtiqueta')
//                $this->titulosColumna.=" $key = \"$value\"  ";
//                else
//                $this->titulosColumna.="><div align=\"center\">$value</div></th>\n";
//            }
//        }
//        $this->titulosColumna.=" </tr>\n";
         $this->titulosColumna="<tr>";
        foreach($config as $detalle){
            $this->titulosColumna.="<th ";
            foreach($detalle as $key=>$value){
                if ($key!='ValorEtiqueta')
                $this->titulosColumna.=" $key = \"$value\"  ";
                else
                $this->titulosColumna.="><div align=\"center\">$value</div></th>\n";
            }
        }
        $this->titulosColumna.="</tr>";

    }
    
    public function SetTitulosTablaMSKS($cssclass, $config){
//        $this->titulosColumna="	<tr>\n";
//        foreach($config as $detalle){
//            $this->titulosColumna.="	<th  class= \"$cssclass\" ";
//            foreach($detalle as $key=>$value){
//                if ($key!='ValorEtiqueta')
//                $this->titulosColumna.=" $key = \"$value\"  ";
//                else
//                $this->titulosColumna.="><div align=\"center\">$value</div></th>\n";
//            }
//        }
//        $this->titulosColumna.=" </tr>\n";
         $this->titulosColumna="<thead><tr height=\"30px\">";
        foreach($config as $detalle){
            $this->titulosColumna.="<th ";
            foreach($detalle as $key=>$value){
                if ($key!='ValorEtiqueta')
                $this->titulosColumna.=" $key = \"$value\"  ";
                else
                $this->titulosColumna.="><div align=\"left\">$value</div></th>\n";
            }
        }
        $this->titulosColumna.="</tr></thead>";

    }
    
    public function AddTitulosTabla($cssclass, $config){
        $this->titulosColumna .="	<tr>\n";
        foreach($config as $detalle){
            $this->titulosColumna.="	<th  class= \"$cssclass\" ";
            foreach($detalle as $key=>$value){
                if ($key!='ValorEtiqueta')
                $this->titulosColumna.=" $key = \"$value\"  ";
                else
                $this->titulosColumna.="><div align=\"center\">$value</div></th>\n";
            }
        }
        $this->titulosColumna.=" </tr>\n";
    }

    /**
     *Define las funciondes de resaltado de fila por defecto
     *
     */
    public function setResaltarFila($dir=null, $efec=null){
        if ($dir!=null) {
            $this->funResalatdo=$dir;
        }
        if ($efec!=null) {
            $this->filaResaltado=$efec;
        }
        return $this->funResalatdo;

    }  

    /**
     * Arma la tabla o grid en funcion de los datos.
     * NOTA: esta funcion sustituye a "setData", es una mejor manera de ingresar los datos a la Tabla.
     *
     *
     * @param String $cssclass
     * 				estilo ccs de la tabla.
     * @param array() $data
     * 				array con datos, de la forma que lo devuelve la consulta.
     * @param array() $funciones
     * 				array con la definicion de funciones, es decir, todos los componentes o botonen que se deseen en una
     *             columna. el arreglo es de la siguiente forma:
     *		   array(
     * 			   			array(
     *			   				 'nombre'     => 'MiFuncion',
     *			   				 'parametros' => ',parametro1,parametro2',
     *			   				 'imagen'     => 'Boton u Obejto'
     *			   				 'segundo'    => 'numFila'
     *			   				 ),
     *			   		   array(
     *			   				 'nombre'     => 'MiFuncion',
     *			   				 'parametros' => ',parametro1,parametro2',
     *			   				 'imagen'     => 'Boton u Obejto'
     *			   				 'segundo'    => 'numFila'
     *			   				 )
     *			   		)
     *			   	Cada subArray representa un boton u Objeto. los indices de los elementos deben ser definidos tal cual	 *			    es decir con el mismo indice exactamente y representa lo siguiente:
     *		   	    - nombre: nombre d ela funcion en JS a ejecutar. Recive por defecto la primera columna del los datos.
     *			   	- parametros: parametros adicionales de dicha funcion js, precedido por ",".
     *			   	- imagen: Objeto HTML, y sobre el cual se hara click para la accion.
     *			   	- segundo: si se desea que otra columna del los datos sea colocada como parametro en la funcion JS,
     *			   	           se coloca el indice de esa columna.
     * @param int $colbotones
     * 				La columna donde va a estar el o los botones.
     * @param int $npag
     * 				El numero de la pagina que se quiere mostrar.
     * @param unknown_type $atributos
     */
     public function SetTotal($cssclass,$data,$columna_funcion,$arriba_o_abajo=1){
        $i=0;
        $this->totalposicion=$arriba_o_abajo;
        foreach($data as $fila ) {
            if ($fila[0]==-1){
                $this->totales="	<tr>\n";
                $col=0;
                foreach($fila as $key=>$value){
                    if($columna_funcion>=$col && $col>0){
                    $this->totales.="	<td  class= \"$cssclass\" ";
                    //foreach($detalle as $key=>$value){
                      //  if ($key!='ValorEtiqueta')
                        //$this->totales.=" $key = \"$value\"  ";
                        //else
                        if(is_numeric($fila[$col]))
                        {if(is_int($fila[$col]))
                            $valor=number_format($fila[$col],0,',','');
                         else
                            $valor=number_format($fila[$col],2,',','.');
                            $this->totales.="><div align=\"right\">$valor</div></td>\n";
                        }
                        else
                            $this->totales.="><div align=\"left\">".$fila[$col]."</div></td>\n";
                    }
                     $col++;
                }
                $this->totales.=" </tr>\n";
            }
        }
    }
public function setData2($cssclass, $data, $funciones=array(), $colbotones=-1,$npag=1,$atributos=null){
        $this->pagActual=$npag;     
        $reg=1;
        if ($atributos != null) $atributos = '"'. $atributos . '"';
        if ($this->color!=null)
        $select='bgcolor='."$this->color";
        if ((is_array($data)) && (count($data)>0)) {
            foreach($data as $fila ){               
                if($fila[0]!=-1){
                    $col=0;                                                    
                    if ($this->color!=null && $this->colorArray[$reg-1]==1)
                    $this->datos.="<tr $select onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'>";
                    else
                    $this->datos.="<tr $this->filaResaltado onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'>";
                    $contador = 0;
                    foreach($fila as $key=>$value){
                        if ($col == 0) $col_id = $key;                       
                        if (!is_integer($key))
                        {                                 
                            if($this->hidden[$col]==true){

                            }
                            elseif ($col==$this->hide)
                                $this->datos.="<td  $atributos style=\"display:none\" > $fila[$col] &nbsp;</td>\n";
                            else
                            {
                                if(!is_numeric($this->valorColumna($key,$fila)))
                                    $valor=$this->valorColumna($key,$fila);
                                else
                                    if(strpos($this->valorColumna($key,$fila), '.')===false)
                                            $valor=number_format($this->valorColumna($key,$fila),0,'','');
                                    else
                                        $valor=number_format($this->valorColumna($key,$fila),2,',','.');
                                $this->datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_decode($valor)."</td>\n";
                            }
                            $col++;
                        }

                    }
                    if ($colbotones > 0 ){
                        $this->datos.="	<td align=\"center\" $atributos>";
                        foreach ($funciones as $fun) {
                            if($this->diffAction==false || !($this->color!=null) || !($this->colorArray[$reg-1]==1)){
                                $n=$fun['nombre'];
                                $i=$fun['imagen'];
                                $p=$fun['parametros'];
                                $o=$fun['opcional'];
                                $condicion = isset($fun['condicion']) ? "'" . $fila[$fun['condicion']['columna']] . "'" . $fun['condicion']['valor']  : 1;
                                if (isset($fun['condicion'])){                                     
                                    eval('$condicion = ' . $condicion . ' ? 1 : 0;');                                    
                                }
                                if ($condicion == 1){
                                    if(isset($fun['segundo'])){
                                        $segundo=$fun['segundo'];                                        
                                        $this->datos.="<a onclick='javascript:$n(\"$fila[$col_id]\"". $this->ArregloParamsSegundo($fun['segundo'],$fila)."  $p);$o;'> $i</a>";

                                    }else
                                    $this->datos.="<a onclick='javascript:$n(\"$fila[$col_id]\" $p);$o;'> $i</a>";
                                }
                            }else{
                                if($this->color!=null && $this->colorArray[$reg-1]==1){
                                        $this->datos.="<a onclick='javascript:".$this->diffAction['nombre']."(\"$fila[$col_id]\");'>".$this->diffAction['imagen']." </a>";
                                        break;
                                }
                            }
                        }
                        $this->datos.="	</td>\n";
                    }
                    $this->datos.="</tr>\n";
                    if ($this->regPagina!=null && $reg==$this->regPagina*$npag){
                        break;
                    }
                    $reg++;                
                }
            }
        }else{
            $this->datos.="<tr> <td  colspan=\"200\" align=\"center\">";
            $this->datos.="NO EXISTEN REGISTROS";
            $this->datos.=" </td></tr>\n";
        }

    }
    
    public function setPagina($pag=1){
        $this->pagActual=$pag; 
    }
    
    public function setDataMSKS($cssclass, $data, $funciones=array(), $colbotones=-1,$npag=1,$atributos=null){
        $this->pagActual=$npag;     
        $reg=1;
        if ($atributos != null) $atributos = '"'. $atributos . '"';
        if ($this->color!=null)
        $select='bgcolor='."$this->color";
        if ((is_array($data)) && (count($data)>0)) {
            foreach($data as $fila ){               
                if($fila[0]!=-1){
                    $col=0;                                                    
                    if ($this->color!=null && $this->colorArray[$reg-1]==1)
                    $this->datos.="<tr $select tok=\"$fila[0]\" onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);' class=\"DatosGrilla\">";
                    else
                    $this->datos.="<tr  tok=\"$fila[0]\"  $this->filaResaltado onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);' class=\"DatosGrilla\">";
                    $contador = 0;
                    if ($colbotones >= 0 ){
                        $col_id = 0;
                        $this->datos.="	<td align=\"center\" $atributos>";
                        //print_r($funciones);
                        if (isset($funciones[funcion])){
                            if ($this->Parent == null)
                                @eval(" \$this->datos .= $funciones[funcion](\$fila);");
                            else 
                                @eval(" \$this->datos .= \$this->Parent->$funciones[funcion](\$fila);");
                        }
                        else{
                            foreach ($funciones as $fun) {
                                if($this->diffAction==false || !($this->color!=null) || !($this->colorArray[$reg-1]==1)){
                                    $n=$fun['nombre'];
                                    $i=$fun['imagen'];
                                    $p=$fun['parametros'];
                                    $o=$fun['opcional'];
                                    $condicion = isset($fun['condicion']) ? "'" . $fila[$fun['condicion']['columna']] . "'" . $fun['condicion']['valor']  : 1;
                                    if (isset($fun['condicion'])){                                     
                                        eval('$condicion = ' . $condicion . ' ? 1 : 0;');                                    
                                    }
                                    if ($condicion == 1){
                                        if(isset($fun['segundo'])){
                                            $segundo=$fun['segundo'];                                        
                                            $this->datos.="<a onclick='javascript:$n(\"$fila[$col_id]\"". $this->ArregloParamsSegundo($fun['segundo'],$fila)."  $p);$o;'> $i</a>";

                                        }else
                                        $this->datos.="<a onclick='javascript:$n(\"$fila[$col_id]\" $p);$o;'> $i</a>";
                                    }
                                }else{
                                    if($this->color!=null && $this->colorArray[$reg-1]==1){
                                            $this->datos.="<a onclick='javascript:".$this->diffAction['nombre']."(\"$fila[$col_id]\");'>".$this->diffAction['imagen']." </a>";
                                            break;
                                    }
                                }
                            }
                        }
                        $this->datos.="	</td>\n";
                    }
                    
                    foreach($fila as $key=>$value){
                        if ($col == 0) $col_id = $key;                       
                        if (!is_integer($key))
                        {                                 
                            if($this->hidden[$col]==true){
                                //echo $col . ' ';
                            }
                            elseif ($col==$this->hide)
                                $this->datos.="<td  $atributos style=\"display:none\" > $fila[$col] &nbsp;</td>\n";
                            else
                            {
                                if(!is_numeric($this->valorColumna($key,$fila)))
                                    $valor=$this->valorColumna($key,$fila);
                                else
                                    if(strpos($this->valorColumna($key,$fila), '.')===false)
                                            $valor=number_format($this->valorColumna($key,$fila),0,'','');
                                    else
                                        $valor=number_format($this->valorColumna($key,$fila),2,',','.');
                                $this->datos.="<td $atributos align='" . $this->aligns[$col] . "'>". utf8_decode($valor)."</td>\n";
                            }
                            $col++;
                        }

                    }
                    
                    $this->datos.="</tr>\n";
                    if ($this->regPagina!=null && $reg==$this->regPagina*$npag){
                        break;
                    }
                    $reg++;                
                }
            }
        }else{
            $this->datos.="<tr> <td  colspan=\"200\" align=\"center\">";
            $this->datos.="NO EXISTEN REGISTROS";
            $this->datos.=" </td></tr>\n";
        }

    }

    public function setData($cssclass, $data, $funciones=array(), $colbotones=-1,$npag=1,$atributos=null){
        $this->pagActual=$npag;
        $reg=1;
        if ($this->color!=null)
        $select='bgcolor='."$this->color";
        if ((is_array($data)) && (count($data)>0)) {
            foreach($data as $fila ){               
                if($fila[0]!=-1){
                    $col=0;  
                    if (($reg % 2))
                    $this->datos.="<tbody id=\"body_1_$reg\"><tr $select onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'>";
                    else
                    $this->datos.="<tbody id=\"body_1_$reg\"><tr $this->filaResaltado onmouseover=\"TRMarkOver(this);\" onmouseout='TRMarkOut(this);'>";
                    $contador = 0;
                    foreach($fila as $key=>$value){
                        if ($col == 0) $col_id = $key;                       
                        {                                 
                            if($this->hidden[$col]==true){

                            }
                            elseif ($col==$this->hide)
                                $this->datos.="<td  $atributos style=\"display:none\" > $fila[$col] &nbsp;</td>\n";
                            else
                            {
                                if(!is_numeric($this->valorColumnaFilaActual($key,$fila, $reg)))
                                    $valor=$this->valorColumnaFilaActual($key,$fila, $reg);
                                else
                                    if(strpos($this->valorColumnaFilaActual($key,$fila, $reg), '.')===false)
                                            $valor=number_format($this->valorColumnaFilaActual($key,$fila, $reg),0,'','');
                                    else
                                        $valor=number_format($this->valorColumnaFilaActual($key,$fila, $reg),2,',','.');
                                $this->datos.="<td \" $atributos\" align=" . $this->aligns[$col] . ">". utf8_decode($valor)."</td>\n";
                            }
                            $col++;
                        }

                    }
                    if ($colbotones > 0 ){
                        $this->datos.="	<td align=\"center\" $atributos>";
                        foreach ($funciones as $fun) {
                            if($this->diffAction==false || !($this->color!=null) || !($this->colorArray[$reg-1]==1)){
                                $n=$fun['nombre'];
                                $i=$fun['imagen'];
                                $p=$fun['parametros'];
                                $o=$fun['opcional'];
                                $condicion = isset($fun['condicion']) ? "'" . $fila[$fun['condicion']['columna']] . "'" . $fun['condicion']['valor']  : 1;
                                if (isset($fun['condicion'])){                                               
                                    eval('$condicion = ' . $condicion . ' ? 1 : 0;');                                    
                                }
                                if ($condicion == 1){
                                    if(isset($fun['segundo'])){
                                        $segundo=$fun['segundo'];                                        
                                        $this->datos.="<a onclick='javascript:$n(\"$fila[$col_id]\"". $this->ArregloParamsSegundo($fun['segundo'],$fila)."  $p);$o;'> $i</a>";

                                    }else
                                    $this->datos.="<a onclick='javascript:$n($fila[$col_id] $p);$o;'> $i</a>";
                                }
                            }else{
                                if($this->color!=null && $this->colorArray[$reg-1]==1){
                                        $this->datos.="<a onclick='javascript:".$this->diffAction['nombre']."(\"$fila[$col_id]\");'>".$this->diffAction['imagen']." </a>";
                                        break;
                                }
                            }
                        }
                        $this->datos.="	</td>\n";
                    }
                    $this->datos.="</tr></tbody>\n";
                    if ($this->regPagina!=null && $reg==$this->regPagina*$npag){
                        break;
                    }
                                    
                }
                $reg++;
            }
        }else{
            $this->datos.="<tr> <td  colspan=\"200\" align=\"center\">";
            $this->datos.="NO EXISTEN REGISTROS";
            $this->datos.=" </td></tr>\n";
        }

    }

    /**
     * Arma el codigo HTML del paginado.
     * @param String $nombre
     * 			El nombre de la funcion de ajax, que reeecribe el grid, por defecto recibe la pagina destino.
     * @param String $para
     * 			Algun otro parametro que se necesite para dibujar el grid, y sea usado por la funcion de ajax.
     * @return String
     *
     */

    public function setPaginadohtml($nombre,$para){
        $nroPag=$this->getNumeroPag();
        //$nroPag=intval($this->getNumeroPag());
//        For ($i=1; $i<$nroPag; $i++ ){
//            if($i==$this->pagActual){
//                $sub="<u>$i</u>";
//            }else{
//                $sub=$i;
//            }
//            $linkpages.="&nbsp; &nbsp;
//                <a style=\"cursor:hand;\" onclick=\"$nombre($i,$para);\">$sub</a>";
//        }
        $html_pag_actual = '<input type="hidden" name="pag_actual" id="pag_actual" value="'. $this->pagActual. '"/><select id="ir_a" name="ir_a" >';
        For ($i=1; $i<=$nroPag; $i++ ){
            if($i==$this->pagActual){
                $html_pag_actual.="<option value='$i' selected>$i</option>";
            }else{
                $html_pag_actual.="<option value='$i'>$i</option>";
            }
        }
        $html_pag_actual .= '</select>';
//        $linkpages = '
//        <div class="paginacion" style="position: relative; width: 100%;" id="paginacion">
//            <table class="form-scaffold-footer">
//                <tbody><tr>
//                    <td width="55%" class="div_pag">
//                        <label>
//                            P&aacute;gina ' . $this->pagActual . ' de ' . $nroPag . '
//                        </label>
//                    </td>';
        $linkpages = '<div id="pager" class="fieldset-content">
                        <div class="page-number">
                            <span>P&aacute;gina ' . $this->pagActual . '/' . $nroPag . '</span>
                        </div>
                        <div class="page-control">';
        if($this->pagActual == 1){
//            $linkpages .= '
//                    <td width="5%" class="div_pag">
//                                <img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/primero_des.png" alt="Primero_des">
//                    </td>
//                    <td width="5%" class="div_pag">
//                                <img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/anterior_des.png" alt="Anterior_des">
//                    </td>';
              //$linkpages .= '<img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'botones/first_over.png" alt="Primero_des">';
              //$linkpages .= '<img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'botones/prev_over.png" alt="Primero_des">';
              //$linkpages .= '<img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'botones/first_over.png" alt="Primero_des">';
              //$linkpages .= '<img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'botones/prev_over.png" alt="Primero_des">';
              $linkpages .= '<input type="button" class="page-button first_over"/>
                             <input type="button" class="page-button prev_over"/>';
        }
        else
        {
//            $linkpages .= '
//                    <td width="5%" class="div_pag">
//                            <a title="Primera P&aacute;gina" onclick="'. $nombre . '(' . 1 .  ','. $para . ');" href="#paginacion"><img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/primero.png" alt="Primero"></a>
//                    </td>
//                    <td width="5%" class="div_pag">
//                            <a title="P&aacute;gina Anterior" onclick="'. $nombre . '(' . ($this->pagActual - 1) .  ','. $para . ');" href="#paginacion"><img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/anterior.png" alt="Anterior"></a>
//                    </td>';
               $linkpages .= '<input type="button" class="page-button first" onclick="'. $nombre . '(' . 1 .  ','. $para . ');"/>
                              <input type="button" class="page-button prev"onclick="'. $nombre . '(' . ($this->pagActual - 1) .  ','. $para . ');" />';
        }

        if($this->pagActual == $nroPag){
//            $linkpages .= '
//                    <td width="5%" class="div_pag">
//                                <img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/siguiente_des.png" alt="Primero_des">
//                    </td>
//                    <td width="5%" class="div_pag">
//                                <img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/ultimo_des.png" alt="Anterior_des">
//                    </td>';
              //$linkpages .= '<img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'botones/next_over.png" alt="Primero_des">';
              //$linkpages .= '<img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'botones/last_over.png" alt="Primero_des">';
              $linkpages .= '<input type="button" class="page-button next_over"/>
                             <input type="button" class="page-button last_over"/>';
        }
        else{
//            $linkpages .= '
//                    <td width="5%" class="div_pag">
//                            <a title="Siguiente P&aacute;gina" onclick="'. $nombre . '(' . ($this->pagActual + 1) .  ','. $para . ');" href="#paginacion"><img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/siguiente.png" alt="Siguiente"></a>
//                    </td>
//                    <td width="5%" class="div_pag">
//                            <a title="&Uacute;ltima P&aacute;gina" onclick="'. $nombre . '(' . $nroPag .  ','. $para . ');" href="#paginacion"><img border="0" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/ultimo.png" alt="Ultimo"></a>
//                    </td>';
               $linkpages .= '<input type="button" class="page-button next" onclick="'. $nombre . '(' . ($this->pagActual + 1) .  ','. $para . ');"/>
                              <input type="button" class="page-button last" onclick="'. $nombre . '(' . $nroPag .  ','. $para . ');"/>';
        }
//        $linkpages .= '
//                    <td width="15%" class="div_pag">
//                        <label class="ir-a-pagina">
//                            Ir a la p&aacute;gina:
//                        </label>
//                    </td>
//                    <td width="5%" class="div_pag">
//                    ' . $html_pag_actual . '
//                    </td>
//                    <td width="5%" align="left" class="div_pag" id="link_ir_a">
//                        &nbsp;<img border="0" onclick="'. $nombre . '($(\'#ir_a\').val()' . ','. $para . ');" align="absmiddle" src="' . PATH_TO_IMG . 'paginado/aplicar.png" alt="Aplicar">
//                    </td>
//                </tr>
//            </tbody></table>
//        </div>
            $linkpages .= '</div><div class="page-select">
                            ' . $html_pag_actual . '
                            <input type="button" class="page-button go" onclick="'. $nombre . '($(\'#ir_a\').val()' . ','. $para . ');" />
                            </div></div>';

        return  $linkpages;
    }
    
        /**
     * Arma el codigo HTML del paginado.
     * @param String $nombre
     * 			El nombre de la funcion de ajax, que reeecribe el grid, por defecto recibe la pagina destino.
     * @param String $para
     * 			Algun otro parametro que se necesite para dibujar el grid, y sea usado por la funcion de ajax.
     * @return String
     *
     */

    public function setPaginadohtmlMSKS($nombre,$para,$nombre_pag_actual='pag_actual',$nombre_r_x_pag='reg_por_pag'){
        $nroPag=$this->getNumeroPag();
        //$html_pag_actual = '<input type="hidden" name="pag_actual" id="'.$nombre_pag_actual.'" value="'. $this->pagActual. '"/><select id="ir_a" name="ir_a" >';
        $html_pag_actual = '<input type="hidden" name="pag_actual" id="'.$nombre_pag_actual.'" value="'. $this->pagActual. '"/>';
        For ($i=1; $i<=$nroPag; $i++ ){
            if($i==$this->pagActual){
                //<li><a href="#">1</a></li>
                //$html_pag_actual.="<option value='$i' selected>$i</option>";
                $html_pag_actual .= "<li><a style=\"background-color:#455a64;color:#fafafa;border-color:#cfd8dc;\" href=\"#grid-paginado\">$i</a></li>";
            }else{
                //$html_pag_actual.="<option value='$i'>$i</option>";
                $html_pag_actual .= "<li><a href=\"#grid\"  onclick=\"". $nombre . '(' . $i .  ','. $para . ");\">$i</a></li>";
            }
        }
        //$html_pag_actual .= '</select>';
        $linkpages ="<div class=\"col-xs-5\">Total: " . $this->total_reg . " Items</div>";
        $reg_x_pag = array(10,15,20,25,50);
        $linkpages .= '<div class="col-xs-6"><div class="row">
                                  <div class="col-xs-7">                                    
                                    <select class="form-control" name="reg_por_pag" id="'.$nombre_r_x_pag.'" onchange="'. $nombre . '(' . 1 .  ','. $para . ');" style="padding-left: 3px; padding-right: 3px;">';
        for($i=0;$i<count($reg_x_pag);$i++){
            $selected = ($reg_x_pag[$i]==$this->regPagina ? "selected" : "");
            $linkpages .= "<option $selected value=\"$reg_x_pag[$i]\">$reg_x_pag[$i]</option>";
        }
        $linkpages .= '             </select>
                                  </div>
                                  <label class="col-xs-17">Registros por Página</label>
                        </div></div>';
//        $linkpages = '<div id="pager" class="fieldset-content">
//                        <div class="page-number DatosGrilla"  style="font-size: 12px;">
//                            <span>P&aacute;gina ' . $this->pagActual . '/' . $nroPag . '</span> &nbsp; <span>Total ' . $this->total_reg . ' Registros </span>
//                        </div>
//                        <div class="page-control">';
        $linkpages .= '<div class="col-xs-12">
                                <ul class="pagination">
                                  <li>
                                    <a href="#grid" onclick="'. $nombre . '(' . 1 .  ','. $para . ');" aria-label="Previous">
                                      <span aria-hidden="true">&laquo;</span>
                                    </a>
                                  </li>';
        $linkpages .= $html_pag_actual;
        
        $linkpages .= '<li>
                                    <a href="#grid"  onclick="'. $nombre . '(' . $nroPag .  ','. $para . ');" aria-label="Next">
                                      <span aria-hidden="true">&raquo;</span>
                                    </a>
                                  </li>
                                </ul>
                              </div>';
//        if($this->pagActual == 1){
//              $linkpages .= '<input type="button" class="page-button first_over"/>
//                             <input type="button" class="page-button prev_over"/>';
//        }
//        else
//        {
//               $linkpages .= '<input type="button" class="page-button first" onclick="'. $nombre . '(' . 1 .  ','. $para . ');"/>
//                              <input type="button" class="page-button prev"onclick="'. $nombre . '(' . ($this->pagActual - 1) .  ','. $para . ');" />';
//        }
//
//        if($this->pagActual == $nroPag){
//              $linkpages .= '<input type="button" class="page-button next_over"/>
//                             <input type="button" class="page-button last_over"/>';
//        }
//        else{
//               $linkpages .= '<input type="button" class="page-button next" onclick="'. $nombre . '(' . ($this->pagActual + 1) .  ','. $para . ');"/>
//                              <input type="button" class="page-button last" onclick="'. $nombre . '(' . $nroPag .  ','. $para . ');"/>';
//        }
//            $linkpages .= '</div><div class="page-select DatosGrilla" style="font-size: 12px;">
//                            Ir a:' . $html_pag_actual . '
//                                
//                            <input type="button" class="page-button go" onclick="'. $nombre . '($(\'#ir_a\').val()' . ','. $para . ');" />&nbsp;&nbsp;
//                            </div></div>';

        return  $linkpages;
    }
    
    public function setPaginadohtmlMSKS_OLD($nombre,$para,$nombre_pag_actual='pag_actual'){
        $nroPag=$this->getNumeroPag();
        $html_pag_actual = '<input type="hidden" name="pag_actual" id="'.$nombre_pag_actual.'" value="'. $this->pagActual. '"/><select id="ir_a" name="ir_a" >';
        For ($i=1; $i<=$nroPag; $i++ ){
            if($i==$this->pagActual){
                $html_pag_actual.="<option value='$i' selected>$i</option>";
            }else{
                $html_pag_actual.="<option value='$i'>$i</option>";
            }
        }
        $html_pag_actual .= '</select>';
        $linkpages = '<div id="pager" class="fieldset-content">
                        <div class="page-number DatosGrilla"  style="font-size: 12px;">
                            <span>P&aacute;gina ' . $this->pagActual . '/' . $nroPag . '</span> &nbsp; <span>Total ' . $this->total_reg . ' Registros </span>
                        </div>
                        <div class="page-control">';
        if($this->pagActual == 1){
              $linkpages .= '<input type="button" class="page-button first_over"/>
                             <input type="button" class="page-button prev_over"/>';
        }
        else
        {
               $linkpages .= '<input type="button" class="page-button first" onclick="'. $nombre . '(' . 1 .  ','. $para . ');"/>
                              <input type="button" class="page-button prev"onclick="'. $nombre . '(' . ($this->pagActual - 1) .  ','. $para . ');" />';
        }

        if($this->pagActual == $nroPag){
              $linkpages .= '<input type="button" class="page-button next_over"/>
                             <input type="button" class="page-button last_over"/>';
        }
        else{
               $linkpages .= '<input type="button" class="page-button next" onclick="'. $nombre . '(' . ($this->pagActual + 1) .  ','. $para . ');"/>
                              <input type="button" class="page-button last" onclick="'. $nombre . '(' . $nroPag .  ','. $para . ');"/>';
        }
            $linkpages .= '</div><div class="page-select DatosGrilla" style="font-size: 12px;">
                            Ir a:' . $html_pag_actual . '
                                
                            <input type="button" class="page-button go" onclick="'. $nombre . '($(\'#ir_a\').val()' . ','. $para . ');" />&nbsp;&nbsp;
                            </div></div>';

        return  $linkpages;
    }

    /**
     * Define si una columna del data grid se va a ocular. es usada en la mayoria de los casos cuando no se quiera
     *  mostrar la columna de id o alguna otra de una tabla.
     *
     * @param unknown_type $i
     * 			Numero de la columna a ocultar
     */
    public function setHide($i){
        $this->hide=$i;
    }

    /**
     * Enter description here...
     *
     * @param int $encode
     * 			define si es codificada a UTF-8 o no. si recibe 1 no codifica, si es llamada sin parametros codifica.
     * @return String
     * 			codigo HTML de la pagina a ser mostrada.
     */
    public function armarTabla($encode=null,$contota=null){
        if ($contota)
            if ($this->totalposicion==1)
                $this->tabla=$this->tituloTabla.$this->configuracion.$this->titulosColumna.$this->datos.$this->totales." </table>\n";
            else
                $this->tabla=$this->tituloTabla.$this->configuracion.$this->totales.$this->titulosColumna.$this->datos." </table>\n";
        else
            $this->tabla=$this->tituloTabla.$this->configuracion.$this->titulosColumna.$this->datos." </table>\n";
        if($encode==null)
        return $this->funResalatdo.utf8_encode($this->tabla);
        else
        return $this->funResalatdo.$this->tabla;

    }

    public function SimpleGrid($columnas, $datos, $indices, $titulo_datagrid = "RESULTADOS"){
        if (count($datos) > 0){
            $strHtml2.= "<table align ='center' border='0' cellspacing='0' cellpadding='0' width = '100%'>" ;
            $strHtml2.= "<tr>";
            $strHtml2.= "<td class='td-titulo-tabla-row' align='center' colspan = '7'>$titulo_datagrid</td>";
            $strHtml2.= "</tr>";
            $strHtml2.= "<tr>" ;
            foreach ($columnas as $campo => $valor )
            {
                $strHtml2.= "<td class='td-titulo-tabla-row' align='center' nowrap >$valor</td>" ;
            }
            $strHtml2.= "</tr>";
            $i = 0;
            while ($i<count($datos))
            {
                $strHtml2.="<tr>";
                foreach ($indices as $indice => $valor)
                {
                    $strHtml2 .="<td  colspan='1' align='left'class='td-table-data' nowrap>".$datos[$i][$valor]."</td>";
                }
                $i++;
            }
            $strHtml2.="</tr>";
            $strHtml2.= "</table>";
        }
        else
        {
            $strHtml2.= "<table align ='center' border='0' cellspacing='4' cellpadding='0' width='99% id='mainTableone'>";
            $strHtml2.= "<tr>";
            $strHtml2.="<td colspan='2' class='td-titulo-tabla-row'  align='center' width='99%'>No hay resultados<input name='mensaje_oculto' type='hidden' value='No hay resultados' /></td>";
            $strHtml2.= "</tr>";
            $strHtml2.= "</table>";
        }

        return $strHtml2;

    }

    public function SimpleGridNum($columnas, $datos, $indices, $titulo_datagrid = "RESULTADOS")
    {
        if (count($datos) > 0)
        {
            $numc = count($columnas);

            $strHtml2.= "<table align ='center' border='0' cellspacing='0' cellpadding='0' width = '100%'>" ;
            $strHtml2.= "<tr>";
            $strHtml2.= "<td class='td-titulo-tabla-row' align='center' colspan ='".++$numc."'>$titulo_datagrid</td>";
            $strHtml2.= "</tr>";
            $strHtml2.= "<tr>" ;
            $strHtml2.= "<td class='td-titulo-tabla-row' align='center' nowrap >Numero</td>" ;
            foreach ($columnas as $campo => $valor )
            {
                $strHtml2.= "<td class='td-titulo-tabla-row' align='center' nowrap >$valor</td>" ;
            }
            $strHtml2.= "</tr>";
            $i = 0;
            $j = 1;
            while ($i<count($datos))
            {
                $strHtml2.="<tr>";
                $strHtml2 .="<td  colspan='1' align='left'class='td-table-data' nowrap>".$j++."</td>";

                foreach ($indices as $indice => $valor)
                {
                    $strHtml2 .="<td  colspan='1' align='left'class='td-table-data' nowrap>".$datos[$i][$valor]."</td>";
                }
                $i++;
            }
            $strHtml2.="</tr>";
            $strHtml2.= "</table>";
        }
        else
        {
            $strHtml2.= "<table align ='center' border='0' cellspacing='4' cellpadding='0' width='99% id='mainTableone'>";
            $strHtml2.= "<tr>";
            $strHtml2.="<td colspan='2' class='td-titulo-tabla-row'  align='center' width='99%'>No hay resultados<input name='mensaje_oculto' type='hidden' value='No hay resultados' /></td>";
            $strHtml2.= "</tr>";
            $strHtml2.= "</table>";
        }

        return $strHtml2;

    }
    public function ArregloParamsSegundo($vector,$fila){
       $i=0;
       $cad ='';
//       while ($i<count($vector)) 
//           {
//           $cad .=',"'.$fila[$vector[$i]].'"';
//           $i++;
//           }
           
        foreach ($vector as $value) {
            $cad .=',"'.utf8_decode($fila[$value]).'"';
        }
        return $cad;
    }


}

?>