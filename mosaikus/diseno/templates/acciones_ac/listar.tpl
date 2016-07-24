{HTML_INICIO_PAG}
<div class="tabs"> 
    <ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-orange" data-toggle="tab">{NOMBRE_PEST_2}</a></li>  
        <li><a href="#hv-red" data-toggle="tab">Agregar/Modificar {NOMBRE_PEST_2}</a></li>
              
    </ul>

<div id="my-tab-content" class="tab-content"  style="padding: 45px 3%;">
    <div class="tab-pane active" id="hv-red">
        <form id="idFormulario-hv" class="form-horizontal" role="form">
            <div class="form-group" style="{TIPO_DISPLAY}">
                <label for="tipo" class="col-md-4 control-label" style="color: black;">{N_TIPO}</label>
                    <div class="col-md-10">
                        
                        <select id="hv-tipo" name="tipo" data-validation="required" class="form-control" data-validation="required">
                            <option selected="" value="">-- Seleccione --</option>
                            {TIPOS}
                        </select>  
                  </div>                                
              </div>
            <div class="form-group">
                    <label for="accion" class="col-md-4 control-label" style="color: black;">{N_ACCION}</label>
                    <div class="col-md-10">                                          
                      <textarea class="form-control" rows="3" id="hv-accion" name="accion" data-validation="required" placeholder="{N_ACCION}">{ACCION}</textarea>
                  </div>                                
              </div>
            <div class="form-group">
                    <label for="fecha_acordada" class="col-md-4 control-label" style="color: black;">{N_FECHA_ACORDADA}</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control"  style="width: 110px;" data-date-format="DD/MM/YYYY"  value="{FECHA_ACORDADA}" id="hv-fecha_acordada" name="fecha_acordada" placeholder="dd/mm/yyyy"  data-validation="required"/>
                  </div>                                
              </div>
            <div class="form-group">
                    <label for="fecha_realizada" class="col-md-4 control-label" style="color: black;">{N_FECHA_REALIZADA}</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control"  style="width: 110px;" data-date-format="DD/MM/YYYY"  value="{FECHA_REALIZADA}" id="hv-fecha_realizada" name="fecha_realizada" placeholder="dd/mm/yyyy" />
                  </div>                                
              </div>
            <div class="form-group">
                    <label for="id_responsable" class="col-md-4 control-label" style="color: black;">{N_ID_RESPONSABLE}</label>
                    <div class="col-md-10">
                        <select id="hv-id_responsable" name="id_responsable" data-validation="required">
                            <option selected="" value="">-- Seleccione --</option>
                            {RESPONSABLE_ANALISIS}
                        </select>                      
                  </div>                                
              </div>


            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <!--
                    <input class="button save" name="guardar" type="button" value="Guardar" onClick="validar_hv(document);">
                    <input class="button " type="button" value="Cancelar" onclick="reset_formulario();">
                    -->
                    <button type="button" id="btn-guardar-hv" class="btn btn-primary" onClick="validar_hv(document);">Guardar</button>            
                    <button type="button" class="btn btn-default" onclick="reset_formulario();">Cancelar</button>


                    <input type="hidden" id="opc-hv" name="opc" value="{OPC}">
                    <input type="hidden" id="id-hv"  name="id"  value="{ID}">
                </div>
            </div>
        </form>
        
        
    </div>
                
                
    <div class="tab-pane active" id="hv-orange">
        
        <div class="table-container">
            <div class="content-wrapper clear-block">
                <div id="grid-hv" class="table-container scrollable">  
                    {TABLA}
                </div>
                
            </div>
        </div>
         
    </div>
        
</div>
</div>
<form id="busquedaFrm-hv" class="form-horizontal form-horizontal-form" role="form">                        
                        <input type="hidden" name="mostrar-col" id="mostrar-col-hv" value="{MOSTRAR_COL}" />
                        <input type="hidden" name="reg_por_pag" id="reg_por_pag-hv" value="12"/>
                        <input type="hidden" name="corder" id="corder-hv" value="{CORDER}"/>
                        <input type="hidden" name="sorder" id="sorder-hv" value="{SORDER}"/>
                        <input type="hidden" value="{COD_EMP}" id="b-cod_emp" name="b-cod_emp"/>
                    </form>
  

