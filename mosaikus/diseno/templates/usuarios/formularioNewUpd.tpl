<tr>
    <td class="title">Login:</td>
    <td class="td-table-data"><input type="text" id="login" name="login" maxlength="20" size="20" class="form-box" value="{LOGIN}">&nbsp;*</td>
  </tr>
  <tr>
    <td class="title">Nombre:</td>
    <td class="td-table-data"><input type="text" name="nombre" id="nombre" maxlength="100" size="40" class="form-box" value="{NOMBRE}">&nbsp;*</td>
  </tr>
  <tr>
    <td class="title">Cedula:</td>
    <td class="td-table-data"><input type="text" name="cedula" id="cedula" maxlength="100" size="40" class="form-box" value="{CEDULA}">&nbsp;</td>
  </tr>
  <tr>
    <td class="title">Password:</td>
    <td class="td-table-data"><input type=password id="password" name="password" maxlength="100" size="40" class="form-box" value="{PASSWORD}">&nbsp;*</td>
  </tr>
  <tr>
    <td class="title">Confirmar&nbsp;Password:</td>
    <td class="td-table-data"><input type=password name="password2" id="password2" maxlength="100" size="40" class="form-box" value="{PASSWORD}">&nbsp;*</td>
  </tr>
  <tr>
    <td class="title">Correo:</td>
    <td class="td-table-data"><input type="text" id="correo" name="correo" maxlength="100" size="50" class="form-box" value="{CORREO}"></td>
  </tr>

<tr>
    <td class="title">Estado:</td>
    <td class="td-table-data">{ESTADO}
        <select id="id_rol" name="id_rol" class="form-box" style="display: none;">
            <option value="0">Seleccione</option>
            {ROL}
        </select>
            {ES_ADMIN_CURSO}
    </td>
  </tr>
<!--
<tr>
    <td class="title">Rol:</td>
    <td class="td-table-data">&nbsp;*</td>
  </tr>
<tr>
    <td class="title">Recibir Notificacion Aviso SMS:</td>
    <td class="td-table-data">&nbsp;*</td>
  </tr>
-->
  <tr>
    <td class="title">Cargo:</td>
    <td class="td-table-data"><input type="text" name="cargo" maxlength="100" size="50" class="form-box"  value="{CARGO}" ></td>
  </tr>  
  <tr>
    <td class="title">Meta Mensual Observaciones:</td>
    <td class="td-table-data"><input type="text" name="m_obs" maxlength="100" size="50" class="form-box"  value="{M_OBS}" ></td>
  </tr>  
  
 <tr>
    <td class="title">Meta Mensual Inspecciones:</td>
    <td class="td-table-data"><input type="text" name="m_insp" maxlength="100" size="50" class="form-box"  value="{M_INSP}" ></td>
  </tr>  
  <tr>
    <td class="title">Meta Mensual Aviso de Incidentes:</td>
    <td class="td-table-data"><input type="text" name="m_incc" maxlength="100" size="50" class="form-box"  value="{M_INCC}" ></td>
  </tr>  
  <tr>
    <td class="title">{UND_PLANTA}:</td>
    <td class="td-table-data">
        <select id="id_empresa" name="id_empresa" class="form-box" onchange="cargar_areas(this.value);">
            <option value="NULL">No Aplica</option>
            {EMPRESAS}
        </select>
        
    </td>
  </tr>
  <tr>
    <td class="title">Área:</td>
    <td class="td-table-data">
    <select id="id_area" name="id_area" class="form-box">
            <option value="NULL">No Aplica</option>
            {AREAS}
        </select>
    </td>
  </tr>