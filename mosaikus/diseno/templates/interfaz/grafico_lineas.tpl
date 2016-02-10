
<div id="container" style="min-width: 600px; height: 400px; margin: 0 ">

</div>
 <script type="text/javascript">
        $(document).ready(function(){
            $('#container').highcharts({
    
                chart: {
                type: 'line'
            },
            title: {
                text: '{TITULO_REPORTE}'
            },
            subtitle: {
                text: '{SUBTITULO_REPORTE}'
            },
            xAxis: {
                categories: [{DATA_SERIES}]
            },
            yAxis: {
                title: {
                    text: '{TEXT_EJE_Y}'
                }
                ,min: 0                
            },
            tooltip: {
                enabled: false,
                formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y +'°C';
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [{VALUE_SERIES}]
        });
        });
    </script>