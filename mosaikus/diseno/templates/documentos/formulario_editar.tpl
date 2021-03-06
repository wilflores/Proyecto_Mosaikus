<div id="main-content" class="panel-container col-xs-24 ">
              <div class="content-panel panel">
                  <div class="content">
                      
    <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
        <div class="modal fade bs-example-modal-lg" id="myModal-observacion-vigencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog  modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">{N_VIGENCIA_TITULO}</h4>
                </div>
                <div class="modal-body">
                    {N_MOTIVO_VIGENCIA}
                    <textarea  id="observacion_vigencia" cols="30" rows="2" name="observacion_vigencia" class="form-control" placeholder="Indique un comentario o motivo"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">{N_CANCELAR}</button>                                  
                  <button type="button" class="btn btn-primary" onClick="$('#myModal-observacion-vigencia').modal('hide');">{N_ACEPTAR}</button>
                </div>
              </div>
            </div>            
        </div>        
        <div class="panel-heading">
            <div class="row">
                <div class="panel-title col-xs-12" id="div-titulo-for">
                    {TITULO_FORMULARIO}                   
                </div>
                <div class="panel-actions col-xs-12">

                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-xs-24">
                <div class="form-group">
                <label for="archivo" class="col-md-4 control-label">{N_ARBOL_ORGANIZACIONAL} </label>
                <div class="col-md-10" >
                    <!--label for="vigencia" class="col-md-12 control-label" style="text-align: left;">Árbol organizacional</label>

                    <iframe id="iframearbol" src="pages/cargo/prueba_arbolV4.php?IDDoc={IDDOC}" frameborder="0" width="100%" height="310px" scrolling="no"></iframe-->
                    <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
                    {DIV_ARBOL_ORGANIZACIONAL}
                </div>
                 </div>
                
                
                <div class="form-group">
                <label for="vigencia" class="col-md-4 control-label">{N_PUBLICO}</label>
                <div class="col-md-10">      
                    <label class="checkbox-inline" style="padding-top: 0px;">
                    <input type="checkbox" name="publico" id="publico" value="S" {CHECKED_PUBLICO}>   &nbsp;                                                                
                    </label>
                </div>                              
                </div>
                <div class="form-group">
                            <label  class="col-md-4 control-label">{N_TIPO_DOCUMENTO}</label>                                                
                            <div class="col-md-10">
                                <select id="tipo_documento" name="tipo_documento" class="form-control" data-validation="required">
                                    <option  value="">-- Selecione --</option>
                                    {TIPOS_DOCUMENTOS}
                                </select>
                          </div>   
                        </div>
<!--<div class="form-group">
                                        <label for="IDDoc" class="col-md-2 control-label">{N_IDDOC}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{IDDOC}" id="IDDoc" name="IDDoc" placeholder="{N_IDDOC}"  data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="Codigo_doc" class="col-md-2 control-label">{N_CODIGO_DOC}</label>
                                        <div class="col-md-2">
                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="nombre_doc" class="col-md-2 control-label">{N_NOMBRE_DOC}</label>
                                        <div class="col-md-2">                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="version" class="col-md-2 control-label">{N_VERSION}</label>
                                        <div class="col-md-2">
                                          
                                      </div>                                
                                  </div>-->         
                             {DOC_FUENTE}
                             
<!--<div class="form-group">
                                        <label for="doc_fisico" class="col-md-6 control-label">{N_DOC_FISICO}</label>
                                        <div class="col-md-6">
                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="contentType" class="col-md-6 control-label">{N_CONTENTTYPE}</label>
                                        <div class="col-md-6">                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="id_filial" class="col-md-6 control-label">{N_ID_FILIAL}</label>
                                        <div class="col-md-6">
                                          <input type="text" class="form-control" value="{ID_FILIAL}" id="id_filial" name="id_filial" placeholder="{N_ID_FILIAL}"  data-validation="required"/>
                                      </div>                                
                                  </div>-->
                            {DOC_VIS}
                            
                            
<!--<div class="form-group">
                                        <label for="nom_visualiza" class="col-md-6 control-label">{N_NOM_VISUALIZA}</label>
                                        <div class="col-md-6">
                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="doc_visualiza" class="col-md-6 control-label">{N_DOC_VISUALIZA}</label>
                                        <div class="col-md-6">
                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="contentType_visualiza" class="col-md-6 control-label">{N_CONTENTTYPE_VISUALIZA}</label>
                                        <div class="col-md-6">                                          
                                      </div>                                
                                  </div>-->
                                <div class="form-group">
                                        <label for="fecha" class="col-md-4 control-label">{N_FECHA}</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" style="width: 120px;" data-date-format="DD/MM/YYYY"  value="{FECHA}" id="fecha" name="fecha" placeholder="{N_FECHA}" readonly="readonly" data-validation="required"/>
                                          <input type="hidden" class="form-control" value="{CODIGO_DOC}" id="Codigo_doc" name="Codigo_doc" placeholder="{N_CODIGO_DOC}" data-validation="required"/>
                                          <input type="hidden" class="form-control" value="{NOMBRE_DOC}" id="nombre_doc" name="nombre_doc" placeholder="{N_NOMBRE_DOC}" data-validation="required"/>
                                          <input type="hidden" class="form-control" value="{NOMBRE_DOC_VIS}" id="nombre_doc_vis" name="nombre_doc_vis" readonly="readonly"/>
                                          <input type="hidden" class="form-control" value="{VERSION}" id="version" name="version" placeholder="{N_VERSION}"  data-validation="required"/>
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
                                            <span style=""><strong> {N_VIGENCIA_TITULO} >=</strong></span>                                          
                                          
                                          {V_MESES}
                                          <span style=""><strong> {N_MESES} </strong></span>   
                                                                                    
                                      </div>  
                                  </div>
                                <!--
                                  <div class="form-group">
                                        <label for="formulario" class="col-md-6 control-label">{N_FORMULARIO}</label>
                                        <div class="col-md-6">      
                                            <label class="checkbox-inline" style="padding-top: 0px;">
                                                <input type="checkbox" id="formulario" name="formulario" value="S" {CHECKED_FORMULARIO}>   &nbsp;
                                            </label>
                                        </div>                                         
                                  </div>
                                -->
<div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{DESCRIPCION}" id="descripcion" name="descripcion" placeholder="{N_DESCRIPCION}" data-validation="required"/>
                                          <input type="hidden" class="form-control" value="{PALABRAS_CLAVES}" id="palabras_claves" name="palabras_claves" placeholder="{N_PALABRAS_CLAVES}" />
                                      </div>                                
                                  </div>
<!--<div class="form-group">
                                        <label for="palabras_claves" class="col-md-2 control-label">{N_PALABRAS_CLAVES}</label>
                                        <div class="col-md-2">
                                          
                                      </div>                                
                                  </div>-->
                                  <div class="form-group">
                                    
                                    
                                    <div class="col-md-24">
                                        <div class="tabs"> 
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">
                                            <li id="li1"><a href="#hv-red-2" data-toggle="tab" style="padding: 8px 32px;">{N_OTROS_DATOS}</a></li>
                                            <li id="li2"><a href="#hv-orange-2" data-toggle="tab" style="padding: 8px 32px;" id="tabs-form-reg" >{N_PARAMETROS_INDEXACION}</a></li>
                                            <li id="li3"><a href="#hv-orange-4" data-toggle="tab" style="padding: 8px 32px;" id="tabs-lista" > {N_ANEXOS_DOC_RELACIONADOS}</a></li>
                                            <li id="li3"><a href="#hv-orange-3" data-toggle="tab" style="padding: 8px 32px;" id="tabs-historico-wf" >{N_HISTORICO_FLUJO_DATOS}</a></li>
                                        </ul>
                                        <div id="my-tab-content" class="tab-content" style="padding: 45px 15px;">
                                            <div class="tab-pane active" id="hv-red-2">
                                                <div class="form-group">
                                                        <label for="vigencia" class="col-md-6 control-label">{N_VIGENCIA}</label>
                                                        <div class="col-md-3">      
                                                            <label class="checkbox-inline" style="padding-top: 0px;">
                                                                <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;
                                                                <input type="hidden" class="form-control" value="{WORKFLOW}" id="workflow" name="workflow" placeholder="{N_WORKFLOW}" data-validation="required"/>
                                                            </label>
                                                        </div>
                                                </div>  
                                                            <!--
                                                <div class="form-group">
                                                        
                                                </div>
                                                            -->
                                                <div class="form-group" style="display:none;">
                                                    <label  class="col-md-6 control-label">{N_REQUIERE_LISTA_DISTRIBUCION}</label>                                                
                                                    <div class="col-md-14">
                                                        <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" onchange="CargaComboCargo(this.value)">
                                                        <option {SELECTEDNO} value="N">No</option>
                                                        <option {SELECTEDSI} value="S">SI</option>
                                                     </select>
                                                  </div>   
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-md-6 control-label">Cargos</label>                                                
                                                    <div class="col-md-14" id="div_cargos">
                        
                                                  </div>                                                       
                                                </div>
                                                <div class="form-group">
                                                    <label for="elaboro" class="col-md-6 control-label">{N_ID_WORKFLOW_DOCUMENTO}</label>                                                
                                                    <div id="div_combo_wf"  class="col-md-14">            
                                                        &nbsp;&nbsp;{N_ELABORO} {N_REVISO} &#8594; {N_APROBO}
                                                      <select {COMBOWFHABILITADO} id="id_workflow_documento" name="id_workflow_documento" data-validation="required">
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
                                                   <input type="hidden" id="id_unico_del" name="id_unico_del" value=""/>
                                                   <!--<input type="button" class="button add" value="Agregar" onClick="agregar_esp();" >-->
                                                   <button type="button" onClick="agregar_esp();" class="btn btn-primary  btn-xs">Agregar</button>
                                                    &nbsp;<input type="checkbox" name="actualizacion_activa" id="actualizacion_activa" value="S" {CHECKED_ACTUALIZACION_ACTIVA}>
                                                    <label for="actualizacion_activa" class="control-label">{N_ACTUALIZACION_ACTIVA}</label>
                                                   <br><br>
                                                    <table id="table-items-esp" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr bgcolor="#FFFFFF" height="30px">
                                                                <th width="10%">
                                                                    <div align="left" style="width: 50px;"> </div>
                                                                </th>
                                                                <th width="25%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">Nombre</div>
                                                                    </div>
                                                                </th>
                                                                <th width="25%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">Tipo</div>
                                                                    </div>
                                                                </th>
                                                                <th width="35%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">Valores</div>                                                
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
                                            <div class="tab-pane active" id="hv-orange-4">
                                                <div class="form-group" id="tabla_fileUpload">
                                                        <label for="archivo_anexo" class="col-md-6 control-label">Anexos</label>
                                                        {ARCHIVOS_ADJUNTOS}                                          
                                                 </div>                                                  
                                                <div class="form-group">
                                                    <label  class="col-md-6 control-label">Documentos Relacionados</label>                                                
                                                    <div class="col-md-18" id="div_doc_relacionados">
                                                        <select id="documento_relacionado" name="documento_relacionado[]" class="form-control" data-actions-box="true" data-live-search="true" multiple>
                                                        {DOCUMENTOS_RELACIONADOS}
                                                     </select>
                                                  </div>                                                       
                                                </div>
                                                
                                               </div>
                                            <div class="tab-pane active" id="hv-orange-3">
                                                    <table id="table-histo" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr bgcolor="#FFFFFF" height="30px">
                                                                <th width="20%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_FECHA}</div>
                                                                    </div>
                                                                </th>
                                                                <th width="55%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_OPERACION}</div>
                                                                    </div>
                                                                </th>
                                                                <th width="25%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_USUARIO_RESPONSABLE}</div>                                                
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {ITEMS_HISTO}
                                                        </tbody>
                                                    </table>                                                
                                            </div>
                                            </div>
                                        </div> 
                                        <!--
                                        <div class="form-group">
                                            <label for="id_usuario" class="col-md-2 control-label">{N_ID_USUARIO}</label>
                                            <div class="col-md-2">
                                              <input type="text" class="form-control" value="{ID_USUARIO}" id="id_usuario" name="id_usuario" placeholder="{N_ID_USUARIO}"  data-validation="required"/>
                                          </div>                                
                                        </div>
                                        <div class="form-group">
                                            <label for="observacion" class="col-md-2 control-label">{N_OBSERVACION}</label>
                                            <div class="col-md-2">
                                              <input type="text" class="form-control" value="{OBSERVACION}" id="observacion" name="observacion" placeholder="{N_OBSERVACION}"  data-validation="required"/>
                                          </div>                                
                                        </div>
                                        <div class="form-group">
                                            <label for="muestra_doc" class="col-md-2 control-label">{N_MUESTRA_DOC}</label>
                                            <div class="col-md-2">
                                              <input type="text" class="form-control" value="{MUESTRA_DOC}" id="muestra_doc" name="muestra_doc" placeholder="{N_MUESTRA_DOC}" data-validation="required"/>
                                          </div>                                
                                      </div>
                                        -->
                                </div>
                            </div>
<!--<div class="form-group">
                                        <label for="estrucorg" class="col-md-2 control-label">{N_ESTRUCORG}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{ESTRUCORG}" id="estrucorg" name="estrucorg" placeholder="{N_ESTRUCORG}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="arbproc" class="col-md-2 control-label">{N_ARBPROC}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{ARBPROC}" id="arbproc" name="arbproc" placeholder="{N_ARBPROC}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="apli_reg_estrorg" class="col-md-2 control-label">{N_APLI_REG_ESTRORG}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{APLI_REG_ESTRORG}" id="apli_reg_estrorg" name="apli_reg_estrorg" placeholder="{N_APLI_REG_ESTRORG}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="apli_reg_arbproc" class="col-md-2 control-label">{N_APLI_REG_ARBPROC}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{APLI_REG_ARBPROC}" id="apli_reg_arbproc" name="apli_reg_arbproc" placeholder="{N_APLI_REG_ARBPROC}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="workflow" class="col-md-2 control-label">{N_WORKFLOW}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{WORKFLOW}" id="workflow" name="workflow" placeholder="{N_WORKFLOW}" data-validation="required"/>
                                      </div>                                
                                  </div>-->


    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-50">
            <!--<input class="button save" name="guardar" type="button" value="{DESC_OPERACION}" onClick="validar(document);">-->
            <button type="button" class="btn btn-primary" onClick="document.getElementById('notificar').value='';validar(document);" id="btn-guardar">{DESC_OPERACION}</button>
            <button type="button" {VERNOTIFICAR} class="btn btn-primary" onClick="document.getElementById('notificar').value='si';validar(document);" id="btn-guardar-not">{DESC_OPERACION_NOTIFICAR}</button>
            
            <!--<input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">-->
            <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">Cancelar</button>
            
            <input type="hidden" id="opc" name="opc" value="{OPC}">
            <input type="hidden" id="id"  name="id"  value="{ID}">
            <input type="hidden" id="notificar"  name="notificar"  value="">
            <input type="hidden" id="etapa" name="etapa" value="{ETAPA}">
        </div>
    </div>
        </div></div>
</form>
</div>
        </div></div>
        <form enctype="multipart/form-data" id="formuploadajax" method="post">
        
            <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
        </form>