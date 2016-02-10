<?
include('bd_conect.php');
?>
<script language="javascript">
function hidden(){ document.getElementById('IngresoMail').style.visibility='hidden';document.getElementById('cerrar').style.visibility='hidden';document.getElementById('VisibleDiv').style.visibility='hidden';}
function Rest()
{
	if(emailCheck(document.getElementById('TxtCorrPass').value)==false)
		return;
	var Pass=document.getElementById("TxtCorrPass").value.replace('@','///');	
	document.getElementById('IngresoMail').style.visibility='hidden';document.getElementById('cerrar').style.visibility='hidden';	
	var Nombre="./login.php?"
	+"&TxtEmpresa="+document.getElementById("TxtEmpresaRes").value
	+"&TxtUsuario="+document.getElementById("TxtUsuarioRes").value
	+"&TxtCorrPass="+Pass
	+"&Opcion=RP";
	Rec(Nombre,'Lorg');
}
</script>
<style>
.LineasBlancasDIV
{
	border-bottom-color:#FFFFFF; border-bottom-style:solid; border-bottom-width:1px; 
	FONT-SIZE: 12px; COLOR:#FFFFFF; FONT-FAMILY:Arial;
}
</style>
<div id="VisibleDiv" style="width:900px;
	height:450px;
	POSITION:absolute; background-color:#FFFFFF; alpha(opacity=40); opacity: .5;
	left:220px;
	top:485px;
	z-index:700;
visibility:visible;">
</div>
<div id="cerrar" style="WIDTH:50%;
	height:20px;
	POSITION:absolute;
	left:318px;
	top:590px;
	z-index:900;
visibility:visible;"><a href="javascript:hidden()"><img src="./images/Cerrar.png" class="SinBorde" title="Cerrar" /></a></div>
<div id="IngresoMail" style="WIDTH:50%;
	height:20px;
	POSITION:absolute;
	left:320px;
	top:600px;
	z-index:800;
visibility:visible;">
<table width="100%" height="200" border="0" cellspacing="6" cellpadding="0" background="./images/FondoDIV.png">
  <tr>
    <td valign="top">
		  <table width="100%" cellpadding="0" border="0" cellspacing="0">
		  <tr class="formulario" height="20">
			<td width="15%" background="./images/fondo_contenido1.png" colspan="2" style="color:#0033CC; font-size:12px;">Restauración de contraseña</td>
		  </tr>
		  <tr class="formulario" height="25">
		    <td class="LineasBlancasDIV">Rut empresa</td>
		    <td class="LineasBlancasDIV"><? echo $TxtEmpresa;?><input type="hidden" name="TxtEmpresaRes" size="41" id="TxtEmpresaRes" class="CajaTexto" value="<? echo $TxtEmpresa;?>" /></td>
		    </tr>
		  <tr class="formulario" height="25">
		    <td class="LineasBlancasDIV">Rut usuario</td>
		    <td class="LineasBlancasDIV"><? echo $TxtUsuario;?><input type="hidden" name="TxtUsuarioRes" size="41" id="TxtUsuarioRes" class="CajaTexto" value="<? echo $TxtUsuario;?>" /></td>
		    </tr>
		  <tr class="formulario" height="25">
		    <td colspan="2" class="LineasBlancasDIV"><input type="text" name="TxtCorrPass" style="color:#000; background:#FFFFFF; height:20px;" size="54" id="TxtCorrPass" class="CajaTexto" value="Ingrese el correo que se registro en mosaikus aplicación." onfocus = "if(this.value=='Ingrese el correo que se registro en mosaikus aplicación.') this.value=''"
			onblur="if(this.value==false) this.value='Ingrese el correo que se registro en mosaikus aplicación.'" /> 
			</td>
		    </tr>
		  <tr class="formulario" height="30">
		    <td colspan="2"> <a href="javascript:Rest()"><img src="./images/ticket.png" class="SinBorde" title="Enviar" /></a><br />
			<label id="Mensajes" style="color:#FFFFFF; font-size:12px;"></label></td>
		    </tr>
	  </table>
	</td>
  </tr>
</table>
</div>
