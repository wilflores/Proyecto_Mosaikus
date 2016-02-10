<?php
 
            function Loading($parametros){
               if(isset($parametros['import'])){
                             import($parametros['import']);
                    }
                eval('$Obj = new '.$parametros['objeto'].'();');
                    eval('$objResponse = $Obj->'.$parametros['metodo'].'($parametros);');
                    return $objResponse;
            }

            chdir('..');
            chdir('..');
            include_once('clases/clases.php');
            include_once('configuracion/import.php');
            include_once('configuracion/configuracion.php');
            import('clases.correcciones.Correcciones');


            $pagina = new Correcciones();
            $pagina->registrar("Loading");
            $pagina->asigna_permiso($_POST['permiso']);
            $pagina->indexCorrecciones(array('permiso' => $_POST['permiso']));
            $pagina->show();


     ?>