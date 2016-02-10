<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?
	include("includes/bd_conect.php");
	$Consulta="Select * from mos_carrusel where vigente='S' order by codigo";
	$Resp=mysql_query($Consulta);$Cont=0;
	$Cant_Filas=mysql_num_rows($Resp);
	if($Cant_Filas >1 )
	{	
	?>
    <style type="text/css">
		*{padding:0;margin:2;}
		#diapos-on{ -moz-box-shadow:0px 0px 0px #000;-webkit-box-shadow:0px 0px 00px #000;box-shadow:0px 0px 00px #000;width:915px;height:350px;position:relative;}
		#diapos-on li{position:absolute;top:0;left:0;width:915px;height:350px;list-style:none; }
		#diapos-on li a{display:block;position:absolute; left:0;bottom:0;height:50px;width:915px;text-decoration:none;font-size:110%;font-weight:bold;color:#FFF;text-shadow:0px 2px 3px #000;}
		#contador{margin-right:20px; background-color:#FF0000;  }
			/* BOTONES */#contador li{ background:#99FF33; float:right; margin-top:0px; height:10px;margin-right:5px;margin-bottom:5px;list-style:none;-moz-box-shadow:0 1px 4px #000;-webkit-box-shadow:0 1px 4px #000;box-shadow:0 1px 4px #000;-moz-border-radius:5px;-webkit-border-radius:5px;  }
			#contador li a{display:block;text-indent:-999em;background:#4F4E54;width:10px;height:10px;overflow:hidden;-moz-border-radius:0px;-webkit-border-radius:5px;}
		#contador li a:focus{border:1px solid #99969D;outline:none;}
		#contador li.actual a{background:#99969D;} /* PUNTO SE SELECCION*/
	</style>
    <script type="text/javascript" src="includes/DP-old.js"></script>
    <script type="text/javascript">
		window.onload = DP.inicio;
	</script>
	<?
	}
	?>	
</head><body>
    <?
	if($Cant_Filas >1 )
	{
	?>	
	<ul id="diapos" style="height:230px;">
	<?
	}
	?>	
	<?PHP
		$Consulta="Select * from mos_carrusel where vigente='S' order by codigo";
		$Resp=mysql_query($Consulta);$Cont=0;
		while($Fila=mysql_fetch_array($Resp))
		{$Cont=$Cont+1;
		?>
			<?
			if($Cant_Filas >1 )
			{
			?>	
			 <li>
			<?
			}
			?>	
			 <table width="98.5%" border="0" cellpadding="0" cellspacing="0" background="<? echo $Fila[imagen];?>" >
			 <tr>
			 <td>
				 <table width="75%" height="250px" border="0" cellpadding="0" cellspacing="3" >
				 <tr>
				 <td height="30px" style="color:#000066; font-size:20px; padding-left:5px;"><? echo utf8_encode($Fila[titulo]);?></td>
				 </tr>
				 <tr>
				 <td align="left" valign="top" style="color:#CCCCCC; font-size:12px; padding-left:5px;" >
				 <? echo trim(utf8_encode($Fila[descripcion]));?></td>
				 </tr>
				 </table>
			 </td>
			 </tr>	
			 </table>
			 <a href="#d<? echo $Cont;?>"></a>
			<?
			if($Cant_Filas >1 )
			{
			?>	
			 </li>
			<?
			}
			?>	
  			<?PHP
		}?>
    <?
	if($Cant_Filas >1 )
	{
	?>	
    </ul>
	<?
	}
	?>	
  
</body>
</html>