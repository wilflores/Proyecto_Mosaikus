
function init_tabla_reporte_reg(){
    $('#tblRegistros > tbody > tr').addClass('cursor-pointer');
    $('#tblRegistros > tbody > tr').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('tok');
            array = new XArray();
            array.setObjeto('Registros','ver_visualiza');
            array.addParametro('import','clases.registros.Registros');
            
            array.addParametro('id',id);     
            var cadena = window.location + '';
            if (cadena.indexOf('index.php')!=-1) {
                window.location = 'index.php#detail-content-aux';
            }
            else{
                window.location = 'portal.php#detail-content-aux';
            }
            
            xajax_Loading(array.getArray());
        });
}

function r_init_filtrar_reporte(){
        
        document.getElementById('contenido-aux').style.display='';
        document.getElementById('contenido-form-aux').style.display='none';
        document.getElementById('contenido-form-aux').innerHTML='';
        document.getElementById('contenido').style.display='none';
        document.getElementById('contenido-form').style.display='none';
        $('#contenido-form-aux').parent().hide();
        $('#contenido-aux').parent().show();
        $('#contenido').parent().hide();
        $('#contenido-form').parent().hide();
        
        init_tabla_reporte_reg();
        PanelOperator.initPanels("-aux");
        ScrollBar.initScroll();
        r_init_filtro_rapido();
        PanelOperator.resize();
        
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
        array.setObjeto('Registros','buscar_reporte');
        array.addParametro('import','clases.registros.Registros');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }