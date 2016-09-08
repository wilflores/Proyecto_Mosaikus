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
                $accion = "Visualizó documento PDF";
                $pagina->registraTransaccionLog(89,$accion,'', $_GET[id]); 
            
                $archivo_aux = $pagina->verDocumentoPDF($_GET[id]);
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
                $carpeta =  ((isset($_GET[cc]))&&($_GET[cc] == '1')) ? $pagina->encryt->Decrypt_Text($_SESSION[BaseDato])."/temp_cc" : $pagina->encryt->Decrypt_Text($_SESSION[BaseDato]);
                
                if ((isset($_GET[cc]))&&($_GET[cc] == '1')){
                    
                    $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);
                    $documento->ActivarDocumento();
                    
                    $template = new Template();
                    $template->PATH = PATH_TO_TEMPLATES.'documentos/';

                    //$contenido_1[TABLA] = $tabla[tabla];
                    $contenido_1['HOME'] = HOME;
                    //$contenido_1[FECHA] = date('d/m/Y');
                    //$contenido_1['N_PAG'] = '{PAGENO}/{nbpg}';
                    $contenido_1[ID_EMPRESA] = $_SESSION[CookIdEmpresa];
                    $template->setTemplate("portada_documentos");     
                    $template->setVars($contenido_1);                    
                    $paginas[] = ($template->show());
                    //echo $template->show();
                    $string = "";
                    require("clases/GenerarPDFReportes.php");
                    $pdf = new GenerarPDFReportes();
                    //echo 1;
                    //echo $template->show();
                    $ruta_portada = $pdf->pdf_create_reporte_portada($paginas, "portada" . $documento->nombre_archivo, false, 1, true, 0,$pagina->encryt->Decrypt_Text($_SESSION[BaseDato]));     

                    
                    include 'clases/PDFMerger/PDFMerger.php';

                    $pdf = new PDFMerger;

                    $pdf->addPDF($ruta_portada)//, '1, 3, 4')
                            //->addPDF('66.pdf')//, '1-2')
                            ->addPDF("downloads/tmp_doc/".$documento->nombre_carpeta."/".$documento->nombre_archivo, 'all')
                            ->merge('file', 'salida.pdf');
                }
                //else if ((isset($_GET[des]))&&($_GET[des] == '1')){
                //    $documento->DescargarDocumento();
                //}
                else {
                    header("Content-type: application/pdf");
                    $content_disposition = "Content-disposition: filename=\"".$NombreDoc."\"";
                    //echo $content_disposition;
                    //exit();
                    header($content_disposition);
                    echo $contenido2;
                    exit();
                    //$documento->VisualizaDocumento();
                    
                }
                
                 
                
            }
?>
