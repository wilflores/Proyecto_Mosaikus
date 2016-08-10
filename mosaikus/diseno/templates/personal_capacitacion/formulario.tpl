<div id="main-content" class="panel-container col-xs-24 ">
    <div class="content-panel panel">
        <div class="content">
                      
   
            <div class="panel-heading">
                <div class="row">
                    <div class="panel-title col-xs-12" id="div-titulo-for">
                        {TITULO_FORMULARIO}                    
                    </div>                

                </div>
            </div>
            <div class="row">
                <div class="col-xs-24">
                    <div class="tabs"> 
    <ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="#hv-red" data-toggle="tab">{N_DATOS_CAPACITACION}</a></li>
        <li><a href="#hv-orange" data-toggle="tab">{N_PERSONAL_CAPACITADO}</a></li>        
        <li><a href="#hv-blue" data-toggle="tab">{N_EVALUACION_CAPACITACION}</a></li>  
    </ul>
    
                        <div id="my-tab-content" class="tab-content" style="padding: 45px 5%;">
            <div class="tab-pane active" id="hv-red">
                <form id="idFormulario" class="form-horizontal form-horizontal-red" role="form">
                                <!--<div class="form-group">
                                        <label for="cod_capacitacion" class="col-md-2 control-label">Cod Capacitacion</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{COD_CAPACITACION}" id="cod_capacitacion" name="cod_capacitacion" placeholder="Cod Capacitacion"  data-validation="required"/>
                                      </div>                                
                                  </div>-->
                                <div class="form-group">
                                        <label for="cod_curso" class="col-md-4 control-label">{N_COD_CURSO}</label>
                                        <div class="col-md-10">
                                            <select id="cod_curso" data-validation="required" name="cod_curso">
                                                <option selected="" value="">Seleccione</option>
                                                {CURSOS}
                                            </select>
                                          <!--<input type="text" class="form-control" value="{COD_CURSO}" id="cod_curso" name="cod_curso" placeholder="Cod Curso"  data-validation="required"/>-->
                                      </div>                                
                                  </div>
                                <div class="form-group">
                                        <label for="cod_emp_relator" class="col-md-4 control-label">{N_COD_EMP_RELATOR}</label>
                                        <div class="col-md-10">
                                          <select id="cod_emp_relator" data-validation="required" name="cod_emp_relator">
                                                <option selected="" value="">-- No Asignado --</option>
                                                {RELATORES}
                                            </select>
                                      </div>                                
                                  </div>
                                <div class="form-group">
                                        <label for="fecha" class="col-md-4 control-label">{N_FECHA}</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" style="width: 140px;" data-date-format="DD/MM/YYYY" value="{FECHA}" id="fecha" name="fecha" placeholder="Fecha Inicio"  data-validation="required fechaMenor"/>
                                      </div>                                
                                  </div>
                                  <div class="form-group">
                                        <label for="fecha_termino" class="col-md-4 control-label">{N_FECHA_TERMINO}</label>
                                        <div class="col-md-10">
                                          <input type="text" class="form-control" style="width: 140px;" data-date-format="DD/MM/YYYY" value="{FECHA_TERMINO}" id="fecha_termino" name="fecha_termino" placeholder="Fecha Termino"  data-validation="required"/>
                                      </div>                                
                                  </div>
                                      <div class="form-group">
                                        <label for="hora" class="col-md-4 control-label">{N_HORA}</label>
                                        <div class="col-md-10">
                                          <!--<input type="text" class="form-control" value="{HORA}" id="hora" name="hora" placeholder="Hora"  data-validation="required"/>
                                          -->
                                          {HORAS}
                                          <span style=""><strong>:</strong></span>
                                          {MINUTOS}     
                                          <input type="hidden" id="hh_min" name="hh_min" data-validation="required">
                                          <!-- <input type="text" class="form-control" value="{MIN}" id="min" name="min" placeholder="Min"  data-validation="required"/>
                                          -->
                                      </div>                                
                                  </div>
                                <!--<div class="form-group">
                                        <label for="id_organizacion" class="col-md-2 control-label">Id Organizacion</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{ID_ORGANIZACION}" id="id_organizacion" name="id_organizacion" placeholder="Id Organizacion"  data-validation="required"/>
                                      </div>                                
                                  </div>-->

                            <div class="form-group" id="tabla_fileUpload"  style="{CSS_TABLA_FILEUPLOAD}">
                                    <label for="archivo" class="col-md-4 control-label">{N_ARCHIVO}</label>
                                    <div class="col-md-16">
                                        <input type="file" style="" value="{ARCHIVO}" id="fileUpload2" name="fileUpload2" onchange="cargar_archivo_otro();"/>
                                        <input type="hidden" id="estado_actual" name="estado_actual">
                                        <input type="hidden" id="filename" name="filename" value="{FILENAME}">
                                        <input type="hidden" id="tamano" name="tamano" value="{TAMANO}">
                                        <input type="hidden" id="tipo_doc" name="tipo_doc" value="{TIPO_DOC}">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="15000000" />
                                        <div id="estado" style="display:none;"><img src="{PAHT_TO_IMG}loading3.gif">Cargando</div>
                                  </div>                                
                             </div>
                             <div class="form-group" id="info_archivo_adjunto"  style="display:none;">
                                    <label for="archivo" class="col-md-4 control-label">{N_ARCHIVO}</label>
                                    <div class="col-md-16">
                                        <p class="form-control-static" style="">
                                            <img src="{PAHT_TO_IMG}adjunto.png">
                                            <span id="info_nombre" style="display:inline;">{TXT_OTRO_METODO}&nbsp;</span>
                                            <a href="#" onclick="cancelar_archivo_otro();">
                                                (<img src="{PAHT_TO_IMG}delete.png" width="12" height="12">
                                                Eliminar)
                                            </a>
                                        </p>                      
                                  </div>                                
                             </div>
                            <!--                                
                        <div class="form-group">
                                        <label for="nom_archivo" class="col-md-2 control-label">Nom Archivo</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{NOM_ARCHIVO}" id="nom_archivo" name="nom_archivo" placeholder="Nom Archivo" data-validation="required"/>
                                      </div>                                
                                  </div>
                        <!--<div class="form-group">
                                        <label for="archivo" class="col-md-2 control-label">Archivo</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{ARCHIVO}" id="archivo" name="archivo" placeholder="Archivo"  data-validation="required"/>
                                      </div>                                
                                  </div>
                            <div class="form-group">
                                        <label for="contenttype" class="col-md-2 control-label">Contenttype</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{CONTENTTYPE}" id="contenttype" name="contenttype" placeholder="Contenttype" data-validation="required"/>
                                      </div>                                
                                  </div>-->
                            <div class="form-group">
                                        <label for="observacion" class="col-md-4 control-label">{N_OBSERVACION}</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="3" id="observacion" name="observacion" >{OBSERVACION}</textarea>                                                                                    
                                      </div>                                
                                  </div>

                            <!--
                            <div class="form-group">
                                        <label for="hh" class="col-md-2 control-label">Hh</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{HH}" id="hh" name="hh" placeholder="Hh"  data-validation="required"/>
                                      </div>                                
                                  </div>
                          
                            -->
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10">
                                    <!--
                                    <input class="button save" name="guardar" type="button" value="Siguiente" onClick="validar_p1();">
                                    <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">
                                    -->
                                    <button type="button" class="btn btn-primary" onClick="validar_p1();">{N_SIGUIENTE}</button>            
                                    <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">{N_CANCELAR}</button>
                                </div>
                            </div>
                     </form>
            </div>
            <div class="tab-pane active" id="hv-orange">
                <div class="form-group" style="border-bottom: 0px solid #ffffff;">
                    <form id="busquedaFrm-Per" class="form-horizontal" role="form">
                        <div class="form-group">
                                <label for="id_personal" class="col-md-3 control-label">{N_FILTRAR_POR}:</label>
                                <label for="id_personal" class="col-md-1 control-label">{N_COD_EMP}</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="b-id_personal" name="b-id_personal" placeholder="Id Personal" />
                                </div>                                

                                  <label for="nombres" class="col-md-4 control-label">{N_NOMBRES}</label>
                                  <div class="col-md-4">
                                    <input type="text" class="form-control" id="b-nombres" name="b-nombres" placeholder="Nombres" />
                                </div>                                
                            
                                  <label for="apellido_paterno" class="col-md-4 control-label">{N_APELLIDO_PATERNO}</label>
                                  <div class="col-md-4">
                                    <input type="text" class="form-control" id="b-apellido_paterno" name="b-apellido_paterno" placeholder="Apellido Paterno" />
                                </div>  </div>
                        <div class="form-group">
                                <label for="apellido_materno" class="col-md-4 control-label">{N_APELLIDO_MATERNO}</label>
                                  <div class="col-md-4">
                                    <input type="text" class="form-control" id="b-apellido_materno" name="b-apellido_materno" placeholder="Apellido Materno" />
                                </div>   
                                <label for="apellido_materno" class="col-md-4 control-label">{N_ARBOL_ORGANIZACIONAL}</label>
                                <div class="col-md-10" style="">  
                                    
                                    <a href="#" data-toggle="modal" style="" data-target="#myModal-Filtrar-Arbol">[Seleccionar]</a> 
                                    <span id="desc-arbol"></span>
                                    <input type="hidden" value="" id="nivel"/>                                    
                                </div>
                        </div>
                        
                        <div class="modal fade bs-example-modal-lg" id="myModal-Filtrar-Arbol" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog  modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">{N_FILTRAR_POR}</h4>
                                </div>
                                <div class="modal-body">
                                    <iframe width="100%" height="350px" id="b-iframe" frameborder="0" scrolling="no" src="pages/personas/emb_jstree_single.php"></iframe>
                                    <input type="hidden" id="b-id_organizacion" name="b-id_organizacion" value=""/>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">{N_CERRAR}</button>
                                  <button type="button" class="btn btn-default" onClick="limpiar_arbol()">{N_LIMPIAR}</button>
                                  <button type="button" class="btn btn-primary" onClick="filtrar_arbol()">{N_FILTRAR}</button>
                                </div>
                              </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="row-height">
                            <div class="col-md-11" id="div-origen">

                                <select name="origen[]" class="form-control" id="origen" multiple="multiple" style="height: 350px;">
                                    {ORIGEN}                                    
                                </select>
                            </div>
                            <div class="col-md-2 ">
                                <br><br>
                                <input type="button" class="pasar izq" value="Pasar »"><input type="button" class="quitar der" value="« Quitar"><br/>
                                <input type="button" class="pasartodos izq" value="Todos »"><input type="button" class="quitartodos der" value="« Todos">
                            </div>
                            <div class="col-md-11 "  >
                                <select name="destino[]" class="form-control" id="destino" multiple="multiple" style="height: 350px;">
                                    {DESTINO}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10" style="">
                            <strong> Total {TOTAl_PER} {N_PERSONAS}.</strong>
                        </div>
                         <div class="col-md-4 ">
                            </div>
                        <div class="col-md-10 " style="" >
                            <strong id="total-pers-sel"> {TOTAl_PER_SEL} {N_PERSONAS_SELECCIONADAS}.</strong>
                         </div>
                    </div>
                    <div class="form-group">
                        
                        
                        
                        <div class="col-md-offset-2 col-md-10">
                            
                            <button type="button" class="btn btn-primary" onClick="validar_p2();">{N_SIGUIENTE}</button>            
                            <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">{N_CANCELAR}</button>
                            
                            <!--
                            <input class="button save" name="guardar" type="button" value="Siguiente" onClick="validar_p2();">
                            <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">
                            -->
                        </div>
                    </div>
                </div>
                
             </div>
            <div class="tab-pane" id="hv-blue">
                <form id="idFormulario-Data">
                    <div class="table-container">
                        <div class="content-wrapper clear-block">
                            <div id="grid-personal-cap" class="table-container scrollable">                            
                                <input type="hidden" id="num_items_esp" name="num_items_esp" value="{NUM_ITEMS_ESP}"/>
                                <input type="hidden" id="tipo_curso" name="tipo_curso" value="{TIPO_CURSO}"/>
                                <table id="table-pers-capa" class="table table-striped table-condensed" width="100%" style="margin-bottom: 0px;">
                                    <thead>
                                        <tr bgcolor="#FFFFFF" height="30px">
                                            <th width="3%">
                                                <div align="left"> </div>
                                            </th>
                                            <th width="5%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_COD_EMP}</div>
                                                </div>
                                            </th>
                                            <th width="15%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_NOMBRES_APELLIDOS}</div>
                                                </div>
                                            </th>
                                            <th width="7%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_APROBACION}</div>                                                
                                                </div>
                                            </th>
                                            <th width="11%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_NOTA_EVALUACION}</div>
                                                </div>
                                            </th>
                                            <th width="7%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_ASISTENCIA}</div>
                                                </div>
                                            </th>
                                            <th width="3%">
                                                <div align="left">
                                                    <div style="cursor:pointer;display:inline;">{N_OBSERVACION}</div>
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
                        <input type="hidden" id="id"  name="id"  value="{ID}">
                        </div>
                    </form>
                
               <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <!--<input class="button save" name="guardar" type="button" value="{DESC_OPERACION}" onClick="validar(document);">
                        <input class="button " type="button" value="Cancelar" onclick="funcion_volver('{PAGINA_VOLVER}');">-->
                        
                        <button type="button" class="btn btn-primary" onClick="validar(document);" id="btn-guardar">{DESC_OPERACION}</button>            
                        <button type="button" class="btn btn-default" onclick="funcion_volver('{PAGINA_VOLVER}');">{N_CANCELAR}</button>

                        <input type="hidden" id="opc" name="opc" value="{OPC}">
                        
                    </div>
                </div></div>
            </div>
         </div>                         
                        </div> 
        </div>
        </div></div>
        </div></div>
        
        <form enctype="multipart/form-data" id="formuploadajax" method="post">
        
            <input  type="file" id="fileUploadOtro" style="display: none;" name="fileUpload"/>
            <input type="hidden" name="MAX_FILE_SIZE" value="15000000" />
        </form>