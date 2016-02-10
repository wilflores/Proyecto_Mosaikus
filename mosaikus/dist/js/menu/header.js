var id_timer = 0;
var ya_busco = 0;
function inicio(){
	ya_busco = 0;
	self.document.form_buscar.q.style.color="#CCCCCC";
}
function foco(){
	if(!ya_busco){
		self.document.form_buscar.q.value = "";
		self.document.form_buscar.q.style.color="#000000";
		ya_busco = 1;
	}
}

function desplegable(e)
{
	clearTimeout(id_timer)
	var e = e.data;
	if($(e.menu_primario).css("display") == "none"){
		$(e.menu_tercero).css("display", "none");
		$(e.menu_cuarto).css("display", "none");
		$(e.menu_quinto).css("display", "none");
		$(e.menu_sexto).css("display", "none");
		$(e.menu_secundario).css("display", "none");
		$(e.menu_primario).fadeIn("normal");
	}
}

function esconder ()
{
	$("#submenu_identidad").fadeOut("fast");
	$("#submenu_recursos").fadeOut("fast");
	$("#submenu_celulas").fadeOut("fast");
	$("#submenu_fotos").fadeOut("fast");
	$("#submenu_ministerios").fadeOut("fast");
	$("#submenu_extras").fadeOut("fast");
}

function timerEsconder ()
{
	id_timer = setTimeout("esconder();", 4000);
}

carga = function(){
	//inicio();
	
	$("#menu_identidad").bind('mouseover',{menu_primario:"#submenu_identidad", menu_secundario:"#submenu_recursos", menu_tercero:"#submenu_celulas", menu_cuarto:"#submenu_fotos", menu_quinto:"#submenu_extras", menu_sexto:"#submenu_ministerios"},desplegable);
	$("#menu_recursos").bind('mouseover',{menu_primario:"#submenu_recursos", menu_secundario:"#submenu_identidad", menu_tercero:"#submenu_celulas", menu_cuarto:"#submenu_fotos", menu_quinto:"#submenu_extras", menu_sexto:"#submenu_ministerios"},desplegable);
	$("#menu_celulas").bind('mouseover',{menu_primario:"#submenu_celulas", menu_secundario:"#submenu_recursos", menu_tercero:"#submenu_identidad", menu_cuarto:"#submenu_fotos", menu_quinto:"#submenu_extras", menu_sexto:"#submenu_ministerios"},desplegable);
	$("#menu_fotos").bind('mouseover',{menu_primario:"#submenu_fotos", menu_secundario:"#submenu_celulas", menu_tercero:"#submenu_recursos", menu_cuarto:"#submenu_identidad", menu_quinto:"#submenu_extras", menu_sexto:"#submenu_ministerios"},desplegable);
	$("#menu_extras").bind('mouseover',{menu_primario:"#submenu_extras", menu_secundario:"#submenu_celulas", menu_tercero:"#submenu_recursos", menu_cuarto:"#submenu_identidad", menu_quinto:"#submenu_fotos", menu_sexto:"#submenu_ministerios"},desplegable);
	$("#menu_ministerios").bind('mouseover',{menu_primario:"#submenu_ministerios", menu_secundario:"#submenu_celulas", menu_tercero:"#submenu_recursos", menu_cuarto:"#submenu_identidad", menu_quinto:"#submenu_fotos", menu_sexto:"#submenu_extras"},desplegable);
	
	$("#submenu_identidad").bind('mouseout',timerEsconder);
	$("#submenu_recursos").bind('mouseout',timerEsconder);
	$("#submenu_cursos").bind('mouseout',timerEsconder);
	
	
	$("#menu_oracion").bind('mouseover',esconder);
	$("#menu_inicio").bind('mouseover',esconder);
	
	$("#submenu_identidad").css("display", "none");
	$("#submenu_recursos").css("display", "none");
	$("#submenu_celulas").css("display", "none");
	$("#submenu_fotos").css("display", "none");
	$("#submenu_extras").css("display", "none");
	$("#submenu_ministerios").css("display", "none");
}

