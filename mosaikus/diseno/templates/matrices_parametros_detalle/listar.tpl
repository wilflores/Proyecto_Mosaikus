{HTML_INICIO_PAG}

<ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-orange" data-toggle="tab">Listado de Items</a></li>  
        <li><a href="#hv-red" data-toggle="tab">Agregar/Modificar Items</a></li>
              
    </ul>

<div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="hv-red">
        <form id="idFormulario-hv" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="descripcion" class="col-md-2 control-label" style="color:black">{N_NOMBRE}</label>
                <div class="col-md-4">
                  <input type="text" class="form-control" value="{NOMBRE}" id="hv-nombre" name="nombre" placeholder="{N_NOMBRE}" data-validation="required"/>
              </div>                                
            </div>
           

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <input class="button save" name="guardar" type="button" value="Guardar" onClick="validar_hv(document);">
                    <input class="button " type="button" value="Cancelar" onclick="reset_formulario();">

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
                    </form>
    <div class="tab-pane active" id="hv-orange">
        
        <div  class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
            <div  class="content-wrapper clear-block">
                <div id="grid-hv" >
                {TABLA}
                </div>
                <br>
            </div>
        </div>
         
    </div>
        
</div>


  

