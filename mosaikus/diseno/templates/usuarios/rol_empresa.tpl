<div class="form-container" >
    <form action="listarClasificacion.php" name="idFormulario" id="idFormulario" method="post">
            <div class="fieldset-content clear-block ">
                <table class="detail-table" style="width:95%;">
                    <tbody>
                        <tr>
                            <input type="hidden" id="num_items" name="num_items" value="{NUM_ITEMS}"/>
                            <input type="hidden" id="id_usuario" name="id_usuario" value="{ID_USUARIO}"/>
                            <table class="data-table" id="table-items-rep">    
                                    <thead>
                                        <tr>			 	 		
                                            <th style="width: 8%">
                                                Planta/Unidad
                                            </th>
                                            <th style="width: 8%">
                                                Acceso 
                                            </th>
                                            <th style="width: 30%">
                                                Rol 
                                            </th>
                                            <th style="width: 8%">
                                                Notificación de Aviso SMS
                                            </th>
                                            <th style="width: 8%">
                                                Predeterminado
                                            </th>
                                        </tr>                                    
                                    </thead>
                                    <tbody>                                        
                                        {ITEMS_REPUESTOS}
                                    </tbody>
                                </table>
                        </tr>
                     </tbody>
                </table>
                <div class="field-wrapp">
                    <input class="button save" name="guardar" type="button" value="Guardar" onClick="validarRol(document);">                    
                </div>
            </div>       
        </form>
</div>

<style type="text/css">
  body {
    background: none;/*url("../images/lateral_bar.png") repeat-x scroll center top #931314*/;
    min-width: 60px;
    background-color: white;
  }
  </style>