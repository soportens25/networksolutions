<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Solutions - {{ $servicio->servicio }}</title>
    <link rel="website icon" href="{{ asset('storage/image/logo.jpg') }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            /* Fondo blanco */
            color: #ffffff;
            /* Texto oscuro */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Navbar */
        .navbar {
            background-color: #333333;
            /* Fondo negro */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #ffffff;
            /* Texto blanco */
            text-decoration: none;
            margin: 0 15px;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #ff6600;
            /* Naranja al hacer hover */
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: #ff6600;
            /* Naranja corporativo */
        }

        /* Sección Superior: Imagen y Texto */
        .seccion-superior {
            margin-bottom: 40px;
            text-align: center;
        }

        .seccion-superior .imagen {
            margin-bottom: 20px;
        }

        .seccion-superior .imagen img {
            width: 50%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .seccion-superior .texto {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 10px;
        }

        .seccion-superior h1 {
            font-size: 2.5rem;
            color: #ff6600;
            /* Naranja corporativo */
            margin-bottom: 20px;
        }

        .seccion-superior p {
            font-size: 1rem;
            color: #555555;
            /* Texto gris oscuro */
            margin-bottom: 10px;
            
        }

        .seccion-superior .especificacion {
            font-size: 0.9rem;
            color: #777777;
            /* Texto gris */
            text-align: justify;
            white-space: pre-line;
            /* Respeta saltos de línea */
            padding-left: 20px;
            padding-right: 20px;
            margin-top: 40px;
            /* Sangría */
            line-height: 1.6;
            /* Espaciado entre líneas */
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        /* Sección Sitios Estratégicos */
        .sitios-estrategicos {
            margin-top: 60px;
            text-align: center;
            padding: 40px 20px;
            background-color: #f4f4f4;
            /* Fondo gris claro */
            border-radius: 10px;
        }

        .sitios-estrategicos h2 {
            font-size: 2.5rem;
            color: #333333;
            /* Texto oscuro */
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sitios-estrategicos .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .sitios-estrategicos .card {
            background-color: #ffffff;
            /* Fondo blanco */
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .sitios-estrategicos .card:hover {
            transform: translateY(-10px);
        }

        .sitios-estrategicos .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .sitios-estrategicos .card h3 {
            font-size: 1.2rem;
            color: #ff6600;
            /* Naranja corporativo */
            margin-bottom: 10px;
        }

        .sitios-estrategicos .card p {
            font-size: 0.9rem;
            color: #555555;
            /* Texto gris oscuro */
            white-space: pre-line;
            /* Respeta saltos de línea */
            padding-left: 20px;
            /* Sangría */
            line-height: 1.6;
            /* Espaciado entre líneas */
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background-color: #333333;
            /* Fondo negro */
            color: #ffffff;
            /* Texto blanco */
        }

        footer a {
            color: #ff6600;
            /* Naranja corporativo */
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ asset('/') }}" class="navbar-brand">Inicio</a>
        <div class="text-red-500 text-center">
            @if (auth()->check())
                <p class="text-white mb-0">
                    {{ auth()->user()->name }}
                </p>
            @else
                <span class="navbar-text">Invitado</span>
            @endif
        </div>
    </nav>

    <div class="container">
        <!-- Sección Superior: Imagen y Texto -->
        <div class="seccion-superior">
            <!-- Imagen -->
            <div class="imagen">
                @if ($servicio->imagen)
                    <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen Principal">
                @endif
            </div>

            <!-- Texto -->
            <div class="texto">
                <h1>{{ $servicio->servicio }}</h1>
                <p><strong>{{ $servicio->tipo }}</strong></p>
                @if ($servicio->especificacion)
                    <div class="especificacion">
                        <p>{{ $servicio->especificacion }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sección Sitios Estratégicos -->
        <div class="sitios-estrategicos">
            <h2></h2>
            <div class="grid">
                @if ($servicio->imagen1)
                    <div class="card">
                        <img src="{{ asset('storage/' . $servicio->imagen1) }}" alt="Sitio Estratégico 1">
                        <h3>Personal calificado</h3>
                        <p>Contamos con el mejor personal con el conocimeto y experiencia para brindarle la mejor
                            experiencia a los clientes.</p>
                    </div>
                @endif
                @if ($servicio->imagen2)
                    <div class="card">
                        <img src="{{ asset('storage/' . $servicio->imagen2) }}" alt="Sitio Estratégico 2">
                        <h3>El mejor conocimiento</h3>
                        <p>Contamos con la experinecia para brindar las mejores soluciones a los clientes.</p>
                    </div>
                @endif
                @if ($servicio->imagen3)
                    <a href="{{ asset('/') }}#products" class="card ">
                        <img src="{{ asset('storage/' . $servicio->imagen3) }}" alt="Sitio Estratégico 3">
                        <h3>Productos de alta calidad</h3>
                        <p>Contamos con productos de alta calidad para brindar las mejores soluciones a los clientes.
                        </p>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 <a href="#">Network Solutions</a>. Todos los derechos reservados.</p>
    </footer>

</body>

</html>
