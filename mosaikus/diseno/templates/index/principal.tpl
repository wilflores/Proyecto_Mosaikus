<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="verify-v1" content="o9+SkXLM06qvwKNgzXHX/Wa3opKllp3AAGSN842/3aI=" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{TITLE}</title>
    {CSS}
    {JAVASCRIPT}        
    <style type="text/css">
        {STYLE_CSS}
  </style>
</head>
<body onload="{LLAMAR_FUNCION}">
    <div style="position:absolute; top:50px; left:250px;">
        <a href="#mensaje_error" id="aqui"></a>
    </div>
    <div id="mensaje_error" class="error mensajeserror" style="z-index:100;position:absolute; top:50px; left:50px; width: 400px; display: none;"></div>
    <div id="mensaje_exito" class="exito mensajesexito" style="z-index:100;position:absolute; top:50px; left:50px; width: 400px; display: none;"></div>
    <div id="mensaje_info" class="info mensajesinfo" style="z-index:100;position:absolute; top:50px; left:50px; width: 400px; display: none;"></div>
    
    <div id="contenido">
        {CONTENIDO}
    </div>
    <input type="hidden" id="permiso_modulo" name="permiso_modulo" value="{PERMISO}"/>
    <input type="hidden" id="modulo_actual" name="modulo_actual" value="{MODULO_ACTUAL}"/>    
    <script type="text/javascript">
        {SCRIPT_LOAD}
    </script>    
    
</body>
</html>



