<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <style>
            
		.centrado, table thead tr th{
                    text-align: center;                    
                }
		.derecha{
                    text-align: right;
                }
		.negrita{
                    font-weight: bold;
                }
		.table{
                    border-collapse: collapse;
                }
		.table td, .table th{
                    border: 1px solid black;
                }
		th{
                    background: #ccc;
                    padding: 5px;
                }
		td{
                    padding: 3px;
                    
                }
                table tbody tr td{
                    vertical-align:top;
                }
		body,td,th{
                    font-family: sans-serif;font-size: 12px;
                }
                
                p{
                    margin: 0 0 10px;
                }
                
        </style>
    </head>
<body>        
    
    <htmlpageheader name="MyHeader1">
        <table width="800px" class="centrado table">
        <thead>
            <tr>
                <td height="60px" width="195" r><img src="diseno/images/logo_empresa/{ID_EMPRESA}_logo_empresa.png"/></td>
                <td width="409" ><h3>Informe individual documento  <br>"{NOMBRE_DOC}"</h3>  </td>   
                <td>Fecha de Emisión: {DATE j/m/Y} </td>
               
            </tr>     
            
            
        </thead>
    </table>
    </htmlpageheader>

    <htmlpagefooter name="MyFooter1">
        <table style="border-top: 1px solid black; vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;" width="100%">
		<tbody>
                    <tr style="">
				<td width="33%"><img src="diseno/images/logo_word_pdf_excel.png"/></td>
				<td style="font-weight: bold; font-style: italic;" align="center" width="33%"><br>{N_PAG}</td>
				<td style="text-align: right;" width="33%">&nbsp;</td>
			</tr>
		</tbody>
	</table>

    </htmlpagefooter>

<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1" />
<sethtmlpagefooter name="MyFooter1" value="on" />

<table width="800px" class="table">
    <tbody>
        <tr>
                <th colspan="4" width="100px"  style="">DATOS BASICOS</th>
                
        </tr>         

         
        <tr>
            <td width="250px" colspan="1"><b>{N_CODIGO_DOC}</b></td>
            <td width="500px" colspan="1">{CODIGO_DOC}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_NOMBRE_DOC}</b></td>
            <td width="500px" colspan="1">{NOMBRE_DOC}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_FECHA}</b></td>
            <td width="500px" colspan="1">{FECHA}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_VERSION}</b></td>
            <td width="500px" colspan="1">{VERSION}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_ELABORO}</b></td>
            <td width="500px" colspan="1">{ELABORO}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_REVISO}</b></td>
            <td width="500px" colspan="1">{REVISO}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_APROBO}</b></td>
            <td width="500px" colspan="1">{APROBO}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_FORMULARIO}</b></td>
            <td width="500px" colspan="1">{FORMULARIO}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_VIGENCIA}</b></td>
            <td width="500px" colspan="1">{VIGENCIA}</td>                        
        </tr>
        <tr>
            <td width="250px" colspan="1"><b>{N_ARBPROC}</b></td>
            <td width="500px" colspan="1">{ARBPROC}</td>                        
        </tr>
        
        
    </tbody>
    </table><br>        
           
    <table width="800px" class="table">
        <tbody>
            <tr>
                <th width="250px"  style="">Responsable</th>
                <th width="50px"  style="">Fecha</th>
                <th width="50px" >Versión</th>
                <th width="50px"  style="">Revisión</th>
                <th width="400px"  style="">Control de cambios</th>
            </tr>   
            {DATOS}
        <!--<tr>
            <td colspan="4" width="33%"><b>Requisito de la Norma:</b><br>{DESCRIPCION}</td>            
        </tr>
        <tr>
            <td colspan="4" width="33%"><b>Evidencia Objetiva:</b><br>{EVIDENCIA}    
                                            {IMAGES_ANTES}</td>
        </tr>
        -->
        </tbody>
    </table>
   
</body>
</html>