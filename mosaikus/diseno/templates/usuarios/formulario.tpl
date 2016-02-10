<div class="form-container">
        <form name="idFormulario-cp" id="idFormulario-cp" class="form-horizontal" role="form">
        
            <div class="form-group">
                    <label for="cod_hoja_vida" class="col-md-2 control-label"style="color:black">Usuario</label>
                    <div class="col-md-10">
                        <p class="form-control-static">{LOGIN}</p>
                    </div>                             
            </div>
            <div class="form-group">
                    <label for="cod_hoja_vida" class="col-md-2 control-label"style="color:black">Nombre Completo</label>
                    <div class="col-md-10">
                        <p class="form-control-static">{NOMBRE}</p>
                    </div>                             
            </div>
            <div class="form-group">
                    <label for="cod_hoja_vida" class="col-md-2 control-label"style="color:black">Teléfono</label>
                    <div class="col-md-10">
                        <p class="form-control-static">{TELEFONO}</p>
                    </div>                             
            </div>
            <div class="form-group">
                    <label for="cod_hoja_vida" class="col-md-2 control-label"style="color:black">Fecha Creación</label>
                    <div class="col-md-10">
                        <p class="form-control-static">{FECHA_C}</p>
                    </div>                             
            </div>       
            <div class="form-group">
                    <label for="cod_hoja_vida" class="col-md-2 control-label"style="color:black">Fecha Expiración</label>
                    <div class="col-md-10">
                        <p class="form-control-static">{FECHA_E}</p>
                    </div>                             
            </div>  
            <div class="form-group">
                <label for="descripcion" class="col-md-2 control-label" style="color:black">Contraseña Actual</label>
                <div class="col-md-4">
                  <input type="password" class="form-control" id="password_actual" name="password_actual" placeholder="Contraseña Actual" data-validation="required"/>
              </div>                                
            </div>
            <div class="form-group">
                <label for="descripcion" class="col-md-2 control-label" style="color:black">Nueva Contraseña</label>
                <div class="col-md-4">
                  <input type="password" class="form-control" id="password" name="password" placeholder="Nueva Contraseña" data-validation="required"/>
              </div>                                
            </div>
            <div class="form-group">
                <label for="descripcion" class="col-md-2 control-label" style="color:black">Repetir Contraseña</label>
                <div class="col-md-4">
                  <input type="password" class="form-control" name="password2" id="password2" placeholder="Repetir Contraseña" data-validation="required igual_pw"/>
              </div>                                
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <input class="button save" name="guardar" type="button" value="Guardar" onClick="validar_cambio(document);">
                    <input class="button " type="button" value="Cancelar" onclick="$('#myModal-Ventana').modal('toggle');;">

                    <input type="hidden" id="opc-hv" name="opc" value="{OPC}">
                    <input type="hidden" id="id-hv"  name="id"  value="{ID}">
                </div>
            </div>
                            
           
    </form>
</div>


