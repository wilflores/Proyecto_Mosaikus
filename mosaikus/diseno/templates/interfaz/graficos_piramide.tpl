
<div id="container" style="min-width: 600px; height: 400px; margin: 0 ">

</div>
 <script type="text/javascript">
        $(document).ready(function(){
            $('#container').highcharts({
                chart: {
                    type: 'pyramid',
                    marginRight: 100
                },
                title: {
                    text: '{TITULO_REPORTE}',
                    x: -50
                },
                subtitle: {
                    text: '{SUBTITULO_REPORTE}'
                },                
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>',
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                            softConnector: true
                        }
                    }
                },                
                legend: {
                    enabled: false
                },
                tooltip: {
                    formatter: function() {
                        return '<b>'+ this.point.name +'</b>';
                    }
                },
                series: [{VALUE_SERIES}]
            });
        });
    </script>