<?php

    set_time_limit (500);
    chdir("../..");
    include_once(dirname(dirname(dirname(__FILE__)))."/clases/bd/MysqlBDP.php");
    include_once(dirname(dirname(dirname(__FILE__)))."/clases/bd/Mysql.php");
    include_once(dirname(dirname(dirname(__FILE__)))."/clases/utilidades/ut_Tool.php");
    include_once(dirname(dirname(dirname(__FILE__)))."/clases/PHPMailer_5.2.4/class.phpmailer.php");
    
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
                mos_adm_empresas -- where id_empresa=11';
    //QUITAR LA VALIDACION DE ID_EMPRESA=11
    $data = $pagina->query($Consulta, array());
    foreach( $data as $bds)
            {
                    //$OrgNom=$OrgNom.",".$Fila3[id_organizacion];
                    //echo "Sheme:".$bds[scheme]." BD:".$bds[db]." User:".$bds[loginDB]." psw:".$bds[passwordDB].'\n';
                    if($bds[db]!='' && $bds[loginDB]!='' && $bds[passwordDB]!=''){
                        $ut_tool = new ut_Tool();
                        $pagina2 = new Mysql($bds["db"],$bds["loginDB"],$bds["passwordDB"]);
                        //SELECCIONAMOS LOS CORREOS INVOLUCRADOS EN LOS WF Y LOS DOCS
                        $sqlcorreos = "SELECT
                                        correos.id,
                                        correos.id_entidad,
                                        correos.modulo,
                                        correos.asunto,
                                        correos.cuerpo,
                                        correos.email,
                                        correos.nombre
                                        FROM
                                        mos_correos_temporales AS correos
                                        WHERE
                                        correos.modulo = 'LISTA DISTRIBUCIÓN'
                                        ORDER BY
                                        correos.id ASC
                                        LIMIT 0, 20";
                        $datacorreos = $pagina2->query($sqlcorreos, array());
                        foreach( $datacorreos as $value){
                            //SELECCIONAMOS LOS DOCUMENTOS CON WF ACTIVOS QUE ESTEN PENDIENTE EN ALGUNA ETAPA
                            //PARA EL CORREO O PERSONAL EN $filacorreo
                            //echo $cuerpo;
                            $from = array(array('correo' => $value[email], 'nombres'=>$value[nombre]));
                            if($value[email]!=''){
                                //$filacorreo[email]='azambrano75@gmail.com';
                                $respuesta = $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $value[asunto], $value[cuerpo]);
                                if(preg_match("/OK/",$respuesta)){
                                    $respuesta = $pagina2->delete("mos_correos_temporales", "id = " . $value[id]);
                                }
                                //echo $cuerpocorreo;
                            }
                                //
                    }
                    echo $bds[db].":'LISTA DISTRIBUCION'  Ejecutado el dia ". date('Y/m/d h:m')."\n";
                    }
            }
            
?>