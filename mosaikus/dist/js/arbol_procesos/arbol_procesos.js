function demo_create() {
        var ref = $('#tree').jstree(true),
                sel = ref.get_selected();
        if(!sel.length) { 
            var ref_ao = $('#div-ao').jstree(true);
            var selecciones = ref_ao.get_selected();
            if ((selecciones.length) && (selecciones.length == 1)){
                //alert(4);
                //sel = ref.create_node(2, {"type":"file"});
                $("#tree").jstree("create_node", null, null, "last", function (node) {
                    this.edit(node);
                });
                //alert(3);
                if(sel) {
                    ref.edit(sel);
                }
                return;
            } 
             alertify.notify('Debe seleccionar un solo nodo del &Aacute;rbol Organizacional para poder crear un proceso.', 'custom', 10);
            return false;
        }
        sel = sel[0];
        //alert(sel);
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

function demo_abrir_ao(){
    var ref = $('#div-ao').jstree(true),
                sel = ref.get_selected();
        if(!sel.length) { ref.open_all(); }
        ref.open_all(sel);
}

function demo_cerrar_ao(){
    var ref = $('#div-ao').jstree(true),
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

    function nuevo_ArbolProcesos(){
            array = new XArray();
            array.setObjeto('ArbolProcesos','crear');
            array.addParametro('import','clases.arbol_procesos.ArbolProcesos');
            xajax_Loading(array.getArray());
    }

    function validar(doc){
        if($('#idFormulario').isValid()) {
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('ArbolProcesos','guardar');
            else
                array.setObjeto('ArbolProcesos','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.arbol_procesos.ArbolProcesos');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarArbolProcesos(id){
        array = new XArray();
        array.setObjeto('ArbolProcesos','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.arbol_procesos.ArbolProcesos');
        xajax_Loading(array.getArray());
    }


    function eliminarArbolProcesos(id){
        if(confirm("¿Desea Eliminar el ArbolProcesos Seleccionado?")){
            array = new XArray();
            array.setObjeto('ArbolProcesos','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.arbol_procesos.ArbolProcesos');
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
//        if ((isNaN(document.getElementById("reg_por_pag").value) == true) || (parseInt(document.getElementById("reg_por_pag").value) <= 0)){
//            array.addParametro('reg_por_pagina', 10);
//            document.getElementById("reg_por_pag").value = 10
//        }
//        else
//        {
//            array.addParametro('reg_por_pagina', document.getElementById("reg_por_pag").value);
//        }
//        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
//        array.addParametro('pag',pag);
        array.setObjeto('ArbolProcesos','buscar');
        array.addParametro('import','clases.arbol_procesos.ArbolProcesos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verArbolProcesos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verArbolProcesos.php?id='+id;
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
         window.open('pages/arbol_procesos/reporte_ap_pdf.php?'+params,'_blank');
    }
    