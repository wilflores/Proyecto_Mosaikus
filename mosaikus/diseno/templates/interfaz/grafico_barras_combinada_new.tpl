

 <script type="text/javascript">
        $(document).ready(function(){
            $('#container-3').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: '{TITULO}'
            },
            subtitle: {
                text: '{SUB_TITULO}'
            },
            xAxis: [{
                categories: [{CATEGORIAS}],
                labels: {
                    rotation: -30,
                    align: 'right'
                }
            }],
            yAxis: [{ 
                
                title: {
                    text: '{NOMBRE_SERIE_2}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                min: 0,
                max: 100,
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            },
              { 
                
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: '{NOMBRE_SERIE_1}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            } ],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            series: [{
                name: '{NOMBRE_SERIE_1}',
                type: 'column',
                yAxis: 1,
                data: [{DATA_SERIE_1}],
                tooltip: {
                    valueSuffix: ' '
                }
    
            }, {
                name: '{NOMBRE_SERIE_2}',
                type: 'spline',
                data: [{DATA_SERIE_2}],
                tooltip: {
                    valueSuffix: '%'
                }
            }]
        });
        });
    </script>