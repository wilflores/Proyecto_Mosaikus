<div id="main-content" class="panel-container col-xs-24 ">
    <div class="content-panel panel">
        <div class="content">
                      <!-- -->
            <form id="r-idFormulario" class="form-horizontal form-horizontal-red" role="form">
                <div class="panel-heading">
                    <div class="row">
                        <div class="panel-title col-xs-12" id="div-titulo-for">
                            {TITULO_FORMULARIO}                    
                        </div>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24">
                <div class="form-group">
                        <label for="nombres" class="col-md-4 control-label">USUARIO :</label>
                        <div class="col-md-10">
                            <input type="hidden" class="form-control" value="{ID_FILIAL}" id="id_filial" name="id_filial" placeholder="{N_ID_FILIAL}"  data-validation="required" />
                            <input type="hidden" class="form-control" value="{ID_USUARIO}" id="id_usuario" name="id_usuario" placeholder="{N_ID_USUARIO}"  data-validation="required" />
                            <input type="text" readonly class="form-control" value="{NOMBRES}" id="nombres" name="nombres" placeholder="{N_NOMBRES}" data-validation="required"/>
                        </div>        
                    </div>
                    <div class="form-group">
                        <label for="nombres" class="col-md-4 control-label">PERFIL :</label>
                        <div class="col-md-10">
                            <input type="hidden" class="form-control" value="{COD_PERFIL}" id="cod_perfil" name="cod_perfil" placeholder="{N_COD_PERFIL}"  data-validation="required" />
                            <input type="text" readonly class="form-control" value="{DESCRIPCION_PERFIL}" id="descripcion_perfil" name="descripcion_perfil" placeholder="{N_DESCRIPCION_PERFIL}" data-validation="required"/>
                        </div>        
                    </div>

                    <div class="form-group">
                        <div class="col-md-9">
                            <label for="vigencia" class="col-md-10 control-label" style="text-align: left;">Accesos&nbsp;a&nbsp;Estructura</label>
                            <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
                            <iframe id="iframearbol" src="pages/mos_usuario/listaEstructura.php?id_usuario={ID_USUARIO}&cod_perfil={COD_PERFIL}&id_filial={ID_FILIAL}&portal=S" frameborder="0" width="100%" height="310px" scrolling="no"></iframe>
                        </div>


                    </div>        


                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <!--<input class="button save" name="guardar" type="button" value="{DESC_OPERACION}" onClick="validar(document);">
                                <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">
                                -->
                                <button type="button" id="btn-guardar" class="btn btn-primary" onClick="validar_perfil_usuario_portal(document);">{DESC_OPERACION}</button>                                
                                <button type="button" class="btn btn-default" onclick="perfil_portal({ID_USUARIO});">Cancelar</button>

                                <input type="hidden" id="opc" name="opc" value="{OPC}">
                                <input type="hidden" id="id"  name="id"  value="{ID}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>    




