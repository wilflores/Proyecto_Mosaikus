{HTML_INICIO_PAG}


<div id="search"
                 class=" panel-container col-md-offset-1  col-xs-6 ">
              <div class="content-panel panel">
                <div class="content">

                  <div id="search-bar" class="search-items">
                       <a class="close-search"   href="#search">
                         <i class="glyphicon glyphicon-remove"></i>
                        </a>
                      <form id="busquedaFrm" > 
                          <input id="b-id_organizacion" name="b-id_organizacion" type="hidden"/>
                      </form>
                        <!--
                        <div class="input-group">
                            <input type="text" class="form-control" id="b-filtro" name="b-filtro" placeholder="Filtrar Listado">
                            <span class="input-group-addon cursor-pointer" id="btn-filtro"><span class="glyphicon glyphicon-search"></span></span>
                        </div>
                        -->
                        <br>
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
            <!-- Fin .panel Panel Busqueda -->

<div id="main-content" class=" panel-container col-xs-17 ">
    <a class="search-show" style="display: none;">
                <i class="glyphicon glyphicon-search" data-toggle="collapse"  href="#search"></i>
              </a>
    <div class="content-panel panel">
        <div class="content">
             <div class="report-heading">

                <div class="row">
                    <div class="panel-title col-xs-18">
                        {TITULO_MODULO}<br/>
                        <span class="small">Fecha: {FECHA}</span><br/> <br/>
                        <span class="small"></span>
                    </div>
                    <div class="col-xs-6 report-logo">
                        <img class="img-responsive" src="diseno/images/logo_empresa/{ID_EMPRESA}_logo_empresa.png">
                    </div>

                </div>
                    <div class="report-footer" style="border-top: 0px solid #cfd8dc;margin-top: 0px;padding-bottom: 0px;padding-top: 0px;">
                        <div class="report-actions">
                            <ul class="nav">
                                <li><i class="icon icon-transmision cursor-pointer" title="Exportar Excel" onclick="exportarExcel();"></i></li>
                                <li><i class="icon icon-alert-print cursor-pointer" title="Generar PDF" onclick="reporte_ao_pdf();"></i></li>
                                <!--<li><i class="glyphicon glyphicon-print"></i></li>
                                <li><i class="glyphicon glyphicon-print"></i></li>-->


                            </ul>
                        </div>    
                    </div>
            </div>
            
            <div class="report-body" id="grid">                    

                    <table class="table table-report  ">
                      <thead>
                      <tr>
                        {TITULO_TABLA}
                      </tr>
                      </thead>
                      <tbody>
                      {TABLA}                                            

                      </tbody>
                    </table>


            </div>
                        
            <div class="report-footer">
                <div class="row">
                  <div class=" col-xs-2 mosaikus-mini-logo">
                    <img class="img-responsive" src="dist/images/logo.png">
                  </div>

                  
                </div>
                <div class="report-actions">
                    <ul class="nav">
                        <li><i class="icon icon-transmision cursor-pointer" title="Exportar Excel" onclick="exportarExcel();"></i></li>
                        <li><i class="icon icon-alert-print cursor-pointer" title="Generar PDF" onclick="reporte_ao_pdf();"></i></li>
                        <!--<li><i class="glyphicon glyphicon-print"></i></li>
                        <li><i class="glyphicon glyphicon-print"></i></li>-->


                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

