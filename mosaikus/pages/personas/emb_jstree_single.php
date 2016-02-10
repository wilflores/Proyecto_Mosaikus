<?php
	chdir('..');
        chdir('..');
        include_once('clases/clases.php');
        include_once('configuracion/import.php');
        include_once('configuracion/configuracion.php');
        import('clases.personas.Personas');
        
        session_name('mosaikus');
            //ini_set("session.gc_maxlifetime", 60);
        session_start();
        $pagina = new Personas();
        
        

	//$Resp=mysql_query("select nombre from mos_empresa_filial where id_filial='".$CookFilial."'");
	//$Fila=mysql_fetch_assoc($Resp);
	//$CookNomEmpresaGeneral=utf8_encode($Fila["nombre"]);
        $CookNomEmpresaGeneral=$_SESSION[CookNomEmpresa];

	//$NomPrincipalEmpresa=$Fila["nombre"];

	//$resp=mysql_query("select title from mos_organizacion where id=2");
	//$arr=mysql_fetch_assoc($resp);
	//$CookNomEmpresaGeneral=utf8_encode($Fila["nombre"]);

	$sql = "select title from mos_organizacion where id=2";
        $dat = $pagina->dbl->query($sql, array());
	//$NomPrincipalEmpresa=$arr["title"];
        //$NomPrincipalEmpresa="Santa Teresa";
        $NomPrincipalEmpresa = $dat[0][title];

	//echo "AAA:".$_GET['id']."BBB";
	if ($_GET['id']!=0) {
		$selec_nodo = "$('#demo1').jstree(\"select_node\",\"#phtml_".$_GET['id']."\");";
		$cod_cargo = $_GET['cod_cargo'];
	}
	else {
		$opciones = "<option value=\"-1\">Ingrese &Aacute;rbol Organizacional antes de especificar un cargo</option>";
		$cod_cargo = "-1";
	}
	//echo "<br />:".$selec_nodo;
?>


<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>jsTree v.1.0 - Demo</title>
	<link href="../../mos_style.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.js"></script>
	<script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.cookie.js"></script>
	<script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.hotkeys.js"></script>
	<script type="text/javascript" src="../../lib/jtreeview/jquery.jstree.js"></script>

	<link type="text/css" rel="stylesheet" href="../../lib/jtreeview/_docs/!style.css"/>
	<script type="text/javascript" src="../../lib/jtreeview/_docs/syntax/!script.js"></script>

	<script type="text/javascript">
	function submitMe() {
	    document.getElementById('jsfields').value = $('#demo1').jstree('get_selected').attr('id');
	}

	function LLenaCargo(id) {
		//alert("Aqui");
                
		$("#flash").hide();
	    //$("#flash").fadeIn(400).html('<img src="images/loading.gif" />');

		var arr = id.split("_");
		id = arr[1];
		/*//alert("SSSS"+id);
		$.ajax({
					url:"emb_procesa_cargo.php",
					type: "POST",
					data:"idmarca="+id+"&cod_cargo="+cod_cargo,
					success: function(opciones){
						$("#CmbCargoFiltro").html(opciones);
						//$("#flash").hide();
						$("#flash").show();
					}
				})
                                */
               window.parent.$('#b-id_organizacion').val(id);               
	}


	</script>
</head>
<body>
<form name="formulario">
<table border="0" width="100%">
	<tr>
		<td width="44%" colspan="2" class="LineasBlancasDIV" height="300px">
                    <label class="control-label" style="color: white;font-size: 12px; font-family: 'Helvetica';padding-top: 2px;">&Aacute;rbol Organizacional:</label><br />
			<div id="demo1" class="demo" style="height:300px;width:100%">
				<ul>
					<li id="phtml_2">
						<a href="#"><?php echo $NomPrincipalEmpresa;?></a>
						<?php //$obj_arbol = new DespliegaArbolOrganizacional();
								//echo $obj_arbol->MuestraPadre();
                                                echo $pagina->MuestraPadre();
                                                ?>
					</li>
				</ul>
			</div>
		</td>
	</tr>
    <!--
	<tr>
		<td class="LineasBlancasDIV" valign="top">
		<div id="flash">
				Cargo:
			<select id="CmbCargoFiltro" class="ComboBox">
				<?php echo $opciones;?>
			</select>
		</div>
		</td>
	</tr>
    -->
</table>







<script type="text/javascript" >
$(function () {
	// TO CREATE AN INSTANCE
	// select the tree container using jQuery
	$("#demo1")
		// call `.jstree` with the options object
		.jstree({
			// the `plugins` array allows you to configure the active plugins on this instance
			"plugins" : ["themes","html_data","ui","crrm","hotkeys"],
			// each plugin you have included can have its own config object
			"core" : { "initially_open" : [ "phtml_1" ] }
			// it makes sense to configure a plugin only if overriding the defaults
		})
		// EVENTS
		// each instance triggers its own events - to process those listen on the container
		// all events are in the `.jstree` namespace
		// so listen for `function_name`.`jstree` - you can function names from the docs
		.bind("loaded.jstree", function (event, data) {
			// you get two params - event & data - check the core docs for a detailed description
			$(this).jstree("open_all");
		});


	//$('#demo1').jstree("check_node","#phtml_2");
	// INSTANCES
	// 1) you can call most functions just by selecting the container and calling `.jstree("func",`
	setTimeout(function () { $("#demo1").jstree("set_focus"); }, 500);
	// with the methods below you can call even private functions (prefixed with `_`)
	// 2) you can get the focused instance using `$.jstree._focused()`.
	setTimeout(function () { <?php echo $selec_nodo?> }, 1000);
	// 3) you can use $.jstree._reference - just pass the container, a node inside it, or a selector
	//setTimeout(function () { $.jstree._reference("#phtml_1").close_node("#phtml_1"); }, 1500);
	// 4) when you are working with an event you can use a shortcut
	$("#demo1").bind("open_node.jstree", function (e, data) {
		// data.inst is the instance which triggered this event
		//data.inst.select_node("#phtml_2", true);

	});

	$("#demo1").bind("select_node.jstree", function(evt, data){
            //selected node object: data.inst.get_json()[0];
            //selected node text: data.inst.get_json()[0].data
            //alert($('#demo1').jstree('get_selected').attr('id'));
            LLenaCargo($('#demo1').jstree('get_selected').attr('id'));
            //Aqui llamada a funcion ajax para llenar la lista de cargos....
        }
);
	//setTimeout(function () { $.jstree._reference("#phtml_1").open_node("#phtml_1"); }, 2500);
});
</script>
	<input type="hidden" name="jsfields" id="jsfields" value="" />
	<input type="hidden" name="realiza_pregunta" value="SI" />
</form>

</body>
</html>
<?php
class DespliegaArbolOrganizacional {

	function __construct(){

	}

	public function MuestraPadre(){
		$sql="Select * from mos_organizacion
				Where parent_id = 2";
		$resp = mysql_query($sql);
		$cabecera_padre = "<ul>";
		$padre_final = "";
		while($arrP=mysql_fetch_assoc($resp)){
			$cuerpo .= "<li id=\"phtml_".$arrP[id]."\">
							<a href=\"#\">".utf8_encode($arrP[title])."</a>
							".$this->MuestraHijos($arrP[id])."
						</li>";
		}
		$pie_padre = "</ul>";
		return $cabecera_padre.$cuerpo.$pie_padre;
	}

	public function MuestraHijos($id){
		$sql="select * from mos_organizacion
				Where parent_id = $id";
		$resp = mysql_query($sql);
		$cabecera = "<ul>";
		while($arr=mysql_fetch_assoc($resp)){
			$extra .= "<li id=\"phtml_".$arr[id]."\">
							<a href=\"#\">".utf8_encode($arr[title])."</a>
							".$this->MuestraHijos($arr[id])."
						</li>";		}
		$pie = "</ul>";
		return $cabecera.$extra.$pie;
	}
}
?>