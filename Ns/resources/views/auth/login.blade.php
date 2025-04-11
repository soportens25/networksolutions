<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            background-image: url('{{ asset('storage/image/banner_login.webp') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Añadir un fondo difuminado */
        .bg-overlay {
            backdrop-filter: blur(4px);
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>

<body class="text-gray-800">
    <main class="min-h-screen flex justify-center items-center">
        <!-- Contenedor del formulario -->
        <div class="relative bg-overlay border border-gray-300 shadow-lg rounded-lg px-12 py-10 w-[28rem]">
            <h1 class="text-2xl font-bold mb-4 text-orange-500">Inicia Sesión</h1>
            <p class="text-gray-600 mb-6">Ingresa tus datos para continuar.</p>

            @if (session('status'))
                <div class="text-red-500 text-sm mb-4">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Campo Correo Electrónico -->
                <div>
                    <label for="email" class="block text-gray-700">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="mt-2 w-full px-3 py-2 border-2 border-gray-300 rounded-md bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Contraseña -->
                <div>
                    <label for="password" class="block text-gray-700">Contraseña</label>
                    <input type="password" name="password" id="password" required
                        class="mt-2 w-full px-3 py-2 border-2 border-gray-300 rounded-md bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="g-recaptcha mb-3" data-sitekey="{{ $recaptchaKey }}"></div>
                @error('g-recaptcha-response')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Opciones -->
                <div class="flex justify-between items-center text-sm">
                    <a href="{{ route('register') }}" class="text-orange-500 hover:underline">Crear cuenta</a>
                    <button type="submit"
                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>

</html>
