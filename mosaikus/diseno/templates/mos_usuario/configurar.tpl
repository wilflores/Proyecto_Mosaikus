<!--
<div class="form-group">
                                        <label for="cod_perfil" class="col-md-4 control-label">{N_COD_PERFIL}</label>
                                        <div class="col-md-10">
                                          <input type="number" class="form-control" value="{COD_PERFIL}" id="cod_perfil" name="cod_perfil" placeholder="{N_COD_PERFIL}"  data-validation="required" />
                                      </div>                                
                                  </div>
-->
    <div class="form-group">
        <label for="nombres" class="col-md-4 control-label">{N_NOMBRES}</label>
        <div class="col-md-10">
            <input type="hidden" class="form-control" value="{ID_USUARIO}" id="id_usuario" name="id_usuario" placeholder="{N_ID_USUARIO}"  data-validation="required" />
            <input type="text" readonly class="form-control" value="{NOMBRES}" id="nombres" name="nombres" placeholder="{N_NOMBRES}" data-validation="required"/>
        </div>        
    </div>
    <div class="form-group">
        <div class="col-md-9">
            <label for="vigencia" class="col-md-10 control-label" style="text-align: left;">{N_ACCESO_PERFIL_ESPECIALISTA}</label>
            <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
            <iframe id="iframearbol" src="pages/mos_usuario/listaPerfiles.php?id_usuario={ID_USUARIO}" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>
        </div>
        <div class="col-md-15">
            <label for="vigencia" class="col-md-10 control-label" style="text-align: left;">{N_ACCESO_PERFIL_PORTAL}</label>
            <input type="hidden" value="{NODOSPORTAL}" name="nodosportal" id="nodosportal"/>
            <iframe id="iframearbolportal" src="pages/mos_usuario/listaPerfilesPortal.php?id_usuario={ID_USUARIO}" frameborder="0" width="60%" height="310px" scrolling="no"></iframe>
        </div>
        
    </div>        
                                      
