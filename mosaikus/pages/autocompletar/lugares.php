<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.lugar_evento.LugarEvento');
        session_name($GLOBALS[SESSION]);
        session_start();

        $pagina = new LugarEvento();

        $data = $pagina->listarLugarEvento(array('campo' => 'lugar_evento', 'valor' => $_GET["term"], 'corder' => 'lugar_evento', 'sorder' => 'asc'), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('id'=> $data[$i][0], 'lugar_evento' => $data[$i][1] . "");
        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
