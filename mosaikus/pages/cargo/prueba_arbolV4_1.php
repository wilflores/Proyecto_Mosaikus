<?php//	chdir('..');//        chdir('..');//        include_once('clases/clases.php');//        include_once('configuracion/import.php');//        include_once('configuracion/configuracion.php');//        import('clases.personas.Personas');//        //        //        session_name('mosaikus');//            //ini_set("session.gc_maxlifetime", 60);//        session_start();//        //$pagina = new Personas();//                	//$Resp=mysql_query("select nombre from mos_empresa_filial where id_filial='".$CookFilial."'");	//$Fila=mysql_fetch_assoc($Resp);	//$CookNomEmpresaGeneral=utf8_encode($Fila["nombre"]);//        $CookNomEmpresaGeneral=$_SESSION[CookNomEmpresa];	//$NomPrincipalEmpresa=$Fila["nombre"];	//$resp=mysql_query("select title from mos_organizacion where id=2");	//$arr=mysql_fetch_assoc($resp);	//$CookNomEmpresaGeneral=utf8_encode($Fila["nombre"]);        $sql = "select title from mos_organizacion where id=2";        //$dat = $pagina->dbl->query($sql, array());	//$NomPrincipalEmpresa=$arr["title"];        $NomPrincipalEmpresa="Santa Teresa";        echo 111111111111;                /*        //$NomPrincipalEmpresa = $dat[0][title];	if ($_GET['cod_cargo']!=0) {            import('clases.cargo.Cargos');            $cargo = new Cargos();            $cargos_arbol = $cargo->verCargosArbol($_GET['cod_cargo']);            $selec_nodo = '';            foreach ($cargos_arbol as $value) {                $selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$value[id]."\");";            }//		$sql = "Select * From mos_cargo_estrorg_arbolproc Where cod_cargo = ".$_GET['cod_cargo'];//		$resp = mysql_query($sql);//		while($arr = mysql_fetch_assoc($resp)){//			$selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$arr[id]."\");";//		} // while	}	else if ($_GET['IDDoc']!=0) {               import('clases.documentos.Documentos');               $doc = new Documentos();               $doc_arbol = $doc->verArbol($_GET['IDDoc']);               $selec_nodo = '';                foreach ($doc_arbol as $value) {                    $selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$value[id_organizacion_proceso]."\");";                }//		$sql = "Select * From mos_documentos_estrorg_arbolproc Where IDDoc = ".$_GET['IDDoc'];//		$resp = mysql_query($sql);//		while($arr = mysql_fetch_assoc($resp)){//			$selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$arr[id_organizacion_proceso]."\");";//		} // while	}	else if ($_GET['Cod_Nivel']==1) {		$sql = "Select * From mos_cierre_mes_niveles";		$resp = mysql_query($sql);		while($arr = mysql_fetch_assoc($resp)){			$selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$arr[id_organizacion]."\");";		} // while	}         * *         */?><!--<!DOCTYPE htmlPUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	<title>jsTree v.1.0 - Demo</title>	<script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.js"></script>	<script type="text/javascript" src="../../lib/jtreeview/jquery.jstree.js"></script>	<link type="text/css" rel="stylesheet" href="../../lib/jtreeview/_docs/!style.css"/>	<script type="text/javascript" src="../../lib/jtreeview/_docs/syntax/!script.js"></script><script type="text/javascript">function submitMe() {    var checked_ids = [];    $("#demo1").jstree("get_checked",null,true).each(function(){        var arr = this.id.split("_");        id_aux = arr[1];        checked_ids.push(id_aux);    });    //setting to hidden field    document.getElementById('jsfields').value = checked_ids.join(",");}</script></head><body><form name="formulario"><div id="demo1" class="demo" style="height:300px; overflow-y: scroll;width: 100%">	<ul>		<li id="phtml_2">			<a href="#"><?php //echo $NomPrincipalEmpresa;?></a>			<?php //$obj_arbol = new DespliegaArbolOrganizacional();					//echo $obj_arbol->MuestraPadre();                        //echo $pagina->MuestraPadre();                        ?>		</li>	</ul></div><script type="text/javascript" >$(function () {	// TO CREATE AN INSTANCE	// select the tree container using jQuery	$("#demo1")		// call `.jstree` with the options object		.jstree({			// the `plugins` array allows you to configure the active plugins on this instance			"plugins" : [ "themes", "html_data", "checkbox", "sort", "ui" ],			// each plugin you have included can have its own config object			"core" : { "initially_open" : [ "phtml_1" ] }			// it makes sense to configure a plugin only if overriding the defaults		})		// EVENTS		// each instance triggers its own events - to process those listen on the container		// all events are in the `.jstree` namespace		// so listen for `function_name`.`jstree` - you can function names from the docs		.bind("loaded.jstree", function (event, data) {			// you get two params - event & data - check the core docs for a detailed description			$(this).jstree("open_all");		});	//$('#demo1').jstree("check_node","#phtml_2");	// INSTANCES	// 1) you can call most functions just by selecting the container and calling `.jstree("func",`	setTimeout(function () { $("#demo1").jstree("set_focus"); }, 500);	// with the methods below you can call even private functions (prefixed with `_`)	// 2) you can get the focused instance using `$.jstree._focused()`.	setTimeout(function () { <?php //echo $selec_nodo?> }, 1000);	// 3) you can use $.jstree._reference - just pass the container, a node inside it, or a selector	//setTimeout(function () { $.jstree._reference("#phtml_1").close_node("#phtml_1"); }, 1500);	// 4) when you are working with an event you can use a shortcut	$("#demo1").bind("open_node.jstree", function (e, data) {		// data.inst is the instance which triggered this event		//data.inst.select_node("#phtml_2", true);	});	//setTimeout(function () { $.jstree._reference("#phtml_1").open_node("#phtml_1"); }, 2500);});</script>	<input type="hidden" name="jsfields" id="jsfields" value="" />	<input type="hidden" name="realiza_pregunta" value="SI" /></form></body></html>-->