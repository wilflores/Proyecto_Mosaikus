<?php
/*require_once(dirname(__FILE__).'/html2ps/config.inc.php');
require_once(HTML2PS_DIR.'pipeline.factory.class.php');

parse_config_file(HTML2PS_DIR.'html2ps.config');*/
require_once (dirname(__FILE__).'/MPDF45/mpdf.php');

/**
 * Handles the saving generated PDF to user-defined output file on server
 */
/*class MyDestinationFile extends Destination {

    var $_dest_filename;

    function MyDestinationFile($dest_filename) {
        $this->_dest_filename = $dest_filename;
    }

    function process($tmp_filename, $content_type) {
        copy($tmp_filename, $this->_dest_filename);
    }
}

class MyFetcherMemory extends Fetcher {
    var $base_path;
    var $content;

    function MyFetcherMemory($content, $base_path) {
        $this->content   = $content;
        $this->base_path = $base_path;
    }

    function get_data($url=0) {
        if (!$url) {
            return new FetchedDataURL($this->content, array(), "");
        } else {
            // remove the "file:///" protocol
            if (substr($url,0,8)=='file:///') {
                $url=substr($url,8);
                // remove the additional '/' that is currently inserted by utils_url.php
                if (PHP_OS == "WINNT") $url=substr($url,1);
            }
            return new FetchedDataURL(@file_get_contents($url), array(), "");
        }
    }

    function get_base_url() {
        return 'file:///'.$this->base_path.'/dummy.html';
    }
}*/

/**
 * Runs the HTML->PDF conversion with default settings
 *
 * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
 * use absolute links (like http://my.host/image.gif).
 *
 * @param $path_to_html String HTML code to be converted
 * @param $path_to_pdf  String path to  file to save generated PDF to.
 * @param $base_path    String base path to use when resolving relative links in HTML code.
 */
class GenerarPDF {

    function pdf_create($html, $filename, $baja=false, $tipo_hoja="", $footer=false) {
        /*$pipeline = PipelineFactory::create_default_pipeline('', // Attempt to auto-detect encoding
                                                       '');



        $path_to_pdf="adjuntos/".$filename.".pdf";
        echo $path_to_pdf;
        $base_path='http://localhost/rrhhsintia/';

        // Override HTMlt http fetcher will return null on incorrect images
        // Bug submitted by 'imatronix' (tufat.com forum).

        $h=mb_convert_encoding($html, 'ISO-8859-1', mb_detect_encoding($html));

        $mfm=new MyFetcherMemory($h, $base_path);
        $pipeline->fetchers[] = $mfm;

        // Override destination to local file
        $pipeline->destination = new MyDestinationFile($path_to_pdf);

        $baseurl = '';
        $media =& Media::predefined('Letter');
        $media->set_landscape(false);
        $media->set_margins(array('left'   => 0,
                            'right'  => 0,
                            'top'    => 20,
                            'bottom' => 20));
        $media->set_pixels(1024);

        global $g_config;
        $g_config = array(
                    'cssmedia'     => 'screen',
                    'scalepoints'  => '1',
                    'renderimages' => true,
                    'renderlinks'  => true,
                    'renderfields' => true,
                    'renderforms'  => false,
                    'mode'         => 'html',
                    'encoding'     => '',
                    'debugbox'     => false,
                    'pdfversion'    => '1.4',
                    'draw_page_border' => false
        );

        $pipeline->configure($g_config);
        $pipeline->process_batch(array($baseurl), $media);*/

        //usando MFPDF

        $path_to_pdf="adjuntos/".$filename.".pdf";


        $mpdf=new mPDF('',$tipo_hoja,'','',5,5,5,5);
        if ($footer) {
            $mpdf->pagenumPrefix = 'Fecha de Emisión '.date('d/m/Y').' Página ';
            $mpdf->pagenumSuffix = '';
            $mpdf->nbpgPrefix = ' de ';
            $mpdf->nbpgSuffix = ' pages';
            $mpdf->SetFooter('{PAGENO}{nbpg}');
        }


        

        //$mpdf->Bookmark('Start of the document');
        $mpdf->WriteHTML(($html));

        if($baja) {
            /*$contenido=file_get_contents($path_to_pdf);
                header('Content-Type: "application/pdf"');
                header('Content-Disposition: attachment; filename="'.$filename.'.pdf"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header("Content-Transfer-Encoding: binary");
                header('Pragma: public');
                header("Content-Length: ".strlen($contenido));
            echo $contenido;*/
            $mpdf->Output($filename.".pdf",'D');
        }else {
            $mpdf->Output($path_to_pdf,'F');
        }

        //header("Location: prueba.pdf");

    }
}

?>