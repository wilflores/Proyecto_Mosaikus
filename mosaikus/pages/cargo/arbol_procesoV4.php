<?php        
    chdir('..');        
    chdir('..');        
    include_once('clases/clases.php');        
    include_once('configuracion/import.php');        
    include_once('configuracion/configuracion.php');        
    import('clases.arbol_procesos.ArbolProcesos');        
    session_name('mosaikus');        
    session_start();                
    $arbol = new ArbolProcesos();        
    $sql = "select title from mos_arbol_procesos where id=2";        
    $dat = $arbol->dbl->query($sql, array());        
    /*print_r($dat);*/	
    $NomPrincipalProceso=$dat[0]["title"];                       
    if ($_GET['IDReg']!=0) {               
        import('clases.registros.Registros');               
        $reg = new Registros();               
        $reg_arbol =  explode(',',$reg->verArbolP($_GET['IDUnico'],$_GET['IDReg']));               
        $selec_nodo = '';                
        foreach ($reg_arbol as $value) {                    
            $selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$value."\");";                
            
        }	
        
    }?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
        <title>jsTree v.1.0 - Demo</title>	
        <script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.js"></script>	
        <script type="text/javascript" src="../../dist/js/registros/registros.js"></script>	
        <script type="text/javascript" src="../../lib/jtreeview/jquery.jstree.js"></script>	
        <link type="text/css" rel="stylesheet" href="../../lib/jtreeview/_docs/!style.css"/>	
        <script type="text/javascript" src="../../lib/jtreeview/_docs/syntax/!script.js">
        </script><script type="text/javascript">
            function submitMe() {    
                var checked_ids = [];    
                $("#demo1").jstree("get_checked",null,true).each(function(){        
                    var arr = this.id.split("_");        
                    id_aux = arr[1];        
                    checked_ids.push(id_aux);    
                });    
                document.getElementById('jsfields').value = checked_ids.join(",");
            }    
            
            function MarcarNodosP(i){        
                var iframe = parent.document.getElementById("iframearbolp_"+i);        
                iframe.contentWindow.submitMe();        
                parent.document.getElementById("nodosp_"+i).value = iframe.contentWindow.document.getElementById('jsfields').value;
            }
        </script>
    </head>
    <body>
        <form name="formulario">    
            <div onmouseout="<?php echo $_GET['funcion']?>"  onclick="<?php echo $_GET['funcion']?>" id="demo1" class="demo" style="height:300px; overflow-y: scroll;width: 100%">	
                <ul>		
                    <li id="phtml_2">			
                        <a href="#"><?php echo $NomPrincipalProceso;?></a>			
                            <?php echo $arbol->MuestraPadre();?>		
                    </li>	
                </ul>
            </div>
            <script type="text/javascript" >
                $(function () {		
                    $("#demo1").jstree({
                        "plugins" : [ "themes", "html_data", "checkbox", "sort", "ui" ],
                        "core" : { "initially_open" : [ "phtml_1" ] }
                    }).bind("loaded.jstree", function (event, data) {
                        $(this).jstree("open_all");		
                    });		
                    setTimeout(function () { $("#demo1").jstree("set_focus"); }, 500);		
                    setTimeout(function () { <?php echo $selec_nodo?> }, 1000);		
                    $("#demo1").bind("open_node.jstree", function (e, data) {});	
                });
            </script>	
            <input type="hidden" name="jsfields" id="jsfields" value="" />	
            <input type="hidden" name="realiza_pregunta" value="SI" />
        </form>
    </body>
</html>