                      
<div class="form-group">
    <label for="descripcion" class="">{N_DESCRIPCION}</label>
    <input type="text" class="form-control" id="b-descripcion" name="b-descripcion" placeholder="{N_DESCRIPCION}" />
</div>
<div class="form-group">
    <label for="analisis_causal" class="">{N_ANALISIS_CAUSAL}</label>
    <input type="text" class="form-control" id="b-analisis_causal" name="b-analisis_causal" placeholder="{N_ANALISIS_CAUSAL}" />
</div>

<div class="form-group">
    <label for="fecha" class="control-label">{N_FECHA_ACORDADA}</label>
    <div class="row">
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Desde</label>
            <input type="text" class="form-control" data-date-format="DD/MM/YYYY"  id="b-fecha_acordada-desde" name="b-fecha_acordada-desde" placeholder="dd/mm/yyyy"  />
        </div>   
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Hasta</label> 
            <input type="text" class="form-control" data-date-format="DD/MM/YYYY"  id="b-fecha_acordada-hasta" name="b-fecha_acordada-hasta" placeholder="dd/mm/yyyy"  />
        </div>                                
    </div>
</div>   
<div class="form-group">
    <label for="fecha" class="control-label">{N_FECHA_REALIZADA}</label>
    <div class="row">
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Desde</label>
            <input type="text" class="form-control" data-date-format="DD/MM/YYYY"  id="b-fecha_realizada-desde" name="b-fecha_realizada-desde" placeholder="dd/mm/yyyy"  />
        </div>   
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Hasta</label> 
            <input type="text" class="form-control" data-date-format="DD/MM/YYYY"  id="b-fecha_realizada-hasta" name="b-fecha_realizada-hasta" placeholder="dd/mm/yyyy"  />
        </div>                                
    </div>
</div>  
<div class="form-group">
        <label for="id_responsable_segui" class="">{N_ID_RESPONSABLE_SEGUI}</label>
            <select id="b-id_responsable_segui" name="b-id_responsable_segui">
              <option selected="" value="">-- Seleccione --</option>
              {RESPONSABLE_ANALISIS}
        </select>                                     
                                                           
</div>
<input type="hidden" id="b-id_organizacion" name="b-id_organizacion"/>  
