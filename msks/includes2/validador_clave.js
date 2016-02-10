/**
 *
 * @access public
 * @return void
 **/
var whitespace = " \t\n\r";

function validarPasswd() {
	var p1 = document.getElementById("TxtEmpresa").value;
	var p2 = document.getElementById("TxtUsuario").value;
	var p3 = document.getElementById("TxtPwd").value;

	var espacios = false;
	var cont = 0;

	while (!espacios && (cont < p3.length)) {
	  if (p3.charAt(cont) == " ")
	    espacios = true;
	  cont++;
	}

	if (espacios) {
	  alert ("La contrase�a no puede contener espacios en blanco");
	  return false;
	}

	if (p2.length == 0 || p2.length == 0 || p3.length == 0) {
	  alert("Los campos no pueden quedar vacios");
	  return false;
	}

	if (Rut('TxtEmpresa', window.document.formulario.TxtEmpresa.value) && Rut('TxtUsuario', window.document.formulario.TxtUsuario.value)) {
		if (p1 != p2 != p3) {
		  return true;
		}
		else {
		  alert("Las passwords deben de coincidir");
		  return false;
		}
	}
	else
		return false;
}

function SoloNumerosV2(cadena, valor_cadena_otros){
	if (window.event) {
		valor_keycode = event.keyCode;
	}
	else {
		valor_keycode = valor_cadena_otros;
		if (valor_keycode==8) {
			return true;
		}
	}

	largo_cadena = cadena.length;
	if (valor_keycode < 48 || valor_keycode > 57){
		//si entra en esta posicion quiere decir que la tecla presionada
		//es cualquiera letra o caracter especial....
		 	return false;
	}
}


function EMB_revisarDigito(nombre_campo, dvr){
  dv = dvr + ""
  if( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K'){
    alert("Debe ingresar un digito verificador valido");
    eval("window.document.formulario."+nombre_campo+".focus();");
    eval("window.document.formulario."+nombre_campo+".select();");
    return false;
  }
  return true;
}

function EMB_revisarDigito2(nombre_campo, crut){
  largo = crut.length;
  if(largo<2){
    alert("Debe ingresar el rut completo");
    eval("window.document.formulario."+nombre_campo+".focus();");
    eval("window.document.formulario."+nombre_campo+".select();");
    return false;
  }
  if(largo>2)
    rut = crut.substring(0, largo - 1);
  else
    rut = crut.charAt(0);
    dv = crut.charAt(largo-1);
    EMB_revisarDigito( nombre_campo, dv );

  if ( rut == null || dv == null )
    return 0
    var dvr = '0'
    suma = 0
    mul  = 2

    for (i= rut.length -1 ; i >= 0; i--){
        suma = suma + rut.charAt(i) * mul
        if (mul == 7)
            mul = 2
        else
            mul++
    }
    res = suma % 11
    if (res==1)
        dvr = 'k'
    else if (res==0)
        dvr = '0'
    else
    {
        dvi = 11-res
        dvr = dvi + ""
    }
    if ( dvr != dv.toLowerCase() )
    {
        alert("EL rut es incorrecto")
        eval("window.document.formulario."+nombre_campo+".focus();");
        eval("window.document.formulario."+nombre_campo+".select();");
        return false
    }

    return true;
}

function EMB_Rut(nombre_campo, texto){
	//SOLO SI EXISTE ALGO INGRESADO EN LA CAJA DE TEXTO Y
	largo = texto.length;
	if (largo>0) {
		  var tmpstr = "";
		  for ( i=0; i < texto.length ; i++ )
		    if ( texto.charAt(i) != ' ' && texto.charAt(i) != '.' && texto.charAt(i) != '-' )
		        tmpstr = tmpstr + texto.charAt(i);
		    texto = tmpstr;
		    largo = texto.length;

		    if ( largo < 2 ){
		        //alert("Debe ingresar el rut completo") desactivado
	            //eval("window.document.formulario."+nombre_campo+".focus();"); desactivado
	            //eval("window.document.formulario."+nombre_campo+".select();"); desactivado
		        return false;
		    }

		    for (i=0; i < largo ; i++ ){
		        if ( texto.charAt(i) !="0" && texto.charAt(i) != "1" && texto.charAt(i) !="2" && texto.charAt(i) != "3" && texto.charAt(i) != "4" && texto.charAt(i) !="5" && texto.charAt(i) != "6" && texto.charAt(i) != "7" && texto.charAt(i) !="8" && texto.charAt(i) != "9" && texto.charAt(i) !="k" && texto.charAt(i) != "K" ){
		            //alert("El valor ingresado no corresponde a un R.U.T valido"); desactivado
		            //eval("window.document.formulario."+nombre_campo+".focus();"); desactivado
		            //eval("window.document.formulario."+nombre_campo+".select();"); desactivado
					//window.document.formulario.rutContacto.focus();
		            //window.document.formulario.rutContacto.select();
		            return false;
		        }
		    }

		    var invertido = "";
		    for ( i=(largo-1),j=0; i>=0; i--,j++ )
		        invertido = invertido + texto.charAt(i);
		    var dtexto = "";
		    dtexto = dtexto + invertido.charAt(0);
		    dtexto = dtexto + '-';
		    cnt = 0;

		    for ( i=1,j=2; i<largo; i++,j++ ){
		        //alert("i=[" + i + "] j=[" + j +"]" );
		        if ( cnt == 3 ){
		            dtexto = dtexto + '.';
		            j++;
		            dtexto = dtexto + invertido.charAt(i);
		            cnt = 1;
		        }else{
		           dtexto = dtexto + invertido.charAt(i);
		           cnt++;
		        }
		    }

		    invertido = "";
		    for ( i=(dtexto.length-1),j=0; i>=0; i--,j++ )
		        invertido = invertido + dtexto.charAt(i);

			//alert(invertido.toUpperCase());
			val_temp = invertido.toUpperCase();
			eval("window.document.formulario."+nombre_campo+".value='"+val_temp+"';");
		    //window.document.formulario.TxtEmpresa.value = invertido.toUpperCase()


		    //if(EMB_revisarDigito2(nombre_campo,texto)) desactivado
		        return true;
		    //return false; desactivado
    }
    else
    	return true;
}

function SoloNumerosPuntosGuionKEnter(cadena, valor_cadena_otros){
	if (window.event) {
		valor_keycode = event.keyCode;
	}
	else {
		valor_keycode = valor_cadena_otros;
		if (valor_keycode==8) {
			return true;
		}
	}

	//TAB = 0
	//ENTER = 13
	//k = 107
	//K = 75
	//punto = 46
	//guion = 45
	largo_cadena = cadena.length;
	if (valor_keycode != 0 && valor_keycode != 45 && valor_keycode != 46 && valor_keycode != 13 && valor_keycode != 107 && valor_keycode != 75){
		if (valor_keycode < 48 || valor_keycode > 57){
			//si entra en esta posicion quiere decir que la tecla presionada
			//es cualquiera letra o caracter especial....
			return false;
		}
	}
}
