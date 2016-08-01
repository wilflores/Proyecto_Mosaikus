{OTROS_CAMPOS}
<!--<div class="form-group">
                                        <label for="id_cargo" class="col-md-4 control-label">{N_ID_CARGO}</label>
                                        <div class="col-md-10">

                                        <input type="text" class="form-control" value="{ID_CARGO}" id="id_cargo" readonly="true"name="id_cargo" placeholder="{N_ID_CARGO}"  data-validation="required"/>
                                      </div>                                
                                  </div>
                                  -->
<!--<div class="form-group">
                                        <label for="id_requisito_items" class="col-md-4 control-label">{N_ID_REQUISITO_ITEMS}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{ID_REQUISITO_ITEMS}" id="id_requisito_items" name="id_requisito_items" placeholder="{N_ID_REQUISITO_ITEMS}"  data-validation="required"/>
                                      </div>                                
                                  </div>
-->
<!--pestañas para familias y sus requisitos asociados a los items-->
                                 <div class="form-group"> 
                                    <div class="col-md-24">
                                        <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">
                                            <li id="li1"><a href="#hv-red-2" data-toggle="tab" style="padding: 8px 32px;">General</a></li>
                                            <li id="li2"><a href="#hv-orange-2" data-toggle="tab" style="padding: 8px 32px;"id="tabs-form-reg" >ECF </a></li>
                                        </ul>
                                        <div id="my-tab-content" class="tab-content" style="padding: 45px 15px;">
                                            <div class="tab-pane active" id="hv-red-2">
                                                <div class="form-group"><label><strong>&nbsp;*Educacion/Experiencia</strong></label>
                                                <br>
                              <div class="col-md-10">      
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;</label>
                                        <label for="vigencia" class="col-md-10 control-label">Educación</label>
                                            
                              </div>
                              <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" onchange="CargaComboCargo(this.value)">
                                              <option  value="N">Enseñanza Universitaria</option>
                                              <option  value="S">Postgrado</option>
                                            </select>
                                          </div> 
                                          <br>
                                          <div class="col-md-10"></div>
                                           <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" >
                                              <option  value="N">Area Docente</option>
                                              <option  value="S">Estudios Profesionales</option>
                                            </select>
                                          </div> <br><div class="col-md-10"></div>
                                          <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" >
                                              <option  value="N">Aprobado</option>
                                              <option  value="S">No Aprobado</option>
                                            </select>
                                          </div><br>
                              <div class="col-md-10">      
                                 <label class="checkbox-inline" >
                                    <input type="checkbox" name="publico" id="publico" value="S" {CHECKED_PUBLICO}>   &nbsp;  </label><label for="vigencia" class="col-md-10 control-label">Experiencia</label>     
                                  </div>
                                  </div>    
                                  <br>
                                  <!--otro items y sus requisito-->
                                  <div class="form-group"><label><strong>&nbsp;*Salud Ocupacional</strong></label>
                                                <br>
                              <div class="col-md-10">      
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;</label>
                                        <label for="vigencia" class="col-md-10 control-label">Evaluación Médica</label>
                                            
                              </div><div class="col-md-10"></div>
                              <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" onchange="CargaComboCargo(this.value)">
                                              <option  value="N"> General</option>
                                              <option  value="S"> Alternativa</option>
                                            </select>
                                            </div><br><div class="col-md-10"></div>
                                            <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" >
                                              <option  value="N">Enfermedades Neurológicas</option>
                                              <option  value="S">Patologías</option>
                                            </select>
                                          </div> <br><div class="col-md-10"></div>
                                          <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" >
                                              <option  value="N">SI</option>
                                              <option  value="S">NO</option>
                                            </select>
                                          </div> 
                                          </div> 
                                          <br>
                              <div class="col-md-10">      
                                 <label class="checkbox-inline" >
                                    <input type="checkbox" name="publico" id="publico" value="S" {CHECKED_PUBLICO}>   &nbsp;  </label><label for="vigencia" class="col-md-10 control-label">Sicosensotécnico</label>     
                                  </div>    
                                  </div>

        
<!--fin de primera pestaña.-->
<div class="tab-pane active" id="hv-orange-2">
                                                <div class="form-group"><label><strong>&nbsp;*Educacion/Experiencia</strong></label>
                                                <br>
                              <div class="col-md-10">      
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;</label>
                                        <label for="vigencia" class="col-md-10 control-label">Educación</label>
                                            
                              </div>
                              <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" onchange="CargaComboCargo(this.value)">
                                              <option  value="N">No</option>
                                              <option  value="S">SI</option>
                                            </select>
                                          </div> 
                                          
                                          <br>
                              <div class="col-md-10">      
                                 <label class="checkbox-inline" >
                                    <input type="checkbox" name="publico" id="publico" value="S" {CHECKED_PUBLICO}>   &nbsp;  </label><label for="vigencia" class="col-md-10 control-label">Experiencia</label>     
                                  </div>    
                                  </div>
                                  <!--otro items y sus requisito-->
                                  <div class="form-group"><label><strong>&nbsp;*Salud Ocupacional</strong></label>
                                                <br>
                              <div class="col-md-10">      
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;</label>
                                        <label for="vigencia" class="col-md-10 control-label">Evaluación Médica</label>
                                            
                              </div>
                              <div class="col-md-10">
                                            <select id="requiere_lista_distribucion" name="requiere_lista_distribucion" class="form-control" data-validation="required" onchange="CargaComboCargo(this.value)">
                                              <option  value="N">No</option>
                                              <option  value="S">SI</option>
                                            </select>
                                          </div> 
                                          <hr>
                                          <br>
                              <div class="col-md-10">      
                                 <label class="checkbox-inline" >
                                    <input type="checkbox" name="publico" id="publico" value="S" {CHECKED_PUBLICO}>   &nbsp;  </label><label for="vigencia" class="col-md-10 control-label">Siconemotécnico</label>     
                                  </div>    
                                  </div>

        </div>
<!--fin de segunda pestaña.-->
        </div></div></div></div>

