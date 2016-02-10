
    function mostra_ocultar_indicador(){
        
        if (($('#tipo').val() == '3') && ($('#dependencia').val() == '2') && ($('input:radio[name=indicador]:checked').val() == 'S')){
            $('#fecha_nom1').attr('data-validation',"required");
            $('#fecha_nom2').attr('data-validation',"required");
            $('#fecha_sem').attr('data-validation',"required");
            $('#datos').attr('data-validation',"required");
            $('#div-fecha_nom1').show();
            $('#div-fecha_nom2').show();
            $('#div-fecha_sem').show();
            $('#div-datos').show();
            $('#div-indicador').show();
        }
        else
        {
            $('#fecha_nom1').removeAttr('data-validation');
            $('#fecha_nom2').removeAttr('data-validation');
            $('#fecha_sem').removeAttr('data-validation');
            $('#datos').removeAttr('data-validation');
            $('#div-fecha_nom1').hide();
            $('#div-fecha_nom2').hide();
            $('#div-fecha_sem').hide();
            $('#div-datos').hide();
            $('#div-indicador').hide();
        }
        if (($('#dependencia').val() == '2')&&($('#tipo').val() == '3')){
            $('#div-indicador').show();
        }
    }
    
    function cargar_autocompletado(){
        $('#tipo').on('change', function() {
            mostra_ocultar_indicador();
        });
        $('#dependencia').on('change', function() {
            mostra_ocultar_indicador();
        });
        $('input:radio[name=indicador]').on('change', function() {
            mostra_ocultar_indicador();
        });
        
        if (($('#id_cmb_acap').val() != '')&&(parseInt($('#id_cmb_acap').val())<=3)){
            $('#div-dependencia').hide();
            $('#div-tipo').hide();
        }
    }
    
    function cargar_autocompletado_config(){        
        $('input:radio[name=indicador_34]').on('change', function() {
            mostra_ocultar_indicador_config();
        });
        $('#sem_1').on('change', function() {
            actualizar_semaforo_3();
        });
        $('#sem_3').on('change', function() {
            actualizar_semaforo_3();
        });
    }
    
    function actualizar_semaforo_3(){
        if ($('#sem_1').val() != ''){
            $('#sem_21').val(parseInt($('#sem_1').val()) + 1);
        }
        if ($('#sem_3').val() != ''){
            $('#sem_22').val(parseInt($('#sem_3').val()) - 1);
        }
    }
    
    function mostra_ocultar_indicador_config(){        
        if (($('input:radio[name=indicador_34]:checked').val() == 'S')){
            $('#sem_1').attr('data-validation',"number");
            $('#sem_3').attr('data-validation',"number");
            //$('#fecha_sem').attr('data-validation',"required");            
            $('#div-verde').show();
            $('#div-rojo').show();
            $('#div-amarillo').show();
            
        }
        else
        {
            $('#sem_1').removeAttr('data-validation');
            $('#sem_3').removeAttr('data-validation');
            //$('#fecha_sem').removeAttr('data-validation');
            
            $('#div-verde').hide();
            $('#div-rojo').hide();
            $('#div-amarillo').hide();
           
        }
        
    }
    
    function filtrar_mostrar_colums(){
        var colums = '1-';
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

    function nuevo_MatricesParametros(){
            array = new XArray();
            array.setObjeto('MatricesParametros','crear');
            array.addParametro('import','clases.matrices_parametros.MatricesParametros');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).val('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('MatricesParametros','guardar');
            else
                array.setObjeto('MatricesParametros','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.matrices_parametros.MatricesParametros');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }
    
    function validar_config(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();           
            array.setObjeto('MatricesParametros','actualizar_config');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.matrices_parametros.MatricesParametros');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarMatricesParametros(id){
        array = new XArray();
        array.setObjeto('MatricesParametros','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.matrices_parametros.MatricesParametros');
        xajax_Loading(array.getArray());
    }
    
    function configuracionModulo(){
        array = new XArray();
        array.setObjeto('MatricesParametros','configuracion');
        //array.addParametro('id',id);
        array.addParametro('import','clases.matrices_parametros.MatricesParametros');
        xajax_Loading(array.getArray());
    }


    function eliminarMatricesParametros(id){
        if(confirm("¿Desea Eliminar el Parametro Seleccionado?")){
            array = new XArray();
            array.setObjeto('MatricesParametros','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.matrices_parametros.MatricesParametros');
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
        array.setObjeto('MatricesParametros','buscar');
        array.addParametro('import','clases.matrices_parametros.MatricesParametros');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verMatricesParametros(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verMatricesParametros.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    function administrarItem(id,nombre){
        //$('#myModal-Ventana').modal('show');
        array = new XArray();
        array.setObjeto('MatricesParametrosDetalle','indexMatricesParametrosDetalle');
        array.addParametro('id',id);
        array.addParametro('nombre',nombre);
        array.addParametro('import','clases.matrices_parametros_detalle.MatricesParametrosDetalle');
        xajax_Loading(array.getArray());
    }
    