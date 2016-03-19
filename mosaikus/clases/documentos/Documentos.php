<?php 
        function archivo($tupla)
        {
            //<img class=\"SinBorde\" src=\"diseno/images/archivoVer.png\">
            $html = "<a target=\"_blank\" title=\"Ver Documento Fuente\" href=\"pages/documentos/descargar_archivo.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";
            $html = '';
            if (strlen($tupla[nom_visualiza])>0){
                //<img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
                $html .= "<a target=\"_blank\"  title=\"Ver Documento PDF\" href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
                            <i class=\"icon icon-view-document\"></i>
                        </a>";                
                return $html;
            }
            return $html;
        }
        
        function archivo_descarga($tupla)
        {
            if (strlen($tupla[nom_visualiza])>0){
                //<img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
                $html .= "<a target=\"_blank\" title=\"Ver Documento PDF\"  href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
                            
                            <i class=\"icon icon-view-document\"></i>
                        </a>";                
                return $html;
            }
            return $html;
        }
        
        function archivo_editable($tupla)
        {
            //<img class=\"SinBorde\"src=\"diseno/images/archivoVer.png\">
            $html = "<a target=\"_blank\" title=\"Ver Documento Fuente\"  href=\"pages/documentos/descargar_archivo.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";
//            if (strlen($tupla[nom_visualiza])>0){
//                $html .= "<a target=\"_blank\" href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
//                            <img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
//                        </a>";                
//                return $html;
//            }
            return $html;
        }
        
        /*
         * 
         */
        
        function BuscaOrganizacionalTodos($tupla)
        {
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select * from mos_documentos_estrorg_arbolproc where IDDoc='".$tupla[IDDoc]."' and tipo='EO'";                    
                    $Resp3 = $dbl->query($Consulta3,array());                    
                    foreach ($Resp3 as $Fila3) 
                    {                                                        
                        $Nivls .= BuscaOrganizacional(array('id_organizacion' => $Fila3[id_organizacion_proceso]))."<br /><br />";
                    }
                    if($Nivls!='')
                            $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                    else
                            $Nivls='-- Sin información --';
            }
            
            return $Nivls;

        }
        
        function BuscaOrganizacionalTodosVerMas($tupla)
        {
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select * from mos_documentos_estrorg_arbolproc where IDDoc='".$tupla[IDDoc]."' and tipo='EO'";                    
                    $Resp3 = $dbl->query($Consulta3,array());                    
                    foreach ($Resp3 as $Fila3) 
                    {                                                        
                        $Nivls .= BuscaOrganizacional(array('id_organizacion' => $Fila3[id_organizacion_proceso]))."<br /><br />";
                    }
                    if($Nivls!='')
                            $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                    else
                            $Nivls='-- Sin información --';
            }
                        
            if (strlen($Nivls)>200){
                $string = explode($Nivls, '<br /><br />');
                $valor_final = '';
                foreach ($string as $value) {
                    $valor_final .= $value;
                    if (strlen($valor_final)>200){
                        return substr($valor_final, 0, 200) . '.. <br/>
                        <a href="#" tok="' .$tupla[IDDoc]. '-doc" class="ver-mas">
                            <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                            <input type="hidden" id="ver-mas-' .$tupla[IDDoc]. '-doc" value="'.$Nivls.'"/>
                        </a>';
                    }
                    $valor_final .= "<br /><br />";
                    
                }
                
                return substr($Nivls, 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[IDDoc]. '-doc" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[IDDoc]. '-doc" value="'.$Nivls.'"/>
                    </a>';
            }
            //return $tupla[analisis_causal];
            
            return $Nivls;

        }
        
        function BuscaRegistros($tupla)
        {
            If ($tupla[formulario] == 'N')
                return 'No Aplica';
            
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select count(*) cant from mos_registro where IDDoc='".$tupla[IDDoc]."'";                    
                    $Resp3 = $dbl->query($Consulta3,array());                    
                    $resp3 = $Resp3[0][cant];
                    //<img border="0" title="Ver Registros" src="diseno/images/ico_explorer.png">
                    $html = '<a class="LinksinLinea" title="Ver Registros" href="JavaScript:MuestraFormulario('.$tupla[IDDoc].')">
                                <i class="icon icon-more"></i>
                            </a>' . str_pad($resp3, 5, '0', STR_PAD_LEFT);

                    return  $html;
            }
            
            return $Nivls;

        }
        
        function BuscaRegistrosReporte($tupla)
        {
            If ($tupla[formulario] == 'N')
                return 'No Aplica';
            
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select count(*) cant from mos_registro where IDDoc='".$tupla[IDDoc]."'";                    
                    $Resp3 = $dbl->query($Consulta3,array());                    
                    $resp3 = $Resp3[0][cant];
                    $html = str_pad($resp3, 5, '0', STR_PAD_LEFT);

                    return  $html;
            }
            
            return $Nivls;

        }
        
        function reporte_individual($tupla)
        {
            
            $html = '';
            
            $html .= "<a target=\"_blank\" href=\"pages/documentos/reporte_individual.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
                        <img class=\"SinBorde\" title=\"PDF control cambios (revisión / versión).\" src=\"diseno/images/pdf.png\">
                    </a>";                
            return $html;
            
        }
        
        function semaforo($tupla)
        {
            if (($tupla[dias_vig])<0){
                $html = "<img class=\"SinBorde\" title=\"Revisión vencida\" src=\"diseno/images/rojo.png\">";                                                                    
                return $html;
            }
            if ($tupla[dias_vig]<$tupla[semaforo]){
                $html = "<img class=\"SinBorde\" title=\"Revisión en plazo\" src=\"diseno/images/amarillo.png\">";                                                                    
                return $html;
            }
            return "<img class=\"SinBorde\" title=\"Revisión ok\" src=\"diseno/images/verde.png\">";
        }
        
        function semaforo_reporte($tupla)
        {
            if (($tupla[dias_vig])<0){
                $html = "<img class=\"SinBorde\" title=\"Revisión vencida\" src=\"diseno/images/rojo.png\"> $tupla[dias_vig]";                                                                    
                return $html;
            }
            if ($tupla[dias_vig]<$tupla[semaforo]){
                $html = "<img class=\"SinBorde\" title=\"Revisión en plazo\" src=\"diseno/images/amarillo.png\"> $tupla[dias_vig]";                                                                    
                return $html;
            }
            return "<img class=\"SinBorde\" title=\"Revisión ok\" src=\"diseno/images/verde.png\"> $tupla[dias_vig]";
        }
        
        
        
        function colum_admin($tupla)
        {
            if($_SESSION[CookM] == 'S'){
                //<img title=\"Modificar Documento $tupla[nombre_doc]\" src=\"diseno/images/ico_modificar.png\" style=\"cursor:pointer\">
                $html = "<a href=\"#\" onclick=\"javascript:editarDocumentos('". $tupla[IDDoc] . "');\"  title=\"Modificar Documento $tupla[nombre_doc]\">                            
                            <i class=\"icon icon-edit\"></i>
                        </a>";
            }
            if($_SESSION[CookE] == 'S'){
                //<img title="Eliminar '.$tupla[nombre_doc].'" src="diseno/images/ico_eliminar.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:eliminarDocumentos(\''. $tupla[IDDoc] . '\');" title="Eliminar '.$tupla[nombre_doc].'">
                        <i class="icon icon-remove"></i>
                        
                    </a>'; 
            }
            if ($_SESSION[CookN] == 'S'){
                //<img title="Crear Versión '.$tupla[nombre_doc].'" src="diseno/images/ticket_ver.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:crearVersionDocumentos(\''. $tupla[IDDoc] . '\');" title="Crear Versión '.$tupla[nombre_doc].'">                        
                            <i class="icon icon-v"></i>
                    </a>'; 
            }
            if ($_SESSION[CookN] == 'S'){
                //<img title="Crear Revisión '.$tupla[nombre_doc].'" src="diseno/images/ticket_rev.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:crearRevisionDocumentos(\''. $tupla[IDDoc] . '\');" title="Crear Revisión '.$tupla[nombre_doc].'" >                        
                            <i class="icon icon-r"></i>
                    </a>'; 
            }
            return $html;
            
        }
        
        function columa_accion($tupla){
                //,cantidad_evidencia    
            //return $tupla[$key];
            $html = '';
            if (strlen($tupla[nom_visualiza])>0){
                //<img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
                //$html .= "<a href='#detail-content' title=\"Ver Documento PDF\"  href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
                $html .= "<a href='#detail-content' title=\"Ver Documento PDF\" onclick=\"ver_visualiza('$tupla[IDDoc]','" . md5($tupla[IDDoc]) ."');\">
                            
                            <i class=\"icon icon-view-document\"></i>
                        </a>";
            }
            //$html .= "<a target=\"_blank\" title=\"Ver Documento Fuente\"  href=\"pages/documentos/descargar_archivo.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
            $html .= "<a href='#detail-content' title=\"Ver Documento Fuente\" onclick=\"ver_fuente('$tupla[IDDoc]','" . md5($tupla[IDDoc]) ."');\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";
            return $html;
        }
        
        function codigo_doc($tupla)
        {                        
            return $tupla[Codigo_doc] . ' ';            
        }
        function version($tupla)
        {                        
            return str_pad($tupla[version], 2, '0', STR_PAD_LEFT) . ' ';
            
        }
?>
<?php
 import("clases.interfaz.Pagina");        
        class Documentos extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        public $nombres_columnas;
        private $placeholder;
            
            public function Documentos(){
                parent::__construct();
                $this->asigna_script('documentos/documentos.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->contenido = array();
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros(){
                $sql = "SELECT cod_parametro, espanol FROM mos_parametro WHERE cod_categoria = '1' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            public function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 6";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 6";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }

            public function verDocumentoFuente($id){
                $atr=array();
                $sql = "SELECT                             
                            doc_fisico
                            ,contentType
                            ,Codigo_doc
                            ,nombre_doc
                            ,version
                            ,IDDoc
                            ,formulario
                         FROM mos_documentos 
                         WHERE IDDoc = $id "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verDocumentoPDF($id){
                $atr=array();
                $sql = "SELECT                             
                            doc_visualiza
                            ,contentType_visualiza                            
                            ,nom_visualiza
                            ,IDDoc
                            ,formulario
                         FROM mos_documentos 
                         WHERE IDDoc = $id "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
     

             public function verDocumentos($id){
                $atr=array();
                $sql = "SELECT IDDoc
                                ,Codigo_doc
                                ,nombre_doc
                                ,version
                                ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha
                                ,descripcion
                                ,palabras_claves
                                ,formulario
                                ,d.vigencia
                                ,1 doc_fisico
                                ,contentType
                                ,d.id_filial
                                ,nom_visualiza
                                ,1 doc_visualiza
                                ,contentType_visualiza
                                ,d.id_usuario
                                ,observacion
                                ,muestra_doc
                                ,estrucorg
                                ,arbproc
                                ,apli_reg_estrorg
                                ,apli_reg_arbproc
                                ,d.workflow
                                ,semaforo
                                ,v_meses
                                ,d.reviso
                                ,d.elaboro
                                ,d.aprobo
                                ,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) elaboro_a
                                ,CONCAT(CONCAT(UPPER(LEFT(re.nombres, 1)), LOWER(SUBSTRING(re.nombres, 2))),' ', CONCAT(UPPER(LEFT(re.apellido_paterno, 1)), LOWER(SUBSTRING(re.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(re.apellido_materno, 1)), LOWER(SUBSTRING(re.apellido_materno, 2)))) reviso_a
                                ,CONCAT(CONCAT(UPPER(LEFT(ap.nombres, 1)), LOWER(SUBSTRING(ap.nombres, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_paterno, 1)), LOWER(SUBSTRING(ap.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_materno, 1)), LOWER(SUBSTRING(ap.apellido_materno, 2)))) aprobo_a

                         FROM mos_documentos  d
                                left join mos_personal p on d.elaboro=p.cod_emp
                                left join mos_personal re on d.reviso=re.cod_emp
                                left join mos_personal ap on d.aprobo=ap.cod_emp
                         WHERE IDDoc = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verDocumentosRevisionSiguiente($id, $Version){
                $atr=array();
                $sql = "SELECT 
                            ifnull(max(revision)+1,1) as maximo
                         FROM mos_documento_revision 
                         WHERE IDDoc = $id and IDDoc_version=".$Version."";                 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0][maximo];
            }
            
        public   function archivo($tupla)
        {
            //<img class=\"SinBorde\" src=\"diseno/images/archivoVer.png\">
            $html = "<a target=\"_blank\" title=\"Ver Documento Fuente\" href=\"pages/documentos/descargar_archivo.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";
            $html = '';
            if (strlen($tupla[nom_visualiza])>0){
                //<img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
                $html .= "<a target=\"_blank\"  title=\"Ver Documento PDF\" href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
                            <i class=\"icon icon-view-document\"></i>
                        </a>";                
                return $html;
            }
            return $html;
        }
        
        public function archivo_descarga($tupla)
        {
            //if (strlen($tupla[nom_visualiza])>0)
            {
                //<img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
                $html .= "<a  tok=\"".$tupla[IDDoc]."\"  href=\"#\" class=\"ver-documento\" title=\"Ver Documento PDF\"  href=\"#\">
                            
                            <i class=\"icon icon-view-document\"></i>
                        </a>";                
                return $html;
            }
            return $html;
        }
        
        public function archivo_editable($tupla)
        {
            //<img class=\"SinBorde\"src=\"diseno/images/archivoVer.png\">
            $html = "<a target=\"_blank\" title=\"Ver Documento Fuente\"  href=\"pages/documentos/descargar_archivo.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";
//            if (strlen($tupla[nom_visualiza])>0){
//                $html .= "<a target=\"_blank\" href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
//                            <img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
//                        </a>";                
//                return $html;
//            }
            return $html;
        }
        
        public function BuscaOrganizacionalTodos($tupla)
        {
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select * from mos_documentos_estrorg_arbolproc where IDDoc='".$tupla[IDDoc]."' and tipo='EO'";                    
                    $Resp3 = $this->dbl->query($Consulta3,array());                    
                    foreach ($Resp3 as $Fila3) 
                    {                                                        
                        $Nivls .= BuscaOrganizacional(array('id_organizacion' => $Fila3[id_organizacion_proceso]))."<br /><br />";
                    }
                    if($Nivls!='')
                            $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                    else
                            $Nivls='-- Sin información --';
            }
            
            return $Nivls;

        }
        
        public function BuscaOrganizacionalTodosVerMas($tupla)
        {
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select * from mos_documentos_estrorg_arbolproc where IDDoc='".$tupla[IDDoc]."' and tipo='EO'";                    
                    $Resp3 = $this->dbl->query($Consulta3,array());                    
                    foreach ($Resp3 as $Fila3) 
                    {                                                        
                        $Nivls .= BuscaOrganizacional(array('id_organizacion' => $Fila3[id_organizacion_proceso]))."<br /><br />";
                    }
                    if($Nivls!='')
                            $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                    else
                            $Nivls='-- Sin información --';
            }
                        
            if (strlen($Nivls)>200){
                $string = explode($Nivls, '<br /><br />');
                $valor_final = '';
                foreach ($string as $value) {
                    $valor_final .= $value;
                    if (strlen($valor_final)>200){
                        return substr($valor_final, 0, 200) . '.. <br/>
                        <a href="#" tok="' .$tupla[IDDoc]. '-doc" class="ver-mas">
                            <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                            <input type="hidden" id="ver-mas-' .$tupla[IDDoc]. '-doc" value="'.$Nivls.'"/>
                        </a>';
                    }
                    $valor_final .= "<br /><br />";
                    
                }
                
                return substr($Nivls, 0, 200) . '.. <br/>
                    <a href="#" tok="' .$tupla[IDDoc]. '-doc" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                        <input type="hidden" id="ver-mas-' .$tupla[IDDoc]. '-doc" value="'.$Nivls.'"/>
                    </a>';
            }
            //return $tupla[analisis_causal];
            
            return $Nivls;

        }
        
        public function BuscaRegistros($tupla)
        {
            If ($tupla[formulario] == 'N')
                return 'No Aplica';
            
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select count(*) cant from mos_registro where IDDoc='".$tupla[IDDoc]."'";                    
                    $Resp3 = $this->dbl->query($Consulta3,array());                    
                    $resp3 = $Resp3[0][cant];
                    //<img border="0" title="Ver Registros" src="diseno/images/ico_explorer.png">
                    $html = '<a  title="Ver Registros" tok="'.$tupla[IDDoc].'"  href=\"#\" class="ver-registros">
                                <i class="icon icon-more"></i>'.
                              str_pad($resp3, 5, '0', STR_PAD_LEFT).'</a>';

                    return  $html;
            }
            
            return $Nivls;

        }
        
        public function BuscaRegistrosReporte($tupla)
        {
            If ($tupla[formulario] == 'N')
                return 'No Aplica';
            
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select count(*) cant from mos_registro where IDDoc='".$tupla[IDDoc]."'";                    
                    $Resp3 = $dbl->query($Consulta3,array());                    
                    $resp3 = $Resp3[0][cant];
                    $html = str_pad($resp3, 5, '0', STR_PAD_LEFT);

                    return  $html;
            }
            
            return $Nivls;

        }
        
       public function reporte_individual($tupla)
        {
            
            $html = '';
            
            $html .= "<a target=\"_blank\" href=\"pages/documentos/reporte_individual.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."\">
                        <img class=\"SinBorde\" title=\"PDF control cambios (revisión / versión).\" src=\"diseno/images/pdf.png\">
                    </a>";                
            return $html;
            
        }
        
       public  function semaforo($tupla)
        {
            if (($tupla[dias_vig])<0){
                $html = "<img class=\"SinBorde\" title=\"Revisión vencida\" src=\"diseno/images/rojo.png\">";                                                                    
                return $html;
            }
            if ($tupla[dias_vig]<$tupla[semaforo]){
                $html = "<img class=\"SinBorde\" title=\"Revisión en plazo\" src=\"diseno/images/amarillo.png\">";                                                                    
                return $html;
            }
            return "<img class=\"SinBorde\" title=\"Revisión ok\" src=\"diseno/images/verde.png\">";
        }
        
        public function semaforo_reporte($tupla)
        {
            if (($tupla[dias_vig])<0){
                $html = "<img class=\"SinBorde\" title=\"Revisión vencida\" src=\"diseno/images/rojo.png\"> $tupla[dias_vig]";                                                                    
                return $html;
            }
            if ($tupla[dias_vig]<$tupla[semaforo]){
                $html = "<img class=\"SinBorde\" title=\"Revisión en plazo\" src=\"diseno/images/amarillo.png\"> $tupla[dias_vig]";                                                                    
                return $html;
            }
            return "<img class=\"SinBorde\" title=\"Revisión ok\" src=\"diseno/images/verde.png\"> $tupla[dias_vig]";
        }
        
        public  function semaforo_excel($tupla)
        {
            if (($tupla[dias_vig])<0){
                $html = "Vencido";                                                                    
                return $html;
            }
            if ($tupla[dias_vig]<$tupla[semaforo]){
                $html = "Vigente";                                                                    
                return $html;
            }
            return "Vigente";
        }
        
                        
        
        
        public function codigo_doc($tupla)
        {                        
            return $tupla[Codigo_doc] . ' ';            
        }
        public function version($tupla)
        {                        
            return str_pad($tupla[version], 2, '0', STR_PAD_LEFT) . ' ';
            
        }
            
            
            public function verArbol($id){
                $atr=array();
                $sql = "SELECT IDDoc
                            ,id_organizacion_proceso

                         FROM mos_documentos_estrorg_arbolproc 
                         WHERE IDDoc = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            private function codigo_siguiente(){
                $sql = "SELECT MAX(IDDoc) total_registros
                         FROM mos_documentos";
                $total_registros = $this->dbl->query($sql, $atr);
                $num_viaje = $total_registros[0][total_registros] + 1;                
                return $num_viaje;                
            }
            
            public function ingresarArbol($atr){
               // print_r($atr);
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_documentos_estrorg_arbolproc(IDDoc,id_organizacion_proceso,tipo,aplica_subnivel)
                            VALUES(
                                $atr[IDDoc],$atr[id],'$atr[tipo]',1
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarCamposFormulario($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "INSERT INTO mos_documentos_datos_formulario(IDDoc,Nombre,tipo,valores,orden)
                            VALUES(
                                $atr[IDDoc],'$atr[nombre]','$atr[tipo]','$atr[valores]',$atr[orden]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function actualizarOrdenCamposFormulario($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "UPDATE mos_documentos_datos_formulario SET orden = $atr[orden]
                            WHERE id_unico = $atr[id_unico]";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarParametro($atr){
                try {                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    if ($atr[cod_parametro_det] == '') 
                        $atr[cod_parametro_det] = 0;
                    $sql = "INSERT INTO mos_parametro_modulos(cod_categoria,cod_parametro,cod_parametro_det,id_registro,cod_categoria_aux)
                            VALUES(
                                1,$atr[cod_parametro],$atr[cod_parametro_det],$atr[id_registro],1
                                )";
                    $this->dbl->insert_update($sql);
                    return "El mos_personal '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarDocumentos($atr,$archivo,$doc_ver){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[IDDoc] = $this->codigo_siguiente();
                    $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_documentos 
                                        WHERE Codigo_doc = '$atr[Codigo_doc]'";                    
                    $total_registros = $this->dbl->query($sql, $atr);
                    $total = $total_registros[0][total_registros];  
                    if ($total > 0){
                        return "- Ya existe una documento registrado con el mismo código";
                    }
                    if (strlen($atr[reviso])== 0){
                        $atr[reviso] = "NULL";                     
                    }                    
                    if (strlen($atr[aprobo])== 0){
                        $atr[aprobo] = "NULL";                     
                    }  
                    $atr[observacion] = '-- Publicación de documento en Mosaikus--';
                    $atr[doc_fisico] = $archivo;
                    $atr[doc_visualiza] = $doc_ver;
                    $atr[id_filial] = $_SESSION[CookFilial];
                    //
                    $sql = "INSERT INTO mos_documentos(IDDoc,Codigo_doc,nombre_doc,version,fecha,descripcion,palabras_claves,formulario,vigencia,doc_fisico,contentType,id_filial,nom_visualiza,doc_visualiza,contentType_visualiza,id_usuario,observacion,estrucorg,arbproc,apli_reg_estrorg,apli_reg_arbproc,workflow,semaforo,v_meses,reviso,elaboro,aprobo)                            
                            VALUES(
                                $atr[IDDoc],'$atr[Codigo_doc]','$atr[nombre_doc]',$atr[version],'$atr[fecha]','$atr[descripcion]','$atr[palabras_claves]','$atr[formulario]','$atr[vigencia]','$atr[doc_fisico]','$atr[contentType]',$atr[id_filial],'$atr[nom_visualiza]','$atr[doc_visualiza]','$atr[contentType_visualiza]',$atr[id_usuario],'$atr[observacion]','$atr[estrucorg]','$atr[arbproc]','$atr[apli_reg_estrorg]','$atr[apli_reg_arbproc]','$atr[workflow]',$atr[semaforo],$atr[v_meses],$atr[reviso],$atr[elaboro],$atr[aprobo]
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos ' . $atr[descripcion_ano], 'mos_documentos');
                      */
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Codigo Doc: \'$atr[Codigo_doc]\', Nombre Doc: \'$atr[nombre_doc]\', Version: \'$atr[version]\', Fecha: \'$atr[fecha]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', ContentType: \'$atr[contentType]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza]\', ContentType Visualiza: \'$atr[contentType_visualiza]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', ";
                    $this->registraTransaccionLog(1,$nuevo,'', '');
                    return $atr[IDDoc];
                    return "El mos_documentos '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarDocumentosVersion($atr,$archivo,$doc_ver){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[IDDoc] = $this->codigo_siguiente();
                    
                    $atr[doc_fisico] = $archivo;
                    $atr[doc_visualiza] = $doc_ver;
                    $atr[id_filial] = $_SESSION[CookFilial];
                    //
                    $sql = "INSERT INTO mos_documentos(IDDoc,Codigo_doc,nombre_doc,version,fecha,descripcion,palabras_claves,formulario,vigencia,doc_fisico,contentType,id_filial,nom_visualiza,doc_visualiza,contentType_visualiza,id_usuario,observacion,estrucorg,arbproc,apli_reg_estrorg,apli_reg_arbproc,workflow,semaforo,v_meses,reviso,elaboro,aprobo)
                            SELECT $atr[IDDoc],Codigo_doc,nombre_doc,version+1,'$atr[fecha]',descripcion,palabras_claves,formulario,vigencia,doc_fisico,contentType,id_filial,nom_visualiza,doc_visualiza,contentType_visualiza,id_usuario,'$atr[observacion]',estrucorg,arbproc,apli_reg_estrorg,apli_reg_arbproc,workflow,semaforo,v_meses,reviso,$atr[elaboro],aprobo
                            FROM mos_documentos
                            WHERE IDDoc = $atr[id]                                                           
                                ";
                    //echo $sql;
                    //echo $sql; $atr[IDDoc],'$atr[Codigo_doc]','$atr[nombre_doc]',$atr[version],'$atr[fecha]','$atr[descripcion]','$atr[palabras_claves]','$atr[formulario]','$atr[vigencia]','$atr[doc_fisico]','$atr[contentType]',$atr[id_filial],'$atr[nom_visualiza]','$atr[doc_visualiza]','$atr[contentType_visualiza]',$atr[id_usuario],'$atr[observacion]','$atr[estrucorg]','$atr[arbproc]','$atr[apli_reg_estrorg]','$atr[apli_reg_arbproc]','$atr[workflow]',$atr[semaforo],$atr[v_meses],$atr[reviso],$atr[elaboro],$atr[aprobo]
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_documentos SET doc_fisico='$atr[doc_fisico]',contentType='$atr[contentType]', nom_visualiza='$atr[nom_visualiza]',doc_visualiza='$atr[doc_visualiza]',contentType_visualiza='$atr[contentType_visualiza]' "
                            . "WHERE IDDoc = $atr[IDDoc]  ";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_documentos SET muestra_doc='N' "
                            . "WHERE IDDoc = $atr[id]  ";
                    $this->dbl->insert_update($sql);
                    $sql = "Insert Into mos_documentos_estrorg_arbolproc (IDDoc, id_organizacion_proceso, tipo, aplica_subnivel)
						Select ".$atr[IDDoc].",  id_organizacion_proceso, tipo, aplica_subnivel From mos_documentos_estrorg_arbolproc
							Where IDDoc = $atr[id]";
                    $this->dbl->insert_update($sql);
                    $sql = "INSERT INTO mos_parametro_modulos(cod_categoria,cod_parametro,cod_parametro_det,id_registro,cod_categoria_aux)
                            SELECT cod_categoria,cod_parametro,cod_parametro_det,$atr[IDDoc],cod_categoria_aux
                            FROM mos_parametro_modulos
                            WHERE id_registro = $atr[id] AND cod_categoria = 1 AND cod_categoria_aux = 1";
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_documentos_datos_formulario SET IDDoc = $atr[IDDoc]"
                            . " WHERE IDDoc = $atr[id] ";
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_registro SET IDDoc = $atr[IDDoc]"
                            . " WHERE IDDoc = $atr[id] ";
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_registro_formulario SET IDDoc = $atr[IDDoc]"
                            . " WHERE IDDoc = $atr[id] ";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos ' . $atr[descripcion_ano], 'mos_documentos');
                      */
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Codigo Doc: \'$atr[Codigo_doc]\', Nombre Doc: \'$atr[nombre_doc]\', Version: \'$atr[version]\', Fecha: \'$atr[fecha]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', ContentType: \'$atr[contentType]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza]\', ContentType Visualiza: \'$atr[contentType_visualiza]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', ";
                    $this->registraTransaccionLog(1,$nuevo,'', '');
                    return $atr[IDDoc];
                    return "El mos_documentos '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarDocumentosRevision($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[IDDoc] = $this->codigo_siguiente();
                    
                    $atr[id_filial] = $_SESSION[CookFilial];
                    $atr[revision] = $this->verDocumentosRevisionSiguiente($atr[id], $atr[version]);
                    //
                    $sql = "insert into mos_documento_revision values ($atr[id],$atr[version],$atr[revision],$atr[id_usuario],'$atr[observacion]','$atr[fecha]',$atr[elaboro]);
                            ";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos ' . $atr[descripcion_ano], 'mos_documentos');
                      */
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Codigo Doc: \'$atr[Codigo_doc]\', Nombre Doc: \'$atr[nombre_doc]\', Version: \'$atr[version]\', Fecha: \'$atr[fecha]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', ContentType: \'$atr[contentType]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza]\', ContentType Visualiza: \'$atr[contentType_visualiza]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', ";
                    $this->registraTransaccionLog(1,$nuevo,'', '');
                    return "La revision ha sido ingresada con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = ''){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]')";            
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarDocumentos($atr,$archivo){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[doc_visualiza] = $archivo;                    
                    if (strlen($atr[doc_visualiza])== 0){
                        $atr[doc_visualiza] = "doc_visualiza";
                        $atr[contentType_visualiza] = "contentType_visualiza";
                        $atr[nom_visualiza] = "nom_visualiza";                        
                    }
                    else
                    {
                        $atr[doc_visualiza] = "'$atr[doc_visualiza]'";
                        $atr[contentType_visualiza_aux] = $atr[contenttype];
                        $atr[contentType_visualiza] = "'$atr[contentType_visualiza]'";                        
                        $atr[nom_visualiza_aux] = "$atr[nom_visualiza]";  
                        $atr[nom_visualiza] = "'$atr[nom_visualiza]'";   
                        
                    }
                    if (strlen($atr[reviso])== 0){
                        $atr[reviso] = "NULL";                     
                    }                    
                    if (strlen($atr[aprobo])== 0){
                        $atr[aprobo] = "NULL";                     
                    }
                    $sql = "UPDATE mos_documentos SET                            
                                    descripcion = '$atr[descripcion]',palabras_claves = '$atr[palabras_claves]',formulario = '$atr[formulario]',vigencia = '$atr[vigencia]'"
                            . ",nom_visualiza = $atr[nom_visualiza],doc_visualiza = $atr[doc_visualiza],contentType_visualiza = $atr[contentType_visualiza],id_usuario = $atr[id_usuario],observacion = '$atr[observacion]',estrucorg = '$atr[estrucorg]',arbproc = '$atr[arbproc]'"
                            . ",apli_reg_estrorg = '$atr[apli_reg_estrorg]',apli_reg_arbproc = '$atr[apli_reg_arbproc]',workflow = '$atr[workflow]',semaforo = $atr[semaforo],v_meses = $atr[v_meses],reviso = $atr[reviso],elaboro = $atr[elaboro],aprobo = $atr[aprobo]
                            WHERE  IDDoc = $atr[id]";      
                    $val = $this->verDocumentos($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza_aux]\',ContentType Visualiza: \'$atr[contentType_visualiza_aux]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', ";
                    $anterior = "IDDoc: \'$val[IDDoc]\', Codigo Doc: \'$val[Codigo_doc]\', Nombre Doc: \'$val[nombre_doc]\', Version: \'$val[version]\', Fecha: \'$val[fecha]\', Descripcion: \'$val[descripcion]\', Palabras Claves: \'$val[palabras_claves]\', Formulario: \'$val[formulario]\', Vigencia: \'$val[vigencia]\', ContentType: \'$val[contentType]\', Id Filial: \'$val[id_filial]\', Nom Visualiza: \'$val[nom_visualiza]\', ContentType Visualiza: \'$val[contentType_visualiza]\', Id Usuario: \'$val[id_usuario]\', Observacion: \'$val[observacion]\', Muestra Doc: \'$val[muestra_doc]\', Estrucorg: \'$val[estrucorg]\', Arbproc: \'$val[arbproc]\', Apli Reg Estrorg: \'$val[apli_reg_estrorg]\', Apli Reg Arbproc: \'$val[apli_reg_arbproc]\', Workflow: \'$val[workflow]\', Semaforo: \'$val[semaforo]\', V Meses: \'$val[v_meses]\', Reviso: \'$val[reviso]\', Elaboro: \'$val[elaboro]\', Aprobo: \'$val[aprobo]\', ";
                    $this->registraTransaccionLog(2,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Documentos ' . $atr[descripcion_ano], 'mos_documentos');
                    */
                    return "El Documento '$atr[info_nombre]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
    public function BuscaOrgNivelHijos($IDORG)
        {
            $OrgNom = $IDORG;
            //$Consulta3="select id_organizacion,organizacion_padre,identificacion from mos_organizacion where organizacion_padre='".$IDORG."' and id_filial='".$Filial."' order by id_organizacion";
            $Consulta3="select id as id_organizacion, parent_id as organizacion_padre, title as identificacion from mos_organizacion where parent_id='".$IDORG."' order by id";
            //echo $Consulta3;
            //$Resp3=mysql_query($Consulta3);
            //while($Fila3=mysql_fetch_assoc($Resp3))
            $data = $this->dbl->query($Consulta3,array());
            foreach( $data as $Fila3)
            {
                    //$OrgNom=$OrgNom.",".$Fila3[id_organizacion];
                    $OrgNom .= ",".$this->BuscaOrgNivelHijos($Fila3[id_organizacion]);
            }
            return $OrgNom;
        }
        
             public function listarDocumentos($atr, $pag, $registros_x_pagina){
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 1;                    
                    foreach ($this->parametros as $value) {
                        $sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                                inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                        where t1.cod_categoria='1' and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = p.cod_emp "; 
                        $sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }
                    $filtro_ao ='';
                    if ((strlen($atr["b-id_organizacion"])>0) && ($atr["b-id_organizacion"] != "2")){                             
                        $id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $filtro_ao .= " INNER JOIN ("
                                . " select IDDoc from mos_documentos_estrorg_arbolproc where id_organizacion_proceso in (". $id_org . ") GROUP BY IDDoc) as ao ON ao.IDDoc = d.IDDoc ";//" AND id_organizacion IN (". $id_org . ")";
                    }
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_documentos  d
                                left join mos_personal p on d.elaboro=p.cod_emp
                                left join mos_personal re on d.reviso=re.cod_emp
                                left join mos_personal ap on d.aprobo=ap.cod_emp
                                left join (select IDDoc, count(*) num_rev, max(fechaRevision) fecha_revision from mos_documento_revision GROUP BY IDDoc) as rev ON rev.IDDoc = d.IDDoc
                            $sql_left
                                $filtro_ao
                            WHERE muestra_doc='S' ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(Codigo_doc) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%' OR upper(nombre_doc) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                    if (strlen($atr["b-IDDoc"])>0)
                        $sql .= " AND IDDoc = '". $atr["b-IDDoc"] . "'";
                    if (strlen($atr["b-Codigo_doc"])>0)
                        $sql .= " AND upper(Codigo_doc) like '%" . strtoupper($atr["b-Codigo_doc"]) . "%'";        
                    if (strlen($atr["b-nombre_doc"])>0)
                        $sql .= " AND upper(nombre_doc) like '%" . strtoupper($atr["b-nombre_doc"]) . "%'";
                    if (strlen($atr["b-version"])>0)
                               $sql .= " AND version = '". $atr["b-version"] . "'";
                    if (strlen($atr['b-fecha-desde'])>0)                        
                    {
                        $atr['b-fecha-desde'] = formatear_fecha($atr['b-fecha-desde']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha-hasta'])>0)                        
                    {
                        $atr['b-fecha-hasta'] = formatear_fecha($atr['b-fecha-hasta']);                        
                        $sql .= " AND fecha <= '" . ($atr['b-fecha-hasta']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_rev-desde'])>0)                        
                    {
                        $atr['b-fecha_rev-desde'] = formatear_fecha($atr['b-fecha_rev-desde']);                        
                        $sql .= " AND fecha_revision >= '" . ($atr['b-fecha_rev-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_rev-hasta'])>0)                        
                    {
                        $atr['b-fecha_rev-hasta'] = formatear_fecha($atr['b-fecha_rev-hasta']);                        
                        $sql .= " AND rev.fecha_revision <= '" . ($atr['b-fecha_rev-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-descripcion"])>0)
                                $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-palabras_claves"])>0)
                                $sql .= " AND upper(palabras_claves) like '%" . strtoupper($atr["b-palabras_claves"]) . "%'";
                    if (strlen($atr["b-formulario"])>0)
                                $sql .= " AND upper(formulario) like '%" . strtoupper($atr["b-formulario"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-doc_fisico"])>0)
                        $sql .= " AND doc_fisico = '". $atr["b-doc_fisico"] . "'";
                    if (strlen($atr["b-contentType"])>0)
                        $sql .= " AND upper(contentType) like '%" . strtoupper($atr["b-contentType"]) . "%'";
                    if (strlen($atr["b-id_filial"])>0)
                        $sql .= " AND id_filial = '". $atr["b-id_filial"] . "'";
                    if (strlen($atr["b-nom_visualiza"])>0)
                        $sql .= " AND upper(nom_visualiza) like '%" . strtoupper($atr["b-nom_visualiza"]) . "%'";
                    if (strlen($atr["b-doc_visualiza"])>0)
                        $sql .= " AND doc_visualiza = '". $atr["b-doc_visualiza"] . "'";
                    if (strlen($atr["b-contentType_visualiza"])>0)
                        $sql .= " AND upper(contentType_visualiza) like '%" . strtoupper($atr["b-contentType_visualiza"]) . "%'";
                    if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND observacion = '". $atr["b-observacion"] . "'";
                    if (strlen($atr["b-muestra_doc"])>0)
                        $sql .= " AND upper(muestra_doc) like '%" . strtoupper($atr["b-muestra_doc"]) . "%'";
                    if (strlen($atr["b-estrucorg"])>0)
                        $sql .= " AND upper(estrucorg) like '%" . strtoupper($atr["b-estrucorg"]) . "%'";
                    if (strlen($atr["b-arbproc"])>0)
                                $sql .= " AND upper(arbproc) like '%" . strtoupper($atr["b-arbproc"]) . "%'";
                    if (strlen($atr["b-apli_reg_estrorg"])>0)
                                $sql .= " AND upper(apli_reg_estrorg) like '%" . strtoupper($atr["b-apli_reg_estrorg"]) . "%'";
                    if (strlen($atr["b-apli_reg_arbproc"])>0)
                                $sql .= " AND upper(apli_reg_arbproc) like '%" . strtoupper($atr["b-apli_reg_arbproc"]) . "%'";
                    if (strlen($atr["b-workflow"])>0)
                        $sql .= " AND upper(workflow) like '%" . strtoupper($atr["b-workflow"]) . "%'";
                    if (strlen($atr["b-semaforo-desde"])>0)
                               $sql .= " AND ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE())) >= ". $atr["b-semaforo-desde"] . "";
                    if (strlen($atr["b-semaforo-hasta"])>0)
                               $sql .= " AND ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE())) <= ". $atr["b-semaforo-hasta"] . "";
                    if (strlen($atr["b-v_meses"])>0)
                               $sql .= " AND v_meses = '". $atr["b-v_meses"] . "'";
                    if (strlen($atr["b-reviso"])>0)
                               $sql .= " AND reviso = '". $atr["b-reviso"] . "'";
                    if (strlen($atr["b-elaboro"])>0)
                               $sql .= " AND elaboro = '". $atr["b-elaboro"] . "'";
                    if (strlen($atr["b-aprobo"])>0)
                        $sql .= " AND aprobo = '". $atr["b-aprobo"] . "'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT d.IDDoc                                    
                                    ,semaforo                                    
                                    ,ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE())) dias_vig
                                    ,1 accion                                    
                                    ,Codigo_doc
                                    ,nombre_doc
                                    ,contentType                                    
                                    ,nom_visualiza                                    
                                    ,contentType_visualiza                                    
                                    ,CONCAT(initcap(p.nombres), ' ', initcap(p.apellido_paterno), ' ', initcap(p.apellido_materno))  elaboro
                                    ,CONCAT(initcap(re.nombres), ' ', initcap(re.apellido_paterno), ' ', initcap(re.apellido_materno)) reviso                                    
                                    ,CONCAT(initcap(ap.nombres), ' ', initcap(ap.apellido_paterno), ' ', initcap(ap.apellido_materno)) aprobo
                                    ,formulario
                                    ,v_meses                                    
                                    ,version
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha                                    
                                    ,d.descripcion
                                    -- ,palabras_claves
                                    ,num_rev
                                    ,DATE_FORMAT(fecha_revision, '%d/%m/%Y') fecha_rev
                                    ,CASE d.vigencia WHEN 'S' Then 'Si' ELSE 'No' END vigencia
                                    ,1 arbol_organizacional
                                    -- ,id_filial                                    
                                    -- ,id_usuario
                                    ,d.observacion
                                    -- ,muestra_doc
                                    -- ,estrucorg
                                    -- ,arbproc
                                    -- ,apli_reg_estrorg
                                    -- ,apli_reg_arbproc
                                    ,d.workflow                                                                                                            
                                     $sql_col_left
                            FROM mos_documentos d
                                left join mos_personal p on d.elaboro=p.cod_emp
                                left join mos_personal re on d.reviso=re.cod_emp
                                left join mos_personal ap on d.aprobo=ap.cod_emp
                                left join (select IDDoc, count(*) num_rev, max(fechaRevision) fecha_revision from mos_documento_revision GROUP BY IDDoc) as rev ON rev.IDDoc = d.IDDoc
                            $sql_left
                                $filtro_ao
                            WHERE muestra_doc='S' ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(Codigo_doc) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%' OR upper(nombre_doc) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%')";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-IDDoc"])>0)
                        $sql .= " AND IDDoc = '". $atr["b-IDDoc"] . "'";
                    if (strlen($atr["b-Codigo_doc"])>0)
                                $sql .= " AND upper(Codigo_doc) like '%" . strtoupper($atr["b-Codigo_doc"]) . "%'";
                    if (strlen($atr["b-nombre_doc"])>0)
                        $sql .= " AND upper(nombre_doc) like '%" . strtoupper($atr["b-nombre_doc"]) . "%'";
                    if (strlen($atr["b-version"])>0)
                               $sql .= " AND version = '". $atr["b-version"] . "'";
                    if (strlen($atr['b-fecha-desde'])>0)                        
                    {
                        //$atr['b-fecha-desde'] = formatear_fecha($atr['b-fecha-desde']);                        
                        $sql .= " AND fecha >= '" . ($atr['b-fecha-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha-hasta'])>0)                        
                    {
                        //$atr['b-fecha-hasta'] = formatear_fecha($atr['b-fecha-hasta']);                        
                        $sql .= " AND fecha <= '" . ($atr['b-fecha-hasta']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_rev-desde'])>0)                        
                    {
                        //$atr['b-fecha_rev-desde'] = formatear_fecha($atr['b-fecha_rev-desde']);                        
                        $sql .= " AND fecha_revision >= '" . ($atr['b-fecha_rev-desde']) . "'";                        
                    }
                    if (strlen($atr['b-fecha_rev-hasta'])>0)                        
                    {
                        //$atr['b-fecha_rev-hasta'] = formatear_fecha($atr['b-fecha_rev-hasta']);                        
                        $sql .= " AND rev.fecha_revision <= '" . ($atr['b-fecha_rev-hasta']) . "'";                        
                    }
                    if (strlen($atr["b-descripcion"])>0)
                                $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-palabras_claves"])>0)
                                $sql .= " AND upper(palabras_claves) like '%" . strtoupper($atr["b-palabras_claves"]) . "%'";
                    if (strlen($atr["b-formulario"])>0)
                                $sql .= " AND upper(formulario) like '%" . strtoupper($atr["b-formulario"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND upper(vigencia) like '%" . strtoupper($atr["b-vigencia"]) . "%'";
                    if (strlen($atr["b-doc_fisico"])>0)
                        $sql .= " AND doc_fisico = '". $atr["b-doc_fisico"] . "'";
                    if (strlen($atr["b-contentType"])>0)
                        $sql .= " AND upper(contentType) like '%" . strtoupper($atr["b-contentType"]) . "%'";
                    if (strlen($atr["b-id_filial"])>0)
                        $sql .= " AND id_filial = '". $atr["b-id_filial"] . "'";
                    if (strlen($atr["b-nom_visualiza"])>0)
                        $sql .= " AND upper(nom_visualiza) like '%" . strtoupper($atr["b-nom_visualiza"]) . "%'";
                    if (strlen($atr["b-doc_visualiza"])>0)
                        $sql .= " AND doc_visualiza = '". $atr["b-doc_visualiza"] . "'";
                    if (strlen($atr["b-contentType_visualiza"])>0)
                        $sql .= " AND upper(contentType_visualiza) like '%" . strtoupper($atr["b-contentType_visualiza"]) . "%'";
                    if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
                    if (strlen($atr["b-observacion"])>0)
                        $sql .= " AND observacion = '". $atr["b-observacion"] . "'";
                    if (strlen($atr["b-muestra_doc"])>0)
                        $sql .= " AND upper(muestra_doc) like '%" . strtoupper($atr["b-muestra_doc"]) . "%'";
                    if (strlen($atr["b-estrucorg"])>0)
                        $sql .= " AND upper(estrucorg) like '%" . strtoupper($atr["b-estrucorg"]) . "%'";
                    if (strlen($atr["b-arbproc"])>0)
                                $sql .= " AND upper(arbproc) like '%" . strtoupper($atr["b-arbproc"]) . "%'";
                    if (strlen($atr["b-apli_reg_estrorg"])>0)
                                $sql .= " AND upper(apli_reg_estrorg) like '%" . strtoupper($atr["b-apli_reg_estrorg"]) . "%'";
                    if (strlen($atr["b-apli_reg_arbproc"])>0)
                                $sql .= " AND upper(apli_reg_arbproc) like '%" . strtoupper($atr["b-apli_reg_arbproc"]) . "%'";
                    if (strlen($atr["b-workflow"])>0)
                        $sql .= " AND upper(workflow) like '%" . strtoupper($atr["b-workflow"]) . "%'";
                    if (strlen($atr["b-semaforo-desde"])>0)
                               $sql .= " AND ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE())) >= ". $atr["b-semaforo-desde"] . "";
                    if (strlen($atr["b-semaforo-hasta"])>0)
                               $sql .= " AND ifnull(DATEDIFF(DATE_ADD(fecha_revision,INTERVAL v_meses MONTH),CURRENT_DATE()),DATEDIFF(DATE_ADD(fecha,INTERVAL v_meses MONTH),CURRENT_DATE())) <= ". $atr["b-semaforo-hasta"] . "";
                    if (strlen($atr["b-v_meses"])>0)
                               $sql .= " AND v_meses = '". $atr["b-v_meses"] . "'";
                    if (strlen($atr["b-reviso"])>0)
                               $sql .= " AND reviso = '". $atr["b-reviso"] . "'";
                    if (strlen($atr["b-elaboro"])>0)
                               $sql .= " AND elaboro = '". $atr["b-elaboro"] . "'";
                    if (strlen($atr["b-aprobo"])>0)
                        $sql .= " AND aprobo = '". $atr["b-aprobo"] . "'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function listarCamposFormulario($atr){       
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "SELECT 
                                Nombre
                                ,tipo
                                ,valores
                                ,id_unico
                                ,orden
                                -- ,1 cant
                                ,(SELECT count(*) FROM mos_registro_formulario WHERE id_unico =  mos_documentos_datos_formulario.id_unico) cant
                            FROM mos_documentos_datos_formulario  
                            
                            WHERE IDDoc = $atr[id] ORDER BY orden"; 
                            
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             
             public function eliminarParametros($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_parametro_modulos", "cod_categoria = 1 AND id_registro = $atr[id_registro]");
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
             
             public function eliminarCargosArbol($atr){
                    try {
                        $respuesta = $this->dbl->delete("mos_documentos_estrorg_arbolproc", "IDDoc = $atr[id]");
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
             
             public function eliminarDocumentos($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $val = $this->verDocumentos($atr[id]);
                        $sql = "SELECT COUNT(*) total_registros
                                            FROM mos_documentos 
                                            WHERE Codigo_doc = '".$val[Codigo_doc]."'";                    
                        $total_registros = $this->dbl->query($sql, $atr);
                        $total = $total_registros[0][total_registros];  
                        
                        if ($total == '1'){
                            $sql = "SELECT COUNT(*) total_registros
                                            FROM mos_registro 
                                            WHERE IDDoc = " . $atr[id];                    
                            $total_registros = $this->dbl->query($sql, $atr);
                            $total = $total_registros[0][total_registros];
                             
                            if ($total+0 > 0){
                                //echo $total; 
                                return "- No se puede eliminar el documentos, existen registros asociados.";
                            }
                            $respuesta = $this->dbl->delete("mos_documentos", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_parametro_semaforo", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_revision", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_version", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documentos_categoria", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documentos_datos_formulario", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documentos_estrorg_arbolproc", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_parametro_modulos", "id_registro = " . $atr[id] . " AND cod_categoria = 1 AND cod_categoria_aux = 1");                         
                            //$respuesta = $this->dbl->delete("mos_registro", "IDDoc = " . $atr[id]);
                        }
                        else{
                            $respuesta = $this->dbl->delete("mos_documentos", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_parametro_semaforo", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_revision", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_version", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documentos_categoria", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_parametro_modulos", "id_registro = " . $atr[id] . " AND cod_categoria = 1 AND cod_categoria_aux = 1");
                            //$respuesta = $this->dbl->delete("mos_documentos_datos_formulario", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documentos_estrorg_arbolproc", "IDDoc = " . $atr[id]);
                            //$respuesta = $this->dbl->delete("mos_registro", "IDDoc = " . $atr[id]);
                            $sql = "Select Max(IDDoc) as IDDoc From mos_documentos  Where Codigo_doc='".$val[Codigo_doc]."'";
                            $arr1 = $this->dbl->query($sql, array());
                            //$resp1 = mysql_query($sql);
                            //$arr1  = mysql_fetch_assoc($resp1);
                            $codigo = $arr1[0][IDDoc];

                            $sql= "Update mos_documentos Set
                                                    muestra_doc = 'S'
                                                    Where IDDoc = '$codigo'";
                            $this->dbl->insert_update($sql);
                            $sql = "UPDATE mos_documentos_datos_formulario SET IDDoc = $codigo"
                                . " WHERE IDDoc = $atr[id] ";
                            $this->dbl->insert_update($sql);
                            $sql = "UPDATE mos_registro SET IDDoc = $codigo"
                                    . " WHERE IDDoc = $atr[id] ";
                            $this->dbl->insert_update($sql);
                            $sql = "UPDATE mos_registro_formulario SET IDDoc = $codigo"
                                    . " WHERE IDDoc = $atr[id] ";
                            $this->dbl->insert_update($sql);
                        }
                        $nuevo = "IDDoc: \'$atr[id]\'";
                        $this->registraTransaccionLog(3,$nuevo,'', '');
                        
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaDocumentos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarDocumentos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblDocumentos", "");
                $config_col=array(
               array( "width"=>"1%","ValorEtiqueta"=>"ID"),     
               array( "width"=>"2%","ValorEtiqueta"=>"Estado"),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos("Días", "dias_vig", $parametros)),
               array( "width"=>"8%","ValorEtiqueta"=>"<div style='width:100px'>&nbsp;</div>"),
               
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[Codigo_doc], "Codigo_doc", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombre_doc], "nombre_doc", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType], "contentType", $parametros)),               
               array( "width"=>"5%","ValorEtiqueta"=>"Visor de Google"),  
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType_visualiza], "contentType_visualiza", $parametros)),
                
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[elaboro], "elaboro", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[reviso], "reviso", $parametros)),               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[aprobo], "aprobo", $parametros)),
               array( "width"=>"4%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[formulario], "formulario", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[v_meses], "v_meses", $parametros)),                    
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha], "fecha", $parametros)),                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),                  
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Revisión", "fecha_revision", $parametros)),     
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Fecha de Revisión", "fecha_revision", $parametros)),  
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[palabras_claves], "palabras_claves", $parametros)),               
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[arbproc], "arbproc", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[observacion], "observacion", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros)),               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_filial], "id_filial", $parametros)),               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[doc_visualiza], "doc_visualiza", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[muestra_doc], "muestra_doc", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estrucorg], "estrucorg", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apli_reg_estrorg], "apli_reg_estrorg", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apli_reg_arbproc], "apli_reg_arbproc", $parametros)),
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               
               
               
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 29;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Documentos'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar Documentos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar Documentos'>"));
               
                $config=array();
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        //case 1:
//                        case 2:
//                        case 3:
                        case 7:
                            if (in_array($i, $array_columns)) {
                                if($_SESSION[CookM] == 'S')
                                    array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;
                            break;

                        default:
                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;
                            
                            break;
                    }
                }
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("semaforo", "semaforo");
                $grid->setFuncion("accion", "colum_admin");
                $grid->setFuncion("nom_visualiza", "archivo");
                $grid->setFuncion("Codigo_doc", "codigo_doc");
                $grid->setFuncion("formulario", "BuscaRegistros");
                $grid->setFuncion("version", "version");
                $grid->setFuncion("arbol_organizacional", "BuscaOrganizacionalTodosVerMas");
                $grid->setAligns(1,"center");
                $grid->setAligns(6,"center");
                $grid->setAligns(7,"center");
                $grid->setAligns(8,"center");
                $grid->setFuncion("contentType_visualiza","archivo_editable");
                $grid->setFuncion("contentType","archivo_descarga");
                
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
                
         
        
            public function verListaDocumentosReporte($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarDocumentos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblDocumentos", "");
                $config_col=array(
               array( "width"=>"1%","ValorEtiqueta"=>"ID"),     
               array( "width"=>"2%","ValorEtiqueta"=>"<div style='width:50px'>&nbsp;</div>"),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos("Días", "dias_vig", $parametros)),
               array( "width"=>"8%","ValorEtiqueta"=>"&nbsp;"),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[Codigo_doc], "Codigo_doc", $parametros)), 
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombre_doc], "nombre_doc", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType], "contentType", $parametros)),               
               array( "width"=>"2%","ValorEtiqueta"=>"Visor de Google"),  
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType_visualiza], "contentType_visualiza", $parametros)),
               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[elaboro], "elaboro", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[reviso], "reviso", $parametros)),               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[aprobo], "aprobo", $parametros)),
               array( "width"=>"4%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[formulario], "formulario", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[v_meses], "v_meses", $parametros)),                    
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha], "fecha", $parametros)),                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),                  
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Revisión", "fecha_revision", $parametros)),     
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Fecha de Revisión", "fecha_revision", $parametros)),  
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[palabras_claves], "palabras_claves", $parametros)),               
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[arbproc], "arbproc", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[observacion], "observacion", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros)),               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_filial], "id_filial", $parametros)),               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[doc_visualiza], "doc_visualiza", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[muestra_doc], "muestra_doc", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estrucorg], "estrucorg", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apli_reg_estrorg], "apli_reg_estrorg", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apli_reg_arbproc], "apli_reg_arbproc", $parametros)),
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               
               
               
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"3%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 29;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Documentos'>"));
                */
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar Documentos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar Documentos'>"));
               
                $config=array();
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        //case 1:
//                        case 2:
                          case 20:
                              
                              if ((in_array($i, $array_columns))&&(strlen($parametros['b-id_organizacion'])<=0))
                                  array_push($config,$config_col[$i]);
                              else                                
                                $grid->hidden[$i] = true;
                              break;
//                        case 7:
//                            if($_SESSION[CookM] == 'S')
//                                array_push($config,$config_col[$i]);
//                            break;

                        default:
                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;
                            
                            break;
                    }
                }
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("dias_vig", "semaforo_reporte");
                $grid->setFuncion("semaforo", "columa_accion");
                $grid->setFuncion("accion", "colum_admin");
                $grid->setFuncion("nom_visualiza", "archivo");
                $grid->setFuncion("Codigo_doc", "codigo_doc");
                $grid->setFuncion("formulario", "BuscaRegistros");
                $grid->setFuncion("version", "version");
                $grid->setFuncion("arbol_organizacional", "BuscaOrganizacionalTodosVerMas");
                $grid->setAligns(1,"center");
                $grid->setAligns(6,"center");
                $grid->setAligns(7,"center");
                $grid->setAligns(8,"center");
                $grid->setFuncion("contentType_visualiza","archivo_editable");
                $grid->setFuncion("contentType","archivo_descarga");
                
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina", "document");
                }
                return $out;
            }
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarDocumentos($parametros, 1, 100000);
            $data=$this->dbl->data;
            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

             $grid->SetConfiguracion("tblDocumentos", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");                
                 
          $config_col=array(
               array( "width"=>"1%","ValorEtiqueta"=>"ID"),     
               array( "width"=>"2%","ValorEtiqueta"=>"Estado"),
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos("Días", "dias_vig", $parametros)),
               array( "width"=>"8%","ValorEtiqueta"=>"&nbsp;"),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[Codigo_doc], "Codigo_doc", $parametros)), 
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombre_doc], "nombre_doc", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType], "contentType", $parametros)),               
               array( "width"=>"2%","ValorEtiqueta"=>"Visor de Google"),  
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType_visualiza], "contentType_visualiza", $parametros)),
               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[elaboro], "elaboro", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[reviso], "reviso", $parametros)),               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[aprobo], "aprobo", $parametros)),
               array( "width"=>"4%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[formulario], "formulario", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[v_meses], "v_meses", $parametros)),                    
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha], "fecha", $parametros)),                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),                  
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Revisión", "fecha_revision", $parametros)),     
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Fecha de Revisión", "fecha_revision", $parametros)),  
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[palabras_claves], "palabras_claves", $parametros)),               
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[arbproc], "arbproc", $parametros)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[observacion], "observacion", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros)),               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_filial], "id_filial", $parametros)),               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[doc_visualiza], "doc_visualiza", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[muestra_doc], "muestra_doc", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[estrucorg], "estrucorg", $parametros)),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apli_reg_estrorg], "apli_reg_estrorg", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[apli_reg_arbproc], "apli_reg_arbproc", $parametros)),
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               
               
               
                );

                $columna_funcion =10;
           // $grid->hidden = array(0 => true);
           if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  utf8_decode($value[espanol])));
                    $k++;
                }
                $columna_funcion =10;
                $config = array();            
                $array_columns =  explode('-', $parametros['mostrar-col']);            
                
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
//                        case 2:
//                          case 20:
//                              
//                              if ((in_array($i, $array_columns))&&(strlen($parametros['b-id_organizacion'])<=0))
                                  array_push($config,$config_col[$i]);
//                              else                                
//                                $grid->hidden[$i] = true;
//                              break;
//                        case 7:
//                            if($_SESSION[CookM] == 'S')
//                                array_push($config,$config_col[$i]);
                            break;
                        case 3:
                        case 6:
                        case 7:
                        case 8:
                            $grid->hidden[$i] = true;
                            break;
                        default:
                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else                                
                                $grid->hidden[$i] = true;
                            
                            break;
                    }
                }
                $grid->setParent($this);
                //$grid->setFuncion("nom_visualiza", "archivo");
                $grid->setFuncion("semaforo", "semaforo_excel");
                $grid->setFuncion("Codigo_doc", "codigo_doc");
                $grid->setFuncion("version", "version");
                $grid->setFuncion("arbol_organizacional", "BuscaOrganizacionalTodos");
                $grid->setAligns(1,"center");
                $grid->setAligns(6,"center");
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
 
            public function indexDocumentos($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="dias_vig";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-7-8-9-12-13-14-17-20";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                $k = 23;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    //$parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                     
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $value[espanol] . '</label>                                  
                            </div>';
                    $k++;
                }
                $grid = $this->verListaDocumentos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Documentos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Documentos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $ut_tool = new ut_Tool();
                $contenido['REVISORES'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['APROBO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);

                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }  
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
                } 
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","documentos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'documentos/documentos.js?'.  rand());
                $objResponse->addScript("$('#MustraCargando').hide();");                                
                //$objResponse->addScript('setTimeout(function(){ init_filtrar(); }, 500);');
                //$objResponse->addScript('setTimeout(function(){ init_tabla(); }, 500);');
                /*JS init_filtrar*/
                $objResponse->addScript('$( "#b-reviso" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                        }); 
                                        $( "#b-elaboro" ).select2({
                                            placeholder: "Selecione el elaborador",
                                            allowClear: true
                                        }); 
                                        $( "#b-aprobo" ).select2({
                                            placeholder: "Selecione el aprobador",
                                            allowClear: true
                                        }); 
                                        $("#b-fecha-desde").datepicker();        
                                        $("#b-fecha-hasta").datepicker();
                                        $("#b-fecha_rev-desde").datepicker();
                                        $("#b-fecha_rev-hasta").datepicker();
                                        PanelOperator.initPanels("");
                                        ScrollBar.initScroll();
                                        init_filtro_rapido();
                                        init_filtro_ao_simple();');
                
                /*Js init_tabla*/
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                    event.preventDefault();
                                    var id = $(this).attr('tok');
                                    $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                    $('#myModal-Ventana-Titulo').html('');
                                    $('#myModal-Ventana').modal('show');
                                });");
                
                return $objResponse;
            }
            
            public function indexDocumentosFormulario($parametros)
            {
                $parametros['b-formulario'] = 'S';
                //return $this->indexDocumentosReporte($parametros);
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if (!isset($parametros['b-formulario'])){
                    $contenido[OTRAS_OPCIONES] = '<li>
                                    <a href="#"  onClick="reporte_documentos_pdf();">
                                      <i class="icon icon-alert-print"></i>
                                      <span>Documentos</span>
                                    </a>
                                  </li>';
                }
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="dias_vig";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-4-5-6-12-14-17-20";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 23;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    //$parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '
                                  <div class="checkbox">      
                                      <label>
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $value[espanol] . '</label>
                                  </div>
                            ';
                    $k++;
                }
                $grid = $this->verListaDocumentosReporte($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Documentos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Documentos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['PERMISO_INGRESAR'] = 'display:none;';
                $ut_tool = new ut_Tool();
                $contenido['REVISORES'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['APROBO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(2);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }  
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
                } 
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';

                $template->setTemplate("mostrar_colums_reporte_reg");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","documentos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'documentos/documentos_reporte_reg.js?'.  rand());
                $objResponse->addScript("$('#MustraCargando').hide();");            
                if (($parametros['b-formulario'])=='S'){
                    $objResponse->addScript("$('#b-formulario').prop('checked',true);");
                }
                //$objResponse->addScript('setTimeout(function(){ init_documentos(); }, 500);');
                //$objResponse->addScript('setTimeout(function(){ init_tabla_reporte(); }, 500);');
                /* JS init_documentos()*/
                $objResponse->addScript('$( "#b-reviso" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); 
                                        $( "#b-elaboro" ).select2({
                                            placeholder: "Selecione el elaborador",
                                            allowClear: true
                                          }); 
                                        $( "#b-aprobo" ).select2({
                                            placeholder: "Selecione el aprobador",
                                            allowClear: true
                                          }); 
                                        $("#b-fecha-desde").datepicker();
                                        $("#b-fecha-hasta").datepicker();
                                        $("#b-fecha_rev-desde").datepicker();
                                        $("#b-fecha_rev-hasta").datepicker();
                                        if(($("#b-formulario").is(":checked"))) {
                                            $("#b-formulario").parent().parent().hide();
                                        }

                                        PanelOperator.initPanels("");
                                        ScrollBar.initScroll();
                                        PanelOperator.showSearch("");

                                        init_filtro_rapido();
                                        init_filtro_ao_simple();
                                        PanelOperator.resize();');
                /* JS init_tabla_reporte*/
                $objResponse->addScript("$('.ver-documento').on('click', function (event) {
                                        event.preventDefault();
                                        var id = $(this).attr('tok');
                                        array = new XArray();
                                        array.setObjeto('Documentos','ver_visualiza');
                                        array.addParametro('id',id);
                                        array.addParametro('import','clases.documentos.Documentos');
                                        var cadena = window.location + '';
                                        if (cadena.indexOf('index.php')!=-1) {
                                            window.location = 'index.php#detail-content';
                                        }
                                        else{
                                            window.location = 'portal.php#detail-content';
                                        }
                                        xajax_Loading(array.getArray());
                                    });

                                    $('.ver-registros').on('click', function (event) {
                                        event.preventDefault();

                                        var id = $(this).attr('tok');
                                        array = new XArray();
                                        array.setObjeto('Registros','indexRegistrosListado');
                                        array.addParametro('titulo',$('#desc-mod-act').html());

                                        array.addParametro('id',id);
                                        array.addParametro('import','clases.registros.Registros');
                                        xajax_Loading(array.getArray());                                
                                        //window.location = 'index.php#detail-content';

                                    });

                                    $('.ver-mas').on('click', function (event) {
                                        event.preventDefault();
                                        var id = $(this).attr('tok');
                                        $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                        $('#myModal-Ventana-Titulo').html('');
                                        $('#myModal-Ventana').modal('show');
                                    });");
                
                return $objResponse;
            }
                   
            
            public function indexDocumentosReporte($parametros)
            {
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                if (!isset($parametros['b-formulario'])){
                    $contenido[OTRAS_OPCIONES] = '<li>
                                    <a href="#"  onClick="reporte_documentos_pdf();">
                                      <i class="icon icon-alert-print"></i>
                                      <span>Documentos</span>
                                    </a>
                                  </li>';
                }
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="dias_vig";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-4-5-14-17-20";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 23;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    //$parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '
                                  <div class="checkbox">      
                                      <label>
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col">   &nbsp;
                                      ' . $value[espanol] . '</label>
                                  </div>
                            ';
                    $k++;
                }
                $grid = $this->verListaDocumentosReporte($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Documentos();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Documentos';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['PERMISO_INGRESAR'] = 'display:none;';
                $ut_tool = new ut_Tool();
                $contenido['REVISORES'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['APROBO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(1);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido["N_" . strtoupper($key)] =  $value;
                }  
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido["P_" . strtoupper($key)] =  $value;
                } 
                $template->setTemplate("busqueda");
                $template->setVars($contenido);
                $contenido['CAMPOS_BUSCAR'] = $template->show();
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';

                $template->setTemplate("mostrar_colums_reporte");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                $template->setTemplate("listar");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                $objResponse->addAssign('modulo_actual',"value","documentos");
                $objResponse->addIncludeScript(PATH_TO_JS . 'documentos/documentos_reporte.js?'.  rand());
                $objResponse->addScript("$('#MustraCargando').hide();");            
                if (($parametros['b-formulario'])=='S'){
                    $objResponse->addScript("$('#b-formulario').prop('checked',true);");
                }
                /* JS init_documentos() */
                $objResponse->addScript('$( "#b-reviso" ).select2({
                                                placeholder: "Selecione el revisor",
                                                allowClear: true
                                              }); 
                                            $( "#b-elaboro" ).select2({
                                                placeholder: "Selecione el elaborador",
                                                allowClear: true
                                              }); 
                                            $( "#b-aprobo" ).select2({
                                                placeholder: "Selecione el aprobador",
                                                allowClear: true
                                              }); 
                                            $("#b-fecha-desde").datepicker();
                                            $("#b-fecha-hasta").datepicker();
                                            $("#b-fecha_rev-desde").datepicker();
                                            $("#b-fecha_rev-hasta").datepicker();
                                            if(($("#b-formulario").is(":checked"))) {
                                                $("#b-formulario").parent().parent().hide();
                                            }

                                            PanelOperator.initPanels("");
                                            ScrollBar.initScroll();
                                            PanelOperator.showSearch("");

                                            init_filtro_rapido();
                                            init_filtro_ao_simple();
                                            PanelOperator.resize();');
                $objResponse->addScript("$('#tblDocumentos > tbody > tr').addClass('cursor-pointer');
                                        $('#tblDocumentos > tbody > tr').on('click', function (event) {
                                                event.preventDefault();
                                                var id = $(this).attr('tok');
                                                array = new XArray();
                                                array.setObjeto('Documentos','ver_visualiza');
                                                array.addParametro('id',id);
                                                array.addParametro('import','clases.documentos.Documentos');
                                                var cadena = window.location + '';
                                                if (cadena.indexOf('index.php')!=-1) {
                                                    window.location = 'index.php#detail-content';
                                                }
                                                else{
                                                    window.location = 'portal.php#detail-content';
                                                }
                                                xajax_Loading(array.getArray());
                                            });

                                            $('.ver-mas').on('click', function (event) {
                                                event.preventDefault();
                                                var id = $(this).attr('tok');
                                                $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                                $('#myModal-Ventana-Titulo').html('');
                                                $('#myModal-Ventana').modal('show');
                                            });");
                //$objResponse->addScript('setTimeout(function(){ init_documentos(); }, 500);');
                //$objResponse->addScript('setTimeout(function(){ init_tabla_reporte(); }, 500);');
                
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido_1["N_" . strtoupper($key)] =  $value;
                }                
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido_1["P_" . strtoupper($key)] =  $value;
                }     
                
                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=8;$i++){                    
                    $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);                    
                    $ids[] = $i*7;
                }
                $contenido_1[NUM_ITEMS_ESP] = 0;
                $contenido_1[CHECKED_VIGENCIA] = 'checked="checked"';
                $contenido_1['SEMAFORO'] = $ut_tool->combo_array("semaforo", $desc, $ids,false,56,false,false,false,false,'display:inline;width:70px');

                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=48;$i++){                    
                    $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);                    
                    $ids[] = $i;
                }
                $contenido_1['V_MESES'] = $ut_tool->combo_array("v_meses", $desc, $ids,false,24,false,false,false,false,'display:inline;width:70px');

                $contenido_1['REVISORES'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido_1['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido_1['APROBO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido_1['WORKFLOW'] = 'N';
                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                $array = $campos_dinamicos->crear_campos_dinamicos(1,null,6,14);
                $contenido_1[OTROS_CAMPOS] = $array[html];
                $js = $array[js];
//                if (count($this->parametros) <= 0){
//                        $this->cargar_parametros();
//                }                
//                $k = 19;
//                $contenido_1[OTROS_CAMPOS] = "";
//                foreach ($this->parametros as $value) {                    
//                    $sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria='1' and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
//                    $data = $this->dbl->query($sql, array());
//                    $ids = array(''); 
//                    $desc = array('Seleccione');
//                    foreach ($data as $value_combos) {
//                        $ids[] = $value_combos[cod_parametro_det]; 
//                        $desc[] = $value_combos[descripcion];                                                
//                    }
//                    $combo_dinamico = $ut_tool->combo_array("cmb-".$value[cod_parametro], $desc, $ids, 'data-validation="required"');
//                    $contenido_1[OTROS_CAMPOS] .= '<div class="form-group">
//                                  <label for="cmb-'.$value[cod_parametro].'" class="col-md-2 control-label">' . $value[espanol] . '</label>
//                                  <div class="col-md-6">      
//                                      '.$combo_dinamico.' 
//                                  </div>
//                            </div>';
//                    $k++;
//                }
                //$contenido_1[CSS_TABLA_FILEUPLOAD_VIS] = $contenido_1[CSS_TABLA_FILEUPLOAD] = 
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("formulario");
//                $template->setVars($contenido_1);
//                $contenido['CAMPOS'] = $template->show();

//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
                $contenido_1['TITULO_FORMULARIO'] = "Formulario Crear";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Documentos";
                $contenido_1['PAGINA_VOLVER'] = "listarDocumentos.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "new";
                $contenido_1['ID'] = "-1";

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('#fecha').datepicker();");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show');");
                $objResponse->addScript($js);
                return $objResponse;
            }
            
            public function crear_revision($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido_1["N_" . strtoupper($key)] =  $value;
                }                
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido_1["P_" . strtoupper($key)] =  $value;
                }                     
                $val = $this->verDocumentos($parametros[id]); 
                $contenido_1['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['elaboro']);
                
                $contenido_1[NOMBRE_DOC] = $val["Codigo_doc"].'-'.$val["nombre_doc"].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                $contenido_1[REVISION] = $this->verDocumentosRevisionSiguiente($parametros[id],$val[version]);
                $contenido_1[FECHA] = date('d/m/Y');
                $contenido_1['VERSION'] = $val["version"];
                
                //$contenido_1[CSS_TABLA_FILEUPLOAD_VIS] = $contenido_1[CSS_TABLA_FILEUPLOAD] = 
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("formulario_revision");
//                $template->setVars($contenido_1);
//                $contenido['CAMPOS'] = $template->show();

//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
                $contenido_1['TITULO_FORMULARIO'] = "Crear&nbsp;Documentos";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Documentos";
                $contenido_1['PAGINA_VOLVER'] = "listarDocumentos.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "new";
                $contenido_1['ID'] = $val["IDDoc"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });
                          $( '#elaboro' ).select2({
                                placeholder: 'Selecione el revisor',
                                allowClear: true
                              });");
                $objResponse->addScript("$('#fecha').datepicker();");
                return $objResponse;
            }
            
            public function crear_version($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido_1["N_" . strtoupper($key)] =  $value;
                }                
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido_1["P_" . strtoupper($key)] =  $value;
                }                     
                $val = $this->verDocumentos($parametros[id]); 
                $contenido_1['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['elaboro']);
                
                $contenido_1[NOMBRE_DOC_AUX] = $val["Codigo_doc"].'-'.$val["nombre_doc"].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                $contenido_1[REVISION] = $this->verDocumentosRevisionSiguiente($parametros[id],$val[version]);
                $contenido_1[FECHA] = date('d/m/Y');
                $contenido_1['VERSION'] = $val["version"]+1;
                $contenido_1['CODIGO_DOC'] = ($val["Codigo_doc"]);
                $contenido_1['NOMBRE_DOC'] = ($val["nombre_doc"]);
                $sql = "SELECT d.IDDoc                                                                        
                                    ,Codigo_doc 
                                    ,nombre_doc
                                                                       
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha  
                                    ,version
                                    ,contentType
                                    ,nom_visualiza 
                                    ,contentType_visualiza                                                                       
                                    ,formulario                                                                                                                                                                                                                      
                            FROM mos_documentos d
                            WHERE Codigo_doc='$val[Codigo_doc]' ";
                $data = $this->dbl->query($sql, array());
                $grid= new DataGrid();
                $grid->SetConfiguracionMSKS("tblDocumentos", "");
                $config=array(
//htmlentities(
               //array( "width"=>"1%","ValorEtiqueta"=>"ID"),     
               array( "width"=>"3%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[Codigo_doc], ENT_QUOTES, "UTF-8")),     
               array( "width"=>"20%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nombre_doc], ENT_QUOTES, "UTF-8")),     
               array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha], ENT_QUOTES, "UTF-8")),         
               array( "width"=>"2%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[version], ENT_QUOTES, "UTF-8")),
               array( "width"=>"2%","ValorEtiqueta"=>"Archivos"),                                                    
               //array( "width"=>"8%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[contentType_visualiza], ENT_QUOTES, "UTF-8")),                
               //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[elaboro], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[formulario], ENT_QUOTES, "UTF-8")),                                                                   
                );                

                $func= array();

                $columna_funcion = -1;
                               
                
                $grid->hidden[0] = $grid->hidden[5] =  $grid->hidden[7] = true;                
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                
                $grid->setFuncion("nom_visualiza", "archivo");
                $grid->setFuncion("Codigo_doc", "codigo_doc");
                $grid->setFuncion("version", "version");
                
                $grid->setAligns(1,"center");
                $grid->setAligns(6,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data);
                $contenido_1['TABLA']= $grid->armarTabla();
                
                
                //$contenido_1[CSS_TABLA_FILEUPLOAD_VIS] = $contenido_1[CSS_TABLA_FILEUPLOAD] = 
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("formulario_version");
//                $template->setVars($contenido_1);
//                $contenido['CAMPOS'] = $template->show();

//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
                $contenido_1['TITULO_FORMULARIO'] = "Crear&nbsp;Documentos";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Documentos";
                $contenido_1['PAGINA_VOLVER'] = "listarDocumentos.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "new";
                $contenido_1['ID'] = $val["IDDoc"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });$( '#elaboro' ).select2({
                                placeholder: 'Selecione el revisor',
                                allowClear: true
                              });");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show');");
                $objResponse->addScript("$('#fecha').datepicker();");
                return $objResponse;
            }
     
 
            public function guardar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                unset ($parametros['id']);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $parametros["fecha"] = formatear_fecha($parametros["fecha"]);
                    $archivo = '';
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            
                            $parametros[contentType] = $parametros[tipo_doc];//'application/pdf';                                    
                            $parametros[nom_archivo] = $parametros['filename'];
                           
                    }
                    $doc_vis = '';
                    if((isset($parametros[filename_vis]))&& ($parametros[filename_vis] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis']), "rb");
                            $doc_vis = fread($fp, $tamanio);
                            $doc_vis = addslashes($doc_vis);
                            fclose($fp);
                            
                            $parametros[contentType_visualiza] = $parametros[tipo_doc_vis];//'application/pdf';                                    
                            $parametros[nom_visualiza] = $parametros['nombre_doc_vis'];
                           
                    }
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    //if (!isset($parametros[formulario])) $parametros[formulario] = 'N';
                    $parametros[formulario] = 'N';
                    for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["nombre_din_$i"])){   
                                $parametros[formulario] = 'S';
                                break;
                            }
                    }
                    
                    $respuesta = $this->ingresarDocumentos($parametros,$archivo,$doc_vis);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
//                        if (count($this->parametros) <= 0){
//                            $this->cargar_parametros();
//                        }                                                                
//                        foreach ($this->parametros as $value) {                    
//                            $params[cod_parametro_det] = $parametros["cmb-".$value[cod_parametro]];
//                            $params[cod_parametro] = $value[cod_parametro];
//                            $params[id_registro] = $respuesta;
//                            if (strlen($params[cod_parametro_det])>0)
//                                $this->ingresarParametro($params);
//                            //$this->ingresarParametro($params);
//                        }
                        $parametros[id] = $respuesta;
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($parametros, 1);
                        
                        $arr = explode(",", $parametros[nodos]);

                        $params[IDDoc] = $respuesta;
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $params[tipo] = 'EO';
                                $this->ingresarArbol($params);
                        }
                        $params = array();
                        $params[IDDoc] = $respuesta;
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["nombre_din_$i"])){                                
                                //$atr[IDDoc],'$atr[nombre]','$atr[tipo]','$atr[valores]'
                                $params[nombre] = $parametros["nombre_din_$i"];
                                $params[tipo] = $parametros["tipo_din_$i"];                                
                                $params[orden] = $parametros["orden_din_$i"];  
                                if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9" )||($params[tipo] == "13" )){
                                    $params[valores] = str_replace("\n", "<br />", $parametros["valores_din_$i"]); 
                                }
                                else $params[valores] = '';
                                 
                                
                                //echo $parametros["cuerpo_$i"];
                                $this->ingresarCamposFormulario($params);
                            }
                        }
                        try{
                            unlink(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            if (((isset($parametros[filename_vis]))&& ($parametros[filename_vis] !=''))&&(file_exists(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis'])))) {
                                unlink(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis']));
                            }
                        } catch (Exception $ex) {

                        }
                        
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',"El Documentos '$parametros[nombre_doc]' ha sido ingresado con exito");
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
            
            public function guardar_version($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                //unset ($parametros['id']);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $parametros["fecha"] = formatear_fecha($parametros["fecha"]);
                    $archivo = '';
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            
                            $parametros[contentType] = $parametros[tipo_doc];//'application/pdf';                                    
                            $parametros[nom_archivo] = $parametros['filename'];
                           
                    }
                    $doc_vis = '';
                    if((isset($parametros[filename_vis]))&& ($parametros[filename_vis] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis']), "rb");
                            $doc_vis = fread($fp, $tamanio);
                            $doc_vis = addslashes($doc_vis);
                            fclose($fp);
                            
                            $parametros[contentType_visualiza] = $parametros[tipo_doc_vis];//'application/pdf';                                    
                            $parametros[nom_visualiza] = $parametros['nombre_doc_vis'];
                           
                    }
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    if (!isset($parametros[formulario])) $parametros[formulario] = 'N';
                    
                    $respuesta = $this->ingresarDocumentosVersion($parametros,$archivo,$doc_vis);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        if (count($this->parametros) <= 0){
                            $this->cargar_parametros();
                        }                                                                
                        foreach ($this->parametros as $value) {                    
                            $params[cod_parametro_det] = $parametros["cmb-".$value[cod_parametro]];
                            $params[cod_parametro] = $value[cod_parametro];
                            $params[id_registro] = $respuesta;
                            if (strlen($params[cod_parametro_det])>0)
                                $this->ingresarParametro($params);
                            //$this->ingresarParametro($params);
                        }
                        
                        
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',"El Documentos '$parametros[nombre_doc]' ha sido ingresado con exito");
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
            
            public function guardar_revision($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                //unset ($parametros['id']);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $parametros["fecha"] = formatear_fecha($parametros["fecha"]);
                    
                    
                    $respuesta = $this->ingresarDocumentosRevision($parametros);

                    if (preg_match("/ha sido ingresada con exito/",$respuesta ) == true) {
                    //if (strlen($respuesta ) < 10 ) {
                        
                        
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verDocumentos($parametros[id]); 

                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                foreach ( $this->nombres_columnas as $key => $value) {
                    $contenido_1["N_" . strtoupper($key)] =  $value;
                }                
                if (count($this->placeholder) <= 0){
                        $this->cargar_placeholder();
                }
                foreach ( $this->placeholder as $key => $value) {
                    $contenido_1["P_" . strtoupper($key)] =  $value;
                }    
                $contenido_1['IDDOC'] = $val["IDDoc"];
                $contenido_1['CODIGO_DOC'] = ($val["Codigo_doc"]);
                $contenido_1['NOMBRE_DOC'] = ($val["nombre_doc"]);
                $contenido_1[NOMBRE_DOC_AUX] = $val["Codigo_doc"].'-'.$val["nombre_doc"].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                $contenido_1['VERSION'] = $val["version"];
                $contenido_1['FECHA'] = ($val["fecha"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['PALABRAS_CLAVES'] = ($val["palabras_claves"]);
                $contenido_1['FORMULARIO'] = ($val["formulario"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
                $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
                $contenido_1['ID_FILIAL'] = $val["id_filial"];
                $contenido_1['NOM_VISUALIZA'] = ($val["nom_visualiza"]);
                $contenido_1['DOC_VISUALIZA'] = $val["doc_visualiza"];
                $contenido_1['CONTENTTYPE_VISUALIZA'] = ($val["contentType_visualiza"]);
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['OBSERVACION'] = $val["observacion"];
                $contenido_1['MUESTRA_DOC'] = ($val["muestra_doc"]);
                $contenido_1['ESTRUCORG'] = ($val["estrucorg"]);
                $contenido_1['ARBPROC'] = ($val["arbproc"]);
                $contenido_1['APLI_REG_ESTRORG'] = ($val["apli_reg_estrorg"]);
                $contenido_1['APLI_REG_ARBPROC'] = ($val["apli_reg_arbproc"]);
                $contenido_1['WORKFLOW'] = ($val["workflow"]);
                $contenido_1['SEMAFORO'] = $val["semaforo"];
                $contenido_1['V_MESES'] = $val["v_meses"];
//                $contenido_1['REVISO'] = $val["reviso"];
//                $contenido_1['ELABORO'] = $val["elaboro"];
//                $contenido_1['APROBO'] = $val["aprobo"];
                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=8;$i++){                    
                    $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);                    
                    $ids[] = $i*7;
                }
                //$contenido_1[CHECKED_VIGENCIA] = 'checked="checked"';
                $contenido_1[CHECKED_VIGENCIA] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
                $contenido_1['SEMAFORO'] = $ut_tool->combo_array("semaforo", $desc, $ids,false,$val["semaforo"],false,false,false,false,'display:inline;width:70px');

                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=48;$i++){                    
                    $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);                    
                    $ids[] = $i;
                }
                $contenido_1['V_MESES'] = $ut_tool->combo_array("v_meses", $desc, $ids,false,$val["v_meses"],false,false,false,false,'display:inline;width:70px');

                $contenido_1['REVISORES'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['reviso']);
                $contenido_1['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['elaboro']);
                $contenido_1['APROBO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['aprobo']);
//                if (count($this->parametros) <= 0){
//                        $this->cargar_parametros();
//                }                                
//                $contenido_1[OTROS_CAMPOS] = "";
//                $sql = "SELECT cod_parametro, cod_parametro_det FROM mos_parametro_modulos WHERE cod_categoria = 1 and id_registro = $val[IDDoc]";
//                
//                $data_params = $this->dbl->query($sql, array());
//                
//                $valores_params = array();
//                foreach ($data_params as $value_data_params) {
//                    $valores_params[$value_data_params[cod_parametro]]  = $value_data_params[cod_parametro_det];
//                }
//                //print_r($valores_params);
//                foreach ($this->parametros as $value) {                    
//                    $sql = "select cod_parametro_det,descripcion from  mos_parametro_det where cod_categoria=1 and cod_parametro='".$value[cod_parametro]."' and vigencia='S'";
//                    $data = $this->dbl->query($sql, array());
//                    $ids = array(''); 
//                    $desc = array('Seleccione');
//                    foreach ($data as $value_combos) {
//                        $ids[] = $value_combos[cod_parametro_det]; 
//                        $desc[] = $value_combos[descripcion];                                                
//                    }
//                   //echo $valores_params[$value[cod_parametro]];
//                    $combo_dinamico = $ut_tool->combo_array("cmb-".$value[cod_parametro], $desc, $ids, 'data-validation="required"', $valores_params[$value[cod_parametro]]);
//                    $contenido_1[OTROS_CAMPOS] .= '<div class="form-group">
//                                  <label for="cmb-'.$value[cod_parametro].'" class="col-md-2 control-label">' . $value[espanol] . '</label>
//                                  <div class="col-md-6">      
//                                      '.$combo_dinamico.' 
//                                  </div>
//                            </div>';                    
//                }
                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                $array = $campos_dinamicos->crear_campos_dinamicos(1,$val[IDDoc],6,14);
                $contenido_1[OTROS_CAMPOS] = $array[html];
                $js_din = $array[js];
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if (strlen($val["nom_visualiza"])>0){
                    $contenido_1['DOC_VIS'] ='<div class="form-group" id="info_archivo_adjunto_vis">
                                    <label for="archivo" class="col-md-4 control-label">'. $contenido_1[N_NOM_VISUALIZA].'</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" style="">                                            
                                            <input type="text" class="form-control" style="width: 250px;display: inline;" readonly="readonly" id="info_nombre_vis" value="'.$val["nom_visualiza"].'">                                             
                                        </p>                      
                                  </div>  
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.PDF</span>
                             </div>';
                }  else {
                    $template->setTemplate("cargar_doc_vis");
                    $template->setVars($contenido_1);
                    $contenido_1['DOC_VIS'] = $template->show();
                }
                
                $this->listarCamposFormulario($parametros);
                $data=$this->dbl->data;
                //print_r($data);
                $item = "";
                $js = "";
                $i = 0;
                $ids = array('7','8','9','1','2','3','5','6','10','11','12','13');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo', 'Árbol Organizacional', 'Árbol Procesos','Vigencia');
                
                //$ids = array('7','8','9','1','2','3','5','6','10');
                //$desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo');
                foreach ($data as $value) {                          
                    $i++;
                    //echo $i;
                    
                    $item = $item. '<tr id="tr-esp-' . $i . '">';                      
                    if ($value[cant]*1 > 0){
                        $item = $item. '<td align="center">'.
                                '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
                                <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>' .
                                '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id_unico].'" type="hidden" >'.
                                '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.$value[orden].'" type="hidden" >'.
                                            '&nbsp;' .                                             
                                       '  </td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input value="'.$value[Nombre].'" readonly="" class="form-control" type="text" size="15" >'.
                                        '</td>';
                         
                         $item = $item. '<td>' .                                            
                                            '<input id="tipo_din_'. $i . '" value="'.$desc[array_search($value["tipo"],$ids)].'" readonly="" class="form-control" type="text" size="15" >'.
                                         '</td>';
                         $item = $item.  '<td>' .
                           ' <textarea cols="30" id="valores_din_'. $i . '" name="valores_din_'. $i . '" rows="2" readonly="">'. str_replace("<br />", "<br>", $value[valores]) .'</textarea>'.
                        '</td>';
                        
                        $js .= "actualizar_atributo_dinamico($i);";
                        $item = $item. '</tr>' ;  
                    }else
                    {
                        
                                                                    
                        $item = $item. '<td align="center">'.
                                            ' <a href="' . $i . '"  title="Eliminar " id="eliminar_esp_' . $i . '"> ' . 
                                            //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' . 
                                             '<i class="icon icon-remove"></i>'.
                                             '</a>' .
                                             '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
                                              <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'.
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.$value[orden].'" type="hidden" >'.
                                       '  </td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input id="nombre_din_'. $i . '" value="'.$value[Nombre].'" class="form-control" type="text" data-validation="required" size="15" maxlength="20" name="nombre_din_'. $i . '">'.
                                        '</td>';
                         $item = $item. '<td>' .
                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
                                         '</td>';
                         $item = $item.  '<td>' .
                           ' <textarea id="valores_din_'. $i . '" cols="30" rows="2" name="valores_din_'. $i . '" readonly="">'. str_replace("<br />", "<br>", $value[valores]) .'</textarea>'.
                        '</td>';
                        
                        
                        $item = $item. '</tr>' ;                    
                        $js .= '$("#eliminar_esp_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("href");                                
                                    $("tr-esp-'. $i .'").remove();
                                    var parent = $(this).parents().parents().get(0);
                                        $(parent).remove();
                            });';
                        $js .= "actualizar_atributo_dinamico($i);";
                    }
                }               
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;
                
                
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("formulario_editar");
                //$template->setVars($contenido_1);

                //$contenido['CAMPOS'] = $template->show();

                //$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->setTemplate("formulario");

                $contenido_1['TITULO_FORMULARIO'] = "Formulario Editar";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Documentos";
                $contenido_1['PAGINA_VOLVER'] = "listarDocumentos.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "upd";
                $contenido_1['ID'] = $val["IDDoc"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");    
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:first').tab('show');");
                $objResponse->addScript("$js");
                $objResponse->addScript("$js_din");
                //$objResponse->addScript("$('#fecha').datepicker();");
                return $objResponse;
            }
     
 
            public function actualizar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];

                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    $parametros["fecha"] = formatear_fecha($parametros["fecha"]);
                    $doc_vis = '';
                    if((isset($parametros[filename_vis]))&& ($parametros[filename_vis] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename_vis']), "rb");
                            $doc_vis = fread($fp, $tamanio);
                            $doc_vis = addslashes($doc_vis);
                            fclose($fp);
                            
                            $parametros[contentType_visualiza] = $parametros[tipo_doc_vis];//'application/pdf';                                    
                            $parametros[nom_visualiza] = $parametros['nombre_doc_vis'];
                           
                    }
                    if (!isset($parametros[vigencia])) $parametros[vigencia] = 'N';
                    //if (!isset($parametros[formulario])) $parametros[formulario] = 'N';
                    $parametros[formulario] = 'N';
                    for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["valores_din_$i"])){   
                                $parametros[formulario] = 'S';
                                break;
                            }
                    }
                    
                    $respuesta = $this->modificarDocumentos($parametros,$doc_vis);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {  
                        //print_r($parametros);
                        //print_r($parametros[nodos]);
                        $arr = explode(",", $parametros[nodos]);
                        $params[IDDoc] = $parametros[id];
                        $this->eliminarCargosArbol($parametros);
                        //print_r($params);
                        foreach($arr as $temp){
                                $params[id] = $temp;
                                $params[tipo] = 'EO';
                                $this->ingresarArbol($params);
                        }
//                        if (count($this->parametros) <= 0){
//                            $this->cargar_parametros();
//                        }                
//                                                
//                        $params[id_registro] = $parametros[id];
//                        $this->eliminarParametros($params);
//                        foreach ($this->parametros as $value) {                                                
//                            $params[cod_parametro_det] = $parametros["cmb-".$value[cod_parametro]];
//                            $params[cod_parametro] = $value[cod_parametro];
//                            if (strlen($params[cod_parametro_det])>0)
//                                $this->ingresarParametro($params);
//                        }
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        $campos_dinamicos->guardar_parametros_dinamicos($parametros, 1);
                        $sql = "DELETE FROM mos_documentos_datos_formulario WHERE IDDoc = $parametros[id] "
                                . " AND NOT id_unico IN (SELECT id_unico FROM mos_registro_formulario WHERE IDDoc = $parametros[id]) ";                               
                        $this->dbl->insert_update($sql);
                        $params[IDDoc] = $parametros[id];
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["nombre_din_$i"])){                                
                                //$atr[IDDoc],'$atr[nombre]','$atr[tipo]','$atr[valores]'
                                $params[nombre] = $parametros["nombre_din_$i"];
                                $params[tipo] = $parametros["tipo_din_$i"];                                
                                $params[orden] = $parametros["orden_din_$i"];  
                                if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9")||($params[tipo] == "13" )){
                                    $params[valores] = str_replace("\n", "<br />", $parametros["valores_din_$i"]); 
                                }
                                else $params[valores] = '';
                                 
                                
                                //echo $parametros["cuerpo_$i"];
                                $this->ingresarCamposFormulario($params);
                            }
                            else
                                if (isset($parametros["valores_din_$i"])){ 
                                    $params[orden] = $parametros["orden_din_$i"];  
                                    $params[id_unico] = $parametros["id_unico_din_$i"];  
                                    $this->actualizarOrdenCamposFormulario($params);
                                }
                        }
                        $objResponse->addScriptCall("MostrarContenido");
                        $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verDocumentos($parametros[id]);
                $respuesta = $this->eliminarDocumentos($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
            public function buscar($parametros)
            {
                $grid = $this->verListaDocumentos($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                $objResponse->addScript("init_tabla();");
                return $objResponse;
            }
            
            public function ver_fuente($parametros)
            {
                $objResponse = new xajaxResponse();
                $archivo_aux = $this->verDocumentoFuente($parametros[id]);
                $sql = "SELECT extension FROM mos_extensiones WHERE extension = '$archivo_aux[contentType]' OR contentType = '$archivo_aux[contentType]'";
                $total_registros = $this->dbl->query($sql, array());
                $Ext2 = $total_registros[0][extension];  
                $NombreDoc = $archivo_aux[nombre_doc];
                //echo $NombreDoc;
                $contenido2 = $archivo_aux[doc_fisico];
                //header("Content-type: application/pdf");
                //print $contenido2;
                $version = $archivo_aux[version];
                $Codigo = $archivo_aux[Codigo_doc];
                $carpeta =  $this->encryt->Decrypt_Text($_SESSION[BaseDato]);
                $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);
                
                $ruta_doc = $documento->ActivarDocumento();
                
                $html = '<div class="content-panel panel">
                <div class="content">
                  <a class="close-detail" href="#detail-content">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>
                  <div class="row" style="height:100%;">
                        <iframe src="http://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:100%;width:100%;" frameborder="0"></iframe>
                  </div>

                </div>
              </div>';
                $objResponse->addAssign('detail-content',"innerHTML",$html);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                //$objResponse->addScript("PanelOperator.initPanels('');");
                $objResponse->addScript("$('.close-detail').click(function (event) {
                        event.preventDefault();
                        PanelOperator.hideDetail('');
                    })

                    $('.detail-show').click(function (event) {
                        event.preventDefault();
                        PanelOperator.showDetail('');
                        PanelOperator.hideSearch('');
                    });");
                $objResponse->addScript("PanelOperator.showDetail('');");          
                $objResponse->addScript("PanelOperator.resize();"); 
                
                return $objResponse;
            }
            
            public function ver_visualiza($parametros)
            {
                $objResponse = new xajaxResponse();
                $archivo_aux = $this->verDocumentoPDF($parametros[id]);
                $contenido2 = $archivo_aux[doc_visualiza];
                $http = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
                $iframe = '';
                if (strlen($contenido2)>0){
                    
                    $html = "<a target=\"_blank\" title=\"Ver Documento PDF\"  href=\"pages/documentos/descargar_archivo_pdf.php?id=$archivo_aux[IDDoc]&token=" . md5($archivo_aux[IDDoc]) ."&des=1\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";                
               
                    $titulo_doc = $archivo_aux[nom_visualiza];
            
                    
                    
                    $sql = "SELECT extension FROM mos_extensiones WHERE extension = '$archivo_aux[contentType_visualiza]' OR contentType = '$archivo_aux[contentType_visualiza]'";
                    $total_registros = $this->dbl->query($sql, $atr);
                    $Ext2 = $total_registros[0][extension];
                    $NombreDoc = $archivo_aux[nom_visualiza].'.'.$Ext2;
                    //echo $NombreDoc;
                    
                    //header("Content-type: application/pdf");
                    //
                    //                            

                    //print $contenido2;
                    $version = "HOJA_VIDA";
                    $Codigo = $Ext2 = "";
                    $carpeta =  $this->encryt->Decrypt_Text($_SESSION[BaseDato]);
                    $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);

                    $ruta_doc = $documento->ActivarDocumento();
                    $titulo_doc = $documento->getNombreArchivo();
                    $iframe = '<iframe id="iframe-vis-aux" src="'.$ruta_doc.'" style="height:90%;width:100%;" frameborder="0"></iframe>';
                }
                else{
                    $archivo_aux = $this->verDocumentoFuente($parametros[id]);
                    $sql = "SELECT extension FROM mos_extensiones WHERE extension = '$archivo_aux[contentType]' OR contentType = '$archivo_aux[contentType]'";
                    $total_registros = $this->dbl->query($sql, array());
                    $Ext2 = $total_registros[0][extension];  
                    $NombreDoc = $archivo_aux[nombre_doc];
                    //echo $NombreDoc;
                    $contenido2 = $archivo_aux[doc_fisico];
                    //header("Content-type: application/pdf");
                    //print $contenido2;
                    $version = $archivo_aux[version];
                    $Codigo = $archivo_aux[Codigo_doc];
                    $carpeta =  $this->encryt->Decrypt_Text($_SESSION[BaseDato]);
                    $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);

                    $ruta_doc = $documento->ActivarDocumento();
                    $titulo_doc = $documento->getNombreArchivo();
                    $html = "<a target=\"_blank\" title=\"Ver Documento Fuente\"  href=\"pages/documentos/descargar_archivo.php?id=$archivo_aux[IDDoc]&token=" . md5($archivo_aux[IDDoc]) ."&des=1\">
                            
                            <i class=\"icon icon-view-document\"></i>
                        </a>";
                    $iframe = '<iframe id="iframe-vis" src="'.$http.'://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;" frameborder="0"></iframe>';
                }
                $html_registro = '';
                if ($archivo_aux[formulario]== 'S'){
                    $html_registro = " <li> <a id=\"a-ver-registros\" title=\"Ver Registros\" tok=\"$archivo_aux[IDDoc]\"  href=\"#\">
                            
                            <i class=\"icon icon-more\"></i>
                        </a>  </li>";
                }
                
                $html = '<div class="content-panel panel">
                <div class="content">
                    <div class="info-container" style="height:90%;">
                        <a class="close-detail" href="#detail-content">
                            <i class="glyphicon glyphicon-remove"></i>
                        </a>
                        <div class="panel-heading">
                        
                            <div class="row">
                                <div id="div-titulo-mod" class="panel-title col-xs-16">
                                    '. $titulo_doc .'
                                </div>

                                <div class="panel-actions col-xs-8">
                                    <ul class="navbar">                                
                                        <li>'.$html_registro.'  
                                            '. $html .'
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        
                        
                        </div>
                    
                  
                        <div class="row" id="div-iframe-vis"  style="height:100%;">
                            <!--<iframe id="iframe-vis" src="#" style="height:90%;width:100%;" frameborder="0"></iframe>-->
                            <!--<iframe id="iframe-vis" src="'.$http.'://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;" frameborder="0"></iframe>-->
                            '.$iframe.'
                        </div>
                        <!--<textarea id="text-iframe">'.$http.'://docs.google.com/gview?url='.$ruta_doc.'</textarea>-->
                        
                    </div>
              </div></div>';
                $objResponse->addAssign('detail-content',"innerHTML",$html);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                //$objResponse->addScript("PanelOperator.initPanels('');");
                $objResponse->addScript("$('.close-detail').click(function (event) {
                        event.preventDefault();
                        PanelOperator.hideDetail('');
                    })

                    $('.detail-show').click(function (event) {
                        event.preventDefault();
                        PanelOperator.showDetail('');
                        PanelOperator.hideSearch('');
                    });");
                $objResponse->addScript("PanelOperator.showDetail('');");  
                $objResponse->addScript("PanelOperator.resize();");
                $objResponse->addScript("init_ver_registros();");
                //$objResponse->addScript('setTimeout(function(){ alert("vaaa");$(\'#iframe-vis\').attr("src",$("#text-iframe").html()+"&embedded=true");},1000);');
                
                return $objResponse;
            }
            
            public function buscar_reporte($parametros)
            {
                $grid = $this->verListaDocumentosReporte($parametros);
                $objResponse = new xajaxResponse();
                
                $objResponse->addScript("limpiar_titulo();");
                if (strlen($parametros['b-id_organizacion'])>0){
                    //echo BuscaOrganizacional(array('id_organizacion' => $parametros['b-id_organizacion']));
                    $objResponse->addScript("$('#div-titulo-mod').html($('#div-titulo-mod').html() + '<br>' + '". BuscaOrganizacional(array('id_organizacion' => $parametros['b-id_organizacion'])). "');");
                }
                 
                    
                $objResponse->addAssign('grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("init_tabla_reporte();");
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verDocumentos($parametros[id]);

                $contenido_1['IDDOC'] = $val["IDDoc"];
                $contenido_1['CODIGO_DOC'] = ($val["Codigo_doc"]);
                $contenido_1['NOMBRE_DOC'] = ($val["nombre_doc"]);
                $contenido_1['VERSION'] = $val["version"];
                $contenido_1['FECHA'] = ($val["fecha"]);
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['PALABRAS_CLAVES'] = ($val["palabras_claves"]);
                $contenido_1['FORMULARIO'] = ($val["formulario"]);
                $contenido_1['VIGENCIA'] = ($val["vigencia"]);
                $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
                $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
                $contenido_1['ID_FILIAL'] = $val["id_filial"];
                $contenido_1['NOM_VISUALIZA'] = ($val["nom_visualiza"]);
                $contenido_1['DOC_VISUALIZA'] = $val["doc_visualiza"];
                $contenido_1['CONTENTTYPE_VISUALIZA'] = ($val["contentType_visualiza"]);
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['OBSERVACION'] = $val["observacion"];
                $contenido_1['MUESTRA_DOC'] = ($val["muestra_doc"]);
                $contenido_1['ESTRUCORG'] = ($val["estrucorg"]);
                $contenido_1['ARBPROC'] = ($val["arbproc"]);
                $contenido_1['APLI_REG_ESTRORG'] = ($val["apli_reg_estrorg"]);
                $contenido_1['APLI_REG_ARBPROC'] = ($val["apli_reg_arbproc"]);
                $contenido_1['WORKFLOW'] = ($val["workflow"]);
                $contenido_1['SEMAFORO'] = $val["semaforo"];
                $contenido_1['V_MESES'] = $val["v_meses"];
                $contenido_1['REVISO'] = $val["reviso"];
                $contenido_1['ELABORO'] = $val["elaboro"];
                $contenido_1['APROBO'] = $val["aprobo"];



                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("verDocumentos");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Documentos";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
     
 }?>