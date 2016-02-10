
    function reset_formulario(){
        
        $('#idFormulario-hv').each (function(){
            this.reset();
        });       
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');
        $('#hv-anotacion').html('');
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
        cancelar_archivo_otro();
    }
    
    function cancelar_archivo_otro(){
        document.getElementById('info_archivo_adjunto').style.display = 'none';
        document.getElementById('fileUpload2').value = '';
        document.getElementById('tabla_fileUpload').style.display = '';
        document.getElementById('estado_actual').value = '';
    }
    
    function cargar_archivo_otro(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2');
            formData.append('fileUpload',fileInput.files[0]);
            formData.append("dato", "valor");
             $('#estado').show();
             var $bar = $('.progress-bar');
             $bar.width(0);
             $bar.text("0%");
            $.ajax({
                url: "pages/Hoja_de_Vida/uploadFileOtro2.php",
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
                            var $bar = $('.progress-bar');
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
                        $('#info_nombre').html(respuesta[0].info_nombre);
                        $('#filename').val(respuesta[0].filename);
                        $('#tamano').val(respuesta[0].tamano);
                        $('#tipo_doc').val(respuesta[0].tipo);
                        $('#estado_actual').val(respuesta[0].estado_actual);
                        $('#info_archivo_adjunto').show();
                   }
                   else{
                       alert(respuesta[0].msj);
                   }
                   //$('#estado').hide();
                   var $bar = $('.progress-bar');
                    $bar.width(0);
                    $bar.text("0%");
                });
    }
    
    function filtrar_mostrar_colums_hv(){
        var colums = '1-2-3-4-';
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

    function nuevo_HojadeVida(){
            array = new XArray();
            array.setObjeto('HojadeVida','crear');
            array.addParametro('import','clases.Hoja_de_Vida.HojadeVida');
            xajax_Loading(array.getArray());
    }

    function validar_hv(doc){
        if($('#idFormulario-hv').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc-hv").value == "new")
                array.setObjeto('HojadeVida','guardar');
            else
                array.setObjeto('HojadeVida','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario-hv');
            array.addParametro('import','clases.Hoja_de_Vida.HojadeVida');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarHojadeVida(id){
        array = new XArray();
        array.setObjeto('HojadeVida','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.Hoja_de_Vida.HojadeVida');
        xajax_Loading(array.getArray());
    }


    function eliminarHojadeVida(id){
        //alertify.confirm('This is a confirm message!');
        if(confirm("¿Desea Eliminar el HojadeVida Seleccionado?")){
            array = new XArray();
            array.setObjeto('HojadeVida','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.Hoja_de_Vida.HojadeVida');
            xajax_Loading(array.getArray());
        }
    }
    function verPagina_hv(pag,doc){
        array = new XArray();
        
        array.getForm('busquedaFrm-hv'); 
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
        array.setObjeto('HojadeVida','buscar');
        array.addParametro('import','clases.Hoja_de_Vida.HojadeVida');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verHojadeVida(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verHojadeVida.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function link_titulos_hv(valor){
    if (valor == $('#corder-hv').val()){
        if ($('#sorder-hv').val()== 'asc')
            $('#sorder-hv').val('desc');
        else 
            $('#sorder-hv').val('asc');
    }
    else
        $('#sorder-hv').val('desc');
    $('#corder-hv').val(valor);
    verPagina_hv(1,1);
}
    