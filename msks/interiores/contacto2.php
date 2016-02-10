<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link href="../divs/estilos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../includes/jquery-1.4.1.min.js"></script> 
<script type="text/javascript" src="../includes/jquery-impromptu.3.1.js"></script> 
<script type="text/javascript" src="../includes/funciones.js"></script> 
<script type="text/javascript">
function Envio()
{				
	var Nom=document.getElementById('name').value.replace(/ /gi, "///");
	var _Msj=document.getElementById('comment').value.replace(/ /gi, "///");
	var _Tel=document.getElementById('telefono').value.replace(/ /gi, "///");
	var _Mail=document.getElementById('email').value.replace(/ /gi, "///");
	var Nombre="envio.php?_Nom="+Nom+"&_Mail="+_Mail+"&_Tel="+_Tel+"&Envio=S&_Msj="+_Msj;	
	$("#Envio").load(Nombre); 
}	

</script>

<style type="text/css">
body {
	background-color: #c1cce2;
}
textarea { 
resize: none; 
}
</style>
</head>

<body>
<h4>Contacto</h4>
<br />
    <h1><img src="../images/contacto.png" width="542" height="289" /></h1>
</body>
</html>
