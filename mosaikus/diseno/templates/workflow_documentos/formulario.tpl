<div class="form-group">
                <label for="id_personal_responsable" class="col-md-4 control-label">{N_ID_PERSONAL_RESPONSABLE}</label>
                <div class="col-md-10">
                <select name="id_personal_responsable" id="id_personal_responsable" data-validation="required" onchange="AsignaCorreo(this.id,'email_responsable')">
                            <option selected="" value="">-- Seleccione --</option>
                            {ID_PERSONAL_RESPONSABLE}
              </select>
                  
              </div>                                
</div>
<div class="form-group">
            <label for="email_responsable" class="col-md-4 control-label">{N_EMAIL_RESPONSABLE}</label>
            <div class="col-md-10">
              <input type="text" class="form-control" value="{EMAIL_RESPONSABLE}" id="email_responsable" name="email_responsable" placeholder="{N_EMAIL_RESPONSABLE}" data-validation="required"/>
          </div>                                
</div>
<div class="form-group">
            <label for="id_personal_revisa" class="col-md-4 control-label">{N_ID_PERSONAL_REVISA}</label>
            <div class="col-md-10">
                <select name="id_personal_revisa" id="id_personal_revisa" onchange="AsignaCorreo(this.id,'email_revisa')">
                            <option selected="" value="">-- Seleccione --</option>
                            {ID_PERSONAL_REVISA}
              </select>
              
          </div>                                
</div>
<div class="form-group">
        <label for="email_revisa" class="col-md-4 control-label">{N_EMAIL_REVISA}</label>
        <div class="col-md-10">
          <input type="text" class="form-control" value="{EMAIL_REVISA}" id="email_revisa" name="email_revisa" placeholder="{N_EMAIL_REVISA}" />
        </div>                                
</div>
<div class="form-group">
            <label for="id_personal_aprueba" class="col-md-4 control-label">{N_ID_PERSONAL_APRUEBA}</label>
            <div class="col-md-10">
                <select name="id_personal_aprueba" id="id_personal_aprueba" data-validation="required" onchange="AsignaCorreo(this.id,'email_aprueba')">
                            <option selected="" value="">-- Seleccione --</option>
                            {ID_PERSONAL_APRUEBA}
              </select>
              
          </div>                                
</div>
<div class="form-group">
        <label for="email_aprueba" class="col-md-4 control-label">{N_EMAIL_APRUEBA}</label>
        <div class="col-md-10">
          <input type="text" class="form-control" value="{EMAIL_APRUEBA}" id="email_aprueba" name="email_aprueba" placeholder="{N_EMAIL_APRUEBA}" data-validation="required"/>
      </div>                                
</div>
