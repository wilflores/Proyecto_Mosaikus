<div class="form-group" >                      
    <div class="row">
        <div class="col-xs-8">
            <label class="checkbox-inline" style="padding-top: 0px;padding-left: 00px;">
                <input type="radio" id="b-filtro-fecha" value="1" name="b-filtro-fecha" checked="checked">   Del<br>

            </label>                               
        </div>
        <div class="col-xs-16">
            <select id="tipo_data" name="tipo_data" class="form-control" >                                        
                                        <option value="SEMANAL">Semanal</option>
                                        <option value="MES">Mensual</option>
                                        <option value="QUARTIL">Cuartil</option>
                                        <option value="SEM">Semestre</option>
                                        <option value="M12">Movil 12</option>
                                        <option selected="" value="YTD">YTD</option>
                                    </select> 
        </div>
        </div>
</div> 
<!--<div class="form-group">
                                  
                                    
                                                               
                            </div>-->
<div class="form-group">  
    <div class="row">
            <label class="checkbox-inline" style="padding-top: 0px;padding-left: 10px;">
                 <input type="radio" id="b-filtro-fecha"  value="2" name="b-filtro-fecha" value="1">   Por Rango<br>
                 
            </label>                               
        </div>
</div> 
<div class="form-group">    
    <div class="row">
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Desde</label>
            <input type="text" class="form-control" id="b-f-desde" disabled="disabled"  readonly="readonly"name="b-f-desde" placeholder="dd/mm/yyyy"  />
        </div>   
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Hasta</label> 
            <input type="text" class="form-control" id="b-f-hasta" value="{B_F_HASTA}" readonly="readonly" name="b-f-hasta" placeholder="dd/mm/yyyy"  />
        </div>                                
    </div>

 </div>
 <div class="form-group">
        <label for="origen_hallazgo" class="">{N_ORIGEN_HALLAZGO}</label>

          <select id="b-f-origen_hallazgo" name="b-origen_hallazgo" class="form-control" >
                      <option selected="" value="">-- Todos --</option>
                      {ORIGENES}
                  </select> 

  </div>
  <div class="form-group">
        <label for="responsable_analisis" class="">{N_RESPONSABLE_ANALISIS}</label>

            <select name="b-responsable_analisis" id="b-f-responsable_analisis">
              <option selected="" value="">-- Seleccione --</option>
              {RESPONSABLE_ANALISIS}
          </select>                                    

  </div>
 <div class="form-group">  
    
            <label class="checkbox-inline" >
                <input type="checkbox" id="b-alto_potencial"  name="b-alto_potencial" value="S">   {N_ALTO_POTENCIAL}<br>
                 
            </label>                               
    
</div> 
      <input type="hidden" id="b-f-id_organizacion" name="b-id_organizacion"/>  
                            