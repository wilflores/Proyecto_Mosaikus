{HTML_INICIO_PAG}

<div  class="col-md-12" style=" background-image: url('diseno/images/FondoDIVPrin.png');padding-left: 0px; padding-right: 0px;">
    <div  class="content-wrapper clear-block">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr height="30px" align="center">
                        <td class="Cabecera" width="30%" align="left" style="padding-left:15px;">
                            Documento [ {DOCUMENTO} ]
                            <img class="SinBorde" src="diseno/images/flecha.gif">
                            Lista de Registros                            
                        </td>
                        <td class="Cabecera" width="8%" align="left" style="padding-left:15px;{PERMISO_INGRESAR}">                            
                            <a class="LinksinLinea" href="javascript:{JS_NUEVO}" style=""  >
                                <img class="SinBorde" title="Nuevo" src="diseno/images/ico_nuevo.png">
                                Nuevo
                            </a>
                        </td>
                        <td class="Cabecera" width="8%" style="padding-left:16px;">
                            <a class="LinksinLinea" href="#" data-toggle="modal" data-target="#r-myModal-Mostrar-Colums">
                                <img class="SinBorde" align="absbottom" title="Personalizar" src="diseno/images/personalizar.png">
                                Personalizar
                            </a>
                        </td>
                        <td class="Cabecera" width="8%">
                            <a class="LinksinLinea" href="#" id="r-a-myModal-Filtro" data-toggle="modal" data-target="#r-myModal-Filtro">
                                <img class="SinBorde" align="absbottom" title="Quitar Filtro" src="diseno/images/filtro.png">
                                Filtro
                            </a>
                            <a class="LinksinLinea" href="#" id="r-a-myModal-Filtro-des" style="display:none;" onclick="r_activar_filtrar_listado();">
                                <img class="SinBorde" align="absbottom" title="Quitar Filtro" src="diseno/images/no_filtro.png">
                                Filtro
                            </a>
                        </td>
                        <td class="Cabecera" width="8%" align="left">
                            <a class="LinksinLinea" href="#"  onClick="r_exportarExcel();">
                                <img class="SinBorde" align="absbottom" title="Exportar a Excel" src="diseno/images/excel.png">
                                Exportar
                            </a>
                        </td>
                        <td class="Cabecera" width="8%" align="left">
                            <a class="LinksinLinea" href="#"  onClick="$('#desc-mod-act').html('{TITULO}'); MostrarContenido();">
                                <img class="SinBorde" align="absbottom" title="Volver" src="diseno/images/ico_volver.png">
                                Volver
                            </a>
                        </td>
                        <td class="Cabecera" width="12%">
                            <!--
                            <a class="LinksinLinea" href="javascript:MuestraArbolesF('FiltrosArboles','mos_personal','1')">
                                <img class="SinBorde" align="absbottom" title="Filtro" src="diseno/images/filtro.png">
                                Filtro Árbol organizacional
                            </a>-->
                        </td>
                        <td class="Cabecera"> </td>
                    </tr>
                </tbody>
            </table>
        
        <div class="modal fade bs-example-modal-lg" id="r-myModal-Filtro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog  modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="r-myModalLabel">Filtrar Por</h4>
                </div>
                <div class="modal-body">
                    <form id="r-busquedaFrm" class="form-horizontal form-horizontal-form" role="form">
                        {CAMPOS_BUSCAR}
                        <input type="hidden" name="mostrar-col" id="r-mostrar-col" value="{MOSTRAR_COL}" />
                        <input type="hidden" name="reg_por_pag" id="r-reg_por_pag" value="12"/>
                        <input type="hidden" name="corder" id="r-corder" value="{CORDER}"/>
                        <input type="hidden" name="sorder" id="r-sorder" value="{SORDER}"/>
                    </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" onClick="r_filtrar_listado();">Filtrar</button>
                </div>
              </div>
            </div>
        </div>
                    
        <div class="modal fade" id="r-myModal-Mostrar-Colums" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="r-myModalLabel">Opciones de visualización</h4>
                </div>
                  <div class="modal-body">
                    <form id="r-FrmMostrar-Columns" class="form-horizontal form-horizontal-form form-horizontal-form-left" role="form"> 
                        <div class="form-group">
                            <label for="ejemplo_password_3" class="col-md-9 control-label" style="text-align: left;"> Todos </label>
                                  <div class="col-md-3">      
                                      <label class="checkbox-inline">
                                          <input type="checkbox" name="Interno" id="r-InternoPer" checked="checked" onclick="r_marcar_desmarcar_checked_columns(this.checked);">   &nbsp;
                                      </label>
                                  </div>
                            </div>
                        {CAMPOS_MOSTRAR_COLUMNS}
                     </form>
                    
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" onclick="r_filtrar_mostrar_colums();">Seleccionar</button>
                </div>
              </div>
            </div>
        </div>
        
        <div id="r-grid" >
        {TABLA}
        </div>
             
    </div>

</div>
<div id="r-grilla-mostrando" class="col-md-12" style=" background-image: url('diseno/images/FondoDIVPrin.png');padding-left: 0px; padding-right: 0px;color: white;height: 10px;">     
     &nbsp;
</div>
<div id="r-grid-paginado" class="col-md-12" style=" background-color: white;padding-left: 0px; padding-right: 0px;color: black">     
     {PAGINADO}
</div>
                    

