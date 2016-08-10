{HTML_INICIO_PAG}


    <div id="main-content" class=" col-md-offset-1 panel-container col-xs-23 ">            
        <div class="content-panel panel">
            <div class="content">
                <div class="panel-heading">
                    <div class="row">
                        <div class="panel-title col-xs-10"  id="div-titulo-mod">
                          {TITULO_MODULO}
                        </div>
                        <div class="panel-actions col-xs-14" style=" height: 40px;">
                            <ul class="navbar">                                       
                                <li>
                                    <a href="#"  onClick="exportarExcel();">
                                        <i class="icon icon-transmision"></i>
                                        <span>{N_EXPORTAR}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="Generar PDF" onclick="reporte_ao_pdf();">
                                        <i class="icon icon-alert-print"></i>
                                        <span>{N_IMPRIMIR}</span>
                                    </a>
                                </li>                                  
                            </ul>
                            <form id="busquedaFrm" > 
                                <input id="b-id_organizacion" name="b-id_organizacion" type="hidden"/>
                                <input type="hidden" name="cod_link" id="cod_link" value="{COD_LINK}"/>
                                <input type="hidden" name="modo" id="modo" value="{MODO}"/>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="container" role="main">
                    <div class="row">
                        <div class="col-md-16 col-sm-16 col-xs-16">
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_create();"><i class="glyphicon glyphicon-asterisk"></i> {N_CREAR}</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_rename();"><i class="glyphicon glyphicon-pencil"></i> {N_RENOMBRAR}</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_delete();"><i class="glyphicon glyphicon-remove"></i> {N_ELIMINAR}</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_abrir();"><i class="glyphicon glyphicon-folder-open"></i> {N_ABRIR_TODOS}</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_cerrar();"><i class="glyphicon glyphicon-folder-close"></i> {N_CERRAR_TODOS}</button>
                        </div>

                        <div class="col-md-8 col-sm-8 col-xs-8" style="text-align:right;">
                                <input type="text" value="" style="box-shadow:inset 0 0 4px #eee; width:220px; margin:0; padding:6px 12px; border-radius:4px; border:1px solid silver; font-size:1.1em;" id="demo_q" placeholder="{N_BUSCAR}">
                        </div>
                    </div>
                    <br/>
                    <div id="tree"></div>
			
                </div>
    <!--
    
   
        <iframe id="iframearbol" src="lib/jtreeview/_demo/index2.html" frameborder="0" width="100%" height="390px" scrolling="no" style=" "></iframe>
        <div class="col-md-12" style=" ');margin-top: -10px;">&nbsp;</div> -->
            </div>
        </div>
                  
    </div>
                            
<div class="modal fade bs-example-modal-lg" id="myModal-Area-Espejo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModal-Area-Espejo-Titulo">{N_ARBOL_ORGANIZACIONAL}</h4>
            </div>
            <div class="modal-body" id="modal-body-area-espejo">
                
            </div>
            <div class="modal-footer">
                <input type="hidden" id="origen-id-area" />
                <input type="hidden" id="relacion-id-area" />
              <button type="button" class="btn btn-default" data-dismiss="modal">{N_CERRAR}</button>                                  
              <button type="button" class="btn btn-primary" onClick="asociar_area();">{N_VINCULAR_AREA}</button>
              <button type="button" class="btn btn-primary" onClick="eliminar_area();">{N_ELIMINAR_VINCULO_AREA}</button>
            </div>
        </div>
    </div>
</div>

