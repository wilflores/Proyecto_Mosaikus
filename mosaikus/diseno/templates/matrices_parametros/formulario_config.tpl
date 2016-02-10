<form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">            
<div class="form-group">
                    <label for="nombre" class="col-md-2 control-label">Nombre (Identificación Pestaña 1)</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" value="{NOMBRE_11}" id="nombre_11" name="nombre_11" data-validation="required"/>                      
                  </div>                                
              </div>
            <div class="form-group">
                    <label for="nombre" class="col-md-2 control-label">Nombre (Acción Correctiva Pestaña 2)</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" value="{NOMBRE_12}" id="nombre_12" name="nombre_12" data-validation="required"/>                      
                  </div>                                
              </div>
            <div class="form-group">
                    <label for="indicador" class="col-md-2 control-label">Aplica (Est.Organizacional)</label>
                    <div class="col-md-3">                       
                    <label class="radio-inline" style="padding-top: 0px;color:white">
                        <input type="radio" id="indicador_31" value="S" name="indicador_31" {CHECKED_IND31_SI}> Si
                    </label>
                    <label class="radio-inline" style="padding-top: 0px;color:white">
                        <input type="radio" id="indicador_31" value="N" name="indicador_31" {CHECKED_IND31_NO}> No
                    </label>                    
                </div>

              </div>
            <div class="form-group">
                    <label for="nombre" class="col-md-2 control-label">Nombre(Est.Organizacional)</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" value="{NOMBRE_31}" id="nombre_31" name="nombre_31" data-validation="required"/>                      
                  </div>                                
              </div>
              <div class="form-group">
                    <label for="indicador" class="col-md-2 control-label">Aplica (Árbol de Proceso)</label>
                    <div class="col-md-3">                       
                    <label class="radio-inline" style="padding-top: 0px;color:white">
                        <input type="radio" id="indicador_32" value="S" name="indicador_32" {CHECKED_IND32_SI}> Si
                    </label>
                    <label class="radio-inline" style="padding-top: 0px;color:white">
                        <input type="radio" id="indicador_32" value="N" name="indicador_32" {CHECKED_IND32_NO}> No
                    </label>                    
                </div>

              </div>
            <div class="form-group">
                    <label for="nombre" class="col-md-2 control-label">Nombre(Árbol de Proceso)</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" value="{NOMBRE_32}" id="nombre_32" name="nombre_32" data-validation="required"/>                      
                  </div>                                
              </div>
              <div class="form-group">
                    <label for="indicador" class="col-md-2 control-label">Aplica (Semáforo Final)</label>
                    <div class="col-md-3">                       
                    <label class="radio-inline" style="padding-top: 0px;color:white">
                        <input type="radio" id="indicador_34" value="S" name="indicador_34" {CHECKED_IND34_SI}> Si
                    </label>
                    <label class="radio-inline" style="padding-top: 0px;color:white">
                        <input type="radio" id="indicador_34" value="N" name="indicador_34" {CHECKED_IND34_NO}> No
                    </label>                    
                </div>

              </div>
            <div class="form-group">
                    <label for="nombre" class="col-md-2 control-label">Nombre (Semáforo Final)</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" value="{NOMBRE_34}" id="nombre_34" name="nombre_34" data-validation="required"/>                      
                  </div>                                
              </div>
                  <div class="form-group" id="div-verde">
                    <label for="nombre" class="col-md-2 control-label">Cuando el Valor es >=</label>
                    <div class="col-md-4">
                        <input type="text" style="width: 100px;display:inline;" class="form-control" value="{SEM_3}" id="sem_3" name="sem_3" />                      
                        &nbsp;<img border="0" align="absmiddle" src="diseno/images/verde.png">
                  </div>                                
              </div>
            <div class="form-group" id="div-rojo">
                    <label for="nombre" class="col-md-2 control-label">Cuando el Valor es < </label>
                    <div class="col-md-4">
                      <input type="text" style="width: 100px;display:inline;" class="form-control" value="{SEM_1}" id="sem_1" name="sem_1" />                      
                      &nbsp; <img border="0" align="absmiddle" src="diseno/images/rojo.png">
                  </div>                                
              </div>
              <div class="form-group" id="div-amarillo">
                    <label for="nombre" class="col-md-2 control-label">Entre </label>
                    <div class="col-md-4">
                      <input type="text" readonly="readonly" style="width: 100px;display:inline;" class="form-control" value="{SEM_21}" id="sem_21" name="sem_21" />                      
                      &nbsp;y&nbsp;
                      <input type="text" readonly="readonly" style="width: 100px;display:inline;" class="form-control" value="{SEM_22}" id="sem_22" name="sem_22" />                      
                      &nbsp;<img border="0" align="absmiddle" src="diseno/images/amarillo.png">
                  </div>                                
              </div>
                      
            <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <input class="button save" name="guardar" type="button" value="{DESC_OPERACION}" id="btn-guardar" onClick="validar_config(document);">
            <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">
            
            <input type="hidden" id="opc" name="opc" value="{OPC}">
            <input type="hidden" id="id"  name="id"  value="{ID}">
        </div>
    </div>
</form>
            

