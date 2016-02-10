    <ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-red" data-toggle="tab">{NOMBRE_PEST_1}</a></li>
        <li><a href="#hv-orange" data-toggle="tab">Agregar/Modificar {NOMBRE_PEST_2}</a></li>        
        <li><a href="#hv-blue" data-toggle="tab">Listado {NOMBRE_PEST_2}</a></li>  
    </ul>
    
        <div id="my-tab-content" class="tab-content">
            <div class="tab-pane active" id="hv-red">
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
                                    <input type="hidden" id="b-id_organizacion_aux" name="b-id_organizacion_aux" value=""/>
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
                                    <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/arbol_procesos/emb_jstree_procesos.php"></iframe>
                                    <input type="hidden" id="b-id_proceso_aux" name="b-id_proceso_aux" value=""/>
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
                                    <input class="button save" name="guardar" type="button" value="Siguiente" onClick="validar_p1();">
                                    <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">
                                    
                                </div>
                            </div>
                     </form>
            </div>
            <div class="tab-pane active" id="hv-orange">
                <div class="form-group" style="border-bottom: 0px solid #ffffff;">
                    <form id="busquedaFrm-Per" class="form-horizontal" role="form">
                        
                    </form>
                    
                    <div class="form-group">                                                                        
                        <div class="col-md-offset-2 col-md-10">
                            <input class="button save" name="guardar" type="button" value="Siguiente" onClick="validar_p2();">
                            <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">

                        </div>
                    </div>
                </div>
                
             </div>
            <div class="tab-pane" id="hv-blue">
                <form id="idFormulario-Data">
                    <div class="col-md-10" style=" background-image: url('diseno/images/FondoDIVPrin.png');padding-left: 0px; padding-right: 0px;">
                        <div class="content-wrapper clear-block">
                            <div id="grid-personal-cap">
                                <input type="hidden" id="num_items_esp" name="num_items_esp" value="{NUM_ITEMS_ESP}"/>
                                <input type="hidden" id="tipo_curso" name="tipo_curso" value="{TIPO_CURSO}"/>
                                <table id="table-pers-capa" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                    <thead>
                                        <tr bgcolor="#FFFFFF" height="30px">
                                            <th width="3%">
                                                <div align="left"> </div>
                                            </th>
                                            <th width="5%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_COD_EMP}</div>
                                                </div>
                                            </th>
                                            <th width="20%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">Nombres y Apellidos</div>
                                                </div>
                                            </th>
                                            <th width="5%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_APROBACION}</div>                                                
                                                </div>
                                            </th>
                                            <th width="5%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_NOTA_EVALUACION}</div>
                                                </div>
                                            </th>
                                            <th width="3%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_ASISTENCIA}</div>
                                                </div>
                                            </th>
                                            <th width="3%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_OBSERVACION}</div>
                                                </div>
                                            </th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {ITEMS_ESP}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="id"  name="id"  value="{ID}">
                    </form>
                </div>
               <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <input class="button save" name="guardar" type="button" value="{DESC_OPERACION}" onClick="validar(document);">
                        <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">

                        <input type="hidden" id="opc" name="opc" value="{OPC}">
                        
                    </div>
                </div>
            </div>
         </div>                         
    
        
        <form enctype="multipart/form-data" id="formuploadajax" method="post">
        
            <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="15000000" />
        </form>