<?php
	include_once('configuracion/configuracion.php');

session_name($GLOBALS[SESSION]);
session_start();
session_unset();
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link href="diseno/css/mos_style.css" rel="stylesheet" type="text/css">
<link href="estilos/jquery.css" rel="stylesheet" type="text/css">

<body topmargin="0" leftmargin="0" background="diseno/images/Fondo.png" style="overflow:hidden;">
<form name="FrmPrincipalG" action=""method="post">
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" >
	<tr>
	<td width="100%" align="right" valign="top" ></td>
	</tr>
	<tr>
	<td width="100%" align="right" >

</td>
	</tr>
	<tr>
<tr><td><br /></td></tr>
	<td align="center">
<br/>

<table width="50%" align="center" border="0"  background="diseno/images/FondoDIV.png" cellpadding="5" cellspacing="0" >
			<tr align="center" >
			<td  colspan="5" align="center" >&nbsp;</td>
			</tr>
			<tr align="center" >
			<td colspan="5" align="center" ></td>
			</tr>
			<tr align="center" >
			<td width="3%" class="LetraGris_delgado" align="left" ></td>
			<td  colspan="3"class="LineasBlancasDIV" align="left" ><br /><br /><br />Su sesión ha caducado.<?
/*
echo "Unidad = ".$CookNomUnidad."  Emp = ".$CookNomEmpresa."  Filial = ".$CookFilial."  Nuser =  ".$CookNamUsuario;
echo " IdEmp = ".$CookIdEmpresa."  IdUS = ".$CookIdUsuario."  BD =".$Cookdatabase."  LOG = ".$CookLogginBD."  PWD = ".$CookPasswordDB;


*/?></td>
			<td width="3%" class="LetraGris_delgado" align="left" ></td>
			</tr>
			<tr>
<tr align="center" >
			<td  colspan="5" align="left" ><br/></td>
			</tr>
			<td width="3%" align="left"  class="InputRojo"></td>
                        <td colspan="3" align="left" ><a href="../msks/index.php"><span class="LetraBlanca10Link">Retornar a Página Principal</span></a></td>
			<td width="3%" class="LetraGris" align="left" ></td>
			</tr>
			<tr align="center" >
			<td  colspan="5" align="left" ></td>
			</tr>
		</table>



	</td></tr>
	</table>
</form>
</body>
</html>


