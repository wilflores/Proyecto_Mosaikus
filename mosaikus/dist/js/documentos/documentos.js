function  activar_desactivar_pest(){
    if ($('#formulario').is(':checked')){
        $('#tabs-form-reg').attr('data-toggle',"tab");
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
    }
    else{
        $('#tabs-form-reg').removeAttr('data-toggle');
        $('.nav-tabs a[href="#hv-red"]').tab('show');
    }
}

function init_filtrar(){
        $( "#b-reviso" ).select2({
            placeholder: "Selecione el revisor",
            allowClear: true
        }); 
        $( "#b-elaboro" ).select2({
            placeholder: "Selecione el elaborador",
            allowClear: true
        }); 
        $( "#b-aprobo" ).select2({
            placeholder: "Selecione el aprobador",
            allowClear: true
        }); 
        $('#b-fecha-desde').datepicker();        
        $('#b-fecha-hasta').datepicker();
        $('#b-fecha_rev-desde').datepicker();
        $('#b-fecha_rev-hasta').datepicker();
        PanelOperator.initPanels("");
        ScrollBar.initScroll();
        init_filtro_rapido();
        init_filtro_ao_simple();
}

function MuestraFormulario(id){
        array = new XArray();
        array.setObjeto('Registros','indexRegistros');
        array.addParametro('titulo',$('#desc-mod-act').html());
    
        array.addParametro('id',id);
        array.addParametro('import','clases.registros.Registros');
        xajax_Loading(array.getArray());
    }

function actualizar_atributo_dinamico(id){        
    
        var valor = $('#tipo_din_' + id).val();           
        if ((valor == '7') || (valor == '8') || (valor == '9')){
           // $("#valores_din_" + id).css("display", "");
            
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, '\n'));
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, ''));
            //$('#valores_din_' + id).attr('readonly','');
            $('#valores_din_' + id).removeAttr('readonly');
            $('#valores_din_' + id).attr('data-validation','required');                     
        }
        else if ((valor == 'Seleccion Simple') || (valor == 'Seleccion Multiple') || (valor == 'Combo')){
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, '\n'));
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, ''));
        }
        else{
            $('#valores_din_' + id).attr('readonly','true');
            $('#valores_din_' + id).removeAttr('data-validation');   
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
                           //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' + 
                            '<i class="icon icon-remove"></i>' +
                            '</a>' +
                      '  </td>';
        html = html + '<td class="td-table-data">'+
                            '<input id="nombre_din_'+ i + '" class="form-control" type="text" data-validation="required" size="15" maxlength="20" name="nombre_din_'+ i + '">'+
                       '</td>';
        html = html + '<td>' +
                           '  <select id="tipo_din_'+ i + '" onchange="actualizar_atributo_dinamico('+ i + ');" name="tipo_din_'+ i + '" class="form-control">'+
                                '<option value="7">Seleccion Simple</option>' +
                                '<option value="8">Seleccion Multiple</option>' +
                                '<option value="9">Combo</option> ' +
                                '<option selected value="1">Texto</option> ' +
                                '<option value="2">Numerico</option>' +
                                '<option value="3">Fecha</option>' +
                                '<option value="5">Rut</option>' +
                                '<option value="6">Persona</option>'+
                                '<option value="10">Semáforo</option>'+ 
                                '<option value="11">Árbol Organizacional</option>'+ 
                                '<option value="12">Árbol Procesos</option>'+ 
                            '</select>' +
                        '</td>';
        html = html + '<td>' +
                           ' <textarea id="valores_din_'+ i + '" cols="30" rows="2" name="valores_din_'+ i + '" readonly=""></textarea>'+
                        '</td>';
        html = html + '</tr>' ;       
        $("#table-items-esp tbody").append(html);          
  
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

function cargar_autocompletado(){
    

    
        $( "#reviso" ).select2({
            placeholder: "Selecione el revisor",
            allowClear: true
          }); 
          $( "#elaboro" ).select2({
            placeholder: "Selecione el elaborador",
            allowClear: true
          }); 
          $( "#aprobo" ).select2({
            placeholder: "Selecione el aprobador",
            allowClear: true
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
          
      }
      
    function filtrar_mostrar_colums(){
        var colums = '1-2-3-';
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

    function nuevo_Documentos(){
            array = new XArray();
            array.setObjeto('Documentos','crear');
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
    }
    
    
    function crearRevisionDocumentos(id){
            array = new XArray();
            array.setObjeto('Documentos','crear_revision');
            array.addParametro('id',id);
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
    }
    
    function crearVersionDocumentos(id){
            array = new XArray();
            array.setObjeto('Documentos','crear_version');
            array.addParametro('id',id);
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        if($('#idFormulario').isValid()) {
            var iframe = document.getElementById("iframearbol");
            iframe.contentWindow.submitMe();

            var _TxtIdNodos = document.getElementById("nodos").value = iframe.contentWindow.document.getElementById('jsfields').value;
            if (_TxtIdNodos == ''){
                VerMensaje('error','Debe Ingresar el Arbol Organizacional');
                return;
            }
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('Documentos','guardar');
            else
                array.setObjeto('Documentos','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
        }else{
            alertify.error("Existen campos no validos.",0); 
        }
    }
    
    function validar_rev(doc){
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
           
            array.setObjeto('Documentos','guardar_revision');            
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }
    
    function validar_ver(doc){
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
           
            array.setObjeto('Documentos','guardar_version');            
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarDocumentos(id){
        array = new XArray();
        array.setObjeto('Documentos','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.documentos.Documentos');
        xajax_Loading(array.getArray());
    }


    function eliminarDocumentos(id){
        if(confirm("¿Desea Eliminar el Documentos Seleccionado?")){
            array = new XArray();
            array.setObjeto('Documentos','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.documentos.Documentos');
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
        array.setObjeto('Documentos','buscar');
        array.addParametro('import','clases.documentos.Documentos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verDocumentos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verDocumentos.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function cancelar_archivo_otro(){
        document.getElementById('info_archivo_adjunto').style.display = 'none';
        document.getElementById('fileUpload2').value = '';
        document.getElementById('tabla_fileUpload').style.display = '';
        document.getElementById('estado_actual').value = '';
    }
    
    function cargar_archivo_otro(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        //alert('entro');
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2');
            $('#estado').show();
            formData.append('fileUpload',fileInput.files[0]);
            formData.append("dato", "valor");
            $.ajax({
                url: "pages/documentos/uploadFileOtro2.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false
            })
                .done(function(res){
                    //alert(res);
                   respuesta = $.parseJSON(res);
           //alert(respuesta);
           //alert(respuesta[0].exito);
                   if (respuesta[0].exito == 1) {
                        //alert() ;
                        $('#tabla_fileUpload').hide();
                        $('#info_nombre').val(respuesta[0].info_nombre);
                        $('#filename').val(respuesta[0].filename);
                        $('#tamano').val(respuesta[0].tamano);
                        $('#tipo_doc').val(respuesta[0].tipo);
                        $('#estado_actual').val(respuesta[0].estado_actual);
                        $('#info_archivo_adjunto').show();
                        $('#Codigo_doc').val(respuesta[0].codigo_doc);
                        $('#nombre_doc').val(respuesta[0].nombre_doc);
                        $('#version').val(respuesta[0].version_doc);
                   }
                   else{
                       VerMensaje('error',respuesta[0].msj);
                   }
                   $('#estado').hide();
                });
    }
    
    function cargar_archivo_ver(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2');
            formData.append('fileUpload',fileInput.files[0]);
            formData.append("nombre_doc", $('#nombre_doc').val());
            formData.append("codigo_doc", $('#Codigo_doc').val());
            formData.append("version", $('#version').val());
            $('#estado').show();
            $.ajax({
                url: "pages/documentos/uploadFileOtro_ver.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false
            })
                .done(function(res){
                   respuesta = $.parseJSON(res);
                   if (respuesta[0].exito == 1) {
                        //alert() ;
                        $('#tabla_fileUpload').hide();
                        $('#info_nombre').val(respuesta[0].info_nombre);
                        $('#filename').val(respuesta[0].filename);
                        $('#tamano').val(respuesta[0].tamano);
                        $('#tipo_doc').val(respuesta[0].tipo);
                        $('#estado_actual').val(respuesta[0].estado_actual);
                        $('#info_archivo_adjunto').show();
                        //$('#Codigo_doc').val(respuesta[0].codigo_doc);
                        //$('#nombre_doc').val(respuesta[0].nombre_doc);
                        //$('#version').val(respuesta[0].version_doc);
                   }
                   else{
                       VerMensaje('error',respuesta[0].msj);
                   }
                   $('#estado').hide();
                });
    }
    
    function cancelar_archivo_vis(){
        document.getElementById('info_archivo_adjunto_vis').style.display = 'none';
        document.getElementById('fileUpload2_vis').value = '';
        document.getElementById('tabla_fileUpload_vis').style.display = '';
        document.getElementById('estado_actual_vis').value = '';
    }
    
    function cargar_archivo_vis(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2_vis');
            formData.append('fileUpload',fileInput.files[0]);
            formData.append("nombre_doc", $('#nombre_doc').val());
            formData.append("codigo_doc", $('#Codigo_doc').val());
            formData.append("version", $('#version').val());
            $('#estado_vis').show();
            $.ajax({
                url: "pages/documentos/uploadFileOtro2_vis.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false
            })
                .done(function(res){
                   respuesta = $.parseJSON(res);
                   if (respuesta[0].exito == 1) {
                        //alert() ;
                        $('#tabla_fileUpload_vis').hide();
                        $('#info_nombre_vis').val(respuesta[0].info_nombre);
                        $('#filename_vis').val(respuesta[0].filename);
                        $('#tamano_vis').val(respuesta[0].tamano);
                        $('#tipo_doc_vis').val(respuesta[0].tipo);
                        $('#estado_actual_vis').val(respuesta[0].estado_actual);
                        $('#info_archivo_adjunto_vis').show();
                        //$('#Codigo_doc_vis').val(respuesta[0].codigo_doc);
                        $('#nombre_doc_vis').val(respuesta[0].nombre_doc);
                        //$('#version_vis').val(respuesta[0].version_doc);
                   }
                   else{
                       VerMensaje('error',respuesta[0].msj);
                   }
                   $('#estado_vis').hide();
                });
    }
    