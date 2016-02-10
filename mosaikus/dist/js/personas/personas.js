    function filtrar_mostrar_colums(){
        var colums = '1-2-3-4-';
         $('.checkbox-mos-col').each(function() {
                if (this.checked){
                    colums = colums + this.value + '-';
                }
         });
         colums = colums.substring(0, colums.length - 1);
         $('#mostrar-col').val(colums);
         verPagina(1,1);
         $('#myModal-Mostrar-Colums').modal('hide');
         
    }
    
    function init_filtrar(){        
        PanelOperator.initPanels("");
        ScrollBar.initScroll();
        init_filtro_rapido();
        init_filtro_ao_simple();
}
    
    function HojadeVida(id){
        //$('#myModal-Ventana').modal('show');
        array = new XArray();
        array.setObjeto('HojadeVida','indexHojadeVida');
        array.addParametro('id',id);
        array.addParametro('import','clases.Hoja_de_Vida.HojadeVida');
        xajax_Loading(array.getArray());
    }        
    
    function cargar_autocompletado(){
        //$.validator.addMethod('rut', function(value, element) {
        //    return this.optional(element) || $.Rut.validar(value);
        //  }, 'Este campo debe ser un rut valido.');
          
          $.formUtils.addValidator({
            name : 'rut',
            validatorFunction : function(value, $el, config, language, $form) {                
              //if ($('input:radio[name=extranjero]:checked').val() == 'NO')  
              if(!($("#extranjero").is(':checked'))) 
                    return $.Rut.validar(value);
              return true;
            },
            errorMessage : 'Este campo debe ser un rut valido.',
            errorMessageKey: 'badEvenNumber'
          });
          $.formUtils.addValidator({
            name : 'sololetras',
            validatorFunction : function(value, $el, config, language, $form) {                 
              if (value.match(/^[a-z,A-Z,ñÑáéíóúÁÉÍÓÚ," "]+$/))  
                    return true;
              return false;
            },
            errorMessage : 'Debe ingresar solo caracteres validos.',
            errorMessageKey: 'badEvenNumber'
          });
    }
    
    function cargar_cargos(id_arbol,cod_cargo) {            
        {

          array = new XArray();
          array.setObjeto('Personas','cargos');
            //array.addParametro('id',id);
          array.addParametro('import','clases.personas.Personas');                           
          array.addParametro('id_arbol',id_arbol);
          array.addParametro('cod_cargo',cod_cargo);
          $('#id_organizacion').val(id_arbol);

          xajax_Loading(array.getArray());
        }
            
    }
    
    function fomatear_rut(value){
        $('#id_personal').val($.Rut.formatear(value));
    }
    
    function nuevo_Personas(){
            array = new XArray();
            array.setObjeto('Personas','crear');
            array.addParametro('import','clases.personas.Personas');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        if ($('#apellido_materno').val().length == 0) $('#apellido_materno').val(' ');
        if ($('#workflow').is(':checked')){ 
            $('#email').attr('data-validation',"email");
        }
        else {
            $('#email').removeAttr('data-validation');
        }      
        if (!$('#vigencia').is(':checked')){ 
            if ( $('#fecha_egreso').is(":visible") ){
                $('#fecha_egreso').attr('data-validation',"required");
            }else {
                $('#fecha_egreso').removeAttr('data-validation');
            }
        }
        else {
            $('#fecha_egreso').removeAttr('data-validation');
        }  
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('Personas','guardar');
            else
                array.setObjeto('Personas','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.personas.Personas');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarPersonas(id){
        array = new XArray();
        array.setObjeto('Personas','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.personas.Personas');
        xajax_Loading(array.getArray());
    }


    function eliminarPersonas(id){
        if(confirm("¿Desea Eliminar el Personas Seleccionado?")){
            array = new XArray();
            array.setObjeto('Personas','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.personas.Personas');
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
        array.setObjeto('Personas','buscar');
        array.addParametro('import','clases.personas.Personas');
         $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verPersonas(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verPersonas.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    