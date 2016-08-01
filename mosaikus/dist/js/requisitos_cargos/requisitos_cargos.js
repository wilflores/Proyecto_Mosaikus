
    
    function init_filtrar(){        
            PanelOperator.initPanels('');
            ScrollBar.initScroll();
            init_filtro_rapido();
    }

    function filtrar_mostrar_colums(){
        //var colums = '0-1-2-';
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

    function nuevo_RequisitosCargos(){
            array = new XArray();
            array.setObjeto('RequisitosCargos','crear');
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
            xajax_Loading(array.getArray());
    }


//asociar requisito al cargo y area
    function relacion_RequisitosCargos(cod_cargo, id_area){//recibe como parametro e cargo y el area escogida
            array = new XArray();
            array.setObjeto('RequisitosCargos','crear');
            array.addParametro('modo',document.getElementById('modo').value);            
            array.addParametro('cod_link',document.getElementById('cod_link').value); 
            array.addParametro('cod_cargo',cod_cargo);//envia dos parametros al crear
            array.addParametro('id_area',id_area);
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
            xajax_Loading(array.getArray());
    }
    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('RequisitosCargos','guardar');
            else
                array.setObjeto('RequisitosCargos','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarRequisitosCargos(id){
        array = new XArray();
        array.setObjeto('RequisitosCargos','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
        xajax_Loading(array.getArray());
    }


    function eliminarRequisitosCargos(id){
        if(confirm("¿Desea Eliminar el RequisitosCargos Seleccionado?")){
            array = new XArray();
            array.setObjeto('RequisitosCargos','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
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
        array.setObjeto('RequisitosCargos','buscar');
        array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verRequisitosCargos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verRequisitosCargos.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    