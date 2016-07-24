<!--<div class="form-group">
                                        <label for="id_responsable" class="col-md-4 control-label">{N_ID_RESPONSABLE}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{ID_RESPONSABLE}" id="id_responsable" name="id_responsable" placeholder="{N_ID_RESPONSABLE}"  data-validation="required"/>
                                      </div>                                
                                  </div>-->
<div class="form-group">
                                        <label for="estado" class="col-md-4 control-label">{N_ESTADO}</label>
                                        <div class="col-md-10">
                                          <input type="text" readonly class="form-control" value="Completado" id="estado" name="estado" placeholder="{N_ESTADO}" data-validation="required"/>
                                      </div>                                
                                  </div>
                                      <div class="form-group">
                                        <label for="fecha_ejecutada" class="col-md-4 control-label">{N_FECHA_EJECUTADA}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" style="width: 120px;" data-date-format="DD/MM/YYYY"  value="{FECHA_EJECUTADA}" id="fecha_ejecutada" name="fecha_ejecutada" placeholder="dd/mm/yyyy"  data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="id_documento" class="col-md-4 control-label">{N_ID_DOCUMENTO}</label>
                                        <div class="col-md-10">
                                            <select name="id_documento" id="id_documento" data-validation="required">
                                                        <option selected="" value="">-- Seleccione --</option>
                                                        {DOCUMENTOS}
                                          </select>
                                          <!--<input type="text" readonly class="form-control" value="{ID_DOCUMENTO}" id="id_documento" name="id_documento" placeholder="{N_ID_DOCUMENTO}"  data-validation="required"/>-->
                                      </div>                                
                                  </div>
                                      <div class="form-group">
                                        <label for="id_documento" class="col-md-4 control-label">{N_ID_AREA}</label>
                                         <div class="col-md-10" id="cmb-id_area">
                                             <select class="selectpicker form-control" id="id_area" name="id_area[]" multiple>
                                                {OPTION_AREAS}
                                            </select>
                                         </div>                                
                                  </div>
                                    <div class="form-group">
                                        <label for="id_documento" class="col-md-4 control-label">{N_ID_CARGO}</label>
                                         <div class="col-md-10" id="cmb-id_cargo">
                                             <select class="selectpicker form-control" id="id_cargo" name="id_cargo[]" multiple>
                                                {OPTION_CARGOS}
                                            </select>
                                         </div>                                
                                  </div>

<!--<div class="form-group">
                                        <label for="fecha_notificacion" class="col-md-4 control-label">{N_FECHA_NOTIFICACION}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{FECHA_NOTIFICACION}" id="fecha_notificacion" name="fecha_notificacion" placeholder="{N_FECHA_NOTIFICACION}"  data-validation="required"/>
                                      </div>                                
                                  </div>-->


                                  <div class="row">
                                      
                        <div class="row-height">
                            <div class="col-md-11" id="div-origen">
<div class="form-group">
                                          <label for="id_documento" class="col-md-10 control-label"><b>Personal Disponible</b></label>
                                                                      
                                  </div>
                                <select name="origen[]" class="form-control" id="origen" multiple="multiple" style="height: 350px;">
                                    {ORIGEN}                                    
                                </select>
                            </div>
                            <div class="col-md-2 ">
                                <br><br><br><br>
                                <input type="button" class="pasar izq" value="Pasar »"><input type="button" class="quitar der" value="« Quitar"><br/>
                                <input type="button" class="pasartodos izq" value="Todos »"><input type="button" class="quitartodos der" value="« Todos">
                            </div>
                            <div class="col-md-11 "  >
                                <div class="form-group">
                                          <label for="id_documento" class="col-md-10 control-label"><b>Personal Capacitado</b></label>
                                                                      
                                  </div>
                                <select name="destino[]" class="form-control" id="destino" multiple="multiple" data-validation="required" style="height: 350px;">
                                    {DESTINO}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10" style="">
                            <strong id="st-total-per" > Total {TOTAl_PER} Personas.</strong>
                        </div>
                         <div class="col-md-4 ">
                            </div>
                        <div class="col-md-10 " style="" >
                            <strong id="total-pers-sel"> {TOTAl_PER_SEL} Personas seleccionadas.</strong>
                         </div>
                    </div><br>
                         <div class="form-group" id="tabla_fileUpload">
                                        <label for="archivo" class="col-md-4 control-label">{N_EVIDENCIAS}</label>
                                        {ARCHIVOS_ADJUNTOS}                                          
                                 </div>  
