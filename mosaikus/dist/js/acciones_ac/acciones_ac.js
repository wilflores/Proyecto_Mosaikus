
    

    function nuevo_AccionesAC(){
            array = new XArray();
            array.setObjeto('AccionesAC','crear');
            array.addParametro('import','clases.acciones_ac.AccionesAC');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('AccionesAC','guardar');
            else
                array.setObjeto('AccionesAC','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.acciones_ac.AccionesAC');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarAccionesAC(id){
        array = new XArray();
        array.setObjeto('AccionesAC','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }
    
    function WFAccionesAC(id){
        array = new XArray();
        array.setObjeto('AccionesAC','WFAccionesAC');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }
    
function CambiarEstadoWF(estado,id){
    array = new XArray();
    array.setObjeto('AccionesAC','cambiar_estado');
    array.addParametro('id',id);
    array.addParametro('estado',estado);
    var myDate = new Date();
    array.addParametro('fecha_estado_wf',(myDate.getDate()) + '/' + (((myDate.getMonth()+1)<10)?'0'+ (myDate.getMonth()+1):(myDate.getMonth()+1))+ '/' + myDate.getFullYear() + ' ' + myDate.getHours()+':'+myDate.getMinutes());
    array.addParametro('import','clases.acciones_ac.AccionesAC');
    xajax_Loading(array.getArray());
}
function RechazarWF(estado,id){
    if(document.getElementById("observacion_rechazo").value!=''){
        array = new XArray();
        array.setObjeto('AccionesAC','cambiar_estado');
        array.addParametro('id',id);
        array.addParametro('estado',estado);
        var myDate = new Date();
        array.addParametro('fecha_estado_wf',(myDate.getDate()) + '/' + (((myDate.getMonth()+1)<10)?'0'+ (myDate.getMonth()+1):(myDate.getMonth()+1))+ '/' + myDate.getFullYear() + ' ' + myDate.getHours()+':'+myDate.getMinutes());
        array.addParametro('observacion_rechazo',document.getElementById("observacion_rechazo").value);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
        $('#myModal-observacion-rechazo').modal('hide');
        
        }
    else{
        document.getElementById("observacion_rechazo").style.display='';
        alertify.error("Cargue una observacion de rechazo y vuelva a presionar Rechazar",5); 
    }
        
}
    
    function agregar_esp(){
        var i = $('#num_items_esp').val();
        i = parseInt(i) + 1;        
        var html = '<tr id="tr-esp-' + i + '">'; 
        html = html + '<td align="center">'+
                           //' <i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i><i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'+
                           '<input id="orden_din_'+ i + '" type="hidden" name="orden_din_'+ i + '" value="'+ i + '">'+   
                           '<i class="bajar glyphicon glyphicon-paperclip cursor-pointer" id="ico_cmb_din_'+ i +  '" tok="'+ i + '" title="Administrar Anexos"></i>'+
                           ' <a href="' + i + '"  title="Eliminar " id="eliminar_esp_' + i + '"> ' + 
                            '<i class="icon icon-remove"></i>' +
                            '</a>' +
                      '  </td>';
        html = html + '<td>' +
                           '  <select id="tipo_'+ i + '" name="tipo_'+ i + '" class="form-control">'+                            
                                $('#option_tipo').val() +
                                
                            '</select>' +
                        '</td>';  
        html = html + '<td>' +                                                       
                           ' <textarea id="accion_'+ i + '"  rows="2" name="accion_'+ i + '"class="form-control" data-validation="required"></textarea>'+
                        '</td>';
        html = html + '<td class="td-table-data">'+
                            '  <select id="responsable_acc_'+ i + '" name="responsable_acc_'+ i + '" class="form-control" data-validation="required" data-live-search="true">'+                            
                            '<option value="">-- Seleccione --</option>' + 
                                $('#option_responsables').val() +
                                
                            '</select>' +
                       '</td>';
        html = html + '<td class="td-table-data"> <div class="col-sm-24" style="padding-left: 0px;padding-right: 0px;">'+
                            '<input id="fecha_acordada_'+ i + '" class="form-control" type="text" data-date-format="DD/MM/YYYY HH:mm" data-validation="required" name="fecha_acordada_'+ i + '" >'+
                       ' </div></td>';

        html = html + '</tr>' ;       
        $("#table-items-esp tbody").append(html);  
        var myDate = new Date();
        $('#fecha_acordada_'+i).val((myDate.getDate()) + '/' + (((myDate.getMonth()+1)<10)?'0'+ (myDate.getMonth()+1):(myDate.getMonth()+1))+ '/' + myDate.getFullYear() + ' ' + myDate.getHours()+':'+myDate.getMinutes());
        $('#fecha_acordada_'+i).datetimepicker();
        $('#responsable_acc_' + i).selectpicker({
                                            style: 'btn-combo'
                                          });
        $("#eliminar_esp_" + i).click(function(e){ 
            e.preventDefault();
            var id = $(this).attr('href');
            $('tr-esp-' + id).remove();
            var parent = $(this).parents().parents().get(0);
		$(parent).remove();
        });             
        $(".subir").click(function(){
            var row = $(this).parents("tr:first");               
            row.insertBefore(row.prev());
            ordenar_tabla();
        });
        $(".bajar").click(function(){
            var row = $(this).parents("tr:first");        
            row.insertAfter(row.next());  
            ordenar_tabla();
        });
        $('#num_items_esp').val(i);
        
    }


    function eliminarAccionesAC(id){
        if(confirm("¿Desea Eliminar la Acción Seleccionada?")){
            array = new XArray();
            array.setObjeto('AccionesAC','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.acciones_ac.AccionesAC');
            xajax_Loading(array.getArray());
        }
    }
    function verPagina(pag,doc){
        array = new XArray();
        if (doc== null)
        {
             $('form')[0].reset();             
        }
        array.getForm('busquedaFrm'); 
        if ((isNaN(document.getElementById("reg_por_pag").value) == true) || (parseInt(document.getElementById("reg_por_pag").value) <= 0)){
            array.addParametro('reg_por_pagina', 10);
            document.getElementById("reg_por_pag").value = 10
        }
        else
        {
            array.addParametro('reg_por_pagina', document.getElementById("reg_por_pag").value);
        }
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('AccionesAC','buscar');
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }



    function verAccionesAC(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verAccionesAC.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function reset_formulario(){
        
        $('#idFormulario-hv').each (function(){
            this.reset();
        });             
        $('#idFormulario-hv textarea').html('');
        $("#hv-id_responsable").select2("val", "");
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');        
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
        
    }
    
    function adminEvidencias(id){
        $('#myModal-Ventana').modal('hide');
        //$('#myModal-Evidencias').modal('show');
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidencia');
        array.addParametro('id_accion',id);
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }
    
    function EvidenciasVerReporte(id){
        $('#myModal-Ventana').modal('hide');
        //$('#myModal-Evidencias').modal('show');
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidencia');
        array.addParametro('id_accion',id);
        array.addParametro('reporte_ac','S');
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }
   function filtrar_mostrar_colums(){
        var colums = '0-1-2-3-4-';
         $('.checkbox-mos-col').each(function() {
                if (this.checked){
                    colums = colums + this.value + '-';
                }
         });
         colums = colums.substring(0, colums.length - 1);
         $('#mostrar-col').val(colums);
         verPagina($('#pag_actual').val(),1);
         $('#myModal-Mostrar-Colums').modal('hide');
         
    }

    function verDetalle(id)
    {
        array = new XArray();
        array.setObjeto('AccionesAC','ver');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }