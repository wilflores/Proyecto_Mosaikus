<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    </head>
<body> 

<htmlpageheader name="MyHeader1">
    
     <div class="report-heading">

                <div class="row">
                    <div class="panel-title col-xs-18">
                        Árbol de Procesos<br/>
                        <span class="small">Fecha: {FECHA}</span><br/> <br/>
                        <span class="small"></span>
                    </div>
                    <div class="col-xs-4 report-logo">
                        <img class="img-responsive" src="{HOME}/diseno/images/logo_empresa/{ID_EMPRESA}_logo_empresa_report.png">
                    </div>

                </div>
            </div>
    
                       
</htmlpageheader>
                    
    <htmlpagefooter name="MyFooter1">
        <div class="report-footer">
                <div class="row">
                  <div class=" col-xs-2 mosaikus-mini-logo">
                    <img class="img-responsive" src="{HOME}/dist/images/logo_report.png">
                  </div>
                  <div class=" col-xs-8">
                    &nbsp;
                  </div>
                  <div class=" col-xs-2">
                    {N_PAG}
                  </div>
                </div>
                
            </div>

    </htmlpagefooter>
            
            
            
    <br/>        <br/>
<div id="main-content" class=" panel-container col-xs-23 ">
    <div class="content-panel panel">
        <div class="content">
            
            
            <div class="report-body">                    

                    <table class="table table-report  ">
                      <thead>
                      <tr>
                         {TITULO}


                      </tr>
                      </thead>
                      <tbody>
                      {TABLA}                                            

                      </tbody>
                    </table>


            </div>
                        
            
        </div>
    </div>

</div>
                  
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1" />
<sethtmlpagefooter name="MyFooter1" value="on" />

</body>
</html>

