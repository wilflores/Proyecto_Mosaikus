function init_archivos_adjuntos(){
    $("#fileUpload_adjuntos").change(function (){
                var names = [];
                var i = $('#num_items_adj').val();
                i = parseInt(i) + 1;    
                //var formData = new FormData(document.getElementById("formuploadajax"));
                var formData = new FormData();
                for (var k = 0; k < this.files.length; ++k) {
                    //alert(this.files[k].type);
                    if(this.files[k].size>1024*1024*3){
                        VerMensaje('error','El archivo ' + this.files[k].name + ' excede el tama&ntilde;o permitido en este sitio, Tama&ntilde;o m&aacute;ximo del archivo a subir: 3MB');
                    }
                    else
                        names.push(k);
                }
                var html;
                
                for (var k = 0; k < names.length;++k){
                    i = $('#num_items_adj').val();
                    i = parseInt(i) + 1;   
                    html = '<tr id="tr-adj-' + i + '">'; 
                    html = html + '<td align="center">'+
                                       ' ' +
                                  '  </td>';
                    html = html + '<td >'+
                                        '<a id="a-img-adj-'+ i +'" href="#" title="'+ this.files[names[k]].name + '" >'+
                                            this.files[names[k]].name + 
                                        '</a>'+
                                        '<div id="a-img-adj-msj-'+ i +'" style="color:red"></div>'
                                   '</td>';
                    html = html + '<td>' +
                                       + Math.round(this.files[names[k]].size / 1024) + ' KB <br>' +
                                        '<div class="progress" style="width: 200px;">'+
                                            '<div class="progress-bar" id="estado-progress-bar-'+i+'" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">'+
                                              '0%'+
                                            '</div>'+
                                        '</div>'+
                                    '</td>';

                    html = html + '<td>' +
                                       '<i class="glyphicon glyphicon-trash cursor-pointer" href="'+ i + '" id="ico_trash_img_'+ i + '" tok=""></i>'+
                                    '</td>';
                    html = html + '</tr>' ;       
                    $("#table-items-adj tbody").append(html);  
                    
                    formData.append('fileUpload',this.files[names[k]]); 
                    formData.append('tok', $('#tok_new_edit').val());
                    formData.append('extensiones', $('#extensiones').val());
                    var ajaxloopreq = function (formData,i) {
                        $.ajax({
                            url: "pages/acciones_evidencia/uploadFileOtro2_vis.php",
                            type: "post",
                            dataType: "html",
                            data: formData,
                            cache: false,
                            contentType: false,
                         processData: false,
                         xhr: function() {
                                myXhr = $.ajaxSettings.xhr();
                                if (myXhr.upload) {
                                    myXhr.upload.addEventListener('progress', function(prog) {
                                        var value = ~~((prog.loaded / prog.total) * 100);
                                        var $bar = $('#estado-progress-bar-'+i);
                                        $bar.width(value*200/100);
                                        $bar.text(value+ "%");
                                    }, false);
                                }
                                return myXhr;
                            }
                        })
                        .done(function(res){
                           try{
                                respuesta = $.parseJSON(res);
                                if (respuesta[0].exito == 1) {
                                    if (respuesta[0].gallery == 1)
                                        $('#a-img-adj-'+i).attr('data-gallery','');
                                    if (respuesta[0].target == 1)
                                        $('#a-img-adj-'+i).attr('target','_blank');
                                    $('#a-img-adj-'+i).attr('href',respuesta[0].url);
                                    $('#ico_trash_img_'+i).attr('tok',respuesta[0].id);
                                    $("#ico_trash_img_" + i).click(function(e){ 
                                        e.preventDefault();
                                        var id = $(this).attr('href');
                                        $('tr-adj-' + id).remove();
                                        var parent = $(this).parents().parents().get(0);
                                            $(parent).remove();
                                        var id = $(this).attr('tok');            
                                        array = new XArray();
                                        array.setObjeto('ArchivosAdjuntos','actualizar');
                                        array.addParametro('tok',id);   
                                        array.addParametro('extensiones', $('#extensiones').val());
                                        array.addParametro('token', $('#tok_new_edit').val());
                                        array.addParametro('import','clases.utilidades.ArchivosAdjuntos');
                                        xajax_Loading(array.getArray());
                                    }); 
                                }
                                else{
                                    $('#a-img-adj-msj-'+i).html(respuesta[0].msj);
                                    $("#ico_trash_img_" + i).click(function(e){ 
                                        e.preventDefault();
                                        var id = $(this).attr('href');
                                        $('tr-adj-' + id).remove();
                                        var parent = $(this).parents().parents().get(0);
                                            $(parent).remove();                                        
                                    });
                                }
                            }catch (e) {
                                $('#a-img-adj-msj-'+i).html(respuesta[0].msj);
                                $("#ico_trash_img_" + i).click(function(e){ 
                                        e.preventDefault();
                                        var id = $(this).attr('href');
                                        $('tr-adj-' + id).remove();
                                        var parent = $(this).parents().parents().get(0);
                                            $(parent).remove();                                        
                                    });
                            }

                           $('#estado-progress-bar-'+i).parent().hide();
                        });
                    };
                    ajaxloopreq(formData,i);
                    
                    $('#num_items_adj').val(i);
                }
                $('#fileUpload_adjuntos').val('');       


            });
}
function admin_ao(){    
        var to = false;
        $('#demo_q').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                        var v = $('#demo_q').val();
                        $('#tree').jstree(true).search(v);
                }, 250);
        });

        $('#tree')
                .jstree({
                        'core' : {
                                'data' : {
                                        'url' : 'clases/organizacion/server.php?cod_link='+$('#cod_link').val()+'&modo='+$('#modo').val(),
                                        //'url' : 'clases/organizacion/response.php?operation=get_node',
                                        'data' : function (node) {
                                                return { 'id' : node.id };
                                        }
                                },
                                'check_callback' : true,
                                'themes' : {
                                        'responsive' : false
                                }
                        },
                        'force_text' : true,
                        'plugins' : ['state','dnd','contextmenu','search','types'],
                        'types' :{
                            'default' : {
                                'icon': ''
                            },
                            'area_espejo':{
                                'icon': 'icon-folder-link' //icon glyphicon-resize-small
                            }
                        },
                        'contextmenu': {
                            items: function($node) {
                                        var tree = $("#tree").jstree(true);
                                        return {
                                            "Espejo":{
                                                "separator_before": false,
                                                "separator_after": false,
                                                "label": "&Aacute;rea Vinculada",
                                                "action": function (obj) { 
                                                    console.log($node.id);
                                                    marcar_area_espejo($node.id);
                                                    /*$node = tree.create_node($node);
                                                    tree.edit($node);*/
                                                }
                                            },
                                            "Create": {
                                                "separator_before": false,
                                                "separator_after": false,
                                                "label": "Crear",
                                                "action": function (obj) { 
                                                    $node = tree.create_node($node);
                                                    tree.edit($node);
                                                }
                                            },
                                            "Rename": {
                                                "separator_before": false,
                                                "separator_after": false,
                                                "label": "Renombrar",
                                                "action": function (obj) { 
                                                    tree.edit($node);
                                                }
                                            },                         
                                            "Remove": {
                                                "separator_before": false,
                                                "separator_after": false,
                                                "label": "Eliminar",
                                                "action": function (obj) { 
                                                    tree.delete_node($node);
                                                }
                                            },
                                            ccp:{
                                                    separator_before:!0,
                                                    icon:!1,
                                                    separator_after:!1,
                                                    label:"Edit",
                                                    action:!1,
                                                    submenu:{
                                                            cut:{
                                                                    separator_before:!1,
                                                                    separator_after:!1,
                                                                    label:"Cut",
                                                                    action:function(b){
                                                                        tree.cut($node);                                                                                                                                            
                                                                    }
                                                            },
                                                            copy:{
                                                                    separator_before:!1,
                                                                    icon:!1,
                                                                    separator_after:!1,
                                                                    label:"Copy",
                                                                    action:function(b){
                                                                            tree.copy($node);                                                                            
                                                                    }
                                                            },
                                                            paste:{
                                                                    separator_before:!1,
                                                                    icon:!1,
                                                                    _disabled:function(b){
                                                                            return!tree.can_paste()
                                                                    },
                                                                    separator_after:!1,
                                                                    label:"Paste",
                                                                    action:function(b){
                                                                        tree.paste($node);                                                                            
                                                                    }
                                                            }
                                                    }
                                                }
                                        };
                                    }
                        }
                    })
                .on('delete_node.jstree', function (e, data) {
                        $.get('clases/organizacion/response.php?operation=delete_node', { 'id' : data.node.id })
                                .done(function (d) {
                                    if(d.exito==2){
                                        VerMensaje('error',''+d.msj+'');
                                        data.instance.refresh();
                                    }

                                        //data.instance.set_id(data.node, d.id);
                                })
                                .fail(function (jqXHR, textStatus, errorThrown) {
                                     console.log(jqXHR.responseText);
                                     if (jqXHR.responseText.indexOf('mos_documentos_estrorg_arbolproc_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen Documentos asociados');
                                     }else if (jqXHR.responseText.indexOf('mos_acciones_correctivas_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen Acciones Correctivas asociadas');
                                     }else if (jqXHR.responseText.indexOf('mos_correcciones_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen Correcciones asociadas');
                                     }else if (jqXHR.responseText.indexOf('mos_personal_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen personas asociados');
                                     }else if (jqXHR.responseText.indexOf('mos_arbol_procesos_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen procesos asociados');
                                     }                                                                          
                                        data.instance.refresh();
                                });
                })
                .on('create_node.jstree', function (e, data) {
                        $.get('clases/organizacion/response.php?operation=create_node', { 'id' : data.node.parent, 'position' : data.position, 'text' : data.node.text })
                                .done(function (d) {
                                        data.instance.set_id(data.node, d.id);
                                })
                                .fail(function () {
                                        data.instance.refresh();
                                });
                })
                .on('rename_node.jstree', function (e, data) {
                        $.get('clases/organizacion/response.php?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
                                .fail(function () {
                                        data.instance.refresh();
                                });
                })
                .on('move_node.jstree', function (e, data) {
                        $.get('clases/organizacion/response.php?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent, 'position' : data.position })
                                .fail(function () {
                                        data.instance.refresh();
                                });
                })
                .on('copy_node.jstree', function (e, data) {
                        $.get('clases/organizacion/response.php?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent, 'position' : data.position })
                                .always(function () {
                                        data.instance.refresh();
                                });
                })
                .on('changed.jstree', function (e, data) {
                        if (data.selected.length > 0){
                            //console.log($("#divtree").jstree("get_selected").text());
                            var arr = data.selected[0].split("_");
                            id = arr[1];
                            $('#b-id_organizacion').val(data.selected[0]);
                            //alert($('#b-id_organizacion-reg'));
                        }
                        else
                            $('#b-id_organizacion').val('');
                });

}

function admin_ap(){    
        var to = false;
        $('#demo_q').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                        var v = $('#demo_q').val();
                        $('#tree').jstree(true).search(v);
                }, 250);
        });
        
        $('#tree')
                .jstree({
                        'core' : {
                                'data' : {
                                        'url' : 'clases/arbol_procesos/server.php?id_ao='+$('#b-id_organizacion').val(),
                                        //'url' : 'clases/organizacion/response.php?operation=get_node',
                                        'data' : function (node) {
                                                return { 'id' : node.id };
                                        }
                                },
                                'check_callback' : true,
                                'themes' : {
                                        'responsive' : false
                                }
                        },
                        'force_text' : true,
                        'plugins' : ['state','dnd','contextmenu','search']
                })
                .on('delete_node.jstree', function (e, data) {
                        $.get('clases/arbol_procesos/response.php?operation=delete_node', { 'id' : data.node.id })
                                .done(function (d) {
                                    if(d.exito==2){
                                        VerMensaje('error',''+d.msj+'');
                                        data.instance.refresh();
                                    }

                                        //data.instance.set_id(data.node, d.id);
                                })
                                .fail(function (jqXHR, textStatus, errorThrown) {
                                     //console.log(jqXHR.responseText);
                                     if (jqXHR.responseText.indexOf('mos_documentos_estrorg_arbolproc_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen Documentos asociados');
                                     }else if (jqXHR.responseText.indexOf('mos_acciones_correctivas_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen Acciones Correctivas asociadas');
                                     }else if (jqXHR.responseText.indexOf('mos_correcciones_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen Correcciones asociadas');
                                     }else if (jqXHR.responseText.indexOf('mos_personal_ibfk_1')!=-1){
                                         VerMensaje('error','No se puede eliminar el &aacute;rea, existen personas asociados');
                                     }
                                        data.instance.refresh();
                                });
                })
                .on('create_node.jstree', function (e, data) {
                        $.get('clases/arbol_procesos/response.php?operation=create_node', { 'id' : data.node.parent, 'position' : data.position, 'text' : data.node.text, 'id_organizacion':$('#b-id_organizacion').val() })
                                .done(function (d) {
                                        data.instance.set_id(data.node, d.id);
                                })
                                .fail(function () {
                                        data.instance.refresh();
                                });
                })
                .on('rename_node.jstree', function (e, data) {
                        $.get('clases/arbol_procesos/response.php?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
                                .fail(function () {
                                        data.instance.refresh();
                                });
                })
                .on('move_node.jstree', function (e, data) {
                        $.get('clases/arbol_procesos/response.php?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent, 'position' : data.position, 'id_organizacion':$('#b-id_organizacion').val() })
                                .fail(function (jqXHR, textStatus, errorThrown) {
                                    if (jqXHR.responseText.indexOf('id_organizacion')!=-1){
                                         VerMensaje('error','No se puede mover el proceso a la raiz, existe mas de un &aacute;rea seleccionada');
                                    }
                                        data.instance.refresh();
                                });
                })
                .on('copy_node.jstree', function (e, data) {
                        $.get('clases/arbol_procesos/response.php?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent, 'position' : data.position })
                                .always(function () {
                                        data.instance.refresh();
                                });
                })
                .on('changed.jstree', function (e, data) {
                        if (data.selected.length > 0){
                            //console.log($("#divtree").jstree("get_selected").text());
                            var arr = data.selected[0].split("_");
                            id = arr[1];
                            $('#b-id_proceso').val(data.selected[0]);
                            //alert($('#b-id_organizacion-reg'));
                        }
                        else
                            $('#b-id_proceso').val('');
                });

}

function marcar_desmarcar_checked_columns(checked){
    
        if(checked) { // check select status
            $('.checkbox-mos-col').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.checkbox-mos-col').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }       
}

function init_filtro_rapido(){
    $('#btn-filtro').on('click', function (event) {
            event.preventDefault();
            $('#b-filtro-sencillo').val($('#b-filtro').val());
            verPagina(1,document);
        });
        
    $('#b-filtro').keyup(function(e){
        if(e.keyCode == 13)
        {
            $('#b-filtro-sencillo').val($('#b-filtro').val());
            verPagina(1,document);
        }
    });
    
    
}

function init_filtro_ao_simple(){
    $('#div-ao').jstree(
//            {
//                "types": {
//                    "verde": {
//                        "icon": "diseno/images/verde.png"
//                    },
//                    "rojo": {
//                        "icon": "diseno/images/rojo.png"
//                    }
//                },
//                "plugins": ["search", "wholerow", "types"]
//            }
        );
    $('#div-ao').on("changed.jstree", function (e, data) {
        if (data.selected.length > 0){
            //console.log($("#divtree").jstree("get_selected").text());
            var arr = data.selected[0].split("_");
            id = arr[1];
            $('#b-id_organizacion').val(id);
            //alert($('#b-id_organizacion-reg'));
        }
        else
            $('#b-id_organizacion').val('');
    
        verPagina(1,document);
        //console.log(data.selected);
    });
}

function init_filtro_ao_multiple(checkbox_cascade){
    $('#div-ao').jstree(
            {
//                "types": {
//                    "verde": {
//                        "icon": "diseno/images/verde.png"
//                    },
//                    "rojo": {
//                        "icon": "diseno/images/rojo.png"
//                    }
//                },
                "checkbox":{
                    three_state : false,
                        cascade : 'down'
                },
                "plugins": ["search", "types","checkbox"]
            }
        );
    $('#div-ao').on("changed.jstree", function (e, data) {
        if (data.selected.length > 0){
            //console.log($("#divtree").jstree("get_selected").text());
            var arr;
            var id = '';
            for(i=0;i<data.selected.length;i++){
                arr = data.selected[i].split("_");
                id = id + arr[1] + ',';
            }
            id = id.substr(0,id.length-1);
            //alert(id);
            $('#b-id_organizacion').val(id);
            //alert($('#b-id_organizacion-reg'));
        }
        else
            $('#b-id_organizacion').val('');
    
        if ( $("#tree").length > 0 ){
            if ($('#b-id_organizacion').val() == '')
                $("#id-tree-ap").html('<div id="tree">Seleccione un &Aacute;rea para administrar el &Aacute;rbol de Procesos</div>');
            else
            {
                $("#id-tree-ap").html('<div id="tree"></div>');
                admin_ap();
            }
        }
        else
           verPagina(1,document);
        //console.log(data.selected);
    });
}
function init_filtro_ao_multiple_reg(checkbox_cascade){
    $('#div-ao-reg').jstree(
            {
//                "types": {
//                    "verde": {
//                        "icon": "diseno/images/verde.png"
//                    },
//                    "rojo": {
//                        "icon": "diseno/images/rojo.png"
//                    }
//                },
                "checkbox":{
                    three_state : false,
                        cascade : 'down'
                },
                "plugins": ["search", "types","checkbox"]
            }
        );
    $('#div-ao-reg').on("changed.jstree", function (e, data) {
        if (data.selected.length > 0){
            //console.log($("#divtree").jstree("get_selected").text());
            var arr;
            var id = '';
            for(i=0;i<data.selected.length;i++){
                arr = data.selected[i].split("_");
                id = id + arr[1] + ',';
            }
            id = id.substr(0,id.length-1);
            //alert(id);
            $('#b-id_organizacion-reg').val(id);
            //alert($('#b-id_organizacion-reg'));
        }
        else
            $('#b-id_organizacion-reg').val('');
    
        
           verPagina_aux(1,document);
        //console.log(data.selected);
    });
}
function init_filtro_ao_simple_reg(){
    //alert(111);
    $('#div-ao-reg').jstree();
    $('#div-ao-reg').on("changed.jstree", function (e, data) {
        if (data.selected.length > 0){
            //console.log($("#divtree").jstree("get_selected").text());
            var arr = data.selected[0].split("_");
            id = arr[1];
            $('#b-id_organizacion-reg').val(id);
            //alert($('#b-id_organizacion-reg').val());
        }
        else
            $('#b-id_organizacion-reg').val('');
    
        verPagina_aux(1,document); 
        //console.log(data.selected);
    });
}


function init_filtro_ao_multiple_reg(checkbox_cascade){
   $('#div-ao-reg').jstree(
           {
//                "types": {
//                    "verde": {
//                        "icon": "diseno/images/verde.png"
//                    },
//                    "rojo": {
//                        "icon": "diseno/images/rojo.png"
//                    }
//                },
               "checkbox":{
                   three_state : false,
                       cascade : 'down'
               },
               "plugins": ["search", "types","checkbox"]
           }
       );
   $('#div-ao-reg').on("changed.jstree", function (e, data) {
       if (data.selected.length > 0){
           //console.log($("#divtree").jstree("get_selected").text());
           var arr;
           var id = '';
           for(i=0;i<data.selected.length;i++){
               arr = data.selected[i].split("_");
               id = id + arr[1] + ',';
           }
           id = id.substr(0,id.length-1);
           //alert(id);
           $('#b-id_organizacion-reg').val(id);
           //alert($('#b-id_organizacion-reg'));
       }
       else
           $('#b-id_organizacion-reg').val('');
   
       
          verPagina_aux(1,document);
       //console.log(data.selected);
   });
}

function init_filtro_ap_simple_reg(){
    $('#div-ap-reg').jstree();
    $('#div-ap-reg').on("changed.jstree", function (e, data) {
        if (data.selected.length > 0){
            //console.log($("#divtree").jstree("get_selected").text());
            var arr = data.selected[0].split("_");
            id = arr[1];
            $('#b-id_proceso-reg').val(id);
            //alert($('#b-id_organizacion-reg'));
        }
        else
            $('#b-id_proceso-reg').val('');
    
        verPagina_aux(1,document);
        //console.log(data.selected);
    });
}
function r_init_filtro_rapido(){
    $('#r-btn-filtro').on('click', function (event) {
            event.preventDefault();
            $('#r-b-filtro-sencillo').val($('#r-b-filtro').val());
            verPagina_aux(1,document);
        });
        
    $('#r-b-filtro').keyup(function(e){
        if(e.keyCode == 13)
        {
            $('#r-b-filtro-sencillo').val($('#r-b-filtro').val());
            verPagina_aux(1,document);
        }
    });
}

function filtrar_listado(){
    verPagina(1,document);
    $('#myModal-Filtro').modal('hide');
    $('#a-myModal-Filtro').hide();
    $('#a-myModal-Filtro-des').show();
}

function activar_filtrar_listado(){
//    $('#busquedaFrm').each (function(){
//        alert(this.val());
//        this.reset();
//    });    
    $('#busquedaFrm').trigger("reset");
    //$('#busquedaFrm textarea').html('');
    
//    $('#myModal-Filtro').modal('hide');
//    $('#a-myModal-Filtro').show();
//    $('#a-myModal-Filtro-des').hide();
//    if ($('#b-iframe').length){
//        $('#b-iframe').attr('src', 'pages/personas/emb_jstree_single.php?' + Math.random() * (1000 - 1)) ;
//        $('#b-id_organizacion').val('');
//    }
//    if ($('#b-iframe-p').length){
//        $('#b-iframe-p').attr('src', 'pages/arbol_procesos/emb_jstree_procesos.php?' + Math.random() * (1000 - 1)) ;
//        $('#b-id_proceso').val('');
    //}
    verPagina(1,document);
}



function mensajes_pendientes(){
    //alert(5);
     array = new XArray();
        array.setObjeto('AvisoSMS','buscar_mensajes');
        array.addParametro('import','clases.aviso_sms.AvisoSMS');
        xajax_Loading(array.getArray());
}

function script_reporte(){          
        $(".pendiente").click(function(e){ 
            e.preventDefault();
            var id_area = ''; 
            if ($(this).attr('id-area'))
                id_area = $(this).attr('id-area');
            var id_gerencia = ''; 
            if ($(this).attr('id-gerencia'))
                id_gerencia = $(this).attr('id-gerencia');
            var params = getForm('busquedaFrm');            
            params = 'estatus=Pendiente&id_area=' + id_area + '&id_gerencia=' + id_gerencia + params;            
            var src = 'pages/reporte/listarReporte.php?'+params;
            $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :false,
                    'type':'iframe',
                    'width' : 700,
                    'height' : 420
                });                   
            $("#ver_ficha_trabajador").trigger('click');            
        });
        $(".activo").click(function(e){ 
            e.preventDefault();
            var id_area = ''; 
            if ($(this).attr('id-area'))
                id_area = $(this).attr('id-area');
            var id_gerencia = ''; 
            if ($(this).attr('id-gerencia'))
                id_gerencia = $(this).attr('id-gerencia');
            var params = getForm('busquedaFrm');            
            params = 'estatus=Activo&id_area=' + id_area + '&id_gerencia=' + id_gerencia + params;            
            var src = 'pages/reporte/listarReporte.php?'+params;
            $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :false,
                    'type':'iframe',
                    'width' : 700,
                    'height' : 420
                });                   
            $("#ver_ficha_trabajador").trigger('click');            
        });
    }

function link_titulos(valor){
    
    if (valor == $('#corder').val()){
        if ($('#sorder').val()== 'asc')
            $('#sorder').val('desc');
        else 
            $('#sorder').val('asc');
    }
    else
        $('#sorder').val('desc');
    $('#corder').val(valor);
    verPagina(1,1);
}

function cargar_estados(valor){
    if (valor == 0){
        clearOptions('id_estado');
        clearOptions('id_municipio');
        clearOptions('id_parroquia');
        addOption('id_parroquia', 'Seleccione', '0');
        addOption('id_estado', 'Seleccione', '0');
        addOption('id_municipio', 'Seleccione', '0');
    }
    else
        {
          clearOptions('id_municipio');
          addOption('id_municipio', 'Seleccione', '0');
          clearOptions('id_parroquia');
          addOption('id_parroquia', 'Seleccione', '0');
          array = new XArray();
          array.setObjeto('Estados','paisesEstados');
          array.addParametro('import','clases.estados.Estados');
          array.addParametro('id_pais',valor);
          xajax_Loading(array.getArray());
        }
}

function cargar_sucursales(valor){
    if (valor == ''){
        clearOptions('id_sucursal');
        addOption('id_sucursal', 'Seleccione', '');
    }
    else
        {
          clearOptions('id_sucursal');
          array = new XArray();
          array.setObjeto('Sucursales','EmpresasSucursalesCombo');
          array.addParametro('import','clases.sucursales.Sucursales');
          array.addParametro('id_empresa',valor);
          array.addParametro('descripcion','');
          xajax_Loading(array.getArray());
        }
}

function cargar_municipios(valor){
    if (valor == 0){
        clearOptions('id_municipio');
        clearOptions('id_parroquia');
        addOption('id_municipio', 'Seleccione', '0');
        addOption('id_parroquia', 'Seleccione', '0');
    }
    else
        {
          clearOptions('id_parroquia');
          addOption('id_parroquia', 'Seleccione', '0');
          array = new XArray();
          array.setObjeto('Municipios','estadosMunicipios');
          array.addParametro('import','clases.municipios.Municipios');
          array.addParametro('id_estado',valor);
          xajax_Loading(array.getArray());
        }
}

function cargar_parroquias(valor){
    if (valor == 0){
        clearOptions('id_parroquia');
        addOption('id_parroquia', 'Seleccione', '0');
    }
    else
        {
          array = new XArray();
          array.setObjeto('Parroquias','municipioParroquias');
          array.addParametro('import','clases.parroquias.Parroquias');
          array.addParametro('id_municipio',valor);
          xajax_Loading(array.getArray());
        }
}

function VerMensaje_GB(tipo,mensaje){
    setTimeout(function(){ parent.$.fancybox.close(); }, 4000);
    VerMensaje(tipo, mensaje);        
}

function ver_ficha_trabajador(id){
     var src = 'pages/trabajador/fichaTrabajador.php?id=' + id;
     $('a#ver_ficha_trabajador').fancybox({
                'titleShow': false,
                'href' : src,
                'autoDimensions' :false,
                'width' : 800,
                'height' : 520
            });
     //$('a#prueba').fancybox().fancybox_show();
     $("#ver_ficha_trabajador").trigger('click');
 }

 function invocar_ficha_trabajador(id){
     window.parent.parent.ver_ficha_trabajador(id);
 }

function VerMensaje(tipo,mensaje){
    if(tipo=='error'){        
     alertify.error(mensaje,15); 
     //document.getElementById("mensaje_error").innerHTML='<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>' + mensaje + '</div>';
     //$("#mensaje_error").fadeIn(800);
     //setTimeout(function(){ $(".mensajeserror").fadeOut(800);}, 5000);
     //window.location='#mensaje_error';     
     
    }
    if(tipo=='exito'){
        alertify.notify(mensaje, 'success', 5);
//     document.getElementById("mensaje_exito").innerHTML='<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>' + mensaje + '</div>';
//     $("#mensaje_exito").fadeIn(800);
//     //setTimeout(function(){ $("#mensaje_exito").fadeOut(800);}, 3000);
//     window.location='#mensaje_exito';    
//     calcHeight();
    }
    if(tipo=='info'){
     document.getElementById("mensaje_info").innerHTML='<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>' + mensaje + '</div>';
     $("#mensaje_info").fadeIn(800);
     setTimeout(function(){ $("#mensaje_info").fadeOut(800);}, 3000);
     window.location='#mensaje_info';     
     calcHeight();
    }
    if(tipo=='info_correo'){
     //alert(document.getElementById("mensaje_info_correo"));
     document.getElementById("aviso_correo").innerHTML=mensaje;
     $(".mensajesinfocorreo").fadeIn(1500);
     setTimeout(function(){ $(".mensajesinfocorreo").fadeOut(100).fadeIn(1000).fadeOut(800).fadeIn(1000).fadeOut(800)}, 3000);
     //window.location='#mensaje_info';
     //calcHeight();

    }

}

function ValidaHora( campo )
{
        //var er_fh = /^(1|01|2|02|3|03|4|04|5|05|6|06|7|07|8|08|9|09|10|11|12)\:([0-5]0|[0-5][1-9])\ (AM|PM)$/
        var er_fh = /^(0|00|1|01|2|02|3|03|4|04|5|05|6|06|7|07|8|08|9|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23)\:([0-5]0|[0-5][1-9])/
        if( document.getElementById(campo).value == "" )
        {
                return false
        }
        if ( !(er_fh.test( document.getElementById(campo).value )) )
        {
                //VerMensaje('error',"El dato en el campo hora no es v�lido.")
                return false
        }

        //VerMensaje('error',"�Campo de hora correcto!")
        return true
}

function MostrarContenido2()
{
    document.getElementById('contenido').style.display='none';
    document.getElementById('contenido-form').style.display='';
    $('#contenido-form').parent().show();
    $('#contenido').parent().hide();
    document.getElementById('contenido-aux').style.display='none';
    document.getElementById('contenido-form-aux').style.display='none';
    document.getElementById('contenido-form-aux').innerHTML='';
    document.getElementById('contenido-aux').innerHTML='';
    $('#contenido-form-aux').parent().show();
    $('#contenido-aux').parent().hide();
    if($('#div-titulo-mod').html().indexOf('<br') >0){
        $('#div-titulo-for').html($('#div-titulo-mod').html().substring(0, $('#div-titulo-mod').html().indexOf('<br')-1) + '<br>' + $('#div-titulo-for').html());
    }
    else
        $('#div-titulo-for').html($('#div-titulo-mod').html() + '<br>' + $('#div-titulo-for').html());
}

function MostrarContenido()
{
    document.getElementById('contenido').style.display='';
    document.getElementById('contenido-form').style.display='none';
    $('#contenido-form').parent().hide();
    $('#contenido').parent().show();
    document.getElementById('contenido-form').innerHTML='';
    document.getElementById('contenido-aux').style.display='none';
    document.getElementById('contenido-form-aux').style.display='none';
    document.getElementById('contenido-form-aux').innerHTML='';
    document.getElementById('contenido-aux').innerHTML='';
    $('#contenido-form-aux').parent().hide();
    $('#contenido-aux').parent().show();
    if(document.getElementById('pag_actual')) 
        verPagina(document.getElementById('pag_actual').value,document); 
    else 
        verPagina(1,document);
    PanelOperator.resize('');
    
}

function MostrarContenido2Aux()
{
    document.getElementById('contenido-aux').style.display='none';
    document.getElementById('contenido-form-aux').style.display='';
    $('#contenido-form-aux').parent().show();
    $('#contenido-aux').parent().hide();
    $('#r-div-titulo-for').html($('#r-div-titulo-mod').html() + '<br>' + $('#r-div-titulo-for').html());
    //document.getElementById('contenido-aux').style.display='none';
    //document.getElementById('contenido-form-aux').style.display='none';

}

function MostrarContenidoAux()
{
    document.getElementById('contenido-aux').style.display='';
    document.getElementById('contenido-form-aux').style.display='none';
    document.getElementById('contenido-form-aux').innerHTML='';
    document.getElementById('contenido').style.display='none';
    document.getElementById('contenido-form').style.display='none';
    $('#contenido-form-aux').parent().hide();
    $('#contenido-aux').parent().show();
    $('#contenido').parent().hide();
    $('#contenido-form').parent().hide();
    
    if(document.getElementById('r-pag_actual')) 
        verPagina_aux(document.getElementById('r-pag_actual').value,document); 
    else 
        verPagina_aux(1,document);
}

function funcion_volver(pagina){
    
    document.getElementById('contenido').style.display='';
    document.getElementById('contenido-form').style.display='none';
    document.getElementById('contenido-form').innerHTML='';
    $('#contenido-form').parent().hide();
    $('#contenido').parent().show();
    if(document.getElementById('pag_actual')) 
        verPagina(document.getElementById('pag_actual').value,document); 
    else 
       if (document.getElementById('reg_por_pag'))
            verPagina(1,document)
    else
        //OpAct = document.getElementById('opcion1');
    //var opcion = OpAct.id;
    //alert(document.getElementById(opcion));
    OpAct.onclick();
    //document.getElementById(opcion).onclick();
//        document.getElementById('frm_volver').action =pagina;
//	document.getElementById('permiso').value =document.getElementById('permiso_modulo').value;
//        if(document.getElementById('modulo')){
//            document.getElementById('modulo').value =document.getElementById('modulo_actual').value;
//        }
//        document.getElementById('frm_volver').submit();
}

function exportarExcel(){
    var params =  getForm('busquedaFrm');
    //window.open('pages/' +  document.getElementById("modulo_actual").value + '/exportarExcel.php?campo='+document.getElementById("campo").value + '&valor=' + document.getElementById("valor").value + '&corder=' + document.getElementById("corder").value + '&sorder=' + document.getElementById("sorder").value,null,'toolbar=no, location=no, menubar=no, width=600,height=400');
    window.open('pages/' +  document.getElementById("modulo_actual").value + '/exportarExcel.php?'+params,'_blank');
}

function addOption(id,text,value){
  CBox = document.getElementById(id);
  CBox.options[CBox.options.length] = new Option(text,value);
}
/******************************************************************************/
function clearOptions(id){
   CBox  = document.getElementById(id);
   items = CBox.options.length;
   for(i=0;i<items;i++)
     CBox.remove(0);
}

//
//JavaScript Document
//borrar los espacios en blanco en javascript en el principio de la cadena
function ltrim(s)
{
    return s.replace(/^\s+/, "");
}

//borrar los espacios en blanco en javascript al final de la cadena
function rtrim(s)
{
    return s.replace(/\s+$/, "");
}

//Borra los espacios en blanco, tanto al principio como al fina de la cadena
function trim(s)
{
    return rtrim(ltrim(s));
}


//Quita los encabezado HTTP/1.1 100 Continue que retorna el servidor en una cadena.
function quitar_encabezado(id)
{
   var car="";
   var i= 0;

    if (id != "")
		cad = document.getElementById(id).innerHTML;

	i = cad.indexOf('HTTP/1.1 100 Continue');

	if(i > -1)
	{
	   cad= cad.substr(cad.indexOf('HTTP/1.1 100 Continue')+22);
	   document.getElementById(id).innerHTML  = cad;
	}

	return document.getElementById(id).innerHTML;

}


function Existe_msj(cad, txt)
{
   var i= 0;      
   
   i = cad.indexOf(txt);
   
   if (i > -1)
     cad= cad.substr(i+4);
   
   return cad;	 
}


function quitar_msj(cad, txt)
{
   
   var i= 0;   
   
   i = cad.indexOf(txt);
   
   if (i > -1)
     cad= cad.substr(0, i);
   
   return cad;

}


function chkstring(regExp,str)
{

    if (str.length == 0)
	  return false;

	else
	{
		var a = str.match(regExp); // Find matches.

		if (a == null)
		{
			return false;
		}
		else
		{
			return true;
		}
   }

}


function alfanum_con_espacios(valorcampo)
{
   var RegExPattern = /(^[a-zA-Z������������|0-9| ]+[-|_|\.|\,]*[a-zA-Z������������|0-9| ]+$)/;

   return chkstring(RegExPattern,valorcampo);

}


function alfanum_sin_espacios(valorcampo)
{
   var RegExPattern = /(^[a-zA-Z������������|0-9]+[a-zA-Z������������|0-9-_]*$)/;

   return chkstring(RegExPattern,valorcampo);

}


function validar_correo(valorcampo)
{
	var palabra = /[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
	return chkstring(palabra,valorcampo);

}


function validar_telefono(valorcampo)
{
    var palabra = /(^[0-9]{2,4}-? ?[0-9]{6,9}$)/;
    return chkstring(palabra,valorcampo);
}


function numero_guiones(campo)
{
  /* var palabra = /(^[0-9]+[-|_|]+[0-9]+$)/;
   return chkstring(palabra,valorcampo);*/
   
    if (campo == "")
   {
     return false;
   }

   var ubicacion="";
   var enter = "\n";
   var caracteres = "1234567890-" + String.fromCharCode(13) + enter;

   var contador = 0;

   for (var i=0; i < campo.length; i++)
   {
     ubicacion = campo.substring(i, i + 1);

     if (caracteres.indexOf(ubicacion) != -1)
	 {
       contador++;
     }
	 else
	 {
       return false;
     }
   }

   return true;
}

function numero_decimal(campo)
{
  /* var palabra = /(^[0-9]+[-|_|]+[0-9]+$)/;
   return chkstring(palabra,valorcampo);*/
   
    if (campo == "")
   {
     return false;
   }

   var ubicacion="";
   var enter = "\n";
   var caracteres = "1234567890." + String.fromCharCode(13) + enter;

   var contador = 0;

   for (var i=0; i < campo.length; i++)
   {
     ubicacion = campo.substring(i, i + 1);

     if (caracteres.indexOf(ubicacion) != -1)
	 {
       contador++;
     }
	 else
	 {
       return false;
     }
   }

   return true;
   
   

}


function ValidarCampoAlfanumerico(campo)
{
   if (campo == "")
   {     
     return false;
   }

   var ubicacion="";
   var enter = "\n";
   var caracteres = "abcdefghijklmnopqrstuvwxyz�1234567890 ABCDEFGHIJKLMNOPQRSTUVWXYZ�����������_-.," + String.fromCharCode(13) + enter;

   var contador = 0;

   for (var i=0; i < campo.length; i++)
   {
     ubicacion = campo.substring(i, i + 1);

     if (caracteres.indexOf(ubicacion) != -1)
	 {
       contador++;
     }
	 else
	 {
       return false;
     }
   }

   return true;
}

function ValidarCampoAlfanumericoVacio(campo)
{
   var ubicacion="";
   var enter = "\n";
   var caracteres = "abcdefghijklmnopqrstuvwxyz�1234567890 ABCDEFGHIJKLMNOPQRSTUVWXYZ�����������_-.," + String.fromCharCode(13) + enter;

   var contador = 0;

   for (var i=0; i < campo.length; i++)
   {
     ubicacion = campo.substring(i, i + 1);

     if (caracteres.indexOf(ubicacion) != -1)
	 {
       contador++;
     }
	 else
	 {
       return false;
     }
   }

   return true;
}



function comprueba_extension(archivo) 
{
   extensiones_permitidas = new Array(".gif", ".jpg", ".txt", ".doc", ".ppt", ".xls", ".pdf", ".rtf", ".odt", ".ods", ".odp", ".png", ".odg");
   
   mierror = "";
   
   if (!archivo) 
   {
        //Si no tengo archivo, es que no se ha seleccionado un archivo en el formulario
        return -1;
   }
   else
   {
      //recupero la extensi�n de este nombre de archivo
      extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();      
	  
      //compruebo si la extensi�n est� entre las permitidas
      permitida = false;
      for (var i = 0; i < extensiones_permitidas.length; i++) 
	  {
         if (extensiones_permitidas[i] == extension) 
		 {
             permitida = true;
             break;
         }
      }
	  
      if (!permitida) 
	  {
         return 0;
		 
      }
	  else
	  {            
         return 1;
      }
   }
   
   return 0;
}


function devuelve_fecha(fecha)
{
	fecha = fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2);
	fecha = fecha.replace(/[-]/g, "/");
	fecha = new Date(fecha);
	
	return fecha
}


function DiferenciaFechas (seleccionada, hoy) 
{    
   var fecha1= devuelve_fecha(seleccionada);	
   var fecha2 = devuelve_fecha(hoy);	  
   
   //Resta fechas y redondea  
   var diferencia = fecha1.getTime() - fecha2.getTime();  
	
   var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));         
      
   return dias;  
	
}  
   

function restar_dias_habiles(fecha, hoy)
{
	
   var cantdias	 = DiferenciaFechas (fecha, hoy);
   
   
	
}
// funcion que recibe el objeto y verifica si es un numero valido
// de ser invalido, envia un mensaje, asigna 0 y devuelve el focus al objeto.
function Validarnumero(txtobj){
if (isNaN(txtobj.value))
{
	VerMensaje('error',"Debe cargar valores numericos");
	txtobj.value = 0;
	txtobj.focus();
	txtobj.select();
    return 0;
}
return 1;
}


// MARCA LA LA FILA DE UNA TABLA CUANDO SE PASA EL MOUSE POR ENCIMA EVENTO:MouseOver
function TRMarkOver(objTR){

	if (objTR.effects=='no' || objTR.effects=='NO' || objTR.effects=='No' || objTR.effects=='nO') return;
	//VerMensaje('error',objTR.cells.length);
	for (var i=0 ; i < objTR.cells.length ; i++ ) {
		objTR.cells[i].previousColor = objTR.cells[i].style.backgroundColor;
		objTR.cells[i].style.backgroundColor='#bbccee';
		//if (objTR.cells[i].all.tags('TABLE').length > 0) {
	//		TRMarkOver(objTR.cells[i].all.tags('TABLE')[0].rows[0]);
		//}

	}
}
// DESMARCA LA LA FILA DE UNA TABLA CUANDO SE PASA EL MOUSE POR ENCIMA EVENTO: MouseOut
function TRMarkOut(objTR) {

	if (objTR.effects=='no' || objTR.effects=='NO' || objTR.effects=='No' || objTR.effects=='nO') return;

	for (var i=0 ; i < objTR.cells.length ; i++ ) {
		objTR.cells[i].style.backgroundColor = objTR.cells[i].previousColor;
		//if (objTR.cells[i].all.tags('TABLE').length > 0) {
		//	TRMarkOut(objTR.cells[i].all.tags('TABLE')[0].rows[0]);
		//}
	}

}
function calcHeight(ancho_fijo)
{

var the_height= document.getElementById('contenido').contentWindow.document.body.scrollHeight;
var the_width= document.getElementById('contenido').contentWindow.document.body.scrollWidth;
if(the_height<100) the_height=250;
if(the_width<840 || !ancho_fijo) the_width=840;
if(ancho_fijo) the_width=ancho_fijo;
document.getElementById('contenido').height=the_height+100;
//parent.document.getElementById('Contenido').width=the_width;
//parent.document.getElementById('DivContenido').width=the_width;
}
// devuelve el valor seleccionado de un radiobutton, hay que pasarle el objeto con el formulario
         
function getRadioSelectedValue(ctrl)
{
    for(i=0;i<ctrl.length;i++)
        if(ctrl[i].checked)
            return ctrl[i].value;
}


function contrasena()
{

    array = new XArray();    
    array.setObjeto('Usuarios','editar_contrasena');    
    //array.addParametro('permiso',document.getElementById('permiso_modulo').value);
    
    array.addParametro('import','clases.usuarios.Usuarios');
    xajax_Loading(array.getArray());
    //GB_showCenter(".:: Cambiar Contraseña ::.", "../pages/seguridad/cambiarcontrasena.php", 400, 800);
//    var src = 'pages/seguridad/cambiarcontrasena.php?';
//    $('a#ver_ficha_trabajador').fancybox({
//                'titleShow': false,
//                'href' : src,
//                'autoDimensions' :false,
//                'type':'iframe',
//                'width' : 600,
//                'height' : 420
//            });
//    //$('a#prueba').fancybox().fancybox_show();
//    $("#ver_ficha_trabajador").trigger('click');

}
function VerConfiguraciones()
{
    array = new XArray();    
    array.setObjeto('mos_usuario','configuraciones');    
    array.addParametro('import','clases.mos_usuario.mos_usuario');
    xajax_Loading(array.getArray());
}
//----------------------------------------------------------------------------------------

function getForm (frm){
    var submitDisabledElements = false;
		var objForm = document.getElementById(frm);
		var sXml = "";
		if (objForm && objForm.tagName.toUpperCase() == 'FORM'){
			var formElements = objForm.elements;
			for(var i=0; i < formElements.length; i++){
				if(!formElements[i].name)
					continue;
				if(formElements[i].type && (formElements[i].type == 'radio' || formElements[i].type == 'checkbox') && formElements[i].checked == false)
					continue;
				if(formElements[i].disabled && formElements[i].disabled == true && submitDisabledElements == false)
					continue;
				var name = formElements[i].name;
				if(name){
					sXml += '&';
					if(formElements[i].type=='select-multiple'){
						for (var j = 0; j < formElements[i].length; j++)
							if (formElements[i].options[j].selected == true)
								sXml += name+"="+encodeURIComponent(formElements[i].options[j].value)+"&";
					}
					else
						sXml += name+"="+encodeURIComponent(formElements[i].value);
				}
			}
		}
		return sXml;
	}
        
        
        function abrir_notificaciones(tipo){
            parametros = {
                "tipo_filtro" : tipo
            };
            $.ajax({
                    data:  parametros,
                    url:   'pages/reporte/tipo_filtro.php',
                    type:  'post',   
                    success:  function (response) {
                            setTimeout(function(){ $("#9").trigger("click"); }, 500);
                            setTimeout(function(){ $(".opcion_menu_tab:first").trigger("click"); }, 1000);
                            setTimeout(function(){ ClickOpciones("28",$("#ul-27 li.leaf a:first"),"1111"); }, 2000);
                            $( "#dialog-message" ).dialog( "close" );
                    }

            });
            
        }
    
    function VerNotificacionesMenu(){
        array = new XArray();
        array.setObjeto('Notificaciones','VerNotificacionesMenu');
        array.addParametro('import','clases.notificaciones.Notificaciones');
        xajax_Loading(array.getArray());        
        $('#messages').css('margin-left',($('.status-bar').width()-300)+'px')
    }
    function LeerNotificacionesMenu(id){
        array = new XArray();
        array.setObjeto('Notificaciones','LeerNotificacionesMenu');
        array.addParametro('id',id);
        array.addParametro('import','clases.notificaciones.Notificaciones');
        xajax_Loading(array.getArray());
        var node=document.getElementById("noti"+id);
        node.parentNode.removeChild(node);
        alto = (document.getElementById('div-notificaciones').style.height);
        alto2 = parseInt(alto.replace('px',''));
        if(alto2-200>0)
            document.getElementById('div-notificaciones').style.height=(alto2-200)+'px';
        else
            $("#messages").collapse("hide");
    }    
    function MostrarNotificacionesEmergente(){
        array = new XArray();
        array.setObjeto('Notificaciones','MostrarNotificacionesEmergente');
        array.addParametro('import','clases.notificaciones.Notificaciones');
        xajax_Loading(array.getArray());
    } 
    function verWorkFlowPopup(id){
    array = new XArray();
    array.setObjeto('Documentos','ver_workflow');
    array.addParametro('id',id);
    array.addParametro('vienede','mensaje');
    array.addParametro('import','clases.documentos.Documentos');
    xajax_Loading(array.getArray());
    //PanelOperator.showDetail('');    
    }
    
    function WFAccionesACPopup(id){
        array = new XArray();
        array.setObjeto('AccionesAC','WFAccionesAC');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }
    
    function verListaDistribucionPopup(id){
        array = new XArray();
        array.setObjeto('ListaDistribucionDoc','editar');
        array.addParametro('id',id);
        array.addParametro('vienede','mensaje');
        array.addParametro('import','clases.lista_distribucion_doc.ListaDistribucionDoc');
        xajax_Loading(array.getArray());
    }
    
    function VerhistoricoNotificaciones(){
    array = new XArray();
    array.setObjeto('Notificaciones','indexNotificacionesHistorico');
    array.addParametro('import','clases.notificaciones.Notificaciones');
    xajax_Loading(array.getArray());
    //PanelOperator.showDetail('');    
    }    
    function CambiarEstadoWF(estado,etapa,id){
    array = new XArray();
    array.setObjeto('Documentos','cambiar_estado');
    array.addParametro('id',id);
    array.addParametro('estado',estado);
    array.addParametro('etapa',etapa);
    array.addParametro('import','clases.documentos.Documentos');
    xajax_Loading(array.getArray());
}
function RechazarWF(estado,etapa,id){
    if(document.getElementById("observacion_rechazo").style.display==''){
        array = new XArray();
        array.setObjeto('Documentos','cambiar_estado');
        array.addParametro('id',id);
        array.addParametro('estado',estado);
        array.addParametro('etapa',etapa);
        array.addParametro('observacion_rechazo',document.getElementById("observacion_rechazo").value);
        array.addParametro('import','clases.documentos.Documentos');
        xajax_Loading(array.getArray());
        $('#myModal-observacion-rechazo').modal('hide');
        
        }
    else{
        document.getElementById("observacion_rechazo").style.display='';
        alertify.error("Cargue una observacion de rechazo y vuelva a presionar Rechazar",5); 
    }
        
}
function CerrarNotificacionesSiOpen(){
    $("body").click(function(e) {
        //alert(e.target.id+'-'+(e.target.id).indexOf('cerrar'));
        if (!( (e.target.id).indexOf('cerrar')!=-1 || e.target.id == "messages" || $(e.target).parents("#messages").size())) { 
           $("#messages").collapse("hide");
        }
    });
} 
function VerBitacoraDocumentos(){
    array = new XArray();
    array.setObjeto('Documentos','indexBitacoraDocumentos');
    array.addParametro('import','clases.documentos.Documentos');
    xajax_Loading(array.getArray());
    //PanelOperator.showDetail('');    
         
}

function verAccionCorrectiva(id){
    array = new XArray();
    array.setObjeto('AccionesAC','ver');
    array.addParametro('id',id);
    array.addParametro('notificacion_interna','S');
    array.addParametro('import','clases.acciones_ac.AccionesAC');
    xajax_Loading(array.getArray());
}
