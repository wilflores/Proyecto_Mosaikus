<?php
    
    
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.organizacion.ArbolOrganizacional');
session_name("$GLOBALS[SESSION]");
                session_start();
	$pagina = new ArbolOrganizacional();
	
	$items = $pagina->admin_jstree_ao();
	

//echo '[{"id":2,"text":"Santa Teresa","children":true}]';
//$items = array(array('id'=>2,'text'=>'Santa Teresa','children'=>array(array('id'=>15,'text'=>'Gerencia General','children'=>true))));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($items);
?>
