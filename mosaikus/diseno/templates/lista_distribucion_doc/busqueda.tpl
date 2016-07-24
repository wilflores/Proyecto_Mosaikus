<!--<div class="form-group">
                                  <label for="estado" class="control-label"></label>
                                  
                                    <input type="text" class="form-control" id="b-estado" name="b-estado" placeholder="{N_ESTADO}" />
                                                          
                            </div>-->
                            
                                <div class="form-group" style="margin-bottom: 0px;"><label>{N_ESTADO}</label></div>
                                <div class="form-group">&nbsp;&nbsp;&nbsp;&nbsp;
                                   
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" value="Completado" name="b-estado[]" id="b-estado"> 
                                    <img style="margin-top: -6px;" src="diseno/images/verde.png"> 
                                    Completado 
                                        </label>
                                        <br>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="checkbox-inline"> 
                                            <input type="checkbox" value="Pendiente" name="b-estado[]" id="b-estado"> 
                                    <img style="margin-top: -6px;" src="diseno/images/atrasado.png"> 
                                    Pendiente 
                                        </label>
                                        <br>
                                </div>
<div class="form-group">
                                  <label for="id_documento" class="control-label">{N_ID_DOCUMENTO}</label>
                                  
                                    <input type="text" class="form-control" id="b-id_documento" name="b-id_documento" placeholder="{N_ID_DOCUMENTO}"/>
                                                             
                            </div>
<!--<div class="form-group">
                                  <label for="fecha_notificacion" class="control-label">{N_FECHA_NOTIFICACION}</label>
                                  
                                    <input type="text" class="form-control" id="b-fecha_notificacion" name="b-fecha_notificacion" placeholder="{N_FECHA_NOTIFICACION}"/>
                                                             
                            </div>-->
<div class="form-group">
                                  <label for="fecha_ejecutada" class="control-label">{N_FECHA_EJECUTADA}</label>
                                  <div class="row">
                                        <div class="col-xs-12">
                                            <label>Desde</label>
                                            <input type="text" class="form-control" data-date-format="DD/MM/YYYY" id="b-fecha_ejecutada-desde" name="b-fecha_ejecutada-desde" placeholder="dd/mm/yyyy"  />
                                        </div>   
                                        <div class="col-xs-12">
                                            <label>Hasta</label>
                                          <input type="text" class="form-control" data-date-format="DD/MM/YYYY" id="b-fecha_ejecutada-hasta" name="b-fecha_ejecutada-hasta" placeholder="dd/mm/yyyy"  />
                                        </div> 
                                  </div>
                            </div>
<div class="form-group">
                                  <label for="id_responsable" class="control-label">{N_ID_RESPONSABLE}</label>
                                  
                                    <input type="text" class="form-control" id="b-id_responsable" name="b-id_responsable" placeholder="{N_ID_RESPONSABLE}"/>
                                                             
                            </div>
                                    <input type="hidden" id="b-id_organizacion" name="b-id_organizacion"/>
