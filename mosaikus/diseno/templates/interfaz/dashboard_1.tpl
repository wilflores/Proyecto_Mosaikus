{HTML_INICIO_PAG}



<!-- .panel Panel Busqueda  -->
<div class="row">
    <div id="search-dashboard" class="  panel-container col-md-offset-1  col-xs-7 ">
        <div class="content-panel panel">
            <div class="content">
                <div id="search-bar-dashboard" class="search-items">                        
                    <div>                            
                        <div class="searchform">
                            <button class="btn btn-info" role="button" data-toggle="collapse" href="#searchform-wrapper-dashboard">
                                Filtro Avanzado |
                                <i class="glyphicon glyphicon glyphicon-search  "></i>
                            </button>
                            <div id="searchform-wrapper-dashboard" class="collapsible panel-collapse collapse">
                                <div class="search-title">
                                    Filtrar por
                                    <a data-toggle="collapse" href="#searchform-wrapper-dashboard">
                                        Minimizar Filtro | <i class="glyphicon glyphicon glyphicon-triangle-top"></i>
                                    </a>
                                </div>
                                <form id="busquedaFrm-Filtro" >  
                                    <!--<div class="form-group">
                                        <button type="button" class="btn btn-primary " onClick="filtrar_grafico();">Filtrar</button>                                          
                                    </div>-->
                                    {CAMPOS_BUSCAR_FILTRO}
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary " onClick="filtrar_grafico();">Filtrar</button>
                                          <!--<button type="button" class="btn btn-default " onClick="activar_filtrar_listado();">Limpiar</button>-->
                                    </div>                                          
                                    
                                </form>
                                <div class="searh-footer">
                                    <a data-toggle="collapse" href="#searchform-wrapper-dashboard">
                                        Minimizar Filtro |  <i class="glyphicon glyphicon glyphicon-triangle-top"></i>
                                    </a>
                                </div>
                            </div>
                        </div>                            
                    </div>                        
                    <div  class="table-container scrollable">
                        {DIV_ARBOL_ORGANIZACIONAL}
                        <br>
                        <?php include 'treemenu.php' ?>
                    </div>
                    <div >
                        <?php include 'treemenu.php' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <div id="main-content-grafico" class=" panel-container col-xs-16 ">              
        <div class="content-panel panel">
            <div class="content">
                <div class="tabs"> 
                    <ul id="tabs-hv" class="nav nav-tabs" data-tabs="tabs">
                        <li><a href="#gf-barra" data-toggle="tab" style="padding-right: 20px;padding-left: 20px;">Barras</a></li>  
                        <li><a href="#gf-linea" data-toggle="tab" style="padding-right: 20px;padding-left: 20px;">Líneas</a></li>
                        <li><a href="#gf-pareto" data-toggle="tab" style="padding-right: 20px;padding-left: 20px;">Línea Unificada</a></li>
                        <li><a href="#gf-barra-2" data-toggle="tab" style="padding-right: 20px;padding-left: 20px;">Atrasos de Verificación</a></li>
                        <li><a href="#gf-barra-3" data-toggle="tab" style="padding-right: 20px;padding-left: 20px;">Atrasos de Acciones</a></li>

                    </ul>
                    <div id="my-tab-content" class="tab-content"  style="padding: 45px 2%;">
                        <div class="tab-pane active" id="gf-barra">
                            <div id="container-grafico-bar" style="min-width: 310px; max-width: 800px; height: 500px; margin: 0 auto"></div>
                            <input type="hidden" id="data-grafico-bar" value="{DATA_GRAFICO_BAR}"/>
                            <input type="hidden" id="nombre-colum-grafico-bar" value="{NOMBRE_COLUM_GRAFICO_BAR}"/>
                            <input type="hidden" id="subtitle-grafico-bar" value="{SUBTITLE_GRAFICO_BAR}"/>
                        </div>
                        <div class="tab-pane " id="gf-linea">
                            <div class="row">
                                <div id="container-grafico-linea" class="col-xs-14" style=" min-width: 610px;height: 500px; margin: 0 auto"></div>
                            <input type="hidden" id="data-grafico-linea" value="{DATA_GRAFICO_LINEA}"/>
                            <input type="hidden" id="nombre-colum-grafico-linea" value="{NOMBRE_COLUM_GRAFICO_LINEA}"/>
                            <input type="hidden" id="subtitle-grafico-linea" value="{SUBTITLE_GRAFICO_LINEA}"/>
                            </div>
                        </div>
                        <div class="tab-pane " id="gf-pareto">
                            <div class="row">
                                <div id="container-grafico-linea-uni" class="col-xs-14" style=" min-width: 610px;height: 500px; margin: 0 auto"></div>
                            <input type="hidden" id="data-grafico-linea-uni" value="{DATA_GRAFICO_LINEA_UNI}"/>
                            <input type="hidden" id="nombre-colum-grafico-linea-uni" value="{NOMBRE_COLUM_GRAFICO_LINEA_UNI}"/>
                            <input type="hidden" id="subtitle-grafico-linea-uni" value="{SUBTITLE_GRAFICO_LINEA_UNI}"/>
                            </div>
                        </div>
                        <div class="tab-pane " id="gf-barra-2">
                            <div id="container-grafico-bar-2" style="min-width: 310px; max-width: 800px; height: 500px; margin: 0 auto"></div>
                            <input type="hidden" id="data-grafico-bar-2" value="{DATA_GRAFICO_BAR_2}"/>
                            <input type="hidden" id="nombre-colum-grafico-bar-2" value="{NOMBRE_COLUM_GRAFICO_BAR_2}"/>
                            <input type="hidden" id="subtitle-grafico-bar-2" value="{SUBTITLE_GRAFICO_BAR_2}"/>
                        </div>
                        <div class="tab-pane " id="gf-barra-3">
                            <div id="container-grafico-bar-3" style="min-width: 310px; max-width: 800px; height: 500px; margin: 0 auto"></div>
                            <input type="hidden" id="data-grafico-bar-3" value="{DATA_GRAFICO_BAR_3}"/>
                            <input type="hidden" id="nombre-colum-grafico-bar-3" value="{NOMBRE_COLUM_GRAFICO_BAR_3}"/>
                            <input type="hidden" id="subtitle-grafico-bar-3" value="{SUBTITLE_GRAFICO_BAR_3}"/>
                        </div>
                    </div>                                
                </div>                        

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div id="search" class=" panel-hidden  panel-container col-md-offset-1  col-xs-5 ">
        <div class="content-panel panel">
            <div class="content">
                <div id="search-bar" class="search-items">
                    <a class="close-search"   href="#search">
                        <i class="glyphicon glyphicon-remove"></i>
                    </a>
                    <div>                            
                        <div class="searchform">
                            <button class="btn btn-info" role="button" data-toggle="collapse" href="#searchform-wrapper">
                                Filtro Avanzado |
                                <i class="glyphicon glyphicon glyphicon-search  "></i>
                            </button>
                            <div id="searchform-wrapper" class="collapsible panel-collapse collapse">
                                <div class="search-title">
                                    Filtrar por
                                    <a data-toggle="collapse" href="#searchform-wrapper">
                                      Minimizar Filtro | <i class="glyphicon glyphicon glyphicon-triangle-top"></i>
                                    </a>
                                </div>
                                <form id="busquedaFrm" >  
                                    <div class="form-group">

                                        <button type="button" class="btn btn-primary " onClick="filtrar_listado();">Filtrar</button>

                                    </div>
                                    {CAMPOS_BUSCAR}                                          
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary " onClick="filtrar_listado();">Filtrar</button>
                                        <!--<button type="button" class="btn btn-default " onClick="activar_filtrar_listado();">Limpiar</button>-->
                                    </div>

                                    <input type="hidden" name="mostrar-col" id="mostrar-col" value="{MOSTRAR_COL}" />
                                    <input type="hidden" id="b-filtro-sencillo" name="b-filtro-sencillo"/>
                                    <!--<input type="hidden" name="reg_por_pag" id="reg_por_pag" value="12"/>-->
                                    <input type="hidden" name="corder" id="corder" value="{CORDER}"/>
                                    <input type="hidden" name="sorder" id="sorder" value="{SORDER}"/>
                                </form>
                                <div class="searh-footer">
                                    <a data-toggle="collapse" href="#searchform-wrapper">
                                      Minimizar Filtro |  <i class="glyphicon glyphicon glyphicon-triangle-top"></i>
                                    </a>
                                </div>
                            </div>
                        </div>                           
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" id="b-filtro" name="b-filtro" placeholder="Filtrar Listado">
                        <span class="input-group-addon cursor-pointer" id="btn-filtro"><span class="glyphicon glyphicon-search"></span></span>
                    </div>
                        
                </div>
            </div>
        </div>
    </div>
            <!-- Fin .panel Panel Busqueda -->

            <!-- .panel Panel Contenido -->
            <div id="main-content" class=" col-md-offset-1 panel-container col-xs-23 ">

              <a class="search-show" >
                <i class="glyphicon glyphicon-search" data-toggle="collapse"  href="#search"></i>
              </a>

              <div class="content-panel panel">
                <div class="content">

                  <div class="table-container">
 
                      <div class="panel-heading">

                            <div class="row">
                              <div class="panel-title col-xs-10"  id="div-titulo-mod">
                                {TITULO_MODULO}
                              </div>
                              <div class="panel-actions col-xs-14">
                                <ul class="navbar">
                                  <li style="{PERMISO_INGRESAR}">
                                    <a href="javascript:{JS_NUEVO}" >
                                      <i class="icon icon-new-document"></i>
                                      <span>Nuevo</span>
                                    </a>
                                  </li>        
                                  <li>
                                    <a href="#"  onClick="exportarExcel();">
                                      <i class="icon icon-transmision"></i>
                                      <span>Exportar</span>
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" data-toggle="modal" data-target="#myModal-Mostrar-Colums">
                                      <i class="icon icon-squares"></i>
                                      <span>Personalizar</span>
                                    </a>
                                  </li>
                                  {OTRAS_OPCIONES}

                                </ul>


                              </div>
                            </div>

                    </div>
                     
                     <div id="grid" class="table-container scrollable">
                         {TABLA}          
                     </div>
                      
                     
                     <nav class="pager-wrapper">
                         <div class="row" id="grid-paginado">
                             {PAGINADO}
                               
                            </div>
                          </nav>


                    </div>

                </div>
              </div>

            </div>
            </div>  
            
        <div class="modal fade" id="myModal-Mostrar-Colums" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Opciones de visualización</h4>
                </div>
                  <div class="modal-body">
                    <form id="FrmMostrar-Columns" class="form-horizontal form-horizontal-form form-horizontal-form-left" role="form"> 
                        <div class="checkbox">
                             
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="Interno" id="InternoPer" checked="checked" onclick="marcar_desmarcar_checked_columns(this.checked);">   &nbsp;
                                      Todos</label>
                            
                            </div>
                        {CAMPOS_MOSTRAR_COLUMNS}
                     </form>
                    
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" onclick="filtrar_mostrar_colums();">Seleccionar</button>
                </div>
              </div>
            </div>
        </div>




