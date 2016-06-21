<div class="row">
          <dl class="col-xs-12">
            <dt>{N_ESTADO} </dt>
            <dd> {ESTADO}</dd>

          </dl>
          
          <dl class="col-xs-12">
            <dt>{N_FECHA_EJECUTADA} </dt>
            <dd> {FECHA_EJECUTADA}</dd>

          </dl>
        </div>
        <div class="row">
          <dl class="col-xs-12">
            <dt>{N_ID_DOCUMENTO} </dt>
            <dd> {ID_DOCUMENTO}</dd>

          </dl>
          <dl class="col-xs-12">
            <dt>{N_ID_RESPONSABLE} </dt>
            <dd> {ID_RESPONSABLE}</dd>

          </dl>
        </div>
        <div class="row">
          <dl class="col-xs-24">
            <dt>{N_ID_AREA} </dt>
            <dd> {OPTION_AREAS}</dd>

          </dl>
          
        </div>
        <div class="row">
          <dl class="col-xs-24">
            <dt>{N_ID_CARGO} </dt>
            <dd> {OPTION_CARGOS}</dd>

          </dl>
          
        </div>
        <div class="row">
          <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc"> 
              <thead>
                  <tr height="30px">
                        <th width="100%"><div align="center">Personal Capacitado</div></th> 
                   </tr>
              </thead>
              <tbody>
                 {DESTINO} 
              </tbody>
          </table>
          
        </div>
        <div class="row">
          <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc"> 
              <thead>
                  <tr height="30px">
                        <th width="100%"><div align="center">{N_EVIDENCIAS}</div></th> 
                   </tr>
              </thead>              
          </table>
          {ARCHIVOS_ADJUNTOS}
        </div>
           
                            