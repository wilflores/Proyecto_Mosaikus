{HTML_INICIO_PAG}
<div class="tabs"> 
<ul id="tabs-hv-2" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-orange-2" data-toggle="tab">Listado de Evidencias</a></li>  
        <li><a href="#hv-red-2" data-toggle="tab">Agregar/Modificar Evidencias</a></li>
              
    </ul>

<div id="my-tab-content" class="tab-content"  style="padding: 45px 3%;">
    <div class="tab-pane active" id="hv-red-2">
        <form id="idFormulario-hv-2" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="fecha_evi" class="col-md-4 control-label" style="color: black;">{N_FECHA_EVI}</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{FECHA_EVI}" style="width: 100px;" id="fecha_evi-hv-2" name="fecha_evi" placeholder="{N_FECHA_EVI}"  data-validation="required"/>
                      </div>                                
                  </div>
                <div class="form-group">
                        <label for="cod_emp" class="col-md-4 control-label" style="color: black;">{N_ID_PERSONA}</label>
                        <div class="col-md-10">
                            <select id="id_persona-hv-2" name="id_persona" data-validation="required">
                                <option selected="" value="">-- No Aplica --</option>
                                {EMPLEADOS}
                             </select>                          
                       </div>                                
                  </div>
                             <div class="form-group" id="tabla_fileUpload">
                                    <label for="archivo" class="col-md-4 control-label" style="color: black;">{N_ARCHIVO}</label>
                                    <div class="col-md-4">
                                        <input type="file" style="" value="{ARCHIVO}" id="fileUpload2" name="fileUpload2" onchange="cargar_archivo();"/>
                                        <input type="hidden" id="estado_actual" name="estado_actual">
                                        <input type="hidden" id="filename" name="filename" value="{FILENAME}">
                                        <input type="hidden" id="tamano" name="tamano" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc" name="tipo_doc" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                                        <div id="estado" style="display:none;"><img src="{PAHT_TO_IMG}loading3.gif">Cargando</div>
                                        <input type="hidden" class="form-control" value="{NOM_ARCHIVO}" id="nom_archivo" name="nom_archivo" placeholder="{N_NOM_ARCHIVO}" />
                                  </div>     
                                  
                             </div>
                             <div class="form-group" id="info_archivo_adjunto"  style="display:none;">
                                    <label for="archivo" class="col-md-4 control-label" style="color: black;">{N_ARCHIVO}</label>
                                    <div class="col-md-20">
                                        <p class="form-control-static" style="">
                                            <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                            <input type="text" class="form-control" style="width: 350px;display: inline;" id="info_nombre" readonly="readonly">
                                            <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->
                                            <a href="#" onclick="cancelar_archivo();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>         
                                  
                             </div>
                <!--                                
                <div class="form-group">
                            <label for="nom_archivo" class="col-md-4 control-label" style="color: black;">{N_NOM_ARCHIVO}</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" value="{NOM_ARCHIVO}" id="nom_archivo" name="nom_archivo" placeholder="{N_NOM_ARCHIVO}" data-validation="required"/>
                          </div>                                
                      </div>
                <div class="form-group">
                            <label for="archivo" class="col-md-4 control-label" style="color: black;">{N_ARCHIVO}</label>
                            <div class="col-md-4">
                              <input type="text" class="form-control" value="{ARCHIVO}" id="archivo" name="archivo" placeholder="{N_ARCHIVO}"  data-validation="required"/>
                          </div>                                
                      </div>
                          
                <div class="form-group">
                        <label for="contenttype" class="col-md-4 control-label"style="color: black;">{N_CONTENTTYPE}</label>
                        <div class="col-md-4">
                          <input type="text" class="form-control" value="{CONTENTTYPE}" id="contenttype" name="contenttype" placeholder="{N_CONTENTTYPE}" data-validation="required"/>
                      </div>                                
                  </div>-->
                <div class="form-group">
                        <label for="observacion" class="col-md-4 control-label"style="">{N_OBSERVACION}</label>
                        <div class="col-md-10">
                            <textarea class="form-control" id="observacion" name="observacion" placeholder="{N_OBSERVACION}" data-validation="required">{OBSERVACION}</textarea>
                      </div>                                
                  </div>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <!--<input class="button save" name="guardar" id="btn-guardar-hv-2" type="button" value="Guardar" onClick="validar_hv_2(document);">
                    <input class="button " type="button" value="Cancelar" onclick="reset_formulario_2();">
                    -->
                    <button type="button" id="btn-guardar-hv-2" class="btn btn-primary" onClick="validar_hv_2(document);">Guardar</button>            
                    <button type="button" class="btn btn-default" onclick="reset_formulario_2();">Cancelar</button>

                    <input type="hidden" id="opc-hv-2" name="opc" value="{OPC}">
                    <input type="hidden" id="id-hv-2"  name="id"  value="{ID}">
                </div>
            </div>
        </form>
        
        
    </div>
                
                
    <div class="tab-pane active" id="hv-orange-2">
        
        <div class="table-container">
            <div class="content-wrapper clear-block">
                <div id="grid-hv-2" class="table-container scrollable">  
                
                {TABLA}
                </div>
                
            </div>
        </div>
         
    </div>
        
</div>
                </div>
<form id="busquedaFrm-hv-2" class="form-horizontal form-horizontal-form" role="form">                        
                        <input type="hidden" name="mostrar-col" id="mostrar-col-hv-2" value="{MOSTRAR_COL}" />
                        <input type="hidden" name="reg_por_pag" id="reg_por_pag-hv-2" value="12"/>
                        <input type="hidden" name="corder" id="corder-hv-2" value="{CORDER}"/>
                        <input type="hidden" name="sorder" id="sorder-hv-2" value="{SORDER}"/>
                        <input type="hidden" value="{COD_EMP}" id="b-cod_emp-2" name="b-cod_emp-2"/>
                    </form>
                


        <form enctype="multipart/form-data" id="formuploadajax" method="post">
        
            <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
        </form>
  

