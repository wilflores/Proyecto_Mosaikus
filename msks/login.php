<?
$MENSAJE='';
include("includes/bd_conect.php");
if($Opcion=='I')
{	
	session_start();
	session_register("Cookdatabase");
	session_register("CookLogginBD");
	session_register("CookPasswordDB");
	session_register("CookIdEmpresa");
	session_register("CookIdUsuario");
	session_register("CookNamUsuario");
	session_register("CookNomUnidad");
	session_register("CookNomEmpresa");
	session_register("CookNomEmpresaGeneralIni");
	session_register("CookFilial");
	session_register("SuperUser");
	session_register("CookN");
	session_register("CookM");
	session_register("CookE");
	session_register("CookWeb");
	require_once("includes/EnDecryptText.php"); 
	$Consulta="Select t1.*,t2.businessName,t2.db,t2.loginDB,t2.passwordDB from mosaikus_admin.mos_adm_acceso t1 inner join  mosaikus_admin.mos_adm_empresas t2 on t1.id_empresa=t2.id_empresa where t1.id_usuario='".$TxtUsuario."' and  t1.id_empresa='".$TxtEmpresa."' and t1.password_1='".md5($TxtPwd)."'";
	$Resp=mysql_query($Consulta);
	if($Fila=mysql_fetch_assoc($Resp))
	{
		$BD=$Fila["db"];
		$Usuario=$Fila["loginDB"];
		$Pwd=$Fila["passwordDB"];	
		$EnDecryptText = new EnDecryptText(); 
		$BaseDato = $EnDecryptText->Encrypt_Text($Fila["db"]);
		$LoginBD = $EnDecryptText->Encrypt_Text($Fila["loginDB"]);
		$PwdBD = $EnDecryptText->Encrypt_Text($Fila["passwordDB"]);
		$Cookdatabase=$BaseDato;
		$CookLogginBD=$LoginBD;
		$CookPasswordDB=$PwdBD;
		$CookNomEmpresaGeneralIni=$Fila["businessName"];
		$CookIdEmpresa=$Fila["id_empresa"];
		$CookIdUsuario=$Fila["id_usuario"];
		$CookWeb='S';
		
		echo 'Redireccionando...';		
		?>
		<script type="text/javascript">			  
		  window.location='../mosaikus/mos_inicio.php?In=S&Op=I';
		</script>
		<?
	}
	else
	{
		echo 'Cuenta de Usuario/Contraseña Incorrecta';			
	}
}	
if($Opcion=='RP')
{
	require_once("includes/EnDecryptText.php"); require_once("includes/class.Email.php"); 
	$Consulta="Select t1.*,t2.businessName,t2.db,t2.loginDB,t2.passwordDB from mosaikus_admin.mos_adm_acceso t1 inner join  mosaikus_admin.mos_adm_empresas t2 on t1.id_empresa=t2.id_empresa where t1.id_usuario='".$TxtUsuario."' and  t1.id_empresa='".$TxtEmpresa."'";
	//echo $Consulta."<br />";
	$Resp=mysql_query($Consulta);
	if($Fila=mysql_fetch_assoc($Resp))
	{
		$EnDecryptText = new EnDecryptText(); 
		$BaseDato = $EnDecryptText->Encrypt_Text($Fila["db"]);
		$LoginBD = $EnDecryptText->Encrypt_Text($Fila["loginDB"]);
		$PwdBD = $EnDecryptText->Encrypt_Text($Fila["passwordDB"]);
		$BD= $EnDecryptText->Decrypt_Text($BaseDato);
		$LBD= $EnDecryptText->Decrypt_Text($LoginBD);
		$PWDBD= $EnDecryptText->Decrypt_Text($PwdBD);	
		$NombreEmpresa=$Fila["businessName"];		
		$link = mysql_connect("localhost",$LBD,$PWDBD);
		mysql_select_db($BD, $link);	
		
		$CEmail="select * from $BD.mos_usuario where id_usuario='".$TxtUsuario."' and email='".str_replace('///','@',$TxtCorrPass)."'";
		$REmail=mysql_query($CEmail);
		if($FEmail=mysql_fetch_assoc($REmail))
		{
			echo 'Rut usuario y contraseña validos.<br/>Se enviara correo al mail '.str_replace('///','@',$TxtCorrPass).'.<br />para restrablecer contraseña.';	
		    ClaseEnvioCorreo('Cambio de contraseña',str_replace('///','@',$TxtCorrPass),$TxtEmpresa,$TxtUsuario,$NombreEmpresa,$FEmail[nombres]." ".$FEmail[apellido_paterno]." ".$FEmail[apellido_materno],$link,$BD);
		}	
		else	
			echo 'Rut usuario o correo invalidos.';		
	}	
	else
	{
		echo 'Rut empresa o Rut usuario invalidos.';		
	}
}
?>
