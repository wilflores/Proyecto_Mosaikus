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
    import('clases.cargo.Cargos');
    include_once('clases/excel/PHPExcel.php');

    import('clases.organizacion.ArbolOrganizacional');
    $ao = new ArbolOrganizacional();

    $filename= $datos[Codigo_doc].'cargos.xlsx';
    $titulo2 ='Listado de Cargos';
    $hoja2='Listado de Cargos';

    $objPHPExcel = new PHPExcel(); // Create new PHPExcel object
    $objPHPExcel->getProperties()->setCreator("Sigit prasetya n")
        ->setLastModifiedBy("Sigit prasetya n")
        ->setTitle("Creating file excel with php Test Document")
        ->setSubject("Creating file excel with php Test Document")
        ->setDescription("How to Create Excel file from PHP with PHPExcel 1.8.0 Classes by seegatesite.com.")
        ->setKeywords("phpexcel")
        ->setCategory("Test result file");

    $style_titulo = array(
        'font' => array(
            'bold' => true,
            'size' => 16,
        )
    );



    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(0);
    $objActSheet = $objPHPExcel->getActiveSheet();
    $objActSheet->setTitle($hoja2);
    $objActSheet->setCellValue('A4', $titulo2);
    $objActSheet->setCellValue('A5', 'Fecha: '.date('d/m/Y'));
    $objActSheet->mergeCells('A4:E4');
    $objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray( $style_titulo );

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

    //content
    $objActSheet = $objPHPExcel->getActiveSheet();
    $pagina = new Cargos();
    if (strlen($_GET['b-id_organizacion'])== 0)
        $params['b-id_organizacion'] = 2;
    else
        $params['b-id_organizacion'] = $_GET['b-id_organizacion'];

    $params[cod_link] = $_GET[cod_link];
    $params[modo] = $_GET[modo];
    $params[reg_por_pagina] = 5000;

    if (count($pagina->nombres_columnas) <= 0){
        $pagina->cargar_nombres_columnas();
    }

    $objActSheet->setCellValueByColumnAndRow(0,6,'Area');
    $objActSheet->setCellValueByColumnAndRow(1,6,$pagina->nombres_columnas['descripcion']);

    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,6)->applyFromArray( $style_header_resumen_head );
    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,6)->applyFromArray( $style_header_resumen_head );

    $pagina->cargar_acceso_nodos($params);
    $pagina->listarCargosReporte($params, 1, $params[reg_por_pagina]);
    $data=$pagina->dbl->data;

    $areas = array();
    $aux_id = $data[0]['id'];
    $cargos = array();
    foreach ($data as $d){
        if($aux_id != $d['id']){
            $areas[$aux_id] = $cargos;
            $aux_id = $d['id'];
            $cargos = array();
            array_push($cargos, $d['descripcion']);
        }else{
            array_push($cargos, $d['descripcion']);
        }
    }
    $areas[$aux_id] = $cargos;

    if (!class_exists('ArbolOrganizacional')){
        import('clases.organizacion.ArbolOrganizacional');
    }
    $ao = new ArbolOrganizacional();
    $index = 7;
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(100);
    foreach($areas as $k => $values){
        $objActSheet->setCellValueByColumnAndRow(0,$index,str_replace('&#8594;','->',$ao->BuscaOrganizacional(array('id_organizacion' => $k))));

        $value = "";
        foreach ($values as $v){
            $value .= $v . "\n";
        }
        $objActSheet->setCellValueByColumnAndRow(1,$index,$value);
        $objPHPExcel->getActiveSheet()->getStyle('B'.$index)->getAlignment()->setWrapText(true);
        $index++;
    }

    //images
    $objPHPExcel->setActiveSheetIndex(0);
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
    $objDrawing2->setResizeProportional(true);
    $objDrawing2->setCoordinates('F1');

    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    $objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());

    $objPHPExcel->setActiveSheetIndex(0);
    $objActSheet = $objPHPExcel->getActiveSheet();

    $objWriter =new PHPExcel_Writer_Excel2007($objPHPExcel);

    header('Content-Type: image/png');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    header("Content-Disposition: attachment;filename=$filename");
    $objWriter->save('php://output');
    exit;
?>