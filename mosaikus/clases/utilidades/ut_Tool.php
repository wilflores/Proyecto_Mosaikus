<?php
//require("class.phpmailer.php");
//$bd = new ut_Database();
// $bd->conectar();



class ut_Tool {

	//**********************************************atributos


	//********************************************* metodos de tools

	//--------------------------------- operaciones con db


	///////////// Funcion que construye una estructura tipo registro c++ con todas las tuplas
	/*  recibe una objeto result recordset
	/*  las tuplas resultantes de la consulta solo recibe la consulta sql para generar la
	/*  estructura esta se trabaja como si fuera un arreglo asociativo con n elementos
	/*  numero de registros, devuelve una estructura manipulable */


	//devuelve un arreglo con el nombre de los campos consultados enpezando desde la pos 0

	private function campos_query($RESULT){

		//  $rowcount=mysql_num_rows($this->result);
		$y = mssql_num_fields($RESULT);

		for ($x=0; $x<$y; $x++) $resultado[$x] = (string) mssql_field_name ($RESULT, $x);

		return $resultado;

	}





	function estructura_db($RESULT){

		$campos = $this->campos_query($RESULT);

		///////////////
		$i=0;

		while ($row = mssql_fetch_row($RESULT)) {    //N de registros

			for($j=0;$j<count($campos);$j++){   ////N campos

				$a[$i][$campos[$j]] = stripslashes($row[$j]);

			}

			$i++;
		}

		return $a;

	}

////////////////////////////////////////////////////////////////////
/*///PARA GENERAR UN COMBO
$idInput: nombre del combo
$strSQL: query o sentencia sql
$ids: nombre del campo del sql que contiene el id que va en el value del combo
$descs:nombre del campo del sql que contiene la descripcion que va en el innerHTML del combo
$seleccionado: value con el cual quieres que se seleccione el combo
*/

function StrCombo($idInput,$strSQL,$ids,$descs,$seleccionado,$param,$valor)
{
	global $bd;
	$sql = array();
	$sql["".$param.""]=$valor;
	$strBuf = "";
	$i = 0;
	$bd->exe($strSQL,$sql);
	$strBuf = "<SELECT class='form-box' id='".$idInput ."' name='" . $idInput . "'>\n";
	while ($i < $bd->nreg) {
		$strBuf .= "	<OPTION value='" . $bd->data[$i][$ids] . "'";
		if ($bd->data[$i][$ids] == $seleccionado) $strBuf .= " SELECTED ";
		$strBuf .= ">" . $bd->data[$i][$descs] . "</OPTION>";
		$i = $i + 1;
	}
	$strBuf .= "</SELECT>";
	return $strBuf;
}


function OptionsCombo($strSQL,$ids,$descs,$seleccionado=null,$parametros=array())
{
        include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/PostgreSQL.php');
        $encryt = new EnDecryptText();
        $bd = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );       
	$i = 0;        
	$bd->data = $bd->query($strSQL,$parametros);
        $datos2 = explode("-",$descs);
        $descripcion = "";
        if (count($datos2)>0){
            $descripcion = ' . ($bd->data[$i]["' . $datos2[0] . '"]) ';
            for($j=1;$j<count($datos2);$j++){
                $descripcion .= '. " - " . ($bd->data[$i]["' . $datos2[$j] . '"]) ';
            }
            $descripcion = '$strBuf .= ">" ' . utf8_decode($descripcion) . ' . "</OPTION>";';
        }
        else
            $descripcion = '$strBuf .= ">" ($bd->data[$i]["' . $descs  . '"]) . "</OPTION>";';        
	while ($i < count($bd->data)) {
		$strBuf .= "	<OPTION value='" . ($bd->data[$i][$ids]) . "'";
                if (is_array($seleccionado) && in_array($bd->data[$i][$ids], $seleccionado)) {
                    $strBuf .= " SELECTED ";
                }
		if ($bd->data[$i][$ids] == $seleccionado) 
                    $strBuf .= " SELECTED ";
                eval($descripcion);
		$i = $i + 1;
	}
	return $strBuf;
}
function OptionsComboMultiple($strSQL,$ids,$descs,$seleccionado=null,$parametros=array())
{
        include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/SQLServer.php');
        $encryt = new EnDecryptText();
        $bd = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );       
        //$bd->conectar();
	$i = 0;
	//$bd->exe($strSQL,$parametros);
        //echo $strSQL;
        $resultado = $bd->query($strSQL);
        //print_r($resultado);
        $datos2 = split("-",$descs);
        $descripcion = "";
        if (count($datos2)>0){
            $descripcion = ' . ($resultado[$i]["' . $datos2[0] . '"]) ';
            for($j=1;$j<count($datos2);$j++){
                $descripcion .= '. " - " . ($resultado[$i]["' . $datos2[$j] . '"]) ';
            }
            $descripcion = '$strBuf .= ">" ' . $descripcion . ' . "</OPTION>";';
        }
        else
            $descripcion = '$strBuf .= ">" ($resultado[$i]["' . $descs  . '"]) . "</OPTION>";';
	while ($i < count($resultado)) {
		$strBuf .= "	<OPTION value='" . utf8_encode($resultado[$i][$ids]) . "'";
		if ($resultado[$i][$ids] == $resultado[$i][$seleccionado])
                    $strBuf .= " SELECTED ";
                //echo $descripcion;
                eval($descripcion);
		$i = $i + 1;
	}
       // echo $strBuf;
	return $strBuf;
}

function checkboxlist($strSQL,$nombre, $ids,$descs,$seleccionado=array(),$parametros=array(),$separador="&nbsp;")
{
        include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/PostgreSQL.php');
        $bd = new Mysql();       
	$i = 0;        
	$bd->data = $bd->query($strSQL,$parametros);
	while ($i < count($bd->data)) {
//                echo $bd->data[$i][$ids] . ' ';
//                print_r($seleccionado);
                if (in_array($bd->data[$i][$ids], $seleccionado)) {
                    $checked = 'checked="checked"';
                }
                else $checked = '';
                $strBuf .= "<input id=\"Reporte_tipo_lesion_0\" type=\"checkbox\" name=\"". $nombre. "[]\" value=\"" . ($bd->data[$i][$ids]) . "\" style=\"width: 15px;\" $checked/>
                            <span>" . ($bd->data[$i]["$descs"]) . "</span>$separador";
		$i = $i + 1;
	}
	return $strBuf;
}

function lista($strSQL,$ids,$descs,$seleccionado=array(),$parametros=array(),$separador="&nbsp;")
{
        //include_once(dirname(dirname(dirname(__FILE__))).'/clases/bd/PostgreSQL.php');
        $bd = new Mysql();       
	$i = 0;        
	$bd->data = $bd->query($strSQL,$parametros);
	while ($i < count($bd->data)) {
//                echo $bd->data[$i][$ids] . ' ';
//                print_r($seleccionado);
                if (in_array($bd->data[$i][$ids], $seleccionado)) 
                        $strBuf .= "<span>" . ($bd->data[$i]["$descs"]) . "</span>$separador";
//                    $checked = 'checked="checked"';
//                }
//                else $checked = '';
                //$strBuf .= "<input id=\"Reporte_tipo_lesion_0\" type=\"checkbox\" name=\"". $nombre. "[]\" value=\"" . ($bd->data[$i][$ids]) . "\" style=\"width: 15px;\" $checked/>
                
		$i = $i + 1;
	}
	return $strBuf;
}

function listaArray($ids,$descs,$seleccionado=array(),$parametros=array(),$separador="&nbsp;")
{             
	$i = 0;    
        $strBuf = "";
	while ($i < count($ids)) {
            
                if (in_array($ids[$i], $seleccionado)) 
                        $strBuf .= "<span>" . ($descs[$i]) . "</span>$separador";                      
                
//                    $checked = 'checked="checked"';
//                }
//                else $checked = '';
                //$strBuf .= "<input id=\"Reporte_tipo_lesion_0\" type=\"checkbox\" name=\"". $nombre. "[]\" value=\"" . ($ids[$i]) . "\" style=\"width: 15px;\" $checked/>                
		$i = $i + 1;
	}
	return $strBuf;
}

function checkboxlistArray($nombre, $ids,$descs,$seleccionado=array(),$parametros=array(),$separador="&nbsp;")
{             
	$i = 0;    
        $strBuf = "";
	while ($i < count($ids)) {
                if (in_array($ids[$i], $seleccionado)) {
                    $checked = 'checked="checked"';
                }
                else $checked = '';
                $strBuf .= "<input id=\"$nombre"."_$i\" type=\"checkbox\" name=\"". $nombre. "[]\" value=\"" . ($ids[$i]) . "\" style=\"width: 15px;\" $checked/>
                            <span>" . ($descs[$i]) . "</span>$separador";
		$i = $i + 1;
	}
	return $strBuf;
}

//$strBuf .= ">" . utf8_encode($bd->data[$i][$descs]) . "</OPTION>";
//echo $descripcion;


	///////////// Funcion que construye una fila de datos dentro de un vector asociativo
	/*  las tuplas resultantes de la consulta solo recibe la consulta sql para generar la
	/*  estructura esta se trabaja como si fuera un arreglo asociativo con n elementos
	/*  numero de registros, y solo 1 fila  NOTA: en el caso de un solo valor se puede utilizar el nombre de arreglo sin indice*/

	function simple_db($RESULT){

		$campos = $this->campos_query($RESULT);
		$row = mssql_fetch_row($RESULT);

		if(count($campos)<=1){

			$a = stripslashes($row[0]);

		}else{

			for($j=0;$j<count($campos);$j++) $a[$campos[$j]] = stripslashes($row[$j]);

		}

		return $a;

	}






	/////////////////funcion que crea un objeto combo a partir de una sentencia sql
	//// parametros: 1: id/nombre, 2: recorset, 3:opcion(campos del query) 4: valor (campos del query)
	/// opcionales: 5: primer valor por defecto (seleccionar ejemplo: cualquiera o seleccione),
	//// 6: seleccion (valor de la variable a seleccionar $id, variable),
	//// 7: on submit();
	//// 8. en caso de no conseguir ningun registro
	//// 9. si se desea seleccion multiple
	//// 10. desabilitar combo true o false

	function combo_db ($id, $result, $option, $value, $select=false,$seleccion=false,$onchange=false,$noreg='',$multiple=false,$desabilita=false,$estilo=false){

		$nreg = mssql_num_rows($result);

		if($nreg>0){

			$combo = '<select class="form-box" name="'.$id.'" id="'.$id.'"';
			if($estilo) $combo.=' style="'.$estilo.'"';
			if($onchange)$combo.=' onChange="'.$onchange.'"';
			if($multiple)$combo.=' multiple size=4 ';
			if($desabilita)$combo.=' disabled="disabled"';

			$combo.= '>';
			if($select) $combo.= '<option value="">'.$select.'</option>';
			if(!$seleccion)$seleccion = $_REQUEST[$id];

			while ($row = mssql_fetch_assoc($result)) {
				$combo.= '<option value="';
				$combo.= stripslashes($row["$value"]);
				$combo.= '"';
				if($seleccion == $row["$value"]) $combo.= ' selected';
				$combo.= '>';
				$combo.= $row["$option"];
				$combo.= '</option>';
			}

			$combo.= '</select>';

		}else{

			$combo = '<b>'.$noreg.'</b>';
		}


		return $combo;

	}



	///// combo array, construye un comobo select a partir de un vector


	/////////////////funcion que crea un objeto combo a partir de una sentencia sql
	//// parametros: 1: id/nombre, 2: arreglo (option), 3:array 2 (value)
	/// opcionales: 4: primer valor por defecto (seleccionar ejemplo: cualquiera o seleccione),
	//// 5: seleccion (valor de la variable a seleccionar $id, variable),
	//// 6: on submit();
	//// 7. en caso de no conseguir ningun registro
	//// 8. si se desea seleccion multiple
	//// 9. desabilitar combo true o false

function combo_hora($id,$select=false,$seleccion=false,$onchange=false,$noreg='',$multiple=false,$desabilita=false)
{
for($i=0;$i<=23;$i++)
   { if($i<10)
       $array[$i]="0".$i.":00";
	 else
	   $array[$i]=$i.":00";

   }

return $this->combo_array ($id,$array,$array,$select,$seleccion,$onchange,$noreg,$multiple,$desabilita);
}


	function combo_array ($id, $array, $array2, $validate=' ', $seleccion=false,$onchange=false,$noreg='',$multiple=false,$desabilita=false,$estilo=false){

		if(count($array)>0){

			$combo = '<select class="form-control" name="'.$id.'" id="'.$id.'"';
			if($estilo) $combo.=' style="'.$estilo.'"';
			if($onchange)$combo.=' onChange="'.$onchange.'"';
			if($multiple)$combo.=' multiple size = "'.(count($array)/2+1).'" ';
			if($desabilita)$combo.=' disabled';
			
			if($validate) $combo.= $validate . ' ';
                        $combo.= '>';
			if(!$seleccion)$seleccion = $_REQUEST[$id];
			$i=0;

			while($i<count($array)) {
				$combo.= '<option value="';
				$combo.= $array2[$i];
				$combo.= '"';
                                //echo $seleccion . '==' .  $array2[$i];
				if($seleccion == $array2[$i]) $combo.= ' selected';
				$combo.= '>';
				$combo.= $array[$i];
				$combo.= '</option>';
				$i++;
			}

			$combo.= '</select>';

		}else{

			$combo = '<b>'.$noreg.'</b>';
		}

		return $combo;

	}



	/// construye un vector simple (1 campo n registros) a partir de un recorset

	function array_query($RESULT){
      if(is_resource($RESULT)==1)
	   mssql_data_seek ($RESULT,0);

		$i=0;
		while ($row = @mssql_fetch_row($RESULT)){
			$vector[$i] =  stripslashes($row[0]);
			$i++;
		}

		return $vector;
	}




	/// construye un vector simple ((1 registro n campos) a partir de un RECORSET

	function array_query2($RESULT){

		$i=0;
		$campos = $this->campos_query($RESULT);
		$row = @mssql_fetch_row($RESULT);   ///se trae el primer registro

		while ($i < count($campos)){
			$vector[$i] =  stripslashes($row[$i]);
			$i++;
		}


		return $vector;
	}







	//------------------------------------- operaciones sobre arrays





	///////////////// encuentra la posicion en donde se encuentra el elemento en el vector
	//////////////// parametros 1: elemento a buscar, 2: vector en donde busca

	function seencuentra($buscar,$en){
		$i=0;
		$pos=false;
		while(($i<count($en))and($pos==false)){
			if($buscar==$en[$i]){
				$pos = true;
				break;
			}
			$i++;
		}
		if($pos) return $i; else return false;
	}



	//////////////////metodos para ARREGLOS

	///limpia de un vector el elemento deseado, devuelve todo lo distinto a elelemento en sus posiciones

	function limpiar_array($array,$element){
		$j=0;
		for($i=0;$i<count($array);$i++){
			if($array[$i]!=$element){
				$nuevo[$j] = $array[$i];
				$j++;
			}
		}

		return $nuevo;

	}


	///Suma todos los elementos de un vector

	function suma_array($array){

		$result = 0;

		for($i=0;$i<count($array);$i++){

			$result+= $array[$i];
		}

		return $result;

	}




	///// llena un vector de elementos separados por comas un vector

	function llenar_array($ELEMENTOS){

		$elementos = explode(",",$ELEMENTOS);

		for($i=0;$i<count($elementos);$i++){

			$ARRAY[$i] = $elementos[$i];

		}

		return $ARRAY;

	}


	function burbuja($array,$modo=0){

		for ($i=1; $i<count($array); $i++){
			for ($j=0; $j<count($array)-1; $j++){

				if($modo==0){

					if ($array[$j]>$array[$j+1]){
						$temp = $array[$j];
						$array[$j] = $array[$j+1];
						$array[$j+1] = $temp;
					}

				}else{

					if ($array[$j]<$array[$j+1]){
						$temp = $array[$j];
						$array[$j] = $array[$j+1];
						$array[$j+1] = $temp;
					}

				}


			}
		}

		return  $array;
	}



	////////////extrae los elementos unicos (elimina los repetidos)

	function unicos($estos){

		$i = 1;
		$j = 1;
		if(count($estos)>0){ ///para saber que por lo menos uno tiene

			$result[0] = $estos[0];
			while($i<count($estos)){  //////while

				if(!in_array($estos[$i],$result)){  $result[$j] = $estos[$i]; $j++;  }

				$i++;

			}////while

		}

		return  $result;

	}


	//// valida se el arreglo tiene elementos repetidos       TRUE O FALSE
	function repetidos ($array){
		$repeat  = @array_unique($array);
		if(count($repeat)!= count($array)) return true; else return false;
	}



	/////////////////////////////// otras funciones

	///function que redirecciona a alguna direccion url, parent (opcional)

	function redirect($url,$parent=''){

		if($parent!='') $parent.='.';

		echo " <script language=\"JavaScript\"
                        type=\"text/javascript\">
                        $parent"."location.replace('$url');
                         </script>";

		die();

	}


	/////////funcion para mostrar un aviso en javascript y redireccionar si asi se quiere

	function javaviso($aviso,$url=false){

		if($url)$url = "location.replace('$url');"; else $url = "";
		echo "<script language=\"JavaScript\" type=\"text/javascript\">
         alert('$aviso');
         $url
         </script>";

		if($url!="")die();


	}



	///////////devolver el formato original del texto ingresado en la base de datos usando la funcion inserar o update
	////////// nota esta funcion recibe el campo texto y lo formatea con los caracteres originales usados
	///////// todos los campos de texto que se repliegan deberian mostrarse con esta funcion

	function format_txt($texto,$textarea=false){

		///////conversion

		if(!$textarea) $contenido = str_replace('\r\n','<p>', mysql_escape_string ($texto));
		$contenido = str_replace('\\','',$contenido);

		///////////

		return $contenido;

	}



	////// abreviar necesita el texto a abreviar y el numero de caracteres a los que se va a reducir
	function abreviar($texto,$cmax){

		///////conversion

		$frase = '<span title="'.$texto.'">';
		if(strlen($texto)>$cmax) $frase.= substr($texto, 0, $cmax).'...';
		$frase.= '</span>';
		///////////

		return $frase;

	}








////////////////////////////////////////////////METODOS PARA ARCHIVOS//////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////////////////////////


  ////subir archivos funcion que sube un archivo de un origen a un destino con ciertas validaciones
  //// parametros
  //// origen: archivo subido con un campo file de un form   $_FILES['archivo']
  //// destino: ruta en donde sera copiado el archivo (incluyendo en nombre del archivo) ej: ../files/nuevo.gif   nota: por defecto sube al mismo dir con el mismo nombre
  //// tmax: tamano maximo del archivo a subir en MB por defecto ilimitado
  //// tipo: tipos de archivo que acepta por defecto cualquiera
  //// overwrite: si acepta overwrite, por defecto true

   public function upload_file($origen,$destino=false,$tmax=false,$tipo=false,$overwrite=true){


	if(!$destino) $destino = $origen['name'];


	//////valida el tamano maximo
	if($tmax){
		$tama =  bcdiv($origen['size'],1048576,2); ///tamano en mb
		if($tama>$tmax){ $this->javaviso("El archivo es demasiado grande");
		return false;
		}
	}


	//////valida el tipo de archivo
	if($tipo){

		$vectortipo = explode(",",strtolower($tipo));

		if(!in_array(strtolower($origen['type']),$vectortipo)){

		$this->javaviso("El tipo de archivo a subir es invalido");
		return false;


		}


	}



	////////////valida si ya existe
	if(!$overwrite){

		if (file_exists($destino)) {

			$this->javaviso("Ya existe un archivo con el mismo nombre");
			return false;

		}


	}



	////////////proceso de subir el archivo

	 if(!copy($origen['tmp_name'], $destino)){
	 	$this->javaviso("Error al subir el archivo, Verifique los permisos de escritura en el ".$origen['tmp_name']." directorio ".$destino);
	 	return false;

	 }else{

	 	return true;

	 }


	///////////////////////////


  }







	///// esta funcion devuelve tosdos los elementos de un directorio en un array
	//////// con solo especificar el path

	function listar_archivos($dirname=".") {
		$i=0;
		if($handle = opendir($dirname)) {
			while(false !== ($file = readdir($handle))) {
				if (($file!='..') && ($file!='.') && ($file!='Thumbs.db')){
					$files[$i] = $file;
					$i++;
				}
			}
			closedir($handle);
		}
		return($files);
	}


	////////////////////////////////////////////////////////////////////

	function combo_dir($id,$path,$select=false,$seleccion=false,$submit=false,$noreg,$multiple=false,$desabilita=false){

		$array = $this->listar_archivos($path);

		if(count($array)>0){

			$combo = '<select name="'.$id.'" id="'.$id.'"';
			if($submit)$combo.=' onChange="submit();"';
			if($multiple)$combo.=' multiple size = "'.(count($array)/2+1).'" ';
			if($desabilita)$combo.=' readonly="true"';
			$combo.= '>';
			if($select) $combo.= '<option value="">'.$select.'</option>';
			$i=0;

			while($i<count($array)) {
				$combo.= '<option value="';
				$combo.= $array[$i];
				$combo.= '"';
				if($seleccion == $array[$i]) $combo.= ' selected';
				$combo.= '>';
				$combo.= $array[$i];
				$combo.= '</option>';
				$i++;
			}
			$combo.= '</select>';

		}else{

			$combo = '<b>'.$noreg.'</b>';
		}

		return $combo;


	}


function csv2xml($file, $container = 'data', $rows = 'row')
{
        $r = "<{$container}>\n";
        $row = 0;
        $cols = 0;
        $titles = array();


        $handle = @fopen($file, 'r');
        if (!$handle) return $handle;

        while (($data = fgetcsv($handle, 1000, ';')) !== FALSE)
        {

             if ($row > 0) $r .= "\t<{$rows}>\n";
             if (!$cols) $cols = count($data);
             for ($i = 0; $i < $cols; $i++)
             {
                  if ($row == 0)
                  {
                       $titles[$i] = $data[$i];
                       continue;
                  }

                  $r .= "\t\t<{$titles[$i]}>";
                  $r .= $data[$i];
                  $r .= "</{$titles[$i]}>\n";
             }
             if ($row > 0) $r .= "\t</{$rows}>\n";
             $row++;
        }
        fclose($handle);
        $r .= "</{$container}>";
        return $r;
}


//Funcion que realiza la suma de fechas segun lo que se le pase
//$dd = dias,  $mm = meses, $yy = a�os, $hh = horas, $mn = minutos, $ss = segundos
public function dateadd($date, $dd=0, $mm=0, $yy=0, $hh=0, $mn=0, $ss=0){

	$date_r = getdate(strtotime($date));
	$date_result = date("m/d/Y h:i:s", mktime(($date_r["hours"]+$hh),($date_r["minutes"]+$mn),($date_r["seconds"]+$ss),($date_r["mon"]+$mm),($date_r["mday"]+$dd),($date_r["year"]+$yy)));

	return $date_result;
	/*return date("d/m/Y", strtotime($date_result));*/
	//$date_r = getdate(strtotime($date));
	$date_format =  date("d/m/Y", strtotime($date));
	//echo $date_format."<br>";
	$date_r = getdate(strtotime($date_format));
	$date_result = date("m/d/Y h:i:s", mktime(($date_r["hours"]+$hh),($date_r["minutes"]+$mn),($date_r["seconds"]+$ss),($date_r["mon"]+$mm),($date_r["mday"]+$dd),($date_r["year"]+$yy)));

	//return $date_result;
	//return date("d/m/Y", strtotime($date_result));*/
}

public function EnviarEMailGCISA($NombreFrom,$para,$asunto,$cuerpo,$adjuntos=array(),$cc=array()){
       
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

    $mail->IsSMTP(); // telling the class to use SMTP
    $user='gerenciacompromisoinstitucionalsocialyambiental@masisa.com';
    try {
      $mail->Host       = "mail.yourdomain.com"; // SMTP server
      $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
      $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
      $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
      $mail->Username   = "mailmasisa.venezuela@gmail.com";  // GMAIL username
      $mail->Password   = "Mejoramiento.2014";            // GMAIL password      
      $mail->Username   = "gerenciacompromisoinstitucionalsocialyambiental@masisa.com";  // GMAIL username
      $mail->Password   = "compromiso";            // GMAIL password      
      $mail->SetFrom($user, $NombreFrom);
      //$mail->AddReplyTo('name@yourdomain.com', 'First Last');
      $mail->Subject = $asunto;
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      //$mail->MsgHTML(file_get_contents('contents.html'));
      $mail->Body = $cuerpo;
      foreach ($adjuntos as $value) {
        $mail->AddAttachment($value);
      }
    // // attachment
    foreach ($para as $value) {        
        $mail->AddAddress($value[correo], $value[nombres]);
    }
    foreach ($cc as $value) {        
        $mail->AddCC($value[correo], $value[nombres]);
    }
      //$mail->AddAttachment('images/phpmailer.gif');      // attachment
      //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
      $mail->Send();
      return "Message Sent OK</p>\n";
    } catch (phpmailerException $e) {
        $this->EnviarEMailAuxGCISA($NombreFrom,$para,$asunto,$cuerpo,$adjuntos,$cc);
      return $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        $this->EnviarEMailAuxGCISA($NombreFrom,$para,$asunto,$cuerpo,$adjuntos,$cc);
      return $e->getMessage(); //Boring error messages from anything else!
    }    
} // fin del metodo

public function EnviarEMailAuxGCISA($NombreFrom,$para,$asunto,$cuerpo,$adjuntos=array(),$cc=array()){
        
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

    $mail->IsSMTP(); // telling the class to use SMTP
    $user='gerenciacompromisoinstitucionalsocialyambiental@masisa.com';
    try {
      $mail->Host       = "mail.yourdomain.com"; // SMTP server
      $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
      $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
      $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
      $mail->Username   = "melvindejesus.garcia@gmail.com";  // GMAIL username
      $mail->Password   = "mifuerzaesdios";   
      $mail->SetFrom($user, $NombreFrom);
      //$mail->AddReplyTo('name@yourdomain.com', 'First Last');
      $mail->Subject = $asunto;
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      //$mail->MsgHTML(file_get_contents('contents.html'));
      $mail->Body = $cuerpo;
      foreach ($adjuntos as $value) {
        $mail->AddAttachment($value);
      }
    // // attachment
    foreach ($para as $value) {        
        $mail->AddAddress($value[correo], $value[nombres]);
    }
    foreach ($cc as $value) {        
        $mail->AddCC($value[correo], $value[nombres]);
    }
      //$mail->AddAttachment('images/phpmailer.gif');      // attachment
      //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
      $mail->Send();
      return "Message Sent OK</p>\n";
    } catch (phpmailerException $e) {
      return $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      return $e->getMessage(); //Boring error messages from anything else!
    }    
} // fin del metodo

public function EnviarEMail($NombreFrom,$para,$asunto,$cuerpo,$adjuntos=array(),$cc=array()){
    
    /*  configuracion conexion a internet normal */
    /*$host='smtp.gmail.com';
    $port='587';
    $user='direcciondeoperaciones.masisavenezuela@masisa.com';
    $pass='sisa1512';
    $user='saludmedioambienteseguridad.ve@masisa.com';
    $pass='masisa2014';
    $user='saludmedioambienteseguridad.chile@masisa.com';
    $pass='masisa.2014';
                
    $mail = new PHPMailer(); // the true param means it will throw exceptions on errors, which we need to catch
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host       = $host; // SMTP server
    $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";
    $mail->Port       = $port+0;                    // set the SMTP port for the GMAIL server
    $mail->Username   = $user; // SMTP account username
    $mail->Password   = $pass;        // SMTP account password
    $mail->SetFrom($user, $NombreFrom);
    $mail->Subject = $asunto;
    $mail->AltBody = 'Para ver el mensaje, por favor use visor de email HTML compatible!'; // optional - MsgHTML will create an alternate automatically
    //$mail->AddEmbeddedImage('diseno/images/empresas/4.jpg', 'imagen.jpg','imagen.jpg','base64','image/jpeg');
    $mail->IsHTML(true);
    $mail->Body = $cuerpo;
    //$mail->MsgHTML(file_get_contents('pages/correo/'.$ee->id_empresa.'.html'));
    //$mail->AddAttachment('adjuntos/RP-'.utf8_decode(trim($ee->TbPlazas->getFirst()->TbUnidadesPuestos->TbUnidadOrganizativa->descripcion).'-'.trim(trim($empleado->primer_apellido)." ".trim($empleado->segundo_apellido)." ".trim($empleado->primer_nombre)." ".trim($empleado->segundo_nombre))).".pdf");      // attachment
    foreach ($adjuntos as $value) {
        $mail->AddAttachment($value);
    }
    // // attachment
    foreach ($para as $value) {        
        $mail->AddAddress($value[correo], $value[nombres]);
    }
    foreach ($cc as $value) {        
        $mail->AddCC($value[correo], $value[nombres]);
    }
    


    if(!$mail->Send()) {
        return "Mailer Error: " . $mail->ErrorInfo;
    } 
    else {
        return "Message sent";
    }
     *
     */
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

    $mail->IsSMTP(); // telling the class to use SMTP
    $user='saludmedioambienteseguridad.chile@masisa.com';
    try {
      $mail->Host       = "mail.yourdomain.com"; // SMTP server
      $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
      $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
      $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
      $mail->Username   = "mailmasisa.venezuela@gmail.com";  // GMAIL username
      $mail->Password   = "Mejoramiento.2014";            // GMAIL password      
      $mail->Username   = CORREO_SALIENTE; //"saludmedioambienteseguridad.chile@masisa.com" ;  // GMAIL username
      $mail->Password   = CONTRASENA_CORREO_SALIENTE;// "masisa.2014";            // GMAIL password      
      $mail->SetFrom($user, $NombreFrom);
      //$mail->AddReplyTo('name@yourdomain.com', 'First Last');
      $mail->Subject = $asunto;
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      //$mail->MsgHTML(file_get_contents('contents.html'));
      $mail->Body = $cuerpo;
      foreach ($adjuntos as $value) {
        $mail->AddAttachment($value);
      }
    // // attachment
    foreach ($para as $value) {        
        $mail->AddAddress($value[correo], $value[nombres]);
    }
    foreach ($cc as $value) {        
        $mail->AddCC($value[correo], $value[nombres]);
    }
      //$mail->AddAttachment('images/phpmailer.gif');      // attachment
      //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
      $mail->Send();
      return "Message Sent OK</p>\n";
    } catch (phpmailerException $e) {
        $this->EnviarEMailAux($NombreFrom,$para,$asunto,$cuerpo,$adjuntos,$cc);
      return $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        $this->EnviarEMailAux($NombreFrom,$para,$asunto,$cuerpo,$adjuntos,$cc);
      return $e->getMessage(); //Boring error messages from anything else!
    }
    /*  configuracion conexion desde servidor masisa
    $user='saludmedioambienteseguridad.ve@masisa.com';
    $mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host       = '10.200.1.2'; // SMTP server
    $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = false;                  // enable SMTP authentication
	$mail->FromName = $NombreFrom;
    //$mail->SMTPSecure = "ssl";
    $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
    //$mail->Username   = $user; // SMTP account username
    //$mail->Password   = $pass;        // SMTP account password
    $mail->SetFrom($user, $NombreFrom);
    $mail->Subject = $asunto;
	
    $mail->AltBody = 'Para ver el mensaje, por favor use visor de email HTML compatible!'; // optional - MsgHTML will create an alternate automatically
    //$mail->AddEmbeddedImage('diseno/images/empresas/4.jpg', 'imagen.jpg','imagen.jpg','base64','image/jpeg');
    $mail->IsHTML(true);
    $mail->Body = $cuerpo;
    //$mail->MsgHTML(file_get_contents('pages/correo/'.$ee->id_empresa.'.html'));
    //$mail->AddAttachment('adjuntos/RP-'.utf8_decode(trim($ee->TbPlazas->getFirst()->TbUnidadesPuestos->TbUnidadOrganizativa->descripcion).'-'.trim(trim($empleado->primer_apellido)." ".trim($empleado->segundo_apellido)." ".trim($empleado->primer_nombre)." ".trim($empleado->segundo_nombre))).".pdf");      // attachment
    foreach ($adjuntos as $value) {
        $mail->AddAttachment($value);
    }
    // // attachment
    foreach ($para as $value) {        
        $mail->AddAddress($value[correo], $value[nombres]);
    }
    foreach ($cc as $value) {        
        $mail->AddCC($value[correo], $value[nombres]);
    }
    


    if(!$mail->Send()) {
        return "Mailer Error: " . $mail->ErrorInfo;
    } 
    else {
        return "Message sent";
    }
*/

} // fin del metodo

public function EnviarEMailAux($NombreFrom,$para,$asunto,$cuerpo,$adjuntos=array(),$cc=array()){
       
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

    $mail->IsSMTP(); // telling the class to use SMTP
    $user='saludmedioambienteseguridad.chile@masisa.com';
    try {
      $mail->Host       = "mail.yourdomain.com"; // SMTP server
      $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
      $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
      $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
      $mail->Username   = "melvindejesus.garcia@gmail.com";  // GMAIL username
      $mail->Password   = "mifuerzaesdios";   
      $mail->SetFrom($user, $NombreFrom);
      //$mail->AddReplyTo('name@yourdomain.com', 'First Last');
      $mail->Subject = $asunto;
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      //$mail->MsgHTML(file_get_contents('contents.html'));
      $mail->Body = $cuerpo;
      foreach ($adjuntos as $value) {
        $mail->AddAttachment($value);
      }
    // // attachment
    foreach ($para as $value) {        
        $mail->AddAddress($value[correo], $value[nombres]);
    }
    foreach ($cc as $value) {        
        $mail->AddCC($value[correo], $value[nombres]);
    }
      //$mail->AddAttachment('images/phpmailer.gif');      // attachment
      //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
      $mail->Send();
      return "Message Sent OK</p>\n";
    } catch (phpmailerException $e) {
      return $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      return $e->getMessage(); //Boring error messages from anything else!
    }

} // fin del metodo

public function EnviarEMailPromocion($NombreFrom,$para,$asunto,$cuerpo,$from=""){
   $mail = new PHPMailer();
    $mail->Host = "mail.cantv.net";
    $mail->Mailer = "smtp";
    $mail->IsSMTP();
    $mail->IsHTML(true);
    $mail->From = 'capacitacion@masisa.com';
    $mail->From = 'gerenciageneral.masisavenezuela@masisa.com';
    $mail->FromName = 'Gerencia General Masisa Venezuela';
    $mail->Subject = $asunto;
    $mail->AddAddress($para);
    $mail->Body = $cuerpo;
    $mail->AltBody = "Corporaci�n Masisa";

    if ($mail->Send())
        return 'Correo Enviado';
    else
        return ("Mailer Error: " . $mail->ErrorInfo);


}

} //// fin de la clase tool



?>