  /* FUNCION DEL ARBOL 31-08-16 RAQUEL ++++*/

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
/********************************/
    
    function init_filtrar(){        
            PanelOperator.initPanels('');
            ScrollBar.initScroll();
            init_filtro_rapido();
    }

    function filtrar_mostrar_colums(){
        var colums = '0-2-3-';
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

    function nuevo_RequisitosCargos(){
            array = new XArray();
            array.setObjeto('RequisitosCargos','crear');
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
            xajax_Loading(array.getArray());
    }


//asociar requisito al cargo y area
    function relacion_RequisitosCargos(id_cargo, id_area){//recibe como parametro e cargo y el area escogida
            array = new XArray();
            array.setObjeto('RequisitosCargos','crear');
            array.addParametro('modo',document.getElementById('modo').value);            
            array.addParametro('cod_link',document.getElementById('cod_link').value); 
            array.addParametro('id_cargo',id_cargo);//envia dos parametros al crear
            array.addParametro('id_area',id_area);
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
            xajax_Loading(array.getArray());
    }
    function validar(doc){        
        if($('#idFormulario').isValid()) {
            var cont_req=0;
            var cadena_req=doc.getElementById("vector_req_item").value;
            var vector_requisitos= cadena_req.split(",");
            for(i=0;i<(vector_requisitos.length-1);i++){
                if($("#req_"+vector_requisitos[i]).is(":checked"))
                        cont_req++;    
            }
            if(cont_req==0){
                VerMensaje('error','Debe seleccionar al menos un requisito');
                return;
            }
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('RequisitosCargos','guardar');
            else
                array.setObjeto('RequisitosCargos','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarRequisitosCargos(id_cargo,id_area){
        array = new XArray();
        array.setObjeto('RequisitosCargos','editar');
        array.addParametro('id_cargo',id_cargo);//envia dos parametros para buscar las relaciones ya exustentesS
        array.addParametro('id_area',id_area);
        array.addParametro('modo',document.getElementById('modo').value);            
        array.addParametro('cod_link',document.getElementById('cod_link').value); 
        array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
        xajax_Loading(array.getArray());
    }


    function eliminarRequisitosCargos(id){
        if(confirm("¿Desea Eliminar el RequisitosCargos Seleccionado?")){
            array = new XArray();
            array.setObjeto('RequisitosCargos','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
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
        array.setObjeto('RequisitosCargos','buscar');
        array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verRequisitosCargos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verRequisitosCargos.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }

    /******** funcion para mostrarselect de formulario**/
    function CargaComboForm(id_requisito_item,opc){
        var id_it=$(id_requisito_item).val();
    //alert('valor '+id_it);
        //if(id_requisito_item.checked){
        if($(id_requisito_item).is(":checked")){
            $('#formulario_doc_'+id_it).show();
            if(opc==1){
                $('#combos_form_'+id_it).show();
                $('#valores_form_'+id_it).show();
            }
            $('#form_'+id_it).attr("data-validation", "required");
           //  $('#form_'+id_it).show();
            
        }
        else{
            $('#formulario_doc_'+id_it).hide();
            $('#combos_form_'+id_it).hide();
            $('#valores_form_'+id_it).hide();
           $('#form_'+id_it).removeAttr("data-validation");
        }
        }
        
    /*********** funcion para mostrar parametros de acurdo al formulario seleccionado */
   function CargaComboParametros(id_formulario,id_requisito_item,tipo,vigencia,id_req_form){
    array = new XArray();

   $('#combos_form_'+id_it).hide();
    var id_form=$(id_formulario).val();
    var id_it=id_requisito_item;
    if(id_form=='') return;//en caso de que marque opcion seleccione
    var capacitacion= id_form.split("_");//para ver si se selecciono fue una capacitacion
 //alert("valor del select "+capacitacion);

    if(capacitacion[0]=='cap'){//si se selecciono un curso
       // alert('escoge capacitacion');
        array.setObjeto('RequisitosCargos','GuardarRequisitoCapacitacion');
        array.addParametro('id_capacitacion',capacitacion[1]);
        array.addParametro('nuevo',id_req_form);//si es 0 es para crear   
        array.addParametro('id_req_item',id_it);
        array.addParametro('tipo_req',tipo);
        array.addParametro('vigencia_req',vigencia);

    }
    else{
        array.setObjeto('RequisitosCargos','Comboparametros');
        array.addParametro('id_form',id_form);
        array.addParametro('nuevo',id_req_form);//si es 0 es para crear         
        array.addParametro('id_req_item',id_it);
        array.addParametro('tipo_req',tipo);
        array.addParametro('vigencia_req',vigencia);
    }
        array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
        xajax_Loading(array.getArray());
    //$('#combos_form_'+id_it).show();
    }

      /*********** funcion para mostrar valores de parametros de acuerdo al parametro de indexacion seleccionado */
   function CargaValoresParametros(id_parametro,id_formulario,id_requisito_item,id_persona,condicion){
   var id_params=$(id_parametro).val();
   var id_form=id_formulario;
   var id_it=id_requisito_item;
   $('#valores_form_'+id_it).hide();
   if(id_form=='') return;
   if(id_params!=null){

  // alert("envio "+envio);
   var envio=false;
    array = new XArray();
   array.setObjeto('RequisitosCargos','ValoresParametros');
   switch(condicion){
    case 1://solo combo
           id_combo=id_params;
           array.addParametro('id_combo',id_combo);
           envio=true;
    break;
    case 2://combo y vigencia
            id_combo=id_params[0];
            id_vigencia=id_params[1];
            if (id_combo !== undefined && id_vigencia !== undefined){ //deben estar marcadas las dos opciones del select
                envio= true;
                array.addParametro('id_combo',id_combo);
                array.addParametro('id_vigencia',id_vigencia);
            }
            else
                envio = false; 

    break;
    case 4://solo vigencia
            id_vigencia=id_params;
            array.addParametro('id_vigencia',id_vigencia);
            envio=true;
    break;


   }
       //var texto = "Opciones Seleccionadas: ";

//alert('combo '+id_combo+" vigencia "+id_vigencia);
    array.addParametro('id_form',id_form);
    array.addParametro('id_req_item',id_it);
    array.addParametro('id_persona',id_persona);
    array.addParametro('condicion',condicion);
    array.addParametro('import','clases.requisitos_cargos.RequisitosCargos');
    if(envio==true)
        xajax_Loading(array.getArray());
}
else
$('#valores_form_'+id_it).hide();
    //$('#combos_form_'+id_it).show();
    }  
