<!--<div class="form-group">
                                  <label for="cod_categoria" class="col-md-2 control-label">{N_COD_CATEGORIA}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-cod_categoria" name="b-cod_categoria" placeholder="{N_COD_CATEGORIA}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="id_acap" class="col-md-2 control-label">{N_ID_ACAP}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-id_acap" name="b-id_acap" placeholder="{N_ID_ACAP}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="cod_cargo" class="col-md-2 control-label">{N_COD_CARGO}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-cod_cargo" name="b-cod_cargo" placeholder="{N_COD_CARGO}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="cod_emp" class="col-md-2 control-label">{N_COD_EMP}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-cod_emp" name="b-cod_emp" placeholder="{N_COD_EMP}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="bloqueo" class="col-md-2 control-label">{N_BLOQUEO}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-bloqueo" name="b-bloqueo" placeholder="{N_BLOQUEO}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="id_proceso" class="col-md-2 control-label">{N_ID_PROCESO}</label>
                                  <div class="col-md-2">
                                    
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="id_organizacion" class="col-md-2 control-label">{N_ID_ORGANIZACION}</label>
                                  <div class="col-md-2">
                                    
                                </div>                                
                            </div>-->
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#red" data-toggle="tab">Datos Basicos</a></li>
        <li><a href="#orange" data-toggle="tab">Árbol organizacional</a></li>        
        <li><a href="#blue" data-toggle="tab">Árbol de procesos</a></li>   
    </ul>
<div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="red">                                
                                
                                {CAMPOS_DINAMICOS}
    </div>
    <div class="tab-pane" id="orange">
       <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/personas/emb_jstree_single.php"></iframe>
       <input type="hidden" id="b-id_organizacion" name="b-id_organizacion" value=""/>
    </div>
    <div class="tab-pane" id="blue">
       <iframe width="100%" height="350px" id="b-iframe-p" frameborder="0" scrolling="no" src="pages/arbol_procesos/emb_jstree_procesos.php"></iframe>
       <input type="hidden" id="b-id_proceso" name="b-id_proceso" value=""/>
    </div>
</div>
