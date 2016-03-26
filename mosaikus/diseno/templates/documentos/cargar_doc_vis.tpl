
                            <div class="form-group" id="tabla_fileUpload_vis"  style="{CSS_TABLA_FILEUPLOAD_VIS}">
                                    <label for="archivo" class="col-md-4 control-label">{N_NOM_VISUALIZA}</label>
                                    <div class="col-md-10">
                                        <input type="file" style="" value="{ARCHIVO}"accept="application/pdf" id="fileUpload2_vis" name="fileUpload2_vis" onchange="cargar_archivo_vis();"/>
                                        <input type="hidden" id="estado_actual_vis" name="estado_actual_vis">
                                        <input type="hidden" id="filename_vis" name="filename_vis" value="{FILENAME}">
                                        <input type="hidden" id="tamano_vis" name="tamano_vis" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc_vis" name="tipo_doc_vis" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                                        <div id="estado_vis" style="display:none;">
                                            <!--<img src="{PAHT_TO_IMG}loading3.gif">Cargando-->
                                            <div class="progress" style="width: 250px;">
                                                <div class="progress-bar" id="estado-vis-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
                                                  0%
                                                </div>
                                            </div>
                                        </div>                                        
                                        
                                  </div> 
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.PDF</span>
                             </div>
                             <div class="form-group" id="info_archivo_adjunto_vis"  style="display:none;">
                                    <label for="archivo" class="col-md-4 control-label">{N_NOM_VISUALIZA}</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" style="">                                            
                                            <input type="text" class="form-control" style="width: 250px;display: inline;" readonly="readonly" id="info_nombre_vis">                                            
                                            <a href="#" onclick="cancelar_archivo_vis();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>  
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.PDF</span>
                             </div>
