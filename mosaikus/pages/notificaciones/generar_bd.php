<?php

    $ruta_actual = dirname(__FILE__);
    //determino si estoy en ambiente local o en el servidor
    if (preg_match("/home/",$ruta_actual ) == true) {
        $url_base = "https://api.mosaikus.com/";
        $sql_filtro_empresa = "";
    }
    else{
        $url_base = "http://localhost/mosaikus-service/public/";
        $sql_filtro_empresa = " and id_empresa=11";
    }
   
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
                mos_adm_empresas where id_empresa in (select id_empresa from mos_admin_usuarios group by id_empresa) ' . $sql_filtro_empresa;
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
                                        id_usuario
                                        FROM mos_usuario";
                        $datacorreos = $pagina2->query($sqlcorreos, array());
                        foreach ($datacorreos as $value) {
                            $ch = curl_init();
                            $url = $url_base . "company/$bds[id_empresa]/inspection/fwefwefwefwe/dataBD/$value[id_usuario]";
                            echo $url;
                            // Establecer URL y otras opciones apropiadas
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_HEADER, 0);

                            // Capturar la URL y pasarla al navegador
                            curl_exec($ch);

                            // Cerrar el recurso cURL y liberar recursos del sistema
                            curl_close($ch);
                        }
                        echo $bds[db].":'LISTA DISTRIBUCION'  Ejecutado el dia ". date('Y/m/d h:m')."\n";
                    }
            }
            
?>