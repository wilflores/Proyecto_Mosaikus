function VerPermisos(id_rol){
        //GB_showCenter("Ver Registro", '../pages/seguridad/menu_permiso_por_rol.php?id_rol=' + id_rol ,500, 450);
        var src = 'pages/usuarios/rol_empresa.php?id=' + id_rol;
            $('a#ver_ficha_trabajador').fancybox({
                    'titleShow': false,
                    'href' : src,
                    'autoDimensions' :false,
                    'type':'iframe',
                    'width' : 600,
                    'height' : 420
                });
        //$('a#prueba').fancybox().fancybox_show();
        $("#ver_ficha_trabajador").trigger('click');
    }

function validarRol(doc){
    array = new XArray();    
    array.setObjeto('Usuarios','guardarRolEmpresa');    
    //array.addParametro('permiso',document.getElementById('permiso_modulo').value);
    array.getForm('idFormulario');
    array.addParametro('import','clases.usuarios.Usuarios');
    xajax_Loading(array.getArray());

}

function nuevo_usuario(){
	array = new XArray();
	array.setObjeto('Usuarios','crear');
        array.addParametro('import','clases.usuarios.Usuarios');
	xajax_Loading(array.getArray());
}

function validar_envio(doc){
    if(doc.getElementById("login").value==""){
        VerMensaje('error','Debe ingresar el usuario');
        doc.getElementById("login").focus();
        return false;
    }

    array = new XArray();
    array.setObjeto('Usuarios','ejecutar_recuperar_contrasena');
    array.getForm('idFormulario');
    array.addParametro('import','clases.usuarios.Usuarios');
    xajax_Loading(array.getArray());    
    return true;
}

function validar_cambio(doc){
//    if(doc.getElementById("password_actual").value==""){
//        VerMensaje('error','Debe ingresar el password actual');
//        doc.getElementById("password_actual").focus();
//        return false;
//    }
//    if(doc.getElementById("password").value==""){
//         VerMensaje('error','Debe ingresar el nuevo password');
//         doc.getElementById("password").focus();
//         return false;
//    }
//
//    if(doc.getElementById("password2").value==""){
//         VerMensaje('error','Debe de confirmar el nuevo password');
//         doc.getElementById("password2").focus();
//         return false;
//    }
//
//    if((doc.getElementById("password").value!=doc.getElementById("password2").value)){
//         VerMensaje('error','La contraseña nueva no coincide con la confirmacion de contraseña');
//         return false;
//    }


    if (($('#idFormulario-cp').isValid())) {

        array = new XArray();
        array.setObjeto('Usuarios','actualizar_contrasena');
        array.getForm('idFormulario-cp');
        array.addParametro('import','clases.usuarios.Usuarios');
        xajax_Loading(array.getArray());
        return true;
    }
}

function validar(doc){
    
    if ((doc.getElementById("correo").value.indexOf('@masisa.com')==-1)&&(rtrim(document.getElementById('login').value)=='')){
           alert('El Login no puede estar en blanco');
           return;
       }
       if (rtrim(document.getElementById('nombre').value)==''){
           alert('El Nombre no puede estar en blanco');
           return;
       }
       if ((doc.getElementById("correo").value.indexOf('@masisa.com')==-1)&&(rtrim(document.getElementById('password').value)=='')){
           alert('El Password no puede estar en blanco');
           return;
       }
        if (document.getElementById('password').value!=document.getElementById('password2').value){
           alert('Ambos Password deben coincidir');
           return;
       }
       if (document.getElementById('correo').value!='')
           if (!validar_correo(document.getElementById('correo').value)){
               alert('El formato del correo no es correcto');
                return;
           }

//       if (rtrim(document.getElementById('id_rol').value)=='0'){
//           alert('Debe seleccionar un rol para el usuario');
//           return;
//       }

    array = new XArray();
    if (doc.getElementById("opc").value == "new")
        array.setObjeto('Usuarios','guardar');
    else
        array.setObjeto('Usuarios','actualizar');
    array.addParametro('permiso',document.getElementById('permiso_modulo').value);
    array.getForm('idFormulario');
    array.addParametro('import','clases.usuarios.Usuarios');
    xajax_Loading(array.getArray());
    
}

function editarUsuario(id){
    array = new XArray();
    array.setObjeto('Usuarios','editar');
    array.addParametro('import','clases.usuarios.Usuarios');
    array.addParametro('id',id);
    xajax_Loading(array.getArray());
}


function eliminarUsuario(id){
    if(confirm("¿Desea Eliminar el Usuario Seleccionado?")){
        array = new XArray();
        array.setObjeto('Usuarios','eliminar');
        array.addParametro('id',id);
        array.addParametro('permiso',document.getElementById('permiso_modulo').value);
        array.addParametro('import','clases.usuarios.Usuarios');
        xajax_Loading(array.getArray());
    }
}
function verPagina(pag,doc){
    array = new XArray();
    if(doc!=null){
        if (doc.getElementById("valor").value!=""){
            array.addParametro('valor',doc.getElementById("valor").value);
        }
        if (doc.getElementById("campo").value!=-1){
            array.addParametro('campo',doc.getElementById("campo").value);
        }
    }else{
        document.getElementById("valor").value="";
        document.getElementById("campo").selectedIndex=0;
    }
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
    array.setObjeto('Usuarios','buscar');
    array.addParametro('import','clases.usuarios.Usuarios');
    xajax_Loading(array.getArray());
}

