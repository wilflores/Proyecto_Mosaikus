<?php


function validar_perfil($ruta='../../login.php'){
    session_name($GLOBALS[SESSION]);
    session_start();
    if($_SESSION['CookIdUsuario']!='')
	 	//FINO
        {
        $a=1;}
    else
		{	session_destroy();
			?>
            <script type="text/javascript">
			location.replace('<?php echo $ruta;?>');
			</script>
            <?php

            die();
        }

 }
 
 function link_titulos($titulo, $valor, $parametros,$ancho=0){
     if ($ancho>0)
         $html = "<div onclick=\"link_titulos('$valor');\" style='cursor:pointer;width:".$ancho."px;height: 20px;'>" .  htmlentities($titulo, ENT_QUOTES, "UTF-8")  . "</div>";     
     else
        $html = "<div onclick=\"link_titulos('$valor');\" style='cursor:pointer;display:inline;'>" .  htmlentities($titulo, ENT_QUOTES, "UTF-8")  . "</div>";     
     if ($parametros[corder] == $valor) {
         if ($parametros[sorder] == 'asc')
            $html .= '<i class="icon icon-up"></i>';
         else
            $html .= '<i class="icon icon-down"></i>';
         
         //$html .= "<img style='float:right;' src='diseno/images/$parametros[sorder].gif'/>";
     }
     return $html;
     
 }
 
 function link_titulos_otro($titulo, $valor, $parametros,$funcion){
     $html = "<div onclick='$funcion(\"$valor\");' style='cursor:pointer;display:inline;'>". htmlentities($titulo, ENT_QUOTES, "UTF-8")  . "</div>";     
     if ($parametros[corder] == $valor) {
         if ($parametros[sorder] == 'asc')
            $html .= '<i class="icon icon-up"></i>';
         else
            $html .= '<i class="icon icon-down"></i>';
         //$html .= "<img style='float:right;' src='diseno/images/$parametros[sorder].gif'/>";
     }
     return $html;
     
 }

 function formatear_fecha($fechaAux){
     $fecha = explode('/', $fechaAux);        
     return  $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
 }
 
 function formatear_fecha_hora($fechaAux){
     $fecha_hora = explode(' ', $fechaAux);  
     $fecha = explode('/', $fecha_hora[0]);  
     return  $fecha[2].'-'.$fecha[1].'-'.$fecha[0] . ' ' . $fecha_hora[1];
 }
 
 function sanear_string($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );


    return $string;
}
 
function nombre_mes($mes){
switch($mes)
{
case 1:
    {
	return ("Enero");
	break;}
case 2:
    {
	return ("Febrero");
	break;}
case 3:
    {
	return ("Marzo");
	break;}
case 4:
    {
	return ("Abril");
	break;}
case 5:
    {
	return ("Mayo");
	break;}
case 6:
    {
	return ("Junio");
	break;}
case 7:
    {
	return ("Julio");
	break;}
case 8:
    {
	return ("Agosto");
	break;}
case 9:
    {
	return ("Septiembre");
	break;}
case 10:
    {
	return ("Octubre");
	break;}
case 11:
    {
	return ("Noviembre");
	break;
    }
case 12:
    {
	return ("Diciembre");
	break;}

}
}

        function estatus($valores){		
		return "<div id='div-estatus-$valores[id]'>$valores[estatus]</div>";
	}
?>
