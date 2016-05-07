<?php
    chdir('..');
    chdir('..');
    include('configuracion/configuracion.php');
    //include_once(dirname(dirname(dirname(__FILE__)))."/clases/bd/Mysql.php");
    include('clases/clases.php');
    
    //echo APPLICATION_DOWNLOADS;
    // Script Que copia el archivo temporal subido al servidor en un directorio.
    $type = $_FILES['fileUpload']['type'];
    $nombre = $_FILES['fileUpload']['name'];
    $data_galery = $target = 0;
    //print_r($_FILES);
    if (isset($_FILES['fileUpload']['name']) && ($_FILES['fileUpload']['size'] <= 1024*1024*3)) {
        switch (trim($type)){
//            case 'application/msword':
//                $tipo = 'doc';
//            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
//                $tipo = $tipo == '' ? 'docx' : $tipo;
//            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
//                $tipo = $tipo == '' ? 'xlsx' : $tipo;
//            case 'application/vnd.ms-powerpoint':
//                $tipo = $tipo == '' ? 'ppt' : $tipo;
//            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
//                $tipo = $tipo == '' ? 'pptx' : $tipo;
            case 'image/jpeg':
            case 'image/pjpeg':
                $tipo = $tipo == '' ? 'jpg' : $tipo;
            case 'image/png':
            case 'image/x-png':
                $tipo = $tipo == '' ? 'png' : $tipo;
            case 'application/pdf':
                $tipo = $tipo == '' ? 'pdf' : $tipo;
//            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
//                $tipo = $tipo == '' ? 'dotx' : $tipo;
//            case 'application/vnd.ms-excel.sheet.binary.macroEnabled.12':
//                $tipo = $tipo == '' ? 'xlsb' : $tipo;
//            case 'application/vnd.openxmlformats-officedocument.presentationml.template':
//                $tipo = $tipo == '' ? 'potx' : $tipo;
//            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.template':
//                $tipo = $tipo == '' ? 'xltx' : $tipo;
//            case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
//                $tipo = $tipo == '' ? 'ppsx' : $tipo;
//            case 'application/vnd.visio':
//                $tipo = $tipo == '' ? 'vsd' : $tipo;
//            case 'application/vnd.ms-excel':
//                $tipo = $tipo == '' ? 'xls' : $tipo;
                session_name("$GLOBALS[SESSION]");
                session_start();
                $encryt = new EnDecryptText();
                //$cadena_conn = 'mysqli://'.$encryt->Decrypt_Text($_SESSION[LoginBD]).':'.$encryt->Decrypt_Text($_SESSION[PwdBD]).'@127.0.0.1/'.$encryt->Decrypt_Text($_SESSION[BaseDato]);
                $cadena_conn = 'mysqli://root:A]bwzI[?Jv!=@127.0.0.1/'.$encryt->Decrypt_Text($_SESSION[BaseDato]);
                $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]),'root',PASSWORD);
                $sql = "INSERT INTO mos_evidencias_temp(tok,nomb_archivo,contenttype,id_usuario,estado) VALUES ('$_POST[tok]','".CambiaSinAcento($_FILES['fileUpload']['name'])."','".trim($type)."',$_SESSION[CookIdUsuario], -1);";
                $dbl->insert_update($sql);
                $sql = "SELECT IFNULL(max(id),0) + 1 as id FROM mos_evidencias_temp";
                $sql = "SELECT max(id) as id FROM mos_evidencias_temp";
                $data = $dbl->query($sql);
                if (!copy($_FILES['fileUpload']['tmp_name'], APPLICATION_DOWNLOADS. 'temp/' . md5($data[0][id]).'.'.$tipo)){
                    $funcion = 'Error al Subir el Archivo';
                    $exito = 0;
                    break;
                }
                else
                {
                    $exito = 1;
                    $tamano = filesize(APPLICATION_DOWNLOADS. 'temp/' . md5($data[0][id]).'.'.$tipo);
                    $tamano_visual = number_format($tamano/ 1024, 2);
                    if ($tipo != 'pdf'){
                        resizeImagen(APPLICATION_DOWNLOADS. 'temp/', md5($data[0][id]).'.'.$tipo, 800, 800,md5($data[0][id]).'.'.$tipo,$tipo);
                        $data_galery = 1;
                    }
                    else $target = 1;

                }
                $sql = "UPDATE mos_evidencias_temp SET id_md5 = '" . md5($data[0][id]).'.'.$tipo . "', estado = 1 where id = " . $data[0][id];
                $dbl->insert_update($sql);
                break;
            default:  //echo $type;
                //$funcion = "window.parent.VerMensaje('error', 'El archivo $nombre tiene un formato no permitido para el documento');";
                $funcion = "El archivo tiene un formato no permitido para el documento";
                //$funcion = "alert('El archivo $nombre tiene un formato no permitido para el documento');";


        }
    }
    else //$funcion = "window.parent.VerMensaje('error', 'El archivo $nombre no puedo cargarse');";
    //echo $type;//echo APPLICATION_DOWNLOADS.$_FILES['fileUpload']['tmp_name'];
    $funcion = "El tamaño del archivo $nombre excede el tamaño permitido en este sitio, Tamaño máximo del archivo a subir: 15MB";

    // Definimos Directorio donde se guarda el archivo
    $dir = 'archs/';
    // Intentamos Subir Archivo
    // (1) Comprovamos que existe el nombre temporal del archivo
    
    // (2) - Comprovamos que se trata de un archivo de im�gen
    
    // (3) Por ultimo se intenta copiar el archivo al servidor.
    
    
   
    
    

            $items = array();
            if ($exito == 1){ 
/*                window.parent.document.getElementById('tabla_fileUpload').style.display = 'none';
//                window.parent.document.getElementById('info_nombre').innerHTML = '<?php echo $nombre . " ($tamano_visual Kb)"; ?>';
<!--//                window.parent.document.getElementById('info_archivo_adjunto').style.display = '';
//                window.parent.document.getElementById('filename').value = '<?php echo $nombre; ?>';
//                window.parent.document.getElementById('tamano').value = '<?php echo $tamano; ?>';
//                window.parent.document.getElementById('tipo').value = '<?php echo $tipo; ?>';
//                window.parent.document.getElementById('estado_actual').value = '2';--> */
                
//$nombre . " ($tamano_visual Kb)"           
            
            $items[]= array('exito' => 1, 'target' => $target, 'gallery' => $data_galery, 'url' => 'downloads/temp/'.md5($data[0][id]).'.'.$tipo, 'id' => $data[0][id]);
        }
        else
            $items[]= array('exito' => 2, 'msj' => $funcion);//"El archivo $type tiene un formato no permitido para el documento");
        //if (!$q) return;
        echo json_encode($items);
         //   window.parent.document.getElementById('estado').style.display = 'none';
            
           ?>