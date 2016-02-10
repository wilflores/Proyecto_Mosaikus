
    
   
    function nuevo_AccionesEvidencia(){
            array = new XArray();
            array.setObjeto('AccionesEvidencia','crear');
            array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
            xajax_Loading(array.getArray());
    }

    function validar_hv_2(doc){        
        if($('#idFormulario-hv-2').isValid()) {
            $( "#btn-guardar-hv-2" ).html('Procesando..');
            $( "#btn-guardar-hv-2" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc-hv-2").value == "new")
                array.setObjeto('AccionesEvidencia','guardar');
            else
                array.setObjeto('AccionesEvidencia','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario-hv-2');
            array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarAccionesEvidencia(id){
        array = new XArray();
        array.setObjeto('AccionesEvidencia','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }


    function eliminarAccionesEvidencia(id){
        if(confirm("¿Desea Eliminar la Evidencia Seleccionada?")){
            array = new XArray();
            array.setObjeto('AccionesEvidencia','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
            xajax_Loading(array.getArray());
        }
    }
    
    function link_titulos_hv_2(valor){
        if (valor == $('#corder-hv-2').val()){
            if ($('#sorder-hv-2').val()== 'asc')
                $('#sorder-hv-2').val('desc');
            else 
                $('#sorder-hv-2').val('asc');
        }
        else
            $('#sorder-hv-2').val('desc');
        $('#corder-hv-2').val(valor);
        verPagina_hv_2(1,1);
    }

    function verPagina_hv_2(pag,doc){
        array = new XArray();
        if (doc== null)
        {
             $('form')[0].reset();             
        }
        array.getForm('busquedaFrm-hv-2'); 
        if ((isNaN(document.getElementById("reg_por_pag-hv-2").value) == true) || (parseInt(document.getElementById("reg_por_pag-hv-2").value) <= 0)){
            array.addParametro('reg_por_pagina', 10);
            document.getElementById("reg_por_pag").value = 10
        }
        else
        {
            array.addParametro('reg_por_pagina', document.getElementById("reg_por_pag-hv-2").value);
        }
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('AccionesEvidencia','buscar');
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verAccionesEvidencia(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verAccionesEvidencia.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function cancelar_archivo(){
        document.getElementById('info_archivo_adjunto').style.display = 'none';
        document.getElementById('fileUpload2').value = '';
        document.getElementById('tabla_fileUpload').style.display = '';
        document.getElementById('estado_actual').value = '';
    }
    
    function cargar_archivo(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2');
            formData.append('fileUpload',fileInput.files[0]);
            //formData.append("nombre_doc", $('#nombre_doc').val());
            //formData.append("codigo_doc", $('#Codigo_doc').val());
            //formData.append("version", $('#version').val());
            $('#estado').show();
            $.ajax({
                url: "pages/acciones_evidencia/uploadFileOtro2_vis.php",
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
                        //$('#Codigo_doc_vis').val(respuesta[0].codigo_doc);
                        $('#nom_archivo').val(respuesta[0].nombre_doc);
                        //$('#version_vis').val(respuesta[0].version_doc);
                   }
                   else{
                       VerMensaje('error',respuesta[0].msj);
                   }
                   $('#estado').hide();
                });
    }


function reset_formulario_2(){
         
        $('#idFormulario-hv-2').each (function(){
            this.reset();
        });             
        $('#idFormulario-hv-2 textarea').html('');
        $("#hv-id_persona").select2("val", "");
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');        
        $('.nav-tabs a[href="#hv-orange-2"]').tab('show');
        cancelar_archivo();
        $("#cod_emp-hv-2").select2("val", "");
    }
    