function init_filtrar(){        
        PanelOperator.initPanels("");
        ScrollBar.initScroll();
        init_filtro_rapido();
}
    
    function filtrar_mostrar_colums(){
        var colums = '2-';
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
    
    function verItemsParametros(id){
        //$('#myModal-Ventana').modal('show');
        array = new XArray();
        array.setObjeto('ParametrosDet','indexParametrosDet');
        array.addParametro('id',id);
        array.addParametro('import','clases.parametros_det.ParametrosDet');
        xajax_Loading(array.getArray());
    }

    function nuevo_Parametros(){
            array = new XArray();
            array.setObjeto('Parametros','crear');
            array.addParametro('import','clases.parametros.Parametros');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('Parametros','guardar');
            else
                array.setObjeto('Parametros','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.parametros.Parametros');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarParametros(id){
        array = new XArray();
        array.setObjeto('Parametros','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.parametros.Parametros');
        xajax_Loading(array.getArray());
    }


    function eliminarParametros(id){
        if(confirm("¿Desea Eliminar el Parametros Seleccionado?")){
            array = new XArray();
            array.setObjeto('Parametros','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.parametros.Parametros');
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
        array.setObjeto('Parametros','buscar');
        array.addParametro('import','clases.parametros.Parametros');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verParametros(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verParametros.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    