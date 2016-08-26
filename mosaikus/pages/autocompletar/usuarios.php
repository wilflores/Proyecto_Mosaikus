<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
        session_name($GLOBALS[SESSION]);
        session_start();
	import('clases.mos_usuario.mos_usuario');


        $pagina = new mos_usuario();
        $data = $pagina->listarmos_usuario(array('campo' => 'nombre', 'valor' => $_GET["term"], 'corder' => 'nombres', 'sorder' => 'asc'), 1, 10000);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('nombre' => $data[$i]['nombres'], 'id' => ($data[$i]['id_usuario']), 'name' => $data[$i]['email']);
        } 
        //if (!$q) return;
        //print_r($items);
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
