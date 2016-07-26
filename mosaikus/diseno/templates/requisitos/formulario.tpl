<div class="form-group">
                                        <label for="tipo" class="col-md-4 control-label">{N_TIPO}</label>
                                        <div class="col-md-10">
                                        <select id="tipo" name="tipo" class="form-control" placeholder="{N_TIPO}" data-validation="required">
                                          <option>Listado</option>
                                          <option>Unico</option>
                                        </select>
                                          <!--<input type="text" class="form-control" value="{TIPO}" id="tipo" name="tipo" placeholder="{N_TIPO}" data-validation="required"/>-->
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="nombre" class="col-md-4 control-label">{N_NOMBRE}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{NOMBRE}" id="nombre" name="nombre" placeholder="{N_NOMBRE}" data-validation="required"/>
                                      </div>                                
                                  </div>

<div class="form-group">
                                        <label for="orden" class="col-md-4 control-label">{N_ORDEN}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{ORDEN}" id="orden" name="orden" placeholder="{N_ORDEN}"  data-validation="required"/>
                                      </div>                                
                                  </div>
{OTROS_CAMPOS}
                         
<div class="form-group">
                                        <label for="vigencia" class="col-md-4 control-label">{N_VIGENCIA}</label>
                                        <div class="col-md-10">
                                          <input type="checkbox" checked class="form-control" value="S" id="vigencia" name="vigencia" placeholder="{N_VIGENCIA}" />
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="estatus" class="col-md-4 control-label">{N_ESTATUS}</label>
                                        <div class="col-md-10">
                                          <input type="checkbox" checked class="form-control" value="1" id="estatus" name="estatus" placeholder="{N_ESTATUS}"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                  <div class="col-md-9" >
                                         <input type="hidden" value="{NODOS}" name="nodos" id="nodos"/>
                                        {DIV_ARBOL_ORGANIZACIONAL}
                                        <input type="hidden" name="nodo_area" id="nodo_area"/>
                                    </div></div>

