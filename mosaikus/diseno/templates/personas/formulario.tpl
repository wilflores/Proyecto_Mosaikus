                            <!--<div class="form-group">
                                  <label for="interno" class="col-md-2 "> {N_INTERNO} </label>
                                  <div class="col-md-2">      
                                      <label class="checkbox-inline" style="padding-top: 0px;">
                                                  &nbsp;       
                                      </label>
                                  </div>
                                  
                            </div>-->
                        <div class="row">
                            <div class="col-xs-18">                                
                                <div class="row">
                                    <div class="form-group col-xs-8">
                                        <label for="id_personal" class="">{N_ID_PERSONAL}</label>
                                        <input type="checkbox" style="display: none;" name="interno" id="interno" value="1" {CHECKED_INTERNO}>                                         
                                        <input type="text"value="{ID_PERSONAL}" class="form-control" id="id_personal" name="id_personal" placeholder="{P_ID_PERSONAL}" data-validation="required"/>  <!--onblur="fomatear_rut(this.value);"-->
                                    </div>    
                                    <div class="form-group col-xs-{COL_NOM}">
                                        <label for="nombres" class="">{N_NOMBRES}</label>                                        
                                        <input type="text" class="form-control" value="{NOMBRES}" id="nombres" name="nombres" placeholder="{P_NOMBRES}" data-validation="required sololetras"/>
                                    </div>  
                                    <div class="form-group col-xs-8" {DISPLAY_FECHA_NACIMIENTO}>
                                        <label for="fecha_nacimiento" {DISPLAY_FECHA_NACIMIENTO} class="">{N_FECHA_NACIMIENTO}</label>

                                        <input style="" type="text" class="form-control" value="{FECHA_NACIMIENTO}" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="{P_FECHA_NACIMIENTO}" {VALIDACION_FECHA_NACIMIENTO}/>
                                    </div>                                                                                                                
                                </div>
                                
                                <div class="row">

                                    <div class="form-group col-xs-8">
                                        <label for="apellido_paterno" class="">{N_APELLIDO_PATERNO}</label>

                                        <input type="text" class="form-control" value="{APELLIDO_PATERNO}" id="apellido_paterno" name="apellido_paterno" placeholder="{P_APELLIDO_PATERNO}" data-validation="required sololetras"/>
                                    </div>                                
                                    <div class="form-group col-xs-{COL_MAT}">
                                        <label for="apellido_materno" class="">{N_APELLIDO_MATERNO}</label>                                        
                                        <input type="text" class="form-control"  value="{APELLIDO_MATERNO}" id="apellido_materno" name="apellido_materno" placeholder="{P_APELLIDO_MATERNO}" data-validation="sololetras"/>
                                    </div>  
                                    <div class="form-group col-xs-8" {DISPLAY_GENERO}>                                                                                        
                                        <label for="genero" class="" {DISPLAY_GENERO}>{N_GENERO}</label>                                        
                                        {GENERO}
                                    </div>
                                </div>
                               
                                
                               
                                    <div class="row">
                                                                      
                                        
                                        <div class="form-group col-xs-8" {DISPLAY_FECHA_INGRESO}>
                                            <label for="fecha_ingreso" {DISPLAY_FECHA_INGRESO} class="">{N_FECHA_INGRESO}</label>                                                
                                            <input type="text" class="form-control" value="{FECHA_INGRESO}" id="fecha_ingreso" name="fecha_ingreso" placeholder="{P_FECHA_INGRESO}" {VALIDACION_FECHA_NACIMIENTO}/>
                                        </div> 
                                        <div class="form-group col-xs-8" {DISPLAY_FECHA_EGRESO}>
                                              <label for="fecha_ingreso" {DISPLAY_FECHA_EGRESO} class="">{N_FECHA_EGRESO}</label>                                                
                                              <input type="text" {DISPLAY_FECHA_EGRESO} class="form-control" value="{FECHA_EGRESO}" id="fecha_egreso" name="fecha_egreso" placeholder="{P_FECHA_EGRESO}"/>
                                        </div> 
                                        <div class="form-group col-xs-{COL_EMA}">
                                            <label for="email" class="">{N_EMAIL}</label>                                        
                                            <input type="text" class="form-control" value="{EMAIL}" id="email" name="email" placeholder="{P_EMAIL}"/> <!--data-validation="email"--> 
                                        </div> 
                                    </div>
                                    
                                

                                  
                            </div>  
                            <div class="col-xs-6">
                                <div class="col-xs-24">
                                            <!--<label for="extranjero" class="">  </label>     
                                            <div class="radio">
                                            <label class="radio-inline" style="">
                                                <input type="radio" id="extranjero" value="SI" id="extranjero" name="extranjero" {CHECKED_EXT_SI}> Si
                                            </label>
                                            <label class="radio-inline" style="">
                                                <input type="radio" id="extranjero" value="NO" id="extranjero" name="extranjero" {CHECKED_EXT_NO}> No
                                            </label>                                                        
                                            </div>
                                        </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="extranjero" value="SI" id="extranjero" {CHECKED_EXT_SI}>
                                            {N_EXTRANJERO}
                                        </label>
                                    </div>-->
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="vigencia" id="vigencia" value="S" {CHECKED_VIGENCIA}>
                                            {N_VIGENCIA}
                                        </label>
                                    </div>  
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox"  value="S" id="workflow" name="workflow" {CHECKED_WORKFLOW}>
                                            {N_WORKFLOW}
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox" value="S" id="relator" name="relator" {CHECKED_RELATOR}>
                                            {N_RELATOR}
                                        </label>
                                    </div> 
                                    <div class="checkbox">
                                        <label>                                            
                                            <input type="checkbox" value="{PROMOVER_CARGO}" id="promover_cargo" name="promover_cargo" {CHECKED_PROMOVER_CARGO}>
                                            {N_PROMOVER_CARGO}
                                        </label>
                                    </div> 
                                    <div >                                                                               
                                           Gestión en Modulo de Documentos                                        
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
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-10" id="arbol1">

                                    {DIV_ARBOL_ORGANIZACIONAL}
                                    <input type="hidden" id="cargar_cargo" name="cargar_cargo" value="{CARGAR_CARGO}">
                                </div>
                                <div class="col-xs-10"  id="arbol2">
                                    <input type="hidden" value="{NODOS_RESPONSABLE}" name="nodos_responsable" id="nodos_responsable"/>
                                        {DIV_ARBOL_ORGANIZACIONAL_RESPONSABLE}
                                </div>

                            </div>
                        </div>
                        <div class="form-group">     
                             <div class="row">
                                 <div class="col-xs-10">
                                    <label for="genero" >{N_COD_CARGO}</label>
                                        <select id="cod_cargo" class="form-control" data-validation="required" name="cod_cargo">
                                            <option value="">{OPCION_CARGO_VACIO}</option>
                                           {CARGOS} 
                                        </select>                                                                                                                              
                                    </div> 
                                <div class="form-group col-xs-8" id="div_fecha_promocion" style="display:{MOSTRAR_FECHA_PROMOCION}" >
                                        <label for="fecha_promocion"  class="">{N_FECHA_PROMOCION}</label>
                                        <input style="" type="text" class="form-control" value="" id="fecha_promocion" name="fecha_promocion" placeholder="{P_FECHA_PROMOCION}" />
                                    </div> 
                                <div class="col-xs-12">
                                    {OTROS_CAMPOS}
                                </div>
                                </div>
                        </div>
                                                   
                        <div class="checkbox">

                                    </div>

                                                <input type="hidden" class="form-control" id="id_organizacion" name="id_organizacion" value="{ID_ORGANIZACION}" placeholder="Id Organizacion"  data-validation="required"/>
                                

