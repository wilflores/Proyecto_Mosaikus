<?php 
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
                $sql = "select mos_personal.email, mos_personal.nombres, mos_personal.apellido_paterno,etapa_workflow, estado_workflow, observacion_rechazo, 
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
                                concat(pers.apellido_paterno,' ',pers.nombres) nombre_revisa,
                                pers_r.email email_reponsable,
                                concat(pers_r.apellido_paterno,' ',pers_r.nombres) nombre_responsable,
                                IFNULL(usu_rev.recibe_notificaciones,'N') recibe_notificaciones_revisa,
                                IFNULL(usu_resp.recibe_notificaciones,'N') recibe_notificaciones_responsable,
                                pers_apr.email email_aprueba,
                                concat(pers_apr.apellido_paterno,' ',pers_apr.nombres) nombre_aprueba,
                                IFNULL(usu_apr.recibe_notificaciones,'N') recibe_notificaciones_aprueba,
                                mos_documentos.IDDoc,
                                mos_documentos.etapa_workflow,
                                mos_documentos.estado_workflow,
                                mos_documentos.observacion_rechazo
                                FROM
                                mos_documentos left join mos_personal pers
                                on mos_documentos.reviso =  pers.cod_emp left join mos_personal pers_r
                                on mos_documentos.elaboro =  pers_r.cod_emp inner join mos_usuario usu_resp
                                on pers_r.email = usu_resp.email inner join mos_usuario usu_rev
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
               // print_r($atr);
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
                    $sql = "INSERT INTO mos_documentos(IDDoc,Codigo_doc,nombre_doc,version,fecha,descripcion,palabras_claves,formulario,vigencia,doc_fisico,contentType,id_filial,nom_visualiza,doc_visualiza,contentType_visualiza,id_usuario,observacion,estrucorg,arbproc,apli_reg_estrorg,apli_reg_arbproc,workflow,semaforo,v_meses,reviso,elaboro,aprobo,publico, id_workflow_documento,etapa_workflow,estado_workflow,id_usuario_workflow)                            
                            VALUES(
                                $atr[IDDoc],'$atr[Codigo_doc]','$atr[nombre_doc]',$atr[version],'$atr[fecha]','$atr[descripcion]','$atr[palabras_claves]','$atr[formulario]','$atr[vigencia]','$atr[doc_fisico]','$atr[contentType]',$atr[id_filial],'$atr[nom_visualiza]','$atr[doc_visualiza]','$atr[contentType_visualiza]',$atr[id_usuario],'$atr[observacion]','$atr[estrucorg]','$atr[arbproc]','$atr[apli_reg_estrorg]','$atr[apli_reg_arbproc]','$atr[workflow]',$atr[semaforo],$atr[v_meses],$atr[reviso],$atr[elaboro],$atr[aprobo]
                                    ,'$atr[publico]',$atr[id_workflow_documento],$atr[etapa_workflow],$atr[estado_workflow],$atr[id_usuario_workflow] 
                                )";
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_documentos ' . $atr[descripcion_ano], 'mos_documentos');
                      */
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Codigo Doc: \'$atr[Codigo_doc]\', Nombre Doc: \'$atr[nombre_doc]\', Version: \'$atr[version]\', Fecha: \'$atr[fecha]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', ContentType: \'$atr[contentType]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza]\', ContentType Visualiza: \'$atr[contentType_visualiza]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', Publico: \'$atr[publico]\'";
                    $this->registraTransaccionLog(1,$nuevo,'', $atr[IDDoc]);
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
            
            public function registraTransaccionLog($accion,$descr, $tabla, $id = 'NULL'){
                session_name("mosaikus");
                session_start();
                $sql = "INSERT INTO mos_log(codigo_accion, fecha_hora, accion, anterior, realizo, ip, id_registro) VALUES ('$accion','".date('Y-m-d G:h:s')."','$descr', '$tabla','$_SESSION[CookIdUsuario]','$_SERVER[REMOTE_ADDR]',$id)";            
                $this->dbl->insert_update($sql);

                return true;
            }

            public function modificarDocumentos($atr,$archivo,$doc_ver){
                //print_r($atr);
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $atr[doc_fisico] = $archivo;
                    $atr[doc_visualiza] = $doc_ver;    
                    $sql_doc_fisico ='';
                    if (strlen($atr[doc_fisico])>= 0){
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
                            $sql_wf = ", id_workflow_documento=$atr[id_workflow_documento],
                                        id_usuario_workflow=$atr[id_usuario],
                                       etapa_workflow=NULL,
                                       observacion_rechazo=null,
                                       estado_workflow=NULL";
                        }                      
                    }
                    //echo $sql_wf;
                    //die;
                    $sql = "UPDATE mos_documentos SET                            
                                    descripcion = '$atr[descripcion]',palabras_claves = '$atr[palabras_claves]',formulario = '$atr[formulario]',vigencia = '$atr[vigencia]'"
                            . ",nom_visualiza = $atr[nom_visualiza],doc_visualiza = $atr[doc_visualiza],contentType_visualiza = $atr[contentType_visualiza],id_usuario = $atr[id_usuario],observacion = '$atr[observacion]',estrucorg = '$atr[estrucorg]',arbproc = '$atr[arbproc]'"
                            . ",apli_reg_estrorg = '$atr[apli_reg_estrorg]',apli_reg_arbproc = '$atr[apli_reg_arbproc]',workflow = '$atr[workflow]',semaforo = $atr[semaforo],v_meses = $atr[v_meses],reviso = $atr[reviso],elaboro = $atr[elaboro],aprobo = $atr[aprobo]
                               ,publico = '$atr[publico]' $sql_wf $sql_doc_fisico
                            WHERE  IDDoc = $atr[id]";      
                   //echo $sql;
                   // die;
                    $val = $this->verDocumentos($atr[id]);
                   
                    $this->dbl->insert_update($sql);
                    $nuevo = "IDDoc: \'$atr[IDDoc]\', Descripcion: \'$atr[descripcion]\', Palabras Claves: \'$atr[palabras_claves]\', Formulario: \'$atr[formulario]\', Vigencia: \'$atr[vigencia]\', Id Filial: \'$atr[id_filial]\', Nom Visualiza: \'$atr[nom_visualiza_aux]\',ContentType Visualiza: \'$atr[contentType_visualiza_aux]\', Id Usuario: \'$atr[id_usuario]\', Observacion: \'$atr[observacion]\', Muestra Doc: \'$atr[muestra_doc]\', Estrucorg: \'$atr[estrucorg]\', Arbproc: \'$atr[arbproc]\', Apli Reg Estrorg: \'$atr[apli_reg_estrorg]\', Apli Reg Arbproc: \'$atr[apli_reg_arbproc]\', Workflow: \'$atr[workflow]\', Semaforo: \'$atr[semaforo]\', V Meses: \'$atr[v_meses]\', Reviso: \'$atr[reviso]\', Elaboro: \'$atr[elaboro]\', Aprobo: \'$atr[aprobo]\', Publico: \'$atr[publico]\'";
                    $anterior = "IDDoc: \'$val[IDDoc]\', Codigo Doc: \'$val[Codigo_doc]\', Nombre Doc: \'$val[nombre_doc]\', Version: \'$val[version]\', Fecha: \'$val[fecha]\', Descripcion: \'$val[descripcion]\', Palabras Claves: \'$val[palabras_claves]\', Formulario: \'$val[formulario]\', Vigencia: \'$val[vigencia]\', ContentType: \'$val[contentType]\', Id Filial: \'$val[id_filial]\', Nom Visualiza: \'$val[nom_visualiza]\', ContentType Visualiza: \'$val[contentType_visualiza]\', Id Usuario: \'$val[id_usuario]\', Observacion: \'$val[observacion]\', Muestra Doc: \'$val[muestra_doc]\', Estrucorg: \'$val[estrucorg]\', Arbproc: \'$val[arbproc]\', Apli Reg Estrorg: \'$val[apli_reg_estrorg]\', Apli Reg Arbproc: \'$val[apli_reg_arbproc]\', Workflow: \'$val[workflow]\', Semaforo: \'$val[semaforo]\', V Meses: \'$val[v_meses]\', Reviso: \'$val[reviso]\', Elaboro: \'$val[elaboro]\', Aprobo: \'$val[aprobo]\', Publico: \'$val[publico]\' ";
                    $this->registraTransaccionLog(2,$nuevo,$anterior, $atr[id]);
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
                 // HABILIYAR LA COLUMNA ESYAD0
                   // print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = "";
                    if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    $k = 1;                    
                    foreach ($this->parametros as $value) {
                        $sql_left .= " LEFT JOIN(select t1.id_registro, t2.descripcion as nom_detalle from mos_parametro_modulos t1
                                inner join mos_parametro_det t2 on t1.cod_categoria=t2.cod_categoria and t1.cod_parametro=t2.cod_parametro and t1.cod_parametro_det=t2.cod_parametro_det
                        where t1.cod_categoria='1' and t1.cod_parametro='$value[cod_parametro]' ) AS p$k ON p$k.id_registro = d.IDDoc "; 
                        $sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }
                    $filtro_ao ='';
                    if ((strlen($atr["b-id_organizacion"])>0)){                             
                        //$id_org = $this->BuscaOrgNivelHijos($atr["b-id_organizacion"]);
                        $id_org = ($atr["b-id_organizacion"]);
                        $filtro_ao .= " INNER JOIN ("
                                . " select IDDoc from mos_documentos_estrorg_arbolproc where id_organizacion_proceso in (". $id_org . ") GROUP BY IDDoc) as ao ON ao.IDDoc = d.IDDoc ";//" AND id_organizacion IN (". $id_org . ")";
                    }
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);                    
                    }
                    if (count($this->id_org_acceso_todos_nivel) <= 0){
                        $this->cargar_acceso_nodos_todos_nivel($atr);                    
                    }
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
                                                                                                                
                            if(($_SESSION[SuperUser]!='S')&&(isset($atr[terceros]))){
                                $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
                                $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
                                $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";    
                                /*SI NO ESTA EL FILTRO VER SOLO FLUJO DE TRABAJO*/
                                //if (strlen($atr["b-flujo-trabajo"])== 0)
                                {
                                    if (count($this->id_org_acceso))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ")) )"; 
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-publico"])>0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso))) . ")) )"; 
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
                                        $sql .= " OR ( d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ")) )"; 
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-publico"])>0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso))) . ")) )"; 
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
                    if (strlen($atr["b-formulario"])>0)
                                $sql .= " AND upper(formulario) like '%" . strtoupper($atr["b-formulario"]) . "%'";
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
                       
                       $sql .= " AND  (d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . "))"; 
                       if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-publico"])>0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso))) . ")) )"; 
                                                                      
                       $sql .= ")";
                    }
                    
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
                                    ,dao.arbol_organizacional arbol_organizacional
                                    ,(SELECT mos_nombres_campos.texto FROM mos_nombres_campos
                                    WHERE mos_nombres_campos.nombre_campo = d.etapa_workflow AND mos_nombres_campos.modulo = 6
                                    ) etapa_workflow
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
                            if(($_SESSION[SuperUser]!='S')&&(isset($atr[terceros]))){
                                $sql .= " and ((p.email='".$atr["email_usuario"]."') or ";
                                $sql .= " (wf.email_revisa ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_revision' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') or ";    
                                $sql .= " (wf.email_aprueba ='".$atr["email_usuario"]."' and (d.etapa_workflow='estado_pendiente_aprobacion' OR d.etapa_workflow='estado_aprobado') and d.estado_workflow='OK') ";    
                                /*SI NO ESTA EL FILTRO VER SOLO FLUJO DE TRABAJO*/                                
                                //if (strlen($atr["b-flujo-trabajo"])== 0)
                                {
                                    if (count($this->id_org_acceso))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ")) )"; 
                                    /*DOCUMENTOS PUBLICOS*/
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-publico"])>0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S'  and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso))) . ")) )"; 
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
                                        $sql .= " OR ( d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . ")) )"; 
                                    if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-publico"])>0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso))) . ")) )"; 
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
                    if (strlen($atr["b-formulario"])>0)
                                $sql .= " AND upper(formulario) like '%" . strtoupper($atr["b-formulario"]) . "%'";
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
                       
                       $sql .= " AND  (d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_keys($this->id_org_acceso)) . "))"; 
                       if ((count($this->id_org_acceso_todos_nivel))&&(strlen($atr["b-publico"])>0))
                                        $sql .= " OR ( d.etapa_workflow ='estado_aprobado' and d.vigencia = 'S' and d.publico ='S' and d.IDDoc IN (select IDDoc FROM mos_documentos_estrorg_arbolproc where id_organizacion_proceso IN (-1,". implode(',', array_diff (array_keys($this->id_org_acceso_todos_nivel),array_keys($this->id_org_acceso))) . ")) )"; 
                                                                      
                       $sql .= ")";
                    }
                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                    //print_r(array_keys($this->id_org_acceso));
                    //print_r($atr);
                    //  echo $sql;
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
                            $sql = "UPDATE mos_registro_item SET IDDoc = $codigo"
                                    . " WHERE IDDoc = $atr[id] ";
                            $this->dbl->insert_update($sql);
                        }
                        $nuevo = "IDDoc: \'$atr[id]\'";
                        $this->registraTransaccionLog(3,$nuevo,'',$atr[id]);
                        
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
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
            { //print_r($parametros);
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="dias_vig";
                if ($parametros['sorder'] == null) $parametros['sorder']="asc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-3-4-5-7-8-9-12-14-17-20-21";//"2-3-4-5-6-7-8-9-10-11-12-13-14-15-16-17-18-19-20-21-22-23-24-25-26-27-28-"; 
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
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
                import('clases.organizacion.ArbolOrganizacional');


                $ao = new ArbolOrganizacional();
                $ao->cargar_acceso_nodos_explicito($parametros);
                $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(1,$parametros);

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
                //$objResponse->addScript('setTimeout(function(){ init_documentos(); }, 500);');
                //$objResponse->addScript('setTimeout(function(){ init_tabla_reporte(); }, 500);');
                
                return $objResponse;
            }
         
 
            public function crear($parametros)
            {    session_name("$GLOBALS[SESSION]");
                session_start();            
                import('clases.organizacion.ArbolOrganizacional');
                $ao = new ArbolOrganizacional();
                $parametros[opcion] = 'simple';
                
                
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
                if($_SESSION[SuperUser]=='S'){
//                    $sql="SELECT wf.id,
//                            CONCAT('".$this->nombres_columnas[elaboro]."=>', 
//                            CONCAT(CONCAT(UPPER(LEFT(perso_responsable.apellido_paterno, 1)), LOWER(SUBSTRING(perso_responsable.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_responsable.apellido_materno, 1)), LOWER(SUBSTRING(perso_responsable.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_responsable.nombres, 1)), LOWER(SUBSTRING(perso_responsable.nombres, 2)))) 
//                            ,'&rarr;".$this->nombres_columnas[reviso]."=>', 
//                            IFNULL(CONCAT(CONCAT(UPPER(LEFT(perso_revisa.apellido_paterno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_revisa.apellido_materno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_revisa.nombres, 1)), LOWER(SUBSTRING(perso_revisa.nombres, 2)))) ,'N/A')
//                            ,'&rarr;".$this->nombres_columnas[aprobo]."=>', CONCAT(CONCAT(UPPER(LEFT(perso_aprueba.apellido_paterno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_aprueba.apellido_materno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_aprueba.nombres, 1)), LOWER(SUBSTRING(perso_aprueba.nombres, 2)))) ) as wf
//                            FROM mos_workflow_documentos AS wf
//                            left JOIN mos_personal AS perso_responsable ON wf.id_personal_responsable = perso_responsable.cod_emp
//                            left JOIN mos_personal AS perso_revisa ON wf.id_personal_revisa = perso_revisa.cod_emp
//                            INNER JOIN mos_personal AS perso_aprueba ON wf.id_personal_aprueba = perso_aprueba.cod_emp";
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
                                                                    , 'wf', $val['cod_emp_relator']);
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
                $contenido_1['TOK_NEW'] = time();
                $contenido_1['DESC_OPERACION_NOTIFICAR'] = "Guardar y Notificar";

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
                        //ENVIAR EMAIL SI ES GUARDAR Y NOTIFICAR
                        //print_r($parametros);
                        if($parametros['notificar']=='si'){
                            $correowf = $this->verWFemail($parametros[id]);
                            $this->cargar_nombres_columnas();
                            $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                            if($correowf[email]!='' && $correowf[recibe_notificaciones]=='S'){
                                $cuerpo = 'Usted tiene una notificación de un documento "'.$etapa.'"<br>';
                               // $correowf[email] = 'azambrano75@gmail.com';
                                $nombres = $correowf[apellido_paterno].' '.$correowf[nombres];
                                $ut_tool = new ut_Tool();
                                //SE ENVIA EL CORREO
                                $ut_tool->EnviarEMail('Notificaciones Mosaikus', array(array('correo' => $correowf[email], 'nombres'=>$nombres)), 'Notificaciones de Flujo de Trabajo', $cuerpo);
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
                                $mensaje=$noti->ingresarNotificaciones($atr);

                        }                        
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
                            echo $parametros["nro_pts_$i"];
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
                    $respuesta = $this->ingresarDocumentosVersion($parametros,$archivo,$doc_vis);
                   // echo($respuesta);
                        
                    
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
                        //ENVIAR EMAIL SI ES GUARDAR Y NOTIFICAR
                        if($parametros['notificar']=='si'){
                            $correowf = $this->verWFemail($params[id_registro]);
                            $this->cargar_nombres_columnas();
                            $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                            if($correowf[email]!='' && $correowf[recibe_notificaciones]=='S'){
                                $cuerpo = 'Usted tiene una notificación de un documento "'.$etapa.'"<br>';
                                //$correowf[email] = 'azambrano75@gmail.com';
                                $nombres = $correowf[apellido_paterno].' '.$correowf[nombres];
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
                $contenido_1[CHECKED_PUBLICO] = $val["publico"] == 'S' ? 'checked="checked"' : '';
                $contenido_1['SEMAFORO'] = $ut_tool->combo_array("semaforo", $desc, $ids,false,$val["semaforo"],false,false,false,false,'display:inline;width:70px');

                $ids = array('0'); 
                $desc = array('00');
                for($i=1;$i<=48;$i++){                    
                    $desc[] = str_pad($i, 2, "0", STR_PAD_LEFT);                    
                    $ids[] = $i;
                }
               // echo $_SESSION['CookCodEmp'].'-'.$_SESSION[SuperUser];
                
                $contenido_1['V_MESES'] = $ut_tool->combo_array("v_meses", $desc, $ids,false,$val["v_meses"],false,false,false,false,'display:inline;width:70px');
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
                            IFNULL(CONCAT(CONCAT(UPPER(LEFT(perso_revisa.apellido_paterno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_revisa.apellido_materno, 1)), LOWER(SUBSTRING(perso_revisa.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_revisa.nombres, 1)), LOWER(SUBSTRING(perso_revisa.nombres, 2)))) ,'N/A')
                            ,' &rarr; ', CONCAT(CONCAT(UPPER(LEFT(perso_aprueba.apellido_paterno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(perso_aprueba.apellido_materno, 1)), LOWER(SUBSTRING(perso_aprueba.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(perso_aprueba.nombres, 1)), LOWER(SUBSTRING(perso_aprueba.nombres, 2)))) ) as wf
                            FROM mos_workflow_documentos AS wf
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
                $sql = "SELECT fecha_registro f1,DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%m')fecha_registro, descripcion_operacion, "
                        . "CONCAT(CONCAT(UPPER(LEFT(user.nombres, 1)), LOWER(SUBSTRING(user.nombres, 2))),' ', CONCAT(UPPER(LEFT(user.apellido_paterno, 1)), LOWER(SUBSTRING(user.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(user.apellido_materno, 1)), LOWER(SUBSTRING(user.apellido_materno, 2)))) usuario "
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
                $contenido_1['ITEMS_HISTO'] = $item_histo;
                $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                $template->setTemplate("formulario_editar");
                //$template->setVars($contenido_1);
                //print_r($val);
                if($val["etapa_workflow"]=='estado_aprobado' && $val["estado_workflow"]=='OK')
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
                $contenido_1['DESC_OPERACION_NOTIFICAR'] = "Guardar y Notificar";
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
                $objResponse->addScript('ao_multiple();');
                $objResponse->addScript("$('#tabs-hv-2').tab();"
                        . "$('#tabs-hv-2 a:first').tab('show');");
                $objResponse->addScript("$js");
                $objResponse->addScript("$jswf");
                $objResponse->addScript("$js_din");
                $objResponse->addScript($js_cambiar_archivos);
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
                        //print_r($parametros);
                        //print_r($parametros[nodos]);
                        //ENVIAR EMAIL SI ES GUARDAR Y NOTIFICAR
                        if($parametros['notificar']=='si'){
                            $correowf = $this->verWFemail($parametros[id]);
                           // print_r($correowf);
                            $this->cargar_nombres_columnas();
                            $etapa = $this->nombres_columnas[$correowf[etapa_workflow]];
                            if($correowf[email]!=''&& $correowf[recibe_notificaciones]=='S'){
                                $cuerpo = 'Usted tiene una notificación de un documento "'.$etapa.'"<br>';
                               // $correowf[email] = 'azambrano75@gmail.com';
                                $nombres = $correowf[apellido_paterno].' '.$correowf[nombres];
                                $ut_tool = new ut_Tool();
                                $ut_tool->EnviarEMail('Notificaciones Mosaikus', array(array('correo' => $correowf[email], 'nombres'=>$nombres)), 'Notificaciones de Flujo de Trabajo', $cuerpo);
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
                
                $parametros[terceros] = 'S';
                $parametros[terceros] = 'S';
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
                        . "CONCAT(CONCAT(UPPER(LEFT(user.nombres, 1)), LOWER(SUBSTRING(user.nombres, 2))),' ', CONCAT(UPPER(LEFT(user.apellido_paterno, 1)), LOWER(SUBSTRING(user.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(user.apellido_materno, 1)), LOWER(SUBSTRING(user.apellido_materno, 2)))) usuario "                        
                        . "FROM mos_historico_wf_documentos wf inner join mos_usuario user "
                        . " on wf.id_usuario = user.id_usuario "
                        . " WHERE IDDoc = $parametros[id] order by f1 desc";
                //echo $sql;
                $historia = $this->dbl->query($sql, array());
                $item_histo .='<table class=table table-striped table-condensed  width=100%>';
                    $item_histo .="<thead><tr>";
                    $item_histo .="<th>Fecha</th>";
                    $item_histo .="<th>Operacion</th>";
                    $item_histo .="<th>Usuario</th>";
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
                        <iframe src="http://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;" frameborder="0"></iframe>
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
            {   ///print_r($parametros);
                
                /*FILTRA LOS DOCUMENTOS QUE ESTAN VIGENTES*/
                $parametros["b-vigencia"] = 'S';
                $grid = $this->verListaDocumentosReporte($parametros);
                $objResponse = new xajaxResponse();
                
                $objResponse->addScript("limpiar_titulo();");
                if ((strlen($parametros['b-id_organizacion'])>0)&&(!strpos($parametros['b-id_organizacion'],','))){
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
                        if($correowf[email]!=''){
                            $cuerpo = 'Usted tiene una notificación de un documento "'.$etapa.'"<br>';
                           // $correowf[email] = 'azambrano75@gmail.com';
                            $nombres = $correowf[apellido_paterno].' '.$correowf[nombres];
                            //echo $cuerpo;
                        }
                        if($correowf[estado_workflow]=='RECHAZADO'){
                            $cuerpo .= 'Rechazado por:<br><span style="color:red">'.$correowf[observacion_rechazo].'</span>';
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
                                if(sizeof($from)>0 || sizeof($cc)>0)
                                    $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, 'Notificaciones de Flujo de Trabajo', $cuerpo, array(),$cc);                                
                            }            
                        }

                        //SE CARGA LA NOTIFICACION
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
                                        if(!class_exists('Template')){
                                            import("clases.interfaz.Template");
                                        }
                                        $contenido   = array();
                                        $contenido['ELABORADOR']=$correowf[nombre_responsable];
                                        $contenido['DOCUMENTO']=$val[Codigo_doc].'-'.$val[nombre_doc].'-V'.  str_pad($val["version"], 2, "0", STR_PAD_LEFT);
                                        $template = new Template();
                                        $template->PATH = PATH_TO_TEMPLATES.'documentos/';
                                        $template->setTemplate("cuerpo_notificacion");
                                        $template->setVars($contenido);
                                        $cuerpo = $template->show();

                                        //echo $cuerpo;
                                        if($correowf[recibe_notificaciones_revisa]=='S'){
                                            $cc = array(array('correo' => $correowf[email_revisa], 'nombres'=>$correowf[nombre_revisa]));
                                            //$cc = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_revisa]));
                                        }
                                        if($correowf[recibe_notificaciones_responsable]=='S'){
                                            $from = array(array('correo' => $correowf[email_responsable], 'nombres'=>$correowf[nombre_responsable]));
                                            //$from = array(array('correo' => 'azambrano75@gmail.com', 'nombres'=>$correowf[nombre_responsable]));
                                        }
                                        if(sizeof($from)>0 || sizeof($cc)>0)
                                            $ut_tool->EnviarEMail('Notificaciones Mosaikus', $from, 'Notificaciones de Flujo de Trabajo', $cuerpo, array(),$cc);                                
                                        $atr[asunto]='Tiene un documento Aprobado';
                                    }
                            
                            $atr[modulo]='DOCUMENTOS';
                            $atr[funcion] = "verWorkFlowPopup(".$val[IDDoc].");";
                            $atr[email]=$correowf[email];
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
 }?>
