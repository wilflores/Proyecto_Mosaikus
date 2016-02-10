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
        $data = $pagina->listarUsuarios(array('campo' => 'concat(nombre)', 'valor' => $_GET["term"], 'corder' => 'nombre', 'sorder' => 'asc'), 1, 100);
        $data=$pagina->dbl->data;
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('label' => ($data[$i]['nombre']), 'value' => ($data[$i]['id']), 'desc' => $data[$i][correo]);
        }
//	import('clases.solicitantes.Solicitantes');
//
//
//        $pagina = new Solicitantes();
//        $data = $pagina->listarSolicitantes(array('campo' => 'concat(nombres , apellidos)', 'valor' => $_GET["term"], 'corder' => 'apellidos', 'sorder' => 'asc'), 1, 100);
//        $data=$pagina->dbl->data;
//        $items = array();
//        //$q = strtolower($_GET["q"]);
//        for($i=0;$i<count($data);$i++){
//            $items[]= array('value'=> $data[$i][0], 'label' => ($data[$i][2]) . ' ' . ($data[$i][1]), 'desc' => $data[$i][3]);
//        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
