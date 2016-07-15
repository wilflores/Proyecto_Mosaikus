<?php
header('Content-Type: image/png');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="listproduct.xls"'); // file name of excel
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
?>
<?php

session_name('mosaikus');            
session_start();
chdir('..');
chdir('..');
include_once('clases/clases.php');
include_once('configuracion/import.php');
include_once('configuracion/configuracion.php');
import('clases.documentos.Documentos');
include_once('clases/excel/PHPExcel.php');
//print_r($_GET);
$doc = new Documentos();
$datos = array();
$datos = $doc->exportarPHPExcelMaestro($_GET);
$datosBd = $doc->exportarPHPExcelDatosBD($_GET);

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
$objActSheet->setCellValue('A1', 'Maestro de Documentos'); 
$objActSheet->setCellValue('A2', 'Fecha: '.date('d/m/Y')); 
$style_titulo = array(
    'font' => array(
        'bold' => true,
        'size' => 14,
    )
);
$objPHPExcel->getActiveSheet()->getStyle('A1:A1')->applyFromArray( $style_titulo ); //
///////////////////////CUADRO RESUMEN /////////////////////////////////

$objActSheet->setCellValue('A4', 'Vigentes'); 
$objActSheet->setCellValue('A5', 'Por Vencer'); 
$objActSheet->setCellValue('A6', 'Vencidos'); 
$objActSheet->setCellValue('A7', 'Total');

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
$objPHPExcel->getActiveSheet()->getStyle('A4:A7')->applyFromArray( $style_header_resumen_head ); //
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
$objPHPExcel->getActiveSheet()->getStyle('B4:B7')->applyFromArray( $style_header_sencillo ); //
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
$col = 0;
$fila=11;
//$objActSheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet(); 
foreach (array_keys($datos) as $value){
    //$nombre = $doc->cargar_nombres_columnas_xls($value);
    $objActSheet->setCellValueByColumnAndRow($col++,$fila, $doc->cargar_nombres_columnas_xls($value)); 
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col-1,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col-1,$fila)->applyFromArray( $style_header_resumen_head ); // give style to header
}
//// PARA ESCRIBIR LOS DATOS DE LA TABLA
$col=0;
//$objActSheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet(); 
//print_r($datos);
$vencidas=$vigentes=$porvencer=0;
foreach ($datos as $fila){
    $row = 12;
    foreach ($fila as $value){
      //  echo $value.' ';
        $objActSheet->setCellValueByColumnAndRow($col,$row++, $value);
        if($col==0){
           switch ($value) {                                             
                case 'L': 
                    $estilo=$style_header_semaforo_rojo;
                    $vencidas++;
                    break;
                case 'K': 
                    $estilo=$style_header_semaforo_verde;
                    $porvencer++;
                    break;
                case 'J': 
                    $estilo=$style_header_semaforo_verde;
                    $vigentes++;
                    break;
           }
           $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->applyFromArray( $estilo ); // give style to header
           $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }
        else{
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->applyFromArray( $style_header_sencillo ); // give style to header
        }
    }
    //echo '<br>';
    $col++;
}
////CUADRO RESUMEN
$objActSheet->setCellValue('B4', $vigentes); 
$objActSheet->setCellValue('B5', $porvencer); 
$objActSheet->setCellValue('B6', $vencidas); 
$objActSheet->setCellValue('B7', $vigentes+$porvencer+$vencidas);
$objActSheet->mergeCells('E1:F1'); 
////PARA LAS IMAGENES 
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('diseno/images/logo_word_pdf_excel.png');
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
$objDrawing2->setCoordinates('E1');

$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet(); 
$objActSheet->setTitle('Maestro de Documentos');
////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////FIN TABLA CON ESTILOS//////////////////////////////////////

 

///////////////////////////////////////////////////////////////////////////
///////////////TABLA SIN ESTILOS PARA LA DINAMICA//////////////////////////////////////

$objPHPExcel->createSheet(); 
$objPHPExcel->getSheet(1)->setTitle('Documentos BD'); 

/////PARA HACER EL ENCABEZADO DE LA TABLA
$col = 0;
$fila=1;
//$objActSheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->setActiveSheetIndex(1); 
$objActSheet = $objPHPExcel->getActiveSheet(); 
foreach (array_keys($datosBd) as $value){
    //$nombre = $doc->cargar_nombres_columnas_xls($value);
    if($value=='Cantidad_codigo')$col_cod=$col;
    if($value=='id_organizacion')
        $objActSheet->setCellValueByColumnAndRow($col++,$fila, $doc->cargar_nombres_columnas_xls('Árbol Organizacional (Niveles)')); 
    else
        $objActSheet->setCellValueByColumnAndRow($col++,$fila, $doc->cargar_nombres_columnas_xls($value)); 
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col-1,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col-1,$fila)->applyFromArray( $style_header_resumen_head ); // give style to header
}
/////PARA HACER EL detalle DE LA TABLA
$col=0;
//$objActSheet = $objPHPExcel->getActiveSheet();

//print_r($datos);

foreach ($datosBd as $fila){
    $row = 2;
    foreach ($fila as $value){
      //  echo $value.' ';
//        if($col==$col_cod){
//            //$objActSheet->setCellValueByColumnAndRow($col,$row);
//            //$objActSheet->setCellValue('D'.$row, '= IF(SUMPRODUCT((C$2:C'.($row).'=C'.($row).')*1)>1,0,1)');
//            $objActSheet->setCellValue('D'.$row, '= SUMPRODUCT(C$2:C'.($row).'=C'.($row).')*1');
//        }
//        else{
        $objActSheet->setCellValueByColumnAndRow($col,$row, $value);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray( $style_header_sencillo ); // give style to header        
        $row++;
    }
    //echo '<br>';
    $col++;
}
$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet(); 

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;            
            
    ?>

                    
                    

    