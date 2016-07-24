
                            <div class="form-group">
                                <label for="id_personal" class="control-label">{N_ID_PERSONAL}</label>
                                
                                    <input type="text" class="form-control" id="b-id_personal" name="b-id_personal" placeholder="{P_ID_PERSONAL}" />
                                </div>                                
                            <div class="form-group">
                                  <label for="nombres" class="col-md-2 control-label">{N_NOMBRES}</label>
                                  
                                    <input type="text" class="form-control" id="b-nombres" name="b-nombres" placeholder="{P_ID_PERSONAL}" />
                                </div>                                
                            
                            <div class="form-group">
                                  <label for="apellido_paterno">{N_APELLIDO_PATERNO}</label>
                                 
                                    <input type="text" class="form-control" id="b-apellido_paterno" name="b-apellido_paterno" placeholder="{P_APELLIDO_PATERNO}" />
                                </div>                                                           
                                 <div class="form-group">
                                  <label for="apellido_materno">{N_APELLIDO_MATERNO}</label>
                                 
                                    <input type="text" class="form-control" id="b-apellido_materno" name="b-apellido_materno" placeholder="{P_APELLIDO_MATERNO}" />
                                </div>
                            <div class="form-group" {DISPLAY_FECHA_NACIMIENTO}>
                                <label for="fecha" class="control-label">{N_FECHA_NACIMIENTO}</label>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label for="exampleInputPassword1">Desde</label>
                                        <input type="text" class="form-control " data-date-format="DD/MM/YYYY" id="b-fecha_nacimiento-desde" name="b-fecha_nacimiento-desde" placeholder="dd/mm/yyyy"  />
                                    </div>   
                                    <div class="col-xs-12">
                                        <label for="exampleInputPassword1">Hasta</label> 
                                        <input type="text" class="form-control " data-date-format="DD/MM/YYYY" id="b-fecha_nacimiento-hasta" name="b-fecha_nacimiento-hasta" placeholder="dd/mm/yyyy"  />
                                    </div>                                
                                </div>
                            </div>
                            <div class="form-group" {DISPLAY_GENERO}>
                                  <label for="vigencia" class="col-md-24 control-label">{N_GENERO}</label>                                                                                                           
                            </div>
                            <div class="form-group" {DISPLAY_GENERO}>                                  
                                    <label class="checkbox-inline" style="padding-top: 0px;">
                                         <input type="radio" id="b-genero" name="b-genero" value="1">   Masculino<br>
                                          <input type="radio" id="b-genero" name="b-genero" value="2">  Femenino <br>
                                             <input type="radio" id="b-genero" name="b-genero" value="" checked="checked">  Todos &nbsp;
                                    </label>                               
                            </div> 
                            <div class="form-group">
                                <label for="cod_cargo" class="col-md-24 control-label">{N_COD_CONTRATISTA}</label>                                  
                                    <input type="text" class="form-control" id="b-cod_contratista" name="b-cod_contratista" placeholder="{P_COD_CONTRATISTA}"/>
                                </div>  
                             <div class="form-group">   
                                 <label for="email" class="col-md-2 control-label">{N_EMAIL}</label>
                                  
                                    <input type="text" class="form-control" id="b-email" name="b-email" placeholder="{P_EMAIL}" />
                                </div>  
                                                        
                                  <input type="hidden" value="0" id="b-interno" name="b-interno">
                                  <!--
                                  <label for="interno" class="col-md-2 control-label">{N_INTERNO}</label>
                                  <div class="col-md-4">                                    
                                    <label class="radio-inline" style="padding-top: 0px;">
                                          <input type="radio" value="1" id="b-interno" name="b-interno"> Si
                                      </label>
                                      <label class="radio-inline" style="padding-top: 0px;">
                                          <input type="radio" value="0" id="b-interno" name="b-interno"> No
                                      </label>
                                </div>
                                  -->
                            
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_VIGENCIA}</label>                                                                                                           
                                        
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-vigencia" name="b-vigencia">  Si<br>
                                            <input type="radio" value="N" id="b-vigencia" name="b-vigencia">  No <br>
                                            <input type="radio" value=""  id="b-vigencia" name="b-vigencia" checked="checked">  Todos 
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_WORKFLOW}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-workflow" name="b-workflow">  Si<br>
                                            <input type="radio" value="N" id="b-workflow" name="b-workflow">  No <br>
                                            <input type="radio" value=""  id="b-workflow" name="b-workflow" checked="checked">  Todos 
                                        </label>                               
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_RELATOR}</label>                                                                                                           
                                        
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-relator" name="b-relator">  Si<br>
                                            <input type="radio" value="N" id="b-relator" name="b-relator">  No <br>
                                            <input type="radio" value=""  id="b-relator" name="b-relator" checked="checked">  Todos
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_REVISO}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-reviso" name="b-reviso">  Si<br>
                                            <input type="radio" value="N" id="b-reviso" name="b-reviso">  No <br>
                                            <input type="radio" value=""  id="b-reviso" name="b-reviso" checked="checked">  Todos 
                                        </label>                               
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_ELABORO}</label>                                                                                                           
                                        
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-elaboro" name="b-elaboro">  Si<br>
                                            <input type="radio" value="N" id="b-elaboro" name="b-elaboro">  No <br>
                                            <input type="radio" value=""  id="b-elaboro" name="b-elaboro" checked="checked">  Todos
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_APROBO}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-aprobo" name="b-aprobo">  Si<br>
                                            <input type="radio" value="N" id="b-aprobo" name="b-aprobo">  No <br>
                                            <input type="radio" value=""  id="b-aprobo" name="b-aprobo" checked="checked">  Todos 
                                        </label>                               
                                    </div> 
                                </div>
                            </div>                                                           
                                                                                                           
                                                        
                            <div class="form-group">                                                             
                            
                                <label for="extranjero" class="col-md-24 control-label">{N_EXTRANJERO}</label>
                            </div>
                            <div class="form-group">                                    
                                    <label class="checkbox-inline" style="padding-top: 0px;">
                                        <input type="radio" value="SI" id="b-extranjero" name="b-extranjero"> Si &nbsp;
                                      
                                          <input type="radio" value="NO" id="b-extranjero" name="b-extranjero"> No &nbsp;
                                          <input type="radio" value="" id="b-extranjero" name="b-extranjero" checked="checked"> Todos
                                      </label>
                                
                            </div>
     
