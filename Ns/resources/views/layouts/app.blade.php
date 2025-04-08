<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Control')</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">

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
    </style>

    @stack('styles')
</head>

<body class="font-sans bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="flex items-center justify-center h-20 border-b border-gray-700">
                <h1 class="text-2xl font-semibold">Mi Aplicación</h1>
            </div>
            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-4">
                    @role('empresarial')
                        <li>
                            <a href="#"
                                class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-2 rounded-md"
                                data-target="inventario">
                                <i class="ri-archive-line text-xl"></i>
                                <span>Inventario</span>
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="#"
                                class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-2 rounded-md"
                                data-target="dashboard">
                                <i class="ri-dashboard-line text-xl"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-2 rounded-md"
                                data-target="tienda">
                                <i class="ri-store-2-line text-xl"></i>
                                <span>Tienda</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-2 rounded-md"
                                data-target="usuarios">
                                <i class="ri-user-3-line text-xl"></i>
                                <span>Usuarios</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-2 rounded-md"
                                data-target="empresas">
                                <i class="ri-building-line text-xl"></i>
                                <span>Empresas</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center space-x-3 text-gray-300 hover:text-white hover:bg-gray-700 p-2 rounded-md"
                                data-target="inventario">
                                <i class="ri-archive-line text-xl"></i>
                                <span>Inventario</span>
                            </a>
                        </li>
                    @endrole
                </ul>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <main class="border shadow-xl rounded-xl m-4 md:m-12 flex-1 flex flex-col overflow-hidden">
            <!-- Barra Superior -->
            <header class="flex items-center justify-between bg-white h-16 px-6 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Panel de Control')</h2>
                </div>
                <div class="relative">
                    <button id="user-menu-toggle" class="flex items-center focus:outline-none">
                        <i class="ri-user-line text-2xl text-gray-600"></i>
                    </button>
                    <div id="user-menu"
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden">
                        <ul>
                            <li>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Perfil</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Configuraciones</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cerrar sesión</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Banner del apartado -->
            @hasSection('apartado')
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-5 px-8 shadow-lg rounded-xl mx-4 md:mx-20 mt-6 mb-6 animate-fade-in">
                    <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
                        @yield('apartado')
                    </h1>
                </div>
            @endif

            <!-- Contenido principal -->
            <div class="flex-1 p-6 bg-gray-50 overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const seccion = urlParams.get('seccion');

            if (seccion) {
                document.querySelectorAll('.content-section').forEach(function(section) {
                    section.classList.add('hidden');
                });

                const targetSection = document.getElementById(seccion);
                if (targetSection) {
                    targetSection.classList.remove('hidden');
                }
            }

            document.querySelectorAll('a[data-target]').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const target = this.getAttribute('data-target');

                    document.querySelectorAll('.content-section').forEach(function(section) {
                        section.classList.add('hidden');
                    });

                    document.getElementById(target).classList.remove('hidden');

                    const newUrl = `${window.location.pathname}?seccion=${target}`;
                    window.history.pushState({}, '', newUrl);
                });
            });

            const userToggle = document.getElementById('user-menu-toggle');
            const userMenu = document.getElementById('user-menu');

            userToggle.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>

</html>
