


function r_init_filtrar(){
        document.getElementById('contenido-aux').style.display='';
        document.getElementById('contenido-form-aux').style.display='none';
        document.getElementById('contenido-form-aux').innerHTML='';
        document.getElementById('contenido').style.display='none';
        document.getElementById('contenido-form').style.display='none';
        $('#contenido-form-aux').parent().hide();
        $('#contenido-aux').parent().show();
        $('#contenido').parent().hide();
        $('#contenido-form').parent().hide();
    
        //init_tabla_reporte_reg();
        PanelOperator.initPanels("-aux");
        ScrollBar.initScroll();
        r_init_filtro_rapido();
}
    
    function r_filtrar_mostrar_colums(){
        var colums = '2-';
         $('.r-checkbox-mos-col').each(function() {
                if (this.checked){
                    colums = colums + this.value + '-';
                }
         });
         colums = colums.substring(0, colums.length - 1);
         $('#r-mostrar-col').val(colums);
         verPagina_aux($('#r-pag_actual').val(),1);
         $('#r-myModal-Mostrar-Colums').modal('hide');
                  
    }
    
    function r_link_titulos(valor){
        if (valor == $('#r-corder').val()){
            if ($('#r-sorder').val()== 'asc')
                $('#r-sorder').val('desc');
            else 
                $('#r-sorder').val('asc');
        }
        else
            $('#r-sorder').val('desc');
        $('#r-corder').val(valor);
        verPagina_aux(1,1);
    }
    
function r_exportarExcel(){
    var params =  getForm('r-busquedaFrm');
    //window.open('pages/' +  document.getElementById("modulo_actual").value + '/exportarExcel.php?campo='+document.getElementById("campo").value + '&valor=' + document.getElementById("valor").value + '&corder=' + document.getElementById("corder").value + '&sorder=' + document.getElementById("sorder").value,null,'toolbar=no, location=no, menubar=no, width=600,height=400');
    window.open('pages/registros/exportarExcel.php?'+params,null,'toolbar=no, location=no, menubar=no, width=600,height=400');
}

function r_marcar_desmarcar_checked_columns(checked){
    
        if(checked) { // check select status
            $('.r-checkbox-mos-col').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.r-checkbox-mos-col').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }       
}

    function nuevo_Registros(){
            array = new XArray();
            array.setObjeto('Registros','crear');
            array.addParametro('import','clases.registros.Registros');
            xajax_Loading(array.getArray());
    }
    
    function fomatear_rut(value){
        $('#id_personal').val($.Rut.formatear(value));
    }
    
    function r_cargar_autocompletado(){
        //$.validator.addMethod('rut', function(value, element) {
        //    return this.optional(element) || $.Rut.validar(value);
        //  }, 'Este campo debe ser un rut valido.');
          
          $.formUtils.addValidator({
            name : 'rut',
            validatorFunction : function(value, $el, config, language, $form) {                
              
                    return $.Rut.validar(value);              
            },
            errorMessage : 'Este campo debe ser un rut valido.',
            errorMessageKey: 'badEvenNumber'
          });
          $.formUtils.addValidator({
            name : 'sololetras',
            validatorFunction : function(value, $el, config, language, $form) {                 
              if (value.match(/^[a-z,A-Z," "]+$/))  
                    return true;
              return false;
            },
            errorMessage : 'Debe ingresar solo caracteres validos.',
            errorMessageKey: 'badEvenNumber'
          });
    }

    function r_validar(doc){
        var arbolsel='1';
        cad=document.getElementById('arbolesO').value;
        var arbolO = cad.split(",");
        for (var i=0; i<arbolO.length; i++) 
            { if (document.getElementById('nodos_'+arbolO[i]).value=='') 
                arbolsel='';
               // alert('Or'+arbolO[i]+document.getElementById('nodos_'+arbolO[i]).value)
            }
        cad=document.getElementById('arbolesP').value;
        var arbolP = cad.split(",");
        for (var i=0; i<arbolP.length; i++) 
            { if(document.getElementById('nodosp_'+arbolP[i]).value=='')
                arbolsel='';
                //alert('Pr'+arbolP[i]+document.getElementById('nodosp_'+arbolP[i]).value)
            }
            
        if(arbolsel=='1'){    
            if($('#r-idFormulario').isValid()) {
                $( "#btn-guardar" ).html('Procesando..');
                $( "#btn-guardar" ).prop( "disabled", true );
                array = new XArray();
                if (doc.getElementById("r-opc").value == "new")
                    array.setObjeto('Registros','guardar');
                else
                    array.setObjeto('Registros','actualizar');
                array.addParametro('permiso',document.getElementById('permiso_modulo').value);
                array.getForm('r-idFormulario');
                array.addParametro('import','clases.registros.Registros');
                xajax_Loading(array.getArray());
            }else{

            }
        }
    else
        VerMensaje('error','Debe marcar al menos una opcion del arbol');
    }

    function editarRegistros(id){
        array = new XArray();
        array.setObjeto('Registros','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.registros.Registros');
        xajax_Loading(array.getArray());
    }


    function eliminarRegistros(id){
        if(confirm("¿Desea Eliminar el Registros Seleccionado?")){
            array = new XArray();
            array.setObjeto('Registros','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.registros.Registros');
            xajax_Loading(array.getArray());
        }
    }
    
    function r_filtrar_listado(){
        verPagina_aux(1,document);
        $('#r-myModal-Filtro').modal('hide');
        $('#r-a-myModal-Filtro').hide();
        $('#r-a-myModal-Filtro-des').show();
    }
    
    function r_activar_filtrar_listado(){
        $('#r-busquedaFrm').each (function(){
            this.reset();
        });
        $('#r-myModal-Filtro').modal('hide');
        $('#r-a-myModal-Filtro').show();
        $('#r-a-myModal-Filtro-des').hide();
        //if ($('#b-iframe').length)
        //    $('#b-iframe').attr('src', 'pages/personas/emb_jstree_single.php?' + Math.random() * (1000 - 1)) ;
        verPagina_aux(1,document);
    }

    function verPagina_aux(pag,doc){        
        array = new XArray();
        if (doc== null)
        {
             $('form')[0].reset();             
        }
        array.getForm('r-busquedaFrm'); 
        if ((isNaN(document.getElementById("r-reg_por_pag").value) == true) || (parseInt(document.getElementById("r-reg_por_pag").value) <= 0)){
            array.addParametro('reg_por_pagina', 10);
            document.getElementById("r-reg_por_pag").value = 10
        }
        else
        {
            array.addParametro('reg_por_pagina', document.getElementById("r-reg_por_pag").value);
        }
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',pag);
        array.setObjeto('Registros','buscar');
        array.addParametro('import','clases.registros.Registros');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verRegistros(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verRegistros.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
    function cancelar_archivo_reg(){
        document.getElementById('r-info_archivo_adjunto').style.display = 'none';
        document.getElementById('r-fileUpload2').value = '';
        document.getElementById('r-tabla_fileUpload').style.display = '';
        document.getElementById('r-estado_actual').value = '';
    }
    
    function cargar_archivo_reg(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('r-fileUpload2');
            formData.append('fileUpload',fileInput.files[0]);
            //formData.append("nombre_doc", $('#nombre_doc').val());
            formData.append("codigo_doc", $('#r-Codigo_doc').val());
            //formData.append("version", $('#version').val());
            $('#r-estado').show();
            $.ajax({
                url: "pages/registros/uploadFileOtro2_vis.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false
            })
                .done(function(res){
                   respuesta = $.parseJSON(res);
                   if (respuesta[0].exito == 1) {
                        //alert() ;
                        $('#r-tabla_fileUpload').hide();
                        $('#r-info_nombre').val(respuesta[0].info_nombre);
                        $('#r-filename').val(respuesta[0].filename);
                        $('#r-tamano').val(respuesta[0].tamano);
                        $('#r-tipo_doc').val(respuesta[0].tipo);
                        $('#r-estado_actual').val(respuesta[0].estado_actual);
                        $('#r-info_archivo_adjunto').show();
                        //$('#Codigo_doc_vis').val(respuesta[0].codigo_doc);
                        $('#r-nombre_doc').val(respuesta[0].nombre_doc);
                        //$('#version_vis').val(respuesta[0].version_doc);
                   }
                   else{
                       VerMensaje('error',respuesta[0].msj);
                   }
                   $('#r-estado').hide();
                });
    }