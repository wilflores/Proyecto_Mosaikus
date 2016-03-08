
    

    function nuevo_AccionesAC(){
            array = new XArray();
            array.setObjeto('AccionesAC','crear');
            array.addParametro('import','clases.acciones_ac.AccionesAC');
            xajax_Loading(array.getArray());
    }

    function validar_hv(doc){        
        if($('#idFormulario-hv').isValid()) {
            $( "#btn-guardar-hv" ).html('Procesando..');
            $( "#btn-guardar-hv" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc-hv").value == "new")
                array.setObjeto('AccionesAC','guardar');
            else
                array.setObjeto('AccionesAC','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario-hv');
            array.addParametro('import','clases.acciones_ac.AccionesAC');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarAccionesAC(id){
        array = new XArray();
        array.setObjeto('AccionesAC','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }


    function eliminarAccionesAC(id){
        if(confirm("¿Desea Eliminar la Acción Seleccionada?")){
            array = new XArray();
            array.setObjeto('AccionesAC','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.acciones_ac.AccionesAC');
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
        array.setObjeto('AccionesAC','buscar');
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verAccionesAC(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verAccionesAC.php?id='+id;
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
        $("#hv-id_responsable").select2("val", "");
        $('#opc-hv').val('new');
        $('#id-hv').val('-1');        
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
        
    }
    
    function adminEvidencias(id){
        $('#myModal-Ventana').modal('hide');
        //$('#myModal-Evidencias').modal('show');
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidencia');
        array.addParametro('id_accion',id);
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }
    
    function EvidenciasVerReporte(id){
        $('#myModal-Ventana').modal('hide');
        //$('#myModal-Evidencias').modal('show');
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidencia');
        array.addParametro('id_accion',id);
        array.addParametro('reporte_ac','S');
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
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
    