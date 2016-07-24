<div class="row"><div class="form-group  col-md-6">
                                        <label for="tipo" class="">{N_ESTADO_SEGUIMIENTO}</label>
                                        
                                          <div >
      <p class="form-control-static">{ESTADO}</p>
    </div>
                                      </div> 
                                      <div class="form-group  col-md-6">
                                        <label for="tipo" class="">{N_TIPO}</label>
                                        
                                          <input type="text" class="form-control" value="{TIPO}" id="tipo" name="tipo" placeholder="{N_TIPO}" readonly="readonly" data-validation="required"/>
                                      </div>
                                      <div class="form-group col-md-9">
                                        <label for="id_responsable" class="">{N_ESTATUS_WF}</label>
                                        
                                          <input type="text" class="form-control" value="{ESTATUS_WF}" id="id_responsable" readonly="readonly" name="id_responsable" placeholder="{N_ID_RESPONSABLE}"  data-validation="required"/>
                                      </div>  
                                  </div>
<div class="row">  <div class="form-group  col-md-21">
                                        <label for="accion" class="">{N_ACCION}</label>
                                                                                
                                          <textarea class="form-control" rows="3" id="accion" name="accion" data-validation="required" readonly="readonly" placeholder="{N_ACCION}">{ACCION}</textarea>
                                      </div>                                
                                  </div>
<div class="row"><div class="form-group col-md-6">
                                        <label for="fecha_acordada" class="">{N_FECHA_ACORDADA}</label>
                                        
                                        <input type="text" class="form-control" value="{FECHA_ACORDADA}" id="fecha_acordada" readonly="readonly" name="fecha_acordada" placeholder="dd/mm/yyyy"  data-validation="required"/>
                                      </div> <div class="form-group col-md-6">
                                        <label for="fecha_realizada" class="">{N_FECHA_REALIZADA}</label>
                                        
                                          <input type="text" class="form-control" value="{FECHA_REALIZADA}" id="fecha_realizada" readonly="readonly" name="fecha_realizada" placeholder="dd/mm/yyyy"  data-validation="required"/>
                                      </div>  <div class="form-group col-md-9">
                                        <label for="id_responsable" class="">{N_ID_RESPONSABLE}</label>
                                        
                                          <input type="text" class="form-control" value="{ID_RESPONSABLE}" id="id_responsable" readonly="readonly" name="id_responsable" placeholder="{N_ID_RESPONSABLE}"  data-validation="required"/>
                                      </div>                                  
                                  </div>
                                      

<!--<div class="row"><div class="form-group col-md-10">
                                        <label for="id_ac" class="">{N_ID_AC}</label>
                                        
                                          <input type="text" class="form-control" value="{ID_AC}" id="id_ac" name="id_ac" placeholder="{N_ID_AC}"  data-validation="required"/>
                                      </div>                                
                                  </div>
 <div class="row"><div class="form-group col-md-10">
                                        <label for="id_correcion" class="">{N_ID_CORRECION}</label>
                                       
                                          <input type="text" class="form-control" value="{ID_CORRECION}" id="id_correcion" name="id_correcion" placeholder="{N_ID_CORRECION}"  data-validation="required"/>
                                      </div>                                
                                  </div>-->

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          {N_TRAZABILIDAD}
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
          
                <input type="hidden" id="num_items_esp" name="num_items_esp" value="{NUM_ITEMS_ESP}"/> 
                <textarea id="option_tipo" style="display: none;">{TIPOS}</textarea>
                <textarea id="option_responsables" style="display: none;">{RESPONSABLE_ACCIONES}</textarea>
                <input type="hidden" id="id_unico_del" name="id_unico_del" value=""/>
               <button type="button" style="margin-bottom: 10px;" onClick="agregar_esp();" class="btn btn-primary "><i class="glyphicon glyphicon-asterisk"></i>Agregar Acción</button>

                <table id="table-items-esp" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                    <thead>
                        <tr bgcolor="#FFFFFF" height="30px">
                            <th width="5%">
                                <div align="left" style="width: 60px;">&nbsp; </div>
                            </th>
                            <th width="13%">
                                <div align="left" >{N_TIPO}</div>
                            </th>


                            <th width="43%">
                                <div align="left">
                                    <div style="cursor:pointer;display:inline;">{N_ACCION_EJECUTADA}</div>                                                
                                </div>
                            </th>
                            <th width="20%">
                                <div align="left">
                                    <div style="cursor:pointer;display:inline;">{N_ID_RESPONSABLE}</div>
                                </div>
                            </th>
                            <th width="15%">
                                <div align="left">
                                    <div style="cursor:pointer;display:inline;">{N_FECHA_REALIZADA}</div>                                                
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