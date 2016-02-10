
    /*
    function filtrar_mostrar_colums(){
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

    function nuevo_MatricesParametrosDetalle(){
            array = new XArray();
            array.setObjeto('MatricesParametrosDetalle','crear');
            array.addParametro('import','clases.matrices_parametros_detalle.MatricesParametrosDetalle');
            xajax_Loading(array.getArray());
    }*/

    function validar_hv(doc){        
        if($('#idFormulario-hv').isValid()) {
            $( "#btn-guardar-hv" ).html('Procesando..');
            $( "#btn-guardar-hv" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc-hv").value == "new")
                array.setObjeto('MatricesParametrosDetalle','guardar');
            else
                array.setObjeto('MatricesParametrosDetalle','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario-hv');
            array.addParametro('import','clases.matrices_parametros_detalle.MatricesParametrosDetalle');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarMatricesParametrosDetalle(id){
        array = new XArray();
        array.setObjeto('MatricesParametrosDetalle','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.matrices_parametros_detalle.MatricesParametrosDetalle');
        xajax_Loading(array.getArray());
    }


    function eliminarMatricesParametrosDetalle(id){
        if(confirm("¿Desea Eliminar el Item Seleccionado?")){
            array = new XArray();
            array.setObjeto('MatricesParametrosDetalle','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.matrices_parametros_detalle.MatricesParametrosDetalle');
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
        array.setObjeto('MatricesParametrosDetalle','buscar');
        array.addParametro('import','clases.matrices_parametros_detalle.MatricesParametrosDetalle');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verMatricesParametrosDetalle(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verMatricesParametrosDetalle.php?id='+id;
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
        //$('#idFormulario-hv textarea').html('');
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');        
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
        
    }
    