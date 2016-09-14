<?php

session_name('mosaikus');            
session_start();
chdir('..');
chdir('..');
if (!isset($_SESSION[CookIdUsuario])) {
        echo "<h1>Acceso denegado</h1>";
        exit();
}
include_once('clases/clases.php');
include_once('configuracion/import.php');
include_once('configuracion/configuracion.php');
import('clases.registros.Registros');
import('clases.documentos.Documentos');
include_once('clases/excel/PHPExcel.php');
$_GET[id] = $_SESSION[IDDoc];
$doc = new Documentos();
$reg = new Registros();
$datos = array();


$datos = $reg->exportarPHPExcelDocEncabezado($_GET);
//print_r($datos);
//$datosBd = $doc->exportarPHPExcelDatosBD($_GET);

$filename= $datos[Codigo_doc].'lista de registros.xlsx';
$titulo ='Formulario['.$datos[Codigo_doc].'-'.$datos[descripcion].']';
$titulo2 ='Formulario['.$datos[Codigo_doc].'-'.$datos[descripcion].'] -> Lista de Registros';
$hoja1='Formulario';
$hoja2='Lista de Registros';

$objPHPExcel = new PHPExcel(); // Create new PHPExcel object
$objPHPExcel->getProperties()->setCreator("Sigit prasetya n")
                             ->setLastModifiedBy("Sigit prasetya n")
                             ->setTitle("Creating file excel with php Test Document")
                             ->setSubject("Creating file excel with php Test Document")
                             ->setDescription("How to Create Excel file from PHP with PHPExcel 1.8.0 Classes by seegatesite.com.")
                             ->setKeywords("phpexcel")
                             ->setCategory("Test result file");
// create style
$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet();
$objActSheet->setCellValue('B4', $titulo); 
$objActSheet->setCellValue('B5', 'Fecha: '.date('d/m/Y')); 
$style_titulo = array(
    'font' => array(
        'bold' => true,
        'size' => 16,
    )
);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
$objActSheet->mergeCells('B4:D4'); 
$objPHPExcel->getActiveSheet()->getStyle('B4:B4')->applyFromArray( $style_titulo ); //
///////////////////////CUADRO RESUMEN /////////////////////////////////



$style_header_documento = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'E1E0F7'),
    ),
    'font' => array(
        'bold' => true,
        'size' => 10,
    ),
    'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        )
);

$style_header_documento_datos = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'font' => array(
        'size' => 10,
    ),
    'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'wrap text' => true,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
        )
);

//    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setWrapText(true);    
//    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

$style_header_semaforo_rojo = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'font' => array(
        'bold' => true,
        'size' => 18,
        'name' =>'Wingdings',
        'color' => array('rgb'=>'DF013A')
    )
);
$style_header_semaforo_verde = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'font' => array(
        'bold' => true,
        'size' => 18,
        'name' =>'Wingdings',
        'color' => array('rgb'=>'349B41')
    )
);

///////////////////////FIN CUADRO RESUMEN /////////////////////////////////

///////////////////////TABLA DE DATOS/////////////////////////////////
$default_border = array(
    'style' => PHPExcel_Style_Border::BORDER_THIN,
    'color' => array('rgb'=>'1006A3')
);
$style_content = array(
    'borders' => array(
        'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'eeeeee'),
    ),
    'font' => array(
        'size' => 12,
    )
);
/////PARA HACER EL ENCABEZADO DE LA TABLA
$col1 = 'B';
$col2 = 'C';
$col3 = 'D';
$fila=7;
//$objActSheet = $objPHPExcel->getActiveSheet();
  
                    
$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet(); 
foreach (array_keys($datos) as $value){
    $objActSheet->mergeCells($col2.$fila.':'.$col3.$fila);
    $fila++;
}
$fila=7;
foreach (array_keys($datos) as $value){
    //$nombre = $doc->cargar_nombres_columnas_xls($value);
    
    $objActSheet->setCellValue($col1.$fila, $doc->cargar_nombres_columnas_xls($value,'S')); 
    $objActSheet->setCellValue($col2.$fila, $datos[$value]); 
    $veces=1;
    if($value=='arbol_organizacional'){
        $veces = substr_count($datos[$value], "\n");        
    }
    $objPHPExcel->getActiveSheet()->getColumnDimension($col1)->setWidth(30);
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila)->getAlignment()->setWrapText(true);    
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila)->applyFromArray( $style_header_documento ); // give style to header
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
//      
    $objPHPExcel->getActiveSheet()->getColumnDimension($col2)->setWidth(40);
    $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(15*$veces);
    $objPHPExcel->getActiveSheet()->getStyle($col2.$fila.':'.$col3.$fila)->getAlignment()->setWrapText(true);    
    $objPHPExcel->getActiveSheet()->getStyle($col2.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento_datos ); // give style to header
    //$objActSheet->mergeCells($col2.$fila.':'.$col3.$fila);
    $fila++;
}

///////////////////////////////////////////////////////////////////
// CAMPOS DE PARAMETROS DE INDEXACION
$datos = $reg->exportarPHPExcelDocDatosIndexados($_GET);

$fila++;
$objActSheet->mergeCells($col1.$fila.':'.$col3.$fila);
$objActSheet->setCellValue($col1.$fila, 'Parámetros para indexación de Registros');
$objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento ); // give style to header
$objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$fila++;
$objActSheet->setCellValue($col1.$fila, 'Nombre');
$objActSheet->setCellValue($col2.$fila, 'Tipo');
$objActSheet->setCellValue($col3.$fila, 'Valores');
$objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento ); // give style to header
$objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$fila++;
foreach ($datos as $value){
    //$nombre = $doc->cargar_nombres_columnas_xls($value);    
    $objActSheet->setCellValue($col1.$fila, $value[Nombre]); 
    $objActSheet->setCellValue($col2.$fila, $value[tipo]); 
    $objActSheet->setCellValue($col3.$fila, $value[valores]); 
    $objPHPExcel->getActiveSheet()->getColumnDimension($col1)->setWidth(28);
    $objPHPExcel->getActiveSheet()->getColumnDimension($col2)->setWidth(28);
    $objPHPExcel->getActiveSheet()->getColumnDimension($col3)->setWidth(28);
//    $objPHPExcel->getActiveSheet()->getColumnDimension($col2)->setWidth(40);
//    $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(15*$veces);
    //$objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);    
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setWrapText(true);    
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento_datos ); // give style to header
    //$objActSheet->mergeCells($col2.$fila.':'.$col3.$fila);
    $fila++;
}
/////////////////////////////////////////////////////////////////////////////////////////////
///////// DOCUMENTOS RELACIONADOS
//
$html = $doc->verVisualizaDocumentosRelacionados($_GET[id]);
$datadocrel = $doc->dbl->data;   
if(sizeof($datadocrel)>0){
    $fila++;
    $objActSheet->mergeCells($col1.$fila.':'.$col3.$fila);
    $objActSheet->setCellValue($col1.$fila, 'Documentos Relacionados');
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento ); // give style to header
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//    $fila++;
//    $objActSheet->mergeCells($col1.$fila.':'.$col3.$fila);
//    $objActSheet->setCellValue($col1.$fila, 'Nombre');
//    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento ); // give style to header
//    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $fila++;
    foreach ($datadocrel as $value){
        //$nombre = $doc->cargar_nombres_columnas_xls($value);    
        $objActSheet->setCellValue($col1.$fila, $value[nombre]);
        $objActSheet->mergeCells($col1.$fila.':'.$col3.$fila);
        $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setWrapText(true);    
        $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento_datos ); // give style to header
        //$objActSheet->mergeCells($col2.$fila.':'.$col3.$fila);
        $fila++;
    }    
}
/////////////////////////////////////////////////////////////////////////////////////////////
///////// ANEXOS RELACIONADOS
//
$dataanexos = $doc->verAnexosDoc($_GET[id]);  
if(sizeof($dataanexos)>0){
    $fila++;
    $objActSheet->mergeCells($col1.$fila.':'.$col3.$fila);
    $objActSheet->setCellValue($col1.$fila, 'Anexos Relacionados');
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento ); // give style to header
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $fila++;
    $objActSheet->mergeCells($col2.$fila.':'.$col3.$fila);
    $objActSheet->setCellValue($col1.$fila, 'Nombre');
    $objActSheet->setCellValue($col2.$fila, 'Tipo');
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento ); // give style to header
    $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $fila++;
    foreach ($dataanexos as $value){
        //$nombre = $doc->cargar_nombres_columnas_xls($value);    
        $objActSheet->setCellValue($col1.$fila, $value[nomb_archivo]);
        $objActSheet->setCellValue($col2.$fila, $value[contenttype]);
        $objActSheet->mergeCells($col2.$fila.':'.$col3.$fila);
        $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->getAlignment()->setWrapText(true);    
        $objPHPExcel->getActiveSheet()->getStyle($col1.$fila.':'.$col3.$fila)->applyFromArray( $style_header_documento_datos ); // give style to header
        //$objActSheet->mergeCells($col2.$fila.':'.$col3.$fila);
        $fila++;
    }    
}

//// PARA ESCRIBIR LOS DATOS DE LA TABLA
////PARA LAS IMAGENES 
$objPHPExcel->setActiveSheetIndex(0); 
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('dist/images/logo_report.png');
$objDrawing->setName('Sample image');
$objDrawing->setDescription('Sample image');
$objDrawing->setWidthAndHeight(68,68);
$objDrawing->setResizeProportional(true);
$objDrawing->setCoordinates('A'.($fila+1));

$objDrawing2 = new PHPExcel_Worksheet_Drawing();
$objDrawing2->setPath('diseno/images/logo_empresa/'.$_SESSION[CookIdEmpresa].'_logo_empresa_report.png');
$objDrawing2->setName('Sample image');
$objDrawing2->setDescription('Sample image');
$objDrawing2->setWidthAndHeight(125,125);
//$objDrawing2->setHeight(28);
$objDrawing2->setResizeProportional(true);
$objDrawing2->setCoordinates('D1');

$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
/////////////////////////////////////////////////////////////////////////////////////////////
///////// REGISTROS BD

$objPHPExcel->createSheet(); 
$objPHPExcel->setActiveSheetIndex(1);
$objActSheet = $objPHPExcel->getActiveSheet();
$objActSheet->setTitle($hoja2);
$objActSheet->setCellValue('A1', $titulo2); 
$objActSheet->setCellValue('A2', 'Fecha: '.date('d/m/Y')); 
//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
$objActSheet->mergeCells('A1:E1'); 
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray( $style_titulo ); //
$columnas = $reg->cargar_nombres_columnas_xls();
$datos = $reg->exportarPHPExcelDatosBD($_GET,$columnas);
//print_r($columnas);
//print_r($datos);die;
///////////////////////////////////////////////////////////////////////////
///////////////TABLA SIN ESTILOS PARA LA DINAMICA//////////////////////////////////////
/////PARA HACER EL ENCABEZADO DE LA TABLA
$col = 0;
$fila=5;
//$objActSheet = $objPHPExcel->getActiveSheet();
$style_header_resumen_head = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'E1E0F7'),
    ),
    'font' => array(
        'bold' => true,
        'size' => 10,
    )
);
$style_header_sencillo = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'font' => array(
        'size' => 10,
    )
);
$style_header_semaforo_rojo = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'font' => array(
        'bold' => true,
        'size' => 18,
        'name' =>'Wingdings',
        'color' => array('rgb'=>'DF013A')
    )
);
$style_header_semaforo_verde = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID
    ),
    'font' => array(
        'bold' => true,
        'size' => 18,
        'name' =>'Wingdings',
        'color' => array('rgb'=>'349B41')
    )
);
$objActSheet = $objPHPExcel->getActiveSheet(); 
foreach (array_keys($columnas) as $value){
    //$nombre = $doc->cargar_nombres_columnas_xls($value);
    //echo strpos($value,"edo").'-'.strtolower($columnas[$value]);
    if(strstr($value,"edo") && strtolower($columnas[$value])=='vigencia'  && (substr($value, 0, 4) == 'edop')) {
            $col_vigencia=$col;
            //echo 'col:'.$value.'-valor:'.$columnas[$value].'-colnum:'. $col_vigencia;
    }

    $objActSheet->setCellValueByColumnAndRow($col,$fila, $columnas[$value]); 
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$fila)->applyFromArray( $style_header_resumen_head ); // give style to header
    $col++;
}
/////PARA HACER EL detalle DE LA TABLA
//print_r($datos);
//exit();
$col=0;
//print_r($datos);
//AJUSTAR COLUMNAS
foreach (array_keys($columnas) as $columna){
   //echo 'columna:'.$columna.'--';
    $row = 6;
    //echo($columna)."\n";
    foreach ($datos[$columna] as $value){
      // echo ($value).'-';
        if(($col_vigencia && $col==$col_vigencia)||(strstr($columna,"estado_semaforo"))){
           $objActSheet->setCellValueByColumnAndRow($col,$row, $value); 
           switch ($value) {                                             
                case 'L': 
                    $estilo=$style_header_semaforo_rojo;
                    break;
                case 'K': 
                    $estilo=$style_header_semaforo_verde;
                    break;
                case 'J': 
                    $estilo=$style_header_semaforo_verde;
                    break;
                default:
                    $estilo=$style_header_semaforo_verde;
                    break;
           }
           $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray( $estilo ); // give style to header
           $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        } 
        else {
            $objActSheet->setCellValueByColumnAndRow($col,$row, $value);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if($columna=='correlativo')
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getNumberFormat()->setFormatCode('00000');
            if(strstr($columna,"id_organizacion_hist"))
                $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(30);
            elseif(strstr($columna,"id_organizacion_act"))
                $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(40);
            else
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray( $style_header_sencillo ); // give style to header        
        }
       $row++;
    }
    //echo $col.'<br>'." \n" ;
    $col++;
}
//die;
////PARA LAS IMAGENES 
$objPHPExcel->setActiveSheetIndex(1); 
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('dist/images/logo_report.png');
$objDrawing->setName('Sample image');
$objDrawing->setDescription('Sample image');
$objDrawing->setWidthAndHeight(68,68);
$objDrawing->setResizeProportional(true);
$objDrawing->setCoordinates('A'.($row+1));

$objDrawing2 = new PHPExcel_Worksheet_Drawing();
$objDrawing2->setPath('diseno/images/logo_empresa/'.$_SESSION[CookIdEmpresa].'_logo_empresa_report.png');
$objDrawing2->setName('Sample image');
$objDrawing2->setDescription('Sample image');
$objDrawing2->setWidthAndHeight(125,125);
//$objDrawing2->setHeight(28);
$objDrawing2->setResizeProportional(true);
$objDrawing2->setCoordinates('F1');

$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
//print_r($datos);

//$objPHPExcel->getActiveSheet()->getStyle('B1:B100')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet(); 
$objActSheet->setTitle($hoja1);
////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////FIN TABLA CON ESTILOS//////////////////////////////////////

 

$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet(); 

//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter =new PHPExcel_Writer_Excel2007($objPHPExcel);

 
header('Content-Type: image/png');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
header("Content-Disposition: attachment;filename=$filename");
$objWriter->save('php://output');
 exit;          
            
    ?>

                    
                    

    