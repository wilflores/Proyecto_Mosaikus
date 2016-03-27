<?php        
    chdir('..');        
    chdir('..');        
    include_once('clases/clases.php');        
    include_once('configuracion/import.php');        
    include_once('configuracion/configuracion.php');        
    import('clases.personas.Personas');        
    session_name('mosaikus');        
    session_start();                
    $pagina = new Personas();        
    $sql = "select title from mos_organizacion where id=2";        
    $dat = $pagina->dbl->query($sql, array());	
    $NomPrincipalEmpresa=$dat[0]["title"];                       
    if ($_GET['cod_cargo']!=0) {            
        import('clases.cargo.Cargos');            
        $cargo = new Cargos();            
        $cargos_arbol = $cargo->verCargosArbol($_GET['cod_cargo']);            
        $selec_nodo = '';            
        foreach ($cargos_arbol as $value) {                
            $selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$value[id]."\");";            

        }	    
    }	
    else if ($_GET['IDDoc']!=0) {               
        import('clases.documentos.Documentos');               
        $doc = new Documentos();               
        $doc_arbol = $doc->verArbol($_GET['IDDoc']);               
        $selec_nodo = '';                
        foreach ($doc_arbol as $value) {                    
            $selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$value[id_organizacion_proceso]."\");";                
            
        }	        
    }	
    else if ($_GET['Cod_Nivel']==1) {		
        $sql = "Select * From mos_cierre_mes_niveles";		
        $resp = mysql_query($sql);		
        while($arr = mysql_fetch_assoc($resp)){			
            $selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$arr[id_organizacion]."\");";		
            
        } 	        
    }   	
    else if ($_GET['IDReg']!=0) {               
        import('clases.registros.Registros');               
        $reg = new Registros();               
        $reg_arbol =  explode(',',$reg->verArbol($_GET['IDUnico'], $_GET['IDReg']));               
        $selec_nodo = '';                
        foreach ($reg_arbol as $value) {                    
            $selec_nodo .= "$('#demo1').jstree(\"check_node\",\"#phtml_".$value."\");";                                                                    
        }                
        if($selec_nodo!='')                    
            $selec_nodo .= "window.parent.VerificarCargoEdit('".$_GET['IDReg']."');";	
        
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
        <title>jsTree v.1.0 - Demo</title>	
        <script type="text/javascript" src="../../lib/jtreeview/_lib/jquery.js"></script>	
        <script type="text/javascript" src="../../lib/jtreeview/jquery.jstree.js"></script>	
        <link type="text/css" rel="stylesheet" href="../../lib/jtreeview/_docs/!style.css"/>	
        <script type="text/javascript" src="../../lib/jtreeview/_docs/syntax/!script.js"></script>
        <script type="text/javascript">
            var vienede='<?=$_GET['IDReg']?>';
            function SelectNodo(){    
                submitMe();    
                if(parent.document.getElementById("nodosreg")){        
                    parent.document.getElementById("nodosreg").value = document.getElementById('jsfields').value;        
                    window.parent.VerificarCargo(parent.document.getElementById("nodosreg").value,'<?=$_GET['IDReg']?>','<?=$_GET['IDUnico']?>');    
                }    
            }
            function submitMe() {    
                var checked_ids = [];    
                $("#demo1").jstree("get_checked",null,true).each(function(){        
                    var arr = this.id.split("_");       
                    id_aux = arr[1];        
                    checked_ids.push(id_aux);    
                });    
                document.getElementById('jsfields').value = checked_ids.join(",");
            }    
            function MarcarNodos(i){        
                var iframe = parent.document.getElementById("iframearbol_"+i);       
                /* var iframe = parent.document.getElementById("iframearbol");*/        
                iframe.contentWindow.submitMe();        
                parent.document.getElementById("nodos").value = iframe.contentWindow.document.getElementById('jsfields').value;       
                /* VerificarCargo(parent.document.getElementById("nodos_"+i).value);        
                   VerificarCargo2(parent.document.getElementById("nodos_"+i).value);*/    
            }    
        </script>
    </head>
    <body>
        <form name="formulario">    
            <div onmouseout="<?php/*php echo $_GET['funcion']*/?>"  onclick="<?php/*php echo $_GET['funcion']*/?>" id="demo1" class="demo" style="height:300px; overflow-y: scroll;width: 100%">	
                <ul>		
                    <li id="phtml_2">			
                        <a href="#"><?php echo $NomPrincipalEmpresa;?></a>			
                            <?php echo $pagina->MuestraPadre(); ?>		
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