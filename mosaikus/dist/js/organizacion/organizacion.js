function demo_create() {
        var ref = $('#tree').jstree(true),
                sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        sel = ref.create_node(sel, {"type":"file"});
        if(sel) {
                ref.edit(sel);
        }
};
function demo_rename() {
        var ref = $('#tree').jstree(true),
                sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        ref.edit(sel);
};
function demo_delete() {
        var ref = $('#tree').jstree(true),
                sel = ref.get_selected();
        if(!sel.length) { return false; }
        ref.delete_node(sel);
};

function demo_abrir(){
    var ref = $('#tree').jstree(true),
                sel = ref.get_selected();
        if(!sel.length) { ref.open_all(); }
        ref.open_all(sel);
}

function demo_cerrar(){
    var ref = $('#tree').jstree(true),
                sel = ref.get_selected();
        if(!sel.length) { ref.close_all(); }
        ref.close_all(sel);
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
    
    function marcar_area_espejo(id){
        array = new XArray();
        array.setObjeto("ArbolOrganizacional","marcar_area_espejo");
        array.addParametro("id",id);
        //array.addParametro("id",$("#cmb_din_"+id).val());
//        array.addParametro("titulo",$("#nombre_din_"+id).val());
//        array.addParametro("token", $("#tok_new_edit").val());
        array.addParametro("import","clases.organizacion.ArbolOrganizacional");
        xajax_Loading(array.getArray());
    }
    
        function ao_simple(){
    $('#div-ao-form').jstree(
            {
//                "types": {
//                    "verde": {
//                        "icon": "diseno/images/verde.png"
//                    },
//                    "rojo": {
//                        "icon": "diseno/images/rojo.png"
//                    }
//                },
                "plugins": ["search", "types"]
            }
        );
    $('#div-ao-form').on("changed.jstree", function (e, data) {
        if (data.selected.length > 0){
            //console.log($("#divtree").jstree("get_selected").text());
            var arr = data.selected[0].split("_");
            id = arr[1];
            $('#relacion-id-area').val(id);            
        }
        else
            $('#relacion-id-area').val('');
        
        //console.log(data.selected);
    });
    //$('#div-ao-form').jstree(true).open_all();               
    var to = false;
    $('#demo_q_ao').keyup(function () {                    
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
                    var v = $('#demo_q_ao').val();
                    $('#div-ao-form').jstree(true).search(v);
            }, 250);
    });    
}

function asociar_area(){
    if ($('#relacion-id-area').val().length > 0){
        array = new XArray();
        array.setObjeto("ArbolOrganizacional","guardar_area_espejo");
        array.addParametro("id",$('#origen-id-area').val());
        array.addParametro("area_espejo",$('#relacion-id-area').val());
        array.addParametro("import","clases.organizacion.ArbolOrganizacional");
        xajax_Loading(array.getArray());
    }
    else{
        VerMensaje('error','Debe seleccionar el área para asignar la relación');
    }
}

function eliminar_area(){
    if ($('#relacion-id-area').val().length > 0){
        $('#div-ao-form').jstree(true).deselect_node('phtml_' + $('#relacion-id-area').val());
    }
    
    array = new XArray();
    array.setObjeto("ArbolOrganizacional","guardar_area_espejo");
    array.addParametro("id",$('#origen-id-area').val());
    array.addParametro("area_espejo",'NULL');
    array.addParametro("import","clases.organizacion.ArbolOrganizacional");
    xajax_Loading(array.getArray());
    
}