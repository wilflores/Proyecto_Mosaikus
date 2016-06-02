<?php
        function archivo_reg($tupla)
        {
            $html = "<a target=\"_blank\" title=\"Ver\" href=\"pages/registros/descargar_archivo.php?id=$tupla[idRegistro]&token=" . md5($tupla[idRegistro]) ."\">
               
                    <i class=\"icon icon-view-document\"></i>
                            
                        </a>";            
            //<img class=\"SinBorde\"  src=\"diseno/images/pdf.png\">
            return $html;
        }

     
        
?>
<?php
 import("clases.interfaz.Pagina");        
        class Registros extends Pagina{
        private $templates;
        private $bd;
        private $total_registros;
        private $parametros;
        private $nombres_columnas;
        private $placeholder;
        private $id_org_acceso;
        private $per_crear;
        private $per_editar;
        private $per_eliminar;
        private $colummas_arbol;
            
            public function Registros(){
                parent::__construct();
                $this->asigna_script('registros/registros.js');                                             
                $this->dbl = new Mysql($this->encryt->Decrypt_Text($_SESSION[BaseDato]), $this->encryt->Decrypt_Text($_SESSION[LoginBD]), $this->encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $this->parametros = $this->nombres_columnas = $this->placeholder = $this->funciones = array();
                $this->contenido = array();
                $this->id_org_acceso = array();
                $this->colummas_arbol = array();
                $this->per_crear = $this->per_editar = $this->per_eliminar = 'N';
            }
            
            public function colum_admin($tupla)
            { //print_r($tupla);
                /*Si tiene activo campos tipo persona o tipo arbol*/
                //print_r($this->colummas_arbol);
                if (count($this->colummas_arbol)>0){
                    $editar = false;
                    foreach ($this->colummas_arbol as $value) {
                        $organizacion = array();
                        if(strpos($tupla[$value],',')){    
                            $organizacion = explode(",", $tupla[$value]);
                        }
                        else{
                            $organizacion[] = $tupla[$value]; 
                            if (strlen($tupla[$value])<=0){
                                if ($_SESSION[SuperUser] == 'S'){
                                    $editar = true;
                                    break;
                                }
                            }
                        }
                        
                        
                        //print_r($organizacion);
                        foreach ($organizacion as $value_2) {
                            if (isset($this->id_org_acceso[$value_2])){
                                if(($this->id_org_acceso[$value_2][modificar]=='S'))
                                    $editar = true;
                            } else{
                                $editar = false;
                                break;
                            }
                        }
                        if (!($editar == true))
                            break;
                        
                    }
                    if ($editar == true)
                    {                    
                        $html = "<a href=\"#\" onclick=\"javascript:editarRegistros('". $tupla[idRegistro] . "');\"  title=\"Editar Registros\">                            
                                    <i class=\"icon icon-edit\"></i>
                                </a>";
                    }
                    $editar = false;
                    foreach ($this->colummas_arbol as $value) {
                        $organizacion = array();
                        if(strpos($tupla[$value],',')){    
                            $organizacion = explode(",", $tupla[$value]);
                        }
                        else{
                            $organizacion[] = $tupla[$value];  
                            if (strlen($tupla[$value])<=0){
                                if ($_SESSION[SuperUser] == 'S'){
                                    $editar = true;
                                    break;
                                }
                            }
                        }
                        foreach ($organizacion as $value_2) {
                            if (isset($this->id_org_acceso[$value_2])){
                                if(($this->id_org_acceso[$value_2][eliminar]=='S'))
                                    $editar = true;
                            } else{
                                $editar = false;
                                break;
                            }
                        }
                        if (!($editar == true))
                            break;
                        
                    }
                    if ($editar == true)
                    {
                        $html .= '<a href="#" onclick="javascript:eliminarRegistros(\''. $tupla[idRegistro] . '\');" title="Eliminar Registros">
                                <i class="icon icon-remove"></i>

                            </a>'; 
                    }
                    if ($tupla[actualizacion_activa] == 'S'){
                        //<img title="Crear Versión '.$tupla[nombre_doc].'" src="diseno/images/ticket_ver.png" style="cursor:pointer">
                        $html .= '<a href="#" onclick="javascript:crearActualizacionRegistro(\''. $tupla[idRegistro] . '\');" title="Crear actualizacion de este registro">                        
                                    <i class="icon icon-v"></i>
                            </a>'; 
                    }                    
                }
                else{
                    /*Validacion sencilla segun rol asignado al usuario en el modulo de registros*/
                    if ($this->per_editar == 'S')
                    {                    
                        $html = "<a href=\"#\" onclick=\"javascript:editarRegistros('". $tupla[idRegistro] . "');\"  title=\"Editar Registros\">                            
                                    <i class=\"icon icon-edit\"></i>
                                </a>";
                    }
                    if ($this->per_eliminar == 'S')
                    {
                        $html .= '<a href="#" onclick="javascript:eliminarRegistros(\''. $tupla[idRegistro] . '\');" title="Eliminar Registros">
                                <i class="icon icon-remove"></i>

                            </a>'; 
                    }
                    if ($tupla[actualizacion_activa] == 'S'){
                        //<img title="Crear Versión '.$tupla[nombre_doc].'" src="diseno/images/ticket_ver.png" style="cursor:pointer">
                        $html .= '<a href="#" onclick="javascript:crearActualizacionRegistro(\''. $tupla[idRegistro] . '\');" title="Crear actualizacion de este registro">                        
                                    <i class="icon icon-v"></i>
                            </a>'; 
                    }
                    
                }
               return $html;

            }
            
            /**
             * Busca los permisos que tiene el usuario en el modulo
             */
            private function cargar_permisos_simple($parametros){
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();
                    $data_permisos = $acceso->obtenerPermisosModulo($_SESSION[CookIdUsuario],$parametros[cod_link]);                    
                    foreach ($data_permisos as $value) {
                        if ($value[nuevo] == 'S'){
                            $this->per_crear =  'S';
                            break;
                        }
                    }                                               
                    foreach ($data_permisos as $value) {
                        if ($value[modificar] == 'S'){
                            $this->per_editar =  'S';
                            break;
                        }
                    } 
                    foreach ($data_permisos as $value) {
                        if ($value[eliminar] == 'S'){
                            $this->per_eliminar =  'S';
                            break;
                        }
                    } 
                }
            }
            
            /**
             * Devuelve si el usuario tiene permiso de crear personas
             * @param array $parametros 
             * @return string
             */
            private function permiso_crear($parametros){
                if (count($this->id_org_acceso) <= 0){
                    $this->cargar_acceso_nodos($parametros);
                }                
                foreach ($this->id_org_acceso as $value) {
                    if ($value[nuevo] == 'S'){
                        return 'S';
                    }
                }                
                return 'N';
            }
            
            /**
             * Activa los nodos donde se tiene explicitamente acceso
             */
            private function cargar_acceso_nodos($parametros){
                if (strlen($parametros[cod_link])>0){
                    if(!class_exists('mos_acceso')){
                        import("clases.mos_acceso.mos_acceso");
                    }
                    $acceso = new mos_acceso();
                    $data_ids_acceso = $acceso->obtenerNodosArbol($_SESSION[CookIdUsuario],$parametros[cod_link],$parametros[modo],$parametros[terceros]);
                    foreach ($data_ids_acceso as $value) {
                        $this->id_org_acceso[$value[id]] = $value;
                    }                                            
                }
            }    

            private function operacion($sp, $atr){
                $param=array();
                $this->dbl->data = $this->dbl->query($sp, $param);
            }
            
            private function cargar_parametros(){
                session_name("$GLOBALS[SESSION]");
                session_start();   
                $sql = "SELECT 
                                id_unico
                                ,IDDoc
                                ,Nombre
                                ,tipo
                                ,valores
                         FROM mos_documentos_datos_formulario 
                         WHERE IDDoc = $_SESSION[IDDoc] ORDER BY orden";
                //echo $sql;
                $this->parametros = $this->dbl->query($sql, array());
                //print_r($this->parametros);
            }
            
            private function cargar_nombres_columnas(){
                $sql = "SELECT nombre_campo, texto FROM mos_nombres_campos WHERE modulo = 9";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->nombres_columnas[$value[nombre_campo]] = $value[texto];
                }
                
            }
            
        public function archivo_reg($tupla)
        {
            $html = "<a target=\"_blank\" title=\"Ver\" href=\"pages/registros/descargar_archivo.php?id=$tupla[idRegistro]&token=" . md5($tupla[idRegistro]) ."\">
               
                    <i class=\"icon icon-view-document\"></i>
                            
                        </a>";            
            //<img class=\"SinBorde\"  src=\"diseno/images/pdf.png\">
            return $html;
        }
            
            public function estado_columna($tupla,$key){            
            if ($tupla[$key]== '1'){
                return "<img src=\"diseno/images/verde.png\"/>";
            }
            if ($tupla[$key]== '2'){
                return "<img src=\"diseno/images/amarillo.png\" /> ";
            }
            if ($tupla[$key]== '3'){
                return "<img src=\"diseno/images/rojo.png\"/ >";
            }
            return "";
        }
        
        public function estado_columna_excel($tupla,$key){            
            if ($tupla[$key]== 'Bueno'){
                return "<img src=\"".PATH_TO_IMG."verde.png\"/ title=\"Verde\">";
            }
            return "<img src=\"".PATH_TO_IMG."rojo.png\"/ title=\"Rojo\">";
        }
            
            private function cargar_placeholder(){
                $sql = "SELECT nombre_campo, placeholder FROM mos_nombres_campos WHERE modulo = 9";
                $nombres_campos = $this->dbl->query($sql, array());
                foreach ($nombres_campos as $value) {
                    $this->placeholder[$value[nombre_campo]] = $value[placeholder];
                }
                
            }


            public function verDocumentoPDF($id){
                $atr=array();
                $sql = "SELECT                             
                            identificacion
                            ,doc_fisico
                            ,contentType    
                            ,idRegistro
                            ,descripcion
                         FROM mos_registro 
                         WHERE idRegistro = $id ";  
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }

             public function verRegistros($id){
                $atr=array();
                $sql = "SELECT idRegistro
                            ,IDDoc
                            ,identificacion
                            ,version
                            ,id_usuario
                            ,descripcion
                            ,1 doc_fisico
                            ,contentType
                            ,id_procesos
                            ,id_organizacion
                            ,idRegistro_original
                         FROM mos_registro 
                         WHERE idRegistro = $id "; 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
            
            public function verCamposDinamicos(){
                $atr=array();
                $sql = "SELECT 
                                id_unico
                                ,IDDoc
                                ,Nombre
                                ,tipo
                                ,valores
                         FROM mos_documentos_datos_formulario 
                         WHERE IDDoc = $_SESSION[IDDoc] ORDER BY orden"; 
                $this->operacion($sql, $atr);
                return $this->dbl->data;
            }
            
            public function verItemsCamposDinamicos($id_unico){
                $atr=array();
                $sql = "SELECT 
                                id
                                ,descripcion                                
                         FROM mos_documentos_formulario_items 
                         WHERE fk_id_unico = $id_unico and vigencia = 'S' ORDER BY descripcion"; 
                //echo $sql;
                return $this->dbl->query($sql);                
            }
            
             public function verArbol($id_unico,$idRegistro){
                 //print_r($atr);
                $atr=array();
                $sql = "SELECT idRegistro,GROUP_CONCAT(valor) id
                         FROM mos_registro_item 
                         WHERE id_unico = ". $id_unico." and idRegistro =". $idRegistro." and tipo='11'
                         group by idRegistro;"; 
                $this->operacion($sql, $atr);
               //echo $sql;
                return $this->dbl->data[0][id];
            }           
             public function verArbolP($id_unico,$idRegistro){
                $atr=array();
                $sql = "SELECT idRegistro,GROUP_CONCAT(valor) id
                         FROM mos_registro_item 
                         WHERE id_unico = ". $id_unico." and idRegistro =". $idRegistro." and tipo='12'
                         group by idRegistro;;"; 
                $this->operacion($sql, $atr);
                //echo $this->dbl->data[0][id];
                return $this->dbl->data[0][id];
            }           
            public function verValoresCamposDinamicos($id){
                $atr=array();
                $sql = "SELECT 
                                df.id_unico
                                ,df.IDDoc
                                ,df.Nombre
                                ,df.tipo
                                ,valores
				,rf.Nombre valor
				,rf.idRegistro
                                ,df.orden
                         FROM mos_documentos_datos_formulario df
                            left join mos_registro_formulario rf on rf.id_unico = df.id_unico AND rf.idRegistro = $id 
                         WHERE df.IDDoc = $_SESSION[IDDoc] and df.tipo in (1,2,3,4,5,6,10,13)
                         union all    
                        SELECT 
                            df.id_unico
                            ,df.IDDoc
                            ,df.Nombre
                            ,df.tipo
                            ,valores
                            ,GROUP_CONCAT(rf.valor) valor
                            ,rf.idRegistro
                             ,df.orden
                        FROM mos_documentos_datos_formulario df
                        left join mos_registro_item rf on rf.id_unico = df.id_unico  AND rf.idRegistro = $id
                        WHERE df.IDDoc = $_SESSION[IDDoc] and df.tipo in (7,8,9,11,12,14)
                        group by
                            df.id_unico
                            ,df.IDDoc
                            ,df.Nombre
                            ,df.tipo
                            ,valores
                            ,rf.idRegistro
                             ,df.orden
                        ORDER BY 8; "; 
                $this->operacion($sql, $atr);
                //echo $sql;
                return $this->dbl->data;
            }
            
            public function verDocumentos($id){
                $atr=array();
                $sql = "SELECT IDDoc
                                ,Codigo_doc
                                ,nombre_doc
                                ,version
                                
                         FROM mos_documentos 
                         WHERE IDDoc = $id ";                 
                $this->operacion($sql, $atr);
                return $this->dbl->data[0];
            }
              public function DocTieneArbolRegistros($tipo){
                $atr=array();
                $sql = "SELECT
                        IFNULL(count(id_unico),0) cant
                        FROM
                        mos_documentos_datos_formulario
                        WHERE
                        IDDoc= ".$_SESSION[IDDoc]." and 
                         tipo = '".$tipo."';";                 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0]['cant'];
            }          
              public function DocTieneCampo($tipo){
                $atr=array();
                $sql = "SELECT
                        IFNULL(count(id_unico),0) cant
                        FROM
                        mos_documentos_datos_formulario
                        WHERE
                        IDDoc= ".$_SESSION[IDDoc]." and 
                         tipo = '".$tipo."';";                 
                //echo $sql;
                $this->operacion($sql, $atr);
                return $this->dbl->data[0]['cant'];
            }
    
        public function BuscaProceso($tupla)
        {
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $OrgNom = "";
                if (strlen($tupla[id_organizacion]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_arbol_procesos where id in ($tupla[id_organizacion])";
                        //echo $Consulta3;
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if($Fila3[organizacion_padre]==2)
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= $this->BuscaProceso(array('id_organizacion' => $Fila3[organizacion_padre])) . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= $_SESSION[CookNomEmpresa];
                return $OrgNom;

        }
 function BuscaProcesoExcel($tupla,$key)
        {   //print_r($tupla);
            //echo $tupla[id_unico]
            if($tupla[$key]!=''){     
                $reg_arbol =  explode(',',$tupla[$key]);
                $encryt = new EnDecryptText();
                //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $Nivls = "";
               // print_r($reg_arbol);
                {                                           
                        //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        foreach ($reg_arbol as $value) 
                        {                                                        
                            $Nivls .= $this->BuscaProceso(array('id_organizacion' => $value))."<br />";
                        }
                        if($Nivls!='')
                                $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                        else
                                $Nivls='-- Sin información --';
                }
            }
            else
                $Nivls='-- Sin información --';        
            //return $tupla[analisis_causal];           
            return $Nivls;

        } 
function semaforo($tupla,$key)
{ 
  // print_r($tupla);
   $vig =  explode(',',$tupla[$key]);
   
   $edo =  $vig[0];
   $dias = $vig[1];
 //   echo $key;
   if ($edo=='V'){
       $html = "<img class=\"SinBorde\" title=\"Vencida y tiene ".($dias*-1)." dia(s) vencida\" src=\"diseno/images/rojo.png\">";                                                                    
       return $html.'&nbsp;'.$dias*-1;
   }
   if ($edo=='P'){
       $html = "<img class=\"SinBorde\" title=\"Vigente pero le quedan $dias dia(s) de vigencia\" src=\"diseno/images/amarillo.png\">";                                                                    
       return $html.'&nbsp;'.$dias;
   }
   return "<img class=\"SinBorde\" title=\"Vigente y le quedan $dias dias\" src=\"diseno/images/verde.png\">&nbsp;".$dias;
}       

function semaforoExcel($tupla,$key)
{ 
  // print_r($tupla);
   $vig =  explode(',',$tupla[$key]);
   
   $edo =  $vig[0];
   $dias = $vig[1];
 //   echo $key;
   if ($edo=='V'){
       return 'Vencida';
   }
   if ($edo=='P'){
       return 'Vigente';
   }
   return 'Vigente';
}  
 function BuscaOrganizacionalExcel($tupla,$key)
        {   //print_r($tupla);
            //echo $tupla[id_unico]
            if($tupla[$key]!=''){
                $reg_arbol =  explode(',',$tupla[$key]);
                //$encryt = new EnDecryptText();
                //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $Nivls = "";
                //print_r($reg_arbol);
                {                                           
                        //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        foreach ($reg_arbol as $value) 
                        {                                                        
                            $Nivls .= $this->BuscaOrganizacional(array('id_organizacion' => $value),'id_organizacion')."<br />";
                        }
                        if($Nivls!='')
                                $Nivls=substr($Nivls,0,strlen($Nivls)-6);
                        else
                                $Nivls='-- Sin información --';
                }
            }
            else
                $Nivls='-- Sin información --';
            //return $tupla[analisis_causal];           
            return $Nivls;

        } 
        
        function BuscaOrganizacionalTodosVerMas($tupla,$key)
        {   //print_r($tupla);
            //echo $tupla[id_unico]
            if($tupla[$key]!=''){
                $reg_arbol =  explode(',',$tupla[$key]);
                //$encryt = new EnDecryptText();
                //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $Nivls = "";
                //print_r($reg_arbol);
                {                                           
                        //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        foreach ($reg_arbol as $value) 
                        {                                                        
                            $Nivls .= $this->BuscaOrganizacional(array('id_organizacion' => $value))."<br /><br />";
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
                            <a href="#" tok="' .$key.$tupla[idRegistro]. '-doc" class="ver-mas">
                                <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                                <input type="hidden" id="ver-mas-' .$key.$tupla[idRegistro]. '-doc" value="'.$Nivls.'"/>
                            </a>';
                        }
                        $valor_final .= "<br /><br />";

                    }

                    return substr($Nivls, 0, 200) . '.. <br/>
                        <a href="#" tok="' .$key.$tupla[idRegistro]. '-doc" class="ver-mas">
                            <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                            <input type="hidden" id="ver-mas-' .$key.$tupla[idRegistro]. '-doc" value="'.$Nivls.'"/>
                        </a>';
                }
                //return $tupla[analisis_causal];
                }
            else
                $Nivls='-- Sin información --';
            return $Nivls;

        }
function BuscaOrganizacional($tupla,$key='id_organizacion')
        {
            //$encryt = new EnDecryptText();
            //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
            $OrgNom = "";
                if (strlen($tupla[$key]) > 0) {                                           
                        $Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[$key])";
                        $Resp3 = $this->dbl->query($Consulta3,array());

                        foreach ($Resp3 as $Fila3) 
                        {
                                if($Fila3[organizacion_padre]==2)
                                {
                                        $OrgNom.=($Fila3[identificacion]);
                                        return($OrgNom);                                        
                                }
                                else
                                {
                                        $OrgNom .= $this->BuscaOrganizacional(array('id_organizacion' => $Fila3[organizacion_padre]),'id_organizacion') . ' -> ' . ($Fila3[identificacion]);
                                }
                        }
                }
                else
                    $OrgNom .= $_SESSION[CookNomEmpresa];
                return $OrgNom;

        }
                
        function BuscaProcesoTodosVerMas($tupla,$key)
        {   //print_r($tupla);
            //echo $tupla[id_unico]
             if($tupla[$key]!=''){
                $reg_arbol =  explode(',',$tupla[$key]);
                $encryt = new EnDecryptText();
                //$dbl = new Mysql($encryt->Decrypt_Text($_SESSION[BaseDato]), $encryt->Decrypt_Text($_SESSION[LoginBD]), $encryt->Decrypt_Text($_SESSION[PwdBD]) );
                $Nivls = "";
                //print_r($reg_arbol);
                {                                           
                        //$Consulta3="select id as id_organizacion,parent_id as organizacion_padre, title as identificacion from mos_organizacion where id in ($tupla[id_organizacion])";
                        foreach ($reg_arbol as $value) 
                        {                                                        
                            $Nivls .= $this->BuscaProceso(array('id_organizacion' => $value))."<br /><br />";
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
                            <a href="#" tok="' .$key.$tupla[idRegistro]. '-doc" class="ver-mas">
                                <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                                <input type="hidden" id="ver-mas-' .$key.$tupla[idRegistro]. '-doc" value="'.$Nivls.'"/>
                            </a>';
                        }
                        $valor_final .= "<br /><br />";

                    }

                    return substr($Nivls, 0, 200) . '.. <br/>
                        <a href="#" tok="' .$key.$tupla[idRegistro]. '-doc" class="ver-mas">
                            <i class="glyphicon glyphicon-search" href="#search"></i> Ver Mas
                            <input type="hidden" id="ver-mas-' .$key.$tupla[idRegistro]. '-doc" value="'.$Nivls.'"/>
                        </a>';
                }        
             }
            else
                $Nivls='-- Sin información --';
            //return $tupla[analisis_causal];
            
            return $Nivls;

        }
            
            public function ingresarRegistros($atr,$archivo){
               // print_r($atr);
                try {
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion
                    $atr[doc_fisico] = $archivo;
                    if($atr['r-id-original'] == '') 
                        $atr['idRegistro_original']='NULL' ;
                    else
                        $atr['idRegistro_original']=$atr['r-id-original'] ;
                    $sql = "INSERT INTO mos_registro(IDDoc,identificacion,id_usuario,descripcion, idRegistro_original,doc_fisico,contentType)
                            VALUES(
                                $_SESSION[IDDoc],'$atr[identificacion]',$atr[id_usuario],'$atr[descripcion]',$atr[idRegistro_original],'$atr[doc_fisico]','$atr[contentType]'
                                )";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                    /*
                    $this->registraTransaccion('Insertar','Ingreso el mos_registro ' . $atr[descripcion_ano], 'mos_registro');
                      */
                    
                    $sql = "SELECT MAX(idRegistro) ultimo FROM mos_registro"; 
                    $this->operacion($sql, $atr);
                    $idregistro = $this->dbl->data[0][0];
                    // actualizamos las actualizacion en caso de que aplique
                    //echo 'aqui:'.$atr[idRegistro_original];
                    if ($atr[idRegistro_original]!='NULL'){
                        $sql = "update mos_registro
				set vigencia = 'N'
				where (idRegistro_original = ".$atr[idRegistro_original].") AND 
                                (idRegistro <> ".$idregistro." );";
                        $this->dbl->insert_update($sql);
                       // echo $sql;
                    }
                    else{
                        $sql = "update mos_registro
				set idRegistro_original = ".$idregistro."
				where idRegistro = ".$idregistro." ;";
                        $this->dbl->insert_update($sql);

                    }
                     // echo $sql;
                    $nuevo = "IdRegistro: \'".$idregistro."\', IDDoc: \'$_SESSION[IDDoc]\', Identificacion: \'$atr[identificacion]\',  Id Usuario: \'$atr[id_usuario]\', Descripcion: \'$atr[descripcion]\', ContentType: \'$atr[contentType]\', Id Procesos: \'$atr[id_procesos]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                    $this->registraTransaccionLog(7,$nuevo,'', '');
                    return $idregistro;
                    return "El mos_registro '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
            public function ingresarRegistrosCampoDinamico($atr){
               // print_r($atr);
                try {
                    //s1<br/>s2<br/>s3
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion                    
                    if($atr[tipo]=='11' ||  $atr[tipo]=='12' || $atr[tipo]=='7' || $atr[tipo]=='8' || $atr[tipo]=='9' || $atr[tipo]=='14')
                    {   if($atr[tipo]!='14' ){
                            if (strpos($atr[Nombre],"<br>")||strpos($atr[Nombre],"<br/>")) {
                                if (strpos($atr[Nombre],"<br>")) 
                                    $reg =  explode(',',str_replace("<br>", ",", $atr[Nombre]));                    
                                if (strpos($atr[Nombre],"<br/>")) 
                                    $reg =  explode(',',str_replace("<br/>", ",", $atr[Nombre]));
                            }
                            else
                                $reg =  explode(',',$atr[Nombre]);
                        }
                        else
                           if($atr[tipo]=='14') 
                               $reg=$atr[Nombre]; 
                           else
                               $reg[]=$atr[Nombre]; 
                        foreach ($reg as $value){
                        $sql = "INSERT INTO mos_registro_item(IDDoc,idRegistro,valor,tipo,id_unico)
                            VALUES(
                                $_SESSION[IDDoc],$atr[idRegistro],'$value','$atr[tipo]',$atr[id_unico]
                                );";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                        // echo $sql;
                        $this->dbl->insert_update($sql);
                        }        
                    }
                    else{
                        $sql = "INSERT INTO mos_registro_formulario(IDDoc,idRegistro,Nombre,tipo,id_unico)
                            VALUES(
                                $_SESSION[IDDoc],$atr[idRegistro],'$atr[Nombre]','$atr[tipo]',$atr[id_unico]
                                );";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                        $this->dbl->insert_update($sql);
                    }
                    //echo $sql;
                    

                    return "El mos_registro '$atr[descripcion_ano]' ha sido ingresado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }
            
             public function modificarRegistrosCampoDinamico($atr){
                // print_r($atr);
                try {
                    $atr = $this->dbl->corregir_parametros($atr);//,version,correlativo,id_procesos,id_organizacion                    
                    if($atr[tipo]=='11' ||  $atr[tipo]=='12' || $atr[tipo]=='7' || $atr[tipo]=='8' || $atr[tipo]=='9' || $atr[tipo]=='14')
                    {   if($atr[tipo]!='14'){
                            if (strpos($atr[Nombre],"<br>")||strpos($atr[Nombre],"<br/>")) {
                                if (strpos($atr[Nombre],"<br>")) 
                                    $reg =  explode(',',str_replace("<br>", ",", $atr[Nombre]));                    
                                if (strpos($atr[Nombre],"<br/>")) 
                                    $reg =  explode(',',str_replace("<br/>", ",", $atr[Nombre]));
                            }
                            else
                                $reg =  explode(',',$atr[Nombre]);
                        }
                        else
                           if($atr[tipo]=='14') 
                               $reg=$atr[Nombre]; 
                           else
                               $reg[]=$atr[Nombre];                         
                        $respuesta = $this->dbl->delete("mos_registro_item", "idRegistro = " . $atr[idRegistro]. " and id_unico = " . $atr[id_unico]. " AND tipo ='".$atr[tipo]."'");  
                        foreach ($reg as $value){
                        $sql = "INSERT INTO mos_registro_item(IDDoc,idRegistro,valor,tipo,id_unico)
                            VALUES(
                                $_SESSION[IDDoc],$atr[idRegistro],'$value','$atr[tipo]',$atr[id_unico]
                                );";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                        //echo $sql;
                        $this->dbl->insert_update($sql);
                        }        
                    }  
                    else{
                    $sql = "UPDATE mos_registro_formulario SET Nombre = '$atr[Nombre]'
                            WHERE idRegistro = $atr[idRegistro] AND id_unico = $atr[id_unico]
                            ";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                    //echo $sql;
                        $this->dbl->insert_update($sql);
                        if (isset($atr['nuevo']) && ($atr['nuevo'] == 1)){
                            $sql = "INSERT INTO mos_registro_formulario(IDDoc,idRegistro,Nombre,tipo,id_unico)
                            VALUES(
                                $_SESSION[IDDoc],$atr[idRegistro],'$atr[Nombre]','$atr[tipo]',$atr[id_unico]
                                );";//,$atr[version],$atr[correlativo],$atr[id_procesos],$atr[id_organizacion]
                                $this->dbl->insert_update($sql);
                        }
                    }

                    return "El mos_registro '$atr[descripcion_ano]' ha sido ingresado con exito";
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

            public function modificarRegistros($atr){
                try {
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql = "UPDATE mos_registro SET                            
                                    idRegistro = $atr[idRegistro],IDDoc = $atr[IDDoc],identificacion = '$atr[identificacion]',version = $atr[version],correlativo = $atr[correlativo],id_usuario = $atr[id_usuario],descripcion = '$atr[descripcion]',doc_fisico = $atr[doc_fisico],contentType = '$atr[contentType]',id_procesos = $atr[id_procesos],id_organizacion = $atr[id_organizacion]
                            WHERE  id = $atr[id]";      
                    $val = $this->verRegistros($atr[id]);
                    $this->dbl->insert_update($sql);
                    $nuevo = "IdRegistro: \'$atr[idRegistro]\', IDDoc: \'$atr[IDDoc]\', Identificacion: \'$atr[identificacion]\', Version: \'$atr[version]\', Correlativo: \'$atr[correlativo]\', Id Usuario: \'$atr[id_usuario]\', Descripcion: \'$atr[descripcion]\', Doc Fisico: \'$atr[doc_fisico]\', ContentType: \'$atr[contentType]\', Id Procesos: \'$atr[id_procesos]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                    $anterior = "IdRegistro: \'$val[idRegistro]\', IDDoc: \'$val[IDDoc]\', Identificacion: \'$val[identificacion]\', Version: \'$val[version]\', Correlativo: \'$val[correlativo]\', Id Usuario: \'$val[id_usuario]\', Descripcion: \'$val[descripcion]\', Doc Fisico: \'$val[doc_fisico]\', ContentType: \'$val[contentType]\', Id Procesos: \'$val[id_procesos]\', Id Organizacion: \'$val[id_organizacion]\', ";
                    $this->registraTransaccionLog(19,$nuevo,$anterior, '');
                    /*
                    $this->registraTransaccion('Modificar','Modifico el Registros ' . $atr[descripcion_ano], 'mos_registro');
                    */
                    return "El mos_registro '$atr[descripcion_ano]' ha sido actualizado con exito";
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }

            public function modificarRegistroActualizacion($atr){
                try {
                    $sql = "select max(idRegistro) id from mos_registro where idRegistro_original =".$atr[idRegistro_original]." ;";      
                    $this->operacion($sql, $atr);
                    $id = $this->dbl->data[0][id];
                    $sql = "update mos_registro
                            set vigencia='S'
                            where idRegistro=".$id.";";      
                    //echo $sql;
                    $this->dbl->insert_update($sql);
                } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/ano_escolar_niveles_secciones_nivel_academico_key/",$error ) == true) 
                            return "Ya existe una sección con el mismo nombre.";                        
                        return $error; 
                    }
            }            
            
            
             public function listarRegistros($atr, $pag, $registros_x_pagina){
                // print_r($atr);
                    $atr = $this->dbl->corregir_parametros($atr);
                    $sql_left = $sql_col_left = $sql_filtro_acceso = "" ;
                     if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                    }                    
                    if (count($this->id_org_acceso) <= 0){
                        $this->cargar_acceso_nodos($atr);                    
                    }
                    $k = 1;   
                    //id_unico,IDDoc,Nombre,tipo,valores
                    if(!class_exists('Personas')){
                        import("clases.personas.Personas");
                    }
                    $personal = new Personas();
                    //print_r($this->parametros);
                    foreach ($this->parametros as $value) {
                        //,CONCAT(CONCAT(UPPER(LEFT(ap.nombres, 1)), LOWER(SUBSTRING(ap.nombres, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_paterno, 1)), LOWER(SUBSTRING(ap.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(ap.apellido_materno, 1)), LOWER(SUBSTRING(ap.apellido_materno, 2)))) aprobo
                        if ($value[tipo]== '6'){//-- , t1.Nombre as nom_detalle   
                            /*Filtro acceso segun el nodo*/
                            $sql_filtro_acceso .= " AND p$k.id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")";
                            $this->colummas_arbol[] = "id_organizacion_p$k";
                            //echo $sql_filtro_acceso;
//                            if ($registros_x_pagina == 100000){
//                                if (count($personal->campos_activos) <= 0){
//                                    $personal->cargar_campos_activos();
//                                }

                                $sql_left .= " LEFT JOIN(select t1.idRegistro
                                , t1.Nombre as nom_detalle_aux
                                ,p.id_organizacion
                                ,p.id_personal,c.descripcion cargo                                
                                ,CONCAT(initcap(p.nombres), ' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as nom_detalle
                                from mos_registro_formulario t1
                                inner join mos_personal p on p.cod_emp = CAST(t1.Nombre AS UNSIGNED)
                                LEFT JOIN mos_cargo c ON c.cod_cargo = p.cod_cargo
                                where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro"; 
                                $sql_col_left .= ",p$k.nom_detalle p$k"
                                        . ",p$k.id_personal id_personal_p$k"
                                        . ",p$k.id_organizacion id_organizacion_p$k"
                                        . ",p$k.cargo cargo_p$k";
                                $this->funciones["id_organizacion_p$k"] = 'BuscaOrganizacional';  
//                            }   
//                            else{
//                                $sql_left .= " LEFT JOIN(select t1.idRegistro
//                                , t1.Nombre as nom_detalle_aux
//                                , p.id_organizacion
//                                ,CONCAT(initcap(p.nombres), ' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as nom_detalle
//                                -- ,CONCAT(CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2)))) as nom_detalle 
//                                from mos_registro_formulario t1
//                                inner join mos_personal p on p.cod_emp = CAST(t1.Nombre AS UNSIGNED)
//                                where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro"; 
//                                $sql_col_left .= ",p$k.nom_detalle p$k ";
//                            }
                            
                        }
                        else if ($value[tipo]== '10'){
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                            where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            if ($registros_x_pagina != 100000)
                                $this->funciones["p$k"] = 'estado_columna';  
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                        }
                      // $grid->setFuncion($value[Nombre], "BuscaOrganizacionalTodosVerMas");
                        else if ($value[tipo]== '13'){
                            $sql_left .= " LEFT JOIN                                
                                (select t1.idRegistro, 
                                t1.Nombre as nom_detalle, 
                                case when DATEDIFF(str_to_date((STR_TO_DATE(t1.Nombre,'%d/%m/%Y')),'%Y-%m-%d'),CURRENT_DATE()) < 0 THEN
                                        CONCAT('V,',DATEDIFF(str_to_date((STR_TO_DATE(t1.Nombre,'%d/%m/%Y')),'%Y-%m-%d'),CURRENT_DATE()))
                                else case when DATEDIFF(str_to_date((STR_TO_DATE(t1.Nombre,'%d/%m/%Y')),'%Y-%m-%d'),CURRENT_DATE()) <= f.valores THEN
                                                CONCAT('P,',f.valores - DATEDIFF(str_to_date((STR_TO_DATE(t1.Nombre,'%d/%m/%Y')),'%Y-%m-%d'),CURRENT_DATE()))
                                                ELSE
                                                        CONCAT('A,',DATEDIFF(str_to_date((STR_TO_DATE(t1.Nombre,'%d/%m/%Y')),'%Y-%m-%d'),CURRENT_DATE()))
                                                end
                                end as edo
                                from mos_registro_formulario t1 inner 
                                join mos_documentos_datos_formulario f on t1.id_unico  = f.id_unico
                            where t1.id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            if ($registros_x_pagina != 100000)
                                $this->funciones["edop$k"] = 'semaforo';  
                            else
                                $this->funciones["edop$k"] = 'semaforoExcel';                                 
                            $sql_col_left .= ",p$k.edo edop$k,p$k.nom_detalle p$k ";
                           // echo $atr[corder].'-'. "p$k";
                            if ($atr[corder] == "p$k"){
                                $atr[corder] = "STR_TO_DATE(p$k.nom_detalle, '%d/%m/%Y')";
                            }
                            if ($atr[corder] == "edop$k"){
                                $atr[corder] = " cast(SUBSTRING(p$k.edo,3)AS SIGNED) ";
                            }                            
                        }

                        else if ($value[tipo]== '14'){
                            if($registros_x_pagina==100000){
                                $campo_cargo="replace(GROUP_CONCAT(cargo.descripcion),',','; ')";
                                $campo_cargo_perso="replace(GROUP_CONCAT(CONCAT(initcap(nombres), ' ', CONCAT(UPPER(LEFT(apellido_paterno, 1)), LOWER(SUBSTRING(apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(apellido_materno, 1)), LOWER(SUBSTRING(apellido_materno, 2))))),',','; ')";
                            }
                            else{
                                $campo_cargo="replace(GROUP_CONCAT(cargo.descripcion),',','<br>')";
                                $campo_cargo_perso="replace(GROUP_CONCAT(CONCAT(initcap(nombres), '&nbsp;', CONCAT(UPPER(LEFT(apellido_paterno, 1)), LOWER(SUBSTRING(apellido_paterno, 2))),'&nbsp;', CONCAT(UPPER(LEFT(apellido_materno, 1)), LOWER(SUBSTRING(apellido_materno, 2))))),',','<br>')";
                            }
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, $campo_cargo as nom_detalle 
                            from mos_registro_item t1 inner join mos_cargo cargo on t1.valor = cargo.cod_cargo
                            where id_unico= $value[id_unico] group by t1.idRegistro) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                            //$clave= array_search(11, $this->parametros);
                            //echo $clave;
                            $sql_left .= "LEFT JOIN
                            (select 
                            t1.idRegistro, 
                            $campo_cargo_perso cargo_perso
                            from mos_registro_item t1 inner join mos_cargo cargo on t1.valor = cargo.cod_cargo
                            inner join mos_registro_item ao on ao.idRegistro = t1.idRegistro and ao.tipo = 11
                            inner JOIN mos_personal p on p.cod_cargo = t1.valor and p.id_organizacion = ao.valor
                            where t1.id_unico = $value[id_unico] 
                            group by t1.idRegistro) pn$k ON pn$k.idRegistro = r.idRegistro";
                            $sql_col_left .= ",pn$k.cargo_perso pn$k ";
                            
                        }
                        else if ($value[tipo]== '11'){
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, GROUP_CONCAT(t1.valor) as nom_detalle from mos_registro_item t1
                            where id_unico= $value[id_unico] group by t1.idRegistro) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            if ($registros_x_pagina != 100000)
                                $this->funciones["p$k"] = 'BuscaOrganizacionalTodosVerMas';  
                            else
                                $this->funciones["p$k"] = 'BuscaOrganizacionalExcel';                                 
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                            /*Para la validacion de botones editar y eliminar*/
                            $this->colummas_arbol[] = "p$k";
                            /*Restriccion para ver solamente informacion asignada segun el arbol*/
                            if($_SESSION[SuperUser] == 'S'){
                                $sql_left .= " INNER JOIN(
                                    select t1.idRegistro from mos_registro_item t1
                                    where id_unico= $value[id_unico] AND t1.valor IN (". implode(',', array_keys($this->id_org_acceso)) . ") group by t1.idRegistro"
                                        . " UNION all 
                                    select idRegistro
                                    from 
                                    mos_registro 
                                    where IDDoc = $_SESSION[IDDoc] AND not idRegistro in (select  idRegistro from mos_registro_item where id_unico= $value[id_unico])
                                    group by idRegistro "
                                        . ") AS p_temp$k ON p_temp$k.idRegistro = r.idRegistro "; 

                            }else{
                                $sql_left .= " INNER JOIN(select t1.idRegistro from mos_registro_item t1
                                where id_unico= $value[id_unico] AND t1.valor IN (". implode(',', array_keys($this->id_org_acceso)) . ") group by t1.idRegistro) AS p_temp$k ON p_temp$k.idRegistro = r.idRegistro "; 
                            }
                                                    }
                        else if ($value[tipo]== '12'){
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, GROUP_CONCAT(t1.valor) as nom_detalle from mos_registro_item t1
                            where id_unico= $value[id_unico] group by t1.idRegistro ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            if ($registros_x_pagina != 100000)
                                $this->funciones["p$k"] = 'BuscaProcesoTodosVerMas'; 
                            else
                                $this->funciones["p$k"] = 'BuscaProcesoExcel'; 
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                        }
                        else if ($value[tipo]== '8'||$value[tipo]== '7'||$value[tipo]== '9'){
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, GROUP_CONCAT(nombre_items.descripcion) as nom_detalle from mos_registro_item t1
                                inner join mos_documentos_formulario_items nombre_items on nombre_items.id = t1.valor
                            where id_unico= $value[id_unico] group by t1.idRegistro ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                        }
                        else{
                            $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                            where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                            $sql_col_left .= ",p$k.nom_detalle p$k ";
                            if ($value[tipo]== '3'){
                                if ($atr[corder] == "p$k"){
                                    $atr[corder] = "STR_TO_DATE(p$k.nom_detalle, '%d/%m/%Y')";
                                }
                            }
                        }
                        
                        $k++;
                    }
                    $sql = "SELECT COUNT(*) total_registros
                         FROM mos_registro r $sql_left
                         WHERE 1 = 1 ";
                    $sql  = $sql . ($atr[actualizacion_historico]=='S' ? " and r.vigencia='N' " : " and r.vigencia='S' ");
                    
                    if (strlen($_SESSION[IDDoc])>0){
                        $sql .= " AND IDDoc = $_SESSION[IDDoc] ";
                    }
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $sql .= " AND (upper(identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%' ";
                        $k = 1; 
                        foreach ($this->parametros as $value) {
                            switch ($value[tipo]) {
                                                                
                                case '1':
                                case '5':
                                case '7':
                                case '9':
                                    {
                                        $sql .= " OR upper(p$k.nom_detalle) LIKE '%". strtoupper($atr["b-filtro-sencillo"]) . "%'";
                                    }                                
                                    break;
                                default:
                                    break;
                            }
                            
                            $k++;
                        }
                        $sql .= " )";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-idRegistro"])>0)
                        $sql .= " AND idRegistro = '". $atr["b-idRegistro"] . "'";
                    if (strlen($atr["b-IDDoc"])>0)
                        $sql .= " AND IDDoc = '". $atr["b-IDDoc"] . "'";
                    if (strlen($atr["b-identificacion"])>0)
                        $sql .= " AND upper(identificacion) like '%" . strtoupper($atr["b-identificacion"]) . "%'";
                    if (strlen($atr["b-version"])>0)
                        $sql .= " AND version = '". $atr["b-version"] . "'";
                    if (strlen($atr["b-correlativo"])>0)
                        $sql .= " AND correlativo = '". $atr["b-correlativo"] . "'";
                    if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-doc_fisico"])>0)
                        $sql .= " AND doc_fisico = '". $atr["b-doc_fisico"] . "'";
                    if (strlen($atr["b-contentType"])>0)
                        $sql .= " AND upper(contentType) like '%" . strtoupper($atr["b-contentType"]) . "%'";
                    if (strlen($atr["b-id_procesos"])>0)
                        $sql .= " AND id_procesos = '". $atr["b-id_procesos"] . "'";
                    if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND id_organizacion = '". $atr["b-id_organizacion"] . "'";
                    $k = 1; 
                    
                    /*Filtro de personas y ao segun nivel de acceso de la persona*/
                    $sql .= $sql_filtro_acceso;
                    //id_unico,IDDoc,Nombre,tipo,valores
                    /*
                      $ids = array('7','8','9','1','2','3','5','6');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona');
                     */
                    $k = 1;   //7=>Seleccion Simple, 8=>Seleccion Multiple,9=>Combo, 1=>Texto, 2=>Numerico, 3=> Fecha, 5=> Rut, 6=> Persona
                    foreach ($this->parametros as $value) {
                        switch ($value[tipo]) {
                            //case '8':
                            case '10':    
                                if (is_array($atr["p$k"])){
                                    for($i=0;$i<count($atr["p$k"]); $i++)
                                        $atr["p$k"][$i] = "'".$atr["p$k"][$i]."'";
                                    $in_values = implode(",", $atr["p$k"]);
                                    //
                                    {
                                        $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //echo " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
//                                if (is_array($atr["p$k"])){
//                                $in_values = implode("','", $atr["p$k"]);
//                                    //for($i=0;$i<count($atr["p$k"]); $i++)
//                                    {
//                                        $sql .= " AND p$k.nom_detalle IN ($in_values)";
//                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
//                                    }
////                                    
////                                    for($i=0;$i<count($atr["p$k"]); $i++){
////                                        $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"][$i] . "%'";
////                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
////                                    }
//                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                               case '8':
                            //case '10':                                    
                                if (is_array($atr["p$k"])){
                                //$in_values = implode("','", $atr["p$k"]);
                                    //for($i=0;$i<count($atr["p$k"]); $i++)
                                   // {
                                   //     $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                   // }
//                                    
                                    for($i=0;$i<count($atr["p$k"]); $i++){
                                        $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"][$i] . "%'";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                            
                            case '7':
                            case '9':
                            case '2':
                            case '3':
                            //case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle = '". $atr["p$k"] . "'";
                                }                                
                                break;
                            case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle = '". $atr["p$k"] . "'";
                                } 
                                if (strlen($atr["b-id_organizacion-reg"])>0 && $atr["b-arbol-filtro"]=='persona'){
                                    $sql .= " AND p$k.id_organizacion in (". $atr["b-id_organizacion-reg"] . ")";
                                } 
                                break;
                            case '1':
                            case '5':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
                                }                                
                                break;
                            case '11':
                                if (strlen($atr["b-id_organizacion-reg"])>0 && $atr["b-arbol-filtro"]=='organizacional'){
                                    $sql .= " AND p$k.nom_detalle in (". $atr["b-id_organizacion-reg"] . ")";
                                } 
                                break;
                            case '12':
                                if (strlen($atr["b-id_proceso-reg"])>0){
                                    $sql .= " AND p$k.nom_detalle like '%". $atr["b-id_proceso-reg"] . "%'";
                                } 
                                break;
                            case '14':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
                                }                                
                                break; 
                            case '13':
                                if (sizeof($atr["p$k"])>0){
                                   // $semaforovigencia = implode(",", $atr["p$k"]);
                                    foreach ($atr["p$k"] as $value) {
                                        $semaforovigencia .= "'".$value."',";
                                    }
                                    $semaforovigencia.="'X'";
                                    $sql .= " AND SUBSTRING(p$k.edo,1,1)  in (". $semaforovigencia . ")"; 
       
                                   // $semaforovigencia = implode(",", $atr["p$k"]);
                                    //$sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
                                } 
                                if (strlen($atr["pdesde$k"])>0)                        
                                {
                                    $atr["pdesde$k"] = formatear_fecha($atr["pdesde$k"]);                        
                                    $sql .= " AND STR_TO_DATE(p$k.nom_detalle,'%d/%m/%Y')  >= '" . ($atr["pdesde$k"]) . "'";                        
                                }                                
                                if (strlen($atr["phasta$k"])>0)                        
                                {
                                    $atr["phasta$k"] = formatear_fecha($atr["phasta$k"]);                        
                                    $sql .= " AND STR_TO_DATE(p$k.nom_detalle,'%d/%m/%Y')  <= '" . ($atr["phasta$k"]) . "'";                        
                                }                                
                                break;
                                
                            default:
                                break;
                        }
                        //$sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
                                
                        //where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
                        //$sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }

                    $total_registros = $this->dbl->query($sql, $atr);
                    $this->total_registros = $total_registros[0][total_registros];   
            
                    $sql = "SELECT r.idRegistro
                                    ,d.Codigo_doc
                                    ,r.identificacion
                                    -- ,version
                                    -- ,correlativo
                                    -- ,id_usuario
                                    -- ,descripcion
                                    ,1 doc_fisico
                                    ,r.contentType
                                    -- ,r.id_procesos
                                    -- ,r.id_organizacion AQUI
                                    ,d.actualizacion_activa
                                     $sql_col_left
                            FROM mos_registro r
                            INNER JOIN mos_documentos d ON d.IDDoc = r.IDDoc
                            $sql_left
                            WHERE 1 = 1 ";
                            $sql  = $sql . ($atr[actualizacion_historico]=='S' ? " and r.vigencia='N' " : " and r.vigencia='S' ");                  
                    if (strlen($_SESSION[IDDoc])>0){
                        $sql .= " AND r.IDDoc = $_SESSION[IDDoc] ";
                    }
                    if (strlen($atr['b-filtro-sencillo'])>0){
                        $k = 1; 
                        $sql .= " AND (upper(identificacion) like '%" . strtoupper($atr["b-filtro-sencillo"]) . "%' ";
                        foreach ($this->parametros as $value) {
                            switch ($value[tipo]) {
                                                                
                                case '1':
                                case '5':
                                case '7':
                                case '9':
                                    {
                                        $sql .= " OR upper(p$k.nom_detalle) LIKE '%". strtoupper($atr["b-filtro-sencillo"]) . "%'";
                                    }                                
                                    break;
                                
                                default:
                                    break;
                            }
                            
                            $k++;
                        }
                        $sql .= " )";
                    }
                    if (strlen($atr[valor])>0)
                        $sql .= " AND upper($atr[campo]) like '%" . strtoupper($atr[valor]) . "%'";
                                 if (strlen($atr["b-idRegistro"])>0)
                        $sql .= " AND idRegistro = '". $atr["b-idRegistro"] . "'";
                    if (strlen($atr["b-IDDoc"])>0)
                        $sql .= " AND IDDoc = '". $atr["b-IDDoc"] . "'";
                    if (strlen($atr["b-identificacion"])>0)
                        $sql .= " AND upper(identificacion) like '%" . strtoupper($atr["b-identificacion"]) . "%'";
                    if (strlen($atr["b-version"])>0)
                        $sql .= " AND version = '". $atr["b-version"] . "'";
                    if (strlen($atr["b-correlativo"])>0)
                        $sql .= " AND correlativo = '". $atr["b-correlativo"] . "'";
                    if (strlen($atr["b-id_usuario"])>0)
                        $sql .= " AND id_usuario = '". $atr["b-id_usuario"] . "'";
                    if (strlen($atr["b-descripcion"])>0)
                        $sql .= " AND upper(descripcion) like '%" . strtoupper($atr["b-descripcion"]) . "%'";
                    if (strlen($atr["b-doc_fisico"])>0)
                        $sql .= " AND doc_fisico = '". $atr["b-doc_fisico"] . "'";
                    if (strlen($atr["b-contentType"])>0)
                        $sql .= " AND upper(contentType) like '%" . strtoupper($atr["b-contentType"]) . "%'";
                    if (strlen($atr["b-id_procesos"])>0)
                        $sql .= " AND id_procesos = '". $atr["b-id_procesos"] . "'";
                    if (strlen($atr["b-id_organizacion"])>0)
                        $sql .= " AND id_organizacion = '". $atr["b-id_organizacion"] . "'";
                    /*Filtro de personas y ao segun nivel de acceso de la persona*/
                    $sql .= $sql_filtro_acceso;
                    //id_unico,IDDoc,Nombre,tipo,valores
                    /*
                      $ids = array('7','8','9','1','2','3','5','6');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona');
                     */
                    $k = 1;  
                    //print_r($this->parametros);
                    foreach ($this->parametros as $value) {
                        switch ($value[tipo]) {
                            //case '8':
                            case '10':    
                                if (is_array($atr["p$k"])){
//                                    for($i=0;$i<count($atr["p$k"]); $i++)
//                                        $atr["p$k"][$i] = "'".$atr["p$k"][$i]."'";
                                    $in_values = implode(",", $atr["p$k"]);
                                    //
                                    {
                                        $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //echo " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                            case '8':
                            //case '10':                                    
                                if (is_array($atr["p$k"])){
                                //$in_values = implode("','", $atr["p$k"]);
                                    //for($i=0;$i<count($atr["p$k"]); $i++)
                                   // {
                                   //     $sql .= " AND p$k.nom_detalle IN ($in_values)";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                   // }
//                                    
                                    for($i=0;$i<count($atr["p$k"]); $i++){
                                        $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"][$i] . "%'";
                                        //$parametros[herramienta_pre][$i] = "'" . $parametros[herramienta_pre][$i] . "'";
                                    }
                                }
                                    //$sql_where .= " AND id_area IN (" . implode('<br/>', $parametros['id_area']) . ")"; 
                                break;
                            case '7':
                            case '9':
                            case '2':
                            case '3':
                            //case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle = '". $atr["p$k"] . "'";
                                }                                
                                break;
                            case '6':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle = '". $atr["p$k"] . "'";
                                }
                                if (strlen($atr["b-id_organizacion-reg"])>0 && $atr["b-arbol-filtro"]=='persona'){
                                    $sql .= " AND p$k.id_organizacion in (". $atr["b-id_organizacion-reg"] . ")";
                                } 
                                break;
                            case '11':
                                if (strlen($atr["b-id_organizacion-reg"])>0 && $atr["b-arbol-filtro"]=='organizacional'){
                                    $sql .= " AND p$k.nom_detalle in (". $atr["b-id_organizacion-reg"] . ")";
                                } 
                                break;
                            case '12':
                                if (strlen($atr["b-id_proceso-reg"])>0){
                                    $sql .= " AND p$k.nom_detalle like '%". $atr["b-id_proceso-reg"] . "%'";
                                } 
                                break;

                            case '13':
                                if (sizeof($atr["p$k"])>0){
                                   // $semaforovigencia = implode(",", $atr["p$k"]);
                                    foreach ($atr["p$k"] as $value) {
                                        $semaforovigencia .= "'".$value."',";
                                    }
                                    $semaforovigencia.="'X'";
                                    $sql .= " AND SUBSTRING(p$k.edo,1,1)  in (". $semaforovigencia . ")"; 
       
                                   // $semaforovigencia = implode(",", $atr["p$k"]);
                                    //$sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
                                } 
                                if (strlen($atr["pdesde$k"])>0)                        
                                {
                                    $atr["pdesde$k"] = formatear_fecha($atr["pdesde$k"]);                        
                                    $sql .= " AND STR_TO_DATE(p$k.nom_detalle,'%d/%m/%Y')  >= '" . ($atr["pdesde$k"]) . "'";                        
                                }                                
                                if (strlen($atr["phasta$k"])>0)                        
                                {
                                    $atr["phasta$k"] = formatear_fecha($atr["phasta$k"]);                        
                                    $sql .= " AND STR_TO_DATE(p$k.nom_detalle,'%d/%m/%Y')  <= '" . ($atr["phasta$k"]) . "'";                        
                                }                                
                                break;
                             
                            case '5':
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
                                }                                
                                break;
                            case '14':
                                //print_r($atr);
                                //echo ("p$k:".$atr["p$k"]);
                                if (strlen($atr["p$k"])>0){
                                    $sql .= " AND p$k.nom_detalle LIKE '%". $atr["p$k"] . "%'";
                                }                                
                                break;
                            default:
                                break;
                        }
//                        $sql_left .= " LEFT JOIN(select t1.idRegistro, t1.Nombre as nom_detalle from mos_registro_formulario t1
//                                
//                        where id_unico= $value[id_unico] ) AS p$k ON p$k.idRegistro = r.idRegistro "; 
//                        $sql_col_left .= ",p$k.nom_detalle p$k ";
                        $k++;
                    }
                    
        
                    $sql .= " order by $atr[corder] $atr[sorder] ";
                    $sql .= "LIMIT " . (($pag - 1) * $registros_x_pagina) . ", $registros_x_pagina ";
                   //print_r($atr);
                    //echo $sql;
                    $this->operacion($sql, $atr);
             }
             public function eliminarRegistros($atr){
                    try {
                        $atr = $this->dbl->corregir_parametros($atr);
                        $respuesta = $this->dbl->delete("mos_registro", "idRegistro = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_registro_formulario", "idRegistro = " . $atr[id]);
                        $respuesta = $this->dbl->delete("mos_registro_item", "idRegistro = " . $atr[id]);
                        $nuevo = "idRegistro: \'$atr[id]\'";
                        $this->registraTransaccionLog(9,$nuevo,'', '');
                        return "ha sido eliminada con exito";
                    } catch(Exception $e) {
                        $error = $e->getMessage();                     
                        if (preg_match("/alumno_inscrito_fk_id_ano_escolar_fkey/",$error ) == true) 
                            return "No se puede eliminar el año escolar porque existen alumnos inscritos para el año seleccionado.";                        
                        return $error; 
                    }
             }
     
 
     public function verListaRegistros($parametros){
                //print_r($parametros);
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarRegistros($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                //print_r($data);
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblRegistros", "");
                $config_col=array(
                   
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[idRegistro], "idRegistro", $parametros,'r_link_titulos')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[IDDoc], "IDDoc", $parametros,'r_link_titulos')),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[identificacion], "identificacion", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[correlativo], "correlativo", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros,'r_link_titulos')),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[contentType], "contentType", $parametros,'r_link_titulos')),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[actualizacion_activa], "actualizacion_activa", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_procesos], "id_procesos", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros))
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                //print_r($this->parametros);
                if(!class_exists('Personas')){
                    import("clases.personas.Personas");
                }
                $personal = new Personas();
                $bandera_permisos_arbol = false;
                foreach ($this->parametros as $value) {   
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $ancho = 5;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $ancho = 5;   
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Combo':
                            $ancho = 7;
                        case '9':
                            $ancho = 7;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Texto':
                        case '1':
                             $ancho = 10;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Numerico':
                        case '2':
                             $ancho = 3;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case '3':
                        case 'Fecha':
                              $ancho = 2;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case '5':
                        case 'Rut':
                              $ancho = 5;     
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Persona':
                        case '6':
                                $ancho = 15;
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                $bandera_permisos_arbol = true;
                                if (count($personal->nombres_columnas) <= 0){
                                    $personal->cargar_nombres_columnas();
                                }
                                if (count($personal->campos_activos) <= 0){
                                        $personal->cargar_campos_activos();
                                }
                                /*Columnas del ID, area y cargo de la persona*/
                                array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_personal], ENT_QUOTES, "UTF-8"))));   
                                array_push($config_col,array( "width"=>"15%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")))); 
                                array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[cod_cargo], ENT_QUOTES, "UTF-8"))));                                
                                //$k++;$k++;$k++;
                            break;
                        case '10':
                                $ancho = 2;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                break;
                        case '11':
                                $ancho = 10;
                                $bandera_permisos_arbol = true;
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                break;
                        case '12':
                                $ancho = 5;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                break;
                        case '13':
                                $ancho = 5;                                                            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro('Vigencia', "edop$k", $parametros,'r_link_titulos')));            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                //$k++;
                                break;
                        case '14':
                                $ancho = 5;                                                            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro('Personas en Cargo', "pn$k", $parametros,'r_link_titulos')));            
                                //$k++;
                                break;
                            

                        default:
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                    }                        
                   // print_r($value);
                    $k++;
                }

                $func= array('funcion'=> 'colum_admin');

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 12;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verRegistros','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Registros'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarRegistros','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar Registros'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarRegistros','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar Registros'></i>"));
               */
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:50px;'>&nbsp;</div>"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);                
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
//                            array_push($config,$config_col[$i]);
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
                $grid->hidden[5] = true;
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("contentType", "archivo_reg");
                $grid->setAligns(4,"center");
                //print_r($this->funciones);
                foreach ($this->funciones as $key => $value) {
                    $grid->setFuncion($key, $value);
                }
                //$grid->hidden = array(0 => true);
                if (!($bandera_permisos_arbol==true)){
                    $this->cargar_permisos_simple($parametros);
                }
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina_aux", "document", "r-pag_actual","r-reg_por_pag");
                }
                return $out;
            }
     public function verListaRegistrosHistorico($parametros){
               // print_r($parametros);
                $grid= "";
                $grid= new DataGrid();
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $parametros['actualizacion_historico']='S';
                //print_r($parametros);
                $this->listarRegistros($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                //print_r($data);
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblRegistros", "");
                $config_col=array(
                   
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[idRegistro], "idRegistro", $parametros,'r_link_titulos')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[IDDoc], "IDDoc", $parametros,'r_link_titulos')),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[identificacion], "identificacion", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[correlativo], "correlativo", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros,'r_link_titulos')),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[contentType], "contentType", $parametros,'r_link_titulos')),
               
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_procesos], "id_procesos", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros))
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                //print_r($this->parametros);
                if(!class_exists('Personas')){
                    import("clases.personas.Personas");
                }
                $personal = new Personas();
                $bandera_permisos_arbol = false;
                foreach ($this->parametros as $value) {   
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $ancho = 5;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $ancho = 5;   
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Combo':
                            $ancho = 7;
                        case '9':
                            $ancho = 7;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Texto':
                        case '1':
                             $ancho = 10;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Numerico':
                        case '2':
                             $ancho = 3;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case '3':
                        case 'Fecha':
                              $ancho = 2;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case '5':
                        case 'Rut':
                              $ancho = 5;     
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                        case 'Persona':
                        case '6':
                                $ancho = 15;
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                $bandera_permisos_arbol = true;
                                if (count($personal->nombres_columnas) <= 0){
                                    $personal->cargar_nombres_columnas();
                                }
                                if (count($personal->campos_activos) <= 0){
                                        $personal->cargar_campos_activos();
                                }
                                /*Columnas del ID, area y cargo de la persona*/
                                array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_personal], ENT_QUOTES, "UTF-8"))));   
                                array_push($config_col,array( "width"=>"15%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")))); 
                                array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[cod_cargo], ENT_QUOTES, "UTF-8"))));                                
                                //$k++;$k++;$k++;
                            break;
                        case '10':
                                $ancho = 2;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                break;
                        case '11':
                                $ancho = 10;
                                $bandera_permisos_arbol = true;
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                break;
                        case '12':
                                $ancho = 5;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                break;
                        case '13':
                                $ancho = 5;                                                            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro('Vigencia', "edop$k", $parametros,'r_link_titulos')));            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                //$k++;
                                break;
                        case '14':
                                $ancho = 5;                                                            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro('Personas en Cargo', "pn$k", $parametros,'r_link_titulos')));            
                                //$k++;
                                break;
                            

                        default:
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                            break;
                    }                        
                   // print_r($value);
                    $k++;
                }

               $func= array();

                $columna_funcion = 0;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 12;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verRegistros','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Registros'>"));
                
                if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                    array_push($func,array('nombre'=> 'editarRegistros','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar Registros'></i>"));
                if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                    array_push($func,array('nombre'=> 'eliminarRegistros','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar Registros'></i>"));
               */
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"<div style='width:50px;'>&nbsp;</div>"));
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);                
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
//                            array_push($config,$config_col[$i]);
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
                $grid->hidden[5] = true;
                $grid->setParent($this);
                $grid->SetTitulosTablaMSKS("td-titulo-tabla-row", $config);
                $grid->setFuncion("contentType", "archivo_reg");
                $grid->setAligns(4,"center");
                //print_r($this->funciones);
                foreach ($this->funciones as $key => $value) {
                    $grid->setFuncion($key, $value);
                }
                //$grid->hidden = array(0 => true);
                if (!($bandera_permisos_arbol==true)){
                    $this->cargar_permisos_simple($parametros);
                }
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina_aux", "document", "r-pag_actual","r-reg_por_pag");
                }
                //echo $out;
                return $out;
            }
            
            public function verListaRegistrosReporte($parametros){
                $grid= "";
                $grid= new DataGrid();
                
                if ($parametros['pag'] == null) 
                    $parametros['pag'] = 1;
                $reg_por_pagina = getenv("PAGINACION");
                if ($parametros['reg_por_pagina'] != null) $reg_por_pagina = $parametros['reg_por_pagina']; 
                $this->listarRegistros($parametros, $parametros['pag'], $reg_por_pagina);
                $data=$this->dbl->data;
                
                if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }

                $grid->SetConfiguracionMSKS("tblRegistros", "");
                $config_col=array(
                   
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[idRegistro], "idRegistro", $parametros,'r_link_titulos')),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[IDDoc], "IDDoc", $parametros,'r_link_titulos')),
               array( "width"=>"15%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[identificacion], "identificacion", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[version], "version", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[correlativo], "correlativo", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_usuario], "id_usuario", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[descripcion], "descripcion", $parametros)),
               array( "width"=>"10%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[doc_fisico], "doc_fisico", $parametros,'r_link_titulos')),
               array( "width"=>"2%","ValorEtiqueta"=>link_titulos_otro($this->nombres_columnas[contentType], "contentType", $parametros,'r_link_titulos')),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_procesos], "id_procesos", $parametros)),
               //array( "width"=>"10%","ValorEtiqueta"=>link_titulos($this->nombres_columnas[id_organizacion], "id_organizacion", $parametros))
                );
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                if(!class_exists('Personas')){
                    import("clases.personas.Personas");
                }
                $personal = new Personas();
                $k = 1;
                foreach ($this->parametros as $value) {   
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $ancho = 5;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            $ancho = 5;    
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                        case 'Combo':
                            $ancho = 7;
                        case '9':
                            $ancho = 7;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                        case 'Texto':
                        case '1':
                             $ancho = 10;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                        case 'Numerico':
                        case '2':
                             $ancho = 3;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                        case '3':
                        case 'Fecha':
                              $ancho = 2;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                        case '5':
                        case 'Rut':
                              $ancho = 5;   
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                        case 'Persona':
                        case '6':
                                $ancho = 15;
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));                            
                                if (count($personal->nombres_columnas) <= 0){
                                    $personal->cargar_nombres_columnas();
                                }
                                if (count($personal->campos_activos) <= 0){
                                        $personal->cargar_campos_activos();
                                }
                                /*Columnas del ID, area y cargo de la persona*/
                                array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_personal], ENT_QUOTES, "UTF-8"))));   
                                array_push($config_col,array( "width"=>"15%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")))); 
                                array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[cod_cargo], ENT_QUOTES, "UTF-8"))));                                
                                //$k++;$k++;$k++;

                            break;
                        case '10':
                                $ancho = 2;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                                break;
                        case '11':
                                $ancho = 5;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                                break;
                        case '12':
                                $ancho = 5;
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                                break;    
                        case '13':
                                $ancho = 5;                                                            
                                array_push($config_col,array( "width"=>"2%","ValorEtiqueta"=>link_titulos_otro('Vigencia', "edop$k", $parametros,'r_link_titulos')));            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                                //$k++;
                                break;
                        case '14':
                                $ancho = 5;                                                            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro('Personas en Cargo', "pn$k", $parametros,'r_link_titulos')));            
                                //$k++;
                                break;
                            
                        default:
                            array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));
                            break;
                    }
                    
                    $k++;
                }

                $func= array();

                $columna_funcion = -1;
                /*if (strrpos($parametros['permiso'], '1') > 0){
                    
                    $columna_funcion = 12;
                }
                if ($parametros['permiso'][1] == "1")
                    array_push($func,array('nombre'=> 'verRegistros','imagen'=> "<img style='cursor:pointer' src='diseno/images/find.png' title='Ver Registros'>"));
                */
                //if($_SESSION[CookM] == 'S')//if ($parametros['permiso'][2] == "1")
                //    array_push($func,array('nombre'=> 'editarRegistros','imagen'=> "<i style='cursor:pointer'  class=\"icon icon-edit\"  title='Editar Registros'></i>"));
                //if($_SESSION[CookE] == 'S')//if ($parametros['permiso'][3] == "1")
                //    array_push($func,array('nombre'=> 'eliminarRegistros','imagen'=> "<i style='cursor:pointer' class=\"icon icon-remove\"  title='Eliminar Registros'></i>"));
               
                $config=array(array("width"=>"5%", "ValorEtiqueta"=>"&nbsp;"));
                $config=array();
                $grid->setPaginado($reg_por_pagina, $this->total_registros);
                $array_columns =  explode('-', $parametros['mostrar-col']);
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
//                        case 1:
//                        case 2:
//                        case 3:
//                        case 4:
//                            array_push($config,$config_col[$i]);
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
                $grid->setFuncion("contentType", "archivo_reg");
                foreach ($this->funciones as $key => $value) {
                    $grid->setFuncion($key, $value);
                }
                //$grid->setAligns(5,"center");
                //$grid->hidden = array(0 => true);                
                $grid->setDataMSKS("td-table-data", $data, $func,$columna_funcion, $parametros['pag'] );
                $out['tabla']= $grid->armarTabla();
                //if (($parametros['pag'] != 1)  || ($this->total_registros >= $reg_por_pagina))
                {
                    $out['paginado']=$grid->setPaginadohtmlMSKS("verPagina_aux", "document", "r-pag_actual","r-reg_por_pag");
                }
                return $out;
            }
     
 
        public function exportarExcel($parametros){


            $grid= new DataGrid();
            $this->listarRegistros($parametros, 1, 100000);

            $data=$this->dbl->data;

            if (count($this->nombres_columnas) <= 0){
                        $this->cargar_nombres_columnas();
                }
             $grid->SetConfiguracion("tblRegistros", "width='100%' align ='center' border='1' cellspacing='0' cellpadding='0'");
                $config_col=array(
                 
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[idRegistro], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[IDDoc], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[identificacion], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[version], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[correlativo], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_usuario], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[descripcion], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[doc_fisico], ENT_QUOTES, "UTF-8")),
         array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[contentType], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_procesos], ENT_QUOTES, "UTF-8")),
         //array( "width"=>"10%","ValorEtiqueta"=>htmlentities($this->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8"))
              );
                $columna_funcion =10;
           /* $grid->hidden = array(0 => true);*/
           if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                }
                $k = 1;
                if(!class_exists('Personas')){
                    import("clases.personas.Personas");
                }
                $personal = new Personas();
                foreach ($this->parametros as $value) {          
                    switch ($value[tipo]){
                        case '6':
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  htmlentities($value[Nombre], ENT_QUOTES, "UTF-8")));
                            if (count($personal->nombres_columnas) <= 0){
                                    $personal->cargar_nombres_columnas();
                            }
                            if (count($personal->campos_activos) <= 0){
                                    $personal->cargar_campos_activos();
                            }
                            
                            /*. ",p$k.id_personal"
                                        . ",p$k.cargo"
                                        . ",p$k.id_organizacion"
                                        . ",p$k.fecha_nacimiento
                                ,p$k.genero
                                ,p$k.workflow
                                ,p$k.vigencia
                                ,p$k.email
                                ,p$k.relator
                                ,p$k.reviso
                                ,p$k.elaboro
                                ,p$k.aprobo
                                ,p$k.extranjero
                                ,p$k.fecha_ingreso
                                ,p$k.fecha_egreso ";*/
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_personal], ENT_QUOTES, "UTF-8"))));   
                            array_push($config_col,array( "width"=>"15%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_organizacion], ENT_QUOTES, "UTF-8")))); 
                            array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[cod_cargo], ENT_QUOTES, "UTF-8"))));                                
//                            if ($personal->campos_activos[fecha_nacimiento][0] == '1')
//                                array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[fecha_nacimiento], ENT_QUOTES, "UTF-8"))));                   
//                             if ($personal->campos_activos[genero][0] == '1')
//                                 array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[genero], ENT_QUOTES, "UTF-8"))));           
//                            
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[workflow], ENT_QUOTES, "UTF-8"))));
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[vigencia], ENT_QUOTES, "UTF-8"))));
//                            //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[interno], ENT_QUOTES, "UTF-8"))));                   
//                            //array_push($config_col,array( "width"=>"10%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[id_filial], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[email], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[relator], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[reviso], ENT_QUOTES, "UTF-8"))));
//                           array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[elaboro], ENT_QUOTES, "UTF-8"))));
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[aprobo], ENT_QUOTES, "UTF-8"))));
//                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[extranjero], ENT_QUOTES, "UTF-8"))));
//                            if ($personal->campos_activos[fecha_ingreso][0] == '1')
//                                array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[fecha_ingreso], ENT_QUOTES, "UTF-8"))));
//                            if ($personal->campos_activos[fecha_egreso][0] == '1')
//                                array_push($config_col, array( "width"=>"5%","ValorEtiqueta"=>(htmlentities($personal->nombres_columnas[fecha_egreso], ENT_QUOTES, "UTF-8"))));
                            $grid->setFuncion("id_organizacion", "BuscaOrganizacional");
                            break;
                        case 13:
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=> 'Vigencia'));
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  htmlentities($value[Nombre], ENT_QUOTES, "UTF-8")));
                            break;
                        case '14':
                                $ancho = 5;                                                            
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro(($value[Nombre]), "p$k", $parametros,'r_link_titulos')));    
                                array_push($config_col,array( "width"=>"$ancho%","ValorEtiqueta"=>link_titulos_otro('Personas en Cargo', "pn$k", $parametros,'r_link_titulos')));            
                                //$k++;
                                break;
                        default :
                            array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  htmlentities($value[Nombre], ENT_QUOTES, "UTF-8")));
                    }
                    //array_push($config_col,array( "width"=>"5%","ValorEtiqueta"=>  htmlentities($value[Nombre], ENT_QUOTES, "UTF-8")));
                    $k++;
                }
                $columna_funcion =10;
                $config = array();            
                $array_columns =  explode('-', $parametros['mostrar-col']);            
                for($i=0;$i<count($config_col);$i++){
                    switch ($i) {                                             
                        //case 1:
                        case 2:
//                        case 3:
//                        case 4:
                            array_push($config,$config_col[$i]);
                            break;
                        case 0:
                        case 3:
                        case 4:
                            $grid->hidden[$i] = true; 
                            break;
                        default:                            
                            if (in_array($i, $array_columns)) {
                                array_push($config,$config_col[$i]);
                            }
                            else      
                                array_push($config,$config_col[$i]);
                                //$grid->hidden[$i] = true;                            
                            break;
                    }
                }
               ///aquiiiiiiiiiiii
            
                $grid->setParent($this);
                foreach ($this->funciones as $key => $value) {
                   $grid->setFuncion($key, $value);
               }

          
            $grid->SetTitulosTabla("td-titulo-tabla-row", $config);
            $grid->setData2("td-table-data", $data);

            return $grid->armarTabla();
        }
 
        //ADMINISTRAR DOCUMENTOS->VER REGISTROS
            public function indexRegistros($parametros)
            {   //print_r($parametros);
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="idRegistro";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2-4"; 
                
                
                session_name("$GLOBALS[SESSION]");
                session_start();                                                
                
                $_SESSION[IDDoc] = $parametros[id];                
                
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } 
                $k = 6;
                $contenido[PARAMETROS_OTROS] = "";
                //print_r($this->parametros);
                foreach ($this->parametros as $value) {  
                    if ($value[tipo] == 13){
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                          Vigencia </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                        $k++;
                    }
                    else if ($value[tipo] == 14){
                       $parametros['mostrar-col'] .= "-$k";
                       $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                      
                                     <label >
                                         <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                     ' . $value[Nombre] . '</label>
                                 
                           </div>';
                       $k++;
                       //  $parametros['mostrar-col'] .= "-$k";
                       $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                      
                                     <label >
                                         <input  type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                     ' . $value[Nombre] . ' Personal </label>
                                 
                           </div>';
                       $k++;   
                        
                    }
                    else if ($value[tipo] == 6){
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          ID ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          Área ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          Cargo ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                    }
                    else{
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                          ' . $value[Nombre] . '</label>

                                </div>';
                        $k++;
                    }
                }
                
                $campos_din = $this->verCamposDinamicos();
                $ut_tool = new ut_Tool();
                $html = '';
                $js='';
                $i = 1;
                /*PARA COMPROBAR TIPO DE PERMISOS A UTILIZAR, SIMPLE O DE ARBOL*/
                $bandera_permisos_arbol = false;
                foreach ($campos_din as $value) {               
                    if ($value[tipo]!='11' && $value[tipo]!='12')
                        $html .= '<div class="form-group"><label>' . $value[Nombre] . '</label>';                                       
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            $html .= '                                             
                                                      <select class="form-control" name="p' . $i . '" id="p' . $i . '" >
                                                        <option selected="" value="">-- Todos --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select>';
                            break;
                        case '10':
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input  type="checkbox" value="1" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/verde.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input  type="checkbox" value="2" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/amarillo.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input type="checkbox" value="3" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/atrasado.png" /> 
                                          </label>';
                            
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            $valores_actuales = split("<br />", $value[valor]) ;
                            $j = 1;
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">';
                            foreach ($cadenas as $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="p' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="p' . $i . '[]"> '. $valores . ' 
                                          </label><br>';
                                //$html .= '<input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '">'. $valores;
                                
                                $j++;
                            }
                           // $html .= '</label>';
                            break;
                        case 'Combo':
                        case '9':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '                                             
                                                      <select class="form-control" name="p' . $i . '" id="p' . $i . '" >
                                                        <option selected="" value="">-- Todos --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select>';                            
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '';
                                $html .= '<input type="text"  class="col-xs-24 form-control" value="'. $value[valor] .'" name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case 'Numerico': 
                        case '2':
                                $html .= '';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '';
                                $html .= '<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                                $js .= "$('#p$i').datepicker();";
                            break;
                        case '13':
                        case 'Vigencia':
                                $html .= '';
                                $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                                $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                                $html .= '</div><div class="form-group">'
                                    . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                                $html .= '<label class="radio-inline" style="color:white;">
                                                <input  type="checkbox" value="A" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/verde.png" /> 
                                              </label>';
                                $html .= '<label class="radio-inline" style="color:white;">
                                                <input  type="checkbox" value="P" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/amarillo.png" /> 
                                              </label>';
                                $html .= '<label class="radio-inline" style="color:white;">
                                                <input type="checkbox" value="V" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/atrasado.png" /> 
                                              </label>';
                            
                                $html .= '<br>Desde:<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="pdesde' . $i . '" id="pdesde' . $i . '">';
                                $html .= 'Hasta:<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="phasta' . $i . '" id="phasta' . $i . '">';
                                $html .= '';
                                $js .= "$('#pdesde$i').datepicker();";
                                $js .= "$('#phasta$i').datepicker();";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';                                
                            break;
                        case 'Persona':
                        case '6':
                                $html .= '                                             
                                                      <select name="p' . $i . '" id="p' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#p' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select>';
                                $bandera_permisos_arbol = true;
                            break;
                        case 'Cargo':
                        case '14':
                                $html .= '';
                                $html .= '<input type="text"  class="col-xs-24 form-control" value="'. $value[valor] .'" name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case '12':
                            break;
                        case '11':
                            $bandera_permisos_arbol = true;
                            break;
                                
                        default:
                            break;
                    }
//                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
//                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
//                    //$html .= '<input id="validacion_' . $i . '" type="hidden" value="' . $value[validacion] . '" name="validacion_' . $i . '">';
//                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
//                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    if ($value[tipo]!='11' && $value[tipo]!='12')
                        $html .= '</div>';
                    $i++;
                }

                $html .= '</table>';
                $contenido[CAMPOS_DINAMICOS] = $html;
                $grid = $this->verListaRegistros($parametros);
                $val = $this->verDocumentos($parametros[id]);
                $contenido[DOCUMENTO] = $val[Codigo_doc] . ' - ' .$val[nombre_doc];
                $contenido[TITULO_MODULO] = 'Documento [ '.$val[Codigo_doc] . ' - ' .$val[nombre_doc].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">
                            Lista de Registros';
                $contenido[TITULO] = $parametros[titulo];
                $_SESSION[Codigo_doc] = $val[Codigo_doc];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Registros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Registros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                IF ($bandera_permisos_arbol == true){                    
                    $contenido['PERMISO_INGRESAR'] = $this->permiso_crear($parametros) == 'S' ? '' : 'display:none;';
                }
                else{
                    $this->cargar_permisos_simple($parametros);
                    $contenido['PERMISO_INGRESAR'] = $this->per_crear == 'S' ? '' : 'display:none;';
                }
                
                

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'registros/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                if ($this->DocTieneArbolRegistros('11')>0){
                    import('clases.organizacion.ArbolOrganizacional');
                    $ao = new ArbolOrganizacional();
                    $paramreg['cod_link'] = $parametros['cod_link'];                
                    $paramreg['modo'] = $parametros['modo'];
                    $paramreg['opcion']='reg';
                    $ao->cargar_acceso_nodos_explicito($parametros);
                    $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(4,$paramreg);
                    $contenido[ARBOLFILTRO]='organizacional';
                }else
                {
                    if ($this->DocTieneCampo('6')>0){
                        import('clases.organizacion.ArbolOrganizacional');
                        $ao = new ArbolOrganizacional();
                        $paramreg['cod_link'] = $parametros['cod_link'];                
                        $paramreg['modo'] = $parametros['modo'];
                        $paramreg['opcion']='reg';
                        $ao->cargar_acceso_nodos_explicito($parametros);
                        // los registros de los empleados de las areas donde tiene permisos
                        $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(6,$paramreg);
                        $contenido[ARBOLFILTRO]='persona';
                    }
                }
                $template->setTemplate("listar_volver");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('desc-mod-act',"innerHTML","Registros - Documento [$val[Codigo_doc] - $val[nombre_doc]]");
                $objResponse->addAssign('contenido-aux',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);                
                //$objResponse->addAssign('modulo_actual',"value","registros");
                $objResponse->addIncludeScript(PATH_TO_JS . 'registros/registros.js?'.rand());
                $objResponse->addScript("$('#MustraCargando').hide();");
                //$objResponse->addScript("init_filtro_ao_simple_reg();");
                $objResponse->addScript("init_filtro_ao_multiple_reg();");
                $objResponse->addScript($js);
                /*Js init_tabla*/
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                    event.preventDefault();
                                    var id = $(this).attr('tok');
                                    $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                    $('#myModal-Ventana-Titulo').html('');
                                    $('#myModal-Ventana').modal('show');
                                });");                
                //$objResponse->addScript('r_init_filtrar();');
                $objResponse->addScript('setTimeout(function(){ r_init_filtrar(); }, 500);');
                //$objResponse->addScriptCall("MostrarContenidoAux"); 
                return $objResponse;
            }
            //MAESTRO REGISTROS->VER REGISTROS
            public function indexRegistrosListado($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                if ($parametros['corder'] == null) $parametros['corder']="idRegistro";
                if ($parametros['sorder'] == null) $parametros['sorder']="desc"; 
                if ($parametros['mostrar-col'] == null) 
                    $parametros['mostrar-col']="2"; 
                
                
                session_name("$GLOBALS[SESSION]");
                session_start();                                                
                
                $_SESSION[IDDoc] = $parametros[id];                
                
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } 
                $k = 5;
                $contenido[PARAMETROS_OTROS] = "";
                foreach ($this->parametros as $value) {
                    if ($value[tipo] == 13){
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                          Vigencia </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                        $k++;
                    }else if ($value[tipo] == 14){
                       $parametros['mostrar-col'] .= "-$k";
                       $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                      
                                     <label >
                                         <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                     ' . $value[Nombre] . '</label>
                                 
                           </div>';
                       $k++;
                       //  $parametros['mostrar-col'] .= "-$k";
                       $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                      
                                     <label >
                                         <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                     ' . $value[Nombre] . ' Personal </label>
                                 
                           </div>';
                       $k++;   
                        
                    }

                    else if ($value[tipo] == 6){
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          ID ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          Área ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          Cargo ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                    }
                    else{
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                          ' . $value[Nombre] . '</label>

                                </div>';
                        $k++;
                    }

                }
                
                $campos_din = $this->verCamposDinamicos();
                $ut_tool = new ut_Tool();
                $html = '';
                $js='';
                $i = 1;
                foreach ($campos_din as $value) {//Nombre,tipo,valores col-md-24
                    if ($value[tipo]!='11' && $value[tipo]!='12')
                        $html .= '<div class="form-group"><label>' . $value[Nombre] . '</label>';                                       
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            //$cadenas = split("<br />", $value[valores]) ;
//                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
//                            $cadenas = array();
//                            foreach ($items_campo as $value_item) {
//                                $cadenas[$value_item[id]] = $value_item[descripcion];
//                            }
//                            $valores_actuales = split("<br/>", $value[valor]) ;
//                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
//                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
//                            $html .= '</div><div class="form-group">
//                                        <label class="checkbox-inline" style="padding-top: 0px; padding-left: 0px;">';
//                            foreach ($cadenas as $valores) {
//                               
//                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
//                                            <input '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $valores . '" name="p' . $i . '" id="p' . $i . '"> '. $valores . ' 
//                                          ';
//                                
//                                //$html .= '<input type="radio" class="form-box" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' value="' . $valores . '" size="20" name="campo_' . $i . '" id="campo_' . $i . '">'. $valores;
//                            }
//                            $html .= '</label>';
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '                                             
                                                      <select class="form-control" name="p' . $i . '" id="p' . $i . '" >
                                                        <option selected="" value="">-- Todos --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select>';
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            $valores_actuales = split("<br />", $value[valor]) ;
                            $j = 1;
                            $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                            $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                            $html .= '</div><div class="form-group">';
                            foreach ($cadenas as $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="p' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="p' . $i . '[]"> '. $valores . ' 
                                          </label><br>';
                                //$html .= '<input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '">'. $valores;
                                
                                $j++;
                            }
                           // $html .= '</label>';
                            break;
                        case 'Combo':
                        case '9':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '                                             
                                                      <select class="form-control" name="p' . $i . '" id="p' . $i . '" >
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $valores . '">' . $valores . '</option>';
                            }
                            $html .= '</select>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '';
                                $html .= '<input type="text"  class="col-xs-24 form-control" value="'. $value[valor] .'" name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '';
                                $html .= '<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                                $js .= "$('#p$i').datepicker();";
                            break;
                        case '13':
                        case 'Vigencia':
                                $html .= '';
                                $html = substr($html, 0, strlen($html)-39-strlen($value[Nombre]));
                                $html .= '<div class="form-group" style="margin-bottom: 0px;"><label>' . $value[Nombre] . '</label>';  
                                $html .= '</div><div class="form-group">'
                                    . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                                $html .= '<label class="radio-inline" style="color:white;">
                                                <input  type="checkbox" value="A" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/verde.png" /> 
                                              </label>';
                                $html .= '<label class="radio-inline" style="color:white;">
                                                <input  type="checkbox" value="P" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/amarillo.png" /> 
                                              </label>';
                                $html .= '<label class="radio-inline" style="color:white;">
                                                <input type="checkbox" value="V" name="p' . $i . '[]" id="p' . $i . '"> <img style="margin-top: -6px;" src="diseno/images/atrasado.png" /> 
                                              </label>';
                            
                                $html .= '<br>Desde:<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="pdesde' . $i . '" id="pdesde' . $i . '">';
                                $html .= 'Hasta:<input type="text" style=""  data-validation="date" placeholder="dd/mm/yyyy" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="phasta' . $i . '" id="phasta' . $i . '">';
                                $html .= '';
                                $js .= "$('#pdesde$i').datepicker();";
                                $js .= "$('#phasta$i').datepicker();";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);" class="form-control" value="'. $value[valor] .'"  name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';                                
                            break;
                        case 'Persona':
                        case '6':
                                $html .= '                                             
                                                      <select name="p' . $i . '" id="p' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#p' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select>';
                            break;
                        case 'Cargo':
                        case '14':
                                $html .= '';
                                $html .= '<input type="text"  class="col-xs-24 form-control" value="'. $value[valor] .'" name="p' . $i . '" id="p' . $i . '">';
                                $html .= '';
                            break;
                        case '12':
                            break;
                        case '11':
                            break;
                                
                                
                        default:
                            break;
                    }
//                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
//                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
//                    //$html .= '<input id="validacion_' . $i . '" type="hidden" value="' . $value[validacion] . '" name="validacion_' . $i . '">';
//                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
//                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    if ($value[tipo]!='11' && $value[tipo]!='12')
                        $html .= '</div>';
                    $i++;
                }
                $html .= '</table>';
                $contenido[CAMPOS_DINAMICOS] = $html;
                $grid = $this->verListaRegistrosReporte($parametros);
                $val = $this->verDocumentos($parametros[id]);
                $contenido[DOCUMENTO] = $val[Codigo_doc] . ' - ' .$val[nombre_doc];
                $contenido[TITULO_MODULO] = 'Documento [ '.$val[Codigo_doc] . ' - ' .$val[nombre_doc].' ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">
                            Lista de Registros';
                $contenido[TITULO] = $parametros[titulo];
                $_SESSION[Codigo_doc] = $val[Codigo_doc];
                $contenido['CORDER'] = $parametros['corder'];
                $contenido['SORDER'] = $parametros['sorder'];
                $contenido['MOSTRAR_COL'] = $parametros['mostrar-col'];
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['OPCIONES_BUSQUEDA'] = " <option value='campo'>campo</option>";
                $contenido['JS_NUEVO'] = 'nuevo_Registros();';
                $contenido['TITULO_NUEVO'] = 'Agregar&nbsp;Nueva&nbsp;Registros';
                $contenido['TABLA'] = $grid['tabla'];
                $contenido['PAGINADO'] = $grid['paginado'];
                $contenido['PERMISO_INGRESAR'] = $_SESSION[CookN] == 'S' ? '' : 'display:none;';
                $contenido['PERMISO_INGRESAR'] = 'display:none;';

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
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
                $template->PATH = PATH_TO_TEMPLATES.'registros/';

                $template->setTemplate("mostrar_colums");
                $template->setVars($contenido);
                $contenido['CAMPOS_MOSTRAR_COLUMNS'] = $template->show();
                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';

                if ($this->DocTieneArbolRegistros('11')>0){
                    import('clases.organizacion.ArbolOrganizacional');
                    $ao = new ArbolOrganizacional();
                    $paramreg['opcion']='reg';
                    $paramreg['cod_link'] = $parametros['cod_link'];                
                    $paramreg['modo'] = $parametros['modo'];
                    $ao->cargar_acceso_nodos_explicito($parametros);
                    $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(4,$paramreg);
                    $contenido[ARBOLFILTRO]='organizacional';
                }else
                {
                    if ($this->DocTieneCampo('6')>0){
                        import('clases.organizacion.ArbolOrganizacional');
                        $ao = new ArbolOrganizacional();
                        $paramreg['opcion']='reg';
                        $paramreg['cod_link'] = $parametros['cod_link'];                
                        $paramreg['modo'] = $parametros['modo'];
                        $ao->cargar_acceso_nodos_explicito($parametros);
                        $contenido[DIV_ARBOL_ORGANIZACIONAL] =  $ao->jstree_ao(6,$paramreg);
                        $contenido[ARBOLFILTRO]='persona';
                    }
                }
//                if ($this->DocTieneArbolRegistros('12')>0){
//                    import('clases.arbol_procesos.ArbolProcesos');
//                    $ap = new ArbolProcesos();
//                    $paramreg['opcion']='reg';
//                    $contenido[DIV_ARBOL_PROCESO] =  $ap->jstree_ap(4,$paramreg);
//                }
                $template->setTemplate("listar_volver");
                $template->setVars($contenido);
                //$this->contenido['CONTENIDO']  = $template->show();
                //$this->asigna_contenido($this->contenido);
                //return $template->show();
                if (isset($parametros['html']))
                    return $template->show();
                $objResponse = new xajaxResponse();
                $objResponse->addIncludeScript(PATH_TO_JS . 'registros/registros_reporte.js?'.rand());
                $objResponse->addAssign('desc-mod-act',"innerHTML","Registros - Documento [$val[Codigo_doc] - $val[nombre_doc]]");
                $objResponse->addAssign('contenido-aux',"innerHTML",$template->show());
                $objResponse->addAssign('permiso_modulo',"value",$parametros['permiso']);
                
                //$objResponse->addAssign('modulo_actual',"value","registros");
                
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("init_filtro_ao_multiple_reg();");
                //$objResponse->addScript("init_filtro_ap_simple_reg();");
                $objResponse->addScript($js);
                
                $objResponse->addScript('setTimeout(function(){ r_init_filtrar_reporte(); }, 500);');
                //$objResponse->addScriptCall("MostrarContenidoAux"); 
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
                $contenido_1[CODIGO_DOC] = $_SESSION[Codigo_doc];
                //$val = $this->verDocumentosNumero($val["numero"]);
                //echo count($val);
                $campos_din = $this->verCamposDinamicos();
                $html = '';
                $js='';
                $i = 1;
                foreach ($campos_din as $value) {//Nombre,tipo,valores
                    $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $value[Nombre] . '</label>';
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br/>", $value[valor]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            
                            foreach ($cadenas as $key => $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
                                            <input '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $key . '" name="campo_' . $i . '" id="campo_' . $i . '"> '. $valores . ' 
                                          </label>';
                             }
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split("<br />", $value[valor]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            $j = 1;
                            foreach ($cadenas as $key => $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $key . '" name="campo_' . $i . '_' . $j . '"> '. $valores . ' 
                                          </label>';
                                $j++;
                            }
                            $html .= '<input id="num_campo_' . $i . '" type="hidden" value="' . ($j - 1) . '" name="num_campo_' . $i . '">';
                            break;
                        case 'Combo':
                        case '9':
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            //$cadenas = split("<br />", $value[valores]) ;
                            $cadenas = array();                            
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            $html .= '<div class="col-md-10">                                              
                                                      <select class="form-control" name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $key => $valores) {
                                $html .= '<option '. ($value[valor] == $valores? 'selected' : '') .' value="' . $key . '">' . $valores . '</option>';
                            }
                            $html .= '</select></div>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '<div class="col-md-11">';
                                $html .= '<input type="text" data-validation="required" class="form-control" value="'. $value[valor] .'" name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy" data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      });";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);"  data-validation="required rut" style="width: 160px;" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';                                
                            break;
                        case 'Persona':
                        case '6':
                                if (count($this->id_org_acceso) <= 0){
                                    $this->cargar_acceso_nodos($parametros);                    
                                }
                                $html .= '<div class="col-md-11">                                              
                                                      <select name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(id_personal, ' - ',CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1
                                                                            AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                        case '10':
                            $html .= '<div class="col-md-11">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. (((count($desc_valores_params)== 0) || ($desc_valores_params[$value[cod_parametro]] == '1'))? 'checked' : '') .' type="radio" value="1" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($desc_valores_params[$value[cod_parametro]] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/amarillo.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($desc_valores_params[$value[cod_parametro]] == '3'? 'checked' : '') .' type="radio" value="3" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                            $html .= '</div>';
                            break;
                        case '11':
                                $html .= '<div class="col-md-11" style="max-height: 350px;overflow-y: scroll;">';
                                //$html .= '<input type="hidden" value="" name="nodos_'.$i.'" id="nodos_'.$i.'"/>
                                import('clases.organizacion.ArbolOrganizacional');
                                $ao = new ArbolOrganizacional();
                                $html_arbol = $ao->MuestraPadre(0, $parametros);
                                //echo $html_arbol;
                                $html .= '<input type="hidden" value="" name="nodosreg" id="nodosreg"/>
                                        <div id="div-ao-'.$i.'" class="jstree-container">
                                            <ul class="jstree">
                                                '.$html_arbol.'
                                            </ul>
                                        </div>
                                        <!--<iframe id="iframearbol_'.$i.'" src="pages/cargo/prueba_arbolV4.php?funcion=MarcarNodos('.$i.')&IDReg=" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>-->';
                                $html .= '</div>';
                                $campos_arbol_o .=$i.',';
                                $js .= "$('#div-ao-$i').jstree(
                                                {
                                                    'checkbox':{
                                                        three_state : false,
                                                            cascade : 'down'
                                                    },
                                                    'plugins': ['search', 'types','checkbox']
                                                }
                                            );
                                        $('#div-ao-$i').on('changed.jstree', function (e, data) {
                                            if (data.selected.length > 0){
                                                var arr;
                                                var id = '';
                                                for(i=0;i<data.selected.length;i++){
                                                    arr = data.selected[i].split('_');
                                                    id = id + arr[1] + ',';
                                                }
                                                id = id.substr(0,id.length-1);
                                                $('#nodosreg').val(id);
                                            }
                                            else
                                                $('#nodosreg').val('');
                                            VerificarCargo($('#nodosreg').val());
                                        });
                                        $('#div-ao-$i').jstree(true).open_all();  ";
                                break;
                        case '12':
                                $html .= '<div class="col-md-11"  style="max-height: 350px;overflow-y: scroll;">';
                                $html .= '<input type="hidden" value="" name="nodosp_'.$i.'" id="nodosp_'.$i.'"/>
                                        <div id="div-ap-'.$i.'">
                                            <div id="div-ap-'.$i.'-n" class="jstree-container">
                                                Seleccione un &Aacute;rea para cargar el &Aacute;rbol de Procesos
                                            </div>
                                        </div>
                                        <!--<iframe id="iframearbolp_'.$i.'" src="pages/cargo/arbol_procesoV4.php?funcion=MarcarNodosP('.$i.')&IDReg=" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>-->';
                                $html .= '</div>';  
                                $campos_arbol_p .=$i.',';
                                
                            break;
                        case '13':
                                $html .= '<div class="col-md-11">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy" data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      });";
                            break;  
                        case '14':
                            $cadenas = split("<br />", $value[valores]) ;
                            $html .= '<input type="hidden" name="campo_cargo_' . $i . '" id="campo_cargo_' . $i . '" value="' . $i . '" />';    
                            $html .= '<div class="col-md-11" id="col-md-10-'.$i.'">                                              
                                                      <select size=7 class="form-control" name="campo_' . $i . '[]" id="campo_' . $i . '" data-validation="required" multiple>
                                                        <option selected="" value="">-- Seleccione --</option>';
                            $html .= '</select></div>';

                            break;
                            
                        default:
                            break;
                    }
                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    $html .= '</div>';
                    $i++;
                }
                    if($campos_arbol_o != '')
                       $campos_arbol_o = substr($campos_arbol_o, 0, strlen($campos_arbol_o)-1);
                    if($campos_arbol_p != '')
                        $campos_arbol_p = substr($campos_arbol_p, 0, strlen($campos_arbol_p)-1);
                    $html .= '<input type="hidden" value="'.$campos_arbol_o.'" name="arbolesO" id="arbolesO"/>';
                    $html .= '<input type="hidden" value="'.$campos_arbol_p.'" name="arbolesP" id="arbolesP"/>';    
                
                //$html .= '</table>';
                $contenido_1[CAMPOS_DINAMICOS] = $html;
                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
                $template->setTemplate("formulario_1");
               // print_r($contenido_1);
//                $contenido['CAMPOS'] = $template->show();
//
//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
                $contenido_1['TITULO_FORMULARIO'] = "Crear&nbsp;Registros";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Registros";
                $contenido_1['PAGINA_VOLVER'] = "listarRegistros.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "new";
                $contenido_1['ID'] = "-1";

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();               
                $objResponse->addAssign('contenido-form-aux',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2Aux"); 
                $objResponse->addScriptCall("r_cargar_autocompletado");
                $objResponse->addScript("$('#MustraCargando').hide();"); 
               // $objResponse->addScript($jquerynodoarbol);
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");   
                $objResponse->addScript($js);
               // print_r($contenido_1);
                return $objResponse;
                
            }
     
 
            public function guardar($parametros)
            {   //print_r($parametros);
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                unset ($parametros['opc']);
                if($parametros['r-actualziacion']=='')
                    unset ($parametros['id']);
                $parametros['id_usuario']= $_SESSION['CookIdUsuario'];
               // print_r($parametros);
                
                for ($i = 1; $i <= 20; $i++) {
                    if (isset($parametros['nombre_' . $i]) == true){
                        if ($parametros["tipo_dato_$i"] == '11'){
                            /*Carga Acceso segun el arbol*/
                            if (count($this->id_org_acceso) <= 0){
                                $this->cargar_acceso_nodos($parametros);
                            }
                            //***********************************
                            //para validar que los nodos seleccionados
                            //tenga permisos
                            $organizacion = array();
                            if(strpos($parametros["nodosreg"],',')){    
                                $organizacion = explode(",", $parametros["nodosreg"]);
                            }
                            else{
                                $organizacion[] = $parametros["nodosreg"];                    
                            }                    
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
                            if ($areas=='break'){
                                $objResponse->addScriptCall('VerMensaje','error',utf8_encode('- Acceso denegado para registrar Documentos en el &aacute;rea seleccionada.'));
                                return $objResponse;
                            }
                            if ($areas!='break' && $areas!='' ){
                                $objResponse->addScriptCall('VerMensaje','error',utf8_encode('- Acceso denegado para registrar Documentos en el &aacute;rea ' . $areas . '.'));                           
                                return $objResponse;                                           
                            }                   
                                        
                        }
                    }
                }
                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    
                    $archivo = '';
                    if((isset($parametros[filename]))&& ($parametros[filename] !=''))
                    {
                            //$Archivo=CambiaSinAcento(str_replace('~~',' ',utf8_encode($Adjunto)));
                            $tamanio=filesize(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                            $fp = fopen(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']), "rb");
                            $archivo = fread($fp, $tamanio);
                            $archivo = addslashes($archivo);
                            fclose($fp);
                            //identificacion,id_usuario,descripcion,doc_fisico,
                            $parametros[identificacion] = $parametros[nombre_doc];
                            $parametros[contentType] = $parametros[tipo_doc];//'application/pdf';                                    
                            $parametros[descripcion] = "$parametros[Codigo_doc]-$parametros[nombre_doc].$parametros[tipo_doc]";
                            //$parametros['filename'];
                           
                    }
                     
                    $respuesta = $this->ingresarRegistros($parametros,$archivo);
                  
                    //if (preg_match("/ha sido ingresado con exito/",$respuesta ) == true) {
                    if (strlen($respuesta ) < 10 ) {
                        for ($i = 1; $i <= 20; $i++) {
                            if (isset($parametros['nombre_' . $i]) == true){
                                if ($parametros["tipo_dato_$i"] == '8'){
                                    $valor_actual_aux = '';
                                    for($j = 1; $j <= $parametros["num_campo_$i"]; $j++){
                                        if (isset($parametros["campo_" . $i . "_" . $j]) == true){
                                            $valor_actual_aux .= $parametros["campo_" . $i . "_" . $j] . '<br/>';
                                        }
                                    }
                                    $valor_actual_aux = substr($valor_actual_aux, 0, strlen($valor_actual_aux) - 5);                                    
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    $params[Nombre] = $valor_actual_aux;
                                    //$params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $respuesta;
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    
                                    $this->ingresarRegistrosCampoDinamico($params);
                                    
                                }
                                else
                                {
                                    //$params[id_documento] = $id_documento;
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    //$params[validacion] = $parametros["validacion_$i"];
                                    //$params[valores] = $parametros["valores_$i"];   
                                    if($params[tipo]=='11')
                                        $params[Nombre] = $parametros["nodosreg"];//str_replace(",", "<br>", $parametros["nodos_".$i]);
                                    elseif($params[tipo]=='12')
                                        $params[Nombre] = $parametros["nodosp_".$i];
                                    else
                                        $params[Nombre] = $parametros["campo_". $i];
                                    
                                    $params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $respuesta;
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    //print_r($params);
                                    $this->ingresarRegistrosCampoDinamico($params);
                                }
                            }
                        }
                        
                        try{
                            unlink(APPLICATION_DOWNLOADS. 'temp/' . CambiaSinAcento($parametros['filename']));
                        } catch (Exception $ex) {

                        }
                        $objResponse->addScriptCall("MostrarContenidoAux");
                        $objResponse->addScriptCall('VerMensaje','exito',"El Registro '$parametros[nombre_doc]' ha sido ingresado con exito");
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                
                return $objResponse;
            }
     
 
            public function editar($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verRegistros($parametros[id]); 

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
                $contenido_1[NOMBRE_DOC] = $val["descripcion"];
                $contenido_1['IDREGISTRO'] = $val["idRegistro"];
                $contenido_1['IDDOC'] = $val["IDDoc"];
                $contenido_1['IDENTIFICACION'] = ($val["identificacion"]);
                $contenido_1['VERSION'] = $val["version"];
                $contenido_1['CORRELATIVO'] = $val["correlativo"];
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
                $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
                $contenido_1['ID_PROCESOS'] = $val["id_procesos"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                
                $campos_din = $this->verValoresCamposDinamicos($val["idRegistro"]);                
                $html = '';
                $js='';
                $i = 1;
                $campos_arbol_o='';
                $campos_arbol_p='';
                foreach ($campos_din as $value) {//Nombre,tipo,valores
                    $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $value[Nombre] . '</label>';
                    //$html .= '<td style="width: 141px;" class="title">' . $value[Nombre] . ':</td><td>';
                    /*
                      $ids = array('7','8','9','1','2','3','5','6');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona');
                     */
                    //print_r($value);
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split(",", $value[valor]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            foreach ($cadenas as $key => $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
                                            <input '. (in_array($key, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $key . '" name="campo_' . $i . '" id="campo_' . $i . '"> '. $valores . ' 
                                          </label>';
                                
                                //$html .= '<input type="radio" class="form-box" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' value="' . $valores . '" size="20" name="campo_' . $i . '" id="campo_' . $i . '">'. $valores;
                            }
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split(",", $value[valor]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            $j = 1;
                            foreach ($cadenas as $key => $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="campo_' . $i . '_' . $j . '" '. (in_array($key, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $key . '" name="campo_' . $i . '_' . $j . '"> '. $valores . ' 
                                          </label>';
                                //$html .= '<input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '">'. $valores;
                                
                                $j++;
                            }
                            $html .= '<input id="num_campo_' . $i . '" type="hidden" value="' . ($j - 1) . '" name="num_campo_' . $i . '">';
                            break;
                        case 'Combo':
                        case '9':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '<div class="col-md-10">                                              
                                                      <select class="form-control" name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $key => $valores) {
                                $html .= '<option '. ($value[valor] == $key? 'selected' : '') .' value="' . $key . '">' . $valores . '</option>';
                            }
                            $html .= '</select></div>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '<div class="col-md-10">';
                                $html .= '<input type="text" data-validation="required" class="form-control" value="'. $value[valor] .'" name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      }); ";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);"  data-validation="required rut" style="width: 160px;" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';                                
                            break;
                        case 'Persona':
                        case '6':
                                if (count($this->id_org_acceso) <= 0){
                                    $this->cargar_acceso_nodos($parametros);                    
                                }
                                $html .= '<div class="col-md-10">                                              
                                                      <select name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(id_personal, ' - ',CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1
                                                                            AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                        case '10':
                            $html .= '<div class="col-md-11">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. (($value[valor] == '1')? 'checked' : '') .' type="radio" value="1" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($value[valor] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/amarillo.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($value[valor] == '3'? 'checked' : '') .' type="radio" value="3" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                            $html .= '</div>';
                            break;
                        
                        case '11':
                                $html .= '<div class="col-md-11" style="max-height: 350px;overflow-y: scroll;">';
                                import('clases.organizacion.ArbolOrganizacional');
                                $ao = new ArbolOrganizacional();
                                
                                //$organizacion = array();
                                //if(strpos($val[id_organizacion],',')){    
                               //         $organizacion = explode(",", $val[id_organizacion]);
                               //     }
                               //     else{
                               //         $organizacion[] = $val[id_organizacion];                    
                               //     }
                                $parametros[nodos_seleccionados] = explode(",", $value[valor]) ;;
                                $html_arbol = $ao->MuestraPadre(0, $parametros);
                                //$html .= '<input type="hidden" value="'.$this->verArbol($value[id_unico],$value[idRegistro]).'" name="nodos_'.$i.'" id="nodos_'.$i.'"/>
                                $html .= '<input type="hidden" value="" name="nodosreg" id="nodosreg"/>
                                        <input type="hidden" id="cargar_cargo" name="cargar_cargo" value="0">
                                        <div id="div-ao-'.$i.'" class="jstree-container">
                                            <ul class="jstree">
                                                '.$html_arbol.'
                                            </ul>
                                        </div>
                                        <!--<iframe id="iframearbol_'.$i.'" src="pages/cargo/prueba_arbolV4.php?IDUnico='.$value[id_unico].'&IDReg='.$val["idRegistro"].'" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>-->';
                                $html .= '</div>';  
                                $campos_arbol_o .=$i.',';
                                $js .= "$('#div-ao-$i').jstree(
                                                {
                                                    'checkbox':{
                                                        three_state : false,
                                                            cascade : ''
                                                    },
                                                    'plugins': ['search', 'types','checkbox']
                                                }
                                            );
                                        $('#div-ao-$i').on('select_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.select_node(data.node.children_d); }
                                        });
                                        $('#div-ao-$i').on('deselect_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.deselect_node(data.node.children_d); }
                                        });
                                        $('#div-ao-$i').on('changed.jstree', function (e, data) {
                                            if (data.selected.length > 0){
                                                var arr;
                                                var id = '';
                                                for(i=0;i<data.selected.length;i++){
                                                    arr = data.selected[i].split('_');
                                                    id = id + arr[1] + ',';
                                                }
                                                id = id.substr(0,id.length-1);
                                                $('#nodosreg').val(id);
                                            }
                                            else
                                                $('#nodosreg').val('');
                                            if ($('#cargar_cargo').val() == '1')
                                                VerificarCargo($('#nodosreg').val());
                                            else
                                                $('#cargar_cargo').val('1');                                                                                 
                                        });
                                        $('#div-ao-$i').jstree(true).open_all();  ";
                            break;/*else{
                                                       */
                        case '12':
                            /*DATOS SELECCIONADOS ARBOL ORGANIZACIONAL*/
                                $sql = 'select GROUP_CONCAT(valor) valor 
                                                from mos_registro_item t1 
                                                where tipo=11 and idRegistro ='.$val["idRegistro"];                           
                                $this->operacion($sql, $atr);
                                $seleccionados = $this->dbl->data[0][valor];  
                                $html .= '<div class="col-md-11" style="max-height: 350px;overflow-y: scroll;">';//'.$this->verArbolP($value[id_unico],$value[idRegistro]).'
                                $html .= '<input type="hidden" value="" name="nodosp_'.$i.'" id="nodosp_'.$i.'"/>
                                        <div id="div-ap-'.$i.'">
                                            <div id="div-ap-'.$i.'-n" class="jstree-container">
                                                Seleccione un &Aacute;rea para cargar el &Aacute;rbol de Procesos
                                            </div>
                                        </div>
                                        <!--<iframe id="iframearbolp_'.$i.'" src="pages/cargo/arbol_procesoV4.php?funcion=MarcarNodosP('.$i.')&IDUnico='.$value[id_unico].'&IDReg='.$val["idRegistro"].'" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>-->';
                                $html .= '</div>';   
                                $campos_arbol_p .=$i.',';
                                $js .= "$('#div-ap-$i-n')
                                            .jstree({
                                                    'core' : {
                                                            'data' : {
                                                                    'url' : 'clases/arbol_procesos/server.php?id_ao=$seleccionados&MarcarNodosP=$value[valor]',                                        
                                                                    'data' : function (node) {
                                                                            return { 'id' : node.id };
                                                                    }
                                                            },
                                                            'check_callback' : true,
                                                            'themes' : {
                                                                    'responsive' : false
                                                            }
                                                    },
                                                    'force_text' : true,                        
                                                    'checkbox':{
                                                        three_state : false,
                                                            cascade : ''
                                                    },
                                                    'plugins' : ['search', 'types','checkbox']
                                            });
                                        $('#div-ao-$i-n').on('select_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.select_node(data.node.children_d); }
                                        });
                                        $('#div-ao-$i-n').on('deselect_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.deselect_node(data.node.children_d); }
                                        });
                                        $('#div-ap-$i-n').on('changed.jstree', function (e, data) {
                                            if (data.selected.length > 0){                                       
                                                var id = '';
                                                for(k=0;k<data.selected.length;k++){                        
                                                    id = id + data.selected[k] + ',';
                                                }
                                                id = id.substr(0,id.length-1);
                                                $('#nodosp_$i').val(id);
                                            }
                                            else
                                                $('#nodosp_$i').val('');               
                                        });";
                            break;
                        case '13':
                                $html .= '<div class="col-md-11">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      });";
                            break;                        
                        
                        case '14':
                                $sql = 'select GROUP_CONCAT(valor) valor 
                                                from mos_registro_item t1 
                                                where id_unico='.$value[id_unico].' and idRegistro ='.$val["idRegistro"];
                                $this->operacion($sql, $atr);
                                $seleccionados = $this->dbl->data[0][valor];                                
                                $html .= '<div class="col-md-11" id="col-md-10-'.$i.'">  ';                                            
                                $sql = 'SELECT DISTINCT
                                        mos_cargo.cod_cargo id,
                                        mos_cargo.descripcion,
                                        cargos_reg.valor
                                        FROM
                                        mos_cargo_estrorg_arbolproc
                                        INNER JOIN mos_cargo ON mos_cargo.cod_cargo = mos_cargo_estrorg_arbolproc.cod_cargo
                                        left join (select valor 
                                                from mos_registro_item t1 
                                                where id_unico='.$value[id_unico].' and idRegistro ='.$val["idRegistro"].') cargos_reg
                                        on mos_cargo.cod_cargo = cargos_reg.valor
                                        where mos_cargo_estrorg_arbolproc.id in (select valor
                                                                                from mos_registro_item 
                                                                                where tipo=11 and idRegistro ='.$val["idRegistro"].')
                                                                            order by mos_cargo.descripcion';                                
                                $combosemp = '';
                                
                                $combosemp .= $ut_tool->OptionsComboMultiple($sql, 'id', 'descripcion','valor');      
                                $html .= '<input type="hidden" name="campo_cargo_' . $i . '" id="campo_cargo_' . $i . '" value="' . $i . '" />';    
                                $html .= '<input type="hidden" name="sel_cargo_' . $i . '" id="sel_cargo_' . $i . '" value="' .$seleccionados. '" />';
                                $html .="<select size=7 class='form-control' id=\"campo_".$i."\" name=\"campo_".$i."[]\"  data-validation=\"required\" multiple>
                                            <option value=''>-- Seleccione --</option>
                                            ".$combosemp."
                                        </select>    ";
                                $html .= '</select></div>';
                                
                                break;
                        default:
                            break;
                    }
                    //CON ESTO CONTROLO CUANTOS ARBOLES HAY PARA EL VALIDAR QUE TENGAN SELECCIONADOS
                    //FIN VALIDACION DE ARBOLES
                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
                    //$html .= '<input id="validacion_' . $i . '" type="hidden" value="' . $value[validacion] . '" name="validacion_' . $i . '">';
                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    if (strlen($value[idRegistro]) == 0)
                        $html .= '<input id="id_unico_campo_new_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_unico_campo_new_' . $i . '">';
                    $html .= '</div>';
                    $i++;
                }
                    if($campos_arbol_o != '')
                       $campos_arbol_o = substr($campos_arbol_o, 0, strlen($campos_arbol_o)-1);
                    if($campos_arbol_p != '')
                        $campos_arbol_p = substr($campos_arbol_p, 0, strlen($campos_arbol_p)-1);
                    $html .= '<input type="hidden" value="'.$campos_arbol_o.'" name="arbolesO" id="arbolesO"/>';
                    $html .= '<input type="hidden" value="'.$campos_arbol_p.'" name="arbolesP" id="arbolesP"/>';    
                
                $html .= '</table>';
                $contenido_1[CAMPOS_DINAMICOS] = $html;

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
                $template->setTemplate("formulario_editar");
//                $template->setVars($contenido_1);
//
//                $contenido['CAMPOS'] = $template->show();

//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");

                $contenido_1['TITULO_FORMULARIO'] = "Editar&nbsp;Registros";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Registros";
                $contenido_1['PAGINA_VOLVER'] = "listarRegistros.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "upd";
                //echo $val["idRegistro"];
                $contenido_1['ID'] = $val["idRegistro"];

                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form-aux',"innerHTML",$template->show());
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2Aux");   
                $objResponse->addScript($js);
                $objResponse->addScriptCall("r_cargar_autocompletado");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  return $objResponse;
            }

            public function crear_actualizacion($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }
                $ut_tool = new ut_Tool();
                $val = $this->verRegistros($parametros[id]); 

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
                $contenido_1[CODIGO_DOC] = $_SESSION[Codigo_doc];
                $contenido_1[NOMBRE_DOC] = $val["descripcion"];
                $contenido_1['IDREGISTRO'] = $val["idRegistro"];
                $contenido_1['IDDOC'] = $val["IDDoc"];
                $contenido_1['IDENTIFICACION'] = ($val["identificacion"]);
                $contenido_1['VERSION'] = $val["version"];
                $contenido_1['CORRELATIVO'] = $val["correlativo"];
                $contenido_1['ID_USUARIO'] = $val["id_usuario"];
                $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
                $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
                $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
                $contenido_1['ID_PROCESOS'] = $val["id_procesos"];
                $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
                
                $campos_din = $this->verValoresCamposDinamicos($val["idRegistro"]);                
                $html = '';
                $js='';
                $i = 1;
                $campos_arbol_o='';
                $campos_arbol_p='';
              
                if (count($this->parametros) <= 0){
                        $this->cargar_parametros();
                } 
                $k = 5;
                $contenido[PARAMETROS_OTROS] = "";
                //print_r($this->parametros);
                $parametros['mostrar-col']="2-4"; 
                foreach ($this->parametros as $value) {  
                    if ($value[tipo] == 13){
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                          Vigencia </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                        $k++;
                    }
                    else if ($value[tipo] == 14){
                       $parametros['mostrar-col'] .= "-$k";
                       $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                      
                                     <label >
                                         <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                     ' . $value[Nombre] . '</label>
                                 
                           </div>';
                       $k++;
                       //  $parametros['mostrar-col'] .= "-$k";
                       $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                      
                                     <label >
                                         <input  type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                     ' . $value[Nombre] . ' Personal </label>
                                 
                           </div>';
                       $k++;   
                        
                    }
                    else if ($value[tipo] == 6){
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">
                                       
                                      <label >
                                          <input checked="checked" type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                      ' . $value[Nombre] . '</label>
                                  
                            </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          ID ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          Área ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" >   &nbsp;
                                          Cargo ' . $value[Nombre] . ' </label>

                                </div>';
                        $k++;
                    }
                    else{
                        $parametros['mostrar-col'] .= "-$k";
                        $contenido[PARAMETROS_OTROS] .= '<div class="checkbox">

                                          <label >
                                              <input type="checkbox" name="SelectAcc" id="SelectAcc" value="' . $k . '" class="r-checkbox-mos-col" checked="checked">   &nbsp;
                                          ' . $value[Nombre] . '</label>

                                </div>';
                        $k++;
                    }
                }
                
                foreach ($campos_din as $value) {//Nombre,tipo,valores
                    $html .= '<div class="form-group">
                                        <label for="idRegistro" class="col-md-4 control-label">' . $value[Nombre] . '</label>';
                    //$html .= '<td style="width: 141px;" class="title">' . $value[Nombre] . ':</td><td>';
                    /*
                      $ids = array('7','8','9','1','2','3','5','6');
                $desc = array('Seleccion Simple','Seleccion Multiple','Combo','Texto','Numerico','Fecha','Rut','Persona');
                     */
                    //print_r($value);
                    switch ($value[tipo]) {
                        case 'Seleccion Simple':
                        case '7':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split(",", $value[valor]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            foreach ($cadenas as $key => $valores) {
                               
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="radio-inline">
                                            <input '. (in_array($key, $valores_actuales)? 'checked' : '') .' type="radio" value="' . $key . '" name="campo_' . $i . '" id="campo_' . $i . '"> '. $valores . ' 
                                          </label>';
                                
                                //$html .= '<input type="radio" class="form-box" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' value="' . $valores . '" size="20" name="campo_' . $i . '" id="campo_' . $i . '">'. $valores;
                            }
                            break;
                        case 'Seleccion Multiple':
                        case '8':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $valores_actuales = split(",", $value[valor]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            $j = 1;
                            foreach ($cadenas as $key => $valores) {
                                
                                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<label class="checkbox-inline">
                                            <input id="campo_' . $i . '_' . $j . '" '. (in_array($key, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $key . '" name="campo_' . $i . '_' . $j . '"> '. $valores . ' 
                                          </label>';
                                //$html .= '<input id="campo_' . $i . '_' . $j . '" '. (in_array($valores, $valores_actuales)? 'checked' : '') .' type="checkbox" value="' . $valores . '" name="campo_' . $i . '_' . $j . '">'. $valores;
                                
                                $j++;
                            }
                            $html .= '<input id="num_campo_' . $i . '" type="hidden" value="' . ($j - 1) . '" name="num_campo_' . $i . '">';
                            break;
                        case 'Combo':
                        case '9':
                            //$cadenas = split("<br />", $value[valores]) ;
                            $items_campo = $this->verItemsCamposDinamicos($value[id_unico]);
                            $cadenas = array();
                            foreach ($items_campo as $value_item) {
                                $cadenas[$value_item[id]] = $value_item[descripcion];
                            }
                            //$html .= '<select id="campo_' . $i . '" name="campo_' . $i . '" class="form-box"><option value="">Seleccione</option>';
                            $html .= '<div class="col-md-10">                                              
                                                      <select class="form-control" name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                            foreach ($cadenas as $key => $valores) {
                                $html .= '<option '. ($value[valor] == $key? 'selected' : '') .' value="' . $key . '">' . $valores . '</option>';
                            }
                            $html .= '</select></div>';
                            break;
                        case 'Texto':
                        case '1':
                                $html .= '<div class="col-md-10">';
                                $html .= '<input type="text" data-validation="required" class="form-control" value="'. $value[valor] .'" name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case 'Numerico':
                        case '2':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" data-validation="number" data-validation-allowing="float,negative" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                            break;
                        case '3':
                        case 'Fecha':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      }); ";
                            break;
                        case '5':
                        case 'Rut':
                                $html .= '<div class="col-md-6">';
                                $html .= '<input type="text" onblur="this.value=$.Rut.formatear(this.value);"  data-validation="required rut" style="width: 160px;" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';                                
                            break;
                        case 'Persona':
                        case '6':
                                if (count($this->id_org_acceso) <= 0){
                                    $this->cargar_acceso_nodos($parametros);                    
                                }
                                $html .= '<div class="col-md-10">                                              
                                                      <select name="campo_' . $i . '" id="campo_' . $i . '" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>';
                                $html .= $ut_tool->OptionsCombo("SELECT cod_emp, 
                                                                        CONCAT(id_personal, ' - ',CONCAT(UPPER(LEFT(p.apellido_paterno, 1)), LOWER(SUBSTRING(p.apellido_paterno, 2))),' ', CONCAT(UPPER(LEFT(p.apellido_materno, 1)), LOWER(SUBSTRING(p.apellido_materno, 2))), ' ', CONCAT(UPPER(LEFT(p.nombres, 1)), LOWER(SUBSTRING(p.nombres, 2))))  nombres
                                                                            FROM mos_personal p WHERE interno = 1
                                                                            AND id_organizacion IN (". implode(',', array_keys($this->id_org_acceso)) . ")"
                                                                    , 'cod_emp'
                                                                    , 'nombres', $value[valor]);
                                $js .= '$( "#campo_' . $i . '" ).select2({
                                            placeholder: "Selecione",
                                            allowClear: true
                                          }); ';
                                $html .= '</select></div>';
                                break;
                        case '10':
                            $html .= '<div class="col-md-11">'
                                . '<label for="campo-'.$value[cod_parametro].'" class="col-md-'.$col_lab.' control-label">' . $value[espanol] . '</label>'; 
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. (($value[valor] == '1')? 'checked' : '') .' type="radio" value="1" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/verde.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($value[valor] == '2'? 'checked' : '') .' type="radio" value="2" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/amarillo.png" /> 
                                          </label>';
                            $html .= '<label class="radio-inline" style="color:white;">
                                            <input '. ($value[valor] == '3'? 'checked' : '') .' type="radio" value="3" name="campo_' . $i . '" id="campo_' . $i . '"> <img src="diseno/images/atrasado.png" /> 
                                          </label>';
                            $html .= '</div>';
                            break;
                        
                        case '11':
                                $html .= '<div class="col-md-11" style="max-height: 350px;overflow-y: scroll;">';
                                import('clases.organizacion.ArbolOrganizacional');
                                $ao = new ArbolOrganizacional();
                                
                                //$organizacion = array();
                                //if(strpos($val[id_organizacion],',')){    
                               //         $organizacion = explode(",", $val[id_organizacion]);
                               //     }
                               //     else{
                               //         $organizacion[] = $val[id_organizacion];                    
                               //     }
                                $parametros[nodos_seleccionados] = explode(",", $value[valor]) ;;
                                $html_arbol = $ao->MuestraPadre(0, $parametros);
                                //$html .= '<input type="hidden" value="'.$this->verArbol($value[id_unico],$value[idRegistro]).'" name="nodos_'.$i.'" id="nodos_'.$i.'"/>
                                $html .= '<input type="hidden" value="" name="nodosreg" id="nodosreg"/>
                                        <input type="hidden" id="cargar_cargo" name="cargar_cargo" value="0">
                                        <div id="div-ao-'.$i.'" class="jstree-container">
                                            <ul class="jstree">
                                                '.$html_arbol.'
                                            </ul>
                                        </div>
                                        <!--<iframe id="iframearbol_'.$i.'" src="pages/cargo/prueba_arbolV4.php?IDUnico='.$value[id_unico].'&IDReg='.$val["idRegistro"].'" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>-->';
                                $html .= '</div>';  
                                $campos_arbol_o .=$i.',';
                                $js .= "$('#div-ao-$i').jstree(
                                                {
                                                    'checkbox':{
                                                        three_state : false,
                                                            cascade : ''
                                                    },
                                                    'plugins': ['search', 'types','checkbox']
                                                }
                                            );
                                        $('#div-ao-$i').on('select_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.select_node(data.node.children_d); }
                                        });
                                        $('#div-ao-$i').on('deselect_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.deselect_node(data.node.children_d); }
                                        });
                                        $('#div-ao-$i').on('changed.jstree', function (e, data) {
                                            if (data.selected.length > 0){
                                                var arr;
                                                var id = '';
                                                for(i=0;i<data.selected.length;i++){
                                                    arr = data.selected[i].split('_');
                                                    id = id + arr[1] + ',';
                                                }
                                                id = id.substr(0,id.length-1);
                                                $('#nodosreg').val(id);
                                            }
                                            else
                                                $('#nodosreg').val('');
                                            if ($('#cargar_cargo').val() == '1')
                                                VerificarCargo($('#nodosreg').val());
                                            else
                                                $('#cargar_cargo').val('1');                                                                                 
                                        });
                                        $('#div-ao-$i').jstree(true).open_all();  ";
                            break;/*else{
                                                       */
                        case '12':
                            /*DATOS SELECCIONADOS ARBOL ORGANIZACIONAL*/
                                $sql = 'select GROUP_CONCAT(valor) valor 
                                                from mos_registro_item t1 
                                                where tipo=11 and idRegistro ='.$val["idRegistro"];                           
                                $this->operacion($sql, $atr);
                                $seleccionados = $this->dbl->data[0][valor];  
                                $html .= '<div class="col-md-11" style="max-height: 350px;overflow-y: scroll;">';//'.$this->verArbolP($value[id_unico],$value[idRegistro]).'
                                $html .= '<input type="hidden" value="" name="nodosp_'.$i.'" id="nodosp_'.$i.'"/>
                                        <div id="div-ap-'.$i.'">
                                            <div id="div-ap-'.$i.'-n" class="jstree-container">
                                                Seleccione un &Aacute;rea para cargar el &Aacute;rbol de Procesos
                                            </div>
                                        </div>
                                        <!--<iframe id="iframearbolp_'.$i.'" src="pages/cargo/arbol_procesoV4.php?funcion=MarcarNodosP('.$i.')&IDUnico='.$value[id_unico].'&IDReg='.$val["idRegistro"].'" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>-->';
                                $html .= '</div>';   
                                $campos_arbol_p .=$i.',';
                                $js .= "$('#div-ap-$i-n')
                                            .jstree({
                                                    'core' : {
                                                            'data' : {
                                                                    'url' : 'clases/arbol_procesos/server.php?id_ao=$seleccionados&MarcarNodosP=$value[valor]',                                        
                                                                    'data' : function (node) {
                                                                            return { 'id' : node.id };
                                                                    }
                                                            },
                                                            'check_callback' : true,
                                                            'themes' : {
                                                                    'responsive' : false
                                                            }
                                                    },
                                                    'force_text' : true,                        
                                                    'checkbox':{
                                                        three_state : false,
                                                            cascade : ''
                                                    },
                                                    'plugins' : ['search', 'types','checkbox']
                                            });
                                        $('#div-ao-$i-n').on('select_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.select_node(data.node.children_d); }
                                        });
                                        $('#div-ao-$i-n').on('deselect_node.jstree', function (e, data) {
                                            if(data.event) { data.instance.deselect_node(data.node.children_d); }
                                        });
                                        $('#div-ap-$i-n').on('changed.jstree', function (e, data) {
                                            if (data.selected.length > 0){                                       
                                                var id = '';
                                                for(k=0;k<data.selected.length;k++){                        
                                                    id = id + data.selected[k] + ',';
                                                }
                                                id = id.substr(0,id.length-1);
                                                $('#nodosp_$i').val(id);
                                            }
                                            else
                                                $('#nodosp_$i').val('');               
                                        });";
                            break;
                        case '13':
                                $html .= '<div class="col-md-11">';
                                $html .= '<input type="text" style="width: 120px;" placeholder="dd/mm/yyyy"  data-validation="date" data-validation-format="dd/mm/yyyy" class="form-control" value="'. $value[valor] .'"  name="campo_' . $i . '" id="campo_' . $i . '">';
                                $html .= '</div>';
                                $js .= "$('#campo_$i').datepicker({
                        changeMonth: true,
                        yearRange: '-50:+20',
                        changeYear: true
                      });";
                            break;                        
                        
                        case '14':
                                $sql = 'select GROUP_CONCAT(valor) valor 
                                                from mos_registro_item t1 
                                                where id_unico='.$value[id_unico].' and idRegistro ='.$val["idRegistro"];
                                $this->operacion($sql, $atr);
                                $seleccionados = $this->dbl->data[0][valor];                                
                                $html .= '<div class="col-md-11" id="col-md-10-'.$i.'">  ';                                            
                                $sql = 'SELECT DISTINCT
                                        mos_cargo.cod_cargo id,
                                        mos_cargo.descripcion,
                                        cargos_reg.valor
                                        FROM
                                        mos_cargo_estrorg_arbolproc
                                        INNER JOIN mos_cargo ON mos_cargo.cod_cargo = mos_cargo_estrorg_arbolproc.cod_cargo
                                        left join (select valor 
                                                from mos_registro_item t1 
                                                where id_unico='.$value[id_unico].' and idRegistro ='.$val["idRegistro"].') cargos_reg
                                        on mos_cargo.cod_cargo = cargos_reg.valor
                                        where mos_cargo_estrorg_arbolproc.id in (select valor
                                                                                from mos_registro_item 
                                                                                where tipo=11 and idRegistro ='.$val["idRegistro"].')
                                                                            order by mos_cargo.descripcion';                                
                                $combosemp = '';
                                
                                $combosemp .= $ut_tool->OptionsComboMultiple($sql, 'id', 'descripcion','valor');      
                                $html .= '<input type="hidden" name="campo_cargo_' . $i . '" id="campo_cargo_' . $i . '" value="' . $i . '" />';    
                                $html .= '<input type="hidden" name="sel_cargo_' . $i . '" id="sel_cargo_' . $i . '" value="' .$seleccionados. '" />';
                                $html .="<select size=7 class='form-control' id=\"campo_".$i."\" name=\"campo_".$i."[]\"  data-validation=\"required\" multiple>
                                            <option value=''>-- Seleccione --</option>
                                            ".$combosemp."
                                        </select>    ";
                                $html .= '</select></div>';
                                
                                break;
                        default:
                            break;
                    }
                    //CON ESTO CONTROLO CUANTOS ARBOLES HAY PARA EL VALIDAR QUE TENGAN SELECCIONADOS
                    //FIN VALIDACION DE ARBOLES
                    $html .= '<input id="tipo_dato_' . $i . '" type="hidden" value="' . $value[tipo] . '" name="tipo_dato_' . $i . '">';
                    $html .= '<input id="nombre_' . $i . '" type="hidden" value="' . $value[Nombre] . '" name="nombre_' . $i . '">';
                    //$html .= '<input id="validacion_' . $i . '" type="hidden" value="' . $value[validacion] . '" name="validacion_' . $i . '">';
                    $html .= '<input id="valores_' . $i . '" type="hidden" value="' . $value[valores] . '" name="valores_' . $i . '">';
                    $html .= '<input id="id_atributo_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_atributo_' . $i . '">';
                    if (strlen($value[idRegistro]) == 0)
                        $html .= '<input id="id_unico_campo_new_' . $i . '" type="hidden" value="' . $value[id_unico] . '" name="id_unico_campo_new_' . $i . '">';
                    $html .= '</div>';
                    $i++;
                }
                    if($campos_arbol_o != '')
                       $campos_arbol_o = substr($campos_arbol_o, 0, strlen($campos_arbol_o)-1);
                    if($campos_arbol_p != '')
                        $campos_arbol_p = substr($campos_arbol_p, 0, strlen($campos_arbol_p)-1);
                    $html .= '<input type="hidden" value="'.$campos_arbol_o.'" name="arbolesO" id="arbolesO"/>';
                    $html .= '<input type="hidden" value="'.$campos_arbol_p.'" name="arbolesP" id="arbolesP"/>';    
                
                $html .= '</table>';
                $contenido_1[CAMPOS_DINAMICOS] = $html;

                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
                $template->setTemplate("formulario_actualizacion");
//                $template->setVars($contenido_1);
//
//                $contenido['CAMPOS'] = $template->show();

//                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
//                $template->setTemplate("formulario");
                $parametros['corder']="idRegistro";
                $parametros['sorder']="desc"; 
                
                
                $grid = $this->verListaRegistrosHistorico($parametros);
                
                $contenido_1['TABLA'] = $grid['tabla'];
                $contenido_1['TITULO_FORMULARIO'] = "Editar&nbsp;Registros";
                $contenido_1['TITULO_VOLVER'] = "Volver&nbsp;a&nbsp;Listado&nbsp;de&nbsp;Registros";
                $contenido_1['PAGINA_VOLVER'] = "listarRegistros.php";
                $contenido_1['DESC_OPERACION'] = "Guardar";
                $contenido_1['OPC'] = "new";
                //echo $val["idRegistro"];
                $contenido_1['ID'] = $val["idRegistro"];
                $contenido_1['IDORIGINAL'] = $val["idRegistro_original"];
                
                $template->setVars($contenido_1);
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('contenido-form-aux',"innerHTML",$template->show());
                
                $objResponse->addScriptCall("calcHeight");
                $objResponse->addScriptCall("MostrarContenido2Aux");   
                $objResponse->addScript($js);
                $objResponse->addScriptCall("r_cargar_autocompletado");
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("$('#tabs-hv').tab();$('#tabs-hv a:last').tab('show');");
                   // $objResponse->addScript ("$('.nav-tabs a[href=\"#hv-red\"]').hide();");
                
                $objResponse->addScript("$.validate({
                            lang: 'es'  
                          });");  
                return $objResponse;
            }
            
 
            public function actualizar($parametros)
            {
                session_name("$GLOBALS[SESSION]");
                session_start();
                $objResponse = new xajaxResponse();
                $objResponse->addScript("$('#MustraCargando').hide();"); 
                 $objResponse->addScript("$('#btn-guardar' ).html('Guardar');
                                        $( '#btn-guardar' ).prop( 'disabled', false );");
                unset ($parametros['opc']);
                $parametros['id_usuario']= $_SESSION['USERID'];
                for ($i = 1; $i <= 20; $i++) {
                    if (isset($parametros['nombre_' . $i]) == true){
                        if ($parametros["tipo_dato_$i"] == '11'){
                            /*Carga Acceso segun el arbol*/
                            if (count($this->id_org_acceso) <= 0){
                                $this->cargar_acceso_nodos($parametros);
                            }
                            //print_r($this->id_org_acceso);
                            //***********************************
                            //para validar que los nodos seleccionados
                            //tenga permisos
                            $organizacion = array();
                            if(strpos($parametros["nodosreg"],',')){    
                                $organizacion = explode(",", $parametros["nodosreg"]);
                            }
                            else{
                                $organizacion[] = $parametros["nodosreg"];                    
                            }                    
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
                            if ($areas=='break'){
                                $objResponse->addScriptCall('VerMensaje','error',utf8_encode('- Acceso denegado para registrar Documentos en el &aacute;rea seleccionada.'));
                                return $objResponse;
                            }
                            if ($areas!='break' && $areas!='' ){
                                $objResponse->addScriptCall('VerMensaje','error',utf8_encode('- Acceso denegado para registrar Documentos en el &aacute;rea ' . $areas . '.'));                           
                                return $objResponse;                                           
                            }                   
                                        
                        }
                    }
                }
                $validator = new FormValidator();
                
                if(!$validator->ValidateForm($parametros)){
                        $error_hash = $validator->GetErrors();
                        $mensaje="";
                        foreach($error_hash as $inpname => $inp_err){
                                $mensaje.="- $inp_err <br/>";
                        }
                         $objResponse->addScriptCall('VerMensaje','error',utf8_encode($mensaje));
                }else{
                    
                    //$respuesta = $this->modificarRegistros($parametros);

                    //if (preg_match("/ha sido actualizado con exito/",$respuesta ) == true) 
                    if (1 == 1)
                    {
                        $nuevo = '';
                        for ($i = 1; $i <= 20; $i++) {
                            if (isset($parametros['nombre_' . $i]) == true){
                                if ($parametros["tipo_dato_$i"] == '8'){
                                    $valor_actual_aux = '';
                                    for($j = 1; $j <= $parametros["num_campo_$i"]; $j++){
                                        if (isset($parametros["campo_" . $i . "_" . $j]) == true){
                                            $valor_actual_aux .= $parametros["campo_" . $i . "_" . $j] . ',';
                                        }
                                    }
                                    $valor_actual_aux = substr($valor_actual_aux, 0, strlen($valor_actual_aux) - 1);                                    
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    //$params[validacion] = $parametros["validacion_$i"];
                                    //$params[valores] = $parametros["valores_$i"];
                                    $params[Nombre] = $valor_actual_aux;
                                    //$params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $parametros[id];
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    $this->modificarRegistrosCampoDinamico($params);
                                    
                                }
                                else
                                {
                                    //$params[id_documento] = $id_documento;
                                    //$params[nombre] = $parametros["nombre_$i"];
                                    $params[tipo] = $parametros["tipo_dato_$i"];
                                    //$params[validacion] = $parametros["validacion_$i"];
                                    //$params[valores] = $parametros["valores_$i"];    
                                    if($params[tipo]=='11')
                                        $params[Nombre] = $parametros["nodosreg"];//$parametros["nodos_".$i];
                                    elseif($params[tipo]=='12')
                                        $params[Nombre] = $parametros["nodosp_".$i];
                                    else
                                        $params[Nombre] = $parametros["campo_". $i];
                                    
                                    $params['id_usuario']= $_SESSION['USERID'];
                                    $params[idRegistro] = $parametros[id];
                                    $params[id_unico] = $parametros["id_atributo_$i"];
                                    if (isset($parametros["id_unico_campo_new_$i"]))
                                        $params['nuevo'] = 1;
                                    else $params['nuevo'] = 0;
                                    $this->modificarRegistrosCampoDinamico($params);
                                    
                                }
                                $nuevo .= " " . $parametros["nombre_$i"] . ": \'" . $params[Nombre] . "\'";
                            }
                        }
                        //$nuevo = "IdRegistro: \'".$this->dbl->data[0][0]."\', IDDoc: \'$_SESSION[IDDoc]\', Identificacion: \'$atr[identificacion]\',  Id Usuario: \'$atr[id_usuario]\', Descripcion: \'$atr[descripcion]\', ContentType: \'$atr[contentType]\', Id Procesos: \'$atr[id_procesos]\', Id Organizacion: \'$atr[id_organizacion]\', ";
                        $this->registraTransaccionLog(8,$nuevo,'', '');
                        $objResponse->addScriptCall("MostrarContenidoAux");
                        $objResponse->addScriptCall('VerMensaje','exito',"Registro actualizado con exito");
                    }
                    else
                        $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                }
                          
                
                return $objResponse;
            }
     
 
            public function eliminar($parametros)
            {
                $val = $this->verRegistros($parametros[id]);
                $respuesta = $this->eliminarRegistros($parametros);
                $objResponse = new xajaxResponse();
                if (preg_match("/ha sido eliminada con exito/",$respuesta ) == true) {
                    $this->modificarRegistroActualizacion($val);
                    $objResponse->addScriptCall("MostrarContenidoAux");
                    $objResponse->addScriptCall('VerMensaje','exito',$respuesta);
                }
                else
                    $objResponse->addScriptCall('VerMensaje','error',$respuesta);
                       
                $objResponse->addScript("$('#MustraCargando').hide();");
            return $objResponse;
            }
     
 
            public function buscar($parametros)
            {   //print_r($parametros);
                $grid = $this->verListaRegistros($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('r-grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('r-grid-paginado',"innerHTML",$grid['paginado']);
                $objResponse->addScript("$('.ver-mas').on('click', function (event) {
                                    event.preventDefault();
                                    var id = $(this).attr('tok');
                                    $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
                                    $('#myModal-Ventana-Titulo').html('');
                                    $('#myModal-Ventana').modal('show');
                                });");                
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;
            }
            
             public function buscar_reporte($parametros)
            {  //print_r($parametros);
                $grid = $this->verListaRegistrosReporte($parametros);                
                $objResponse = new xajaxResponse();
                $objResponse->addAssign('r-grid',"innerHTML",$grid[tabla]);
                $objResponse->addAssign('r-grid-paginado',"innerHTML",$grid['paginado']);
                          
                $objResponse->addScript("$('#MustraCargando').hide();");
                $objResponse->addScript("init_tabla_reporte_reg();");                
                //$objResponse->addScript('PanelOperator.initPanels("");ScrollBar.initScroll();');
                $objResponse->addScript("PanelOperator.resize();");
                return $objResponse;                
            }
         
 
     public function ver($parametros)
            {
                if(!class_exists('Template')){
                    import("clases.interfaz.Template");
                }

                $val = $this->verRegistros($parametros[id]);

                            $contenido_1['IDREGISTRO'] = $val["idRegistro"];
            $contenido_1['IDDOC'] = $val["IDDoc"];
            $contenido_1['IDENTIFICACION'] = ($val["identificacion"]);
            $contenido_1['VERSION'] = $val["version"];
            $contenido_1['CORRELATIVO'] = $val["correlativo"];
            $contenido_1['ID_USUARIO'] = $val["id_usuario"];
            $contenido_1['DESCRIPCION'] = ($val["descripcion"]);
            $contenido_1['DOC_FISICO'] = $val["doc_fisico"];
            $contenido_1['CONTENTTYPE'] = ($val["contentType"]);
            $contenido_1['ID_PROCESOS'] = $val["id_procesos"];
            $contenido_1['ID_ORGANIZACION'] = $val["id_organizacion"];
;


                $template = new Template();
                $template->PATH = PATH_TO_TEMPLATES.'registros/';
                $template->setTemplate("verRegistros");
                $template->setVars($contenido_1);
                $contenido['DATOS'] = $template->show();
                $contenido['TITULO'] = "Datos de la Registros";

                $template->PATH = PATH_TO_TEMPLATES.'interfaz/';
                $template->setTemplate("ver");

                $template->setVars($contenido);
                $this->contenido['CONTENIDO']  = $template->show();
                $this->asigna_contenido($this->contenido);

                return $template->show();
            }
            
       public function ver_visualiza($parametros)
            {
                $objResponse = new xajaxResponse();
                $archivo_aux = $this->verDocumentoPDF($parametros[id]);
                $contenido2 = $archivo_aux[doc_fisico];
                if (strlen($contenido2)>0){
                    
                    $html = "<a target=\"_blank\" title=\"Ver Documento PDF\"  href=\"pages/registros/descargar_archivo.php?id=$archivo_aux[idRegistro]&token=" . md5($archivo_aux[idRegistro]) ."&des=1\">
                            
                            <i class=\"icon icon-download\"></i>
                        </a>";                
                                                                                  
                    $sql = "SELECT extension FROM mos_extensiones WHERE extension = '$archivo_aux[contentType]' OR contentType = '$archivo_aux[contentType]'";
                    $total_registros = $this->dbl->query($sql, $atr);
                    $Ext2 = $total_registros[0][extension];
                    $NombreDoc = $archivo_aux[identificacion].'.'.$Ext2;  
                    $NombreDoc = $archivo_aux['descripcion'];
                    $version = "HOJA_VIDA";
                    $Codigo = $Ext2 = "";
                    $carpeta =  $this->encryt->Decrypt_Text($_SESSION[BaseDato]);
                    $documento = new visualizador_documentos($carpeta, $NombreDoc, $Codigo, $version, $Ext2, $contenido2);

                    $ruta_doc = $documento->ActivarDocumento();
                    $titulo_doc = $documento->getNombreArchivo();
                }
                
                $http = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
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
                                        <li>'.'  
                                            '. $html .'
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        
                        
                        </div>
                    
                  
                        <div class="row"  style="height:100%;">
                            <!--<iframe src="'.$http.'://docs.google.com/gview?url='.$ruta_doc.'&embedded=true" style="height:90%;width:100%;" frameborder="0"></iframe>-->
                            <iframe src="'.$ruta_doc.'" style="height:90%;width:100%;" frameborder="0"></iframe>
                        </div>
                        
                    </div>
              </div></div>';
                $objResponse->addAssign('detail-content-aux',"innerHTML",$html);
                //$objResponse->addAssign('grid-paginado',"innerHTML",$grid['paginado']);
                //$objResponse->addScript("PanelOperator.initPanels('');");
                $objResponse->addScript("$('.close-detail').click(function (event) {
                        event.preventDefault();
                        PanelOperator.hideDetail('-aux');
                    })

                    $('.detail-show').click(function (event) {
                        event.preventDefault();
                        PanelOperator.showDetail('-aux');
                        PanelOperator.hideSearch('-aux');
                    });");
                $objResponse->addScript("PanelOperator.showDetail('-aux');");  
                $objResponse->addScript("PanelOperator.resize();");
                $objResponse->addScript("init_ver_registros();");
                
                return $objResponse;
            }
         public function ComboCargoOrg($parametros){
            $ut_tool = new ut_Tool(); 
            $sql = 'SELECT DISTINCT
                    mos_cargo.cod_cargo id,
                    mos_cargo.descripcion
                    FROM
                    mos_cargo_estrorg_arbolproc
                    INNER JOIN mos_cargo ON mos_cargo.cod_cargo = mos_cargo_estrorg_arbolproc.cod_cargo
                     where mos_cargo_estrorg_arbolproc.id in ('.$parametros['nodos'].')
                                                        order by mos_cargo.descripcion';
            //echo $sql;
            $combosemp .= $ut_tool->OptionsComboMultiple($sql, 'id', 'descripcion','valor');      
            $combo .= '<input type="hidden" name="campo_cargo_' . $parametros['i'] . '" id="campo_cargo_' . $parametros['i'] . '" value="' . $parametros['i'] . '" />';    
            $combo .="<select size=7 onchange='ValidarSeleccion(this);' class='form-control' id=\"campo_".$parametros['i']."\" name=\"campo_".$parametros['i']."[]\"  data-validation=\"required\" multiple>
                        <option value=''>-- Seleccione --</option>
                        ".$combosemp."
                    </select>    ";
            
            $objResponse = new xajaxResponse();            
            
            $objResponse->addAssign('col-md-10-' . $parametros['i'] . '',"innerHTML",$combo);
            return $objResponse;
            }             

         public function ComboCargoOrgEdit($parametros){
            // print_r($parametros);
            $ut_tool = new ut_Tool(); 
            $sql = 'SELECT DISTINCT
                    mos_cargo.cod_cargo id,
                    mos_cargo.descripcion,
                    cargos_reg.valor
                    FROM
                    mos_cargo_estrorg_arbolproc
                    INNER JOIN mos_cargo ON mos_cargo.cod_cargo = mos_cargo_estrorg_arbolproc.cod_cargo
                    left join (select DISTINCT valor 
                            from mos_registro_item t1 
                            where tipo=14 and idRegistro =\''.$parametros[idRegistro].'\' and valor in('.$parametros[sel_cargo].') ) cargos_reg
                    on mos_cargo.cod_cargo = cargos_reg.valor
                    where mos_cargo_estrorg_arbolproc.id in ('.$parametros['nodos'].')
                                                        order by mos_cargo.descripcion';
            //echo $sql;
            $combosemp .= $ut_tool->OptionsComboMultiple($sql, 'id', 'descripcion','valor');      
            $combo .= '<input type="hidden" name="campo_cargo_' . $parametros['i'] . '" id="campo_cargo_' . $parametros['i'] . '" value="' . $parametros['i'] . '" />';    
            $combo .="<select size=7 onchange='ValidarSeleccion(this);' class='form-control' id=\"campo_".$parametros['i']."\" name=\"campo_".$parametros['i']."[]\"  data-validation=\"required\" multiple>
                        <option value=''>-- Seleccione --</option>
                        ".$combosemp."
                    </select>    ";
            
            $objResponse = new xajaxResponse();            
            
            $objResponse->addAssign('col-md-10-' . $parametros['i'] . '',"innerHTML",$combo);
            return $objResponse;
            }             
            
 }?>