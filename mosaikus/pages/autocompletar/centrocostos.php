<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	//include_once('configuracion/configuracion.php');
	import('clases.centro_costos.CentroCostos');

	$pagina = new CentroCostos();
        $data = $pagina->listarCentroCostos(array('campo' => 'concat(codigo, descripcion)', 'valor' => $_GET["term"], 'corder' => 'codigo', 'sorder' => 'asc'), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('id'=> $data[$i][0], 'codigo' => $data[$i][1] . "", 'descripcion' => $data[$i][2], 'empresa' => $data[$i][3]);
        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
