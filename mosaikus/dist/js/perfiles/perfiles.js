
    
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

    function nuevo_Perfiles(){
            array = new XArray();
            array.setObjeto('Perfiles','crear');
            array.addParametro('import','clases.perfiles.Perfiles');
            xajax_Loading(array.getArray());
    }

    function configurarPerfiles(id){      
        array = new XArray();
        array.setObjeto('Perfiles','configurar');
        array.addParametro('cod_perfil',id);
        array.addParametro('import','clases.perfiles.Perfiles');
        xajax_Loading(array.getArray());        
    }
    
    function validar(doc){    
        //alert(doc.getElementById("opc").value);
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('Perfiles','guardar');
            else
                array.setObjeto('Perfiles','actualizar');
            
            if (doc.getElementById("opc").value == "conf"){
                var iframe = document.getElementById("iframearbol");
                iframe.contentWindow.submitMe();
                var _TxtIdNodos = document.getElementById("nodos").value = iframe.contentWindow.document.getElementById('jsfields').value;
                if (_TxtIdNodos == ''){
                    VerMensaje('error','Debe Ingresar el Parte del Menu a Permitir');
                    $('#MustraCargando').hide();
                    $( "#btn-guardar" ).html('Guardar');
                    $( "#btn-guardar" ).prop( "disabled", false );                    
                    return;
                }                
                array.setObjeto('Perfiles','cargarConfiguracion');
                array.addParametro('nodos',_TxtIdNodos);
            }
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);            
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.perfiles.Perfiles');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }
  function validarSiNumero(numero){
    if (!/^([0-9])*$/.test(numero))
      alert("El valor " + numero + " no es un número");
  }
    function editarPerfiles(id){
        array = new XArray();
        array.setObjeto('Perfiles','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.perfiles.Perfiles');
        xajax_Loading(array.getArray());
    }


    function eliminarPerfiles(id){
        if(confirm("¿Desea Eliminar el Perfiles Seleccionado?")){
            array = new XArray();
            array.setObjeto('Perfiles','eliminar');
            array.addParametro('id',id);
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);            
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.perfiles.Perfiles');
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
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);        
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('Perfiles','buscar');
        array.addParametro('import','clases.perfiles.Perfiles');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verPerfiles(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verPerfiles.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    