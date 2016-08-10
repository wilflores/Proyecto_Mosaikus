<!--    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#red" data-toggle="tab">Datos Basicos</a></li>
        <li><a href="#orange" data-toggle="tab">Árbol organizacional</a></li>        
    </ul>

<div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="red">-->
                            
                                <div class="form-group">
                                
                                <label for="id_personal">{N_ID_PERSONAL}</label>
                                
                                    <input type="text" class="form-control" id="b-id_personal" name="b-id_personal" placeholder="{P_ID_PERSONAL}" />
                                </div>      
                            
                                <div class="form-group">
                            
                                  <label for="nombres" >{N_NOMBRES}</label>
                                  
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
                                <div class="form-group">
                                    <label for="cod_cargo" class="control-label">{N_COD_CARGO}</label>
                                  
                                    <input type="text" class="form-control" id="b-cod_cargo" name="b-cod_cargo" placeholder="{P_COD_CARGO}"/>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-2 control-label">{N_EMAIL}</label>                                  
                                    <input type="text" class="form-control" id="b-email" name="b-email" placeholder="{P_EMAIL}" />
                                </div>  
                            <div class="form-group" {DISPLAY_FECHA_NACIMIENTO}>
                                <label for="fecha" class="control-label">{N_FECHA_NACIMIENTO}</label>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label for="exampleInputPassword1">{N_DESDE}</label>
                                        <input type="text" class="form-control clsDatePicker" data-date-format="DD/MM/YYYY" id="b-fecha_nacimiento-desde" name="b-fecha_nacimiento-desde" placeholder="dd/mm/yyyy"  />
                                    </div>   
                                    <div class="col-xs-12">
                                        <label for="exampleInputPassword1">{N_HASTA}</label> 
                                        <input type="text" class="form-control clsDatePicker" data-date-format="DD/MM/YYYY" id="b-fecha_nacimiento-hasta" name="b-fecha_nacimiento-hasta" placeholder="dd/mm/yyyy"  />
                                    </div>                                
                                </div>
                            </div>
                            <div class="form-group" {DISPLAY_GENERO}>
                                  <label for="vigencia" class="col-md-24 control-label">{N_GENERO}</label>                                                                                                           
                            </div>
                            <div class="form-group" {DISPLAY_GENERO}>                                  
                                    <label class="checkbox-inline" style="padding-top: 0px;">
                                         <input type="radio" id="b-genero" name="b-genero" value="1">   {N_MASCULINO}<br>
                                          <input type="radio" id="b-genero" name="b-genero" value="2">  {N_FEMENINO} <br>
                                             <input type="radio" id="b-genero" name="b-genero" value="" checked="checked">  {N_TODOS} &nbsp;
                                    </label>                               
                            </div>                                   
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_VIGENCIA}</label>                                                                                                           
                                        
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-vigencia" name="b-vigencia">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-vigencia" name="b-vigencia">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-vigencia" name="b-vigencia" checked="checked">  {N_TODOS} 
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_WORKFLOW}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-workflow" name="b-workflow">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-workflow" name="b-workflow">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-workflow" name="b-workflow" checked="checked">  {N_TODOS} 
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
                                            <input type="radio" value="S" id="b-relator" name="b-relator">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-relator" name="b-relator">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-relator" name="b-relator" checked="checked">  {N_TODOS}
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_REVISO}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-reviso" name="b-reviso">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-reviso" name="b-reviso">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-reviso" name="b-reviso" checked="checked">  {N_TODOS} 
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
                                            <input type="radio" value="S" id="b-elaboro" name="b-elaboro">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-elaboro" name="b-elaboro">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-elaboro" name="b-elaboro" checked="checked">  {N_TODOS}
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_APROBO}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-aprobo" name="b-aprobo">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-aprobo" name="b-aprobo">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-aprobo" name="b-aprobo" checked="checked">  {N_TODOS} 
                                        </label>                               
                                    </div> 
                                </div>
                            </div>                                                           
                                  <input type="hidden" value="1" id="b-interno" name="b-interno">                                                                                
                                                        
                            <!--<div class="form-group">                                                             
                            
                                <label for="extranjero" class="col-md-24 control-label">{N_EXTRANJERO}</label>
                            </div>
                            <div class="form-group">                                    
                                    <label class="checkbox-inline" style="padding-top: 0px;">
                                        <input type="radio" value="SI" id="b-extranjero" name="b-extranjero"> Si &nbsp;
                                      
                                          <input type="radio" value="NO" id="b-extranjero" name="b-extranjero"> No &nbsp;
                                          <input type="radio" value="" id="b-extranjero" name="b-extranjero" checked="checked"> Todos
                                      </label>
                                <input type="hidden" id="b-id_organizacion" name="b-id_organizacion"/>
                            </div>-->
                            <div class="form-group">                                                             
                            
                                <label for="extranjero" class="col-md-24 control-label">{N_RESPONSABLE_AREA}</label>
                            </div>
                            <div class="form-group">                                    
                                    <label class="checkbox-inline" style="padding-top: 0px;">
                                        <input type="radio" value="S" id="b-responsable_area" name="b-responsable_area"> {N_SI} &nbsp;
                                      
                                          <input type="radio" value="N" id="b-responsable_area" name="b-responsable_area"> {N_NO} &nbsp;
                                          <input type="radio" value="" id="b-responsable_area" name="b-responsable_area" checked="checked"> {N_TODOS}
                                      </label>
                                <input type="hidden" id="b-id_organizacion" name="b-id_organizacion"/>
                            </div>
     <!--
     </div>
    <div class="tab-pane active" id="orange">
       <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/personas/emb_jstree_single.php"></iframe>
       
    </div>
</div>-->
