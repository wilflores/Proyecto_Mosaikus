<div id="main-content" class="panel-container col-xs-24 ">
    <div class="content-panel panel">
        <div class="content">
                      
            <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
                <div class="panel-heading">
                    <div class="row">
                        <div class="panel-title col-xs-12" id="div-titulo-for">
                            {TITULO_FORMULARIO}                    
                        </div>                

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24">
                        <div class="form-group">
                            <label for="archivo" class="col-md-4 control-label">{N_ARBOL_ORGANIZACIONAL} </label>
                            <div class="col-md-10" >
                                <!--label for="vigencia" class="col-md-10 control-label" style="text-align: left;">Árbol organizacional</label>
                                <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
                                <iframe id="iframearbol" src="pages/cargo/prueba_arbolV4.php?IDDoc={IDDOC}" frameborder="0" width="100%" height="310px" scrolling="no"></iframe-->
                                 <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
                                {DIV_ARBOL_ORGANIZACIONAL}
                                <input type="hidden" name="nodo_area" id="nodo_area"/>
                            </div>
                        </div>
                                
                        <div class="form-group">        
                            <label for="vigencia" class="col-md-4 control-label">{N_PUBLICO}</label>
                            <div class="col-md-6">      
                                <label class="checkbox-inline" style="padding-top: 5px;">
                                    <input type="checkbox" name="publico" id="publico" value="S" {CHECKED_PUBLICO}>   &nbsp;                                                                
                                </label>
                            </div>    
                        </div>            
                        <div class="form-group">
                            <label  class="col-md-4 control-label">{N_TIPO_DOCUMENTO}</label>                                                
                            <div class="col-md-10">
                                <select id="tipo_documento" name="tipo_documento" class="form-control" data-validation="required">
                                    <option  value="">-- Seleccione --</option>
                                    {TIPOS_DOCUMENTOS}
                                </select>
                          </div>   
                        </div>
                            <div class="form-group" id="tabla_fileUpload"  style="{CSS_TABLA_FILEUPLOAD}">
                                    <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-12">
                                        <input type="file" value="{ARCHIVO}" accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.ms-excel"  id="fileUpload2" name="fileUpload2" onchange="cargar_archivo_otro();" data-validation="required"/>
                                        <input type="hidden" id="estado_actual" name="estado_actual">
                                        <input type="hidden" id="filename" name="filename" value="{FILENAME}">
                                        <input type="hidden" id="tamano" name="tamano" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc" name="tipo_doc" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                                        <div id="estado" style="display:none;">
                                            <!--<img src="{PAHT_TO_IMG}loading3.gif">Cargando-->
                                            <div class="progress" style="width: 250px;">
                                                <div class="progress-bar" id="estado-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
                                                  0%
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!--<input type="hidden" class="form-control" value="{DOC_FISICO}" id="doc_fisico" name="doc_fisico" placeholder="{N_DOC_FISICO}"  data-validation="required"/>
                                        <input type="hidden" class="form-control" value="{CONTENTTYPE}" id="contentType" name="contentType" placeholder="{N_CONTENTTYPE}" data-validation="required"/>
                                        -->
                                  </div>     
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>
                             <div class="form-group" id="info_archivo_adjunto"  style="display:none;">
                                    <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-12">
                                        <p class="form-control-static" style="">
                                            <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                            <input type="text" class="form-control" value="{CODIGO_DOC}" style="display: inline;width: 150px;" id="Codigo_doc" name="Codigo_doc" placeholder="{N_CODIGO_DOC}" data-validation="required"/>
                                            -
                                            <input type="text" class="form-control" value="{NOMBRE_DOC}" style="display: inline;width: 200px;"id="nombre_doc" name="nombre_doc" placeholder="{N_NOMBRE_DOC}" data-validation="required"/>
                                            -V
                                            <input type="text" class="form-control" value="{VERSION}" style="padding-left: 6px;display: inline;width: 50px;"id="version" name="version" placeholder="{N_VERSION}"  data-validation="required number"/>
                                            <input type="hidden" class="form-control" style="width: 250px;display: inline;" id="info_nombre" readonly="readonly">
                                            
                                            <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->
                                            <a href="#" onclick="cancelar_archivo_otro();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>         
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>
                            <div class="form-group" id="tabla_fileUpload_vis"  style="{CSS_TABLA_FILEUPLOAD_VIS}">
                                    <label for="archivo" class="col-md-4 control-label">{N_NOM_VISUALIZA}</label>
                                    <div class="col-md-12">
                                        <input type="file" value="{ARCHIVO}" accept="application/pdf" id="fileUpload2_vis" name="fileUpload2_vis" onchange="cargar_archivo_vis();"/>
                                        <input type="hidden" id="estado_actual_vis" name="estado_actual_vis">
                                        <input type="hidden" id="filename_vis" name="filename_vis" value="{FILENAME}">
                                        <input type="hidden" id="tamano_vis" name="tamano_vis" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc_vis" name="tipo_doc_vis" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
                                        <div id="estado_vis" style="display:none;">
                                            <!--<img src="{PAHT_TO_IMG}loading3.gif">Cargando-->
                                            <div class="progress" style="width: 250px;">
                                                <div class="progress-bar" id="estado-vis-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
                                                  0%
                                                </div>
                                            </div>
                                        </div>

                                  </div> 
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.PDF</span>
                             </div>
                             <div class="form-group" id="info_archivo_adjunto_vis"  style="display:none;">
                                    <label for="archivo" class="col-md-4 control-label">{N_NOM_VISUALIZA}</label>
                                    <div class="col-md-12">
                                        <p class="form-control-static" style="">
                                            <input type="text" class="form-control" style="width: 380px;display: inline;" id="info_nombre_vis" readonly="readonly">
                                            <a href="#" onclick="cancelar_archivo_vis();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>  
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.PDF</span>
                             </div>
                                <div class="form-group">
                                        <label for="fecha" class="col-md-4 control-label">{N_FECHA}</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" style="width: 120px;" data-date-format="DD/MM/YYYY"  value="{FECHA}" id="fecha" name="fecha" placeholder="{N_FECHA}"  data-validation="required"/>
                                            <input type="hidden" class="form-control" value="{NOMBRE_DOC_VIS}" id="nombre_doc_vis" name="nombre_doc_vis"/>                                          
                                      </div>                                
                                  </div>
                                <div class="form-group">
                                        <label for="semaforo" class="col-md-4 control-label">{N_SEMAFORO}</label>
                                        <div class="col-md-10" style="font-size: 12px;">
                                          
                                          <span style=""><strong> {N_AVISO} >=</strong></span>
                                          {SEMAFORO}                                          
                                          <span style=""><strong> {N_SEMANAS} </strong></span>
                                                                                    
                                      </div>    
                                          <div class="col-md-10" style="font-size: 12px;">
                                            <span style=""><strong> {N_VIGENCIA} >=</strong></span>                                          
                                          
                                          {V_MESES}
                                          <span style=""><strong> {N_MESES} </strong></span>   
                                                                                    
                                      </div>  
                                  </div>

                                  
<div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{DESCRIPCION}" id="descripcion" name="descripcion" placeholder="{N_DESCRIPCION}" data-validation="required"/>
                                          <input type="hidden" class="form-control" value="{PALABRAS_CLAVES}" id="palabras_claves" name="palabras_claves" placeholder="{N_PALABRAS_CLAVES}" />
                                      </div>                                
                                  </div>
                                  <div class="form-group">
                                    
                                    
                                    <div class="col-md-24">
                                        <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">
                                            <li id="li1"><a href="#hv-red-2" data-toggle="tab" style="padding: 8px 32px;">{N_OTROS_DATOS}</a></li>
                                            <li id="li2"><a href="#hv-orange-2" data-toggle="tab" style="padding: 8px 32px;"id="tabs-form-reg" >{N_PARAMETROS_INDEXACION} </a></li>
                                            <li id="li3"><a href="#hv-orange-3" data-toggle="tab" style="padding: 8px 32px;"id="tabs-lista" > {N_ANEXOS_DOC_RELACIONADOS}</a></li>
                                        </ul>
                                        <div id="my-tab-content" class="tab-content" style="padding: 45px 15px;">
                                            <div class="tab-pane active" id="hv-red-2">
                                                <div class="form-group">
                                                        <label for="vigencia" class="col-md-6 control-label">{N_VIGENCIA}</label>
                                                        <div class="col-md-3">      
                                                            <label class="checkbox-inline" style="padding-top: 5px;">
                                                                <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;
                                                                <input type="hidden" class="form-control" value="{WORKFLOW}" id="workflow" name="workflow" placeholder="{N_WORKFLOW}" data-validation="required"/>
                                                            </label>
                                                        </div>
                                                </div>
                                                <!--<div class="form-group">
                                                        
                                                </div>-->
                                                <div class="form-group">
                                                    <label  class="col-md-6 control-label">{N_REQUIERE_LISTA_DISTRIBUCION}</label>                                                
                                                    <div class="col-md-14">
                                                        <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" onchange="CargaComboCargo(this.value)">
                                                        <option  value="N">{N_NO}</option>
                                                        <option  value="S">{N_SI}</option>
                                                     </select>
                                                  </div>   
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-md-6 control-label">{N_CARGOS}</label>                                                
                                                    <div class="col-md-14" id="div_cargos">
                                                  </div>                                                       
                                                </div>
                                                <div class="form-group">
                                                    <label for="elaboro" class="col-md-6 control-label">{N_ID_WORKFLOW_DOCUMENTO}</label>                                                
                                                    &nbsp;&nbsp;{N_ELABORO} {N_REVISO} &#8594; {N_APROBO}<br>
                                                    <div id="div_combo_wf" class="col-md-14">
                                                      <select id="id_workflow_documento" name="id_workflow_documento" data-validation="required">
                                                        <option selected="" value="">-- No Asignado --</option>
                                                        {ID_WORKFLOW_DOCUMENTO}
                                                     </select>
                                                  </div>   
                                                </div>
                                                     {OTROS_CAMPOS}
                                               </div>
                                               <div class="tab-pane active" id="hv-orange-2">
                                                   <input type="hidden" id="num_items_esp" name="num_items_esp" value="{NUM_ITEMS_ESP}"/> 
                                                   <input type="hidden" id="tok_new_edit" name="tok_new_edit" value="{TOK_NEW}"/>
                                                   <!--<input type="button" class="button add" value="Agregar" onClick="agregar_esp();" >-->
                                                                                                    
                                                    <button type="button" onClick="agregar_esp();" class="btn btn-primary  btn-xs">{N_AGREGAR}</button>
                                                   &nbsp;<label for="actualizacion_activa" class="control-label">{N_ACTUALIZACION_ACTIVA}</label>
                                                    <input type="checkbox" name="actualizacion_activa" id="actualizacion_activa" value="S" {CHECKED_ACTUALIZACION_ACTIVA}>
                                                   
                                                   <br><br>
                                                            
                                                            
                                                    

                                                    <table id="table-items-esp" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr bgcolor="#FFFFFF" height="30px">
                                                                <th width="10%">
                                                                    <div align="left" style="width: 50px;"> </div>
                                                                </th>
                                                                <th width="25%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_NOMBRE_REGISTROS}</div>
                                                                    </div>
                                                                </th>
                                                                <th width="25%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_TIPO_REGISTROS}</div>
                                                                    </div>
                                                                </th>
                                                                <th width="35%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_VALORES_REGISTROS}</div>                                                
                                                                    </div>
                                                                </th>
                                                                <th width="5%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">&nbsp;</div>                                                
                                                                    </div>
                                                                </th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                            {ITEMS_ESP}
                                                        </tbody>
                                                    </table>
                                               </div>
                                             <div class="tab-pane active" id="hv-orange-3">
                                                <div class="form-group" id="tabla_fileUpload_anexo">
                                                        <label for="archivo" class="col-md-6 control-label">{N_ANEXOS}</label>
                                                        {ARCHIVOS_ADJUNTOS}                                          
                                                 </div> 
                                                <div class="form-group">
                                                    <label  class="col-md-6 control-label">{N_DOCUMENTOS_RELACIONADOS}</label>                                                
                                                    <div class="col-md-18" id="div_doc_relacionados">
                                                        <select id="documento_relacionado" class="form-control" name="documento_relacionado[]" data-actions-box="true" data-live-search="true" multiple>
                                                        {DOCUMENTOS_RELACIONADOS}
                                                     </select>
                                                  </div>                                                       
                                                </div>
                                                 
                                             </div>
                                            </div>
                                        </div>             
                                </div>
                            </div>


    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-15">
            <!--<input class="button save" name="guardar" type="button" value="">-->
            <button type="button" class="btn btn-primary" onClick="document.getElementById('notificar').value='';validar(document);" id="btn-guardar">{DESC_OPERACION}</button>
            <button type="button" class="btn btn-primary" onClick="document.getElementById('notificar').value='si';validar(document);" id="btn-guardar-not">{DESC_OPERACION_NOTIFICAR}</button>
            <!--<input class="button " type="button" value="Cancelar" >-->
            <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">{N_CANCELAR}</button>
            
            <input type="hidden" id="opc" name="opc" value="{OPC}">
            <input type="hidden" id="id"  name="id"  value="{ID}">
            <input type="hidden" id="notificar"  name="notificar"  value="">
        </div>
    </div>
        </div></div>
</form>
</div>
        </div></div>

        <form enctype="multipart/form-data" id="formuploadajax" method="post">
        
            <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
        </form>                                              