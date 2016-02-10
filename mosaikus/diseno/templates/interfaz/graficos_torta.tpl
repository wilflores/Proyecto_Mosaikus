
<div id="container" style="min-width: 600px; height: 400px; margin: 0 ">

</div>
 <script type="text/javascript">
        $(document).ready(function(){
            $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '{TITULO}'
            },
                    
            subtitle: {
                text: '{SUB_TITULO}'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.2f} %'                        
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '{NOMBRE_SERIES}',
                data: [
                    {DATA_SERIES}
                ]
            }]
        });
        });
    </script>