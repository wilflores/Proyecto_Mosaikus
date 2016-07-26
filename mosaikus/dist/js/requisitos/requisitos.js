    /* FUNCION DEL ARBOL 30-06-16 RAQUEL ++++*/

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
           
           if(to_2) { clearTimeout(to_2); }
           to_2 = setTimeout(function () {
                  /* validar_codigo_version();*/
                   /*CargarCombowf($('#nodos').val(),$('#id').val());    
                   CargaComboCargo(document.getElementById('requiere_lista_distribucion').value);*/
           }, 250);
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
/*** funciones para la tabla de categoria- copiada de plantilla inspecciones*/
        function ajustar_valor_atributo_dinamico(id){          
         $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, '\n'));
         $('#valores_din_' + id).val($('#valores_din_' + id).val().replace(/<br>/gi, ''));
    }
        function ordenar_tabla(){
    $("#table-items-esp tbody tr").each(function (i, row) {         
                var row_2 = $(row);
                family = row_2.find('input[name*="orden_din"]');
                family.val(i + 1);                                                               
            });
}

/* hasta aqui agregue -Raquel +++*/
    
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

    function nuevo_Requisitos(){
            array = new XArray();
            array.setObjeto('Requisitos','crear');
            array.addParametro('modo',document.getElementById('modo').value);            
            array.addParametro('cod_link',document.getElementById('cod_link').value); 
            array.addParametro('import','clases.requisitos.Requisitos');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            var _TxtIdNodos = document.getElementById("nodos").value;
            if (_TxtIdNodos == ''){
                VerMensaje('error','Debe Ingresar el Arbol Organizacional');
                return;
            }
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('Requisitos','guardar');
            else
                array.setObjeto('Requisitos','actualizar');
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value); 
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.requisitos.Requisitos');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarRequisitos(id){
        array = new XArray();
        array.setObjeto('Requisitos','editar');
        array.addParametro('modo',document.getElementById('modo').value);            
        array.addParametro('cod_link',document.getElementById('cod_link').value); 
        array.addParametro('id',id);
        array.addParametro('import','clases.requisitos.Requisitos');
        xajax_Loading(array.getArray());
    }


    function eliminarRequisitos(id){
        if(confirm("¿Desea Eliminar el Requisitos Seleccionado?")){
            array = new XArray();
            array.setObjeto('Requisitos','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.requisitos.Requisitos');
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
        array.setObjeto('Requisitos','buscar');
        array.addParametro('import','clases.requisitos.Requisitos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verRequisitos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verRequisitos.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    