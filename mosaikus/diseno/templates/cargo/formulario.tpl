<!--<div class="form-group">
                                        <label for="cod_cargo" class="col-md-2 control-label">Cod Cargo</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{COD_CARGO}" id="cod_cargo" name="cod_cargo" placeholder="Cod Cargo"  data-validation="required"/>
                                      </div>                                
                                  </div>-->
<div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{DESCRIPCION}" id="descripcion" name="descripcion" placeholder="{P_DESCRIPCION}" data-validation="required"/>
                                      </div>                                
                                  </div>
<!--<div class="form-group">
                                        <label for="observacion" class="col-md-2 control-label">Observacion</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{OBSERVACION}" id="observacion" name="observacion" placeholder="Observacion"  data-validation="required"/>
                                      </div>                                
                                  </div>-->
<div class="form-group">
                                        <label for="interno" class="col-md-4 control-label">{N_INTERNO}</label>
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                          <input type="checkbox" name="interno" id="interno" value="1" {CHECKED_INTERNO}>         &nbsp;       
                                      </label>                               
                                  </div>
<div class="form-group">
                                        <label for="vigencia" class="col-md-4 control-label">{N_VIGENCIA}</label>
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                          <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;
                                      </label>                              
                                  </div>
                                <div class="form-group">
                                    
                                    <div class="col-md-10">
                                        <label for="vigencia" class="col-md-10 control-label" style="text-align: left;">Árbol organizacional</label>
                                        <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
                                        <iframe id="iframearbol" src="pages/cargo/prueba_arbolV4.php?cod_cargo={COD_CARGO}" frameborder="0" width="100%" height="350px" scrolling="no"></iframe>
                                    </div>
                                    <div class="col-md-12">
                                        
                                                {OTROS_CAMPOS}
                                        
                                    </div>
                                </div>
