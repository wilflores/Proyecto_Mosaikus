
    
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

    function nuevo_DocumentosMigracion(){
            array = new XArray();
            array.setObjeto('DocumentosMigracion','crear');
            array.addParametro('modo',document.getElementById('modo').value);            
            array.addParametro('cod_link',document.getElementById('cod_link').value);            
            array.addParametro('import','clases.documentos_migracion.DocumentosMigracion');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        
        
        if($('#idFormulario').isValid()) {
            var ejecutar = function (){
            
                $( "#btn-guardar" ).html('Procesando..');
                $( "#btn-guardar" ).prop( "disabled", true );
                array = new XArray();
                if (doc.getElementById("opc").value == "new")
                    array.setObjeto('DocumentosMigracion','guardar');
                else
                    array.setObjeto('DocumentosMigracion','actualizar');
                array.addParametro('permiso',document.getElementById('permiso_modulo').value);
                array.getForm('idFormulario');
                array.addParametro('modo',document.getElementById('modo').value);            
                array.addParametro('cod_link',document.getElementById('cod_link').value);            
                array.addParametro('import','clases.documentos_migracion.DocumentosMigracion');
                xajax_Loading(array.getArray());
            }        
            bootbox.confirm("Al realizar esta operación, se va a migrar las responsabilidades del usuario <b>"
                       + $("#id_responsable_actual option:selected").text() + "</b>, "
                       + "esto no se podrá deshacer. ¿Desea continuar?", function(result) {
                    if (result == true){
                        ejecutar();
                    }

                }); 
        }
        

    }

    function editarDocumentosMigracion(id){
        array = new XArray();
        array.setObjeto('DocumentosMigracion','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.documentos_migracion.DocumentosMigracion');
        xajax_Loading(array.getArray());
    }


    function eliminarDocumentosMigracion(id){
        if(confirm("¿Desea Eliminar el DocumentosMigracion Seleccionado?")){
            array = new XArray();
            array.setObjeto('DocumentosMigracion','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.documentos_migracion.DocumentosMigracion');
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
        array.setObjeto('DocumentosMigracion','buscar');
        array.addParametro('import','clases.documentos_migracion.DocumentosMigracion');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verDocumentosMigracion(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verDocumentosMigracion.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    function ActivaPersonal(valor, obj){
       if (valor == 'N'){
           $('#'+obj).val('').change();
           $('#'+obj).prop('disabled', true);
           $('#'+obj).removeAttr('data-validation');
       }
       else{
           $('#'+obj).prop('disabled', false);
           $('#' +obj).attr('data-validation','required');  
       }
    }    