
    
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

    function nuevo_mos_usuario(){
            array = new XArray();
            array.setObjeto('mos_usuario','crear');
            array.addParametro('import','clases.mos_usuario.mos_usuario');
            xajax_Loading(array.getArray());
    }
    function validar_perfil_usuario(doc){
        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
                        
            var iframe = document.getElementById("iframearbol");
            iframe.contentWindow.submitMe();
            var _TxtIdNodos = document.getElementById("nodos").value = iframe.contentWindow.document.getElementById('jsfields').value;
            if (_TxtIdNodos == ''){
                VerMensaje('error','Debe Ingresar el Parte del Menu a Permitir');
                return;
            }
            array.addParametro('nodos',_TxtIdNodos);

            
            array.setObjeto('mos_usuario','cargarConfiguracionPerfiles');                
                      
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.mos_usuario.mos_usuario');
            xajax_Loading(array.getArray());
        }else{
        
        }        
    }
    
    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('mos_usuario','guardar');
            else
                array.setObjeto('mos_usuario','actualizar');
            
            if (doc.getElementById("opc").value == "conf"){
                var iframe = document.getElementById("iframearbol");
                iframe.contentWindow.submitMe();
                var _TxtIdNodos = document.getElementById("nodos").value = iframe.contentWindow.document.getElementById('jsfields').value;
//                if (_TxtIdNodos == ''){
//                    VerMensaje('error','Debe Ingresar el Parte del Menu a Permitir');
//                    return;
//                }
                array.addParametro('nodos',_TxtIdNodos);
                
                var iframeportal = document.getElementById("iframearbolportal");
                iframeportal.contentWindow.submitMe();
                var _TxtIdNodosPortal = document.getElementById("nodosportal").value = iframeportal.contentWindow.document.getElementById('jsfields').value;

                if (_TxtIdNodosPortal == '' && _TxtIdNodos == ''){
                    VerMensaje('error','Debe Ingresar al menos un perfil a Permitir');
                    $('#MustraCargando').hide();
                    $( "#btn-guardar" ).html('Guardar');
                    $( "#btn-guardar" ).prop( "disabled", false );
                    return;
                }    
                array.addParametro('nodosportal',_TxtIdNodosPortal);                
                array.setObjeto('mos_usuario','cargarConfiguracion');                

            }            
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.mos_usuario.mos_usuario');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarmos_usuario(id){
        array = new XArray();
        array.setObjeto('mos_usuario','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.mos_usuario.mos_usuario');
        xajax_Loading(array.getArray());
    }


    function eliminarmos_usuario(id){
        if(confirm("¿Desea Eliminar el mos_usuario Seleccionado?")){
            array = new XArray();
            array.setObjeto('mos_usuario','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.mos_usuario.mos_usuario');
            xajax_Loading(array.getArray());
        }
    }

    function vermos_usuario(id){
        
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verUsuario.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    
    function configurarmos_usuario(id){      
        array = new XArray();
        array.setObjeto('mos_usuario','configurar');
        array.addParametro('id_usuario',id);
        array.addParametro('import','clases.mos_usuario.mos_usuario');
        xajax_Loading(array.getArray());        
    }
    
    function configurarPerfiles(id_filial){      
        array = new XArray();
        array.setObjeto('mos_usuario','configurarPerfil');
        //array.addParametro('id_usuario',id_usuario);
        //array.addParametro('cod_perfil',cod_perfil);
        array.addParametro('if_filial',id_filial);
        array.addParametro('import','clases.mos_usuario.mos_usuario');
        xajax_Loading(array.getArray());        
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
        array.setObjeto('mos_usuario','buscar');
        array.addParametro('import','clases.mos_usuario.mos_usuario');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function volverindex(){
        array = new XArray();
        array.setObjeto('mos_usuario','indexmos_usuario');
        array.addParametro('import','clases.mos_usuario.mos_usuario');
        xajax_Loading(array.getArray());        
    }
    
    function perfil_especialista(id){         
        array = new XArray();
        array.setObjeto('mos_usuario','perfil_especialista');
        array.addParametro('id_usuario',id);

        array.addParametro('import','clases.mos_usuario.mos_usuario');
        xajax_Loading(array.getArray());        
    }
    