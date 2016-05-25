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

function init_tabla(){
        $('.ver-mas').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
            $('#myModal-Ventana-Titulo').html('');
            $('#myModal-Ventana').modal('show');
        });
               
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
        $("#b-fecha-desde").datepicker();        
        $("#b-fecha-hasta").datepicker();
        $("#b-fecha_rev-desde").datepicker();
        $("#b-fecha_rev-hasta").datepicker();
        PanelOperator.initPanels("");
        ScrollBar.initScroll();
        init_filtro_rapido();
        init_filtro_ao_simple();
}

function MuestraFormulario(id){
        array = new XArray();
        array.setObjeto('Registros','indexRegistros');
        array.addParametro('titulo',$('#desc-mod-act').html());
        array.addParametro('modo',document.getElementById('modo').value);
        array.addParametro('cod_link',document.getElementById('cod_link').value);
        array.addParametro('id',id);
        array.addParametro('import','clases.registros.Registros');
        xajax_Loading(array.getArray());
    }

function actualizar_atributo_dinamico(id){        
    
        var valor = $('#tipo_din_' + id).val();           
        if ((valor == '7') || (valor == '8') || (valor == '9')|| (valor == 'Seleccion Simple') || (valor == 'Seleccion Multiple') || (valor == 'Combo')){
           // $("#valores_din_" + id).css("display", "");
            
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, '\n'));
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, ''));
            //$('#valores_din_' + id).attr('readonly','');
            //$('#valores_din_' + id).removeAttr('readonly');
            $('#ico_cmb_din_' + id).show();
            $('#valores_din_' + id).attr('data-validation','required');                     
        }
        else if ((valor == '13')|| (valor == 'Vigencia')){
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, '\n'));
            $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, ''));
            $('#ico_cmb_din_' + id).hide();
            $('#valores_din_' + id).removeAttr('readonly');
            $('#valores_din_' + id).attr('data-validation','required');  
        }
        else{
            $('#valores_din_' + id).attr('readonly','true');
            $('#valores_din_' + id).removeAttr('data-validation');   
            $('#ico_cmb_din_' + id).hide();
        }

    }
    
    function ajustar_valor_atributo_dinamico(id){          
         $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, '\n'));
         $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, ''));
    }
    
    function agregar_esp(){
        var i = $('#num_items_esp').val();
        i = parseInt(i) + 1;        
        var html = '<tr id="tr-esp-' + i + '">'; 
        html = html + '<td align="center">'+
                           ' <i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i><i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'+
                           '<input id="orden_din_'+ i + '" type="hidden" name="orden_din_'+ i + '" value="'+ i + '">'+
                           '<input id="cmb_din_'+ i + '" type="hidden" name="cmb_din_'+ i + '" tok="' + i + '" value="'+ i + '">'+
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
                                '<option value="13">Vigencia</option>'+ 
                                '<option value="14">Cargo</option>'+ 
                            '</select>' +
                        '</td>';
        html = html + '<td>' +
                            //' <input type="text" id="valores_din_'+ i + '" name="valores_din_'+ i + '" size="15"  readonly="readonly"/>'+
                           
                           ' <textarea id="valores_din_'+ i + '" cols="30" rows="2" name="valores_din_'+ i + '"class="form-control" readonly=""></textarea>'+
                           //'<i class="icon icon-more cursor-pointer" id="ico_cmb_din_'+ i + '" tok="'+i+'"></i>'+
//                            '<div class="input-group">'+
//                                  ' <input type="text" id="add_item_din_'+ i + '" size="15" class="form-control"/>'+
//                                  '<span class="input-group-addon cursor-pointer" id=""><span class="glyphicon glyphicon glyphicon-plus"></span></span>'
//                           + '</div>'+
                        '</td>';
        html = html + '<td>' +
                           '<i class="icon icon-more cursor-pointer" style="display:none;" id="ico_cmb_din_'+ i + '" tok="'+i+'"></i>'+
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
        $("#ico_cmb_din_" + i).click(function(e){ 
            e.preventDefault();
            var id = $(this).attr('tok');            
            array = new XArray();
            array.setObjeto('ItemsFormulario','indexItemsFormulario');
            array.addParametro('tok',id);
            array.addParametro('id',$('#cmb_din_'+id).val());
            array.addParametro('titulo',$('#nombre_din_'+id).val());
            array.addParametro('token', $('#tok_new_edit').val());
            array.addParametro('import','clases.items_formulario.ItemsFormulario');
            xajax_Loading(array.getArray());
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
    

    
        $( "#id_workflow_documento" ).select2({
            placeholder: "Selecione el revisor",
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
        var colums = '2-3-';
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
            array.addParametro('modo',document.getElementById('modo').value);            
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
    }
    
    
    function crearRevisionDocumentos(id){
            array = new XArray();
            array.setObjeto('Documentos','crear_revision');
            array.addParametro('id',id);
            array.addParametro('import','clases.documentos.Documentos');
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            xajax_Loading(array.getArray());
    }
    
    function crearVersionDocumentos(id){
            array = new XArray();
            array.setObjeto('Documentos','crear_version');
            array.addParametro('id',id);
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        
        if ($('#estado_actual').length > 0){
            if (!($('#estado_actual').val() == -1)){
                $('#fileUpload2').attr('data-validation',"required");
            }
        }
        if($('#idFormulario').isValid()) {
            //var iframe = document.getElementById("iframearbol");
            //iframe.contentWindow.submitMe();

            var _TxtIdNodos = document.getElementById("nodos").value;
            if (_TxtIdNodos == ''){
                VerMensaje('error','Debe Ingresar el Arbol Organizacional');
                return;
            }
            
            if (($('#nombre_doc_vis').val().length > 0)){                
                /*VALIDAR QUE EL NOMBRE DEL DOC VIS SEA EL MISMO QUE EL DOC FUENTE*/
                var nombre_doc = $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V' + $('#version').val();                
                if (($('#nombre_doc_vis').val() != nombre_doc)&&($('#estado_actual_vis').val()!=-1)){
                    if(!confirm("El nombre del documento de visualización, no corresponde con el nombre del documento fuente, este sera renombrado con \""+nombre_doc + "\"\n¿Desea Continuar?")){
                        return;
                    }
                    else{
                        $('#nombre_doc_vis').val(nombre_doc);
                    }
                }
            }
            var ejecutar = function (){
                if(document.getElementById('id_workflow_documento').disabled)
                    document.getElementById('id_workflow_documento').disabled=false;
                $( "#btn-guardar" ).html('Procesando..');
                $( "#btn-guardar" ).prop( "disabled", true );
                $( "#btn-guardar-not" ).prop( "disabled", true );
                array = new XArray();
                if (doc.getElementById("opc").value == "new")
                    array.setObjeto('Documentos','guardar');
                else
                    array.setObjeto('Documentos','actualizar');

                array.addParametro('modo',document.getElementById('modo').value);
                array.addParametro('cod_link',document.getElementById('cod_link').value);
                array.addParametro('permiso',document.getElementById('permiso_modulo').value);
                array.getForm('idFormulario');
                array.addParametro('import','clases.documentos.Documentos');
                xajax_Loading(array.getArray());
            }
            if (($('#notificar').val() == 'si')&&($('#estado_actual').length > 0)){
                bootbox.confirm("Va a Notificar al WF " + $("#id_workflow_documento option:selected").text() 
                + " para Revisión, asegúrese que el " + $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V' + $('#version').val()
                + " son las correctas en el cuerpo del documento, <br>¿Desea Continuar?", function(result) {
                    if (result == true){
                        ejecutar();
                    }

                }); 
            }
            else{
                ejecutar();
            }
//alert(2);
//return;
            
            
        }else{
            alertify.error("Existen campos no validos.",5); 
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
            if (($('#nombre_doc_vis').val().length > 0)){                
                /*VALIDAR QUE EL NOMBRE DEL DOC VIS SEA EL MISMO QUE EL DOC FUENTE*/
                var nombre_doc = $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V' + $('#version').val();                
                if ($('#nombre_doc_vis').val() != nombre_doc){
                    if(!confirm("El nombre del documento de visualización, no corresponde con el nombre del documento fuente, este sera renombrado con \""+nombre_doc + "\"\n¿Desea Continuar?")){
                        return;
                    }
                    else{
                        $('#nombre_doc_vis').val(nombre_doc);
                    }
                }
            }
            var ejecutar = function (){
                $( "#btn-guardar" ).html('Procesando..');
                $( "#btn-guardar" ).prop( "disabled", true );
                array = new XArray();

                array.setObjeto('Documentos','guardar_version');            
                array.addParametro('permiso',document.getElementById('permiso_modulo').value);
                array.getForm('idFormulario');
                array.addParametro('import','clases.documentos.Documentos');
                xajax_Loading(array.getArray());
            }
            bootbox.confirm("Va a Notificar al WF " + $("#id_workflow_documento option:selected").text() 
                + " para Revisión, asegúrese que el " + $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V' + $('#version').val()
                + " son las correctas en el cuerpo del documento, <br>¿Desea Continuar?", function(result) {
                    if (result == true){
                        ejecutar();
                    }

                }); 
            
        }else{
        
        }
    }

    function editarDocumentos(id){
        array = new XArray();
        array.setObjeto('Documentos','editar');
        array.addParametro('modo',document.getElementById('modo').value);            
        array.addParametro('cod_link',document.getElementById('cod_link').value);        
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
        document.getElementById('filename').value = '';
        
    }
    
    function cargar_archivo_otro(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        //alert('entro');
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2');
            if(fileInput.files[0].size>1024*1024*3){
                VerMensaje('error','El archivo excede el tamaño permitido en este sitio, Tamaño máximo del archivo a subir: 3MB');
                $('#fileUpload2_vis').val('');
                return;
            }
            $('#estado').show();
            var $bar = $('#estado-progress-bar');
             $bar.width(0);
             $bar.text("0%");
            formData.append('fileUpload',fileInput.files[0]);
            formData.append("dato", "valor");
            $.ajax({
                url: "pages/documentos/uploadFileOtro2.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false,
             xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress', function(prog) {
                            var value = ~~((prog.loaded / prog.total) * 100);
                            var $bar = $('#estado-progress-bar');
                            $bar.width(value*250/100);
                            $bar.text(value+ "%");
                        }, false);
                    }
                    return myXhr;
                }
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
                        validar_codigo_version();
                   }
                   else{
                       VerMensaje('error',respuesta[0].msj);
                       $('#fileUpload2').val('');
                       
                   }
                   $('#estado').hide();
                });
    }
    
    function cargar_archivo_ver(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2');  
            if(fileInput.files[0].size>1024*1024*3){
                VerMensaje('error','El archivo excede el tamaño permitido en este sitio, Tamaño máximo del archivo a subir: 3MB');
                $('#fileUpload2_vis').val('');
                return;
            }
            var nombres_aux = fileInput.files[0].name.split('-');
            if(nombres_aux.length!=3){
                if (!confirm('El documento seleccionado, tiene un nombre diferente al esperado, sera renombrado a ' + 
                        $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V'+$('#version').val()+'\n ¿Deseas continuar?')){
                        $('#fileUpload2').val('');
                    return;
                }
            }
            else{
                var codigo = nombres_aux[0].replace(' ', '');
                var version = nombres_aux[2].replace(' ', '');
                version = version.replace('V', '');
                version = version.substring(0,version.lastIndexOf('.')-1);
                if (nombres_aux[1] != $('#nombre_doc').val()){
                    if (!confirm('El documento seleccionado, tiene un nombre diferente al esperado, sera renombrado a ' + 
                            $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V'+$('#version').val()+'\n ¿Deseas continuar?')){
                            $('#fileUpload2').val('');
                        return;
                    }
                }
                else
                if (codigo != $('#Codigo_doc').val()){
                    if (!confirm('El documento seleccionado, tiene un nombre diferente al esperado, sera renombrado a ' + 
                            $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V'+$('#version').val()+'\n ¿Deseas continuar?')){
                            $('#fileUpload2').val('');
                        return;
                    }
                }
                else
                if (version != $('#version').val()){
                    if (!confirm('El documento seleccionado, tiene un nombre diferente al esperado, sera renombrado a ' + 
                            $('#Codigo_doc').val() + '-' + $('#nombre_doc').val() + '-V'+$('#version').val()+'\n ¿Deseas continuar?')){
                            $('#fileUpload2').val('');
                        return;
                    }
                }
            }
            formData.append('fileUpload',fileInput.files[0]);
            
            formData.append("nombre_doc", $('#nombre_doc').val());
            formData.append("codigo_doc", $('#Codigo_doc').val());
            formData.append("version", $('#version').val());
            $('#estado').show();
            var $bar = $('#estado-progress-bar');
            $bar.width(0);
            $bar.text("0%");
            $.ajax({
                url: "pages/documentos/uploadFileOtro_ver.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false,
             xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress', function(prog) {
                            var value = ~~((prog.loaded / prog.total) * 100);
                            var $bar = $('#estado-progress-bar');
                            $bar.width(value*250/100);
                            $bar.text(value+ "%");
                        }, false);
                    }
                    return myXhr;
                }
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
                       $('#fileUpload2').val('');
                   }
                   $('#estado').hide();
                });
    }
    
    function cancelar_archivo_vis(){
        document.getElementById('info_archivo_adjunto_vis').style.display = 'none';
        document.getElementById('fileUpload2_vis').value = '';
        document.getElementById('tabla_fileUpload_vis').style.display = '';
        document.getElementById('estado_actual_vis').value = '';
        $('#nombre_doc_vis').val('');        
    }
    
    function cargar_archivo_vis(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2_vis');
            if(fileInput.files[0].size>1024*1024*3){
                VerMensaje('error','El archivo excede el tamaño permitido en este sitio, Tamaño máximo del archivo a subir: 3MB');
                $('#fileUpload2_vis').val('');
                return;
            }
            
            formData.append('fileUpload',fileInput.files[0]);
            formData.append("nombre_doc", $('#nombre_doc').val());
            formData.append("codigo_doc", $('#Codigo_doc').val());
            formData.append("version", $('#version').val());
            $('#estado_vis').show();
            var $bar = $('#estado-vis-progress-bar');
            $bar.width(0);
            $bar.text("0%");
            $.ajax({
                url: "pages/documentos/uploadFileOtro2_vis.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false,
             xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress', function(prog) {
                            var value = ~~((prog.loaded / prog.total) * 100);
                            var $bar = $('#estado-vis-progress-bar');
                            $bar.width(value*250/100);
                            $bar.text(value+ "%");
                        }, false);
                    }
                    return myXhr;
                }
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
                       $('#fileUpload2_vis').val('');
                   }
                   $('#estado_vis').hide();
                });
    }
    
function verWorkFlow(id){
    array = new XArray();
    array.setObjeto('Documentos','ver_workflow');
    array.addParametro('id',id);
    array.addParametro('import','clases.documentos.Documentos');
    xajax_Loading(array.getArray());
    //PanelOperator.showDetail('');    
}
function CambiarEstadoWF(estado,etapa,id){
    array = new XArray();
    array.setObjeto('Documentos','cambiar_estado');
    array.addParametro('id',id);
    array.addParametro('estado',estado);
    array.addParametro('etapa',etapa);
    array.addParametro('import','clases.documentos.Documentos');
    xajax_Loading(array.getArray());
}
function RechazarWF(estado,etapa,id){
    if(document.getElementById("observacion_rechazo").style.display==''){
        array = new XArray();
        array.setObjeto('Documentos','cambiar_estado');
        array.addParametro('id',id);
        array.addParametro('estado',estado);
        array.addParametro('etapa',etapa);
        array.addParametro('observacion_rechazo',document.getElementById("observacion_rechazo").value);
        array.addParametro('import','clases.documentos.Documentos');
        xajax_Loading(array.getArray());
        $('#myModal-observacion-rechazo').modal('hide');
        
        }
    else{
        document.getElementById("observacion_rechazo").style.display='';
        alertify.error("Cargue una observacion de rechazo y vuelva a presionar Rechazar",5); 
    }
        
}
 function Notificar(doc){
            array = new XArray();
            array.setObjeto('Documentos','actualizar_notificar_wf');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
    }
 function GuardarNotificar(doc){
            array = new XArray();
            array.setObjeto('Documentos','actualizar_notificar_wf');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
    }   
   function CargarCombowf(nodos,id){
       if ($('#opc').val() == 'new'){
            array = new XArray();
            array.setObjeto('Documentos','cargar_combo_wf');
            array.addParametro('nodos',nodos);
            array.addParametro('id',id);
            array.addParametro('import','clases.documentos.Documentos');
            xajax_Loading(array.getArray());
        }
    }  
function ao_multiple(){    
    $('#div-ao-form').jstree(
            {
                "checkbox":{
                    three_state : false,
                        cascade : ''
                },
                "plugins": ["search", "types","checkbox"]
            }
        );
    $("#div-ao-form").on("select_node.jstree", function (e, data) {
        if(data.event) { 
            data.instance.select_node(data.node.children_d);
        }
    });
    $("#div-ao-form").on("deselect_node.jstree", function (e, data) {
        if(data.event) { data.instance.deselect_node(data.node.children_d); }
    });
    var to_2 = false;
   $('#div-ao-form').on("changed.jstree", function (e, data) {
       if (data.selected.length > 0){
           var arr;
           var id = '';
           for(i=0;i<data.selected.length;i++){
               arr = data.selected[i].split("_");
               id = id + arr[1] + ',';
           }
           id = id.substr(0,id.length-1);
           $('#nodos').val(id);
           
           if(to_2) { clearTimeout(to_2); }
           to_2 = setTimeout(function () {
                   validar_codigo_version();
                   CargarCombowf($('#nodos').val(),$('#id').val());                   
           }, 250);
       }
       else
           $('#nodos').val('');        
   });
    var to = false;
    $('#demo_q_ao').keyup(function () {                    
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
                    var v = $('#demo_q_ao').val();
                    $('#div-ao-form').jstree(true).search(v);
            }, 250);
    });  
//    $('#div-ao-form').jstree(true).open_all();               
        
}    
function validar_codigo_version(){
   if (($('#nodos').val().length > 0) && ($('#opc').val() == 'new')){
       array = new XArray();
       array.setObjeto('DocumentoCodigos','validar_codigo_version');
       array.addParametro('nodos',$('#nodos').val());        
       array.addParametro('import','clases.documento_codigos.DocumentoCodigos');
       xajax_Loading(array.getArray());
   }
}