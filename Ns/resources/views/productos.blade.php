<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoria->categoria }} - Catálogo de Productos</title>
    <link rel="icon" href="{{ asset('image/logo.jpg') }}">
    <!-- Vincular Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Vincular Remixicon para iconos -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
            color: #343a40;
            font-family: Arial, sans-serif;
        }

        main {
            flex: 1;
        }

        .navbar-custom {
            background-color: #343a40;
        }

        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
            max-width: 18rem; /* Ancho máximo de la tarjeta */
            margin: auto; /* Centrar la tarjeta */
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: auto;
            object-fit: cover;
            margin-top: 1rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
        }

        .price-text {
            color: #28a745;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .stock-text {
            font-size: 1rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
            <div class="container">
                <a href="javascript:history.back()" class="navbar-brand">
                    <i class="ri-arrow-go-back-fill"></i>
                </a>
                <span class="navbar-text mx-auto h5 mb-0">{{ $categoria->categoria }}</span>
                <!-- Menú de usuario -->
                <div>
                    @if (auth()->check())
                        <p class="text-white mb-0">
                            {{ auth()->user()->name }}
                        </p>
                    @else
                        <span class="navbar-text">Invitado</span>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <main class="container py-5">
        <h1 class="text-center mb-5">Productos</h1>

        @if ($productos->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($productos as $producto)
                    <div class="col">
                        <div class="card h-100 product-card border-0 shadow-sm">
                            @if ($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                                    class="card-img-top product-image">
                            @endif
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $producto->producto }}</h5>
                                <p class="price-text">$ {{ number_format($producto->precio, 0, ',', '.') }}</p>
                                <p class="stock-text">Disponibilidad: {{ $producto->stock }} Unidades</p>
                                <p class="card-text">{{ $producto->descripcion }}</p>
                            </div>
                            <div class="card-footer text-center bg-white border-0">
                                @auth
                                    <a href="https://wa.me/573182927165?text=¡Hola!, quisiera consultar más sobre el producto '{{ $producto->producto }}', quedo atento gracias." target="_blank" class="btn btn-primary text-white"    
                                        target="_blank" class="btn btn-primary text-white">
                                        Ir a comprar
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-warning text-white">
                                        Inicia sesión para comprar
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Mensaje si no hay productos -->
            <div class="text-center mt-5">
                <h2>No hay productos disponibles en esta categoría.</h2>
            </div>
        @endif
    </main>

    <!-- Pie de página -->
    <footer class="bg-dark text-white py-3 text-center mt-auto">
        <p class="mb-0">© 2025 Network Solutions. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>
