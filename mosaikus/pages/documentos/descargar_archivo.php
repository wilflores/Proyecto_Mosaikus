<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_name('mosaikus');            
            session_start();
            chdir('..');
            chdir('..');
            include_once('clases/clases.php');
            include_once('configuracion/import.php');
            include_once('configuracion/configuracion.php');
            import('clases.documentos.Documentos');

            $pagina = new Documentos();
            if ((isset($_SESSION[CookIdUsuario]) ) && (md5($_GET[id]) == $_GET[token])){
                $accion = "Visualizó documento Fuente";
                $pagina->registraTransaccionLog(89,$accion,'', $_GET[id]); 

                $archivo_aux = $pagina->verDocumentoFuente($_GET[id]);
                $sql = "SELECT extension,contentType FROM mos_extensiones WHERE extension = '$archivo_aux[contentType]' OR contentType = '$archivo_aux[contentType]'";
                $total_registros = $pagina->dbl->query($sql, $atr);
                $Ext2 = $total_registros[0][extension];  
                $contenttype = $total_registros[0][contentType];
                $NombreDoc = $archivo_aux[nombre_doc];
                //echo $NombreDoc;
                $contenido2 = $archivo_aux[doc_fisico];
                //header("Content-type: application/pdf");
                //print $contenido2;
                $version = $archivo_aux[version];
                $Codigo = $archivo_aux[Codigo_doc];
                header("Content-type: $contenttype");
                $content_disposition = "Content-disposition: filename=\"".$Codigo."-".($NombreDoc)."-V".str_pad($version,2,0,STR_PAD_LEFT).".".$Ext2."\"";
                //echo $content_disposition;
                //exit();
                header($content_disposition);
                echo $contenido2;
                exit();
                $carpeta =  $pagina->encryt->Decrypt_Text($_SESSION[BaseDato]);
                $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);
                if ((isset($_GET[des]))&&($_GET[des] == '1')){
                    $documento->DescargarDocumento();
                }
                else {
                    $documento->VisualizaDocumento();
                }
                 
                
            }
?>
