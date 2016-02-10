
    
    function filtrar_mostrar_colums(){
        var colums = '8-';
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
    
    function filtrar_arbol(){
          array = new XArray();
          array.setObjeto('ArbolOrganizacional','buscar_hijos');
          array.addParametro('import','clases.organizacion.ArbolOrganizacional');                      
          array.addParametro('b-id_organizacion',$('#b-id_organizacion_aux').val());
          xajax_Loading(array.getArray());
    }
    
    function filtrar_proceso(){
          array = new XArray();
          array.setObjeto('ArbolProcesos','buscar_hijos');
          array.addParametro('import','clases.arbol_procesos.ArbolProcesos');                      
          array.addParametro('b-id_proceso',$('#b-id_proceso_aux').val());
          xajax_Loading(array.getArray());
    }

    function nuevo_AccionesCorrectivas(){
            array = new XArray();
            array.setObjeto('AccionesCorrectivas','crear');
            array.addParametro('import','clases.acciones_correc.AccionesCorrectivas');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        
        if($('#idFormulario').isValid()) {
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('AccionesCorrectivas','guardar');
            else
                array.setObjeto('AccionesCorrectivas','actualizar');
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.acciones_correc.AccionesCorrectivas');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarAccionesCorrectivas(id){
        array = new XArray();
        array.setObjeto('AccionesCorrectivas','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_correc.AccionesCorrectivas');
        xajax_Loading(array.getArray());
    }


    function eliminarAccionesCorrectivas(id){
        if(confirm("¿Desea Eliminar la Acción Correctiva Seleccionada?")){
            array = new XArray();
            array.setObjeto('AccionesCorrectivas','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.acciones_correc.AccionesCorrectivas');
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
        array.setObjeto('AccionesCorrectivas','buscar');
        array.addParametro('import','clases.acciones_correc.AccionesCorrectivas');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verAccionesCorrectivas(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verAccionesCorrectivas.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function verAcciones(id, id_accion){
        array = new XArray();
        array.setObjeto('AccionesCorectivasDetalle','indexAccionesCorectivasDetalle');
        array.addParametro('id',id);
        array.addParametro('id_accion',id_accion);
        array.addParametro('import','clases.accion_detalle.AccionesCorectivasDetalle');
        xajax_Loading(array.getArray());
    }
    
    
    