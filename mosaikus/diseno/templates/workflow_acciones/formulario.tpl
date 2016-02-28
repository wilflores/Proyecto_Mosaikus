<div class="form-group">
            <label for="id_personal" class="col-md-4 control-label">{N_ID_PERSONAL}</label>
            <div class="col-md-10">
                <select name="id_personal" id="id_personal" data-validation="required" onchange="AsignaCorreo(this.id,'email')">
                            <option selected="" value="">-- Seleccione --</option>
                            {ID_PERSONAL}
              </select>
          </div>                                
</div>
<div class="form-group">
            <label for="email" class="col-md-4 control-label">{N_EMAIL}</label>
            <div class="col-md-10">
                <input readonly type="text" class="form-control" value="{EMAIL}" id="email" name="email" placeholder="{N_EMAIL}" data-validation="required"/>
            </div>                                
</div>
<div class="form-group">
            <label for="id_personal_wf" class="col-md-4 control-label">{N_ID_PERSONAL_WF}</label>
            <div class="col-md-10">
                <select name="id_personal_wf" id="id_personal_wf" data-validation="required" onchange="AsignaCorreo(this.id,'email_wf')">
                            <option selected="" value="">-- Seleccione --</option>
                            {ID_PERSONAL_WF}
              </select>
          </div>                                
</div>
<div class="form-group">
            <label for="email_wf" class="col-md-4 control-label">{N_EMAIL_WF}</label>
            <div class="col-md-10">

              <input type="text" readonly class="form-control" value="{EMAIL_WF}" id="email_wf" name="email_wf" placeholder="{N_EMAIL_WF}" data-validation="required"/>
          </div>                                
</div>
<div class="form-group">
            <label for="id_personal_vaca" class="col-md-4 control-label">{N_ID_PERSONAL_VACA}</label>
            <div class="col-md-10">
                <select name="id_personal_vaca" id="id_personal_vaca" onchange="AsignaCorreo(this.id,'email_wf_vaca')">
                            <option selected="" value="">-- Seleccione --</option>
                            {ID_PERSONAL_VACA}
              </select>
          </div>                                
</div>
<div class="form-group">
            <label for="email_wf_vaca" class="col-md-4 control-label">{N_EMAIL_WF_VACA}</label>
            <div class="col-md-10">
              <input type="text" readonly class="form-control" value="{EMAIL_WF_VACA}" id="email_wf_vaca" name="email_wf_vaca" placeholder="{N_EMAIL_WF_VACA}"/>
          </div>                                
</div>
<div class="form-group">
            <label for="email_alerta" class="col-md-4 control-label">{N_EMAIL_ALERTA}</label>
            <div class="col-md-10">
                <select name="email_alerta" id="email_alerta" data-validation="required" >
                            <option selected="" value="">-- Seleccione --</option>
                            {EMAIL_ALERTA}
              </select>
                
          </div>                                
</div>
