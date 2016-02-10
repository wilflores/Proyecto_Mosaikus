<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.proceso.Proceso');
        session_name($GLOBALS[SESSION]);
        session_start();

        $pagina = new Proceso();

        $data = $pagina->listarProceso(array('campo' => 'nombre', 'valor' => $_GET["term"], 'corder' => 'nombre', 'sorder' => 'asc', 'area'=>$_GET[area]), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('id'=> $data[$i][0], 'proceso' => $data[$i][1] . "");
        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
