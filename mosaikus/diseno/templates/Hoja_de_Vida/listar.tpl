{HTML_INICIO_PAG}
<div class="tabs"> 
<ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-orange" data-toggle="tab">Registro Anotaciones</a></li> 
        <li><a href="#hv-red" data-toggle="tab">Ingreso Anotaciones</a></li>
               
    </ul>

    <div id="my-tab-content" class="tab-content" style="padding: 45px 3%;">
    <div class="tab-pane active" id="hv-red">
        <form id="idFormulario-hv" class="form-horizontal" role="form">
            <div class="row">
            <div class="col-xs-24">
            

                <div class="form-group">
                        <label for="cod_hoja_vida" class="col-md-4 control-label"style="color:black">Nombres</label>
                        <div class="col-lg-10">
                            <p class="form-control-static">{NOMBRES}</p>
                        </div>                             
                </div>
                <div class="form-group">
                    <label for="cod_emp" class="col-md-4 control-label" style="color:black">{N_COD_EMP}</label>
                    <div class="col-md-4">
                        <p class="form-control-static">{RUT}</p>
                        <input type="hidden" class="form-control" value="{COD_EMP}" id="cod_emp" name="cod_emp" placeholder="Cod Emp"  data-validation="required"/>
                    </div>                                
                </div>
                    
                <div class="form-group">
                    <label for="cod_emp" class="col-md-4 control-label" style="color:black">Cargo</label>
                    <div class="col-md-4">
                        <p class="form-control-static">{CARGO}</p>                    
                    </div>                                
                </div>
                <div class="form-group">
                        <label for="fecha" class="col-md-4 control-label" style="color:black">{N_FECHA}</label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" value="{FECHA}" id="hv-fecha" name="fecha" placeholder="dd/mm/yyyy"  data-validation="required"/>
                      </div>                                
                  </div>
                <div class="form-group">
                        <label for="anotacion" class="col-md-4 control-label" style="color:black">{N_ANOTACION}</label>
                        <div class="col-md-20">
                            <textarea class="form-control" rows="2" id="hv-anotacion" name="anotacion" data-validation="required">{ANOTACION}</textarea>                                          
                      </div>                                
                  </div>
                <div class="form-group" id="tabla_fileUpload"  style="{CSS_TABLA_FILEUPLOAD}">
                        <label for="archivo" class="col-md-4 control-label" style="color:black">{N_ARCHIVO}</label>
                        <div class="col-md-20">
                            <input type="file" class="form-control" value="{ARCHIVO}" id="fileUpload2" name="fileUpload2" onchange="cargar_archivo_otro();"/>
                            <input type="hidden" id="estado_actual" name="estado_actual">
                            <input type="hidden" id="filename" name="filename" value="{FILENAME}">
                            <input type="hidden" id="tamano" name="tamano" value="{TAMANO}">
                            <input type="hidden" id="tipo_doc" name="tipo_doc" value="{TIPO_DOC}">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                            <div id="estado" style="">
                                <!--<img src="{PAHT_TO_IMG}loading3.gif">Cargando-->
                                <div class="progress" style="width: 250px;">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
                                      0%
                                    </div>
                                </div>
                            </div>
                      </div>                                
                 </div>
                 <div class="form-group" id="info_archivo_adjunto"  style="display:none;">
                        <label for="archivo" class="col-md-4 control-label" style="color:black">{N_ARCHIVO}</label>
                        <div class="col-md-20">
                            <p class="form-control-static">
                                <img src="{PAHT_TO_IMG}adjunto.png">
                                <span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>
                                <a href="#" onclick="cancelar_archivo_otro();">
                                    (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                    Eliminar)
                                </a>
                            </p>                      
                      </div>                                
                 </div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <!--<input class="button save" name="guardar" type="button" value="Guardar" onClick="validar_hv(document);">
                        <input class="button " type="button" value="Cancelar" onclick="reset_formulario();">
                        -->
                        <button type="button" class="btn btn-primary" id="btn-guardar"  onClick="validar_hv(document);">Guardar</button>                                
                        <button type="button" class="btn btn-default" onclick="reset_formulario();">Cancelar</button>


                        <input type="hidden" id="opc-hv" name="opc" value="{OPC}">
                        <input type="hidden" id="id-hv"  name="id"  value="{ID}">
                    </div>
                </div>
            </div></div>
        </form>
        <form enctype="multipart/form-data" id="formuploadajax" method="post">
        
            <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
        </form>
        
    </div>
                
                <form id="busquedaFrm-hv" class="form-horizontal form-horizontal-form" role="form">                        
                        <input type="hidden" name="mostrar-col" id="mostrar-col-hv" value="{MOSTRAR_COL}" />
                        <input type="hidden" name="reg_por_pag" id="reg_por_pag-hv" value="12"/>
                        <input type="hidden" name="corder" id="corder-hv" value="{CORDER}"/>
                        <input type="hidden" name="sorder" id="sorder-hv" value="{SORDER}"/>
                        <input type="hidden" value="{COD_EMP}" id="b-cod_emp" name="b-cod_emp"/>
                    </form>
    <div class="tab-pane active" id="hv-orange">
        
        
            <div class="table-container scrollable">
                <div id="grid-hv" >
                {TABLA}
                </div>
                <br>
            </div>
        
         
    </div>
        
</div>
</div>

  

