@php
    use Illuminate\Support\Str;

    $graficos = [
        'Usuarios Registrados por Mes' => [
            'titulo' => 'Usuarios Registrados por Mes',
            'nombreSerie' => 'Usuarios Registrados',
            'dataX' => $meses,
            'dataY' => $dataForCharts['Usuarios Registrados por Mes'],
            'tipo' => 'bar',
        ],
        'Categorias' => [
            'titulo' => 'Categorías por Mes',
            'nombreSerie' => 'Cantidad de Categorías',
            'dataX' => $meses,
            'dataY' => $dataForCharts['Categorias'],
            'tipo' => 'line',
        ],
        'Productos' => [
            'titulo' => 'Productos por Mes',
            'nombreSerie' => 'Cantidad de Productos',
            'dataX' => $meses,
            'dataY' => $dataForCharts['Productos'],
            'tipo' => 'pie',
        ],
        'Servicios' => [
            'titulo' => 'Servicios por Mes',
            'nombreSerie' => 'Cantidad de Servicios',
            'dataX' => $meses,
            'dataY' => $dataForCharts['Servicios'],
            'tipo' => 'bar',
        ],
        'Empresas' => [
            'titulo' => 'Empresas por Mes',
            'nombreSerie' => 'Cantidad de Empresas',
            'dataX' => $meses,
            'dataY' => $dataForCharts['Empresas'],
            'tipo' => 'line',
        ],
        'Tickets' => [
            'titulo' => 'Tickets por Mes',
            'nombreSerie' => 'Cantidad de Tickets',
            'dataX' => $meses,
            'dataY' => $dataForCharts['Tickets'],
            'tipo' => 'bar',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Gráficos</title>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <style>
        body {
            margin: 0;
        }   

        .charts-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .chart-container {
            flex: 1 1 calc(50% - 40px);
            max-width: calc(50% - 40px);
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            box-sizing: border-box;
        }

        .chart-box {
            width: 100%;
            height: 360px;
        }

        @media (max-width: 768px) {
            .chart-container {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <h1>Panel de Gráficos Estadísticos</h1>
    <div class="charts-wrapper">
        @foreach ($graficos as $id => $grafico)
            @php
                $slugId = Str::slug($id, '-');
                $varId = Str::slug($id, '_');
            @endphp

            <div class="chart-container" role="region" aria-label="Gráfico: {{ $grafico['titulo'] }}">
                <div id="chart-{{ $slugId }}" class="chart-box"></div>
            </div>

            <script>
                const chart_{{ $varId }} = echarts.init(document.getElementById('chart-{{ $slugId }}'));
                const detalle_{{ $varId }} = {!! json_encode($dataForCharts['Detalle ' . $id] ?? []) !!};
                const tipo_{{ $varId }} = '{{ $grafico['tipo'] }}';

                const option_{{ $varId }} = tipo_{{ $varId }} === 'pie' ? {
                    title: {
                        text: '{{ $grafico['titulo'] }}',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: function(params) {
                            const mesIndex = params.dataIndex;
                            const nombres = detalle_{{ $varId }}[mesIndex + 1] || [];
                            let html = `<strong>${params.name}</strong><br/>Cantidad: ${params.value}`;
                            if (nombres.length > 0) {
                                html += '<br/>Nombres:<ul>';
                                nombres.forEach(n => html += `<li>${n}</li>`);
                                html += '</ul>';
                            }
                            return html;
                        }
                    },
                    legend: {
                        bottom: 10,
                        left: 'center'
                    },
                    series: [{
                        name: '{{ $grafico['nombreSerie'] }}',
                        type: 'pie',
                        radius: '50%',
                        data: {!! json_encode(array_map(function($label, $val) {
                            return ['name' => $label, 'value' => $val];
                        }, $grafico['dataX'], $grafico['dataY'])) !!},
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                } : {
                    title: {
                        text: '{{ $grafico['titulo'] }}',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'axis',
                        formatter: function(params) {
                            const mesIndex = params[0].dataIndex;
                            const cantidad = params[0].data;
                            const nombres = detalle_{{ $varId }}[mesIndex + 1] || [];
                            let html = `<strong>${params[0].axisValue}</strong><br/>Cantidad: ${cantidad}`;
                            if (nombres.length > 0) {
                                html += '<br/>Nombres:<ul>';
                                nombres.forEach(n => html += `<li>${n}</li>`);
                                html += '</ul>';
                            }
                            return html;
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: {!! json_encode($grafico['dataX']) !!}
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        name: '{{ $grafico['nombreSerie'] }}',
                        type: tipo_{{ $varId }},
                        data: {!! json_encode($grafico['dataY']) !!},
                        itemStyle: {
                            color: tipo_{{ $varId }} === 'line' ? '#00BFA6' : '#2196F3'
                        }
                    }]
                };

                chart_{{ $varId }}.setOption(option_{{ $varId }});
            </script>
        @endforeach
    </div>
</body>
</html>
