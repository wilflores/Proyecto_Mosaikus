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
                                        <span>Exportar</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="Generar PDF" onclick="reporte_ao_pdf();">
                                        <i class="icon icon-alert-print"></i>
                                        <span>Imprimir</span>
                                    </a>
                                </li>                                  
                            </ul>
                        
                            <form id="busquedaFrm" > 
                                <input id="b-id_proceso" name="b-id_proceso" type="hidden"/>
                                <input id="b-id_organizacion" name="b-id_organizacion" type="hidden"/>
                                <input type="hidden" name="cod_link" id="cod_link" value="{COD_LINK}"/>
                                <input type="hidden" name="modo" id="modo" value="{MODO}"/>
                            </form>
                        </div>
                        
                    </div>
                </div>
                <div id="container" role="main">
                    <div class="row">
                        <div class="col-md-11 col-sm-11 col-xs-11">
                             <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_abrir_ao();"><i class="glyphicon glyphicon-folder-open"></i> Abrir Todos</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_cerrar_ao();"><i class="glyphicon glyphicon-folder-close"></i> Cerrar Todos</button>                            
                        </div>

                        <div class="col-md-13 col-sm-13 col-xs-13" style="">
                             <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_create();"><i class="glyphicon glyphicon-asterisk"></i> Crear</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_rename();"><i class="glyphicon glyphicon-pencil"></i> Renombrar</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_delete();"><i class="glyphicon glyphicon-remove"></i> Eliminar</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_abrir();"><i class="glyphicon glyphicon-folder-open"></i> Abrir Todos</button>
                            <button type="button" class="btn btn-primary btn-sm" style="padding-left: 20px;padding-right: 20px;" onclick="demo_cerrar();"><i class="glyphicon glyphicon-folder-close"></i> Cerrar Todos</button>                            
   
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-11 col-sm-11 col-xs-12">                           
                            {DIV_ARBOL_ORGANIZACIONAL}
                        </div>
                        <div class="col-md-11 col-sm-11 col-xs-12" >
                            <div class="jstree-title"> 
                                Árbol de Procesos 
                                &nbsp;&nbsp;<input type="text" value="" style="box-shadow:inset 0 0 4px #eee; width:220px; margin:0; padding:6px 12px; border-radius:4px; border:1px solid silver; font-size:1.1em;" id="demo_q" placeholder="Buscar">
                            </div>  
                            <div id="id-tree-ap">
                                <div id="tree">Seleccione un Área para administrar el Arbol de Procesos</div>
                            </div>
                        </div>
                    </div>
                    
			
                </div>
    <!--
                <iframe id="iframearbol" src="lib/jtreeview/_demo/arbol_procesos.html" frameborder="0" width="100%" height="390px" scrolling="no" style=" "></iframe>
                <div class="col-md-12" style=" ');margin-top: -10px;">&nbsp;</div>-->
            </div>
</div></div>
                              


