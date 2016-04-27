
                            <div class="form-group" id="tabla_fileUpload"  style="display:none;">
                                    <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-10">
                                        <input type="file"  accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.ms-excel" value="1" id="fileUpload2" name="fileUpload2" onchange="cargar_archivo_ver();" />
                                        <input type="hidden" id="estado_actual" name="estado_actual" value="-1">
                                        <input type="hidden" id="filename" name="filename" value="{FILENAME}">
                                        <input type="hidden" id="tamano" name="tamano" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc" name="tipo_doc" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                                        <div id="estado" style="display:none;color:white;">
                                            <div class="progress" style="width: 250px;">
                                                <div class="progress-bar" id="estado-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
                                                  0%
                                                </div>
                                            </div>
                                        </div>
                                  </div>     
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>
                             <div class="form-group" id="info_archivo_adjunto"  >
                                    <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" style="">
                                            <input type="text" value="{NOMBRE_DOC_AUX}" readonly="readonly" class="form-control" style="width: 250px;display: inline;" id="info_nombre">
                                            <a href="#" onclick="cancelar_archivo_otro();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>         
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>
                                                                           