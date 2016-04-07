<?php
require_once(dirname(__FILE__) . '/class.db.php');
require_once(dirname(__FILE__) . '/class.tree.php');

switch ($_SERVER['SERVER_NAME']) {
    case 'localhost':
        $cadena_conn = 'mysqli://root:123456@127.0.0.1/santateresa';
                //$id_empresa = 8;
                break;
    default:
        chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.arbol_procesos.ArbolProcesos');
        session_name("$GLOBALS[SESSION]");
        session_start();
        $encryt = new EnDecryptText();
        //$cadena_conn = 'mysqli://'.$encryt->Decrypt_Text($_SESSION[LoginBD]).':'.$encryt->Decrypt_Text($_SESSION[PwdBD]).'@127.0.0.1/'.$encryt->Decrypt_Text($_SESSION[BaseDato]);
        $cadena_conn = 'mysqli://root:A]bwzI[?Jv!=@127.0.0.1/'.$encryt->Decrypt_Text($_SESSION[BaseDato]);
                //$id_empresa = 8;
                break;
}

if(isset($_GET['operation'])) {
	$fs = new tree(db::get($cadena_conn), array('structure_table' => 'mos_organizacion', 'data_table' => 'mos_organizacion_nombres', 'data' => array('title')));
	try {
		$rslt = null;
		switch($_GET['operation']) {
			case 'analyze':
				var_dump($fs->analyze(true));
				die();
				break;
			case 'get_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 1;
                                $rslt = array();
                                //echo $node;
//                                $v = $fs->get_node($node);                               
////                                //foreach($temp as $v) {
//					$rslt[] = array('id' => $v['id'], 'text' => $v['title'], 'children' => ($v['right_a'] - $v['left_a'] > 1));
////				//}                                
				$temp = $fs->get_children($node);				                                
				foreach($temp as $v) {
					$rslt[] = array('id' => $v['id'], 'text' => $v['title'], 'children' => ($v['right_a'] - $v['left_a'] > 1));
				}
                                //print_r($rslt);
				break;
			case "get_content":
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 2;
				$node = explode(':', $node);
				if(count($node) > 1) {
					$rslt = array('content' => 'Multiple selected');
				}
				else {
					$temp = $fs->get_node((int)$node[0], array('with_path' => true));
					$rslt = array('content' => 'Selected: /' . implode('/',array_map(function ($v) { return $v['nm']; }, $temp['path'])). '/'.$temp['nm']);
				}
				break;
			case 'create_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 2;
				$temp = $fs->mk($node, isset($_GET['position']) ? (int)$_GET['position'] : 0, array('title' => isset($_GET['text']) ? $_GET['text'] : 'New node'));
				$rslt = array('id' => $temp);
				break;
			case 'rename_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$rslt = $fs->rn($node, array('title' => isset($_GET['text']) ? $_GET['text'] : 'Renamed node'));
				break;
			case 'delete_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$rslt = $fs->rm($node);
				break;
			case 'move_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
				$rslt = $fs->mv($node, $parn, isset($_GET['position']) ? (int)$_GET['position'] : 0);
				break;
			case 'copy_node':
				$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
				$parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
				$rslt = $fs->cp($node, $parn, isset($_GET['position']) ? (int)$_GET['position'] : 0);
				break;
			default:
				throw new Exception('Unsupported operation: ' . $_GET['operation']);
				break;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($rslt);
	}
	catch (Exception $e) {
		header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
		header('Status:  500 Server Error');
		echo $e->getMessage();
	}
	die();
}
?>
