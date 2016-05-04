<div id="main-content" class="panel-container col-xs-24 ">
              <div class="content-panel panel">
                  <div class="content">
                      
    <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
        <div class="modal fade bs-example-modal-lg" id="myModal-observacion-rechazo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog  modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Flujo de Trabajo</h4>
                </div>
                <div class="modal-body">
                    Observaciones de Rechazo de Documento
                    <textarea  id="observacion_rechazo" cols="30" rows="2" name="observacion" class="form-control" placeholder="Por favor indique las observaciones, se enviará correo al elaborador"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>                                  
                  <button type="button" class="btn btn-primary" onClick="RechazarWF('RECHAZADO','{ETAPARECHAZO}',{IDDOC});">Enviar</button>
                </div>
              </div>
            </div>            
        </div>
        <div class="panel-heading">
            <div class="row">
                <div class="panel-title col-xs-20" id="div-titulo-for-wf">
                    {TITULO_FORMULARIO}  {VERHISTO}
                    <br>
                    <button  {MOSTRARCAMBIAR} type="button" class="btn btn-default" onclick="CambiarEstadoWF('OK','{ETAPANUEVA}',{IDDOC});"><i class="glyphicon glyphicon-ok"></i> &nbsp;{TITULOESTADO}</button>
                    <button {MOSTRARRECHAZAR} type="button" class="btn btn-default" onclick="$('#myModal-observacion-rechazo').modal('show');"><i class="glyphicon glyphicon-remove"></i> &nbsp;Rechazar</button>
                    <!--<button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');"><i class="glyphicon glyphicon-chevron-left"></i> Volver</button>-->
                </div>
                <div class="panel-actions col-xs-4">
                    <ul class="navbar">                                          

                      <li class="">
                        <a href="#contenido"  onClick="MostrarContenido();">
                          <i class="glyphicon glyphicon-menu-left"></i>
                          <span>Volver</span>
                        </a>
                      </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div style="height: 500px;" class="col-xs-12">
               DOCUMENTO FUENTE 
               {DOCFUENTE}
           </div>
            <div class="col-xs-12">
                DOCUMENTO DE VISUALIZACI&Oacute;N
                {DOCVISUALIZA}
            </div>
        </div>
</form>
</div>
        </div>
</div>
