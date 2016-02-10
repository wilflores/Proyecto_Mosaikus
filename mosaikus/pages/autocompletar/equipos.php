<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
        session_name($GLOBALS[SESSION]);
        session_start();
	import('clases.equipos.Equipos');

	$pagina = new Equipos();
        $data = $pagina->listarEquipos(array('campo' => 'equipo', 'valor' => $_GET["term"], 'id_area' => $_GET[id_area], 'id_proceso' => $_GET[id_proceso], 'corder' => 'equipo', 'sorder' => 'asc'), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('id'=> $data[$i][id], 'equipo' => $data[$i][equipo], 'area' => $data[$i][area], 'proceso' => $data[$i][proceso]
                , 'id_area' => $data[$i][id_area], 'id_proceso' => $data[$i][id_proceso]);
        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
