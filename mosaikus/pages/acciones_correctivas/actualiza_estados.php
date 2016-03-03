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
                mos_adm_empresas where id_empresa=11';
    //QUITAR LA VALIDACION DE ID_EMPRESA=11
    $data = $pagina->query($Consulta, array());
    foreach( $data as $bds)
            {
                    //$OrgNom=$OrgNom.",".$Fila3[id_organizacion];
                    //echo "Sheme:".$bds[scheme]." BD:".$bds[db]." User:".$bds[loginDB]." psw:".$bds[passwordDB].'\n';
                    if($bds[db]!='' && $bds[loginDB]!='' && $bds[passwordDB]!=''){
                        $pagina2 = new Mysql($bds["db"],$bds["loginDB"],$bds["passwordDB"]);
                        //PRIMERO OBTENEMOS LOS ID DE LAS ACCIONES CORRECTIVAS QUE NO TIENEN VERIFICACION ACTIVADA
                        $Consulta ="select id from mos_acciones_correctivas where fecha_acordada is null;";
                        $data2 = $pagina2->query($Consulta, array());        
                        foreach( $data2 as $fila){
                            $id_ac .=$fila['id'].','; 
                        }                        
                        if($id_ac){
                            $id_ac = substr($id_ac, 0, strlen($id_ac)-1);
                            /*APLICA PARA LAS ACCIONES CORRECTIVAS*/
                            if($id_ac!='')
                            {
                                $Consulta ="update mos_acciones_ac_co
                                            set mos_acciones_ac_co.estado=1
                                            where 
                                            id_ac is not null and
                                            /*APLICA PARA LAS ACCIONES CORRECTIVAS*/
                                            mos_acciones_ac_co.fecha_acordada is NULL and 
                                            /*NO HAN SIDO CERRADAS*/
                                            DATEDIFF(mos_acciones_ac_co.fecha_acordada,NOW())<0 AND
                                            /*LA FECHA ACORDADA ES MENOR A LA FECHA ACTUAL*/
                                            id_ac IN (".$id_ac.") and
                                            /*SU ACCION CORRECTIVA PADRE AUN ESTA ABIERTA*/
                                            mos_acciones_ac_co.estado<>1;
                                            /*SI YA ESTA ATRASADA, NO LA ACTUALIZO OTRA VEZ */";
                                $data2 = $pagina2->insert_update($Consulta, array());
                                echo $bds[db].":'ACCIONES DE AC' Ejecutado el dia ". date('Y/m/d h:m')."\n";
                                $id_ac='';
                            }
                        }
                            //APLICA PARA LAS CORRECIONES*/
                            $Consulta ="update mos_acciones_ac_co
                                        set mos_acciones_ac_co.estado=1
                                        where 
                                        id_correcion is not null and
                                        /*APLICA PARA LAS CORRECIONES*/
                                        mos_acciones_ac_co.fecha_realizada is NULL and 
                                        /*NO HAN SIDO CERRADAS*/
                                        DATEDIFF(mos_acciones_ac_co.fecha_acordada,NOW())<0 AND
                                        /*LA FECHA ACORDADA ES MENOR A LA FECHA ACTUAL*/
                                        mos_acciones_ac_co.estado<>1;
                                        /*SI YA ESTA ATRASADA, NO LA ACTUALIZO OTRA VEZ */                                        
                                        ";
                            $data2 = $pagina2->insert_update($Consulta, array());
                            echo $bds[db].":'ACCIONES DE CO'  Ejecutado el dia ". date('Y/m/d h:m')."\n";
                        // NUEVO DEL 22/02/2016
                        // ACTUALIZAMOS LAS ACCIONES CORRECTIVAS ATRASADAS QUE TENGAN
                        // VERIFICACION ACTIVA y LA FECHA ACORDADA SEA MENOR AL DIA DE HOY
                        // Y QUE LAS ACCIONES ESTEN CERRADAS
                            $Consulta="UPDATE mos_acciones_correctivas "
                                    . " SET estado = 1, fecha_cambia_estado= now()  "
                                    . " WHERE fecha_acordada is not null and fecha_realizada is null and 
                                        DATEDIFF(fecha_acordada,NOW())<0 AND
                                        (select count(*) from mos_acciones_ac_co  where id_ac = mos_acciones_correctivas.id and fecha_realizada is null)=0;";
                            $data2 = $pagina2->insert_update($Consulta, array());
                            echo $bds[db].":'ACCIONES CORRECTIVAS'  Ejecutado el dia ". date('Y/m/d h:m')."\n";
                    }
            }
            
?>