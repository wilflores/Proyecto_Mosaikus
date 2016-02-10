<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	//include_once('configuracion/configuracion.php');
	import('clases.repuestos.Repuestos');

	$pagina = new Repuestos();
        $data = $pagina->listarRepuestos(array('campo' => 'nombre', 'valor' => $_GET["term"], 'corder' => 'nombre', 'sorder' => 'asc'), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('id'=> $data[$i][0], 'nombre' => ($data[$i][1]), 'marca' => ($data[$i][2]), 'tipo' => $data[$i][4]);
        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
