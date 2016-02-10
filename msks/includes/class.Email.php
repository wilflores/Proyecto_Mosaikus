<?
function ClaseEnvioCorreo($Asunto,$Correo,$Empresa,$Usuario,$NomEmpresa,$NomUsuario,$link,$BD)
{
		require_once("EnDecryptText.php");	
		set_time_limit('50000');
		mysql_select_db($BD, $link);	
		$EnDecryptText = new EnDecryptText(); 
		$RutEmp = $EnDecryptText->Encrypt_Text($Empresa);
		$RutUsu = $EnDecryptText->Encrypt_Text($Usuario);
		$Email = $EnDecryptText->Encrypt_Text($Correo);
		
		$DatosFont='style="font-family:Arial; font-size:12px;" color="#2861A6"';
		$Mensaje.= "<br />";
			$Mensaje.='<table width="400px" border="0" cellpadding="0" cellspacing="0">';
			$Mensaje.='<tr>';
			$Mensaje.='<td '.$DatosFont.'>Hola '.($NomUsuario).'</td>';
			$Mensaje.='</tr>';
			$Mensaje.='</table>';
			$Mensaje.= "<br />";			
			$Mensaje.= "<br />";			
			$Mensaje.='<table width="900px" border="0" cellpadding="0" cellspacing="0">';
			$Mensaje.="<td colspan='3' ".$DatosFont." color='#2861A6'>".utf8_decode("Haz clic en el enlace que aparece a continuación, el cual lo derivara a la creación de la nueva contraseña de acceso al Sistema Mosaikus").".<br/>
			http://www.mosaikus.com/msks/rcu.php?rE=".$RutEmp."&rU=".$RutUsu."&e=".$Email."</td>";
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
	
		//echo $Correo."<br />";
		//echo $Mensaje;
		Envio($Asunto,$Mensaje,$Correo);
}
function Envio($Asunto,$Mensaje,$Correo)
{
	require "class.phpmailer.php";
	$mail = new phpmailer();
	$mail->AddEmbeddedImage("./images/LogoEnvio.png","logo5","./images/LogoEnvio.png","base64","image/png");
	$mail->PluginDir = "";
	$mail->Mailer = "smtp";
	$mail->Host = "smtpout.secureserver.net";		
	$mail->SMTPAuth=true;
	$mail->Username = "servicios@mosaikus.com";
	$mail->Password = "servicios";
	$mail->From = "servicios@mosaikus.com";
	$mail->FromName = "www.mosaikus.com";
	$mail->Subject = utf8_decode($Asunto);
	$mail->Body=$Mensaje;
	$mail->IsHTML(false);
	$mail->AltBody =str_replace('<br>','\n',$Mensaje);
	$mail->AddAddress($Correo);	
	$mail->Timeout=120;
	//$mail->AddAttachment($Doc,$Doc);
	$exito = $mail->Send();
	$intentos=1; 
	while((!$exito)&&($intentos<5)&&($mail->ErrorInfo!="SMTP Error: Data not accepted")){
	sleep(5);
	$exito = $mail->Send();
	$intentos=$intentos+1;				
	}
	$mail->ClearAddresses();
	/*if(!$exito)
	{
	  echo "Problemas enviando correo electrónico a ".$valor;
	  echo "<br/>".$mail->ErrorInfo;	
	}
	else
	{
	 echo "Mensaje enviado";
	} */
}
?>