
function init_tabla(){
        $('.ver-mas').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
            $('#myModal-Ventana-Titulo').html('');
            $('#myModal-Ventana').modal('show');
        });
        
        $('.ver-reporte').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            verAccionesCorrectivas(id);
            /*window.open('pages/acciones_correctivas/reporte_ac_pdf.php?id='+id,'_blank');*/
        });
}

function init_filtrar(){
        
        $( "#b-responsable_analisis" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); 
        $( "#b-id_responsable_segui" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); 
        $("#b-fecha_generacion-desde").datetimepicker();
        $("#b-fecha_acordada-desde").datetimepicker();;
        $("#b-fecha_realizada-desde").datetimepicker();
        $("#b-fecha_generacion-hasta").datetimepicker();
        $("#b-fecha_acordada-hasta").datetimepicker();
        $("#b-fecha_realizada-hasta").datetimepicker();
        
        PanelOperator.initPanels("");
        ScrollBar.initScroll();
        init_filtro_rapido();
        init_filtro_ao_simple();
}

    function filtrar_mostrar_colums(){
        var colums = '0-1-3-';
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
    
    function cargar_autocompletado(){   
        $( "#responsable_analisis" ).select2({
            placeholder: "Selecione el revisor",
            allowClear: true
          }); 
          $( "#responsable_desvio" ).select2({
            placeholder: "Selecione el elaborador",
            allowClear: true
          });    
          $( "#reportado_por" ).select2({
            placeholder: "Selecione el elaborador",
            allowClear: true
          }); 
          
          $('#responsable_desvio').change(function() {                    
               actualizar_cambio_respon_desvio();
          });
          $('#responsable_analisis').change(function() {                    
               if ($('#responsable_desvio').val() == $('#responsable_analisis').val()){
                   $('#analisis_causal').parent().parent().show();
                   $('#analisis_causal').attr('data-validation','required');  
                   $('#descripcion_val').parent().parent().show();
                   $('#descripcion_val').attr('data-validation','required'); 
                   $('#alto_potencial_val').parent().parent().show();
                   $('#alto_potencial_val').attr('data-validation','required'); 
                   $('#proceso').parent().parent().show();
                   $('#div-tabs').show();
                   var campos_din = $('#nombre_cam_din').val().split(',');
              
                    for (i = 0; i < campos_din.length; i++) {
                        if (campos_din[i].length > 0){
                            $('#'+campos_din[i]).parent().parent().show();
                            //$('#' + campos_din[i]).removeAttr('data-validation');
                            $('#' + campos_din[i]).attr('data-validation','required');  
                        }                     
                    }
                    if ($('#descripcion_val').val().length == 0){
                        $('#descripcion_val').val($('#descripcion').val());
                    }
               }
               else{
                   /*OCULTAMOS CAMPOS NO REQuERIDOS ETAPA 3*/
                    $('#analisis_causal').parent().parent().hide();
                    $('#analisis_causal').removeAttr('data-validation');
                    $('#descripcion_val').parent().parent().hide();
                    $('#descripcion_val').removeAttr('data-validation');
                    $('#alto_potencial_val').parent().parent().hide();
                    $('#alto_potencial_val').removeAttr('data-validation');
                    $('#proceso').parent().parent().hide();
                    $('#div-tabs').hide();
                    var campos_din = $('#nombre_cam_din').val().split(',');

                    for (i = 0; i < campos_din.length; i++) {
                        if (campos_din[i].length > 0){
                            $('#'+campos_din[i]).parent().parent().hide();
                            $('#' + campos_din[i]).removeAttr('data-validation');
                            //$('#' + id).attr('data-validation','required');  
                        }                     
                    }
               }
          });
          if ($('#opc').val() == 'new'){
              /*OCULTAMOS CAMPOS NO REQuERIDOS ETAPA 2*/
              $('#responsable_desvio').removeAttr('data-validation');
              $('#responsable_analisis').parent().parent().hide();
              $('#id_responsable_segui').parent().parent().hide();
              $('#id_responsable_segui').removeAttr('data-validation');   
              
              $('#analisis_causal').removeAttr('data-validation');
              $('#analisis_causal').parent().parent().hide();
              $('#descripcion_val').parent().parent().hide();
              $('#descripcion_val').removeAttr('data-validation');                            
              $('#alto_potencial_val').parent().parent().hide();
              $('#alto_potencial_val').removeAttr('data-validation');
              $('#proceso').parent().parent().hide();
              $('#div-tabs').hide();
              var campos_din = $('#nombre_cam_din').val().split(',');
              
              for (i = 0; i < campos_din.length; i++) {
                  if (campos_din[i].length > 0){
                      $('#'+campos_din[i]).parent().parent().hide();
                      $('#' + campos_din[i]).removeAttr('data-validation');
                      //$('#' + id).attr('data-validation','required');  
                  }                     
              }
          }else{
              /*OCULTAMOS CAMPOS NO REQuERIDOS ETAPA 2*/
              $('#responsable_desvio').removeAttr('data-validation');
              $('#responsable_analisis').parent().parent().hide();
              $('#id_responsable_segui').parent().parent().hide();
              $('#analisis_causal').removeAttr('data-validation');
              $('#analisis_causal').parent().parent().hide();
              $('#descripcion_val').parent().parent().hide();
              $('#descripcion_val').removeAttr('data-validation');                            
              $('#alto_potencial_val').parent().parent().hide();
              $('#alto_potencial_val').removeAttr('data-validation');
              $('#proceso').parent().parent().hide();
              $('#div-tabs').hide();
              var campos_din = $('#nombre_cam_din').val().split(',');
              
              for (i = 0; i < campos_din.length; i++) {
                  if (campos_din[i].length > 0){
                      $('#'+campos_din[i]).parent().parent().hide();
                      $('#' + campos_din[i]).removeAttr('data-validation');
                      //$('#' + id).attr('data-validation','required');  
                  }                     
              }
//              if ($('#responsable_desvio').val() != ''){
//                  $('#responsable_analisis').parent().parent().show();
//              }
//              if ($('#responsable_analisis').val() != ''){
//                  $('#analisis_causal').parent().parent().show();
//                  $('#proceso').parent().parent().show();
//                  $('#div-tabs').show();
//              }
//alert($('#estatus').val());
              switch ($('#estatus').val()){
                  case 'en_elaboracion':
                        if (($('#responsable_desvio').val() != '') && ($('#responsable_desvio').val() == $('#user_tok').val())){
                            $('#responsable_analisis').parent().parent().show();
                            $('#id_responsable_segui').parent().parent().show();
                            
                        }
                        if (($('#responsable_analisis').val() != '') && ($('#user_tok').val() == $('#responsable_analisis').val())){
                            $('#responsable_analisis').parent().parent().show();
                            $('#analisis_causal').attr('data-validation','required');  
                            $('#analisis_causal').parent().parent().show();
                            $('#descripcion_val').parent().parent().show();
                            $('#descripcion_val').attr('data-validation','required'); 
                            $('#alto_potencial_val').parent().parent().show();
                            $('#alto_potencial_val').attr('data-validation','required'); 
                            $('#proceso').parent().parent().show();
                            $('#div-tabs').show();
                            var campos_din = $('#nombre_cam_din').val().split(',');

                            for (i = 0; i < campos_din.length; i++) {
                                if (campos_din[i].length > 0){
                                    $('#'+campos_din[i]).parent().parent().show();
                                    $('#' + campos_din[i]).attr('data-validation','required');   
                                }                     
                            }
                      }
                  
                  case 'en_buzon':
                      break;
                  case 'sin_responsable_analisis':     
                      if ($('#user_tok').val() == $('#responsable_desvio').val()){
                            $('#responsable_analisis').parent().parent().show();
                            $('#id_responsable_segui').parent().parent().show();
                      }
                      if (($('#responsable_analisis').val() != '') && ($('#user_tok').val() == $('#responsable_analisis').val())){
                            $('#responsable_analisis').parent().parent().show();
                            $('#analisis_causal').attr('data-validation','required');  
                            $('#analisis_causal').parent().parent().show();
                            $('#descripcion_val').parent().parent().show();
                            $('#descripcion_val').attr('data-validation','required'); 
                            $('#alto_potencial_val').parent().parent().show();
                            $('#alto_potencial_val').attr('data-validation','required'); 
                            $('#proceso').parent().parent().show();
                            $('#div-tabs').show();
                            var campos_din = $('#nombre_cam_din').val().split(',');

                            for (i = 0; i < campos_din.length; i++) {
                                if (campos_din[i].length > 0){
                                    $('#'+campos_din[i]).parent().parent().show();
                                    $('#' + campos_din[i]).attr('data-validation','required');   
                                }                     
                            }
                      }
                      
                      break;
                  case 'sin_plan_accion':
                      if ($('#user_tok').val() == $('#responsable_desvio').val()){
                            $('#responsable_analisis').parent().parent().show();
                            $('#id_responsable_segui').parent().parent().show();
                      }                      
                      if ($('#user_tok').val() == $('#responsable_analisis').val()){
                            $('#responsable_analisis').parent().parent().show();
                            $('#analisis_causal').attr('data-validation','required'); 
                            $('#descripcion_val').parent().parent().show();
                            $('#descripcion_val').attr('data-validation','required'); 
                            $('#alto_potencial_val').parent().parent().show();
                            $('#alto_potencial_val').attr('data-validation','required'); 
                            $('#analisis_causal').parent().parent().show();
                            $('#proceso').parent().parent().show();
                            $('#div-tabs').show();
                            var campos_din = $('#nombre_cam_din').val().split(',');

                            for (i = 0; i < campos_din.length; i++) {
                                if (campos_din[i].length > 0){
                                    $('#'+campos_din[i]).parent().parent().show();
                                    $('#' + campos_din[i]).attr('data-validation','required');   
                                }                     
                            }
                      }
                      break;
                   case 'implementacion_acciones':
                      if ($('#user_tok').val() == $('#responsable_desvio').val()){
                            $('#responsable_analisis').parent().parent().show();
                            $('#id_responsable_segui').parent().parent().show();
                      }                      
                      if ($('#user_tok').val() == $('#responsable_analisis').val()){
                            $('#responsable_analisis').parent().parent().show();
                            $('#analisis_causal').attr('data-validation','required');  
                            $('#analisis_causal').parent().parent().show();
                            $('#descripcion_val').parent().parent().show();
                            $('#descripcion_val').attr('data-validation','required'); 
                            $('#alto_potencial_val').parent().parent().show();
                            $('#alto_potencial_val').attr('data-validation','required'); 
                            $('#proceso').parent().parent().show();
                            $('#div-tabs').show();
                            var campos_din = $('#nombre_cam_din').val().split(',');

                            for (i = 0; i < campos_din.length; i++) {
                                if (campos_din[i].length > 0){
                                    $('#'+campos_din[i]).parent().parent().show();
                                    $('#' + campos_din[i]).attr('data-validation','required');   
                                }                     
                            }
                      }
                      break;
              }
              
          }
          
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
          
    }
    
    function actualizar_cambio_respon_desvio(){
        if ($('#responsable_desvio').val() == $('#reportado_por').val()){
           $('#responsable_analisis').parent().parent().show();
           $('#id_responsable_segui').parent().parent().show();
        }else{
           /*OCULTAMOS CAMPOS NO REQuERIDOS ETAPA 2*/
           if (!($('#estatus').val() == 'sin_plan_accion')){
                $('#responsable_analisis').parent().parent().hide();
                 $('#id_responsable_segui').parent().parent().hide();
            }
           $('#analisis_causal').removeAttr('data-validation');
            $('#analisis_causal').parent().parent().hide();
            $('#div-tabs').hide();
            $('#proceso').parent().parent().hide();
            var campos_din = $('#nombre_cam_din').val().split(',');

            for (i = 0; i < campos_din.length; i++) {
                if (campos_din[i].length > 0){
                    $('#'+campos_din[i]).parent().parent().hide();
                    $('#' + campos_din[i]).removeAttr('data-validation');
                    //$('#' + id).attr('data-validation','required');  
                }                     
            }
       }
    }
    function agregar_esp(){
        var i = $('#num_items_esp').val();
        i = parseInt(i) + 1;        
        var html = '<tr id="tr-esp-' + i + '">'; 
        html = html + '<td align="center">'+
                           ' <i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i><i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'+
                           '<input id="orden_din_'+ i + '" type="hidden" name="orden_din_'+ i + '" value="'+ i + '">'+                           
                           ' <a href="' + i + '"  title="Eliminar " id="eliminar_esp_' + i + '"> ' + 
                            '<i class="icon icon-remove"></i>' +
                            '</a>' +
                      '  </td>';
        html = html + '<td style="display:none;">' +
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
               html = html + '<td>'+
                            '  <select id="validador_acc_'+ i + '" name="validador_acc_'+ i + '" class="form-control" data-validation="required" data-live-search="true">'+                            
                            '<option value="">-- Seleccione --</option>' + 
                                $('#option_responsables').val() +
                                
                            '</select>' +
                       '</td>';
        html = html + '<td class="td-table-data"><div class="col-sm-24" style="padding-left: 0px;padding-right: 0px;">'+
                            '<input id="fecha_acordada_'+ i + '" class="form-control"  data-date-format="DD/MM/YYYY"  type="text" data-validation="required" name="fecha_acordada_'+ i + '" >'+
                       '</div></td>';

        html = html + '</tr>' ;       
        $("#table-items-esp tbody").append(html);          
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
    
    function ordenar_tabla(){
    $("#table-items-esp tbody tr").each(function (i, row) {         
                var row_2 = $(row);
                family = row_2.find('input[name*="orden_din"]');
                family.val(i + 1);                                                               
            });
}
    
    function filtrar_arbol(){
          array = new XArray();
          array.setObjeto('ArbolOrganizacional','buscar_hijos');
          array.addParametro('import','clases.organizacion.ArbolOrganizacional');                      
          array.addParametro('b-id_organizacion',$('#b-id_organizacion_aux').val());
          array.addParametro('respon',1);
          xajax_Loading(array.getArray());
          $('#id-tree-ap').html('<div id="tree"></div>');
          ap_simple();
    }
    
    function filtrar_proceso(){
          array = new XArray();
          array.setObjeto('ArbolProcesos','buscar_hijos');
          array.addParametro('import','clases.arbol_procesos.ArbolProcesos');                      
          array.addParametro('b-id_proceso',$('#b-id_proceso_aux').val());
          xajax_Loading(array.getArray());
    }

    function nuevo_AccionesCorrectivas(){
            array = new XArray();
            array.setObjeto('AccionesCorrectivas','crear');
            array.addParametro('modo',document.getElementById('modo').value);            
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
            xajax_Loading(array.getArray());
    }

    function validar(doc){    
//        if ($('#fecha_acordada').val() != ''){ 
//            $('#id_responsable_segui').attr('data-validation',"required");
//        }
//        else {
//            $('#id_responsable_segui').removeAttr('data-validation');
//        }  
//        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('AccionesCorrectivas','guardar');
            else
                array.setObjeto('AccionesCorrectivas','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }
    
    function validarVer(doc){  
        if ($('#notificar').val() == 'si'){
            $('#desc_verificacion').attr('data-validation',"required");
            $('#fecha_realizada').attr('data-validation',"required");
        }
        else {
            $('#desc_verificacion').removeAttr('data-validation');
            $('#fecha_realizada').removeAttr('data-validation');
        }
        if ($('#fecha_acordada').val() != ''){ 
            $('#id_responsable_segui').attr('data-validation',"required");
        }
        else {
            $('#id_responsable_segui').removeAttr('data-validation');
        }  
        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            array.setObjeto('AccionesCorrectivas','actualizar_verificacion');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarAccionesCorrectivas(id){
        array = new XArray();
        array.setObjeto('AccionesCorrectivas','editar');
        array.addParametro('id',id);
        array.addParametro('modo',document.getElementById('modo').value);            
        array.addParametro('cod_link',document.getElementById('cod_link').value);
        array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
        xajax_Loading(array.getArray());
    }
    
    function verificarAccionesCorrectivas(id){
        array = new XArray();
        array.setObjeto('AccionesCorrectivas','verificar');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
        xajax_Loading(array.getArray());
    }


    function eliminarAccionesCorrectivas(id){
        if(confirm("¿Desea Eliminar el AccionesCorrectivas Seleccionado?")){
            array = new XArray();
            array.setObjeto('AccionesCorrectivas','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
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
        array.setObjeto('AccionesCorrectivas','buscar');
        array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
        array.addParametro('modo',document.getElementById('modo').value);
        array.addParametro('cod_link',document.getElementById('cod_link').value);
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verAccionesCorrectivas(id){
        array = new XArray();
        array.setObjeto('AccionesCorrectivas','ver');
        array.addParametro('id',id);
       array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
        xajax_Loading(array.getArray());
    }
    
    function verAcciones(id){
        array = new XArray();
        array.setObjeto('AccionesAC','indexAccionesAC');
        array.addParametro('id_ac',id);
        array.addParametro('id_accion',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }
    
    function EvidenciasMuestra(id){
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidenciaVisualizacion');
        array.addParametro('id_accion',id);
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }
    
    
    function adminEvidenciasVer(id){
        $('#myModal-Ventana').modal('hide');
        //$('#myModal-Evidencias').modal('show');
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidencia');
        array.addParametro('id_accion_correctiva',id);
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }
    
    
    
   function ao_simple(){    
    $('#div-ao-form').jstree(
            {
                "checkbox":{
                    three_state : false,
                        cascade : ''
                },
                "plugins": ["search", "types"]
            }
        );

   $('#div-ao-form').on("changed.jstree", function (e, data) {
       if (data.selected.length > 0){
           var arr;
           var id = '';
           for(i=0;i<data.selected.length;i++){
               arr = data.selected[i].split("_");
               id = id + arr[1] + ',';
           }
           id = id.substr(0,id.length-1);
           $('#b-id_organizacion_aux').val(id);
           
           
       }
       else
           $('#b-id_organizacion_aux').val('');        
   });
    var to = false;
    $('#demo_q_ao').keyup(function () {                    
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
                    var v = $('#demo_q_ao').val();
                    $('#div-ao-form').jstree(true).search(v);
            }, 250);
    });  
   }
   
    function ap_simple(){    
        /*var to = false;
        $('#demo_q').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                        var v = $('#demo_q').val();
                        $('#tree').jstree(true).search(v);
                }, 250);
        });*/
        
        $('#tree')
                .jstree({
                        'core' : {
                                'data' : {
                                        'url' : 'clases/arbol_procesos/server.php?id_ao='+$('#b-id_organizacion_aux').val(),
                                        //'url' : 'clases/organizacion/response.php?operation=get_node',
                                        'data' : function (node) {
                                                return { 'id' : node.id };
                                        }
                                },
                                'check_callback' : true,
                                'themes' : {
                                        'responsive' : false
                                }
                        },
                        'force_text' : true,
                        'plugins' : ['state','dnd','search']
                });
        $('#tree').on("changed.jstree", function (e, data) {
            if (data.selected.length > 0){
                var arr;
                var id = '';
                for(i=0;i<data.selected.length;i++){
                    arr = data.selected[i].split("_");
                    id = id + arr[1] + ',';
                }
                id = id.substr(0,id.length-1);
                $('#b-id_proceso_aux').val(data.selected[0]);


            }
            else
                $('#b-id_proceso_aux').val('');        
        });
}
//    $('#div-ao-form').jstree(true).open_all();               
        
   