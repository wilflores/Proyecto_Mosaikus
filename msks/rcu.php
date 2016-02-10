<?
	require_once("includes/EnDecryptText.php");require_once("includes/class.Email.php");include("includes/bd_conect.php"); 
	$EnDecryptText = new EnDecryptText(); 
	$RutEmp= $EnDecryptText->Decrypt_Text($rE);
	$RutUsu= $EnDecryptText->Decrypt_Text($rU);
	$Correo= $EnDecryptText->Decrypt_Text($e);
	

	$Consulta="Select t1.*,t2.businessName,t2.db,t2.loginDB,t2.passwordDB from mosaikus_admin.mos_adm_acceso t1 inner join  mosaikus_admin.mos_adm_empresas t2 on t1.id_empresa=t2.id_empresa where t1.id_usuario='".$RutUsu."' and  t1.id_empresa='".$RutEmp."'";
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
		$CEmail="select * from $BD.mos_usuario where id_usuario='".$RutUsu."' and email='".$Correo."'";
		$REmail=mysql_query($CEmail);
		$FEmail=mysql_fetch_assoc($REmail);
		$NomUsu=$FEmail[nombres]." ".$FEmail[apellido_paterno]." ".$FEmail[apellido_materno];
   }		
?>

<style type="text/css">
body {
	background: url(images/fondo.png) no-repeat fixed;
	background-size: cover;
}
</style>
<script type="text/javascript" src="includes/jquery-1.4.1.min.js"></script> 
<script type="text/javascript" src="includes/funciones.js"></script> 
<script language="javascript">
function Rest()
{
if(document.getElementById('TxtNueva').value=='')
{
	document.getElementById('Nueva').innerHTML='<? echo utf8_decode("(*) Debe ingresar nueva contraseña.");?>';
	document.getElementById('TxtNueva').focus();
	return;
}
else
	document.getElementById('Nueva').innerHTML='';
	
if(document.getElementById('TxtNuevaR').value=='')
{
	document.getElementById('NuevaR').innerHTML='<? echo utf8_decode("(*) Debe ingresar repetir contraseña.");?>';
	document.getElementById('TxtNuevaR').focus();
	return;
}
else
	document.getElementById('NuevaR').innerHTML='';
var def=document.getElementById('TxtNueva').value!=document.getElementById('TxtNuevaR').value;
if(def==true)
{
	document.getElementById('NoCoin').innerHTML='<? echo utf8_decode("Contraseñas no coinciden.");?>';
	document.getElementById('TxtNuevaR').focus();
	return;
}
document.getElementById('Imagen').style.visibility='visible';
var Nombre="includes/changepass.php?"
+"&RE="+'<? echo $RutEmp;?>'
+"&RU="+'<? echo $RutUsu;?>'
+"&Co="+'<? echo $Correo;?>'
+"&NomUsu="+'<? echo str_replace(' ','~',$NomUsu);?>'
+"&TxNueva="+document.getElementById('TxtNueva').value;
$("#changePass").load(Nombre);
}
</script>
<link href="divs/estilos.css" rel="stylesheet" type="text/css" />
<body onLoad="MM_preloadImages('images/b1_2.png','images/b2_2.png','images/b3_2.png','images/b4_2.png','images/b5_2.png','images/b6_2.png','images/b7_2.png')">
<div id="contenedor">
	<div id="header">
    	<a href="mailto:info@mosaikus.com"><img src="images/header.png" width="900" height="100" /></a>
    </div><br />
	<div id="botonera" style="background:url(images/fondo_contenido1.png); height:270px;">
	<br />
	<p style="height:4px;">Cambio de contrase&ntilde;a usuario:&nbsp;<? echo $NomUsu;?>&nbsp;&nbsp;<img src="images/CargaPrin.gif" id="Imagen" style="visibility:hidden;"><br>Ingrese los datos para completar cambio de contrase&ntilde;a.</p>
	<br />
	<p style="width:500px; height:8px; padding-left:200px;">Contrase&ntilde;a nueva: &nbsp;&nbsp;</p><p style="padding-left:200px;"><input type="password" id="TxtNueva" size="8" maxlength="8" />&nbsp;&nbsp;<label id="Nueva"></label></p>
	<p style="width:500px; height:8px; padding-left:200px;">Repetir contrase&ntilde;a: &nbsp;&nbsp;</p><p style="padding-left:200px;"><input type="password" id="TxtNuevaR" size="8" maxlength="8" />&nbsp;&nbsp;<label id="NuevaR"></label></p>
	<p style="width:500px; height:10px; padding-left:200px;"><a href="javascript:Rest()"><img src="./images/ticket.png" class="SinBorde" title="Enviar" /></a>&nbsp;&nbsp;<label id="NoCoin"></label></p>
	
	<p><div id="changePass" style="width:200px; height:10px; padding-left:20px;"></div></p>
	</div>	
  <div id="separador">
    </div>
    
    <div id="footer" style="padding-top:0px; text-align:right;" > <a href="http://www.sgs.cl/"><img src="images/footer_sgs.png" width="158" height="67" /></a></div>

</body>	
</div>