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
                                        <input type="text" class="form-control"  value="{APELLIDO_MATERNO}" id="apellido_materno" name="apellido_materno" placeholder="{P_APELLIDO_MATERNO}" data-validation="sololetras"/>
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
                                <div class="form-group col-xs-8" id="div_fecha_promocion" >
                                        <label for="fecha_promocion"  class="">{N_FECHA_PROMOCION}</label>
                                        <input style="" type="text" data-date-format="DD/MM/YYYY" class="form-control" value="" id="fecha_promocion" name="fecha_promocion" placeholder="{P_FECHA_PROMOCION}" />
                                    </div> 
                                </div>
                        </div>
                     </div>                    
                    <input type="hidden" class="form-control" id="id_organizacion" name="id_organizacion" value="{ID_ORGANIZACION}" placeholder="Id Organizacion"  data-validation="required"/>
                                

