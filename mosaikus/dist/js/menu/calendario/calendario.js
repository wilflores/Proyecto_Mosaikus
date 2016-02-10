// JavaScript Document
function calendario_actividades(anio, mes){

     array = new XArray();
     array.setObjeto('Calendario','ver_mes_pagina_calendario');
     array.addParametro('import','clases.calendario.Calendario');
     array.addParametro('anio',anio);
     array.addParametro('mes',mes);
     array.addParametro('ajax',true);
     array.addParametro('permiso',document.getElementById('permiso_modulo').value);

     xajax_Loading(array.getArray());

}

function crear_objeto_fecha(fecha){
    var fecha_nueva;
    //var fecha2 = fecha + "";
    //alert(fecha);
    var xMonth=fecha.substring(3, 5);
    var xDay=fecha.substring(0, 2);
    var xYear=fecha.substring(6,10);
    fecha_nueva = new Date(xYear, xMonth - 1, xDay);
    return fecha_nueva;
}

function validar(doc){
    var inicio_mes_permitido = crear_objeto_fecha(document.getElementById('inicio_mes_permitido').value);
    var fin_mes_permitido = crear_objeto_fecha(document.getElementById('fin_mes_permitido').value);
    var inicio_mes = crear_objeto_fecha(document.getElementById('inicio_mes').value);
    var fin_mes = crear_objeto_fecha(doc.getElementById('fin_mes').value);
    var inicio_semana_1 = crear_objeto_fecha(doc.getElementById('inicio_semana_1').value);
    var fin_semana_1 = crear_objeto_fecha(doc.getElementById('fin_semana_1').value);
    var inicio_semana_2 = crear_objeto_fecha(doc.getElementById('inicio_semana_2').value);
    var fin_semana_2 = crear_objeto_fecha(doc.getElementById('fin_semana_2').value);
    var inicio_semana_3 = crear_objeto_fecha(doc.getElementById('inicio_semana_3').value);
    var fin_semana_3 = crear_objeto_fecha(doc.getElementById('fin_semana_3').value);
    var inicio_semana_4 = crear_objeto_fecha(doc.getElementById('inicio_semana_4').value);
    var fin_semana_4 = crear_objeto_fecha(doc.getElementById('fin_semana_4').value);
    if (doc.getElementById('inicio_mes').value == ""){
         alert('Debe de ingresar la fecha inicial del mes');
         return false;
    }
    if (doc.getElementById('fin_mes').value == ""){
         alert('Debe de ingresar la fecha final del mes');
         return false;
    }
    if(inicio_mes > fin_mes){
         alert('Rango invalido para el mes:\n\t - Fecha inicial "' + doc.getElementById('inicio_mes').value + '" es mayor que la fecha final');
         return false;
    }
    if(inicio_mes < inicio_mes_permitido){
         alert('Rango invalido para el mes:\n\t - Fecha inicial "' + doc.getElementById('inicio_mes').value + '" es menor a la fecha inicial permitida "' + doc.getElementById('inicio_mes_permitido').value + '"');
         return false;
    }
    if(fin_mes > fin_mes_permitido){
         alert('Rango invalido para el mes:\n\t - Fecha final "' + doc.getElementById('inicio_mes').value + '" es mayor a la fecha final permitida "' + doc.getElementById('fin_mes_permitido').value + '"');
         return false;
    }
    if (doc.getElementById('inicio_semana_1').value == ""){
         alert('Debe de ingresar la fecha inicial de la semana 1');
         return false;
    }
    if (doc.getElementById('fin_semana_1').value == ""){
         alert('Debe de ingresar la fecha final de la semana 1');
         return false;
    }
    if(inicio_semana_1 < inicio_mes){
         alert('El inicio de la semana 1 esta fuera del rango del mes.');
         return false;
    }
    if(inicio_semana_1 > fin_semana_1){
         alert('Rango de semana invalida:\n\t - Fecha inicial "' + doc.getElementById('inicio_semana_1').value + '" de la semana 1 es mayor que la fecha final de la semana 1');
         return false;
    }
    if ((doc.getElementById('inicio_semana_2').value == "") && (doc.getElementById('fin_semana_2').value != "")){
         alert('Debe de ingresar la fecha inicial de la semana 2');
         return false;
    }
    if ((doc.getElementById('inicio_semana_2').value != "") && (doc.getElementById('fin_semana_2').value == "")){
         alert('Debe de ingresar la fecha final de la semana 2');
         return false;
    }
    if ((doc.getElementById('inicio_semana_2').value != "") && (doc.getElementById('fin_semana_2').value != "") && (inicio_semana_2 > fin_semana_2)){
         alert('Rango de semana invalida:\n\t - Fecha inicial "' + doc.getElementById('inicio_semana_2').value + '" de la semana 2 es mayor que la fecha final de la semana 2');
         return false;
    }
    if ((doc.getElementById('inicio_semana_2').value != "") && (doc.getElementById('fin_semana_2').value != "") && (inicio_semana_2 <= fin_semana_1)){
         alert('Rango de semana invalida:\n\t - Fecha inicial "' + doc.getElementById('inicio_semana_2').value + '" de la semana 2 no puede estar en el rango de fechas de la semana 1');
         return false;
    }

//semana 3
    if (((doc.getElementById('inicio_semana_3').value != "") || (doc.getElementById('fin_semana_3').value != "")) && (doc.getElementById('inicio_semana_2').value == "")){
         alert('No puede usar la semana 3 sin llenar los datos de la semana 2');
         return false;
    }
    if ((doc.getElementById('inicio_semana_3').value == "") && (doc.getElementById('fin_semana_3').value != "")){
         alert('Debe de ingresar la fecha inicial de la semana 3');
         return false;
    }
    if ((doc.getElementById('inicio_semana_3').value != "") && (doc.getElementById('fin_semana_3').value == "")){
         alert('Debe de ingresar la fecha final de la semana 3');
         return false;
    }
    if ((doc.getElementById('inicio_semana_3').value != "") && (doc.getElementById('fin_semana_3').value != "") && (inicio_semana_3 > fin_semana_3)){
         alert('Rango de semana invalida:\n\t - Fecha inicial "' + doc.getElementById('inicio_semana_3').value + '" de la semana 3 es mayor que la fecha final de la semana 3');
         return false;
    }
    if ((doc.getElementById('inicio_semana_3').value != "") && (doc.getElementById('fin_semana_3').value != "") && (inicio_semana_3 <= fin_semana_2)){
         alert('Rango de semana invalida:\n\t - Fecha inicial "' + doc.getElementById('inicio_semana_3').value + '" de la semana 3 no puede estar en el rango de fechas de la semana 2');
         return false;
    }
//semana 4

    if (((doc.getElementById('inicio_semana_4').value != "") || (doc.getElementById('fin_semana_4').value != "")) && (doc.getElementById('inicio_semana_3').value == "")){
         alert('No puede usar la semana 4 sin llenar los datos de la semana 3');
         return false;
    }
    if ((doc.getElementById('inicio_semana_4').value == "") && (doc.getElementById('fin_semana_4').value != "")){
         alert('Debe de ingresar la fecha inicial de la semana 4');
         return false;
    }
    if ((doc.getElementById('inicio_semana_4').value != "") && (doc.getElementById('fin_semana_4').value == "")){
         alert('Debe de ingresar la fecha final de la semana 4');
         return false;
    }
    if ((doc.getElementById('inicio_semana_4').value != "") && (doc.getElementById('fin_semana_4').value != "") && (inicio_semana_4 > fin_semana_4)){
         alert('Rango de semana invalida:\n\t - Fecha inicial "' + doc.getElementById('inicio_semana_4').value + '" de la semana 4 es mayor que la fecha final de la semana 4');
         return false;
    }
    if ((doc.getElementById('inicio_semana_4').value != "") && (doc.getElementById('fin_semana_4').value != "") && (inicio_semana_4 <= fin_semana_3)){
         alert('Rango de semana invalida:\n\t - Fecha inicial "' + doc.getElementById('inicio_semana_4').value + '" de la semana 4 no puede estar en el rango de fechas de la semana 3');
         return false;
    }

    if(fin_semana_4 > fin_mes){
         alert('La fecha final de la semana 4 esta fuera del rango del mes.');
         return false;
    }

//    alert(inicio_mes);
//    alert(fin_mes);

    array = new XArray();
    if (doc.getElementById("opc").value == "new")
        array.setObjeto('Calendario','guardar');
    else
        array.setObjeto('Calendario','actualizar');
    array.addParametro('permiso',document.getElementById('permiso_modulo').value);
    array.getForm('idFormulario');
    xajax_Loading(array.getArray());
    return true;
}

function cambiar_dia(dia, mes, anio, objeto){
     //alert(document.getElementById('opc').value);
     //alert(document.getElementById('permiso_modulo').value.substring(0, 1));
     //alert(document.getElementById('permiso_modulo').value.substring(2, 3));
     if ((document.getElementById('permiso_modulo').value.substring(0, 1) == "0") && (document.getElementById('opc').value == "new"))
         return false;
     if ((document.getElementById('permiso_modulo').value.substring(2, 3) == "0") && (document.getElementById('opc').value == "upd"))
        return false;
     array = new XArray();
     //
     //alert(document.getElementById('marcar_dia').checked);
     if (document.getElementById('marcar_dia').checked){
         array.setObjeto('Calendario','eliminar_dia_no_laborable');
         objeto.style.color = '#000000';
     }
     else{
         array.setObjeto('Calendario','registrar_dia_no_laborable');
         objeto.style.color = '#FF0000';
     }
     array.addParametro('import','clases.calendario.Calendario');
     array.addParametro('anio',anio);
     array.addParametro('mes',mes);
     array.addParametro('dia',dia);
    //
    xajax_Loading(array.getArray());


}