function init_graficos_atrasos(){
    var serie_plazo = $('#data-grafico-bar-2').val().split(";");
    grafico_barra = "$('#container-grafico-bar-2').highcharts({"+
        "chart: {"+
            "type: 'bar'"+
        "},"+
        "title: {"+
            "text: 'Acciones Correctivas'"+
        "},"+        
        "subtitle: {"+
            "text: '"+ $('#subtitle-grafico-bar-2').val()+"'"+
        "},"+
        "tooltip: {"+
//            "formatter: function () {"+
//                "var s = '<b>' + this.x + ' - Total AC ' + '';"+
//                "var i = 0;"+
//                "$.each(this.points, function () {"+
//                    "if (i == 0){"+
//                       "s += this.point.total_ac + '</b>';"+
//                       "i = i + 1;"+
//                     "}"+
//                    "s += '<br/><span style=\"color:'+ this.series.color +'\">\u25CF</span> ' + this.series.name + ': ' +"+
//                     "   this.point.valor + ' - ' + this.y + '%  <b>' +  '</b>';"+
//                "});"+
//
//                "return s;"+
//            "},"+
            "shared: true"+
        "},"+
        "xAxis: {"+
            "categories: ["+$('#nombre-colum-grafico-bar-2').val()+"]"+
        "},"+
        "yAxis: {"+
            "min: 0,"+
            "title: {"+
                "text: 'N° de Verificaciones Atrasadas'"+
            "}"+
           
        "},"+
        "legend: {"+
            "reversed: true"+
        "},"+
        "plotOptions: {"+
            "series: {"+
                "stacking: 'normal'"+
            "}"+
        "},"+
        "series: [{"+            
            "name: 'Atrasados',"+
            "color: '#ff7272',"+
            "data: ["+serie_plazo[0]+"]"+
        "}]"+
    "});" ;
    eval(grafico_barra); 
    
    var serie_plazo = $('#data-grafico-bar-3').val().split(";");
    grafico_barra = "$('#container-grafico-bar-3').highcharts({"+
        "chart: {"+
            "type: 'bar'"+
        "},"+
        "title: {"+
            "text: 'Acciones Correctivas'"+
        "},"+        
        "subtitle: {"+
            "text: '"+ $('#subtitle-grafico-bar-3').val()+"'"+
        "},"+
        "tooltip: {"+
//            "formatter: function () {"+
//                "var s = '<b>' + this.x + ' - Total AC ' + '';"+
//                "var i = 0;"+
//                "$.each(this.points, function () {"+
//                    "if (i == 0){"+
//                       "s += this.point.total_ac + '</b>';"+
//                       "i = i + 1;"+
//                     "}"+
//                    "s += '<br/><span style=\"color:'+ this.series.color +'\">\u25CF</span> ' + this.series.name + ': ' +"+
//                     "   this.point.valor + ' - ' + this.y + '%  <b>' +  '</b>';"+
//                "});"+
//
//                "return s;"+
//            "},"+
            "shared: true"+
        "},"+
        "xAxis: {"+
            "categories: ["+$('#nombre-colum-grafico-bar-3').val()+"]"+
        "},"+
        "yAxis: {"+
            "min: 0,"+
            "title: {"+
                "text: 'N° de Verificaciones Atrasadas'"+
            "}"+
           
        "},"+
        "legend: {"+
            "reversed: true"+
        "},"+
        "plotOptions: {"+
            "series: {"+
                "stacking: 'normal'"+
            "}"+
        "},"+
        "series: [{"+            
            "name: 'Atrasados',"+
            "color: '#ff7272',"+
            "data: ["+serie_plazo[0]+"]"+
        "}]"+
    "});" ;
    eval(grafico_barra); 
}

function init_graficos(){
    //alert('Entrro');
    var serie_plazo = $('#data-grafico-bar').val().split(";");
    //alert(serie_plazo[2]);
    /*
     ,"+
                "dataLabels:{"+
                "enabled:true,"+
                "formatter:function() {"+
                    "var pcnt = (this.y / 200) * 100;"+
                    "return Highcharts.numberFormat(pcnt) + '%';"+
                "}"+
            "}"+
     */
    grafico_barra = "$('#container-grafico-bar').highcharts({"+
        "chart: {"+
            "type: 'bar'"+
        "},"+
        "title: {"+
            "text: 'Acciones Correctivas'"+
        "},"+        
        "subtitle: {"+
            "text: '"+ $('#subtitle-grafico-bar').val()+"'"+
        "},"+
        "tooltip: {"+
            "formatter: function () {"+
                "var s = '<b>' + this.x + ' - Total AC ' + '';"+
                "var i = 0;"+
                "$.each(this.points, function () {"+
                    "if (i == 0){"+
                       "s += this.point.total_ac + '</b>';"+
                       "i = i + 1;"+
                     "}"+
                    "s += '<br/><span style=\"color:'+ this.series.color +'\">\u25CF</span> ' + this.series.name + ': ' +"+
                     "   this.point.valor + ' - ' + this.y + '%  <b>' +  '</b>';"+
                "});"+

                "return s;"+
            "},"+
            "shared: true"+
        "},"+
        "xAxis: {"+
            "categories: ["+$('#nombre-colum-grafico-bar').val()+"]"+
        "},"+
        "yAxis: {"+
            "min: 0,"+
            "title: {"+
                "text: 'Porcentaje de Acciones Correctivas'"+
            "}"+
           
        "},"+
        "legend: {"+
            "reversed: true"+
        "},"+
        "plotOptions: {"+
            "series: {"+
                "stacking: 'normal'"+
            "}"+
        "},"+
        "series: [{"+
            "name: 'Realizado',"+
            "color: '#00950e',"+
            "data: ["+serie_plazo[3]+"]"+
        "}, {"+
            "name: 'Realizado con atraso',"+
            "color: '#00d714',"+
            "data: ["+serie_plazo[2]+"]"+
        "},{"+
            "name: 'En Plazo',"+
            "color: '#63e46f',"+
            "data: ["+serie_plazo[1]+"]"+
        "}, {"+
            "name: 'Atrasados',"+
            "color: '#ff7272',"+
            "data: ["+serie_plazo[0]+"]"+
        "}]"+
    "});" ;
    eval(grafico_barra); 
    var grafico_linea ='';
    grafico_linea = "$('#container-grafico-linea').highcharts({"+
        "title: {"+
            "text: 'Acciones Correctivas',"+
            "x: -20 "+
        "},"+
        "subtitle: {"+
            "text: '"+ $('#subtitle-grafico-linea').val()+"',"+
            "x: -20"+
        "},"+
        "xAxis: {"+
            "categories: ["+$('#nombre-colum-grafico-linea').val()+"]"+
        "},"+
        "yAxis: {"+
            "title: {"+
                "text: 'Cumplimiento (%)'"+
            "},"+
            "plotLines: [{"+
                "value: 0,"+
                "width: 1,"+
                "color: '#808080'"+
            "}]"+
        "},       "+ 
        "tooltip: {"+
            "valueSuffix: '°C',"+
            "formatter: function () {"+
                "var s = '<b>' + this.x  + '</b>';"+

                "$.each(this.points, function () {"+
                    "s += '<br/><span style=\"color:'+ this.series.color +'\">\u25CF</span> ' + this.series.name + ': ' +"+
                     "   this.point.valor + ' - ' + this.y + '%  <b>' +  '</b>';"+
                "});"+

                "return s;"+
            "},"+
            "shared: true"+
        "},"+
        "legend: {"+
            "layout: 'vertical',"+
            "align: 'center',"+
            "verticalAlign: 'bottom',"+
            "borderWidth: 0"+
        "},"+
        "series: [" + $('#data-grafico-linea').val()+ "]"+
    "});";   
    eval(grafico_linea);
    var grafico_linea_uni = "$('#container-grafico-linea-uni').highcharts({"+
        "title: {"+
            "text: 'Acciones Correctivas',"+
            "x: -20 "+
        "},"+
        "subtitle: {"+
            "text: '"+ $('#subtitle-grafico-linea-uni').val()+"',"+
            "x: -20"+
        "},"+
        "xAxis: {"+
            "categories: ["+$('#nombre-colum-grafico-linea-uni').val()+"]"+
        "},"+
        "yAxis: {"+
            "title: {"+
                "text: 'N° Acciones Correctivas'"+
            "},min: 0,"+
            "plotLines: [{"+
                "value: 0,"+
                "width: 1,"+
                "color: '#808080'"+
            "}]"+
        "},       "+ 
        "tooltip: {"+
            "valueSuffix: '',"+
//            "formatter: function () {"+
//                "var s = '<b>' + this.x + '</b>';"+
//
//                "$.each(this.points, function () {"+
//                    "s += '<br/><span style=\"color:'+ this.series.color +'\">\u25CF</span> ' + this.series.name + ': ' +"+
//                        "   this.point.valor + ' - ' + this.y + '%  <b>' +  '</b>';"+
//                "});"+
//
//                "return s;"+
//            "},"+
            "shared: true"+
        "},"+
        "legend: {"+
            "layout: 'vertical',"+
            "align: 'center',"+
            "verticalAlign: 'bottom',"+
            "borderWidth: 0"+
        "},"+
        "series: [" + $('#data-grafico-linea-uni').val()+ "]"+
    "});";   
    eval(grafico_linea_uni);
}

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
        $('#b-fecha_generacion-desde').datepicker();
        $('#b-fecha_acordada-desde').datepicker();;
        $('#b-fecha_realizada-desde').datepicker();
        $('#b-fecha_generacion-hasta').datepicker();
        $('#b-fecha_acordada-hasta').datepicker();
        $('#b-fecha_realizada-hasta').datepicker();
        
        $('#tabs-hv').tab();
        $('#tabs-hv a:first').tab('show'); 
        
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
          $( "#id_responsable_segui" ).select2({
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
    
    function filtrar_grafico(){
        array = new XArray();
//        if (doc== null)
//        {
//             $('form')[0].reset();             
//        }
        array.getForm('busquedaFrm-Filtro'); 
        
        //array.addParametro('pag',pag);
        array.setObjeto('AccionesCorrectivas','buscarReporte');
        array.addParametro('import','clases.acciones_correctivas.AccionesCorrectivas');
        $('#MustraCargando').show();
        xajax_Loading(array.getArray());
    }
    
    function filtrar_proceso(){
          array = new XArray();
          array.setObjeto('ArbolProcesos','buscar_hijos');
          array.addParametro('import','clases.arbol_procesos.ArbolProcesos');                      
          array.addParametro('b-id_proceso',$('#b-id_proceso_aux').val());
          xajax_Loading(array.getArray());
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
    
    