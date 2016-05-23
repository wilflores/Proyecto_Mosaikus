function  activar_desactivar_pest(){
    if ($('#formulario').is(':checked')){
        $('#tabs-form-reg').attr('data-toggle',"tab");
        $('.nav-tabs a[href="#hv-orange"]').tab('show');
    }
    else{
        $('#tabs-form-reg').removeAttr('data-toggle');
        $('.nav-tabs a[href="#hv-red"]').tab('show');
    }
}

function reporte_documentos_pdf(){
    var params =  getForm('busquedaFrm');
    window.open('pages/documentos/reporte_arbol_pdf.php?'+params,'_blank');
    
}


function limpiar_titulo(){
    var titulo = $('#div-titulo-mod').html();
    if (titulo.lastIndexOf("<br>")>(titulo.lastIndexOf("</label>"))){       
        $('#div-titulo-mod').html(titulo.substr(0, titulo.lastIndexOf("<br>")));
        $('.b-area_espejo').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-area_espejo').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-area_espejo').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });
         $('.b-mi-ocultar-publico').on('change', function (event) {
                                /*event.preventDefault();
                                var id = $(this).attr('tok');*/
                                 if( $(this).is(':checked') ){
                                    $('#b-ocultar-publico').val('1');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido seleccionado');*/
                                } else {
                                    $('#b-ocultar-publico').val('');
                                    verPagina(1,document);
                                    /*alert('El checkbox con valor ' + $(this).val() + ' ha sido deseleccionado');*/
                                }
                            });
    }
}

function init_documentos(){
    //alert('init_documentos');
    $( "#b-reviso" ).select2({
        placeholder: "Selecione el revisor",
        allowClear: true
      }); 
    $( "#b-elaboro" ).select2({
        placeholder: "Selecione el elaborador",
        allowClear: true
      }); 
    $( "#b-aprobo" ).select2({
        placeholder: "Selecione el aprobador",
        allowClear: true
      }); 
    $("#b-fecha-desde").datepicker();
    $("#b-fecha-hasta").datepicker();
    $("#b-fecha_rev-desde").datepicker();
    $("#b-fecha_rev-hasta").datepicker();
    if(($("#b-formulario").is(":checked"))) {
        $("#b-formulario").parent().parent().hide();
    }

    PanelOperator.initPanels("");
    ScrollBar.initScroll();
    PanelOperator.showSearch("");
    
    init_filtro_rapido();
    init_filtro_ao_simple();
    PanelOperator.resize();
    //$('.jstree-container').jstree();
}



function init_ver_registros()
{//alert('init_ver_registros');
    $('#a-ver-registros').on('click', function (event) {
            event.preventDefault();
            
            var id = $(this).attr('tok');
            array = new XArray();
            array.setObjeto('Registros','indexRegistrosListado');
            array.addParametro('titulo',$('#desc-mod-act').html());
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            array.addParametro('id',id);
            array.addParametro('import','clases.registros.Registros');
            xajax_Loading(array.getArray());                                
            //window.location = 'index.php#detail-content';
            
        });
}

function init_tabla_reporte(){
    //$('#tblDocumentos > tbody > tr').addClass('cursor-pointer');
    $('.ver-documento').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            array = new XArray();
            array.setObjeto('Documentos','ver_visualiza');
            array.addParametro('id',id);
            array.addParametro('import','clases.documentos.Documentos');
            var cadena = window.location + '';
            if (cadena.indexOf('index.php')!=-1) {
                window.location = 'index.php#detail-content';
            }
            else{
                window.location = 'portal.php#detail-content';
            }
            xajax_Loading(array.getArray());
        });
        
        $('.ver-registros').on('click', function (event) {
            event.preventDefault();
            
            var id = $(this).attr('tok');
            array = new XArray();
            array.setObjeto('Registros','indexRegistrosListado');
            array.addParametro('titulo',$('#desc-mod-act').html());
            array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);
            array.addParametro('id',id);
            array.addParametro('import','clases.registros.Registros');
            xajax_Loading(array.getArray());                                
            //window.location = 'index.php#detail-content';
            
        });
        
        $('.ver-mas').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            $('#myModal-Ventana-Cuerpo').html($('#ver-mas-'+id).val());
            $('#myModal-Ventana-Titulo').html('');
            $('#myModal-Ventana').modal('show');
        });
}

function ver_fuente(id,token){
    array = new XArray();
    array.setObjeto('Documentos','ver_fuente');
    array.addParametro('id',id);
    array.addParametro('import','clases.documentos.Documentos');
    xajax_Loading(array.getArray());
    //PanelOperator.showDetail('');    
}

function ver_visualiza(id,token){
    array = new XArray();
    array.setObjeto('Documentos','ver_visualiza');
    array.addParametro('id',id);
    array.addParametro('import','clases.documentos.Documentos');
    xajax_Loading(array.getArray());
    //PanelOperator.showDetail('');    
}



function MuestraFormulario(id){
        array = new XArray();
        array.setObjeto('Registros','indexRegistros');
        array.addParametro('titulo',$('#desc-mod-act').html());
        array.addParametro('modo',document.getElementById('modo').value);
            array.addParametro('cod_link',document.getElementById('cod_link').value);
        array.addParametro('id',id);
        array.addParametro('import','clases.registros.Registros');
        xajax_Loading(array.getArray());
    }



function cargar_autocompletado(){   
        $( "#reviso" ).select2({
            placeholder: "Selecione el revisor",
            allowClear: true
          }); 
          $( "#elaboro" ).select2({
            placeholder: "Selecione el elaborador",
            allowClear: true
          }); 
          $( "#aprobo" ).select2({
            placeholder: "Selecione el aprobador",
            allowClear: true
          }); 
      }
      
    function filtrar_mostrar_colums(){
        var colums = '2-';
        //alert(0000000000);
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

    function verPagina(pag,doc){
        //alert('buscar');
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
        array.setObjeto('Documentos','buscar_reporte');
        array.addParametro('import','clases.documentos.Documentos');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }

    function verDocumentos(id){
        var src = 'pages/' +  document.getElementById("modulo_actual").value + '/verDocumentos.php?id='+id;
        $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :true,
                    'type':'iframe'                    
                });        
        $("#ver_ficha_trabajador").trigger('click');        
    }
    
