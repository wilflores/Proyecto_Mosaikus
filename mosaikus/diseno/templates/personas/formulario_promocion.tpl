                        <div class="row">
                            <div class="form-group">                                
                                <div class="row">
                                    <div class="form-group col-xs-8">
                                        <label for="id_personal" class="">{N_ID_PERSONAL}</label>
                                        <input type="checkbox" style="display: none;" name="interno" id="interno" value="1" {CHECKED_INTERNO}>                                         
                                        <input readonly type="text"value="{ID_PERSONAL}" class="form-control" id="id_personal" name="id_personal" placeholder="{P_ID_PERSONAL}" data-validation="required"/>  <!--onblur="fomatear_rut(this.value);"-->
                                    </div>    
                                    <div class="form-group col-xs-8">
                                        <label for="nombres" class="">{N_NOMBRES_APELLIDOS}</label>                                        
                                        <input readonly type="text" class="form-control" value="{NOMBRES_APELLIDOS}" id="nombres_apellidos" name="nombres_apellidos" placeholder="{P_NOMBRES_APELLIDOS}" />
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
                                        <input style="" type="text" data-date-format="DD/MM/YYYY" class="form-control" value="" id="fecha_promocion" name="fecha_promocion" placeholder="{P_FECHA_PROMOCION}" data-validation="required"/>
                                    </div> 
                                </div>
                        </div>
                        <div class="form-group">     
                             <div class="row">
                                 <div class="col-xs-10">
                                    <label for="comentario_promocion" >{N_COMENTARIO_PROMOCION}</label>
                                    <textarea id="comentario_promocion" class="form-control" name="comentario_promocion" placeholder="{P_COMENTARIO_PROMOCION}"></textarea>
                                 </div> 
                             </div>
                        </div>
                     </div>                    
                    <input type="hidden" class="form-control" id="id_organizacion" name="id_organizacion" value="{ID_ORGANIZACION}" placeholder="Id Organizacion"  data-validation="required"/>
                                

