<div class="col-xs-24">
        <div class="row">
              <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc"> 
                  <thead>
                      <tr height="30px">
                            <th width="100%"><div align="center">1. Identificación</div></th> 
                       </tr>
                  </thead>

              </table>
         </div>
        <div class="row">
            <dl class="col-xs-12">
              <dt>Estado </dt>
              <dd> {ESTADO}</dd>

            </dl>
            <dl class="col-xs-12">
              <dt>{N_ID} </dt>
              <dd> {ID}</dd>

            </dl>
            
        </div>
        <div class="row">
           
            <dl class="col-xs-12">
              <dt>{N_REPORTADO_POR} </dt>
              <dd> {REPORTADO_POR}</dd>

            </dl>

            <dl class="col-xs-12">
              <dt>{N_FECHA_GENERACION} </dt>
              <dd> {FECHA_GENERACION}</dd>

            </dl>
        </div>
        <div class="row">
            <dl class="col-xs-24">
              <dt>{N_ID_ORGANIZACION} </dt>
              <dd> {ID_ORGANIZACION}</dd>

            </dl>            
        </div>
        <div class="row">
            <dl class="col-xs-12">
              <dt>{N_ALTO_POTENCIAL} </dt>
              <dd> {ALTO_POTENCIAL}</dd>

            </dl>

            <dl class="col-xs-12">
              <dt>{N_ORIGEN_HALLAZGO} </dt>
              <dd> {ORIGEN_HALLAZGO}</dd>

            </dl>
        </div>
        <div class="row">
            <dl class="col-xs-24">
              <dt>{N_DESCRIPCION} </dt>
              <dd> {DESCRIPCION}</dd>

            </dl>            
        </div>
        <div class="row">
            <dl class="col-xs-12">
              <dt>{N_RESPONSABLE_DESVIO} </dt>
              <dd> {RESPONSABLE_DESVIO}</dd>

            </dl>

            <dl class="col-xs-12">
              <dt>{N_RESPONSABLE_ANALISIS} </dt>
              <dd> {RESPONSABLE_ANALISIS}</dd>

            </dl>
        </div>
        <div class="row">
            <dl class="col-xs-24">
              <dt>{N_ANALISIS_CAUSAL} </dt>
              <dd> {ANALISIS_CAUSAL}</dd>

            </dl>            
        </div>                                                                   
       
        <div class="row">
            <dl class="col-xs-24">
              <dt>{N_ID_PROCESO} </dt>
              <dd> {ID_PROCESO}</dd>

            </dl>            
        </div>
        <div class="row">
              <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc"> 
                  <thead>
                      <tr height="30px">
                          <th width="100%" colspan="6"><div align="center">2. Acciones Correctivas</div></th> 
                       </tr>
                  </thead>
                  <thead>
                      <tr height="30px">
                            <th style="width: 12%;">{NA_TIPO}</th>
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
         </div>       
         <div class="row">
              <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc"> 
                  <thead>
                      <tr height="30px">
                          <th width="100%" colspan="6"><div align="center">3. Trazabilidad de Acciones Correctivas</div></th> 
                       </tr>
                  </thead>
                  <thead>
                      <tr>
                            <th style="width: 12%;">{NA_TIPO}</th>
                            <th style="width: 44%">{NA_ACCION}</th>
                            <th style="width: 44%">{NA_TRAZABILIDAD}</th>                        

                      </tr>
                  </thead>
                  <tbody>
                      {TABLA_TRAZA}   
                      </tbody>
              </table>
         </div> 
        <div class="row">
              <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc"> 
                  <thead>
                      <tr height="30px">
                            <th width="100%"><div align="center">4. Verificación de Eficacia</div></th> 
                       </tr>
                  </thead>

              </table>
         </div>            
        <div class="row">
            <dl class="col-xs-12">
              <dt>{N_FECHA_ACORDADA} </dt>
              <dd> {FECHA_ACORDADA}</dd>

            </dl>

            <dl class="col-xs-12">
              <dt>{N_FECHA_REALIZADA} </dt>
              <dd> {FECHA_REALIZADA}</dd>

            </dl>
        </div>
       
        <div class="row">
            <dl class="col-xs-24">
              <dt>{N_ID_RESPONSABLE_SEGUI} </dt>
              <dd> {ID_RESPONSABLE_SEGUI}</dd>

            </dl>            
        </div>
                                                        
                            
</div>