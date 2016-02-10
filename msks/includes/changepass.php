<?
include("bd_conect.php");include("class.Email.php");
$Consulta="select id_empresa from mosaikus_admin.mos_adm_acceso where id_usuario='".$RU."' and id_empresa='".$RE."'";
$Resp=mysql_query($Consulta);
if($Fila=mysql_fetch_assoc($Resp))
{
	$Actualiza="update mosaikus_admin.mos_adm_acceso set password_1='".md5(trim($TxNueva))."' where id_usuario='".$RU."' and id_empresa='".$RE."'";	
	mysql_query($Actualiza);
	echo "Contraseña cambiada con éxito";
	
	
	
	$DatosFont='style="font-family:Arial; font-size:12px;" color="#2861A6"';
	$Mensaje= "<br />";
		$Mensaje.='<table width="400px" border="0" cellpadding="0" cellspacing="0">';
		$Mensaje.='<tr>';
		$Mensaje.='<td '.$DatosFont.'>Hola '.utf8_decode(str_replace('~',' ',($NomUsu))).'</td>';
		$Mensaje.='</tr>';
		$Mensaje.='</table>';
		$Mensaje.= "<br />";			
		$Mensaje.= "<br />";			
		$Mensaje.='<table width="900px" border="0" cellpadding="0" cellspacing="0">';
		$Mensaje.="<td colspan='3' ".$DatosFont." color='#2861A6'>".utf8_decode("Para su información, se ha realizado el cambio de contraseña para el acceso al Sistema Mosaikus.<br><br>Rut empresa: ".$RE."<br>Rut usuario: ".$RU."<br>Contraseña: ".$TxNueva."").".<br/>";
		$Mensaje.='</tr>';
		$Mensaje.='</table>';
	$Mensaje.= "<br />";
	$Mensaje.= "<br />";
	$Mensaje.='<table width="100%" border="0" cellpadding="0" cellspacing="0">';
	$Mensaje.='<tr>';
	$Mensaje.='<td>';
	$Mensaje.="<font ".$DatosFont."> Este es un mensaje autom&aacute;tico de Mosaikus,  cualquier consulta dirigirlas a la  administraci&oacute;n del SIG.</font>";
	$Mensaje.= "<br />";
	$Mensaje.= "<br />";
	//$Mensaje.="<font ".$DatosFont." color='#2861A6'> Nota: Los tildes en asunto del correo han sido omitidos intencionalmente</font>";
	$Mensaje.= "<br />";
	$Mensaje.= "<br />";
	$Mensaje.= "<br />";
	$Mensaje.="<font ".$DatosFont."><strong> Administrador sistemas de gesti&oacute;n</strong></font>";
	$Mensaje.= "<br />";
	$Mensaje.="<img src='cid:logo5' />";
	$Mensaje.='</td>';
	$Mensaje.='</tr>';
	$Mensaje.='</table>';
	
	Envio('Contraseña cambiada.',$Mensaje,$Co);
}
?>
<script language="javascript">
window.location="http://www.mosaikus.com";
</script>