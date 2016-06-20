
function cargar_autocompletado(){       
        
          $('.pasar').click(function() { !$('#origen option:selected').remove().appendTo('#destino'); total_per_sel();});  
            $('.quitar').click(function() { !$('#destino option:selected').remove().appendTo('#origen'); total_per_sel();});
            $('.pasartodos').click(function() { $('#origen option').each(function() { $(this).remove().appendTo('#destino'); }); total_per_sel();});
            $('.quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); total_per_sel();});
            $('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });
            
            $('#origen').on('dblclick', 'option', function() {
                !$('#origen option:selected').remove().appendTo('#destino');
                total_per_sel();
            });            
            
            String.prototype.capitalize = function() {
                return this.charAt(0).toUpperCase() + this.slice(1);
            }
            
            $('#b-id_personal').keyup(function() {
                procesar_filtrar_arbol();
            });
            
            $('#b-nombres').keyup(function() {
                procesar_filtrar_arbol();
            });
            $('#b-apellido_paterno').keyup(function() {
                procesar_filtrar_arbol();
            });
            $('#b-apellido_materno').keyup(function() {
                procesar_filtrar_arbol();
            });
    }
    
    function total_per_sel(){
        $("#total-pers-sel").html($('#destino option').length + ' Personas seleccionadas.');
    }
    
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

    function nuevo_ListaDistribucionDoc(){
            array = new XArray();
            array.setObjeto('ListaDistribucionDoc','crear');
            array.addParametro('import','clases.lista_distribucion_doc.ListaDistribucionDoc');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('ListaDistribucionDoc','guardar');
            else
                array.setObjeto('ListaDistribucionDoc','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.lista_distribucion_doc.ListaDistribucionDoc');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarListaDistribucionDoc(id){
        array = new XArray();
        array.setObjeto('ListaDistribucionDoc','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.lista_distribucion_doc.ListaDistribucionDoc');
        xajax_Loading(array.getArray());
    }


    function eliminarListaDistribucionDoc(id){
        if(confirm("¿Desea Eliminar el ListaDistribucionDoc Seleccionado?")){
            array = new XArray();
            array.setObjeto('ListaDistribucionDoc','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.lista_distribucion_doc.ListaDistribucionDoc');
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
        array.setObjeto('ListaDistribucionDoc','buscar');
        array.addParametro('import','clases.lista_distribucion_doc.ListaDistribucionDoc');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verListaDistribucionDoc(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verListaDistribucionDoc.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    