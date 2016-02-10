
<div id="container" style="min-width: 600px; height: 400px; margin: 0 ">

</div>
 <script type="text/javascript">
        $(document).ready(function(){
            $('#container').highcharts({
    
                chart: {
                    type: 'column'
                },

                title: {
                    text: '{TITULO_REPORTE}'
                },
                
            subtitle: {
                text: '{SUBTITULO_REPORTE}'
            },
                xAxis: {
                    categories: {GERENCIAS}
                },

                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: '{TITULO_EJE_Y}'
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:10px"><b>{point.key}</b></span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} {VALOR_ESCALA}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.05,
                        borderWidth: 0
                    }
                },

                series: [{SERIES}]
            });
        });
    </script>