<?php
    include('../../configuracion/configuracion.php');
    
function CambiaSinAcento($Texto)
{
	$Texto=str_replace('á','a',$Texto);
	$Texto=str_replace('Á','A',$Texto);
	$Texto=str_replace('é','e',$Texto);
	$Texto=str_replace('É','E',$Texto);
	$Texto=str_replace('í','i',$Texto);
	$Texto=str_replace('Í','I',$Texto);
	$Texto=str_replace('ó','o',$Texto);
	$Texto=str_replace('Ó','O',$Texto);
	$Texto=str_replace('ú','u',$Texto);
	$Texto=str_replace('Ú','U',$Texto);
	$Texto=str_replace('Ü','U',$Texto);
	$Texto=str_replace('ü','u',$Texto);
	$Texto=str_replace('Ñ','N',$Texto);
	$Texto=str_replace('ñ','n',$Texto);
	$Texto=str_replace('ñ','n',$Texto);
	return($Texto);
}
    //echo APPLICATION_DOWNLOADS;
    // Script Que copia el archivo temporal subido al servidor en un directorio.
    $type = $_FILES['fileUpload']['type'];
    $nombre = $_FILES['fileUpload']['name'];
    //print_r($_FILES);
    if (isset($_FILES['fileUpload']['name']) && ($_FILES['fileUpload']['size'] <= $_POST['MAX_FILE_SIZE'])) {
        switch (trim($type)){
//            case 'application/msword':
//                $tipo = 'doc';
//            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
//                $tipo = $tipo == '' ? 'docx' : $tipo;
            //case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            //    $tipo = $tipo == '' ? 'xlsx' : $tipo;
//            case 'application/vnd.ms-powerpoint':
//                $tipo = $tipo == '' ? 'ppt' : $tipo;
//            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
//                $tipo = $tipo == '' ? 'pptx' : $tipo;
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                $tipo = $tipo == '' ? 'jpg' : $tipo;
//            case 'image/png':
//            case 'image/x-png':
//                $tipo = $tipo == '' ? 'png' : $tipo;
            case 'application/pdf':
                $tipo = $tipo == '' ? 'pdf' : $tipo;
//            case 'application/vnd.ms-excel':
//                $tipo = $tipo == '' ? 'xls' : $tipo;
                if (!copy($_FILES['fileUpload']['tmp_name'], APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($_FILES['fileUpload']['name']))){
                    echo '<script> alert("Error al Subir el Archivo");</script>';
                }
                else
                {
                    $exito = 1;
                    $tamano = filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($_FILES['fileUpload']['name']));
                    $tamano_visual = number_format($tamano/ 1024, 2);
                }
                break;
            default:  //echo $type;
                //$funcion = "window.parent.VerMensaje('error', 'El archivo $nombre tiene un formato no permitido para el documento');";
                $funcion = "El archivo $type tiene un formato no permitido para el documento";
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
                
           
            
            $items[]= array('exito' => 1, 'info_nombre'=> $nombre . " ($tamano_visual Kb)", 'filename' => $nombre, 'tamano' => $tamano, 'tipo' => $tipo, 'estado_actual' => 2);
        }
        else
            $items[]= array('exito' => 2, 'msj' => $funcion);//"El archivo $type tiene un formato no permitido para el documento");
        //if (!$q) return;
        echo json_encode($items);
         //   window.parent.document.getElementById('estado').style.display = 'none';
            
           ?>