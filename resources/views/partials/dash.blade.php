@php

    use Illuminate\Support\Str;

    $graficos = [
        'Usuarios Registrados' => ['titulo' => 'Usuarios Registrados por Mes', 'tipo' => 'bar', 'color' => '#6366F1'],
        'Categorias' => ['titulo' => 'Crecimiento de Categorías', 'tipo' => 'line', 'color' => '#10B981'],
        'Productos' => ['titulo' => 'Distribución de Productos', 'tipo' => 'pie', 'color' => '#8B5CF6'],
        'Servicios' => ['titulo' => 'Evolución de Servicios', 'tipo' => 'bar3d', 'color' => '#F59E0B'],
        'Empresas' => ['titulo' => 'Empresas Registradas', 'tipo' => 'radar', 'color' => '#F472B6'],
        'Tickets' => ['titulo' => 'Tickets Mensuales', 'tipo' => 'linearea', 'color' => '#EF4444'],
    ];
@endphp
@php
    $initials = collect(explode(' ', Auth::user()->name))
        ->map(fn($part) => strtoupper($part[0]))
        ->implode('');
@endphp

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        /* gray-300 */
        border-radius: 4px;
    }
</style>

<div class="flex flex-col md:flex-row items-start justify-between px-6 py-8 space-y-8 md:space-y-0 md:space-x-8">
    <!-- Saludo -->
    <div class="flex-1">
        <h1 class="text-4xl font-extrabold text-gray-800 leading-tight mt-12">
            ¡Bienvenido a Network Solutions!
        </h1>
        <div class="d-flex space-x-4">
        <p class="w-10 h-10 rounded-full bg-gray-800 text-white flex items-center justify-center font-bold shadow-md">
            {{ $initials }}
        </p>
        <p class="mt-2 text-lg text-gray-500 d-flex">
            {{ Auth::user()->name }}
        </p>
        
        </div>
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Próximos Agendamientos</h3>

            <div class="max-h-52 overflow-y-auto pr-2 custom-scrollbar">
                @if ($eventos->isEmpty())
                    <p class="text-sm text-gray-500">No tienes agendamientos próximos.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($eventos as $evento)
                            <li class="flex items-start space-x-4">
                                <div class="flex-shrink-0 mt-1 text-orange-500">
                                    <i class="ri-calendar-check-line text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-800 font-semibold">{{ $evento->title }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($evento->date ?? $evento->created_at)->format('d M, Y H:i') }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>

    <!-- Calendario -->
    <section class="max-w-xl mx-auto">
        <div class="relative bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
            <!-- Botón Hoy -->
            <button onclick="calendar.today();"
                class="absolute top-4 right-4 bg-indigo-600 text-white px-3 py-1 rounded-full text-sm hover:bg-indigo-700 transition">
                Hoy
            </button>

            <!-- Header Degradado -->
            <header class="bg-gradient-to-r from-orange-500 via-gray-500 to-stone-500 p-5">
                <h2 class="text-2xl font-semibold text-white text-center tracking-wide">
                    📅 Agendamientos
                </h2>
            </header>

            <!-- Contenedor del Calendario -->
            <div id="calendar1" class="h-96 bg-gray-50 p-6 overflow-auto backdrop-blur-sm"></div>
        </div>
    </section>
</div>
<h2 class="text-xl font-bold px-4 pb-2 text-gray-700">Estadísticas Generales</h2>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-4">
    @foreach ($graficos as $clave => $grafico)
        @php
            $slug = Str::slug($clave, '-');
            $dataX = $meses;
            $dataY = $dataForCharts[$clave] ?? [];
            $detalle = $dataForCharts['Detalle ' . $clave] ?? [];
        @endphp

        <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition" role="region"
            aria-label="{{ $grafico['titulo'] }}">
            <div class="flex justify-between mb-2">
                <h3 class="font-semibold">{{ $grafico['titulo'] }}</h3>
                <button onclick="exportChart('{{ $slug }}','{{ $grafico['titulo'] }}')"
                    class="text-blue-600">📤</button>
            </div>
            @if (!array_sum($dataY))
                <p class="text-center text-gray-500 py-8">No hay datos este año</p>
            @else
                <div id="chart-{{ $slug }}" class="h-64"></div>
                <script>
                    (function() {
                        const chart = echarts.init(document.getElementById('chart-{{ $slug }}'));
                        const color = '{{ $grafico['color'] }}';
                        const dataX = {!! json_encode($dataX) !!};
                        const dataY = {!! json_encode($dataY) !!};
                        const detalle = {!! json_encode($detalle) !!};

                        let opt;
                        switch ('{{ $grafico['tipo'] }}') {
                            case 'bar':
                                opt = {
                                    tooltip: {
                                        trigger: 'axis',
                                        formatter: params => {
                                            let i = params[0].dataIndex,
                                                names = detalle[i] || [];
                                            let list = names.length ? names.map(n => '• ' + n).join('<br>') : 'Cantidad: ' +
                                                dataY[i];
                                            return `<strong>${params[0].name}</strong><br>` + list;
                                        }
                                    },
                                    xAxis: {
                                        type: 'category',
                                        data: dataX
                                    },
                                    yAxis: {
                                        type: 'value'
                                    },
                                    series: [{
                                        type: 'bar',
                                        data: dataY,
                                        itemStyle: {
                                            color
                                        }
                                    }]
                                };
                                break;
                            case 'line':
                                opt = {
                                    tooltip: {
                                        trigger: 'axis'
                                    },
                                    xAxis: {
                                        data: dataX
                                    },
                                    yAxis: {},
                                    series: [{
                                        type: 'line',
                                        smooth: true,
                                        data: dataY,
                                        itemStyle: {
                                            color
                                        }
                                    }]
                                };
                                break;
                            case 'pie':
                                opt = {
                                    tooltip: {
                                        trigger: 'item'
                                    },
                                    series: [{
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        data: dataX.map((n, i) => ({
                                            name: n,
                                            value: dataY[i]
                                        })),
                                        itemStyle: {
                                            color
                                        }
                                    }]
                                };
                                break;
                            case 'radar':
                                opt = {
                                    tooltip: {},
                                    radar: {
                                        indicator: dataX.map((n, i) => ({
                                            name: n,
                                            max: Math.max(...dataY, 10)
                                        }))
                                    },
                                    series: [{
                                        type: 'radar',
                                        data: [{
                                            value: dataY,
                                            name: 'Datos'
                                        }]
                                    }]
                                };
                                break;
                            case 'bar3d': // representado como bar
                                opt = {
                                    tooltip: {
                                        trigger: 'axis'
                                    },
                                    xAxis: {
                                        data: dataX
                                    },
                                    yAxis: {},
                                    series: [{
                                        type: 'bar',
                                        data: dataY,
                                        itemStyle: {
                                            color
                                        }
                                    }]
                                };
                                break;
                            case 'linearea':
                                opt = {
                                    tooltip: {
                                        trigger: 'axis'
                                    },
                                    xAxis: {
                                        data: dataX
                                    },
                                    yAxis: {},
                                    series: [{
                                        type: 'line',
                                        areaStyle: {},
                                        smooth: true,
                                        data: dataY,
                                        itemStyle: {
                                            color
                                        }
                                    }]
                                };
                                break;
                            default:
                                opt = {};
                        }
                        chart.setOption(opt);
                        window.addEventListener('resize', () => chart.resize());
                    })
                    ();
                </script>
            @endif
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<script>
    function exportChart(slug, title) {
        const c = echarts.getInstanceByDom(document.getElementById('chart-' + slug));
        const img = c.getDataURL({
            type: 'png',
            pixelRatio: 2,
            backgroundColor: '#fff'
        });
        let a = document.createElement('a');
        a.href = img;
        a.download = title.replace(/\s+/g, '_') + '.png';
        a.click();
    }
</script>
