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
include_once('clases/excel/PHPExcel.php');

$filename= $_GET[modulo_actual].'.xlsx';
$titulo =  str_replace('_',' ',strtoupper($_GET[modulo_actual]) );
$hoja1='Listado';
if ($_GET['corder'] == null) $_GET['corder']="id";
if ($_GET['sorder'] == null) $_GET['sorder']="desc"; 
$datos = array();

$objPHPExcel = new PHPExcel(); // Create new PHPExcel object
$objPHPExcel->getProperties()->setCreator("Sigit prasetya n")
                             ->setLastModifiedBy("Sigit prasetya n")
                             ->setTitle("Creating file excel with php Test Document")
                             ->setSubject("Creating file excel with php Test Document")
                             ->setDescription("How to Create Excel file from PHP with PHPExcel 1.8.0 Classes by seegatesite.com.")
                             ->setKeywords("phpexcel")
                             ->setCategory("Test result file");
import('clases.utilidades.NivelAcceso');
$nivel = new NivelAcceso();

////////////////////////////////////////////////////////////////
/////// PARA ADAPTAR A TODOS LOS MODULOS
        //1.IMPORT DE LA CLASE DEL MODELO
        import('clases.workflow_documentos.WorkflowDocumentos');
        //2.INSTANCIA DE LA CLASE
        $modulo = new WorkflowDocumentos();
        //// APLICA PARA ESTE MODULO NADA MAS
        $modulo->restricciones = new NivelAcceso();
        $modulo->restricciones->cargar_acceso_nodos_explicito($_GET);
        $modulo->restricciones->cargar_permisos($_GET);
        if ($modulo->restricciones->per_viz_terceros == 'S'){
            if (count($modulo->restricciones->id_org_acceso_viz_terceros) <= 0){
                $modulo->restricciones->cargar_acceso_nodos_visualiza_terceros($_GET);
                }
        }        
        ///////////////////////////////////
        //3.LLAMAR AL METODO LISTAR DE LA CLASE
        $modulo->listarWorkflowDocumentos($_GET,1, 100000);
        ///////////////////////////////////
        $array_columns =  array_unique(explode('-', $_GET['mostrar-col']));
        unset($array_columns[array_search(0, $array_columns)]);//BORRO EL ELEMENTO 0 QUE NORMALMENTE ES EL ID
        $modulo->cargar_nombres_columnas();
        $columnas = $modulo->nombres_columnas;
/////// FIN DE ADAPTAR A TODOS LOS MODULOS
/////////////////////////////////////////////////////////////////
$datos = $modulo->dbl->data;
$col = 0;
$nombre_colum = array();
$dataxls =  array();
//PARA ARREGLAR EL ARREGLO DE COLUMNAS
foreach ($datos[0] as $key => $value)
{
   if(!is_integer($key)){
       $nombre_colum[$col] = $key;
       $col++;
   }
}
foreach ($nombre_colum as $key => $col){
    $dataxls[$col]= array_column($datos,$col);              
 } 

$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet();
$objActSheet->setTitle($hoja1);
$objActSheet->setCellValue('A1', $titulo); 
$objActSheet->setCellValue('A2', 'Fecha: '.date('d/m/Y')); 
//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
$objActSheet->mergeCells('A1:E1');
$style_titulo = array(
    'font' => array(
        'bold' => true,
        'size' => 16,
    )
);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray( $style_titulo ); //
$col=0;
$fila=3;

///////////////////////////////////////////////////////////////////////////
///////////////ESTILOS //////////////////////////////////////
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

///////////////NOMBRE DE LAS COLUMNAS//////////////////////////////////////
$objActSheet = $objPHPExcel->getActiveSheet(); 
foreach ($array_columns as $value){
    $objActSheet->setCellValueByColumnAndRow($col,$fila, $columnas[$nombre_colum[$value]]); 
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$fila)->applyFromArray( $style_header_resumen_head ); // give style to header
    $col++;
}

///////////////TABLA DE DATOS//////////////////////////////////////
$row=6;
foreach ($datos as $fila){
    $col = 0;
    foreach ($array_columns as $value){
            $objActSheet->setCellValueByColumnAndRow($col,$row, $fila[$nombre_colum[$value]]);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray( $style_header_sencillo ); // give style to header        
       $col++;
    }
    $row++;
}

//PARA LAS IMAGENES 
$objPHPExcel->setActiveSheetIndex(0); 
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('dist/images/logo_report.png');
$objDrawing->setName('Sample image');
$objDrawing->setDescription('Sample image');
$objDrawing->setWidthAndHeight(68,68);
$objDrawing->setResizeProportional(true);
$objDrawing->setCoordinates('A'.($row+1));
//
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

                    
                    

    