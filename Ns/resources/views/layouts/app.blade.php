<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Control')</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <link href="https://unpkg.com/tabulator-tables@5.4.3/dist/css/tabulator.min.css" rel="stylesheet">
    <script src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>
    <!-- Favicon -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Head -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts-gl@2.0.9/dist/echarts-gl.min.js"></script>

    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }

        a {
            text-decoration: none;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 transition-all duration-300 bg-gradient-to-b from-gray-900 via-gray-800 to-orange-600 text-white flex flex-col shadow-xl rounded-r-xl overflow-hidden">
            <div class="flex items-center justify-between py-4 px-4">
                <img src="{{ asset('storage/image/ns.png') }}" alt="Logo" class="h-12">
                <button id="toggleSidebar" class="text-white focus:outline-none">
                    <i class="ri-arrow-left-s-line text-2xl"></i>
                </button>
            </div>
            <nav class="flex-1 px-2 py-4">
                <ul class="space-y-2">
                    @role('empresarial')
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="inventario">
                                <i class="ri-archive-line text-xl"></i><span class="sidebar-label">Inventario</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="docs">
                                <i class="ri-docs-todo-line text-xl"></i><span class="sidebar-label">Docs</span>
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="dashboard">
                                <i class="ri-dashboard-line text-xl"></i><span class="sidebar-label">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="tienda">
                                <i class="ri-store-2-line text-xl"></i><span class="sidebar-label">Tienda</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="usuarios">
                                <i class="ri-user-3-line text-xl"></i><span class="sidebar-label">Usuarios</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="empresas">
                                <i class="ri-building-line text-xl"></i><span class="sidebar-label">Empresas</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="inventario">
                                <i class="ri-archive-line text-xl"></i><span class="sidebar-label">Inventario</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="calendario">
                                <i class="ri-calendar-event-line text-xl"></i><span class="sidebar-label">Calendario</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 text-gray-300 hover:text-white hover:bg-orange-600 px-3 py-2 rounded-md"
                                data-target="docs">
                                <i class="ri-article-line text-xl"></i><span class="sidebar-label">Documentos</span>
                            </a>
                        </li>
                    @endrole
                </ul>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <main class="flex-1 flex flex-col m-4 md:m-6 rounded-xl shadow-xl bg-white overflow-hidden">
            <!-- Barra Superior -->
            <header class="flex justify-between items-center h-16 px-6 border-b border-gray-200 bg-white">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Panel de Control')</h2>
                <div class="relative">
                    <button id="user-menu-toggle" class="focus:outline-none">
                        <i class="ri-user-line text-2xl text-gray-600"></i>
                    </button>
                    <div id="user-menu"
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden">
                        <ul>
                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Perfil</a>
                            </li>
                            <li><a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Configuraciones</a></li>
                            <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 text-gray-700">Cerrar
                                    sesión</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            @hasSection('apartado')
                <div
                    class="bg-gradient-to-r from-indigo-500 to-orange-500 text-white py-4 px-6 md:px-12 rounded-xl shadow-md mx-4 mt-6 animate-fade-in">
                    <h1 class="text-2xl md:text-3xl font-bold">@yield('apartado')</h1>
                </div>
            @endif

            <div class="flex-1 p-6 overflow-y-auto bg-gray-50">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- JS -->
    <script src="https://unpkg.com/tabulator-tables@5.4.3/dist/js/tabulator.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seccion = new URLSearchParams(window.location.search).get('seccion');
            if (seccion) {
                document.querySelectorAll('.content-section').forEach(el => el.classList.add('hidden'));
                const target = document.getElementById(seccion);
                if (target) target.classList.remove('hidden');
            }
            document.querySelectorAll('a[data-target]').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    const target = link.getAttribute('data-target');
                    document.querySelectorAll('.content-section').forEach(el => el.classList.add(
                        'hidden'));
                    document.getElementById(target).classList.remove('hidden');
                    history.pushState({}, '', `${window.location.pathname}?seccion=${target}`);
                });
            });
            document.getElementById('user-menu-toggle').addEventListener('click', () => {
                document.getElementById('user-menu').classList.toggle('hidden');
            });

            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('w-64');
                sidebar.classList.toggle('w-20');
                document.querySelectorAll('.sidebar-label').forEach(label => label.classList.toggle(
                    'hidden'));
                toggleBtn.querySelector('i').classList.toggle('ri-arrow-left-s-line');
                toggleBtn.querySelector('i').classList.toggle('ri-arrow-right-s-line');
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
