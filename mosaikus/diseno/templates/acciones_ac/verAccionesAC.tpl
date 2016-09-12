<div class="col-xs-24">
    <div class="row">
        <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc">
            <thead>
            <tr height="30px">
                <th width="100%"><div align="center">1. {N_DESVIO}</div></th>
            </tr>
            </thead>

        </table>
    </div>
    <div class="row">
        <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc">
            <tr height="30px">
                <th width="20%"><div align="center">{N_ID_CORRECION}</div></th>
                <td width="80%"><div align="left">{ID_CORRECION}</div></td>
            </tr>
            <tr height="30px">
                <th width="20%"><div align="center">{N_DESCRIPCION}</div></th>
                <td width="80%"><div align="left">{DESCRIPCION}</div></td>
            </tr>
            <tr height="30px">
                <th width="20%"><div align="center">{N_FECHA_GENERACION}</div></th>
                <td width="80%"><div align="left">{FECHA_GENERACION}</div></td>
            </tr>
            <tr height="30px">
                <th width="20%"><div align="center">{N_ACCION}</div></th>
                <td width="80%"><div align="left">{ACCION}</div></td>
            </tr>
            <tr height="30px">
                <th width="20%"><div align="center">{N_ELABORO}</div></th>
                <td width="80%"><div align="left">{RESPONSABLE}</div></td>
            </tr>
            <tr height="30px">
                <th width="20%"><div align="center">{N_FECHA_EJECUTADA}</div></th>
                <td width="80%"><div align="left">{FECHA_ACORDADA}</div></td>
            </tr>
        </table>
    </div>

    <div class="row">
        <table width="100%" class="table table-striped table-condensed" id="tblListaDistribucionDoc">
            <thead>
            <tr height="30px">
                <th width="100%" colspan="6"><div align="center">2. {N_TRAZABILIDAD_ACCION}</div></th>
            </tr>
            </thead>
            <thead>
            <tr>
                <th style="width: 20%;">{N_TIPO}</th>
                <th style="width: 60%">{N_ACCION}</th>
                <th style="width: 20%">{N_FECHA_EJECUTADA}</th>
            </tr>
            </thead>
            <tbody>
            {TABLA_TRAZA}
            </tbody>
        </table>
    </div>
</div>