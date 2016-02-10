<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
        session_name($GLOBALS[SESSION]);
        session_start();
	import('clases.usuarios.Usuarios');


        $pagina = new Usuarios();
        $data = $pagina->listarUsuarios(array('campo' => 'nombre', 'valor' => $_GET["term"], 'corder' => 'nombre', 'sorder' => 'asc'), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('label' => $data[$i]['nombre'], 'id' => ($data[$i]['id']), 'correo' => $data[$i]['correo']);
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
