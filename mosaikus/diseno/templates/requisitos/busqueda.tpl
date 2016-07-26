<div class="form-group">
                                  <label for="nombre" class="control-label">{N_NOMBRE}</label>
                                  
                                    <input type="text" class="form-control" id="b-nombre" name="b-nombre" placeholder="{N_NOMBRE}" />
                                                          
                            </div>
<div class="form-group">
                                  <label for="tipo" class="control-label">{N_TIPO}</label>
                                 <select id="b-tipo" name="b-tipo" class="form-control" placeholder="{N_TIPO}">
                                          <option>Listado</option>
                                          <option>Unico</option>
                                        </select>
                                 <!--  <input  class="form-control" id="b-tipo" name="b-tipo" placeholder="{N_TIPO}" />-->
                                                          
                            </div>
<div class="form-group">
                                  <label for="vigencia" class="control-label">{N_VIGENCIA}</label>
                                    <select id="b-vigencia" name="b-vigencia" class="form-control" placeholder="{N_VIGENCIA}">
                                          <option value="S">Si</option>
                                          <option value="N">No</option>
                                        </select>
                                                          
                            </div>
<div class="form-group">
                                  <label for="estatus" class="control-label">{N_ESTATUS}</label>
                                   <select id="b-estatus" name="b-estatus" class="form-control" placeholder="{N_TIPO}" data-validation="required">
                                          <option value="1">Activo</option>
                                          <option value="0">Inactivo</option>
                                        </select>
                                    <!--<input type="text" class="form-control" id="b-estatus" name="b-estatus" placeholder="{N_ESTATUS}"/>-->
                                                             
                            </div>
<div class="form-group">
                                  <label for="orden" class="control-label">{N_ORDEN}</label>
                                  
                                    <input type="text" class="form-control" id="b-orden" name="b-orden" placeholder="{N_ORDEN}"/>
                                                             
                            </div>
 <input type="hidden" id="b-id_organizacion" name="b-id_organizacion"/>