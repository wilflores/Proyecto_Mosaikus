var OpAct;
var modulo_actual;
var tab_actual;
var css_modulo_actual;
var num_clic_menu;

num_clic_menu = 0;

function HabilitaOpciones(){
    //$(".second-level > li").click(function(){
    $(".opcion_menu_tab").click(function(){
    $('.third-level').hide();
    array = new XArray();
    array.addParametro('import','clases.menu.Menu');
    array.setObjeto('Menu','indexMenuOpciones');
    array.addParametro('indice_tab',$(this).attr('ref'));
    var texto = $(this).html();
    //alert(texto);
    texto = texto.replace(/&nbsp;/g,' ');
    //alert(texto);
    tab_actual = texto.substring(texto.indexOf('<a>') + 3, texto.indexOf('</a>'));

    xajax_Loading(array.getArray());
    $('#ul-'+$(this).attr('ref')).show();
    });
}
function ClickOpciones(id,obj,permisos){
//alert(pagina.lastIndexOf('_h'));

	//alert(obj);
//        document.getElementById('frmtabs').target ='Contenido';
//	document.getElementById('frmtabs').action =pagina;
//	document.getElementById('permiso').value =permisos;
//        if (!OpAct)
//            OpAct = document.getElementById('opcion1');
       // document.getElementById('loading').style.display='';
        OpAct = obj;
        array = new XArray();
        array.addParametro('import','clases.menu.Menu');
        array.setObjeto('Menu','indexMenuTab');
        array.addParametro('id_menu_opcion',id);
        array.addParametro('permiso',permisos);
        //array.addParametro('ajax',true);
        //array.addParametro('indice_modulo',opc);
        xajax_Loading(array.getArray());
	//alert(obj.Id);
	//document.getElementById('frmtabs').submit();
        document.getElementById('contenido').innerHTML="<div id='loading' style='position:absolute;top:320px;left:650px;'><img src='diseno/images/ajax-loader.gif'></div>";
        document.getElementById('contenido-form').innerHTML='';
        document.getElementById('contenido').style.display='';
        document.getElementById('contenido-form').style.display='none';  
        

        var miga = '<a href="#">INICIO</a></span><span class="breadcrumb-link"><a href="#">';
        miga = miga + modulo_actual.toUpperCase() + '</a></span><span class="breadcrumb-link"><a href="#">';
        miga = miga + tab_actual.toUpperCase() + '</a></span><span class="breadcrumb-link"><strong>';
        miga = miga + $(obj).html().toUpperCase() + '</strong></span>';
        //alert(miga);
        document.getElementById('div_rastros').innerHTML = miga;
        $("#h1_page_title").css({'display':''});
        $('#span_modulo_actual').removeClass();
        $('#div_titulo_modulo_actual').removeClass();
        $('#span_modulo_actual').addClass('icon icon-' + css_modulo_actual);
        $('#div_titulo_modulo_actual').addClass('text-' + css_modulo_actual + ' bg-' + css_modulo_actual);
        $('#div_titulo_modulo_actual').html(modulo_actual);
}

function ClickOpcionesMosaikus(id,nombre,imagen){
//alert(pagina.lastIndexOf('_h'));

	//alert(obj);
//        document.getElementById('frmtabs').target ='Contenido';
//	document.getElementById('frmtabs').action =pagina;
//	document.getElementById('permiso').value =permisos;
//        if (!OpAct)
//            OpAct = document.getElementById('opcion1');
       // document.getElementById('loading').style.display='';
        // OpAct = obj;
        array = new XArray();
        array.addParametro('import','clases.menu.Menu');
        array.setObjeto('Menu','indexMenuTab');
        array.addParametro('id_menu_opcion',id);
        //array.addParametro('permiso',permisos);
        //array.addParametro('ajax',true);
        //array.addParametro('indice_modulo',opc);
        $('#MustraCargando').show();
        
	//alert(obj.Id);
	//document.getElementById('frmtabs').submit();
        //document.getElementById('contenido').innerHTML="<div id='loading' style='position:absolute;top:320px;left:650px;'><img src='diseno/images/ajax-loader.gif'></div>";
        document.getElementById('contenido').innerHTML="";
        document.getElementById('contenido-form').innerHTML='';
        document.getElementById('contenido').style.display='';
        document.getElementById('contenido-form').style.display='none';
        $('#contenido-form').parent().hide();
        $('#contenido').parent().show();
        document.getElementById('contenido-form-aux').style.display='none'; 
        document.getElementById('contenido-aux').style.display='none'; 
        document.getElementById('contenido-form-aux').innerHTML='';
        document.getElementById('contenido-aux').innerHTML='';
        $('#contenido-form-aux').parent().hide();
        $('#contenido-aux').parent().hide();
        MenuHandler.hideMenu();
        
        xajax_Loading(array.getArray());
        //$('#desc-mod-act').html(nombre);
        //$('#order_updatepanel3').html('<div><img class="SinBorde" src="diseno/images/'+ imagen +'"></div>');
        //alert(nombre);
        //alert(imagen);

/*
        $("#h1_page_title").css({'display':''});
        $('#span_modulo_actual').removeClass();
        $('#div_titulo_modulo_actual').removeClass();
        $('#span_modulo_actual').addClass('icon icon-' + css_modulo_actual);
        $('#div_titulo_modulo_actual').addClass('text-' + css_modulo_actual + ' bg-' + css_modulo_actual);
        $('#div_titulo_modulo_actual').html(modulo_actual);
        */
}
//var j = jQuery.noConflict();

	//$("#main-logo").simpleSlide({duration:5000,transition:1000});
       


