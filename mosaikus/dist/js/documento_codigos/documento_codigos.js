
    
    function init_filtrar(){        
            PanelOperator.initPanels('');
            ScrollBar.initScroll();
            init_filtro_rapido();
    }

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

    function nuevo_DocumentoCodigos(){
            array = new XArray();
            array.setObjeto('DocumentoCodigos','crear');
            array.addParametro('import','clases.documento_codigos.DocumentoCodigos');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('DocumentoCodigos','guardar');
            else
                array.setObjeto('DocumentoCodigos','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.documento_codigos.DocumentoCodigos');
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarDocumentoCodigos(id){
        array = new XArray();
        array.setObjeto('DocumentoCodigos','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.documento_codigos.DocumentoCodigos');
        xajax_Loading(array.getArray());
    }


    function eliminarDocumentoCodigos(id){
        if(confirm("¿Desea Eliminar el DocumentoCodigos Seleccionado?")){
            array = new XArray();
            array.setObjeto('DocumentoCodigos','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.documento_codigos.DocumentoCodigos');
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
        array.setObjeto('DocumentoCodigos','buscar');
        array.addParametro('import','clases.documento_codigos.DocumentoCodigos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verDocumentoCodigos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verDocumentoCodigos.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    