<!--
<div class="form-group">
                                        <label for="cod_perfil" class="col-md-4 control-label">{N_COD_PERFIL}</label>
                                        <div class="col-md-10">
                                          <input type="number" class="form-control" value="{COD_PERFIL}" id="cod_perfil" name="cod_perfil" placeholder="{N_COD_PERFIL}"  data-validation="required" />
                                      </div>                                
                                  </div>
-->
    <div class="form-group">
        <label for="descripcion_perfil" class="col-md-4 control-label">{N_DESCRIPCION_PERFIL}</label>
        <div class="col-md-10">
            <input type="hidden" class="form-control" value="{COD_PERFIL}" id="cod_perfil" name="cod_perfil" placeholder="{N_COD_PERFIL}"  data-validation="required" />
            <input type="text" readonly class="form-control" value="{DESCRIPCION_PERFIL}" id="descripcion_perfil" name="descripcion_perfil" placeholder="{N_DESCRIPCION_PERFIL}" data-validation="required"/>

            <label for="vigencia" class="col-md-10 control-label" style="text-align: left;">{N_ACCESO_MENU}</label>
            <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
            <iframe id="iframearbol" src="pages/perfiles/prueba_menuV1.php?cod_perfil={COD_PERFIL}" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>
        </div>        
    </div>
                                      
