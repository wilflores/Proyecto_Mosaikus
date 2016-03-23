{HTML_INICIO_PAG}
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
                                    <a href="javascript:volverindex();" >
                                      <i class="icon icon-back-document"></i>
                                      <span>Volver</span>
                                    </a>
                                  </li>        
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
            <!-- Fin .panel Panel Contenido -->
            <!-- .panel Panel Detalle -->
            <div id="detail-content" class=" panel-hidden panel-container col-xs-11 ">

              <div class="content-panel panel">
                <div class="content">
                  <a class="close-detail" href="#detail-content">
                    <i class="glyphicon glyphicon-remove"></i>
                  </a>
                  <?php include '..\templates\base\detail.php' ?>

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




