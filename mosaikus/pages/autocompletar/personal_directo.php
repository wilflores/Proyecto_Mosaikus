<?php
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
        session_name($GLOBALS[SESSION]);
        session_start();
	import('clases.personas.Personas');

	$pagina = new Personas();
        $sql = "SELECT cod_emp
                                    ,id_personal
                                    ,initcap(nombres) nombres
                                    ,initcap(apellido_paterno) apellido_paterno
                                    ,initcap(apellido_materno) apellido_materno                                                                                                          
                                    ,email                                    
                            FROM mos_personal p
                            WHERE p.vigencia = 'S' AND p.interno = '1' AND CHAR_LENGTH(email) > 0";
        $data=$pagina->dbl->query($sql);
        $items = array();
        //$q = strtolower($_GET["q"]);
        for($i=0;$i<count($data);$i++){
            $items[]= array('id'=> $data[$i]['id_personal'], 'name' => $data[$i]['email'] . "", 'nombres' => $data[$i]['nombres'], 'apellido_pa' => $data[$i]["apellido_paterno"], 'apellido_ma' => $data[$i]["apellido_materno"]);
        }
        //if (!$q) return;
        echo json_encode($items);
//        foreach ($items as $key=>$value) {
//                if (strpos(strtolower($key), $q)!== false) {
//                        echo "$key|$value\n";
//                }
//        }

?>
