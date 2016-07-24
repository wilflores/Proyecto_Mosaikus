function init_filtrar(){        
         $('#b-fecha-desde').datetimepicker();
            $('#b-fecha-hasta').datetimepicker();
            $('#b-fecha_termino-desde').datetimepicker();
            $('#b-fecha_termino-hasta').datetimepicker();
    
        PanelOperator.initPanels("");
        ScrollBar.initScroll();
        init_filtro_rapido();
}


function cargar_autocompletado(){       
        $.formUtils.addValidator({
            name : 'fechaMenor',
            validatorFunction : function(value, $el, config, language, $form) { 
                if ((value.length > 0) && ($('#fecha_termino').val().length > 0)){                    
                    var array_fecha = value.split("/");

                    var dia=array_fecha[0];
                    var mes=(array_fecha[1]-1);
                    var ano=(array_fecha[2]);
                    var fecha = new Date(ano,mes,dia);

                    array_fecha = $('#fecha_termino').val().split("/");

                    dia=array_fecha[0];
                    mes=(array_fecha[1]-1);
                    ano=(array_fecha[2]);
                    var fecha_termino = new Date(ano,mes,dia);
                    if (fecha<=fecha_termino){
                        return true;
                    }
                    return false;
                }                  
              return true;
            },
            errorMessage : 'Fecha de inicio no puede ser mayor a fecha termino.',
            errorMessageKey: 'badEvenNumber'
          });
        $( "#cod_curso" ).select2({
            placeholder: "Selecione el curso",
            allowClear: true
          });

          $( "#cod_emp_relator" ).select2({
            placeholder: "Selecione el relator",
            allowClear: true
          });        
          $('.pasar').click(function() { !$('#origen option:selected').remove().appendTo('#destino'); total_per_sel();});  
            $('.quitar').click(function() { !$('#destino option:selected').remove().appendTo('#origen'); total_per_sel();});
            $('.pasartodos').click(function() { $('#origen option').each(function() { $(this).remove().appendTo('#destino'); }); total_per_sel();});
            $('.quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); total_per_sel();});
            $('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });
            
            $('#origen').on('dblclick', 'option', function() {
                !$('#origen option:selected').remove().appendTo('#destino');
                total_per_sel();
            });            
            
            String.prototype.capitalize = function() {
                return this.charAt(0).toUpperCase() + this.slice(1);
            }
            
            $('#b-id_personal').keyup(function() {
                procesar_filtrar_arbol();
            });
            
            $('#b-nombres').keyup(function() {
                procesar_filtrar_arbol();
            });
            $('#b-apellido_paterno').keyup(function() {
                procesar_filtrar_arbol();
            });
            $('#b-apellido_materno').keyup(function() {
                procesar_filtrar_arbol();
            });
    }
    
    function total_per_sel(){
        $("#total-pers-sel").html($('#destino option').length + ' Personas seleccionadas.');
    }
    
    function agregar_esp(){
        //fomatear_rut(this.value);
        var i = $('#num_items_esp').val();
        i = parseInt(i); 
        var datos = new Array();
        for(j=1;j<=i;j++){
            if ( $("#id_pers_" + j).length > 0 ) {
                datos.push($("#id_pers_" + j).val());
            }
        }
        $("#destino option").each(function()
        {
            if (!($.inArray($(this).val(), datos) > -1)){
                i = $('#num_items_esp').val();
                i = parseInt(i) + 1;        
                var html = '<tr id="tr-esp-' + i + '" class="DatosGrilla" onmouseout="TRMarkOut(this);" onmouseover="TRMarkOver(this);">'; 
                html = html + '<td align="center">';
                html = html + '<a href="' + i + '" id="eliminar_esp_' + i + '"><i class="icon icon-remove"></i></a>';  
                html = html + '<input type="hidden" id="id_pers_' + i + '" name="id_pers_' + i + '" value="' + $(this).val() + '">'; 
                html = html + '</td>' ; 
                html = html + '<td>' + $.Rut.formatear($(this).attr('rut')) + '</td>';
                html = html + '<td>' + ($(this).attr('nom')) + ' ' + ($(this).attr('ap_p')) + ' ' + ($(this).attr('ap_m')) + '</td>';
                html = html + '<td><span class="">'+
                                                '    Si '+
                                                '    <input id="aprobo_' + i + '" value="S" class="CajaTexto" type="radio" checked="checked" name="aprobo_' + i + '">'+
                                                    ' No '+
                                                    '<input id="aprobo_' + i + '" value="N" class="CajaTexto" type="radio" name="aprobo_' + i + '">'+
                                                '</span></td>';
                if ($('#tipo_curso').val()=='S'){
                    html = html + '<td><input type="text" id="nota_' + i + '" name="nota_' + i + '" maxlength="4" size="6" class="form-box" value="" data-validation="number"></td>';         
                }
                else{
                    html = html + '<td><input type="text" value="No Aplica" readonly="readonly" maxlength="4" size="6"></td>';         
                }
                html = html + '<td><input type="text" id="asis_' + i + '" name="asis_' + i + '" maxlength="4" size="6" class="form-box" value="" data-validation="number" data-validation-allowing="range[1;100]"> %</td>';         
                html = html + '<td><textarea id="obs_' + i + '" class="CajaTexto" name="obs_' + i + '" cols="30"></textarea>' + '</td>';
                html = html + '</tr>' ;      
                $("#table-pers-capa tbody").append(html);          

                $("#eliminar_esp_" + i).click(function(e){ 
                    e.preventDefault();
                    var id = $(this).attr('href');
                    $("#destino option:selected").prop("selected", false);
                    $("#destino option[value='" + $('#id_pers_' + i).val() + "']").prop("selected", true);

                    !$('#destino option:selected').remove().appendTo('#origen');

                    $('tr-esp-' + id).remove();
                    var parent = $(this).parents().parents().get(0);
                        $(parent).remove();
                    total_per_sel();
                });        
                $('#num_items_esp').val(i);
            }
        });
        
        $.validate({
                            lang: 'es'  
                          });
        
    }
    
    function validar_p2(){  
        if ($('#destino option').length){
            agregar_esp();
            $('.nav-tabs a[href="#hv-blue"]').tab('show');
        }
        else{
            
        }
        
        /*
        var hora_min = $('#hora').val() + $('#min').val();
        if (hora_min > 0){
            $('#hh_min').val(hora_min);
        }
        
        if($('#idFormulario').isValid()) {
            $('.nav-tabs a[href="#hv-orange"]').tab('show');
            cargarPersonal();
        }else{
        
        }*/
    }
    
    function filtrar_arbol(){
          array = new XArray();
          array.setObjeto('ArbolOrganizacional','buscar_hijos');
          array.addParametro('import','clases.organizacion.ArbolOrganizacional');                      
          array.addParametro('b-id_organizacion',$('#b-id_organizacion').val());
          xajax_Loading(array.getArray());
    }
    
    function limpiar_arbol(){
        $('#nivel').val('');
        $('#desc-arbol').html('');
        procesar_filtrar_arbol();
        $('#myModal-Filtrar-Arbol').modal('hide');
    }
    
    function procesar_filtrar_arbol(){
        var ban_sap;
        var ban_nom;
        var ban_pap;
        var ban_rut;
        var ban_arb;        
        var email;
        
        var myarr = $('#nivel').val().split(",");
        
        $("#origen option").each(function()
        {
            ban_arb = ban_nom = ban_pap = ban_pap = ban_rut = ban_sap = 0;
            if (($('#nivel').val().length==0)||(myarr.indexOf($(this).attr('arb'))>=0)) 
            {
                ban_arb = 1;
            }
            email = $('#b-apellido_materno').val();
            if ((email.length== 0)||($(this).attr('ap_m').indexOf(email)!=-1)||($(this).attr('ap_m').indexOf(email.capitalize())!=-1)) {
                ban_sap = 1;
            }
            email = $('#b-apellido_paterno').val();
            if ((email.length== 0)||($(this).attr('ap_p').indexOf(email)!=-1)||($(this).attr('ap_p').indexOf(email.capitalize())!=-1)) {
                ban_pap = 1;
            }
            email = $('#b-nombres').val();
            
            if ((email.length== 0)||($(this).attr('nom').indexOf(email)!=-1)||($(this).attr('nom').indexOf(email.capitalize())!=-1)) {
                
                
                ban_nom = 1;
            }
            email = $('#b-id_personal').val();
            if ((email.length== 0)||$(this).attr('rut').indexOf(email)!=-1) {
                ban_rut = 1;
            }
//            alert($(this).attr('nom'));
//            alert('arb ' + ban_arb);
//            alert('nom ' + ban_nom);
//            alert('pap ' + ban_pap);
//            alert('rut ' + ban_rut);
//            alert('sap ' + ban_sap);
            if ((ban_arb == 1) && (ban_nom == 1) && (ban_pap == 1) && (ban_rut == 1) && (ban_sap == 1)){
                $(this).show();
            }
            else $(this).hide();
        });
        
        $('#MustraCargando').hide();
    }
    
    function cargarPersonal(){
        array = new XArray();        
        {
            array.addParametro('reg_por_pagina', 100000);
        }
        //array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('pag',1);
        array.setObjeto('Personas','buscar_personas');
        array.addParametro('import','clases.personas.Personas');
         $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }
    
    function tipo_curso(){
        array = new XArray();        
        
        //array.addParametro('permiso',document.getElementById('permiso_modulo').value);
       
        array.setObjeto('Cursos','tipo_curso');
        array.addParametro('id',$('#cod_curso').val());
        array.addParametro('import','clases.cursos.Cursos');        
        xajax_Loading(array.getArray());
    }
    
    function cancelar_archivo_otro(){
        document.getElementById('info_archivo_adjunto').style.display = 'none';
        document.getElementById('fileUpload2').value = '';
        document.getElementById('tabla_fileUpload').style.display = '';
        document.getElementById('estado_actual').value = '';
    }
    
    function cargar_archivo_otro(){
        //$('#fileUploadOtro').val($('#fileUpload2').val());
        var formData = new FormData(document.getElementById("formuploadajax"));
            //formData.append("dato", "valor"); 
            var fileInput = document.getElementById('fileUpload2');
            formData.append('fileUpload',fileInput.files[0]);
            formData.append("dato", "valor");
            $('#estado').show();
            $.ajax({
                url: "pages/personal_capacitacion/uploadFileOtro2.php",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
	     processData: false,
             xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload && progressFn) {
                        myXhr.upload.addEventListener('progress', function(prog) {
                            var value = ~~((prog.loaded / prog.total) * 100);
                             var $bar = $('.progress');

                                {
                                    $bar.width(value*250/100);
                                }
                                $bar.text(value*250/100 + "%");

//                            // if we passed a progress function
//                            if (progressFn && typeof progressFn == "function") {
//                                progressFn(prog, value);
//
//                                // if we passed a progress element
//                            } else if (progressFn) {
//                                $(progressFn).val(value);
//                            }
                        }, false);
                    }
                    return myXhr;
                },
            })
                .done(function(res){
                   respuesta = $.parseJSON(res);
                   if (respuesta[0].exito == 1) {
                        //alert() ;
                        $('#tabla_fileUpload').hide();
                        $('#info_nombre').html(respuesta[0].info_nombre);
                        $('#filename').val(respuesta[0].filename);
                        $('#tamano').val(respuesta[0].tamano);
                        $('#tipo_doc').val(respuesta[0].tipo);
                        $('#estado_actual').val(respuesta[0].estado_actual);
                        $('#info_archivo_adjunto').show();
                   }
                   else{
                       alert(respuesta[0].msj);
                   }
                   $('#estado').hide();
                });
    }
    
    function filtrar_mostrar_colums(){
        var colums = '1-';
         $('.checkbox-mos-col').each(function() {
                if (this.checked){
                    colums = colums + this.value + '-';
                }
         });
         colums = colums.substring(0, colums.length - 1);
         $('#mostrar-col').val(colums);
         verPagina(1,1);
         $('#myModal-Mostrar-Colums').modal('hide');
         
    }

    function nuevo_PersonalCapacitacion(){
            array = new XArray();
            array.setObjeto('PersonalCapacitacion','crear');
            array.addParametro('import','clases.personal_capacitacion.PersonalCapacitacion');
            xajax_Loading(array.getArray());
    }
    
    function validar_p1(){        
        var hora_min = $('#hora').val() + $('#min').val();
        if (hora_min > 0){
            $('#hh_min').val(hora_min);
        }
        
        if($('#idFormulario').isValid()) {
            $('.nav-tabs a[href="#hv-orange"]').tab('show');
            //cargarPersonal();
            tipo_curso();
        }else{
        
        }
    }

    function validar(doc){        
        var hora_min = $('#hora').val() + $('#min').val();
        if (hora_min > 0){
            $('#hh_min').val(hora_min);
        }
        
        
        if (($('#idFormulario').isValid())&&($('#idFormulario-Data').isValid())) {
            $( "#btn-guardar" ).html('Procesando..');
            $( "#btn-guardar" ).prop( "disabled", true );
            array = new XArray();
            if (doc.getElementById("opc").value == "new")
                array.setObjeto('PersonalCapacitacion','guardar');
            else
                array.setObjeto('PersonalCapacitacion','actualizar');
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('nombre_curso', $('#cod_curso option:selected').text());
            array.getForm('idFormulario');
            array.getForm('idFormulario-Data');
            array.addParametro('import','clases.personal_capacitacion.PersonalCapacitacion');
            xajax_Loading(array.getArray());
        }else{
        
        }
    }

    function editarPersonalCapacitacion(id){
        array = new XArray();
        array.setObjeto('PersonalCapacitacion','editar');
        array.addParametro('id',id);
        array.addParametro('import','clases.personal_capacitacion.PersonalCapacitacion');
        xajax_Loading(array.getArray());
    }


    function eliminarPersonalCapacitacion(id){
        if(confirm("¿Desea Eliminar el PersonalCapacitacion Seleccionado?")){
            array = new XArray();
            array.setObjeto('PersonalCapacitacion','eliminar');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.personal_capacitacion.PersonalCapacitacion');
            xajax_Loading(array.getArray());
        }
    }
    
    function eliminarArchivoCapacitacion(id){
        if(confirm("¿Desea Eliminar el archivo Seleccionado?")){
            array = new XArray();
            array.setObjeto('PersonalCapacitacion','eliminar_archivo');
            array.addParametro('id',id);
            array.addParametro('permiso',document.getElementById('permiso_modulo').value);
            array.addParametro('import','clases.personal_capacitacion.PersonalCapacitacion');
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
        array.setObjeto('PersonalCapacitacion','buscar');
        array.addParametro('import','clases.personal_capacitacion.PersonalCapacitacion');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verPersonalCapacitacion(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verPersonalCapacitacion.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    