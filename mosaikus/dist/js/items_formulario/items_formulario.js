function reset_formulario(){
        
        $('#idFormulario-hv').each (function(){
            this.reset();
        });       
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');        
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
    }
    
    function r_init_filtrar(){        
            PanelOperator.initPanels('');
            ScrollBar.initScroll();
            init_filtro_rapido();
    }

    function r_filtrar_mostrar_colums(){
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

    function nuevo_ItemsFormulario(){
            array = new XArray();
            array.setObjeto('ItemsFormulario','crear');
            array.addParametro('import','clases.items_formulario.ItemsFormulario');
            xajax_Loading(array.getArray());
    }

    function validar_hv(doc){        
        if($('#idFormulario-hv').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc-hv").value == "new")
                array.setObjeto('ItemsFormulario','guardar');
            else
                array.setObjeto('ItemsFormulario','actualizar');
            array.addParametro('token', $('#tok_new_edit').val());
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario-hv');
            array.addParametro('import','clases.items_formulario.ItemsFormulario');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarItemsFormulario(id){
        array = new XArray();
        array.setObjeto('ItemsFormulario','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.items_formulario.ItemsFormulario');
        xajax_Loading(array.getArray());
    }


    function eliminarItemsFormulario(id){
        if(confirm("¿Desea Eliminar el ItemsFormulario Seleccionado?")){
            array = new XArray();
            array.setObjeto('ItemsFormulario','eliminar');
            array.addParametro('id',id);
            array.addParametro('token', $('#tok_new_edit').val());
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.items_formulario.ItemsFormulario');
            xajax_Loading(array.getArray());
        }
    }
    function r_verPagina(pag,doc){
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
        array.addParametro('token', $('#tok_new_edit').val());
        array.setObjeto('ItemsFormulario','buscar');
        array.addParametro('import','clases.items_formulario.ItemsFormulario');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verItemsFormulario(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verItemsFormulario.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    