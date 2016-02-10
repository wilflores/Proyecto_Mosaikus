
    
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

    function nuevo_ParametrosDet(){
            array = new XArray();
            array.setObjeto('ParametrosDet','crear');
            array.addParametro('import','clases.parametros_det.ParametrosDet');
            xajax_Loading(array.getArray());
    }
    
    function reset_formulario(){
        
        $('#idFormulario-hv').each (function(){
            this.reset();
        });       
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');        
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
    }

    function validar_hv(doc){
        if($('#idFormulario-hv').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc-hv").value == "new")
                array.setObjeto('ParametrosDet','guardar');
            else
                array.setObjeto('ParametrosDet','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario-hv');
            array.addParametro('import','clases.parametros_det.ParametrosDet');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarParametrosDet(id){
        array = new XArray();
        array.setObjeto('ParametrosDet','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.parametros_det.ParametrosDet');
        xajax_Loading(array.getArray());
    }


    function eliminarParametrosDet(id){
        if(confirm("¿Desea Eliminar el Items Seleccionado?")){
            array = new XArray();
            array.setObjeto('ParametrosDet','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.parametros_det.ParametrosDet');
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
        array.setObjeto('ParametrosDet','buscar');
        array.addParametro('import','clases.parametros_det.ParametrosDet');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verParametrosDet(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verParametrosDet.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    