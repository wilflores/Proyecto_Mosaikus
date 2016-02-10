<div id="main-content" class="panel-container col-xs-24 ">
              <div class="content-panel panel">
                  <div class="content">
                              <div class="panel-heading">
            <div class="row">
                <div class="panel-title col-xs-12" id="div-titulo-for">
                    Nueva Versión                    
                </div>                

            </div>
        </div>

<div class="row">
    <div class="col-xs-24">
<div class="tabs"> 
    <ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-red" data-toggle="tab">Versión </a></li>
        <li><a href="#hv-blue" data-toggle="tab">Versiones Anteriores</a></li>                
    </ul>        
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="hv-red">
            
            <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
    
                            <div class="form-group">
                                <label for="archivo" class="col-md-5 control-label">Agregar Nueva Versión Documento</label>
                                <div class="col-md-10">
                                    <p class="form-control-static" style="">
                                        <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                        <input type="text" class="form-control" readonly="readonly" value="{NOMBRE_DOC_AUX}">
                                        <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->                                            
                                    </p>                      
                                </div>         
                                   
                             </div>
                            <div class="form-group" id="tabla_fileUpload"  style="{CSS_TABLA_FILEUPLOAD}">
                                    <label for="archivo" class="col-md-5 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-10">
                                        <input type="file" style="" value="{ARCHIVO}" id="fileUpload2" name="fileUpload2" onchange="cargar_archivo_ver();" data-validation="required"/>
                                        <input type="hidden" id="estado_actual" name="estado_actual">
                                        <input type="hidden" id="filename" name="filename" value="{FILENAME}">
                                        <input type="hidden" id="tamano" name="tamano" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc" name="tipo_doc" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                                        <div id="estado" style="display:none;color:white;"><img src="{PAHT_TO_IMG}loading3.gif">Cargando</div>
                                        <!--<input type="hidden" class="form-control" value="{DOC_FISICO}" id="doc_fisico" name="doc_fisico" placeholder="{N_DOC_FISICO}"  data-validation="required"/>
                                        <input type="hidden" class="form-control" value="{CONTENTTYPE}" id="contentType" name="contentType" placeholder="{N_CONTENTTYPE}" data-validation="required"/>
                                        -->
                                  </div>     
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>
                             <div class="form-group" id="info_archivo_adjunto"  style="display:none;">
                                    <label for="archivo" class="col-md-5 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" style="">
                                            <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                            <input type="text" class="form-control" style="width: 250px;display: inline;" id="info_nombre">
                                            <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->
                                            <a href="#" onclick="cancelar_archivo_otro();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>         
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>
                            <div class="form-group" id="tabla_fileUpload_vis"  style="{CSS_TABLA_FILEUPLOAD_VIS}">
                                    <label for="archivo" class="col-md-5 control-label">{N_NOM_VISUALIZA}</label>
                                    <div class="col-md-10">
                                        <input type="file" style="" value="{ARCHIVO}" id="fileUpload2_vis" name="fileUpload2_vis" onchange="cargar_archivo_vis();"/>
                                        <input type="hidden" id="estado_actual_vis" name="estado_actual_vis">
                                        <input type="hidden" id="filename_vis" name="filename_vis" value="{FILENAME}">
                                        <input type="hidden" id="tamano_vis" name="tamano_vis" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc_vis" name="tipo_doc_vis" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                                        <div id="estado_vis" style="display:none;"><img src="{PAHT_TO_IMG}loading3.gif">Cargando</div>
                                        <!--<input type="hidden" class="form-control" value="{NOM_VISUALIZA}" id="nom_visualiza" name="nom_visualiza" placeholder="{N_NOM_VISUALIZA}" data-validation="required"/>
                                        <input type="hidden" class="form-control" value="{DOC_VISUALIZA}" id="doc_visualiza" name="doc_visualiza" placeholder="{N_DOC_VISUALIZA}"  data-validation="required"/>
                                        <input type="hidden" class="form-control" value="{CONTENTTYPE_VISUALIZA}" id="contentType_visualiza" name="contentType_visualiza" placeholder="{N_CONTENTTYPE_VISUALIZA}" data-validation="required"/>
                                        -->
                                  </div> 
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>
                             <div class="form-group" id="info_archivo_adjunto_vis"  style="display:none;">
                                    <label for="archivo" class="col-md-5 control-label">{N_NOM_VISUALIZA}</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static" style="">
                                            <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                            <input type="text" class="form-control" style="width: 250px;display: inline;" id="info_nombre_vis">
                                            <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->
                                            <a href="#" onclick="cancelar_archivo_vis();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>  
                                  <span class="help-block" style="font-size: small;">(*) Código-Nombre archivo-Versión.Extension</span>
                             </div>                                                                    
                             
                                <div class="form-group">
                                    <label for="elaboro" class="col-md-5 control-label">{N_ELABORO}</label>                                                
                                    <div class="col-md-10">                                              
                                      <select id="elaboro" name="elaboro" data-validation="required">
                                        <option selected="" value="">-- No Asignado --</option>
                                        {ELABORO}
                                     </select>
                                  </div>   
                                </div>
                                     
                                <div class="form-group">
                                        <label for="fecha" class="col-md-5 control-label">{N_FECHA}</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" style="width: 120px;" value="{FECHA}" id="fecha" name="fecha" placeholder="{N_FECHA}"  data-validation="required"/>
                                            <input type="hidden" class="form-control" value="{VERSION}" id="version" name="version"/>
                                            <input type="hidden" class="form-control" value="{CODIGO_DOC}" id="Codigo_doc" name="Codigo_doc" placeholder="{N_CODIGO_DOC}" data-validation="required"/>
                                            <input type="hidden" class="form-control" value="{NOMBRE_DOC}" id="nombre_doc" name="nombre_doc" placeholder="{N_NOMBRE_DOC}" data-validation="required"/>
                                            <input type="hidden" class="form-control" value="{NOMBRE_DOC_VIS}" id="nombre_doc_vis" name="nombre_doc_vis"/>

                                      </div>                                
                                </div>
                                <div class="form-group">
                                        <label for="observacion" class="col-md-5 control-label">{N_OBSERVACION_REV}</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" data-validation="required" rows="3" id="observacion" name="observacion" >{OBSERVACION}</textarea>                                                                                    
                                      </div>                                
                                  </div>                                                               


            <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">                        
                        
                        <button type="button" class="btn btn-primary" onClick="validar_ver(document);" id="btn-guardar">{DESC_OPERACION}</button>            
                        <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">Cancelar</button>

                        <input type="hidden" id="opc" name="opc" value="{OPC}">
                        <input type="hidden" id="id"  name="id"  value="{ID}">
                    </div>
                </div>
            </form>
            <form enctype="multipart/form-data" id="formuploadajax" method="post">

                <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
                <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
            </form>        

       </div>
         
       <div class="tab-pane" id="hv-blue">                
                    
                            <div id="grid-personal-cap">                                
                               <!-- <table id="table-pers-capa" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                    <thead>
                                        <tr bgcolor="#FFFFFF" height="30px">
                                            <!--<th width="3%">
                                                <div align="left"> </div>
                                            </th>--><!--
                                            <th width="5%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_CODIGO_DOC}</div>
                                                </div>
                                            </th>
                                            <th width="20%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_NOMBRE_DOC}</div>
                                                </div>
                                            </th>
                                            <th width="5%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_FECHA}</div>                                                
                                                </div>
                                            </th>
                                            <th width="5%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_VERSION}</div>
                                                </div>
                                            </th>
                                            <th width="3%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">Archivo</div>
                                                </div>
                                            </th>
                                            <th width="3%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_FORMULARIO}</div>
                                                </div>
                                            </th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {ITEMS_ESP}
                                    </tbody>
                                </table>
                                                    -->
                                                    {TABLA}
                                                    <br>&nbsp;
                            </div>
                        
</div>
                                                    </div></div>
</div></div></div></div></div>