<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <!--<style >

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
    body{
                font-family: sans-serif;font-size: 12px;
            }
            td,th{
                font-family: sans-serif;font-size: 10px;
            }

            p{
                margin: 0 0 10px;
            }

    </style>-->
</head>
<body>

<htmlpageheader name="MyHeader1">
    <div class="report-heading">
        <div class="row">
            <div class="panel-title col-xs-20">
                Maestro de Cargos - Árbol Organizacional<br/>
                <span class="small">Fecha de Emisión: {FECHA}</span><br/> <br/>
                <span class="small"></span>
            </div>
            <div class="col-xs-3 report-logo">
                <img class="img-responsive" src="{HOME}/diseno/images/logo_empresa/{ID_EMPRESA}_logo_empresa_report.png">
            </div>

        </div>
    </div>
</htmlpageheader>

<htmlpagefooter name="MyFooter1">

    <div class="report-footer">
        <div class="row">
            <div class=" col-xs-1 mosaikus-mini-logo">
                <img class="img-responsive" src="{HOME}/dist/images/logo_report.png">
            </div>
            <div class=" col-xs-9">
                &nbsp;
            </div>
            <div class=" col-xs-2">
                {N_PAG}
            </div>
        </div>

    </div>

</htmlpagefooter>

<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1" />
<sethtmlpagefooter name="MyFooter1" value="on" />
<br/>
<div id="main-content" class=" panel-container col-xs-23 ">
    <div class="content-panel panel">
        <div class="content">





            <table width="800px" class="table">
                <tbody>



                <tr>
                    <td width="250px" colspan="1"><b>Nivel</b></td>
                    <td width="500px" colspan="1">{ARBPROC}</td>
                </tr>


                </tbody>
            </table><br>

            <table class="table table-report  ">
                <thead>
                <tr>
                    <th width="10%"  style="">{N_COD_CARGO}</th>
                    <th width="20%" >{N_DESCRIPCION}</th>
                    <th width="5%"  style="">{N_INTERNO}</th>
                    <th width="15%"  style="">{N_VIGENCIA}</th>
                </tr>
                </thead>
                <tbody>

                {DATOS}

                </tbody>
            </table>
            <div class="report-body">     </div>               </div></div></div>
</body>
</html>