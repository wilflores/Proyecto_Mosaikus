<div id="main-content" class="panel-container col-xs-24 ">
    <div class="content-panel panel">
        <div class="content">
            <form id="r-idFormulario" class="form-horizontal form-horizontal-red" role="form">
                <div class="panel-heading">
                    <div class="row">
                        <div class="panel-title col-xs-12" id="r-div-titulo-for">
                            {TITULO_FORMULARIO}                    
                        </div>                

                    </div>
                </div>
                <div class="row">
                <div class="col-xs-24"> 
                             <div class="form-group" id="info_archivo_adjunto">
                                    <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" >
                                            <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                            <input type="text" class="form-control" readonly="readonly" style="display: inline;" id="info_nombre" name="info_nombre" value="{NOMBRE_DOC}">
                                            <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->                                            
                                        </p>                      
                                  </div>         
                                  <span class="help-block" style="font-size: small;">(*) Código-nombre archivo.extension</span>
                             </div>


                               
                                                     {CAMPOS_DINAMICOS}
                                               


                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    
                                    <button type="button" class="btn btn-primary" id="btn-guardar" onClick="r_validar(document);">{DESC_OPERACION}</button>                
                                    <button type="button" class="btn btn-default" onclick="MostrarContenidoAux();">Cancelar</button>

                                    <input type="hidden" id="r-opc" name="opc" value="{OPC}">
                                    <input type="hidden" id="r-id"  name="id"  value="{ID}">
                                </div>
                            </div>
</div></div>
</form>
</div>
        </div></div>

        