/*
  Jorge Rodriguez 
  C.I.: 17.592.569
  rodriguez.jorge.mail@gmail.com
  24/08/2007
*/
function XArray(){
  this.Forms = '';
  this.Objeto = '';
  this.Parametros = '';
  /****************************************************************************/
	this.getForm = function(frm){
    var submitDisabledElements = false;
		var objForm = document.getElementById(frm);
		var sXml = "";
		if (objForm && objForm.tagName.toUpperCase() == 'FORM'){
			var formElements = objForm.elements;
			for(var i=0; i < formElements.length; i++){
				if(!formElements[i].name)
					continue;
				if(formElements[i].type && (formElements[i].type == 'radio' || formElements[i].type == 'checkbox') && formElements[i].checked == false)
					continue;
				if(formElements[i].disabled && formElements[i].disabled == true && submitDisabledElements == false)
					continue;
				var name = formElements[i].name;
				if(name){
					sXml += '&';
					if(formElements[i].type=='select-multiple'){
						for (var j = 0; j < formElements[i].length; j++)
							if (formElements[i].options[j].selected == true)
								sXml += name+"="+encodeURIComponent(formElements[i].options[j].value)+"&";
					}
					else
						sXml += name+"="+encodeURIComponent(formElements[i].value);
				}
			}
		}
		this.Forms = this.Forms + sXml;
	}
  /****************************************************************************/
  this.setObjeto = function(Nombre,Metodo){
    this.Objeto = 'objeto=' + Nombre + '&metodo=' + Metodo;
  }
  /****************************************************************************/
  this.addParametro = function(Nombre,Valor){
     this.Parametros = '&' + Nombre + '=' + Valor + this.Parametros;
  }
  /****************************************************************************/
  this.getArray = function(){
  if(this.Objeto == ''){
    alert('Indique el objeto receptor -> setObjeto(Nombre,Metodo)');
    return ''; 
   }
   return '<xjxquery><q>'+ this.Objeto + this.Parametros + this.Forms + '</q></xjxquery>';
  }
  /****************************************************************************/
}


function Paginar(pagina){
 var parametros = document.getElementById('P_paginar').value;
 xajax_Loading('<xjxquery><q>pagina='+pagina+parametros+'</q></xjxquery>');
}
