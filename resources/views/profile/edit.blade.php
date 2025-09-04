<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('storage/image/logo.jpg') }}">
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

    <!-- Encabezado -->
    <header class="bg-gray-900 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center space-x-2">
            <a href="{{ asset('/') }}"><i class="text-white ri-arrow-left-line"></i></a>
            <i class="ri-user-settings-line text-xl text-blue-600"></i>
            <h1 class="text-lg font-semibold text-white">Perfil de Usuario</h1>
        </div>
    </header>

    <main class="py-10 ">
        <h1 class="text-xl text-center">Información</h1>
        <div class="grid grid-cols-2 mx-20 px-4 space-x-12 mt-10 mb-8">

            <!-- Sección: Información del Perfil -->
            <section class="bg-white p-5 rounded-lg shadow-md">
                <h1 class="mb-12"><i class="ri-user-3-line"></i> Información de usaurio</h1>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input id="name" name="name" type="text"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input id="email" name="email" type="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-sm transition mt-12">Guardar
                            cambios</button>
                    </div>
                </form>
            </section>

            <!-- Sección: Cambiar Contraseña -->
            <section class="bg-white p-5 rounded-lg shadow-md">
                <h1 class="mb-12"><i class="ri-key-fill"></i> Cambio de contraseña</h1>
                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña
                            actual</label>
                        <input id="current_password" name="current_password" type="password"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                        <input id="password" name="password" type="password"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar
                            nueva contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-400 text-white px-4 py-2 rounded text-sm transition">Actualizar
                            contraseña</button>
                    </div>
                </form>
            </section>

        </div>
    </main>
    <footer class="bg-gray-900 text-white text-center py-6 mt-16">
        <p>&copy; 2025 <a href="#" class="text-orange-500 hover:underline">Network Solutions</a>. Todos los
            derechos reservados.</p>
    </footer>

</body>

</html>
