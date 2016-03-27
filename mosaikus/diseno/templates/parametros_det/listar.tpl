{HTML_INICIO_PAG}
<div class="tabs"> 
<ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-orange" data-toggle="tab">Listado de Items</a></li>  
        <li><a href="#hv-red" data-toggle="tab">Agregar/Modificar Items</a></li>
              
    </ul>

<div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="hv-red">
        <form id="idFormulario-hv" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="descripcion" class="col-md-4 control-label" style="color:black">{N_DESCRIPCION}</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" value="{DESCRIPCION}" id="hv-descripcion" name="descripcion" placeholder="{N_DESCRIPCION}" data-validation="required"/>
              </div>                                
            </div>
            <div class="form-group">
                <label for="vigencia" class="col-md-4 control-label" style="color:black">{N_VIGENCIA}</label>
                <div class="col-md-10">
                    <label class="checkbox-inline" style="padding-top: 0px;">
                        <input type="checkbox" name="vigencia" id="hv-vigencia" value="S" checked="checked" {CHECKED_VIGENCIA}>   &nbsp;
                    </label>                              
                </div>
           </div>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-20">
                    
                    
                    <button type="button" class="btn btn-primary" id="btn-guardar" onClick="validar_hv(document);">Guardar</button>                                
                    <button type="button" class="btn btn-default" onclick="reset_formulario();">Cancelar</button>

                    <input type="hidden" id="opc-hv" name="opc" value="{OPC}">
                    <input type="hidden" id="id-hv"  name="id"  value="{ID}">
                </div>
            </div>
        </form>
        
        
    </div>
                
                <form id="busquedaFrm-hv" class="form-horizontal form-horizontal-form" role="form">                        
                        <input type="hidden" name="mostrar-col" id="mostrar-col-hv" value="{MOSTRAR_COL}" />
                        <input type="hidden" name="reg_por_pag" id="reg_por_pag-hv" value="12"/>
                        <input type="hidden" name="corder" id="corder-hv" value="{CORDER}"/>
                        <input type="hidden" name="sorder" id="sorder-hv" value="{SORDER}"/>
                        <input type="hidden" value="{COD_EMP}" id="b-cod_emp" name="b-cod_emp"/>
                        {OTROS_CAMPOS} 
                    </form>
    <div class="tab-pane active" id="hv-orange">
        
        
                <div id="grid-hv" >
                {TABLA}
                </div>
                <br>
            
         
    </div>
        
</div>
                </div>


  

