<?php        chdir('..');        chdir('..');        include_once('clases/clases.php');        include_once('configuracion/import.php');        include_once('configuracion/configuracion.php');        import('clases.arbol_procesos.ArbolProcesos');                session_name('mosaikus');                    session_start();        $pagina = new ArbolProcesos();        $CookNomEmpresaGeneral=$_SESSION[CookNomEmpresa];        $sql = "select title from mos_arbol_procesos where id=2";        $dat = $pagina->dbl->query($sql, array());	        $NomPrincipalEmpresa = $dat[0][title];		if ($_GET['id']!=0)		$selec_nodo = "$('#demo1').jstree(\"select_node\",\"#phtml_".$_GET['id']."\");";	?><!DOCTYPE htmlPUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	<title>jsTree v.1.0 - Demo</title>        <link href="../../mos_style.css" rel="stylesheet" type="text/css">	<script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.js"></script>	<script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.cookie.js"></script>	<script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.hotkeys.js"></script>	<script type="text/javascript" src="../../lib/jtreeview/jquery.jstree.js"></script>	<link type="text/css" rel="stylesheet" href="../../lib/jtreeview/_docs/!style.css"/>	<script type="text/javascript" src="../../lib/jtreeview/_docs/syntax/!script.js"></script>	<script type="text/javascript">	function submitMe() {	    document.getElementById('jsfields').value = $('#demo1').jstree('get_selected').attr('id');	}                function LLenaCargo(id) {                		$("#flash").hide();	    		var arr = id.split("_");		id = arr[1];                               window.parent.$('#b-id_proceso').val(id);               	}	</script></head><body><form name="formulario"><table border="0" width="100%">	<tr>		<td width="44%" colspan="2" class="LineasBlancasDIV" height="295px">			Seleccione &Aacute;rbol de Procesos:<br />			<div id="demo1" class="demo" style="height:295px;width:100%">				<ul>					<li id="phtml_2">						<a href="#"><?php echo $NomPrincipalEmpresa;?></a>						<?php                                                 echo $pagina->MuestraPadre();                                                ?>					</li>				</ul>			</div>		</td>	</tr></table><script type="text/javascript" >$(function () {		$("#demo1")				.jstree({						"plugins" : ["themes","html_data","ui","crrm","hotkeys"],						"core" : { "initially_open" : [ "phtml_1" ] }					})				.bind("loaded.jstree", function (event, data) {						$(this).jstree("open_all");		});	setTimeout(function () { $("#demo1").jstree("set_focus"); }, 500);		setTimeout(function () { <?php echo $selec_nodo?> }, 1000);		$("#demo1").bind("open_node.jstree", function (e, data) {		});	$("#demo1").bind("select_node.jstree", function(evt, data){                      LLenaCargo($('#demo1').jstree('get_selected').attr('id'));                    });	});</script>	<input type="hidden" name="jsfields" id="jsfields" value="" />	<input type="hidden" name="realiza_pregunta" value="SI" /></form></body></html>