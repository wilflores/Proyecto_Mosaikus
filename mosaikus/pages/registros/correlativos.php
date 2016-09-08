<?php

    set_time_limit (500);
    chdir("../..");
    include_once(dirname(dirname(dirname(__FILE__)))."/clases/bd/MysqlBDP.php");
    include_once(dirname(dirname(dirname(__FILE__)))."/clases/bd/Mysql.php");

    $pagina = new MysqlBDP();
    /*SELECCIONAMOS LAS BDS Y OBTENEMOS CREDENCIALES*/
    $Consulta = 'SELECT
                id_empresa,
                businessName,
                address,
                scheme,
                db,
                loginDB,
                passwordDB
                FROM
                mos_adm_empresas  --where id_empresa=11
                ';
    //QUITAR LA VALIDACION DE ID_EMPRESA=11
    $data = $pagina->query($Consulta, array());
    foreach( $data as $bds)
            {
                    //$OrgNom=$OrgNom.",".$Fila3[id_organizacion];
                    //echo "Sheme:".$bds[scheme]." BD:".$bds[db]." User:".$bds[loginDB]." psw:".$bds[passwordDB].'\n';
                    if($bds[db]!='' && $bds[loginDB]!='' && $bds[passwordDB]!=''){
                        $pagina2 = new Mysql($bds["db"],$bds["loginDB"],$bds["passwordDB"]);
                        //PRIMERO OBTENEMOS LOS ID DE LAS ACCIONES CORRECTIVAS QUE NO TIENEN VERIFICACION ACTIVADA
                        $Consulta ="select distinct IDDoc from mos_registro;";
                        $data2 = $pagina2->query($Consulta, array());        
                        foreach( $data2 as $fila){
                            $sql = "update mos_registro "
                                    ." cross join (select @rownumber := 0) r "
                                    . " set correlativo = LPAD((@rownumber := @rownumber + 1),5,0)"
                                    . " where IDDoc=". $fila[IDDoc]." ";
                            $pagina2->insert_update($sql, array());
                            echo $sql."\n";
                        }                        
                    }
            }
            
?>