<?php 
function BuscaOrganizacional($tupla)
        {
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $OrgNom = "";
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        $Resp3 = $dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if($Fila3[organizacion_padre]==1)
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= BuscaOrganizacional(array('id_organizacion' => $Fila3[organizacion_padre])) . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= $_SESSION[CookNomEmpresa];
                return $OrgNom;

        }
        
        function BuscaProceso($tupla)
        {
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $OrgNom = "";
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_arbol_procesos where id in ($tupla[id_organizacion])";
                        //echo $Consulta3;
                        $Resp3 = $dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if($Fila3[organizacion_padre]==1)
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= BuscaProceso(array('id_organizacion' => $Fila3[organizacion_padre])) . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= $_SESSION[CookNomEmpresa];
                return $OrgNom;

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
function completar_espacios($texto,$numero_espacios,$sentido=1){
    $tam = strlen($texto);
    $new_text = $texto;
    for($i=1;$i<=($numero_espacios-$tam);$i++){
        if ($sentido==1){
            $new_text = $new_text . '&nbsp;';
        }
        else {
            $new_text = '&nbsp;' . $new_text;
        }
    }
    //echo $new_text . " $tam - $numero_espacios <br> ";
    return $new_text;
}
function CambiaSinAcento($Texto)
{
	$Texto=str_replace('á','a',$Texto);
	$Texto=str_replace('Á','A',$Texto);
	$Texto=str_replace('é','e',$Texto);
	$Texto=str_replace('É','E',$Texto);
	$Texto=str_replace('í','i',$Texto);
	$Texto=str_replace('Í','I',$Texto);
	$Texto=str_replace('ó','o',$Texto);
	$Texto=str_replace('Ó','O',$Texto);
	$Texto=str_replace('ú','u',$Texto);
	$Texto=str_replace('Ú','U',$Texto);
	$Texto=str_replace('Ü','U',$Texto);
	$Texto=str_replace('ü','u',$Texto);
	$Texto=str_replace('Ñ','N',$Texto);
	$Texto=str_replace('ñ','n',$Texto);
	$Texto=str_replace('ñ','n',$Texto);
	return($Texto);
}
function descripcion_mes($mes)
	{
		   switch($mes){
		        case 1: return "Enero";
				case 2: return "Febrero";
				case 3: return "Marzo";
				case 4: return "Abril";
				case 5: return "Mayo";
		        case 6: return "Junio";
		   		case 7: return "Julio";
				case 8: return "Agosto";
				case 9: return "Septiembre";
				case 10: return "Octubre";
				case 11: return "Noviembre";
				case 12: return "Diciembre";

		   }
	}

        function descripcion_dia_semana($mes)
	{
		   switch($mes){
		        case 1: return "Lunes";
                        case 2: return "Martes";
                        case 3: return "Miercoles";
                        case 4: return "Jueves";
                        case 5: return "Viernes";
		        case 6: return "Sabado";
                        case 7: return "Domingo";
		   }
	}

?>