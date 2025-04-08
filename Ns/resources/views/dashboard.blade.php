@extends('layouts.app')
@section('title', 'Panel de Control')

@section('content')

    {{-- ADMIN: acceso total --}}
    @role('admin')
        <!-- Dashboard -->
        <div id="dashboard" class="content-section">
            Bienvenido al Dashboard
        </div>

        <!-- Tienda -->
        <div id="tienda" class="content-section hidden">
            <h2 class="mb-4">Gestión de Tienda</h2>
            <div class="mb-4 flex space-x-4">
                <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                    data-target="tienda-categorias">Categorías</button>
                <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                    data-target="tienda-productos">Productos</button>
                <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                    data-target="tienda-servicios">Servicios</button>
                <button type="button" class="tienda-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                    data-target="tienda-facturas">Facturas</button>
            </div>

            <div id="tienda-categorias" class="tienda-content hidden">@include('partials.categorias')</div>
            <div id="tienda-productos" class="tienda-content hidden">@include('partials.productos')</div>
            <div id="tienda-servicios" class="tienda-content hidden">@include('partials.servicios')</div>
            <div id="tienda-facturas" class="tienda-content hidden">@include('partials.facturas')</div>
        </div>

        <!-- Usuarios -->
        <div id="usuarios" class="content-section hidden">@include('partials.usuarios')</div>

        <!-- Empresas -->
        <div id="empresas" class="content-section hidden">@include('partials.empresas')</div>

        <!-- Inventario -->
        <div id="inventario" class="content-section hidden">
            @include('partials.inventario', ['empresas' => $empresas])
        </div>

    @elserole('empresarial')
        <!-- SOLO Inventario para empresariales -->
        <div id="inventario" class="content-section">
            @include('partials.inventario', ['empresas' => $empresas])
        </div>

    @else
        {{-- Aquí podés poner contenido para otros roles si querés --}}
        <div class="text-gray-700">
            Bienvenido, tu rol tiene acceso limitado. Consulta con el administrador.
        </div>
    @endrole

@endsection
