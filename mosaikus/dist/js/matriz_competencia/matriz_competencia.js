
    
    function init_filtrar(){        
            PanelOperator.initPanels('');
            ScrollBar.initScroll();
            init_filtro_rapido();
    }

    function filtrar_mostrar_colums(){
        var colums = '0-';
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

    function nuevo_MatrizCompetencias(){
            array = new XArray();
            array.setObjeto('MatrizCompetencias','crear');
            array.addParametro('modo',document.getElementById('modo').value);            
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            array.addParametro('import','clases.matriz_competencia.MatrizCompetencias');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            var _TxtIdNodos = document.getElementById("nodos").value;
            if (_TxtIdNodos == ''){
                VerMensaje('error','Debe Ingresar el Arbol Organizacional');
                return;
            }
            var i = $('#num_items_esp').val();
            if(i==0){// para validar que al menos deben agregar una categoria
                VerMensaje('error','Debe Agregar al menos una familia');
                return;
            }
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('MatrizCompetencias','guardar');
            else
                array.setObjeto('MatrizCompetencias','actualizar');

            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value); 
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.matriz_competencia.MatrizCompetencias');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarMatrizCompetencias(id){
        array = new XArray();
        array.setObjeto('MatrizCompetencias','editar');
        array.addParametro('modo',document.getElementById('modo').value);            
        array.addParametro('cod_link',document.getElementById('cod_link').value); 
        array.addParametro('id',id);
        array.addParametro('import','clases.matriz_competencia.MatrizCompetencias');
        xajax_Loading(array.getArray());
    }


    function eliminarMatrizCompetencias(id){
        if(confirm("¿Desea Eliminar la Matriz Competencias Seleccionado?")){
            array = new XArray();
            array.setObjeto('MatrizCompetencias','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.matriz_competencia.MatrizCompetencias');
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
        array.setObjeto('MatrizCompetencias','buscar');
        array.addParametro('import','clases.matriz_competencia.MatrizCompetencias');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verMatrizCompetencias(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verMatrizCompetencias.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');  
            /*    array = new XArray();
        array.setObjeto('MatrizCompetencias','ver');
        array.addParametro('id',id);
        array.addParametro('import','clases.matriz_competencia.MatrizCompetencias');
        xajax_Loading(array.getArray()); */     
    }
    

        /*agregue esta funcion - RAQUEL 30-06-16 */
        function agregar_esp(){
        var i = $('#num_items_esp').val();
        i = parseInt(i) + 1;        
        var html = '<tr id="tr-esp-' + i + '">'; 
        html = html + '<td align="center">'+
                           ' <i class="subir glyphicon glyphicon-arrow-up cursor-pointer"></i><i class="bajar glyphicon glyphicon-arrow-down cursor-pointer"></i>'+
                           '<input id="orden_din_'+ i + '" type="hidden" name="orden_din_'+ i + '" value="'+ i + '">'+
                           '<input id="cmb_din_'+ i + '" type="hidden" name="cmb_din_'+ i + '" tok="' + i + '" value="'+ i + '">'+              
                           ' <a href="' + i + '"  title="Eliminar " id="eliminar_esp_' + i + '"> ' + 
                           //' <imgsrc="diseno/images/ico_eliminar.png" style="cursor:pointer">' + 
                            '<i class="icon icon-remove"></i>' +
                            '</a>' +
                      '  </td>';
        html = html + '<td class="td-table-data">'+
                            '<input id="codigo_din_'+ i + '" class="form-control" type="text" data-validation="required" name="codigo_din_'+ i + '">'+
                       '</td>';
        html = html + '<td class="td-table-data">'+
                            '<input id="nombre_din_'+ i + '" class="form-control" type="text" data-validation="required" name="nombre_din_'+ i + '">'+
                       '</td>';      
        html = html + '<td>' +
                            //' <input type="text" id="valores_din_'+ i + '" name="valores_din_'+ i + '" size="15"  readonly="readonly"/>'+
                           
                           ' <textarea id="valores_din_'+ i + '" rows="5" name="valores_din_'+ i + '"class="form-control" readonly="" data-validation="required"></textarea>'+
                           //'<i class="icon icon-more cursor-pointer" id="ico_cmb_din_'+ i + '" tok="'+i+'"></i>'+
//                            '<div class="input-group">'+
//                                  ' <input type="text" id="add_item_din_'+ i + '" size="15" class="form-control"/>'+
//                                  '<span class="input-group-addon cursor-pointer" id=""><span class="glyphicon glyphicon glyphicon-plus"></span></span>'
//                           + '</div>'+
                        '</td>';
        html = html + '<td>' +
                           '<i class="icon icon-more cursor-pointer" title="Administrar Items" id="ico_cmb_din_'+ i + '" tok="'+i+'"></i>'+
                        '</td>';
        html = html + '</tr>' ;       
        $("#table-items-esp tbody").append(html);          
  
        $("#eliminar_esp_" + i).click(function(e){ 
            e.preventDefault();
            var id = $(this).attr('href');
            $('tr-esp-' + id).remove();
            var parent = $(this).parents().parents().get(0);
        $(parent).remove();
        });     
        $("#ico_cmb_din_" + i).click(function(e){ 
            e.preventDefault();
            var id = $(this).attr('tok');            
            array = new XArray();
            array.setObjeto('ItemsFormulario','indexItemsFormulario');
            array.addParametro('tok',id);
            array.addParametro('id',$('#cmb_din_'+id).val());
            array.addParametro('titulo',$('#nombre_din_'+id).val());
            array.addParametro('titulo',$('#codigo_din_'+id).val());/********** codigo de la categoria********/
            array.addParametro('token', $('#tok_new_edit').val());
            array.addParametro('desc_larga', 1);
            array.addParametro('import','clases.items_formulario.ItemsFormulario');
            xajax_Loading(array.getArray());
        }); 
        $(".subir").click(function(){
            var row = $(this).parents("tr:first");               
            row.insertBefore(row.prev());
            ordenar_tabla();
        });
        $(".bajar").click(function(){
            var row = $(this).parents("tr:first");        
            row.insertAfter(row.next());  
            ordenar_tabla();
        });
        $('#num_items_esp').val(i);
        
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
/* hasta aqui agregue -Raquel +++*/