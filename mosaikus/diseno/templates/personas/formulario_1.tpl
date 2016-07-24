                            <!--<div class="form-group">
                                  <label for="interno" class="col-md-2 control-label"> {N_INTERNO} </label>
                                  <div class="col-md-2">      
                                      <label class="checkbox-inline" style="padding-top: 0px;">
                                                  &nbsp;       
                                      </label>
                                  </div>
                                  
                            </div>-->
                                <div class="form-group">
                                        <label for="id_personal" class="col-md-4 control-label">{N_ID_PERSONAL}</label>
                                        <input type="checkbox" style="display: none;" name="interno" id="interno" value="1" {CHECKED_INTERNO}> 
                                        <div class="col-md-6">
                                            <input type="text" onblur="fomatear_rut(this.value);" value="{ID_PERSONAL}" class="form-control" id="id_personal" name="id_personal" placeholder="{P_ID_PERSONAL}" data-validation="required rut" style="width: 140px;"/>
                                      </div>    
                                      
                                    <label for="extranjero" class="col-md-2 control-label">{N_EXTRANJERO}  </label>
                                    <div class="col-md-6">                       
                                        <label class="radio-inline" style="">
                                            <input type="radio" id="extranjero" value="SI" id="extranjero" name="extranjero" {CHECKED_EXT_SI}> Si
                                        </label>
                                        <label class="radio-inline" style="">
                                            <input type="radio" id="extranjero" value="NO" id="extranjero" name="extranjero" {CHECKED_EXT_NO}> No
                                        </label>                    
                                    </div>
                                      <label for="vigencia" class="col-md-2 control-label"> {N_VIGENCIA} </label>  
                                      <div class="col-md-4">      
                                        <label class="checkbox-inline" style="">
                                            <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>   &nbsp;
                                        </label>
                                        </div>
                                  
                                </div>
                                <div class="form-group">
                                        <label for="nombres" class="col-md-4 control-label">{N_NOMBRES}</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" value="{NOMBRES}" id="nombres" name="nombres" placeholder="{P_NOMBRES}" data-validation="required sololetras"/>
                                      </div>                                
                                  
                                        <label for="apellido_paterno" class="col-md-4 control-label">{N_APELLIDO_PATERNO}</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" value="{APELLIDO_PATERNO}" id="apellido_paterno" name="apellido_paterno" placeholder="{P_APELLIDO_PATERNO}" data-validation="required sololetras"/>
                                      </div>                                
                                        <label for="apellido_materno" class="col-md-4 control-label">{N_APELLIDO_MATERNO}</label>
                                        <div class="col-md-4">
                                          <input type="text" class="form-control"  value="{APELLIDO_MATERNO}" id="apellido_materno" name="apellido_materno" placeholder="{P_APELLIDO_MATERNO}" data-validation="sololetras"/>
                                      </div>                                
                                  </div>
                                  <div class="form-group" {DISPLAY_FECHA_GENERO}>
                                    <label for="fecha_nacimiento" {DISPLAY_FECHA_NACIMIENTO} class="col-md-4 control-label">{N_FECHA_NACIMIENTO}</label>
                                        <div class="col-md-6" {DISPLAY_FECHA_NACIMIENTO}>
                                            <input style="width: 120px;" type="text" data-date-format="DD/MM/YYYY"  class="form-control" value="{FECHA_NACIMIENTO}" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="{P_FECHA_NACIMIENTO}" {VALIDACION_FECHA_NACIMIENTO}/>
                                      </div> 
                                            <label for="genero" class="col-md-2 control-label" {DISPLAY_FECHA_NACIMIENTO_ENTRE}>&nbsp;</label>
                                        <label for="genero" class="col-md-2 control-label" {DISPLAY_GENERO}>{N_GENERO}</label>
                                        <div class="col-md-6" {DISPLAY_GENERO}>                                            
                                                {GENERO}                                                                                      
                                      </div>                                                                                                                                                                                     
                                  </div>
                                  <div class="form-group">
                                        <label for="workflow" class="col-md-4 control-label">{N_WORKFLOW}</label>
                                        
                                        <div class="col-md-4">
                                            <label class="checkbox-inline" style="">
                                                <input type="checkbox"  value="S" id="workflow" name="workflow" {CHECKED_WORKFLOW}>   &nbsp;
                                            </label>        
                                        </div>                                
                                        <label for="email" class="col-md-4 control-label">{N_EMAIL}</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" value="{EMAIL}" id="email" name="email" placeholder="{P_EMAIL}"/> <!--data-validation="email"--> 
                                        </div> 
                                  </div>

                                  <div class="form-group">
                                      <label for="relator" class="col-md-4 control-label">{N_RELATOR}</label>
                                      <div class="col-md-4">      
                                            <label class="checkbox-inline" style="">
                                                <input type="checkbox" value="S" id="relator" name="relator" {CHECKED_RELATOR}>   &nbsp;
                                            </label>
                                        </div>
                                        <label for="reviso" class="col-md-2 control-label">{N_REVISO}</label>
                                        <div class="col-md-4">
                                            <label class="checkbox-inline" style="">
                                                <input type="checkbox"  value="S" id="reviso" name="reviso" {CHECKED_REVISO}>   &nbsp;
                                            </label>                                           
                                      </div>
                                        <label for="elaboro" class="col-md-2 control-label">{N_ELABORO}</label>
                                        <div class="col-md-4">
                                             <label class="checkbox-inline" style="">
                                                <input type="checkbox"  value="S" id="elaboro" name="elaboro" {CHECKED_ELABORO}>   &nbsp;
                                            </label>                                            
                                      </div>   
                                        <label for="aprobo" class="col-md-2 control-label">{N_APROBO}</label>
                                        <div class="col-md-2">
                                            <label class="checkbox-inline" style="">
                                                <input type="checkbox"  value="S" id="aprobo" name="aprobo" {CHECKED_APROBO}>   &nbsp;
                                            </label> 
                                          
                                      </div> 
                                  </div>
                                  
                                <div class="form-group">
                                    
                                    <div class="col-md-10">
                                        
                                        <iframe id="iframearbol" src="pages/personas/emb_jstree_single_persona.php?id={ID_ORGANIZACION}&cod_cargo={COD_CARGO}" frameborder="0" width="100%" height="350px" scrolling="no"></iframe>
                                    </div>
                                    <div class="col-md-12">
                                         <div class="form-group">
                                              <label for="fecha_ingreso" {DISPLAY_FECHA_INGRESO} class="col-md-4 control-label">{N_FECHA_INGRESO}</label>
                                                <div class="col-md-10" {DISPLAY_FECHA_INGRESO}>
                                                    <input type="text" data-date-format="DD/MM/YYYY" class="form-control" value="{FECHA_INGRESO}" id="fecha_ingreso" name="fecha_ingreso" placeholder="{P_FECHA_INGRESO}" {VALIDACION_FECHA_NACIMIENTO}/>
                                              </div> 
                                              <label for="fecha_ingreso" {DISPLAY_FECHA_EGRESO} class="col-md-4 control-label">{N_FECHA_EGRESO}</label>
                                                <div class="col-md-10" {DISPLAY_FECHA_EGRESO}>
                                                    <input type="text" data-date-format="DD/MM/YYYY"  {DISPLAY_FECHA_EGRESO} class="form-control" value="{FECHA_EGRESO}" id="fecha_egreso" name="fecha_egreso" placeholder="{P_FECHA_EGRESO}"/>
                                              </div> 
                                         </div>
                                                
                                         {OTROS_CAMPOS}
                                        
                                    </div>
                                </div>
                                <div class="form-group">                                            
                                            <label for="genero" class="col-md-2 control-label">{N_COD_CARGO}</label>
                                            <div class="col-md-10">  
                                                <select id="cod_cargo" class="form-control" data-validation="required" name="cod_cargo">
                                                    <option value="">{OPCION_CARGO_VACIO}</option>
                                                   {CARGOS} 
                                                </select>                                                                                                                              
                                            </div>   
                                        </div>

                                                <input type="hidden" class="form-control" id="id_organizacion" name="id_organizacion" value="{ID_ORGANIZACION}" placeholder="Id Organizacion"  data-validation="required"/>
                                

