<div class="form-group">
    <label for="id_responsable_actual" class="col-md-4 control-label">{N_ID_RESPONSABLE_ACTUAL}</label>
    <div class="col-md-10">
        <select name="id_responsable_actual" id="id_responsable_actual" data-validation="required">
                    <option selected="" value="">-- Seleccione --</option>
                    {ID_RESPONSABLE_ACTUAL}
      </select>
      <!--<input type="text" readonly class="form-control" value="{ID_DOCUMENTO}" id="id_documento" name="id_documento" placeholder="{N_ID_DOCUMENTO}"  data-validation="required"/>-->
  </div>       
</div>
<div class="form-group">
    <label for="migrar_responsable_doc" class="col-md-4 control-label">{N_MIGRAR_RESPONSABLE_DOC}</label>
    <div class="col-md-10">
        <select class="form-control" onchange="ActivaPersonal(this.value,'id_nuevo_responsable')" name="migrar_responsable_doc" id="migrar_responsable_doc" data-validation="required">
                    {MIGRAR_RESPONSABLE_DOC}
      </select>      
  </div>                                
</div>
<div class="form-group">
    <label for="id_nuevo_responsable" class="col-md-4 control-label">{N_ID_NUEVO_RESPONSABLE}</label>
    <div class="col-md-10">
        <select   name="id_nuevo_responsable" id="id_nuevo_responsable" >
                    <option selected="" value="">-- Seleccione --</option>
                    {ID_NUEVO_RESPONSABLE}
      </select>
  </div>                                
</div>
<div class="form-group">
    <label for="migrar_wf_revisa" class="col-md-4 control-label">{N_MIGRAR_WF_REVISA}</label>
    <div class="col-md-10">
      <select class="form-control"  onchange="ActivaPersonal(this.value,'id_revisa')" name="migrar_wf_revisa" id="migrar_wf_revisa" data-validation="required">
                    {MIGRAR_WF_REVISA}
      </select>      
      
  </div>                                
</div>
<div class="form-group">
    <label for="id_revisa" class="col-md-4 control-label">{N_ID_REVISA}</label>
    <div class="col-md-10">
        <select name="id_revisa" id="id_revisa" >
                    <option selected="" value="">-- Seleccione --</option>
                    {ID_REVISA}
      </select>
      
  </div>                                
</div>
<div class="form-group">
    <label for="migrar_wf_aprueba" class="col-md-4 control-label">{N_MIGRAR_WF_APRUEBA}</label>
    <div class="col-md-10">
      <select  class="form-control" onchange="ActivaPersonal(this.value,'id_aprueba')" name="migrar_wf_aprueba" id="migrar_wf_aprueba" data-validation="required">
                    {MIGRAR_WF_APRUEBA}
      </select>      
      
  </div>                                
</div>
<div class="form-group">
    <label for="id_aprueba" class="col-md-4 control-label">{N_ID_APRUEBA}</label>
    <div class="col-md-10">
      <select name="id_aprueba" id="id_aprueba" >
                    <option selected="" value="">-- Seleccione --</option>
                    {ID_APRUEBA}
      </select>      
  </div>                                
</div>
