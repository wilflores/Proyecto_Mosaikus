<!--<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#red" data-toggle="tab">Datos Basicos</a></li>
        <li><a href="#orange" data-toggle="tab">Árbol organizacional</a></li>        
    </ul><div class="form-group">
                                  <label for="IDDoc" class="col-md-2 control-label">{N_IDDOC}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-IDDoc" name="b-IDDoc" placeholder="{N_IDDOC}"/>
                                </div>                                
                            </div>
<div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="red">-->
<div class="form-group">
    <label for="semaforo" class="control-label">Días validez</label>
    <div class="row">
      <div class="col-xs-12">
        <label for="exampleInputPassword1">Desde</label>
        <input type="text" class="form-control" id="b-semaforo-desde" name="b-semaforo-desde" placeholder="Nro. de Dias"  />                                      
      </div>

      <div class="col-xs-12">
        <label for="exampleInputPassword1">Hasta</label>
        <input type="text" class="form-control" id="b-semaforo-hasta" name="b-semaforo-hasta" placeholder="Nro. de Dias"  />
      </div>
    </div>
</div>

<div class="form-group">
    <label for="nombre_doc" class=" control-label">{N_NOMBRE_DOC}</label>                                  
    <input type="text" class="form-control" id="b-nombre_doc" name="b-nombre_doc" placeholder="{N_NOMBRE_DOC}" />
</div>
<div class="form-group">                     
    <div class="row">
        <div class="col-xs-12">
            <label for="Codigo_doc" class=" control-label">{N_CODIGO_DOC}</label>                              
            <input type="text" class="form-control" id="b-Codigo_doc" name="b-Codigo_doc" placeholder="{N_CODIGO_DOC}" />                                                                                        
        </div>
        <div class="col-xs-12">
            <label for="version" class="control-label">{N_VERSION}</label>                                 
            <input type="text" class="form-control" id="b-version" name="b-version" placeholder="{N_VERSION}"/>                                                           
        </div>
    </div>
</div>
<div class="form-group">
    <label for="fecha" class="control-label">{N_FECHA} Versión</label>
    <div class="row">
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Desde</label>
            <input type="text" class="form-control" id="b-fecha-desde" name="b-fecha-desde" placeholder="{N_FECHA}"  />
        </div>   
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Hasta</label> 
            <input type="text" class="form-control" id="b-fecha-hasta" name="b-fecha-hasta" placeholder="{N_FECHA}"  />
        </div>                                
    </div>
</div>
<div class="form-group">
    <label for="fecha" class="control-label">Fecha Revisión</label>
    <div class="row">
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Desde</label>
            <input type="text" class="form-control" id="b-fecha_rev-desde" name="b-fecha_rev-desde" placeholder="{N_FECHA}"  />
        </div>   
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Hasta</label>
            <input type="text" class="form-control" id="b-fecha_rev-hasta" name="b-fecha_rev-hasta" placeholder="{N_FECHA}"  />
        </div>                                
    </div>
</div>
<div class="form-group">
    <label for="descripcion" class="control-label">{N_DESCRIPCION}</label>                                  
    <input type="text" class="form-control" id="b-descripcion" name="b-descripcion" placeholder="{N_DESCRIPCION}" />                                
</div>
<!--<div class="form-group">
                                  <label for="palabras_claves" class="col-md-2 control-label">{N_PALABRAS_CLAVES}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-palabras_claves" name="b-palabras_claves" placeholder="{N_PALABRAS_CLAVES}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="formulario" class="control-label">{N_FORMULARIO}</label>
                
                                    <label class="checkbox-inline" style="padding-top: 0px;">
                                        <input type="checkbox" id="b-formulario" name="b-formulario" value="S">   &nbsp;
                                    </label>                                        
                            </div>-->
                                  
                            <div class="form-group" style="margin-bottom: 0px;">                                
                                <label class="checkbox-inline"> 
                                    <input type="checkbox" id="b-formulario" name="b-formulario" value="S"> {N_FORMULARIO} </label>                                     
                            </div>   
                            <div class="form-group">                                                                
                                <label class="checkbox-inline"> 
                                    <input type="checkbox" id="b-publico" name="b-publico" value="S"> Documento {N_PUBLICO} </label>  
                                    <br>
                                    <label class="checkbox-inline"> 
                                    <input type="checkbox" id="b-privado" name="b-privado" value="N"> Documento Privado </label> 
                            </div>   
<!--<div class="form-group">
                                  <label for="vigencia" class="col-md-2 control-label">{N_VIGENCIA}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-vigencia" name="b-vigencia" placeholder="{N_VIGENCIA}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="doc_fisico" class="col-md-2 control-label">{N_DOC_FISICO}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-doc_fisico" name="b-doc_fisico" placeholder="{N_DOC_FISICO}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="contentType" class="col-md-2 control-label">{N_CONTENTTYPE}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-contentType" name="b-contentType" placeholder="{N_CONTENTTYPE}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="id_filial" class="col-md-2 control-label">{N_ID_FILIAL}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-id_filial" name="b-id_filial" placeholder="{N_ID_FILIAL}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="nom_visualiza" class="col-md-2 control-label">{N_NOM_VISUALIZA}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-nom_visualiza" name="b-nom_visualiza" placeholder="{N_NOM_VISUALIZA}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="doc_visualiza" class="col-md-2 control-label">{N_DOC_VISUALIZA}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-doc_visualiza" name="b-doc_visualiza" placeholder="{N_DOC_VISUALIZA}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="contentType_visualiza" class="col-md-2 control-label">{N_CONTENTTYPE_VISUALIZA}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-contentType_visualiza" name="b-contentType_visualiza" placeholder="{N_CONTENTTYPE_VISUALIZA}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="id_usuario" class="col-md-2 control-label">{N_ID_USUARIO}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-id_usuario" name="b-id_usuario" placeholder="{N_ID_USUARIO}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="observacion" class="col-md-2 control-label">{N_OBSERVACION}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-observacion" name="b-observacion" placeholder="{N_OBSERVACION}"/>
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="muestra_doc" class="col-md-2 control-label">{N_MUESTRA_DOC}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-muestra_doc" name="b-muestra_doc" placeholder="{N_MUESTRA_DOC}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="estrucorg" class="col-md-2 control-label">{N_ESTRUCORG}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-estrucorg" name="b-estrucorg" placeholder="{N_ESTRUCORG}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="arbproc" class="col-md-2 control-label">{N_ARBPROC}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-arbproc" name="b-arbproc" placeholder="{N_ARBPROC}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="apli_reg_estrorg" class="col-md-2 control-label">{N_APLI_REG_ESTRORG}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-apli_reg_estrorg" name="b-apli_reg_estrorg" placeholder="{N_APLI_REG_ESTRORG}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="apli_reg_arbproc" class="col-md-2 control-label">{N_APLI_REG_ARBPROC}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-apli_reg_arbproc" name="b-apli_reg_arbproc" placeholder="{N_APLI_REG_ARBPROC}" />
                                </div>                                
                            </div>
<div class="form-group">
                                  <label for="workflow" class="col-md-2 control-label">{N_WORKFLOW}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-workflow" name="b-workflow" placeholder="{N_WORKFLOW}" />
                                </div>                                
                            </div>-->

<!--<div class="form-group">
                                  <label for="v_meses" class="col-md-2 control-label">{N_V_MESES}</label>
                                  <div class="col-md-2">
                                    <input type="text" class="form-control" id="b-v_meses" name="b-v_meses" placeholder="{N_V_MESES}"/>
                                </div>                                
                            </div>-->
                            <div class="form-group">
                                            <label for="reviso" class="control-label">{N_REVISO}</label>                                            
                                                                                        
                                              <select id="b-reviso" name="b-reviso" >
                                                <option selected="" value="">-- Seleccione --</option>
                                                {REVISORES}
                                             </select>
                                                                          
                                        </div>
                                        <div class="form-group">
                                            <label for="elaboro" class="control-label">{N_ELABORO}</label>                                                
                                                                                         
                                              <select id="b-elaboro" name="b-elaboro" data-validation="required">
                                                <option selected="" value="">-- Seleccione --</option>
                                                {ELABORO}
                                             </select>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="aprobo" class="control-label">{N_APROBO}</label>
                                                                                       
                                              <select id="b-aprobo" name="b-aprobo" >
                                                <option selected="" value="">-- Seleccione --</option>
                                                {APROBO}
                                             </select>
                                             <input type="hidden" id="b-id_organizacion" name="b-id_organizacion"/>                                                                        
                                        </div>
                                             <!--
</div>
    <div class="tab-pane active" id="orange">
       <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/personas/emb_jstree_single.php"></iframe>
       
    </div>
</div>
                                             
                                             -->