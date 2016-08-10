        <div class="content">
                      <!-- -->
            <form id="idFormulariogeneral" name="idFormulariogeneral" class="form-horizontal form-horizontal-red" role="form">
                <div class="row">
                    <div class="col-xs-24">
                        {CAMPOS}

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-20">
                                <button type="button" id="btn-guardar" class="btn btn-primary" onClick="validar_general(document);">{DESC_OPERACION}</button>
                                <button type="button" class="btn btn-default"  data-dismiss="modal">{N_CANCELAR}</button>

                                <input type="hidden" id="opc" name="opc" value="{OPC}">
                                <input type="hidden" id="id"  name="id"  value="{ID}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

