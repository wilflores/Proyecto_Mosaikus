
    
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

    function nuevo_ArbolOrganizacional(){
            array = new XArray();
            array.setObjeto('ArbolOrganizacional','crear');
            array.addParametro('import','clases.organizacion.ArbolOrganizacional');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        if($('#idFormulario').isValid()) {
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('ArbolOrganizacional','guardar');
            else
                array.setObjeto('ArbolOrganizacional','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.organizacion.ArbolOrganizacional');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarArbolOrganizacional(id){
        array = new XArray();
        array.setObjeto('ArbolOrganizacional','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.organizacion.ArbolOrganizacional');
        xajax_Loading(array.getArray());
    }


    function eliminarArbolOrganizacional(id){
        if(confirm("¿Desea Eliminar el ArbolOrganizacional Seleccionado?")){
            array = new XArray();
            array.setObjeto('ArbolOrganizacional','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.organizacion.ArbolOrganizacional');
            xajax_Loading(array.getArray());
        }
    }
    function verPagina(pag,doc){
        array = new XArray();
//        if (doc== null)
//        {
//             $('form')[0].reset();             
//        }
        array.getForm('busquedaFrm'); 
//        if ((isNaN(document.getElementById("reg_por_pag").value) == true) || (parseInt(document.getElementById("reg_por_pag").value) <= 0)){
//            array.addParametro('reg_por_pagina', 10);
//            document.getElementById("reg_por_pag").value = 10
//        }
//        else
//        {
//            array.addParametro('reg_por_pagina', document.getElementById("reg_por_pag").value);
//        }
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('ArbolOrganizacional','buscar');
        array.addParametro('import','clases.organizacion.ArbolOrganizacional');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verArbolOrganizacional(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verArbolOrganizacional.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function reporte_ao_pdf(){
        var params = getForm('busquedaFrm'); ;
         window.open('pages/organizacion/reporte_ao_pdf.php?'+params,'_blank');
    }
    