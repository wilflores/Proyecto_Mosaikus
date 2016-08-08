<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    </head>
<body> 

<htmlpageheader name="MyHeader1">
    
     <div class="report-heading">

                <div class="row">
                    <div class="panel-title col-xs-18">
                        Acción Correctiva - Reporte Individual<br/>
                        <span class="small">Fecha de Generación: {FECHA}</span><br/> <br/>
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
    <sethtmlpageheader name="MyHeader1" value="on" show-this-page="1"  />
<sethtmlpagefooter name="MyFooter1" value="on" />
            
            
    <br/> 
<div id="main-content" class=" panel-container col-xs-23 ">
    <div class="content-panel panel">
        <div class="content">
            
            
            <div class="report-body">                    

                <table class="table table-striped ">
                      <thead>
                          <tr style="border-top: 0px solid #cfd8dc;">
                        <th colspan="2" align="left" style="border-top: 0px solid #cfd8dc;">1. Identificación</th>

                      </tr>
                      </thead>
                      <tbody>
                      <tr class="even gradeC">
                          <td style="width: 150px;">Estado</td>
                        <td>{ESTADO}</td>

                      </tr>
                        <tr class="odd gradeC">
                        <td>{N_ID}</td>
                        <td>{ID}</td>

                      </tr>
                      <tr class="even gradeX">
                        <td>{N_ALTO_POTENCIAL}</td>
                        <td><input type="checkbox" name="alto_potencial" id="alto_potencial" value="S" {CHECKED_ALTO_POTENCIAL}></td>

                      </tr>
                      <tr class="odd gradeC">
                        <td>{N_ORIGEN_HALLAZGO}</td>
                        <td>{ORIGEN_HALLAZGO}</td>

                      </tr>
                      <tr class="even gradeA">
                        <td>{N_FECHA_GENERACION}</td>
                        <td>{FECHA_GENERACION}</td>

                      </tr>
                      <tr class="odd gradeA">
                        <td>{N_DESCRIPCION}</td>
                        <td>{DESCRIPCION}</td>

                      </tr>
                      <tr class="even gradeA">
                        <td>{N_ANALISIS_CAUSAL}</td>
                        <td>{ANALISIS_CAUSAL}</td>

                      </tr>
                      <tr class="odd gradeA">
                        <td>{N_RESPONSABLE_ANALISIS}</td>
                        <td>{RESPONSABLE_ANALISIS}</td>

                      </tr>
                       {CAMPOS_DINAMICOS}
                        {ID_ORGANIZACIONES}
                        {ID_PROCESOS}
                        <tr class="{CLASES_A}">
                                <td>{N_FECHA_ACORDADA}</td>
                                <td>{FECHA_ACORDADA}&nbsp;</td>                                                          
                        </tr>
                        <tr class="{CLASES_B}">
                                <td>{N_FECHA_REALIZADA}</td>
                                <td>{FECHA_REALIZADA}&nbsp;</td>                 
                        </tr>
                        <tr class="{CLASES_A}">
                                <td>{N_ID_RESPONSABLE_SEGUI}</td>
                                <td>{ID_RESPONSABLE_SEGUI}&nbsp;</td>
                        </tr>
                        <tr class="{CLASES_B}">
                                <td>{NA_TRAZABILIDAD}</td>
                                <td>{TRAZABILIDAD}&nbsp;</td>                 
                        </tr>
                      </tbody>
                    </table>
                
                    <table class="table table-report  ">
                        <thead>
                          <tr style="border-top: 0px solid #cfd8dc;">
                        <th colspan="2" align="left" style="border: 0px solid #cfd8dc;background-color: white;">2. Acciones Correctivas</th>

                      </tr>
                      </thead>
                      <thead>
                      <tr>
                         <!--<th style="width: 12%;">{NA_TIPO}</th>-->
                        <th style="width: 47%">{NA_ACCION}</th>
                        <th style="width: 10%">{NA_FECHA_ACORDADA}</th>
                        <th style="width: 10%">{NA_FECHA_REALIZADA}</th>
                        <th style="width: 12%">{NA_ID_RESPONSABLE}</th>
                        <th style="width: 9%">{NA_ESTADO_SEGUIMIENTO}</th>


                      </tr>
                      </thead>
                      <tbody>
                      {TABLA_ACCIONES}                                            

                      </tbody>
                    </table>
                      
                    <table class="table table-report  ">
                        <thead>
                          <tr style="border-top: 0px solid #cfd8dc;">
                        <th colspan="2" align="left" style="border: 0px solid #cfd8dc;background-color: white;">3. Trazabilidad de Acciones Correctivas</th>

                      </tr>
                      </thead>
                      <thead>
                      <tr>
                            <!--<th style="width: 12%;">{NA_TIPO}</th>-->
                            <th style="width: 40%">{NA_ACCION}</th>
                            <th style="width: 60%">{NA_TRAZABILIDAD}</th>                        

                      </tr>
                      </thead>
                      <tbody>
                      {TABLA_TRAZA}                                            

                      </tbody>
                    </table>


            </div>
                        
            
        </div>
    </div>

</div>
                  


</body>
</html>

