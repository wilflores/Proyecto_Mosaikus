
                <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
                                {CAMPOS_DINAMICOS_PES_1}
                                
                        <div class="modal fade bs-example-modal-lg" id="myModal-Filtrar-Arbol" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog  modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Árbol Organizacional</h4>
                                </div>
                                <div class="modal-body">
                                    <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/personas/emb_jstree_single_aux.php"></iframe>
                                    <input type="hidden" id="b-id_organizacion_aux" name="b-id_organizacion_aux" value="{ID_ORGANIZACION}"/>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>                                  
                                  <button type="button" class="btn btn-primary" onClick="filtrar_arbol()">Seleccionar</button>
                                </div>
                              </div>
                            </div>
                        </div>
                                
                        <div class="modal fade bs-example-modal-lg" id="myModal-Filtrar-Proceso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog  modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Árbol de Procesos</h4>
                                </div>
                                <div class="modal-body">
                                    <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/arbol_procesos/emb_jstree_procesos_aux.php"></iframe>
                                    <input type="hidden" id="b-id_proceso_aux" name="b-id_proceso_aux" value="{ID_PROCESO}"/>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>                                  
                                  <button type="button" class="btn btn-primary" onClick="filtrar_proceso()">Seleccionar</button>
                                </div>
                              </div>
                            </div>
                        </div>
                                
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10">
                                    <input class="button save" name="guardar" type="button" value="Guardar" onClick="validar(document);">
                                    <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">
                                    <input type="hidden" id="opc" name="opc" value="{OPC}">
                                    <input type="hidden" id="id"  name="id"  value="{ID}">
                                </div>
                            </div>
                     </form>
            </div>
           