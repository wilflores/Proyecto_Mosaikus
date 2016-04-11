{HTML_INICIO_PAG}


<div id="main-content" class=" col-md-offset-4 panel-container col-xs-16 ">
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
            </div>
            
            <div class="report-body">                    

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
                        <li><i class="icon icon-transmision cursor-pointer" onclick="exportarExcel();"></i></li>
                        <li><i class="icon icon-view-document cursor-pointer" onclick="reporte_ao_pdf();"></i></li>
                        <!--<li><i class="glyphicon glyphicon-print"></i></li>
                        <li><i class="glyphicon glyphicon-print"></i></li>-->


                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

