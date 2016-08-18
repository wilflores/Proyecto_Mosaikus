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
        import('clases.organizacion.ArbolOrganizacional');
        include_once('clases/excel/PHPExcel.php');
        


        
        //print_r($datos);
        //$datosBd = $doc->exportarPHPExcelDatosBD($_GET);

        $filename= $datos[Codigo_doc].'Arbol Organizacional.xlsx';
        
        $titulo2 ='Árbol Organizacional';
        
        $hoja2='Árbol Organizacional';

        $objPHPExcel = new PHPExcel(); // Create new PHPExcel object
        $objPHPExcel->getProperties()->setCreator("Sigit prasetya n")
                                     ->setLastModifiedBy("Sigit prasetya n")
                                     ->setTitle("Creating file excel with php Test Document")
                                     ->setSubject("Creating file excel with php Test Document")
                                     ->setDescription("How to Create Excel file from PHP with PHPExcel 1.8.0 Classes by seegatesite.com.")
                                     ->setKeywords("phpexcel")
                                     ->setCategory("Test result file");
       

        ///////////////////////ESTILOS /////////////////////////////////
        $style_titulo = array(
            'font' => array(
                'bold' => true,
                'size' => 16,
            )
        );

        
        /////////////////////////////////////////////////////////////////////////////////////////////
        ///////// REGISTROS BD

        $objPHPExcel->createSheet(); 
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->setTitle($hoja2);
        $objActSheet->setCellValue('A1', $titulo2); 
        $objActSheet->setCellValue('A2', 'Fecha: '.date('d/m/Y')); 
        //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
        $objActSheet->mergeCells('A1:E1'); 
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray( $style_titulo ); //
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
        
        $objActSheet = $objPHPExcel->getActiveSheet(); 
        
        $pagina = new ArbolOrganizacional();
        if (strlen($_GET['b-id_organizacion'])== 0)
            $params['b-id_organizacion'] = 2;
        else $params['b-id_organizacion'] = $_GET['b-id_organizacion'];
        $params[cod_link] = $_GET[cod_link];
        $params[modo] = $_GET[modo];
        $params[reg_por_pagina] = 5000;
        $params[niveles] = $pagina->numeroNivelesHijos(array($params["b-id_organizacion"]));
        if (count($pagina->nombres_columnas) <= 0){
                $pagina->cargar_nombres_columnas();
        }
//        foreach ( $this->nombres_columnas as $key => $value) {
//            $contenido["N_" . strtoupper($key)] =  $value;
//        }                
        for($i=1;$i<=$params[niveles];$i++){
            //$out[titulo] .= "<th style=\"width: ". 100 / $niveles  . "%\" >Nivel $i</th>" ;
            $objActSheet->setCellValueByColumnAndRow($i-1,6, $pagina->nombres_columnas['nivel']." ". $i); 
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i-1,6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i-1,6)->applyFromArray( $style_header_resumen_head ); // give style to header

        }
        $pagina->cargar_acceso_nodos($params);
        $pagina->listarArbolOrganizacionalReporte($params, 1, $params[reg_por_pagina]);
        $data=$pagina->dbl->data;
       // print_r($data);exit;
        $columnas = array('A','B','C','D', 'E', 'F', 'G', 'H');
        $ancho_fijo=30;
        for($i=1;$i<=$params[niveles];$i++){  
            $row = 7;
            $con_g = 7;
            $veces=0;
            $id_aux=$data[0]["id_$i"];
            //echo $id_aux.'-';
            foreach ($data as $value) { 
               // print_r($value);
                if ($value["id_$i"] != $id_aux ){
                    if($veces>1){
                        $objActSheet->mergeCells($columnas[$i-1].($row-$veces).':'.$columnas[$i-1].($row-1));
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnas[$i-1])->setWidth($ancho_fijo);
                        //$objPHPExcel->getActiveSheet()->getColumnDimension($columnas[$i-1])->setAutoSize(true);
                    }
                    $veces=1;
                    $id_aux = $value["id_$i"];
                }
                else
                {   if ($value["nombre_$i"]<>''){
                        $veces++;
                    }
                    $id_aux = $value["id_$i"];
                
                }
                    $objActSheet->setCellValue($columnas[$i-1].$row, $value["nombre_$i"]);
                    $objPHPExcel->getActiveSheet()->getStyle($columnas[$i-1].$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnas[$i-1])->setWidth($ancho_fijo);
                   // $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($columnas[$i-1].$row)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle($columnas[$i-1].$row)->applyFromArray( $style_header_sencillo ); // give style to header        
                    $row++;
            }  
            if($veces>1){
                $objActSheet->mergeCells($columnas[$i-1].($row-$veces).':'.$columnas[$i-1].($row-1));
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnas[$i-1])->setWidth($ancho_fijo);
                //$objPHPExcel->getActiveSheet()->getColumnDimension($columnas[$i-1])->setAutoSize(true);
            }

       }

        //exit;
        /////PARA HACER EL detalle DE LA TABLA
        $col=0;
        /*
        foreach (array_keys($columnas) as $columna){
           //echo 'columna:'.$columna.'--';
            $row = 6;
           // print_r($datos[$columna]);
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
                   }
                   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray( $estilo ); // give style to header
                   $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                } 
                else {
                    $objActSheet->setCellValueByColumnAndRow($col,$row, $value);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray( $style_header_sencillo ); // give style to header        
                }
               $row++;
            }
            //echo $col.'<br>'." \n" ;
            $col++;
        }
*/
        ////PARA LAS IMAGENES 
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
        //$objDrawing2->setHeight(28);
        $objDrawing2->setResizeProportional(true);
        $objDrawing2->setCoordinates('F1');

        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        $objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
        //print_r($datos);

        //$objPHPExcel->getActiveSheet()->getStyle('B1:B100')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        
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

                    
                    

    