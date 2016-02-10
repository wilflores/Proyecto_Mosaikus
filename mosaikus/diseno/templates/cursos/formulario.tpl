<div class="form-group">
                                        <label for="cod_curso" class="col-md-4 control-label">{N_COD_CURSO}</label>
                                        <div class="col-md-4">
                                          <input type="text" class="form-control" value="{COD_CURSO}" readonly="true" id="cod_curso" name="cod_curso" placeholder="{P_COD_CURSO}" />
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="identificacion" class="col-md-4 control-label">{N_IDENTIFICACION}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{IDENTIFICACION}" id="identificacion" name="identificacion" placeholder="{P_IDENTIFICACION}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION}</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="3" id="descripcion" name="descripcion" data-validation="required">{DESCRIPCION}</textarea>                                                                                    
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="cod_clase" class="col-md-4 control-label">{N_COD_CLASE}</label>
                                        <div class="col-md-5">
                                            <select id="cod_clase" class="form-control" data-validation="required" name="cod_clase">
                                                <option selected="" value="">Seleccione</option>
                                                {CLASES}
                                            </select>
                                                                                      
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="cod_tipo" class="col-md-4 control-label">{N_COD_TIPO}</label>
                                        <div class="col-md-5">
                                            <select id="cod_tipo" class="form-control" data-validation="required" name="cod_tipo">
                                                <option selected="" value="">Seleccione</option>
                                                {TIPOS}
                                            </select>                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                  <label for="vigencia" class="col-md-4 control-label">{N_VIGENCIA}</label>
                                  <div class="col-md-10">
                                    
                                    <label class="checkbox-inline" style="padding-top: 0px;">
                                          <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;
                                      </label>
                                </div> 
                                  </div> 
<div class="form-group">
                                        <label for="aplica_evaluacion" class="col-md-4 control-label">{N_APLICA_EVALUACION}</label>
                                        <div class="col-md-10">                                          
                                            <label class="checkbox-inline" style="padding-top: 0px;">
                                                <input type="checkbox" name="aplica_evaluacion" id="aplica_evaluacion" value="S" {CHECKED_APLICA_EVALUACION}>   &nbsp;
                                            </label>                                            
                                      </div>                                
                                  </div>
