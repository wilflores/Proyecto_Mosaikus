<div id="main-content" class="panel-container col-xs-24 ">
    <div class="content-panel panel">
        <div class="content">
            <form id="r-idFormulario" class="form-horizontal form-horizontal-red" role="form">
                <div class="panel-heading">
                    <div class="row">
                        <div class="panel-title col-xs-12"  id="r-div-titulo-for">
                            {TITULO_FORMULARIO}                    
                        </div>                

                    </div>
                </div>
                <div class="row">
                <div class="col-xs-24">
                            <div  class="form-group" id="r-tabla_fileUpload"  style="{CSS_TABLA_FILEUPLOAD}">
                                    <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-10" >
                                        <input type="file" value="{ARCHIVO}" accept="application/pdf" id="r-fileUpload2" name="fileUpload2" onchange="cargar_archivo_reg();" data-validation="required"/>
                                        <input type="hidden" id="r-estado_actual" name="estado_actual">
                                        <input type="hidden" id="r-filename" name="filename" value="{FILENAME}">
                                        <input type="hidden" id="r-tamano" name="tamano" value="{TAMANO}">
                                        <input type="hidden" id="r-tipo_doc" name="tipo_doc" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                                        <div id="r-estado" style="display:none;">
                                            <!--<img src="{PAHT_TO_IMG}loading3.gif">Cargando-->
                                            <div class="progress" style="width: 250px;">
                                                <div class="progress-bar" id="estado-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
                                                  0%
                                                </div>
                                            </div>
                                        </div>
                                                                                
                                  </div>     
                                  <span class="help-block" style="font-size: small;">(*) Código-nombre archivo.PDF </span>
                             </div>
                             <div class="form-group" id="r-info_archivo_adjunto"  style="display:none;">
                                    <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                    <div class="col-md-10">
                                        <p class="form-control-static">
                                            <!--<img src="{PAHT_TO_IMG}adjunto.png">-->                                            
                                            <input type="text" readonly="readonly" onkeypress="return false;" style="display: inline;width: 80px;" class="form-control" value="{CODIGO_DOC}" id="r-Codigo_doc" name="Codigo_doc" placeholder="{N_CODIGO_DOC}" data-validation="required"/>
                                            -
                                            <input type="text" style="display: none;width: 220px;" class="form-control" value="{NOMBRE_DOC}" id="r-nombre_doc" name="nombre_doc" placeholder="{N_NOMBRE_DOC}" data-validation="required"/>
                                            <input type="text" readonly="readonly" onkeypress="return false;" style="display: inline;width: 220px;" class="form-control" value="{CORRELATIVO}" id="correlativo" name="correlativo" placeholder="{N_CORRELATIVO}" data-validation="required"/>
                                            .pdf
                                            <input type="hidden" class="form-control" style="width: 250px;display: inline;" id="r-info_nombre" readonly="readonly">
                                            <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->
                                            <a href="#" onclick="cancelar_archivo_reg();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>         
                                  <span class="help-block" style="font-size: small;">(*) Código-nombre archivo.PDF </span>
                             </div>


                               
                                                     {CAMPOS_DINAMICOS}
                                               


                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">

                                    <button type="button" class="btn btn-primary" onClick="r_validar(document);" id="btn-guardar">{DESC_OPERACION}</button>                
                                    <button type="button" class="btn btn-default" onclick="MostrarContenidoAux();">Cancelar</button>

                                    <input type="hidden" id="r-opc" name="opc" value="{OPC}">
                                    <input type="hidden" id="r-id"  name="id"  value="{ID}">
                                </div>
                            </div>
</div></div>
</form>
</div>
        </div></div>

        <form enctype="multipart/form-data" id="formuploadajax" method="post">
        
            <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
        </form>