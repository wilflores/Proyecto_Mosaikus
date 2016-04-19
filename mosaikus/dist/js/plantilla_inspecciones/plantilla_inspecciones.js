
    
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

    function nuevo_PlantilaInspecciones(){
            array = new XArray();
            array.setObjeto('PlantilaInspecciones','crear');
            array.addParametro('import','clases.plantilla_inspecciones.PlantilaInspecciones');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('PlantilaInspecciones','guardar');
            else
                array.setObjeto('PlantilaInspecciones','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.plantilla_inspecciones.PlantilaInspecciones');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarPlantilaInspecciones(id){
        array = new XArray();
        array.setObjeto('PlantilaInspecciones','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.plantilla_inspecciones.PlantilaInspecciones');
        xajax_Loading(array.getArray());
    }


    function eliminarPlantilaInspecciones(id){
        if(confirm("¿Desea Eliminar el PlantilaInspecciones Seleccionado?")){
            array = new XArray();
            array.setObjeto('PlantilaInspecciones','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.plantilla_inspecciones.PlantilaInspecciones');
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
        array.setObjeto('PlantilaInspecciones','buscar');
        array.addParametro('import','clases.plantilla_inspecciones.PlantilaInspecciones');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verPlantilaInspecciones(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verPlantilaInspecciones.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
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
                           '<i class="icon icon-more cursor-pointer" title="Administrar Verificadores" id="ico_cmb_din_'+ i + '" tok="'+i+'"></i>'+
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
    