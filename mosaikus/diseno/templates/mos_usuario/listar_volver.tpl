{HTML_INICIO_PAG}



<!-- .panel Panel Busqueda  -->
            <div id="search-aux"
                 class=" panel-hidden  panel-container col-md-offset-1  col-xs-5 ">
              <div class="content-panel panel">
                <div class="content">

                  <div id="search-bar-aux" class="search-items">
                        <a class="close-search"   href="#search-aux">
                         <i class="glyphicon glyphicon-remove"></i>
                        </a>
                        <div>
                            
                            <div class="searchform">
                                    <button class="btn btn-info" role="button" data-toggle="collapse" href="#searchform-wrapper-aux">
                                      {N_FILTRO_AVANZADO} |
                                      <i class="glyphicon glyphicon glyphicon-search  "></i>
                                    </button>
                                    <div id="searchform-wrapper-aux" class="collapsible panel-collapse collapse">
                                      <div class="search-title">
                                        {N_FILTRAR_POR}
                                        <a data-toggle="collapse" href="#searchform-wrapper-aux">
                                          {N_MINIMIZAR_FILTRO} | <i class="glyphicon glyphicon glyphicon-triangle-top"></i>
                                        </a>
                                      </div>
                                      
                                      <form id="r-busquedaFrm" >
                                          <div class="form-group">

                                          <button type="button" class="btn btn-default" onClick="r_filtrar_listado();">{N_FILTRAR}</button>
                                        </div>
                                        {CAMPOS_BUSCAR}

                                          
                                        <div class="form-group">

                                          <button type="button" class="btn btn-default" onClick="r_filtrar_listado();">{N_FILTRAR}</button>
                                        </div>
                                          
                                        <input type="hidden" name="mostrar-col" id="r-mostrar-col" value="{MOSTRAR_COL}" />
                                        <input type="hidden" id="r-b-filtro-sencillo" name="b-filtro-sencillo"/>
                                        <!--<input type="hidden" name="reg_por_pag" id="reg_por_pag" value="12"/>-->
                                        <input type="hidden" name="corder" id="r-corder" value="{CORDER}"/>
                                        <input type="hidden" name="sorder" id="r-sorder" value="{SORDER}"/>
                                      </form>
                                      <div class="searh-footer">

                                        <a data-toggle="collapse" href="#searchform-wrapper-aux">
                                          {N_MINIMIZAR_FILTRO} |  <i class="glyphicon glyphicon glyphicon-triangle-top"></i>
                                        </a>
                                      </div>
                                    </div>
                                  </div>


                            
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="r-b-filtro" name="b-filtro" placeholder="{P_FILTRAR_LISTADO}">
                            <span class="input-group-addon cursor-pointer" id="r-btn-filtro"><span class="glyphicon glyphicon-search"></span></span>
                        </div>
                        <div >
                            <?php include 'treemenu.php' ?>
                        </div>
                        <div >
                            <?php include 'treemenu.php' ?>
                        </div>
                        <div  class="table-container scrollable">
                            
                            <br>
                           
                        </div>   

                              {DIV_ARBOL_ORGANIZACIONAL}
                              <br>
                              <?php include 'treemenu.php' ?>
                              {DIV_ARBOL_PROCESO}
                              <br>
                              <?php include 'treemenu.php' ?>
                   
                      </div>

                </div>
              </div>


            </div>
            <!-- Fin .panel Panel Busqueda -->

            <!-- .panel Panel Contenido -->
            <div id="main-content-aux" class=" col-md-offset-1 panel-container col-xs-23 ">

              <a class="search-show" >
                <i class="glyphicon glyphicon-search" data-toggle="collapse"  href="#search-aux"></i>
              </a>

              <div class="content-panel panel">
                <div class="content">

                  <div class="table-container">
 
                      <div class="panel-heading">

                            <div class="row">
                              <div class="panel-title col-xs-10" id="r-div-titulo-mod">
                                {TITULO_MODULO}
                              </div>
                              <div class="panel-actions col-xs-14">
                                <ul class="navbar">
                                  <li class="separator">
                                    <a href="#contenido" onClick=" MostrarContenido();">
                                      <i class="glyphicon glyphicon-menu-left"></i>
                                      <span>{N_VOLVER}</span
                                    </a>
                                  </li>
                                  <li>
                                    <a href="#" data-toggle="modal" data-target="#r-myModal-Mostrar-Colums">
                                      <i class="icon icon-squares"></i>
                                      <span>{N_PERSONALIZAR}</span>
                                    </a>
                                  </li>


                                </ul>


                              </div>
                            </div>

                    </div>
                     <div id="r-grid"  class="table-container scrollable">
                         {TABLA}          
                     </div>
                      
                     
                     <nav class="pager-wrapper">
                         <div class="row" id="r-grid-paginado">
                             {PAGINADO}
                                <!--
                              <div class="col-xs-5">Tolal: 150 registros</div>
                              <div class="col-xs-6">

                                <div class="row">

                                  <div class="col-xs-7">
                                    
                                    <select class="form-control" name="reg_por_pag" id="reg_por_pag">
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        
                                    </select>
                                  </div>
                                  <label class="col-xs-17">Registros por pagina</label>
                                </div>

                              </div>
                              <div class="col-xs-12">
                                <ul class="pagination">
                                  <li>
                                    <a href="#" aria-label="Previous">
                                      <span aria-hidden="true">&laquo;</span>
                                    </a>
                                  </li>
                                  <li><a href="#">1</a></li>
                                  <li><a href="#">2</a></li>
                                  <li><a href="#">3</a></li>
                                  <li><a href="#">4</a></li>
                                  <li><a href="#">5</a></li>
                                  <li>
                                    <a href="#" aria-label="Next">
                                      <span aria-hidden="true">&raquo;</span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                                -->
                            </div>
                          </nav>


                    </div>

                </div>
              </div>

            </div>
            <!-- Fin .panel Panel Contenido -->
            <!-- .panel Panel Detalle -->
            <div id="detail-content-aux" class=" panel-hidden panel-container col-xs-11 ">

              <div class="content-panel panel">
                <div class="content">
                  <a class="close-detail"   href="#search">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>
                  <?php include '..\templates\base\detail.php' ?>

                </div>
              </div>
            </div>
            
        <div class="modal fade" id="r-myModal-Mostrar-Colums" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="r-myModalLabel">{N_OPCIONES_VISUALIZACION}</h4>
                </div>
                  <div class="modal-body">
                    <form id="r-FrmMostrar-Columns" class="form-horizontal form-horizontal-form form-horizontal-form-left" role="form"> 
                        <div class="checkbox">
                             
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="Interno" id="InternoPer" checked="checked" onclick="r_marcar_desmarcar_checked_columns(this.checked);">   &nbsp;
                                      {N_TODOS}</label>
                            
                            </div>
                        {CAMPOS_MOSTRAR_COLUMNS}
                     </form>
                    
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">{N_CERRAR}</button>
                  <button type="button" class="btn btn-primary" onclick="r_filtrar_mostrar_colums();">{N_SELECCIONAR}</button>
                </div>
              </div>
            </div>
        </div>




