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
                        $pagina2 = new Mysql($bds["db"],$bds["loginDB"],$bds["passwordDB"]);
                        //SELECCIONAMOS LOS CORREOS INVOLUCRADOS EN LOS WF Y LOS DOCS
                        $sqlcorreos = "SELECT
                                        distinct email
                                        from (
                                        SELECT
                                        elabora.email
                                        FROM
                                        mos_documentos AS doc
                                        INNER JOIN mos_personal AS elabora ON doc.elaboro = elabora.cod_emp
                                        where doc.id_workflow_documento is not null and etapa_workflow<>'estado_aprobado' and etapa_workflow is not null
                                        UNION all 
                                        SELECT
                                        revisa.email 
                                        FROM
                                        mos_documentos AS doc
                                        LEFT JOIN mos_personal AS revisa ON doc.reviso = revisa.cod_emp
                                        where doc.id_workflow_documento is not null and etapa_workflow<>'estado_aprobado' and etapa_workflow is not null
                                        UNION all 
                                        SELECT
                                        aprueba.email
                                        FROM
                                        mos_documentos AS doc
                                        LEFT JOIN mos_personal AS aprueba ON doc.aprobo = aprueba.cod_emp
                                        where doc.id_workflow_documento is not null and etapa_workflow<>'estado_aprobado' and etapa_workflow is not null
                                        union all 
                                        SELECT DISTINCT
                                        p.email
                                        FROM mos_documentos d
                                        left join mos_personal p on d.elaboro=p.cod_emp
                                        WHERE muestra_doc='S' ) correos
                                        where email is not null and email in (select email from mos_usuario where recibe_notificaciones='S')";
                        $datacorreos = $pagina2->query($sqlcorreos, array());
                        foreach( $datacorreos as $filacorreo){
                            $cuerpocorreo='';
                            //SELECCIONAMOS LOS DOCUMENTOS CON WF ACTIVOS QUE ESTEN PENDIENTE EN ALGUNA ETAPA
                            //PARA EL CORREO O PERSONAL EN $filacorreo
                            $Consulta ="select 
                            IDDoc,
                            Codigo_doc,
                            nombre_doc,
                            version,
                            descripcion,
                            case when estado_workflow='RECHAZADO' then 'Rechazado'
                            ELSE
                                    wftexto
                            end estadowf,
                            case when estado_workflow='RECHAZADO' then elaboro
                            ELSE
                                    case when etapa_workflow='estado_pendiente_revision' then reviso
                                    else aprobo
                                    END
                            end as persona,
                            case when estado_workflow='RECHAZADO' then email_elabora
                            ELSE
                                    case when etapa_workflow='estado_pendiente_revision' then email_revisa
                                    else email_aprueba
                                    END
                            end as email,
                            DATE_FORMAT(fecha_estado_workflow, '%d/%m/%Y') fecha
                            from 
                            (SELECT
                            doc.IDDoc,
                            doc.Codigo_doc,
                            doc.nombre_doc,
                            doc.version,
                            doc.descripcion,
                            doc.etapa_workflow,
                            doc.estado_workflow,
                            doc.fecha_estado_workflow,
                            mos_nombres_campos.texto wftexto,
                            CONCAT(initcap(elabora.nombres), ' ', initcap(elabora.apellido_paterno), ' ', initcap(elabora.apellido_materno)) AS elaboro,
                            CONCAT(initcap(revisa.nombres), ' ', initcap(revisa.apellido_paterno), ' ', initcap(revisa.apellido_materno)) AS reviso,
                            CONCAT(initcap(aprueba.nombres), ' ', initcap(aprueba.apellido_paterno), ' ', initcap(aprueba.apellido_materno)) AS aprobo,
                            elabora.email AS email_elabora,
                            revisa.email AS email_revisa,
                            aprueba.email AS email_aprueba
                            FROM
                            mos_documentos AS doc
                            INNER JOIN mos_personal AS elabora ON doc.elaboro = elabora.cod_emp
                            LEFT JOIN mos_personal AS revisa ON doc.reviso = revisa.cod_emp
                            LEFT JOIN mos_personal AS aprueba ON doc.aprobo = aprueba.cod_emp
                            INNER JOIN mos_nombres_campos ON doc.etapa_workflow = mos_nombres_campos.nombre_campo
                            where doc.id_workflow_documento is not null and etapa_workflow<>'estado_aprobado' and etapa_workflow is not null) docs
                            where (case when estado_workflow='RECHAZADO' then email_elabora
                                    ELSE
                                            case when etapa_workflow='estado_pendiente_revision' then email_revisa
                                            else email_aprueba
                                            END
                                    end)='".$filacorreo[email]."'
                            order by email,fecha_estado_workflow;";
                            //echo $Consulta;
                            $data = $pagina2->query($Consulta, array());
                            $email='';
                            $cuerpo='';
                            $nombre='';
                            $nombrecorreo='';
                            $celdas='';
                            foreach( $data as $fila){
                                $nombre ='Estimado&nbsp;<strong>'.$fila['persona'].'</strong>'; 
                                $nombrecorreo=$fila['persona'];
                                $celdas .='<tr><td>'.$fila[Codigo_doc].'-'.$fila[nombre_doc].'-V'. str_pad($fila["version"], 2, "0", STR_PAD_LEFT).'</td>';
                                $celdas .='<td>'.$fila[estadowf].'</td>';
                                $celdas .='<td>'.$fila[fecha].'</td></tr>';
                            }
                            if(sizeof($data)>0){
                            $cuerpo = $nombre;
                            $cuerpo .= "<br><br>A continuaci&oacute;n se detalla resumen del Sistema<br><br>";
                            $cuerpo .= "<strong>Documentos Flujo de Trabajo</strong><br>";
                            $cuerpo .= "<table width='80%'>";
                            $cuerpo .= "<tr><td width='40%'><strong>C&oacute;digo-Nombre-Versi&oacute;n</strong></td>";
                            $cuerpo .= "<td width='40%'><strong>Estado</strong></td>";
                            $cuerpo .= "<td width='20%'><strong>Fecha</strong></td></tr>";
                            $cuerpo .= $celdas;
                            $cuerpo .= "</table>";
                            $cuerpocorreo= $cuerpo;
                            }
                            
                            //SELECCIONAMOS LOS DOCUMENTOS A PUNTO DE VENCER Y VENCIDOS
                            //PARA EL CORREO O PERSONAL EN $filacorreo
                            $Consulta ="SELECT d.IDDoc                                    
                                        ,semaforo                                    
                                        ,ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE())) dias_vig
                                        , case when ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE()))<0 then 'Vencido'
                                                else case when ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE()))<semaforo then 'A vencer'
                                                else 'Ok' end 
                                        end estado
                                        , v_meses
                                        ,DATE_FORMAT(IFNULL(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),DATE_ADD(fecha,INTERVAL v_meses MONTH)), '%d/%m/%Y') fecha_vencimiento
                                        ,Codigo_doc
                                        ,nombre_doc
                                        ,CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno), ' ', initcap(p.apellido_materno))  persona
                                        ,v_meses                                    
                                        ,version
                                        ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha                                    
                                        ,DATE_FORMAT(fecha_revision, '%d/%m/%Y') fecha_rev
                                        ,p.email
                            FROM mos_documentos d
                                            left join mos_personal p on d.elaboro=p.cod_emp
                                            left join (select IDDoc, count(*) num_rev, max(fechaRevision) fecha_revision from mos_documento_revision GROUP BY IDDoc) as rev ON rev.IDDoc = d.IDDoc		
                            WHERE muestra_doc='S' and p.email='".$filacorreo[email]."' and 
                            (case when ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE()))<0 then 'Vencido'
                                    else case when ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE()))<semaforo then 'A vencer'
                                    else 'Ok' end 
                            end ) in ('Vencido','A vencer')
                            order by dias_vig asc;";
                            //echo $Consulta;
                            $data2 = $pagina2->query($Consulta, array());
                            $cuerpo='';
                            $celdas='';
                            foreach( $data2 as $fila){
                                if($nombre=='') {
                                    $nombre ='Estimado&nbsp;<strong>'.$fila['persona'].'</strong>'; 
                                    $nombrecorreo=$fila['persona'];
                                }
                                $celdas .='<tr><td>'.$fila[Codigo_doc].'-'.$fila[nombre_doc].'-V'. str_pad($fila["version"], 2, "0", STR_PAD_LEFT).'</td>';
                                $celdas .='<td>'.$fila[fecha_vencimiento].'&nbsp;('.$fila[dias_vig].'&nbsp;Dias)</td></tr>';
                            }
                            if(sizeof($data2)>0){
                                if(sizeof($data)==0) {
                                    $cuerpo = $nombre;
                                    $cuerpo .= "<br><br>A continuaci&oacute;n se detalla resumen del Sistema<br>";
                                }
                                $cuerpo .= "<br><strong>Documentos Pr&oacute;ximo a Vencer/Vencidos</strong><br>";
                                $cuerpo .= "<table width='60%'>";
                                $cuerpo .= "<tr><td width='55%'><strong>C&oacute;digo-Nombre-Versi&oacute;n</strong></td>";
                                $cuerpo .= "<td width='45%'><strong>Fecha</strong></td></tr>";
                                $cuerpo .= $celdas;
                                $cuerpo .= "</table>";
                                $cuerpocorreo .= $cuerpo;
                            }
                            //echo $cuerpo;
                            if($cuerpocorreo!=''){
                                $ut_tool = new ut_Tool();
                                //$filacorreo[email]='azambrano75@gmail.com';
                                $ut_tool->EnviarEMail('Notificaciones Mosaikus', array(array('correo' => $filacorreo[email], 'nombres'=>$nombrecorreo)), 'Notificaciones de Mosaikus', $cuerpocorreo);
                                //echo $cuerpocorreo;
                            }
                                //
                    }
                    echo $bds[db].":'NOTIFICACION CORREO'  Ejecutado el dia ". date('Y/m/d h:m')."\n";
                    }
            }
            
?>