<div class="form-group">
                                        <label for="email" class="col-md-4 control-label">{N_EMAIL}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{EMAIL}" id="email" name="email" placeholder="{N_EMAIL}" data-validation="required email" data-provide="typeahead" autocomplete="off"/>                                          
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="nombres" class="col-md-4 control-label">{N_NOMBRES}</label>
                                        <div class="col-md-10">
                                          <input type="hidden" class="form-control" value="{ID_USUARIO}" id="id_usuario" name="id_usuario" placeholder="{N_ID_USUARIO}"   />
                                          <input type="text" class="form-control" value="{NOMBRES}" id="nombres" name="nombres" placeholder="{N_NOMBRES}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="apellido_paterno" class="col-md-4 control-label">{N_APELLIDO_PATERNO}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{APELLIDO_PATERNO}" id="apellido_paterno" name="apellido_paterno" placeholder="{N_APELLIDO_PATERNO}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="apellido_materno" class="col-md-4 control-label">{N_APELLIDO_MATERNO}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{APELLIDO_MATERNO}" id="apellido_materno" name="apellido_materno" placeholder="{N_APELLIDO_MATERNO}" />
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="cedula" class="col-md-4 control-label">{N_CEDULA}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{CEDULA}" id="cedula" name="cedula" placeholder="{N_CEDULA}"/>
                                      </div>                                
                                  </div>

                                      <div class="form-group">
                                        <label for="telefono" class="col-md-4 control-label">{N_TELEFONO}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{TELEFONO}" id="telefono" name="telefono" placeholder="{N_TELEFONO}" />
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="vigencia" class="col-md-4 control-label">{N_VIGENCIA}</label>
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                        <div class="col-md-10">
                                          <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>                                            
                                      </div>                                
                                        </label>
                                  </div>
<div class="form-group">
                                        <label for="fecha_expi" class="col-md-4 control-label">{N_FECHA_EXPI}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" style="width: 120px;" data-date-format="DD/MM/YYYY" value="{FECHA_EXPI}" id="fecha_expi" name="fecha_expi" placeholder="dd/mm/yyyy"  data-validation="required"/>
                                      </div>                                
                                  </div>

                                      <div class="form-group">
                                        <label for="super_usuario" class="col-md-4 control-label">{N_SUPER_USUARIO}</label>
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                        <div class="col-md-10">
                                         <input type="checkbox" name="super_usuario" id="super_usuario" value="S" {READ_SUPER_USUARIO} {SUPER_USUARIO}>                                             
                                      </div>
                                        </label>
                                  </div>
   
                                      <div class="form-group">
                                        <label for="recibe_notificaciones" class="col-md-4 control-label">{N_RECIBE_NOTIFICACIONES}</label>
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                        <div class="col-md-10">
                                         <input type="checkbox" name="recibe_notificaciones" id="recibe_notificaciones" value="S" {RECIBE_NOTIFICACIONES}>                                             
                                      </div>
                                        </label>
                                  </div>                                      

<div class="form-group">
                                        <label for="password_1" class="col-md-4 control-label">{N_PASSWORD_1}</label>
                                        <div class="col-md-10">
                                          <input type="password" class="form-control" value="{PASSWORD_1}" id="password_1" name="password_1" placeholder="{N_PASSWORD_1}" data-validation="required"/>
                                      </div>                                
                                  </div>
