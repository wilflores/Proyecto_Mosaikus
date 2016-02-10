<?php
    function Loading($parametros){
	   if(isset($parametros['import'])){
			 import($parametros['import']);
		}
	    eval('$Obj = new '.$parametros['objeto'].'();');
		eval('$objResponse = $Obj->'.$parametros['metodo'].'($parametros);');
		return $objResponse;
	}

        session_name('mosaikus');            
        session_start();
	chdir('..');
	chdir('..');
        include_once('clases/clases.php');
	include_once('configuracion/import.php');
	include_once('configuracion/configuracion.php');
	import('clases.acciones_correc.AccionesCorrectivas');

	$pagina = new AccionesCorrectivas();
        
        $sql = "SELECT * FROM mos_matrices_detalle WHERE id_item = 'Txt'";
        $data = $pagina->dbl->query($sql, array());
        //print_r($data);
        foreach ($data as $value) {
            $sql = "UPDATE mos_matrices_detalle SET descripcion_2 = '". utf8_encode($value[descripcion]) . "' WHERE (cod_categoria=$value[cod_categoria]) AND (id_acap='$value[id_acap]') AND (`id_cmb_acap`='$value[id_cmb_acap]') AND (`id_item`='Txt')";
            $pagina->dbl->insert_update($sql);
    
        }
        
        $sql = "SELECT * FROM mos_matrices_control_detalle WHERE id_item = 'Txt'";
        $data = $pagina->dbl->query($sql, array());
        //print_r($data);
        foreach ($data as $value) {                                                                                    //WHERE (`cod_categoria`='8') AND (`id_acap`='2') AND (`id_cmb_acap`='13') AND (`id_control_detalle`='2') AND (`id_item`='Wor')
            $sql = "UPDATE mos_matrices_control_detalle SET descripcion_2 = '". utf8_encode($value[descripcion]) . "' WHERE (cod_categoria=$value[cod_categoria]) AND (id_acap='$value[id_acap]') AND (`id_cmb_acap`='$value[id_cmb_acap]') AND (id_control_detalle='$value[id_control_detalle]') AND (`id_item`='Txt')";
            $pagina->dbl->insert_update($sql);
    
        }
        
	//$pagina->registrar("Loading");
	//$pagina->ver(array('id' => $_GET['id']));
	//$pagina->show();

?>
