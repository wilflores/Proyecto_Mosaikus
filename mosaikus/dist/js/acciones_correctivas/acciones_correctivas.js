
function init_tabla(){
        $('.ver-mas').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
            $('#myModal-Ventana-Titulo').html('');
            $('#myModal-Ventana').modal('show');
        });
        
        $('.ver-reporte').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            window.open('pages/acciones_correctivas/reporte_ac_pdf.php?id='+id,'_blank');
        });
}

function init_filtrar(){
        
        $( "#b-responsable_analisis" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); 
        $( "#b-id_responsable_segui" ).select2({
                                            placeholder: "Selecione el revisor",
                                            allowClear: true
                                          }); 
        $("#b-fecha_generacion-desde").datepicker();
        $("#b-fecha_acordada-desde").datepicker();;
        $("#b-fecha_realizada-desde").datepicker();
        $("#b-fecha_generacion-hasta").datepicker();
        $("#b-fecha_acordada-hasta").datepicker();
        $("#b-fecha_realizada-hasta").datepicker();
        
        PanelOperator.initPanels("");
        ScrollBar.initScroll();
        init_filtro_rapido();
        init_filtro_ao_simple();
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
    
    function cargar_autocompletado(){   
        $( "#responsable_analisis" ).select2({
            placeholder: "Selecione el revisor",
            allowClear: true
          }); 
          $( "#responsable_desvio" ).select2({
            placeholder: "Selecione el elaborador",
            allowClear: true
          });    
          $( "#reportado_por" ).select2({
            placeholder: "Selecione el elaborador",
            allowClear: true
          });    
          
          
    }
    
    function filtrar_arbol(){
          array = new XArray();
          array.setObjeto('ArbolOrganizacional','buscar_hijos');
          array.addParametro('import','clases.organizacion.ArbolOrganizacional');                      
          array.addParametro('b-id_organizacion',$('#b-id_organizacion_aux').val());
          xajax_Loading(array.getArray());
    }
    
    function filtrar_proceso(){
          array = new XArray();
          array.setObjeto('ArbolProcesos','buscar_hijos');
          array.addParametro('import','clases.arbol_procesos.ArbolProcesos');                      
          array.addParametro('b-id_proceso',$('#b-id_proceso_aux').val());
          xajax_Loading(array.getArray());
    }

    function nuevo_AccionesCorrectivas(){
            array = new XArray();
            array.setObjeto('AccionesCorrectivas','crear');
            array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
            xajax_Loading(array.getArray());
    }

    function validar(doc){    
        if ($('#fecha_acordada').val() != ''){ 
            $('#id_responsable_segui').attr('data-validation',"required");
        }
        else {
            $('#id_responsable_segui').removeAttr('data-validation');
        }  
        
        if($('#idFormulario').isValid()) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('AccionesCorrectivas','guardar');
            else
                array.setObjeto('AccionesCorrectivas','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.getForm('idFormulario');
            array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarAccionesCorrectivas(id){
        array = new XArray();
        array.setObjeto('AccionesCorrectivas','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
        xajax_Loading(array.getArray());
    }


    function eliminarAccionesCorrectivas(id){
        if(confirm("¿Desea Eliminar el AccionesCorrectivas Seleccionado?")){
            array = new XArray();
            array.setObjeto('AccionesCorrectivas','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
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
        array.setObjeto('AccionesCorrectivas','buscar');
        array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verAccionesCorrectivas(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verAccionesCorrectivas.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function verAcciones(id){
        array = new XArray();
        array.setObjeto('AccionesAC','indexAccionesAC');
        array.addParametro('id_ac',id);
        array.addParametro('id_accion',id);
        array.addParametro('import','clases.acciones_ac.AccionesAC');
        xajax_Loading(array.getArray());
    }
    
    function EvidenciasMuestra(id){
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidenciaVisualizacion');
        array.addParametro('id_accion',id);
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }
    
    
    function adminEvidenciasVer(id){
        $('#myModal-Ventana').modal('hide');
        //$('#myModal-Evidencias').modal('show');
        array = new XArray();
        array.setObjeto('AccionesEvidencia','indexAccionesEvidencia');
        array.addParametro('id_accion_correctiva',id);
        array.addParametro('import','clases.acciones_evidencia.AccionesEvidencia');
        xajax_Loading(array.getArray());
    }
    
    
    
    