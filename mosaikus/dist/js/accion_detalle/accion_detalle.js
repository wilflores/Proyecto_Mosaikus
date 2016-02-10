
    
    /*function filtrar_mostrar_colums(){
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
         
    }*/

    function nuevo_AccionesCorectivasDetalle(){
            array = new XArray();
            array.setObjeto('AccionesCorectivasDetalle','crear');
            array.addParametro('import','clases.accion_detalle.AccionesCorectivasDetalle');
            xajax_Loading(array.getArray());
    }

    function validar_hv(doc){
        
        if($('#idFormulario-hv').isValid()) {
            array = new XArray();
            if (doc.getElementById("opc-hv").value == "new")
                array.setObjeto('AccionesCorectivasDetalle','guardar');
            else
                array.setObjeto('AccionesCorectivasDetalle','actualizar');
            $( "#btn-guardar-hv" ).val('Procesando..');
            $( "#btn-guardar-hv" ).prop( "disabled", true );
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario-hv');
            array.addParametro('import','clases.accion_detalle.AccionesCorectivasDetalle');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarAccionesCorectivasDetalle(id){
        array = new XArray();
        array.setObjeto('AccionesCorectivasDetalle','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.accion_detalle.AccionesCorectivasDetalle');
        xajax_Loading(array.getArray());
    }


    function eliminarAccionesCorectivasDetalle(id){
        if(confirm("¿Desea Eliminar la Acción Corectiva Seleccionada?")){
            array = new XArray();
            array.setObjeto('AccionesCorectivasDetalle','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.accion_detalle.AccionesCorectivasDetalle');
            xajax_Loading(array.getArray());
        }
    }
    function verPagina_hv(pag,doc){
        array = new XArray();
        if (doc== null)
        {
             $('form')[0].reset();             
        }
        array.getForm('busquedaFrm-hv'); 
        if ((isNaN(document.getElementById("reg_por_pag-hv").value) == true) || (parseInt(document.getElementById("reg_por_pag-hv").value) <= 0)){
            array.addParametro('reg_por_pagina', 10);
            document.getElementById("reg_por_pag-hv").value = 10
        }
        else
        {
            array.addParametro('reg_por_pagina', document.getElementById("reg_por_pag-hv").value);
        }
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('AccionesCorectivasDetalle','buscar');
        array.addParametro('import','clases.accion_detalle.AccionesCorectivasDetalle');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verAccionesCorectivasDetalle(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verAccionesCorectivasDetalle.php?id='+id;
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
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');        
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
        
    }
    
    function adminEvidencias(id){
        $('#myModal-Ventana').modal('hide');
        //$('#myModal-Evidencias').modal('show');
        array = new XArray();
        array.setObjeto('EvidenciaAccionesCorrec','indexEvidenciaAccionesCorrec');
        array.addParametro('id',id);
        array.addParametro('import','clases.evidencia_acciones.EvidenciaAccionesCorrec');
        xajax_Loading(array.getArray());
    }
    