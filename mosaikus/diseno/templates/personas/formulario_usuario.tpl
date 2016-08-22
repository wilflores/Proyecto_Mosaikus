
                        <div class="row">
                               <div class="form-group">                                
                                <div class="row">
                                    <div class="form-group col-xs-8">
                                        <label for="id_personal" class="">{N_ID_PERSONAL}</label>
                                        <input type="checkbox" style="display: none;" name="interno" id="interno" value="1" {CHECKED_INTERNO}>                                         
                                        <input readonly type="text"value="{ID_PERSONAL}" class="form-control" id="id_personal" name="id_personal" placeholder="{P_ID_PERSONAL}" data-validation="required"/>  <!--onblur="fomatear_rut(this.value);"-->
                                    </div>    
                                    <div class="form-group col-xs-8">
                                        <label for="nombres" class="">{N_NOMBRES}</label>                                        
                                        <input readonly type="text" class="form-control" value="{NOMBRES}" id="nombres" name="nombres" placeholder="{P_NOMBRES}" data-validation="required sololetras"/>
                                    </div>  
                                </div>
                                </div> 
                            <div class="form-group"> 
                                <div class="row">

                                    <div class="form-group col-xs-8">
                                        <label for="apellido_paterno" class="">{N_APELLIDO_PATERNO}</label>

                                        <input readonly type="text" class="form-control" value="{APELLIDO_PATERNO}" id="apellido_paterno" name="apellido_paterno" placeholder="{P_APELLIDO_PATERNO}" data-validation="required sololetras"/>
                                    </div>                                
                                    <div class="form-group col-xs-8">
                                        <label for="apellido_materno" class="">{N_APELLIDO_MATERNO}</label>                                        
                                        <input readonly type="text" class="form-control"  value="{APELLIDO_MATERNO}" id="apellido_materno" name="apellido_materno" placeholder="{P_APELLIDO_MATERNO}" data-validation="sololetras"/>
                                    </div>  
                                </div>
                            </div> 
                                    
                        <div class="form-group">
                            <div class="row" style="display: block;">
                                <div class="col-xs-10" id="arbol1" style="display: block;">
                                    {DIV_ARBOL_ORGANIZACIONAL}
                                    <input type="hidden" id="cargar_cargo" name="cargar_cargo" value="{CARGAR_CARGO}">
                                </div>

                            </div>
                        </div>
                        <div class="form-group">     
                             <div class="row">
                                 <div class="col-xs-10">
                                    <label for="genero" >{N_COD_CARGO}</label>
                                        <select disabled id="cod_cargo" class="form-control" data-validation="required" name="cod_cargo">
                                            <option value="">{OPCION_CARGO_VACIO}</option>
                                           {CARGOS} 
                                        </select>                                                                                                                              
                                    </div> 
                                </div>
                        </div>
                                    
                            <div class="form-group">
                                <div class="col-xs-24">
                                    <div >                                                                               
                                           {N_RELATOR}                                        
                                    </div>
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox" value="S" id="relator" name="relator" {CHECKED_RELATOR}>
                                            {N_RELATOR}
                                        </label>
                                    </div> 
                                    <div >                                                                               
                                           {N_GESTION_MODULO_DOCUMENTO}                                        
                                    </div>
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="elaboro" name="elaboro" {CHECKED_ELABORO}>
                                            {N_ELABORO}
                                        </label>
                                    </div> 
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="reviso" name="reviso" {CHECKED_REVISO}>
                                            {N_REVISO}
                                        </label>
                                    </div>                                     
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="aprobo" name="aprobo" {CHECKED_APROBO}>
                                            {N_APROBO}
                                        </label>
                                    </div>                                     
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="impresion_cc" name="impresion_cc" {CHECKED_IMPRESION_CC}>
                                            {N_IMPRESION_CC}
                                        </label>
                                    </div>                                     
                                    <div >                                                                               
                                           {N_WORKFLOW}                                        
                                    </div>
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="workflow" name="workflow" {CHECKED_WORKFLOW}>
                                            {N_WORKFLOW}
                                        </label>
                                    </div>
                                    <div >                                                                               
                                           {N_ACC_CO}                                        
                                    </div>
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="analisis_causa" name="analisis_causa" {CHECKED_ANALISIS_CAUSA}>
                                            {N_ANALISIS_CAUSA}
                                        </label>
                                    </div> 
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="verifica_eficacia" name="verifica_eficacia" {CHECKED_VERIFICA_EFICACIA}>
                                            {N_VERIFICA_EFICACIA}
                                        </label>
                                    </div>                                     
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="valida_acc_co" name="valida_acc_co" {CHECKED_VALIDA_ACC_CO}>
                                            {N_VALIDA_ACC_CO}
                                        </label>
                                    </div>                                          
                            </div>
                        </div>
                                                   
                        <div class="checkbox">

                                    </div>

                                                <input type="hidden" class="form-control" id="id_organizacion" name="id_organizacion" value="{ID_ORGANIZACION}" placeholder="Id Organizacion"  data-validation="required"/>
                                

