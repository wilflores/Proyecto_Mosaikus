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
            import('clases.Hoja_de_Vida.HojadeVida');
            $pagina = new HojadeVida();
            if ((isset($_SESSION[CookIdUsuario]) ) && (md5($_GET[id]) == $_GET[token])){
            
                $archivo_aux = $pagina->verHojadeVidaArchivo($_GET[id]);
                $NombreDoc = $archivo_aux[nom_archivo];
                //echo $NombreDoc;
                $contenido2 = $archivo_aux[archivo];
                //header("Content-type: application/pdf");
                //print $contenido2;
                $version = "HOJA_VIDA";
                $Codigo = $Ext2 = "";
                $carpeta =  $pagina->encryt->Decrypt_Text($_SESSION[BaseDato]);
                $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);
                $documento->VisualizaDocumento();
                 
                
            }

?>
