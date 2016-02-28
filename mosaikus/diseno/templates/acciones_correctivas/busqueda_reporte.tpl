<div class="form-group" >                      
    <div class="row">
        <div class="col-xs-8">
            <label class="checkbox-inline" style="padding-top: 0px;padding-left: 00px;">
                <input type="radio" id="b-filtro-fecha" name="b-filtro-fecha" checked="checked">   Del<br>

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
                 <input type="radio" id="b-filtro-fecha" name="b-filtro-fecha" value="1">   Por Rango<br>
                 
            </label>                               
        </div>
</div> 
<div class="form-group">    
    <div class="row">
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Desde</label>
            <input type="text" class="form-control" id="b-fecha_generacion-desde" disabled="disabled" name="b-fecha_generacion-desde" placeholder="dd/mm/yyyy"  />
        </div>   
        <div class="col-xs-12">
            <label for="exampleInputPassword1">Hasta</label> 
            <input type="text" class="form-control" id="b-fecha_generacion-hasta" disabled="disabled" name="b-fecha_generacion-hasta" placeholder="dd/mm/yyyy"  />
        </div>                                
    </div>

 </div>