<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.documentos_subclasificacion.DocumentosSubClasificacion');

	$pagina = new DocumentosSubClasificacion();
        $data = $pagina->listarDocumentosSubClasificacion(array('id_clasificacion' => $_GET["id"], 'corder' => 'subclasificacion', 'sorder' => 'asc'), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('id'=> $data[$i][id], 'subclasificacion' => ($data[$i][subclasificacion]));
        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
