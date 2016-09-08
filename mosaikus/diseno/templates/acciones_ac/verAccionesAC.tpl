<div class="col-xs-24">
    <div class="row">
        <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc">
            <thead>
            <tr height="30px">
                <th width="100%"><div align="center">1. Desvio</div></th>
            </tr>
            </thead>

        </table>
    </div>
    <div class="row">
        <dl class="col-xs-6">
            <dt>{N_DESCRICION} </dt>
            <dd> {TIPO}</dd>

        </dl>
        <dl class="col-xs-6">
            <dt>{N_ACCION}</dt>
            <dd> {ACCION}</dd>
        </dl>
        <dl class="col-xs-6">
            <dt>{N_ELABORO} </dt>
            <dd> {RESPONSABLE}</dd>
        </dl>
        <dl class="col-xs-6">
            <dt>{N_FECHA}</dt>
            <dd> {FECHA_ACORDADA}</dd>
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
            <tr>
                <th style="width: 20%;">{NA_TIPO}</th>
                <th style="width: 60%">{NA_ACCION}</th>
                <th style="width: 20%">{NA_FECHA_EJECUTADA}</th>
            </tr>
            </thead>
            <tbody>
            {TABLA_TRAZA}
            </tbody>
        </table>
    </div>
</div>