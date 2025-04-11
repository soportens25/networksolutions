<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>

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
</head>

<body class="bg-gray-100 font-sans">

    <!-- Banner superior -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center space-x-4">
            <i class="ri-user-settings-line text-2xl"></i>
            <h1 class="text-xl font-semibold">Perfil de Usuario</h1>
        </div>
    </div>

    <!-- Contenido del perfil -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Actualizar información -->
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <i class="ri-user-line text-blue-500 text-xl"></i>
                    <span>Información del Perfil</span>
                </h2>
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Actualizar contraseña -->
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <i class="ri-lock-password-line text-yellow-500 text-xl"></i>
                    <span>Actualizar Contraseña</span>
                </h2>
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Eliminar usuario -->
            <div class="p-6 bg-white rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <i class="ri-delete-bin-6-line text-red-500 text-xl"></i>
                    <span>Eliminar Cuenta</span>
                </h2>
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

</body>

</html>
