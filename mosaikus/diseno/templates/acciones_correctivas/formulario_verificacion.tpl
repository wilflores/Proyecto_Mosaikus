<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Identificación de la Ocurrencia
            </a>
          </h4>
        </div>
        
        <div id="collapseOne" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="responsable_analisis" class=""> <b>{N_REPORTADO_POR}</b></label><br>                                           
                        {REPORTADO_POR}
                        <input type="hidden" id="notificar"  name="notificar"  value="">
                    </div>
                    <div class="form-group col-md-10">
                        <label for="fecha_generacion" class=""><b>{N_FECHA_GENERACION}</b></label><br>
                        
                            {FECHA_GENERACION}                        
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">                                  
                        <label for="archivo" class=""><b>{N_ANEXOS}</b></label><br>
                        {ARCHIVOS_ADJUNTOS}                                          
                    </div>
                </div>
                <div class="row">                        
                    <div class="form-group col-md-24">
                        <label for="descripcion" class=""><b>{N_DESCRIPCION}</b></label><br>                                                                                 
                        {DESCRIPCION}
                    </div>                                
                </div>

                <div class="row"> 
                    <div class="form-group col-md-10">
                        <label for="origen_hallazgo" class=""> <b>{N_ORIGEN_HALLAZGO}</b></label><br>  
                        {ORIGEN}
                    </div>    
                    <div class="form-group col-md-10">                                                               
                        <label for="vigencia" class=""> <b>{N_ALTO_POTENCIAL} </b></label><br>   
                        {ALTO_POTENCIAL}   &nbsp;
                                       
                                  
                    </div>
                </div>
                {ID_ORGANIZACIONES}
                <div class="row"> 
                    <div class="form-group col-md-10">
                        <label for="responsable_analisis" class=""><b>{N_RESPONSABLE_DESVIO}</b></label><br>                                          
                        {RESPONSABLE_DESVIO}
                    </div>
                    <div class="form-group col-md-10">
                        <label for="responsable_analisis" class=""><b>{N_RESPONSABLE_ANALISIS}</b></label><br>                                          
                        {RESPONSABLE_ANALISIS}                         
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <label for="analisis_causal" class=""><b>{N_ANALISIS_CAUSAL}</b></label><br>
                        {ANALISIS_CAUSAL}
                    </div>                                
                </div>
                {CAMPOS_DINAMICOS_}
                {ID_PROCESOS}
          </div>
        </div>
    

                                   
                                  
                                  <div class="panel-heading" role="tab" id="headingTwo">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
              Plan de Acción
            </a>
          </h4>
        </div>                                   
                                 <div id="collapseTwo" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
                                                    
                                                    <table id="table-items-esp" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr bgcolor="#FFFFFF" height="30px">
                                                                <!--<th width="5%">
                                                                    <div align="left" style="width: 60px;">&nbsp; </div>
                                                                </th>
                                                                
                                                                <th width="13%">
                                                                    <div align="left" >{N_TIPO}</div>
                                                                </th>
                                                                -->
                                                                <th style="width: 10%"><div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_ESTADO_SEGUIMIENTO}</div>                                                
                                                                    </div>
                                                                </th>
                                                                <th width="35%">
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
                                                                <th width="10%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">{N_FECHA_ACORDADA}</div>                                                
                                                                    </div>
                                                                </th>
                                                                <th width="10%">
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
                                                        
                                                 <div class="panel-heading" role="tab" id="headingTres">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTres" aria-expanded="true" aria-controls="collapseOne">
              Verificación de Eficacia
            </a>
          </h4>
        </div>                                   
                        <div id="collapseTres" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <div class="row"> 
                                    <div class="form-group col-md-10">
                                        <label for="fecha_acordada" class="">{N_FECHA_ACORDADA}</label>
                                       
                                        <input type="text" class="form-control"  data-date-format="DD/MM/YYYY"value="{FECHA_ACORDADA}" id="fecha_acordada" name="fecha_acordada" placeholder="{P_FECHA_ACORDADA}" style="width: 140px;"/>
                                    </div>                                
                                
                                    <div class="form-group col-md-10">
                                        <label for="fecha_realizada" class="">{N_FECHA_REALIZADA}</label>
                                        
                                          <input type="text" class="form-control" data-date-format="DD/MM/YYYY" value="{FECHA_REALIZADA}" id="fecha_realizada" name="fecha_realizada" placeholder="{P_FECHA_REALIZADA}"  style="width: 140px;"/>
                                      </div>                                
                                </div>
                                <div class="row">       
                                    <div class="form-group col-md-20">
                                        <label for="descripcion" >{N_DESC_VERIFICACION}</label>
                                                                                
                                        <textarea class="form-control" rows="3" id="desc_verificacion" name="desc_verificacion" data-validation="required" placeholder="{P_DESC_VERIFICACION}">{DESC_VERIFICACION}</textarea>
                                    </div>                                
                                </div>
                                <div class="row">  
                                      <div class="form-group col-md-10">
                                        <label for="id_responsable_segui">{N_ID_RESPONSABLE_SEGUI}</label>                                                                               
                                                                                        
                                                    <select class="form-control "  name="id_responsable_segui" id="id_responsable_segui">
                                                        <option selected="" value="">-- Seleccione --</option>
                                                        {RESPONSABLE_SEGUI}
                                                    </select>
                                          </div>                                                                     
                                </div>
                                <div class="row">                     
                                    <div class="form-group  col-md-24" id="tabla_fileUpload">
                                        <label for="archivo" class="">{N_ANEXOS}</label>
                                        {ARCHIVOS_ADJUNTOS_VER}                                          
                                    </div> 
                                </div>                    
                                     </div>
                                     </div>
                                        
<!--                                  


-->
                                      
</div>
</div>