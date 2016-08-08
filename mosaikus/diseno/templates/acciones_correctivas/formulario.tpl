<div class="form-group">
                                        <label for="responsable_analisis" class="col-md-4 control-label"> {N_REPORTADO_POR}</label>                                           
                                      <div class="col-md-10">                                              
                                                    <select name="reportado_por" id="reportado_por" >
                                                       
                                                        {REPORTADO_POR}
                                                    </select>
                                          </div>
                                  </div>
                                  <div class="form-group" id="tabla_fileUpload">
                                        <label for="archivo" class="col-md-4 control-label">{N_ANEXOS}</label>
                                        {ARCHIVOS_ADJUNTOS}                                          
                                 </div> 
                                        <div class="form-group">
                                        <label for="fecha_generacion" class="col-md-4 control-label">{N_FECHA_GENERACION}</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" data-date-format="DD/MM/YYYY"  value="{FECHA_GENERACION}" id="fecha_generacion" name="fecha_generacion" placeholder="{P_FECHA_GENERACION}" style="width: 110px;" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION}</label>
                                        <div class="col-md-10">                                          
                                          <textarea class="form-control" rows="3" id="descripcion" name="descripcion" data-validation="required" placeholder="{N_DESCRIPCION}">{DESCRIPCION}</textarea>
                                      </div>                                
                                  </div>


<div class="form-group">
                                        <label for="origen_hallazgo" class="col-md-4 control-label"> {N_ORIGEN_HALLAZGO}</label>
                                        <div class="col-md-10">                                            
                                            <select id="origen_hallazgo" name="origen_hallazgo" data-validation="required" class="form-control" >
                                                <option selected="" value="">-- Seleccione --</option>
                                                {ORIGENES}
                                            </select>                                          
                                      </div>                                
                                  </div>
                                   
                                            <div class="form-group">                                                               
                                      <label for="vigencia" class="col-md-4 control-label"> {N_ALTO_POTENCIAL} </label>  
                                      <div class="col-md-10">      
                                          {ALTO_POTENCIAL}
                                        <!--<label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="checkbox" name="alto_potencial" id="alto_potencial" value="S" {CHECKED_ALTO_POTENCIAL}>   &nbsp;
                                        </label>-->
                                        </div>
                                  
                                </div>
                                        {ID_ORGANIZACIONES}
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
                                        <label for="responsable_analisis" class="col-md-4 control-label">{N_RESPONSABLE_ANALISIS}</label>                                           
                                      <div class="col-md-10">                                              
                                                    <select name="responsable_analisis" id="responsable_analisis" >
                                                        <option selected="" value="">-- Seleccione --</option>
                                                        {RESPONSABLE_ANALISIS}
                                                    </select>
                                          </div>
                                  </div>
                                <div class="form-group">  
                                      
                                        <label for="id_responsable_segui" class="col-md-4 control-label">{N_ID_RESPONSABLE_SEGUI}</label>                                                                               
                                         <div class=" col-md-10">                                               
                                                    <select class="form-control "  name="id_responsable_segui" id="id_responsable_segui">
                                                        <option selected="" value="">-- Seleccione --</option>
                                                        {RESPONSABLE_SEGUI}
                                                    </select>
                                          </div>                                                                     
                                </div>
                                
                                <div class="form-group">                                                               
                                      <label for="vigencia" class="col-md-4 control-label"> {N_ALTO_POTENCIAL_VAL} </label>  
                                      <div class="col-md-10">      
                                          {ALTO_POTENCIAL_VAL}                                       
                                        </div>
                                  
                                </div>
                                <div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION_VAL}</label>
                                        <div class="col-md-10">                                          
                                          <textarea class="form-control" rows="3" id="descripcion_val" name="descripcion_val" data-validation="required" placeholder="{N_DESCRIPCION_VAL}">{DESCRIPCION_VAL}</textarea>
                                      </div>                                
                                </div>
                                      
                                  <div class="form-group">
                                        <label for="analisis_causal" class="col-md-4 control-label">{N_ANALISIS_CAUSAL}</label>
                                        <div class="col-md-10">                                          
                                          <textarea class="form-control" rows="3" id="analisis_causal" name="analisis_causal" placeholder="{N_ANALISIS_CAUSAL}">{ANALISIS_CAUSAL}</textarea>
                                          <input type="hidden" id="notificar"  name="notificar"  value="">
                                          <input type="hidden" id="estatus"  name="estatus"  value="{ESTATUS}">
                                          <input type="hidden" id="user_tok"  name="user_tok"  value="{USER_TOK}">
                                      </div>                                
                                  </div>
                                   {CAMPOS_DINAMICOS}
                                   <input type="hidden" id="nombre_cam_din" value="{NOMBRE_CAMPOS_DIN}">
                                    {ID_PROCESOS}
                                   
                                  
                                                                     
                                 <div class="form-group" id="div-tabs">
                                    <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">
                                            <li><a href="#hv-red-2" data-toggle="tab">Agregar/Modificar Plan de Acción</a></li>
                                            
                                        </ul>
                                        <div id="my-tab-content" class="tab-content" style="padding: 45px 15px;">
                                            <div class="tab-pane active" id="hv-red-2">
                                                    <input type="hidden" id="num_items_esp" name="num_items_esp" value="{NUM_ITEMS_ESP}"/> 
                                                    <textarea id="option_tipo" style="display: none;">{TIPOS}</textarea>
                                                    <textarea id="option_responsables" style="display: none;">{RESPONSABLE_ACCIONES}</textarea>
                                                    <input type="hidden" id="id_unico_del" name="id_unico_del" value=""/>
                                                   <!--<input type="hidden" id="tok_new_edit" name="tok_new_edit" value="{TOK_NEW}"/>
                                                   
                                                   <!--<input type="button" class="button add" value="Agregar" onClick="agregar_esp();" >-->
                                                   <button type="button" style="margin-bottom: 10px;" onClick="agregar_esp();" class="btn btn-primary "><i class="glyphicon glyphicon-asterisk"></i>Agregar Acción</button>
                                                   
                                                    <table id="table-items-esp" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr bgcolor="#FFFFFF" height="30px">
                                                                <th width="5%">
                                                                    <div align="left" style="width: 60px;">&nbsp; </div>
                                                                </th>
                                                                <!--
                                                                <th width="13%">
                                                                    <div align="left" >{N_TIPO}</div>
                                                                </th>
                                                                -->
                                                                
                                                                <th width="40%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_ACCION}</div>                                                
                                                                    </div>
                                                                </th>
                                                                <th width="20%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_ID_RESPONSABLE}</div>
                                                                    </div>
                                                                </th>
                                                                <th width="20%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_VALIDADOR_ACCION}</div>
                                                                    </div>
                                                                </th>
                                                                <th width="13%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_FECHA_ACORDADA}</div>                                                
                                                                    </div>
                                                                </th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                            {ITEMS_ESP}
                                                        </tbody>
                                                    </table>
                                            </div> 
                                        </div>
                                    </div>
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
                                    {DIV_ARBOL_ORGANIZACIONAL}
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
                                    <div id="id-tree-ap">
                                        <div id="tree">Seleccione un Área para administrar el Arbol de Procesos</div>
                                    </div>
                                    <input type="hidden" id="b-id_proceso_aux" name="b-id_proceso_aux" value="{ID_PROCESO}"/>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>                                  
                                  <button type="button" class="btn btn-primary" onClick="filtrar_proceso()">Seleccionar</button>
                                </div>
                              </div>
                            </div>
                        </div>