
    
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

    function nuevo_WorkflowAcciones(){
            array = new XArray();
            array.setObjeto('WorkflowAcciones','crear');
            array.addParametro('import','clases.workflow_acciones.WorkflowAcciones');
            xajax_Loading(array.getArray());
    }

    function validar(doc){        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('WorkflowAcciones','guardar');
            else
                array.setObjeto('WorkflowAcciones','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.workflow_acciones.WorkflowAcciones');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarWorkflowAcciones(id){
        array = new XArray();
        array.setObjeto('WorkflowAcciones','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.workflow_acciones.WorkflowAcciones');
        xajax_Loading(array.getArray());
    }


    function eliminarWorkflowAcciones(id){
        if(confirm("¿Desea Eliminar el WorkflowAcciones Seleccionado?")){
            array = new XArray();
            array.setObjeto('WorkflowAcciones','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.workflow_acciones.WorkflowAcciones');
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
        array.setObjeto('WorkflowAcciones','buscar');
        array.addParametro('import','clases.workflow_acciones.WorkflowAcciones');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verWorkflowAcciones(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verWorkflowAcciones.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    function cargar_autocompletado(){   
        $( "#id_personal" ).select2({
            placeholder: "Selecione el usuario",
            allowClear: true
          }); 
          $( "#id_personal_wf" ).select2({
            placeholder: "Selecione el usuario secundario",
            allowClear: true
          });           
          $( "#id_personal_vaca" ).select2({
            placeholder: "Selecione el usuario en Vacaciones",
            allowClear: true
          });           
          $( "#email_alerta" ).select2({
            placeholder: "Selecione el correo de alertas",
            allowClear: true
          });           
    }
    function AsignaCorreo(combo,correo){
        if($("#"+combo).select2('data').text.split("=>")[1])
            $("#"+correo).val($("#"+combo).select2('data').text.split("=>")[1]);
        else
            $("#"+correo).val('');
    }