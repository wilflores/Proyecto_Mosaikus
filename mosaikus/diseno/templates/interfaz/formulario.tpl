<!--<form method="post" id="frm_volver">
    <input type="hidden" name="permiso" id="permiso">
</form>
<br>
<div class="buttom-panel">
    <input type="button" class="button undo" value="{TITULO_VOLVER}" onclick="funcion_volver('{PAGINA_VOLVER}');" >
</div>
<div class="form-container">
        <form action="listarClasificacion.php" name="idFormulario" id="idFormulario" method="post">
        <fieldset class=" fieldset titled">
            <legend>
                <span class="fieldset-title"> {TITULO_FORMULARIO}</span>
            </legend>
            <div class="fieldset-content clear-block ">
            <table class="form-table">
				
              </table>
                <div class="field-wrapp">                    
                    <input class="button save" name="guardar" type="button" value="{DESC_OPERACION}" onClick="validar(document);">
                    <input class="button borrador" name="limpiar" type="reset" value="Limpiar">
                </div>
            </div>
        </fieldset>
        
    </form>
</div>
-->

<div id="main-content" class="panel-container col-xs-24 ">
    <div class="content-panel panel">
        <div class="content">
                      <!-- -->
            <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
                <div class="panel-heading">
                    <div class="row">
                            <div class="panel-title col-xs-20" id="div-titulo-for">
                                {TITULO_FORMULARIO}                    
                            </div>       
                            <div class="panel-actions col-xs-4">
                                    <ul class="navbar">                                          

                                        <li class="">
                                            <a href="#contenido"  onClick="MostrarContenido();">
                                                <i class="glyphicon glyphicon-menu-left"></i>
                                                <span>{N_VOLVER}</span>
                                            </a>
                                        </li>
                                    </ul>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24">
                        {CAMPOS}

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-20">
                                <button type="button" id="btn-guardar" class="btn btn-primary" onClick="{JS_PREVIO_GUARDAR}validar(document);">{DESC_OPERACION}</button>
                                {OTRO_BOTON_PRINCIPAL}
                                <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">{N_CANCELAR}</button>

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

