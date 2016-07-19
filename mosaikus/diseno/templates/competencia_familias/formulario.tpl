<div class="form-group">
                                        <label for="codigo" class="col-md-4 control-label">{N_CODIGO}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{CODIGO}" id="codigo" name="codigo" placeholder="{N_CODIGO}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="descripcion" class="col-md-4 control-label">{N_DESCRIPCION}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{DESCRIPCION}" id="descripcion" name="descripcion" placeholder="{N_DESCRIPCION}" data-validation="required"/>
                                      </div>                                
                                  </div>
<!--<div class="form-group">
                                        <label for="orden" class="col-md-4 control-label">{N_ORDEN}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" value="{ORDEN}" id="orden" name="orden" placeholder="{N_ORDEN}"  data-validation="required"/>
                                      </div>                                
                                  </div>-->
<!--ADAPTACION DE FAMILIAS E ITEMS-->

<div class="form-group">
                                    <div class="tabs">
                                        <ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">
                                            <li><a href="#hv-red-2" data-toggle="tab">Items</a></li>
                                            
                                        </ul>
                                        <div id="my-tab-content" class="tab-content" style="padding: 45px 15px;">
                                            <div class="tab-pane active" id="hv-red-2">
                                                    <input type="hidden" id="num_items_esp" name="num_items_esp" value="{NUM_ITEMS_ESP}"/> 
                                                   <input type="hidden" id="tok_new_edit" name="tok_new_edit" value="{TOK_NEW}"/>
                                                   <input type="hidden" id="id_unico_del" name="id_unico_del" value=""/>
                                                   <!--<input type="button" class="button add" value="Agregar" onClick="agregar_esp();" >-->
                                                   <button type="button" style="margin-bottom: 10px;" id="boton_cat" onClick="agregar_esp();" class="btn btn-primary "><i class="glyphicon glyphicon-asterisk"></i>Agregar Items</button>
                                                   
                                                    <table id="table-items-esp" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr bgcolor="#FFFFFF" height="30px">
                                                                <th width="10%">
                                                                    <div align="left" style="width: 50px;"> </div>
                                                                </th>
                                                                <th width="10%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">Vigencia</div>
                                                                    </div>
                                                                </th>
                                                                <th width="10%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">Codigo</div>
                                                                    </div>
                                                                </th>
                                                                
                                                                <th width="50%">
                                                                    <div align="left">
                                                                        <div style="cursor:pointer;display:inline;">Descripción</div>                                                
                                                                    </div>
                                                                </th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                            {ITEMS_ESP}
                                                        </tbody>
                                                    </table>
                                            </div> 
                                        </div>
                                    </div>
                                </div>