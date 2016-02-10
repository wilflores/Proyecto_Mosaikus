<?
	$Label='';
	if($_Nom=='')
		$Label='Debe ingresar Nombre<br />';
	if($_Mail=='')	
		$Label=$Label.'Debe ingresar Email<br />';
	if($_Tel=='')	
		$Label=$Label.'Debe ingresar Telefono<br />';
	if($_Msj=='')	
		$Label=$Label.'Debe ingresar Comentario<br />';
	if($Label!='')
		echo	'<span style="color:#FF0000">'.$Label.'</span>';
	else
	{	
		if($Envio=='S')
		{
			$DatosFont='style="font-family:Arial; font-size:12px;" color="#2861A6"';
			$Mensaje='<table border="0"><tr><td width="80px"><font "'.$DatosFont.'" color="#2861A6">Nombre</font></td><td>:<font "'.$DatosFont.'" color="#2861A6">'.str_replace('///',' ',utf8_decode($_Nom)).'</font></td></tr>';
			$Mensaje.='<tr><td width="80px"><font "'.$DatosFont.'" color="#2861A6">Email</font></td><td>:<font "'.$DatosFont.'" color="#2861A6">'.str_replace('///',' ',utf8_decode($_Mail)).'</font></td></tr>';
			$Mensaje.='<tr><td width="80px"><font "'.$DatosFont.'" color="#2861A6">Telefono</font></td><td>:<font "'.$DatosFont.'" color="#2861A6">'.str_replace('///',' ',$_Tel).'</font></td></tr>';
			$Mensaje.='<tr><td width="80px"><font "'.$DatosFont.'" color="#2861A6">Cometario</font></td><td>:<font "'.$DatosFont.'" color="#2861A6">'.str_replace('///',' ',utf8_decode($_Msj)).'</font></td></tr></table>';
			$Mensaje.= "<br />";
			$Mensaje.= "<br />";
			$Mensaje.= "<br />";
			$Mensaje.='<table width="100%" border="0" cellpadding="0" cellspacing="0">';
			$Mensaje.='<tr>';
			$Mensaje.='<td>';
			$Mensaje.="<font ".$DatosFont." color='#2861A6'> Este es un mensaje autom&aacute;tico de Mosaikus,  cualquier consulta dirigirlas a la  administraci&oacute;n del SIG.</font>";
			$Mensaje.= "<br />";
			$Mensaje.= "<br />";
			//$Mensaje.="<font ".$DatosFont." color='#2861A6'> Nota: Los tildes en asunto del correo han sido omitidos intencionalmente</font>";
			$Mensaje.= "<br />";
			$Mensaje.="<font ".$DatosFont." color='#2861A6'> Se despide atentamente,</font>";
			$Mensaje.= "<br />";
			$Mensaje.= "<br />";
			$Mensaje.= "<br />";
			$Mensaje.="<font ".$DatosFont." color='#2861A6'><strong> Administrador sistemas de gesti&oacute;n</strong></font>";
			$Mensaje.= "<br />";
			$Mensaje.="<img src='cid:logo5' />";
			$Mensaje.='</td>';
			$Mensaje.='</tr>';
			$Mensaje.='</table>';
			
			$Correo='czuniga@mosaikus.com';
			
			require "../includes/class.phpmailer.php";
			//echo $Mensaje;
			$mail = new phpmailer();	
			$mail->AddEmbeddedImage("../../../archivos/LogoEnvio.png","logo5","../../../archivos/Logo.png","base64","image/png");
			$mail->PluginDir = "../includes/";
			$mail->Mailer = "smtp";
			$mail->Host = "smtpout.secureserver.net";		
			$mail->SMTPAuth=true;
			$mail->Username = "servicios@mosaikus.com";
			$mail->Password = "servicios";
			$mail->From = "servicios@mosaikus.com";
			$mail->FromName = "www.mosaikus.com";
			$mail->Subject = "Contacto Mosaikus";
			$mail->Body=$Mensaje;
			$mail->IsHTML(true);
			$mail->AltBody =str_replace('<br>','\n',$Mensaje);
			$mail->AddAddress($Correo);	
			$mail->Timeout=120;
			//$mail->AddAttachment($Doc,$Doc);
			$exito = $mail->Send();
			$intentos=1; 
			while((!$exito)&&($intentos<5)&&($mail->ErrorInfo!="SMTP Error: Data not accepted")){
			sleep(2);
			$exito = $mail->Send();
			$intentos=$intentos+1;				
			}
			$mail->ClearAddresses();
		   $MSJ="Email no enviado";
		   if($exito)
				$MSJ="Email enviado correctamente";
			?>
			<script type="text/javascript">			  
			  RecargaIframe('por_que','<? echo $MSJ;?>');
			</script>			
			<?
		}	
	}
?>