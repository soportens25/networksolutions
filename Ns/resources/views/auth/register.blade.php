<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        /* Fondo con imagen */
        body {
            background-image: url('{{ asset('storage/image/banner_registro.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-800 text-gray-800">

    <!-- Contenedor principal -->
    <main class="min-h-screen flex justify-center items-center backdrop-blur-sm bg-black/30">

        <!-- Contenedor del formulario -->
        <div class="relative bg-white border border-gray-300 shadow-lg rounded-lg px-12 py-1 w-[28rem]">
            <h1 class="text-2xl font-bold mb-2 mt-2 text-orange-500">Registro de Usuario</h1>
            <p class="text-gray-600 mb-6">Ingresa tus datos para registrarte.</p>

            <!-- Formulario -->
            <form method="POST" action="{{ route('register') }}" class="space-y-2">
                @csrf

                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-gray-700">Nombre</label>
                    <input type="text" name="name" id="name"
                        class="px-3 py-2 border-2 border-gray-300 rounded-md rounded-md w-full"
                        value="{{ old('name') }}">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="block text-gray-700">Correo Electrónico</label>
                    <input type="email" name="email" id="email"
                        class="px-3 py-2 border-2 border-gray-300 rounded-md rounded-md w-full"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-gray-700">Contraseña</label>
                    <input type="password" name="password" id="password"
                        class="px-3 py-2 border-2 border-gray-300 rounded-md rounded-md w-full">
                    <p class="text-gray-500 text-sm mt-1">
                        La contraseña debe cumplir con lo siguiente:<br>
                        - No puede ser muy similar a su información personal.<br>
                        - Debe contener al menos 8 caracteres.<br>
                        - No puede ser una contraseña de uso común.<br>
                        - No puede ser completamente numérica.
                    </p>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmación de contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-gray-700">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="px-3 py-2 border-2 border-gray-300 rounded-md rounded-md w-full">
                </div>

                <div class="g-recaptcha mb-3" data-sitekey="{{ $recaptchaKey }}"></div>
                @error('g-recaptcha-response')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <!-- Botón de registro -->
                <button type="submit"
                    class="bg-orange-500 text-white px-4 py-2 rounded-md w-full hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400">
                    Registrarse
                </button>
            </form>

            <!-- Enlace para iniciar sesión -->
            <div class="mt-2 mb-2 text-center">
                <p class="text-gray-600">¿Ya tienes una cuenta? <a href="{{ route('login') }}"
                        class="text-orange-500 hover:underline">Inicia sesión</a></p>
            </div>
        </div>
    </main>

</body>

</html>
