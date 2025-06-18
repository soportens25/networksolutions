    @extends('layouts.app')

    @section('title', 'Panel de Control')

    @push('styles')
    <!-- Estilos específicos para el dashboard -->
    @endpush

    @section('content')

    @role('admin')

    <div id="dashboard" class="content-section">
        @include('partials.dash', ['dataForCharts' => $dataForCharts])
    </div>

    <!-- Sección Tienda con navegación interna -->
    <div id="tienda" class="content-section hidden">
        <h2 class="mb-4">Gestión de Tienda</h2>
        <!-- Navegación interna de la Tienda -->
        <div class="mb-4 flex space-x-4">
            <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" data-target="tienda-categorias">
                Categorías
            </button>
            <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" data-target="tienda-productos">
                Productos
            </button>
            <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" data-target="tienda-servicios">
                Servicios
            </button>
            <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" data-target="tienda-facturas">
                Facturas
            </button>
        </div>
        <!-- Contenidos internos de la Tienda -->

        <div id="tienda-categorias" class="tienda-content hidden">
            @include('partials.categorias')
        </div>
        <div id="tienda-productos" class="tienda-content hidden">
            @include('partials.productos')
        </div>
        <div id="tienda-servicios" class="tienda-content hidden">
            @include('partials.servicios')
        </div>
        <div id="tienda-facturas" class="tienda-content hidden">
            @include('partials.facturas')
        </div>
    </div>
    <div id="usuarios" class="content-section hidden">
        @include('partials.usuarios')
    </div>
    <!-- Sección Empresas -->
    <div id="empresas" class="content-section hidden">
        <h2>Empresas</h2>
        @include('partials.empresas')
    </div>
    @endrole
    <!-- Sección Inventario -->
    <div id="inventario" class="content-section hidden">
        @include('partials.inventario', ['empresas' => $empresas])
    </div>
    <div id="docs" class="content-section hidden">
        @include('partials.docs')
    </div>
        <div id="calendario" class="content-section hidden">
        @include('partials.calendar')
    </div>



    @endsection

    @push('scripts')
    <script>
        // Mostrar por defecto la sección del Dashboard al cargar la vista
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.content-section').forEach(function(section) {
                if (section.id !== 'dashboard') {
                    section.classList.add('hidden');
                }
            });

            // Navegación interna de la Tienda
            const tiendaNavButtons = document.querySelectorAll('.tienda-btn');
            tiendaNavButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    // Ocultar todos los contenidos internos de Tienda
                    document.querySelectorAll('.tienda-content').forEach(function(content) {
                        content.classList.add('hidden');
                    });
                    // Mostrar el contenido seleccionado
                    document.getElementById(target).classList.remove('hidden');
                });
            });
        });
    </script>
    @endpush