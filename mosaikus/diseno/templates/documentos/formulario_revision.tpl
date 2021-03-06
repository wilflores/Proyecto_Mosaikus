<div id="main-content" class="panel-container col-xs-24 ">
    <div class="content-panel panel">
        <div class="content">
            <div class="panel-heading">
                <div class="row">
                    <div class="panel-title col-xs-20" id="div-titulo-for">
                        {N_REVISIONES_DOCUMENTOS}
                    </div>   
                    <div class="panel-actions col-xs-4">
                        <ul class="navbar">                                          
                            <li class="">
                                <a href="#contenido"  onClick="MostrarContenido();">
                                    <i class="glyphicon glyphicon-menu-left"></i>
                                    <span>{N_VOLVER}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-24">
                    <div class="tabs"> 
                        <ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
                            <li><a href="#hv-red" data-toggle="tab">{N_CREAR_REVISION} </a></li>
                            <li><a href="#hv-blue" data-toggle="tab">{N_HISTORICO_REVISION}</a></li>                
                        </ul>        
                        <div id="my-tab-content" style="padding-right: 5%;" class="tab-content">
                            <div class="tab-pane active" id="hv-red">
                                    <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
                                        <div class="form-group" id="info_archivo_adjunto">
                                            <label for="archivo" class="col-md-4 control-label">{N_DOC_FISICO}</label>
                                            <div class="col-md-10">
                                                <p class="form-control-static" style="">
                                                    <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                                    <input type="text" class="form-control" readonly="readonly" value="{NOMBRE_DOC}">
                                                    <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->                                            
                                                </p>                      
                                            </div>         

                                        </div>
                                        <div class="form-group" id="info_archivo_adjunto">
                                            <label for="archivo" class="col-md-4 control-label">{N_REVISION}</label>
                                            <div class="col-md-2">
                                                <p class="form-control-static" style="">
                                                    <!--<img src="{PAHT_TO_IMG}adjunto.png">-->
                                                    <input type="text" class="form-control" readonly="readonly" value="{REVISION}">
                                                    <!--<span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>-->                                            
                                                </p>                      
                                            </div>         

                                        </div>
                                        <div class="form-group">
                                            <label for="elaboro" class="col-md-4 control-label">{N_ELABORO}</label>                                                
                                            <div class="col-md-10">                                              
                                              <select id="elaboro" name="elaboro" data-validation="required">
                                                <option selected="" value="">-- No Asignado --</option>
                                                {ELABORO}
                                             </select>
                                          </div>   
                                        </div>
                                     
                                        <div class="form-group">
                                                <label for="fecha" class="col-md-4 control-label">{N_FECHA}</label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" style="width: 120px;" data-date-format="DD/MM/YYYY"  value="{FECHA}" id="fecha" name="fecha" placeholder="{N_FECHA}"  data-validation="required"/>
                                                    <input type="hidden" class="form-control" value="{VERSION}" id="version" name="version"/>
                                              </div>                                
                                        </div>
                                        <div class="form-group">
                                            <label for="observacion" class="col-md-4 control-label">{N_OBSERVACION_REV}</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" data-validation="required" rows="3" id="observacion" name="observacion" >{OBSERVACION}</textarea>                                                                                    
                                          </div>                                
                                        </div>                                                               
                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">

                                                <button type="button" class="btn btn-primary" onClick="validar_rev(document);" id="btn-guardar">{DESC_OPERACION}</button>            
                                                <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">{N_CANCELAR}</button>

                                                <input type="hidden" id="opc" name="opc" value="{OPC}">
                                                <input type="hidden" id="id"  name="id"  value="{ID}">
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="hv-blue">                
                    
                                <div id="grid-personal-cap">                                
                              
                                    {TABLA}
                                    <br>&nbsp;
                                </div>
                        
                            </div>
        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                    

       