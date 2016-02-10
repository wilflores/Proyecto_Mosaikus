
function init_tabla(){
    $('.ver-mas').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
            $('#myModal-Ventana-Titulo').html('');
            $('#myModal-Ventana').modal('show');
        });                
}
    
    function filtrar_mostrar_colums(){
        var colums = '1-2-';
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

    function nuevo_Correcciones(){
            array = new XArray();
            array.setObjeto('Correcciones','crear');
            array.addParametro('import','clases.correcciones.Correcciones');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('Correcciones','guardar');
            else
                array.setObjeto('Correcciones','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.correcciones.Correcciones');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarCorrecciones(id){
        array = new XArray();
        array.setObjeto('Correcciones','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.correcciones.Correcciones');
        xajax_Loading(array.getArray());
    }


    function eliminarCorrecciones(id){
        if(confirm("¿Desea Eliminar el Correcciones Seleccionado?")){
            array = new XArray();
            array.setObjeto('Correcciones','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.correcciones.Correcciones');
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
        array.setObjeto('Correcciones','buscar');
        array.addParametro('import','clases.correcciones.Correcciones');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verCorrecciones(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verCorrecciones.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
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
    
    function verAcciones(id){
        array = new XArray();
        array.setObjeto('AccionesAC','indexAccionesAC');
        array.addParametro('id_correccion',id);
        array.addParametro('id_accion',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }