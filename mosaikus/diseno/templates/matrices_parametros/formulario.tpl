<!--<div class="form-group">
                                        <label for="cod_categoria" class="col-md-2 control-label">{N_COD_CATEGORIA}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{COD_CATEGORIA}" id="cod_categoria" name="cod_categoria" placeholder="{N_COD_CATEGORIA}"  data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="id_cmb_acap" class="col-md-2 control-label">{N_ID_CMB_ACAP}</label>
                                        <div class="col-md-2">
                                          
                                      </div>                                
                                  </div>-->
<div class="form-group">
                                        <label for="nombre" class="col-md-2 control-label">{N_NOMBRE}</label>
                                        <div class="col-md-4">
                                          <input type="text" class="form-control" value="{NOMBRE}" id="nombre" name="nombre" placeholder="{N_NOMBRE}" data-validation="required"/>
                                          <input type="hidden" class="form-control" value="{ID_CMB_ACAP}" id="id_cmb_acap" name="id_cmb_acap" />
                                      </div>                                
                                  </div>
                                  <div class="form-group">
                                        <label for="orden" class="col-md-2 control-label">{N_ORDEN}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{ORDEN}" id="orden" name="orden" placeholder="{N_ORDEN}"  data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group"  id="div-dependencia">
                                        <label for="dependencia" class="col-md-2 control-label">{N_DEPENDENCIA}</label>
                                        <div class="col-md-4">
                                            <select class="form-control" id="dependencia" name="dependencia" data-validation="required">
                                                <option selected="" value="">-- Seleccione --</option>
                                                {DEPENDENCIA}
                                             </select>                                         
                                      </div>                                
                                  </div>
                                      <!--
<div class="form-group">
                                        <label for="texto" class="col-md-2 control-label">{N_TEXTO}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{TEXTO}" id="texto" name="texto" placeholder="{N_TEXTO}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="formula" class="col-md-2 control-label">{N_FORMULA}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{FORMULA}" id="formula" name="formula" placeholder="{N_FORMULA}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="calculo_formula" class="col-md-2 control-label">{N_CALCULO_FORMULA}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{CALCULO_FORMULA}" id="calculo_formula" name="calculo_formula" placeholder="{N_CALCULO_FORMULA}"  data-validation="required"/>
                                      </div>                                
                                  </div>-->
<!--<div class="form-group">
                                        <label for="muestra" class="col-md-2 control-label">{N_MUESTRA}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{MUESTRA}" id="muestra" name="muestra" placeholder="{N_MUESTRA}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group">
                                        <label for="muestrarpt" class="col-md-2 control-label">{N_MUESTRARPT}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{MUESTRARPT}" id="muestrarpt" name="muestrarpt" placeholder="{N_MUESTRARPT}" data-validation="required"/>
                                      </div>                                
                                  </div>-->
<div class="form-group"  id="div-tipo">
                                        <label for="tipo" class="col-md-2 control-label">{N_TIPO}</label>
                                        <div class="col-md-4">
                                            {TIPOS}                                          
                                      </div>                                
                                  </div>
                                <div class="form-group" id="div-indicador">
                                        <label for="indicador" class="col-md-2 control-label">{N_INDICADOR}</label>
                                        <div class="col-md-3">                       
                                        <label class="radio-inline" style="padding-top: 0px;color:white">
                                            <input type="radio" id="indicador" value="S" name="indicador" {CHECKED_IND_SI}> Si
                                        </label>
                                        <label class="radio-inline" style="padding-top: 0px;color:white">
                                            <input type="radio" id="indicador" value="N" name="indicador" {CHECKED_IND_NO}> No
                                        </label>                    
                                    </div>
                                                                     
                                  </div>

<div class="form-group" id="div-fecha_nom1">
                                        <label for="fecha_nom1" class="col-md-2 control-label">{N_FECHA_NOM1}</label>
                                        <div class="col-md-4">
                                          <input type="text" class="form-control" value="{FECHA_NOM1}" id="fecha_nom1" name="fecha_nom1" placeholder="{N_FECHA_NOM1}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group" id="div-fecha_nom2">
                                        <label for="fecha_nom2" class="col-md-2 control-label">{N_FECHA_NOM2}</label>
                                        <div class="col-md-4">
                                          <input type="text" class="form-control" value="{FECHA_NOM2}" id="fecha_nom2" name="fecha_nom2" placeholder="{N_FECHA_NOM2}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group" id="div-fecha_sem">
                                        <label for="fecha_sem" class="col-md-2 control-label">{N_FECHA_SEM}</label>
                                        <div class="col-md-4">
                                          <input type="text" class="form-control" value="{FECHA_SEM}" id="fecha_sem" name="fecha_sem" placeholder="{N_FECHA_SEM}" data-validation="required"/>
                                      </div>                                
                                  </div>
<div class="form-group" id="div-datos">
                                        <label for="datos" class="col-md-2 control-label">{N_DATOS}</label>
                                        <div class="col-md-4">
                                          <input type="text" class="form-control" value="{DATOS}" id="datos" name="datos" placeholder="{N_DATOS}"  data-validation="required"/>
                                      </div>                                
                                  </div>
<!--<div class="form-group">
                                        <label for="tip_familia_requisito" class="col-md-2 control-label">{N_TIP_FAMILIA_REQUISITO}</label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" value="{TIP_FAMILIA_REQUISITO}" id="tip_familia_requisito" name="tip_familia_requisito" placeholder="{N_TIP_FAMILIA_REQUISITO}" data-validation="required"/>
                                      </div>                                
                                  </div>-->
