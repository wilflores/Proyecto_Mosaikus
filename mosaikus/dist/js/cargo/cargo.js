
    
    function filtrar_mostrar_colums(){
        
        var colums = '1-';
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
}

    function nuevo_Cargos(){
            array = new XArray();
            array.setObjeto('Cargos','crear');
            array.addParametro('import','clases.cargo.Cargos');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        
        
        if($('#idFormulario').isValid()) {
            
            //var iframe = document.getElementById("iframearbol");
            //iframe.contentWindow.submitMe();

            var _TxtIdNodos = document.getElementById("nodos").value;
            if (_TxtIdNodos == ''){
                VerMensaje('error','Debe Ingresar el Arbol Organizacional');
                return;
            }
            
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('Cargos','guardar');
            else
                array.setObjeto('Cargos','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.cargo.Cargos');
            //array.addParametro('nodos',_TxtIdNodos);
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarCargos(id){
        array = new XArray();
        array.setObjeto('Cargos','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.cargo.Cargos');
        xajax_Loading(array.getArray());
    }


    function eliminarCargos(id){
        if(confirm("¿Desea Eliminar el Cargos Seleccionado?")){
            array = new XArray();
            array.setObjeto('Cargos','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.cargo.Cargos');
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

        if ( $("#reg_por_pag").length > 0 ) {
            if ((isNaN(document.getElementById("reg_por_pag").value) == true) || (parseInt(document.getElementById("reg_por_pag").value) <= 0)){
                array.addParametro('reg_por_pagina', 10);
                document.getElementById("reg_por_pag").value = 10
            }
            else
            {
                array.addParametro('reg_por_pagina', document.getElementById("reg_por_pag").value);
            }
        }
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('Cargos','buscar');
        array.addParametro('import','clases.cargo.Cargos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verCargos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verCargos.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }

function ao_multiple(){    
    $('#div-ao-form').jstree(
            {
                "checkbox":{
                    three_state : false,
                        cascade : ''
                },
                "plugins": ["search", "types","checkbox"]
            }
        );
    $("#div-ao-form").on("select_node.jstree", function (e, data) {
        if(data.event) { 
            data.instance.select_node(data.node.children_d);
        }
    });
    $("#div-ao-form").on("deselect_node.jstree", function (e, data) {
        if(data.event) { data.instance.deselect_node(data.node.children_d); }
    });
    var to_2 = false;
   $('#div-ao-form').on("changed.jstree", function (e, data) {
       if (data.selected.length > 0){
           var arr;
           var id = '';
           for(i=0;i<data.selected.length;i++){
               arr = data.selected[i].split("_");
               id = id + arr[1] + ',';
           }
           id = id.substr(0,id.length-1);
           $('#nodos').val(id);
           
           
       }
       else
           $('#nodos').val('');        
   });
    var to = false;
    $('#demo_q_ao').keyup(function () {                    
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
                    var v = $('#demo_q_ao').val();
                    $('#div-ao-form').jstree(true).search(v);
            }, 250);
    });  
//    $('#div-ao-form').jstree(true).open_all();               
        
}

    function reporte_ao_pdf(){
        var params =  getForm('busquedaFrm');
        window.open('pages/cargo/reporte_cargos_pdf.php?'+params,'_blank');

    }