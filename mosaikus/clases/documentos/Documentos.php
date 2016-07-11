<?php 
        if (!function_exists('array_column')) {
            function array_column($input, $column_key, $index_key = null) {
                $arr = array_map(function($d) use ($column_key, $index_key) {
                    if (!isset($d[$column_key])) {
                        return null;
                    }
                    if ($index_key !== null) {
                        return array($d[$index_key] => $d[$column_key]);
                    }
                    return $d[$column_key];
                }, $input);

                if ($index_key !== null) {
                    $tmp = array();
                    foreach ($arr as $ar) {
                        $tmp[key($ar)] = current($ar);
                    }
                    $arr = $tmp;
                }
                return $arr;
            }
        }
        function archivo($tupla)
        {
            //<img class=\"SinBorde\" src=\"diseno/images/archivoVer.png\">
            $html = "<a target=\"_blank\" title=\"Ver Documento Fuente\" href=\"pages/documentos/descargar_archivo.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";
            $html = '';
            if (strlen($tupla[nom_visualiza])>0){
                //<img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
                $html .= "<a target=\"_blank\"  title=\"Ver Documento PDF\" href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
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
        {   //echo $_SESSION['CookCodEmp'];
            //print_r($tupla);
           //if($_SESSION[CookM] == 'S')          
           if(strpos($tupla[arbol_organizacional],',')){
               if($tupla[cod_elabora]==$_SESSION['CookCodEmp'] || $_SESSION[CookM] == 'S'){
                $html = "<a href=\"#\" onclick=\"javascript:editarDocumentos('". $tupla[IDDoc] . "');\"  title=\"Modificar Documento $tupla[nombre_doc]\">                            
                            <i class=\"icon icon-edit\"></i>
                        </a>";
                }
           }
           else{
              // if(isset($this->id_org_acceso[$tupla[arbol_organizacional]][modificar]))
               print_r($this->id_org_acceso);
//                if (array_key_exists($tupla[arbol_organizacional], $this->id_org_acceso)){
//                    if ($this->id_org_acceso[$tupla[arbol_organizacional]][modificar] == 'S')
//                     {
//                     $html = "<a href=\"#\" onclick=\"javascript:editarDocumentos('". $tupla[IDDoc] . "');\"  title=\"Modificar Documento $tupla[nombre_doc]\">                            
//                                 <i class=\"icon icon-edit\"></i>
//                             </a>";
//                     }
//               }
            }
            //echo 'asas';
            if($_SESSION[CookE] == 'S'){
           // if ($this->id_org_acceso[$tupla[id_organizacion][eliminar]] == 'S'){
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
            if ($_SESSION[CookN] == 'S'){
                //<img title="Crear Revisión '.$tupla[nombre_doc].'" src="diseno/images/ticket_rev.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:verWorkFlow(\''. $tupla[IDDoc] . '\');" title="Ver Flujo de Trabajo '.$tupla[nombre_doc].'" >                        
                            <i class="icon  icon-document"></i>
                    </a>'; 
            }
            //array_push($func,array('nombre'=> 'verWorkFlow','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_nuevo.png' title='Ver Flujo de Trabajo'>"));
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
        private $id_org_acceso;
        private $id_org_acceso_todos_nivel;
        private $nivel_area; // guarda el nivel menor donde el usaurio tiene acceso
        
            public function Documentos(){
                parent::__construct();
                $this->asigna_script('documentos/documentos.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = array();
                $this->contenido = array();
                $this->id_org_acceso = array();
                $this->nivel_area = 2;
            }

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            /**
             * carga el nivel mas bajo donde el usuario tiene acceso
             * @param array $atr
             */
            private function get_min_nivel_area($atr){
                $tercero_aux = $atr[terceros];
                unset($atr[terceros]);
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($atr);                    
                }
                //print_r($atr);
                $sql = "select min(level) nivel from mos_organizacion where id IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                //echo $sql;
                $data = $this->dbl->query($sql);
                $this->nivel_area = $data[0][nivel];
                $atr[terceros] = $tercero_aux;
                $this->id_org_acceso = array();
                $this->cargar_acceso_nodos($atr);
                //echo     $this->nivel_area;
            }
            
            private function cargar_parametros($cod_categoria){
                $sql = "SELECT cod_parametro, espanol, tipo FROM mos_parametro WHERE cod_categoria = '$cod_categoria' AND vigencia = 'S' ORDER BY cod_parametro";
                $this->parametros = $this->dbl->query($sql, array());
            }
            
            public function cargar_nombres_columnas($modulo=6){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = $modulo";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            /**
             * Activa los nodos donde se tiene explicitamente acceso
             */
            private function cargar_acceso_nodos($parametros){
              //  echo $_SESSION[CookIdUsuario];
                //print_r($parametros);
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();                    
                    if ($_SESSION[SuperUser]=='S')
                        unset($parametros[terceros]);                    
                    $data_ids_acceso = $acceso->obtenerNodosArbol($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo],$parametros[terceros]);
                   //print_r($data_ids_acceso);
                    foreach ($data_ids_acceso as $value) {
                        $this->id_org_acceso[$value[id]] = $value;
                    }                                            
                }
            }  
       public function cargar_acceso_nodos_todos_nivel($parametros){
           if (strlen($parametros[cod_link])>0){
               if(!class_exists('mos_acceso')){
                   import("clases.mos_acceso.mos_acceso");
               }
               $acceso = new mos_acceso();
               $data_ids_acceso = $acceso->obtenerArbolEstructura($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo]);
               //print_r($data_ids_acceso);
               foreach ($data_ids_acceso as $value) {
                   $this->id_org_acceso_todos_nivel[$value[id]] = $value;
               }                                            
           }
       }            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 6";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }                
            }
            public function verWFemail($id){
                $atr=array();
                $sql = "select mos_personal.email, 
                    CONCAT(initcap(SUBSTR(mos_personal.nombres,1,IF(LOCATE(' ' ,mos_personal.nombres,1)=0,LENGTH(mos_personal.nombres),LOCATE(' ' ,mos_personal.nombres,1)-1))),' ',initcap(mos_personal.apellido_paterno)) nombres,etapa_workflow, estado_workflow, observacion_rechazo, 
                        IFNULL(recibe_notificaciones,'N') recibe_notificaciones,email_revisa,nombre_revisa,email_reponsable,nombre_responsable,
                        recibe_notificaciones_revisa,recibe_notificaciones_responsable, email_aprueba, nombre_aprueba,recibe_notificaciones_aprueba
                        from mos_personal inner join 
                        (
                                SELECT
                                case 
                                        when mos_documentos.etapa_workflow='estado_pendiente_revision' and mos_documentos.estado_workflow='OK' then mos_documentos.reviso
                                ELSE
                                        case when mos_documentos.etapa_workflow='estado_pendiente_revision' and mos_documentos.estado_workflow='RECHAZADO' then 
                                                mos_documentos.elaboro
                                        else 
                                                case when mos_documentos.etapa_workflow='estado_pendiente_aprobacion' then mos_documentos.aprobo 
                                                else 
                                                        case when mos_documentos.etapa_workflow='estado_aprobado' then mos_documentos.elaboro
                                                                END
                                                end	
                                end
                                end as id_persona,
                                pers.email email_revisa,                                
                                IFNULL(CONCAT(initcap(SUBSTR(pers.nombres,1,IF(LOCATE(' ' ,pers.nombres,1)=0,LENGTH(pers.nombres),LOCATE(' ' ,pers.nombres,1)-1))),' ',initcap(pers.apellido_paterno)),'N/A') nombre_revisa,
                                pers_r.email email_reponsable,
                                CONCAT(initcap(SUBSTR(pers_r.nombres,1,IF(LOCATE(' ' ,pers_r.nombres,1)=0,LENGTH(pers_r.nombres),LOCATE(' ' ,pers_r.nombres,1)-1))),' ',initcap(pers_r.apellido_paterno)) nombre_responsable,
                                IFNULL(usu_rev.recibe_notificaciones,'N') recibe_notificaciones_revisa,
                                IFNULL(usu_resp.recibe_notificaciones,'N') recibe_notificaciones_responsable,
                                pers_apr.email email_aprueba,
                                CONCAT(initcap(SUBSTR(pers_apr.nombres,1,IF(LOCATE(' ' ,pers_apr.nombres,1)=0,LENGTH(pers_apr.nombres),LOCATE(' ' ,pers_apr.nombres,1)-1))),' ',initcap(pers_apr.apellido_paterno)) nombre_aprueba,
                                IFNULL(usu_apr.recibe_notificaciones,'N') recibe_notificaciones_aprueba,
                                mos_documentos.IDDoc,
                                mos_documentos.etapa_workflow,
                                mos_documentos.estado_workflow,
                                mos_documentos.observacion_rechazo
                                FROM
                                mos_documentos left join mos_personal pers
                                on mos_documentos.reviso =  pers.cod_emp left join mos_personal pers_r
                                on mos_documentos.elaboro =  pers_r.cod_emp inner join mos_usuario usu_resp
                                on pers_r.email = usu_resp.email left join mos_usuario usu_rev
                                on pers.email = usu_rev.email left join mos_personal pers_apr
                                on mos_documentos.aprobo =  pers_apr.cod_emp inner join mos_usuario usu_apr
                                on pers_apr.email = usu_apr.email
                                where IDDoc=$id
                        ) as wf
                        on mos_personal.cod_emp= id_persona inner join mos_usuario usu
                        on mos_personal.email = usu.email 
                        ;"; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
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
             public function verVisualizaDocumentosRelacionados($id){
                $atr=array();
                $sql = "SELECT
                        mos_documentos_relacionados.IDDoc_relacionado AS id,
                        CONCAT(mos_documentos.Codigo_doc,'-',mos_documentos.nombre_doc) AS nombre
                        FROM
                        mos_documentos_relacionados
                        INNER JOIN mos_documentos ON mos_documentos_relacionados.IDDoc_relacionado = mos_documentos.IDDoc
                        WHERE
                        mos_documentos_relacionados.IDDoc = $id order by 2"; 
                //echo $sql;
                $this->operacion($sql, $atr);
                $i = 1;
                foreach($this->dbl->data as $value){
                    $html .= '<tr id="tr-esp-' .$i. '">'; 
                    $html.= '<td >'.$value[nombre].'</td>';
                    $html.= '<td align="center">';
                    $html .= "<a target=\"_blank\"  title=\"Ver Documento PDF\" href=\"pages/documentos/descargar_archivo_pdf.php?id=$value[id]&token=" . md5($value[id]) ."&des=1\">
                            <i class=\"icon icon-view-document\"></i>
                        </a>";                      
                    $html.= '</td>';                    
                    $html .= '<tr>'; 
                    $i ++;
                }
                if($this->dbl->data){
                    $html = '<table id="table-items-esp-vis" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">                                                        
                            <tbody>
                                ' . $html . '
                            </tbody>
                        </table>';                
                }
                return $html;
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
                                ,d.publico
                                ,d.id_workflow_documento
                                ,d.estado_workflow
                                ,d.etapa_workflow
                                ,wf.email_responsable
                                ,wf.email_revisa
                                ,wf.email_aprueba
                                ,dao.id_organizacion
                                ,d.actualizacion_activa
                                ,requiere_lista_distribucion
                         FROM mos_documentos  d
                                left join mos_personal p on d.elaboro=p.cod_emp
                                left join mos_personal re on d.reviso=re.cod_emp
                                left join mos_personal ap on d.aprobo=ap.cod_emp
                                left join mos_workflow_documentos wf on d.id_workflow_documento = wf.id
                                left JOIN (select IDDoc id , GROUP_CONCAT(id_organizacion_proceso) id_organizacion from mos_documentos_estrorg_arbolproc GROUP BY IDDoc) AS dao ON  d.IDDoc = dao.id
                         WHERE IDDoc = $id "; 
                $this->operacion($sql, $atr);
                //echo $sql;
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
            $html = "<a target=\"_blank\" title=\"Ver Documento Fuente\" href=\"pages/documentos/descargar_archivo.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";
            $html = '';
            if (strlen($tupla[nom_visualiza])>0){
                //<img class=\"SinBorde\" title=\"Ver Documento PDF\" src=\"diseno/images/pdf.png\">
                $html .= "<a target=\"_blank\"  title=\"Ver Documento PDF\" href=\"pages/documentos/descargar_archivo_pdf.php?id=$tupla[IDDoc]&token=" . md5($tupla[IDDoc]) ."&des=1\">
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
                        $Nivls .= $this->BuscaOrganizacional(array('id_organizacion' => $Fila3[id_organizacion_proceso]))."<br /><br />";
                    }
                    if($Nivls!='')
                            $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                    else
                            $Nivls='-- Sin información --';
            }
            
            return $Nivls;

        }
        
        function BuscaOrganizacional($tupla)
        {
                $OrgNom = "";
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion, level from mos_organizacion where id in ($tupla[id_organizacion])";
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if(($Fila3[organizacion_padre]==2)||($Fila3[organizacion_padre]==1)||($Fila3[level] == $this->nivel_area))
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= $this->BuscaOrganizacional(array('id_organizacion' => $Fila3[organizacion_padre])) . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= $_SESSION[CookNomEmpresa];
                return $OrgNom;

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
                        $Nivls .= $this->BuscaOrganizacional(array('id_organizacion' => $Fila3[id_organizacion_proceso]))."<br /><br />";
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
        
        function BuscaRegistrosAdministrador($tupla)
        {
            If ($tupla[formulario] == 'N')
                return 'No Aplica';
            
            $encryt = new EnDecryptText();
            $dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select count(*) cant from mos_registro where vigencia='S' and IDDoc='".$tupla[IDDoc]."'";                    
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
        
        public function BuscaRegistros($tupla)
        {
            If ($tupla[formulario] == 'N')
                return 'No Aplica';
            
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $Nivls = "";
            {                                           
                    //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                    $Consulta3="select count(*) cant from mos_registro where vigencia='S' and IDDoc='".$tupla[IDDoc]."'";                    
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
        public function colum_admin($tupla)
        {   //echo $_SESSION[CookM];
            //print_r($tupla);
           //if($_SESSION[CookM] == 'S')            
           //**********EDITAR **********
           if($_SESSION[SuperUser] == 'S'){
                $editar = false;                        
                $organizacion = array();
                if(strpos($tupla[arbol_organizacional],',')){    
                    $organizacion = explode(",", $tupla[arbol_organizacional]);
                }
                else{
                    $organizacion[] = $tupla[arbol_organizacional];                                 
                }
                /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                foreach ($organizacion as $value_2) {
                    if (isset($this->id_org_acceso[$value_2])){
                        //if(($this->id_org_acceso[$value_2][modificar]=='S'))
                            $editar = true;
                    } else{
                        $editar = false;
                        break;
                    }
                }
                if (($editar == true))
                    $html = "<a href=\"#\" onclick=\"javascript:editarDocumentos('". $tupla[IDDoc] . "');\"  title=\"Modificar Documento $tupla[nombre_doc]\">                            
                            <i class=\"icon icon-edit\"></i>
                        </a>";
                } 
           else{               
                    if(($tupla[cod_elabora]==$_SESSION['CookCodEmp'])){
                        $editar = false;                        
                        $organizacion = array();
                        if(strpos($tupla[arbol_organizacional],',')){    
                            $organizacion = explode(",", $tupla[arbol_organizacional]);
                        }
                        else{
                            $organizacion[] = $tupla[arbol_organizacional];                                 
                        }
                        /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                        foreach ($organizacion as $value_2) {
                            if (isset($this->id_org_acceso[$value_2])){
                                if(($this->id_org_acceso[$value_2][modificar]=='S'))
                                    $editar = true;
                            } else{
                                $editar = false;
                                break;
                            }
                        }
                        if (($editar == true))                                                       
                            $html = "<a href=\"#\" onclick=\"javascript:editarDocumentos('". $tupla[IDDoc] . "');\"  title=\"Modificar Documento $tupla[nombre_doc]\">                            
                                <i class=\"icon icon-edit\"></i>
                            </a>";
                     }
                
                else{
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($tupla[arbol_organizacional],',')){    
                        $organizacion = explode(",", $tupla[arbol_organizacional]);
                    }
                    else{
                        $organizacion[] = $tupla[arbol_organizacional];                                 
                    }
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if (isset($this->id_org_acceso[$value_2])){
                            if(($this->id_org_acceso[$value_2][modificar_terceros]=='S'))
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                    if (($editar == true))                                                       
                        $html = "<a href=\"#\" onclick=\"javascript:editarDocumentos('". $tupla[IDDoc] . "');\"  title=\"Modificar Documento $tupla[nombre_doc]\">                            
                            <i class=\"icon icon-edit\"></i>
                        </a>";
                     
                }
            }
           //********** ELIMINAR **********
           if($_SESSION[SuperUser] == 'S'){
                $editar = false;                        
                $organizacion = array();
                if(strpos($tupla[arbol_organizacional],',')){    
                    $organizacion = explode(",", $tupla[arbol_organizacional]);
                }
                else{
                    $organizacion[] = $tupla[arbol_organizacional];                                 
                }
                /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                foreach ($organizacion as $value_2) {
                    if (isset($this->id_org_acceso[$value_2])){
                        //if(($this->id_org_acceso[$value_2][eliminar]=='S'))
                            $editar = true;
                    } else{
                        $editar = false;
                        break;
                    }
                }
                if (($editar == true))
                    $html .= '<a href="#" onclick="javascript:eliminarDocumentos(\''. $tupla[IDDoc] . '\');" title="Eliminar '.$tupla[nombre_doc].'">
                        <i class="icon icon-remove"></i>                        
                    </a>'; 
                } 
           else{
                if(strpos($tupla[arbol_organizacional],',')){
                    if($tupla[cod_elabora]==$_SESSION['CookCodEmp']){
                        
                        $editar = false;                        
                        $organizacion = array();
                        if(strpos($tupla[arbol_organizacional],',')){    
                            $organizacion = explode(",", $tupla[arbol_organizacional]);
                        }
                        else{
                            $organizacion[] = $tupla[arbol_organizacional];                                 
                        }
                        /*SE VALIDA QUE PUEDE ELIMINAR EN TODAS LAS AREAS*/
                        foreach ($organizacion as $value_2) {
                            if (isset($this->id_org_acceso[$value_2])){
                                if(($this->id_org_acceso[$value_2][eliminar]=='S'))
                                    $editar = true;
                            } else{
                                $editar = false;
                                break;
                            }
                        }
                        if (($editar == true)) 
                            $html .= '<a href="#" onclick="javascript:eliminarDocumentos(\''. $tupla[IDDoc] . '\');" title="Eliminar '.$tupla[nombre_doc].'">
                            <i class="icon icon-remove"></i>                        
                            </a>';
                     }
                }                
            }
            
            //if ($_SESSION[CookN] == 'S')
            {
                //<img title="Crear Versión '.$tupla[nombre_doc].'" src="diseno/images/ticket_ver.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:crearVersionDocumentos(\''. $tupla[IDDoc] . '\');" title="Crear Versión '.$tupla[nombre_doc].'">                        
                            <i class="icon icon-v"></i>
                    </a>'; 
            }
            //if ($_SESSION[CookN] == 'S')
            {
                //<img title="Crear Revisión '.$tupla[nombre_doc].'" src="diseno/images/ticket_rev.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:crearRevisionDocumentos(\''. $tupla[IDDoc] . '\');" title="Crear Revisión '.$tupla[nombre_doc].'" >                        
                            <i class="icon icon-r"></i>
                    </a>'; 
            }            
            if($tupla[cod_elabora]==$_SESSION['CookCodEmp'] ||$tupla[cod_revisa]==$_SESSION['CookCodEmp'] || $tupla[cod_aprueba]==$_SESSION['CookCodEmp']){
                //<img title="Crear Revisión '.$tupla[nombre_doc].'" src="diseno/images/ticket_rev.png" style="cursor:pointer">
                $html .= '<a href="#" onclick="javascript:verWorkFlow(\''. $tupla[IDDoc] . '\');" title="Ver Flujo de Trabajo '.$tupla[nombre_doc].'" >                        
                            <i class="icon icon-user-document"></i>
                    </a>'; 
            }
            //array_push($func,array('nombre'=> 'verWorkFlow','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_nuevo.png' title='Ver Flujo de Trabajo'>"));
            return $html;
            
        }
        
                        
        
        
        public function codigo_doc($tupla)
        {                        
            return $tupla[Codigo_doc] . ' ';            
        }
        public function version($tupla)
        {                        
            return str_pad($tupla[version], 2, '0', STR_PAD_LEFT) . ' ';
            
        }
            
             public function ResponsablesAreasOrganizacion($id_organizacion){
                $atr=array();
                $sql = "SELECT 
                        CONCAT(initcap(SUBSTR(pers.nombres,1,IF(LOCATE(' ' ,pers.nombres,1)=0,LENGTH(pers.nombres),LOCATE(' ' ,pers.nombres,1)-1))),' ',initcap(pers.apellido_paterno)) AS nombres,
                        pers.email correo,
                        GROUP_CONCAT(resp.id_organizacion) id_organizacion,
                        mos_usuario.recibe_notificaciones,
                        pers.cod_emp
                        FROM
                        mos_personal AS pers
                        INNER JOIN mos_responsable_area AS resp ON pers.cod_emp = resp.cod_emp left join
                        mos_usuario on pers.email = mos_usuario.email
                        WHERE
                        resp.id_organizacion IN ($id_organizacion)
                        group by mos_usuario.recibe_notificaciones,
                        CONCAT(initcap(SUBSTR(pers.nombres,1,IF(LOCATE(' ' ,pers.nombres,1)=0,LENGTH(pers.nombres),LOCATE(' ' ,pers.nombres,1)-1))),' ',initcap(pers.apellido_paterno)),
                        pers.email,
                        pers.cod_emp
                        "; 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
             public function CargosPersonalDocumentos($id, $id_organizacion){
                $atr=array();
                $sql = "SELECT
                        cargo.descripcion cargo,
                        GROUP_CONCAT(CONCAT(initcap(SUBSTR(mos_personal.nombres,1,IF(LOCATE(' ' ,mos_personal.nombres,1)=0,LENGTH(mos_personal.nombres),LOCATE(' ' ,mos_personal.nombres,1)-1))),' ',initcap(mos_personal.apellido_paterno))) AS nombres
                        FROM
                        mos_documentos_cargos AS doc_cargo
                        INNER JOIN mos_documentos_estrorg_arbolproc AS doc_org ON doc_org.IDDoc = doc_cargo.IDDoc
                        INNER JOIN mos_cargo AS cargo ON doc_cargo.cod_cargo = cargo.cod_cargo
                        INNER JOIN mos_personal  ON mos_personal.id_organizacion = doc_org.id_organizacion_proceso AND mos_personal.cod_cargo = doc_cargo.cod_cargo
                        where doc_org.IDDoc=$id and mos_personal.id_organizacion in ($id_organizacion)
                        group by cargo.descripcion     
                        order by 1,2"; 
               // echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data;
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
                    $sql = "SELECT MAX(id_unico) total_registros
                         FROM mos_documentos_datos_formulario";
                    $total_registros = $this->dbl->query($sql, $atr);
                    $num_viaje = $total_registros[0][total_registros];                
                    return $num_viaje;     
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function actualizarCamposFormulario($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);                    
                    $sql = "UPDATE mos_documentos_datos_formulario "
                            . " SET orden = $atr[orden], Nombre = '$atr[nombre]', valores = '$atr[valores]'
                            WHERE id_unico = $atr[id_unico]";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    return "El Cargo '$atr[cod_cargo]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una secciÃ³n con el mismo nombre.";                        
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
                                $atr[cod_categoria],$atr[cod_parametro],$atr[cod_parametro_det],$atr[id_registro],$atr[cod_categoria]
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
                //print_r($atr);
                try {
                    
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[IDDoc] = $this->codigo_siguiente();
                /*VALIDAR CODIGO SUGERIDO*/
                   import('clases.documento_codigos.DocumentoCodigos');
                   $pagina = new DocumentoCodigos();
                   $val_codigo_doc = $pagina->verDocumentoCodigosArea($atr['nodo_area']);
                   if (count($val_codigo_doc) > 0){
                       //$val = $this->verDocumentoCodigosArea($data[0][id]);
                       //print_r($val);
                       if ($val_codigo_doc[bloqueo_codigo] == 'S'){
                           $atr[Codigo_doc] = $val_codigo_doc["codigo"] . '_' . str_pad($val_codigo_doc["correlativo"], 3, "0", STR_PAD_LEFT);
                       }
                       if ($val_codigo_doc[bloqueo_version] == 'S'){
                           $atr[version] = 1; 
                       }
                   
                   }
                   /*FIN CODIGO SUGERIDO*/                    
                    $sql = "SELECT COUNT(*) total_registros
                                        FROM mos_documentos 
                                        WHERE Codigo_doc = '$atr[Codigo_doc]'";                    
                    $total_registros = $this->dbl->query($sql, $atr);
                    $total = $total_registros[0][total_registros];  
                    if ($total > 0){
                        return "- Ya existe una documento registrado con el mismo código";
                    }
                    /*Carga Acceso segun el arbol*/
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);
                    }
                    //***********************************
                    //para validar que los nodos seleccionados
                    //tenga permisos
                    $organizacion = array();
                    if(strpos($atr[nodos],',')){    
                        $organizacion = explode(",", $atr[nodos]);
                    }
                    else{
                        $organizacion[] = $atr[nodos];                    
                    }
                    //print_r($organizacion);
                    $areas='';
                    foreach ($organizacion as $value) {
                        if (isset($this->id_org_acceso[$value])){
                            if(!($this->id_org_acceso[$value][nuevo]=='S' || $this->id_org_acceso[$value][modificar]=='S'))
                                $areas .= $this->id_org_acceso[$value][title].',';
                        } else{
                            $areas='break';
                            break;
                        }
                    }
                    /*Valida Restriccion*/
                    if ($areas=='break')
                        return '- Acceso denegado para registrar Documentos en el &aacute;rea seleccionada.';
                    if ($areas!='break' && $areas!='' )
                        return '- Acceso denegado para registrar Documentos en el &aacute;rea ' . $areas . '.';                    
                     //***********************************
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
                    if($atr[notificar]=='si'){
                        if($atr[reviso]=='NULL'){
                            $atr[etapa_workflow]="'estado_pendiente_aprobacion'";
                        }
                        else{
                            $atr[etapa_workflow]="'estado_pendiente_revision'";
                        }
                        $atr[estado_workflow]="'OK'";
                        $atr[id_usuario_workflow]=$atr[id_usuario];                            
                    }else
                    {   $atr[etapa_workflow]='NULL';
                        $atr[estado_workflow]='NULL';
                        $atr[id_usuario_workflow]=$atr[id_usuario];
                    }   
                    //
                    $sql = "INSERT INTO mos_documentos(IDDoc,Codigo_doc,nombre_doc,version,fecha,descripcion,palabras_claves,formulario,vigencia,doc_fisico,contentType,id_filial,nom_visualiza,doc_visualiza,contentType_visualiza,id_usuario,observacion,estrucorg,arbproc,apli_reg_estrorg,apli_reg_arbproc,workflow,semaforo,v_meses,reviso,elaboro,aprobo,publico, id_workflow_documento,etapa_workflow,estado_workflow,id_usuario_workflow, actualizacion_activa, requiere_lista_distribucion)                            
                            VALUES(
                                $atr[IDDoc],'$atr[Codigo_doc]','$atr[nombre_doc]',$atr[version],'$atr[fecha]','$atr[descripcion]','$atr[palabras_claves]','$atr[formulario]','$atr[vigencia]','$atr[doc_fisico]','$atr[contentType]',$atr[id_filial],'$atr[nom_visualiza]','$atr[doc_visualiza]','$atr[contentType_visualiza]',$atr[id_usuario],'$atr[observacion]','$atr[estrucorg]','$atr[arbproc]','$atr[apli_reg_estrorg]','$atr[apli_reg_arbproc]','$atr[workflow]',$atr[semaforo],$atr[v_meses],$atr[reviso],$atr[elaboro],$atr[aprobo]
                                    ,'$atr[publico]',$atr[id_workflow_documento],$atr[etapa_workflow],$atr[estado_workflow],$atr[id_usuario_workflow] ,'$atr[actualizacion_activa]','$atr[requiere_lista_distribucion]'
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos ' . $atr[descripcion_ano], 'mos_documentos');
                      */
                    $atr[id]=$atr[IDDoc];
                    $this->CargaCargosDocumento($atr);
                    $this->CargaDocumentosRelacionados($atr);
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Codigo Doc: \'$atr[Codigo_doc]\', Nombre Doc: \'$atr[nombre_doc]\', Version: \'$atr[version]\', Fecha: \'$atr[fecha]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', ContentType: \'$atr[contentType]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza]\', ContentType Visualiza: \'$atr[contentType_visualiza]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', Publico: \'$atr[publico]\'";
                    $this->registraTransaccionLog(1,$nuevo,'', $atr[IDDoc]);
                   /*AUMENTAR CORRELATIVO SUGERIDO*/
                   $pagina->aumentarCorrelativo($val_codigo_doc);                    
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
                    if (strlen($atr[reviso])== 0){
                        $atr[reviso] = "NULL";                     
                    }                    
                    if (strlen($atr[aprobo])== 0){
                        $atr[aprobo] = "NULL";                     
                    }  
                    if($atr[notificar]=='si'){
                        if($atr[reviso]=='NULL'){
                            $atr[etapa_workflow]="'estado_pendiente_aprobacion'";
                        }
                        else{
                            $atr[etapa_workflow]="'estado_pendiente_revision'";
                        }
                        $atr[estado_workflow]="'OK'";
                        $atr[id_usuario_workflow]=$atr[id_usuario];
                        }  
                    else {
                        $atr[etapa_workflow]='NULL';
                        $atr[estado_workflow]='NULL';
                        $atr[id_usuario_workflow]=$atr[id_usuario];
                    }
                    $sql = "INSERT INTO mos_documentos(IDDoc,Codigo_doc,nombre_doc,version,fecha,descripcion,palabras_claves,formulario,vigencia,doc_fisico,contentType,id_filial,nom_visualiza,doc_visualiza,contentType_visualiza,id_usuario,observacion,estrucorg,arbproc,apli_reg_estrorg,apli_reg_arbproc,workflow,semaforo,v_meses,reviso,elaboro,aprobo,publico, id_workflow_documento,etapa_workflow,estado_workflow,id_usuario_workflow)
                            SELECT $atr[IDDoc],Codigo_doc,nombre_doc,version+1,'$atr[fecha]',descripcion,palabras_claves,formulario,vigencia,doc_fisico,contentType,id_filial,nom_visualiza,doc_visualiza,contentType_visualiza,id_usuario,'$atr[observacion]',estrucorg,arbproc,apli_reg_estrorg,apli_reg_arbproc,workflow,semaforo,v_meses,$atr[reviso],$atr[elaboro],$atr[aprobo],publico,
                                    $atr[id_workflow_documento],$atr[etapa_workflow],$atr[estado_workflow],$atr[id_usuario_workflow]
                            FROM mos_documentos
                            WHERE IDDoc = $atr[id];
                                
                                ";
                   // echo $sql;
                    //die;
                    //echo $sql; $atr[IDDoc],'$atr[Codigo_doc]','$atr[nombre_doc]',$atr[version],'$atr[fecha]','$atr[descripcion]','$atr[palabras_claves]','$atr[formulario]','$atr[vigencia]','$atr[doc_fisico]','$atr[contentType]',$atr[id_filial],'$atr[nom_visualiza]','$atr[doc_visualiza]','$atr[contentType_visualiza]',$atr[id_usuario],'$atr[observacion]','$atr[estrucorg]','$atr[arbproc]','$atr[apli_reg_estrorg]','$atr[apli_reg_arbproc]','$atr[workflow]',$atr[semaforo],$atr[v_meses],$atr[reviso],$atr[elaboro],$atr[aprobo]
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_documentos SET doc_fisico='$atr[doc_fisico]',contentType='$atr[contentType]', nom_visualiza='$atr[nom_visualiza]',doc_visualiza='$atr[doc_visualiza]',contentType_visualiza='$atr[contentType_visualiza]' "
                            . "WHERE IDDoc = $atr[IDDoc]  ";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    $sql = "UPDATE mos_documentos SET muestra_doc='N' "
                            . "WHERE IDDoc = $atr[id]  ";
                    $this->dbl->insert_update($sql);
                    //actualizar documentos relacionados con esta nueva version
                     $sql = "update mos_documentos_relacionados
                            set IDDoc_relacionado=$atr[IDDoc]
                            WHERE IDDoc_relacionado = $atr[id]  ";
                    $this->dbl->insert_update($sql);  
                     $sql = "update mos_documentos_relacionados
                            set IDDoc=$atr[IDDoc]
                            WHERE IDDoc = $atr[id]  ";
                    $this->dbl->insert_update($sql);  
                    
                    $sql = "Insert Into mos_documentos_estrorg_arbolproc (IDDoc, id_organizacion_proceso, tipo, aplica_subnivel)
						Select ".$atr[IDDoc].",  id_organizacion_proceso, tipo, aplica_subnivel From mos_documentos_estrorg_arbolproc
							Where IDDoc = $atr[id]";
                    $this->dbl->insert_update($sql);
                    $sql = "INSERT INTO mos_parametro_modulos(cod_categoria,cod_parametro,cod_parametro_det,id_registro,cod_categoria_aux)
                            SELECT cod_categoria,cod_parametro,cod_parametro_det,$atr[IDDoc],cod_categoria_aux
                            FROM mos_parametro_modulos
                            WHERE id_registro = $atr[id] AND cod_categoria = $atr[cod_categoria] AND cod_categoria_aux = $atr[cod_categoria]";
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
                    $sql = "UPDATE mos_registro_item SET IDDoc = $atr[IDDoc]"
                            . " WHERE IDDoc = $atr[id] ";
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos ' . $atr[descripcion_ano], 'mos_documentos');
                      */
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Codigo Doc: \'$atr[Codigo_doc]\', Nombre Doc: \'$atr[nombre_doc]\', Version: \'$atr[version]\', Fecha: \'$atr[fecha]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', ContentType: \'$atr[contentType]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza]\', ContentType Visualiza: \'$atr[contentType_visualiza]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', ";
                    $this->registraTransaccionLog(4,$nuevo,'', $atr[id]);
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
                    $this->registraTransaccionLog(5,$nuevo,'', $atr[id]);
                    return "La revision ha sido ingresada con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            public function registraCorreoTareaProgramada($id_entidad,$modulo, $asunto, $cuerpo, $email, $nombre){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_correos_temporales (id_entidad, modulo, asunto, cuerpo, email, nombre) "
                        . "VALUES ($id_entidad,'$modulo', '$asunto', '$cuerpo', '$email', '$nombre')";            
                $this->dbl->insert_update($sql);
                return true;
            }
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = 'NULL'){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip, id_registro) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]',$id)";            
                $this->dbl->insert_update($sql);

                return true;
            }
            public function CargaCargosDocumento($atr) {
            //PARA GUARDAR LOS CARGOS ASOCIADOS A LOS NODOS DEL ARBOL
                    //$atr[id] 
                    $sql = "delete from mos_documentos_cargos  where IDDoc=".$atr[id];    
                    $this->dbl->insert_update($sql);
                    //echo $sql;
                    $cargos = array();
                    //print_r($atr);
                    //print_r($cargos);
                    if($atr[cod_cargo]){
                        foreach ($atr[cod_cargo] as $value) {
                            $sql = "insert into mos_documentos_cargos (IDDoc,cod_cargo) "
                                    . " values (".$atr[id].",".$value."); ";
                            //echo $sql;
                            $this->dbl->insert_update($sql);
                        }
                    }
                    //FIN GUARDAR LAS AREAS DONDE ES RESPONSABLE                
            }
            public function CargaDocumentosRelacionados($atr) {
            //PARA GUARDAR LOS CARGOS ASOCIADOS A LOS NODOS DEL ARBOL
                    //$atr[id] 
                    $sql = "delete from mos_documentos_relacionados  where IDDoc=".$atr[id];    
                    $this->dbl->insert_update($sql);
                    //echo $sql;
                    $cargos = array();
                    //print_r($atr);
                    //print_r($cargos);
                    if($atr[documento_relacionado]){
                        foreach ($atr[documento_relacionado] as $value) {
                            $sql = "insert into mos_documentos_relacionados (IDDoc,IDDoc_relacionado) "
                                    . " values (".$atr[id].",".$value."); ";
                           // echo $sql;
                            $this->dbl->insert_update($sql);
                        }
                    }
                    //FIN GUARDAR LAS AREAS DONDE ES RESPONSABLE                
            }            
            public function modificarDocumentos($atr,$archivo,$doc_ver){
                //print_r($atr);
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[doc_fisico] = $archivo;
                    $atr[doc_visualiza] = $doc_ver;    
                    $sql_doc_fisico ='';
                    if (strlen($atr[doc_fisico])> 0){
                        $sql_doc_fisico .= ",doc_fisico = '$atr[doc_fisico]', contentType = '$atr[contentType]'";
                                         
                    }                    
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
                    /*Carga Acceso segun el arbol*/
                   // print_r($atr);
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);
                    }
                    //***********************************
                    //para validar que los nodos seleccionados
                    //tenga permisos
                    $organizacion = array();
                    if(strpos($atr[nodos],',')){    
                        $organizacion = explode(",", $atr[nodos]);
                    }
                    else{
                        $organizacion[] = $atr[nodos];                    
                    }
                    //print_r($organizacion);
                    $areas='';
                    foreach ($organizacion as $value) {
                        if (isset($this->id_org_acceso[$value])){
                            if(!($this->id_org_acceso[$value][nuevo]=='S' || $this->id_org_acceso[$value][modificar]=='S'))
                                $areas .= $this->id_org_acceso[$value][title].',';
                        } else{
                            $areas='break';
                            break;
                        }
                    }
                    /*Valida Restriccion*/
                    if ($areas=='break')
                        return '- Acceso denegado para registrar Documentos en el &aacute;rea seleccionada.';
                    if ($areas!='break' && $areas!='' )
                        return '- Acceso denegado para registrar Documentos en el &aacute;rea ' . $areas . '.';                    
                     //***********************************
                    
                    if (strlen($atr[reviso])== 0){
                        $atr[reviso] = "NULL";                     
                    }                    
                    if (strlen($atr[aprobo])== 0){
                        $atr[aprobo] = "NULL";                     
                    }
                    if (strlen($atr[elaboro])== 0){
                        $atr[elaboro] = "elaboro";                     
                    }
                    $val = $this->verDocumentos($atr[id]);
                    //echo $val[etapa_workflow];
                    if(!($val[etapa_workflow]=='estado_aprobado' && $val[estado_workflow]=='OK')){
                        if($atr[notificar]=='si'){                            
                            if($atr[reviso]=='NULL'){                                
                                $atr[etapa_workflow]="'estado_pendiente_aprobacion'";
                            }
                            else{                                
                                $atr[etapa_workflow]="'estado_pendiente_revision'";
                            }
                            $sql_wf = ", id_workflow_documento=$atr[id_workflow_documento],
                                        id_usuario_workflow=$atr[id_usuario],
                                       etapa_workflow=$atr[etapa_workflow],
                                       observacion_rechazo=null,
                                       estado_workflow='OK'";
                        }else
                        {   
//                            $sql_wf = ", id_workflow_documento=$atr[id_workflow_documento],
//                                        id_usuario_workflow=$atr[id_usuario],
//                                       etapa_workflow=NULL,
//                                       observacion_rechazo=null,
//                                       estado_workflow=NULL";
                            $sql_wf = ", id_workflow_documento=$atr[id_workflow_documento]";
                        }                      
                    }
                    //echo $sql_wf;
                    //die;
                    $sql = "UPDATE mos_documentos SET                            
                                    descripcion = '$atr[descripcion]',palabras_claves = '$atr[palabras_claves]',formulario = '$atr[formulario]',vigencia = '$atr[vigencia]'"
                            . ",nom_visualiza = $atr[nom_visualiza],doc_visualiza = $atr[doc_visualiza],contentType_visualiza = $atr[contentType_visualiza],id_usuario = $atr[id_usuario],observacion = '$atr[observacion]',estrucorg = '$atr[estrucorg]',arbproc = '$atr[arbproc]'"
                            . ",apli_reg_estrorg = '$atr[apli_reg_estrorg]',apli_reg_arbproc = '$atr[apli_reg_arbproc]',workflow = '$atr[workflow]',semaforo = $atr[semaforo],v_meses = $atr[v_meses],reviso = $atr[reviso],elaboro = $atr[elaboro],aprobo = $atr[aprobo]
                               ,requiere_lista_distribucion = '$atr[requiere_lista_distribucion]', publico = '$atr[publico]',actualizacion_activa= '$atr[actualizacion_activa]' $sql_wf $sql_doc_fisico
                            WHERE  IDDoc = $atr[id]";      
                   //echo $sql;
                   // die;
                    $val = $this->verDocumentos($atr[id]);
                    $this->CargaCargosDocumento($atr);
                    $this->CargaDocumentosRelacionados($atr);
                    $this->dbl->insert_update($sql);
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza_aux]\',ContentType Visualiza: \'$atr[contentType_visualiza_aux]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', Publico: \'$atr[publico]\'";
                    $anterior = "IDDoc: \'$val[IDDoc]\', Codigo Doc: \'$val[Codigo_doc]\', Nombre Doc: \'$val[nombre_doc]\', Version: \'$val[version]\', Fecha: \'$val[fecha]\', Descripcion: \'$val[descripcion]\', Palabras Claves: \'$val[palabras_claves]\', Formulario: \'$val[formulario]\', Vigencia: \'$val[vigencia]\', ContentType: \'$val[contentType]\', Id Filial: \'$val[id_filial]\', Nom Visualiza: \'$val[nom_visualiza]\', ContentType Visualiza: \'$val[contentType_visualiza]\', Id Usuario: \'$val[id_usuario]\', Observacion: \'$val[observacion]\', Muestra Doc: \'$val[muestra_doc]\', Estrucorg: \'$val[estrucorg]\', Arbproc: \'$val[arbproc]\', Apli Reg Estrorg: \'$val[apli_reg_estrorg]\', Apli Reg Arbproc: \'$val[apli_reg_arbproc]\', Workflow: \'$val[workflow]\', Semaforo: \'$val[semaforo]\', V Meses: \'$val[v_meses]\', Reviso: \'$val[reviso]\', Elaboro: \'$val[elaboro]\', Aprobo: \'$val[aprobo]\', Publico: \'$val[publico]\' ";
                    $this->registraTransaccionLog(2,$nuevo,$anterior, $atr[id]);
                    /*
                     * 
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
              public function listarDocumentosComboRelacion($atr){
                    //print_r($atr);
                  $ut_tool = new ut_Tool();
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);                    
                    }
                    if (count($this->id_org_acceso_todos_nivel) <= 0){
                        $this->cargar_acceso_nodos_todos_nivel($atr);                    
                    }
                  if($atr[operacion]=='crear'){  
                    $sql = "SELECT
                        d.IDDoc id,
                        CONCAT(d.Codigo_doc,'-',
                        d.nombre_doc) descripcion
                        FROM
                        mos_documentos d left join mos_personal p on d.elaboro=p.cod_emp 
                        WHERE
                        d.etapa_workflow = 'estado_aprobado' AND
                        d.muestra_doc = 'S' ";
                  }else {                  
                      $sql = "SELECT
                        d.IDDoc id,
                        CONCAT(d.Codigo_doc,'-',
                        d.nombre_doc) descripcion,
                        rel.IDDoc_relacionado valor
                        FROM
                        mos_documentos d left join mos_personal p on d.elaboro=p.cod_emp left join
                        (select IDDoc,IDDoc_relacionado
                        from mos_documentos_relacionados 
                        where IDDoc=".$atr[id].") rel on d.IDDoc = rel.IDDoc_relacionado 
                        WHERE
                        d.etapa_workflow = 'estado_aprobado' AND
                        d.muestra_doc = 'S' and
                        d.IDDoc <>$atr[id]";
                        }
                    if($atr[formulario]=='S')
                        $sql .= " and formulario = 'S'";
                    else
                        $sql .= " and formulario = 'N' ";
                    if(($_SESSION[SuperUser]!='S')){
                        $sql .= " and ((p.email='".$atr["email_usuario"]."') ";
                            if (count($this->id_org_acceso))
                                $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo) )"; 
                            if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                        $sql .= ")";
                    }
                            if (($_SESSION[SuperUser]=='S')){
                                    $sql .= " and ((p.email='".$atr["email_usuario"]."') ";

                                    if (count($this->id_org_acceso))
                                        $sql .= " OR ( d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo) )"; 
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                                    $sql .= ")";
//                                }
                            }                        
                    $sql .= " ORDER BY 2 ASC " ;                
                    //echo $sql;
                    $combosemp = $ut_tool->OptionsComboMultiple($sql, 'id', 'descripcion','valor');      
                    return ($combosemp);
              }
             public function listarDocumentos($atr, $pag, $registros_x_pagina){
                 //print_r($atr);
                 // HABILIYAR LA COLUMNA ESYAD0
                    //print_r($atr);
                    if(!class_exists('Parametros')){
                        import("clases.parametros.Parametros");
                    }                 
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    if($atr[formulario]=='S') {
                        $cod_categoria = 15;
                        $campo_formulario=',formulario ';
                    }
                    else{
                        $cod_categoria = 1;
                        $campo_formulario=',formulario ';
                    }                       
                    if (count($this->parametros) <= 0){
                        $this->cargar_parametros($cod_categoria);
                    }                    
                    $k = 1;

                    $campos_dinamicos = new Parametros();
                    $valores = $campos_dinamicos->ArmaSqlParamsDinamicos($cod_categoria,$k,$this->parametros,'d.IDDoc');
                    $sql_left = $valores[sql_left];
                    $sql_col_left = $valores[sql_col_left];
                    $k = $valores[k];
                    //$k = 1;
                    //$sql_left = $sql_col_left = "";
//                    foreach ($this->parametros as $value) {
//                        $sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
//                                inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
//                        where t1.cod_categoria='$cod_categoria' and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = d.IDDoc "; 
//                        $sql_col_left .= ",p$k.nom_detalle p$k ";
//                        $k++;
//                    }
                    //echo $sql_left;
                                        
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);                    
                    }
                    if (count($this->id_org_acceso_todos_nivel) <= 0){
                        $this->cargar_acceso_nodos_todos_nivel($atr);                    
                    }
                    //print_r(array_keys($this->id_org_acceso));
                    //print_r(array_keys($this->id_org_acceso_todos_nivel));
                    //print_r(array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)));
                    //echo implode(',',  array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso))));
                    /*FILTRO NO INCLUIR AREA ESPEJO*/
                    import('clases.organizacion.ArbolOrganizacional');
                    
                    $ao = new ArbolOrganizacional();
                    //$ao->jstree_ao(0,$parametros);                    
                    $sql_filtro_area_espejo = "";
                    if (strlen($atr["b-area_espejo"])>0){
                        if(strlen($_SESSION['CookCodEmp'])>0){
                            $sql ="SELECT distinct id
                                    FROM mos_organizacion
                                    where  area_espejo is not null and 
                                    id not in (SELECT id
                                    FROM mos_organizacion
                                    where area_espejo in (select id_organizacion
                                                          from mos_responsable_area
                                                          WHERE cod_emp =". $_SESSION['CookCodEmp'].")
                                               );";  
                            $sql_resp="select id_organizacion
                                        from mos_responsable_area
                                        WHERE cod_emp =". $_SESSION['CookCodEmp']."";
                        }else{
                            $sql ="SELECT distinct id
                                    FROM mos_organizacion
                                    where  area_espejo is not null;";                        
                            
                        }
                        //$empl_inter = array();
                        //$empl_inter = (array_diff(array_column($empleados,'cod_emp'), array_column($emp_resp,'id_personal_aprueba')));
                        //$sql = "SELECT id FROM mos_organizacion_nombres WHERE NOT area_espejo IS NULL AND id IN (" . implode(',', array_keys($this->id_org_acceso)) . ")";
                        //echo $sql;
                        $data_area_espejo = $this->dbl->query($sql);
                        $ids_area_espejo = array();
                        $cad_id_nodos_vinc_norespo='';
                        //PRIMERO LOS NODOS VINCULADOS DONDE NO SE ES RESPONSABLE Y SUS HIJOS
                        foreach ($data_area_espejo as $value) {
                            $ids_area_espejo[] = $value[id];
                            $cad_id_nodos_vinc_norespo .= $ao->BuscaOrgNivelHijos($value[id]).',';
                        }
                        $cad_id_nodos_vinc_norespo .='-1';
                        $nodos_vinc_no_resp = array();
                        $nodos_vinc_no_resp = explode(",", $cad_id_nodos_vinc_norespo);
                        if(strlen($_SESSION['CookCodEmp'])>0){
                            $data_area_resp = $this->dbl->query($sql_resp);
                            //SEGUNDO LOS NODOS DONDE SE ES RESPONSABLE Y SUS HIJOS
                            $cad_id_nodos_respo='';
                            //print_r($data_area_resp);
                            foreach ($data_area_resp as $value) {
                                $cad_id_nodos_respo .= $ao->BuscaOrgNivelHijos($value[id_organizacion]).',';
                            }
                            $cad_id_nodos_respo .='-2';
                            $nodos_resp = array();
                            $nodos_resp = explode(",", $cad_id_nodos_respo);
                            // quitamos los nodos que coincidan
                            $nodos_vinc_no_resp = (array_diff($nodos_vinc_no_resp, $nodos_resp));
                        }
                        if ($cad_id_nodos_vinc_norespo !='-1'){
                            //$sql_filtro_area_espejo = " AND NOT id_organizacion_proceso IN (". implode(',', $ids_area_espejo) . ")";
                            $sql_filtro_area_espejo = " AND NOT id_organizacion_proceso IN (". implode(',', $nodos_vinc_no_resp) . ")";
                        }
                    } 
                    $filtro_ao ='';
                    if ((strlen($atr["b-id_organizacion"])>0)){                             
                        //$id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $id_org = ($atr["b-id_organizacion"]);
                        $filtro_ao .= " INNER JOIN ("
                                . " select IDDoc from mos_documentos_estrorg_arbolproc where id_organizacion_proceso in (". $id_org . ") $sql_filtro_area_espejo GROUP BY IDDoc) as ao ON ao.IDDoc = d.IDDoc ";//" AND id_organizacion IN (". $id_org . ")";
                    }
                    /*FIN FILTRO AREA ESPEJO*/
                   // print_r($this->id_org_acceso_todos_nivel);
                   // echo 'aqui';
                   // print_r($this->id_org_acceso_todos_nivel);
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_documentos  d
                                left join mos_personal p on d.elaboro=p.cod_emp
                                left join mos_personal re on d.reviso=re.cod_emp
                                left join mos_personal ap on d.aprobo=ap.cod_emp
                                left join mos_workflow_documentos wf on d.id_workflow_documento = wf.id
                                left join (select IDDoc, count(*) num_rev, max(fechaRevision) fecha_revision from mos_documento_revision GROUP BY IDDoc) as rev ON rev.IDDoc = d.IDDoc
                            $sql_left
                                $filtro_ao
                            WHERE muestra_doc='S' " ;                    
                            if($atr[formulario]=='S')
                                $sql .= " and formulario = 'S'";
                            else
                                $sql .= " and formulario = 'N' ";
                                                                                                              
                            if(($_SESSION[SuperUser]!='S')&&(isset($atr[terceros]))){
                                $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
                                $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
                                $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";    
                                /*SI NO ESTA EL FILTRO VER SOLO FLUJO DE TRABAJO*/
                                //if (strlen($atr["b-flujo-trabajo"])== 0)
                                {
                                    if (count($this->id_org_acceso))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo) )"; 
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                                }
                                $sql .= ")";
                            }
                            if (($_SESSION[SuperUser]=='S')){
                                /*SI NO ESTA EL FILTRO VER SOLO FLUJO DE TRABAJO*/    
//                                if ((strlen($atr["b-flujo-trabajo"])>= 0)&&(($atr["b-flujo-trabajo"])== '1')){
//                                    $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
//                                    $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
//                                    $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";                                                                                                
//                                    $sql .= ")";
//                                }
//                                else{
                                    $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
                                    $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
                                    $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";    

                                    if (count($this->id_org_acceso))
                                        $sql .= " OR ( d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo) )"; 
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                                    $sql .= ")";
//                                }
                            }
                    /*FILTRO VER SOLO FLUJO DE TRABAJO*/
                    if (strlen($atr["b-flujo-trabajo"])> 0){
                        $sql .= " and ((p.email='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' ) and d.estado_workflow='RECHAZADO') or ";
                        $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' ) and d.estado_workflow='OK') or ";    
                        $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' ) and d.estado_workflow='OK') )";    

                    }
                            //FILTRO PARA MOSTRAR TODOS LOS DOC SI ES SUPERUSER O ESTA EN ALGUNA ETAPA DEL WF
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
//                    if (strlen($atr["b-formulario"])>0)
//                                $sql .= " AND upper(formulario) like '%" . strtoupper($atr["b-formulario"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND (d.vigencia) = '" . ($atr["b-vigencia"]) . "'";
//                    if (strlen($atr["b-publico"])>0){
//                        if (strlen($atr["b-privado"])>0){
//                            
//                        }
//                        else
//                            
//                            $sql .= " AND publico = 'S'";
//                    }
//                    else
//                    if (strlen($atr["b-privado"])>0)
//                        $sql .= " AND publico = 'N'";
//                    if (strlen($atr["b-ocultar-publico"])>0)
//                        $sql .= " AND d.publico <> 'S' ";
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
                               $sql .= " AND d.reviso = '". $atr["b-reviso"] . "'";
                    if (strlen($atr["b-elaboro"])>0)
                               $sql .= " AND d.elaboro = '". $atr["b-elaboro"] . "'";
                    if (strlen($atr["b-aprobo"])>0)
                        $sql .= " AND d.aprobo = '". $atr["b-aprobo"] . "'";
                    /*Acceso a los documentos segun el arbol, no aplica para el administrador de documentos*/
                    if ((!isset($atr[terceros]))){                            
                       $sql .= " AND d.etapa_workflow ='estado_aprobado' "; 
                       
                       $sql .= " AND  (d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo)"; 
                       if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                                                                      
                       $sql .= ")";
                    }
                    //echo $sql;
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
                                    ,CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  elaboro
                                    ,CONCAT(initcap(SUBSTR(re.nombres,1,IF(LOCATE(' ' ,re.nombres,1)=0,LENGTH(re.nombres),LOCATE(' ' ,re.nombres,1)-1))),' ',initcap(re.apellido_paterno)) reviso                                    
                                    ,CONCAT(initcap(SUBSTR(ap.nombres,1,IF(LOCATE(' ' ,ap.nombres,1)=0,LENGTH(ap.nombres),LOCATE(' ' ,ap.nombres,1)-1))),' ',initcap(ap.apellido_paterno)) aprobo
                                    $campo_formulario
                                    ,v_meses                                    
                                    ,version
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha                                    
                                    ,d.descripcion
                                    -- ,palabras_claves
                                    ,num_rev
                                    ,DATE_FORMAT(fecha_revision, '%d/%m/%Y') fecha_rev
                                    ,CASE d.vigencia WHEN 'S' Then 'Si' ELSE 'No' END vigencia
                                    ,dao.arbol_organizacional arbol_organizacional
                                    ,IFNULL((SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE mos_nombres_campos.nombre_campo = d.etapa_workflow AND mos_nombres_campos.modulo = 6
                                    ),(SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE mos_nombres_campos.nombre_campo = 'estado_sin_asignar' AND mos_nombres_campos.modulo = 6)) etapa_workflow
                                     ,CASE d.publico WHEN 'S' Then 'Si' ELSE 'No' END publico                                    
                                    -- ,id_usuario
                                    ,d.observacion
                                    -- ,muestra_doc
                                    -- ,estrucorg
                                    -- ,arbproc
                                    -- ,apli_reg_estrorg
                                    -- ,apli_reg_arbproc
                                    ,d.workflow  
                                    ,p.email
                                    ,'".$_SESSION[SuperUser]."' superuser
                                    ,p.cod_emp cod_elabora    
                                    ,re.cod_emp cod_revisa 
                                    ,ap.cod_emp cod_aprueba 
                                     $sql_col_left
                            FROM mos_documentos d
                                left join mos_personal p on d.elaboro=p.cod_emp
                                left join mos_personal re on d.reviso=re.cod_emp
                                left join mos_personal ap on d.aprobo=ap.cod_emp
                                left join mos_workflow_documentos wf on d.id_workflow_documento = wf.id
                                left join (select IDDoc, count(*) num_rev, max(fechaRevision) fecha_revision from mos_documento_revision GROUP BY IDDoc) as rev ON rev.IDDoc = d.IDDoc
                                INNER JOIN (select IDDoc id , GROUP_CONCAT(id_organizacion_proceso) arbol_organizacional from mos_documentos_estrorg_arbolproc GROUP BY IDDoc) AS dao ON dao.id = d.IDDoc
                            $sql_left
                                $filtro_ao
                            WHERE muestra_doc='S' ";
                            if($atr[formulario]=='S')
                                $sql .= " and formulario = 'S'";
                            else
                                $sql .= " and formulario = 'N' ";
                    
                            if(($_SESSION[SuperUser]!='S')&&(isset($atr[terceros]))){
                                $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
                                $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
                                $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";    
                                /*SI NO ESTA EL FILTRO VER SOLO FLUJO DE TRABAJO*/                                
                                //if (strlen($atr["b-flujo-trabajo"])== 0)
                                {
                                    if (count($this->id_org_acceso))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo) )"; 
                                    /*DOCUMENTOS PUBLICOS*/
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S'  and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                                }
                                $sql .= ")";
                            }
                            if (($_SESSION[SuperUser]=='S')){
                                /*SI NO ESTA EL FILTRO VER SOLO FLUJO DE TRABAJO*/    
//                                if ((strlen($atr["b-flujo-trabajo"])>= 0)&&(($atr["b-flujo-trabajo"])== '1')){
//                                    $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
//                                    $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
//                                    $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";                                                                                                
//                                    $sql .= ")";
//                                }
//                                else{
                                    $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
                                    $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
                                    $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";    

                                    if (count($this->id_org_acceso))
                                        $sql .= " OR ( d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo) )"; 
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                                     $sql .= ")";
//                                }
                            }
                    /*FILTRO VER SOLO FLUJO DE TRABAJO*/
                    if (strlen($atr["b-flujo-trabajo"])> 0){
                        $sql .= " and ((p.email='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' ) and d.estado_workflow='RECHAZADO') or ";
                        $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' ) and d.estado_workflow='OK') or ";    
                        $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' ) and d.estado_workflow='OK') )";    

                    }
                            //FILTRO PARA MOSTRAR TODOS LOS DOC SI ES SUPERUSER O ESTA EN ALGUNA ETAPA DEL WF
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
//                    if (strlen($atr["b-formulario"])>0)
//                                $sql .= " AND upper(formulario) like '%" . strtoupper($atr["b-formulario"]) . "%'";
                    if (strlen($atr["b-vigencia"])>0)
                        $sql .= " AND (d.vigencia) = '" . ($atr["b-vigencia"]) . "'";
//                    if (strlen($atr["b-publico"])>0){
//                        if (strlen($atr["b-privado"])>0){
//                            
//                        }
//                        else
//                            
//                            $sql .= " AND publico = 'S'";
//                    }
//                    else
//                    if (strlen($atr["b-privado"])>0)
//                        $sql .= " AND publico = 'N'";
//                    if (strlen($atr["b-ocultar-publico"])>0)
//                        $sql .= " AND d.publico <> 'S' ";
                    
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
                               $sql .= " AND d.reviso = '". $atr["b-reviso"] . "'";
                    if (strlen($atr["b-elaboro"])>0)
                               $sql .= " AND d.elaboro = '". $atr["b-elaboro"] . "'";
                    if (strlen($atr["b-aprobo"])>0)
                        $sql .= " AND d.aprobo = '". $atr["b-aprobo"] . "'";
                    if (strlen($atr["nodos"])>0)
                        $sql .= " AND d.IDDoc in (SELECT DISTINCT IDDoc
                                                    FROM mos_registro_item 
                                                    where valor in (".$atr["nodos"].") and tipo='11')";
                    if (strlen($atr["nodosp"])>0)
                        $sql .= " AND d.IDDoc in (SELECT DISTINCT IDDoc
                                                    FROM mos_registro_item 
                                                    where valor in (".$atr["nodosp"].") and tipo='12')";
                    
                    /*Acceso a los documentos segun el arbol, no aplica para el administrador de documentos*/
                    if ((!isset($atr[terceros]))){                            
                       $sql .= " AND d.etapa_workflow ='estado_aprobado' "; 
                       
                       $sql .= " AND  (d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ") $sql_filtro_area_espejo)"; 
                       if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-ocultar-publico"])==0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_merge(array(0), array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso)))) . ") $sql_filtro_area_espejo) )"; 
                                                                      
                       $sql .= ")";
                    }
                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //print_r(array_keys($this->id_org_acceso));
                    //print_r($atr);
                    // echo $sql;
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
                                ,(SELECT count(*) FROM mos_registro_item WHERE id_unico =  mos_documentos_datos_formulario.id_unico) cant_2
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
                                return "- No se puede eliminar el documento, existen registros asociados.";
                            }
                            $respuesta = $this->dbl->delete("mos_documentos", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_parametro_semaforo", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_revision", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_version", "IDDoc = " . $atr[id]);
                            //$respuesta = $this->dbl->delete("mos_documentos_categoria", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documentos_datos_formulario", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documentos_estrorg_arbolproc", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_parametro_modulos", "id_registro = " . $atr[id] . " AND cod_categoria = " . $atr[cod_categoria] . " AND cod_categoria_aux = " . $atr[cod_categoria] . "");                         
                            //$respuesta = $this->dbl->delete("mos_registro", "IDDoc = " . $atr[id]);
                        }
                        else{
                            $respuesta = $this->dbl->delete("mos_documentos", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_parametro_semaforo", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_revision", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_documento_version", "IDDoc = " . $atr[id]);
                            //$respuesta = $this->dbl->delete("mos_documentos_categoria", "IDDoc = " . $atr[id]);
                            $respuesta = $this->dbl->delete("mos_parametro_modulos", "id_registro = " . $atr[id] . " AND cod_categoria = " . $atr[cod_categoria] . " AND cod_categoria_aux = " . $atr[cod_categoria] . "");
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
                            $sql = "UPDATE mos_registro_item SET IDDoc = $codigo"
                                    . " WHERE IDDoc = $atr[id] ";
                            $this->dbl->insert_update($sql);
                        }
                        $nuevo = "IDDoc: \'$atr[id]\'";
                        $this->registraTransaccionLog(3,$nuevo,'',$atr[id]);
                        
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/mos_documentos_distribucion/",$error ) == true) 
                            return "- No se puede eliminar el documento, existen Lista de Distribuci&oacute;n asociados.";                        
                        if (preg_match("/mos_documentos_relacionados/",$error ) == true) 
                            return "- No se puede eliminar el documento, esta asociado a otros Documentos.";
                        return $error; 
                                
                    }
             }
             public function cambiarestadowf($atr){
                    try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_documentos 
                        SET                            
                        estado_workflow = '$atr[estado]',
                            etapa_workflow = '$atr[etapa]',
                            observacion_rechazo = '$atr[observacion_rechazo]',    
                            fecha_estado_workflow = now(),id_usuario_workflow = $atr[id_usuario]
                            WHERE  IDDoc = $atr[id]";     
                    //echo $sql;
                    $val = $this->verDocumentos($atr[id]);
                    $this->dbl->insert_update($sql);
                    
                    $nuevo = "Id usuario wf: \'$atr[id_usuario_workflow]\', etapa_workflow = \'$atr[etapa]\', estado wf: \'$atr[estado_workflow]\', ";
                    $anterior = "Id usuario wf: \'$val[id_usuario_workflow]\', etapa_workflow = \'$val[etapa_workflow]\', estado wf: \'$val[estado_workflow]\',  ";
                    $this->registraTransaccionLog(6,$nuevo,$anterior, $atr[id]);
                    /*
                    $this->registraTransaccion('Modificar','Modifico el WorkflowDocumentos ' . $atr[descripcion_ano], 'mos_workflow_documentos');
                    */
                    return "El flujo de trabajo de documentos ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }

             }
 
     public function verListaDocumentos($parametros){
         //print_r($parametros);
                if($_SESSION[ParamAdic]=='formulario') {
                    $parametros['formulario']='S';
                    $cod_categoria=15;
                    $muestra_col_formulario="display:;";
                }
                else{
                    $cod_categoria=1;
                    $muestra_col_formulario="display:none";
                }
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
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos("Días", "dias_vig", $parametros,50)),
               array( "width"=>"8%","ValorEtiqueta"=>"<div style='width:100px'>&nbsp;</div>"),
               
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[Codigo_doc], "Codigo_doc", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[nombre_doc], "nombre_doc", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType], "contentType", $parametros)),               
               array( "width"=>"5%","ValorEtiqueta"=>"Visor de Google"),  
               array( "width"=>"3%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[contentType_visualiza], "contentType_visualiza", $parametros)),
                //"style"=>$muestra_col_formulario,
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[elaboro], "elaboro", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[reviso], "reviso", $parametros)),               
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[aprobo], "aprobo", $parametros)),
               array( "width"=>"4%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[formulario], "formulario", $parametros,65)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[v_meses], "v_meses", $parametros)),                    
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha], "fecha", $parametros)),                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),                  
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Revisión", "fecha_revision", $parametros)),     
               array( "width"=>"5%","ValorEtiqueta"=>link_titulos("Fecha de Revisión", "fecha_revision", $parametros)),  
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[palabras_claves], "palabras_claves", $parametros)),               
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[vigencia], "vigencia", $parametros)),
               array( "width"=>"20%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[arbproc], "arbproc", $parametros, 150,40)),     
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[etapa_workflow], "etapa_workflow", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[publico], "publico", $parametros)),
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
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    //array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    //array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               
               
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros($cod_categoria);
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
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_modificar.png' title='Editar Documentos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarDocumentos','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_eliminar.png' title='Eliminar Documentos'>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'verWorkFlow','imagen'=> "<img style='cursor:pointer' src='diseno/images/ico_nuevo.png' title='Ver Flujo de Trabajo'>"));
               */
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
                //if($modulo==1) $grid->hidden[12] = true;
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                //$grid->setFuncion("semaforo", "semaforo");
                $grid->setParent($this);
                $grid->setFuncion("dias_vig", "semaforo_reporte");
                $grid->setFuncion("accion", "colum_admin");
                $grid->setFuncion("nom_visualiza", "archivo");
                $grid->setFuncion("Codigo_doc", "codigo_doc");
                $grid->setFuncion("formulario", "BuscaRegistrosAdministrador");
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
               // print_r($parametros);
                if($_SESSION[ParamAdic]=='formulario') $parametros['formulario']='S';
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
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[etapa_workflow], "etapa_workflow", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[publico], "publico", $parametros)),
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
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    //array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    //array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               
               
               
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros(15);
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
                              
                              if ((in_array($i, $array_columns))&&((strlen($parametros['b-id_organizacion'])==0)|| (strpos($parametros['b-id_organizacion'],','))))
                                  
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
        public function exportarPHPExcel($parametros){
        $grid= new DataGrid();
        $this->listarDocumentos($parametros, 1, 100000);
        return $this->dbl->data;
        
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
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[etapa_workflow], "etapa_workflow", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[publico], "publico", $parametros)),
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
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    //array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
                    //array( "width"=>"23%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[workflow], "workflow", $parametros)),
               
               
               
                );

                $columna_funcion =10;
           // $grid->hidden = array(0 => true);
                if($_SESSION[ParamAdic]=='formulario') {
                    $cod_categoria=15;
                }
                else{
                    $cod_categoria=1;
                }                
           if (count($this->parametros) <= 0){
                        $this->cargar_parametros($cod_categoria);
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
           // print_r($data);die;
            return $grid->armarTabla();
        }
 
 
            public function indexDocumentos($parametros)
            { //print_r($parametros);
            
                //echo  $_SESSION[ParamAdic];
                if($_SESSION[ParamAdic]=='formulario') {
                    $parametros['formulario']='S';
                    $cod_categoria = 15;
                }
                else{
                    $cod_categoria = 1;
                }
                $parametros['b-vigencia'] = 'S';
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="dias_vig";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    if($cod_categoria == 15)
                        $parametros['mostrar-col']="2-3-4-5-7-8-9-12-14-17-20-21";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                    else
                        $parametros['mostrar-col']="2-3-4-5-7-8-9-14-17-20-21";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros($cod_categoria);
                }
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                $contenido[TITULO_MODULO] .= '<br><label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-flujo-trabajo" value="S"> Mi Flujo de Trabajo </label>';
                $k = 30;
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
                $parametros['email_usuario']= $_SESSION['CookEmail'];
                /*Para visualizar los documentos de terceros*/
                //if ($_SESSION[SuperUser]!='S')
                $parametros[terceros] = 'S';
                $this->get_min_nivel_area($parametros);                
                $parametros["b-publico"] = 'S';
                
                $grid = $this->verListaDocumentos($parametros);
                
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];                
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
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres
                                                                            FROM mos_personal p WHERE reviso = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres
                                                                            FROM mos_personal p WHERE elaboro = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);
                $contenido['APROBO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(initcap(SUBSTR(p.nombres,1,IF(LOCATE(' ' ,p.nombres,1)=0,LENGTH(p.nombres),LOCATE(' ' ,p.nombres,1)-1))),' ',initcap(p.apellido_paterno))  nombres
                                                                            FROM mos_personal p WHERE aprobo = 'S'"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $val['cod_emp_relator']);

                /*HTML FILTRO RAPIDO FLUJO TRABAJO*/
                $contenido[FILTRO_OTROS_CAMPOS] = '<br/><div class="form-group">                                                                
                                <label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-flujo-trabajo" value="S"> Mi Flujo de Trabajo </label>';                            
                /*JS Busqueda Filtro Rapido*/
                $js_flujo = "$('.b-mi-flujo-trabajo').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-flujo-trabajo').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-flujo-trabajo').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });
                            ";
                /*VALIDAMOS QUE EL USUARIO CONECTADO ESTE REGISTRADO COMO PERSONAL*/
                if ($_SESSION['CookCodEmp'] <> ''){
                    if(!class_exists('Personas')){
                        import("clases.personas.Personas");
                    }
                    $p = new Personas();
                    $id_ao = $p->verAreaPersonas($_SESSION['CookCodEmp']);
                    $contenido[FILTRO_OTROS_CAMPOS] .= '<br>
                                    <label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-nivel" value="N"> Mi Nivel </label>';
                    $contenido[TITULO_MODULO] .= ' &nbsp;<label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-nivel" value="N"> Mi Nivel </label>' ;       
                    $js_flujo .= "
                            $('.b-mi-nivel').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#div-ao').jstree(true).deselect_all(true);
                                    $('#div-ao').jstree(true).select_node('phtml_$id_ao');                                    
                                } else {
                                     $('#div-ao').jstree(true).deselect_node('phtml_$id_ao');  
                                }
                            });";
                }
                $contenido[FILTRO_OTROS_CAMPOS] .= '</div>';
                /*FIN VALIDACION*/
                
                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                $contenido["FORMULARIOCHECKED"] = "checked";
                foreach ( $this->nombres_columnas as $key => $value) {
                    //echo($key).' ';
                    if(!($cod_categoria == 1 && $key=='formulario'))
                        $contenido["N_" . strtoupper($key)] =  $value;
                    else{
                        $contenido["MOSTRARREGISTRO"] =  "style='display:none;'";
                        $contenido["FORMULARIOCHECKED"] = "";
                    }
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
                                        init_filtro_ao_multiple();');
                $objResponse->addScript($js_flujo);
                
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
                $contenido[TITULO_MODULO] .= '<br><label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-ocultar-publico" value="S"> Ocultar P&uacute;blicos </label>';
                $contenido[TITULO_MODULO] .= '<label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-area_espejo" value="S"> Ocultar Áreas Vinculadas </label>';
                /*JS Busqueda Filtro Rapido*/
                $js_flujo = "$('.b-area_espejo').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-area_espejo').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-area_espejo').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });
                            ";
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
                        $this->cargar_parametros(15);
                }
                $k = 30;
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
                /*FILTRA LOS DOCUMENTOS QUE ESTAN VIGENTES*/
                $parametros["b-vigencia"] = 'S';
                $parametros["b-publico"] = 'S';
                $grid = $this->verListaDocumentosReporte($parametros);
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];  
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
                if ($_SESSION['CookCodEmp'] <> ''){
                    if(!class_exists('Personas')){
                        import("clases.personas.Personas");
                    }
                    $p = new Personas();
                    $id_ao = $p->verAreaPersonas($_SESSION['CookCodEmp']);
                    $contenido[FILTRO_OTROS_CAMPOS] .= '<br>
                                    <label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-nivel" value="N"> Mi Nivel </label>';
                    $contenido[TITULO_MODULO] .= ' &nbsp;<label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-nivel" value="N"> Mi Nivel </label>' ;       
                    $js_flujo .= "
                            $('.b-mi-nivel').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#div-ao').jstree(true).deselect_all(true);
                                    $('#div-ao').jstree(true).select_node('phtml_$id_ao');                                    
                                } else {
                                     $('#div-ao').jstree(true).deselect_node('phtml_$id_ao');  
                                }
                            });";
                }   

                $js_flujo .= "$('.b-mi-ocultar-publico').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-ocultar-publico').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-ocultar-publico').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });
                            ";                
                
                import('clases.organizacion.ArbolOrganizacional');

                //AQUI EL ARBOL
                $contenido['ARBOLORGANIZACIONAL'] = '<input type="hidden" value="" name="nodos" id="nodos"/>
                        <iframe id="iframearbol" src="pages/cargo/arbol_organizacion_registros.php" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>';
                $contenido['ARBOLPROCESO'] = '<input type="hidden" value="" name="nodosp" id="nodosp"/>
                        <iframe id="iframearbolP" src="pages/cargo/arbol_proceso_registros.php" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>';
                
                $ao = new ArbolOrganizacional();
                $ao->cargar_acceso_nodos_explicito($parametros);
                $contenido[DIV_ARBOL_ORGANIZACIONAL] = $ao->jstree_ao(2,$parametros);

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
//                if (($parametros['b-formulario'])=='S'){
//                    $objResponse->addScript("$('#b-formulario').prop('checked',true);");
//                }
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
//                                        if(($("#b-formulario").is(":checked"))) {
//                                            $("#b-formulario").parent().parent().hide();
//                                        }

                                        PanelOperator.initPanels("");
                                        ScrollBar.initScroll();
                                        PanelOperator.showSearch("");

                                        init_filtro_rapido();
                                        init_filtro_ao_multiple();
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
                                        array.addParametro('modo',document.getElementById('modo').value);
                                       array.addParametro('cod_link',document.getElementById('cod_link').value);
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
                $objResponse->addScript("$('#tabs-visualiza').tab();"
                        . "$('#tabs-visualiza a:first').tab('show');"); 
                
                $objResponse->addScript($js_flujo);
                return $objResponse;
            }
                   
            
            public function indexDocumentosReporte($parametros)
            { 
                $contenido[TITULO_MODULO] = $parametros[nombre_modulo];
                $contenido[TITULO_MODULO] .= '<br><label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-ocultar-publico" value="S"> Ocultar P&uacute;blicos </label>';
                $contenido[TITULO_MODULO] .= '<label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-area_espejo" value="S"> Ocultar Áreas Vinculadas </label>';
                /*JS Busqueda Filtro Rapido*/
                $js_flujo = "$('.b-area_espejo').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-area_espejo').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-area_espejo').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });
                            ";
                if (!isset($parametros['b-formulario'])){
                    $contenido[OTRAS_OPCIONES] = '<li>
                                    <a href="#"  onClick="reporte_documentos_pdf();">
                                      <i class="icon icon-alert-print"></i>
                                      <span>Documentos</span>
                                    </a>
                                  </li>';
                }
                if($_SESSION[ParamAdic]=='formulario') {
                    $parametros['formulario']='S';
                    $cod_categoria = 15;
                }
                else{
                    $cod_categoria = 1;
                }
                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="dias_vig";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-4-5-14-17-20";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros(1);
                }
                $k = 30;
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
                /*FILTRA LOS DOCUMENTOS QUE ESTAN VIGENTES*/
                $parametros["b-vigencia"] = 'S';
                $parametros["b-publico"] = 'S';
                $grid = $this->verListaDocumentosReporte($parametros);
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];  
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
                /*VALIDAMOS QUE EL USUARIO CONECTADO ESTE REGISTRADO COMO PERSONAL*/
                if ($_SESSION['CookCodEmp'] <> ''){
                    if(!class_exists('Personas')){
                        import("clases.personas.Personas");
                    }
                    $p = new Personas();
                    $id_ao = $p->verAreaPersonas($_SESSION['CookCodEmp']);
                    $contenido[FILTRO_OTROS_CAMPOS] .= '<br>
                                    <label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-nivel" value="N"> Mi Nivel </label>';
                    $contenido[TITULO_MODULO] .= ' &nbsp;<label class="checkbox-inline"> 
                                    <input type="checkbox" class="b-mi-nivel" value="N"> Mi Nivel </label>' ;       
                    $js_flujo .= "
                            $('.b-mi-nivel').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#div-ao').jstree(true).deselect_all(true);
                                    $('#div-ao').jstree(true).select_node('phtml_$id_ao');                                    
                                } else {
                                     $('#div-ao').jstree(true).deselect_node('phtml_$id_ao');  
                                }
                            });";
                }   
                $js_flujo .= "$('.b-mi-ocultar-publico').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-ocultar-publico').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-ocultar-publico').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });
                            ";                
                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $ao->cargar_acceso_nodos_explicito($parametros);
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(1,$parametros);

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
                $contenido["FORMULARIOCHECKED"] = "checked";
                foreach ( $this->nombres_columnas as $key => $value) {
                    //echo($key).' ';
                    if(!($cod_categoria == 1 && $key=='formulario'))
                        $contenido["N_" . strtoupper($key)] =  $value;
                    else{
                        $contenido["MOSTRARREGISTRO"] =  "style='display:none;'";
                        $contenido["FORMULARIOCHECKED"] = "";
                    }
                }                  
//                foreach ( $this->nombres_columnas as $key => $value) {
//                    $contenido["N_" . strtoupper($key)] =  $value;
//                }  
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
//                if (($parametros['b-formulario'])=='S'){
//                    $objResponse->addScript("$('#b-formulario').prop('checked',true);");
//                }
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
//                                            if(($("#b-formulario").is(":checked"))) {
//                                                $("#b-formulario").parent().parent().hide();
//                                            }

                                            PanelOperator.initPanels("");
                                            ScrollBar.initScroll();
                                            PanelOperator.showSearch("");

                                            init_filtro_rapido();
                                            init_filtro_ao_multiple();
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
                $objResponse->addScript("$('#tabs-visualiza').tab();"
                        . "$('#tabs-visualiza a:first').tab('show');");                
                $objResponse->addScript($js_flujo);
                
                //$objResponse->addScript('setTimeout(function(){ init_documentos(); }, 500);');
                //$objResponse->addScript('setTimeout(function(){ init_tabla_reporte(); }, 500);');                
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {              
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $parametros[opcion] = 'simple';
                if($_SESSION[ParamAdic]=='formulario') {
                    $parametros[formulario]='S';
                    $cod_categoria = 15;
                }
                else{
                    $cod_categoria = 1;
                    $parametros[formulario]='N';
                }                
                
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);
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
                //$_SESSION[CookEmail]
                //$_SESSION[CookCodEmp]
                $contenido_1["N_ELABORO"] .= ' &#8594;';
                if($_SESSION[SuperUser]=='S'){
                    $sql="SELECT wf.id,
                            CONCAT(CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno))
                            ,' &rarr; ', 
                           IF(perso_revisa.nombres is null,'N/A',CONCAT(initcap(SUBSTR(perso_revisa.nombres,1,IF(LOCATE(' ' ,perso_revisa.nombres,1)=0,LENGTH(perso_revisa.nombres),LOCATE(' ' ,perso_revisa.nombres,1)-1))),' ',initcap(perso_revisa.apellido_paterno))	) 
                           ,' &rarr; ', 
                            CONCAT(initcap(SUBSTR(perso_aprueba.nombres,1,IF(LOCATE(' ' ,perso_aprueba.nombres,1)=0,LENGTH(perso_aprueba.nombres),LOCATE(' ' ,perso_aprueba.nombres,1)-1))),' ',initcap(perso_aprueba.apellido_paterno))) as wf
                            FROM mos_workflow_documentos AS wf
                            left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
                            left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                            INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp";
                }
                else
                {
                    $sql="SELECT wf.id,
                            CONCAT(CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno))
                            ,' &rarr; ', 
                           IF(perso_revisa.nombres is null,'N/A',CONCAT(initcap(SUBSTR(perso_revisa.nombres,1,IF(LOCATE(' ' ,perso_revisa.nombres,1)=0,LENGTH(perso_revisa.nombres),LOCATE(' ' ,perso_revisa.nombres,1)-1))),' ',initcap(perso_revisa.apellido_paterno))	) 
                           ,' &rarr; ', 
                            CONCAT(initcap(SUBSTR(perso_aprueba.nombres,1,IF(LOCATE(' ' ,perso_aprueba.nombres,1)=0,LENGTH(perso_aprueba.nombres),LOCATE(' ' ,perso_aprueba.nombres,1)-1))),' ',initcap(perso_aprueba.apellido_paterno))) as wf
                            FROM mos_workflow_documentos AS wf
                           left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
                           left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                           INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp
                    WHERE wf.id_personal_responsable='".$_SESSION['CookCodEmp']."'";
                    
                }
                //echo $sql;
//                $contenido_1['ID_WORKFLOW_DOCUMENTO'] = $ut_tool->OptionsCombo($sql
//                                                                    , 'id'
//                                                                    , 'wf', $val['cod_emp_relator']);
                $contenido_1['WORKFLOW'] = 'N';
                if(!class_exists('Parametros')){
                    import("clases.parametros.Parametros");
                }
                $campos_dinamicos = new Parametros();
                if($_SESSION[ParamAdic]=='formulario') 
                    $array = $campos_dinamicos->crear_campos_dinamicos(15,null,6,14);
                else
                    $array = $campos_dinamicos->crear_campos_dinamicos(1,null,6,14);
                    
                $contenido_1[OTROS_CAMPOS] = $array[html];
                $js = $array[js];
                $js .="$('#div_cargos').parent().hide();";

                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();
                $array_nuevo = $adjuntos->crear_archivos_adjuntos('mos_documentos_anexos', 'id_documento', null, 18);
                $contenido_1[ARCHIVOS_ADJUNTOS] = $array_nuevo[html];
                $js .= $array_nuevo[js];
                //echo $_SESSION['CookEmail'];
                $parametros['email_usuario']= $_SESSION['CookEmail'];
                $parametros[operacion]='crear';
                $contenido_1[DOCUMENTOS_RELACIONADOS]=$this->listarDocumentosComboRelacion($parametros);

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
                $contenido_1['TOK_NEW'] = time();
                $contenido_1['DESC_OPERACION_NOTIFICAR'] = "Enviar a Revisión";//"Guardar y Notificar";

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
                $objResponse->addScript('ao_multiple();');

                $objResponse->addScript("$('#fecha').datepicker();");
                $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
                if($_SESSION[ParamAdic]!='formulario')
                {
                    $objResponse->addScript("$($('#tabs-hv-2').find('#li2')).hide();");
                }
                $objResponse->addScript("$('#documento_relacionado').selectpicker({
                                                style: 'btn-combo'
                                              });$js");
                
                $objResponse->addScript($js);
                return $objResponse;
            }
            
            public function crear_revision($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $val = $this->verDocumentos($parametros[id]); 
                $ut_tool = new ut_Tool();
                $contenido_1   = array();
                /*Carga Acceso segun el arbol*/
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($parametros);
                }
                $editar = false;
                if($_SESSION[SuperUser]=='S'){
                    $editar = true;
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($val[id_organizacion],',')){    
                        $organizacion = explode(",", $val[id_organizacion]);
                    }
                    else{
                        $organizacion[] = $val[id_organizacion];                                 
                    }
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if (isset($this->id_org_acceso[$value_2])){
                            //if(($this->id_org_acceso[$value_2][crear]=='S'))
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }       
                }
                else if(($val[elaboro]==$_SESSION['CookCodEmp'])){
                        $editar = false;                        
                        $organizacion = array();
                        if(strpos($val[id_organizacion],',')){    
                            $organizacion = explode(",", $val[id_organizacion]);
                        }
                        else{
                            $organizacion[] = $val[id_organizacion];                                 
                        }
                        /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                        foreach ($organizacion as $value_2) {
                            if (isset($this->id_org_acceso[$value_2])){
                                if(($this->id_org_acceso[$value_2][crear]=='S'))
                                    $editar = true;
                            } else{
                                $editar = false;
                                break;
                            }
                        }                        
                     }
                
                else{
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($val[id_organizacion],',')){    
                        $organizacion = explode(",", $val[id_organizacion]);
                    }
                    else{
                        $organizacion[] = $val[id_organizacion];                                 
                    }
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if (isset($this->id_org_acceso[$value_2])){
                            if(($this->id_org_acceso[$value_2][modificar_terceros]=='S'))
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                }
                if (count($this->nombres_columnas) <= 0){
                    $this->cargar_nombres_columnas();
                }
                
               if ($editar === true){
                    foreach ( $this->nombres_columnas as $key => $value) {
                        $contenido_1["N_" . strtoupper($key)] =  $value;
                    }                
                    if (count($this->placeholder) <= 0){
                            $this->cargar_placeholder();
                    }
                    foreach ( $this->placeholder as $key => $value) {
                        $contenido_1["P_" . strtoupper($key)] =  $value;
                    }                     

                    if(!($_SESSION[SuperUser]=='S')){
                        $sql_filtro=" AND cod_emp = " . $_SESSION['CookCodEmp'];
                    }     
                    else $sql_filtro = '';
                    $contenido_1['ELABORO'] = $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                            CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                                FROM mos_personal p WHERE elaboro = 'S' $sql_filtro"
                                                                        , 'cod_emp'
                                                                        , 'nombres', $val['elaboro']);

                    $contenido_1[NOMBRE_DOC] = $val["Codigo_doc"].'-'.$val["nombre_doc"].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                    $contenido_1[REVISION] = $this->verDocumentosRevisionSiguiente($parametros[id],$val[version]);
                    $contenido_1[FECHA] = date('d/m/Y');
                    $contenido_1['VERSION'] = $val["version"];
               }
               
               $sql = "SELECT d.IDDoc                                                                        
                                    ,Codigo_doc 
                                    ,nombre_doc
                                    ,version
                                    ,revision
                                    ,DATE_FORMAT(fecha, '%d/%m/%Y') fecha 
                                    ,contentType
                                    ,nom_visualiza 
                                    ,contentType_visualiza 
                                    ,r.observacion
                            FROM mos_documentos d
                            inner JOIN mos_documento_revision r on r.IDDoc = d.IDDoc
                            WHERE Codigo_doc='$val[Codigo_doc]' "
                        . "order by version desc, revision desc ";
                $data = $this->dbl->query($sql, array());
                $grid= new DataGrid();
                $grid->SetConfiguracionMSKS("tblDocumentos", "");
                $config=array(
//htmlentities(
               //array( "width"=>"1%","ValorEtiqueta"=>"ID"),     
               array( "width"=>"3%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[Codigo_doc], ENT_QUOTES, "UTF-8")),     
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[nombre_doc], ENT_QUOTES, "UTF-8")),     
               
               array( "width"=>"2%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[version], ENT_QUOTES, "UTF-8")),
                    array( "width"=>"2%","ValorEtiqueta"=>"Revisi&oacute;n"),  
                    array( "width"=>"5%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[fecha], ENT_QUOTES, "UTF-8")),         
               array( "width"=>"2%","ValorEtiqueta"=>"Archivos"),                                                    
               //array( "width"=>"8%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[contentType_visualiza], ENT_QUOTES, "UTF-8")),                
               //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[elaboro], ENT_QUOTES, "UTF-8")),
               array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[observacion_rev], ENT_QUOTES, "UTF-8")),                                                                   
                );      
                
                $func= array();

                $columna_funcion = -1;
                               
                
                $grid->hidden[0] = $grid->hidden[6] =  $grid->hidden[8] = true;                
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
                if ($editar === true){
                    $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:last').tab('show');");
                }
                else{
                    $objResponse->addScript("$('#tabs-hv').tab();$('#tabs-hv a:last').tab('show');");
                    $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");
                }
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
                                 
                $val = $this->verDocumentos($parametros[id]); 
                /*Carga Acceso segun el arbol*/
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($parametros);
                }
                $editar = false;
                if($_SESSION[SuperUser]=='S'){
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($val[id_organizacion],',')){    
                        $organizacion = explode(",", $val[id_organizacion]);
                    }
                    else{
                        $organizacion[] = $val[id_organizacion];                                 
                    }
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if (isset($this->id_org_acceso[$value_2])){
                            //if(($this->id_org_acceso[$value_2][crear]=='S'))
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }       
                }
                else if(($val[elaboro]==$_SESSION['CookCodEmp'])){
                        $editar = false;                        
                        $organizacion = array();
                        if(strpos($val[id_organizacion],',')){    
                            $organizacion = explode(",", $val[id_organizacion]);
                        }
                        else{
                            $organizacion[] = $val[id_organizacion];                                 
                        }
                        /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                        foreach ($organizacion as $value_2) {
                            if (isset($this->id_org_acceso[$value_2])){
                                if(($this->id_org_acceso[$value_2][crear]=='S'))
                                    $editar = true;
                            } else{
                                $editar = false;
                                break;
                            }
                        }                        
                     }
                
                else{
                    $editar = false;                        
                    $organizacion = array();
                    if(strpos($val[id_organizacion],',')){    
                        $organizacion = explode(",", $val[id_organizacion]);
                    }
                    else{
                        $organizacion[] = $val[id_organizacion];                                 
                    }
                    /*SE VALIDA QUE PUEDE EDITAR EN TODAS LAS AREAS*/
                    foreach ($organizacion as $value_2) {
                        if (isset($this->id_org_acceso[$value_2])){
                            if(($this->id_org_acceso[$value_2][modificar_terceros]=='S'))
                                $editar = true;
                        } else{
                            $editar = false;
                            break;
                        }
                    }
                }
                if (count($this->nombres_columnas) <= 0){
                    $this->cargar_nombres_columnas();
                }
                if ($editar === true){
                    
                    foreach ( $this->nombres_columnas as $key => $value) {
                        $contenido_1["N_" . strtoupper($key)] =  $value;
                    }                
                    if (count($this->placeholder) <= 0){
                            $this->cargar_placeholder();
                    }
                    foreach ( $this->placeholder as $key => $value) {
                        $contenido_1["P_" . strtoupper($key)] =  $value;
                    }   
                    $contenido_1["N_ELABORO"] .= ' &#8594;';
                    if($_SESSION[SuperUser]=='S'){
                        $sql="SELECT wf.id,
                                CONCAT( 
                                CONCAT(CONCAT(UPPER(LEFT(perso_responsable.apellido_paterno, 1)), LOWER(SUBSTRING(perso_responsable.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_responsable.apellido_materno, 1)), LOWER(SUBSTRING(perso_responsable.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_responsable.nombres, 1)), LOWER(SUBSTRING(perso_responsable.nombres, 2)))) 
                                ,' &rarr; ', 
                                IFNULL(CONCAT(CONCAT(UPPER(LEFT(perso_revisa.apellido_paterno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_revisa.apellido_materno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_revisa.nombres, 1)), LOWER(SUBSTRING(perso_revisa.nombres, 2)))) ,'N/A')
                                ,' &rarr; ', CONCAT(CONCAT(UPPER(LEFT(perso_aprueba.apellido_paterno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_aprueba.apellido_materno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_aprueba.nombres, 1)), LOWER(SUBSTRING(perso_aprueba.nombres, 2)))) ) as wf
                                FROM mos_workflow_documentos AS wf
                                left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
                                left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                                INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp";
                        
                        
                    }
                    else
                    {
                        $sql="SELECT wf.id,
                                CONCAT( 
                                IFNULL(CONCAT(CONCAT(UPPER(LEFT(perso_revisa.apellido_paterno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_revisa.apellido_materno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_revisa.nombres, 1)), LOWER(SUBSTRING(perso_revisa.nombres, 2)))),'N/A') 
                                ,' &rarr; ', CONCAT(CONCAT(UPPER(LEFT(perso_aprueba.apellido_paterno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_aprueba.apellido_materno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_aprueba.nombres, 1)), LOWER(SUBSTRING(perso_aprueba.nombres, 2)))) ) as wf
                                FROM mos_workflow_documentos AS wf
                                left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                                INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp
                        WHERE wf.id_personal_responsable='".$_SESSION['CookCodEmp']."'";
                       
                    }
                    //echo $sql;
                    $contenido_1['ID_WORKFLOW_DOCUMENTO'] = $ut_tool->OptionsCombo($sql
                                                                        , 'id'
                                                                        , 'wf', $val['id_workflow_documento']);

                    $contenido_1[NOMBRE_DOC_AUX] = $val["Codigo_doc"].'-'.$val["nombre_doc"].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                    $contenido_1[REVISION] = $this->verDocumentosRevisionSiguiente($parametros[id],$val[version]);
                    $contenido_1[FECHA] = date('d/m/Y');
                    $contenido_1['VERSION'] = $val["version"]+1;
                    $contenido_1['CODIGO_DOC'] = ($val["Codigo_doc"]);
                    $contenido_1['NOMBRE_DOC'] = ($val["nombre_doc"]);
                }
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
                if ($editar === true){
                    $objResponse->addScript("$('#tabs-hv').tab();"
                        . "$('#tabs-hv a:last').tab('show');");
                }
                else{
                    $objResponse->addScript("$('#tabs-hv').tab();$('#tabs-hv a:last').tab('show');");
                    $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");
                }
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
                //NUEVA VALIDACION DE ARBOL Y CARGO
                $validator = new FormValidator();
                $tiene_arbol=0;
                $tiene_cargo=0;
                for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                    if (isset($parametros["nombre_din_$i"])){                                
                        if (($parametros["tipo_din_$i"] == "11")){//cuento cuantos arboles organizacionales hay
                            $tiene_arbol++; 
                        }
                        if (($parametros["tipo_din_$i"] == "14")){//verifico si hay 
                            $tiene_cargo++; 
                        }
                    }
                }  
                //validamos 1 solo arbol Org y si hay campo Cargo
                if($tiene_cargo>=1){
                    if($tiene_arbol<1){
                        $objResponse->addScriptCall('VerMensaje','error','Debe agregar un campo "Arbol Organizacional" si define un campo "Cargo"');
                        $objResponse->addScript("$('#MustraCargando').hide();"); 
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar y Notificar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");                        
                        return $objResponse;
                    }
                }
                if($tiene_arbol>1){
                    $objResponse->addScriptCall('VerMensaje','error','No puede definir mas de un campo "Arbol Organizacional"');
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar y Notificar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");                    
                    return $objResponse;
                }  
                //FIN DE NUEVA VALIDACION DE ARBOL Y CARGO
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
                    if (!isset($parametros[publico])) $parametros[publico] = 'N';
                    //if (!isset($parametros[formulario])) $parametros[formulario] = 'N';
                    $parametros[formulario] = 'N';
                    for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["nombre_din_$i"])){   
                                $parametros[formulario] = 'S';
                                break;
                            }
                    }

                    if($parametros[formulario]=='N' && $_SESSION[ParamAdic]=='formulario'){
                        $objResponse->addScriptCall('VerMensaje','error','Debe agregar al menos un Parámetros para Indexación de Registros');
                        $objResponse->addScript("$('#MustraCargando').hide();"); 
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar y Notificar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");                        
                        return $objResponse;
                    }
                    
                    if($parametros["id_workflow_documento"]!=''){
                        import("clases.workflow_documentos.WorkflowDocumentos");
                        $wf = new WorkflowDocumentos();
                        $datoswf = $wf->verWorkflowDocumentos($parametros["id_workflow_documento"]);
                        //reviso,elaboro,aprobo
                        $parametros['reviso']=$datoswf['id_personal_revisa'];
                        $parametros['elaboro']=$datoswf['id_personal_responsable'];
                        $parametros['aprobo']=$datoswf['id_personal_aprueba'];
                        
                    }
                    $respuesta = $this->ingresarDocumentos($parametros,$archivo,$doc_vis);

                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        $parametros[id] = $respuesta;
                        
                        if(!class_exists('ArchivosAdjuntos')){
                            import("clases.utilidades.ArchivosAdjuntos");
                        }
                        $adjuntos = new ArchivosAdjuntos();                        
                        $parametros[tabla] = 'mos_documentos_anexos';
                        $parametros[clave_foranea] = 'id_documento';
                        $parametros[valor_clave_foranea] = $parametros[id];
                        $adjuntos->guardar($parametros);
                        
                        //ENVIAR EMAIL SI ES GUARDAR Y NOTIFICAR
                        //print_r($parametros);
                        if($parametros['notificar']=='si'){
                            $correowf = $this->verWFemail($parametros[id]);
                            //print_r($correowf);
                            $this->cargar_nombres_columnas();
                            $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                            if($correowf[email]!='' && $correowf[recibe_notificaciones]=='S'){
                               $cuerpo = 'Sr(a). ' .$correowf[nombres]. '<br><br> Usted tiene una notificación de un documento "'.$etapa.'"<br>';
                               if($correowf[etapa_workflow]=='estado_pendiente_revision'){
                                   $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_responsable].'.';
                               }else if($correowf[etapa_workflow]=='estado_pendiente_aprobacion'){
                                   $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_responsable].'. &rarr;';
                                   $anexo_adicional .='<br><strong>'. $this->nombres_columnas['reviso'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_revisa].'. &rarr;';
                               }
                               $cuerpo .= $anexo_adicional.'<br>'.APPLICATION_ROOT; 
                               $asunto = 'Documento '. $etapa . ': ' . $parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'. str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                              //$correowf[email] = 'azambrano75@gmail.com';
                               $nombres = $correowf[nombres];
                               $ut_tool = new ut_Tool();
                               //SE ENVIA EL CORREO
                               $ut_tool->EnviarEMail('Notificaciones Mosaikus', array(array('correo' => $correowf[email], 'nombres'=>$nombres)), $asunto, $cuerpo);
                           }
                            //SE CARGA LA NOTIFICACION
                                import('clases.notificaciones.Notificaciones');
                                $noti = new Notificaciones();
                                $atr[cuerpo] .=$parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'. str_pad($parametros["version"], 2, "0", STR_PAD_LEFT).'<br>';
                                //if($correowf[etapa_workflow]=='estado_pendiente_revision') $atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su revision<br>';
                                //if($correowf[etapa_workflow]=='estado_pendiente_aprobacion') $atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su aprobacion<br>';
                                $atr[funcion] = "verWorkFlowPopup(".$parametros[id].");";

                                $atr[modulo]='DOCUMENTOS';
                                $atr[asunto]='Tiene un documento '.$etapa.'';
                                $atr[email]=$correowf[email];
                                $atr[id_entidad]=$parametros[id];
                                $mensaje=$noti->ingresarNotificaciones($atr);

                        }                        
                        if(!class_exists('Parametros')){
                            import("clases.parametros.Parametros");
                        }
                        $campos_dinamicos = new Parametros();
                        if($_SESSION[ParamAdic]=='formulario') 
                            $campos_dinamicos->guardar_parametros_dinamicos($parametros, 15);
                        else
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
                                //if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9" )||($params[tipo] == "13" )){
                                if (($params[tipo] == "13" )){  
                                    $params[valores] = str_replace("\n", "<br />", $parametros["valores_din_$i"]); 
                                }
                                else $params[valores] = '';
                                 
                                
                                //echo $parametros["cuerpo_$i"];
                                $id_unico = $this->ingresarCamposFormulario($params);
                                if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9" )){
                                    $sql = 'INSERT INTO mos_documentos_formulario_items(fk_id_unico, descripcion, vigencia, tipo)'
                                            . ' SELECT ' . $id_unico . ', descripcion, vigencia, '.$params[tipo] . ' '
                                            . ' FROM mos_documentos_formulario_items_temp '
                                            . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                            . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    
                                }
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
                        $( '#btn-guardar' ).prop( 'disabled', false );
                        $('#btn-guardar-not' ).html('Guardar y Notificar');
                        $( '#btn-guardar-not' ).prop( 'disabled', false );");      
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
                $val = $this->verDocumentos($parametros['id']);
                $validator = new FormValidator();
                //print_r($val);
                if(($val["etapa_workflow"]=='estado_pendiente_revision') || ($val["etapa_workflow"]=='estado_pendiente_aprobacion')){
                    $mensaje='No se puede crear una versi&oacute;n del documento porque tiene Flujo de Trabajo activo';
                    $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                            $( '#btn-guardar' ).prop( 'disabled', false );");
                    return $objResponse;                    
                }
                //die;
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

                    if($parametros["id_workflow_documento"]!=''){
                        import("clases.workflow_documentos.WorkflowDocumentos");
                        $wf = new WorkflowDocumentos();
                        $datoswf = $wf->verWorkflowDocumentos($parametros["id_workflow_documento"]);
                        //reviso,elaboro,aprobo
                        $parametros['reviso']=$datoswf['id_personal_revisa'];
                        $parametros['elaboro']=$datoswf['id_personal_responsable'];
                        $parametros['aprobo']=$datoswf['id_personal_aprueba'];
                        
                    }
                  //  print_r($parametros);
                    if($_SESSION[ParamAdic]=='formulario') {
                        $cod_categoria = 15;
                        $parametros[cod_categoria]=15;
                    }
                    else{
                        $cod_categoria = 1;
                        $parametros[cod_categoria]=1;
                    }                    
                    $respuesta = $this->ingresarDocumentosVersion($parametros,$archivo,$doc_vis);
                   // echo($respuesta);
       
                    
                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        if (count($this->parametros) <= 0){
                            $this->cargar_parametros($cod_categoria);
                        }                                                                
                        foreach ($this->parametros as $value) {                    
                            $params[cod_parametro_det] = $parametros["cmb-".$value[cod_parametro]];
                            $params[cod_parametro] = $value[cod_parametro];
                            $params[id_registro] = $respuesta;
                            if($_SESSION[ParamAdic]=='formulario') {
                                $params[cod_categoria]=15;
                            }
                            else{
                                $params[cod_categoria]==1;
                            }
                            if (strlen($params[cod_parametro_det])>0)
                                $this->ingresarParametro($params);
                            //$this->ingresarParametro($params);
                        }
                        //ENVIAR EMAIL SI ES GUARDAR Y NOTIFICAR
                        if($parametros['notificar']=='si'){
                            $correowf = $this->verWFemail($params[id_registro]);
                            $this->cargar_nombres_columnas();
                            $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                            if($correowf[email]!='' && $correowf[recibe_notificaciones]=='S'){
                                $cuerpo = 'Usted tiene una notificación de un documento "'.$etapa.'"<br>';
                                //$correowf[email] = 'azambrano75@gmail.com';
                                $cuerpo .= '<br>'.APPLICATION_ROOT;
                                $nombres = $correowf[nombres];
                                $ut_tool = new ut_Tool();
                                $ut_tool->EnviarEMail('Notificaciones Mosaikus', array(array('correo' => $correowf[email], 'nombres'=>$nombres)), 'Notificaciones de Flujo de Trabajo', $cuerpo);
                            } 
                            //SE CARGA LA NOTIFICACION
                                import('clases.notificaciones.Notificaciones');
                                $noti = new Notificaciones();
                                $atr[cuerpo] .=$parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'. str_pad($parametros["version"], 2, "0", STR_PAD_LEFT).'<br>';
                                //if($correowf[etapa_workflow]=='estado_pendiente_revision') $atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su revision<br>';
                                //if($correowf[etapa_workflow]=='estado_pendiente_aprobacion') $atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su aprobacion<br>';
                                $atr[funcion] = "verWorkFlowPopup(".$params[id_registro].");";                                
                                $atr[modulo]='DOCUMENTOS';
                                $atr[asunto]='Tiene un documento '.$etapa.'';
                                $atr[email]=$correowf[email];
                                $atr[id_entidad]=$params[id_registro];
                                $mensaje=$noti->ingresarNotificaciones($atr);
                            
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

                $val = $this->verDocumentos($parametros['id']);
                $validator = new FormValidator();
                //print_r($val);
                if(($val["etapa_workflow"]=='estado_pendiente_revision') || ($val["etapa_workflow"]=='estado_pendiente_aprobacion')){
                    $mensaje='No se puede crear una revisi&oacute;n del documento porque tiene Flujo de Trabajo activo';
                    $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                            $( '#btn-guardar' ).prop( 'disabled', false );");
                    return $objResponse;                    
                }
                
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
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $parametros[opcion] = 'simple';
                // aqui tienes que cargar los id asociados al documento, tienes que armar el array simple, ejemplo array(15,45,78)
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verDocumentos($parametros[id]);
                $organizacion = array();
                if(strpos($val[id_organizacion],',')){    
                        $organizacion = explode(",", $val[id_organizacion]);
                    }
                    else{
                        $organizacion[] = $val[id_organizacion];                    
                    }
                $parametros[nodos_seleccionados] = $organizacion;
                $contenido_1[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(0,$parametros);

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
                $parametros['email_usuario']= $_SESSION['CookEmail'];
                $parametros[operacion]='editar';
                $contenido_1[DOCUMENTOS_RELACIONADOS]=$this->listarDocumentosComboRelacion($parametros);
                
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
                $contenido_1[CHECKED_ACTUALIZACION_ACTIVA] = $val["actualizacion_activa"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_VIGENCIA] = $val["vigencia"] == 'S' ? 'checked="checked"' : '';
                $contenido_1[CHECKED_PUBLICO] = $val["publico"] == 'S' ? 'checked="checked"' : '';
                $contenido_1['SEMAFORO'] = $ut_tool->combo_array("semaforo", $desc, $ids,false,$val["semaforo"],false,false,false,false,'display:inline;width:70px');

                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=48;$i++){                    
                    $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);                    
                    $ids[] = $i;
                }
               // echo $_SESSION['CookCodEmp'].'-'.$_SESSION[SuperUser];
                $contenido_1["N_ELABORO"] .= ' &#8594;';
                $contenido_1['V_MESES'] = $ut_tool->combo_array("v_meses", $desc, $ids,false,$val["v_meses"],false,false,false,false,'display:inline;width:70px');
                if($_SESSION[SuperUser]=='S'){
                    $sql="SELECT wf.id,
                            CONCAT(CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno))
                            ,' &rarr; ', 
                           IF(perso_revisa.nombres is null,CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno)),CONCAT(initcap(SUBSTR(perso_revisa.nombres,1,IF(LOCATE(' ' ,perso_revisa.nombres,1)=0,LENGTH(perso_revisa.nombres),LOCATE(' ' ,perso_revisa.nombres,1)-1))),' ',initcap(perso_revisa.apellido_paterno))	) 
                           ,' &rarr; ', 
                            CONCAT(initcap(SUBSTR(perso_aprueba.nombres,1,IF(LOCATE(' ' ,perso_aprueba.nombres,1)=0,LENGTH(perso_aprueba.nombres),LOCATE(' ' ,perso_aprueba.nombres,1)-1))),' ',initcap(perso_aprueba.apellido_paterno))) as wf
                            FROM mos_workflow_documentos AS wf
                            left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
                            left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                            INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp";
                }
                else
                {
                    $sql="SELECT  wf.id,CONCAT(CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno))
                            ,' &rarr; ', 
                           IF(perso_revisa.nombres is null,CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno)),CONCAT(initcap(SUBSTR(perso_revisa.nombres,1,IF(LOCATE(' ' ,perso_revisa.nombres,1)=0,LENGTH(perso_revisa.nombres),LOCATE(' ' ,perso_revisa.nombres,1)-1))),' ',initcap(perso_revisa.apellido_paterno))	) 
                           ,' &rarr; ', 
                            CONCAT(initcap(SUBSTR(perso_aprueba.nombres,1,IF(LOCATE(' ' ,perso_aprueba.nombres,1)=0,LENGTH(perso_aprueba.nombres),LOCATE(' ' ,perso_aprueba.nombres,1)-1))),' ',initcap(perso_aprueba.apellido_paterno))) as wf
                            FROM mos_workflow_documentos AS wf
                            left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
                            left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                            INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp
                    WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."' or wf.id_personal_revisa='".$_SESSION['CookCodEmp']."')";
                    
                }
                //echo $sql;
                $contenido_1['ID_WORKFLOW_DOCUMENTO'] = $ut_tool->OptionsCombo($sql
                                                                    , 'id'
                                                                    , 'wf', $val['id_workflow_documento']);
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
                if($_SESSION[ParamAdic]=='formulario') 
                    $array = $campos_dinamicos->crear_campos_dinamicos(15,$val[IDDoc],6,14);
                else
                    $array = $campos_dinamicos->crear_campos_dinamicos(1,$val[IDDoc],6,14);
                
                
                $contenido_1[OTROS_CAMPOS] = $array[html];
                $js_din = $array[js];
                $template = new Template();
                $js_cambiar_archivos = '';
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if ((($val[estado_workflow] != '') && ($val[estado_workflow] != 'RECHAZADO')) ||($val[etapa_workflow] == 'estado_aprobado') ){
                    $contenido_1['DOC_VIS'] ='<div class="form-group" id="info_archivo_adjunto_vis">
                                    <label for="archivo" class="col-md-4 control-label">'. $contenido_1[N_NOM_VISUALIZA].'</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" style="">                                            
                                            <input type="text" class="form-control" style="display: inline;" readonly="readonly" id="info_nombre_vis" value="'.$val["nom_visualiza"].'">                                             
                                        </p>                      
                                  </div>  
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.PDF</span>
                             </div>';
                    $contenido_1['DOC_FUENTE'] = '<div class="form-group" id="info_archivo_adjunto">
                                    <label for="archivo" class="col-md-4 control-label">'. $contenido_1[N_DOC_FISICO].'</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" style="">
                                            <input type="text" class="form-control" style="display: inline;" id="info_nombre" readonly="readonly" name="info_nombre" value="'.$contenido_1[NOMBRE_DOC_AUX].'">
                                        </p>                      
                                  </div>         
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>';
                }  else {
                    if ((strlen($val["nom_visualiza"])>0)){
                        $contenido_1[NOMBRE_DOC_VIS] = $contenido_1[NOMBRE_DOC_AUX]; 
                        $template->setTemplate("cargar_doc_vis");
                        $template->setVars($contenido_1);
                        $contenido_1['DOC_VIS'] = $template->show();
                        $js_cambiar_archivos = "$('#tabla_fileUpload_vis').hide();$('#info_archivo_adjunto_vis').show();";
                    }
                    else{
                        $template->setTemplate("cargar_doc_vis");
                        $template->setVars($contenido_1);
                        $contenido_1['DOC_VIS'] = $template->show();
                    }
                    
                    $template->setTemplate("cargar_doc_fuente");
                    $template->setVars($contenido_1);
                    $contenido_1['DOC_FUENTE'] = $template->show();
                }
                
                $this->listarCamposFormulario($parametros);
                $data=$this->dbl->data;
                //print_r($data);
                $item = "";
                $js = "";
                $i = 0;
                $contenido_1['TOK_NEW'] = time();// quitar el rut
                $ids = array('7','8','9','1','2','3','5','6','10','11','12','13','14');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo', 'Árbol Organizacional', 'Árbol Procesos','Vigencia','Cargo');
                
                //$ids = array('7','8','9','1','2','3','5','6','10');
                //$desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona','Semáforo');
                foreach ($data as $value) {                          
                    $i++;
                    //echo $i;
                    /*CARGA de items temporal para campos combo, seleccion simple y multiple*/
                    if (($value[tipo] == 7) || ($value[tipo] == 8) || ($value[tipo] == 9)){
                            $sql = "INSERT INTO mos_documentos_formulario_items_temp(fk_id_unico, descripcion, vigencia, tipo, fk_id_item, id_usuario, tok, estado)"
                                    . " SELECT fk_id_unico, descripcion, vigencia, tipo, id, $_SESSION[CookIdUsuario],$contenido_1[TOK_NEW],0 "
                                    . " FROM mos_documentos_formulario_items"
                                    . " WHERE fk_id_unico = $value[id_unico] ";
                            $this->dbl->insert_update($sql);
                            $sql = "SELECT 
                                    descripcion
                                    ,vigencia
                                    
                                     
                            FROM mos_documentos_formulario_items_temp 
                            WHERE tok = $contenido_1[TOK_NEW] and id_usuario = $_SESSION[CookIdUsuario] and fk_id_unico = $value[id_unico] ORDER BY descripcion";
                            $data_items = $this->dbl->query($sql);
                            $value[valores] = '';
                            foreach ($data_items as $value_items) {
                                $value[valores] .= $value_items[descripcion] . '<br />';                                
                                
                            }
                            $value[valores] = substr($value[valores], 0, strlen($value[valores])-6);
                            
                        }
                    $item = $item. '<tr id="tr-esp-' . $i . '">';                      
                    
                    if (($value[cant]*1 > 0) || ($value[cant_2]*1 > 0)){
                        $item = $item. '<td align="center">'.
                                '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
                                <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>' .
                                '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id_unico].'" type="hidden" >'.
                                '<input id="cmb_din_'. $i . '" type="hidden" name="cmb_din_'. $i . '" tok="' . $i . '" value="'.$value[id_unico].'">'.
                                '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.$value[orden].'" type="hidden" >'.
                                            '&nbsp;' .                                             
                                       '  </td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input id="nombre_din_'. $i . '" value="'.$value[Nombre].'"  class="form-control" type="text" size="15" name="nombre_din_'. $i . '">'.
                                        '</td>';
                         
                         $item = $item. '<td>' .                                            
                                            '<input value="'.$desc[array_search($value["tipo"],$ids)].'" readonly="" class="form-control" type="text" size="15" >'.
                                            '<input id="tipo_din_'. $i . '" name="tipo_din_'. $i . '" value="'.$value["tipo"].'" readonly="" class="form-control" type="hidden" size="15" >'.
                                         '</td>';
//                         $item = $item.  '<td>' .
//                           ' <textarea cols="30" id="valores_din_'. $i . '" name="valores_din_'. $i . '" rows="2" readonly="">'. str_replace("<br />", "<br>", $value[valores]) .'</textarea>'.
//                        '</td>';
                         $item = $item.  '<td>' .
                                            ' <textarea id="valores_din_'. $i . '" cols="30" rows="2" name="valores_din_'. $i . '" readonly="" class="form-control">'. str_replace("<br />", "<br>", $value[valores]) .'</textarea>'.
                                         '</td>';
                         $item = $item. '<td>' .
                                            '<i class="icon icon-more cursor-pointer" style="display:none;" id="ico_cmb_din_'. $i . '" tok="'. $i .'"></i>'.
                                         '</td>';
                        $js .= '$("#ico_cmb_din_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("tok");            
                                    array = new XArray();
                                    array.setObjeto("ItemsFormulario","indexItemsFormulario");
                                    array.addParametro("tok",id);
                                    array.addParametro("id",$("#cmb_din_"+id).val());
                                    array.addParametro("titulo",$("#nombre_din_"+id).val());
                                    array.addParametro("token", $("#tok_new_edit").val());
                                    array.addParametro("import","clases.items_formulario.ItemsFormulario");
                                    xajax_Loading(array.getArray());
                                }); ';
                        $js .= "actualizar_atributo_dinamico($i);";
                        $item = $item. '</tr>' ;  
                    }else
                    {
                        
                                                                    
                        $item = $item. '<td align="center">'.
                                            ' <a href="' . $i . '"  title="Eliminar " id="eliminar_esp_' . $i . '"> ' . 
                                            //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' . 
                                             '<i class="icon icon-remove" style="width: 18px;"></i>'.
                                             '</a>' .
                                             '<i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i>
                                              <i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'.
                                              
                                              '<input id="id_unico_din_'. $i . '" name="id_unico_din_'. $i . '" value="'.$value[id_unico].'" type="hidden" >'.
                                              '<input id="cmb_din_'. $i . '" type="hidden" name="cmb_din_'. $i . '" tok="' . $i . '" value="'.$value[id_unico].'">'.
                                              '<input id="orden_din_'. $i . '" name="orden_din_'. $i . '" value="'.$value[orden].'" type="hidden" >'.
                                       '  </td>';
                         $item = $item. '<td class="td-table-data">'.
                                             '<input id="nombre_din_'. $i . '" value="'.$value[Nombre].'" class="form-control" type="text" data-validation="required" size="15" maxlength="20" name="nombre_din_'. $i . '">'.
                                        '</td>';
                         $item = $item. '<td>' .                                            
                                            '<input value="'.$desc[array_search($value["tipo"],$ids)].'" readonly="" class="form-control" type="text" size="15" >'.
                                            '<input id="tipo_din_'. $i . '" name="tipo_din_'. $i . '" value="'.$value["tipo"].'" readonly="" class="form-control" type="hidden" size="15" >'.
                                         '</td>';
//                         $item = $item. '<td>' .
//                                            $ut_tool->combo_array("tipo_din_$i", $desc, $ids, false, $value["tipo"],"actualizar_atributo_dinamico($i);")  .
//                                         '</td>';
                         $item = $item.  '<td>' .
                                            ' <textarea id="valores_din_'. $i . '" cols="30" rows="2" name="valores_din_'. $i . '" readonly="" class="form-control">'. str_replace("<br />", "<br>", $value[valores]) .'</textarea>'.
                                         '</td>';
                         $item = $item. '<td>' .
                                            '<i class="icon icon-more cursor-pointer" style="display:none;" id="ico_cmb_din_'. $i . '" tok="'. $i .'"></i>'.
                                         '</td>';
                        
                        
                        $item = $item. '</tr>' ;                    
                        $js .= '$("#eliminar_esp_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("href");  
                                    $("#id_unico_del").val($("#id_unico_del").val() + $("#id_unico_din_"+id).val() + ",");
                                    $("tr-esp-'. $i .'").remove();
                                    var parent = $(this).parents().parents().get(0);
                                        $(parent).remove();
                            });';
                        $js .= '$("#ico_cmb_din_'. $i .'").click(function(e){ 
                                    e.preventDefault();
                                    var id = $(this).attr("tok");            
                                    array = new XArray();
                                    array.setObjeto("ItemsFormulario","indexItemsFormulario");
                                    array.addParametro("tok",id);
                                    array.addParametro("id",$("#cmb_din_"+id).val());
                                    array.addParametro("titulo",$("#nombre_din_"+id).val());
                                    array.addParametro("token", $("#tok_new_edit").val());
                                    array.addParametro("import","clases.items_formulario.ItemsFormulario");
                                    xajax_Loading(array.getArray());
                                }); ';
                        $js .= "actualizar_atributo_dinamico($i);";
                        
                    }
                }               
                $contenido_1['ITEMS_ESP'] = $item;
                $contenido_1['NUM_ITEMS_ESP'] = $i;
                $sql = "SELECT fecha_registro f1,DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%i')fecha_registro, descripcion_operacion, "
                        . "CONCAT(initcap(SUBSTR(user.nombres,1,IF(LOCATE(' ' ,user.nombres,1)=0,LENGTH(user.nombres),LOCATE(' ' ,user.nombres,1)-1))),' ',initcap(user.apellido_paterno)) usuario "
                        . "FROM mos_historico_wf_documentos wf inner join mos_usuario user "
                        . " on wf.id_usuario = user.id_usuario "
                        . " WHERE IDDoc = $parametros[id] order by f1 desc";
                //echo $sql;
                $historia = $this->dbl->query($sql, array());
                foreach ($historia as $value) {
                    $item_histo .="<tr>";
                    $item_histo .="<td>".$value[fecha_registro]."</td>";
                    $item_histo .="<td>".$value[descripcion_operacion]."</td>";
                    $item_histo .="<td>".$value[usuario]."</td>";
                    $item_histo .="</tr>";
                }
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();
                $array_nuevo = $adjuntos->crear_archivos_adjuntos('mos_documentos_anexos', 'id_documento',$val["IDDoc"],18);
                $contenido_1[ARCHIVOS_ADJUNTOS] = $array_nuevo[html];
                $js .= $array_nuevo[js];
                
                $contenido_1['ITEMS_HISTO'] = $item_histo;
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("formulario_editar");
                //$template->setVars($contenido_1);
                //print_r($val);
                if($val["etapa_workflow"]!='' && $val["estado_workflow"]=='OK')
                    $contenido_1['COMBOWFHABILITADO'] = ' disabled ';
                if($val["etapa_workflow"]=='' && $val["estado_workflow"]=='')
                    $contenido_1['VERNOTIFICAR'] = '';
                else{
                    if($val["etapa_workflow"]=='estado_pendiente_revision' && $val["estado_workflow"]=='RECHAZADO' && $val["email_responsable"]==$_SESSION['CookEmail'])
                        $contenido_1['VERNOTIFICAR'] = '';
                    else
                        $contenido_1['VERNOTIFICAR'] = "style='display:none;'";
                }
                $contenido_1['ETAPA'] = $val["etapa_workflow"];
                //$contenido['CAMPOS'] = $template->show();

                //$template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                //$template->setTemplate("formulario");

                $contenido_1['TITULO_FORMULARIO'] = "Formulario Editar";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Documentos";
                $contenido_1['PAGINA_VOLVER'] = "listarDocumentos.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['DESC_OPERACION_NOTIFICAR'] = "Enviar a Revisión";//"Guardar y Notificar";
                $contenido_1['OPC'] = "upd";
                $contenido_1['ID'] = $val["IDDoc"];

                $js .= "$('#vigencia').on('change', function (event) {
                                 if( $(this).is(':checked') ){
                                   $('#observacion_vigencia').val(''); 
                                } else {
                                    if($('#etapa').val()=='estado_aprobado'){
                                        $('#myModal-observacion-vigencia').modal('show');
                                    }
                                }
                            });
                            ";                
                
                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");    
                $objResponse->addScriptCall('cargar_autocompletado');
                
                $objResponse->addScript("$('#requiere_lista_distribucion').val('".$val["requiere_lista_distribucion"]."');");
               // $objResponse->addScriptCall('CargaComboCargoEditar',$val["requiere_lista_distribucion"],$val["IDDoc"],$organizacion);
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript('ao_multiple();');
                $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
                if($_SESSION[ParamAdic]!='formulario')
                {
                    $objResponse->addScript("$($('#tabs-hv-2').find('#li2')).hide();");
                }
                $objResponse->addScript("$('#documento_relacionado').selectpicker({
                                                style: 'btn-combo'
                                              });$js");
                
                //echo $_SESSION[ParamAdic];
                $objResponse->addScript("$js");
                $objResponse->addScript("$jswf");
                $objResponse->addScript("$js_din");
                $objResponse->addScript($js_cambiar_archivos);
                //$objResponse->addScript("$('#fecha').datepicker();");
                return $objResponse;
            }
     
 
            public function actualizar($parametros)
            {   //print_r($parametros);
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                unset ($parametros['opc']);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];
                
                //NUEVA VALIDACION DE ARBOL Y CARGO
                $validator = new FormValidator();
                $tiene_arbol=0;
                $tiene_cargo=0;
                for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                    if (isset($parametros["nombre_din_$i"])){                                
                        if (($parametros["tipo_din_$i"] == "11")){//cuento cuantos arboles organizacionales hay
                            $tiene_arbol++; 
                        }
                        if (($parametros["tipo_din_$i"] == "14")){//verifico si hay 
                            $tiene_cargo++; 
                        }
                    }
                }  
                //validamos 1 solo arbol Org y si hay campo Cargo
                //echo $parametros['observacion_vigencia'];
                if($tiene_cargo>=1){
                    if($tiene_arbol<1){
                        $objResponse->addScriptCall('VerMensaje','error','Debe agregar un campo "Arbol Organizacional" si define un campo "Cargo"');
                        $objResponse->addScript("$('#MustraCargando').hide();"); 
                        $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                        $( '#btn-guardar' ).prop( 'disabled', false );");                        
                        return $objResponse;
                    }
                }
                if($tiene_arbol>1){
                    $objResponse->addScriptCall('VerMensaje','error','No puede definir mas de un campo "Arbol Organizacional"');
                    $objResponse->addScript("$('#MustraCargando').hide();"); 
                    $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                    $( '#btn-guardar' ).prop( 'disabled', false );");                     
                    return $objResponse;
                }  
                //FIN DE NUEVA VALIDACION DE ARBOL Y CARGO                
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
                    //print_r($parametros);
                    //exit();
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            
                            $parametros[contentType] = $parametros[tipo_doc];//'application/pdf';                                                                
                           
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

                    if($parametros['vigencia']=='N' && $parametros['etapa']=='estado_aprobado' && $parametros['observacion_vigencia']==''){
                            $objResponse->addScriptCall('VerMensaje','error','Debe cargar un motivo de la no vigencia del documento');
                            $objResponse->addScript("$('#MustraCargando').hide();"); 
                            $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                            $( '#btn-guardar' ).prop( 'disabled', false );");                        
                            return $objResponse;                    
                    }
                    
                    //if (!isset($parametros[formulario])) $parametros[formulario] = 'N';
                    $parametros[formulario] = 'N';
                    for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (isset($parametros["valores_din_$i"])){   
                                $parametros[formulario] = 'S';
                                break;
                            }
                    }
                    if($parametros["id_workflow_documento"]!=''){
                        import("clases.workflow_documentos.WorkflowDocumentos");
                        $wf = new WorkflowDocumentos();
                        $datoswf = $wf->verWorkflowDocumentos($parametros["id_workflow_documento"]);
                        //reviso,elaboro,aprobo
                        $parametros['reviso']=$datoswf['id_personal_revisa'];
                        $parametros['elaboro']=$datoswf['id_personal_responsable'];
                        $parametros['aprobo']=$datoswf['id_personal_aprueba'];
                    }        
                    
                    $respuesta = $this->modificarDocumentos($parametros,$archivo,$doc_vis);

                    if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {  
                        /* EVIDENCIAS ADJUNTADAS*/
                        if(!class_exists('ArchivosAdjuntos')){
                            import("clases.utilidades.ArchivosAdjuntos");
                        }
                        $adjuntos = new ArchivosAdjuntos();                        
                        $parametros[tabla] = 'mos_documentos_anexos';
                        $parametros[clave_foranea] = 'id_documento';
                        $parametros[valor_clave_foranea] = $parametros[id];
                        //print_r($parametros);
                        $adjuntos->guardar($parametros);
                        
                        //print_r($parametros);
                        //print_r($parametros[nodos]);
                        //ENVIAR EMAIL SI DESMARCA LA VIGENCIA Y ESTA APROBADO
                        if($parametros['vigencia']=='N' && $parametros['etapa']=='estado_aprobado'){
                            //$doc = $this->verDocumentos($parametros[id]);
                            import("clases.usuarios.Usuarios");
                            $usr = new Usuarios();
                            $datos = $usr->verUsuario($parametros['id_usuario']);
                            $correowf = $this->verWFemail($parametros[id]); 
                            $asunto = 'Documento enviado a Histórico: ' . $parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'. str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                            $cuerpo = '<strong>Documento enviado a Hist&oacute;rico:</strong> ' . $parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'. str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                            $cuerpo .= '<br><br><strong>Motivo:</strong>&nbsp;'.$parametros[observacion_vigencia];
                            $cuerpo .= '<br><br><strong>Responsable:</strong>&nbsp;'.ucwords($datos[nombres]).' '.ucwords($datos[apellido_paterno]);
                            $correos = array(array('correo' => $correowf[email_revisa], 'nombres'=>ucwords($correowf[nombre_revisa]) ),
                                array('correo' => $correowf[email_reponsable], 'nombres'=>$correowf[nombre_responsable]),
                                array('correo' => $correowf[email_aprueba], 'nombres'=>$correowf[nombre_aprueba]));
                            //$correowf[email_aprueba]
                            $ut_tool = new ut_Tool();
                            //$ut_tool->EnviarEMail('Notificaciones Mosaikus', $correos, $asunto, $cuerpo);
                            //echo $asunto.$cuerpo;
                            //print_r($correos);
                        }
                        //ENVIAR EMAIL SI ES GUARDAR Y NOTIFICAR
                        if($parametros['notificar']=='si'){
                            $correowf = $this->verWFemail($parametros[id]);
                            //print_r($correowf);
                            $this->cargar_nombres_columnas();
                            $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                             if($correowf[email]!=''&& $correowf[recibe_notificaciones]=='S'){
                               $cuerpo = 'Sr(a). ' .$correowf[nombres]. '<br><br> Usted tiene una notificación de un documento "'.$etapa.'"<br>';
                               $asunto = 'Documento '. $etapa . ': ' . $parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'. str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                               //$correowf[email] = 'azambrano75@gmail.com';
                               if($correowf[etapa_workflow]=='estado_pendiente_revision'){
                                   $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_responsable].'.<br>';
                               }else if($correowf[etapa_workflow]=='estado_pendiente_aprobacion'){
                                   $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_responsable].' &rarr;';
                                   $anexo_adicional .='<strong>'. $this->nombres_columnas['reviso'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_revisa].'.<br>';
                               }
                               $cuerpo .= $anexo_adicional.'<br>'.APPLICATION_ROOT; 
                               //echo $cuerpo;
                               $nombres = $correowf[nombres];
                               $ut_tool = new ut_Tool();
                               $ut_tool->EnviarEMail('Notificaciones Mosaikus', array(array('correo' => $correowf[email], 'nombres'=>$nombres)), $asunto, $cuerpo);
                           }
                            //SE CARGA LA NOTIFICACION
                                import('clases.notificaciones.Notificaciones');
                                $noti = new Notificaciones();
                                $atr[cuerpo] .=$parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'. str_pad($parametros["version"], 2, "0", STR_PAD_LEFT).'<br>';
                                //if($correowf[etapa_workflow]=='estado_pendiente_revision') $atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su revision<br>';
                                //if($correowf[etapa_workflow]=='estado_pendiente_aprobacion') $atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su aprobacion<br>';
                                $atr[funcion] = "verWorkFlowPopup(".$parametros[id].");";
                                $atr[modulo]='DOCUMENTOS';
                                $atr[asunto]='Tiene un documento '.$etapa.'';
                                $atr[email]=$correowf[email];
                                $atr[id_entidad]=$parametros[id];
                                $mensaje=$noti->ingresarNotificaciones($atr);
                            
                        }
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
                        if($_SESSION[ParamAdic]=='formulario') 
                            $campos_dinamicos->guardar_parametros_dinamicos($parametros, 15);
                        else
                            $campos_dinamicos->guardar_parametros_dinamicos($parametros, 1);
                        
                        
                        if (strlen($parametros[id_unico_del])>0){
                            $parametros[id_unico_del] = substr($parametros[id_unico_del], 0, strlen($parametros[id_unico_del]) - 1);
                            $sql = "DELETE FROM mos_documentos_datos_formulario WHERE id_unico IN ($parametros[id_unico_del]) "
                                . " AND NOT id_unico IN (SELECT id_unico FROM mos_registro_formulario WHERE IDDoc = $parametros[id]) ";                               
                            $this->dbl->insert_update($sql);
                        }

                        $params[IDDoc] = $parametros[id];
                        for($i=1;$i <= $parametros[num_items_esp] * 1; $i++){                              
                            //echo $parametros["nro_pts_$i"];
                            if (!isset($parametros["id_unico_din_$i"])){                                
                                //$atr[IDDoc],'$atr[nombre]','$atr[tipo]','$atr[valores]'
                                $params[nombre] = $parametros["nombre_din_$i"];
                                $params[tipo] = $parametros["tipo_din_$i"];                                
                                $params[orden] = $parametros["orden_din_$i"];  
                                //if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9")||($params[tipo] == "13" )){
                                if (($params[tipo] == "13" )){ 
                                    $params[valores] = str_replace("\n", "<br />", $parametros["valores_din_$i"]); 
                                }
                                else $params[valores] = '';
                                 
                                
                                //echo $parametros["cuerpo_$i"];
                                //$this->ingresarCamposFormulario($params);
                                
                                $id_unico = $this->ingresarCamposFormulario($params);
                                if (($params[tipo] == "7")||($params[tipo] == "8")||($params[tipo] == "9" )){
                                    $sql = 'INSERT INTO mos_documentos_formulario_items(fk_id_unico, descripcion, vigencia, tipo)'
                                            . ' SELECT ' . $id_unico . ', descripcion, vigencia, '.$params[tipo] . ' '
                                            . ' FROM mos_documentos_formulario_items_temp '
                                            . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                            . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    
                                }
                            }
                            else
                                //if (isset($parametros["valores_din_$i"]))
                                { 
                                    $params[orden] = $parametros["orden_din_$i"];  
                                    $params[nombre] = $parametros["nombre_din_$i"];
                                    $params[tipo] = $parametros["tipo_din_$i"];                                
                                    $params[orden] = $parametros["orden_din_$i"]; 
                                    $params[id_unico] = $parametros["id_unico_din_$i"];  
                                    if (($params[tipo] == "13" )){ 
                                        $params[valores] = str_replace("\n", "<br />", $parametros["valores_din_$i"]); 
                                    }
                                    else $params[valores] = '';
                                    $this->actualizarCamposFormulario($params);
                                    $sql = 'INSERT INTO mos_documentos_formulario_items(fk_id_unico, descripcion, vigencia, tipo)'
                                            . ' SELECT ' . $params[id_unico] . ', descripcion, vigencia, '.$params[tipo] . ' '
                                            . ' FROM mos_documentos_formulario_items_temp '
                                            . ' WHERE tok = ' . $parametros[tok_new_edit] . ' AND id_usuario = ' . $_SESSION['CookIdUsuario'] . ' '
                                            . ' AND fk_id_unico = ' . $parametros["cmb_din_$i"] . ' AND estado = 1';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    $sql = 'update mos_documentos_formulario_items,mos_documentos_formulario_items_temp
                                            set mos_documentos_formulario_items.descripcion = mos_documentos_formulario_items_temp.descripcion,
                                            mos_documentos_formulario_items.vigencia = mos_documentos_formulario_items_temp.vigencia
                                            where id_usuario = ' . $_SESSION['CookIdUsuario'] . ' and tok = ' . $parametros[tok_new_edit] . ' and mos_documentos_formulario_items_temp.fk_id_unico = ' . $parametros["cmb_din_$i"] . ' and mos_documentos_formulario_items.id = mos_documentos_formulario_items_temp.fk_id_item and estado = 2';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    $sql = 'delete from mos_documentos_formulario_items
                                            where id in (select fk_id_item from mos_documentos_formulario_items_temp 
                                            where id_usuario = ' . $_SESSION['CookIdUsuario'] . ' and tok = ' . $parametros[tok_new_edit] . ' and fk_id_unico = '. $params[id_unico] .' and estado = 3)
                                            and 0 = (SELECT count(*) from mos_registro_item where id_unico = fk_id_unico and tipo = mos_documentos_formulario_items.tipo and id = valor)';
                                    //echo $sql;
                                    $this->dbl->insert_update($sql);
                                    
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
                if($_SESSION[ParamAdic]=='formulario') {
                    $parametros[cod_categoria]=15;
                }
                else{
                    $parametros[cod_categoria]==1;
                }
                //$val = $this->verDocumentos($parametros[id]);
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
            {   $parametros['email_usuario']= $_SESSION['CookEmail'];
                //print_r($parametros);
                $parametros[terceros] = 'S';
                if($_SESSION[ParamAdic]!='formulario') 
                    $parametros[mostrar-col] = str_replace('-12','',$parametros[mostrar-col]);
                    //$parametros['mostrar-col']="2-3-4-5-7-8-9-12-14-17-20-21";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
               // else
                   // $parametros['mostrar-col']="2-3-4-5-7-8-9-14-17-20-21";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                
                $this->get_min_nivel_area($parametros);
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
                    $iframe = '<iframe id="iframe-vis" src="'.$ruta_doc.'" style="height:90%;width:100%;min-height:600px;" frameborder="0"></iframe>';
                    $accion = "Visualizó documento PDF";
                    $this->registraTransaccionLog(89,$accion,'', $parametros[id]);                    
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
                    $iframe = '<iframe id="iframe-vis" src="'.$http.'://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;min-height:600px;" frameborder="0"></iframe>';
                    $accion = "Visualizó documento Fuente";
                    $this->registraTransaccionLog(89,$accion,'', $parametros[id]);                    
                    
                }
                $html_registro = '';
                if ($archivo_aux[formulario]== 'S'){
                    $html_registro = " <li> <a id=\"a-ver-registros\" title=\"Ver Registros\" tok=\"$archivo_aux[IDDoc]\"  href=\"#\">
                            
                            <i class=\"icon icon-more\"></i>
                        </a>  </li>";
                }
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if(!class_exists('ArchivosAdjuntos')){
                    import("clases.utilidades.ArchivosAdjuntos");
                }
                $adjuntos = new ArchivosAdjuntos();
                $array_nuevo = $adjuntos->visualizar_archivos_adjuntos('mos_documentos_anexos', 'id_documento',$parametros["id"],24);
                $contenido_2['ANEXOS'] = $array_nuevo[html];
                $contenido_2['DOCUMENTOSRELACIONADOS'] = $this->verVisualizaDocumentosRelacionados($parametros[id]);
                $js .= $array_nuevo[js];
                //echo $contenido_1['ANEXOS'];
                
                $template = new Template();
                if($contenido_2['ANEXOS']!='' || $contenido_2['DOCUMENTOSRELACIONADOS']!=''){
                    $contenido_2['DOCUMENTOS']=$iframe;//$template->show();
                    $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                    $template->setTemplate("visualizacion_documento_tab");
                    $template->setVars($contenido_2);
                    $contenido_1['DATOS']= $template->show(); 
                    $contenido_1['TITULO']=$titulo_doc;
                    if($contenido_2['ANEXOS']!=''){
                        //$contenido_1['DATOS']= $template->show();     
                        $contenido_1['TITULO'].= "<br><a href=\"#anexos-doc\" title=\"Ver Anexos del Documento\">                            
                                        <i class=\"	glyphicon glyphicon-paperclip\" style=\"\"></i>
                                        Anexos
                                    </a>";
                    }
                    if($contenido_2['DOCUMENTOSRELACIONADOS']!=''){
                        //$contenido_1['DATOS']= $template->show();     
                        $contenido_1['TITULO'].="<br><a href=\"#anexos-doc\" title=\"Ver Documentos relacionados\">                            
                                        <i class=\"	glyphicon glyphicon-paperclip\" style=\"\"></i>
                                        Documentos Relacionados
                                    </a>";
                    }
                }
                else{
                    $contenido_1['DATOS']=$iframe;
                    $contenido_1['TITULO']=$titulo_doc;
                }
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");
                
                $contenido_1['OPCIONES']=$html_registro.$html;
                
                                
                $template->setVars($contenido_1);   
                

                $objResponse->addAssign('detail-content',"innerHTML",$template->show());
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                //$objResponse->addScript("PanelOperator.initPanels('');");
                $objResponse->addScript("$('.close-detail').click(function (event) {
                        event.preventDefault();
                        PanelOperator.hideDetail('');
                        PanelOperator.resize();
                    })

                    $('.detail-show').click(function (event) {
                        event.preventDefault();
                        PanelOperator.showDetail('');
                        PanelOperator.hideSearch('');
                    });
                    ");
                $objResponse->addScript("PanelOperator.showDetail('');");  
                $objResponse->addScript("if (($('#grid').height() - 200) > 600) $('#iframe-vis').height($('#grid').height()  ); ");
                $objResponse->addScript("PanelOperator.resize();");
                $objResponse->addScript("init_ver_registros();");
                //$objResponse->addScript($js);
                //$objResponse->addScript('setTimeout(function(){ alert("vaaa");$(\'#iframe-vis\').attr("src",$("#text-iframe").html()+"&embedded=true");},1000);');
                
                return $objResponse;
            }

            public function ver_workflow($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $parametros['email_usuario']= $_SESSION['CookEmail'];
                $ut_tool = new ut_Tool();
                $val = $this->verDocumentos($parametros[id]); 
                //foreach ( $this->nombres_columnas as $key => $value)
                $this->cargar_nombres_columnas();
                //print_r($this->nombres_columnas);
               // print_r($val);
                //ESTADOS DE WF
                //estado_pendiente_revision
                //estado_pendiente_aprobacion
                //estado_aprobado
                $contenido_1['IDDOC']=$parametros[id];
                $contenido_1['MOSTRARCAMBIAR']='style="display:none"';
                $contenido_1['MOSTRARRECHAZAR']='style="display:none"'; 

                $sql = "SELECT fecha_registro f1,DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%m')fecha_registro, descripcion_operacion, "
                        . "CONCAT(initcap(SUBSTR(user.nombres,1,IF(LOCATE(' ' ,user.nombres,1)=0,LENGTH(user.nombres),LOCATE(' ' ,user.nombres,1)-1))),' ',initcap(user.apellido_paterno)) usuario "                        
                        . "FROM mos_historico_wf_documentos wf inner join mos_usuario user "
                        . " on wf.id_usuario = user.id_usuario "
                        . " WHERE IDDoc = $parametros[id] order by f1 desc";
                //echo $sql;
                $historia = $this->dbl->query($sql, array());
                $item_histo .='<table class=table table-striped table-condensed  width=100%>';
                    $item_histo .="<thead><tr>";
                    $item_histo .="<th>Fecha</th>";
                    $item_histo .="<th>Operaci&oacute;n</th>";
                    $item_histo .="<th>Usuario Responsable</th>";
                    $item_histo .="</tr></thead>";
                foreach ($historia as $value) {
                    $item_histo .="<tr>";
                    $item_histo .="<td>".$value[fecha_registro]."</td>";
                    $item_histo .="<td>".str_replace('"',"'",$value[descripcion_operacion])."</td>";
                    $item_histo .="<td>".$value[usuario]."</td>";
                    $item_histo .="</tr>";
                }                
                $item_histo .="</table>";
                //$item_histo='sakdsjasd askdj aksdjn askdjnasdk jas dkajsdn kajsdn askdjn';
                $cuadro_historico = '.. <br/>
                    <a href="#" tok="' .$parametros[id]. '-doc-hist" class="ver-mas">
                        <i class="glyphicon glyphicon-search" href="#search"></i> Ver Historial
                        <input type="hidden" id="ver-mas-' .$parametros[id]. '-doc-hist" value="'.$item_histo.'"/>
                    </a>';
                $contenido_1['VERHISTO']=$cuadro_historico;
                //EL USUARIO ES QUIEN REVISA
                if($val[etapa_workflow]=='estado_pendiente_revision' ){
                    $contenido_1['ETAPANUEVA']='estado_pendiente_aprobacion';
                    $contenido_1['ETAPARECHAZO']='estado_pendiente_revision';
                    if($val[email_revisa]==$parametros['email_usuario'] && $val[estado_workflow]=='OK'){
                        //ESTA EN PENDIENTE REVISION
                        $contenido_1['TITULOESTADO']=$this->nombres_columnas[reviso];
                        $contenido_1['MOSTRARCAMBIAR']='';
                        $contenido_1['MOSTRARRECHAZAR']=''; 
                    }
                    $persona_pendiente = $val[reviso_a];
                }
                if($val[etapa_workflow]=='estado_pendiente_aprobacion'){
                    $contenido_1['ETAPANUEVA']='estado_aprobado';
                    $contenido_1['ETAPARECHAZO']='estado_pendiente_revision';
                    if($val[email_aprueba]==$parametros['email_usuario'] && $val[estado_workflow]=='OK'){
                        //ESTA EN PENDIENTE APROBACION
                        $contenido_1['TITULOESTADO']=$this->nombres_columnas[aprobo];
                        $contenido_1['MOSTRARCAMBIAR']='';
                        $contenido_1['MOSTRARRECHAZAR']='';
                    }
                    $persona_pendiente = $val[aprobo_a];
                }
                if($val[etapa_workflow]=='estado_aprobado'){
                    $persona_pendiente = $val[aprobo_a];
                }
                $contenido_1['N_ELABORO']=$val[elaboro_a];
                $objResponse = new xajaxResponse();
                /*DOCUMENTO DE VISUALIZACION*/
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
                
                $html = '<div style="height:700px;" class="content-panel panel">
                <div class="content">
                    <div class="info-container" style="height:700px;">
                        <div class="row" id="div-iframe-vis"  style="height:100%;">
                            <!--<iframe id="iframe-vis" src="#" style="height:90%;width:100%;" frameborder="0"></iframe>-->
                            <!--<iframe id="iframe-vis" src="'.$http.'://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;" frameborder="0"></iframe>-->
                            '.$iframe.'
                        </div>
                        <!--<textarea id="text-iframe">'.$http.'://docs.google.com/gview?url='.$ruta_doc.'</textarea>-->
                        
                    </div>
              </div></div>';
                /**/
                $contenido_1['DOCVISUALIZA']=$html;
                
                /*VER DOCUMENTO FUENTE*/
                $archivo_aux = $this->verDocumentoFuente($parametros[id]);
                $sql = "SELECT extension FROM mos_extensiones WHERE extension = '$archivo_aux[contentType]' OR contentType = '$archivo_aux[contentType]'";
                $total_registros = $this->dbl->query($sql, array());
                //print_r($total_registros);
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
                
                $html = '<div style="height:700px;" class="content-panel panel">
               <div class="content">
                 <div class="row" style="height:700px;">
                       <iframe src="'.$http.'://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;" frameborder="0"></iframe>
                 </div>

               </div>
             </div>';
               /**/
              
              $contenido_1['DOCFUENTE']=$html;
               $contenido_1['PAGINA_VOLVER'] = "listarDocumentos.php";
               
               $contenido_1['TITULO_FORMULARIO'] =$val["Codigo_doc"].'-'.$val["nombre_doc"].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT). '<br>Flujo de Trabajo "'.$this->nombres_columnas[$val[etapa_workflow]].'"-'.$val[estado_workflow].' por '.$persona_pendiente;
               //echo $val[etapa_workflow] 
              // print_r($this->nombres_columnas);
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("formulario_wf");
                $template->setVars($contenido_1);
                
                $contenido_1['OPC'] = "upd";
                $contenido_1['ID'] = $val["IDDoc"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form',"innerHTML",$template->show());
                
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2");    
                $objResponse->addScriptCall('cargar_autocompletado');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("init_tabla();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");
                $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
                //$objResponse->addAssign('div-titulo-for',"innerHTML",'xxxxxxxxxxx');
                //$objResponse->addScript('setTimeout(function(){ alert("vaaa");$(\'#iframe-vis\').attr("src",$("#text-iframe").html()+"&embedded=true");},1000);');
                $objResponse->addScript("$js");
                return $objResponse;
            }            
            public function buscar_reporte($parametros)
            {   //print_r($parametros);
                
                /*FILTRA LOS DOCUMENTOS QUE ESTAN VIGENTES*/
                $parametros["b-vigencia"] = 'S';
                $grid = $this->verListaDocumentosReporte($parametros);
                $objResponse = new xajaxResponse();
                
                $objResponse->addScript("limpiar_titulo();");
                if ((strlen($parametros['b-id_organizacion'])>0)&&(!strpos($parametros['b-id_organizacion'],','))){
                    //echo BuscaOrganizacional(array('id_organizacion' => $parametros['b-id_organizacion']));
                    $objResponse->addScript("$('#div-titulo-mod').html($('#div-titulo-mod').html() + '<br>' + '". BuscaOrganizacional(array('id_organizacion' => $parametros['b-id_organizacion'])). "');");
                    if (strlen($parametros['b-area_espejo'])>0)
                        $objResponse->addScript ('$(".b-area_espejo").prop("checked", true);');
                    $objResponse->addScript("$('.b-area_espejo').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-area_espejo').val('1');                                    
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-area_espejo').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                        }
                            })");    
                    $objResponse->addScript("$('.b-mi-ocultar-publico').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-ocultar-publico').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-ocultar-publico').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });");   
                 
                }
                if ($_SESSION['CookCodEmp'] <> ''){
                    if(!class_exists('Personas')){
                        import("clases.personas.Personas");
                    }
                    $p = new Personas();
                    $id_ao = $p->verAreaPersonas($_SESSION['CookCodEmp']);

                    $objResponse->addScript("
                            $('.b-mi-nivel').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                     $('#div-ao').jstree(true).deselect_all(true);
                                    $('#div-ao').jstree(true).select_node('phtml_$id_ao');                                    
                                } else {
                                     $('#div-ao').jstree(true).deselect_node('phtml_$id_ao');  
                                }
                            });");
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
            public function NotificacionListaDistribucion($parametros){
                $ut_tool = new ut_Tool();
                import('clases.notificaciones.Notificaciones');
                $noti = new Notificaciones();
                /*CLASE DE LISTA DE DISTRIBUCION*/
                import('clases.lista_distribucion_doc.ListaDistribucionDoc');
                $lista_distribucion = new ListaDistribucionDoc();
                $responsables = array();
                $persocargos = array();
                if($parametros[publico]=='S'){
                //SI ES PUBLICO, CONVERTIMOS LOS NODOS EN UN ARRAY
                    import('clases.organizacion.ArbolOrganizacional');
                    $ao = new ArbolOrganizacional();
                    $organizacion = array();
                    $nuevo_organizacion = array();
                    if(strpos($parametros[id_organizacion],',')){    
                        $organizacion = explode(",", $parametros[id_organizacion]);
                    }
                    else{
                        $organizacion[] = $parametros[id_organizacion];                                 
                    }
                //RECOERREMOS LOS NODOS Y BUSCAMOS SUS HIJOS DE HIJOS Y MAS
                    $hijos = '0';
                    foreach ($organizacion as $value){
                        $hijos .= ','.$ao->BuscaOrgNivelHijos($value);
                    }
                    //echo $parametros[id_organizacion].'-';
                    $parametros[id_organizacion] .= $hijos;
                   // echo $parametros[id_organizacion].'-';
                    $nuevo_organizacion = explode(",", $parametros[id_organizacion]);
                    $nuevo_organizacion = array_unique($nuevo_organizacion);
                    $parametros[id_organizacion] = implode(",", array_values($nuevo_organizacion));
                    //echo $parametros[id_organizacion].'-';
                }
                $responsables = $this->ResponsablesAreasOrganizacion($parametros[id_organizacion]);
                //$persocargos = $this->CargosPersonalDocumentos($parametros[IDDoc]);
                
                $contenido   = array();
                
                if($parametros["version"]>1){
                    $contenido['CODIGONOMBRE']=$parametros[Codigo_doc].'-'.$parametros[nombre_doc];
                    $contenido['VERSION']= str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                    $nombretpl='cuerpo_notificacion_lista_version';
                }
                else {
                    $contenido['CODIGONOMBREVERSION']=$parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-'.  str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                    $nombretpl='cuerpo_notificacion_lista_original';
                }
                $asunto = 'Documento Publicado: ' . $parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-V'.  str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate($nombretpl);
                //print_r($responsables);
                //echo $asunto;
                //echo $cuerpo;
                $atr[asunto]='Lista de distribución pendiente';
                $atr[modulo]='LISTA DISTRIBUCIÓN';//'DOCUMENTOS';
                $atr[cuerpo]=$parametros[Codigo_doc].'-'.$parametros[nombre_doc].'-'.  str_pad($parametros["version"], 2, "0", STR_PAD_LEFT);
                //$atr[email]','$atr[asunto]','$atr[cuerpo]','$atr[modulo]','$atr[funcion]'
                foreach ($responsables as $value) {
                    $persocargos = $this->CargosPersonalDocumentos($parametros[IDDoc],$value[id_organizacion]);
                    $cuerpo_cargos ='<table  border="1" cellpadding="0" cellspacing="0"   width=50%><tr>';
                    $cuerpo_cargos .='<td align="center" bgcolor="#f6f8f1" style="border:0px 1px 1px 0px solid black" width=50%><strong>Cargo</strong></td>';
                    $cuerpo_cargos .='<td align="center" bgcolor="#f6f8f1" style="border:0px 0px 1px 1px solid black" width=50%><strong>Personal</strong></td></tr>';
                    foreach ($persocargos as $value2) {
                        $cuerpo_cargos .='<tr>';
                        $cuerpo_cargos .='<td style="border:1px 1px 0px 1px solid black">'.$value2[cargo].'</td>';
                        $cuerpo_cargos .='<td style="border:1px 1px 0px 0px solid black">'.str_replace(",", "<br/>", $value2[nombres]).'</td>';
                        $cuerpo_cargos .='</tr>';
                    }
                    $cuerpo_cargos .='</table>';
                    $contenido['NOMBRES']= $value[nombres];
                    $contenido['LISTADOCARGOS']= $cuerpo_cargos;
                    $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                    $template->setTemplate($nombretpl);
                    $template->setVars($contenido);
                    $cuerpo = $template->show().'<br>'.APPLICATION_ROOT;
                    //print_r($value);
                    //echo $asunto;
                    //echo $cuerpo;
                    //$from = array(array('correo' => $value[correo], 'nombres'=>$value[nombres]));
                    if($value[recibe_notificaciones]=='S'){
                        //$ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $asunto, $cuerpo, array());
                        $this->registraCorreoTareaProgramada($id_entidad,$atr[modulo], $asunto, $cuerpo, $value[correo], $value[nombres]);
                        $a=1;
                    }
                    $atr[email]=$value[correo];
                    /*SE GUARDA REGISTRO EN LISTA DE DISTRIBUCION*/
                    $id_entidad = $lista_distribucion->ingresarListaDistribucionDocNotioficacion($parametros[IDDoc],$value[id_organizacion], $value[cod_emp]);
                    $atr[id_entidad]=$id_entidad;//$parametros[IDDoc];
                    $atr[funcion] = "verListaDistribucionPopup(".$id_entidad.");";
                    /**/
                    $mensaje=$noti->ingresarNotificaciones($atr);                
                }
            }
            public function cambiar_estado($parametros)
            {   //print_r($parametros);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];
                $respuesta = $this->cambiarestadowf($parametros);
                $val = $this->verDocumentos($parametros[id]);
                $objResponse = new xajaxResponse();
               // echo $respuesta;
               // print_r($parametros);
                if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) {
                        $cc=array();
                        $from=array();
                        $ut_tool = new ut_Tool();
                        $correowf = $this->verWFemail($parametros[id]);
                        //echo $correowf[email];
                        $this->cargar_nombres_columnas();
                        $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                        //echo $correowf[email];
                            
                            $cuerpo = 'Sr(a). ' .$correowf[nombres] . '<br><br> Usted tiene una notificación de un documento "'.$etapa.'"<br><br>';
                            $asunto = 'Documento '. $etapa . ': ' . $val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                           // $correowf[email] = 'azambrano75@gmail.com';
                            $nombres = $correowf[nombres];
                            //echo $cuerpo;
                           // print_r($correowf);
                        if($correowf[etapa_workflow]=='estado_pendiente_aprobacion' && $correowf[estado_workflow]=='OK') {                            
                            if($correowf[recibe_notificaciones]=='S'){
                                $from = array(array('correo' => $correowf[email], 'nombres'=>$nombres));                                
                            }                            
                            if($correowf[recibe_notificaciones_responsable]=='S'){
                                $cc = array(array('correo' => $correowf[email_responsable], 'nombres'=>$correowf[nombre_responsable]));
                               // $cc = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_responsable]));
                            }
                            if(sizeof($from)>0 || sizeof($cc)>0){
                                   $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_responsable].' &rarr;';
                                   $anexo_adicional .='<strong>'. $this->nombres_columnas['reviso'].'</strong>&nbsp;';
                                   $anexo_adicional .=$correowf[nombre_revisa].'.<br>';
                                   $cuerpo .= $anexo_adicional.'<br>'.APPLICATION_ROOT;
                                   $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $asunto, $cuerpo, array(),$cc); 
                            }
                        }
                        
                        else if($correowf[estado_workflow]=='RECHAZADO'){
                            $cuerpo = 'Rechazado por:&nbsp;'.$correowf[nombres];
                            $cuerpo .= '<br><br>Motivo del Rechazo:<br><span style="color:red">'.$correowf[observacion_rechazo].'</span>';                            
                            $asunto = 'Documento RECHAZADO: ' . $val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                            //echo $_SESSION[CookEmail].' '.$correowf[email_aprueba].' '.$correowf[recibe_notificaciones_revisa].' '.$correowf[recibe_notificaciones_responsable];
                            if($_SESSION[CookEmail]==$correowf[email_aprueba]){
                                if($correowf[recibe_notificaciones_revisa]=='S'){
                                    $cc = array(array('correo' => $correowf[email_revisa], 'nombres'=>$correowf[nombre_revisa]));
                                    //$cc = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_revisa]));
                                }
                                if($correowf[recibe_notificaciones_responsable]=='S'){
                                    $from = array(array('correo' => $correowf[email_responsable], 'nombres'=>$correowf[nombre_responsable]));
                                    //$from = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_responsable]));
                                }
                                if(sizeof($from)>0 || sizeof($cc)>0){
                                    $cuerpo .= '<br><br>'.APPLICATION_ROOT;
                                    //echo $cuerpo;
                                    //$correowf[email] = 'azambrano75@gmail.com';
                                    $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $asunto, $cuerpo, array(),$cc);                                
                                }
                            }            
                        }
                        

                        //SE CARGA LA NOTIFICACION
                            //echo 'etapa:'.$correowf[etapa_workflow];
                            //echo 'edo:'.$correowf[estado_workflow];
                            import('clases.notificaciones.Notificaciones');
                            $noti = new Notificaciones();
                            $atr[cuerpo] .=$val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT).'<br>';
                            if($correowf[etapa_workflow]=='estado_pendiente_revision' && $correowf[estado_workflow]=='OK') {
                                //$atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su revision<br>';
                                $atr[asunto]='Tiene un documento '.$etapa.'';
                            }
                            else 
                                if($correowf[etapa_workflow]=='estado_pendiente_aprobacion' && $correowf[estado_workflow]=='OK') {
                                    //$atr[cuerpo] .=$etapa.'. Se le ha asignado el documento para su aprobacion<br>';
                                    $atr[asunto]='Tiene un documento '.$etapa.'';
                                }
                                else
                                    if($correowf[estado_workflow]=='RECHAZADO') {
                                        //$atr[cuerpo] .='Tiene un documento Rechazado<br>';
                                        $atr[asunto]='Tiene un documento Rechazado';
                                    } else
                                    if($correowf[etapa_workflow]=='estado_aprobado') {
                                        //$atr[cuerpo] .='Tiene un documento Aprobado<br>';
                                        //ESTE BLOQUE APLICA SI EL DOCUMENTO TIENE LISTA DE DISTR ACTIVA
                                        //print_r($val);
                                        if($val[requiere_lista_distribucion]=='S'){
                                            //echo 'paso';
                                            $this->NotificacionListaDistribucion($val);
                                        }
                                        
                                        if(!class_exists('Template')){
                                            import("clases.interfaz.Template");
                                        }
                                        $contenido   = array();
                                        $contenido['ELABORADOR']=$correowf[nombre_responsable];
                                        if($val["version"]>1){
                                            $contenido['MENSAJE']='Se ha actualizado el documento';
                                            $contenido['DOCUMENTO']=$val[Codigo_doc].'-'.$val[nombre_doc].' a la versi&oacute;n '.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                                        }
                                        else {
                                            $contenido['MENSAJE']='Se ha publicado el documento';
                                            $contenido['DOCUMENTO']=$val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                                        }
                                        $asunto = 'Documento Publicado: ' . $val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                                        
                                        $template = new Template();
                                        $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                                        $template->setTemplate("cuerpo_notificacion");
                                        $template->setVars($contenido);
                                        $cuerpo = $template->show();

                                        //echo $cuerpo;
                                        if($correowf[recibe_notificaciones_revisa]=='S'){
                                            $cc = array(array('correo' => $correowf[email_revisa], 'nombres'=>$correowf[nombre_revisa]));
                                           // $cc = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_revisa]));
                                        }
                                        if($correowf[recibe_notificaciones_responsable]=='S'){
                                            $from = array(array('correo' => $correowf[email_responsable], 'nombres'=>$correowf[nombre_responsable]));
                                           // $from = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_responsable]));
                                        }
                                        if(sizeof($from)>0 || sizeof($cc)>0){
                                            $anexo_adicional ='<br><strong>'. $this->nombres_columnas['elaboro'].'</strong>&nbsp;';
                                            $anexo_adicional .=$correowf[nombre_responsable].' &rarr;';
                                            $anexo_adicional .='<strong>'. $this->nombres_columnas['reviso'].'</strong>&nbsp;';
                                            $anexo_adicional .=$correowf[nombre_revisa].' &rarr;';
                                            $anexo_adicional .='<strong>'. $this->nombres_columnas['aprobo'].'</strong>&nbsp;';
                                            $anexo_adicional .=$correowf[nombre_aprueba].'.<br>';
                                            $cuerpo .= $anexo_adicional.'<br>'.APPLICATION_ROOT;
                                            $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, $asunto, $cuerpo, array(),$cc);                                
                                        }
                                        $atr[asunto]='Tiene un documento Aprobado';
                                    }
                            
                            $atr[modulo]='DOCUMENTOS';
                            $atr[funcion] = "verWorkFlowPopup(".$val[IDDoc].");";
                            $atr[email]=$correowf[email];
                            $atr[id_entidad]=$val[IDDoc];
                            $mensaje=$noti->ingresarNotificaciones($atr);
                        //die;
                    $objResponse->addScriptCall("MostrarContenido");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
            
         public function cargar_combo_wf($parametros){
            // print_r($parametros);
            import("clases.workflow_documentos.WorkflowDocumentos");
            $wf = new WorkflowDocumentos(); 
            $val = $this->verDocumentos($parametros[id]);
            $ut_tool = new ut_Tool(); 
            $campos = "SELECT wf.id,
                            CONCAT(CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno))
                            ,' &rarr; ', 
                           IF(perso_revisa.nombres is null,CONCAT(initcap(SUBSTR(perso_responsable.nombres,1,IF(LOCATE(' ' ,perso_responsable.nombres,1)=0,LENGTH(perso_responsable.nombres),LOCATE(' ' ,perso_responsable.nombres,1)-1))),' ',initcap(perso_responsable.apellido_paterno)),CONCAT(initcap(SUBSTR(perso_revisa.nombres,1,IF(LOCATE(' ' ,perso_revisa.nombres,1)=0,LENGTH(perso_revisa.nombres),LOCATE(' ' ,perso_revisa.nombres,1)-1))),' ',initcap(perso_revisa.apellido_paterno))	) 
                           ,' &rarr; ', 
                            CONCAT(initcap(SUBSTR(perso_aprueba.nombres,1,IF(LOCATE(' ' ,perso_aprueba.nombres,1)=0,LENGTH(perso_aprueba.nombres),LOCATE(' ' ,perso_aprueba.nombres,1)-1))),' ',initcap(perso_aprueba.apellido_paterno))) as wf
                            FROM mos_workflow_documentos AS wf
                           left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
                           left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                           INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp";
            
            $sql = "SELECT
                    mos_personal.cod_emp, mos_personal.email, mos_organizacion.id id_organizacion
                    FROM
                    mos_organizacion inner join (SELECT
                            min(mos_organizacion.level) level
                            FROM
                            mos_organizacion
                            WHERE
                            mos_organizacion.id in (".$parametros[nodos].")) nivel_minimo
                    on nivel_minimo.`level`= mos_organizacion.`level` INNER JOIN mos_documentos_cargos AS resp 
                    ON mos_organizacion.id = resp.id_organizacion INNER JOIN mos_personal 
                    ON mos_personal.cod_emp = resp.cod_emp 
                    WHERE
                    resp.id_organizacion in (".$parametros[nodos]."); ";
            //echo $sql;
            $this->operacion($sql, $atr);
                //echo $sql;
            $empleados = $this->dbl->data;
            $cod_emp = implode(",", array_column($empleados,'cod_emp'));
            if($_SESSION['CookCodEmp'] == '' AND ($_SESSION[SuperUser]=='S')){
                $sql = $campos;
            }
            else{
                if($cod_emp!=''){
                    //echo $cod_emp;
                    // CONSULTAMOS SI ESTOS COD_EMP TIENEN WF
                    $sql="SELECT wf.id_personal_aprueba
                              FROM mos_workflow_documentos AS wf
                              left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                              INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp
                              WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba in (".$cod_emp." )";
                    $this->operacion($sql, $atr);
                    $emp_resp = $this->dbl->data;
                    // print_r($empleados);
                    $empl_inter = array();
                    $empl_inter = (array_diff(array_column($empleados,'cod_emp'), array_column($emp_resp,'id_personal_aprueba')));
                        //RECORREMOS LOS COD_EMP QUE NO TIENEN WF Y HAY QUE CREARLO
                       foreach($empleados as $value){
                           if (in_array($value[cod_emp], $empl_inter)){
                               $atr =array();
                               $atr[id_personal_responsable]=$_SESSION['CookCodEmp'];
                               $atr[email_responsable]=$_SESSION['CookEmail'];
                               $atr[id_personal_aprueba]=$value[cod_emp];
                               $atr[email_aprueba]=$value[email];
                               $wf->ingresarWorkflowDocumentos($atr);
                             //  print_r($atr);
                           }

                       }                 
                   //VERIFICAMOS SI EL RESPONSABLE DE AREA TIENE WF ASIGNADO COMO APROBADOR
                   $sql= $campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba in (".$cod_emp." )";
                   //echo 'query 1 VERIFICAMOS SI EL RESPONSABLE DE AREA TIENE WF ASIGNADO COMO APROBADOR '.$sql;
                   $this->operacion($sql, $atr);
                   if(sizeof($this->dbl->data)>0){
                       //ESTE RESPONSABLE DE AREA TIENE WF COMO APROBADOR
                       $datos = $this->dbl->data;
                       $sql .=" UNION ALL ".$campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba not in (".$cod_emp." )";
                       $seleccionar=$cod_emp;
                       //echo 'query 2 TIENE WF EL RESPONSABLE  '.$sql;
                   }
                   else{

                       $sql= $campos . " WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."')";
                       // echo 'query 2 NO TIENE WF EL RESPONSABLE  E INSERTAMOS '.$sql;
                   }
                   $sql_wf_sel="SELECT wf.id
                      FROM mos_workflow_documentos AS wf
                      WHERE wf.id_personal_aprueba in (".$cod_emp.") and (wf.id_personal_responsable=".$_SESSION['CookCodEmp'].")"
                   . " limit 0,1";
                   $this->operacion($sql_wf_sel, $atr);
                   $seleccionar=$this->dbl->data[0][id];

               }
               else{
                   //EL AREA NO TIENE RESPONSABLE Y BUSCAMOS EL REPONSABLE DEL AREA SUPERIOR
                   //select min(level) nivel from mos_organizacion where id IN
                   $sql = "SELECT
                       mos_personal.cod_emp, mos_personal.email, mos_organizacion.id id_organizacion
                       FROM
                       mos_organizacion inner join (SELECT
                               min(mos_organizacion.level) level
                               FROM
                               mos_organizacion
                               WHERE
                               mos_organizacion.id in ((select parent_id from mos_organizacion where id IN (".$parametros[nodos].") ))) nivel_minimo
                       on nivel_minimo.`level`= mos_organizacion.`level` INNER JOIN mos_documentos_cargos AS resp 
                       ON mos_organizacion.id = resp.id_organizacion INNER JOIN mos_personal 
                       ON mos_personal.cod_emp = resp.cod_emp 
                       WHERE
                       resp.id_organizacion in ((select parent_id from mos_organizacion where id IN (".$parametros[nodos].") )); 
                       ";
                   //echo $sql;
                   //echo 'query 1 SINO VERIFICAMOS SI EL RESPONSABLE SUPERIOR DE AREA TIENE WF ASIGNADO COMO APROBADOR '.$sql;
                   $this->operacion($sql, $atr);
                   //echo $sql;
                   //print_r($this->dbl->data);
                   $empleados = $this->dbl->data;
                   if(sizeof($this->dbl->data)>0){
                   $cod_emp = implode(',', array_column($this->dbl->data,'cod_emp'));
                   }  
                   else{ 
                       //SI NO TRAE NADA, SUBO UN NIVEL MAS
                       $sql = "SELECT
                           mos_personal.cod_emp, mos_personal.email, mos_organizacion.id id_organizacion
                           FROM
                           mos_organizacion inner join (SELECT
                                   min(mos_organizacion.level) level
                                   FROM
                                   mos_organizacion
                                   WHERE
                                   mos_organizacion.id in ((select parent_id from mos_organizacion where id IN (select parent_id from mos_organizacion where id IN (".$parametros[nodos].")) ))) nivel_minimo
                           on nivel_minimo.`level`= mos_organizacion.`level` INNER JOIN mos_documentos_cargos AS resp 
                           ON mos_organizacion.id = resp.id_organizacion INNER JOIN mos_personal 
                           ON mos_personal.cod_emp = resp.cod_emp 
                           WHERE
                           resp.id_organizacion in ((select parent_id from mos_organizacion where id IN (select parent_id from mos_organizacion where id IN (".$parametros[nodos].")) )); 
                           ";
                       $this->operacion($sql, $atr);
                       //echo $sql;
                       //print_r($this->dbl->data);
                       $empleados = $this->dbl->data;
                       if(sizeof($this->dbl->data)>0){
                       $cod_emp = implode(',', array_column($this->dbl->data,'cod_emp'));
                       }                      
                   }
                   //print_r($empleados);
                   //echo $cod_emp;
                   if($cod_emp!=''){
                       $sql="SELECT wf.id_personal_aprueba
                                 FROM mos_workflow_documentos AS wf
                                 left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
                                 INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp
                                 WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba in (".$cod_emp." )";
                       $this->operacion($sql, $atr);
                       $emp_resp = $this->dbl->data;
                       // print_r($emp_resp);
                       $empl_inter = array();
                       $empl_inter = (array_diff(array_column($empleados,'cod_emp'), array_column($emp_resp,'id_personal_aprueba')));
                           //RECORREMOS LOS COD_EMP QUE NO TIENEN WF Y HAY QUE CREARLO
                          foreach($empleados as $value){
                              if (in_array($value[cod_emp], $empl_inter)){
                                  $atr =array();
                                  $atr[id_personal_responsable]=$_SESSION['CookCodEmp'];
                                  $atr[email_responsable]=$_SESSION['CookEmail'];
                                  $atr[id_personal_aprueba]=$value[cod_emp];
                                  $atr[email_aprueba]=$value[email];
                                  $wf->ingresarWorkflowDocumentos($atr);
                                  //print_r($atr);
                              }

                          }                          
                       //VERIFICAMOS SI EL RESPONSABLE DE AREA TIENE WF ASIGNADO COMO APROBADOR
                       $sql = $campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba in (".$cod_emp." )";
                       $this->operacion($sql, $atr);
                       if(sizeof($this->dbl->data)>0){
                           //ESTE RESPONSABLE DE AREA TIENE WF COMO APROBADOR
                           $datos = $this->dbl->data;
                           $sql .=" UNION ALL ". $campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba not in (".$cod_emp." )";
                             //$this->operacion($sql, $atr);
                           //$datos = $this->dbl->data;
                           //$resultado = array_merge($datos, $this->dbl->data);
                       }
                       else{
                           $sql=  $campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."')";
                           $sql_wf_sel="SELECT wf.id
                                      FROM mos_workflow_documentos AS wf
                                      WHERE wf.id_personal_aprueba in (".$cod_emp.") and (wf.id_personal_responsable=".$_SESSION['CookCodEmp'].")"
                                   . " limit 1,1";
                           $this->operacion($sql_wf_sel, $atr);
                           $seleccionar=$this->dbl->data[0][id];
                       }
                   }  
                   else {
                       $sql=  $campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."')";
                   }
               }
            }
            //$sql .=" UNION ALL ".$campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba not in (".$cod_emp." )";
            //$sql .=" UNION ALL ".$campos ." WHERE (wf.id_personal_responsable='".$_SESSION['CookCodEmp']."') and wf.id_personal_aprueba not in (".$cod_emp." )";
           // echo $sql;    
            if($val['id_workflow_documento']=='' && $seleccionar==''){
                $js = "$('#id_workflow_documento option').eq(1).attr('selected', 'selected');";
            }
            //echo $sql;
            $combosemp .= $ut_tool->OptionsCombo($sql, 'id', 'wf', $seleccionar);    
            $combo .="<select class='form-control' id=\"id_workflow_documento\" name=\"id_workflow_documento\"  data-validation=\"required\" >
                        <option value=''>-- No Asignado --</option>
                        ".$combosemp."
                    </select>    ";

            

            $objResponse = new xajaxResponse();      
            if($mensaje!=''){
                $objResponse->addScriptCall('VerMensaje','exito',$mensaje);
            }
            $objResponse->addAssign('div_combo_wf',"innerHTML",$combo);
            $objResponse->addScript($js);
            return $objResponse;

            }    
            public function ComboCargoOrg($parametros){
              //  print_r($parametros);
            $ut_tool = new ut_Tool(); 
            $js = $combosemp='';
            if($parametros[valor]=='S'){
                if($parametros[publico]=='S'){
                //SI ES PUBLICO, CONVERTIMOS LOS NODOS EN UN ARRAY
                    import('clases.organizacion.ArbolOrganizacional');
                    $ao = new ArbolOrganizacional();
                    $organizacion = array();
                    $nuevo_organizacion = array();
                    if(strpos($parametros[nodos],',')){    
                        $organizacion = explode(",", $parametros[nodos]);
                    }
                    else{
                        $organizacion[] = $parametros[nodos];                                 
                    }
                //RECOERREMOS LOS NODOS Y BUSCAMOS SUS HIJOS DE HIJOS Y MAS
                    $hijos = '0';
                    foreach ($organizacion as $value){
                        $hijos .= ','.$ao->BuscaOrgNivelHijos($value);
                    }
                    //echo $parametros[id_organizacion].'-';
                    $parametros[nodos] .= $hijos;
                   // echo $parametros[id_organizacion].'-';
                    $nuevo_organizacion = explode(",", $parametros[nodos]);
                    $nuevo_organizacion = array_unique($nuevo_organizacion);
                    $parametros[nodos] = implode(",", array_values($nuevo_organizacion));
                    //echo $parametros[id_organizacion].'-';
                }
                $sql = "SELECT DISTINCT
                        mos_cargo.cod_cargo id,
                        mos_cargo.descripcion,
                        cargo.cod_cargo valor
                        FROM
                        mos_cargo_estrorg_arbolproc
                        INNER JOIN mos_cargo ON mos_cargo.cod_cargo = mos_cargo_estrorg_arbolproc.cod_cargo left JOIN
			(select cod_cargo from mos_documentos_cargos where IDDoc='".$parametros['id']."') as cargo on 	mos_cargo.cod_cargo = cargo.cod_cargo
                         where mos_cargo_estrorg_arbolproc.id in (".$parametros['nodos'].")
                                                            order by mos_cargo.descripcion";
                //echo $sql;
                $combosemp .= $ut_tool->OptionsComboMultiple($sql, 'id', 'descripcion','valor');      
                $combo .= '<input type="hidden" name="campo_cargo" id="campo_cargo" value="" />';    
                $combo .="<select size=7 onchange='ValidarSeleccion(this);' class='form-control' id=\"cod_cargo\" name=\"cod_cargo[]\" title=\"-- Seleccione Cargos --\"  data-validation=\"required\" data-actions-box=\"true\" data-live-search=\"true\" multiple>
                            <!--<option value=''>-- Seleccione --</option>-->
                            ".$combosemp."
                        </select>";
               // echo $combo;
                $js = "$('#div_cargos').parent().show()";
            }else
                $js = "$('#div_cargos').parent().hide()";
            
            $objResponse = new xajaxResponse();            
            //echo $combo;
            $objResponse->addAssign('div_cargos',"innerHTML",$combo);
            $objResponse->addScript("$('#cod_cargo').selectpicker({
                                            style: 'btn-combo'
                                          });$js");
           // $objResponse->addScript("$('#requiere_lista_distribucion').val('".$parametros[valor]."');");
            return $objResponse;
            }             

            public function indexBitacoraDocumentos($parametros)
            {
                $contenido[TITULO_MODULO] = 'Bitácora de acceso a Documentos';
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="id";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="1-2-3-4-5-6-7-8-9"; 
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } */               
                $k = 19;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {                    
                    $parametros['mostrar-col'] .= "-$k";
                    $contenido[PARAMETROS_OTROS] .= '<div class="form-group">
                                  <label for="SelectAcc" class="col-md-9 control-label">' . $value[espanol] . '</label>
                                  <div class="col-md-3">      
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="checkbox-mos-col" checked="checked">   &nbsp;
                                      </label>
                                  </div>
                            </div>';
                    $k++;
                }
               // $this->cargar_permisos($parametros);
                
                $grid = $this->verListaBitacoraDocumentos($parametros);
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['MODO'] = $parametros['modo'];
                $contenido['COD_LINK'] = $parametros['cod_link'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                //$contenido['JS_NUEVO'] = 'nuevo_Notificaciones();';
                //$contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Notificaciones';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas(89);
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
                $objResponse->addIncludeScript(PATH_TO_JS . 'notificaciones/notificaciones.js');
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript('PanelOperator.initPanels("");
                        ScrollBar.initScroll();
                        init_filtro_rapido();
                        init_filtro_ao_simple();');
                return $objResponse;
            }
            public function verListaBitacoraDocumentos($parametros){
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarBitacoraDocumentos($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas(89);
                }

                $grid->SetConfiguracionMSKS("tblBitacoras", "");
                $config_col=array(
                    
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_hora], "fecha_hora", $parametros)),
               array( "width"=>"30%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[accion], "accion", $parametros)),
               array( "width"=>"35%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[codigo], "codigo", $parametros)),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[usuario], "usuario", $parametros))
                );
                /*if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }*/
                $k = 1;
                foreach ($this->parametros as $value) {                    
                    array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>link_titulos(($value[espanol]), "p$k", $parametros)));
                    $k++;
                }

                $func= array();

                $columna_funcion = -1;
                $config=array(array("width"=>"10%", "ValorEtiqueta"=>link_titulos($this->nombres_columnas[fecha_hora], "fecha_hora", $parametros)));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                            array_push($config,$config_col[$i]);
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
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("id", "colum_admin");
                //$grid->setFuncion("en_proceso_inscripcion", "enProcesoInscripcion");
                //$grid->setAligns(1,"center");
                //$grid->hidden = array(0 => true);
    
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPaginaBitacora", "document");
                }
                return $out;
            }
             public function listarBitacoraDocumentos($atr, $pag, $registros_x_pagina){
                 //print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_log 
                         WHERE log.codigo_accion = 89 ";
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND ((upper(accion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%') OR (upper(codigo) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
                    }
            if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";      
            if (strlen($atr["b-fecha_hora"])>0)
                        $sql .= " AND fecha_hora like '%" . strtoupper($atr["b-fecha_hora"]) . "%'";
            if (strlen($atr["b-accion"])>0)
                        $sql .= " AND upper(accion) like '%" . strtoupper($atr["b-accion"]) . "%'";
            if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-usuario"])>0)
                        $sql .= " AND upper(usuario) like '%" . strtoupper($atr["b-usuario"]) . "%'";

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT id
                                    ,fecha_hora
                                    ,accion
                                    ,codigo
                                    ,usuario
                                     $sql_col_left
                            FROM 
                            /*SUB QUERY PARA DOCUMENTOS*/
                               (SELECT
                                    log.id,
                                    DATE_FORMAT(log.fecha_hora, '%d/%m/%Y %H:%i') fecha_hora,
                                    log.accion,
                                    CONCAT(doc.Codigo_doc,'-',doc.nombre_doc,'-V',doc.version) codigo,
                                    CONCAT(initcap(SUBSTR(usr.nombres,1,IF(LOCATE(' ' ,usr.nombres,1)=0,LENGTH(usr.nombres),LOCATE(' ' ,usr.nombres,1)-1))),' ',initcap(usr.apellido_paterno)) usuario
                                    FROM
                                    mos_log AS log
                                    INNER JOIN mos_usuario AS usr ON log.realizo = usr.id_usuario
                                    INNER JOIN mos_documentos AS doc ON log.id_registro = doc.IDDoc
                                    WHERE
                                    log.codigo_accion = 89
                                )
                            /*FIN SUB QUERY PARA DOCUMENTOS*/
                            AS log $sql_left
                            WHERE 1 = 1 ";
            if (strlen($atr['b-filtro-sencillo'])>0){
                $sql .= " AND ((upper(accion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%') OR (upper(codigo) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%'))";
            }
            if (strlen($atr[valor])>0)
                $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
            if (strlen($atr["b-fecha_hora"])>0)
                $sql .= " AND fecha_hora like '%" . strtoupper($atr["b-fecha_hora"]). "%'";
            if (strlen($atr["b-accion"])>0)
                        $sql .= " AND upper(accion) like '%" . strtoupper($atr["b-accion"]) . "%'";
            if (strlen($atr["b-codigo"])>0)
                        $sql .= " AND upper(codigo) like '%" . strtoupper($atr["b-codigo"]) . "%'";
            if (strlen($atr["b-usuario"])>0)
                        $sql .= " AND upper(usuario) like '%" . strtoupper($atr["b-usuario"]) . "%'";

                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
            
            
            
 }?>
