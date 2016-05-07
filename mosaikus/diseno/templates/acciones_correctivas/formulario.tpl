<div class="form-group">                                                               
                                      <label for="vigencia" class="col-md-4 control-label"> {N_ALTO_POTENCIAL} </label>  
                                      <div class="col-md-10">      
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="checkbox" name="alto_potencial" id="alto_potencial" value="S" {CHECKED_ALTO_POTENCIAL}>   &nbsp;
                                        </label>
                                        </div>
                                  
                                </div>
<div class="form-group">
                                        <label for="origen_hallazgo" class="col-md-4 control-label">{N_ORIGEN_HALLAZGO}</label>
                                        <div class="col-md-10">                                            
                                            <select id="origen_hallazgo" name="origen_hallazgo" data-validation="required" class="form-control" >
                                                <option selected="" value="">-- Seleccione --</option>
                                                {ORIGENES}
                                            </select>                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="fecha_generacion" class="col-md-4 control-label">{N_FECHA_GENERACION}</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" value="{FECHA_GENERACION}" id="fecha_generacion" name="fecha_generacion" placeholder="{P_FECHA_GENERACION}" style="width: 110px;" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION}</label>
                                        <div class="col-md-10">                                          
                                          <textarea class="form-control" rows="3" id="descripcion" name="descripcion" data-validation="required" placeholder="{N_DESCRIPCION}">{DESCRIPCION}</textarea>
                                      </div>                                
                                  </div>
                                  <div class="form-group">
                                        <label for="responsable_analisis" class="col-md-4 control-label">{N_RESPONSABLE_DESVIO}</label>                                           
                                      <div class="col-md-10">                                              
                                                    <select name="responsable_desvio" id="responsable_desvio" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>
                                                        {RESPONSABLE_DESVIO}
                                                    </select>
                                          </div>
                                  </div>
<div class="form-group">
                                        <label for="analisis_causal" class="col-md-4 control-label">{N_ANALISIS_CAUSAL}</label>
                                        <div class="col-md-10">                                          
                                          <textarea class="form-control" rows="3" id="analisis_causal" name="analisis_causal" placeholder="{N_ANALISIS_CAUSAL}">{ANALISIS_CAUSAL}</textarea>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="responsable_analisis" class="col-md-4 control-label">{N_RESPONSABLE_ANALISIS}</label>                                           
                                      <div class="col-md-10">                                              
                                                    <select name="responsable_analisis" id="responsable_analisis" >
                                                        <option selected="" value="">-- Seleccione --</option>
                                                        {RESPONSABLE_ANALISIS}
                                                    </select>
                                          </div>
                                  </div>
                                                    {CAMPOS_DINAMICOS}
                                    {ID_ORGANIZACIONES}
                                    {ID_PROCESOS}
                                  
                                  <div class="form-group" id="tabla_fileUpload">
                                        <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                        {ARCHIVOS_ADJUNTOS}                                          
                                 </div>                                    
                                      
<!--                                  
<div class="form-group">
                                        <label for="fecha_acordada" class="col-md-4 control-label">{N_FECHA_ACORDADA}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{FECHA_ACORDADA}" id="fecha_acordada" name="fecha_acordada" placeholder="{P_FECHA_ACORDADA}" style="width: 140px;"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="fecha_realizada" class="col-md-4 control-label">{N_FECHA_REALIZADA}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{FECHA_REALIZADA}" id="fecha_realizada" name="fecha_realizada" placeholder="{P_FECHA_REALIZADA}"  style="width: 140px;"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="id_responsable_segui" class="col-md-4 control-label">{N_ID_RESPONSABLE_SEGUI}</label>
                                                                               
                                          <div class="col-md-10">                                              
                                                    <select name="id_responsable_segui" id="id_responsable_segui">
                                                        <option selected="" value="">-- Seleccione --</option>
                                                        {RESPONSABLE_SEGUI}
                                                    </select>
                                          </div>
                                                                     
                                  </div>
-->
                                      
<div class="modal fade bs-example-modal-lg" id="myModal-Filtrar-Arbol" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog  modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Árbol Organizacional</h4>
                                </div>
                                <div class="modal-body">
                                    <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/personas/emb_jstree_single_aux.php"></iframe>
                                    <input type="hidden" id="b-id_organizacion_aux" name="b-id_organizacion_aux" value="{ID_ORGANIZACION}"/>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>                                  
                                  <button type="button" class="btn btn-primary" onClick="filtrar_arbol()">Seleccionar</button>
                                </div>
                              </div>
                            </div>
                        </div>
                                
                        <div class="modal fade bs-example-modal-lg" id="myModal-Filtrar-Proceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog  modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Árbol de Procesos</h4>
                                </div>
                                <div class="modal-body">
                                    <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/arbol_procesos/emb_jstree_procesos_aux.php"></iframe>
                                    <input type="hidden" id="b-id_proceso_aux" name="b-id_proceso_aux" value="{ID_PROCESO}"/>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>                                  
                                  <button type="button" class="btn btn-primary" onClick="filtrar_proceso()">Seleccionar</button>
                                </div>
                              </div>
                            </div>
                        </div>