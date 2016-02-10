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
            //import('clases.evidencia_acciones.EvidenciaAccionesCorrec');
            
            import('clases.acciones_evidencia.AccionesEvidencia');
            $pagina = new AccionesEvidencia();

            //$pagina = new EvidenciaAccionesCorrec();
            if ((isset($_SESSION[CookIdUsuario]) ) && (md5($_GET[id]) == $_GET[token])){
            
                $archivo_aux = $pagina->verDocumentoPDF($_GET[id]);
                //print_r($archivo_aux);
                $sql = "SELECT extension FROM mos_extensiones WHERE extension = '$archivo_aux[contentType_visualiza]' OR contentType = '$archivo_aux[contentType_visualiza]'";
                $total_registros = $pagina->dbl->query($sql, $atr);
                $Ext2 = $total_registros[0][extension];
                $NombreDoc = $archivo_aux[nom_visualiza].'.'.$Ext2;
                //echo $NombreDoc;
                $contenido2 = $archivo_aux[doc_visualiza];
                //header("Content-type: application/pdf");
                //
                //                            
                                    
                //print $contenido2;
                $version = "HOJA_VIDA";
                $Codigo = $Ext2 = "";
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
