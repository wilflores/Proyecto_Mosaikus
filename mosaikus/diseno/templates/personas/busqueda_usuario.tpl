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
                            <div class="row">
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
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_ANALISIS_CAUSA}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-analisis-causa" name="b-analisis-causa">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-analisis-causa" name="b-analisis-causa">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-analisis-causa" name="b-analisis-causa" checked="checked">  {N_TODOS}
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_VERIFICA_EFICACIA}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-verifica-eficacia" name="b-verifica-eficacia">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-verifica-eficacia" name="b-verifica-eficacia">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-verifica-eficacia" name="b-verifica-eficacia" checked="checked">  {N_TODOS} 
                                        </label>                               
                                    </div> 
                                </div>
                            </div>                                                           
                            <div class="row">
                                <div class="col-xs-14">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_VALIDA_ACC_CO}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-valida-acc-co" name="b-valida-acc-co">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-valida-acc-co" name="b-valida-acc-co">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-valida-acc-co" name="b-valida-acc-co" checked="checked">  {N_TODOS} 
                                        </label>                               
                                    </div> 
                                </div>
                                <div class="col-xs-10">
                                    <div class="form-group">
                                        <label for="vigencia" class="col-md-24 control-label">{N_IMPRESION_CC}</label>                                                                                                           
                                    </div>
                                    <div class="form-group">                                  
                                        <label class="checkbox-inline" style="padding-top: 0px;">
                                            <input type="radio" value="S" id="b-impresion-cc" name="b-impresion-cc">  {N_SI}<br>
                                            <input type="radio" value="N" id="b-impresion-cc" name="b-impresion-cc">  {N_NO} <br>
                                            <input type="radio" value=""  id="b-impresion-cc" name="b-impresion-cc" checked="checked">  {N_TODOS}
                                        </label>                               
                                    </div> 
                                </div>
                            </div>                                                           
                                  <input type="hidden" value="1" id="usuario-asig" name="usuario-asig">
                                  <input type="hidden" value="1" id="b-interno" name="b-interno">                                                                                
                                  <input type="hidden" id="b-id_organizacion" name="b-id_organizacion"/>
     <!--
     </div>
    <div class="tab-pane active" id="orange">
       <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/personas/emb_jstree_single.php"></iframe>
       
    </div>
</div>-->
